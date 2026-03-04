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
        Schema::table('footers', function (Blueprint $table) {
            // Add page ID fields for privacy, refund, and terms pages
            $table->unsignedBigInteger('privacy_page_id')->nullable()->after('privacy');
            $table->unsignedBigInteger('refund_page_id')->nullable()->after('privacy_page_id');
            $table->unsignedBigInteger('terms_page_id')->nullable()->after('refund_page_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('footers', function (Blueprint $table) {
            $table->dropColumn(['privacy_page_id', 'refund_page_id', 'terms_page_id']);
        });
    }
};
