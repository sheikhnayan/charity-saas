@extends('admin.main')

@section('content')
<!-- Load Chart.js UMD version and other dependencies FIRST -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        background: #f8f9fa !important;
        min-height: 100vh;
    }
    
    .analytics-container {
        background: #f8f9fa;
        padding: 0;
        min-height: 100vh;
    }
    
    .dashboard-header {
        background: #ffffff;
        border-radius: 0;
        padding: 20px 24px;
        margin-bottom: 0;
        border-bottom: 1px solid #e5e7eb;
        box-shadow: none;
    }
    
    .dashboard-title {
        color: #111827;
        font-weight: 600;
        font-size: 1rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .dashboard-subtitle {
        color: #6b7280;
        font-size: 0.875rem;
        margin: 4px 0 0 0;
        font-weight: 400;
    }
    
    .filter-controls {
        background: #ffffff;
        border-radius: 0;
        padding: 16px 24px;
        border-bottom: 1px solid #e5e7eb;
        box-shadow: none;
    }
    
    .filter-controls select, .filter-controls input {
        background: #ffffff;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        padding: 8px 12px;
        font-weight: 400;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }
    
    .filter-controls select:focus, .filter-controls input:focus {
        background: white;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        border-color: #3b82f6;
        outline: none;
    }
    
    .filter-controls label {
        color: #374151 !important;
        font-weight: 500;
        font-size: 0.875rem;
        margin-bottom: 6px;
    }
    
    .btn-filter {
        background: #1f2937;
        border: none;
        border-radius: 6px;
        padding: 8px 16px;
        color: white;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        box-shadow: none;
    }
    
    .btn-filter:hover {
        background: #111827;
        transform: none;
        box-shadow: none;
        color: white;
    }
    
    .period-selector {
        display: inline-flex;
        gap: 0.5rem;
        padding: 0.25rem;
        background: #f3f4f6;
        border-radius: 6px;
    }
    
    .period-btn {
        padding: 0.375rem 0.75rem;
        border: none;
        background: transparent;
        border-radius: 4px;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s;
        color: #6b7280;
        font-weight: 500;
    }
    
    .period-btn.active {
        background: white;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        color: #111827;
    }
    
    .metric-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 20px;
        text-align: left;
        box-shadow: none;
        transition: all 0.2s ease;
        position: relative;
        overflow: visible;
        height: 100%;
    }
    
    .metric-card::before {
        content: '';
        display: none;
    }
    
    .metric-card:hover {
        transform: none;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    
    .metric-card .metric-value {
        animation: none;
    }
    
    @keyframes countUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .metric-card h6, .metric-card .metric-label {
        color: #6b7280;
        font-size: 0.875rem;
        font-weight: 500;
        margin-bottom: 8px;
        text-transform: none;
        letter-spacing: 0;
    }
    
    .metric-value {
        color: #111827;
        font-size: 1.875rem;
        font-weight: 600;
        margin: 8px 0;
        line-height: 1.2;
    }
    
    .metric-subtitle {
        color: #10b981;
        font-size: 0.875rem;
        font-weight: 500;
        margin-top: 8px;
    }
    
    .metric-icon {
        display: none;
    }
    
    .chart-card {
        background: #ffffff;
        border-radius: 8px;
        box-shadow: none;
        padding: 24px;
        margin-bottom: 24px;
        border: 1px solid #e5e7eb;
        transition: all 0.2s ease;
    }
    
    .chart-card:hover {
        transform: none;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    
    .chart-card:hover .chart-title i {
        transform: none;
        color: inherit;
    }
    
    .chart-title i {
        transition: none;
    }
    
    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 0;
        border-bottom: none;
    }
    
    .chart-title {
        font-size: 1rem;
        font-weight: 600;
        color: #111827;
        margin: 0;
    }
    
    .chart-title i {
        margin-right: 8px;
        color: #6b7280;
        font-size: 1rem;
    }
    
    .chart-controls select {
        background: #ffffff;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        padding: 6px 10px;
        font-weight: 400;
        font-size: 0.875rem;
        color: #374151;
        transition: all 0.2s ease;
    }
    
    .chart-controls select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        outline: none;
    }
    
    .chart-container {
        position: relative;
        height: 300px;
        margin: 20px 0;
    }
    
    .funnel-container {
        position: relative;
        height: 400px;
    }
    
    .geomap-container {
        height: 500px;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .funnel-step {
        background: #f3f4f6;
        color: #111827;
        padding: 16px;
        margin: 8px 0;
        border-radius: 6px;
        text-align: left;
        transition: all 0.2s ease;
        box-shadow: none;
        border: 1px solid #e5e7eb;
    }
    
    .funnel-step:hover {
        transform: none;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        background: #e5e7eb;
    }
    
    .funnel-step strong {
        display: block;
        font-size: 0.875rem;
        margin-bottom: 4px;
        font-weight: 600;
    }
    
    .product-item {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        padding: 16px;
        margin: 12px 0;
        transition: all 0.2s ease;
        box-shadow: none;
    }
    
    .product-item:hover {
        transform: none;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .progress-custom {
        height: 8px;
        border-radius: 4px;
        background: #e5e7eb;
        overflow: hidden;
        margin: 8px 0;
    }
    
    .progress-bar-custom {
        height: 100%;
        background: linear-gradient(90deg, #0ea5e9, #38bdf8);
        transition: width 0.8s ease;
        border-radius: 4px;
    }
    
    .badge-custom {
        background: #1f2937;
        color: white;
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.875rem;
    }
    
    .funnel-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 30px;
        align-items: start;
    }
    
    .product-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 30px;
        align-items: start;
    }
    
    @media (max-width: 768px) {
        .funnel-grid, .product-grid {
            grid-template-columns: 1fr;
        }
        
        .dashboard-title {
            font-size: 2rem;
        }
        
        .metric-value {
            font-size: 2.2rem;
        }
        
        .chart-header {
            flex-direction: column;
            gap: 15px;
            align-items: flex-start;
        }
    }
    
    .loading-spinner {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid rgba(255,255,255,.3);
        border-radius: 50%;
        border-top-color: white;
        animation: spin 1s ease-in-out infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #6c757d;
    }
    
    .empty-state i {
        font-size: 4rem;
        margin-bottom: 20px;
        opacity: 0.5;
    }
    
    .skeleton {
        background: linear-gradient(-90deg, #f0f0f0 0%, #e0e0e0 50%, #f0f0f0 100%);
        background-size: 400% 400%;
        animation: skeleton-loading 1.6s ease-in-out infinite;
        border-radius: 10px;
        height: 20px;
        margin: 10px 0;
    }
    
    @keyframes skeleton-loading {
        0% { background-position: 0% 0%; }
        100% { background-position: -135% 0%; }
    }
    
    .fade-in {
        animation: fadeIn 0.8s ease-in-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
<div class="analytics-container">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="dashboard-title">
                    <i class="fas fa-chart-line"></i>
                    @if($selectedWebsite && $selectedWebsite->isInvestment())
                        Investment Analytics Dashboard
                    @elseif($selectedWebsite && $selectedWebsite->isFundraiser())
                        Fundraising Analytics Dashboard
                    @else
                        Analytics Dashboard
                    @endif
                </h1>
                <p class="dashboard-subtitle">
                    @if($selectedWebsite && $selectedWebsite->isInvestment())
                        Real-time insights into your investment platform's performance
                    @elseif($selectedWebsite && $selectedWebsite->isFundraiser())
                        Real-time insights into your fundraising campaign's performance
                    @else
                        Real-time insights into your charity's performance
                    @endif
                </p>
            </div>
            <div class="col-md-4">
                <div class="text-end">
                    <span class="badge badge-custom">
                        <i class="fas fa-clock"></i>
                        Last updated: <span id="last-updated">{{ now()->format('M j, Y g:i A') }}</span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Controls -->
    <div class="filter-controls mb-4">
        <form id="websiteForm" action="{{ route('analytics.dashboard') }}" method="GET">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label class="form-label text-white fw-semibold">
                        <i class="fas fa-globe me-2"></i>Website
                    </label>
                    <select name="website_id" class="form-select" onchange="this.form.submit()">
                        @foreach($websites as $website)
                            <option value="{{ $website->id }}" {{ $selectedWebsiteId == $website->id ? 'selected' : '' }}>
                                {{ $website->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-white fw-semibold">
                        <i class="fas fa-play me-2"></i>Start Date
                    </label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate->format('Y-m-d') }}" max="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label text-white fw-semibold">
                        <i class="fas fa-stop me-2"></i>End Date
                    </label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate->format('Y-m-d') }}" max="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-filter w-100">
                        <i class="fas fa-filter me-2"></i>Apply Filter
                        <span class="loading-spinner d-none ms-2"></span>
                    </button>
                </div>
                <div class="col-md-1">
                    <label class="form-label text-white fw-semibold" style="visibility: hidden;">Export</label>
                    <div class="d-flex gap-1">
                        <a href="{{ route('analytics.dashboard.export', array_merge(request()->all(), ['format' => 'csv'])) }}" 
                           class="btn btn-success flex-fill" 
                           style="font-size: 11px; padding: 8px 4px;"
                           title="Export as CSV">
                            <i class="bi bi-filetype-csv"></i> CSV
                        </a>
                        <a href="{{ route('analytics.dashboard.export', array_merge(request()->all(), ['format' => 'excel'])) }}" 
                           class="btn btn-primary flex-fill" 
                           style="font-size: 11px; padding: 8px 4px;"
                           title="Export as Excel">
                            <i class="bi bi-file-earmark-excel"></i> XLS
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Overview Stats -->
    <div class="row g-3 mb-4">
        <!-- Gross Sales -->
        <div class="col-xl-3 col-md-6">
            <div class="metric-card">
                <h6 class="metric-label">Gross sales</h6>
                <div class="metric-value">{{ $stats['today']['grossSalesFormatted'] ?? '$0.00' }}</div>
            </div>
        </div>
        
        <!-- Returning Customer Rate -->
        <div class="col-xl-3 col-md-6">
            <div class="metric-card">
                <h6 class="metric-label">Returning customer rate</h6>
                <div class="metric-value">{{ $stats['today']['returningCustomerRateFormatted'] ?? '0%' }}</div>
            </div>
        </div>
        
        <!-- Orders Fulfilled -->
        <div class="col-xl-3 col-md-6">
            <div class="metric-card">
                <h6 class="metric-label">Orders fulfilled</h6>
                <div class="metric-value">{{ number_format($stats['today']['ordersFulfilled'] ?? 0) }}</div>
            </div>
        </div>
        
        <!-- Orders -->
        <div class="col-xl-3 col-md-6">
            <div class="metric-card">
                <h6 class="metric-label">Orders</h6>
                <div class="metric-value">{{ number_format($stats['today']['orders'] ?? 0) }}</div>
            </div>
        </div>
    </div>

    <!-- GROSS SALES BREAKDOWN SECTION -->
    <div class="row g-3 mb-4">
        <!-- Sales by Payment Method -->
        <div class="col-xl-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-credit-card me-2 text-primary"></i>Sales by Payment Method</h5>
                </div>
                <div class="card-body">
                    @if(!empty($stats['salesByPaymentMethod']))
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Method</th>
                                        <th class="text-center">Transactions</th>
                                        <th class="text-end">Total Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $totalByMethod = 0; @endphp
                                    @foreach($stats['salesByPaymentMethod'] as $method)
                                        @php $totalByMethod += $method->total; @endphp
                                        <tr>
                                            <td><span class="badge bg-info">{{ ucfirst($method->payment_method ?? 'Unknown') }}</span></td>
                                            <td class="text-center">{{ number_format($method->count ?? 0) }}</td>
                                            <td class="text-end fw-bold text-success">${{ number_format($method->total ?? 0, 2) }}</td>
                                        </tr>
                                    @endforeach
                                    <tr class="table-light fw-bold">
                                        <td colspan="2">Total</td>
                                        <td class="text-end text-success">${{ number_format($totalByMethod, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-3">No payment method data available</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sales by Donation Type -->
        <div class="col-xl-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-list me-2 text-success"></i>Sales by Donation Type</h5>
                </div>
                <div class="card-body">
                    @if(!empty($stats['salesByType']))
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Type</th>
                                        <th class="text-center">Transactions</th>
                                        <th class="text-end">Total Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $totalByType = 0; @endphp
                                    @foreach($stats['salesByType'] as $type)
                                        @php $totalByType += $type->total; @endphp
                                        <tr>
                                            <td><span class="badge bg-warning">{{ ucfirst($type->type ?? 'Unknown') }}</span></td>
                                            <td class="text-center">{{ number_format($type->count ?? 0) }}</td>
                                            <td class="text-end fw-bold text-success">${{ number_format($type->total ?? 0, 2) }}</td>
                                        </tr>
                                    @endforeach
                                    <tr class="table-light fw-bold">
                                        <td colspan="2">Total</td>
                                        <td class="text-end text-success">${{ number_format($totalByType, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-3">No donation type data available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- DETAILED TRANSACTION REPORT -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-receipt me-2 text-info"></i>Detailed Transaction Report</h5>
                    <small class="text-muted">All transactions with dates (newest first)</small>
                </div>
                <div class="card-body">
                    @if(!empty($stats['detailedTransactions']))
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date & Time</th>
                                        <th>Donor</th>
                                        <th>Email</th>
                                        <th>Type</th>
                                        <th>Payment Method</th>
                                        <th class="text-center">Location</th>
                                        <th class="text-end">Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stats['detailedTransactions']->take(50) as $txn)
                                        <tr>
                                            <td><small class="text-muted">{{ $txn->created_at->format('M d, Y H:i') }}</small></td>
                                            <td>{{ $txn->name ?? '-' }}</td>
                                            <td><a href="mailto:{{ $txn->email }}" class="text-decoration-none">{{ $txn->email ?? '-' }}</a></td>
                                            <td><span class="badge bg-secondary">{{ ucfirst($txn->type ?? '-') }}</span></td>
                                            <td><span class="badge bg-info">{{ ucfirst($txn->payment_method ?? '-') }}</span></td>
                                            <td class="text-center"><small>{{ $txn->city }}{{ $txn->city && $txn->state ? ',' : '' }} {{ $txn->state }}</small></td>
                                            <td class="text-end fw-bold text-success">${{ number_format($txn->amount ?? 0, 2) }}</td>
                                            <td><span class="badge {{ ($txn->status ?? '') === 'completed' ? 'bg-success' : 'bg-warning' }}">{{ ucfirst($txn->status ?? '-') }}</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($stats['detailedTransactions']->count() > 50)
                            <div class="alert alert-info m-0 mt-2"><small><i class="fas fa-info-circle me-2"></i>Showing first 50. Download CSV for complete report.</small></div>
                        @endif
                    @else
                        <p class="text-muted text-center py-3">No transactions found</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- PAGES & REFERRERS SECTION -->
    <div class="row g-3 mb-4">
        <!-- Top Pages Viewed -->
        <div class="col-xl-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-file-alt me-2 text-warning"></i>Top Pages Viewed</h5>
                </div>
                <div class="card-body">
                    @if(!empty($stats['pageViewDetails']))
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Page URL</th>
                                        <th class="text-end">Views</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stats['pageViewDetails'] as $page)
                                        <tr>
                                            <td><small>{{ Illuminate\Support\Str::limit($page->url ?? 'Unknown', 40, '...') }}</small></td>
                                            <td class="text-end"><strong>{{ number_format($page->views ?? 0) }}</strong></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-3">No page data available</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Top Referrers / Traffic Sources -->
        <div class="col-xl-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-link me-2 text-danger"></i>Traffic Sources (Referrers)</h5>
                </div>
                <div class="card-body">
                    @if(!empty($stats['referrerDetails']))
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Referrer URL</th>
                                        <th class="text-end">Visitors</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stats['referrerDetails'] as $referrer)
                                        <tr>
                                            <td><small>{{ $referrer->referrer_url ? Illuminate\Support\Str::limit($referrer->referrer_url, 40, '...') : 'Direct' }}</small></td>
                                            <td class="text-end"><strong>{{ number_format($referrer->count ?? 0) }}</strong></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-3">No referrer data available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Real-time Activity Section -->
    <div class="chart-card">
        <div class="chart-header">
            <h4 class="chart-title">
                <i class="fas fa-broadcast-tower"></i>
                Real-time Activity
            </h4>
            <div class="chart-controls">
                <span class="badge badge-custom" id="active-users">
                    <i class="fas fa-users me-1"></i>
                    <span id="active-count">-</span> Active Users
                </span>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table" id="realtime-activity">
                <thead>
                    <tr>
                        <th><i class="fas fa-clock me-1"></i>Time</th>
                        <th><i class="fas fa-file-alt me-1"></i>Page</th>
                        <th><i class="fas fa-user me-1"></i>User</th>
                        <th><i class="fas fa-mouse-pointer me-1"></i>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="4" class="text-center text-muted">
                            <i class="fas fa-spinner fa-spin me-2"></i>
                            Loading real-time data...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Time-Based Charts Row -->
    <div class="row mb-4">
        <div class="col-xl-6">
            <div class="chart-card">
                <div class="chart-header">
                    <h4 class="chart-title">
                        <i class="fas fa-chart-line"></i>
                        @if($selectedWebsite && $selectedWebsite->isInvestment())
                            Investments Over Time
                        @elseif($selectedWebsite && $selectedWebsite->isFundraiser())
                            Donations Over Time
                        @else
                            Conversions Over Time
                        @endif
                    </h4>
                    <div class="chart-controls">
                        <select id="conversions-timeframe" class="form-select">
                            <option value="day">Daily View</option>
                            <option value="week">Weekly View</option>
                            <option value="month">Monthly View</option>
                        </select>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="conversionsChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="chart-card">
                <div class="chart-header">
                    <h4 class="chart-title">
                        <i class="fas fa-users"></i>
                        Sessions & Page Views
                    </h4>
                    <div class="chart-controls">
                        <select id="sessions-timeframe" class="form-select">
                            <option value="day">Daily View</option>
                            <option value="week">Weekly View</option>
                            <option value="month">Monthly View</option>
                        </select>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="sessionsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Conversion Funnel -->
    <div class="chart-card">
        <div class="chart-header">
            <h4 class="chart-title">
                <i class="fas fa-funnel-dollar"></i>
                @if($selectedWebsite && $selectedWebsite->isInvestment())
                    Investment Funnel Analysis
                @elseif($selectedWebsite && $selectedWebsite->isFundraiser())
                    Donation Funnel Analysis
                @else
                    Conversion Funnel Analysis
                @endif
            </h4>
            <div class="chart-controls">
                <span class="badge badge-custom">
                    <i class="fas fa-chart-pie me-1"></i>
                    Detailed Breakdown
                </span>
            </div>
        </div>
        <div class="funnel-grid">
            <div class="funnel-container">
                <canvas id="funnelChart"></canvas>
            </div>
            <div id="funnel-breakdown">
                <!-- Funnel steps will be populated by JavaScript -->
            </div>
        </div>
    </div>

    <!-- Device & Location Analytics -->
    <div class="row mb-4">
        <div class="col-xl-6">
            <div class="chart-card">
                <div class="chart-header">
                    <h4 class="chart-title">
                        <i class="fas fa-mobile-alt"></i>
                        Device Performance
                    </h4>
                    <div class="chart-controls">
                        <span class="badge badge-custom">
                            <i class="fas fa-chart-pie me-1"></i>
                            Usage Stats
                        </span>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="deviceChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="chart-card">
                <div class="chart-header">
                    <h4 class="chart-title">
                        <i class="fas fa-globe-americas"></i>
                        Location Performance
                    </h4>
                    <div class="chart-controls">
                        <span class="badge badge-custom">
                            <i class="fas fa-map-marker-alt me-1"></i>
                            Geographic Data
                        </span>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="locationChart"></canvas>
                </div>
                <div class="mt-3">
                    <h6 class="mb-2"><i class="fas fa-list"></i> Top Locations</h6>
                    <div id="location-breakdown-table" style="max-height: 300px; overflow-y: auto;">
                        <table class="table table-sm table-hover">
                            <thead style="position: sticky; top: 0; background: white; z-index: 1;">
                                <tr>
                                    <th>Location</th>
                                    <th class="text-center">Visitors</th>
                                    <th class="text-center">Conversions</th>
                                    <th class="text-center">Rate</th>
                                    <th class="text-end">Revenue</th>
                                </tr>
                            </thead>
                            <tbody id="location-breakdown-body">
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Interactive Geomap -->
    <div class="chart-card">
        <div class="chart-header">
            <h4 class="chart-title">
                <i class="fas fa-map"></i>
                Interactive Visitor Map
            </h4>
            <div class="chart-controls">
                <span class="badge badge-custom">
                    <i class="fas fa-eye me-1"></i>
                    Real-time Locations
                </span>
            </div>
        </div>
        <div id="geomap" class="geomap-container"></div>
    </div>

    <!-- Product Sell-Through Rates -->
    <div class="chart-card">
        <div class="chart-header">
            <h4 class="chart-title">
                <i class="fas fa-shopping-cart"></i>
                Top Selling Items
            </h4>
            <div class="chart-controls">
                <span class="badge badge-custom">
                    <i class="fas fa-chart-bar me-1"></i>
                    Performance Metrics
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="chart-container" style="height: 350px;">
                    <canvas id="productChart"></canvas>
                </div>
            </div>
        </div>
        <div class="mt-4">
            <h6 class="mb-3"><i class="fas fa-table me-1"></i> All Items Performance</h6>
            <div style="max-height: 400px; overflow-y: auto;">
                <table class="table table-hover" id="product-table">
                    <thead style="position: sticky; top: 0; background: white; z-index: 1;">
                        <tr>
                            <th>Item Name</th>
                            <th class="text-center">Items Sold</th>
                            <th class="text-center">Items Remaining</th>
                            <th class="text-end">Gross Sales</th>
                            <th class="text-center">Sell-Through Rate</th>
                        </tr>
                    </thead>
                    <tbody id="product-table-body">
                        <tr>
                            <td colspan="5" class="text-center text-muted">Loading...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
{{-- @endsection --}}
        </div>
    </div>
</div>

<script>
// Global variables
let currentWebsiteId = {{ $selectedWebsiteId }};
let currentStartDate = '{{ $startDate->format('Y-m-d') }}';
let currentEndDate = '{{ $endDate->format('Y-m-d') }}';

// Chart instances
let conversionsChart, sessionsChart, funnelChart, deviceChart, locationChart, productChart;
let geomap;

// Check if Chart.js is loaded and wait for it
function waitForChartJS(callback, attempts = 0) {
    if (typeof Chart !== 'undefined') {
        console.log('Chart.js loaded successfully');
        callback();
    } else if (attempts < 50) { // Wait up to 5 seconds
        console.log('Waiting for Chart.js to load... attempt', attempts + 1);
        setTimeout(() => waitForChartJS(callback, attempts + 1), 100);
    } else {
        console.error('Chart.js failed to load after 5 seconds');
        showToast('Failed to load Chart.js. Please refresh the page.', 'error');
    }
}

// Initialize all charts and components
document.addEventListener('DOMContentLoaded', function() {
    // Update last updated time
    updateLastUpdatedTime();
    
    // Wait for Chart.js to be available before initializing charts
    waitForChartJS(function() {
        initializeCharts();
        initializeGeomap();
        loadAllData();
        
        // Add event listeners for timeframe changes
        document.getElementById('conversions-timeframe').addEventListener('change', loadConversionsData);
        document.getElementById('sessions-timeframe').addEventListener('change', loadSessionsData);
    });
    
    // Add loading states to buttons
    addLoadingStates();
});

function updateLastUpdatedTime() {
    const now = new Date();
    const timeString = now.toLocaleString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
        hour: 'numeric',
        minute: '2-digit',
        hour12: true
    });
    const lastUpdatedEl = document.getElementById('last-updated');
    if (lastUpdatedEl) {
        lastUpdatedEl.textContent = timeString;
    }
}

function addLoadingStates() {
    const filterBtn = document.querySelector('.btn-filter');
    if (filterBtn) {
        filterBtn.addEventListener('click', function() {
            const spinner = this.querySelector('.loading-spinner');
            if (spinner) {
                spinner.classList.remove('d-none');
            }
        });
    }
}

function showToast(message, type = 'info') {
    // Create a simple toast notification
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'error' ? 'danger' : 'info'} position-fixed fade-in`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `
        <i class="fas fa-${type === 'error' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
        ${message}
    `;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => toast.remove(), 300);
    }, 5000);
}

function showLoadingSkeleton(containerId) {
    const container = document.getElementById(containerId);
    if (container) {
        container.innerHTML = `
            <div class="skeleton" style="height: 30px; width: 80%; margin-bottom: 15px;"></div>
            <div class="skeleton" style="height: 20px; width: 60%; margin-bottom: 10px;"></div>
            <div class="skeleton" style="height: 20px; width: 70%; margin-bottom: 10px;"></div>
            <div class="skeleton" style="height: 15px; width: 50%;"></div>
        `;
    }
}

function initializeCharts() {
    // Conversions Chart
    const conversionsCtx = document.getElementById('conversionsChart').getContext('2d');
    conversionsChart = new Chart(conversionsCtx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Conversions',
                data: [],
                borderColor: '#4CAF50',
                backgroundColor: 'rgba(76, 175, 80, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Revenue ($)',
                data: [],
                borderColor: '#FF9800',
                backgroundColor: 'rgba(255, 152, 0, 0.1)',
                tension: 0.4,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Conversions' }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: { display: true, text: 'Revenue ($)' },
                    grid: { drawOnChartArea: false }
                }
            }
        }
    });

    // Sessions Chart
    const sessionsCtx = document.getElementById('sessionsChart').getContext('2d');
    sessionsChart = new Chart(sessionsCtx, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Sessions',
                data: [],
                backgroundColor: '#2196F3',
                borderRadius: 6
            }, {
                label: 'Page Views',
                data: [],
                backgroundColor: '#03DAC6',
                borderRadius: 6
            }, {
                label: 'Unique Visitors',
                data: [],
                backgroundColor: '#FF5722',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Funnel Chart
    const funnelCtx = document.getElementById('funnelChart').getContext('2d');
    funnelChart = new Chart(funnelCtx, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Count',
                data: [],
                backgroundColor: [
                    '#4CAF50', '#8BC34A', '#CDDC39', 
                    '#FFEB3B', '#FFC107', '#FF9800', '#FF5722'
                ],
                borderRadius: 6
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: { beginAtZero: true }
            }
        }
    });

    // Device Chart
    const deviceCtx = document.getElementById('deviceChart').getContext('2d');
    deviceChart = new Chart(deviceCtx, {
        type: 'doughnut',
        data: {
            labels: [],
            datasets: [{
                data: [],
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Location Chart
    const locationCtx = document.getElementById('locationChart').getContext('2d');
    locationChart = new Chart(locationCtx, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Visitors',
                data: [],
                backgroundColor: '#673AB7',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Product Chart
    const productCtx = document.getElementById('productChart').getContext('2d');
    productChart = new Chart(productCtx, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Gross Sales ($)',
                data: [],
                backgroundColor: '#0ea5e9',
                borderRadius: 6
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        title: function(context) {
                            // Show full name in tooltip
                            return context[0].label;
                        }
                    }
                }
            },
            scales: {
                x: { 
                    beginAtZero: true,
                    title: { display: true, text: 'Gross Sales ($)' }
                },
                y: {
                    ticks: {
                        font: {
                            size: 11
                        }
                    }
                }
            }
        }
    });
}

function initializeGeomap() {
    try {
        // Initialize map with world view
        geomap = L.map('geomap', {
            center: [20, 0],
            zoom: 2,
            minZoom: 2,
            maxZoom: 18
        });
        
        // Add OpenStreetMap tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(geomap);
        
        console.log('Geomap initialized successfully');
    } catch (error) {
        console.error('Error initializing geomap:', error);
    }
}

function loadAllData() {
    loadConversionsData();
    loadSessionsData();
    loadFunnelData();
    loadDeviceData();
    loadLocationData();
    loadProductData();
    loadGeoMapData();
    loadRealTimeData();
    
    // Set up real-time updates every 30 seconds
    setInterval(loadRealTimeData, 30000);
}

function loadConversionsData() {
    if (!conversionsChart) {
        console.error('Conversions chart not initialized');
        return;
    }
    
    const timeframeElement = document.getElementById('conversions-timeframe');
    const timeframe = timeframeElement ? timeframeElement.value : 'day';
    
    fetch(`/analytics/api/conversions?website_id=${currentWebsiteId}&start_date=${currentStartDate}&end_date=${currentEndDate}&group_by=${timeframe}`)
        .then(response => response.json())
        .then(data => {
            conversionsChart.data.labels = data.map(item => item.period);
            conversionsChart.data.datasets[0].data = data.map(item => item.conversions);
            conversionsChart.data.datasets[1].data = data.map(item => item.revenue);
            conversionsChart.update();
            
            // Update overview stats
            const totalConversions = data.reduce((sum, item) => sum + item.conversions, 0);
            const totalRevenue = data.reduce((sum, item) => sum + item.revenue, 0);
            const totalConversionsEl = document.getElementById('total-conversions');
            const totalRevenueEl = document.getElementById('total-revenue');
            const avgOrderValueEl = document.getElementById('avg-order-value');
            
            if (totalConversionsEl) totalConversionsEl.textContent = totalConversions.toLocaleString();
            if (totalRevenueEl) totalRevenueEl.textContent = totalRevenue.toLocaleString();
            if (avgOrderValueEl) avgOrderValueEl.textContent = totalConversions > 0 ? '$' + (totalRevenue / totalConversions).toFixed(2) : '$0';
        })
        .catch(error => console.error('Error loading conversions data:', error));
}

function loadSessionsData() {
    if (!sessionsChart) {
        console.error('Sessions chart not initialized');
        return;
    }
    
    const timeframeElement = document.getElementById('sessions-timeframe');
    const timeframe = timeframeElement ? timeframeElement.value : 'day';
    
    fetch(`/analytics/api/sessions?website_id=${currentWebsiteId}&start_date=${currentStartDate}&end_date=${currentEndDate}&group_by=${timeframe}`)
        .then(response => response.json())
        .then(data => {
            sessionsChart.data.labels = data.map(item => item.period);
            sessionsChart.data.datasets[0].data = data.map(item => item.sessions);
            sessionsChart.data.datasets[1].data = data.map(item => item.page_views);
            sessionsChart.data.datasets[2].data = data.map(item => item.unique_visitors);
            sessionsChart.update();
            
            // Update sessions total
            const totalSessions = data.reduce((sum, item) => sum + item.sessions, 0);
            const totalSessionsEl = document.getElementById('total-sessions');
            if (totalSessionsEl) totalSessionsEl.textContent = totalSessions.toLocaleString();
        })
        .catch(error => console.error('Error loading sessions data:', error));
}

function loadFunnelData() {
    if (!funnelChart) {
        console.error('Funnel chart not initialized');
        return;
    }
    
    fetch(`/analytics/api/funnel?website_id=${currentWebsiteId}&start_date=${currentStartDate}&end_date=${currentEndDate}`)
        .then(response => response.json())
        .then(data => {
            funnelChart.data.labels = data.map(item => item.step);
            funnelChart.data.datasets[0].data = data.map(item => item.count);
            funnelChart.update();
            
            // Update funnel breakdown if element exists
            const funnelBreakdownEl = document.getElementById('funnel-breakdown');
            if (funnelBreakdownEl) {
                const breakdownHtml = data.map(item => `
                    <div class="funnel-step">
                        <strong>${item.step}</strong><br>
                        <span>${item.count.toLocaleString()} (${item.conversion_rate}%)</span><br>
                        <small>Dropoff: ${item.dropoff_rate}%</small>
                    </div>
                `).join('');
                funnelBreakdownEl.innerHTML = breakdownHtml;
            }
            
            // Update conversion rate if element exists
            const conversionRateEl = document.getElementById('conversion-rate');
            if (conversionRateEl) {
                const sessions = data[0]?.count || 0;
                const conversions = data[data.length - 1]?.count || 0;
                const conversionRate = sessions > 0 ? ((conversions / sessions) * 100).toFixed(2) : '0';
                conversionRateEl.textContent = conversionRate + '%';
            }
        })
        .catch(error => console.error('Error loading funnel data:', error));
}

function loadDeviceData() {
    if (!deviceChart) {
        console.error('Device chart not initialized');
        return;
    }
    
    fetch(`/analytics/api/devices?website_id=${currentWebsiteId}&start_date=${currentStartDate}&end_date=${currentEndDate}`)
        .then(response => response.json())
        .then(data => {
            deviceChart.data.labels = data.map(item => item.device_type || 'Unknown');
            deviceChart.data.datasets[0].data = data.map(item => item.visitors);
            deviceChart.update();
        })
        .catch(error => console.error('Error loading device data:', error));
}

function loadLocationData() {
    if (!locationChart) {
        console.error('Location chart not initialized');
        return;
    }
    
    console.log('Loading location data:', { website: currentWebsiteId, start: currentStartDate, end: currentEndDate });
    
    fetch(`/analytics/api/locations?website_id=${currentWebsiteId}&start_date=${currentStartDate}&end_date=${currentEndDate}`)
        .then(response => {
            console.log('Location API response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Location data received:', data);
            console.log('Number of locations:', data.length);
            const topLocations = data.slice(0, 10); // Show top 10 for chart
            locationChart.data.labels = topLocations.map(item => item.country_name || item.country);
            locationChart.data.datasets[0].data = topLocations.map(item => item.visitors);
            locationChart.update();
            
            // Populate the location breakdown table
            const tableBody = document.getElementById('location-breakdown-body');
            if (data.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">No location data available</td></tr>';
            } else {
                tableBody.innerHTML = data.slice(0, 20).map(location => `
                    <tr>
                        <td>
                            <i class="fas fa-map-marker-alt text-primary me-1"></i>
                            <strong>${location.country_name || location.country}</strong>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-info">${location.visitors.toLocaleString()}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-success">${location.conversions.toLocaleString()}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge ${location.conversion_rate >= 5 ? 'bg-success' : location.conversion_rate >= 2 ? 'bg-warning' : 'bg-secondary'}">${location.conversion_rate}%</span>
                        </td>
                        <td class="text-end">
                            <strong>$${location.revenue.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</strong>
                        </td>
                    </tr>
                `).join('');
            }
        })
        .catch(error => console.error('Error loading location data:', error));
}

function loadProductData() {
    if (!productChart) {
        console.error('Product chart not initialized');
        return;
    }
    
    // Helper function to truncate text
    function truncateText(text, maxLength = 20) {
        if (text.length <= maxLength) return text;
        return text.substring(0, maxLength) + '...';
    }
    
    fetch(`/analytics/api/products?website_id=${currentWebsiteId}&start_date=${currentStartDate}&end_date=${currentEndDate}`)
        .then(response => response.json())
        .then(data => {
            // Show top 5 items in chart with truncated names
            const topItems = data.slice(0, 5);
            // Store full names for tooltip
            productChart.data.labels = topItems.map(item => truncateText(item.name, 20));
            // Store full names in dataset for tooltip access
            productChart.fullNames = topItems.map(item => item.name);
            productChart.data.datasets[0].data = topItems.map(item => item.revenue);
            
            // Update tooltip to use full names
            productChart.options.plugins.tooltip.callbacks.title = function(context) {
                const index = context[0].dataIndex;
                return productChart.fullNames[index];
            };
            
            productChart.update();
            
            // Populate the table with all items
            const tableBody = document.getElementById('product-table-body');
            if (data.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">No product data available</td></tr>';
            } else {
                tableBody.innerHTML = data.map((item, index) => {
                    const remaining = item.available - item.sold;
                    const sellThroughClass = item.sell_through_rate >= 75 ? 'bg-success' : 
                                            item.sell_through_rate >= 50 ? 'bg-warning' : 'bg-secondary';
                    const truncatedName = truncateText(item.name, 25);
                    return `
                        <tr>
                            <td title="${item.name}" style="cursor: help;">
                                <strong>${truncatedName}</strong>
                                ${index < 5 ? '<span class="badge bg-primary ms-2" style="font-size: 0.7rem;">Top 5</span>' : ''}
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success">${item.sold.toLocaleString()}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info">${remaining.toLocaleString()}</span>
                            </td>
                            <td class="text-end">
                                <strong>$${item.revenue.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</strong>
                            </td>
                            <td class="text-center">
                                <span class="badge ${sellThroughClass}">${item.sell_through_rate}%</span>
                            </td>
                        </tr>
                    `;
                }).join('');
            }
        })
        .catch(error => console.error('Error loading product data:', error));
}

function loadGeoMapData() {
    if (!geomap) {
        console.error('Geomap not initialized');
        return;
    }
    
    fetch(`/analytics/api/geomap?website_id=${currentWebsiteId}&start_date=${currentStartDate}&end_date=${currentEndDate}`)
        .then(response => response.json())
        .then(data => {
            console.log('Geomap data loaded:', data);
            
            // Clear existing markers
            geomap.eachLayer(layer => {
                if (layer instanceof L.Marker) {
                    geomap.removeLayer(layer);
                }
            });
            
            if (data.length === 0) {
                console.warn('No location data available for map');
                return;
            }
            
            // Add markers for each location
            data.forEach((location, index) => {
                console.log(`Adding marker ${index + 1}:`, location);
                
                // Create marker at location coordinates
                const marker = L.marker([location.lat, location.lng])
                    .bindPopup(`
                        <div style="min-width: 200px;">
                            <h6 style="margin-bottom: 10px; color: #333;"><i class="fas fa-map-marker-alt"></i> ${location.country_name}</h6>
                            <p style="margin: 5px 0;"><strong>Visitors:</strong> ${location.visitors.toLocaleString()}</p>
                            <p style="margin: 5px 0;"><strong>Sessions:</strong> ${location.sessions.toLocaleString()}</p>
                            <p style="margin: 5px 0;"><strong>Conversions:</strong> ${location.conversions.toLocaleString()}</p>
                            <p style="margin: 5px 0;"><strong>Revenue:</strong> $${location.revenue.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</p>
                            <p style="margin: 5px 0;"><strong>Conversion Rate:</strong> <span style="color: ${location.conversion_rate >= 5 ? 'green' : location.conversion_rate >= 2 ? 'orange' : 'gray'}">${location.conversion_rate}%</span></p>
                        </div>
                    `)
                    .addTo(geomap);
                
                // Adjust marker size based on visitor count
                const baseSize = 20;
                const size = Math.max(baseSize, Math.min(60, baseSize + (location.visitors / 50)));
                const color = location.conversion_rate >= 5 ? '#4CAF50' : location.conversion_rate >= 2 ? '#FF9800' : '#9E9E9E';
                
                marker.setIcon(L.divIcon({
                    className: 'custom-map-marker',
                    html: `<div style="
                        background-color: ${color}; 
                        width: ${size}px; 
                        height: ${size}px; 
                        border-radius: 50%; 
                        border: 3px solid white; 
                        box-shadow: 0 2px 8px rgba(0,0,0,0.3);
                        display: flex; 
                        align-items: center; 
                        justify-content: center; 
                        color: white; 
                        font-size: ${Math.max(10, size/4)}px; 
                        font-weight: bold;
                        cursor: pointer;
                    ">${location.visitors}</div>`,
                    iconSize: [size, size],
                    iconAnchor: [size/2, size/2]
                }));
            });
            
            console.log(`Added ${data.length} markers to map`);
        })
        .catch(error => console.error('Error loading geomap data:', error));
}

