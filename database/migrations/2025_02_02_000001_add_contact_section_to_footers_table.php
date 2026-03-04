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
            // Contact section heading customization
            $table->string('contact_heading')->default('Contact Us')->nullable()->after('investment_disclaimer');
            $table->string('contact_heading_color')->default('#ffffff')->nullable()->after('contact_heading');
            $table->string('contact_heading_font')->default('outfit')->nullable()->after('contact_heading_color');
            $table->string('contact_heading_size')->default('14px')->nullable()->after('contact_heading_font');
            
            // Contact email customization
            $table->string('contact_email_color')->default('#ffffff')->nullable()->after('contact_heading_size');
            $table->string('contact_email_font')->default('outfit')->nullable()->after('contact_email_color');
            $table->string('contact_email_size')->default('14px')->nullable()->after('contact_email_font');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('footers', function (Blueprint $table) {
            $table->dropColumn([
                'contact_heading',
                'contact_heading_color',
                'contact_heading_font',
                'contact_heading_size',
                'contact_email_color',
                'contact_email_font',
                'contact_email_size'
            ]);
        });
    }
};
