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
        Schema::create('page_views', function (Blueprint $table) {
            $table->id();
            $table->string('visitor_id'); // Links to unique_visitors.visitor_id
            $table->string('session_id'); // Session identifier
            $table->unsignedBigInteger('website_id')->nullable();
            $table->text('url'); // Full URL visited
            $table->string('page_title')->nullable(); // Page title if available
            $table->text('referrer')->nullable(); // Where they came from
            $table->timestamp('viewed_at'); // When page was viewed
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['visitor_id', 'website_id']);
            $table->index(['website_id', 'viewed_at']);
            $table->index('session_id');
            $table->index('viewed_at');
            
            // Foreign keys
            $table->foreign('website_id')->references('id')->on('websites')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_views');
    }
};
