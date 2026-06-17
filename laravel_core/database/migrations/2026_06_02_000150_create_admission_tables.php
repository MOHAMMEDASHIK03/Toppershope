<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admission_users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['head', 'member'])->default('member');
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('admission_contacts', function (Blueprint $table) {
            $table->id();
            $table->enum('source_type', ['ad_lead', 'registered', 'non_purchaser']);
            $table->foreignId('ad_lead_id')->nullable()->constrained('ad_leads')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('admission_users')->nullOnDelete();
            $table->enum('call_status', ['not_called', 'answered', 'no_response'])->default('not_called');
            $table->enum('outcome', ['pending', 'will_join', 'rejected'])->default('pending');
            $table->timestamp('last_called_at')->nullable();
            $table->timestamp('trial_issued_at')->nullable();
            $table->boolean('needs_followup')->default(false);
            $table->timestamps();
        });

        Schema::create('admission_remarks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->constrained('admission_contacts')->cascadeOnDelete();
            $table->foreignId('admission_user_id')->constrained('admission_users')->cascadeOnDelete();
            $table->text('note');
            $table->timestamp('called_at')->useCurrent();
            $table->timestamps();
        });

        Schema::create('trial_accesses', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->foreignId('contact_id')->nullable()->constrained('admission_contacts')->nullOnDelete();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('trial_email')->unique();
            $table->string('trial_password');
            $table->string('plain_password', 50)->nullable();
            $table->foreignId('batch_id')->constrained('batches')->cascadeOnDelete();
            $table->foreignId('issued_by')->constrained('admission_users')->cascadeOnDelete();
            $table->timestamp('expires_at');
            $table->boolean('is_expired')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trial_accesses');
        Schema::dropIfExists('admission_remarks');
        Schema::dropIfExists('admission_contacts');
        Schema::dropIfExists('admission_users');
    }
};
