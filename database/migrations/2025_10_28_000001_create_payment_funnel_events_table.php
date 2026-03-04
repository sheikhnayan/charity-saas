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
        Schema::create('payment_funnel_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id')->constrained('websites')->onDelete('cascade');
            $table->string('session_id');
            $table->string('funnel_step'); // form_view, amount_entered, personal_info_started, personal_info_completed, payment_initiated, payment_completed, payment_failed
            $table->string('form_type')->nullable(); // student, general, ticket, auction, investment
            $table->bigInteger('user_id')->nullable(); // student ID for student donations
            $table->decimal('amount', 10, 2)->nullable();
            $table->json('form_data')->nullable(); // Capture form field data
            $table->string('payment_method')->nullable(); // stripe, authorize_net
            $table->string('transaction_id')->nullable();
            $table->string('error_message')->nullable(); // For failed payments
            $table->timestamp('completed_at')->nullable(); // When step was completed
            $table->string('referrer_url')->nullable();
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->string('device_type')->nullable();
            $table->string('browser')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['website_id', 'session_id']);
            $table->index(['website_id', 'funnel_step', 'created_at']);
            $table->index(['form_type', 'created_at']);
            $table->index('transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_funnel_events');
    }
};