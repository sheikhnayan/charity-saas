<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailTestController extends Controller
{
    public function testEmail(Request $request)
    {
        $testEmail = $request->get('email', 'nman0171@gmail.com');
        
        try {
                if ($transaction && $transaction->website) { \App\Services\WebsiteMailService::applyForWebsite($transaction->website); }
            if ($testEmail !== 'admin@admin') {
                Mail::raw('Test email from Laravel application. This is to verify SMTP configuration is working correctly.', function ($message) use ($testEmail) {
                    $message->to($testEmail)
                           ->subject('Laravel SMTP Test - ' . now()->format('Y-m-d H:i:s'))
                           ->from(config('mail.from.address'), config('mail.from.name'));
                });
            }
            
            return response()->json([
                'success' => true,
                'message' => $testEmail === 'admin@admin' ? "Skipped sending to admin@admin" : "Test email sent successfully to {$testEmail}",
                'smtp_config' => [
                    'host' => config('mail.mailers.smtp.host'),
                    'port' => config('mail.mailers.smtp.port'),
                    'encryption' => config('mail.mailers.smtp.encryption'),
                    'username' => config('mail.mailers.smtp.username'),
                    'from_address' => config('mail.from.address')
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'smtp_config' => [
                    'host' => config('mail.mailers.smtp.host'),
                    'port' => config('mail.mailers.smtp.port'),
                    'encryption' => config('mail.mailers.smtp.encryption'),
                    'username' => config('mail.mailers.smtp.username'),
                    'from_address' => config('mail.from.address')
                ]
            ], 500);
        }
    }
    
    public function testInvoiceEmail(Request $request)
    {
        $testEmail = $request->get('email', 'nman0171@gmail.com');
        
        // Get a sample transaction for testing
        $transaction = \App\Models\Transaction::with(['website', 'investment'])->first();
        
        if (!$transaction) {
            return response()->json(['error' => 'No transactions found for testing'], 404);
        }
        
        try {
                if ($transaction && $transaction->website) { \App\Services\WebsiteMailService::applyForWebsite($transaction->website); }
            if ($testEmail !== 'admin@admin') {
                Mail::to($testEmail)->send(new \App\Mail\TransactionInvoice($transaction, $transaction->website));
            }
            
            return response()->json([
                'success' => true,
                'message' => $testEmail === 'admin@admin' ? "Skipped sending to admin@admin" : "Invoice email sent successfully to {$testEmail}",
                'transaction_id' => $transaction->transaction_id
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'transaction_id' => $transaction->transaction_id ?? 'unknown'
            ], 500);
        }
    }
}