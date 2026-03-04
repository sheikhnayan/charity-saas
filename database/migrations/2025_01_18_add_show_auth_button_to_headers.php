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
            $table->boolean('show_auth_button')->default(false)->after('invest_now_button_text');
            $table->string('auth_button_text')->default('Login / Register')->after('show_auth_button');
            $table->string('auth_button_bg_color')->default('#007bff')->after('auth_button_text');
            $table->string('auth_button_text_color')->default('#ffffff')->after('auth_button_bg_color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('headers', function (Blueprint $table) {
            $table->dropColumn(['show_auth_button', 'auth_button_text', 'auth_button_bg_color', 'auth_button_text_color']);
        });
    }
};
