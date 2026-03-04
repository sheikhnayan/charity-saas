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
            $table->unsignedBigInteger('template_id')->nullable()->after('website_id');
            $table->boolean('is_template')->default(false)->after('template_id');
            $table->string('template_name')->nullable()->after('is_template');
            
            $table->foreign('template_id')->references('id')->on('page_templates')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropForeign(['template_id']);
            $table->dropColumn(['template_id', 'is_template', 'template_name']);
        });
    }
};
