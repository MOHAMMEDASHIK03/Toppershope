<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\PaginatesListings;
use App\Models\HR\Employee;
use App\Models\HR\Leave;
use App\Models\HR\LeaveType;
use App\Rules\LeaveDurationWithinTypeLimit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    use PaginatesListings;

    public function index(Request $request)
    {
        $leaves = $this->paginateListing(
            Leave::with(['employee.department', 'leaveType', 'approver'])->latest(),
            $request
        );
            
        return view('hr.leaves.index', compact('leaves'));
    }

    public function create()
    {
        $employees = Employee::where('is_active', true)->orderBy('first_name')->get();
        $leaveTypes = LeaveType::where('is_active', true)->get();
        
        return view('hr.leaves.create', compact('employees', 'leaveTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => ['required', 'date', 'after_or_equal:start_date', new LeaveDurationWithinTypeLimit],
            'reason' => 'required|string',
        ]);

        Leave::create([
            'employee_id' => $request->employee_id,
            'leave_type_id' => $request->leave_type_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'status' => 'pending'
        ]);

        return redirect()->route('hr.leaves.index')->with('success', 'Leave request submitted successfully.');
    }

    public function edit(Leave $leaf)
    {
        $leaf->load(['employee.department', 'leaveType', 'approver']);

        $employees = Employee::where('is_active', true)->orderBy('first_name')->get();
        $leaveTypes = LeaveType::where('is_active', true)->get();

        return view('hr.leaves.edit', [
            'leave' => $leaf,
            'employees' => $employees,
            'leaveTypes' => $leaveTypes,
        ]);
    }

    public function update(Request $request, Leave $leaf)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'admin_remarks' => 'nullable|string'
        ]);

        if ($request->status != 'pending' && $leaf->status == 'pending') {
            $leaf->approved_by = Auth::guard('hr')->id();
        }

        $leaf->update([
            'status' => $request->status,
            'admin_remarks' => $request->admin_remarks
        ]);

        return redirect()->route('hr.leaves.index')->with('success', 'Leave request updated successfully.');
    }

    public function destroy(Leave $leaf)
    {
        $leaf->delete();
        return redirect()->route('hr.leaves.index')->with('success', 'Leave request deleted successfully.');
    }
}
