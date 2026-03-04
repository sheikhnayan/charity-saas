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
        Schema::table('fraud_rules', function (Blueprint $table) {
            if (!Schema::hasColumn('fraud_rules', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('action');
            }
            if (!Schema::hasColumn('fraud_rules', 'priority')) {
                $table->integer('priority')->default(0)->after('is_active');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fraud_rules', function (Blueprint $table) {
            $table->dropColumn(['is_active', 'priority']);
        });
    }
};
