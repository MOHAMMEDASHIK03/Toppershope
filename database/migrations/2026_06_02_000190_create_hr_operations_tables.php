<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('leave_type_id')->constrained('leave_types')->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('reason');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('hr_users')->nullOnDelete();
            $table->text('admin_remarks')->nullable();
            $table->timestamps();
        });

        Schema::create('leave_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('leave_type_id')->constrained('leave_types')->cascadeOnDelete();
            $table->integer('balance');
            $table->year('year');
            $table->timestamps();

            $table->unique(['employee_id', 'leave_type_id', 'year']);
        });

        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->date('date');
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->enum('status', ['present', 'absent', 'half_day', 'late', 'on_leave'])->default('present');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['employee_id', 'date']);
        });

        Schema::create('salary_structures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->decimal('basic_salary', 10, 2);
            $table->decimal('hra', 10, 2)->default(0);
            $table->decimal('other_allowances', 10, 2)->default(0);
            $table->decimal('tax_deductions', 10, 2)->default(0);
            $table->decimal('other_deductions', 10, 2)->default(0);
            $table->decimal('net_salary', 10, 2);
            $table->timestamps();
        });

        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('month_year');
            $table->decimal('basic_salary', 10, 2);
            $table->decimal('allowances', 10, 2)->default(0);
            $table->decimal('deductions', 10, 2)->default(0);
            $table->decimal('net_payable', 10, 2);
            $table->enum('status', ['pending', 'paid'])->default('pending');
            $table->date('payment_date')->nullable();
            $table->string('transaction_id')->nullable();
            $table->timestamps();

            $table->unique(['employee_id', 'month_year']);
        });

        Schema::create('kpis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('target')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'achieved', 'missed'])->default('pending');
            $table->date('deadline')->nullable();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->foreignId('designation_id')->nullable()->constrained('designations')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('performance_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('kpi_id')->nullable()->constrained('kpis')->cascadeOnDelete();
            $table->foreignId('reviewer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('review_period');
            $table->date('review_date')->nullable();
            $table->integer('rating')->nullable();
            $table->text('feedback')->nullable();
            $table->text('manager_feedback')->nullable();
            $table->text('employee_feedback')->nullable();
            $table->enum('status', ['draft', 'published', 'acknowledged'])->default('published');
            $table->text('goals_for_next_period')->nullable();
            $table->timestamps();
        });

        Schema::create('job_postings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->string('location')->nullable();
            $table->string('employment_type')->default('full-time');
            $table->text('description');
            $table->text('requirements')->nullable();
            $table->string('salary_range')->nullable();
            $table->integer('vacancies')->default(1);
            $table->enum('status', ['open', 'closed', 'draft'])->default('draft');
            $table->date('closing_date')->nullable();
            $table->timestamps();
        });

        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_posting_id')->constrained('job_postings')->cascadeOnDelete();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('resume_path')->nullable();
            $table->enum('status', ['applied', 'shortlisted', 'interviewed', 'offered', 'hired', 'rejected'])->default('applied');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('interviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_application_id')->constrained('job_applications')->cascadeOnDelete();
            $table->foreignId('interviewer_id')->nullable()->constrained('hr_users')->nullOnDelete();
            $table->dateTime('scheduled_at');
            $table->string('location_or_link')->nullable();
            $table->text('feedback')->nullable();
            $table->enum('status', ['scheduled', 'completed', 'cancelled', 'no_show'])->default('scheduled');
            $table->integer('rating')->nullable();
            $table->timestamps();
        });

        Schema::create('employee_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('document_name');
            $table->string('document_type', 50)->default('other');
            $table->string('file_path');
            $table->date('expiry_date')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
        });

        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->foreignId('department_id')->nullable()->constrained('departments')->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('hr_users')->cascadeOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
        Schema::dropIfExists('employee_documents');
        Schema::dropIfExists('interviews');
        Schema::dropIfExists('job_applications');
        Schema::dropIfExists('job_postings');
        Schema::dropIfExists('performance_reviews');
        Schema::dropIfExists('kpis');
        Schema::dropIfExists('payrolls');
        Schema::dropIfExists('salary_structures');
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('leave_balances');
        Schema::dropIfExists('leaves');
    }
};
