<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Hotjar-style session recording structure
     */
    public function up(): void
    {
        Schema::create('session_recordings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id')->constrained('websites')->onDelete('cascade');
            $table->string('session_id')->index();
            $table->string('visitor_id')->nullable()->index();
            $table->bigInteger('user_id')->nullable()->index();
            
            // Session metadata
            $table->string('url')->nullable();
            $table->string('page_title')->nullable();
            $table->integer('duration_ms')->default(0); // Total session duration
            $table->integer('viewport_width')->nullable();
            $table->integer('viewport_height')->nullable();
            
            // Device & browser info
            $table->string('device_type')->nullable();
            $table->string('browser')->nullable();
            $table->string('os')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('country')->nullable();
            $table->string('country_code', 2)->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            
            // Recording status
            $table->enum('status', ['recording', 'completed', 'archived'])->default('recording');
            $table->boolean('has_rage_clicks')->default(false);
            $table->boolean('has_errors')->default(false);
            $table->integer('event_count')->default(0);
            
            // Hotjar-style flags
            $table->boolean('is_starred')->default(false);
            $table->text('notes')->nullable();
            $table->json('tags')->nullable();
            
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['website_id', 'created_at']);
            $table->index(['session_id', 'website_id']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_recordings');
    }
};
