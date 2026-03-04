<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Transaction;
use App\Models\Website;
use Illuminate\Database\Eloquent\Collection;
use Barryvdh\DomPDF\Facade\Pdf;

class TransactionInvoice extends Mailable
{
    use Queueable, SerializesModels;

    public $transactions;
    public $website;
    public $isCustomer;

    /**
     * Create a new message instance.
     * Accepts either a single Transaction or a Collection of Transactions
     */
    public function __construct($transactions, Website $website = null, $isCustomer = true)
    {
        // Convert single transaction to collection for unified handling
        if ($transactions instanceof Transaction) {
            $this->transactions = collect([$transactions]);
        } else if ($transactions instanceof Collection) {
            $this->transactions = $transactions;
        } else {
            // Handle if it's an array or other collection type
            $this->transactions = collect((array) $transactions);
        }
        
        $this->website = $website;
        $this->isCustomer = $isCustomer;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        // Apply website-specific email settings
        if ($this->website) {
            \App\Services\WebsiteMailService::applyForWebsite($this->website);
        }

        $fee_percentage = 2.9; // Default fee
        if ($this->website && $this->website->paymentSettings) {
            $fee_percentage = $this->website->paymentSettings->fee ?? 2.9;
        }
        
        // Calculate totals for all transactions
        $totalAmount = 0;
        $totalFee = 0;
        $totalTip = 0;
        
        foreach ($this->transactions as $transaction) {
            $totalAmount += $transaction->amount;
            $totalFee += $transaction->fee ?? 0;
            $totalTip += $transaction->tip_amount ?? 0;
        }
        
        $grandTotal = $totalAmount + $totalFee + $totalTip;
        
        // Get the first transaction for basic details (they all share same transaction_id and email)
        $firstTransaction = $this->transactions->first();
        $subject = $this->getCustomSubject($firstTransaction);
        
        // Generate PDF with all items
        $pdf = Pdf::loadView('emails.invoice-pdf', [
            'transactions' => $this->transactions,
            'website' => $this->website,
            'totalAmount' => $totalAmount,
            'totalFee' => $totalFee,
            'totalTip' => $totalTip,
            'grandTotal' => $grandTotal,
            'fee_percentage' => $fee_percentage,
            'isCustomer' => $this->isCustomer
        ]);

        // Set PDF options for better rendering
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,
            'defaultFont' => 'Arial'
        ]);

        $message = $this->subject($subject)
                    ->view('emails.transaction-invoice')
                    ->with([
                        'transactions' => $this->transactions,
                        'website' => $this->website,
                        'totalAmount' => $totalAmount,
                        'totalFee' => $totalFee,
                        'totalTip' => $totalTip,
                        'grandTotal' => $grandTotal,
                        'fee_percentage' => $fee_percentage,
                        'isCustomer' => $this->isCustomer
                    ])
                    ->attachData(
                        $pdf->output(),
                        $this->getFileName($firstTransaction),
                        [
                            'mime' => 'application/pdf',
                        ]
                    );

        // Apply from address from website settings if available
        if ($this->website && $this->website->emailSettings && $this->website->emailSettings->from_address) {
            $message->from(
                $this->website->emailSettings->from_address,
                $this->website->emailSettings->from_name ?? $this->website->name
            );
        } else if ($this->website) {
            $message->from(config('mail.from.address', 'noreply@' . $this->website->domain), $this->website->name);
        }

        // Apply reply-to if configured
        if (config('mail.reply_to.address')) {
            $message->replyTo(config('mail.reply_to.address'), config('mail.reply_to.name'));
        }

        return $message;
    }
    
    /**
     * Get custom subject based on transaction type
     */
    private function getCustomSubject($transaction = null)
    {
        $transaction = $transaction ?? $this->transactions->first();
        $transactionId = $transaction->transaction_id;
        $websiteName = $this->website ? $this->website->name : 'Our Platform';
        $itemCount = $this->transactions->count();
        
        // Add item count to subject if multiple items
        $itemInfo = $itemCount > 1 ? " ({$itemCount} items)" : '';
        
        switch($transaction->type) {
            case 'student':
            case 'general':
                return "Donation Receipt #{$transactionId}{$itemInfo} - {$websiteName}";
                
            case 'ticket':
                return "Ticket Purchase Confirmation #{$transactionId}{$itemInfo} - {$websiteName}";
                
            case 'auction':
                return "Auction Bid Confirmation #{$transactionId}{$itemInfo} - {$websiteName}";
                
            case 'investment':
                return "Investment Confirmation #{$transactionId}{$itemInfo} - {$websiteName}";
                
            default:
                return "Transaction Receipt #{$transactionId}{$itemInfo} - {$websiteName}";
        }
    }
    
    /**
     * Get custom filename based on transaction type
     */
    private function getFileName($transaction = null)
    {
        $transaction = $transaction ?? $this->transactions->first();
        
        switch($transaction->type) {
            case 'student':
            case 'general':
                return 'donation-receipt-' . $transaction->transaction_id . '.pdf';
                
            case 'ticket':
                return 'ticket-confirmation-' . $transaction->transaction_id . '.pdf';
                
            case 'auction':
                return 'auction-confirmation-' . $transaction->transaction_id . '.pdf';
                
            case 'investment':
                return 'investment-confirmation-' . $transaction->transaction_id . '.pdf';
                
            default:
                return 'transaction-receipt-' . $transaction->transaction_id . '.pdf';
        }
    }
}