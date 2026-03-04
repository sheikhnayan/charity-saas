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
        Schema::table('dealmaker_config', function (Blueprint $table) {
            // Additional Social Media
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('twitter_url')->nullable();
            
            // Footer Additional Fields
            $table->text('footer_company_description')->nullable();
            $table->text('footer_address')->nullable();
            $table->json('footer_menu_raise_capital')->nullable();
            $table->json('footer_menu_solutions')->nullable();
            $table->json('footer_menu_company')->nullable();
            $table->json('footer_menu_resources')->nullable();
            $table->string('footer_newsletter_title')->nullable();
            $table->text('footer_newsletter_description')->nullable();
            
            // Case Studies Section
            $table->string('case_studies_title')->nullable();
            $table->json('case_studies')->nullable();
            
            // DealMaker Difference Section (Tabs)
            $table->string('difference_section_title')->nullable();
            $table->string('difference_eyebrow_text')->nullable();
            $table->json('difference_tabs')->nullable();
            
            // Testimonials Section
            $table->string('testimonials_section_title')->nullable();
            $table->string('testimonials_section_subtitle')->nullable();
            
            // Capital Raising Section
            $table->string('capital_raising_title')->nullable();
            $table->string('capital_raising_subtitle')->nullable();
            $table->json('capital_raising_features')->nullable();
            
            // Final CTA Section
            $table->string('final_cta_title')->nullable();
            $table->string('final_cta_subtitle')->nullable();
            $table->string('final_cta_button_text')->nullable();
            $table->string('final_cta_button_url')->nullable();
            $table->text('final_cta_background_image')->nullable();
            
            // Slider Images for Phone Section
            $table->json('slider_images')->nullable();
            
            // Additional Section Toggles
            $table->boolean('show_case_studies')->default(true);
            $table->boolean('show_difference_section')->default(true);
            $table->boolean('show_capital_raising')->default(true);
            $table->boolean('show_final_cta')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dealmaker_config', function (Blueprint $table) {
            // Drop all the added columns
            $table->dropColumn([
                'facebook_url', 'instagram_url', 'twitter_url',
                'footer_company_description', 'footer_address',
                'footer_menu_raise_capital', 'footer_menu_solutions', 
                'footer_menu_company', 'footer_menu_resources',
                'footer_newsletter_title', 'footer_newsletter_description',
                'case_studies_title', 'case_studies',
                'difference_section_title', 'difference_eyebrow_text', 'difference_tabs',
                'testimonials_section_title', 'testimonials_section_subtitle',
                'capital_raising_title', 'capital_raising_subtitle', 'capital_raising_features',
                'final_cta_title', 'final_cta_subtitle', 'final_cta_button_text', 
                'final_cta_button_url', 'final_cta_background_image',
                'slider_images',
                'show_case_studies', 'show_difference_section', 
                'show_capital_raising', 'show_final_cta'
            ]);
        });
    }
};
