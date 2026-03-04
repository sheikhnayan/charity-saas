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
        Schema::create('student_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id'); // The student receiving the message
            $table->string('sender_name', 100);
            $table->string('sender_email', 150);
            $table->text('message');
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
            
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['student_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_messages');
    }
};
