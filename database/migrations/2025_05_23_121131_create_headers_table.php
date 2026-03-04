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
        Schema::create('headers', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable();
            $table->string('website_id')->nullable();
            $table->string('background')->nullable();
            $table->string('status')->nullable();
            $table->string('color')->nullable();
            $table->string('floating')->default(0);
            $table->string('menu')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('headers');
    }
};
