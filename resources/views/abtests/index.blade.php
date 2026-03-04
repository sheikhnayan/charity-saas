@extends('admin.main')

@section('content')
<style>
    .ab-test-card {
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        transition: all 0.3s ease;
    }
    .ab-test-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    .variant-card {
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        transition: all 0.3s ease;
    }
    .variant-card.winner {
        border-color: #71dd37;
        background: linear-gradient(135deg, #e7ffe7 0%, #f5fff5 100%);
    }
    .variant-card.control {
        border-color: #696cff;
        background: linear-gradient(135deg, #e7e7ff 0%, #f5f5ff 100%);
    }
    .conversion-badge {
        font-size: 2rem;
        font-weight: 700;
        margin: 10px 0;
    }
    .significance-meter {
        height: 40px;
        background: linear-gradient(to right, #ff3e1d 0%, #ffab00 50%, #71dd37 100%);
        border-radius: 20px;
        position: relative;
        margin: 20px 0;
    }
    .significance-pointer {
        position: absolute;
        top: -10px;
        width: 3px;
        height: 60px;
        background: #000;
        transition: left 0.5s ease;
    }
    .chart-container {
        height: 350px;
        position: relative;
    }
</style>

<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="flex-grow-1">
            <h4 class="fw-bold mb-1">
                <i class="bx bx-test-tube text-primary me-2"></i>A/B Testing Dashboard
            </h4>
            <p class="text-muted mb-0">Data-driven experimentation platform • Inspired by Optimizely</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <select class="form-select form-select-sm" id="websiteFilter" style="width: 200px;">
                <option value="">All Websites</option>
                @foreach($websites as $website)
                    <option value="{{ $website->id }}" {{ $selectedWebsiteId == $website->id ? 'selected' : '' }}>
                        {{ $website->name }}
                    </option>
                @endforeach
            </select>
            <button class="btn btn-sm btn-primary" onclick="safeShowModal('#createTestModal')">
                <i class="bx bx-plus me-1"></i>Create Test
            </button>
            <button class="btn btn-sm btn-outline-secondary">
                <i class="bx bx-book-open me-1"></i>Test Library
            </button>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card ab-test-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Active Tests</p>
                            <h3 class="mb-0 fw-bold" id="active-tests">
                                <span class="spinner-border spinner-border-sm" role="status"></span>
                            </h3>
                            <small class="text-info">
                                <i class="bx bx-play-circle"></i> Running now
                            </small>
                        </div>
                        <div class="avatar">
                            <div class="avatar-initial rounded" style="background: #e7e7ff;">
                                <i class="bx bx-test-tube text-primary fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card ab-test-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Winners Found</p>
                            <h3 class="mb-0 fw-bold text-success" id="winners">
                                <span class="spinner-border spinner-border-sm" role="status"></span>
                            </h3>
                            <small class="text-success">
                                <i class="bx bx-trophy"></i> Significant results
                            </small>
                        </div>
                        <div class="avatar">
                            <div class="avatar-initial rounded" style="background: #e7ffe7;">
                                <i class="bx bx-trophy text-success fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card ab-test-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Total Participants</p>
                            <h3 class="mb-0 fw-bold" id="total-participants">
                                <span class="spinner-border spinner-border-sm" role="status"></span>
                            </h3>
                            <small class="text-muted">
                                <i class="bx bx-user"></i> Across all tests
                            </small>
                        </div>
                        <div class="avatar">
                            <div class="avatar-initial rounded" style="background: #fff3e0;">
                                <i class="bx bx-group text-warning fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card ab-test-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Avg Conversion Lift</p>
                            <h3 class="mb-0 fw-bold text-primary" id="avg-lift">
                                <span class="spinner-border spinner-border-sm" role="status"></span>
                            </h3>
                            <small class="text-primary">
                                <i class="bx bx-trending-up"></i> Performance gain
                            </small>
                        </div>
                        <div class="avatar">
                            <div class="avatar-initial rounded" style="background: #e7f7ff;">
                                <i class="bx bx-line-chart text-info fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Test - Only show if there's a test with a winner -->
    @php
        $featuredTest = $tests->firstWhere('winning_variant_id', '!=', null);
    @endphp
    
    @if($featuredTest)
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">Featured Test: {{ $featuredTest->name }}</h5>
                    <small class="text-muted">
                        @if($featuredTest->started_at)
                            Running for {{ $featuredTest->started_at->diffInDays(now()) }} days
                        @endif
                    </small>
                </div>
                <span class="badge bg-success">Winner Detected</span>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-4 mb-4">
                @php
                    $control = $featuredTest->results->where('variant.is_control', true)->first();
                    $winner = $featuredTest->results->where('variant_id', $featuredTest->winning_variant_id)->first();
                @endphp
                
                @if($control)
                <div class="col-md-6">
                    <div class="variant-card control">
                        <div class="badge bg-primary mb-2">Control</div>
                        <h6 class="fw-bold mb-3">{{ $control->variant->name ?? 'Control Variant' }}</h6>
                        <div class="conversion-badge text-primary">{{ number_format($control->conversion_rate, 1) }}%</div>
                        <p class="text-muted mb-3">Conversion Rate</p>
                        <div class="d-flex justify-content-around text-center">
                            <div>
                                <p class="mb-0 fw-bold">{{ number_format($control->conversions) }}</p>
                                <small class="text-muted">Conversions</small>
                            </div>
                            <div>
                                <p class="mb-0 fw-bold">{{ number_format($control->impressions) }}</p>
                                <small class="text-muted">Visitors</small>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                
                @if($winner)
                <div class="col-md-6">
                    <div class="variant-card winner">
                        <div class="badge bg-success mb-2">Winner! 🎉</div>
                        <h6 class="fw-bold mb-3">{{ $winner->variant->name ?? 'Winner Variant' }}</h6>
                        <div class="conversion-badge text-success">{{ number_format($winner->conversion_rate, 1) }}%</div>
                        <p class="text-muted mb-3">Conversion Rate</p>
                        <div class="d-flex justify-content-around text-center">
                            <div>
                                <p class="mb-0 fw-bold">{{ number_format($winner->conversions) }}</p>
                                <small class="text-muted">Conversions</small>
                            </div>
                            <div>
                                <p class="mb-0 fw-bold">{{ number_format($winner->impressions) }}</p>
                                <small class="text-muted">Visitors</small>
                            </div>
                        </div>
                        @if($control && $control->conversion_rate > 0)
                        @php
                            $improvement = (($winner->conversion_rate - $control->conversion_rate) / $control->conversion_rate) * 100;
                        @endphp
                        <div class="mt-3">
                            <span class="badge bg-success">+{{ number_format($improvement, 1) }}% Improvement</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            @if($winner && $winner->confidence_level)
            <!-- Statistical Significance Meter -->
            <div class="mt-4">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Statistical Significance</span>
                    <span class="fw-bold text-success">{{ number_format($winner->confidence_level, 1) }}%</span>
                </div>
                <div class="significance-meter">
                    <div class="significance-pointer" style="left: {{ $winner->confidence_level }}%;"></div>
                </div>
                <div class="d-flex justify-content-between text-muted small">
                    <span>Not Significant</span>
                    <span>95% Threshold</span>
                    <span>Highly Significant</span>
                </div>
            </div>
            @endif

            @if($control && $winner && $winner->is_significant)
            <div class="alert alert-success mt-4" role="alert">
                <i class="bx bx-check-circle me-2"></i>
                <strong>Test Complete:</strong> {{ $winner->variant->name }} shows a statistically significant improvement. 
                @if($control->conversion_rate > 0)
                    @php $lift = (($winner->conversion_rate - $control->conversion_rate) / $control->conversion_rate) * 100; @endphp
                    Consider implementing this change to increase conversions by {{ number_format($lift, 1) }}%.
                @endif
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Charts Row -->
    <div class="row g-3 mb-4">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Conversion Trends Over Time</h5>
                    <small class="text-muted">Daily conversion rates for each variant</small>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="conversionTrendsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Conversion Funnel</h5>
                    <small class="text-muted">User journey analysis</small>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="funnelChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- All Tests List -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">All A/B Tests</h5>
            <div class="dropdown">
                <button class="btn btn-sm btn-text-secondary rounded-pill dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="bx bx-filter me-1"></i>Filter
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">All Tests</a></li>
                    <li><a class="dropdown-item" href="#">Running</a></li>
                    <li><a class="dropdown-item" href="#">Completed</a></li>
                    <li><a class="dropdown-item" href="#">Paused</a></li>
                    <li><a class="dropdown-item" href="#">Draft</a></li>
                </ul>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Test Name</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Started</th>
                            <th>Participants</th>
                            <th>Variants</th>
                            <th>Best Performer</th>
                            <th>Confidence</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tests ?? [] as $test)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-2">
                                        <div class="avatar-initial rounded-circle bg-label-primary">
                                            <i class="bx bx-test-tube"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <strong>{{ $test->name }}</strong>
                                        @if($test->description)
                                        <br><small class="text-muted">{{ Str::limit($test->description, 40) }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-label-info">{{ ucfirst($test->test_type) }}</span>
                            </td>
                            <td>
                                @php
                                    $statusColors = [
                                        'draft' => 'secondary',
                                        'running' => 'primary',
                                        'paused' => 'warning',
                                        'completed' => 'success',
                                        'cancelled' => 'danger'
                                    ];
                                    $color = $statusColors[$test->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $color }}">
                                    @if($test->status === 'running')
                                    <i class="bx bx-play-circle"></i>
                                    @endif
                                    {{ ucfirst($test->status) }}
                                </span>
                            </td>
                            <td>{{ $test->started_at ? $test->started_at->format('M d, Y') : '-' }}</td>
                            <td>
                                <strong>{{ number_format($test->participants_count ?? 0) }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-label-secondary">{{ $test->variants_count ?? 2 }} Variants</span>
                            </td>
                            <td>
                                @if($test->winner_variant)
                                <span class="text-success">
                                    <i class="bx bx-trophy"></i> {{ $test->winner_variant }}
                                </span>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($test->confidence_level)
                                @php
                                    $confidenceColor = $test->confidence_level >= 95 ? 'success' : ($test->confidence_level >= 80 ? 'warning' : 'danger');
                                @endphp
                                <span class="text-{{ $confidenceColor }} fw-bold">{{ $test->confidence_level }}%</span>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn btn-sm btn-icon btn-text-secondary rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="/ab-tests/{{ $test->id }}/results"><i class="bx bx-show me-2"></i>View Results</a></li>
                                        <li><a class="dropdown-item" href="/ab-tests/{{ $test->id }}/edit"><i class="bx bx-edit me-2"></i>Edit</a></li>
                                        @if($test->status === 'draft')
                                        <li><a class="dropdown-item" href="#" onclick="startTest({{ $test->id }}); return false;"><i class="bx bx-play me-2"></i>Start Test</a></li>
                                        @elseif($test->status === 'running')
                                        <li><a class="dropdown-item" href="#" onclick="pauseTest({{ $test->id }}); return false;"><i class="bx bx-pause me-2"></i>Pause Test</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="endTest({{ $test->id }}); return false;"><i class="bx bx-stop-circle me-2"></i>End Test</a></li>
                                        @elseif($test->status === 'paused')
                                        <li><a class="dropdown-item" href="#" onclick="startTest({{ $test->id }}); return false;"><i class="bx bx-play me-2"></i>Resume Test</a></li>
                                        @endif
                                        <li><a class="dropdown-item" href="/ab-tests/{{ $test->id }}/export"><i class="bx bx-download me-2"></i>Export Data</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#" onclick="deleteTest({{ $test->id }}); return false;"><i class="bx bx-trash me-2"></i>Delete</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-5">
                                <i class="bx bx-test-tube display-1 mb-3"></i>
                                <p>No A/B tests found. Create your first test to start optimizing conversions.</p>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createTestModal">
                                    <i class="bx bx-plus me-1"></i>Create First Test
                                </button>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Create Test Modal -->
<div class="modal fade" id="createTestModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create A/B Test</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createTestForm">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Test Name</label>
                            <input type="text" class="form-control" name="name" required placeholder="e.g., Donation Button Color Test">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Test Type</label>
                            <select class="form-select" name="test_type" required>
                                <option value="button">Button</option>
                                <option value="headline">Headline</option>
                                <option value="layout">Layout</option>
                                <option value="form">Form</option>
                                <option value="color">Color</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="2" placeholder="Describe what you're testing..."></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Goal Metric</label>
                            <select class="form-select" name="goal_metric" required>
                                <option value="conversion_rate">Conversion Rate</option>
                                <option value="click_through_rate">Click-Through Rate</option>
                                <option value="donation_amount">Donation Amount</option>
                                <option value="time_on_page">Time on Page</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Traffic Split (%)</label>
                            <input type="number" class="form-control" name="traffic_percentage" value="50" min="10" max="90">
                        </div>
                    </div>
                    <div class="alert alert-info">
                        <i class="bx bx-info-circle me-2"></i>
                        <strong>Note:</strong> You'll configure variants and targeting after creating the test.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="createTest()">Create Test</button>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
// Website filter handler
const websiteFilter = document.getElementById('websiteFilter');
websiteFilter.addEventListener('change', function() {
    const websiteId = this.value;
    // Reload page with new website filter
    const url = new URL(window.location.href);
    if (websiteId) {
        url.searchParams.set('website_id', websiteId);
    } else {
        url.searchParams.delete('website_id');
    }
    window.location.href = url.toString();
});

// Load Stats - Real data from backend
document.getElementById('active-tests').textContent = '{{ $stats["running"] ?? 0 }}';
document.getElementById('winners').textContent = '{{ $stats["winners"] ?? 0 }}';
document.getElementById('total-participants').textContent = '{{ number_format($stats["total_participants"] ?? 0) }}';
const avgLift = {{ $stats["avg_lift"] ?? 0 }};
document.getElementById('avg-lift').textContent = (avgLift >= 0 ? '+' : '') + avgLift + '%';

// Conversion Trends Chart - Real Data
const ctx1 = document.getElementById('conversionTrendsChart').getContext('2d');
new Chart(ctx1, {
    type: 'line',
    data: {
        labels: @json($chartData['trend']['labels']),
        datasets: [{
            label: 'Total Conversions',
            data: @json($chartData['trend']['conversions']),
            borderColor: '#696cff',
            backgroundColor: 'rgba(105, 108, 255, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.dataset.label + ': ' + context.parsed.y + '%';
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: false,
                min: 20,
                max: 32,
                ticks: {
                    callback: function(value) {
                        return value + '%';
                    }
                }
            }
        }
    }
});

// Variant Performance Chart - Real Data
const ctx2 = document.getElementById('funnelChart').getContext('2d');
new Chart(ctx2, {
    type: 'bar',
    data: {
        labels: @json($chartData['variants']['labels']),
        datasets: [{
            label: 'Conversions',
            data: @json($chartData['variants']['conversions']),
            backgroundColor: 'rgba(113, 221, 55, 0.8)',
        }, {
            label: 'Views',
            data: @json($chartData['variants']['views']),
            backgroundColor: 'rgba(105, 108, 255, 0.8)',
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top'
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Create Test Function
function createTest() {
    const form = document.getElementById('createTestForm');
    const formData = new FormData(form);
    
    // Validate form
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    const data = {
        name: formData.get('name'),
        test_type: formData.get('test_type'),
        description: formData.get('description') || '',
        goal_metric: formData.get('goal_metric'),
        variants: [
            { 
                name: 'Control', 
                configuration: {}, 
                is_control: true, 
                traffic_percentage: 50 
            },
            { 
                name: 'Variant A', 
                configuration: {}, 
                is_control: false, 
                traffic_percentage: 50 
            }
        ],
        traffic_split: { control: 50, variant: 50 },
        min_sample_size: 100,
        confidence_level: 95
    };
    
    console.log('Sending AB Test data:', data);
    
    fetch('/ab-tests/', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(async response => {
        const responseData = await response.json();
        if (!response.ok) {
            throw new Error(responseData.message || JSON.stringify(responseData.errors || responseData));
        }
        return responseData;
    })
    .then(data => {
        console.log('Success:', data);
        if (data.message) {
            alert(data.message);
            window.location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to create test: ' + error.message);
    });
}

// Safe modal opener
function safeShowModal(selector) {
    try {
        const el = document.querySelector(selector);
        if (!el) return;
        if (window.bootstrap && bootstrap.Modal) {
            let modal = bootstrap.Modal.getInstance(el);
            if (!modal) modal = new bootstrap.Modal(el);
            modal.show();
            return;
        }
        el.classList.add('show');
        el.style.display = 'block';
        document.body.classList.add('modal-open');
        if (!document.querySelector('.modal-backdrop')) {
            const backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            document.body.appendChild(backdrop);
        }
    } catch (e) {
        console.error('safeShowModal error', e);
    }
}

// Test Action Functions
function startTest(testId) {
    if (!confirm('Are you sure you want to start this test?')) return;
    
    fetch(`/ab-tests/${testId}/start`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message || 'Test started successfully');
        window.location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to start test');
    });
}

function pauseTest(testId) {
    if (!confirm('Are you sure you want to pause this test?')) return;
    
    fetch(`/ab-tests/${testId}/pause`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message || 'Test paused successfully');
        window.location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to pause test');
    });
}

function endTest(testId) {
    if (!confirm('Are you sure you want to end this test? This action cannot be undone.')) return;
    
    fetch(`/ab-tests/${testId}/end`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message || 'Test ended successfully');
        window.location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to end test');
    });
}

function deleteTest(testId) {
    if (!confirm('Are you sure you want to delete this test? This will permanently remove all data.')) return;
    
    fetch(`/ab-tests/${testId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message || 'Test deleted successfully');
        window.location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to delete test');
    });
}
</script>
@endsection
