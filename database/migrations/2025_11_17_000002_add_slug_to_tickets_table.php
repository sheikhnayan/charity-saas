<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Ticket;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Only add slug column if it doesn't exist
        if (!Schema::hasColumn('tickets', 'slug')) {
            Schema::table('tickets', function (Blueprint $table) {
                $table->string('slug')->nullable()->after('name');
            });
        }

        // Generate slugs for existing tickets that don't have one
        Ticket::whereNull('slug')->orWhere('slug', '')->each(function ($ticket) {
            $slug = Str::slug($ticket->name);
            $originalSlug = $slug;
            $count = 1;
            
            // Ensure uniqueness
            while (Ticket::where('slug', $slug)->where('id', '!=', $ticket->id)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }
            
            $ticket->slug = $slug;
            $ticket->save();
        });

        // Check if unique index already exists using raw SQL
        $indexes = DB::select("SHOW INDEX FROM tickets WHERE Column_name = 'slug' AND Non_unique = 0");
        
        if (empty($indexes)) {
            Schema::table('tickets', function (Blueprint $table) {
                $table->unique('slug');
            });
        }

        // Make slug non-nullable after generation if column exists
        if (Schema::hasColumn('tickets', 'slug')) {
            Schema::table('tickets', function (Blueprint $table) {
                $table->string('slug')->nullable(false)->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('tickets', 'slug')) {
            Schema::table('tickets', function (Blueprint $table) {
                $table->dropUnique(['slug']);
                $table->dropColumn('slug');
            });
        }
    }
};
