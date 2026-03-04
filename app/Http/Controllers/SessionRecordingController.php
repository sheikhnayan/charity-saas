<?php

namespace App\Http\Controllers;

use App\Services\SessionRecordingService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SessionRecordingController extends Controller
{
    protected SessionRecordingService $service;

    public function __construct(SessionRecordingService $service)
    {
        $this->service = $service;
    }

    /**
     * Start new session recording
     */
    public function start(Request $request): JsonResponse
    {

        $validated = $request->validate([
            'session_id' => 'required|string',
            'website_id' => 'required|integer',
            'visitor_id' => 'string|nullable',
            'url' => 'required|url',
            'page_title' => 'string|nullable',
            'viewport_width' => 'integer|required',
            'viewport_height' => 'integer|required',
            'device_type' => 'string|nullable',
            'browser' => 'string|nullable',
            'os' => 'string|nullable',
            'ip_address' => 'string|nullable',
            'country' => 'string|nullable',
            'country_code' => 'string|nullable',
            'state' => 'string|nullable',
            'city' => 'string|nullable',
        ]);

        $recording = $this->service->startSession($validated);

        dd($recording);

        return response()->json([
            'success' => true,
            'recording_id' => $recording->id,
            'session_id' => $recording->session_id,
        ]);
    }

    /**
     * Store session events (batch)
     */
    public function storeEvents(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'session_id' => 'required|string',
            'website_id' => 'required|integer',
            'events' => 'required|array',
            'events.*.timestamp' => 'required|integer',
            'events.*.type' => 'required|integer',
            'events.*.data' => 'required',
        ]);

        $recording = $this->service->getOrCreateSession(
            $validated['session_id'],
            $validated['website_id'],
            []
        );

        $storedCount = $this->service->storeEvents($recording, $validated['events']);

        return response()->json([
            'success' => true,
            'stored_count' => $storedCount,
            'recording_id' => $recording->id,
        ]);
    }

    /**
     * Complete session recording
     */
    public function complete(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'session_id' => 'required|string',
            'website_id' => 'required|integer',
            'duration_ms' => 'required|integer',
        ]);

        $recording = $this->service->getOrCreateSession(
            $validated['session_id'],
            $validated['website_id'],
            []
        );

        $this->service->completeSession($recording, $validated['duration_ms']);

        return response()->json([
            'success' => true,
            'recording_id' => $recording->id,
        ]);
    }

    /**
     * Get session for playback
     */
    public function getSession(int $recordingId): JsonResponse
    {
        $session = $this->service->getSessionForPlayback($recordingId);

        if (!$session) {
            return response()->json(['error' => 'Recording not found'], 404);
        }

        return response()->json($session);
    }

    /**
     * List sessions with filters
     */
    public function list(Request $request): JsonResponse
    {
        $filters = $request->only([
            'website_id',
            'status',
            'has_rage_clicks',
            'has_errors',
            'min_duration',
            'date_from',
            'date_to',
            'device_type',
            'country_code',
            'per_page',
            'starred'
        ]);

        $sessions = $this->service->listSessions($filters);
        
        // Add metadata for stats
        $query = \App\Models\SessionRecording::query();
        if (isset($filters['website_id'])) {
            $query->where('website_id', $filters['website_id']);
        }
        
        $sessions['meta'] = [
            'total' => $sessions['total'] ?? 0,
            'rage_clicks_count' => (clone $query)->where('has_rage_clicks', true)->count(),
            'errors_count' => (clone $query)->where('has_errors', true)->count(),
            'avg_duration' => (clone $query)->avg('duration_ms'),
        ];

        return response()->json($sessions);
    }

    /**
     * Delete session recording
     */
    public function delete(int $recordingId): JsonResponse
    {
        $recording = \App\Models\SessionRecording::find($recordingId);

        if (!$recording) {
            return response()->json(['error' => 'Recording not found'], 404);
        }

        $recording->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Star/unstar session
     */
    public function toggleStar(int $recordingId): JsonResponse
    {
        $recording = \App\Models\SessionRecording::find($recordingId);

        if (!$recording) {
            return response()->json(['error' => 'Recording not found'], 404);
        }

        $recording->is_starred = !$recording->is_starred;
        $recording->save();

        return response()->json([
            'success' => true,
            'is_starred' => $recording->is_starred,
        ]);
    }

    /**
     * Update notes/tags
     */
    public function updateMeta(int $recordingId, Request $request): JsonResponse
    {
        $validated = $request->validate([
            'notes' => 'string|nullable',
            'tags' => 'array|nullable',
        ]);

        $recording = \App\Models\SessionRecording::find($recordingId);

        if (!$recording) {
            return response()->json(['error' => 'Recording not found'], 404);
        }

        if (isset($validated['notes'])) {
            $recording->notes = $validated['notes'];
        }

        if (isset($validated['tags'])) {
            $recording->tags = $validated['tags'];
        }

        $recording->save();

        return response()->json(['success' => true]);
    }
}
