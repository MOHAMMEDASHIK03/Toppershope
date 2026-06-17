<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\PaginatesListings;
use Illuminate\Http\Request;
use App\Models\HR\Employee;
use App\Models\HR\Attendance;
use Illuminate\Support\Carbon;

class AttendanceController extends Controller
{
    use PaginatesListings;

    public function index(Request $request)
    {
        $date = $request->get('date', date('Y-m-d'));
        
        $employees = $this->paginateListing(
            Employee::with(['department', 'designation'])->where('is_active', true)->orderBy('first_name'),
            $request
        );
            
        $attendances = Attendance::where('date', $date)->get()->keyBy('employee_id');

        return view('hr.attendance.index', compact('employees', 'attendances', 'date'));
    }

    public function mark(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,half_day,late,on_leave',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i',
            'notes' => 'nullable|string|max:500'
        ]);

        $status = $request->status;
        $notes = $request->notes;

        // Check if an attendance record already exists for the employee on this date
        $attendance = Attendance::where('employee_id', $request->employee_id)
            ->where('date', $request->date)
            ->first();

        if (in_array($status, ['absent', 'on_leave'])) {
            $checkIn = null;
            $checkOut = null;
        } else {
            // If the time fields are not submitted (hidden/disabled in Check-In or Check-Out Only mode),
            // preserve the existing values from the database to avoid overwriting them to null.
            $checkIn = $request->has('check_in') ? $request->check_in : ($attendance ? $attendance->check_in : null);
            $checkOut = $request->has('check_out') ? $request->check_out : ($attendance ? $attendance->check_out : null);
        }

        Attendance::updateOrCreate(
            [
                'employee_id' => $request->employee_id,
                'date' => $request->date,
            ],
            [
                'status' => $status,
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'notes' => $notes,
            ]
        );

        return back()->with('success', 'Attendance marked successfully.');
    }

    public function report(Request $request, Employee $employee)
    {
        $month = $request->get('month', date('Y-m'));
        
        $attendances = Attendance::where('employee_id', $employee->id)
            ->where('date', 'like', $month . '%')
            ->orderBy('date', 'desc')
            ->get();
            
        // Handle CSV Export
        if ($request->has('export') && $request->export === 'csv') {
            $filename = "attendance_report_{$employee->employee_id}_{$month}.csv";
            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$filename",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];
            
            $columns = ['Date', 'Status', 'Check In', 'Check Out', 'Notes'];
            
            $callback = function() use ($attendances, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($attendances as $record) {
                    fputcsv($file, [
                        $record->date->format('Y-m-d'),
                        ucfirst(str_replace('_', ' ', $record->status)),
                        $record->check_in,
                        $record->check_out,
                        $record->notes
                    ]);
                }
                fclose($file);
            };
            
            return response()->stream($callback, 200, $headers);
        }

        return view('hr.attendance.report', compact('employee', 'attendances', 'month'));
    }
}
