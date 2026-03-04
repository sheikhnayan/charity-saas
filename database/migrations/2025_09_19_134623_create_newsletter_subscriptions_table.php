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
        Schema::create('newsletter_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->unsignedBigInteger('website_id');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamp('subscribed_at')->useCurrent();
            $table->timestamps();
            
            // Add foreign key constraint
            $table->foreign('website_id')->references('id')->on('websites')->onDelete('cascade');
            
            // Ensure unique email per website
            $table->unique(['email', 'website_id']);
            
            // Add index for performance
            $table->index(['website_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newsletter_subscriptions');
    }
};
