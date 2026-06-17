<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\HR\Employee;
use App\Models\HR\HrUser;
use App\Models\User;
use App\Models\Ads\AdsUser;
use App\Models\Admission\AdmissionUser;
use App\Services\HR\EmployeeIdGenerator;

class SyncPanelUsersToEmployees extends Command
{
    protected $signature   = 'hr:sync-employees';
    protected $description = 'Import all existing panel users (HR, Faculty, Ads, Admission) as HR Employee records.';

    public function handle()
    {
        $idGenerator = app(EmployeeIdGenerator::class);
        $created = 0;
        $skipped = 0;

        $sources = [
            [HrUser::where('is_active', true)->get(),      HrUser::class,         'HR'],
            [User::whereIn('role', ['faculty','admin','faculty_head'])->get(), User::class, 'Faculty/Admin'],
            [AdsUser::where('is_active', true)->get(),     AdsUser::class,        'Ads'],
            [AdmissionUser::where('is_active', true)->get(), AdmissionUser::class, 'Admission'],
        ];

        $defaultBranch = Branch::first();
        if (!$defaultBranch) {
            $this->error("No branch found. Please create a branch in the system first.");
            return 1;
        }

        foreach ($sources as [$users, $modelClass, $label]) {
            foreach ($users as $user) {
                if (Employee::where('email', $user->email)->exists()) {
                    $this->line("  <comment>SKIP</comment> {$user->email}");
                    $skipped++;
                    continue;
                }
                $name = explode(' ', $user->name, 2);
                $empId = $idGenerator->next($defaultBranch->id, $created);

                Employee::create([
                    'employee_id'  => $empId,
                    'first_name'   => $name[0],
                    'last_name'    => $name[1] ?? '',
                    'email'        => $user->email,
                    'is_active'    => true,
                    'account_type' => $modelClass,
                    'account_id'   => $user->id,
                    'branch_id'    => $defaultBranch->id,
                    'office_branch' => $defaultBranch->name,
                ]);
                $this->line("  <info>CREATE</info> [{$label}] {$user->email} → {$empId}");
                $created++;
            }
        }

        $total = Employee::count();
        $this->newLine();
        $this->info("✅ Sync complete: {$created} created, {$skipped} skipped. Total employees: {$total}");
        return 0;
    }
}
