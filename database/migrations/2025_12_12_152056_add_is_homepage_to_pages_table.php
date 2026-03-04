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
        Schema::table('pages', function (Blueprint $table) {
            $table->boolean('is_homepage')->default(false)->after('default');
            
            // Add index for faster homepage queries
            $table->index(['website_id', 'is_homepage']);
        });
        
        // Migrate existing default pages to is_homepage
        DB::table('pages')->where('default', 1)->update(['is_homepage' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropIndex(['website_id', 'is_homepage']);
            $table->dropColumn('is_homepage');
        });
    }
};
