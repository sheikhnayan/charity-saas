<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('website_email_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('website_id');
            $table->string('mailer')->default('smtp');
            $table->string('host')->nullable();
            $table->unsignedInteger('port')->default(587);
            $table->string('encryption')->nullable(); // tls, ssl, null
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('from_address')->nullable();
            $table->string('from_name')->nullable();
            $table->string('reply_to_address')->nullable();
            $table->string('reply_to_name')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('settings')->nullable();
            $table->timestamps();

            $table->foreign('website_id')->references('id')->on('websites')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('website_email_settings');
    }
};
