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
        // Add page_bg_color to auctions table
        if (Schema::hasTable('auctions')) {
            Schema::table('auctions', function (Blueprint $table) {
                if (!Schema::hasColumn('auctions', 'page_bg_color')) {
                    $table->string('page_bg_color', 7)->nullable()->default('#ffffff')->after('status');
                }
            });
        }

        // Add page_bg_color to tickets table
        if (Schema::hasTable('tickets')) {
            Schema::table('tickets', function (Blueprint $table) {
                if (!Schema::hasColumn('tickets', 'page_bg_color')) {
                    $table->string('page_bg_color', 7)->nullable()->default('#ffffff')->after('status');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove page_bg_color from auctions table
        if (Schema::hasTable('auctions') && Schema::hasColumn('auctions', 'page_bg_color')) {
            Schema::table('auctions', function (Blueprint $table) {
                $table->dropColumn('page_bg_color');
            });
        }

        // Remove page_bg_color from tickets table
        if (Schema::hasTable('tickets') && Schema::hasColumn('tickets', 'page_bg_color')) {
            Schema::table('tickets', function (Blueprint $table) {
                $table->dropColumn('page_bg_color');
            });
        }
    }
};
