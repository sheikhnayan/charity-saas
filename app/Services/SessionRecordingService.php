<?php

namespace App\Services;

use App\Models\SessionRecording;
use App\Models\SessionEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Hotjar-style Session Recording Service
 * Handles recording, storage, and retrieval of user sessions
 */
class SessionRecordingService
{
    /**
     * Start a new session recording
     */
    public function startSession(array $data): SessionRecording
    {
        return SessionRecording::create([
            'website_id' => $data['website_id'],
            'session_id' => $data['session_id'],
            'visitor_id' => $data['visitor_id'] ?? null,
            'user_id' => $data['user_id'] ?? null,
            'url' => $data['url'] ?? null,
            'page_title' => $data['page_title'] ?? null,
            'viewport_width' => $data['viewport_width'] ?? null,
            'viewport_height' => $data['viewport_height'] ?? null,
            'device_type' => $data['device_type'] ?? null,
            'browser' => $data['browser'] ?? null,
            'os' => $data['os'] ?? null,
            'ip_address' => $data['ip_address'] ?? null,
            'country' => $data['country'] ?? null,
            'country_code' => $data['country_code'] ?? null,
            'state' => $data['state'] ?? null,
            'city' => $data['city'] ?? null,
            'status' => 'recording',
            'started_at' => now(),
        ]);
    }

    /**
     * Store session events (rrweb format)
     * Batch processing for performance
     */
    public function storeEvents(SessionRecording $recording, array $events): int
    {
        $eventsToInsert = [];
        $now = now();

        foreach ($events as $event) {
            $eventsToInsert[] = [
                'session_recording_id' => $recording->id,
                'timestamp' => $event['timestamp'] ?? 0,
                'event_type' => $event['type'] ?? 0,
                'data' => json_encode($event['data'] ?? $event),
                'action' => $this->extractAction($event),
                'target_element' => $this->extractTargetElement($event),
                'x' => $event['data']['x'] ?? null,
                'y' => $event['data']['y'] ?? null,
                'scroll_x' => $event['data']['scrollX'] ?? null,
                'scroll_y' => $event['data']['scrollY'] ?? null,
                'created_at' => $now,
            ];
        }

        if (!empty($eventsToInsert)) {
            // Batch insert for performance
            DB::table('session_events')->insert($eventsToInsert);
            
            // Update event count
            $recording->increment('event_count', count($eventsToInsert));
        }

        return count($eventsToInsert);
    }

    /**
     * Complete a session recording
     */
    public function completeSession(SessionRecording $recording, int $duration): SessionRecording
    {
        $recording->update([
            'status' => 'completed',
            'duration_ms' => $duration,
            'ended_at' => now(),
        ]);

        // Analyze for rage clicks and errors
        $this->analyzeSession($recording);

        return $recording->fresh();
    }

    /**
     * Get or create session recording
     */
    public function getOrCreateSession(string $sessionId, int $websiteId, array $metadata = []): SessionRecording
    {
        $recording = SessionRecording::where('session_id', $sessionId)
            ->where('website_id', $websiteId)
            ->where('status', 'recording')
            ->first();

        if (!$recording) {
            $recording = $this->startSession(array_merge([
                'session_id' => $sessionId,
                'website_id' => $websiteId,
            ], $metadata));
        }

        return $recording;
    }

    /**
     * Get session with all events for playback
     */
    public function getSessionForPlayback(int $recordingId): ?array
    {
        $recording = SessionRecording::with('events')->find($recordingId);

        if (!$recording) {
            return null;
        }

        // CRITICAL: Sort events so type 2 (full snapshot) is FIRST
        // Without this, rrweb can't replay because it needs the DOM structure first
        $events = $recording->events
            ->map(function ($event) {
                // Decode the rrweb event
                return json_decode($event->data, true);
            })
            ->sortBy(function ($rrwebEvent) {
                // Type 2 (full snapshot) gets priority 0
                // All other events sorted by timestamp
                return $rrwebEvent['type'] === 2 ? 0 : (1000000000000 + $rrwebEvent['timestamp']);
            })
            ->values(); // Re-index array starting from 0

        return [
            'recording' => $recording,
            'events' => $events,
        ];
    }

