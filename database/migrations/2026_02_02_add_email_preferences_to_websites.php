<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Restructures contact_emails to store individual preferences per email address
     */
    public function up(): void
    {
        // Convert existing contact_emails from simple array to new structure with preferences
        $websites = DB::table('websites')->get();
        foreach ($websites as $website) {
            if ($website->contact_emails) {
                $emails = is_array(json_decode($website->contact_emails, true)) ? json_decode($website->contact_emails, true) : [];
                $newStructure = [];
                foreach ($emails as $email) {
                    if (!empty($email)) {
                        $newStructure[] = [
                            'email' => $email,
                            'receive_contact_form' => true,  // Default enabled for backward compatibility
                            'receive_transaction_emails' => true  // Default enabled for backward compatibility
                        ];
                    }
                }
                DB::table('websites')->where('id', $website->id)->update([
                    'contact_emails' => json_encode($newStructure)
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Convert back to simple email array format
        $websites = DB::table('websites')->get();
        foreach ($websites as $website) {
            if ($website->contact_emails) {
                $data = json_decode($website->contact_emails, true);
                if (is_array($data) && isset($data[0]['email'])) {
                    $emails = array_column($data, 'email');
                    DB::table('websites')->where('id', $website->id)->update([
                        'contact_emails' => json_encode($emails)
                    ]);
                }
            }
        }
    }
};
