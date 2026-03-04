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