    /**
     * List sessions with filters (Hotjar-style)
     */
    public function listSessions(array $filters = [])
    {
        $query = SessionRecording::query()
            ->with('website')
            ->orderByDesc('created_at');

        if (isset($filters['website_id'])) {
            $query->where('website_id', $filters['website_id']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['has_rage_clicks']) && $filters['has_rage_clicks']) {
            $query->withRageClicks();
        }

        if (isset($filters['has_errors']) && $filters['has_errors']) {
            $query->withErrors();
        }

        if (isset($filters['min_duration'])) {
            $query->where('duration_ms', '>=', $filters['min_duration'] * 1000);
        }

        if (isset($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }

        if (isset($filters['device_type'])) {
            $query->where('device_type', $filters['device_type']);
        }

        if (isset($filters['starred']) && $filters['starred']) {
            $query->where('is_starred', true);
        }

        if (isset($filters['country_code'])) {
            $query->where('country_code', $filters['country_code']);
        }

        $perPage = $filters['per_page'] ?? 20;
        return $query->paginate($perPage);
    }

    /**
     * Analyze session for rage clicks and errors
     */
    protected function analyzeSession(SessionRecording $recording): void
    {
        // Detect rage clicks (multiple clicks in short time on same element)
        $clicks = $recording->events()
            ->where('action', 'click')
            ->orderBy('timestamp')
            ->get();

        $rageClicks = $this->detectRageClicks($clicks);
        
        if ($rageClicks > 0) {
            $recording->update(['has_rage_clicks' => true]);
        }

        // Check for JavaScript errors
        $errors = $recording->events()
            ->where('action', 'error')
            ->count();

        if ($errors > 0) {
            $recording->update(['has_errors' => true]);
        }
    }

    /**
     * Detect rage clicks (Hotjar algorithm)
     */
    protected function detectRageClicks($clicks): int
    {
        $rageCount = 0;
        $clickGroups = [];
        $currentGroup = [];
        $lastTimestamp = 0;
        $lastElement = null;

        foreach ($clicks as $click) {
            $timeDiff = $click->timestamp - $lastTimestamp;
            $sameElement = $click->target_element === $lastElement;

            if ($sameElement && $timeDiff < 1000) { // Within 1 second
                $currentGroup[] = $click;
            } else {
                if (count($currentGroup) >= 3) { // 3+ clicks = rage click
                    $rageCount++;
                }
                $currentGroup = [$click];
            }

            $lastTimestamp = $click->timestamp;
            $lastElement = $click->target_element;
        }

        // Check last group
        if (count($currentGroup) >= 3) {
            $rageCount++;
        }

        return $rageCount;
    }

    /**
     * Extract action type from event
     */
    protected function extractAction(array $event): ?string
    {
        if ($event['type'] === 3 && isset($event['data']['source'])) {
            return match ($event['data']['source']) {
                2 => 'move',
                3 => 'click',
                4 => 'scroll',
                5 => 'viewport_resize',
                6 => 'input',
                default => null,
            };
        }

        return null;
    }

    /**
     * Extract target element selector
     */
    protected function extractTargetElement(array $event): ?string
    {
        if (isset($event['data']['node'])) {
            return $event['data']['node']['tagName'] ?? null;
        }

        return null;
    }

    /**
     * Delete old recordings (cleanup)
     */
    public function deleteOldRecordings(int $daysToKeep = 90): int
    {
        $cutoffDate = now()->subDays($daysToKeep);
        
        return SessionRecording::where('created_at', '<', $cutoffDate)
            ->where('is_starred', false)
            ->delete();
    }
}
