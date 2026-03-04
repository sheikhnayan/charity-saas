<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PageBuilderController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\BidController;
use App\Http\Controllers\Api\FunnelTrackingController;
use App\Http\Controllers\Api\PushNotificationController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\SessionRecordingController;
use App\Http\Controllers\HeatmapController;
use Illuminate\Support\Facades\Log;

Route::middleware('auth')->group(function () {
    Route::post('/page-builder/save', [PageBuilderController::class, 'save']);
    Route::get('/page-builder/load', [PageBuilderController::class, 'load']);
});

Route::post('/donation-general', [FrontendController::class, 'donation_general'])->name('donation-general');

// Payment Funnel Tracking API routes (no auth required for public tracking)
Route::post('/track-funnel', [FunnelTrackingController::class, 'trackEvent']);
Route::post('/track-funnel/bulk', [FunnelTrackingController::class, 'bulkTrackEvents']);
Route::get('/funnel-progress', [FunnelTrackingController::class, 'getSessionProgress']);
Route::get('/funnel-step-check', [FunnelTrackingController::class, 'checkStepCompletion']);

// Hotjar-style Session Recording API routes (no auth required for public tracking)
Route::prefix('session-recording')->group(function () {
    Route::post('/start', [SessionRecordingController::class, 'start']);
    Route::post('/events', [SessionRecordingController::class, 'storeEvents']);
    Route::post('/complete', [SessionRecordingController::class, 'complete']);
    Route::get('/{recordingId}', [SessionRecordingController::class, 'getSession'])->middleware('web', 'auth');
    Route::get('/', [SessionRecordingController::class, 'list'])->middleware('web', 'auth');
    Route::delete('/{recordingId}', [SessionRecordingController::class, 'delete'])->middleware('web', 'auth');
    Route::post('/{recordingId}/star', [SessionRecordingController::class, 'toggleStar'])->middleware('web', 'auth');
    Route::post('/{recordingId}/meta', [SessionRecordingController::class, 'updateMeta'])->middleware('web', 'auth');
});

// Hotjar-style Heatmap API routes (no auth required for public tracking)
Route::prefix('heatmap')->group(function () {
    Route::post('/track', [HeatmapController::class, 'trackEvent']);
    Route::post('/track/batch', [HeatmapController::class, 'trackBatch']);
    Route::get('/click', [HeatmapController::class, 'getClickHeatmap'])->middleware('web', 'auth');
    Route::get('/move', [HeatmapController::class, 'getMoveHeatmap'])->middleware('web', 'auth');
    Route::get('/scroll', [HeatmapController::class, 'getScrollHeatmap'])->middleware('web', 'auth');
    Route::get('/aggregated', [HeatmapController::class, 'getAggregatedHeatmap'])->middleware('web', 'auth');
    Route::get('/popular-pages', [HeatmapController::class, 'getPopularPages'])->middleware('web', 'auth');
    Route::get('/element-stats', [HeatmapController::class, 'getElementStats'])->middleware('web', 'auth');
    Route::get('/screenshot', [HeatmapController::class, 'getScreenshot'])->middleware('web', 'auth');
    Route::post('/screenshot/capture', [HeatmapController::class, 'captureScreenshot'])->middleware('web', 'auth');
});

// Public comment routes (no auth required for posting comments)
Route::post('/comments', [CommentController::class, 'store']);
Route::get('/comments', [CommentController::class, 'index']);

// Auction bid routes (public - no auth required)
Route::post('/auction/bid', [BidController::class, 'store']);
Route::get('/auction/{auctionId}/latest-bid', [BidController::class, 'getLatestBid']);
Route::get('/auction/{auctionId}/bids', [BidController::class, 'getBids']);

