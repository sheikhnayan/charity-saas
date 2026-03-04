@extends('admin.main')

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Payment Funnel Analytics</h5>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-primary" onclick="refreshData()">
                                <i class="fas fa-sync"></i> Refresh
                            </button>
                            <a href="{{ route('admin.payment-funnel.export', ['website_id' => $website->id, 'date_from' => $dateFrom, 'date_to' => $dateTo]) }}" 
                               class="btn btn-success">
                                <i class="fas fa-download"></i> Export CSV
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Date Range Filter -->
                        <form method="GET" class="row g-3 mb-4">
                            <input type="hidden" name="website_id" value="{{ $website->id }}">
                            <div class="col-md-4">
                                <label for="date_from" class="form-label">From Date</label>
                                <input type="date" class="form-control" id="date_from" name="date_from" value="{{ $dateFrom }}">
                            </div>
                            <div class="col-md-4">
                                <label for="date_to" class="form-label">To Date</label>
                                <input type="date" class="form-control" id="date_to" name="date_to" value="{{ $dateTo }}">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary">Apply Filter</button>
                            </div>
                        </form>

                        <!-- Key Metrics Row -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <h6 class="card-title mb-1">Form Views</h6>
                                                <h4 class="mb-0">{{ number_format($conversionData['form_view']['count']) }}</h4>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-eye fa-2x opacity-75"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <h6 class="card-title mb-1">Completed Payments</h6>
                                                <h4 class="mb-0">{{ number_format($conversionData['payment_completed']['count']) }}</h4>
                                                <small class="opacity-75">{{ $conversionData['payment_completed']['conversion_rate'] }}% conversion</small>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-check-circle fa-2x opacity-75"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <h6 class="card-title mb-1">Total Revenue</h6>
                                                <h4 class="mb-0">${{ number_format($revenueData['total_revenue'], 2) }}</h4>
                                                <small class="opacity-75">AOV: ${{ number_format($revenueData['average_order_value'], 2) }}</small>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-dollar-sign fa-2x opacity-75"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <h6 class="card-title mb-1">Lost Revenue</h6>
                                                <h4 class="mb-0">${{ number_format($revenueData['lost_revenue'], 2) }}</h4>
                                                <small class="opacity-75">{{ $revenueData['failed_transactions'] }} failed payments</small>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-exclamation-triangle fa-2x opacity-75"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Charts Row -->
                        <div class="row mb-4">
                            <div class="col-lg-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">Conversion Funnel</h6>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="funnelChart" height="300"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">Form Type Breakdown</h6>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="formTypeChart" height="300"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Funnel Steps Table -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">Funnel Step Analysis</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Funnel Step</th>
                                                        <th>Users</th>
                                                        <th>Conversion Rate</th>
                                                        <th>Drop-off Rate</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $previousCount = 0;
                                                    @endphp
                                                    @foreach([
                                                        'form_view' => 'Form View',
                                                        'amount_entered' => 'Amount Entered',
                                                        'personal_info_completed' => 'Personal Info Completed',
                                                        'payment_initiated' => 'Payment Initiated',
                                                        'payment_completed' => 'Payment Completed'
                                                    ] as $step => $stepName)
                                                        @php
                                                            $currentCount = $conversionData[$step]['count'] ?? 0;
                                                            $conversionRate = $conversionData[$step]['conversion_rate'] ?? 0;
                                                            $dropoffRate = $previousCount > 0 ? round((($previousCount - $currentCount) / $previousCount) * 100, 2) : 0;
                                                            $previousCount = $currentCount;
                                                        @endphp
                                                        <tr>
                                                            <td>{{ $stepName }}</td>
                                                            <td>{{ number_format($currentCount) }}</td>
                                                            <td>
                                                                <span class="badge bg-primary">{{ $conversionRate }}%</span>
                                                            </td>
                                                            <td>
                                                                @if($dropoffRate > 0)
                                                                    <span class="badge bg-warning">{{ $dropoffRate }}%</span>
                                                                @else
                                                                    <span class="badge bg-success">0%</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if($conversionRate >= 80)
                                                                    <span class="badge bg-success">Excellent</span>
                                                                @elseif($conversionRate >= 60)
                                                                    <span class="badge bg-primary">Good</span>
                                                                @elseif($conversionRate >= 40)
                                                                    <span class="badge bg-warning">Needs Improvement</span>
                                                                @else
                                                                    <span class="badge bg-danger">Poor</span>
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
                        </div>

                        <!-- Exit Points Analysis -->
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">Top Exit Points</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="list-group list-group-flush">
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>View to Amount Entry</strong>
                                                    <br><small class="text-muted">Users who viewed form but didn't enter amount</small>
                                                </div>
                                                <span class="badge bg-danger rounded-pill">{{ $exitPoints['view_to_amount'] ?? 0 }}</span>
                                            </div>
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>Amount to Personal Info</strong>
                                                    <br><small class="text-muted">Users who entered amount but didn't complete info</small>
                                                </div>
                                                <span class="badge bg-warning rounded-pill">{{ $exitPoints['amount_to_info'] ?? 0 }}</span>
                                            </div>
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>Info to Payment</strong>
                                                    <br><small class="text-muted">Users who completed info but didn't initiate payment</small>
                                                </div>
                                                <span class="badge bg-warning rounded-pill">{{ $exitPoints['info_to_payment'] ?? 0 }}</span>
                                            </div>
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>Payment to Completion</strong>
                                                    <br><small class="text-muted">Users who initiated payment but failed to complete</small>
                                                </div>
                                                <span class="badge bg-danger rounded-pill">{{ $exitPoints['payment_to_complete'] ?? 0 }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">Optimization Recommendations</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-info">
                                            <h6 class="alert-heading">Top Priority Issues:</h6>
                                            <ul class="mb-0">
                                                @if(($exitPoints['view_to_amount'] ?? 0) > 10)
                                                    <li>High exit rate at amount entry - consider simplifying donation amounts or adding presets</li>
                                                @endif
                                                @if(($exitPoints['payment_to_complete'] ?? 0) > 5)
                                                    <li>Payment failures detected - check payment gateway configuration</li>
                                                @endif
                                                @if(($conversionData['personal_info_completed']['conversion_rate'] ?? 0) < 50)
                                                    <li>Low completion rate for personal info - consider reducing required fields</li>
                                                @endif
                                                @if(($revenueData['success_rate'] ?? 0) < 80)
                                                    <li>Payment success rate below 80% - investigate technical issues</li>
                                                @endif
                                            </ul>
                                        </div>
                                        
                                        @if(($conversionData['payment_completed']['conversion_rate'] ?? 0) > 10)
                                            <div class="alert alert-success">
                                                <strong>Good Performance:</strong> Your overall conversion rate of {{ $conversionData['payment_completed']['conversion_rate'] }}% is above average!
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Funnel Chart
const funnelCtx = document.getElementById('funnelChart').getContext('2d');
const funnelChart = new Chart(funnelCtx, {
    type: 'bar',
    data: {
        labels: ['Form View', 'Amount Entered', 'Info Completed', 'Payment Initiated', 'Payment Completed'],
        datasets: [{
            label: 'Users',
            data: [
                {{ $conversionData['form_view']['count'] ?? 0 }},
                {{ $conversionData['amount_entered']['count'] ?? 0 }},
                {{ $conversionData['personal_info_completed']['count'] ?? 0 }},
                {{ $conversionData['payment_initiated']['count'] ?? 0 }},
                {{ $conversionData['payment_completed']['count'] ?? 0 }}
            ],
            backgroundColor: [
                '#007bff',
                '#17a2b8',
                '#28a745',
                '#ffc107',
                '#dc3545'
            ],
            borderColor: [
                '#0056b3',
                '#117a8b',
                '#1e7e34',
                '#d39e00',
                '#bd2130'
            ],
            borderWidth: 1
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
                        const conversionRates = [
                            {{ $conversionData['form_view']['conversion_rate'] ?? 0 }},
                            {{ $conversionData['amount_entered']['conversion_rate'] ?? 0 }},
                            {{ $conversionData['personal_info_completed']['conversion_rate'] ?? 0 }},
                            {{ $conversionData['payment_initiated']['conversion_rate'] ?? 0 }},
                            {{ $conversionData['payment_completed']['conversion_rate'] ?? 0 }}
                        ];
                        return 'Conversion Rate: ' + conversionRates[context.dataIndex] + '%';
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Number of Users'
                }
            },
            x: {
                title: {
                    display: true,
                    text: 'Funnel Steps'
                }
            }
        }
    }
});

// Form Type Chart
const formTypeCtx = document.getElementById('formTypeChart').getContext('2d');
const formTypeChart = new Chart(formTypeCtx, {
    type: 'doughnut',
    data: {
        labels: [
            @foreach($formTypeData as $formType => $data)
                '{{ ucfirst($formType) }}',
            @endforeach
        ],
        datasets: [{
            data: [
                @foreach($formTypeData as $formType => $data)
                    {{ $data->unique_sessions }},
                @endforeach
            ],
            backgroundColor: [
                '#007bff',
                '#28a745',
                '#ffc107',
                '#dc3545',
                '#17a2b8'
            ]
        }]
    },
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

function refreshData() {
    location.reload();
}
</script>
@endsection