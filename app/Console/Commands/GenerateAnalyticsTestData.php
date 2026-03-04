<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UniqueVisitor;
use App\Models\PageView;
use App\Models\PaymentFunnelEvent;
use App\Models\Ticket;
use App\Models\Website;
use Carbon\Carbon;
use Faker\Factory as Faker;

class GenerateAnalyticsTestData extends Command
{
    protected $signature = 'test:generate-analytics-data {--clear : Clear existing test data}';
    protected $description = 'Generate comprehensive test data for analytics dashboard';

    public function handle()
    {
        $faker = Faker::create();
        
        if ($this->option('clear')) {
            $this->info('🧹 Clearing existing test data...');
            $this->clearTestData();
        }
        
        $this->info('🎯 Generating analytics test data...');
        
        $website = Website::where('domain', 'pickpockets.com')->first();
        if (!$website) {
            $this->error('❌ pickpockets.com website not found. Please ensure website exists.');
            return 1;
        }
        
        $websiteId = $website->id;
        $countries = ['US', 'CA', 'GB', 'DE', 'FR', 'AU', 'JP', 'BR', 'IN', 'NL'];
        $devices = ['desktop', 'mobile', 'tablet'];
        $browsers = ['Chrome', 'Firefox', 'Safari', 'Edge'];
        $formTypes = ['ticket', 'investment', 'donation'];
        
        // Generate data for the last 30 days
        for ($day = 30; $day >= 0; $day--) {
            $date = Carbon::now()->subDays($day);
            $dailyVisitors = rand(50, 200);
            
            $this->info("📅 Generating data for {$date->format('Y-m-d')} ({$dailyVisitors} visitors)");
            
            for ($i = 0; $i < $dailyVisitors; $i++) {
                // Create unique visitor
                $visitorId = time() . '.' . $faker->lexify('????????????????');
                $sessionId = 'session_' . $faker->uuid();
                $country = $faker->randomElement($countries);
                $device = $faker->randomElement($devices);
                $browser = $faker->randomElement($browsers);
                
                $visitor = UniqueVisitor::create([
                    'visitor_id' => $visitorId,
                    'session_id' => $sessionId,
                    'website_id' => $websiteId,
                    'ip_address' => $faker->ipv4(),
                    'user_agent' => $faker->userAgent(),
                    'device_type' => $device,
                    'browser' => $browser,
                    'operating_system' => $faker->randomElement(['Windows', 'macOS', 'Linux', 'iOS', 'Android']),
                    'referrer' => $faker->randomElement([
                        'https://google.com/search',
                        'https://facebook.com',
                        'https://twitter.com',
                        'direct',
                        null
                    ]),
                    'landing_page' => $faker->randomElement([
                        'https://pickpockets.com/',
                        'https://pickpockets.com/tickets',
                        'https://pickpockets.com/invest',
                        'https://pickpockets.com/about'
                    ]),
                    'country' => $country,
                    'visited_at' => $date->copy()->addHours(rand(0, 23))->addMinutes(rand(0, 59)),
                    'last_seen_at' => $date->copy()->addHours(rand(0, 23))->addMinutes(rand(0, 59)),
                    'created_at' => $date,
                    'updated_at' => $date
                ]);
                
                // Generate page views for this visitor (1-5 pages)
                $pageViews = rand(1, 5);
                for ($p = 0; $p < $pageViews; $p++) {
                    PageView::create([
                        'visitor_id' => $visitorId,
                        'session_id' => $sessionId,
                        'website_id' => $websiteId,
                        'url' => $faker->randomElement([
                            'https://pickpockets.com/',
                            'https://pickpockets.com/tickets',
                            'https://pickpockets.com/invest',
                            'https://pickpockets.com/about',
                            'https://pickpockets.com/contact'
                        ]),
                        'page_title' => $faker->sentence(3),
                        'referrer' => $p === 0 ? $visitor->referrer : null,
                        'viewed_at' => $date->copy()->addHours(rand(0, 23))->addMinutes(rand(0, 59)),
                        'created_at' => $date,
                        'updated_at' => $date
                    ]);
                }
                
                // Generate payment funnel events (simulate user journey)
                if (rand(1, 100) <= 30) { // 30% of visitors view a form
                    $formType = $faker->randomElement($formTypes);
                    $amount = $formType === 'ticket' ? rand(5000, 50000) : rand(10000, 100000); // cents
                    
                    // Form view
                    PaymentFunnelEvent::create([
                        'website_id' => $websiteId,
                        'session_id' => $sessionId,
                        'visitor_id' => $visitorId,
                        'funnel_step' => 'form_view',
                        'form_type' => $formType,
                        'completed_at' => $date->copy()->addHours(rand(0, 23)),
                        'device_type' => $device,
                        'browser' => $browser,
                        'ip_address' => $visitor->ip_address,
                        'created_at' => $date,
                        'updated_at' => $date
                    ]);
                    
                    if (rand(1, 100) <= 60) { // 60% of form viewers enter amount
                        PaymentFunnelEvent::create([
                            'website_id' => $websiteId,
                            'session_id' => $sessionId,
                            'visitor_id' => $visitorId,
                            'funnel_step' => 'amount_entered',
                            'form_type' => $formType,
                            'amount' => $amount,
                            'completed_at' => $date->copy()->addHours(rand(0, 23)),
                            'device_type' => $device,
                            'browser' => $browser,
                            'ip_address' => $visitor->ip_address,
                            'created_at' => $date,
                            'updated_at' => $date
                        ]);
                        
                        if (rand(1, 100) <= 70) { // 70% start personal info
                            PaymentFunnelEvent::create([
                                'website_id' => $websiteId,
                                'session_id' => $sessionId,
                                'visitor_id' => $visitorId,
                                'funnel_step' => 'personal_info_started',
                                'form_type' => $formType,
                                'amount' => $amount,
                                'completed_at' => $date->copy()->addHours(rand(0, 23)),
                                'device_type' => $device,
                                'browser' => $browser,
                                'ip_address' => $visitor->ip_address,
                                'created_at' => $date,
                                'updated_at' => $date
                            ]);
                            
                            if (rand(1, 100) <= 80) { // 80% complete personal info
                                PaymentFunnelEvent::create([
                                    'website_id' => $websiteId,
                                    'session_id' => $sessionId,
                                    'visitor_id' => $visitorId,
                                    'funnel_step' => 'personal_info_completed',
                                    'form_type' => $formType,
                                    'amount' => $amount,
                                    'completed_at' => $date->copy()->addHours(rand(0, 23)),
                                    'device_type' => $device,
                                    'browser' => $browser,
                                    'ip_address' => $visitor->ip_address,
                                    'created_at' => $date,
                                    'updated_at' => $date
                                ]);
                                
                                if (rand(1, 100) <= 50) { // 50% reach payment page
                                    PaymentFunnelEvent::create([
                                        'website_id' => $websiteId,
                                        'session_id' => $sessionId,
                                        'visitor_id' => $visitorId,
                                        'funnel_step' => 'payment_initiated',
                                        'form_type' => $formType,
                                        'amount' => $amount,
                                        'payment_method' => $faker->randomElement(['stripe', 'authorize_net']),
                                        'completed_at' => $date->copy()->addHours(rand(0, 23)),
                                        'device_type' => $device,
                                        'browser' => $browser,
                                        'ip_address' => $visitor->ip_address,
                                        'created_at' => $date,
                                        'updated_at' => $date
                                    ]);
                                    
                                    if (rand(1, 100) <= 75) { // 75% complete payment
                                        PaymentFunnelEvent::create([
                                            'website_id' => $websiteId,
                                            'session_id' => $sessionId,
                                            'visitor_id' => $visitorId,
                                            'funnel_step' => 'payment_completed',
                                            'form_type' => $formType,
                                            'amount' => $amount,
                                            'payment_method' => $faker->randomElement(['stripe', 'authorize_net']),
                                            'transaction_id' => 'test_' . $faker->uuid(),
                                            'completed_at' => $date->copy()->addHours(rand(0, 23)),
                                            'device_type' => $device,
                                            'browser' => $browser,
                                            'ip_address' => $visitor->ip_address,
                                            'created_at' => $date,
                                            'updated_at' => $date
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        
        // Create some test tickets for sell-through analysis
        $this->info('🎫 Creating test tickets...');
        $ticketTitles = [
            'VIP Premium Experience',
            'General Admission',
            'Early Bird Special',
            'Group Package Deal',
            'Student Discount Ticket'
        ];
        
        foreach ($ticketTitles as $title) {
            $quantity = rand(50, 200);
            Ticket::create([
                'website_id' => $websiteId,
                'name' => $title,
                'description' => $faker->paragraph(),
                'price' => rand(2500, 15000), // $25-$150
                'quantity' => $quantity,
                'status' => 'active',
                'user_id' => 1,
                'created_at' => Carbon::now()->subDays(rand(5, 30)),
                'updated_at' => Carbon::now()
            ]);
        }
        
        $this->info('✅ Analytics test data generated successfully!');
        
        // Show summary
        $totalVisitors = UniqueVisitor::where('website_id', $websiteId)->count();
        $totalPageViews = PageView::where('website_id', $websiteId)->count();
        $totalConversions = PaymentFunnelEvent::where('website_id', $websiteId)
            ->where('funnel_step', 'payment_completed')->count();
        $totalRevenue = PaymentFunnelEvent::where('website_id', $websiteId)
            ->where('funnel_step', 'payment_completed')->sum('amount') / 100;
        
        $this->info("📊 Summary:");
        $this->line("   Unique Visitors: {$totalVisitors}");
        $this->line("   Page Views: {$totalPageViews}");
        $this->line("   Conversions: {$totalConversions}");
        $this->line("   Revenue: $" . number_format($totalRevenue, 2));
        
        return 0;
    }
    
    protected function clearTestData()
    {
        PaymentFunnelEvent::where('transaction_id', 'like', 'test_%')->delete();
        PageView::whereIn('visitor_id', function($query) {
            $query->select('visitor_id')->from('unique_visitors')
                  ->where('visitor_id', 'like', '%test%');
        })->delete();
        UniqueVisitor::where('visitor_id', 'like', '%test%')->delete();
        
        // Clear test tickets
        Ticket::where('name', 'like', '%Test%')->delete();
    }
}