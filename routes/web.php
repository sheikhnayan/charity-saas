<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\HotjarViewController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\WebsitePaymentController;
use App\Http\Controllers\Api\PageBuilderController;
use App\Http\Controllers\AuthorizeNetController;
use App\Http\Controllers\CoinbaseController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketCategoryController;
use App\Http\Controllers\Admin\PropertyCategoryController;
use App\Http\Controllers\SponsorController;
use App\Http\Controllers\AuctionController;
use App\Http\Controllers\Analytics\DashboardController;
use App\Http\Controllers\Admin\PaymentMethodAnalyticsController;
use App\Http\Controllers\Admin\WebsiteEmailSettingsController;
use App\Http\Middleware\admin;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Website;

// Include debug routes
include __DIR__ . '/debug.php';

// Include funnel tracking test routes
include __DIR__ . '/test-funnel.php';
include __DIR__ . '/test-db.php';

// Firebase Config JS Route
Route::get('/firebase-config.js', function () {
    $config = [
        'apiKey' => env('FIREBASE_API_KEY'),
        'authDomain' => env('FIREBASE_AUTH_DOMAIN'),
        'projectId' => env('FIREBASE_PROJECT_ID'),
        'storageBucket' => env('FIREBASE_STORAGE_BUCKET'),
        'messagingSenderId' => env('FIREBASE_MESSAGING_SENDER_ID'),
        'appId' => env('FIREBASE_APP_ID')
    ];
    
    $js = "// Firebase Configuration (Auto-generated from .env)\n";
    $js .= "self.firebaseConfig = " . json_encode($config) . ";\n";
    
    return response($js)->header('Content-Type', 'application/javascript');
});

// Custom Fonts CSS (Public - for loading fonts in editors and frontend)
Route::get('/fonts/custom.css', [\App\Http\Controllers\Admin\FontController::class, 'css'])->name('fonts.css');

// Public API endpoint for getting teachers by website
Route::get('/api/teachers', [\App\Http\Controllers\Admin\TeacherController::class, 'getTeachers'])->name('api.teachers');

// Public metals price API (used by scrap calculator component)
Route::get('/api/metals/prices', function () {
    $cacheKey = 'metals_live_prices_usd';

    $payload = \Illuminate\Support\Facades\Cache::remember($cacheKey, 60, function () {
        $debugLog = [];
        $normalized = [
            'gold' => null,
            'silver' => null,
            'platinum' => null,
            'palladium' => null,
        ];
        
        // Track if we got any real data from APIs (not fallbacks)
        $gotRealData = false;

        // Primary source: Metals.live spot prices API (most reliable free source)
        try {
            $debugLog[] = '🔄 Attempting metals.live primary API';
            
            $response = \Illuminate\Support\Facades\Http::timeout(10)
                ->withoutVerifying()  // Production servers often have different SSL config
                ->acceptJson()
                ->get('https://api.metals.live/v1/spot/price?codes=XAU,XAG,XPT,XPD');

            $debugLog[] = '📥 metals.live Response Status: ' . $response->status();

            if ($response->ok()) {
                $data = $response->json();
                $debugLog[] = '✓ metals.live returned data';
                
                $metalMap = [
                    'XAU' => 'gold',
                    'XAG' => 'silver',
                    'XPT' => 'platinum',
                    'XPD' => 'palladium',
                ];

                foreach ($metalMap as $code => $metal) {
                    $price = $data[$code] ?? null;
                    if (is_numeric($price) && $price > 0) {
                        $normalized[$metal] = (float) $price;
                        $gotRealData = true;  // Mark that we got real data
                        $debugLog[] = "  ✓ {$metal}: \${$price}/oz (from metals.live)";
                    }
                }
            } else {
                $debugLog[] = '❌ metals.live returned non-OK status: ' . $response->status();
            }
        } catch (\Throwable $e) {
            $debugLog[] = '❌ metals.live Exception: ' . $e->getMessage();
            \Log::warning('Metals price primary source (metals.live) failed', ['message' => $e->getMessage()]);
        }

        // Secondary source: GoldPrice.org API (alternative free source)
        if (in_array(null, $normalized, true)) {
            $debugLog[] = '🔄 Attempting goldprice.org fallback API';
            try {
                $response = \Illuminate\Support\Facades\Http::timeout(10)
                    ->withoutVerifying()
                    ->acceptJson()
                    ->get('https://data-asg.goldprice.org/dbXRates/USD');

                $debugLog[] = '📥 goldprice.org Response Status: ' . $response->status();

                if ($response->ok()) {
                    $data = $response->json();
                    $debugLog[] = '✓ goldprice.org returned data';
                    
                    // goldprice.org returns array of records with flattop structure
                    if (is_array($data) && count($data) > 0) {
                        $record = $data[0];  // Get first record
                        
                        // Map their fields to our metals
                        $rateMap = [
                            'XAU' => 'gold',
                            'XAG' => 'silver',
                            'XPT' => 'platinum',
                            'XPD' => 'palladium',
                        ];

                        foreach ($rateMap as $code => $metal) {
                            if ($normalized[$metal] !== null) {
                                continue;  // Skip if already have this price
                            }
                            
                            $price = $record[$code . 'rate'] ?? null;
                            if (is_numeric($price) && $price > 0) {
                                $normalized[$metal] = (float) $price;
                                $gotRealData = true;  // Mark that we got real data
                                $debugLog[] = "  ✓ {$metal}: \${$price}/oz (from goldprice.org)";
                            }
                        }
                    }
                } else {
                    $debugLog[] = '❌ goldprice.org returned non-OK status: ' . $response->status();
                }
            } catch (\Throwable $e) {
                $debugLog[] = '❌ goldprice.org Exception: ' . $e->getMessage();
            }
        }

        // Tertiary source: Twelve Data (has free spot prices for metals)
        if (in_array(null, $normalized, true)) {
            $debugLog[] = '🔄 Attempting Twelve Data API';
            try {
                $metals = ['XAUUSD' => 'gold', 'XAGUSD' => 'silver', 'XPTUSD' => 'platinum', 'XPDUSD' => 'palladium'];
                
                foreach ($metals as $symbol => $metal) {
                    if ($normalized[$metal] !== null) {
                        continue;
                    }
                    
                    $response = \Illuminate\Support\Facades\Http::timeout(5)
                        ->withoutVerifying()
                        ->acceptJson()
                        ->get('https://api.twelvedata.com/price', [
                            'symbol' => $symbol,
                            'apikey' => 'demo'  // Free tier with demo key
                        ]);

                    if ($response->ok()) {
                        $data = $response->json();
                        $price = $data['price'] ?? null;
                        if (is_numeric($price) && $price > 0) {
                            $normalized[$metal] = (float) $price;
                            $gotRealData = true;
                            $debugLog[] = "  ✓ {$metal}: \${$price}/oz (from Twelve Data)";
                        }
                    }
                }
            } catch (\Throwable $e) {
                // Silent fail for tertiary source
                $debugLog[] = '⚠️  Twelve Data Exception: ' . $e->getMessage();
            }
        }
        
        // Final fallback: Hardcoded reasonable prices
        if (!$gotRealData) {
            $debugLog[] = '⚠️  All live sources failed, using approximate fallback prices';
            \Log::warning('Metals calculator - all live API sources failed');
            
            $normalized = [
                'gold' => 2045.50,
                'silver' => 27.30,
                'platinum' => 1050.75,
                'palladium' => 1125.25,
            ];
            foreach ($normalized as $metal => $price) {
                $debugLog[] = "  📝 {$metal}: \${$price}/oz (fallback)";
            }
        }
        
        return [
            'prices_per_ounce_usd' => $normalized,
            'source' => $gotRealData ? 'live' : 'fallback',
            'last_updated' => now()->toIso8601String(),
            'debug' => $debugLog,  // Always send debug logs to diagnose issues
        ];
    });

    $pricesPerOunce = $payload['prices_per_ounce_usd'] ?? [];
    $pricesPerGram = [];
    $gramsPerTroyOunce = 31.1034768;
    foreach ($pricesPerOunce as $metal => $pricePerOunce) {
        $pricesPerGram[$metal] = is_numeric($pricePerOunce)
            ? round(((float) $pricePerOunce) / $gramsPerTroyOunce, 4)
            : null;
    }

    return response()->json([
        'success' => true,
        'prices_per_ounce_usd' => $pricesPerOunce,
        'prices_per_gram_usd' => $pricesPerGram,
        'source' => $payload['source'] ?? 'fallback',
        'last_updated' => $payload['last_updated'] ?? now()->toIso8601String(),
        'debug' => $payload['debug'] ?? null,
    ]);
})->name('api.metals.prices');

