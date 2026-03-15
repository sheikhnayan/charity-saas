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
        Schema::table('headers', function (Blueprint $table) {
            $table->json('builder_state')->nullable()->after('auth_button_text_color');
            $table->boolean('use_builder')->default(false)->after('builder_state');
        });

        Schema::table('footers', function (Blueprint $table) {
            $table->json('builder_state')->nullable()->after('contact_email_size');
            $table->boolean('use_builder')->default(false)->after('builder_state');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('headers', function (Blueprint $table) {
            $table->dropColumn(['builder_state', 'use_builder']);
        });

        Schema::table('footers', function (Blueprint $table) {
            $table->dropColumn(['builder_state', 'use_builder']);
        });
    }
};
