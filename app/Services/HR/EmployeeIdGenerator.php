<?php

namespace App\Services\HR;

use App\Models\Branch;
use App\Models\HR\Employee;

class EmployeeIdGenerator
{
    public const COMPANY_CODE = 'TH';

    public const HEAD_OFFICE_SEGMENT = 'HO';

    /** First head-office employee: TH-HO-1111 */
    public const HO_SERIAL_START = 1111;

    /** First branch employee: TH-{branch}-01111 */
    public const BRANCH_SERIAL_START = 1111;

    public const BRANCH_SERIAL_WIDTH = 5;

    /**
     * Generate the next employee ID for a branch.
     *
     * @param  int  $branchId  Assigned branch.
     * @param  int  $runOffset  Extra increment when creating multiple IDs in one request.
     */
    public function next(int $branchId, int $runOffset = 0): string
    {
        [$prefix, $padWidth] = $this->prefixAndPadWidth($branchId);

        $serial = $this->nextSerialForPrefix($prefix, $padWidth, $runOffset);

        if ($padWidth > 0) {
            return $prefix . str_pad((string) $serial, $padWidth, '0', STR_PAD_LEFT);
        }

        return $prefix . $serial;
    }

    /**
     * @return array{0: string, 1: int} [prefix, padWidth — 0 means no leading zeros]
     */
    protected function prefixAndPadWidth(int $branchId): array
    {
        $branch = Branch::query()->findOrFail($branchId);
        if (filled($branch->code)) {
            $code = strtoupper(trim($branch->code));

            if ($code === self::HEAD_OFFICE_SEGMENT) {
                return [self::COMPANY_CODE . '-' . self::HEAD_OFFICE_SEGMENT . '-', 0];
            }

            return [self::COMPANY_CODE . '-' . $code . '-', self::BRANCH_SERIAL_WIDTH];
        }

        throw new \InvalidArgumentException('Branch code is required for employee ID generation.');
    }

    protected function nextSerialForPrefix(string $prefix, int $padWidth, int $runOffset): int
    {
        $start = $padWidth > 0 ? self::BRANCH_SERIAL_START : self::HO_SERIAL_START;

        $max = Employee::query()
            ->where('employee_id', 'like', $prefix . '%')
            ->pluck('employee_id')
            ->map(fn (string $id) => $this->serialFromEmployeeId($id, $prefix))
            ->filter()
            ->max();

        $next = ($max !== null && $max >= $start) ? $max + 1 : $start;

        return $next + $runOffset;
    }

    protected function serialFromEmployeeId(string $employeeId, string $prefix): ?int
    {
        if (! str_starts_with($employeeId, $prefix)) {
            return null;
        }

        $suffix = substr($employeeId, strlen($prefix));

        if ($suffix === '' || ! ctype_digit($suffix)) {
            return null;
        }

        return (int) $suffix;
    }
}
