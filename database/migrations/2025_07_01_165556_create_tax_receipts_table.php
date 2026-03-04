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
        Schema::create('tax_receipts', function (Blueprint $table) {
            $table->id();
            $table->string('organization')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('website')->nullable();
            $table->string('charitable_id')->nullable();
            $table->string('reference')->nullable();
            $table->string('number_prefix')->nullable();
            $table->string('starting_number')->nullable();
            $table->string('logo')->nullable();
            $table->string('signature')->nullable();
            $table->string('website_id')->nullable();
            $table->string('user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_receipts');
    }
};
