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
            $table->string('topbar_background_color')->default('#1e3a8a')->after('investor_exclusives_url');
            $table->string('topbar_text_color')->default('#ffffff')->after('topbar_background_color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('headers', function (Blueprint $table) {
            $table->dropColumn(['topbar_background_color', 'topbar_text_color']);
        });
    }
};
