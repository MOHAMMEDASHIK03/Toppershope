<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Doubt;
use App\Models\DoubtReply;

class DoubtController extends Controller
{
    public function index()
    {
        $doubts = Doubt::where('user_id', Auth::id())
            ->withCount('replies')
            ->latest()
            ->get();

        return view('student.doubts.index', compact('doubts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        Doubt::create([
            'user_id' => Auth::id(),
            'subject' => $request->title,
            'description' => $request->body,
            'is_resolved' => false,
        ]);

        return back()->with('success', 'Doubt posted successfully! Faculty will respond shortly.');
    }

    public function show($doubtId)
    {
        $doubt = Doubt::with(['replies.user'])
            ->where('user_id', Auth::id())
            ->findOrFail($doubtId);

        return view('student.doubts.show', compact('doubt'));
    }

    public function reply(Request $request, $doubtId)
    {
        $request->validate(['reply_text' => 'required|string']);

        $doubt = Doubt::where('user_id', Auth::id())->findOrFail($doubtId);

        DoubtReply::create([
            'doubt_id' => $doubt->id,
            'user_id' => Auth::id(),
            'reply_text' => $request->reply_text,
        ]);

        return back()->with('success', 'Reply added.');
    }
}
