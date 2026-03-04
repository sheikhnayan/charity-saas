@extends('admin.main')

@section('content')
<style>
    .filters-card { background: white; border-radius: 8px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    .recording-card { background: white; border-radius: 8px; padding: 20px; margin-bottom: 15px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); transition: all 0.3s; cursor: pointer; }
    .recording-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.1); transform: translateY(-2px); }
    .badge-rage { background: #ff4444; color: white; font-size: 11px; padding: 4px 8px; border-radius: 4px; }
    .badge-error { background: #ff9800; color: white; font-size: 11px; padding: 4px 8px; border-radius: 4px; }
    .badge-starred { color: #ffc107; font-size: 16px; }
    .device-icon { font-size: 14px; color: #666; }
    .duration { font-weight: 600; color: #696cff; }
    .location { color: #666; font-size: 13px; }
    .timestamp { color: #999; font-size: 12px; }
    .filter-btn { margin-right: 10px; margin-bottom: 10px; }
    .stats-row { display: flex; gap: 20px; margin-bottom: 20px; }
    .stat-card { flex: 1; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    .stat-value { font-size: 32px; font-weight: 700; color: #696cff; }
    .stat-label { color: #666; font-size: 14px; margin-top: 5px; }
</style>

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">User Behavior /</span> Session Recordings
    </h4>
        <!-- Stats Row -->
        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-value" id="totalRecordings">0</div>
                <div class="stat-label">Total Recordings</div>
            </div>
            <div class="stat-card">
                <div class="stat-value" id="rageClicks">0</div>
                <div class="stat-label">With Rage Clicks</div>
            </div>
            <div class="stat-card">
                <div class="stat-value" id="withErrors">0</div>
                <div class="stat-label">With Errors</div>
            </div>
            <div class="stat-card">
                <div class="stat-value" id="avgDuration">0s</div>
                <div class="stat-label">Avg Duration</div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filters-card">
            <h5 class="mb-3">Filters</h5>
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Website</label>
                    <select class="form-select" id="filterWebsite">
                        <option value="">All Websites</option>
                        @foreach($websites as $website)
                            <option value="{{ $website->id }}">{{ $website->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" id="filterStatus">
                        <option value="">All</option>
                        <option value="completed">Completed</option>
                        <option value="recording">Recording</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Device Type</label>
                    <select class="form-select" id="filterDevice">
                        <option value="">All Devices</option>
                        <option value="desktop">Desktop</option>
                        <option value="mobile">Mobile</option>
                        <option value="tablet">Tablet</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Min Duration (seconds)</label>
                    <input type="number" class="form-control" id="filterDuration" placeholder="e.g., 30">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <button class="btn btn-sm btn-outline-danger filter-btn" id="filterRageClicks">
                        <i class="fas fa-angry"></i> Has Rage Clicks
                    </button>
                    <button class="btn btn-sm btn-outline-warning filter-btn" id="filterErrors">
                        <i class="fas fa-exclamation-triangle"></i> Has Errors
                    </button>
                    <button class="btn btn-sm btn-outline-warning filter-btn" id="filterStarred">
                        <i class="fas fa-star"></i> Starred Only
                    </button>
                    <button class="btn btn-primary filter-btn" onclick="loadRecordings()">
                        <i class="fas fa-search"></i> Apply Filters
                    </button>
                    <button class="btn btn-secondary filter-btn" onclick="resetFilters()">
                        <i class="fas fa-redo"></i> Reset
                    </button>
                </div>
            </div>
        </div>

        <!-- Recordings List -->
        <div id="recordingsList"></div>

        <!-- Pagination -->
        <div id="pagination" class="mt-4"></div>

    <script>
        let currentPage = 1;
        let activeFilters = {
            has_rage_clicks: false,
            has_errors: false,
            starred: false
        };

        // Toggle filter buttons
        document.getElementById('filterRageClicks').addEventListener('click', function() {
            activeFilters.has_rage_clicks = !activeFilters.has_rage_clicks;
            this.classList.toggle('btn-danger');
            this.classList.toggle('btn-outline-danger');
        });

        document.getElementById('filterErrors').addEventListener('click', function() {
            activeFilters.has_errors = !activeFilters.has_errors;
            this.classList.toggle('btn-warning');
            this.classList.toggle('btn-outline-warning');
        });

        document.getElementById('filterStarred').addEventListener('click', function() {
            activeFilters.starred = !activeFilters.starred;
            this.classList.toggle('btn-warning');
            this.classList.toggle('btn-outline-warning');
        });

        async function loadRecordings(page = 1) {
            currentPage = page;
            const params = new URLSearchParams({
                page: page,
                per_page: 20
            });

            // Add filters
            const websiteId = document.getElementById('filterWebsite').value;
            if (websiteId) params.append('website_id', websiteId);

            const status = document.getElementById('filterStatus').value;
            if (status) params.append('status', status);

            const device = document.getElementById('filterDevice').value;
            if (device) params.append('device_type', device);

            const duration = document.getElementById('filterDuration').value;
            if (duration) params.append('min_duration', duration * 1000);

            if (activeFilters.has_rage_clicks) params.append('has_rage_clicks', '1');
            if (activeFilters.has_errors) params.append('has_errors', '1');
            if (activeFilters.starred) params.append('starred', '1');

            try {
                const response = await fetch(`/api/session-recording?${params}`, {
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                console.log('API Response:', data);
                console.log('Recordings Array:', data.data);
                
                displayRecordings(data.data);
                displayPagination(data);
                updateStats(data);
            } catch (error) {
                console.error('Failed to load recordings:', error);
            }
        }

        function displayRecordings(recordings) {
            const container = document.getElementById('recordingsList');
            console.log('displayRecordings called with:', recordings);
            
            if (!recordings || recordings.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-5">
                        <i class="fas fa-video fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No recordings found matching your filters</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = recordings.filter(rec => rec && rec.id).map(rec => `
                <div class="recording-card" onclick="viewRecording(${rec.id})">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <h5 class="mb-0">${rec.page_title || 'Untitled Page'}</h5>
                                ${rec.is_starred ? '<i class="fas fa-star badge-starred"></i>' : ''}
                                ${rec.has_rage_clicks ? '<span class="badge-rage"><i class="fas fa-angry"></i> Rage Clicks</span>' : ''}
                                ${rec.has_errors ? '<span class="badge-error"><i class="fas fa-exclamation-triangle"></i> Errors</span>' : ''}
                            </div>
                            <div class="text-muted small mb-2">${rec.url || 'Unknown URL'}</div>
                            <div class="d-flex gap-3 align-items-center">
                                <span class="duration">
                                    <i class="fas fa-clock"></i> ${formatDuration(rec.duration_ms)}
                                </span>
                                <span class="device-icon">
                                    <i class="fas fa-${getDeviceIcon(rec.device_type)}"></i> ${rec.device_type || 'Unknown'}
                                </span>
                                <span class="location">
                                    <i class="fas fa-map-marker-alt"></i> ${rec.country || 'Unknown'} ${rec.state ? ', ' + rec.state : ''}
                                </span>
                                <span class="timestamp">
                                    <i class="fas fa-calendar"></i> ${formatDate(rec.started_at)}
                                </span>
                            </div>
                            ${rec.notes ? `<div class="mt-2 small text-muted"><i class="fas fa-sticky-note"></i> ${rec.notes}</div>` : ''}
                        </div>
                        <div class="text-end">
                            <button class="btn btn-sm btn-primary" onclick="event.stopPropagation(); viewRecording(${rec.id})">
                                <i class="fas fa-play"></i> Watch
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function displayPagination(data) {
            const container = document.getElementById('pagination');
            if (data.last_page <= 1) {
                container.innerHTML = '';
                return;
            }

            let html = '<nav><ul class="pagination justify-content-center">';
            
            for (let i = 1; i <= data.last_page; i++) {
                html += `<li class="page-item ${i === data.current_page ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="loadRecordings(${i}); return false;">${i}</a>
                </li>`;
            }
            
            html += '</ul></nav>';
            container.innerHTML = html;
        }

        function updateStats(data) {
            // Update stats from metadata if available
            if (data.meta) {
                document.getElementById('totalRecordings').textContent = data.meta.total || data.total || 0;
                document.getElementById('rageClicks').textContent = data.meta.rage_clicks_count || 0;
                document.getElementById('withErrors').textContent = data.meta.errors_count || 0;
                document.getElementById('avgDuration').textContent = data.meta.avg_duration ? 
                    formatDuration(data.meta.avg_duration) : '0s';
            }
        }

        function viewRecording(id) {
            if (!id) {
                console.error('Cannot view recording: ID is undefined');
                alert('Error: Recording ID is missing. Please refresh and try again.');
                return;
            }
            window.location.href = `/hotjar/recordings/${id}/replay`;
        }

        function formatDuration(ms) {
            if (!ms) return '0s';
            const seconds = Math.floor(ms / 1000);
            const minutes = Math.floor(seconds / 60);
            const hours = Math.floor(minutes / 60);
            
            if (hours > 0) return `${hours}h ${minutes % 60}m`;
            if (minutes > 0) return `${minutes}m ${seconds % 60}s`;
            return `${seconds}s`;
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
        }

        function getDeviceIcon(device) {
            switch(device) {
                case 'mobile': return 'mobile-alt';
                case 'tablet': return 'tablet-alt';
                case 'desktop': return 'desktop';
                default: return 'laptop';
            }
        }

        function resetFilters() {
            document.getElementById('filterWebsite').value = '';
            document.getElementById('filterStatus').value = '';
            document.getElementById('filterDevice').value = '';
            document.getElementById('filterDuration').value = '';
            
            activeFilters = { has_rage_clicks: false, has_errors: false, starred: false };
            
            document.getElementById('filterRageClicks').classList.remove('btn-danger');
            document.getElementById('filterRageClicks').classList.add('btn-outline-danger');
            document.getElementById('filterErrors').classList.remove('btn-warning');
            document.getElementById('filterErrors').classList.add('btn-outline-warning');
            document.getElementById('filterStarred').classList.remove('btn-warning');
            document.getElementById('filterStarred').classList.add('btn-outline-warning');
            
            loadRecordings();
        }

        // Load on page load
        loadRecordings();
    </script>
</div>
@endsection
