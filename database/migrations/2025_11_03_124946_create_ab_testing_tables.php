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
        // A/B Tests table - Define tests
        Schema::create('ab_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id')->constrained('websites')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('test_type'); // button_color, headline, layout, price, donation_amount, etc.
            $table->json('variants'); // Array of variant definitions [{"name": "A", "config": {...}}, {"name": "B", ...}]
            $table->json('traffic_split'); // Traffic allocation [{"variant": "A", "percentage": 50}, ...]
            $table->string('status')->default('draft'); // draft, running, paused, completed
            $table->string('goal_metric'); // conversion_rate, revenue, clicks, time_on_page
            $table->decimal('goal_value', 10, 2)->nullable(); // Target value if applicable
            $table->integer('min_sample_size')->default(100); // Minimum conversions needed
            $table->decimal('confidence_level', 5, 2)->default(95.00); // Required confidence %
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->foreignId('winning_variant_id')->nullable();
            $table->timestamps();
            
            $table->index(['website_id', 'status']);
            $table->index('started_at');
        });

        // A/B Test Variants table - Individual variants
        Schema::create('ab_test_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->constrained('ab_tests')->onDelete('cascade');
            $table->string('name'); // A, B, C, Control
            $table->json('configuration'); // Variant-specific settings
            $table->boolean('is_control')->default(false);
            $table->integer('traffic_percentage')->default(50);
            $table->timestamps();
            
            $table->unique(['test_id', 'name']);
        });

        // A/B Test Assignments table - Track user assignments
        Schema::create('ab_test_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->constrained('ab_tests')->onDelete('cascade');
            $table->foreignId('variant_id')->constrained('ab_test_variants')->onDelete('cascade');
            $table->string('user_identifier'); // user_id, session_id, or cookie
            $table->string('identifier_type'); // user, session, cookie
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('session_id')->nullable();
            $table->timestamp('assigned_at');
            $table->timestamps();
            
            $table->index(['test_id', 'user_identifier']);
            $table->index('variant_id');
            $table->index('assigned_at');
        });

        // A/B Test Conversions table - Track conversion events
        Schema::create('ab_test_conversions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->constrained('ab_tests')->onDelete('cascade');
            $table->foreignId('variant_id')->constrained('ab_test_variants')->onDelete('cascade');
            $table->foreignId('assignment_id')->constrained('ab_test_assignments')->onDelete('cascade');
            $table->string('conversion_type'); // donation, click, signup, etc.
            $table->decimal('conversion_value', 10, 2)->nullable();
            $table->json('metadata')->nullable(); // Additional conversion data
            $table->timestamp('converted_at');
            $table->timestamps();
            
            $table->index(['test_id', 'variant_id']);
            $table->index('converted_at');
        });

        // A/B Test Results table - Statistical analysis snapshots
        Schema::create('ab_test_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->constrained('ab_tests')->onDelete('cascade');
            $table->foreignId('variant_id')->constrained('ab_test_variants')->onDelete('cascade');
            $table->integer('impressions')->default(0); // How many users saw this variant
            $table->integer('conversions')->default(0);
            $table->decimal('conversion_rate', 5, 2)->default(0);
            $table->decimal('total_revenue', 10, 2)->default(0);
            $table->decimal('avg_revenue_per_user', 10, 2)->default(0);
            $table->decimal('confidence_level', 5, 2)->nullable(); // Statistical confidence
            $table->decimal('p_value', 10, 8)->nullable();
            $table->boolean('is_significant')->default(false);
            $table->timestamp('calculated_at');
            $table->timestamps();
            
            $table->unique(['test_id', 'variant_id', 'calculated_at']);
        });

        // A/B Test Events table - Detailed event tracking
        Schema::create('ab_test_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->constrained('ab_tests')->onDelete('cascade');
            $table->foreignId('variant_id')->constrained('ab_test_variants')->onDelete('cascade');
            $table->foreignId('assignment_id')->nullable()->constrained('ab_test_assignments')->onDelete('cascade');
            $table->string('event_type'); // view, click, interaction, bounce
            $table->string('page_url')->nullable();
            $table->json('event_data')->nullable();
            $table->timestamp('event_at');
            $table->timestamps();
            
            $table->index(['test_id', 'event_type']);
            $table->index('event_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ab_test_events');
        Schema::dropIfExists('ab_test_results');
        Schema::dropIfExists('ab_test_conversions');
        Schema::dropIfExists('ab_test_assignments');
        Schema::dropIfExists('ab_test_variants');
        Schema::dropIfExists('ab_tests');
    }
};