// Simple logger endpoint for debugging
Route::post('/logger', function (Request $request) {
    $level = $request->input('level', 'info');
    $source = $request->input('source', 'frontend');
    $reason = $request->input('reason', 'unknown');
    $data = $request->input('data', []);
    
    $logMessage = "Frontend Log - Source: $source, Reason: $reason";
    
    switch ($level) {
        case 'error':
            Log::error($logMessage, ['data' => $data]);
            break;
        case 'warning':
            Log::warning($logMessage, ['data' => $data]);
            break;
        case 'debug':
            Log::debug($logMessage, ['data' => $data]);
            break;
        default:
            Log::info($logMessage, ['data' => $data]);
    }
    
    return response()->json(['status' => 'logged'], 200);
});
Route::post('/investor', function (Request $request) {
    $dealId = $request->query('dealId');
    $payload = $request->all();
    
    Log::info('DealMaker Investor POST', [
        'dealId' => $dealId,
        'payload' => $payload
    ]);
    
    // Return DealMaker-compatible response format
    return response()->json([
        'success' => true,
        'investor' => [
            'id' => rand(1000, 9999),
            'dealId' => $dealId,
            'status' => 'pending',
            'created_at' => now()->toISOString(),
            'data' => $payload
        ]
    ], 200);
});

Route::put('/investor', function (Request $request) {
    $dealId = $request->query('dealId');
    $payload = $request->all();
    
    Log::info('DealMaker Investor PUT', [
        'dealId' => $dealId,
        'payload' => $payload
    ]);
    
    // Return DealMaker-compatible response format
    return response()->json([
        'success' => true,
        'investor' => [
            'id' => $payload['id'] ?? rand(1000, 9999),
            'dealId' => $dealId,
            'status' => 'updated',
            'updated_at' => now()->toISOString(),
            'data' => $payload
        ]
    ], 200);
});

Route::post('/deal/{dealId}/investors', function (Request $request, $dealId) {
    $email = $request->query('email');
    $payload = $request->all();
    
    Log::info('DealMaker Deal Investors', [
        'dealId' => $dealId,
        'email' => $email,
        'payload' => $payload
    ]);
    
    // Return DealMaker-compatible response format
    return response()->json([
        'success' => true,
        'deal' => [
            'id' => $dealId,
            'status' => 'active'
        ],
        'investor' => [
            'id' => rand(1000, 9999),
            'email' => $email,
            'status' => 'verified',
            'tags' => $payload['tags'] ?? [],
            'mode' => $payload['mode'] ?? 'standard'
        ]
    ], 200);
});

Route::post('/investor-profile/{type}', function (Request $request, $type) {
    $dealId = $request->query('dealId');
    $payload = $request->all();
    
    Log::info('DealMaker Investor Profile', [
        'type' => $type,
        'dealId' => $dealId,
        'payload' => $payload
    ]);
    
    // Return DealMaker-compatible response format
    return response()->json([
        'success' => true,
        'profile' => [
            'id' => rand(1000, 9999),
            'type' => $type,
            'dealId' => $dealId,
            'status' => 'created',
            'data' => $payload
        ]
    ], 200);
});

Route::post('/klaviyo-v5/subscribe', function (Request $request) {
    $payload = $request->all();
    
    Log::info('DealMaker Klaviyo Subscribe', [
        'payload' => $payload
    ]);
    
    // Return Klaviyo-compatible response format
    return response()->json([
        'success' => true,
        'subscription' => [
            'id' => rand(1000, 9999),
            'email' => $payload['email'] ?? null,
            'status' => 'subscribed',
            'list_id' => 'default'
        ]
    ], 200);
});

