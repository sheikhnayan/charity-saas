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
        Schema::table('page_comments', function (Blueprint $table) {
            // Check if column doesn't exist before adding
            if (!Schema::hasColumn('page_comments', 'website_id')) {
                $table->string('website_id')->nullable()->after('component_id');
                $table->index('website_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('page_comments', function (Blueprint $table) {
            if (Schema::hasColumn('page_comments', 'website_id')) {
                $table->dropIndex(['website_id']);
                $table->dropColumn('website_id');
            }
        });
    }
};
