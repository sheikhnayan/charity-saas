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
        // Check if tables already exist before creating them (due to duplicate migration cleanup)
        if (!Schema::hasTable('user_sessions')) {
            Schema::create('user_sessions', function (Blueprint $table) {
                $table->id();
                $table->string('session_id')->unique();
                $table->foreignId('website_id')->constrained('websites')->onDelete('cascade');
                $table->string('user_agent')->nullable();
                $table->string('ip_address')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('analytics_events')) {
            Schema::create('analytics_events', function (Blueprint $table) {
                $table->id();
                $table->foreignId('website_id')->constrained('websites')->onDelete('cascade');
                $table->string('session_id');
                $table->string('event_type');
                $table->string('page_url');
                $table->string('user_agent')->nullable();
                $table->string('ip_address')->nullable();
                $table->string('referrer')->nullable();
                $table->timestamps();

                $table->foreign('session_id')
                      ->references('session_id')
                      ->on('user_sessions')
                      ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analytics_events');
        Schema::dropIfExists('user_sessions');
    }
};
