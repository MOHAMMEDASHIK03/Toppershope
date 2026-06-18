<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });

        Schema::create('quiz_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->integer('time_limit_minutes')->nullable();
            $table->decimal('marks_per_question', 5, 2)->default(1);
            $table->decimal('negative_marks_per_question', 5, 2)->default(0);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_section_id')->constrained()->cascadeOnDelete();
            $table->text('question_text')->nullable();
            $table->string('question_image_path')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('question_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained()->cascadeOnDelete();
            $table->text('option_text')->nullable();
            $table->string('option_image_path')->nullable();
            $table->boolean('is_correct')->default(false);
            $table->timestamps();
        });

        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('score', 8, 2)->default(0);
            $table->text('faculty_remarks')->nullable();
            $table->timestamps();
        });

        Schema::create('quiz_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_attempt_id')->constrained()->cascadeOnDelete();
            $table->foreignId('question_id')->constrained()->cascadeOnDelete();
            $table->foreignId('option_id')->nullable()->constrained('question_options')->cascadeOnDelete();
            $table->text('subjective_answer')->nullable();
            $table->boolean('is_correct')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_answers');
        Schema::dropIfExists('quiz_attempts');
        Schema::dropIfExists('question_options');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('quiz_sections');
        Schema::dropIfExists('quizzes');
    }
};
