<?php

namespace App\Http\Controllers;

use App\Services\HeatmapService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class HeatmapController extends Controller
{
    protected HeatmapService $service;

    public function __construct(HeatmapService $service)
    {
        $this->service = $service;
    }

    /**
     * Store heatmap event
     */
    public function trackEvent(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'website_id' => 'required|integer',
            'page_url' => 'required|url',
            'event_type' => 'required|in:click,move,scroll,attention',
            'x' => 'integer|nullable',
            'y' => 'integer|nullable',
            'viewport_width' => 'required|integer',
            'viewport_height' => 'required|integer',
            'element_selector' => 'string|nullable',
            'element_text' => 'string|nullable',
            'element_class' => 'string|nullable',
            'element_id' => 'string|nullable',
            'scroll_depth' => 'integer|nullable',
            'max_scroll' => 'integer|nullable',
            'duration_ms' => 'integer|nullable',
            'device_type' => 'string|nullable',
            'session_id' => 'string|nullable',
            'visitor_id' => 'string|nullable',
        ]);

        $this->service->storeEvent($validated);

        return response()->json(['success' => true]);
    }

    /**
     * Store batch heatmap events
     */
    public function trackBatch(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'events' => 'required|array',
            'events.*.website_id' => 'required|integer',
            'events.*.page_url' => 'required|url',
            'events.*.event_type' => 'required|in:click,move,scroll,attention',
        ]);

        $count = $this->service->storeBatchEvents($validated['events']);

        return response()->json([
            'success' => true,
            'stored_count' => $count,
        ]);
    }

    /**
     * Get click heatmap data
     */
    public function getClickHeatmap(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'website_id' => 'required|integer',
            'page_path' => 'required|string',
            'date_from' => 'date|nullable',
            'date_to' => 'date|nullable',
            'device_type' => 'string|nullable',
            'days' => 'integer|nullable',
        ]);

        $filters = $request->only(['date_from', 'date_to', 'device_type', 'days']);
        $data = $this->service->getClickHeatmap(
            $validated['website_id'],
            $validated['page_path'],
            $filters
        );

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Get move heatmap data
     */
    public function getMoveHeatmap(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'website_id' => 'required|integer',
            'page_path' => 'required|string',
            'date_from' => 'date|nullable',
            'date_to' => 'date|nullable',
            'device_type' => 'string|nullable',
            'days' => 'integer|nullable',
        ]);

        $filters = $request->only(['date_from', 'date_to', 'device_type', 'days']);
        $data = $this->service->getMoveHeatmap(
            $validated['website_id'],
            $validated['page_path'],
            $filters
        );

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Get scroll depth data
     */
    public function getScrollHeatmap(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'website_id' => 'required|integer',
            'page_path' => 'required|string',
            'date_from' => 'date|nullable',
            'date_to' => 'date|nullable',
            'device_type' => 'string|nullable',
            'days' => 'integer|nullable',
        ]);

        $filters = $request->only(['date_from', 'date_to', 'device_type', 'days']);
        $data = $this->service->getScrollHeatmap(
            $validated['website_id'],
            $validated['page_path'],
            $filters
        );

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Get aggregated normalized heatmap
     */
    public function getAggregatedHeatmap(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'website_id' => 'required|integer',
            'page_path' => 'required|string',
            'type' => 'required|in:click,move',
            'target_width' => 'integer|nullable',
            'target_height' => 'integer|nullable',
        ]);

        $data = $this->service->getAggregatedHeatmap(
            $validated['website_id'],
            $validated['page_path'],
            $validated['type'],
            $validated['target_width'] ?? 1440,
            $validated['target_height'] ?? 2400
        );

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Get popular pages
     */
    public function getPopularPages(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'website_id' => 'required|integer',
            'limit' => 'integer|nullable',
        ]);

        $pages = $this->service->getPopularPages(
            $validated['website_id'],
            $validated['limit'] ?? 20
        );

        return response()->json([
            'success' => true,
            'pages' => $pages,
        ]);
    }

    /**
     * Get element click statistics
     */
    public function getElementStats(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'website_id' => 'required|integer',
            'page_path' => 'required|string',
        ]);

        $stats = $this->service->getElementClickStats(
            $validated['website_id'],
            $validated['page_path']
        );

        return response()->json([
            'success' => true,
            'elements' => $stats,
        ]);
    }

    /**
     * Get screenshot for page
     */
    public function getScreenshot(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'website_id' => 'required|integer',
            'page_path' => 'required|string',
        ]);

        $screenshot = \App\Models\PageScreenshot::where('website_id', $validated['website_id'])
            ->where('page_path', $validated['page_path'])
            ->first();

        if (!$screenshot) {
            return response()->json([
                'success' => false,
                'message' => 'No screenshot found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'screenshot_path' => asset('storage/' . $screenshot->screenshot_path),
            'viewport_width' => $screenshot->viewport_width,
            'viewport_height' => $screenshot->viewport_height,
        ]);
    }

    /**
     * Capture screenshot for page
     */
    public function captureScreenshot(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'website_id' => 'required|integer',
                'page_url' => 'required|string',
                'page_path' => 'required|string',
                'screenshot_data' => 'required|string', // Base64 image data
                'viewport_width' => 'required|integer',
                'viewport_height' => 'required|integer',
                'device_type' => 'string|nullable',
            ]);

            \Log::info('Screenshot capture request', [
                'website_id' => $validated['website_id'],
                'page_path' => $validated['page_path'],
                'data_size' => strlen($validated['screenshot_data'])
            ]);

            // Decode base64 image
            $imageData = explode(',', $validated['screenshot_data']);
            $imageData = end($imageData);
            $imageData = base64_decode($imageData);

            if (!$imageData) {
                throw new \Exception('Failed to decode screenshot data');
            }

            // Generate filename
            $filename = 'screenshots/' . $validated['website_id'] . '/' . md5($validated['page_path']) . '_' . time() . '.png';
            $path = storage_path('app/public/' . $filename);

            // Create directory if it doesn't exist
            $dir = dirname($path);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            // Save image
            $bytes = file_put_contents($path, $imageData);
            if ($bytes === false) {
                throw new \Exception('Failed to save screenshot file');
            }

            \Log::info('Screenshot file saved', ['path' => $path, 'bytes' => $bytes]);

            // Store in database
            $screenshot = \App\Models\PageScreenshot::updateOrCreate(
                [
                    'website_id' => $validated['website_id'],
                    'page_path' => $validated['page_path'],
                    'device_type' => $validated['device_type'] ?? 'desktop',
                ],
                [
                    'page_url' => $validated['page_url'],
                    'screenshot_path' => $filename,
                    'viewport_width' => $validated['viewport_width'],
                    'viewport_height' => $validated['viewport_height'],
                ]
            );

            // dd($screenshot);

            \Log::info('Screenshot record saved', ['id' => $screenshot->id]);

            return response()->json([
                'success' => true,
                'screenshot' => $screenshot,
                'screenshot_path' => asset('storage/' . $filename),
            ]);
        } catch (\Exception $e) {
            \Log::error('Screenshot capture failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
