<?php

namespace App\Rules;

use App\Models\HR\LeaveType;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class LeaveDurationWithinTypeLimit implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $startDate = request()->input('start_date');
        $leaveTypeId = request()->input('leave_type_id');

        if (!$startDate || !$value || !$leaveTypeId) {
            return;
        }

        $leaveType = LeaveType::query()->find($leaveTypeId);
        if (!$leaveType) {
            return;
        }

        try {
            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($value)->startOfDay();
        } catch (\Throwable) {
            return;
        }

        if ($end->lt($start)) {
            return;
        }

        $requestedDays = (int) $start->diffInDays($end) + 1;
        $allowedDays = (int) $leaveType->days_allowed;

        if ($requestedDays > $allowedDays) {
            $fail($this->messageFor($allowedDays));
        }
    }

    protected function messageFor(int $allowedDays): string
    {
        if ($allowedDays === 1) {
            return 'Only 1 day is allowed for this leave type.';
        }

        return "Only {$allowedDays} days are allowed for this leave type.";
    }
}
