<?php

namespace App\Http\Controllers\Trial;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use Illuminate\Support\Facades\Auth;

class ContentController extends Controller
{
    /**
     * Reuses the student partials to render the chapter contents.
     * We just need to verify they have access to this specific chapter.
     */
    public function showChapter(Chapter $chapter)
    {
        $trial = Auth::guard('trial')->user();

        // Security Check: Is this chapter ID in their allowed array?
        if (!in_array($chapter->id, $trial->allowedChapterIds())) {
            abort(403, 'Trial limits access to Chapter 1 of each subject only, or this subject is not in your batch.');
        }

        $chapter->load([
            'subject',
            'units' => fn($q) => $q->orderBy('order'),
            'units.videos' => fn($q) => $q->orderBy('order'),
            'units.notes' => fn($q) => $q->orderBy('order'),
            'units.quizzes' => fn($q) => $q->orderBy('order'),
        ]);

        return view('trial.content.chapter', compact('trial', 'chapter'));
    }
}
