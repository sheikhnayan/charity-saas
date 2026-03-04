@extends('admin.main')

@section('title', 'Payment Method Analytics - ' . $website->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Payment Method Analytics</h1>
                <div class="btn-group">
                    <a href="{{ route('admin.payment-methods.analytics.export', array_merge(request()->all(), ['website_id' => $website->id])) }}" 
                       class="btn btn-outline-primary">
                        <i class="fas fa-download"></i> Export CSV
                    </a>
                </div>
            </div>

            <!-- Date Range Filter -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <input type="hidden" name="website_id" value="{{ $website->id }}">
                        
                        <div class="col-md-3">
                            <label for="date_from" class="form-label">From Date</label>
                            <input type="date" class="form-control" id="date_from" name="date_from" 
                                   value="{{ $dateFrom }}" required>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="date_to" class="form-label">To Date</label>
                            <input type="date" class="form-control" id="date_to" name="date_to" 
                                   value="{{ $dateTo }}" required>
                        </div>
                        
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-primary d-block">
                                <i class="fas fa-search"></i> Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Payment Method Overview Cards -->
            <div class="row mb-4">
                @foreach($conversionByMethod as $method => $data)
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-header bg-{{ $method === 'authorize_net' ? 'primary' : ($method === 'stripe' ? 'success' : 'warning') }} text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-{{ $method === 'authorize_net' ? 'credit-card' : ($method === 'stripe' ? 'stripe' : 'coins') }}"></i>
                                {{ ucfirst(str_replace('_', ' ', $method)) }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="h4 text-primary mb-0">{{ $data['initiated'] }}</div>
                                    <small class="text-muted">Initiated</small>
                                </div>
                                <div class="col-4">
                                    <div class="h4 text-success mb-0">{{ $data['completed'] }}</div>
                                    <small class="text-muted">Completed</small>
                                </div>
                                <div class="col-4">
                                    <div class="h4 text-danger mb-0">{{ $data['failed'] }}</div>
                                    <small class="text-muted">Failed</small>
                                </div>
                            </div>
                            <hr>
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="h5 text-success mb-0">{{ $data['success_rate'] }}%</div>
                                    <small class="text-muted">Success Rate</small>
                                </div>
                                <div class="col-6">
                                    <div class="h5 text-danger mb-0">{{ $data['failure_rate'] }}%</div>
                                    <small class="text-muted">Failure Rate</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Supported Payment Methods -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-cogs"></i> Supported Payment Methods for {{ $website->name }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($supportedMethods as $method)
                        <div class="col-md-4 mb-2">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span class="badge bg-light text-dark">
                                    {{ ucfirst(str_replace('_', ' ', $method)) }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Form Type Performance by Payment Method -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar"></i> Revenue by Form Type & Payment Method
                    </h5>
                </div>
                <div class="card-body">
                    @if($formTypeByMethod->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Form Type</th>
                                    <th>Payment Method</th>
                                    <th>Completions</th>
                                    <th>Average Amount</th>
                                    <th>Total Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($formTypeByMethod as $formType => $methods)
                                    @foreach($methods as $methodData)
                                    <tr>
                                        <td>
                                            <span class="badge bg-primary">{{ ucfirst($formType) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $methodData->payment_method === 'authorize_net' ? 'primary' : ($methodData->payment_method === 'stripe' ? 'success' : 'warning') }}">
                                                {{ ucfirst(str_replace('_', ' ', $methodData->payment_method)) }}
                                            </span>
                                        </td>
                                        <td>{{ $methodData->completions }}</td>
                                        <td>${{ number_format($methodData->avg_amount, 2) }}</td>
                                        <td class="fw-bold">${{ number_format($methodData->total_revenue, 2) }}</td>
                                    </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No payment completion data found for the selected date range.</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Payment Method Event Timeline -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-timeline"></i> Payment Method Performance Details
                    </h5>
                </div>
                <div class="card-body">
                    @if($paymentMethodStats->count() > 0)
                    <div class="accordion" id="paymentMethodAccordion">
                        @foreach($paymentMethodStats as $method => $steps)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading{{ ucfirst($method) }}">
                                <button class="accordion-button collapsed" type="button" 
                                        data-bs-toggle="collapse" data-bs-target="#collapse{{ ucfirst($method) }}" 
                                        aria-expanded="false" aria-controls="collapse{{ ucfirst($method) }}">
                                    <i class="fas fa-{{ $method === 'authorize_net' ? 'credit-card' : ($method === 'stripe' ? 'stripe' : 'coins') }} me-2"></i>
                                    {{ ucfirst(str_replace('_', ' ', $method)) }} 
                                    <span class="badge bg-secondary ms-2">{{ $steps->sum('event_count') }} events</span>
                                </button>
                            </h2>
                            <div id="collapse{{ ucfirst($method) }}" class="accordion-collapse collapse" 
                                 aria-labelledby="heading{{ ucfirst($method) }}" data-bs-parent="#paymentMethodAccordion">
                                <div class="accordion-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Funnel Step</th>
                                                    <th>Events</th>
                                                    <th>Unique Sessions</th>
                                                    <th>Avg Amount</th>
                                                    <th>Total Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($steps as $step)
                                                <tr>
                                                    <td>
                                                        <span class="badge bg-{{ $step->funnel_step === 'payment_completed' ? 'success' : ($step->funnel_step === 'payment_failed' ? 'danger' : 'info') }}">
                                                            {{ ucfirst(str_replace('_', ' ', $step->funnel_step)) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $step->event_count }}</td>
                                                    <td>{{ $step->unique_sessions }}</td>
                                                    <td>${{ number_format($step->avg_amount, 2) }}</td>
                                                    <td class="fw-bold">${{ number_format($step->total_amount, 2) }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No payment method data found for the selected date range.</p>
                        <p class="text-muted">
                            <small>Make sure payment funnel tracking is properly implemented and users are making payments.</small>
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Auto-refresh data every 5 minutes
setInterval(function() {
    if (document.hidden) return; // Don't refresh if tab is not active
    
    // You can add AJAX refresh logic here if needed
    console.log('Payment method analytics auto-refresh');
}, 300000); // 5 minutes

// Date range validation
document.getElementById('date_from').addEventListener('change', function() {
    const dateFrom = new Date(this.value);
    const dateTo = new Date(document.getElementById('date_to').value);
    
    if (dateFrom > dateTo) {
        document.getElementById('date_to').value = this.value;
    }
});

document.getElementById('date_to').addEventListener('change', function() {
    const dateFrom = new Date(document.getElementById('date_from').value);
    const dateTo = new Date(this.value);
    
    if (dateTo < dateFrom) {
        document.getElementById('date_from').value = this.value;
    }
});
</script>
@endsection