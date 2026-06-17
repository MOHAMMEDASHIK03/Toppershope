<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['slug' => 'admin', 'name' => 'Admin'],
            ['slug' => 'faculty', 'name' => 'Faculty'],
            ['slug' => 'faculty_head', 'name' => 'Faculty Head'],
            ['slug' => 'admission', 'name' => 'Admission'],
            ['slug' => 'doubt', 'name' => 'Doubt'],
            ['slug' => 'parent', 'name' => 'Parent'],
            ['slug' => 'student', 'name' => 'Student'],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['slug' => $role['slug']],
                [
                    'name' => $role['name'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
