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
        Schema::table('payment_funnel_events', function (Blueprint $table) {
            $table->string('visitor_id')->nullable()->after('session_id');
            $table->index('visitor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_funnel_events', function (Blueprint $table) {
            $table->dropIndex(['visitor_id']);
            $table->dropColumn('visitor_id');
        });
    }
};
