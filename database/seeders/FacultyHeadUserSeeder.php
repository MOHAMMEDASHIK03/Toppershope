<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class FacultyHeadUserSeeder extends Seeder
{
    public function run(): void
    {
        $password = env('ADMIN_SEED_PASSWORD', 'Admin@123');

        User::updateOrCreate(
            ['email' => 'facultyHead@toppershope.com'],
            [
                'name' => 'Faculty Head',
                'password' => Hash::make($password),
                'role' => 'faculty_head',
            ]
        );
    }
}