// Push Notification API Routes
Route::prefix('notifications')->middleware(['web', 'auth'])->group(function () {
    Route::post('/save-token', [PushNotificationController::class, 'saveToken']);
    Route::post('/delete-token', [PushNotificationController::class, 'deleteToken']);
    Route::get('/devices', [PushNotificationController::class, 'getDevices']);
    Route::get('/list', [PushNotificationController::class, 'getNotifications']);
    Route::post('/{id}/read', [PushNotificationController::class, 'markAsRead']);
    Route::post('/mark-all-read', [PushNotificationController::class, 'markAllAsRead']);
    Route::get('/unread-count', [PushNotificationController::class, 'getUnreadCount']);
    Route::get('/preferences', [PushNotificationController::class, 'getPreferences']);
    Route::post('/preferences', [PushNotificationController::class, 'updatePreferences']);
    Route::post('/test', [PushNotificationController::class, 'testNotification']);
});

// DealMaker logger endpoint
Route::post('/logger', function (Request $request) {
    $level = $request->input('level', 'info');
    $source = $request->input('source', 'dealmaker');
    $reason = $request->input('reason', 'unknown');
    $data = $request->input('data', []);
    
    $logMessage = "DealMaker Log - Source: $source, Reason: $reason";
    
    switch ($level) {
        case 'error':
            Log::error($logMessage, ['data' => $data]);
            break;
        case 'warning':
            Log::warning($logMessage, ['data' => $data]);
            break;
        case 'debug':
            Log::debug($logMessage, ['data' => $data]);
            break;
        default:
            Log::info($logMessage, ['data' => $data]);
    }
    
    return response()->json(['status' => 'logged'], 200);
});

// Public API: Lists for QR donate selection (website-scoped by domain)
Route::get('/auctions', function (Request $request) {
    try {
        $host = $request->getHost();
        $website = \App\Models\Website::where('domain', $host)->first();
        if (!$website && auth('web')->check()) {
            $website = auth('web')->user()->website;
        }
        if (!$website) {
            return response()->json(['success' => false, 'items' => []]);
        }
        $auctions = \App\Models\Auction::where('website_id', $website->id)
            ->orderByDesc('id')
            ->get(['id','title','value']);
        return response()->json(['success' => true, 'auctions' => $auctions]);
    } catch (\Throwable $e) {
        return response()->json(['success' => false, 'items' => [], 'message' => $e->getMessage()], 500);
    }
});

Route::get('/tickets', function (Request $request) {
    try {
        $host = $request->getHost();
        $website = \App\Models\Website::where('domain', $host)->first();
        if (!$website && auth('web')->check()) {
            $website = auth('web')->user()->website;
        }
        if (!$website) {
            return response()->json(['success' => false, 'items' => []]);
        }
        $tickets = \App\Models\Ticket::where('website_id', $website->id)
            ->orderByDesc('id')
            ->get(['id','name','price','category_id']);
        return response()->json(['success' => true, 'tickets' => $tickets]);
    } catch (\Throwable $e) {
        return response()->json(['success' => false, 'items' => [], 'message' => $e->getMessage()], 500);
    }
});

Route::get('/students', function (Request $request) {
    try {
        $host = $request->getHost();
        $website = \App\Models\Website::where('domain', $host)->first();
        if (!$website && auth('web')->check()) {
            $website = auth('web')->user()->website;
        }
        if (!$website) {
            return response()->json(['success' => false, 'items' => []]);
        }
        $students = \App\Models\User::where('website_id', $website->id)
            ->whereNotNull('parent_id')
            ->orderBy('name')
            ->get(['id','name','last_name','email']);
        return response()->json(['success' => true, 'students' => $students]);
    } catch (\Throwable $e) {
        return response()->json(['success' => false, 'items' => [], 'message' => $e->getMessage()], 500);
    }
});

