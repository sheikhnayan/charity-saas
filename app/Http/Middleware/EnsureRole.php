<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureRole
{
    /**
     * Handle an incoming request.
     * Usage: ->middleware('role:superadmin|website_owner')
     */
    public function handle(Request $request, Closure $next, $roles)
    {
        if (!Auth::check()) {
            abort(403);
        }

        $user = Auth::user();
        $roleList = array_map('trim', explode('|', $roles));

        // Determine current website context from route or session
        $websiteId = $request->route('website') ?? $request->get('website_id') ?? session('website_id') ?? ($user->website_id ?? null);

        foreach ($roleList as $role) {
            if ($user->hasRoleForWebsite($role, $websiteId)) {
                return $next($request);
            }
        }

        abort(403);
    }
}
