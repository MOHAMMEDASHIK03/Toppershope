<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_personal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->unique()->constrained('employees')->cascadeOnDelete();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('blood_group')->nullable();
            $table->string('nationality')->nullable()->default('Indian');
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->nullable();
            $table->string('spouse_name')->nullable();
            $table->integer('num_dependents')->nullable()->default(0);
            $table->timestamps();
        });

        Schema::create('employee_contact', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->unique()->constrained('employees')->cascadeOnDelete();
            $table->string('alternate_phone')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_number')->nullable();
            $table->string('emergency_contact_relationship')->nullable();
            $table->string('permanent_address_house')->nullable();
            $table->string('permanent_address_city')->nullable();
            $table->string('permanent_address_district')->nullable();
            $table->string('permanent_address_state')->nullable();
            $table->string('permanent_address_postal_code')->nullable();
            $table->string('current_address_house')->nullable();
            $table->string('current_address_city')->nullable();
            $table->string('current_address_district')->nullable();
            $table->string('current_address_state')->nullable();
            $table->string('current_address_postal_code')->nullable();
            $table->string('perm_house')->nullable();
            $table->string('perm_city')->nullable();
            $table->string('perm_district')->nullable();
            $table->string('perm_state')->nullable();
            $table->string('perm_country')->nullable()->default('India');
            $table->string('perm_postal')->nullable();
            $table->string('curr_house')->nullable();
            $table->string('curr_city')->nullable();
            $table->string('curr_district')->nullable();
            $table->string('curr_state')->nullable();
            $table->string('curr_country')->nullable()->default('India');
            $table->string('curr_postal')->nullable();
            $table->boolean('same_as_permanent')->default(false);
            $table->timestamps();
        });

        Schema::create('employee_identity', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->unique()->constrained('employees')->cascadeOnDelete();
            $table->string('aadhaar_number')->nullable();
            $table->string('pan_number')->nullable();
            $table->string('passport_number')->nullable();
            $table->date('passport_expiry')->nullable();
            $table->string('driving_license_number')->nullable();
            $table->string('voter_id')->nullable();
            $table->text('visa_details')->nullable();
            $table->timestamps();
        });

        Schema::create('employee_payroll_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->unique()->constrained('employees')->cascadeOnDelete();
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_ifsc_code')->nullable();
            $table->string('uan_number')->nullable();
            $table->string('esic_number')->nullable();
            $table->decimal('basic_salary', 12, 2)->nullable();
            $table->decimal('hra', 12, 2)->nullable();
            $table->decimal('other_allowances', 12, 2)->nullable();
            $table->decimal('bonuses', 12, 2)->nullable();
            $table->decimal('incentives', 12, 2)->nullable();
            $table->decimal('tax_deductions', 12, 2)->nullable();
            $table->enum('payment_method', ['bank_transfer', 'cheque', 'cash'])->default('bank_transfer');
            $table->timestamps();
        });

        Schema::create('employee_education', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('degree');
            $table->string('institution');
            $table->string('field_of_study')->nullable();
            $table->year('graduation_year')->nullable();
            $table->string('grade_cgpa')->nullable();
            $table->timestamps();
        });

        Schema::create('employee_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('skill_name');
            $table->enum('skill_type', ['technical', 'soft', 'language', 'certification'])->default('technical');
            $table->string('level')->nullable();
            $table->timestamps();
        });

        Schema::create('employee_assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('asset_type');
            $table->string('asset_serial')->nullable();
            $table->date('assigned_date')->nullable();
            $table->date('returned_date')->nullable();
            $table->string('status')->default('assigned');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('employee_exit', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->unique()->constrained('employees')->cascadeOnDelete();
            $table->date('resignation_date')->nullable();
            $table->date('last_working_day')->nullable();
            $table->text('exit_interview_notes')->nullable();
            $table->string('reason_for_leaving')->nullable();
            $table->boolean('assets_returned')->default(false);
            $table->enum('settlement_status', ['pending', 'processing', 'settled'])->default('pending');
            $table->text('hr_remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_exit');
        Schema::dropIfExists('employee_assets');
        Schema::dropIfExists('employee_skills');
        Schema::dropIfExists('employee_education');
        Schema::dropIfExists('employee_payroll_details');
        Schema::dropIfExists('employee_identity');
        Schema::dropIfExists('employee_contact');
        Schema::dropIfExists('employee_personal');
    }
};