// Analytics Routes
Route::middleware(['auth', \App\Http\Middleware\admin::class])->group(function () {
    Route::get('/analytics', [DashboardController::class, 'index'])->name('analytics.dashboard');
    Route::get('/analytics/export', [DashboardController::class, 'export'])->name('analytics.dashboard.export');
    Route::get('/analytics/real-time', [DashboardController::class, 'realTime'])->name('analytics.realtime');
    
    // Chart Data API Routes
    Route::get('/analytics/api/conversions', [DashboardController::class, 'getTimeBasedConversions'])->name('analytics.api.conversions');
    Route::get('/analytics/api/sessions', [DashboardController::class, 'getTimeBasedSessions'])->name('analytics.api.sessions');
    Route::get('/analytics/api/funnel', [DashboardController::class, 'getConversionFunnel'])->name('analytics.api.funnel');
    Route::get('/analytics/api/devices', [DashboardController::class, 'getDeviceData'])->name('analytics.api.devices');
    Route::get('/analytics/api/locations', [DashboardController::class, 'getLocationChartData'])->name('analytics.api.locations');
    Route::get('/analytics/api/products', [DashboardController::class, 'getProductData'])->name('analytics.api.products');
    Route::get('/analytics/api/geomap', [DashboardController::class, 'getGeoMapData'])->name('analytics.api.geomap');
    
    // Payment Method Analytics Routes
    Route::prefix('admins/payment-methods')->name('admin.payment-methods.')->group(function () {
        Route::get('/analytics', [PaymentMethodAnalyticsController::class, 'index'])->name('analytics');
        Route::get('/analytics/api', [PaymentMethodAnalyticsController::class, 'api'])->name('analytics.api');
        Route::get('/analytics/export', [PaymentMethodAnalyticsController::class, 'export'])->name('analytics.export');
    });
    
    // Hotjar-style Session Recording & Heatmap Routes
    Route::prefix('hotjar')->name('hotjar.')->group(function () {
        Route::get('/recordings', [HotjarViewController::class, 'recordings'])->name('recordings');
        Route::get('/recordings/{recordingId}/replay', [HotjarViewController::class, 'replay'])->name('recordings.replay');
        Route::get('/heatmaps', [HotjarViewController::class, 'heatmaps'])->name('heatmaps');
    });
    
    // Push Notification Settings Route
    Route::get('/admin/notification-settings', function () {
        return view('admin.notification-settings');
    })->name('admin.notification-settings');
    
    // Hotjar Admin API Routes (for viewing data)
    Route::prefix('api')->group(function () {
        Route::get('/session-recording', [HotjarViewController::class, 'getRecordings']);
        Route::get('/heatmap/popular-pages', [HotjarViewController::class, 'getPopularPages']);
        Route::get('/heatmap/click', [HotjarViewController::class, 'getClickHeatmap']);
        Route::get('/heatmap/move', [HotjarViewController::class, 'getMoveHeatmap']);
        Route::get('/heatmap/scroll', [HotjarViewController::class, 'getScrollDepth']);
        Route::get('/heatmap/element-stats', [HotjarViewController::class, 'getElementStats']);
    });
    
    // Heatmaps & Session Recordings (use existing working system)
    Route::get('/heatmaps', [HotjarViewController::class, 'heatmaps'])->name('heatmaps.index');
    Route::get('/recordings', [HotjarViewController::class, 'recordings'])->name('recordings.index');
    
    // Fraud Detection Routes
    Route::prefix('fraud')->name('fraud.')->group(function () {
        Route::get('/', [\App\Http\Controllers\FraudDetectionController::class, 'index'])->name('index');
        Route::get('/statistics', [\App\Http\Controllers\FraudDetectionController::class, 'getStatistics'])->name('statistics');
        Route::get('/pending', [\App\Http\Controllers\FraudDetectionController::class, 'pending'])->name('pending');
        Route::post('/detections/{id}/review', [\App\Http\Controllers\FraudDetectionController::class, 'review'])->name('detections.review');
        
        // API routes for dashboard
        Route::get('/api/stats', [\App\Http\Controllers\FraudDetectionController::class, 'getStatistics'])->name('api.stats');
        Route::get('/api/recent', [\App\Http\Controllers\FraudDetectionController::class, 'getRecentDetections'])->name('api.recent');
        
        Route::get('/rules', [\App\Http\Controllers\FraudDetectionController::class, 'rules'])->name('rules');
        Route::post('/rules', [\App\Http\Controllers\FraudDetectionController::class, 'createRule'])->name('rules.create');
        Route::put('/rules/{id}', [\App\Http\Controllers\FraudDetectionController::class, 'updateRule'])->name('rules.update');
        Route::delete('/rules/{id}', [\App\Http\Controllers\FraudDetectionController::class, 'deleteRule'])->name('rules.delete');
        Route::post('/rules/{id}/toggle', [\App\Http\Controllers\FraudDetectionController::class, 'toggleRule'])->name('rules.toggle');
    });

    // Cohort Analysis Routes
    Route::prefix('cohorts')->name('cohorts.')->group(function () {
        Route::get('/', [\App\Http\Controllers\CohortController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\CohortController::class, 'create'])->name('create');
        Route::get('/{id}', [\App\Http\Controllers\CohortController::class, 'show'])->name('show');
        Route::put('/{id}', [\App\Http\Controllers\CohortController::class, 'update'])->name('update');
        Route::delete('/{id}', [\App\Http\Controllers\CohortController::class, 'destroy'])->name('destroy');
        
        // API routes for dashboard
        Route::get('/api/retention-heatmap', [\App\Http\Controllers\CohortController::class, 'getRetentionHeatmap'])->name('api.retention-heatmap');
        
        Route::post('/{id}/refresh', [\App\Http\Controllers\CohortController::class, 'refresh'])->name('refresh');
        Route::post('/{id}/retention', [\App\Http\Controllers\CohortController::class, 'calculateRetention'])->name('retention');
        Route::get('/{id}/retention-chart', [\App\Http\Controllers\CohortController::class, 'retentionChart'])->name('retention.chart');
        Route::get('/{id}/members', [\App\Http\Controllers\CohortController::class, 'members'])->name('members');
        Route::get('/{id}/export', [\App\Http\Controllers\CohortController::class, 'export'])->name('export');
        
        Route::post('/compare', [\App\Http\Controllers\CohortController::class, 'compare'])->name('compare');
    });

    // UTM Attribution Analytics Routes (WEBSITE-BASED)
    Route::prefix('analytics/utm')->group(function () {
        Route::get('/', [\App\Http\Controllers\Analytics\UTMAnalyticsController::class, 'index'])->name('analytics.utm');
        Route::get('/export', [\App\Http\Controllers\Analytics\UTMAnalyticsController::class, 'export'])->name('analytics.utm.export');
    });

    // API endpoint for UTM URL generator
    Route::get('/api/websites/{website}/products', function($websiteId) {
        try {
            // Query tickets directly by website_id
            $tickets = \App\Models\Ticket::where('website_id', $websiteId)
                ->get(['id', 'name', 'slug']);
            
            $products = $tickets->map(function($ticket) {
                return [
                    'id' => $ticket->id,
                    'name' => $ticket->name,
                    'slug' => $ticket->slug ?: \Illuminate\Support\Str::slug($ticket->name)
                ];
            });
            
            return response()->json([
                'products' => $products->values()->toArray(),
                'count' => $products->count()
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading products for website: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to load products',
                'message' => $e->getMessage()
            ], 500);
        }
    })->middleware('auth');

    // Referrer Analytics Routes (WEBSITE-BASED)
    Route::prefix('analytics/referrer')->group(function () {
        Route::get('/', [\App\Http\Controllers\Analytics\ReferrerAnalyticsController::class, 'index'])->name('analytics.referrer');
        Route::get('/export', [\App\Http\Controllers\Analytics\ReferrerAnalyticsController::class, 'export'])->name('analytics.referrer.export');
    });
    
    // QR Code Donation Admin Routes
    Route::prefix('qr-codes')->name('admin.qr.')->group(function () {
        Route::get('/', [\App\Http\Controllers\QRCodeDonationController::class, 'adminIndex'])->name('index');
        Route::post('/generate', [\App\Http\Controllers\QRCodeDonationController::class, 'generate'])->name('generate');
        Route::post('/generate-campaign', [\App\Http\Controllers\QRCodeDonationController::class, 'generateCampaign'])->name('generate.campaign');
        Route::post('/download', [\App\Http\Controllers\QRCodeDonationController::class, 'download'])->name('download');
        Route::get('/statistics', [\App\Http\Controllers\QRCodeDonationController::class, 'statistics'])->name('statistics');
    });

    // Website Email/SMTP Settings
    Route::prefix('admins/websites/{website}/email-settings')->name('admin.website.email.')->group(function () {
        Route::get('/', [WebsiteEmailSettingsController::class, 'index'])->name('index');
        Route::post('/', [WebsiteEmailSettingsController::class, 'store'])->name('store');
        Route::put('/', [WebsiteEmailSettingsController::class, 'update'])->name('update');
    });

    // Custom Font Management Routes
    Route::prefix('admin/fonts')->name('admin.fonts.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\FontController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\Admin\FontController::class, 'store'])->name('store');
        Route::patch('/{id}/toggle', [\App\Http\Controllers\Admin\FontController::class, 'toggle'])->name('toggle');
        Route::delete('/{id}', [\App\Http\Controllers\Admin\FontController::class, 'destroy'])->name('destroy');
    });

    // A/B Testing Routes
    Route::prefix('ab-tests')->name('abtests.')->group(function () {
        Route::get('/', [\App\Http\Controllers\ABTestController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\ABTestController::class, 'create'])->name('create');
        Route::get('/{id}/edit', [\App\Http\Controllers\ABTestController::class, 'edit'])->name('edit');
        Route::get('/{id}', [\App\Http\Controllers\ABTestController::class, 'show'])->name('show');
        Route::put('/{id}', [\App\Http\Controllers\ABTestController::class, 'update'])->name('update');
        Route::delete('/{id}', [\App\Http\Controllers\ABTestController::class, 'destroy'])->name('destroy');
        
        Route::post('/{id}/start', [\App\Http\Controllers\ABTestController::class, 'start'])->name('start');
        Route::post('/{id}/pause', [\App\Http\Controllers\ABTestController::class, 'pause'])->name('pause');
        Route::post('/{id}/end', [\App\Http\Controllers\ABTestController::class, 'end'])->name('end');
        
        Route::post('/{id}/assign', [\App\Http\Controllers\ABTestController::class, 'assignVariant'])->name('assign');
        Route::post('/{id}/conversion', [\App\Http\Controllers\ABTestController::class, 'trackConversion'])->name('conversion');
        
        Route::get('/{id}/results', [\App\Http\Controllers\ABTestController::class, 'results'])->name('results');
        Route::post('/{id}/calculate', [\App\Http\Controllers\ABTestController::class, 'calculateResults'])->name('calculate');
        Route::post('/{id}/winner', [\App\Http\Controllers\ABTestController::class, 'determineWinner'])->name('winner');
        Route::get('/{id}/chart', [\App\Http\Controllers\ABTestController::class, 'conversionChart'])->name('chart');
        Route::get('/{id}/export', [\App\Http\Controllers\ABTestController::class, 'export'])->name('export');
    });

    // Scheduled Reports Routes
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [\App\Http\Controllers\ReportController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\ReportController::class, 'create'])->name('create');
        Route::get('/{id}', [\App\Http\Controllers\ReportController::class, 'show'])->name('show');
        Route::put('/{id}', [\App\Http\Controllers\ReportController::class, 'update'])->name('update');
        Route::delete('/{id}', [\App\Http\Controllers\ReportController::class, 'destroy'])->name('destroy');
        
        Route::post('/{id}/generate', [\App\Http\Controllers\ReportController::class, 'generate'])->name('generate');
        Route::get('/{id}/executions', [\App\Http\Controllers\ReportController::class, 'executions'])->name('executions');
        Route::get('/execution/{executionId}/download', [\App\Http\Controllers\ReportController::class, 'download'])->name('download');
    });

    // Data Export Routes
    Route::prefix('exports')->name('exports.')->group(function () {
        Route::post('/analytics', [\App\Http\Controllers\ExportController::class, 'analytics'])->name('analytics');
        Route::post('/donations', [\App\Http\Controllers\ExportController::class, 'donations'])->name('donations');
        Route::post('/transactions', [\App\Http\Controllers\ExportController::class, 'transactions'])->name('transactions');
        Route::post('/users', [\App\Http\Controllers\ExportController::class, 'users'])->name('users');
        Route::post('/custom', [\App\Http\Controllers\ExportController::class, 'custom'])->name('custom');
    });
});

