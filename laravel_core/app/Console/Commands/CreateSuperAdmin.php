<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create-owner';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the Master Super Admin (Owner) account';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->ask('Owner Full Name');
        $email = $this->ask('Owner Email Address');
        
        if (\App\Models\Admin\Admin::where('email', $email)->exists()) {
            $this->error('An Admin with this email already exists.');
            return;
        }

        $password = $this->secret('Password');
        $confirmPassword = $this->secret('Confirm Password');

        if ($password !== $confirmPassword) {
            $this->error('Passwords do not match.');
            return;
        }

        \App\Models\Admin\Admin::create([
            'name' => $name,
            'email' => $email,
            'password' => \Illuminate\Support\Facades\Hash::make($password),
            'is_super_admin' => true,
        ]);

        $this->info("Super Admin '$name' created successfully! You can now login at /admin/login");
    }
}
