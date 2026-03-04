<?php

namespace App\Services;

use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\Storage;
use App\Models\PageScreenshot;
use App\Models\Page;
use Exception;

class ScreenshotService
{
    /**
     * Capture a screenshot of a page-builder page
     *
     * @param int $pageId
     * @return bool
     */
    public function capturePageScreenshot($pageId)
    {
        try {
            // Get the page from database
            $page = Page::findOrFail($pageId);
            
            // Only capture screenshots for page-builder pages (pages with state)
            if (empty($page->state)) {
                \Log::warning("Skipping screenshot for page {$pageId}: No page builder state found");
                return false;
            }

            // Build the full URL to the page
            $pageUrl = $this->getPageUrl($page);
            
            \Log::info("Capturing screenshot for page {$pageId}: {$pageUrl}");

            // Create temporary file path
            $tempPath = storage_path('app/temp/screenshot_' . $pageId . '_' . time() . '.png');
            
            // Ensure temp directory exists
            if (!file_exists(dirname($tempPath))) {
                mkdir(dirname($tempPath), 0755, true);
            }

            // Capture screenshot using Browsershot with production-safe flags
            $args = ['--no-sandbox', '--disable-setuid-sandbox'];
            
            // Additional flags for headless environments
            if (app()->environment('production')) {
                $args[] = '--disable-dev-shm-usage'; // Use less memory
                $args[] = '--disable-gpu'; // Disable GPU acceleration
                $args[] = '--single-process'; // Run as single process
            }
            
            // Capture screenshot using Browsershot
            Browsershot::url($pageUrl)
                ->windowSize(1920, 1080) // Desktop viewport
                ->setDelay(2000) // Wait 2 seconds for page to load
                ->fullPage() // Capture entire page, not just viewport
                ->dismissDialogs() // Auto-dismiss any alerts/confirms
                ->waitUntilNetworkIdle() // Wait for network requests to finish
                ->setOption('args', $args) // Set Chromium arguments
                ->save($tempPath);

            // Read the screenshot file
            $imageData = file_get_contents($tempPath);
            
            if (!$imageData) {
                throw new Exception("Failed to read screenshot file");
            }

            // Get image dimensions
            $imageInfo = getimagesize($tempPath);
            $width = $imageInfo[0];
            $height = $imageInfo[1];

            // Save to storage
            $filename = 'screenshots/page_' . $pageId . '_' . time() . '.png';
            Storage::disk('public')->put($filename, $imageData);
            $storagePath = Storage::disk('public')->url($filename);

            // Save to database
            $pagePath = $page->is_homepage ? '/' : '/page/' . str_replace(' ', '-', strtolower($page->name));
            
            PageScreenshot::updateOrCreate(
                [
                    'page_path' => $pagePath,
                    'website_id' => $page->website_id
                ],
                [
                    'screenshot_path' => $storagePath,
                    'screenshot_url' => $storagePath,
                    'viewport_width' => $width,
                    'viewport_height' => $height,
                    'created_at' => now()
                ]
            );

            // Log screenshot capture details
            \Log::info('Page screenshot captured successfully', [
                'page_id' => $pageId,
                'page_name' => $page->name,
                'page_path' => $pagePath,
                'website_id' => $page->website_id,
                'screenshot_url' => $storagePath,
                'dimensions' => $width . 'x' . $height . 'px',
                'file_path' => 'storage/app/public/' . $filename,
                'timestamp' => now()
            ]);

            // Clean up temp file
            if (file_exists($tempPath)) {
                unlink($tempPath);
            }

            \Log::info("Screenshot captured successfully for page {$pageId}");
            return true;

        } catch (Exception $e) {
            \Log::error("Failed to capture screenshot for page {$pageId}: " . $e->getMessage());
            
            // Clean up temp file on error
            if (isset($tempPath) && file_exists($tempPath)) {
                unlink($tempPath);
            }
            
            return false;
        }
    }

    /**
     * Build the full URL for a page
     *
     * @param Page $page
     * @return string
     */
    protected function getPageUrl(Page $page)
    {
        // Get the website associated with this page
        $website = $page->website;
        
        // Build the base URL from the website's domain
        if ($website && $website->domain) {
            // Use the website's domain (e.g., example.com)
            $baseUrl = 'https://' . rtrim($website->domain, '/');
        } else {
            // Fallback to application URL
            $baseUrl = rtrim(config('app.url'), '/');
        }
        
        // Check if this is the homepage
        if ($page->is_homepage) {
            // Homepage is accessed via root path
            return $baseUrl . '/';
        }
        
        // Build page path for regular pages
        $pagePath = '/page/' . str_replace(' ', '-', strtolower($page->name));
        
        return $baseUrl . $pagePath;
    }

    /**
     * Capture screenshots for multiple pages
     *
     * @param array $pageIds
     * @return array Results array with success/failure status
     */
    public function captureMultipleScreenshots(array $pageIds)
    {
        $results = [];
        
        foreach ($pageIds as $pageId) {
            $results[$pageId] = $this->capturePageScreenshot($pageId);
        }
        
        return $results;
    }
}
