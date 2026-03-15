<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Drop the old global unique constraint on email if it still exists
        // (in some environments it may have already been removed).
        try {
            Schema::table('users', function (Blueprint $table) {
                $table->dropUnique(['email']);
            });
        } catch (\Exception $e) {
            // Index did not exist — nothing to drop
        }

        // Add composite unique (email, website_id).
        // email is VARCHAR(255) and website_id is VARCHAR(255) in the schema,
        // so combining them with utf8mb4 would exceed MySQL's 1000-byte key
        // limit. We use short prefixes that are sufficient in practice:
        //   email    : 100 chars  (actual IDs stored as integers, never near 255)
        //   website_id: 20 chars  (stored as an integer value, max ~18 digits)
        DB::statement(
            'ALTER TABLE users ADD UNIQUE users_email_website_unique (email(100), website_id(20))'
        );
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE users DROP INDEX users_email_website_unique');

        Schema::table('users', function (Blueprint $table) {
            $table->unique('email');
        });
    }
};
