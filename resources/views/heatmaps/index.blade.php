@extends('admin.main')

@section('content')
<style>
    .heatmap-card {
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        transition: all 0.3s ease;
    }
    .heatmap-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    .heatmap-preview {
        position: relative;
        height: 400px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 8px;
        overflow: hidden;
    }
    .heatmap-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle at 50% 30%, rgba(255,0,0,0.6) 0%, rgba(255,255,0,0.3) 20%, transparent 40%),
                    radial-gradient(circle at 70% 60%, rgba(255,0,0,0.5) 0%, rgba(255,165,0,0.3) 15%, transparent 30%),
                    radial-gradient(circle at 30% 70%, rgba(255,0,0,0.4) 0%, rgba(255,255,0,0.2) 10%, transparent 25%);
    }
    .session-recording-card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
    }
    .session-recording-card:hover {
        border-color: #696cff;
        box-shadow: 0 2px 8px rgba(105,108,255,0.2);
    }
    .click-indicator {
        position: absolute;
        width: 20px;
        height: 20px;
        background: rgba(255,0,0,0.6);
        border: 2px solid #fff;
        border-radius: 50%;
        animation: pulse 1.5s ease-in-out infinite;
    }
    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.8; }
        50% { transform: scale(1.3); opacity: 1; }
    }
    .scroll-depth-bar {
        height: 30px;
        background: linear-gradient(to right, #e0e0e0, #696cff);
        border-radius: 15px;
        position: relative;
    }
    .chart-container {
        height: 300px;
        position: relative;
    }
</style>

<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">
                <i class="bx bx-map text-primary me-2"></i>Heatmaps & Session Recordings
            </h4>
            <p class="text-muted mb-0">Visualize user behavior and interactions • Inspired by Hotjar & FullStory</p>
        </div>
        <div>
            <button class="btn btn-sm btn-primary">
                <i class="bx bx-video me-1"></i>Watch Latest Session
            </button>
            <button class="btn btn-sm btn-outline-secondary ms-2">
                <i class="bx bx-cog me-1"></i>Recording Settings
            </button>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card heatmap-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Total Sessions</p>
                            <h3 class="mb-0 fw-bold">28,451</h3>
                            <small class="text-success">
                                <i class="bx bx-trending-up"></i> +15.3% this week
                            </small>
                        </div>
                        <div class="avatar">
                            <div class="avatar-initial rounded" style="background: #e7e7ff;">
                                <i class="bx bx-video text-primary fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card heatmap-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Avg Session Duration</p>
                            <h3 class="mb-0 fw-bold text-info">4m 32s</h3>
                            <small class="text-info">
                                <i class="bx bx-time"></i> +12s vs last week
                            </small>
                        </div>
                        <div class="avatar">
                            <div class="avatar-initial rounded" style="background: #e7f7ff;">
                                <i class="bx bx-time-five text-info fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card heatmap-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Rage Clicks Detected</p>
                            <h3 class="mb-0 fw-bold text-danger">234</h3>
                            <small class="text-danger">
                                <i class="bx bx-error-circle"></i> Needs attention
                            </small>
                        </div>
                        <div class="avatar">
                            <div class="avatar-initial rounded" style="background: #ffe0e0;">
                                <i class="bx bx-pointer text-danger fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card heatmap-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Avg Scroll Depth</p>
                            <h3 class="mb-0 fw-bold text-success">72%</h3>
                            <small class="text-success">
                                <i class="bx bx-trending-up"></i> +5% improvement
                            </small>
                        </div>
                        <div class="avatar">
                            <div class="avatar-initial rounded" style="background: #e7ffe7;">
                                <i class="bx bx-down-arrow-circle text-success fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Heatmap Type Selector -->
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Heatmap Visualization</h5>
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-primary active">Click Heatmap</button>
                    <button type="button" class="btn btn-outline-primary">Move Heatmap</button>
                    <button type="button" class="btn btn-outline-primary">Scroll Map</button>
                    <button type="button" class="btn btn-outline-primary">Attention Map</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-8">
                    <div class="heatmap-preview">
                        <div class="heatmap-overlay"></div>
                        <!-- Simulated Click Indicators -->
                        <div class="click-indicator" style="top: 28%; left: 48%;"></div>
                        <div class="click-indicator" style="top: 58%; left: 68%; animation-delay: 0.3s;"></div>
                        <div class="click-indicator" style="top: 68%; left: 32%; animation-delay: 0.6s;"></div>
                        
                        <div class="position-absolute bottom-0 start-0 w-100 p-3 bg-dark bg-opacity-75 text-white">
                            <p class="mb-0 small">
                                <i class="bx bx-info-circle me-1"></i>
                                Showing click distribution for <strong>Homepage</strong> | Last 7 days | 15,234 visitors
                            </p>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-center gap-2 mt-3">
                        <span class="badge" style="background: rgba(255,0,0,0.8);">Very High Activity</span>
                        <span class="badge" style="background: rgba(255,165,0,0.8);">High Activity</span>
                        <span class="badge" style="background: rgba(255,255,0,0.8);">Medium Activity</span>
                        <span class="badge" style="background: rgba(0,255,0,0.6);">Low Activity</span>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <h6 class="mb-3">Top Clicked Elements</h6>
                    <div class="list-group">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Donate Now Button</strong>
                                <br><small class="text-muted">Primary CTA</small>
                            </div>
                            <span class="badge bg-danger rounded-pill">3,245</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Featured Project Card</strong>
                                <br><small class="text-muted">Hero section</small>
                            </div>
                            <span class="badge bg-warning rounded-pill">2,189</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Navigation Menu</strong>
                                <br><small class="text-muted">Top bar</small>
                            </div>
                            <span class="badge bg-info rounded-pill">1,876</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Success Stories Link</strong>
                                <br><small class="text-muted">Footer</small>
                            </div>
                            <span class="badge bg-success rounded-pill">943</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll Depth Analysis -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Scroll Depth Analysis</h5>
            <small class="text-muted">How far users scroll on your pages</small>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="chart-container">
                        <canvas id="scrollDepthChart"></canvas>
                    </div>
                </div>
                <div class="col-md-6">
                    <h6 class="mb-3">Page Performance</h6>
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Homepage</span>
                            <span class="fw-bold">85%</span>
                        </div>
                        <div class="scroll-depth-bar">
                            <div style="width: 85%; height: 100%; background: #696cff; border-radius: 15px;"></div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Donation Page</span>
                            <span class="fw-bold">72%</span>
                        </div>
                        <div class="scroll-depth-bar">
                            <div style="width: 72%; height: 100%; background: #71dd37; border-radius: 15px;"></div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span>About Us</span>
                            <span class="fw-bold">58%</span>
                        </div>
                        <div class="scroll-depth-bar">
                            <div style="width: 58%; height: 100%; background: #ffab00; border-radius: 15px;"></div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Contact Page</span>
                            <span class="fw-bold">45%</span>
                        </div>
                        <div class="scroll-depth-bar">
                            <div style="width: 45%; height: 100%; background: #ff3e1d; border-radius: 15px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Session Recordings -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">Recent Session Recordings</h5>
                <small class="text-muted">Watch real user interactions</small>
            </div>
            <div class="dropdown">
                <button class="btn btn-sm btn-text-secondary rounded-pill dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="bx bx-filter me-1"></i>Filter
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">All Sessions</a></li>
                    <li><a class="dropdown-item" href="#">Converted</a></li>
                    <li><a class="dropdown-item" href="#">Abandoned Cart</a></li>
                    <li><a class="dropdown-item" href="#">Rage Clicks</a></li>
                    <li><a class="dropdown-item" href="#">Error Occurred</a></li>
                </ul>
            </div>
        </div>
        <div class="card-body">
            <!-- Session 1 -->
            <div class="session-recording-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="d-flex gap-3 flex-grow-1">
                        <div class="avatar avatar-lg">
                            <div class="avatar-initial rounded bg-label-primary">
                                <i class="bx bx-video fs-3"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <h6 class="mb-0">User #45231 • New York, US</h6>
                                    <small class="text-muted">
                                        <i class="bx bx-time me-1"></i>5 minutes ago
                                        <i class="bx bx-desktop ms-2 me-1"></i>Desktop • Chrome
                                    </small>
                                </div>
                                <div class="d-flex gap-2">
                                    <span class="badge bg-success">Converted</span>
                                    <button class="btn btn-sm btn-primary">
                                        <i class="bx bx-play me-1"></i>Play
                                    </button>
                                </div>
                            </div>
                            <div class="row g-3 mt-2">
                                <div class="col-md-3">
                                    <small class="text-muted d-block">Duration</small>
                                    <strong>4m 15s</strong>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted d-block">Pages Visited</small>
                                    <strong>7 pages</strong>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted d-block">Clicks</small>
                                    <strong>23 clicks</strong>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted d-block">Donation Amount</small>
                                    <strong class="text-success">$250</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Session 2 -->
            <div class="session-recording-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="d-flex gap-3 flex-grow-1">
                        <div class="avatar avatar-lg">
                            <div class="avatar-initial rounded bg-label-warning">
                                <i class="bx bx-video fs-3"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <h6 class="mb-0">User #45229 • London, UK</h6>
                                    <small class="text-muted">
                                        <i class="bx bx-time me-1"></i>12 minutes ago
                                        <i class="bx bx-mobile ms-2 me-1"></i>Mobile • Safari
                                    </small>
                                </div>
                                <div class="d-flex gap-2">
                                    <span class="badge bg-warning">Abandoned</span>
                                    <button class="btn btn-sm btn-primary">
                                        <i class="bx bx-play me-1"></i>Play
                                    </button>
                                </div>
                            </div>
                            <div class="row g-3 mt-2">
                                <div class="col-md-3">
                                    <small class="text-muted d-block">Duration</small>
                                    <strong>2m 48s</strong>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted d-block">Pages Visited</small>
                                    <strong>4 pages</strong>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted d-block">Clicks</small>
                                    <strong>15 clicks</strong>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted d-block">Exit Page</small>
                                    <strong class="text-warning">Checkout</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Session 3 -->
            <div class="session-recording-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="d-flex gap-3 flex-grow-1">
                        <div class="avatar avatar-lg">
                            <div class="avatar-initial rounded bg-label-danger">
                                <i class="bx bx-video fs-3"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <h6 class="mb-0">User #45227 • Toronto, CA</h6>
                                    <small class="text-muted">
                                        <i class="bx bx-time me-1"></i>28 minutes ago
                                        <i class="bx bx-desktop ms-2 me-1"></i>Desktop • Firefox
                                    </small>
                                </div>
                                <div class="d-flex gap-2">
                                    <span class="badge bg-danger">Rage Click</span>
                                    <button class="btn btn-sm btn-primary">
                                        <i class="bx bx-play me-1"></i>Play
                                    </button>
                                </div>
                            </div>
                            <div class="row g-3 mt-2">
                                <div class="col-md-3">
                                    <small class="text-muted d-block">Duration</small>
                                    <strong>6m 32s</strong>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted d-block">Pages Visited</small>
                                    <strong>5 pages</strong>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted d-block">Clicks</small>
                                    <strong class="text-danger">87 clicks</strong>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted d-block">Issue</small>
                                    <strong class="text-danger">Button not working</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-3">
                <button class="btn btn-outline-primary btn-sm">
                    Load More Sessions
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
// Scroll Depth Chart
const ctx = document.getElementById('scrollDepthChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['0-25%', '25-50%', '50-75%', '75-100%'],
        datasets: [{
            label: 'Users',
            data: [15234, 12456, 8923, 5234],
            backgroundColor: [
                'rgba(105, 108, 255, 0.8)',
                'rgba(113, 221, 55, 0.8)',
                'rgba(255, 171, 0, 0.8)',
                'rgba(255, 62, 29, 0.8)'
            ],
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    afterLabel: function(context) {
                        const percentage = (context.parsed.y / 15234 * 100).toFixed(1);
                        return percentage + '% of total visitors';
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
@endsection
