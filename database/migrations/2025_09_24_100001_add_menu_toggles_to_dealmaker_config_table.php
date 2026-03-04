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
            // Menu toggle fields for each section (using shorter column names)
            $table->boolean('menu_hero')->default(false)->after('social_icon_color');
            $table->boolean('menu_about')->default(false)->after('menu_hero');
            $table->boolean('menu_services')->default(false)->after('menu_about');
            $table->boolean('menu_logos')->default(false)->after('menu_services');
            $table->boolean('menu_cases')->default(false)->after('menu_logos');
            $table->boolean('menu_difference')->default(false)->after('menu_cases');
            $table->boolean('menu_testimonials')->default(false)->after('menu_difference');
            $table->boolean('menu_solutions')->default(false)->after('menu_testimonials');
            $table->boolean('menu_cta')->default(false)->after('menu_solutions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dealmaker_config', function (Blueprint $table) {
            $table->dropColumn([
                'menu_hero', 'menu_about', 'menu_services', 'menu_logos',
                'menu_cases', 'menu_difference', 'menu_testimonials',
                'menu_solutions', 'menu_cta'
            ]);
        });
    }
};