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
        Schema::table('cohort_members', function (Blueprint $table) {
            $table->timestamp('joined_at')->nullable()->change();
        });
        
        Schema::table('fraud_detections', function (Blueprint $table) {
            if (!Schema::hasColumn('fraud_detections', 'action_taken')) {
                $table->string('action_taken')->nullable()->after('risk_score');
            }
        });
        
        Schema::table('ab_test_conversions', function (Blueprint $table) {
            if (!Schema::hasColumn('ab_test_conversions', 'ab_test_id')) {
                $table->unsignedBigInteger('ab_test_id')->nullable()->after('id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cohort_members', function (Blueprint $table) {
            $table->timestamp('joined_at')->nullable(false)->change();
        });
    }
};
