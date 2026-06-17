<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateHrUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hr:create-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new HR User administrator account';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->ask('Full Name of HR Admin');
        $email = $this->ask('Email Address');
        
        if (\App\Models\HR\HrUser::where('email', $email)->exists()) {
            $this->error('An HR User with this email already exists.');
            return;
        }

        $password = $this->secret('Password');
        $confirmPassword = $this->secret('Confirm Password');

        if ($password !== $confirmPassword) {
            $this->error('Passwords do not match.');
            return;
        }

        \App\Models\HR\HrUser::create([
            'name' => $name,
            'email' => $email,
            'password' => \Illuminate\Support\Facades\Hash::make($password),
            'role' => 'hr_manager',
            'is_active' => true,
        ]);

        $this->info("HR User '$name' created successfully. They can now login at /hr/login");
    }
}
