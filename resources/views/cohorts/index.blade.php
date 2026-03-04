@extends('admin.main')

@section('content')
<style>
    .cohort-card {
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        transition: all 0.3s ease;
    }
    .cohort-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    .retention-table {
        font-size: 0.875rem;
    }
    .retention-cell {
        text-align: center;
        padding: 12px 8px;
        font-weight: 600;
    }
    .retention-high { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
    .retention-good { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; }
    .retention-medium { background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); color: #333; }
    .retention-low { background: linear-gradient(135deg, #fdfbfb 0%, #ebedee 100%); color: #666; }
    .cohort-type-badge {
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 0.813rem;
        font-weight: 600;
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
                <i class="bx bx-group text-primary me-2"></i>Cohort Analysis
            </h4>
            <p class="text-muted mb-0">Track user behavior patterns over time • Inspired by Mixpanel</p>
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
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createCohortModal">
                <i class="bx bx-plus me-1"></i>Create Cohort
            </button>
            <button class="btn btn-sm btn-outline-secondary">
                <i class="bx bx-download me-1"></i>Export Data
            </button>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card cohort-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Active Cohorts</p>
                            <h3 class="mb-0 fw-bold" id="active-cohorts">
                                <span class="spinner-border spinner-border-sm" role="status"></span>
                            </h3>
                            <small class="text-info">
                                <i class="bx bx-info-circle"></i> Tracking {{count($cohorts ?? [])}} groups
                            </small>
                        </div>
                        <div class="avatar">
                            <div class="avatar-initial rounded" style="background: #e7f7ff;">
                                <i class="bx bx-group text-info fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card cohort-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Avg Retention (30D)</p>
                            <h3 class="mb-0 fw-bold text-success" id="avg-retention">
                                <span class="spinner-border spinner-border-sm" role="status"></span>
                            </h3>
                            <small class="text-success">
                                <i class="bx bx-trending-up"></i> +5.2% improvement
                            </small>
                        </div>
                        <div class="avatar">
                            <div class="avatar-initial rounded" style="background: #e7ffe7;">
                                <i class="bx bx-line-chart text-success fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card cohort-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Customer LTV</p>
                            <h3 class="mb-0 fw-bold text-primary" id="avg-ltv">
                                <span class="spinner-border spinner-border-sm" role="status"></span>
                            </h3>
                            <small class="text-primary">
                                <i class="bx bx-dollar"></i> Lifetime value
                            </small>
                        </div>
                        <div class="avatar">
                            <div class="avatar-initial rounded" style="background: #e7e7ff;">
                                <i class="bx bx-dollar-circle text-primary fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card cohort-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Total Members</p>
                            <h3 class="mb-0 fw-bold" id="total-members">
                                <span class="spinner-border spinner-border-sm" role="status"></span>
                            </h3>
                            <small class="text-muted">
                                <i class="bx bx-user"></i> Across all cohorts
                            </small>
                        </div>
                        <div class="avatar">
                            <div class="avatar-initial rounded" style="background: #fff3e0;">
                                <i class="bx bx-user-check text-warning fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Retention Curve Chart -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">Retention Curve</h5>
                <small class="text-muted">Compare retention rates across cohorts</small>
            </div>
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-outline-primary active">Day 1</button>
                <button type="button" class="btn btn-outline-primary">Day 7</button>
                <button type="button" class="btn btn-outline-primary">Day 30</button>
                <button type="button" class="btn btn-outline-primary">Day 90</button>
            </div>
        </div>
        <div class="card-body">
            <div class="chart-container">
                <canvas id="retentionCurveChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Cohort Comparison & LTV Trends -->
    <div class="row g-3 mb-4">
        <div class="col-xl-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Cohort Comparison</h5>
                    <small class="text-muted">Side-by-side performance analysis</small>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="cohortComparisonChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Lifetime Value Trends</h5>
                    <small class="text-muted">Revenue per cohort over time</small>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="ltvTrendsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Retention Heatmap Table -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Cohort Retention Heatmap</h5>
            <small class="text-muted">Darker colors indicate higher retention rates</small>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered retention-table">
                    <thead class="table-light">
                        <tr>
                            <th>Cohort</th>
                            <th>Size</th>
                            <th>Day 1</th>
                            <th>Day 7</th>
                            <th>Day 14</th>
                            <th>Day 30</th>
                            <th>Day 60</th>
                            <th>Day 90</th>
                        </tr>
                    </thead>
                    <tbody id="retention-heatmap">
                        <tr>
                            <td colspan="8" class="text-center">
                                <div class="spinner-border text-primary my-3" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Cohorts List -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">All Cohorts</h5>
            <div class="dropdown">
                <button class="btn btn-sm btn-text-secondary rounded-pill dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="bx bx-filter me-1"></i>Filter by Type
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">All Types</a></li>
                    <li><a class="dropdown-item" href="#">Registration Date</a></li>
                    <li><a class="dropdown-item" href="#">First Purchase</a></li>
                    <li><a class="dropdown-item" href="#">Behavioral</a></li>
                    <li><a class="dropdown-item" href="#">Custom</a></li>
                </ul>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Cohort Name</th>
                            <th>Type</th>
                            <th>Members</th>
                            <th>Created</th>
                            <th>Avg LTV</th>
                            <th>30D Retention</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cohorts ?? [] as $cohort)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-2">
                                        <div class="avatar-initial rounded-circle bg-label-primary">
                                            <i class="bx bx-group"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <strong>{{ $cohort->name }}</strong>
                                        @if($cohort->description)
                                        <br><small class="text-muted">{{ Str::limit($cohort->description, 40) }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="cohort-type-badge bg-label-info">
                                    {{ ucfirst($cohort->cohort_type) }}
                                </span>
                            </td>
                            <td>
                                <strong>{{ number_format($cohort->members_count ?? 0) }}</strong>
                                <small class="text-muted d-block">users</small>
                            </td>
                            <td>{{ $cohort->created_at->format('M d, Y') }}</td>
                            <td>
                                <strong class="text-success">${{ number_format($cohort->average_ltv ?? 0, 2) }}</strong>
                            </td>
                            <td>
                                @php
                                    $retention = $cohort->retention_30d ?? 0;
                                    $color = $retention > 70 ? 'success' : ($retention > 40 ? 'warning' : 'danger');
                                @endphp
                                <div class="d-flex align-items-center">
                                    <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                        <div class="progress-bar bg-{{ $color }}" role="progressbar" style="width: {{ $retention }}%"></div>
                                    </div>
                                    <span class="text-{{ $color }} fw-bold">{{ $retention }}%</span>
                                </div>
                            </td>
                            <td>
                                @if($cohort->is_active)
                                <span class="badge bg-success">Active</span>
                                @else
                                <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn btn-sm btn-icon btn-text-secondary rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="/cohorts/{{ $cohort->id }}"><i class="bx bx-show me-2"></i>View Details</a></li>
                                        <li><a class="dropdown-item" href="/cohorts/{{ $cohort->id }}/edit"><i class="bx bx-edit me-2"></i>Edit</a></li>
                                        <li><a class="dropdown-item" href="#"><i class="bx bx-download me-2"></i>Export Members</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#"><i class="bx bx-trash me-2"></i>Delete</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="bx bx-group display-1 mb-3"></i>
                                <p>No cohorts found. Create your first cohort to start tracking user behavior.</p>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createCohortModal">
                                    <i class="bx bx-plus me-1"></i>Create First Cohort
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

<!-- Create Cohort Modal -->
<div class="modal fade" id="createCohortModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Cohort</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createCohortForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Cohort Name</label>
                            <input type="text" class="form-control" name="name" required placeholder="e.g., January 2024 Users">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Cohort Type</label>
                            <select class="form-select" name="type" required>
                                <option value="">Select Type</option>
                                <option value="first_time">First Time Donors</option>
                                <option value="repeat">Repeat Donors</option>
                                <option value="high_value">High Value</option>
                                <option value="lapsed">Lapsed Donors</option>
                                <option value="by_date">By Registration Date</option>
                                <option value="custom">Custom</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3" placeholder="Describe this cohort..."></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" class="form-control" name="start_date">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">End Date</label>
                            <input type="date" class="form-control" name="end_date">
                        </div>
                    </div>
                    <div class="alert alert-info">
                        <i class="bx bx-info-circle me-2"></i>
                        <strong>Note:</strong> After creating the cohort, members will be automatically added based on the selected criteria.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="createCohort()">Create Cohort</button>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
// Website filter handler
const websiteFilter = document.getElementById('websiteFilter');
let currentWebsiteId = websiteFilter.value;

websiteFilter.addEventListener('change', function() {
    currentWebsiteId = this.value;
    // Reload page with new website filter
    const url = new URL(window.location.href);
    if (currentWebsiteId) {
        url.searchParams.set('website_id', currentWebsiteId);
    } else {
        url.searchParams.delete('website_id');
    }
    window.location.href = url.toString();
});

// Load Stats
document.getElementById('active-cohorts').textContent = '{{ $stats["active_cohorts"] ?? 0 }}';
document.getElementById('avg-retention').textContent = '{{ isset($stats["avg_retention"]) ? number_format($stats["avg_retention"], 1) : "0.0" }}%';
document.getElementById('avg-ltv').textContent = '${{ isset($stats["avg_ltv"]) ? number_format($stats["avg_ltv"], 0) : "0" }}';
document.getElementById('total-members').textContent = '{{ isset($stats["total_members"]) ? number_format($stats["total_members"]) : "0" }}';

// Load Retention Heatmap
const heatmapUrl = '/cohorts/api/retention-heatmap' + (currentWebsiteId ? '?website_id=' + currentWebsiteId : '');
fetch(heatmapUrl)
    .then(res => res.json())
    .then(data => {
        const tbody = document.getElementById('retention-heatmap');
        if (data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">No retention data available</td></tr>';
            return;
        }
        
        tbody.innerHTML = data.map(cohort => {
            const getRetentionClass = (rate) => {
                if (rate >= 70) return 'retention-high';
                if (rate >= 50) return 'retention-good';
                if (rate >= 30) return 'retention-medium';
                return 'retention-low';
            };
            
            return `
                <tr>
                    <td><strong>${cohort.name}</strong></td>
                    <td>${cohort.size}</td>
                    <td class="retention-cell ${getRetentionClass(cohort.day_1)}">${cohort.day_1}%</td>
                    <td class="retention-cell ${getRetentionClass(cohort.day_7)}">${cohort.day_7}%</td>
                    <td class="retention-cell ${getRetentionClass(cohort.day_14)}">${cohort.day_14}%</td>
                    <td class="retention-cell ${getRetentionClass(cohort.day_30)}">${cohort.day_30}%</td>
                    <td class="retention-cell ${getRetentionClass(cohort.day_60)}">${cohort.day_60}%</td>
                    <td class="retention-cell ${getRetentionClass(cohort.day_90)}">${cohort.day_90}%</td>
                </tr>
            `;
        }).join('');
    })
    .catch(err => console.error('Retention heatmap error:', err));

// Retention Curve Chart - Real Data
const ctx1 = document.getElementById('retentionCurveChart').getContext('2d');
new Chart(ctx1, {
    type: 'line',
    data: {
        labels: ['Day 0', 'Day 5', 'Day 10', 'Day 15', 'Day 20', 'Day 25', 'Day 30'],
        datasets: [{
            label: 'Average Retention',
            data: @json($chartData['retention_curve']),
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
                        return 'Retention: ' + context.parsed.y + '%';
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                max: 100,
                ticks: {
                    callback: function(value) {
                        return value + '%';
                    }
                }
            }
        }
    }
});

// Cohort Growth Chart - Real Data
const ctx2 = document.getElementById('cohortComparisonChart').getContext('2d');
new Chart(ctx2, {
    type: 'bar',
    data: {
        labels: @json($chartData['growth']['labels']),
        datasets: [{
            label: 'Cohorts Created',
            data: @json($chartData['growth']['counts']),
            backgroundColor: 'rgba(105, 108, 255, 0.8)',
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

// LTV Trends Chart - Real Data
const ctx3 = document.getElementById('ltvTrendsChart').getContext('2d');
new Chart(ctx3, {
    type: 'line',
    data: {
        labels: @json($chartData['ltv_trends']['labels']),
        datasets: [{
            label: 'High Value Cohort',
            data: @json($chartData['ltv_trends']['high_value']),
            borderColor: '#71dd37',
            backgroundColor: 'rgba(113, 221, 55, 0.1)',
            tension: 0.4,
            fill: true
        }, {
            label: 'Repeat Cohort',
            data: @json($chartData['ltv_trends']['repeat']),
            borderColor: '#ffab00',
            backgroundColor: 'rgba(255, 171, 0, 0.1)',
            tension: 0.4,
            fill: true
        }, {
            label: 'First Time Cohort',
            data: @json($chartData['ltv_trends']['first_time']),
            borderColor: '#ff3e1d',
            backgroundColor: 'rgba(255, 62, 29, 0.1)',
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
                        return context.dataset.label + ': $' + context.parsed.y;
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '$' + value;
                    }
                }
            }
        }
    }
});

// Create Cohort Function
function createCohort() {
    const form = document.getElementById('createCohortForm');
    const formData = new FormData(form);
    
    // Convert FormData to JSON
    const data = {
        name: formData.get('name'),
        type: formData.get('type'),
        description: formData.get('description'),
        start_date: formData.get('start_date'),
        end_date: formData.get('end_date'),
        definition: { criteria: formData.get('type') }
    };
    
    fetch('/cohorts/', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.message) {
            alert(data.message);
            window.location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to create cohort: ' + error.message);
    });
}
</script>
@endsection
