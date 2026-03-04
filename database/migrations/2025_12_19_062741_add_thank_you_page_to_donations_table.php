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
        Schema::table('donations', function (Blueprint $table) {
            $table->string('thank_you_page_url')->nullable()->after('referrer_url');
            $table->string('thank_you_page_type')->nullable()->after('thank_you_page_url'); // 'default', 'custom', 'student_profile', etc.
            $table->timestamp('thank_you_page_shown_at')->nullable()->after('thank_you_page_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn(['thank_you_page_url', 'thank_you_page_type', 'thank_you_page_shown_at']);
        });
    }
};
