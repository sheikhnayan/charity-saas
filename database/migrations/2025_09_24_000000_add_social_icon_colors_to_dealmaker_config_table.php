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
            $table->string('social_icon_bg_color', 7)->default('#f31cb6')->after('button_text_color');
            $table->string('social_icon_hover_color', 7)->default('#d1179a')->after('social_icon_bg_color');
            $table->string('social_icon_color', 7)->default('#ffffff')->after('social_icon_hover_color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dealmaker_config', function (Blueprint $table) {
            $table->dropColumn(['social_icon_bg_color', 'social_icon_hover_color', 'social_icon_color']);
        });
    }
};