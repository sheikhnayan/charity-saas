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
        Schema::create('session_events', function (Blueprint $table) {
            $table->id();
            $table->string('session_recording_id')->index(); // Foreign key to session_recordings table
            
            // Hotjar/rrweb event structure
            $table->integer('timestamp')->index(); // Milliseconds since session start
            $table->integer('event_type'); // 0=DomContentLoaded, 1=Load, 2=FullSnapshot, 3=IncrementalSnapshot, 4=Meta, 5=Custom
            
            // Event data (JSON) - stores complete rrweb event
            $table->longText('data'); // JSON: Contains HTML snapshots, mutations, mouse moves, etc.
            
            // Quick access fields (denormalized for queries)
            $table->string('action')->nullable(); // click, move, scroll, input, etc.
            $table->string('target_element')->nullable(); // CSS selector
            $table->integer('x')->nullable(); // Mouse X position
            $table->integer('y')->nullable(); // Mouse Y position
            $table->integer('scroll_x')->nullable();
            $table->integer('scroll_y')->nullable();
            
            $table->timestamp('created_at')->index();
            
            // Indexes for playback performance
            $table->index(['session_recording_id', 'timestamp']);
            $table->index(['session_recording_id', 'event_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_events');
    }
};
