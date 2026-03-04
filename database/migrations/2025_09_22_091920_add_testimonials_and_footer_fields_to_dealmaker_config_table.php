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
            // Footer Content (check if they don't already exist)
            if (!Schema::hasColumn('dealmaker_config', 'footer_company_description')) {
                $table->text('footer_company_description')->nullable();
            }
            if (!Schema::hasColumn('dealmaker_config', 'footer_company_address')) {
                $table->text('footer_company_address')->nullable();
            }
            if (!Schema::hasColumn('dealmaker_config', 'footer_award_image')) {
                $table->string('footer_award_image')->nullable();
            }
            
            // Footer Legal Links
            if (!Schema::hasColumn('dealmaker_config', 'footer_terms_url')) {
                $table->string('footer_terms_url')->nullable();
            }
            if (!Schema::hasColumn('dealmaker_config', 'footer_privacy_url')) {
                $table->string('footer_privacy_url')->nullable();
            }
            if (!Schema::hasColumn('dealmaker_config', 'footer_cookies_url')) {
                $table->string('footer_cookies_url')->nullable();
            }
            if (!Schema::hasColumn('dealmaker_config', 'footer_security_url')) {
                $table->string('footer_security_url')->nullable();
            }
            if (!Schema::hasColumn('dealmaker_config', 'footer_accessibility_url')) {
                $table->string('footer_accessibility_url')->nullable();
            }
            if (!Schema::hasColumn('dealmaker_config', 'footer_copyright_text')) {
                $table->string('footer_copyright_text')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dealmaker_config', function (Blueprint $table) {
            $table->dropColumn([
                'footer_company_description',
                'footer_company_address',
                'footer_award_image',
                'footer_terms_url',
                'footer_privacy_url',
                'footer_cookies_url',
                'footer_security_url',
                'footer_accessibility_url',
                'footer_copyright_text'
            ]);
        });
    }
};
