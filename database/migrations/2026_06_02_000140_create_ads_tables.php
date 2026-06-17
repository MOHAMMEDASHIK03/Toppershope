<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ads_users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['ads_manager', 'ads_head'])->default('ads_manager');
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('ad_campaigns', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->longText('description')->nullable();
            $table->string('course_name')->nullable();
            $table->string('badge_text')->nullable();
            $table->string('hero_image')->nullable();
            $table->json('features')->nullable();
            $table->decimal('fee', 10, 2)->nullable();
            $table->decimal('original_fee', 10, 2)->nullable();
            $table->string('brochure_pdf')->nullable();
            $table->string('faculty_name')->nullable();
            $table->string('faculty_title')->nullable();
            $table->text('faculty_bio')->nullable();
            $table->string('faculty_photo')->nullable();
            $table->string('faculty_experience')->nullable();
            $table->json('stats')->nullable();
            $table->json('testimonials')->nullable();
            $table->json('faqs')->nullable();
            $table->string('cta_button_text')->default('Enrol Now');
            $table->string('interest_button_text')->default("I'm Interested");
            $table->string('primary_color', 7)->default('#1B2AFF');
            $table->string('secondary_color', 7)->default('#7B61FF');
            $table->string('accent_color', 7)->default('#00D2FF');
            $table->string('bg_color', 7)->default('#0a0a1a');
            $table->string('text_color', 7)->default('#ffffff');
            $table->enum('popup_type', ['none', 'global', 'custom'])->default('global');
            $table->string('popup_image')->nullable();
            $table->integer('popup_delay_seconds')->default(3);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('ads_users')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('ad_popup_global', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(false);
            $table->integer('delay_seconds')->default(3);
            $table->string('link_url')->nullable();
            $table->string('link_text')->default('Learn More');
            $table->foreignId('updated_by')->nullable()->constrained('ads_users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('ad_leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('ad_campaigns')->cascadeOnDelete();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('city')->nullable();
            $table->text('message')->nullable();
            $table->enum('enquiry_type', ['enrol', 'interest'])->default('interest');
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ad_leads');
        Schema::dropIfExists('ad_popup_global');
        Schema::dropIfExists('ad_campaigns');
        Schema::dropIfExists('ads_users');
    }
};
