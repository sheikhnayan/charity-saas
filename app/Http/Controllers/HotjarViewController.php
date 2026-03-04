<?php

namespace App\Http\Controllers;

use App\Models\SessionRecording;
use App\Models\Website;
use Illuminate\Http\Request;

class HotjarViewController extends Controller
{
    /**
     * Show recordings list
     */
    public function recordings()
    {
        $websites = Website::orderBy('name')->get();
        
        return view('hotjar.recordings.index', compact('websites'));
    }

    /**
     * Show recording replay
     */
    public function replay($recordingId)
    {
        $recording = SessionRecording::with('website')->findOrFail($recordingId);
        
        // Use hybrid replay: renders actual page in iframe + overlays interactions
        return view('hotjar.recordings.replay-hybrid', compact('recording'));
    }

    /**
     * Show heatmaps
     */
    public function heatmaps()
    {
        $websites = Website::orderBy('name')->get();
        
        return view('hotjar.heatmaps.index', compact('websites'));
    }

    /**
     * Get session recordings API
     */
    public function getRecordings(Request $request)
    {
        $query = SessionRecording::with('website');

        // Apply filters
        if ($request->website_id) {
            $query->where('website_id', $request->website_id);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->device_type) {
            $query->where('device_type', $request->device_type);
        }
        if ($request->min_duration) {
            $query->where('duration_ms', '>=', $request->min_duration);
        }
        if ($request->has_rage_clicks) {
            $query->where('has_rage_clicks', true);
        }
        if ($request->has_errors) {
            $query->where('has_errors', true);
        }
        if ($request->starred) {
            $query->where('is_starred', true);
        }

        $recordings = $query->orderBy('started_at', 'desc')
            ->paginate($request->per_page ?? 20);

        // Calculate stats
        $totalQuery = SessionRecording::query();
        if ($request->website_id) {
            $totalQuery->where('website_id', $request->website_id);
        }

        $stats = [
            'total' => $totalQuery->count(),
            'rage_clicks_count' => (clone $totalQuery)->where('has_rage_clicks', true)->count(),
            'errors_count' => (clone $totalQuery)->where('has_errors', true)->count(),
            'avg_duration' => $totalQuery->avg('duration_ms'),
        ];

        return response()->json([
            'data' => $recordings->items(),
            'current_page' => $recordings->currentPage(),
            'last_page' => $recordings->lastPage(),
            'total' => $recordings->total(),
            'per_page' => $recordings->perPage(),
            'meta' => $stats,
        ]);
    }

    /**
     * Get heatmap popular pages
     */
    public function getPopularPages(Request $request)
    {
        $websiteId = $request->website_id;
        if (!$websiteId) {
            return response()->json(['pages' => []]);
        }

        // Get only page-builder pages (dynamic pages from pages table)
        $pages = \DB::table('pages')
            ->where('website_id', $websiteId)
            ->whereNotNull('state') // Has page builder data
            ->get();

        // Build paths list - includes homepage as '/' and regular pages as '/page/{name}'
        $pageBuilderPaths = [];
        foreach ($pages as $page) {
            if ($page->is_homepage) {
                // Homepage is accessed via root path '/'
                $pageBuilderPaths[] = '/';
            } else {
                // Regular pages use '/page/{name}' format
                $pageBuilderPaths[] = '/page/' . str_replace(' ', '-', strtolower($page->name));
            }
        }

        // Get pages with heatmap data - ONLY for page-builder pages
        $heatmapPages = \DB::table('heatmap_data')
            ->select('page_path', 'page_url', \DB::raw('COUNT(DISTINCT session_id) as visitors'))
            ->where('website_id', $websiteId)
            ->whereIn('page_path', $pageBuilderPaths) // Filter to only page-builder pages
            ->groupBy('page_path', 'page_url')
            ->orderBy('visitors', 'desc')
            ->limit(20)
            ->get();

        return response()->json(['pages' => $heatmapPages]);
    }

