<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->nullable()->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('subcategory_id')->nullable()->constrained('subcategories')->nullOnDelete();
            $table->string('thumbnail')->nullable();
            $table->text('about')->nullable();
            $table->string('language')->default('Hindi & English');
            $table->string('duration')->nullable();
            $table->json('what_you_learn')->nullable();
            $table->json('highlights')->nullable();
            $table->json('includes')->nullable();
            $table->json('syllabus')->nullable();
            $table->string('syllabus_pdf_path')->nullable();
            $table->json('faqs')->nullable();
            $table->json('faculty')->nullable();
            $table->string('hero_image')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