// Real-time data loading function
function loadRealTimeData() {
    console.log('Loading real-time data for website:', currentWebsiteId);
    
    fetch(`/analytics/real-time?website_id=${currentWebsiteId}`)
        .then(response => {
            console.log('Real-time API response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Real-time data received:', data);
            console.log('Active users:', data.activeUsers);
            console.log('Recent page views count:', data.recentPageViews ? data.recentPageViews.length : 0);
            
            // Update active users count
            document.getElementById('active-count').textContent = data.activeUsers || 0;
            
            // Update real-time activity table
            const tbody = document.querySelector('#realtime-activity tbody');
            if (data.recentPageViews && data.recentPageViews.length > 0) {
                console.log('Displaying', data.recentPageViews.length, 'activities');
                tbody.innerHTML = '';
                
                data.recentPageViews.slice(0, 10).forEach(activity => {
                    const timeAgo = formatTimeAgo(new Date(activity.created_at));
                    const location = activity.country ? ` from ${activity.state ? activity.state + ', ' : ''}${activity.country}` : '';
                    const user = activity.user_id ? `User ${activity.user_id}` : `Visitor ${activity.session_id.substring(0, 8)}`;
                    
                    // Determine action type and badge color
                    let action = '';
                    let badgeColor = 'primary';
                    
                    // Check for completion events (payment_completed, payment_complete, completed, etc.)
                    const isCompleted = activity.event_type.includes('complete') || activity.event_type === 'success';
                    
                    if (isCompleted) {
                        const amount = activity.amount ? ` ($${parseFloat(activity.amount).toFixed(2)})` : '';
                        action = `${activity.form_type.charAt(0).toUpperCase() + activity.form_type.slice(1)} Completed${amount}${location}`;
                        badgeColor = 'success';
                    } else if (activity.event_type === 'amount_entered') {
                        const amount = activity.amount ? ` ($${parseFloat(activity.amount).toFixed(2)})` : '';
                        action = `${activity.form_type.charAt(0).toUpperCase() + activity.form_type.slice(1)} Amount Entered${amount}${location}`;
                        badgeColor = 'warning';
                    } else if (activity.event_type === 'form_view') {
                        action = `${activity.form_type.charAt(0).toUpperCase() + activity.form_type.slice(1)} Form Viewed${location}`;
                        badgeColor = 'info';
                    } else if (activity.event_type === 'auction_activity') {
                        const amount = activity.amount ? ` ($${parseFloat(activity.amount).toFixed(2)})` : '';
                        action = `Auction Bid${amount}${location}`;
                        badgeColor = 'danger';
                    } else {
                        action = activity.event_type.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()) + location;
                        badgeColor = 'primary';
                    }
                    
                    const row = `
                        <tr>
                            <td><small class="text-muted">${timeAgo}</small></td>
                            <td><code>${activity.page_url || activity.url || 'Unknown'}</code></td>
                            <td><span class="badge bg-secondary">${user}</span></td>
                            <td><span class="badge bg-${badgeColor}">${action}</span></td>
                        </tr>
                    `;
                    tbody.insertAdjacentHTML('beforeend', row);
                });
            } else {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="4" class="text-center text-muted">
                            <i class="fas fa-info-circle me-2"></i>
                            No recent activity
                        </td>
                    </tr>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading real-time data:', error);
            document.getElementById('active-count').textContent = '-';
        });
}

// Helper function to format time ago
function formatTimeAgo(date) {
    const now = new Date();
    const diffInSeconds = Math.floor((now - date) / 1000);
    
    if (diffInSeconds < 60) return `${diffInSeconds}s ago`;
    if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)}m ago`;
    if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)}h ago`;
    return `${Math.floor(diffInSeconds / 86400)}d ago`;
}
</script>
@endsection