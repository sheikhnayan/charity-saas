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
        Schema::create('investments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id')->constrained()->onDelete('cascade');
            $table->string('investor_name');
            $table->string('investor_email');
            $table->string('investor_phone')->nullable();
            $table->decimal('investment_amount', 12, 2);
            $table->integer('share_quantity');
            $table->string('deal_id')->nullable();
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->string('transaction_id')->nullable();
            $table->string('payment_method')->nullable();
            $table->json('investor_data')->nullable();
            $table->enum('kyc_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('aml_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['website_id', 'status']);
            $table->index(['investor_email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investments');
    }
};
