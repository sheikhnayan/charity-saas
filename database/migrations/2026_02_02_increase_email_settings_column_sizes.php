<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('website_email_settings', function (Blueprint $table) {
            // Change string columns to text to accommodate encrypted values
            $table->text('username')->change();
            $table->text('password')->change();
        });
    }

    public function down(): void
    {
        Schema::table('website_email_settings', function (Blueprint $table) {
            // Revert to string type
            $table->string('username')->nullable()->change();
            $table->string('password')->nullable()->change();
        });
    }
};
