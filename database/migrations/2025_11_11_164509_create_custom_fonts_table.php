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
        Schema::create('custom_fonts', function (Blueprint $table) {
            $table->id();
            $table->string('font_name'); // Display name
            $table->string('font_family'); // CSS font-family name (unique)
            $table->string('file_path'); // Path to font file
            $table->string('file_format'); // woff2, woff, ttf, otf
            $table->integer('file_size')->nullable(); // File size in bytes
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique('font_family');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_fonts');
    }
};
