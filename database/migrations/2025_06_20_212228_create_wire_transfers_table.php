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
        Schema::create('wire_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->string('paybale_to')->nullable();
            $table->string('send_check_to')->nullable();
            $table->string('address_to_send')->nullable();
            $table->string('city_to_send')->nullable();
            $table->string('country_to_send')->nullable();
            $table->string('state_to_send')->nullable();
            $table->string('zip_to_send')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wire_transfers');
    }
};
