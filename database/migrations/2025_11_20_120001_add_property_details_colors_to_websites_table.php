<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('websites', function (Blueprint $table) {
            $table->string('property_details_bg_color')->nullable();
            $table->string('property_details_text_color')->nullable();
            $table->string('property_details_muted_color')->nullable();
            $table->string('property_details_heading_color')->nullable();
            $table->string('property_details_price_color')->nullable();
            $table->string('property_details_accent_color')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('websites', function (Blueprint $table) {
            $table->dropColumn([
                'property_details_bg_color',
                'property_details_text_color',
                'property_details_muted_color',
                'property_details_heading_color',
                'property_details_price_color',
                'property_details_accent_color',
            ]);
        });
    }
};
