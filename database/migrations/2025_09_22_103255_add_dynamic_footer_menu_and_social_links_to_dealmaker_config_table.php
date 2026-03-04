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
            // Dynamic Footer Menu Columns
            $table->json('footer_menu_columns')->nullable()->after('footer_copyright_text');
            
            // Social Media Links (replace individual social fields with dynamic system)
            $table->json('social_media_links')->nullable()->after('footer_menu_columns');
            
            // Keep existing social fields for backward compatibility, but will be replaced
            // Note: We'll keep existing linkedin_url, twitter_url, etc. for now and migrate later
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dealmaker_config', function (Blueprint $table) {
            $table->dropColumn([
                'footer_menu_columns',
                'social_media_links'
            ]);
        });
    }
};
