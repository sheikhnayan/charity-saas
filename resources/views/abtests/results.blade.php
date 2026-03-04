@extends('admin.main')

@section('content')
<style>
    .result-card {
        border-radius: 8px;
        border: 2px solid #e0e0e0;
        transition: all 0.3s ease;
    }
    .result-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    .result-card.winner {
        border-color: #71dd37;
        background: linear-gradient(135deg, #e7ffe7 0%, #f5fff5 100%);
    }
    .result-card.control {
        border-color: #696cff;
        background: linear-gradient(135deg, #e7e7ff 0%, #f5f5ff 100%);
    }
    .metric-value {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 10px 0;
    }
    .chart-container {
        height: 400px;
        position: relative;
    }
</style>

<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/ab-tests">A/B Tests</a></li>
                    <li class="breadcrumb-item active">Test Results</li>
                </ol>
            </nav>
            <h4 class="fw-bold mb-1">
                <i class="bx bx-bar-chart text-primary me-2"></i>{{ $test->name }}
            </h4>
            <p class="text-muted mb-0">{{ $test->description }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="/ab-tests/{{ $test->id }}/export" class="btn btn-sm btn-success">
                <i class="bx bx-download me-1"></i>Export Data
            </a>
            @if($test->status === 'running')
            <button class="btn btn-sm btn-warning" onclick="pauseTest({{ $test->id }})">
                <i class="bx bx-pause me-1"></i>Pause Test
            </button>
            <button class="btn btn-sm btn-danger" onclick="endTest({{ $test->id }})">
                <i class="bx bx-stop-circle me-1"></i>End Test
            </button>
            @endif
            <a href="/ab-tests/{{ $test->id }}/edit" class="btn btn-sm btn-primary">
                <i class="bx bx-edit me-1"></i>Edit
            </a>
        </div>
    </div>

    <!-- Test Info Card -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <small class="text-muted">Status</small>
                    <h5 class="mb-0 mt-1">
                        @php
                            $statusColors = [
                                'draft' => 'secondary',
                                'running' => 'primary',
                                'paused' => 'warning',
                                'completed' => 'success',
                            ];
                            $color = $statusColors[$test->status] ?? 'secondary';
                        @endphp
                        <span class="badge bg-{{ $color }}">{{ ucfirst($test->status) }}</span>
                    </h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <small class="text-muted">Started</small>
                    <h5 class="mb-0 mt-1">{{ $test->started_at ? $test->started_at->format('M d, Y') : 'Not started' }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <small class="text-muted">Duration</small>
                    <h5 class="mb-0 mt-1">
                        @if($test->started_at)
                            {{ $test->started_at->diffInDays($test->ended_at ?? now()) }} days
                        @else
                            -
                        @endif
                    </h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <small class="text-muted">Goal Metric</small>
                    <h5 class="mb-0 mt-1">{{ ucwords(str_replace('_', ' ', $test->goal_metric)) }}</h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Variant Results -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Variant Performance</h5>
        </div>
        <div class="card-body">
            <div class="row g-4">
                @foreach($variantStats as $stat)
                @php
                    $variant = $stat['variant'];
                    $result = $stat['result'];
                    $isWinner = $test->winning_variant_id == $variant->id;
                    $isControl = $variant->is_control;
                @endphp
                <div class="col-md-6">
                    <div class="result-card {{ $isWinner ? 'winner' : ($isControl ? 'control' : '') }} p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="mb-1">{{ $variant->name }}</h5>
                                @if($isControl)
                                <span class="badge bg-primary">Control</span>
                                @endif
                                @if($isWinner)
                                <span class="badge bg-success">Winner 🎉</span>
                                @endif
                            </div>
                            <div class="text-end">
                                <small class="text-muted">Traffic Split</small>
                                <h6 class="mb-0">{{ $variant->traffic_percentage }}%</h6>
                            </div>
                        </div>

                        @if($result)
                        <!-- Conversion Rate -->
                        <div class="text-center mb-4">
                            <small class="text-muted">Conversion Rate</small>
                            <div class="metric-value {{ $isWinner ? 'text-success' : 'text-primary' }}">
                                {{ number_format($result->conversion_rate, 2) }}%
                            </div>
                        </div>

                        <!-- Stats Grid -->
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <div class="text-center p-3 bg-white rounded">
                                    <small class="text-muted d-block">Impressions</small>
                                    <h5 class="mb-0">{{ number_format($result->impressions) }}</h5>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-3 bg-white rounded">
                                    <small class="text-muted d-block">Conversions</small>
                                    <h5 class="mb-0">{{ number_format($result->conversions) }}</h5>
                                </div>
                            </div>
                            @if($result->total_revenue > 0)
                            <div class="col-6">
                                <div class="text-center p-3 bg-white rounded">
                                    <small class="text-muted d-block">Total Revenue</small>
                                    <h5 class="mb-0">${{ number_format($result->total_revenue, 2) }}</h5>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-3 bg-white rounded">
                                    <small class="text-muted d-block">Avg Revenue/User</small>
                                    <h5 class="mb-0">${{ number_format($result->avg_revenue_per_user, 2) }}</h5>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Statistical Significance -->
                        @if($result->confidence_level && !$isControl)
                        <div class="mt-3">
                            <div class="d-flex justify-content-between mb-2">
                                <small class="text-muted">Statistical Confidence</small>
                                <strong class="{{ $result->is_significant ? 'text-success' : 'text-muted' }}">
                                    {{ number_format($result->confidence_level, 1) }}%
                                </strong>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar {{ $result->is_significant ? 'bg-success' : 'bg-warning' }}" 
                                     style="width: {{ $result->confidence_level }}%"></div>
                            </div>
                            @if($result->is_significant)
                            <small class="text-success"><i class="bx bx-check-circle"></i> Statistically Significant</small>
                            @else
                            <small class="text-muted">Not yet significant (need 95%)</small>
                            @endif
                        </div>
                        @endif
                        @else
                        <div class="text-center text-muted py-5">
                            <i class="bx bx-data display-4"></i>
                            <p class="mt-3">No data collected yet</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Conversion Trend Chart -->
    @if($conversionTrend->isNotEmpty())
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Conversion Trend Over Time</h5>
        </div>
        <div class="card-body">
            <div class="chart-container">
                <canvas id="conversionTrendChart"></canvas>
            </div>
        </div>
    </div>
    @endif

    <!-- Detailed Stats Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Detailed Statistics</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Variant</th>
                            <th>Impressions</th>
                            <th>Conversions</th>
                            <th>Conversion Rate</th>
                            <th>Total Revenue</th>
                            <th>Avg Revenue/User</th>
                            <th>Confidence</th>
                            <th>Significant</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($variantStats as $stat)
                        @php
                            $variant = $stat['variant'];
                            $result = $stat['result'];
                        @endphp
                        <tr>
                            <td>
                                <strong>{{ $variant->name }}</strong>
                                @if($variant->is_control)
                                <span class="badge bg-label-primary">Control</span>
                                @endif
                                @if($test->winning_variant_id == $variant->id)
                                <span class="badge bg-label-success">Winner</span>
                                @endif
                            </td>
                            <td>{{ $result ? number_format($result->impressions) : '0' }}</td>
                            <td>{{ $result ? number_format($result->conversions) : '0' }}</td>
                            <td>
                                @if($result)
                                <strong>{{ number_format($result->conversion_rate, 2) }}%</strong>
                                @else
                                -
                                @endif
                            </td>
                            <td>{{ $result && $result->total_revenue ? '$' . number_format($result->total_revenue, 2) : '-' }}</td>
                            <td>{{ $result && $result->avg_revenue_per_user ? '$' . number_format($result->avg_revenue_per_user, 2) : '-' }}</td>
                            <td>
                                @if($result && $result->confidence_level && !$variant->is_control)
                                {{ number_format($result->confidence_level, 1) }}%
                                @else
                                -
                                @endif
                            </td>
                            <td>
                                @if($result && $result->is_significant)
                                <span class="badge bg-success">Yes</span>
                                @else
                                <span class="badge bg-secondary">No</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@if($conversionTrend->isNotEmpty())
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const ctx = document.getElementById('conversionTrendChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json($conversionTrend->pluck('date')),
        datasets: [{
            label: 'Conversions',
            data: @json($conversionTrend->pluck('conversions')),
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
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

function pauseTest(testId) {
    if (!confirm('Pause this test?')) return;
    fetch(`/ab-tests/${testId}/pause`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        window.location.reload();
    });
}

function endTest(testId) {
    if (!confirm('End this test? This cannot be undone.')) return;
    fetch(`/ab-tests/${testId}/end`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        window.location.reload();
    });
}
</script>
@endif
@endsection