// Public Hotjar Tracking API Routes (NO AUTH REQUIRED - for tracking scripts on public pages)
Route::prefix('api')->middleware([\App\Http\Middleware\CorsMiddleware::class])->group(function () {
    Route::post('/session-recording/start', [HotjarViewController::class, 'startRecording']);
    Route::post('/session-recording/events', [HotjarViewController::class, 'saveEvents']);
    Route::post('/session-recording/complete', [HotjarViewController::class, 'completeRecording']);
    Route::get('/session-recording/{id}', [HotjarViewController::class, 'getRecordingWithEvents']);
    Route::post('/heatmap/track', [HotjarViewController::class, 'trackHeatmapEvent']); // Unified tracking endpoint
    Route::post('/heatmap/click', [HotjarViewController::class, 'trackClick']);
    Route::post('/heatmap/move', [HotjarViewController::class, 'trackMouseMove']);
    Route::post('/heatmap/scroll', [HotjarViewController::class, 'trackScroll']);
    Route::get('/heatmap/screenshot', [HotjarViewController::class, 'getScreenshot']);
    Route::post('/heatmap/screenshot/capture', [HotjarViewController::class, 'captureScreenshot']);
});

// Public Hotjar Demo (no auth required)
Route::get('/hotjar/demo', function() {
    $website = \App\Models\Website::first();
    return view('hotjar.demo', compact('website'));
})->name('hotjar.demo');



// Debug route to check PaymentFunnelEvent data
Route::get('/debug-analytics', function() {
    $websites = \App\Models\Website::all(['id', 'name', 'type']);
    $paymentData = \App\Models\PaymentFunnelEvent::select('website_id', 'funnel_step', 'form_type', 'amount', 'created_at')
        ->whereIn('funnel_step', ['payment_completed', 'form_view'])
        ->latest()
        ->limit(10)
        ->get();
    
    $websiteData = [];
    foreach($websites as $website) {
        $conversions = \App\Models\PaymentFunnelEvent::where('website_id', $website->id)
            ->where('funnel_step', 'payment_completed')
            ->count();
        
        $revenue = \App\Models\PaymentFunnelEvent::where('website_id', $website->id)
            ->where('funnel_step', 'payment_completed')
            ->sum('amount') ?? 0;
            
        $sessions = \App\Models\PaymentFunnelEvent::where('website_id', $website->id)
            ->distinct('session_id')
            ->count();
            
        $websiteData[] = [
            'id' => $website->id,
            'name' => $website->name,
            'type' => $website->type,
            'conversions' => $conversions,
            'revenue' => $revenue,
            'sessions' => $sessions
        ];
    }
    
    return response()->json([
        'websites' => $websiteData,
        'recent_payments' => $paymentData,
        'total_payment_events' => \App\Models\PaymentFunnelEvent::count(),
        'current_user_websites' => auth()->check() ? (auth()->user()->role === 'admin' ? 'All websites' : \App\Models\Website::where('user_id', auth()->id())->pluck('name', 'id')) : 'Not authenticated'
    ]);
});

// Analytics debug page
Route::get('/debug-analytics-page', function() {
    return view('debug.analytics');
})->middleware('auth');

