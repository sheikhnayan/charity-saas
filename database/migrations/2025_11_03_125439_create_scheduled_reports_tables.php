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
        // Scheduled Reports table
        Schema::create('scheduled_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id')->constrained('websites')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('report_type'); // analytics, donations, conversions, cohort, fraud, ab_test
            $table->json('configuration'); // Report-specific settings, filters, date ranges
            $table->string('frequency'); // daily, weekly, monthly, quarterly
            $table->string('format'); // pdf, csv, excel, json
            $table->json('recipients'); // Array of email addresses
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_run_at')->nullable();
            $table->timestamp('next_run_at')->nullable();
            $table->timestamps();
            
            $table->index(['website_id', 'is_active']);
            $table->index('next_run_at');
        });

        // Report Executions table - Track report generation history
        Schema::create('report_executions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scheduled_report_id')->constrained('scheduled_reports')->onDelete('cascade');
            $table->string('status'); // pending, processing, completed, failed
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->string('file_path')->nullable(); // Path to generated report file
            $table->integer('file_size')->nullable(); // In bytes
            $table->json('execution_data')->nullable(); // Stats, row counts, etc.
            $table->text('error_message')->nullable();
            $table->boolean('email_sent')->default(false);
            $table->timestamps();
            
            $table->index(['scheduled_report_id', 'status']);
            $table->index('completed_at');
        });

        // Report Templates table - Reusable report configurations
        Schema::create('report_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id')->constrained('websites')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('report_type');
            $table->json('default_configuration');
            $table->boolean('is_public')->default(false); // Can be used by all users
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index(['website_id', 'report_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_templates');
        Schema::dropIfExists('report_executions');
        Schema::dropIfExists('scheduled_reports');
    }
};
