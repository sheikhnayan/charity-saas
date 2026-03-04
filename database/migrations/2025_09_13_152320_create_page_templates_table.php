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
        Schema::create('page_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('state'); // Page content state
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('background_color')->nullable();
            $table->string('preview_image')->nullable(); // Template preview screenshot
            $table->boolean('is_public')->default(true); // Whether template is available to all users
            $table->integer('usage_count')->default(0); // Track how many times template is used
            $table->string('category')->nullable(); // Template category (e.g., 'landing', 'about', 'contact')
            $table->string('created_by')->nullable(); // User who created the template
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_templates');
    }
};
