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
            // Add columns only if they don't exist
            if (!Schema::hasColumn('analytics_events', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable();
            }
            
            if (!Schema::hasColumn('analytics_events', 'referrer_url')) {
                $table->string('referrer_url')->nullable();
            }
            
            if (!Schema::hasColumn('analytics_events', 'meta_data')) {
                $table->json('meta_data')->nullable();
            }
            
            if (!Schema::hasColumn('analytics_events', 'method')) {
                $table->string('method')->nullable();
            }
            
            if (!Schema::hasColumn('analytics_events', 'platform')) {
                $table->string('platform')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('analytics_events', function (Blueprint $table) {
            // Remove columns if they exist
            $columnsToRemove = ['user_id', 'referrer_url', 'meta_data', 'method', 'platform'];
            
            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('analytics_events', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
