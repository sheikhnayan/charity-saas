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
        Schema::table('headers', function (Blueprint $table) {
            $table->string('menu_font_family')->nullable()->after('header_font_family');
            $table->string('contact_topbar_font_family')->nullable()->after('menu_font_family');
            $table->string('investor_exclusives_font_family')->nullable()->after('contact_topbar_font_family');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('headers', function (Blueprint $table) {
            $table->dropColumn(['menu_font_family', 'contact_topbar_font_family', 'investor_exclusives_font_family']);
        });
    }
};
