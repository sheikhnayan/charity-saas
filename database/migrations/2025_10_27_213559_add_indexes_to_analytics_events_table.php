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
            // Add indexes for better analytics query performance
            $table->index(['website_id', 'event_type', 'created_at'], 'analytics_events_website_event_date_index');
            $table->index(['website_id', 'created_at'], 'analytics_events_website_date_index');
            $table->index(['event_type', 'created_at'], 'analytics_events_event_date_index');
            $table->index('session_id', 'analytics_events_session_index');
            $table->index(['website_id', 'utm_source'], 'analytics_events_website_utm_source_index');
            $table->index(['website_id', 'utm_medium'], 'analytics_events_website_utm_medium_index');
            $table->index(['website_id', 'utm_campaign'], 'analytics_events_website_utm_campaign_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('analytics_events', function (Blueprint $table) {
            $table->dropIndex('analytics_events_website_event_date_index');
            $table->dropIndex('analytics_events_website_date_index');
            $table->dropIndex('analytics_events_event_date_index');
            $table->dropIndex('analytics_events_session_index');
            $table->dropIndex('analytics_events_website_utm_source_index');
            $table->dropIndex('analytics_events_website_utm_medium_index');
            $table->dropIndex('analytics_events_website_utm_campaign_index');
        });
    }
};
