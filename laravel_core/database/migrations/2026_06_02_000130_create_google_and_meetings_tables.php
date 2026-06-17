<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('google_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('access_token');
            $table->text('refresh_token')->nullable();
            $table->string('token_type')->default('Bearer');
            $table->timestamp('expires_at')->nullable();
            $table->string('google_email')->nullable();
            $table->timestamps();

            $table->unique('user_id');
        });

        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faculty_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('batch_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->string('google_event_id')->nullable();
            $table->string('meet_link')->nullable();
            $table->enum('type', ['batch', 'one_on_one'])->default('batch');
            $table->enum('status', ['scheduled', 'cancelled'])->default('scheduled');
            $table->timestamps();
        });

        Schema::create('meeting_invitees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('email');
            $table->string('name')->nullable();
            $table->enum('status', ['invited', 'accepted', 'declined'])->default('invited');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meeting_invitees');
        Schema::dropIfExists('meetings');
        Schema::dropIfExists('google_tokens');
    }
};
