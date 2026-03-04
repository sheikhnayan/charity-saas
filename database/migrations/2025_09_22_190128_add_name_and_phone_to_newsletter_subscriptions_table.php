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
        Schema::table('newsletter_subscriptions', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('email');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('phone')->nullable()->after('last_name');
            $table->string('country_code', 5)->default('+1')->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('newsletter_subscriptions', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name', 'phone', 'country_code']);
        });
    }
};
