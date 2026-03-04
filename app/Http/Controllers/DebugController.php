<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Investment;
use App\Models\Website;

class DebugController extends Controller
{
    /**
     * Debug fee calculations and SSN saving
     */
    public function debugFeesAndSSN()
    {
        $html = '<h2>Debug Report: Fees and SSN Fields</h2>';
        
        // Check recent transactions
        $transactions = Transaction::with(['website', 'website.paymentSettings'])->latest()->take(5)->get();
        
        $html .= '<h3>Recent Transactions Fee Analysis:</h3>';
        $html .= '<table border="1" style="border-collapse: collapse; width: 100%;">';
        $html .= '<tr><th>ID</th><th>Amount</th><th>Website</th><th>Payment Settings</th><th>Fee Used</th><th>Total with Fee</th></tr>';
        
        foreach ($transactions as $transaction) {
            $globalPayment = \App\Models\PaymentSetting::first();
            $defaultFee = $globalPayment ? $globalPayment->fee : 2.9;
            
            $websiteHasSettings = $transaction->website && $transaction->website->paymentSettings;
            $feeUsed = $websiteHasSettings && $transaction->website->paymentSettings->fee 
                ? $transaction->website->paymentSettings->fee 
                : $defaultFee;
                
            $totalWithFee = $transaction->amount + (($transaction->amount / 100) * $feeUsed);
            
            $html .= '<tr>';
            $html .= '<td>' . $transaction->id . '</td>';
            $html .= '<td>$' . number_format($transaction->amount, 2) . '</td>';
            $html .= '<td>' . ($transaction->website->name ?? 'N/A') . '</td>';
            $html .= '<td>' . ($websiteHasSettings ? 'Yes (' . ($transaction->website->paymentSettings->fee ?? 'no fee') . '%)' : 'No') . '</td>';
            $html .= '<td>' . $feeUsed . '%</td>';
            $html .= '<td>$' . number_format($totalWithFee, 2) . '</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';
        
        // Check recent investments for SSN data
        $investments = Investment::latest()->take(5)->get();
        
        $html .= '<h3>Recent Investments SSN Analysis:</h3>';
        $html .= '<table border="1" style="border-collapse: collapse; width: 100%;">';
        $html .= '<tr><th>ID</th><th>Investor Name</th><th>SSN Fields Found</th><th>All Investor Data</th></tr>';
        
        foreach ($investments as $investment) {
            $investorData = $investment->investor_data ?? [];
            
            $ssnFields = [];
            $potentialSsnFields = ['ssn', 'taxpayer_id', 'primary_ssn', 'secondary_ssn', 'joint_holder_taxpayer_id'];
            
            foreach ($potentialSsnFields as $field) {
                if (!empty($investorData[$field])) {
                    $ssnFields[] = $field . ': ' . substr($investorData[$field], 0, 3) . '***';
                }
            }
            
            $html .= '<tr>';
            $html .= '<td>' . $investment->id . '</td>';
            $html .= '<td>' . ($investment->investor_name ?? 'N/A') . '</td>';
            $html .= '<td>' . (empty($ssnFields) ? 'None found' : implode('<br>', $ssnFields)) . '</td>';
            $html .= '<td><pre>' . json_encode($investorData, JSON_PRETTY_PRINT) . '</pre></td>';
            $html .= '</tr>';
        }
        $html .= '</table>';
        
        // Check if WebsitePaymentSetting has fee field
        $html .= '<h3>Payment Settings Structure:</h3>';
        $websitePaymentSetting = \App\Models\WebsitePaymentSetting::first();
        if ($websitePaymentSetting) {
            $html .= '<p><strong>WebsitePaymentSetting fields:</strong> ' . implode(', ', array_keys($websitePaymentSetting->toArray())) . '</p>';
            if (isset($websitePaymentSetting->settings)) {
                $html .= '<p><strong>Settings JSON:</strong> <pre>' . json_encode($websitePaymentSetting->settings, JSON_PRETTY_PRINT) . '</pre></p>';
            }
        } else {
            $html .= '<p>No WebsitePaymentSettings found</p>';
        }
        
        $globalPayment = \App\Models\PaymentSetting::first();
        if ($globalPayment) {
            $html .= '<p><strong>Global PaymentSetting fee:</strong> ' . ($globalPayment->fee ?? 'null') . '</p>';
        } else {
            $html .= '<p>No global PaymentSettings found</p>';
        }
        
        return response($html)->header('Content-Type', 'text/html');
    }
}