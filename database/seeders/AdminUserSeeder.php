<?php

namespace Database\Seeders;

use App\Models\Admin\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $password = env('ADMIN_SEED_PASSWORD', 'Admin@123');

        Admin::updateOrCreate(
            ['email' => 'admin@toppershope.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make($password),
                'is_super_admin' => true,
            ]
        );
    }
}
