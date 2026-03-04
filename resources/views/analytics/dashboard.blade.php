@extends('admin.main')

@section('title', 'Analytics Dashboard')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4">Analytics Dashboard</h1>
        
        <div class="d-flex gap-3">
            <!-- Export Buttons -->
            <div class="btn-group">
                <a href="{{ route('analytics.dashboard.export', array_merge(request()->all(), ['format' => 'csv'])) }}" class="btn btn-success">
                    <i class="bi bi-filetype-csv"></i> Export CSV
                </a>
                <a href="{{ route('analytics.dashboard.export', array_merge(request()->all(), ['format' => 'excel'])) }}" class="btn btn-primary">
                    <i class="bi bi-file-earmark-excel"></i> Export Excel
                </a>
            </div>
            
            <!-- Website Selector -->
            <form id="websiteForm" class="d-flex gap-3" action="{{ route('analytics.dashboard') }}" method="GET">
                <select name="website_id" class="form-select" onchange="this.form.submit()">
                    @foreach($websites as $website)
                        <option value="{{ $website->id }}" {{ $selectedWebsiteId == $website->id ? 'selected' : '' }}>
                            {{ $website->name }}
                        </option>
                    @endforeach
                </select>

                <!-- Date Range Filter -->
                <input type="date" name="start_date" class="form-control" value="{{ $startDate->format('Y-m-d') }}" max="{{ date('Y-m-d') }}">
                <input type="date" name="end_date" class="form-control" value="{{ $endDate->format('Y-m-d') }}" max="{{ date('Y-m-d') }}">
                <button type="submit" class="btn btn-primary">Apply Filter</button>
            </form>
        </div>
    </div>
    
    <!-- Overview Stats -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="h6 mb-0">Page Views</div>
                            <div class="display-6">{{ number_format($stats['today']['pageViews']) }}</div>
                        </div>
                        <i class="fas fa-eye fa-2x opacity-50"></i>
                    </div>
                    <div class="small mt-2">Today's traffic</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="h6 mb-0">Unique Visitors</div>
                            <div class="display-6">{{ number_format($stats['today']['uniqueVisitors']) }}</div>
                        </div>
                        <i class="fas fa-users fa-2x opacity-50"></i>
                    </div>
                    <div class="small mt-2">Today's visitors</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="h6 mb-0">Conversions</div>
                            <div class="display-6">{{ number_format($stats['today']['conversions']) }}</div>
                        </div>
                        <i class="fas fa-chart-line fa-2x opacity-50"></i>
                    </div>
                    <div class="small mt-2">Today's conversions</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="h6 mb-0">Revenue</div>
                            <div class="display-6">${{ number_format($stats['today']['revenue'], 2) }}</div>
                        </div>
                        <i class="fas fa-dollar-sign fa-2x opacity-50"></i>
                    </div>
                    <div class="small mt-2">Today's revenue</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Real-time Section -->
    <div class="row mb-4">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Real-time Activity</h5>
                    <span class="badge bg-success" id="active-users">-</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="realtime-activity">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Page</th>
                                    <th>User</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Traffic Overview</h5>
                </div>
                <div class="card-body">
                    <canvas id="trafficChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Device Breakdown</h5>
                </div>
                <div class="card-body">
                    <canvas id="deviceChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tables Row -->
    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Top Pages</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Page</th>
                                    <th>Views</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stats['topPages'] as $page)
                                <tr>
                                    <td>{{ $page->url }}</td>
                                    <td>{{ number_format($page->views) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Top Referrers</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Source</th>
                                    <th>Visits</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stats['topReferrers'] as $referrer)
                                <tr>
                                    <td>{{ $referrer->referrer_url }}</td>
                                    <td>{{ number_format($referrer->count) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
console.log('Analytics dashboard JavaScript loaded');
console.log('Selected website ID:', {{ $selectedWebsiteId }});
console.log('Stats data:', @json($stats));
// Traffic Overview Chart
const trafficCtx = document.getElementById('trafficChart').getContext('2d');
const trafficLabels = @json($stats['week']['dates']);
const pageViewsData = @json($stats['week']['pageViews']);
const uniqueVisitorsData = @json($stats['week']['uniqueVisitors']);

console.log('Traffic chart labels:', trafficLabels);
console.log('Page views data:', pageViewsData);
console.log('Unique visitors data:', uniqueVisitorsData);

new Chart(trafficCtx, {
    type: 'line',
    data: {
        labels: trafficLabels,
        datasets: [{
            label: 'Page Views',
            data: pageViewsData,
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.1)',
            tension: 0.1,
            fill: false
        }, {
            label: 'Unique Visitors',
            data: uniqueVisitorsData,
            borderColor: 'rgb(255, 99, 132)',
            backgroundColor: 'rgba(255, 99, 132, 0.1)',
            tension: 0.1,
            fill: false
        }]
    },
    options: {
        responsive: true,
        interaction: {
            intersect: false,
            mode: 'index'
        },
        plugins: {
            legend: {
                position: 'top'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// Device Breakdown Chart
const deviceCtx = document.getElementById('deviceChart').getContext('2d');
const deviceLabels = {!! json_encode($stats['deviceBreakdown']->pluck('device_type')) !!};
const deviceData = {!! json_encode($stats['deviceBreakdown']->pluck('count')) !!};

console.log('Device chart labels:', deviceLabels);
console.log('Device chart data:', deviceData);

new Chart(deviceCtx, {
    type: 'doughnut',
    data: {
        labels: deviceLabels,
        datasets: [{
            data: deviceData,
            backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 205, 86)',
                'rgb(255, 159, 64)',
                'rgb(153, 102, 255)'
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Real-time Updates
function updateRealTimeStats() {
    console.log('Updating real-time stats...');
    
    // Get the selected website ID
    const websiteId = {{ $selectedWebsiteId }};
    
    fetch(`/analytics/real-time?website_id=${websiteId}`)
        .then(response => {
            console.log('Real-time response:', response);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Real-time data:', data);
            
            // Update active users badge
            const activeUsersElement = document.getElementById('active-users');
            if (activeUsersElement) {
                activeUsersElement.textContent = (data.activeUsers || 0) + ' Active Users';
            }
            
            // Update recent activity table
            const tbody = document.querySelector('#realtime-activity tbody');
            if (tbody) {
                tbody.innerHTML = '';
                
                if (data.recentPageViews && data.recentPageViews.length > 0) {
                    data.recentPageViews.forEach(view => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${new Date(view.created_at).toLocaleTimeString()}</td>
                            <td>${view.url || view.page_url || 'Unknown'}</td>
                            <td>${view.user ? view.user.name : 'Anonymous'}</td>
                            <td>Page View</td>
                        `;
                        tbody.appendChild(tr);
                    });
                } else {
                    // Show "No recent activity" message
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td colspan="4" class="text-center text-muted">No recent activity</td>
                    `;
                    tbody.appendChild(tr);
                }
            }
        })
        .catch(error => {
            console.error('Error fetching real-time stats:', error);
            
            // Update UI to show error state
            const activeUsersElement = document.getElementById('active-users');
            if (activeUsersElement) {
                activeUsersElement.textContent = 'Error';
                activeUsersElement.className = 'badge bg-danger';
            }
        });
}

// Start real-time updates
console.log('Starting real-time analytics updates');
setInterval(updateRealTimeStats, 5000);
updateRealTimeStats();
</script>
@endsection