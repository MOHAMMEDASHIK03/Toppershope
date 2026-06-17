<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['slug' => 'manage-courses', 'name' => 'Manage Courses'],
            ['slug' => 'manage-batches', 'name' => 'Manage Batches'],
            ['slug' => 'manage-enrollments', 'name' => 'Manage Enrollments'],
            ['slug' => 'manage-lms', 'name' => 'Manage LMS'],
            ['slug' => 'manage-hr', 'name' => 'Manage HR'],
            ['slug' => 'manage-payroll', 'name' => 'Manage Payroll'],
            ['slug' => 'manage-ads', 'name' => 'Manage Ads'],
            ['slug' => 'manage-admission', 'name' => 'Manage Admission'],
            ['slug' => 'manage-users', 'name' => 'Manage Users'],
            ['slug' => 'manage-settings', 'name' => 'Manage Settings'],
        ];

        $permissionIds = [];

        foreach ($permissions as $permission) {
            DB::table('permissions')->updateOrInsert(
                ['slug' => $permission['slug']],
                [
                    'name' => $permission['name'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            $permissionIds[] = DB::table('permissions')
                ->where('slug', $permission['slug'])
                ->value('id');
        }

        $adminRoleId = DB::table('roles')->where('slug', 'admin')->value('id');

        if ($adminRoleId) {
            foreach ($permissionIds as $permissionId) {
                DB::table('role_permission')->updateOrInsert(
                    [
                        'role_id' => $adminRoleId,
                        'permission_id' => $permissionId,
                    ],
                    []
                );
            }
        }
    }
}
