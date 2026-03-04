<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dealmaker_config', function (Blueprint $table) {
            $table->id();
            // Hero Section
            $table->string('hero_title')->nullable();
            $table->string('hero_subtitle')->nullable();
            $table->string('hero_cta_text')->nullable();
            $table->string('hero_cta_url')->nullable();
            $table->text('hero_background_video')->nullable();
            $table->text('hero_background_image')->nullable();
            
            // Site Branding
            $table->text('site_logo')->nullable();
            $table->string('site_tagline')->nullable();
            
            // Statistics
            $table->string('stat_1_number')->nullable();
            $table->string('stat_1_text')->nullable();
            $table->string('stat_2_number')->nullable();
            $table->string('stat_2_text')->nullable();
            $table->string('stat_3_number')->nullable();
            $table->string('stat_3_text')->nullable();
            
            // Announcement
            $table->string('announcement_text')->nullable();
            $table->string('announcement_badge')->nullable();
            $table->string('announcement_url')->nullable();
            
            // Navigation
            $table->json('navigation_items')->nullable();
            
            // About Section
            $table->text('about_title')->nullable();
            $table->text('about_description')->nullable();
            $table->text('about_image')->nullable();
            
            // Services & Testimonials
            $table->json('services')->nullable();
            $table->json('testimonials')->nullable();
            
            // Client Logos
            $table->json('client_logos')->nullable();
            
            // Contact Info
            $table->string('phone_number')->nullable();
            $table->text('address')->nullable();
            $table->string('business_hours')->nullable();
            
            // Social Media
            $table->string('linkedin_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('tiktok_url')->nullable();
            
            // SEO Meta
            $table->text('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('og_image')->nullable();
            
            // Custom Code
            $table->longText('custom_css')->nullable();
            $table->longText('custom_js')->nullable();
            $table->longText('custom_head_code')->nullable();
            
            // Footer
            $table->text('footer_text')->nullable();
            $table->text('footer_copyright')->nullable();
            
            // Section Toggles
            $table->boolean('show_hero')->default(true);
            $table->boolean('show_stats')->default(true);
            $table->boolean('show_about')->default(true);
            $table->boolean('show_services')->default(true);
            $table->boolean('show_testimonials')->default(true);
            $table->boolean('show_contact')->default(true);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dealmaker_config');
    }
};
