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
        // Add investment_disclaimer to footers table
        Schema::table('footers', function (Blueprint $table) {
            if (!Schema::hasColumn('footers', 'investment_disclaimer')) {
                $table->text('investment_disclaimer')->nullable()->after('description_text');
            }
        });

        // Migrate data from websites to footers
        $websites = DB::table('websites')
            ->whereNotNull('investment_disclaimer')
            ->get();

        foreach ($websites as $website) {
            DB::table('footers')
                ->where('website_id', $website->id)
                ->update(['investment_disclaimer' => $website->investment_disclaimer]);
        }

        // Remove investment_disclaimer from websites table
        Schema::table('websites', function (Blueprint $table) {
            if (Schema::hasColumn('websites', 'investment_disclaimer')) {
                $table->dropColumn('investment_disclaimer');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add investment_disclaimer back to websites table
        Schema::table('websites', function (Blueprint $table) {
            if (!Schema::hasColumn('websites', 'investment_disclaimer')) {
                $table->text('investment_disclaimer')->nullable();
            }
        });

        // Migrate data back from footers to websites
        $footers = DB::table('footers')
            ->whereNotNull('investment_disclaimer')
            ->get();

        foreach ($footers as $footer) {
            DB::table('websites')
                ->where('id', $footer->website_id)
                ->update(['investment_disclaimer' => $footer->investment_disclaimer]);
        }

        // Remove investment_disclaimer from footers table
        Schema::table('footers', function (Blueprint $table) {
            if (Schema::hasColumn('footers', 'investment_disclaimer')) {
                $table->dropColumn('investment_disclaimer');
            }
        });
    }
};
