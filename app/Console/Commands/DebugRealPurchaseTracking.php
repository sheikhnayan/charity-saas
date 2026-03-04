<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PaymentFunnelEvent;
use App\Models\Website;
use App\Models\Transaction;
use App\Models\TicektSell;

class DebugRealPurchaseTracking extends Command
{
    protected $signature = 'debug:real-purchase';
    protected $description = 'Debug why real purchases are not being tracked in payment funnel';

    public function handle()
    {
        $this->info('🔍 Debugging Real Purchase Tracking...');
        
        // 1. Check recent transactions
        $this->info('1. Recent transactions:');
        $transactions = Transaction::where('type', 'ticket')
            ->where('created_at', '>=', now()->subHours(2))
            ->orderBy('created_at', 'desc')
            ->get();
            
        if ($transactions->count() > 0) {
            $this->info("Found {$transactions->count()} recent ticket transactions:");
            foreach ($transactions as $trans) {
                $this->line("- ID: {$trans->id}, Amount: {$trans->amount}, Website: {$trans->website_id}, Transaction ID: {$trans->transaction_id}, Created: {$trans->created_at}");
            }
        } else {
            $this->warn('No recent ticket transactions found');
        }
        
        // 2. Check recent payment funnel events
        $this->info('2. Recent payment funnel events:');
        $events = PaymentFunnelEvent::where('created_at', '>=', now()->subHours(2))
            ->orderBy('created_at', 'desc')
            ->get();
            
        if ($events->count() > 0) {
            $this->info("Found {$events->count()} recent payment funnel events:");
            foreach ($events as $event) {
                $this->line("- ID: {$event->id}, Website: {$event->website_id}, Step: {$event->funnel_step}, Type: {$event->form_type}, Amount: {$event->amount}, Method: {$event->payment_method}, Created: {$event->created_at}");
            }
        } else {
            $this->warn('No recent payment funnel events found');
        }
        
        // 3. Check if website 12 exists and matches pickpockets.com
        $this->info('3. Website verification:');
        $website12 = Website::find(12);
        if ($website12) {
            $this->info("Website ID 12: {$website12->domain}");
            if ($website12->domain === 'pickpockets.com') {
                $this->info('✅ Website 12 correctly maps to pickpockets.com');
            } else {
                $this->error('❌ Website 12 maps to different domain: ' . $website12->domain);
            }
        } else {
            $this->error('❌ Website ID 12 not found!');
        }
        
        // 4. Check recent ticket sells
        $this->info('4. Recent ticket sells:');
        $ticketSells = TicektSell::where('created_at', '>=', now()->subHours(2))
            ->orderBy('created_at', 'desc')
            ->get();
            
        if ($ticketSells->count() > 0) {
            $this->info("Found {$ticketSells->count()} recent ticket sells:");
            foreach ($ticketSells as $sell) {
                $this->line("- ID: {$sell->id}, Status: {$sell->status}, Website: {$sell->website_id}, Created: {$sell->created_at}");
            }
        } else {
            $this->warn('No recent ticket sells found');
        }
        
        // 5. Analyze the gap
        $this->info('5. Gap Analysis:');
        if ($transactions->count() > 0 && $events->count() === 0) {
            $this->error('🚨 PROBLEM FOUND: Transactions exist but NO payment funnel events!');
            $this->error('This means trackPaymentFunnel() is not being called or failing silently.');
            
            // Check the most recent transaction
            $recentTrans = $transactions->first();
            $this->info('Most recent transaction details:');
            $this->line("- Transaction ID: {$recentTrans->transaction_id}");
            $this->line("- Website ID: {$recentTrans->website_id}");
            $this->line("- Amount: {$recentTrans->amount}");
            
            // Check if this should have triggered payment funnel tracking
            if ($recentTrans->website_id == 12) {
                $this->error('❌ This transaction has website_id 12 but no corresponding payment funnel event');
                $this->error('The trackPaymentFunnel call on line 496 of AuthorizeNetController is not working');
            }
        } elseif ($transactions->count() > 0 && $events->count() > 0) {
            $this->info('✅ Both transactions and events exist - system working correctly');
        } else {
            $this->warn('No recent data to analyze');
        }
        
        return 0;
    }
}