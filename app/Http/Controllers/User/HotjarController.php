<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HotjarController extends Controller
{
    public function heatmaps()
    {
        // Redirect to admin heatmaps
        return redirect('/hotjar/heatmaps');
    }

    public function recordings()
    {
        // Redirect to admin recordings
        return redirect('/hotjar/recordings');
    }
}
