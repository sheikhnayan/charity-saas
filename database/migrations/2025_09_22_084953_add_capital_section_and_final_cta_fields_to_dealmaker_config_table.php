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
            // Capital Raising Section (n_section_play)
            $table->string('capital_revolutionized_title')->nullable()->default('Capital raising, revolutionized');
            $table->text('capital_revolutionized_description')->nullable();
            
            // Regulation Circles Content
            $table->string('reg_cf_title')->nullable()->default('Via Reg CF');
            $table->string('reg_cf_subtitle')->nullable()->default('Raise up to');
            $table->string('reg_cf_investor_text')->nullable()->default('Anyone can invest');
            $table->string('reg_cf_url')->nullable()->default('/na-typ');
            
            $table->string('reg_a_title')->nullable()->default('Via Reg A');
            $table->string('reg_a_subtitle')->nullable()->default('Raise up to');
            $table->string('reg_a_investor_text')->nullable()->default('Anyone can invest');
            $table->string('reg_a_url')->nullable()->default('/na-typ');
            
            $table->string('reg_d_title')->nullable()->default('Via Reg D');
            $table->string('reg_d_subtitle')->nullable()->default('Raise up to');
            $table->string('reg_d_investor_text')->nullable()->default('Accredited investors only');
            $table->string('reg_d_url')->nullable()->default('/na-typ');
            
            // Final CTA Section (n_final-section)
            $table->string('final_cta_main_title')->nullable()->default('Your vision. Your terms.');
            $table->text('final_cta_main_description')->nullable();
            
            $table->string('final_cta_primary_button_text')->nullable()->default('Book a Call');
            $table->string('final_cta_primary_button_url')->nullable()->default('/connect');
            $table->string('final_cta_secondary_button_text')->nullable()->default('View Case Studies');
            $table->string('final_cta_secondary_button_url')->nullable()->default('/category/case-studies');
            
            // Final CTA Background Images
            $table->string('final_cta_growth_image')->nullable();
            $table->string('final_cta_sky_image')->nullable();
            $table->string('final_cta_city_image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dealmaker_config', function (Blueprint $table) {
            $table->dropColumn([
                'capital_revolutionized_title',
                'capital_revolutionized_description',
                'reg_cf_title',
                'reg_cf_subtitle', 
                'reg_cf_investor_text',
                'reg_cf_url',
                'reg_a_title',
                'reg_a_subtitle',
                'reg_a_investor_text', 
                'reg_a_url',
                'reg_d_title',
                'reg_d_subtitle',
                'reg_d_investor_text',
                'reg_d_url',
                'final_cta_main_title',
                'final_cta_main_description',
                'final_cta_primary_button_text',
                'final_cta_primary_button_url',
                'final_cta_secondary_button_text',
                'final_cta_secondary_button_url',
                'final_cta_growth_image',
                'final_cta_sky_image',
                'final_cta_city_image'
            ]);
        });
    }
};
