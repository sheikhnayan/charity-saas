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
            // Navigation Menu Items
            $table->text('nav_raise_capital_title')->nullable();
            $table->text('nav_products_title')->nullable();
            $table->text('nav_resources_title')->nullable();
            
            // Slider Section Content 
            $table->text('platform_section_title')->nullable(); // "Capital Redefined"
            $table->text('platform_section_description')->nullable();
            $table->text('platform_cta_text')->nullable(); // "Download Now"
            $table->text('platform_cta_url')->nullable();
            
            // Static Slide Content (non-dynamic slides)
            $table->text('slide_2_title')->nullable(); // "Raise Boldly. Own Your Future."
            $table->text('slide_2_description')->nullable();
            $table->text('slide_2_cta_text')->nullable();
            $table->text('slide_2_cta_url')->nullable();
            
            $table->text('slide_3_title')->nullable(); // "Superior Retail Experience."
            $table->text('slide_3_description')->nullable();
            $table->text('slide_3_cta_text')->nullable();
            $table->text('slide_3_cta_url')->nullable();
            
            // Case Study Labels
            $table->text('case_study_capital_raised_label')->nullable(); // "Capital Raised"
            $table->text('case_study_investors_label')->nullable(); // "Investors"
            $table->text('case_study_learn_more_text')->nullable(); // "Learn More"
            
            // Tab Button Text (using existing or adding specific ones)
            $table->text('tab_plan_button_text')->nullable();
            $table->text('tab_raise_button_text')->nullable();
            $table->text('tab_engage_button_text')->nullable();
            $table->text('tab_repeat_button_text')->nullable();
            
            // Additional Static Text
            $table->text('main_slider_default_description')->nullable();
            $table->text('testimonials_intro_text')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dealmaker_config', function (Blueprint $table) {
            $table->dropColumn([
                'nav_raise_capital_title', 'nav_products_title', 'nav_resources_title',
                'platform_section_title', 'platform_section_description', 'platform_cta_text', 'platform_cta_url',
                'slide_2_title', 'slide_2_description', 'slide_2_cta_text', 'slide_2_cta_url',
                'slide_3_title', 'slide_3_description', 'slide_3_cta_text', 'slide_3_cta_url',
                'case_study_capital_raised_label', 'case_study_investors_label', 'case_study_learn_more_text',
                'tab_plan_button_text', 'tab_raise_button_text', 'tab_engage_button_text', 'tab_repeat_button_text',
                'main_slider_default_description', 'testimonials_intro_text'
            ]);
        });
    }
};
