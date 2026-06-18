<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('subcategory_id')->nullable()->constrained('subcategories')->nullOnDelete();
            $table->string('name');
            $table->decimal('price', 8, 2);
            $table->decimal('original_price', 8, 2)->nullable();
            $table->integer('total_seats')->default(100);
            $table->integer('filled_seats')->default(0);
            $table->date('start_date')->nullable();
            $table->string('status')->default('active');
            $table->string('mode')->default('Online Live');
            $table->string('schedule')->nullable();
            $table->string('mentor_name')->nullable();
            $table->string('mentor_photo')->nullable();
            $table->string('mentor_designation')->nullable();
            $table->text('batch_description')->nullable();
            $table->boolean('is_upcoming')->default(false);
            $table->string('language')->nullable();
            $table->integer('duration_weeks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};
