<!DOCTYPE html>
<html>
<head>
    <title>Analytics Debug - Charity Platform</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .debug-section { background: #f5f5f5; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .error { background: #ffebee; color: #c62828; }
        .success { background: #e8f5e9; color: #2e7d32; }
        .warning { background: #fff3e0; color: #ef6c00; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
    <h1>Analytics Debug Report</h1>
    <p><strong>Generated:</strong> {{ now()->format('Y-m-d H:i:s') }}</p>
    
    <div class="debug-section">
        <h3>🔍 Database Connection Test</h3>
        @php
            try {
                $dbTest = DB::connection()->getPdo();
                echo '<div class="success">✅ Database connection successful</div>';
            } catch(Exception $e) {
                echo '<div class="error">❌ Database connection failed: ' . $e->getMessage() . '</div>';
            }
        @endphp
    </div>
 
    <div class="debug-section">
        <h3>� Funnel Step Analysis</h3>
        @php
            $funnelSteps = \App\Models\PaymentFunnelEvent::select('funnel_step')
                ->selectRaw('COUNT(*) as count')
                ->groupBy('funnel_step')
                ->orderByDesc('count')
                ->get();
        @endphp
        
        <p><strong>Available Funnel Steps in Database:</strong></p>
        @if($funnelSteps->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Funnel Step</th>
                        <th>Count</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($funnelSteps as $step)
                        <tr>
                            <td><code>{{ $step->funnel_step }}</code></td>
                            <td>{{ number_format($step->count) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="error">❌ No funnel steps found</div>
        @endif
    </div>

    <div class="debug-section">
        <h3>�📊 PaymentFunnelEvent Data Overview</h3>
        @php
            $totalEvents = \App\Models\PaymentFunnelEvent::count();
            
            // Try multiple possible completion step names
            $completionSteps = ['payment_completed', 'payment_complete', 'completed', 'payment_success', 'success'];
            $totalCompletedPayments = 0;
            $actualCompletionStep = null;
            
            foreach ($completionSteps as $step) {
                $count = \App\Models\PaymentFunnelEvent::where('funnel_step', $step)->count();
                if ($count > 0) {
                    $totalCompletedPayments = $count;
                    $actualCompletionStep = $step;
                    break;
                }
            }
            
            $websiteDataCounts = \App\Models\PaymentFunnelEvent::select('website_id')
                ->selectRaw('COUNT(*) as total_events')
                ->selectRaw('COUNT(CASE WHEN funnel_step = "' . ($actualCompletionStep ?? 'payment_completed') . '" THEN 1 END) as completed_payments')
                ->selectRaw('SUM(CASE WHEN funnel_step = "' . ($actualCompletionStep ?? 'payment_completed') . '" THEN amount ELSE 0 END) as total_revenue')
                ->groupBy('website_id')
                ->orderByDesc('total_events')
                ->get();
        @endphp
        
        @if($actualCompletionStep)
            <div class="success">✅ Found completion step: <code>{{ $actualCompletionStep }}</code></div>
        @else
            <div class="error">❌ No completion step found. Looking for: {{ implode(', ', $completionSteps) }}</div>
        @endif
        
        <p><strong>Total PaymentFunnelEvent records:</strong> {{ number_format($totalEvents) }}</p>
        <p><strong>Total completed payments:</strong> {{ number_format($totalCompletedPayments) }}</p>
        
        @if($websiteDataCounts->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Website ID</th>
                        <th>Website Name</th>
                        <th>Total Events</th>
                        <th>Completed Payments</th>
                        <th>Total Revenue (Raw)</th>
                        <th>Revenue (Formatted)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($websiteDataCounts as $data)
                        @php
                            $website = \App\Models\Website::find($data->website_id);
                        @endphp
                        <tr>
                            <td>{{ $data->website_id }}</td>
                            <td>{{ $website ? $website->name : 'Unknown' }} ({{ $website ? $website->type : 'N/A' }})</td>
                            <td>{{ number_format($data->total_events) }}</td>
                            <td>{{ number_format($data->completed_payments) }}</td>
                            <td>${{ number_format($data->total_revenue / 100, 2) }}</td>
                            <td>${{ number_format($data->total_revenue, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="error">❌ No PaymentFunnelEvent data found</div>
        @endif
    </div>

    <div class="debug-section">
        <h3>👤 User Access & Website Selection</h3>
        @auth
            <p><strong>User:</strong> {{ auth()->user()->email ?? 'N/A' }}</p>
            <p><strong>Role:</strong> {{ auth()->user()->role ?? 'N/A' }}</p>
            
            @php
                if (auth()->user()->role === 'admin') {
                    $userWebsites = \App\Models\Website::all(['id', 'name', 'type']);
                } else {
                    $userWebsites = \App\Models\Website::where('user_id', auth()->id())->get(['id', 'name', 'type']);
                }
            @endphp
            
            <p><strong>Accessible Websites:</strong></p>
            @if($userWebsites->count() > 0)
                <ul>
                    @foreach($userWebsites as $website)
                        @php
                            $paymentCount = \App\Models\PaymentFunnelEvent::where('website_id', $website->id)->count();
                            $completedCount = \App\Models\PaymentFunnelEvent::where('website_id', $website->id)->where('funnel_step', 'payment_completed')->count();
                        @endphp
                        <li>
                            <strong>{{ $website->name }}</strong> (ID: {{ $website->id }}, Type: {{ $website->type ?? 'N/A' }})
                            - {{ $paymentCount }} events, {{ $completedCount }} completed payments
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="warning">⚠️ No websites accessible to current user</div>
            @endif
        @else
            <div class="error">❌ User not authenticated</div>
        @endauth
    </div>

    <div class="debug-section">
        <h3>📅 Recent PaymentFunnelEvent Records</h3>
        @php
            $recentEvents = \App\Models\PaymentFunnelEvent::with('website')
                ->orderByDesc('created_at')
                ->limit(10)
                ->get(['id', 'website_id', 'funnel_step', 'form_type', 'amount', 'created_at']);
        @endphp
        
        @if($recentEvents->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Website</th>
                        <th>Funnel Step</th>
                        <th>Form Type</th>
                        <th>Amount</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentEvents as $event)
                        <tr>
                            <td>{{ $event->id }}</td>
                            <td>{{ $event->website_id }} ({{ $event->website->name ?? 'Unknown' }})</td>
                            <td>{{ $event->funnel_step }}</td>
                            <td>{{ $event->form_type }}</td>
                            <td>${{ $event->amount ? number_format($event->amount / 100, 2) : 'N/A' }}</td>
                            <td>{{ $event->created_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="error">❌ No recent PaymentFunnelEvent records found</div>
        @endif
    </div>

    <div class="debug-section">
        <h3>🔧 Controller Debug Information</h3>
        @php
            // Simulate what the controller would select
            if (auth()->check()) {
                if (auth()->user()->role === 'admin') {
                    $websites = \App\Models\Website::all();
                } else {
                    $websites = \App\Models\Website::where('user_id', auth()->id())->get();
                }
                
                // Find website with most PaymentFunnelEvent data
                $websiteWithData = \App\Models\PaymentFunnelEvent::select('website_id')
                    ->groupBy('website_id')
                    ->orderByRaw('COUNT(*) DESC')
                    ->first();
                    
                $selectedWebsiteId = $websiteWithData ? $websiteWithData->website_id : ($websites->first()->id ?? null);
                
                echo "<p><strong>Selected Website ID:</strong> $selectedWebsiteId</p>";
                
                if ($selectedWebsiteId) {
                    $startDate = now()->subDays(90)->startOfDay();
                    $endDate = now()->endOfDay();
                    
                    $conversions = \App\Models\PaymentFunnelEvent::where('funnel_step', 'payment_completed')
                        ->where('website_id', $selectedWebsiteId)
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->count();
                        
                    $revenue = \App\Models\PaymentFunnelEvent::where('funnel_step', 'payment_completed')
                        ->where('website_id', $selectedWebsiteId)
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->sum('amount') ?? 0;
                        
                    $visitors = \App\Models\PaymentFunnelEvent::where('website_id', $selectedWebsiteId)
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->distinct('visitor_id')
                        ->count('visitor_id');
                    
                    echo "<p><strong>Date Range:</strong> {$startDate->format('Y-m-d')} to {$endDate->format('Y-m-d')}</p>";
                    echo "<p><strong>Conversions Found:</strong> $conversions</p>";
                    echo "<p><strong>Revenue Found:</strong> \$" . number_format($revenue / 100, 2) . " (Raw: $revenue)</p>";
                    echo "<p><strong>Unique Visitors:</strong> $visitors</p>";
                }
            } else {
                echo '<div class="error">User not authenticated - cannot test controller logic</div>';
            }
        @endphp
    </div>

    <div class="debug-section">
        <h3>🎯 Recommendations</h3>
        @php
            $recommendations = [];
            
            if (\App\Models\PaymentFunnelEvent::count() === 0) {
                $recommendations[] = "❌ No PaymentFunnelEvent data found. Check if the tracking is working.";
            }
            
            if (auth()->check()) {
                $userWebsiteCount = auth()->user()->role === 'admin' ? 
                    \App\Models\Website::count() : 
                    \App\Models\Website::where('user_id', auth()->id())->count();
                    
                if ($userWebsiteCount === 0) {
                    $recommendations[] = "⚠️ User has no accessible websites.";
                }
            }
            
            $websitesWithData = \App\Models\PaymentFunnelEvent::distinct('website_id')->count();
            $totalWebsites = \App\Models\Website::count();
            
            if ($websitesWithData < $totalWebsites) {
                $recommendations[] = "⚠️ Only $websitesWithData out of $totalWebsites websites have PaymentFunnelEvent data.";
            }
            
            if (empty($recommendations)) {
                $recommendations[] = "✅ Data structure looks good! Check the date range and website selection in the dashboard.";
            }
        @endphp
        
        @foreach($recommendations as $recommendation)
            <p>{{ $recommendation }}</p>
        @endforeach
    </div>

    <div class="debug-section">
        <h3>🌍 Location Data Check</h3>
        @php
            $eventsWithLocation = \App\Models\PaymentFunnelEvent::whereNotNull('country_code')->count();
            $locationBreakdown = \App\Models\PaymentFunnelEvent::whereNotNull('country_code')
                ->select('country_code', 'country', 'state')
                ->selectRaw('COUNT(*) as count')
                ->groupBy('country_code', 'country', 'state')
                ->orderByDesc('count')
                ->limit(10)
                ->get();
        @endphp
        
        <p><strong>Location Data Status:</strong></p>
        <ul>
            <li>Total Events: <strong>{{ number_format($totalEvents) }}</strong></li>
            <li>Events with Location: <strong>{{ number_format($eventsWithLocation) }}</strong> ({{ $totalEvents > 0 ? round(($eventsWithLocation / $totalEvents) * 100, 1) : 0 }}%)</li>
        </ul>

        @if($locationBreakdown->count() > 0)
            <p><strong>Top 10 Locations:</strong></p>
            <table>
                <thead>
                    <tr>
                        <th>Country Code</th>
                        <th>Location</th>
                        <th>Count</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($locationBreakdown as $loc)
                        <tr>
                            <td><code>{{ $loc->country_code }}</code></td>
                            <td>{{ $loc->state ? $loc->state . ', ' : '' }}{{ $loc->country ?: $loc->country_code }}</td>
                            <td>{{ number_format($loc->count) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="error">❌ No location data found!</div>
            <p><strong>Solution:</strong> Run this command on your server:</p>
            <code style="background: #333; color: #0f0; padding: 10px; display: block; margin: 10px 0;">php update_locations.php</code>
        @endif
    </div>

    <div class="debug-section">
        <h3>⏱️ Real-Time Activity Test</h3>
        @php
            $last7Days = now()->subDays(7);
            $recentActivity = \App\Models\PaymentFunnelEvent::where('created_at', '>=', $last7Days)
                ->orderByDesc('created_at')
                ->limit(20)
                ->get(['id', 'funnel_step', 'form_type', 'amount', 'country', 'state', 'created_at']);
        @endphp
        
        <p><strong>Recent Activity (Last 7 Days):</strong></p>
        <p>Found <strong>{{ $recentActivity->count() }}</strong> events</p>

        @if($recentActivity->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Time</th>
                        <th>Event</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Location</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentActivity as $activity)
                        <tr>
                            <td>{{ $activity->id }}</td>
                            <td><small>{{ $activity->created_at->diffForHumans() }}</small></td>
                            <td><code>{{ $activity->funnel_step }}</code></td>
                            <td><span style="background: #e3f2fd; padding: 2px 6px; border-radius: 3px;">{{ $activity->form_type }}</span></td>
                            <td>{{ $activity->amount ? '$' . number_format($activity->amount, 2) : '-' }}</td>
                            <td>{{ $activity->state ? $activity->state . ', ' : '' }}{{ $activity->country ?: 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="error">❌ No activity in the last 7 days! This is why real-time feed is empty.</div>
            <p><strong>What this means:</strong> Visitors aren't creating payment_funnel_events when they visit your site.</p>
        @endif
    </div>

    <div class="debug-section">
        <h3>🔗 API Endpoint Tests (Click to Test)</h3>
        <p><strong>Test these URLs directly in your browser:</strong></p>
        
        @php
            $testWebsiteId = \App\Models\PaymentFunnelEvent::first()->website_id ?? 1;
            $testStartDate = now()->subDays(90)->format('Y-m-d');
            $testEndDate = now()->format('Y-m-d');
        @endphp

        <ul style="list-style: none; padding: 0;">
            <li style="margin: 15px 0;">
                <strong>📊 Location API:</strong><br>
                <a href="{{ url('/analytics/api/locations') }}?website_id={{ $testWebsiteId }}&start_date={{ $testStartDate }}&end_date={{ $testEndDate }}" target="_blank" style="background: #2196F3; color: white; padding: 8px 15px; text-decoration: none; border-radius: 4px; display: inline-block; margin-top: 5px;">
                    Test Location API
                </a>
            </li>
            <li style="margin: 15px 0;">
                <strong>🗺️ Geomap API:</strong><br>
                <a href="{{ url('/analytics/api/geomap') }}?website_id={{ $testWebsiteId }}&start_date={{ $testStartDate }}&end_date={{ $testEndDate }}" target="_blank" style="background: #4CAF50; color: white; padding: 8px 15px; text-decoration: none; border-radius: 4px; display: inline-block; margin-top: 5px;">
                    Test Geomap API
                </a>
            </li>
            <li style="margin: 15px 0;">
                <strong>⏱️ Real-Time API:</strong><br>
                <a href="{{ url('/analytics/real-time') }}?website_id={{ $testWebsiteId }}" target="_blank" style="background: #FF9800; color: white; padding: 8px 15px; text-decoration: none; border-radius: 4px; display: inline-block; margin-top: 5px;">
                    Test Real-Time API
                </a>
            </li>
            <li style="margin: 15px 0;">
                <strong>📈 Full Dashboard:</strong><br>
                <a href="{{ url('/analytics/dashboard') }}" target="_blank" style="background: #9C27B0; color: white; padding: 8px 15px; text-decoration: none; border-radius: 4px; display: inline-block; margin-top: 5px;">
                    Open Dashboard
                </a>
            </li>
        </ul>

        <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 10px; margin-top: 15px;">
            <p><strong>📝 What to Check:</strong></p>
            <ul>
                <li><strong>Location API:</strong> Should return array with <code>country_name</code>, <code>visitors</code>, <code>conversions</code>, <code>revenue</code></li>
                <li><strong>Geomap API:</strong> Should return array with <code>lat</code>, <code>lng</code>, <code>country_name</code>, <code>visitors</code></li>
                <li><strong>Real-Time API:</strong> Should return <code>recentPageViews</code> array with activity (check if empty or has data)</li>
            </ul>
        </div>
    </div>

    <p><em>This debug page helps identify issues with the analytics dashboard. Please share this information when reporting problems.</em></p>
</body>
</html>