// QR Code API Routes (for fetching data)
Route::prefix('qr')->group(function () {
    // Get auctions for a website
    Route::get('/auctions', function (Request $request) {
        $websiteId = $request->query('website_id');
        if (!$websiteId) {
            return response()->json(['success' => false, 'message' => 'Website ID required'], 400);
        }
        
        $auctions = \App\Models\Auction::where('website_id', $websiteId)
            ->where('status', 1)
            ->select('id', 'title', 'dead_line', 'value')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response()->json(['success' => true, 'auctions' => $auctions]);
    });

    // Get tickets/sales listings for a website
    Route::get('/tickets', function (Request $request) {
        $websiteId = $request->query('website_id');
        if (!$websiteId) {
            return response()->json(['success' => false, 'message' => 'Website ID required'], 400);
        }
        
        $tickets = \App\Models\Ticket::where('website_id', $websiteId)
            ->where('status', 1)
            ->with('category:id,name')
            ->select('id', 'name', 'category_id', 'price')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($ticket) {
                return [
                    'id' => $ticket->id,
                    'name' => $ticket->name,
                    'price' => $ticket->price,
                    'category_name' => $ticket->category ? $ticket->category->name : null
                ];
            });
        
        return response()->json(['success' => true, 'tickets' => $tickets]);
    });

    // Get active students for a website
    Route::get('/students', function (Request $request) {
        $websiteId = $request->query('website_id');
        if (!$websiteId) {
            return response()->json(['success' => false, 'message' => 'Website ID required'], 400);
        }
        
        $students = \App\Models\User::where('website_id', $websiteId)
            ->where('role', 'user')
            ->where('status', 1)
            ->select('id', 'name', 'last_name', 'email', 'goal')
            ->orderBy('name')
            ->get();
        
        return response()->json(['success' => true, 'students' => $students]);
    });
});  // Close Route::prefix('qr')->group()
// Shopping Cart API Routes - NO AUTH REQUIRED (public cart) - USING CartService
Route::prefix('cart')->group(function () {
    // Get current cart
    Route::get('/', function (Request $request) {
        $cartService = app(\App\Services\CartService::class);
        $cart = $cartService->getCart();
        
        return response()->json([
            'success' => true,
            'cart' => $cart
        ]);
    });
    
    // Add item to cart
    Route::post('/add', function (Request $request) {
        $cartService = app(\App\Services\CartService::class);
        $item = $request->all();
        
        // Validate required fields
        if (empty($item['id']) || empty($item['name'])) {
            return response()->json([
                'success' => false,
                'message' => 'Item ID and name are required'
            ], 400);
        }
        
        // Determine type (default to 'product' if not specified)
        $type = $item['type'] ?? 'product';
        
        // Add item using CartService
        $success = $cartService->addItem($type, $item);
        
        if ($success) {
            $cart = $cartService->getCart();
            return response()->json([
                'success' => true,
                'message' => 'Item added to cart',
                'cart' => $cart
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add item to cart'
            ], 400);
        }
    });
    
    // Remove item from cart
    Route::delete('/item/{key}', function (Request $request, $key) {
        $cartService = app(\App\Services\CartService::class);
        
        $success = $cartService->removeItem($key);
        
        if ($success) {
            $cart = $cartService->getCart();
            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart',
                'cart' => $cart
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Item not found in cart'
        ], 404);
    });
    
    // Update item in cart
    Route::put('/item/{key}', function (Request $request, $key) {
        $cartService = app(\App\Services\CartService::class);
        $updates = $request->all();
        
        $success = $cartService->updateItem($key, $updates);
        
        if ($success) {
            $cart = $cartService->getCart();
            return response()->json([
                'success' => true,
                'message' => 'Item updated',
                'cart' => $cart
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Item not found in cart'
        ], 404);
    });
    
    // Clear entire cart
    Route::delete('/clear', function (Request $request) {
        $cartService = app(\App\Services\CartService::class);
        $cartService->clearCart();
        $cart = $cartService->getCart();
        
        return response()->json([
            'success' => true,
            'message' => 'Cart cleared',
            'cart' => $cart
        ]);
    });
    
    // Validate cart for checkout
    Route::get('/validate', function (Request $request) {
        $cartService = app(\App\Services\CartService::class);
        $validation = $cartService->validateForCheckout();
        
        return response()->json($validation);
    });
});