// Test route to populate demo data
Route::get('/populate-demo', function() {
    Setting::truncate(); // Clear existing data
    
    Setting::create([
        'user_id' => 1,
        'hero_title' => 'ADMIN CONTROLLED HERO!',
        'hero_subtitle' => 'This content is now dynamic from admin panel!',
        'stat_1_number' => '5B+',
        'stat_1_text' => 'Raised via admin',
        'stat_2_number' => '2.5B',
        'stat_2_text' => 'Admin controlled',
        'stat_3_number' => '1200+',
        'stat_3_text' => 'Dynamic offers',
        'meta_title' => 'Dynamic DealMaker | Admin Controlled',
        'meta_description' => 'This page is now completely controlled by the admin panel!',
        'site_logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/93/Amazon_Web_Services_Logo.svg/256px-Amazon_Web_Services_Logo.svg.png',
        'client_logos' => json_encode([
            [
                'name' => 'AWS',
                'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/93/Amazon_Web_Services_Logo.svg/256px-Amazon_Web_Services_Logo.svg.png',
                'url' => 'https://aws.amazon.com'
            ],
            [
                'name' => 'Google',
                'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c1/Google_%22G%22_logo.svg/120px-Google_%22G%22_logo.svg.png',
                'url' => 'https://google.com'
            ],
            [
                'name' => 'Microsoft',
                'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/44/Microsoft_logo.svg/256px-Microsoft_logo.svg.png',
                'url' => 'https://microsoft.com'
            ]
        ]),
        'slider_images' => json_encode([
            [
                'image' => 'https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/685561045749461ab86204c2_homepage_phone-02.webp',
                'title' => 'ADMIN CONTROLLED SLIDER!',
                'description' => 'This slider content is now completely dynamic and controlled from the admin panel!',
                'cta_text' => 'Admin Demo',
                'cta_url' => '/admins/dealmaker-settings'
            ],
            [
                'image' => 'https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/6855610466fede381344c563_homepage_phone-03.webp',
                'title' => 'Dynamic Content Management',
                'description' => 'Change any content on this page through the admin interface without touching code.',
                'cta_text' => 'Manage Content',
                'cta_url' => '/admins/dealmaker-settings'
            ]
        ])
    ]);
    
    return 'Demo data populated! Visit <a href="/dealmaker-demo">dealmaker-demo</a> to see the changes.';
});

Route::get('authorize/payment/{type}/{id}', [AuthorizeNetController::class, 'index']);
Route::post('authorize/payment', [AuthorizeNetController::class, 'paymentPost'])->name('authorize.payment');
Route::post('authorize/stripe', [AuthorizeNetController::class, 'paymentStripe'])->name('stripe.post');

// Crypto Payment Routes (Coinbase Commerce)
Route::get('/crypto-payment', [CoinbaseController::class, 'showPaymentPage'])->name('crypto.payment');
Route::post('/coinbase/create-charge', [CoinbaseController::class, 'createCharge'])->name('coinbase.create');
Route::post('/webhook/coinbase', [CoinbaseController::class, 'webhook'])->name('coinbase.webhook');
Route::get('/coinbase/status/{chargeCode}', [CoinbaseController::class, 'checkStatus'])->name('coinbase.status');

Route::get('/product', function(){
    return view('thank-you');
});

// Comments routes (with CSRF protection)
Route::post('/comments', [App\Http\Controllers\Api\CommentController::class, 'store'])->name('comments.store');
Route::get('/comments', [App\Http\Controllers\Api\CommentController::class, 'index'])->name('comments.index');

// CSRF Test Route
Route::post('/test-csrf', function (\Illuminate\Http\Request $request) {
    return response()->json([
        'status' => 'success',
        'message' => 'CSRF token is valid',
        'data' => $request->all()
    ]);
})->name('test.csrf');

