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
        Schema::create('website_payment_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('website_id');
            $table->string('payment_method')->default('authorize'); // 'stripe' or 'authorize'
            
            // Stripe credentials (encrypted)
            $table->text('stripe_publishable_key')->nullable();
            $table->text('stripe_secret_key')->nullable();
            $table->text('stripe_webhook_secret')->nullable();
            
            // Authorize.net credentials (encrypted)
            $table->text('authorize_login_id')->nullable();
            $table->text('authorize_transaction_key')->nullable();
            $table->boolean('authorize_sandbox')->default(true);
            
            // Status and configuration
            $table->boolean('is_active')->default(true);
            $table->json('settings')->nullable(); // Additional gateway-specific settings
            
            $table->timestamps();
            
            // Foreign key constraint
            $table->foreign('website_id')->references('id')->on('websites')->onDelete('cascade');
            
            // Ensure one payment setting per website
            $table->unique('website_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_payment_settings');
    }
};
