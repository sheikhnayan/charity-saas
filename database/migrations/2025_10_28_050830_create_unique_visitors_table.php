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
        Schema::create('unique_visitors', function (Blueprint $table) {
            $table->id();
            $table->string('visitor_id')->unique(); // Cookie-based unique ID (Shopify approach)
            $table->string('session_id')->nullable(); // Current session ID
            $table->unsignedBigInteger('website_id')->nullable();
            $table->string('ip_address', 45)->nullable(); // IPv4/IPv6 support
            $table->text('user_agent')->nullable();
            $table->string('device_type')->nullable(); // mobile, desktop, tablet
            $table->string('browser')->nullable();
            $table->string('operating_system')->nullable();
            $table->text('referrer')->nullable();
            $table->text('landing_page')->nullable();
            $table->string('country', 2)->nullable(); // ISO country code
            $table->timestamp('visited_at')->nullable(); // First visit time
            $table->timestamp('last_seen_at')->nullable(); // Most recent activity
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['visitor_id', 'website_id']);
            $table->index(['website_id', 'visited_at']);
            $table->index('session_id');
            
            // Foreign key
            $table->foreign('website_id')->references('id')->on('websites')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unique_visitors');
    }
};
