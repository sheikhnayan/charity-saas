<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Hotjar-style heatmap data storage
     */
    public function up(): void
    {
        Schema::create('heatmap_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id')->constrained('websites')->onDelete('cascade');
            $table->string('page_url')->index(); // Full URL or normalized path
            $table->string('page_path')->index(); // Normalized path for grouping
            
            // Heatmap types (like Hotjar)
            $table->enum('event_type', ['click', 'move', 'scroll', 'attention'])->index();
            
            // Position data
            $table->integer('x')->nullable(); // X coordinate
            $table->integer('y')->nullable(); // Y coordinate
            $table->integer('viewport_width'); // Viewport width at time of event
            $table->integer('viewport_height'); // Viewport height at time of event
            
            // Element data
            $table->string('element_selector')->nullable(); // CSS selector
            $table->string('element_text')->nullable(); // Element text content
            $table->string('element_class')->nullable(); // Element classes
            $table->string('element_id')->nullable(); // Element ID
            
            // Scroll specific
            $table->integer('scroll_depth')->nullable(); // Percentage (0-100)
            $table->integer('max_scroll')->nullable(); // Max scroll position
            
            // Attention/move specific
            $table->integer('duration_ms')->nullable(); // Time spent on area
            
            // Device context
            $table->string('device_type')->nullable();
            $table->string('session_id')->nullable()->index();
            $table->string('visitor_id')->nullable()->index();
            
            $table->timestamp('created_at')->index();
            
            // Indexes for heatmap queries
            $table->index(['website_id', 'page_path', 'event_type']);
            $table->index(['page_path', 'event_type', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('heatmap_data');
    }
};
