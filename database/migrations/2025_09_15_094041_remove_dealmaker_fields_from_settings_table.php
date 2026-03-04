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
            // Remove DealMaker-specific fields from settings table
            $columnsToRemove = [
                'hero_title', 'hero_subtitle', 'hero_cta_text', 'hero_cta_url',
                'hero_background_video', 'hero_background_image', 'site_logo', 'site_tagline',
                'stat_1_number', 'stat_1_text', 'stat_2_number', 'stat_2_text', 'stat_3_number', 'stat_3_text',
                'announcement_text', 'announcement_badge', 'announcement_url', 'navigation_items',
                'about_title', 'about_description', 'about_image', 'services', 'testimonials',
                'business_hours', 'linkedin_url', 'youtube_url', 'tiktok_url',
                'meta_title', 'meta_description', 'meta_keywords', 'og_image',
                'custom_css', 'custom_js', 'custom_head_code', 'footer_text', 'footer_copyright',
                'show_hero', 'show_stats', 'show_about', 'show_services', 'show_testimonials', 'show_contact'
            ];
            
            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('settings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            // Re-add the columns if rollback is needed
            $table->string('hero_title')->nullable();
            $table->string('hero_subtitle')->nullable();
            $table->string('hero_cta_text')->nullable();
            $table->string('hero_cta_url')->nullable();
            $table->text('hero_background_video')->nullable();
            $table->text('hero_background_image')->nullable();
            $table->text('site_logo')->nullable();
            $table->string('site_tagline')->nullable();
            $table->string('stat_1_number')->nullable();
            $table->string('stat_1_text')->nullable();
            $table->string('stat_2_number')->nullable();
            $table->string('stat_2_text')->nullable();
            $table->string('stat_3_number')->nullable();
            $table->string('stat_3_text')->nullable();
            $table->string('announcement_text')->nullable();
            $table->string('announcement_badge')->nullable();
            $table->string('announcement_url')->nullable();
            $table->json('navigation_items')->nullable();
            $table->text('about_title')->nullable();
            $table->text('about_description')->nullable();
            $table->text('about_image')->nullable();
            $table->json('services')->nullable();
            $table->json('testimonials')->nullable();
            $table->string('business_hours')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('tiktok_url')->nullable();
            $table->text('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('og_image')->nullable();
            $table->longText('custom_css')->nullable();
            $table->longText('custom_js')->nullable();
            $table->longText('custom_head_code')->nullable();
            $table->text('footer_text')->nullable();
            $table->text('footer_copyright')->nullable();
            $table->boolean('show_hero')->default(true);
            $table->boolean('show_stats')->default(true);
            $table->boolean('show_about')->default(true);
            $table->boolean('show_services')->default(true);
            $table->boolean('show_testimonials')->default(true);
            $table->boolean('show_contact')->default(true);
        });
    }
};
