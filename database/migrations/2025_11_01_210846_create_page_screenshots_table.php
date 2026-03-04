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
        Schema::create('page_screenshots', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('website_id');
            $table->string('page_url', 500);
            $table->string('page_path', 191); // Reduced for MySQL index limit
            $table->string('screenshot_path', 500);
            $table->integer('viewport_width')->default(1920);
            $table->integer('viewport_height')->default(1080);
            $table->string('device_type', 20)->default('desktop');
            $table->timestamps();
            
            $table->foreign('website_id')->references('id')->on('websites')->onDelete('cascade');
            $table->index('website_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_screenshots');
    }
};
