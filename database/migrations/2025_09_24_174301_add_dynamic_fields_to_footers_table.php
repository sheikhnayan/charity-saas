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
        Schema::table('footers', function (Blueprint $table) {
            $table->text('disclaimer_text')->nullable()->after('blue_sky');
            $table->text('description_text')->nullable()->after('disclaimer_text');
            $table->string('background_image_desktop')->nullable()->after('description_text');
            $table->string('background_image_mobile')->nullable()->after('background_image_desktop');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('footers', function (Blueprint $table) {
            $table->dropColumn(['disclaimer_text', 'description_text', 'background_image_desktop', 'background_image_mobile']);
        });
    }
};
