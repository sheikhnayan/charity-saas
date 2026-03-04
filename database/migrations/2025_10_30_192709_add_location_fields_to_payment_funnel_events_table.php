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
        // Check if columns don't exist before adding
        if (!Schema::hasColumn('payment_funnel_events', 'country')) {
            Schema::table('payment_funnel_events', function (Blueprint $table) {
                $table->string('country')->nullable()->after('ip_address');
            });
        }
        if (!Schema::hasColumn('payment_funnel_events', 'country_code')) {
            Schema::table('payment_funnel_events', function (Blueprint $table) {
                $table->string('country_code', 2)->nullable()->after('ip_address');
            });
        }
        if (!Schema::hasColumn('payment_funnel_events', 'state')) {
            Schema::table('payment_funnel_events', function (Blueprint $table) {
                $table->string('state')->nullable()->after('ip_address');
            });
        }
        if (!Schema::hasColumn('payment_funnel_events', 'city')) {
            Schema::table('payment_funnel_events', function (Blueprint $table) {
                $table->string('city')->nullable()->after('ip_address');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_funnel_events', function (Blueprint $table) {
            $columnsToDropColumn = [];
            if (Schema::hasColumn('payment_funnel_events', 'country')) {
                $columnsToDropColumn[] = 'country';
            }
            if (Schema::hasColumn('payment_funnel_events', 'country_code')) {
                $columnsToDropColumn[] = 'country_code';
            }
            if (Schema::hasColumn('payment_funnel_events', 'state')) {
                $columnsToDropColumn[] = 'state';
            }
            if (Schema::hasColumn('payment_funnel_events', 'city')) {
                $columnsToDropColumn[] = 'city';
            }
            
            if (!empty($columnsToDropColumn)) {
                $table->dropColumn($columnsToDropColumn);
            }
        });
    }
};