Route::get('/run-migrate', function () {
    try {
        Artisan::call('migrate', ['--force' => true]);
        return 'Migration completed: ' . Artisan::output();
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});

// Clear all cache route (for deployment)
Route::get('/clear-all-cache', function () {
    try {
        Artisan::call('route:clear');
        $route = Artisan::output();
        
        Artisan::call('config:clear');
        $config = Artisan::output();
        
        Artisan::call('cache:clear');
        $cache = Artisan::output();
        
        Artisan::call('view:clear');
        $view = Artisan::output();
        
        Artisan::call('optimize');
        $optimize = Artisan::output();
        
        return response()->json([
            'success' => true,
            'message' => 'All caches cleared successfully!',
            'details' => [
                'route' => $route,
                'config' => $config,
                'cache' => $cache,
                'view' => $view,
                'optimize' => $optimize
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
});

Route::get('/', [
    FrontendController::class, 'index'
])->name('home');

Route::get('/page-builder', function () {
    return view('page-builder');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/auction', [AuctionController::class, 'all'])->name('auction');

Route::get('/place-bid', [AuctionController::class, 'store'])->name('auction.store');

Route::get('/page/{id}', [FrontendController::class, 'page'])->name('page');

Route::get('/dealmaker-demo', [FrontendController::class, 'dealmakerDemo'])->name('dealmaker.demo');

Route::post('/login', [AuthController::class, 'login']);

Route::post('/register', [AuthController::class, 'register']);

Route::get('/logout', [AuthController::class, 'logout']);

Route::get('/donate', [FrontendController::class, 'donate'])->name('donate');

Route::get('/cart', function() {
    return view('cart');
})->name('cart');

Route::get('/invest', [FrontendController::class, 'invest'])->name('invest');

// Investment-related routes
Route::post('/invest/save-info', [FrontendController::class, 'saveInvestmentInfo'])->name('invest.save-info');
Route::post('/invest/process-investment', [FrontendController::class, 'processInvestment'])->name('invest.process');
Route::get('/invest/thank-you', [FrontendController::class, 'investmentThankYou'])->name('invest.thank-you');
Route::get('/invest/status/{id}', [FrontendController::class, 'investmentStatus'])->name('invest.status');
Route::post('/invest/contact', [FrontendController::class, 'investmentContact'])->name('invest.contact');
Route::get('/invest/terms', [FrontendController::class, 'investmentTerms'])->name('invest.terms');
Route::get('/invest/privacy', [FrontendController::class, 'investmentPrivacy'])->name('invest.privacy');

Route::post('/donations', [FrontendController::class, 'donation'])->name('donation');
Route::post('/student-message', [FrontendController::class, 'sendStudentMessage'])->name('student.message');

Route::post('/tickets', [FrontendController::class, 'tickets'])->name('tickets');
Route::get('/product/{slug}', [FrontendController::class, 'productDetails'])->name('product.details');

Route::post('/custom-form', [FrontendController::class, 'custom_form'])->name('custom-form');

Route::post('/donation-general', [FrontendController::class, 'donation_general'])->name('donation-general');

Route::get('/profile/{slug}', [FrontendController::class, 'student'])->name('donate');

Route::get('/leader-board', [FrontendController::class, 'leaderBoard'])->name('leader-board');

Route::get('/volunteer', [FrontendController::class, 'volunteer'])->name('volunteer');

Route::get('/photo', [FrontendController::class, 'photo'])->name('photo');

Route::get('/about', [FrontendController::class, 'about'])->name('about');

Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');

Route::post('/contact-form', [FrontendController::class, 'contact_form'])->name('contact-form');

// Newsletter subscription route
Route::post('/newsletter/subscribe', [FrontendController::class, 'newsletterSubscribe'])->name('newsletter.subscribe');

// QR Code Donation Routes (Public)
Route::get('/qr-donate', [\App\Http\Controllers\QRCodeDonationController::class, 'donate'])->name('qr.donate');
Route::post('/qr-donate/process', [\App\Http\Controllers\QRCodeDonationController::class, 'process'])->name('qr.donate.process');

Route::group(['prefix' => 'users', 'middleware' => 'auth'], function () {
    Route::get('/', [AdminController::class, 'donation']);

    Route::get('/setting', [AdminController::class, 'index']);

    Route::get('/direct_deposit', [AdminController::class, 'direct_deposit']);
    Route::post('/direct_deposit/store', [AdminController::class, 'direct_deposit_store']);

    Route::get('/mailed_deposit', [AdminController::class, 'mailed_deposit']);
    Route::post('/mailed_deposit/store', [AdminController::class, 'mailed_deposit_store']);

    Route::get('/wire_transfer', [AdminController::class, 'wire_transfer']);
    Route::post('/wire_transfer/store', [AdminController::class, 'wire_transfer_store']);

    Route::get('/tax', [AdminController::class, 'tax']);
    Route::post('/tax/store', [AdminController::class, 'tax_store']);

    Route::get('/tax-receipt', [AdminController::class, 'tax_receipt']);
    Route::post('/tax-receipt/store', [AdminController::class, 'tax_receipt_store']);


    Route::get('/profile', function () {
        $user = Auth::user()->load('website', 'website.setting');
        $showTutorial = false;
        $teachers = collect();
        if ($user->role == 'parents') {
            $showTutorial = !$user->parent_tutorial_seen;
            // Get teachers for the parent's website, sorted alphabetically by name (ignoring prefixes)
            $teachers = \App\Models\Teacher::where('website_id', $user->website_id)->get();
            $teachers = $teachers->sort(function($a, $b) {
                // Strip common prefixes for sorting
                $nameA = preg_replace('/^(Mr\.|Ms\.|Mrs\.|Dr\.)\s*/i', '', $a->name);
                $nameB = preg_replace('/^(Mr\.|Ms\.|Mrs\.|Dr\.)\s*/i', '', $b->name);
                return strcasecmp($nameA, $nameB);
            })->values();
        }
        return view('user.profile', compact('user', 'showTutorial', 'teachers'));
    });

    Route::post('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');

    // Investor Profile Routes
    Route::post('/investor-profile/save', [AuthController::class, 'saveInvestorProfile'])->name('investor-profile.save');
    Route::get('/investor-profile', [AuthController::class, 'getInvestorProfile'])->name('investor-profile.get');

    Route::get('/donation', [AdminController::class, 'donation']);
    Route::get('/payments', [AdminController::class, 'payments']);

    Route::get('/student',[
        AdminController::class, 'student'
    ])->name('admin.student');

    Route::get('/user/profile/{id}', [
        AdminController::class, 'userProfile'
    ])->name('admin.user.profile');

    Route::delete('/user/delete/{id}', [
        AdminController::class, 'deleteUser'
    ])->name('admin.user.delete');

    // Analytics Routes
    Route::get('/analytics', [\App\Http\Controllers\User\AnalyticsController::class, 'dashboard'])->name('users.analytics.dashboard');
    Route::get('/analytics/utm', [\App\Http\Controllers\User\AnalyticsController::class, 'utm'])->name('users.analytics.utm');

    // Notifications Routes
    Route::get('/notifications', [\App\Http\Controllers\User\NotificationController::class, 'settings'])->name('users.notifications.settings');

    // QR Codes Routes
    Route::get('/qr-codes', [\App\Http\Controllers\User\QRCodeController::class, 'index'])->name('users.qr-codes.index');
    Route::post('/qr-codes/generate', [\App\Http\Controllers\User\QRCodeController::class, 'generate'])->name('users.qr-codes.generate');
    Route::get('/qr-codes/{id}/download', [\App\Http\Controllers\User\QRCodeController::class, 'download'])->name('users.qr-codes.download');
    Route::post('/student/{student_id}/qr-code', [\App\Http\Controllers\User\QRCodeController::class, 'generateStudentQR'])->name('users.student-qr.generate');
    Route::post('/profile/qr-code', [\App\Http\Controllers\User\QRCodeController::class, 'generateProfileQR'])->name('users.profile-qr.generate');

    // User Behavior Routes
    Route::get('/hotjar/heatmaps', [\App\Http\Controllers\User\HotjarController::class, 'heatmaps'])->name('users.hotjar.heatmaps');
    Route::get('/hotjar/recordings', [\App\Http\Controllers\User\HotjarController::class, 'recordings'])->name('users.hotjar.recordings');

    // User Management Routes
    Route::get('/manage-users', [\App\Http\Controllers\User\UserManagementController::class, 'index'])->name('users.manage-users.index');
    Route::get('/manage-users/create', [\App\Http\Controllers\User\UserManagementController::class, 'create'])->name('users.manage-users.create');
    Route::post('/manage-users', [\App\Http\Controllers\User\UserManagementController::class, 'store'])->name('users.manage-users.store');
    Route::get('/manage-users/{id}/edit', [\App\Http\Controllers\User\UserManagementController::class, 'edit'])->name('users.manage-users.edit');
    Route::put('/manage-users/{id}', [\App\Http\Controllers\User\UserManagementController::class, 'update'])->name('users.manage-users.update');
    Route::delete('/manage-users/{id}', [\App\Http\Controllers\User\UserManagementController::class, 'destroy'])->name('users.manage-users.destroy');

    // Role Management Routes
    Route::get('/roles', [\App\Http\Controllers\User\RoleController::class, 'index'])->name('users.roles.index');
    Route::get('/roles/create', [\App\Http\Controllers\User\RoleController::class, 'create'])->name('users.roles.create');
    Route::post('/roles', [\App\Http\Controllers\User\RoleController::class, 'store'])->name('users.roles.store');
    Route::get('/roles/{id}/edit', [\App\Http\Controllers\User\RoleController::class, 'edit'])->name('users.roles.edit');
    Route::put('/roles/{id}', [\App\Http\Controllers\User\RoleController::class, 'update'])->name('users.roles.update');
    Route::delete('/roles/{id}', [\App\Http\Controllers\User\RoleController::class, 'destroy'])->name('users.roles.destroy');

    // Permission Management Routes
    Route::get('/permissions', [\App\Http\Controllers\User\PermissionController::class, 'index'])->name('users.permissions.index');
    Route::post('/permissions/assign', [\App\Http\Controllers\User\PermissionController::class, 'assign'])->name('users.permissions.assign');

    // Children Management Routes (for parents)
    Route::get('/children', [\App\Http\Controllers\User\ChildrenController::class, 'index'])->name('users.children.index');
    Route::get('/children/create', [\App\Http\Controllers\User\ChildrenController::class, 'create'])->name('users.children.create');
    Route::post('/children', [\App\Http\Controllers\User\ChildrenController::class, 'store'])->name('users.children.store');
    Route::get('/children/{id}/edit', [\App\Http\Controllers\User\ChildrenController::class, 'edit'])->name('users.children.edit');
    Route::put('/children/{id}', [\App\Http\Controllers\User\ChildrenController::class, 'update'])->name('users.children.update');
    Route::delete('/children/{id}', [\App\Http\Controllers\User\ChildrenController::class, 'destroy'])->name('users.children.destroy');
    
    // Parent add student route
    Route::post('/parent/add-student',[
        AdminController::class, 'addStudentByParent'
    ])->name('parent.add-student');
    
    // Parent tutorial route
    Route::post('/parent/tutorial/seen', [
        AdminController::class, 'markTutorialSeen'
    ])->name('parent.tutorial.seen');
    
    Route::get('/student/profile/{id}', [
        AdminController::class, 'editStudentProfile'
    ])->name('parent.edit-student');
    
    Route::post('/student/profile/{id}', [
        AdminController::class, 'updateStudentProfile'
    ])->name('parent.update-student');
});

    Route::post('/admins/store',[AdminController::class, 'store'])->name('admin.store');

    Route::get('/admins/approve/{id}',[
        AdminController::class, 'approve'
    ])->name('admin.approve');

    Route::get('/admins/student/approve/{id}',[
        AdminController::class, 'student_approve'
    ])->name('admin.student.approve');

Route::group(['prefix' => 'admins', 'middleware' => ['auth',admin::class]], function () {
    Route::post('/students/mass-approve',[
        AdminController::class, 'mass_approve_students'
    ])->name('admin.students.mass-approve');
    Route::get('/', [
        AdminController::class, 'index'
    ])->name('admin.index');

    Route::get('/setting/{id}', [
        AdminController::class, 'setting'
    ])->name('admin.setting');


    Route::get('/change-password', [
        AdminController::class, 'change_password'
    ])->name('admin.change-password');

    Route::post('/change-password', [
        AdminController::class, 'update_password'
    ])->name('admin.update-password');

    Route::get('/tax/show/{id}',[AdminController::class, 'tax_show'])->name('admin.tax.show');

    Route::get('/tax-list',[AdminController::class, 'tax_list'])->name('admin.tax.list');

    Route::get('/tax-receipt/show/{id}',[AdminController::class, 'tax_receipt_show'])->name('admin.tax-receipt.show');

    Route::get('/tax-receipt-list',[AdminController::class, 'tax_receipt_list'])->name('admin.tax-receipt.list');

    Route::get('/auction/{id}',[AdminController::class, 'auction_edit'])->name('admin.auction.edit');

    Route::get('/auction/add/{id}',[AdminController::class, 'auction_add'])->name('admin.add');

    Route::get('/menu',[AdminController::class, 'menu_index'])->name('admin.menu');

    Route::get('/auction',[AdminController::class, 'auction_index'])->name('admin.auction');

    Route::get('/auction/{id}',[AdminController::class, 'auction_edit'])->name('admin.auction.edit');

    Route::get('/auction/add/{id}',[AdminController::class, 'auction_add'])->name('admin.add');

    Route::get('/auction-edit/{id}',[AdminController::class, 'auction_edit_auction'])->name('admin.edit-auction');

    Route::post('/auction/store',[AdminController::class, 'store_auction'])->name('admin.auction.store');

    Route::post('/auction/update/{id}',[AdminController::class, 'update_auction'])->name('admin.auction.update');

    Route::post('/auction/update-status/{id}',[AdminController::class, 'update_auction_status'])->name('admin.auction.update-status');

    Route::get('/auction/{auctionId}/bids',[AdminController::class, 'getAuctionBids'])->name('admin.auction.bids');

    Route::get('/menu/{id}',[AdminController::class, 'menu'])->name('admin.menu');

    Route::post('/menu/store',[AdminController::class, 'store_menu'])->name('admin.menu.store');

    Route::get('/payment',[AuthorizeNetController::class, 'setting'])->name('admin.payment.setting');

    Route::post('/payment/update',[AuthorizeNetController::class, 'update'])->name('admin.payment.update');

    Route::get('/payout-methods',[AdminController::class, 'payment_method'])->name('admin.payment-method');

    Route::get('/payment_method/{id}',[AdminController::class, 'payment_method_details'])->name('admin.payment-method.details');

    Route::get('/footer',[AdminController::class, 'footer_index'])->name('admin.footer');

    Route::get('/footer/{id}',[AdminController::class, 'footer'])->name('admin.footer');

    Route::post('/footer/store',[AdminController::class, 'store_footer'])->name('admin.footer.store');

    // Menu Builder Routes
    Route::prefix('menus')->name('admin.menus.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\MenuController::class, 'websites'])->name('index');
        Route::get('/website/{websiteId}', [\App\Http\Controllers\Admin\MenuController::class, 'index'])->name('list');
        Route::get('/website/{websiteId}/create', [\App\Http\Controllers\Admin\MenuController::class, 'create'])->name('create');
        Route::post('/website/{websiteId}/store', [\App\Http\Controllers\Admin\MenuController::class, 'store'])->name('store');
        Route::get('/website/{websiteId}/{id}/edit', [\App\Http\Controllers\Admin\MenuController::class, 'edit'])->name('edit');
        Route::put('/website/{websiteId}/{id}', [\App\Http\Controllers\Admin\MenuController::class, 'update'])->name('update');
        Route::delete('/website/{websiteId}/{id}', [\App\Http\Controllers\Admin\MenuController::class, 'destroy'])->name('destroy');
        Route::post('/website/{websiteId}/{id}/update-items', [\App\Http\Controllers\Admin\MenuController::class, 'updateMenuItems'])->name('update-items');
        Route::post('/website/{websiteId}/{id}/add-item', [\App\Http\Controllers\Admin\MenuController::class, 'addItem'])->name('add-item');
        Route::delete('/website/{websiteId}/{id}/remove-item/{itemId}', [\App\Http\Controllers\Admin\MenuController::class, 'removeItem'])->name('remove-item');
    });

    // Newsletter management routes
    Route::get('/newsletter',[AdminController::class, 'newsletter_index'])->name('admin.newsletter');
    Route::get('/newsletter/{website_id}',[AdminController::class, 'newsletter_manage'])->name('admin.newsletter.manage');
    Route::post('/newsletter/send-email',[AdminController::class, 'newsletter_send_email'])->name('admin.newsletter.send');
    Route::delete('/newsletter/subscription/{id}',[AdminController::class, 'newsletter_delete_subscription'])->name('admin.newsletter.delete');
    Route::post('/newsletter/export/{website_id}',[AdminController::class, 'newsletter_export'])->name('admin.newsletter.export');

    // Comment management routes
    Route::get('/comments',[AdminController::class, 'comments_index'])->name('admin.comments');
    Route::post('/comments/{id}/reply',[AdminController::class, 'comments_reply'])->name('admin.comments.reply');
    Route::delete('/comments/{id}',[AdminController::class, 'comments_delete'])->name('admin.comments.delete');

    Route::get('/donation', [
        AdminController::class, 'donation'
    ])->name('admin.donation');

    Route::post('/transactions/update-status', [
        AdminController::class, 'updateTransactionStatus'
    ])->name('admin.transactions.update-status');

    Route::get('/transactions/{transactionId}/download-invoice', [
        AdminController::class, 'downloadTransactionInvoice'
    ])->name('admin.transactions.download-invoice');

    Route::post('/transactions/{transactionId}/resend-invoice', [
        AdminController::class, 'resendTransactionInvoice'
    ])->name('admin.transactions.resend-invoice');

    // Test routes for invoice functionality
    Route::get('/test/invoice-pdf', [
        \App\Http\Controllers\InvoiceTestController::class, 'testInvoice'
    ])->name('admin.test.invoice-pdf');

    Route::get('/test/invoice-email', [
        \App\Http\Controllers\InvoiceTestController::class, 'testEmail'
    ])->name('admin.test.invoice-email');

    // Debug route for fees and SSN
    Route::get('/debug/fees-ssn', [
        \App\Http\Controllers\DebugController::class, 'debugFeesAndSSN'
    ])->name('admin.debug.fees-ssn');
    
    // Email testing routes
    Route::get('/debug/test-email', [
        \App\Http\Controllers\EmailTestController::class, 'testEmail'
    ])->name('admin.debug.test-email');
    
    Route::get('/debug/test-invoice-email', [
        \App\Http\Controllers\EmailTestController::class, 'testInvoiceEmail'
    ])->name('admin.debug.test-invoice-email');
    
    Route::get('/debug/test-investment-email', [
        \App\Http\Controllers\EmailTestController::class, 'testInvestmentEmail'
    ])->name('admin.debug.test-investment-email');

    Route::get('/student',[
        AdminController::class, 'student'
    ])->name('admin.student');

    // User management (custom role-based)
    Route::get('/users/create', [\App\Http\Controllers\Admin\UserController::class, 'create'])
        ->name('admin.users.create')
        ->middleware('role:superadmin|website_owner');

    Route::post('/users', [\App\Http\Controllers\Admin\UserController::class, 'store'])
        ->name('admin.users.store')
        ->middleware('role:superadmin|website_owner');

    Route::get('/users/{id}/edit', [\App\Http\Controllers\Admin\UserController::class, 'edit'])
        ->name('admin.users.edit')
        ->middleware('role:superadmin|website_owner');

    Route::put('/users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'update'])
        ->name('admin.users.update')
        ->middleware('role:superadmin|website_owner');

    // Role management (simple)
    Route::get('/roles', [\App\Http\Controllers\Admin\RoleController::class, 'index'])
        ->name('admin.roles.index')
        ->middleware('role:superadmin');

    Route::get('/roles/create', [\App\Http\Controllers\Admin\RoleController::class, 'create'])
        ->name('admin.roles.create')
        ->middleware('role:superadmin');

    Route::post('/roles', [\App\Http\Controllers\Admin\RoleController::class, 'store'])
        ->name('admin.roles.store')
        ->middleware('role:superadmin');

    Route::delete('/roles/{id}', [\App\Http\Controllers\Admin\RoleController::class, 'destroy'])
        ->name('admin.roles.destroy')
        ->middleware('role:superadmin');

    route::group(['prefix' => 'website'], function () {
        Route::get('/', [
            WebsiteController::class, 'index'
        ])->name('admin.website.index');

        Route::get('/create', [
            WebsiteController::class, 'create'
        ])->name('admin.website.create');

        Route::post('/store', [
            WebsiteController::class, 'store'
        ])->name('admin.website.store');

        Route::get('/edit/{id}', [
            WebsiteController::class, 'edit'
        ])->name('admin.website.edit');

        Route::post('/update/{id}', [
            WebsiteController::class, 'update'
        ])->name('admin.website.update');

        Route::get('/delete/{id}', [
            WebsiteController::class, 'delete'
        ])->name('admin.website.delete');

        // Payment settings routes
        Route::get('/{website}/payment-settings', [
            WebsitePaymentController::class, 'show'
        ])->name('admin.websites.payment.show');

        Route::put('/{website}/payment-settings', [
            WebsitePaymentController::class, 'update'
        ])->name('admin.websites.payment.update');

        Route::post('/{website}/payment-settings/test', [
            WebsitePaymentController::class, 'test'
        ])->name('admin.websites.payment.test');

        Route::delete('/{website}/payment-settings', [
            WebsitePaymentController::class, 'destroy'
        ])->name('admin.websites.payment.destroy');
    });

    // Teacher Management Routes (Admin)
    Route::prefix('teachers')->name('admin.teachers.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\TeacherController::class, 'websites'])->name('websites');
        Route::get('/website/{websiteId}', [\App\Http\Controllers\Admin\TeacherController::class, 'index'])->name('index');
        Route::get('/create/{websiteId}', [\App\Http\Controllers\Admin\TeacherController::class, 'create'])->name('create');
        Route::post('/store', [\App\Http\Controllers\Admin\TeacherController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [\App\Http\Controllers\Admin\TeacherController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [\App\Http\Controllers\Admin\TeacherController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [\App\Http\Controllers\Admin\TeacherController::class, 'destroy'])->name('delete');
    });

    Route::prefix('ticket')->name('admin.ticket.')->group(function () {
        Route::get('/', [TicketController::class, 'websites'])->name('websites');
        Route::get('/website/{websiteId}', [TicketController::class, 'index'])->name('index');
        Route::get('/create', [TicketController::class, 'create'])->name('create');
        Route::post('/store', [TicketController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [TicketController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [TicketController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [TicketController::class, 'destroy'])->name('delete');
    });

    Route::prefix('ticket-category')->name('admin.ticket-category.')->group(function () {
        Route::get('/', [TicketCategoryController::class, 'index'])->name('index');
        Route::get('/create', [TicketCategoryController::class, 'create'])->name('create');
        Route::post('/store', [TicketCategoryController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [TicketCategoryController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [TicketCategoryController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [TicketCategoryController::class, 'destroy'])->name('delete');
    });

    Route::prefix('property-category')->name('admin.property-category.')->group(function () {
        Route::get('/', [PropertyCategoryController::class, 'index'])->name('index');
        Route::get('/create', [PropertyCategoryController::class, 'create'])->name('create');
        Route::post('/store', [PropertyCategoryController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [PropertyCategoryController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [PropertyCategoryController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [PropertyCategoryController::class, 'destroy'])->name('destroy');
        Route::get('/by-website/{websiteId}', [PropertyCategoryController::class, 'getByWebsite'])->name('by-website');
    });

    Route::prefix('sponsor')->name('admin.sponsor.')->group(function () {
        Route::get('/', [SponsorController::class, 'index'])->name('index');
        Route::get('/create', [SponsorController::class, 'create'])->name('create');
        Route::post('/store', [SponsorController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [SponsorController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [SponsorController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [SponsorController::class, 'destroy'])->name('delete');
    });

    route::group(['prefix' => 'page'], function () {
        Route::get('/', [
            PageBuilderController::class, 'websites'
        ])->name('admin.page.websites');
        
        Route::get('/main-site', [
            PageBuilderController::class, 'mainSitePages'
        ])->name('admin.page.main-site');
        
        Route::get('/website/{websiteId}', [
            PageBuilderController::class, 'index'
        ])->name('admin.page.index');

        Route::get('/create', [
            PageBuilderController::class, 'create'
        ])->name('admin.page.create');

        Route::post('/store', [
            PageBuilderController::class, 'store'
        ])->name('admin.page.store');

        Route::get('/show/{id}', [
            PageBuilderController::class, 'show'
        ])->name('admin.page.show');

        Route::get('/edit/{id}', [
            PageBuilderController::class, 'edit'
        ])->name('admin.page.edit');

        Route::post('/update/{id}', [
            PageBuilderController::class, 'update'
        ])->name('admin.page.update');

        Route::get('/delete/{id}', [
            PageBuilderController::class, 'delete'
        ])->name('admin.page.delete');

        Route::post('/save/{id}',
         [PageBuilderController::class, 'save'
        ])->name('admin.page.save');

        Route::get('/load/{id}', [PageBuilderController::class, 'load'
        ])->name('admin.page.load');

        // Component properties routes
        Route::get('/component-properties/{component}', [
            PageBuilderController::class, 'componentProperties'
        ])->name('admin.page.component-properties');
    });

    // Template management routes
    route::group(['prefix' => 'templates'], function () {
        Route::get('/', [
            \App\Http\Controllers\PageTemplateController::class, 'index'
        ])->name('admin.templates.index');

        Route::get('/create', [
            \App\Http\Controllers\PageTemplateController::class, 'create'
        ])->name('admin.templates.create');

        Route::post('/store', [
            \App\Http\Controllers\PageTemplateController::class, 'store'
        ])->name('admin.templates.store');

        Route::get('/show/{template}', [
            \App\Http\Controllers\PageTemplateController::class, 'show'
        ])->name('admin.templates.show');

        Route::get('/edit/{template}', [
            \App\Http\Controllers\PageTemplateController::class, 'edit'
        ])->name('admin.templates.edit');

        Route::put('/update/{template}', [
            \App\Http\Controllers\PageTemplateController::class, 'update'
        ])->name('admin.templates.update');

        Route::delete('/destroy/{template}', [
            \App\Http\Controllers\PageTemplateController::class, 'destroy'
        ])->name('admin.templates.destroy');

        Route::get('/preview/{template}', [
            \App\Http\Controllers\PageTemplateController::class, 'preview'
        ])->name('admin.templates.preview');

        // AJAX routes
        Route::get('/get-templates', [
            \App\Http\Controllers\PageTemplateController::class, 'getTemplates'
        ])->name('admin.templates.get');

        Route::post('/save-from-page/{page}', [
            \App\Http\Controllers\PageTemplateController::class, 'saveFromPage'
        ])->name('admin.templates.save-from-page');

        Route::post('/apply-to-page/{template}/{page}', [
            \App\Http\Controllers\PageTemplateController::class, 'applyToPage'
        ])->name('admin.templates.apply-to-page');
    });

    // Image upload route for page builder
    Route::post('/upload-image', [AdminController::class, 'uploadImage'])->name('admin.upload.image');
    
    // Get upload configuration limits
    Route::get('/upload-config', [AdminController::class, 'getUploadConfig'])->name('admin.upload.config');
    
    // Video upload route for page builder
    Route::post('/upload-video', [AdminController::class, 'uploadVideo'])->name('admin.upload.video');

    // Section Template Routes
    Route::prefix('section-templates')->name('section-templates.')->group(function() {
        Route::post('/save', [
            \App\Http\Controllers\SectionTemplateController::class, 'save'
        ])->name('save');
        
        Route::get('/list', [
            \App\Http\Controllers\SectionTemplateController::class, 'list'
        ])->name('list');
        
        Route::get('/get/{id}', [
            \App\Http\Controllers\SectionTemplateController::class, 'get'
        ])->name('get');
        
        Route::delete('/delete/{id}', [
            \App\Http\Controllers\SectionTemplateController::class, 'delete'
        ])->name('delete');
    });

    // DealMaker Admin Routes
    Route::get('/dealmaker-settings', [App\Http\Controllers\DealmakerAdminController::class, 'index'])->name('dealmaker.admin.index');
    Route::post('/dealmaker-settings', [App\Http\Controllers\DealmakerAdminController::class, 'update'])->name('dealmaker.admin.update');
    Route::post('/dealmaker-settings/add-logo', [App\Http\Controllers\DealmakerAdminController::class, 'addLogo'])->name('dealmaker.admin.add-logo');
    Route::delete('/dealmaker-settings/remove-logo/{index}', [App\Http\Controllers\DealmakerAdminController::class, 'removeLogo'])->name('dealmaker.admin.remove-logo');

});

// Temporary test route for DealMaker admin (REMOVE AFTER TESTING)
Route::get('/test-dealmaker-admin', function() {
    $setting = App\Models\DealmakerConfig::getInstance();
    return response()->json([
        'current_settings' => $setting->toArray(),
        'message' => 'DealMaker config loaded successfully'
    ]);
});

Route::post('/test-dealmaker-save', function(Illuminate\Http\Request $request) {
    $setting = App\Models\DealmakerConfig::getInstance();
    
    $testData = [
        'meta_title' => 'Test Title - ' . now(),
        'hero_title' => 'Test Hero - ' . now()
    ];
    
    $result = $setting->update($testData);
    
    return response()->json([
        'result' => $result,
        'updated_settings' => $setting->fresh()->toArray(),
        'message' => $result ? 'Save successful' : 'Save failed'
    ]);
});

// Temporary test route for video upload (remove after testing)
Route::post('/test-upload-video', function(Illuminate\Http\Request $request) {
    try {
        $request->validate([
            'video' => 'required|file|mimes:mp4,webm,ogg,avi,mov,wmv|max:10240',
        ]);

        $video = $request->file('video');
        $videoName = time() . '_test_' . uniqid() . '.' . $video->getClientOriginalExtension();
        
        $video->move(public_path('uploads'), $videoName);
        $videoUrl = asset('uploads/' . $videoName);

        return response()->json([
            'success' => true,
            'url' => $videoUrl,
            'message' => 'Video uploaded successfully (test route)'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Upload failed: ' . $e->getMessage()
        ], 400);
    }
});

// --- Ticket Auth/Verification AJAX Endpoints ---
Route::post('/ajax/ticket-auth/register', function(Request $request) {
    try {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'name' => 'required|string|max:255'
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        $errors = $e->validator->errors()->all();
        return response()->json([
            'success' => false, 
            'message' => implode(' ', $errors)
        ], 422);
    }
    
    $user = User::where('email', $request->email)->first();
    if ($user) {
        return response()->json(['success' => false, 'message' => 'Email already registered. Please login.'], 409);
    }
    $code = rand(100000, 999999);
    $user = User::create([
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'name' => $request->name,
        'role' => 'customer',
    ]);
    // assign website if found by domain
    try {
        $url = url()->current();
        $domain = parse_url($url, PHP_URL_HOST);
        $check = Website::where('domain', $domain)->first();
        if ($check) {
            $user->website_id = $check->id;
            $user->save();
        }
    } catch (\Exception $e) {
        // ignore website assignment
    }
    // set verification code
    $user->email_verification_code = $code;
    $user->email_verified_at = null;
    $user->save();
    // Send code with website-specific email settings
    \App\Services\WebsiteMailService::sendForUser($user, 'emails.verification-code', ['code' => $code, 'name' => $user->name], function($m) use ($user) {
        $m->to($user->email)->subject('Verify Your Account - Registration Verification Code');
    });
    return response()->json(['success' => true, 'message' => 'Verification code sent.']);
});

Route::post('/ajax/ticket-auth/login', function(Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);
    $user = User::where('email', $request->email)->first();
    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['success' => false, 'message' => 'Invalid credentials.'], 401);
    }
    if (!$user->email_verified_at) {
        $code = rand(100000, 999999);
        $user->email_verification_code = $code;
        $user->save();
        \App\Services\WebsiteMailService::sendForUser($user, 'emails.verification-code', ['code' => $code, 'name' => $user->name], function($m) use ($user) {
            $m->to($user->email)->subject('Verify Your Account - Registration Verification Code');
        });
        return response()->json(['success' => false, 'message' => 'Email not verified. Verification code sent.', 'require_verification' => true]);
    }
    Auth::login($user);
    $request->session()->regenerate(); // Regenerate session to get new CSRF token
    return response()->json(['success' => true, 'csrf_token' => csrf_token()]);
});

Route::post('/ajax/ticket-auth/verify', function(Request $request) {
    $request->validate([
        'email' => 'required|email',
        'code' => 'required',
    ]);
    $user = User::where('email', $request->email)->first();
    if (!$user || $user->email_verification_code !== $request->code) {
        return response()->json(['success' => false, 'message' => 'Invalid verification code.'], 422);
    }
    $user->email_verified_at = now();
    $user->email_verification_code = null;
    $user->save();
    Auth::login($user);
    $request->session()->regenerate(); // Regenerate session to get new CSRF token
    return response()->json(['success' => true, 'csrf_token' => csrf_token()]);
});

// Resend verification code
Route::post('/ajax/ticket-auth/resend-code', function(Request $request) {
    $request->validate([
        'email' => 'required|email'
    ]);
    $user = User::where('email', $request->email)->first();
    if (!$user) {
        return response()->json(['success' => false, 'message' => 'User not found.'], 404);
    }
    if ($user->email_verified_at) {
        return response()->json(['success' => true, 'message' => 'Email already verified.']);
    }

    $code = rand(100000, 999999);
    $user->email_verification_code = $code;
    $user->save();

    \App\Services\WebsiteMailService::sendForUser($user, 'emails.verification-code', ['code' => $code, 'name' => $user->name ?? ''], function($m) use ($user) {
        $m->to($user->email)->subject('Verify Your Account - Verification Code');
    });

    return response()->json(['success' => true, 'message' => 'Verification code resent.']);
});

Route::post('/ajax/ticket-auth/check', function(Request $request) {
    $user = Auth::user();
    return response()->json([
        'authenticated' => (bool) $user,
        'verified' => $user ? (bool) $user->email_verified_at : false
    ]);
});

Route::get('/refresh-csrf', function() {
    return response()->json(['token' => csrf_token()]);
});

Route::post('/clear-intended-url', function(Request $request) {
    $request->session()->forget('url.intended');
    return response()->json(['success' => true]);
});

// --- Forgot Password AJAX Endpoints ---
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

Route::post('/ajax/ticket-auth/forgot-request', function(Request $request) {
    $request->validate(['email' => 'required|email']);
    $user = \App\Models\User::where('email', $request->email)->first();
    if (!$user) {
        return response()->json(['success' => false, 'message' => 'No user found with that email.'], 404);
    }
    $code = rand(100000, 999999);
    $user->password_reset_code = $code;
    $user->password_reset_expires = now()->addMinutes(15);
    $user->save();
    \App\Services\WebsiteMailService::sendForUser($user, 'emails.password-reset-code', ['code' => $code, 'name' => $user->name], function($m) use ($user) {
        $m->to($user->email)->subject('Password Reset Code');
    });
    return response()->json(['success' => true, 'message' => 'Reset code sent to your email.']);
});

Route::post('/ajax/ticket-auth/forgot-verify', function(Request $request) {
    $request->validate([
        'email' => 'required|email',
        'code' => 'required',
    ]);
    $user = \App\Models\User::where('email', $request->email)->first();
    if (!$user || !$user->password_reset_code || !$user->password_reset_expires) {
        return response()->json(['success' => false, 'message' => 'No reset request found.'], 404);
    }
    if ($user->password_reset_code !== $request->code) {
        return response()->json(['success' => false, 'message' => 'Invalid reset code.'], 422);
    }
    if (\Carbon\Carbon::parse($user->password_reset_expires)->isPast()) {
        return response()->json(['success' => false, 'message' => 'Reset code expired.'], 422);
    }
    return response()->json(['success' => true, 'message' => 'Code verified.']);
});

Route::post('/ajax/ticket-auth/forgot-reset', function(Request $request) {
    $request->validate([
        'email' => 'required|email',
        'code' => 'required',
        'password' => 'required|min:6',
    ]);
    $user = \App\Models\User::where('email', $request->email)->first();
    if (!$user || !$user->password_reset_code || !$user->password_reset_expires) {
        return response()->json(['success' => false, 'message' => 'No reset request found.'], 404);
    }
    if ($user->password_reset_code !== $request->code) {
        return response()->json(['success' => false, 'message' => 'Invalid reset code.'], 422);
    }
    if (\Carbon\Carbon::parse($user->password_reset_expires)->isPast()) {
        return response()->json(['success' => false, 'message' => 'Reset code expired.'], 422);
    }
    $user->password = Hash::make($request->password);
    $user->password_reset_code = null;
    $user->password_reset_expires = null;
    $user->save();
    return response()->json(['success' => true, 'message' => 'Password reset successful. You can now log in.']);
});
// --- End Ticket Auth/Verification AJAX Endpoints ---

// Shopping Cart Routes
Route::prefix('api/cart')->group(function () {
    Route::post('/add', [App\Http\Controllers\CartController::class, 'add'])->name('cart.api.add');
    Route::get('/', [App\Http\Controllers\CartController::class, 'get'])->name('cart.api.get');
    Route::put('/item/{key}', [App\Http\Controllers\CartController::class, 'update'])->name('cart.api.update');
    Route::delete('/item/{key}', [App\Http\Controllers\CartController::class, 'remove'])->name('cart.api.remove');
    Route::delete('/clear', [App\Http\Controllers\CartController::class, 'clear'])->name('cart.api.clear');
    Route::get('/count', [App\Http\Controllers\CartController::class, 'getCount'])->name('cart.api.count');
    Route::get('/validate', [App\Http\Controllers\CartController::class, 'validate'])->name('cart.api.validate');
});

// Checkout Routes
Route::get('/checkout', [App\Http\Controllers\CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/checkout', [App\Http\Controllers\CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/success', [App\Http\Controllers\CheckoutController::class, 'success'])->name('checkout.success');


