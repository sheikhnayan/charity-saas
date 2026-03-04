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
        Schema::table('tickets', function (Blueprint $table) {
            // Drop foreign key if it exists
            try {
                $table->dropForeign(['property_category_id']);
            } catch (\Exception $e) {
                // Foreign key doesn't exist, continue
            }
            
            // Drop the column
            $table->dropColumn('property_category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->foreignId('property_category_id')->nullable()->constrained('property_categories');
        });
    }
};
