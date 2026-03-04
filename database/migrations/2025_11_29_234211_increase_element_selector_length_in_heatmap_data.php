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
        Schema::table('heatmap_data', function (Blueprint $table) {
            $table->text('element_selector')->nullable()->change();
            $table->text('element_text')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('heatmap_data', function (Blueprint $table) {
            $table->string('element_selector')->nullable()->change();
            $table->string('element_text')->nullable()->change();
        });
    }
};
