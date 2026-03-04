@extends('admin.main')

@section('content')
<style>
    .fraud-stat-card {
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        transition: all 0.3s ease;
    }
    .fraud-stat-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    .risk-badge {
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .risk-critical { background: #fee; color: #c00; }
    .risk-high { background: #ffeaa7; color: #d63031; }
    .risk-medium { background: #fff3cd; color: #fd7e14; }
    .risk-low { background: #d1f2eb; color: #198754; }
    .chart-container {
        height: 300px;
        position: relative;
    }
    .fraud-timeline {
        border-left: 3px solid #696cff;
        padding-left: 20px;
    }
    .fraud-timeline-item {
        position: relative;
        padding-bottom: 20px;
    }
    .fraud-timeline-item::before {
        content: '';
        position: absolute;
        left: -26px;
        top: 0;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #696cff;
        border: 3px solid #fff;
        box-shadow: 0 0 0 3px #e7e7ff;
    }
</style>

<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="flex-grow-1">
            <h4 class="fw-bold mb-1">
                <i class="bx bx-shield-alt-2 text-primary me-2"></i>Fraud Detection
            </h4>
            <p class="text-muted mb-0">Real-time fraud monitoring powered by AI • Inspired by Stripe Radar</p>
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
            <button class="btn btn-sm btn-primary" onclick="safeShowModal('#createRuleModal')">
                <i class="bx bx-plus me-1"></i>Create Rule
            </button>
            <button class="btn btn-sm btn-outline-secondary" onclick="exportReport()">
                <i class="bx bx-download me-1"></i>Export Report
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card fraud-stat-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Total Detections</p>
                            <h3 class="mb-0 fw-bold" id="total-detections">
                                <span class="spinner-border spinner-border-sm" role="status"></span>
                            </h3>
                            <small class="text-success">
                                <i class="bx bx-trending-up"></i> +12.5% vs last month
                            </small>
                        </div>
                        <div class="avatar">
                            <div class="avatar-initial rounded" style="background: #e7e7ff;">
                                <i class="bx bx-error-circle text-primary fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card fraud-stat-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">High Risk Transactions</p>
                            <h3 class="mb-0 fw-bold text-danger" id="high-risk">
                                <span class="spinner-border spinner-border-sm" role="status"></span>
                            </h3>
                            <small class="text-danger">
                                <i class="bx bx-trending-down"></i> -8.3% vs last week
                            </small>
                        </div>
                        <div class="avatar">
                            <div class="avatar-initial rounded" style="background: #ffe0e0;">
                                <i class="bx bx-shield-x text-danger fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card fraud-stat-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Blocked Transactions</p>
                            <h3 class="mb-0 fw-bold" id="blocked">
                                <span class="spinner-border spinner-border-sm" role="status"></span>
                            </h3>
                            <small class="text-muted">
                                <i class="bx bx-minus"></i> 0.0% change
                            </small>
                        </div>
                        <div class="avatar">
                            <div class="avatar-initial rounded" style="background: #fff3cd;">
                                <i class="bx bx-block text-warning fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card fraud-stat-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Amount Saved</p>
                            <h3 class="mb-0 fw-bold text-success" id="amount-saved">
                                <span class="spinner-border spinner-border-sm" role="status"></span>
                            </h3>
                            <small class="text-success">
                                <i class="bx bx-trending-up"></i> +$45K this month
                            </small>
                        </div>
                        <div class="avatar">
                            <div class="avatar-initial rounded" style="background: #d1f2eb;">
                                <i class="bx bx-dollar-circle text-success fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-3 mb-4">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Risk Score Trends</h5>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-primary active">7D</button>
                        <button type="button" class="btn btn-outline-primary">30D</button>
                        <button type="button" class="btn btn-outline-primary">90D</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="fraudTrendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Risk Distribution</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="riskDistributionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detection Rules & Recent Activity -->
    <div class="row g-3 mb-4">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Active Detection Rules</h5>
                    <span class="badge bg-label-primary">8 Active</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Rule Name</th>
                                    <th>Type</th>
                                    <th>Detections</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><i class="bx bx-dollar text-warning me-2"></i>High Value Transaction</td>
                                    <td><span class="badge bg-label-info">Amount</span></td>
                                    <td>127</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-icon btn-text-secondary rounded-pill">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><i class="bx bx-map text-danger me-2"></i>Suspicious Location</td>
                                    <td><span class="badge bg-label-warning">Velocity</span></td>
                                    <td>89</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-icon btn-text-secondary rounded-pill">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><i class="bx bx-credit-card text-primary me-2"></i>Multiple Failed Attempts</td>
                                    <td><span class="badge bg-label-danger">Pattern</span></td>
                                    <td>56</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-icon btn-text-secondary rounded-pill">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Activity</h5>
                </div>
                <div class="card-body">
                    <div class="fraud-timeline">
                        <div class="fraud-timeline-item">
                            <small class="text-muted">2 minutes ago</small>
                            <p class="mb-1 fw-medium">High-risk transaction blocked</p>
                            <small class="text-muted">Transaction #TXN-8923 • $12,500 • Risk Score: 95</small>
                        </div>
                        <div class="fraud-timeline-item">
                            <small class="text-muted">15 minutes ago</small>
                            <p class="mb-1 fw-medium">Velocity rule triggered</p>
                            <small class="text-muted">User #4231 • 5 transactions in 2 minutes</small>
                        </div>
                        <div class="fraud-timeline-item">
                            <small class="text-muted">1 hour ago</small>
                            <p class="mb-1 fw-medium">Suspicious pattern detected</p>
                            <small class="text-muted">Multiple cards from same device</small>
                        </div>
                        <div class="fraud-timeline-item">
                            <small class="text-muted">3 hours ago</small>
                            <p class="mb-1 fw-medium">Location mismatch flagged</p>
                            <small class="text-muted">User location 2000km from billing address</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Detections Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Recent Fraud Detections</h5>
            <div class="dropdown">
                <button class="btn btn-sm btn-text-secondary rounded-pill dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="bx bx-filter me-1"></i>Filter
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">All</a></li>
                    <li><a class="dropdown-item" href="#">High Risk Only</a></li>
                    <li><a class="dropdown-item" href="#">Blocked</a></li>
                    <li><a class="dropdown-item" href="#">Approved</a></li>
                </ul>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>User</th>
                            <th>Amount</th>
                            <th>Risk Score</th>
                            <th>Risk Level</th>
                            <th>Action</th>
                            <th>Reason</th>
                            <th>Time</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody id="detections-table">
                        <tr>
                            <td colspan="9" class="text-center">
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
</div>

<!-- Create Rule Modal -->
<div class="modal fade" id="createRuleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Fraud Detection Rule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createRuleForm">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Rule Name</label>
                            <input type="text" class="form-control" name="name" required placeholder="e.g., High Value Velocity Check">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Priority</label>
                            <input type="number" class="form-control" name="priority" value="1" min="1" max="100">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Rule Type</label>
                            <select class="form-select" name="rule_type" required>
                                <option value="">Select Type</option>
                                <option value="velocity">Velocity Check</option>
                                <option value="geolocation">Geolocation</option>
                                <option value="amount_threshold">Amount Threshold</option>
                                <option value="card_testing">Card Testing</option>
                                <option value="pattern_matching">Pattern Matching</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Action</label>
                            <select class="form-select" name="action" required>
                                <option value="flag">Flag</option>
                                <option value="block">Block</option>
                                <option value="review">Review</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Risk Score</label>
                            <input type="number" class="form-control" name="risk_score" required min="1" max="100" value="50">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="2" placeholder="Describe what this rule does..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Parameters (JSON)</label>
                        <textarea class="form-control" name="parameters" rows="4" placeholder='{"max_transactions": 5, "time_window": 3600}'>{}</textarea>
                        <small class="text-muted">Enter rule parameters as JSON object</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="createRule()">Create Rule</button>
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
const statsUrl = '/fraud/api/stats' + (currentWebsiteId ? '?website_id=' + currentWebsiteId : '');
fetch(statsUrl)
    .then(response => response.json())
    .then(data => {
        document.getElementById('total-detections').textContent = data.total || '0';
        document.getElementById('high-risk').textContent = data.high_risk || '0';
        document.getElementById('blocked').textContent = data.blocked || '0';
        document.getElementById('amount-saved').textContent = '$' + (data.amount_saved || '0');
    })
    .catch(err => console.error('Stats error:', err));

// Load Recent Detections
const recentUrl = '/fraud/api/recent' + (currentWebsiteId ? '?website_id=' + currentWebsiteId : '');
fetch(recentUrl)
    .then(response => response.json())
    .then(data => {
        const tbody = document.getElementById('detections-table');
        if (data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="9" class="text-center text-muted">No fraud detections found</td></tr>';
            return;
        }
        
        tbody.innerHTML = data.map(d => {
            const riskClass = d.risk_level === 'critical' ? 'risk-critical' : 
                             d.risk_level === 'high' ? 'risk-high' : 
                             d.risk_level === 'medium' ? 'risk-medium' : 'risk-low';
            
            const actionBadge = d.action_taken === 'blocked' ? 'bg-danger' : 
                               d.action_taken === 'flagged' ? 'bg-warning' : 'bg-success';
            
            return `
                <tr>
                    <td><code>#${d.transaction_id || 'N/A'}</code></td>
                    <td>User #${d.user_id || 'N/A'}</td>
                    <td>$${d.amount || '0.00'}</td>
                    <td><strong>${d.risk_score || 0}</strong>/100</td>
                    <td><span class="risk-badge ${riskClass}">${d.risk_level || 'low'}</span></td>
                    <td><span class="badge ${actionBadge}">${d.action_taken || 'reviewed'}</span></td>
                    <td><small class="text-muted">${d.detection_reason || 'N/A'}</small></td>
                    <td><small>${new Date(d.created_at).toLocaleString()}</small></td>
                    <td>
                        <button class="btn btn-sm btn-icon btn-text-secondary rounded-pill">
                            <i class="bx bx-show"></i>
                        </button>
                    </td>
                </tr>
            `;
        }).join('');
    })
    .catch(err => {
        console.error('Detections error:', err);
        document.getElementById('detections-table').innerHTML = '<tr><td colspan="9" class="text-center text-danger">Error loading detections</td></tr>';
    });

// Fraud Trend Chart - Real Data
const ctx1 = document.getElementById('fraudTrendChart').getContext('2d');
new Chart(ctx1, {
    type: 'line',
    data: {
        labels: @json($chartData['trend']['labels']),
        datasets: [{
            label: 'Risk Score',
            data: @json($chartData['trend']['risk_scores']),
            borderColor: '#696cff',
            backgroundColor: 'rgba(105, 108, 255, 0.1)',
            tension: 0.4,
            fill: true
        }, {
            label: 'Blocked',
            data: @json($chartData['trend']['blocked_counts']),
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
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Risk Distribution Chart - Real Data
const ctx2 = document.getElementById('riskDistributionChart').getContext('2d');
new Chart(ctx2, {
    type: 'doughnut',
    data: {
        labels: ['Low', 'Medium', 'High', 'Critical'],
        datasets: [{
            data: [
                {{ $chartData['distribution']['low'] }},
                {{ $chartData['distribution']['medium'] }},
                {{ $chartData['distribution']['high'] }},
                {{ $chartData['distribution']['critical'] }}
            ],
            backgroundColor: ['#71dd37', '#ffab00', '#ff3e1d', '#8e2c1c'],
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
            }
        }
    }
});

// Create Rule Function
function createRule() {
    const form = document.getElementById('createRuleForm');
    const formData = new FormData(form);
    
    // Parse parameters JSON
    let parameters;
    try {
        parameters = JSON.parse(formData.get('parameters'));
    } catch (e) {
        alert('Invalid JSON in parameters field');
        return;
    }
    
    const data = {
        name: formData.get('name'),
        rule_type: formData.get('rule_type'),
        action: formData.get('action'),
        risk_score: parseInt(formData.get('risk_score')),
        priority: parseInt(formData.get('priority')),
        description: formData.get('description'),
        parameters: parameters
    };
    
    fetch('/fraud/rules', {
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
            bootstrap.Modal.getInstance(document.getElementById('createRuleModal')).hide();
            window.location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to create rule: ' + error.message);
    });
}

// Export Report Function
function exportReport() {
    const websiteId = document.getElementById('websiteFilter').value;
    const url = '/fraud/export' + (websiteId ? '?website_id=' + websiteId : '');
    window.location.href = url;
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
</script>
@endsection
