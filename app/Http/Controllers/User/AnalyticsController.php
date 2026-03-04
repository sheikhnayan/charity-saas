<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function dashboard()
    {
        // Redirect to admin analytics dashboard
        return redirect('/analytics');
    }

    public function utm()
    {
        // Redirect to admin UTM analytics
        return redirect('/analytics/utm');
    }
}
