<?php

namespace Database\Seeders;

use App\Models\HR\LeaveType;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(CategorySeeder::class);
        $this->call(DepartmentAndDesignationSeeder::class);

        $leaveTypes = [
            [
                'name' => 'Casual Leave',
                'days_allowed' => 12,
            ],
            [
                'name' => 'Sick Leave',
                'days_allowed' => 12,
            ],
            [
                'name' => 'Earned Leave',
                'days_allowed' => 15,
            ],
            [
                'name' => 'Loss of Pay',
                'days_allowed' => 0,
            ],
            [
                'name' => 'Compensatory Off',
                'days_allowed' => 5,
            ],
            [
                'name' => 'Maternity Leave',
                'days_allowed' => 180,
            ],
            [
                'name' => 'Paternity Leave',
                'days_allowed' => 15,
            ],
            [
                'name' => 'Marriage Leave',
                'days_allowed' => 5,
            ],
            [
                'name' => 'Bereavement Leave',
                'days_allowed' => 3,
            ],
            [
                'name' => 'Work From Home',
                'days_allowed' => 365,
            ],
        ];

        foreach ($leaveTypes as $type) {
            LeaveType::updateOrCreate(
                ['name' => $type['name']],
                [
                    'days_allowed' => $type['days_allowed'],
                    'is_active' => true,
                ]
            );
        }
    }
}
