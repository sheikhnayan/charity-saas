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
        Schema::table('settings', function (Blueprint $table) {
            // Only add columns that don't already exist
            if (!Schema::hasColumn('settings', 'hero_title')) {
                $table->string('hero_title')->nullable();
            }
            if (!Schema::hasColumn('settings', 'hero_subtitle')) {
                $table->string('hero_subtitle')->nullable();
            }
            if (!Schema::hasColumn('settings', 'hero_cta_text')) {
                $table->string('hero_cta_text')->nullable();
            }
            if (!Schema::hasColumn('settings', 'hero_cta_url')) {
                $table->string('hero_cta_url')->nullable();
            }
            if (!Schema::hasColumn('settings', 'hero_background_video')) {
                $table->text('hero_background_video')->nullable();
            }
            if (!Schema::hasColumn('settings', 'hero_background_image')) {
                $table->text('hero_background_image')->nullable();
            }
            if (!Schema::hasColumn('settings', 'site_logo')) {
                $table->text('site_logo')->nullable();
            }
            if (!Schema::hasColumn('settings', 'site_tagline')) {
                $table->string('site_tagline')->nullable();
            }
            if (!Schema::hasColumn('settings', 'stat_1_number')) {
                $table->string('stat_1_number')->nullable();
            }
            if (!Schema::hasColumn('settings', 'stat_1_text')) {
                $table->string('stat_1_text')->nullable();
            }
            if (!Schema::hasColumn('settings', 'stat_2_number')) {
                $table->string('stat_2_number')->nullable();
            }
            if (!Schema::hasColumn('settings', 'stat_2_text')) {
                $table->string('stat_2_text')->nullable();
            }
            if (!Schema::hasColumn('settings', 'stat_3_number')) {
                $table->string('stat_3_number')->nullable();
            }
            if (!Schema::hasColumn('settings', 'stat_3_text')) {
                $table->string('stat_3_text')->nullable();
            }
            if (!Schema::hasColumn('settings', 'announcement_text')) {
                $table->string('announcement_text')->nullable();
            }
            if (!Schema::hasColumn('settings', 'announcement_badge')) {
                $table->string('announcement_badge')->nullable();
            }
            if (!Schema::hasColumn('settings', 'announcement_url')) {
                $table->string('announcement_url')->nullable();
            }
            if (!Schema::hasColumn('settings', 'navigation_items')) {
                $table->json('navigation_items')->nullable();
            }
            if (!Schema::hasColumn('settings', 'about_title')) {
                $table->text('about_title')->nullable();
            }
            if (!Schema::hasColumn('settings', 'about_description')) {
                $table->text('about_description')->nullable();
            }
            if (!Schema::hasColumn('settings', 'about_image')) {
                $table->text('about_image')->nullable();
            }
            if (!Schema::hasColumn('settings', 'services')) {
                $table->json('services')->nullable();
            }
            if (!Schema::hasColumn('settings', 'testimonials')) {
                $table->json('testimonials')->nullable();
            }
            if (!Schema::hasColumn('settings', 'business_hours')) {
                $table->string('business_hours')->nullable();
            }
            if (!Schema::hasColumn('settings', 'linkedin_url')) {
                $table->string('linkedin_url')->nullable();
            }
            if (!Schema::hasColumn('settings', 'youtube_url')) {
                $table->string('youtube_url')->nullable();
            }
            if (!Schema::hasColumn('settings', 'tiktok_url')) {
                $table->string('tiktok_url')->nullable();
            }
            if (!Schema::hasColumn('settings', 'meta_title')) {
                $table->text('meta_title')->nullable();
            }
            if (!Schema::hasColumn('settings', 'meta_description')) {
                $table->text('meta_description')->nullable();
            }
            if (!Schema::hasColumn('settings', 'meta_keywords')) {
                $table->text('meta_keywords')->nullable();
            }
            if (!Schema::hasColumn('settings', 'og_image')) {
                $table->text('og_image')->nullable();
            }
            if (!Schema::hasColumn('settings', 'custom_css')) {
                $table->longText('custom_css')->nullable();
            }
            if (!Schema::hasColumn('settings', 'custom_js')) {
                $table->longText('custom_js')->nullable();
            }
            if (!Schema::hasColumn('settings', 'custom_head_code')) {
                $table->longText('custom_head_code')->nullable();
            }
            if (!Schema::hasColumn('settings', 'footer_text')) {
                $table->text('footer_text')->nullable();
            }
            if (!Schema::hasColumn('settings', 'footer_copyright')) {
                $table->text('footer_copyright')->nullable();
            }
            if (!Schema::hasColumn('settings', 'show_hero')) {
                $table->boolean('show_hero')->default(true);
            }
            if (!Schema::hasColumn('settings', 'show_stats')) {
                $table->boolean('show_stats')->default(true);
            }
            if (!Schema::hasColumn('settings', 'show_about')) {
                $table->boolean('show_about')->default(true);
            }
            if (!Schema::hasColumn('settings', 'show_services')) {
                $table->boolean('show_services')->default(true);
            }
            if (!Schema::hasColumn('settings', 'show_testimonials')) {
                $table->boolean('show_testimonials')->default(true);
            }
            if (!Schema::hasColumn('settings', 'show_contact')) {
                $table->boolean('show_contact')->default(true);
            }
            // Use existing phone_number column instead of creating new phone field
            // Use existing address column instead of creating new address field
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'hero_title', 'hero_subtitle', 'hero_cta_text', 'hero_cta_url',
                'hero_background_video', 'hero_background_image', 'site_logo', 'site_tagline',
                'stat_1_number', 'stat_1_text', 'stat_2_number', 'stat_2_text', 'stat_3_number', 'stat_3_text',
                'announcement_text', 'announcement_badge', 'announcement_url', 'navigation_items',
                'about_title', 'about_description', 'about_image', 'services', 'testimonials',
                'phone_number', 'address', 'business_hours', 'linkedin_url', 'youtube_url', 'tiktok_url',
                'meta_title', 'meta_description', 'meta_keywords', 'og_image',
                'custom_css', 'custom_js', 'custom_head_code', 'footer_text', 'footer_copyright',
                'show_hero', 'show_stats', 'show_about', 'show_services', 'show_testimonials', 'show_contact'
            ]);
        });
    }
};
