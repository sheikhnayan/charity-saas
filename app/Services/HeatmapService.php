<?php

namespace App\Services;

use App\Models\HeatmapData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

/**
 * Hotjar-style Heatmap Service
 * Handles heatmap data collection and aggregation
 */
class HeatmapService
{
    /**
     * Store heatmap event (click, move, scroll)
     */
    public function storeEvent(array $data): HeatmapData
    {
        return HeatmapData::create([
            'website_id' => $data['website_id'],
            'page_url' => $data['page_url'],
            'page_path' => $this->normalizePath($data['page_url']),
            'event_type' => $data['event_type'],
            'x' => $data['x'] ?? null,
            'y' => $data['y'] ?? null,
            'viewport_width' => $data['viewport_width'],
            'viewport_height' => $data['viewport_height'],
            'element_selector' => $data['element_selector'] ?? null,
            'element_text' => $data['element_text'] ?? null,
            'element_class' => $data['element_class'] ?? null,
            'element_id' => $data['element_id'] ?? null,
            'scroll_depth' => $data['scroll_depth'] ?? null,
            'max_scroll' => $data['max_scroll'] ?? null,
            'duration_ms' => $data['duration_ms'] ?? null,
            'device_type' => $data['device_type'] ?? null,
            'session_id' => $data['session_id'] ?? null,
            'visitor_id' => $data['visitor_id'] ?? null,
        ]);
    }

    /**
     * Batch store events for performance
     */
    public function storeBatchEvents(array $events): int
    {
        $eventsToInsert = [];
        $now = now();

        foreach ($events as $event) {
            $eventsToInsert[] = [
                'website_id' => $event['website_id'],
                'page_url' => $event['page_url'],
                'page_path' => $this->normalizePath($event['page_url']),
                'event_type' => $event['event_type'],
                'x' => $event['x'] ?? null,
                'y' => $event['y'] ?? null,
                'viewport_width' => $event['viewport_width'],
                'viewport_height' => $event['viewport_height'],
                'element_selector' => $event['element_selector'] ?? null,
                'element_text' => $event['element_text'] ?? null,
                'element_class' => $event['element_class'] ?? null,
                'element_id' => $event['element_id'] ?? null,
                'scroll_depth' => $event['scroll_depth'] ?? null,
                'max_scroll' => $event['max_scroll'] ?? null,
                'duration_ms' => $event['duration_ms'] ?? null,
                'device_type' => $event['device_type'] ?? null,
                'session_id' => $event['session_id'] ?? null,
                'visitor_id' => $event['visitor_id'] ?? null,
                'created_at' => $now,
            ];
        }

        if (!empty($eventsToInsert)) {
            DB::table('heatmap_data')->insert($eventsToInsert);
        }

        return count($eventsToInsert);
    }

    /**
     * Get click heatmap data for a page (Hotjar format)
     */
    public function getClickHeatmap(int $websiteId, string $pagePath, array $filters = []): Collection
    {
        $query = HeatmapData::forWebsite($websiteId)
            ->forPage($pagePath)
            ->clicks()
            ->select('x', 'y', 'viewport_width', 'viewport_height', 'element_selector', 
                     DB::raw('COUNT(*) as click_count'));

        $this->applyFilters($query, $filters);

        return $query->groupBy('x', 'y', 'viewport_width', 'viewport_height', 'element_selector')
            ->orderByDesc('click_count')
            ->get();
    }

    /**
     * Get move heatmap data (attention/engagement)
     */
    public function getMoveHeatmap(int $websiteId, string $pagePath, array $filters = []): Collection
    {
        $query = HeatmapData::forWebsite($websiteId)
            ->forPage($pagePath)
            ->moves()
            ->select('x', 'y', 'viewport_width', 'viewport_height', 
                     DB::raw('SUM(duration_ms) as total_duration'),
                     DB::raw('COUNT(*) as move_count'));

        $this->applyFilters($query, $filters);

        return $query->groupBy('x', 'y', 'viewport_width', 'viewport_height')
            ->having('move_count', '>=', 1) // Show all moves (changed from > 5)
            ->orderByDesc('total_duration')
            ->get();
    }

