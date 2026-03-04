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
            $table->string('header_font_family')->nullable()->after('invest_now_button_text');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('headers', function (Blueprint $table) {
            $table->dropColumn('header_font_family');
        });
    }
};
