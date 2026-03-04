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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('quantity')->nullable();
            $table->string('price')->nullable();
            $table->string('status')->nullable();
            $table->string('user_id')->nullable();
            $table->string('hide_until')->nullable();
            $table->string('hide_after')->nullable();
            $table->string('image')->nullable();
            $table->longText('description')->nullable();
            $table->string('website_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
