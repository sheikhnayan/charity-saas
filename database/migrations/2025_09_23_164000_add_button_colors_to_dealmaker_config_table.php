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
            $table->string('button_primary_color', 7)->default('#f31cb6')->after('section_background_colors');
            $table->string('button_hover_color', 7)->default('#d1179a')->after('button_primary_color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dealmaker_config', function (Blueprint $table) {
            $table->dropColumn(['button_primary_color', 'button_hover_color']);
        });
    }
};