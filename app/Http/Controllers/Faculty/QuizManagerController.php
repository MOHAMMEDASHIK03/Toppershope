<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Unit;
use App\Models\Quiz;
use App\Models\QuizSection;
use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class QuizManagerController extends Controller
{
    private function checkAccess(Course $course)
    {
        // faculty_head has global access — regular faculty must be assigned to the course
        if (!auth()->user()->isFacultyHead() && !auth()->user()->courses->contains($course->id)) {
            abort(403, 'Unauthorized to manage this course.');
        }
    }

    /**
     * Display the quiz dashboard for the course.
     */
    public function index(Course $course)
    {
        $this->checkAccess($course);
        $course->load(['subjects.chapters.units.quizzes']);
        return view('faculty.courses.quizzes_index', compact('course'));
    }

    /**
     * Store a new Quiz Header.
     */
    public function storeQuiz(Request $request, Course $course, Unit $unit)
    {
        $this->checkAccess($course);
        
        if ($unit->chapter->subject->course_id !== $course->id) {
            abort(403, 'Unit does not belong to this course.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $unit->quizzes()->create([
            'title' => $request->title,
            'description' => $request->description,
            'is_published' => false,
        ]);

        return redirect()->back()->with('success', 'Quiz created successfully. Click "Manage" to build it.');
    }

    public function destroyQuiz(Course $course, Quiz $quiz)
    {
        $this->checkAccess($course);
        if ($quiz->unit->chapter->subject->course_id !== $course->id) {
            abort(403, 'Quiz does not belong to this course.');
        }

        // Questions and Option images should be cleaned up ideally, but DB cascade handles rows.
        // For simplicity, we assume DB cascade is enough for now. Advanced physical file deletion can be added later if needed.
        $quiz->delete();
        return redirect()->back()->with('success', 'Quiz deleted.');
    }

    /**
     * Open the Builder UI.
     */
    public function builder(Course $course, Quiz $quiz)
    {
        $this->checkAccess($course);
        if ($quiz->unit->chapter->subject->course_id !== $course->id) {
            abort(403, 'Quiz does not belong to this course.');
        }

        $quiz->load(['sections.questions.options']);
        return view('faculty.courses.quiz_builder', compact('course', 'quiz'));
    }

    /**
     * Store or update a Section inline.
     */
    public function storeSection(Request $request, Course $course, Quiz $quiz)
    {
        $this->checkAccess($course);
        if ($quiz->unit->chapter->subject->course_id !== $course->id) abort(403);

        $request->validate([
            'name' => 'required|string|max:255',
            'time_limit_minutes' => 'nullable|numeric|min:1',
            'marks_per_question' => 'required|numeric|min:0',
            'negative_marks_per_question' => 'required|numeric|min:0',
            'order' => 'nullable|integer',
        ]);

        $quiz->sections()->create($request->only([
            'name', 'time_limit_minutes', 'marks_per_question', 'negative_marks_per_question', 'order'
        ]));

        return redirect()->back()->with('success', 'Section added successfully.');
    }

    public function destroySection(Course $course, QuizSection $section)
    {
        $this->checkAccess($course);
        if ($section->quiz->unit->chapter->subject->course_id !== $course->id) abort(403);
        $section->delete();
        return redirect()->back()->with('success', 'Section deleted.');
    }

    /**
     * Advanced: Store a Question with its options (handling images)
     */
    public function storeQuestion(Request $request, Course $course, QuizSection $section)
    {
        $this->checkAccess($course);
        if ($section->quiz->unit->chapter->subject->course_id !== $course->id) abort(403);

        $request->validate([
            'question_text' => 'nullable|string',
            'question_image' => 'nullable|image|max:2048', // 2MB max
            'options' => 'required|array|min:2',
            'options.*.text' => 'nullable|string',
            'options.*.image' => 'nullable|image|max:2048',
            'correct_option' => 'required|integer', // Index of the correct option
        ]);

        // Need either text or image for question
        if (!$request->question_text && !$request->hasFile('question_image')) {
            return redirect()->back()->withErrors(['question' => 'A question must have either text or an image.']);
        }

        DB::beginTransaction();

        try {
            $questionImagePath = null;
            if ($request->hasFile('question_image')) {
                $questionImagePath = $request->file('question_image')->store('quiz_images', 'public');
            }

            $question = $section->questions()->create([
                'question_text' => $request->question_text,
                'question_image_path' => $questionImagePath,
                'order' => $section->questions()->count() + 1,
            ]);

            foreach ($request->options as $index => $optionData) {
                // Ensure option has either text or image
                if (empty($optionData['text']) && !isset($optionData['image'])) {
                     throw new \Exception('An option must have either text or an image.');
                }

                $optionImagePath = null;
                if (isset($optionData['image'])) {
                    $optionImagePath = $optionData['image']->store('quiz_images', 'public');
                }

                $question->options()->create([
                    'option_text' => $optionData['text'] ?? null,
                    'option_image_path' => $optionImagePath,
                    'is_correct' => ((int)$request->correct_option === $index),
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Question added successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Failed to save question: ' . $e->getMessage()]);
        }
    }

    public function publishQuiz(Course $course, Quiz $quiz)
    {
        $this->checkAccess($course);
        if ($quiz->unit->chapter->subject->course_id !== $course->id) abort(403);

        $quiz->update([
            'is_published' => !$quiz->is_published
        ]);

        $status = $quiz->is_published ? 'published' : 'reverted to draft';
        return redirect()->back()->with('success', "Quiz {$status} successfully.");
    }

    public function destroyQuestion(Course $course, Question $question)
    {
        $this->checkAccess($course);
        if ($question->section->quiz->unit->chapter->subject->course_id !== $course->id) abort(403);
        
        // Clean up images
        if ($question->question_image_path) Storage::disk('public')->delete($question->question_image_path);
        foreach ($question->options as $opt) {
            if ($opt->option_image_path) Storage::disk('public')->delete($opt->option_image_path);
        }

        $question->delete();
        return redirect()->back()->with('success', 'Question deleted.');
    }

    /**
     * Advanced: Bulk Import via ZIP Archive (CSV + Images)
     */
    public function importQuiz(Request $request, Course $course, Quiz $quiz)
    {
        $this->checkAccess($course);
        if ($quiz->unit->chapter->subject->course_id !== $course->id) abort(403);

        $request->validate([
            'quiz_zip' => 'required|mimes:zip|max:51200', // 50MB max
        ]);

        $zipFile = $request->file('quiz_zip');
        $extractPath = storage_path('app/temp_zip/' . uniqid());
        
        $zip = new \ZipArchive;
        if ($zip->open($zipFile->path()) === TRUE) {
            $zip->extractTo($extractPath);
            $zip->close();
        } else {
            return redirect()->back()->withErrors(['quiz_zip' => 'Failed to open the ZIP file.']);
        }

        // Look for quiz.csv
        $csvPath = $extractPath . '/quiz.csv';
        if (!file_exists($csvPath)) {
            \Illuminate\Support\Facades\File::deleteDirectory($extractPath);
            return redirect()->back()->withErrors(['quiz_zip' => 'Missing quiz.csv in the root of the ZIP file.']);
        }

        DB::beginTransaction();
        try {
            $file = fopen($csvPath, "r");
            $header = fgetcsv($file); // Skip header

            while (($data = fgetcsv($file)) !== FALSE) {
                // Expected CSV format mapping:
                $sectionName = trim($data[0] ?? '');
                if (empty($sectionName)) continue;

                // 1. Find or Create Section
                $section = $quiz->sections()->firstOrCreate(
                    ['name' => $sectionName],
                    [
                        'time_limit_minutes' => empty($data[1]) ? null : (int)$data[1],
                        'marks_per_question' => empty($data[2]) ? 1 : (float)$data[2],
                        'negative_marks_per_question' => empty($data[3]) ? 0 : (float)$data[3],
                    ]
                );

                // 2. Process Question Image from Zip
                $questionImagePath = null;
                $qImageFilename = trim($data[5] ?? '');
                if (!empty($qImageFilename) && file_exists($extractPath . '/' . $qImageFilename)) {
                    $newPath = 'quiz_images/' . uniqid() . '_' . basename($qImageFilename);
                    Storage::disk('public')->put($newPath, file_get_contents($extractPath . '/' . $qImageFilename));
                    $questionImagePath = $newPath;
                }

                // 3. Create Question Row
                $questionText = trim($data[4] ?? '');
                if (empty($questionText) && empty($questionImagePath)) continue; // skip entirely empty rows
                
                $question = $section->questions()->create([
                    'question_text' => $questionText,
                    'question_image_path' => $questionImagePath,
                    'order' => $section->questions()->count() + 1,
                ]);

                // 4. Map Options logic
                $optionsMap = [
                    'A' => ['text' => 6, 'img' => 7],
                    'B' => ['text' => 8, 'img' => 9],
                    'C' => ['text' => 10, 'img' => 11],
                    'D' => ['text' => 12, 'img' => 13],
                ];

                $correctLetter = strtoupper(trim($data[14] ?? 'A'));

                foreach ($optionsMap as $letter => $cols) {
                    $optText = trim($data[$cols['text']] ?? '');
                    $optImgFilename = trim($data[$cols['img']] ?? '');
                    $optImagePath = null;
                    
                    if (!empty($optImgFilename) && file_exists($extractPath . '/' . $optImgFilename)) {
                        $newPath = 'quiz_images/' . uniqid() . '_' . basename($optImgFilename);
                        Storage::disk('public')->put($newPath, file_get_contents($extractPath . '/' . $optImgFilename));
                        $optImagePath = $newPath;
                    }

                    if (!empty($optText) || !empty($optImagePath)) {
                        $question->options()->create([
                            'option_text' => $optText,
                            'option_image_path' => $optImagePath,
                            'is_correct' => ($letter === $correctLetter),
                        ]);
                    }
                }
            }
            fclose($file);
            DB::commit();

            \Illuminate\Support\Facades\File::deleteDirectory($extractPath);
            return redirect()->back()->with('success', 'Bulk ZIP Import Parsing Successful!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\File::deleteDirectory($extractPath);
            return redirect()->back()->withErrors(['quiz_zip' => 'Import failed: ' . $e->getMessage()]);
        }
    }
}
