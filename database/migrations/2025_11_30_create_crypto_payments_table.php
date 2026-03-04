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
        Schema::create('crypto_payments', function (Blueprint $table) {
            $table->id();
            $table->string('charge_code')->unique();
            $table->string('charge_id')->nullable();
            $table->string('payment_type'); // donation, ticket, auction, investment
            $table->unsignedBigInteger('reference_id'); // ID of donation/ticket/etc
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('website_id')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 10)->default('USD');
            $table->string('status')->default('pending'); // pending, completed, failed, delayed, resolved
            $table->string('hosted_url')->nullable();
            $table->text('session_id')->nullable();
            $table->json('charge_data')->nullable(); // Store full Coinbase charge object
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            $table->index('charge_code');
            $table->index('payment_type');
            $table->index('reference_id');
            $table->index('user_id');
            $table->index('website_id');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crypto_payments');
    }
};
