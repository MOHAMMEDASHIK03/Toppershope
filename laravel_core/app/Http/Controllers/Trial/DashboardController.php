<?php

namespace App\Http\Controllers\Trial;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $trial = Auth::guard('trial')->user();
        $batch = $trial->batch()->with('course')->first();

        // Get subjects for this batch's course, each with only the FIRST chapter
        $subjects = Subject::where('course_id', $batch->course_id)
            ->with(['chapters' => fn($q) => $q->orderBy('order')->limit(1)])
            ->orderBy('order')
            ->get();

        $allowedChapterIds = $trial->allowedChapterIds();

        return view('trial.dashboard', compact('trial', 'batch', 'subjects', 'allowedChapterIds'));
    }
}
