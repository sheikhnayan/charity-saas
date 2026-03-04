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
        Schema::create('page_comments', function (Blueprint $table) {
            $table->id();
            $table->string('page_identifier', 100); // Identifies which page/component
            $table->string('component_id', 50); // Specific component instance ID
            $table->string('author_name', 100);
            $table->string('author_email', 150);
            $table->text('comment');
            $table->boolean('is_approved')->default(true);
            $table->boolean('is_anonymous')->default(false);
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 200)->nullable();
            $table->unsignedBigInteger('parent_id')->nullable(); // For reply functionality
            $table->timestamps();
            
            $table->index(['page_identifier', 'component_id']);
            $table->index(['is_approved', 'created_at']);
            $table->foreign('parent_id')->references('id')->on('page_comments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_comments');
    }
};
