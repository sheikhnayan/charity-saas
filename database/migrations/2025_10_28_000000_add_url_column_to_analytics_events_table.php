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
        Schema::table('analytics_events', function (Blueprint $table) {
            // Add url column if missing
            if (!Schema::hasColumn('analytics_events', 'url')) {
                $table->string('url')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('analytics_events', function (Blueprint $table) {
            // Remove url column if it exists
            if (Schema::hasColumn('analytics_events', 'url')) {
                $table->dropColumn('url');
            }
        });
    }
};
