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
        Schema::create('footers', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable();
            $table->string('website_id')->nullable();
            $table->string('background')->nullable();
            $table->string('status')->nullable();
            $table->string('color')->nullable();
            $table->string('menu')->nullable();
            $table->string('message')->nullable();
            $table->string('copy_right')->nullable();
            $table->string('social')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('twitter')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('youtube')->nullable();
            $table->string('pinterest')->nullable();
            $table->string('tiktok')->nullable();
            $table->string('blue_sky')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('footers');
    }
};
