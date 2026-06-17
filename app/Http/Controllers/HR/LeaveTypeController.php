<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\PaginatesListings;
use Illuminate\Http\Request;
use App\Models\HR\LeaveType;

class LeaveTypeController extends Controller
{
    use PaginatesListings;

    public function index(Request $request)
    {
        $leaveTypes = $this->paginateListing(LeaveType::latest(), $request);
        return view('hr.leave_types.index', compact('leaveTypes'));
    }

    public function create()
    {
        return view('hr.leave_types.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'days_allowed' => 'required|integer|min:0',
            'is_active' => 'boolean'
        ]);
        
        $data['is_active'] = $request->boolean('is_active', true);
        LeaveType::create($data);

        return redirect()->route('hr.leave-types.index')->with('success', 'Leave Type created successfully.');
    }

    public function edit(LeaveType $leaveType)
    {
        return view('hr.leave_types.edit', compact('leaveType'));
    }

    public function update(Request $request, LeaveType $leaveType)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'days_allowed' => 'required|integer|min:0',
            'is_active' => 'boolean'
        ]);
        
        $data['is_active'] = $request->boolean('is_active', false);
        $leaveType->update($data);

        return redirect()->route('hr.leave-types.index')->with('success', 'Leave Type updated successfully.');
    }

    public function destroy(LeaveType $leaveType)
    {
        if ($leaveType->leaves()->count() > 0) {
            return back()->with('error', 'Cannot delete leave type because it has associated leave requests.');
        }
        
        $leaveType->delete();
        return redirect()->route('hr.leave-types.index')->with('success', 'Leave Type deleted successfully.');
    }
}
