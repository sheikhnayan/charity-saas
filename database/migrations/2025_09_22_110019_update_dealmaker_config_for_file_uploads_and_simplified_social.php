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
            // Remove complex social media links system and replace with simple toggles
            $table->dropColumn('social_media_links');
            $table->dropColumn('footer_menu_columns');
            
            // Add simple social media toggle fields
            $table->boolean('show_linkedin')->default(true)->after('instagram_url');
            $table->boolean('show_twitter')->default(true)->after('show_linkedin');
            $table->boolean('show_facebook')->default(true)->after('show_twitter');
            $table->boolean('show_instagram')->default(true)->after('show_facebook');
            
            // Add logo upload field
            $table->string('uploaded_logo')->nullable()->after('site_logo');
            
            // Change OG image to upload field
            $table->string('uploaded_og_image')->nullable()->after('og_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dealmaker_config', function (Blueprint $table) {
            $table->json('social_media_links')->nullable();
            $table->json('footer_menu_columns')->nullable();
            
            $table->dropColumn([
                'show_linkedin',
                'show_twitter', 
                'show_facebook',
                'show_instagram',
                'uploaded_logo',
                'uploaded_og_image'
            ]);
        });
    }
};
