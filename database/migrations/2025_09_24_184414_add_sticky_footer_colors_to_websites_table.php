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
        Schema::table('websites', function (Blueprint $table) {
            $table->string('sticky_footer_button_bg')->nullable()->comment('Sticky footer button background color');
            $table->string('sticky_footer_button_text')->nullable()->comment('Sticky footer button text color');
            $table->string('sticky_footer_text_color')->nullable()->comment('Sticky footer outside text color');
            $table->string('sticky_footer_bg_color')->nullable()->comment('Sticky footer background color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('websites', function (Blueprint $table) {
            $table->dropColumn(['sticky_footer_button_bg', 'sticky_footer_button_text', 'sticky_footer_text_color', 'sticky_footer_bg_color']);
        });
    }
};
