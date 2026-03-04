<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\UniqueVisitorService;
use Symfony\Component\HttpFoundation\Response;

class TrackUniqueVisitor
{
    protected $uniqueVisitorService;

    public function __construct(UniqueVisitorService $uniqueVisitorService)
    {
        $this->uniqueVisitorService = $uniqueVisitorService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only track GET requests (not POST, API calls, etc.)
        if ($request->isMethod('GET') && !$request->is('api/*')) {
            try {
                // Track the visitor using Shopify's approach
                $this->uniqueVisitorService->trackVisitor($request);
            } catch (\Exception $e) {
                // Don't break the request if visitor tracking fails
                \Log::error('Unique visitor tracking failed: ' . $e->getMessage());
            }
        }

        return $next($request);
    }
}