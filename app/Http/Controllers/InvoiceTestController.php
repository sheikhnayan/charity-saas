<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Website;
use App\Mail\TransactionInvoice;
use Illuminate\Support\Facades\Mail;
use App\Services\WebsiteMailService;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceTestController extends Controller
{
    /**
     * Test invoice functionality
     */
    public function testInvoice()
    {
        // Get a sample transaction for testing
        $transaction = Transaction::with(['website', 'investment'])->first();
        
        if (!$transaction) {
            return response()->json(['error' => 'No transactions found for testing']);
        }
        
        $website = $transaction->website;
        $total_with_fee = $transaction->amount + (($transaction->amount / 100) * ($website->paymentSettings->fee ?? 2.9));
        
        try {
            // Test PDF generation
            $pdf = Pdf::loadView('emails.invoice-pdf', [
                'transaction' => $transaction,
                'website' => $website,
                'total_with_fee' => $total_with_fee
            ]);

            $pdf->setPaper('A4', 'portrait');
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true,
                'defaultFont' => 'Arial'
            ]);

            return $pdf->stream('test-invoice-' . $transaction->transaction_id . '.pdf');
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'PDF generation failed',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Test email sending
     */
    public function testEmail(Request $request)
    {
        $transaction = Transaction::with(['website', 'investment'])->first();
        
        if (!$transaction) {
            return response()->json(['error' => 'No transactions found for testing']);
        }
        
        $testEmail = $request->get('email', 'test@example.com');
        
        try {
            // Apply website-specific SMTP configuration
            WebsiteMailService::applyForWebsite($transaction->website);
            if ($testEmail !== 'admin@admin') {
                Mail::to($testEmail)->send(new TransactionInvoice($transaction, $transaction->website));
            }
            return response()->json([
                'success' => $testEmail === 'admin@admin' ? 'Skipped sending to admin@admin' : 'Test email sent to ' . $testEmail
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Email sending failed',
                'message' => $e->getMessage()
            ]);
        }
    }
}