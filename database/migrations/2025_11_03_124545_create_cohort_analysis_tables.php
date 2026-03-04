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
        // Cohorts table - Define cohort segments
        Schema::create('cohorts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id')->constrained('websites')->onDelete('cascade');
            $table->string('name'); // e.g., "First-time Donors October 2025"
            $table->string('type'); // first_time, repeat, high_value, lapsed, by_date, custom
            $table->text('description')->nullable();
            $table->json('definition'); // Cohort criteria (date_range, conditions, filters)
            $table->date('start_date')->nullable(); // For time-based cohorts
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('member_count')->default(0);
            $table->timestamps();
            
            $table->index(['website_id', 'type']);
            $table->index('is_active');
        });

        // Cohort members - Track which users belong to which cohorts
        Schema::create('cohort_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cohort_id')->constrained('cohorts')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('joined_at'); // When user entered the cohort
            $table->decimal('lifetime_value', 10, 2)->default(0);
            $table->integer('transaction_count')->default(0);
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamps();
            
            $table->unique(['cohort_id', 'user_id']);
            $table->index('joined_at');
        });

        // Cohort retention metrics - Track cohort performance over time
        Schema::create('cohort_retention', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cohort_id')->constrained('cohorts')->onDelete('cascade');
            $table->integer('period'); // Days since cohort start (0, 1, 7, 30, 60, 90)
            $table->date('period_date');
            $table->integer('retained_users')->default(0);
            $table->decimal('retention_rate', 5, 2)->default(0); // Percentage
            $table->decimal('revenue', 10, 2)->default(0);
            $table->integer('transactions')->default(0);
            $table->timestamps();
            
            $table->unique(['cohort_id', 'period']);
            $table->index('period_date');
        });

        // Cohort comparisons - Save comparison analysis results
        Schema::create('cohort_comparisons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id')->constrained('websites')->onDelete('cascade');
            $table->string('name');
            $table->json('cohort_ids'); // Array of cohort IDs being compared
            $table->json('metrics'); // Comparison results
            $table->timestamp('compared_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cohort_comparisons');
        Schema::dropIfExists('cohort_retention');
        Schema::dropIfExists('cohort_members');
        Schema::dropIfExists('cohorts');
    }
};