    /**
     * Get click heatmap data
     */
    public function getClickHeatmap(Request $request)
    {
        $websiteId = $request->website_id;
        $pagePath = $request->page_path;
        $days = $request->days ?? 30;

        // Verify this is a page-builder page
        $isPageBuilderPage = $this->isPageBuilderPage($websiteId, $pagePath);

        if (!$isPageBuilderPage) {
            return response()->json(['data' => []]);
        }

        $data = \DB::table('heatmap_data')
            ->select('x', 'y', 'viewport_width', 'viewport_height', \DB::raw('COUNT(*) as click_count'))
            ->where('website_id', $websiteId)
            ->where('page_path', $pagePath)
            ->where('event_type', 'click')
            ->where('created_at', '>=', now()->subDays($days))
            ->when($request->device_type, function($q) use ($request) {
                return $q->where('device_type', $request->device_type);
            })
            ->groupBy('x', 'y', 'viewport_width', 'viewport_height')
            ->get();

        return response()->json(['data' => $data]);
    }

    /**
     * Get move heatmap data
     */
    public function getMoveHeatmap(Request $request)
    {
        $websiteId = $request->website_id;
        $pagePath = $request->page_path;
        $days = $request->days ?? 30;

        // Verify this is a page-builder page
        $isPageBuilderPage = $this->isPageBuilderPage($websiteId, $pagePath);

        if (!$isPageBuilderPage) {
            return response()->json(['data' => []]);
        }

        $data = \DB::table('heatmap_data')
            ->select('x', 'y', 'viewport_width', 'viewport_height', \DB::raw('COUNT(*) as move_count'))
            ->where('website_id', $websiteId)
            ->where('page_path', $pagePath)
            ->where('event_type', 'move')
            ->where('created_at', '>=', now()->subDays($days))
            ->when($request->device_type, function($q) use ($request) {
                return $q->where('device_type', $request->device_type);
            })
            ->groupBy('x', 'y', 'viewport_width', 'viewport_height')
            ->get();

        return response()->json(['data' => $data]);
    }

    /**
     * Get scroll depth data
     */
    public function getScrollDepth(Request $request)
    {
        $websiteId = $request->website_id;
        $pagePath = $request->page_path;
        $days = $request->days ?? 30;

        // Verify this is a page-builder page
        $isPageBuilderPage = $this->isPageBuilderPage($websiteId, $pagePath);

        if (!$isPageBuilderPage) {
            return response()->json(['scroll_percentages' => []]);
        }

        // Calculate scroll percentages
        $scrollData = \DB::table('heatmap_data')
            ->where('website_id', $websiteId)
            ->where('page_path', $pagePath)
            ->where('event_type', 'scroll')
            ->where('created_at', '>=', now()->subDays($days))
            ->when($request->device_type, function($q) use ($request) {
                return $q->where('device_type', $request->device_type);
            })
            ->get();

        $totalUsers = $scrollData->unique('session_id')->count();
        $avgScroll = $scrollData->avg('scroll_depth') ?? 0;

        // Calculate percentage at each depth
        $percentages = [];
        foreach ([0, 25, 50, 75, 100] as $depth) {
            $count = $scrollData->where('scroll_depth', '>=', $depth)->unique('session_id')->count();
            $percentages[$depth] = $totalUsers > 0 ? round(($count / $totalUsers) * 100) : 0;
        }

        return response()->json([
            'data' => [
                'total_users' => $totalUsers,
                'average_scroll' => $avgScroll,
                'scroll_percentages' => $percentages,
            ]
        ]);
    }

    /**
     * Get element click statistics
     */
    public function getElementStats(Request $request)
    {
        $websiteId = $request->website_id;
        $pagePath = $request->page_path;

        $elements = \DB::table('heatmap_data')
            ->select('element_selector', 'element_text', \DB::raw('COUNT(*) as clicks'), \DB::raw('COUNT(DISTINCT session_id) as unique_users'))
            ->where('website_id', $websiteId)
            ->where('page_path', $pagePath)
            ->where('event_type', 'click')
            ->whereNotNull('element_selector')
            ->groupBy('element_selector', 'element_text')
            ->orderBy('clicks', 'desc')
            ->limit(20)
            ->get();

        return response()->json(['elements' => $elements]);
    }

    /**
     * Get screenshot URL
     */
    public function getScreenshot(Request $request)
    {
        $websiteId = $request->website_id;
        $pagePath = $request->page_path;

        // Get the latest screenshot for this page (order by updated_at since updateOrCreate updates that)
        $screenshot = \DB::table('page_screenshots')
            ->where('website_id', $websiteId)
            ->where('page_path', $pagePath)
            ->orderBy('updated_at', 'desc')
            ->first();

        // If no screenshot found, return 404 so JavaScript knows to capture one
        if (!$screenshot || !$screenshot->screenshot_path) {
            return response()->json([
                'message' => 'No screenshot found'
            ], 404);
        }

        return response()->json([
            'screenshot_path' => $screenshot->screenshot_path,
            'screenshot_url' => asset($screenshot->screenshot_path), // Full URL for easy access
            'viewport_width' => $screenshot->viewport_width,
            'viewport_height' => $screenshot->viewport_height,
            'created_at' => $screenshot->created_at
        ]);
    }

