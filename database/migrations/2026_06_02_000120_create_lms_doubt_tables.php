<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doubts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('batch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('subject');
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
            $table->boolean('is_resolved')->default(false);
            $table->text('faculty_reply')->nullable();
            $table->timestamps();
        });

        Schema::create('doubt_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doubt_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('reply_text');
            $table->boolean('is_solution')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doubt_replies');
        Schema::dropIfExists('doubts');
    }
};
