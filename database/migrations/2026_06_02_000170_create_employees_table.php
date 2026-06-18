<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id')->unique();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('profile_photo')->nullable();
            $table->string('email')->unique();
            $table->enum('employee_type', ['full_time', 'part_time', 'contract', 'intern', 'probation'])->default('full_time');
            $table->enum('employment_status', ['active', 'on_leave', 'suspended', 'resigned', 'terminated'])->default('active');
            $table->string('work_location')->nullable();
            $table->string('office_branch')->nullable();
            $table->unsignedBigInteger('reporting_manager_id')->nullable();
            $table->integer('probation_period')->nullable();
            $table->date('confirmation_date')->nullable();
            $table->string('shift_type')->nullable();
            $table->string('official_email')->nullable();
            $table->string('phone')->nullable();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->foreignId('designation_id')->nullable()->constrained('designations')->nullOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->date('joining_date')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->text('address')->nullable();
            $table->string('account_type')->nullable();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('reporting_manager_id')->references('id')->on('employees')->nullOnDelete();
            $table->index(['account_type', 'account_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
