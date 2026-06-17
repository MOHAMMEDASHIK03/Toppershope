<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\PaginatesListings;
use Illuminate\Http\Request;
use App\Models\HR\Announcement;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    use PaginatesListings;

    public function index(Request $request)
    {
        $announcements = $this->paginateListing(
            Announcement::with(['author', 'department'])->latest(),
            $request
        );
        return view('hr.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('hr.announcements.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'department_id' => 'nullable|exists:departments,id',
            'is_active' => 'nullable|in:0,1',
        ]);

        Announcement::create([
            'title' => $request->title,
            'content' => $request->content,
            'department_id' => $request->department_id ?: null,
            'created_by' => Auth::guard('hr')->id(),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('hr.announcements.index')->with('success', 'Announcement published.');
    }

    public function edit(Announcement $announcement)
    {
        return view('hr.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'department_id' => 'nullable|exists:departments,id',
            'is_active' => 'nullable|in:0,1',
        ]);

        $announcement->update([
            'title' => $request->title,
            'content' => $request->content,
            'department_id' => $request->department_id ?: null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('hr.announcements.index')->with('success', 'Announcement updated.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return redirect()->route('hr.announcements.index')->with('success', 'Announcement deleted.');
    }
}
