<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Website;
use App\Models\User;

class NotificationController extends Controller
{
    /**
     * Show notification settings page for user's website
     */
    public function settings()
    {
        $user = Auth::user();
        
        // Get user's website (for site type detection)
        $website = Website::where('user_id', $user->id)->first();
        
        // Determine site type (fundraiser, investment, ticket, auction, etc.)
        $siteType = $website ? $website->type : 'fundraiser';
        
        return view('user.notifications', compact('user', 'website', 'siteType'));
    }
}
