@extends('admin.main')

@section('title', 'Referrer Analytics')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-2">Referrer Analytics</h1>
                    <p class="text-muted">Track traffic sources and referral performance (Website-Based)</p>
                </div>
                <div class="btn-group">
                    <a href="{{ route('analytics.referrer.export', array_merge(request()->all(), ['format' => 'csv'])) }}" class="btn btn-success">
                        <i class="bi bi-filetype-csv"></i> Export CSV
                    </a>
                    <a href="{{ route('analytics.referrer.export', array_merge(request()->all(), ['format' => 'excel'])) }}" class="btn btn-primary">
                        <i class="bi bi-file-earmark-excel"></i> Export Excel
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters (WEBSITE-BASED) -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('analytics.referrer') }}" class="row g-3">
                @if(count($websites) > 1)
                <div class="col-md-3">
                    <label class="form-label">Website</label>
                    <select name="website_id" class="form-select">
                        <option value="">All Websites</option>
                        @foreach($websites as $website)
                            <option value="{{ $website->id }}" {{ $websiteId == $website->id ? 'selected' : '' }}>
                                {{ $website->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="col-md-3">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate->format('Y-m-d') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate->format('Y-m-d') }}">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Referral Traffic</p>
                            <h3 class="mb-0">{{ number_format($stats['total_with_referrer']) }}</h3>
                            <small class="text-primary">{{ $stats['referrer_percentage'] }}% of total</small>
                        </div>
                        <div class="text-primary fs-1">
                            <i class="bi bi-arrow-left-right"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-secondary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Direct Traffic</p>
                            <h3 class="mb-0">{{ number_format($stats['direct_traffic']) }}</h3>
                            <small class="text-secondary">No referrer</small>
                        </div>
                        <div class="text-secondary fs-1">
                            <i class="bi bi-box-arrow-in-down-right"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Referrer Revenue</p>
                            <h3 class="mb-0">${{ number_format($stats['referrer_revenue'], 2) }}</h3>
                            <small class="text-success">{{ number_format($stats['referrer_conversions']) }} conversions</small>
                        </div>
                        <div class="text-success fs-1">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Unique Referrers</p>
                            <h3 class="mb-0">{{ $stats['unique_referrers'] }}</h3>
                            <small class="text-info">Different domains</small>
                        </div>
                        <div class="text-info fs-1">
                            <i class="bi bi-globe"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Top Referrers Table -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-list-ol"></i> Top Referrers</h5>
                </div>
                <div class="card-body">
                    @if($topReferrers->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Referrer</th>
                                    <th class="text-end">Sessions</th>
                                    <th class="text-end">Visitors</th>
                                    <th class="text-end">Conversions</th>
                                    <th class="text-end">Conv. Rate</th>
                                    <th class="text-end">Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topReferrers as $referrer)
                                <tr>
                                    <td>
                                        <div>
                                            <strong>{{ $referrer['domain'] }}</strong>
                                            <br>
                                            <small class="text-muted text-truncate d-block" style="max-width: 300px;" title="{{ $referrer['referrer_url'] }}">
                                                {{ Str::limit($referrer['referrer_url'], 50) }}
                                            </small>
                                        </div>
                                    </td>
                                    <td class="text-end">{{ number_format($referrer['sessions']) }}</td>
                                    <td class="text-end">{{ number_format($referrer['visitors']) }}</td>
                                    <td class="text-end">
                                        <strong>{{ number_format($referrer['conversions']) }}</strong>
                                    </td>
                                    <td class="text-end">
                                        @if($referrer['conversion_rate'] >= 5)
                                            <span class="badge bg-success">{{ $referrer['conversion_rate'] }}%</span>
                                        @elseif($referrer['conversion_rate'] >= 2)
                                            <span class="badge bg-warning">{{ $referrer['conversion_rate'] }}%</span>
                                        @else
                                            <span class="badge bg-danger">{{ $referrer['conversion_rate'] }}%</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <strong class="text-success">${{ number_format($referrer['revenue'], 2) }}</strong>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox fs-1 text-muted"></i>
                        <p class="text-muted mt-2">No referrer data available for the selected period</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Referrer Performance by Domain -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Performance by Domain</h5>
                </div>
                <div class="card-body">
                    @if($performance->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Domain</th>
                                    <th class="text-end">Sessions</th>
                                    <th class="text-end">Visitors</th>
                                    <th class="text-end">Conversions</th>
                                    <th class="text-end">Conv. Rate</th>
                                    <th class="text-end">Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($performance as $perf)
                                <tr>
                                    <td>
                                        <i class="bi bi-globe2 text-muted me-2"></i>
                                        <strong>{{ $perf['domain'] }}</strong>
                                    </td>
                                    <td class="text-end">{{ number_format($perf['sessions']) }}</td>
                                    <td class="text-end">{{ number_format($perf['visitors']) }}</td>
                                    <td class="text-end">{{ number_format($perf['conversions']) }}</td>
                                    <td class="text-end">
                                        <span class="badge bg-{{ $perf['conversion_rate'] >= 5 ? 'success' : ($perf['conversion_rate'] >= 2 ? 'warning' : 'secondary') }}">
                                            {{ $perf['conversion_rate'] }}%
                                        </span>
                                    </td>
                                    <td class="text-end text-success fw-bold">${{ number_format($perf['revenue'], 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-muted text-center">No performance data available</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Traffic Sources & Charts -->
        <div class="col-md-4">
            <!-- Traffic Sources Breakdown -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-pie-chart"></i> Traffic Sources</h5>
                </div>
                <div class="card-body">
                    @if($sources->count() > 0)
                    <div class="mb-3">
                        <canvas id="sourcesChart" height="200"></canvas>
                    </div>
                    <div class="list-group list-group-flush">
                        @foreach($sources as $source)
                        <div class="list-group-item px-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $source['type'] }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        {{ number_format($source['sessions']) }} sessions
                                    </small>
                                </div>
                                <div class="text-end">
                                    <div class="text-success fw-bold">
                                        ${{ number_format($source['revenue'], 2) }}
                                    </div>
                                    <small class="text-muted">{{ $source['conversions'] }} conv</small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-muted text-center">No traffic source data available</p>
                    @endif
                </div>
            </div>

            <!-- Conversion by Type -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-graph-up"></i> Conversion Rates</h5>
                </div>
                <div class="card-body">
                    @if($conversionByType->count() > 0)
                    <canvas id="conversionChart" height="250"></canvas>
                    @else
                    <p class="text-muted text-center">No conversion data available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if($sources->count() > 0)
    // Traffic Sources Chart
    const sourcesData = {
        labels: {!! json_encode($sources->pluck('type')) !!},
        datasets: [{
            label: 'Sessions',
            data: {!! json_encode($sources->pluck('sessions')) !!},
            backgroundColor: [
                'rgba(54, 162, 235, 0.8)',
                'rgba(255, 99, 132, 0.8)',
                'rgba(255, 206, 86, 0.8)',
                'rgba(75, 192, 192, 0.8)',
                'rgba(153, 102, 255, 0.8)',
                'rgba(255, 159, 64, 0.8)'
            ]
        }]
    };

    new Chart(document.getElementById('sourcesChart'), {
        type: 'doughnut',
        data: sourcesData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
    @endif

    @if($conversionByType->count() > 0)
    // Conversion Rate Chart
    const conversionData = {
        labels: {!! json_encode($conversionByType->pluck('type')) !!},
        datasets: [{
            label: 'Conversion Rate (%)',
            data: {!! json_encode($conversionByType->pluck('conversion_rate')) !!},
            backgroundColor: 'rgba(75, 192, 192, 0.6)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 2
        }]
    };

    new Chart(document.getElementById('conversionChart'), {
        type: 'bar',
        data: conversionData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Conversion Rate: ' + context.parsed.y + '%';
                        }
                    }
                }
            }
        }
    });
    @endif
});
</script>
@endpush
@endsection
