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
        Schema::create('bids', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('auction_id');
            $table->string('name');
            $table->string('email');
            $table->decimal('amount', 10, 2);
            $table->timestamps();

            $table->foreign('auction_id')->references('id')->on('auctions')->onDelete('cascade');
            $table->index(['auction_id', 'amount']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bids');
    }
};