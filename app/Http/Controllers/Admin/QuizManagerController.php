<?php

namespace App\Http\Controllers\Admin;

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
    /** Display the quiz dashboard for a course. */
    public function index(Course $course)
    {
        $course->load(['subjects.chapters.units.quizzes']);
        return view('admin.academic.quizzes.index', compact('course'));
    }

    /** Store a new Quiz. */
    public function storeQuiz(Request $request, Course $course, Unit $unit)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $unit->quizzes()->create([
            'title'        => $request->title,
            'description'  => $request->description,
            'is_published' => false,
        ]);

        return redirect()->back()->with('success', 'Quiz created. Click "Build & Edit" to add questions.');
    }

    /** Delete a quiz. */
    public function destroyQuiz(Course $course, Quiz $quiz)
    {
        $quiz->delete();
        return redirect()->back()->with('success', 'Quiz deleted.');
    }

    /** Open the Quiz Builder UI. */
    public function builder(Course $course, Quiz $quiz)
    {
        $quiz->load(['sections.questions.options']);
        return view('admin.academic.quizzes.builder', compact('course', 'quiz'));
    }

    /** Add a Section to a Quiz. */
    public function storeSection(Request $request, Course $course, Quiz $quiz)
    {
        $request->validate([
            'name'                        => 'required|string|max:255',
            'time_limit_minutes'          => 'nullable|numeric|min:1',
            'marks_per_question'          => 'required|numeric|min:0',
            'negative_marks_per_question' => 'required|numeric|min:0',
        ]);

        $quiz->sections()->create($request->only([
            'name', 'time_limit_minutes', 'marks_per_question', 'negative_marks_per_question'
        ]));

        return redirect()->back()->with('success', 'Section added.');
    }

    /** Delete a section. */
    public function destroySection(Course $course, QuizSection $section)
    {
        $section->delete();
        return redirect()->back()->with('success', 'Section deleted.');
    }

    /** Store a Question with MCQ options. */
    public function storeQuestion(Request $request, Course $course, QuizSection $section)
    {
        $request->validate([
            'question_text'   => 'nullable|string',
            'question_image'  => 'nullable|image|max:2048',
            'options'         => 'required|array|min:2',
            'options.*.text'  => 'nullable|string',
            'options.*.image' => 'nullable|image|max:2048',
            'correct_option'  => 'required|integer',
        ]);

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
                'question_text'      => $request->question_text,
                'question_image_path' => $questionImagePath,
                'order'              => $section->questions()->count() + 1,
            ]);

            foreach ($request->options as $index => $optionData) {
                $optionImagePath = null;
                if (isset($optionData['image'])) {
                    $optionImagePath = $optionData['image']->store('quiz_images', 'public');
                }
                $question->options()->create([
                    'option_text'       => $optionData['text'] ?? null,
                    'option_image_path' => $optionImagePath,
                    'is_correct'        => ((int)$request->correct_option === $index),
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Question added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Failed: ' . $e->getMessage()]);
        }
    }

    /** Delete a question. */
    public function destroyQuestion(Course $course, Question $question)
    {
        if ($question->question_image_path) Storage::disk('public')->delete($question->question_image_path);
        foreach ($question->options as $opt) {
            if ($opt->option_image_path) Storage::disk('public')->delete($opt->option_image_path);
        }
        $question->delete();
        return redirect()->back()->with('success', 'Question deleted.');
    }

    /** Toggle quiz published status. */
    public function publishQuiz(Course $course, Quiz $quiz)
    {
        $quiz->update(['is_published' => !$quiz->is_published]);
        $status = $quiz->is_published ? 'published' : 'reverted to draft';
        return redirect()->back()->with('success', "Quiz {$status}.");
    }

    /** Bulk import via ZIP (CSV + images). */
    public function importQuiz(Request $request, Course $course, Quiz $quiz)
    {
        $request->validate(['quiz_zip' => 'required|mimes:zip|max:51200']);

        $zipFile     = $request->file('quiz_zip');
        $extractPath = storage_path('app/temp_zip/' . uniqid());

        $zip = new \ZipArchive;
        if ($zip->open($zipFile->path()) !== TRUE) {
            return redirect()->back()->withErrors(['quiz_zip' => 'Failed to open ZIP file.']);
        }
        $zip->extractTo($extractPath);
        $zip->close();

        $csvPath = $extractPath . '/quiz.csv';
        if (!file_exists($csvPath)) {
            \Illuminate\Support\Facades\File::deleteDirectory($extractPath);
            return redirect()->back()->withErrors(['quiz_zip' => 'Missing quiz.csv inside the ZIP.']);
        }

        DB::beginTransaction();
        try {
            $file   = fopen($csvPath, 'r');
            $header = fgetcsv($file);
            while (($data = fgetcsv($file)) !== FALSE) {
                $sectionName = trim($data[0] ?? '');
                if (empty($sectionName)) continue;

                $section = $quiz->sections()->firstOrCreate(
                    ['name' => $sectionName],
                    ['time_limit_minutes' => empty($data[1]) ? null : (int)$data[1],
                     'marks_per_question' => empty($data[2]) ? 1 : (float)$data[2],
                     'negative_marks_per_question' => empty($data[3]) ? 0 : (float)$data[3]]
                );

                $questionImagePath = null;
                $qImgFilename = trim($data[5] ?? '');
                if (!empty($qImgFilename) && file_exists($extractPath . '/' . $qImgFilename)) {
                    $newPath = 'quiz_images/' . uniqid() . '_' . basename($qImgFilename);
                    Storage::disk('public')->put($newPath, file_get_contents($extractPath . '/' . $qImgFilename));
                    $questionImagePath = $newPath;
                }

                $questionText = trim($data[4] ?? '');
                if (empty($questionText) && empty($questionImagePath)) continue;

                $question = $section->questions()->create([
                    'question_text'       => $questionText,
                    'question_image_path' => $questionImagePath,
                    'order'               => $section->questions()->count() + 1,
                ]);

                $optionsMap   = ['A' => ['text' => 6, 'img' => 7], 'B' => ['text' => 8, 'img' => 9],
                                 'C' => ['text' => 10, 'img' => 11], 'D' => ['text' => 12, 'img' => 13]];
                $correctLetter = strtoupper(trim($data[14] ?? 'A'));

                foreach ($optionsMap as $letter => $cols) {
                    $optText = trim($data[$cols['text']] ?? '');
                    $optImgFile = trim($data[$cols['img']] ?? '');
                    $optImagePath = null;
                    if (!empty($optImgFile) && file_exists($extractPath . '/' . $optImgFile)) {
                        $newPath = 'quiz_images/' . uniqid() . '_' . basename($optImgFile);
                        Storage::disk('public')->put($newPath, file_get_contents($extractPath . '/' . $optImgFile));
                        $optImagePath = $newPath;
                    }
                    if (!empty($optText) || !empty($optImagePath)) {
                        $question->options()->create([
                            'option_text'       => $optText,
                            'option_image_path' => $optImagePath,
                            'is_correct'        => ($letter === $correctLetter),
                        ]);
                    }
                }
            }
            fclose($file);
            DB::commit();
            \Illuminate\Support\Facades\File::deleteDirectory($extractPath);
            return redirect()->back()->with('success', 'Bulk ZIP import completed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\File::deleteDirectory($extractPath);
            return redirect()->back()->withErrors(['quiz_zip' => 'Import failed: ' . $e->getMessage()]);
        }
    }
}
