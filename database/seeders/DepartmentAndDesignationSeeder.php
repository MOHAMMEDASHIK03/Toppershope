<?php

namespace Database\Seeders;

use App\Models\HR\Department;
use App\Models\HR\Designation;
use Illuminate\Database\Seeder;

class DepartmentAndDesignationSeeder extends Seeder
{
    public function run(): void
    {
        $structure = [
            'Human Resources' => [
                'HR Manager',
                'HR Executive',
                'HR Senior',
            ],
            'Canteen' => [
                'Faculty Head',
                'Faculty',
            ],
            'Technical Team' => [
                'Ads Manager',
                'Ads Executive',
                'WhatsApp Marketing',
                'Data Analytics',
                'Studio Manager',
            ],
            'House Keeping' => [],
            'Security' => [],
            'CEO & Founder' => [],
            'Foundation - Class 8th' => [
                'Admission Counselor',
                'Admission Counseling Coordinator',
                'Mentorship',
            ],
            'Foundation - Class 9th' => [
                'Admission Counselor',
                'Admission Counseling Coordinator',
                'Mentorship',
            ],
            'Foundation - Class 10th' => [
                'Admission Head',
                'Admission Counsellor',
                'Mentorship',
            ],
            'Foundation - Class 11th' => [
                'Admission Counselor',
                'Mentorship',
            ],
            'Foundation - Class 12th' => [
                'Admission Counselor',
                'Mentorship',
            ],
            'NEET UG' => [
                'Admission Counselor',
                'Biology Faculty',
                'Physics Faculty',
                'Chemistry Faculty',
                'Botany Faculty',
                'Zoology Faculty',
                'Studio Operator',
            ],
            'JEE Main' => [
                'Admission Counselor',
                'Physics Faculty',
                'Maths Faculty',
                'Chemistry Faculty',
                'Mentorship',
            ],
            'TNPSC' => [
                'Admission Counselor',
            ],
            'IT Course' => [
                'Admission Counselor',
                'Mentorship',
            ],
            'English Partner' => [
                'Admission Counselor',
                'Admission Counseling Coordinator',
            ],
            'Front Office' => [],
            'General Manager' => [],
            'Personal Assistant' => [],
            'Auditor' => [],
            'Mentorship' => [],
            "Topper's Hope" => [
                'CEO & Founder',
            ],
            'Child Care' => [
                'Child Care Staff',
            ],
            'Finance' => [
                'Finance Manager',
            ],
        ];

        foreach ($structure as $departmentName => $designations) {
            $department = Department::updateOrCreate(
                ['name' => $departmentName],
                ['is_active' => true]
            );

            foreach ($designations as $designationName) {
                Designation::updateOrCreate(
                    [
                        'name' => $designationName,
                        'department_id' => $department->id,
                    ],
                    ['is_active' => true]
                );
            }
        }
    }
}