    /**
     * Get scroll depth data
     */
    public function getScrollHeatmap(int $websiteId, string $pagePath, array $filters = []): array
    {
        $query = HeatmapData::forWebsite($websiteId)
            ->forPage($pagePath)
            ->scrolls();

        $this->applyFilters($query, $filters);

        $scrollData = $query->select(
            DB::raw('scroll_depth'),
            DB::raw('COUNT(DISTINCT session_id) as user_count')
        )
        ->groupBy('scroll_depth')
        ->orderBy('scroll_depth')
        ->get();

        // Convert to percentage distribution
        $totalUsers = $scrollData->sum('user_count');
        $scrollPercentages = [];

        foreach (range(0, 100, 10) as $depth) {
            $usersAtDepth = $scrollData->where('scroll_depth', '>=', $depth)->sum('user_count');
            $scrollPercentages[$depth] = $totalUsers > 0 ? round(($usersAtDepth / $totalUsers) * 100, 1) : 0;
        }

        return [
            'scroll_percentages' => $scrollPercentages,
            'total_users' => $totalUsers,
            'average_scroll' => $scrollData->avg('scroll_depth') ?? 0,
        ];
    }

    /**
     * Get aggregated heatmap for normalized viewport
     * Hotjar normalizes to common viewport size (1440x900)
     */
    public function getAggregatedHeatmap(int $websiteId, string $pagePath, string $type = 'click', int $targetWidth = 1440, int $targetHeight = 2400): array
    {
        $method = match($type) {
            'click' => 'getClickHeatmap',
            'move' => 'getMoveHeatmap',
            default => 'getClickHeatmap',
        };

        $rawData = $this->$method($websiteId, $pagePath);
        
        // Normalize coordinates to target viewport
        $normalizedData = [];
        
        foreach ($rawData as $point) {
            if (!$point->x || !$point->y) continue;

            $normalizedX = (int) (($point->x / $point->viewport_width) * $targetWidth);
            $normalizedY = (int) (($point->y / $point->viewport_height) * $targetHeight);
            
            $key = "{$normalizedX},{$normalizedY}";
            
            if (!isset($normalizedData[$key])) {
                $normalizedData[$key] = [
                    'x' => $normalizedX,
                    'y' => $normalizedY,
                    'value' => 0,
                ];
            }
            
            $normalizedData[$key]['value'] += $point->click_count ?? $point->move_count ?? 1;
        }

        return array_values($normalizedData);
    }

    /**
     * Get popular pages for heatmap
     */
    public function getPopularPages(int $websiteId, int $limit = 20): Collection
    {
        return HeatmapData::forWebsite($websiteId)
            ->select('page_path', 'page_url', DB::raw('COUNT(DISTINCT session_id) as visitors'))
            ->groupBy('page_path', 'page_url')
            ->orderByDesc('visitors')
            ->limit($limit)
            ->get();
    }

    /**
     * Get element click statistics
     */
    public function getElementClickStats(int $websiteId, string $pagePath): Collection
    {
        return HeatmapData::forWebsite($websiteId)
            ->forPage($pagePath)
            ->clicks()
            ->whereNotNull('element_selector')
            ->select(
                'element_selector',
                'element_text',
                'element_id',
                'element_class',
                DB::raw('COUNT(*) as clicks'),
                DB::raw('COUNT(DISTINCT session_id) as unique_users')
            )
            ->groupBy('element_selector', 'element_text', 'element_id', 'element_class')
            ->orderByDesc('clicks')
            ->limit(50)
            ->get();
    }

    /**
     * Normalize URL path (remove query params, hash)
     */
    protected function normalizePath(string $url): string
    {
        $parsed = parse_url($url);
        $path = $parsed['path'] ?? '/';
        
        // Remove trailing slash except for root
        if ($path !== '/' && substr($path, -1) === '/') {
            $path = substr($path, 0, -1);
        }
        
        return $path;
    }

    /**
     * Apply common filters to query
     */
    protected function applyFilters($query, array $filters): void
    {
        if (isset($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }

        if (isset($filters['device_type'])) {
            $query->where('device_type', $filters['device_type']);
        }

        if (isset($filters['days'])) {
            $query->recent($filters['days']);
        }
    }

    /**
     * Delete old heatmap data (cleanup)
     */
    public function deleteOldData(int $daysToKeep = 90): int
    {
        $cutoffDate = now()->subDays($daysToKeep);
        
        return HeatmapData::where('created_at', '<', $cutoffDate)->delete();
    }
}