    /**
     * Capture and save page screenshot
     */
    public function captureScreenshot(Request $request)
    {
        try {
            $request->validate([
                'website_id' => 'required|integer',
                'page_path' => 'required|string',
                'screenshot_data' => 'required|string',
                'viewport_width' => 'nullable|integer',
                'viewport_height' => 'nullable|integer'
            ]);

            // Extract base64 image data
            $screenshotData = $request->screenshot_data;
            
            if (preg_match('/^data:image\/(\w+);base64,/', $screenshotData, $type)) {
                $screenshotData = substr($screenshotData, strpos($screenshotData, ',') + 1);
                $type = strtolower($type[1]); // jpg, png, gif

                $screenshotData = base64_decode($screenshotData);

                if ($screenshotData === false) {
                    throw new \Exception('Base64 decode failed');
                }
            } else {
                throw new \Exception('Invalid image data');
            }

            // Generate filename
            $filename = 'screenshot_' . $request->website_id . '_' . md5($request->page_path) . '_' . time() . '.png';
            $filepath = 'screenshots/' . $filename;
            
            // Ensure directory exists in storage/app/public
            $directory = storage_path('app/public/screenshots');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            // Save file to storage
            $fullPath = storage_path('app/public/' . $filepath);
            file_put_contents($fullPath, $screenshotData);

            // Save to database (URL should use storage link)
            $screenshotUrl = asset('storage/' . $filepath);
            
            \DB::table('page_screenshots')->insert([
                'website_id' => $request->website_id,
                'page_url' => $request->page_url, // Add page_url field
                'page_path' => $request->page_path,
                'screenshot_path' => $screenshotUrl,
                'viewport_width' => $request->viewport_width ?? 1920,
                'viewport_height' => $request->viewport_height ?? 1080,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            \Log::info('Screenshot captured successfully', [
                'website_id' => $request->website_id,
                'page_path' => $request->page_path,
                'screenshot_path' => $screenshotUrl
            ]);

            return response()->json([
                'success' => true,
                'screenshot_path' => $screenshotUrl,
                'message' => 'Screenshot captured successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('Screenshot capture failed: ' . $e->getMessage(), [
                'website_id' => $request->website_id ?? null,
                'page_path' => $request->page_path ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to capture screenshot: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Start a new session recording
     */
    public function startRecording(Request $request)
    {
        try {
            $request->validate([
                'session_id' => 'required|string',
                'website_id' => 'required|integer',
                'url' => 'required|string',
                'visitor_id' => 'nullable|string',
                'page_title' => 'nullable|string',
                'viewport_width' => 'nullable|integer',
                'viewport_height' => 'nullable|integer',
                'device_type' => 'nullable|string',
                'browser' => 'nullable|string',
                'os' => 'nullable|string',
            ]);

            $recording = SessionRecording::create([
                'website_id' => $request->website_id,
                'session_id' => $request->session_id,
                'visitor_id' => $request->visitor_id,
                'url' => $request->url, // Using 'url' field from model
                'page_title' => $request->page_title,
                'viewport_width' => $request->viewport_width ?? 1920,
                'viewport_height' => $request->viewport_height ?? 1080,
                'device_type' => $request->device_type ?? 'desktop',
                'browser' => $request->browser,
                'os' => $request->os,
                'ip_address' => $request->ip(),
                'started_at' => now(),
                'status' => 'recording',
            ]);

            return response()->json([
                'success' => true,
                'recording_id' => $recording->id,
                'message' => 'Recording started'
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to start recording: ' . $e->getMessage(), [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to start recording: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save session recording events
     */
    public function saveEvents(Request $request)
    {
        try {
            $validated = $request->validate([
                'session_id' => 'required|string',
                'website_id' => 'required|integer',
                'events' => 'required|array',
            ]);

            $recording = SessionRecording::where('session_id', $request->session_id)
                ->where('website_id', $request->website_id)
                ->latest()
                ->first();

            if (!$recording) {
                \Log::warning('Recording not found for saveEvents', [
                    'session_id' => $request->session_id,
                    'website_id' => $request->website_id
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Recording not found'
                ], 404);
            }

            // Save events to session_events table
            $eventsInserted = 0;
            // foreach ($request->events as $event) {
            //     try {
            //         \DB::table('session_events')->insert([
            //             'session_recording_id' => $recording->id,
            //             'timestamp' => $event['timestamp'] ?? 0,
            //             'event_type' => $event['type'] ?? 0, // rrweb event type
            //             'data' => json_encode($event['data'] ?? $event), // Store full event data
            //             'created_at' => now(),
            //         ]);
            //         $eventsInserted++;
            //     } catch (\Exception $e) {
            //         \Log::error('Failed to insert event', [
            //             'error' => $e->getMessage(),
            //             'event' => $event
            //         ]);
            //     }
            // }
            
            // Update recording metadata
            $recording->update([
                'ended_at' => now(),
                'duration_ms' => now()->diffInMilliseconds($recording->started_at),
                'event_count' => ($recording->event_count ?? 0) + $eventsInserted,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Events saved',
                'events_inserted' => $eventsInserted
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed for saveEvents', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Failed to save events', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to save events: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Complete a session recording
     */
    public function completeRecording(Request $request)
    {
        try {
            $request->validate([
                'session_id' => 'required|string',
                'website_id' => 'required|integer',
                'duration_ms' => 'nullable|integer',
            ]);

            $recording = SessionRecording::where('session_id', $request->session_id)
                ->where('website_id', $request->website_id)
                ->latest()
                ->first();

            if (!$recording) {
                return response()->json([
                    'success' => false,
                    'message' => 'Recording not found'
                ], 404);
            }

            $recording->update([
                'ended_at' => now(),
                'duration_ms' => $request->duration_ms ?? now()->diffInMilliseconds($recording->started_at),
                'status' => 'completed',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Recording completed'
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to complete recording: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to complete recording'
            ], 500);
        }
    }

    /**
     * Get recording with all events for replay
     */
    public function getRecordingWithEvents($recordingId)
    {
        try {
            $recording = SessionRecording::findOrFail($recordingId);
            
            // Get all events for this recording
            $events = \DB::table('session_events')
                ->where('session_recording_id', $recordingId)
                ->orderBy('timestamp', 'asc')
                ->get()
                ->map(function($event) {
                    // The 'data' field contains the full rrweb event as JSON
                    // So we just need to parse it and return it directly
                    $fullEvent = json_decode($event->data, true);
                    
                    // Return the full rrweb event structure with the stored timestamp
                    return $fullEvent;
                });
            
            \Log::info('Fetched recording events', [
                'recording_id' => $recordingId,
                'event_count' => $events->count(),
                'first_event_type' => $events->first()['type'] ?? null,
                'last_event_type' => $events->last()['type'] ?? null,
                'first_timestamp' => $events->first()['timestamp'] ?? null,
                'last_timestamp' => $events->last()['timestamp'] ?? null,
            ]);
            
            return response()->json([
                'recording' => $recording,
                'events' => $events
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Failed to get recording events', [
                'error' => $e->getMessage(),
                'recording_id' => $recordingId
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load recording'
            ], 500);
        }
    }

    /**
     * Track heatmap event - unified endpoint for all heatmap types
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Recording completed'
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to complete recording: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to complete recording'
            ], 500);
        }
    }

    /**
     * Track heatmap event - unified endpoint for all heatmap types
     */
    public function trackClick(Request $request)
    {
        try {
            $request->validate([
                'website_id' => 'required|integer',
                'page_path' => 'required|string',
                'x' => 'required|numeric',
                'y' => 'required|numeric',
                'element' => 'nullable|string',
            ]);

            \DB::table('heatmap_clicks')->insert([
                'website_id' => $request->website_id,
                'page_path' => $request->page_path,
                'x' => $request->x,
                'y' => $request->y,
                'element' => $request->element,
                'viewport_width' => $request->viewport_width ?? 1920,
                'viewport_height' => $request->viewport_height ?? 1080,
                'created_at' => now(),
            ]);

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            \Log::error('Failed to track click: ' . $e->getMessage());
            return response()->json(['success' => false], 500);
        }
    }

    /**
     * Track mouse movement for heatmap
     */
    public function trackMouseMove(Request $request)
    {
        try {
            $request->validate([
                'website_id' => 'required|integer',
                'page_path' => 'required|string',
                'x' => 'required|numeric',
                'y' => 'required|numeric',
            ]);

            \DB::table('heatmap_moves')->insert([
                'website_id' => $request->website_id,
                'page_path' => $request->page_path,
                'x' => $request->x,
                'y' => $request->y,
                'viewport_width' => $request->viewport_width ?? 1920,
                'viewport_height' => $request->viewport_height ?? 1080,
                'created_at' => now(),
            ]);

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            \Log::error('Failed to track move: ' . $e->getMessage());
            return response()->json(['success' => false], 500);
        }
    }

    /**
     * Track scroll depth for heatmap
     */
    public function trackScroll(Request $request)
    {
        try {
            $request->validate([
                'website_id' => 'required|integer',
                'page_path' => 'required|string',
                'scroll_depth' => 'required|numeric',
                'max_scroll' => 'required|numeric',
            ]);

            \DB::table('heatmap_scrolls')->insert([
                'website_id' => $request->website_id,
                'page_path' => $request->page_path,
                'scroll_depth' => $request->scroll_depth,
                'max_scroll' => $request->max_scroll,
                'viewport_width' => $request->viewport_width ?? 1920,
                'viewport_height' => $request->viewport_height ?? 1080,
                'created_at' => now(),
            ]);

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            \Log::error('Failed to track scroll: ' . $e->getMessage());
            return response()->json(['success' => false], 500);
        }
    }

    /**
     * Unified heatmap event tracking (all types)
     */
    public function trackHeatmapEvent(Request $request)
    {
        try {
            $request->validate([
                'website_id' => 'required|integer',
                'page_url' => 'required|string',
                'page_path' => 'nullable|string',
                'event_type' => 'required|in:click,move,scroll,attention',
                'x' => 'nullable|integer',
                'y' => 'nullable|integer',
                'viewport_width' => 'nullable|integer',
                'viewport_height' => 'nullable|integer',
                'element_selector' => 'nullable|string',
                'element_text' => 'nullable|string',
                'element_class' => 'nullable|string',
                'element_id' => 'nullable|string',
                'scroll_depth' => 'nullable|integer',
                'max_scroll' => 'nullable|integer',
                'duration_ms' => 'nullable|integer',
                'device_type' => 'nullable|string',
                'session_id' => 'nullable|string',
                'visitor_id' => 'nullable|string',
            ]);

            // Extract page path from URL if not provided
            $pagePath = $request->page_path ?? parse_url($request->page_url, PHP_URL_PATH) ?? '/';

            // Insert into heatmap_data table
            \DB::table('heatmap_data')->insert([
                'website_id' => $request->website_id,
                'page_url' => $request->page_url,
                'page_path' => $pagePath,
                'event_type' => $request->event_type,
                'x' => $request->x,
                'y' => $request->y,
                'viewport_width' => $request->viewport_width ?? 1920,
                'viewport_height' => $request->viewport_height ?? 1080,
                'element_selector' => $request->element_selector,
                'element_text' => $request->element_text,
                'element_class' => $request->element_class,
                'element_id' => $request->element_id,
                'scroll_depth' => $request->scroll_depth,
                'max_scroll' => $request->max_scroll,
                'duration_ms' => $request->duration_ms,
                'device_type' => $request->device_type ?? 'desktop',
                'session_id' => $request->session_id,
                'visitor_id' => $request->visitor_id,
                'created_at' => now(),
            ]);

            return response()->json(['success' => true]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Heatmap validation failed', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Failed to track heatmap event: ' . $e->getMessage(), [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to track heatmap event'
            ], 500);
        }
    }

    /**
     * Helper method to check if a page path belongs to a page-builder page
     * Handles both homepage (/) and regular pages (/page/{name})
     *
     * @param int $websiteId
     * @param string $pagePath
     * @return bool
     */
    private function isPageBuilderPage($websiteId, $pagePath)
    {
        // Check if this is the homepage
        if ($pagePath === '/' || $pagePath === '') {
            return \DB::table('pages')
                ->where('website_id', $websiteId)
                ->where('is_homepage', true)
                ->whereNotNull('state')
                ->exists();
        }

        // Check if this is a regular page-builder page
        // Extract page name from path like '/page/about-us' -> 'about us'
        $pageName = str_replace(['/page/', '/'], '', $pagePath);
        $pageName = str_replace('-', ' ', $pageName);

        return \DB::table('pages')
            ->where('website_id', $websiteId)
            ->where('is_homepage', false)
            ->whereNotNull('state')
            ->where('name', $pageName)
            ->exists();
    }
}

