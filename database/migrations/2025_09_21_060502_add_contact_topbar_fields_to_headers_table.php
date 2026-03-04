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
        Schema::table('headers', function (Blueprint $table) {
            $table->boolean('show_contact_topbar')->default(0);
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_address')->nullable();
            $table->string('contact_cta_text')->nullable();
            $table->string('contact_cta_url')->nullable();
            $table->string('contact_topbar_bg_color')->default('#000000');
            $table->string('contact_topbar_text_color')->default('#ffffff');
            $table->string('contact_cta_bg_color')->default('#007bff');
            $table->string('contact_cta_text_color')->default('#ffffff');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('headers', function (Blueprint $table) {
            $table->dropColumn([
                'show_contact_topbar',
                'contact_phone',
                'contact_email',
                'contact_address',
                'contact_cta_text',
                'contact_cta_url',
                'contact_topbar_bg_color',
                'contact_topbar_text_color',
                'contact_cta_bg_color',
                'contact_cta_text_color'
            ]);
        });
    }
};
