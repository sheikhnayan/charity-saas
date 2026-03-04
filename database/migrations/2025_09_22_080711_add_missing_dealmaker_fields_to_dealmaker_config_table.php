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
            // Navigation & CTA Fields
            $table->string('signin_text')->nullable();
            $table->string('signin_url')->nullable();
            $table->string('main_cta_text')->nullable();
            $table->string('main_cta_url')->nullable();
            
            // Video Upload Fields
            $table->text('bg_video_mp4')->nullable();
            $table->text('bg_video_webm')->nullable();
            $table->text('bg_video_poster')->nullable();
            $table->text('modal_video_desktop')->nullable();
            $table->text('modal_video_mobile')->nullable();
            
            // Announcement Toggle
            $table->boolean('show_announcement')->default(false);
            
            // Plan Tab Button Fields
            $table->string('plan_button_text')->nullable();
            $table->string('plan_button_url')->nullable();
            
            // Raise Tab Button Fields  
            $table->string('raise_button_text')->nullable();
            $table->string('raise_button_url')->nullable();
            
            // Engage Tab Button Fields
            $table->string('engage_button_text')->nullable();
            $table->string('engage_button_url')->nullable();
            
            // Repeat Tab Button Fields
            $table->string('repeat_button_text')->nullable();
            $table->string('repeat_button_url')->nullable();
            
            // Additional Tab Content Fields (if needed)
            $table->text('plan_title')->nullable();
            $table->text('plan_description')->nullable();
            $table->text('raise_title')->nullable();  
            $table->text('raise_description')->nullable();
            $table->text('engage_title')->nullable();
            $table->text('engage_description')->nullable();
            $table->text('repeat_title')->nullable();
            $table->text('repeat_description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dealmaker_config', function (Blueprint $table) {
            $table->dropColumn([
                'signin_text', 'signin_url', 'main_cta_text', 'main_cta_url',
                'bg_video_mp4', 'bg_video_webm', 'bg_video_poster', 
                'modal_video_desktop', 'modal_video_mobile',
                'show_announcement',
                'plan_button_text', 'plan_button_url',
                'raise_button_text', 'raise_button_url', 
                'engage_button_text', 'engage_button_url',
                'repeat_button_text', 'repeat_button_url',
                'plan_title', 'plan_description',
                'raise_title', 'raise_description',
                'engage_title', 'engage_description', 
                'repeat_title', 'repeat_description'
            ]);
        });
    }
};
