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
        Schema::table('transactions', function (Blueprint $table) {
            // IP and Package Information
            $table->string('ip_address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Remove all added columns
            $table->dropColumn([
                'ip_address',
                'package_name',
                'package_date_of_use',
                'dob',
                'note',
                'number_of_guests',
                'male_guests',
                'female_guests',
                'transportation_pickup_time',
                'transportation_address',
                'transportation_phone',
                'transportation_guest',
                'transportation_note',
                'business_company_name',
                'business_vat_number',
                'business_address',
                'business_purpose',
                'payment_first_name',
                'payment_last_name',
                'payment_phone',
                'payment_email',
                'payment_address',
                'payment_city',
                'payment_state',
                'payment_country',
                'payment_dob',
                'payment_zip_code',
                'event_id',
                'add_ons',
                'promo_code',
                'discounted_amount',
                'total_amount',
                'gratuity',
                'non_refundable_deposit',
                'total_amount_paid',
                'total_due',
                'accepted_terms_and_conditions',
                'accepted_sms',
                'payment_date',
                'payment_gateway',
                'internal_status'
            ]);
        });
    }
};