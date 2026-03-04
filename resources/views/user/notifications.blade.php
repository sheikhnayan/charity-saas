@extends('user.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="mb-1"><i class="bx bx-bell me-2"></i>Notification Settings</h4>
                            <p class="text-muted mb-0">Manage how you receive notifications for {{ $website?->type === 'investment' ? 'investment' : 'fundraiser' }} activities</p>
                        </div>
                        <button type="button" class="btn btn-sm btn-primary" id="test-notification-btn">
                            <i class="bx bx-test-tube me-1"></i> Send Test Notification
                        </button>
                    </div>
                </div>
            </div>

            <!-- Main Settings Card -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0">Push Notification Settings</h5>
                </div>
                <div class="card-body">
                    <!-- Notification Permission Status -->
                    <div class="alert alert-info d-flex align-items-center" role="alert" id="permission-status">
                        <i class="bx bx-info-circle me-2"></i>
                        <div>
                            <strong>Status:</strong> <span id="permission-text">Checking notification permission...</span>
                        </div>
                    </div>

                    <!-- Enable Notifications Button -->
                    <div class="mb-4 text-center" id="enable-section">
                        <div class="card bg-primary-subtle border-primary mb-3">
                            <div class="card-body py-4">
                                <i class="bx bx-bell-off bx-lg text-primary mb-2"></i>
                                <h5 class="card-title">Enable Push Notifications</h5>
                                <p class="card-text mb-3">Get real-time alerts for {{ $website?->type === 'investment' ? 'investor activities, funding milestones, and transaction updates' : 'donations, campaign updates, and fundraising progress' }}.</p>
                                <button type="button" class="btn btn-primary btn-lg" id="enable-notifications-btn">
                                    <i class="bx bx-bell me-2"></i> Enable Notifications
                                </button>
                            </div>
                        </div>
                    </div>

                    <form id="notification-settings-form">
                        @csrf
                        
                        <!-- Notification Types - Tailored by Site Type -->
                        <div class="mb-4">
                            <h6 class="mb-3">Notification Types</h6>
                            <div class="row g-3">
                                @if ($website?->type === 'investment')
                                    <!-- Investment-specific notifications -->
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="investment_inquiry_enabled" name="investment_inquiry_enabled" checked>
                                            <label class="form-check-label" for="investment_inquiry_enabled">
                                                <strong>Investment Inquiries</strong>
                                                <small class="d-block text-muted">New investor questions or inquiries</small>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="investment_milestone_enabled" name="investment_milestone_enabled" checked>
                                            <label class="form-check-label" for="investment_milestone_enabled">
                                                <strong>Funding Milestones</strong>
                                                <small class="d-block text-muted">When investment rounds reach key targets</small>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="investment_update_enabled" name="investment_update_enabled" checked>
                                            <label class="form-check-label" for="investment_update_enabled">
                                                <strong>Investment Updates</strong>
                                                <small class="d-block text-muted">Progress reports and project updates</small>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="investor_request_enabled" name="investor_request_enabled" checked>
                                            <label class="form-check-label" for="investor_request_enabled">
                                                <strong>New Investor Applications</strong>
                                                <small class="d-block text-muted">When investors submit applications</small>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="transaction_enabled" name="transaction_enabled" checked>
                                            <label class="form-check-label" for="transaction_enabled">
                                                <strong>Investment Transactions</strong>
                                                <small class="d-block text-muted">Successful investments and payments</small>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="compliance_alert_enabled" name="compliance_alert_enabled" checked>
                                            <label class="form-check-label" for="compliance_alert_enabled">
                                                <strong>Compliance Alerts</strong>
                                                <small class="d-block text-muted">Regulatory or compliance notifications</small>
                                            </label>
                                        </div>
                                    </div>
                                @else
                                    <!-- Fundraiser-specific notifications -->
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="donation_enabled" name="donation_enabled" checked>
                                            <label class="form-check-label" for="donation_enabled">
                                                <strong>Donation Notifications</strong>
                                                <small class="d-block text-muted">New donations and contributions</small>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="goal_reached_enabled" name="goal_reached_enabled" checked>
                                            <label class="form-check-label" for="goal_reached_enabled">
                                                <strong>Goal Reached</strong>
                                                <small class="d-block text-muted">Celebrate when campaigns reach their goals</small>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="campaign_update_enabled" name="campaign_update_enabled" checked>
                                            <label class="form-check-label" for="campaign_update_enabled">
                                                <strong>Campaign Updates</strong>
                                                <small class="d-block text-muted">Progress and status updates</small>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="auction_won_enabled" name="auction_won_enabled" checked>
                                            <label class="form-check-label" for="auction_won_enabled">
                                                <strong>Auction Activity</strong>
                                                <small class="d-block text-muted">Auction wins and bids</small>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="ticket_purchased_enabled" name="ticket_purchased_enabled" checked>
                                            <label class="form-check-label" for="ticket_purchased_enabled">
                                                <strong>Ticket Purchases</strong>
                                                <small class="d-block text-muted">Event tickets and event reminders</small>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="donation_message_enabled" name="donation_message_enabled" checked>
                                            <label class="form-check-label" for="donation_message_enabled">
                                                <strong>Donor Messages</strong>
                                                <small class="d-block text-muted">Messages from donors and supporters</small>
                                            </label>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Notification Frequency -->
                        <div class="mb-4">
                            <label for="frequency" class="form-label">
                                <h6 class="mb-0">Notification Frequency</h6>
                            </label>
                            <select class="form-select" id="frequency" name="frequency">
                                <option value="realtime" selected>Real-time (Instant notifications)</option>
                                <option value="hourly">Hourly Digest</option>
                                <option value="daily">Daily Digest</option>
                                <option value="weekly">Weekly Digest</option>
                            </select>
                            <small class="text-muted">Choose how often you want to receive notifications</small>
                        </div>

                        <hr class="my-4">

                        <!-- Quiet Hours -->
                        <div class="mb-4">
                            <h6 class="mb-3">Quiet Hours</h6>
                            <p class="text-muted mb-3">Set a time range when you don't want to receive notifications</p>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="quiet_hours_start" class="form-label">Start Time</label>
                                    <input type="time" class="form-control" id="quiet_hours_start" name="quiet_hours_start" value="22:00">
                                </div>
                                <div class="col-md-6">
                                    <label for="quiet_hours_end" class="form-label">End Time</label>
                                    <input type="time" class="form-control" id="quiet_hours_end" name="quiet_hours_end" value="08:00">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-outline-secondary" id="reset-btn">Reset to Defaults</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-1"></i> Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Device Management -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0">Connected Devices</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Device Type</th>
                                    <th>Browser</th>
                                    <th>Last Active</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="devices-list">
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        <i class="bx bx-devices me-2"></i>No devices registered yet
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('User notification settings page loaded');
    
    const form = document.getElementById('notification-settings-form');
    const permissionStatus = document.getElementById('permission-status');
    const permissionText = document.getElementById('permission-text');
    const enableSection = document.getElementById('enable-section');
    const enableBtn = document.getElementById('enable-notifications-btn');
    const testBtn = document.getElementById('test-notification-btn');
    const resetBtn = document.getElementById('reset-btn');

    // Check notification permission
    function checkPermission() {
        if (!('Notification' in window)) {
            permissionText.textContent = 'Your browser does not support notifications';
            permissionStatus.classList.remove('alert-info');
            permissionStatus.classList.add('alert-warning');
            enableSection.style.display = 'none';
            return;
        }

        const permission = Notification.permission;
        
        if (permission === 'granted') {
            permissionText.innerHTML = 'Push notifications are enabled ✓ <button class="btn btn-sm btn-outline-primary ms-2" id="refresh-token-btn"><i class="bx bx-refresh"></i> Re-register Device</button>';
            permissionStatus.classList.remove('alert-info', 'alert-warning');
            permissionStatus.classList.add('alert-success');
            enableSection.style.display = 'none';
            loadSettings();
            loadDevices();
            
            // Add refresh token handler
            setTimeout(() => {
                const refreshBtn = document.getElementById('refresh-token-btn');
                if (refreshBtn) {
                    refreshBtn.addEventListener('click', async function() {
                        refreshBtn.disabled = true;
                        refreshBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Re-registering...';
                        
                        try {
                            if (typeof PushNotificationManager !== 'undefined') {
                                const manager = new PushNotificationManager();
                                await manager.init();
                                await manager.getAndSaveToken();
                                alert('Device re-registered successfully!');
                                loadDevices();
                                refreshBtn.disabled = false;
                                refreshBtn.innerHTML = '<i class="bx bx-refresh"></i> Re-register Device';
                            }
                        } catch (error) {
                            alert('Failed to re-register: ' + error.message);
                            refreshBtn.disabled = false;
                            refreshBtn.innerHTML = '<i class="bx bx-refresh"></i> Re-register Device';
                        }
                    });
                }
            }, 500);
        } else if (permission === 'denied') {
            permissionText.textContent = 'Push notifications are blocked. Please enable them in your browser settings.';
            permissionStatus.classList.remove('alert-info');
            permissionStatus.classList.add('alert-danger');
            enableSection.style.display = 'none';
        } else {
            permissionText.innerHTML = 'Push notifications are not enabled. <strong>Click the button below to enable them.</strong>';
            permissionStatus.classList.remove('alert-info');
            permissionStatus.classList.add('alert-warning');
            enableSection.style.display = 'block';
        }
    }

    // Enable notifications
    enableBtn.addEventListener('click', async function() {
        try {
            enableBtn.disabled = true;
            enableBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Requesting permission...';
            
            const permission = await Notification.requestPermission();
            
            if (permission === 'granted') {
                if (typeof PushNotificationManager !== 'undefined') {
                    const manager = new PushNotificationManager();
                    await manager.init();
                    await manager.getAndSaveToken();
                }
                
                checkPermission();
                loadDevices();
                
                const successAlert = document.createElement('div');
                successAlert.className = 'alert alert-success alert-dismissible fade show mt-3';
                successAlert.innerHTML = `
                    <strong>Success!</strong> Push notifications are now enabled.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                enableSection.appendChild(successAlert);
                
                setTimeout(() => successAlert.remove(), 5000);
            } else {
                alert('Notification permission was denied. Please check your browser settings.');
            }
            
            enableBtn.disabled = false;
            enableBtn.innerHTML = '<i class="bx bx-bell me-2"></i> Enable Notifications';
            
        } catch (error) {
            console.error('Error enabling notifications:', error);
            alert('Failed to enable notifications: ' + error.message);
            enableBtn.disabled = false;
            enableBtn.innerHTML = '<i class="bx bx-bell me-2"></i> Enable Notifications';
        }
    });

    // Load connected devices
    async function loadDevices() {
        try {
            const response = await fetch('/api/notifications/devices', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                const devicesList = document.getElementById('devices-list');
                
                if (data.devices && data.devices.length > 0) {
                    devicesList.innerHTML = data.devices.map(device => `
                        <tr>
                            <td><i class="bx bx-${device.device_type === 'web' ? 'desktop' : 'mobile'} me-2"></i>${device.device_type}</td>
                            <td>${device.browser || 'Unknown'}</td>
                            <td>${new Date(device.last_used_at).toLocaleString()}</td>
                            <td><span class="badge bg-success">Active</span></td>
                            <td>
                                <button class="btn btn-sm btn-danger" onclick="removeDevice('${device.token_hash}')">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `).join('');
                } else {
                    devicesList.innerHTML = '<tr><td colspan="5" class="text-center text-muted"><i class="bx bx-devices me-2"></i>No devices registered yet</td></tr>';
                }
            }
        } catch (error) {
            console.error('Failed to load devices:', error);
        }
    }

    // Load current settings
    async function loadSettings() {
        try {
            const response = await fetch('/api/notifications/preferences', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                if (data.preferences) {
                    Object.keys(data.preferences).forEach(key => {
                        const checkbox = document.getElementById(key);
                        if (checkbox && typeof data.preferences[key] === 'boolean') {
                            checkbox.checked = data.preferences[key];
                        }
                    });
                    
                    if (data.preferences.frequency) {
                        document.getElementById('frequency').value = data.preferences.frequency;
                    }
                    
                    if (data.preferences.quiet_hours_start) {
                        document.getElementById('quiet_hours_start').value = data.preferences.quiet_hours_start;
                    }
                    if (data.preferences.quiet_hours_end) {
                        document.getElementById('quiet_hours_end').value = data.preferences.quiet_hours_end;
                    }
                }
            }
        } catch (error) {
            console.error('Failed to load settings:', error);
        }
    }

    // Save settings
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        const settings = {};
        
        for (let [key, value] of formData.entries()) {
            if (key !== '_token') {
                if (key.includes('_enabled')) {
                    settings[key] = true;
                } else {
                    settings[key] = value;
                }
            }
        }
        
        const checkboxes = form.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            if (!checkbox.checked) {
                settings[checkbox.name] = false;
            }
        });
        
        try {
            const response = await fetch('/api/notifications/preferences', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(settings)
            });
            
            if (response.ok) {
                alert('Settings saved successfully!');
            } else {
                alert('Failed to save settings. Please try again.');
            }
        } catch (error) {
            console.error('Save error:', error);
            alert('Failed to save settings. Please try again.');
        }
    });

    // Test notification
    testBtn.addEventListener('click', async function() {
        try {
            const response = await fetch('/api/notifications/test', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            if (response.ok) {
                alert('Test notification sent! Check your notifications.');
            } else {
                alert('Failed to send test notification.');
            }
        } catch (error) {
            console.error('Test error:', error);
            alert('Failed to send test notification.');
        }
    });

    // Reset to defaults
    resetBtn.addEventListener('click', function() {
        if (confirm('Reset all notification settings to defaults?')) {
            form.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = true);
            document.getElementById('frequency').value = 'realtime';
            document.getElementById('quiet_hours_start').value = '22:00';
            document.getElementById('quiet_hours_end').value = '08:00';
        }
    });

    // Initialize
    setTimeout(function() {
        checkPermission();
    }, 100);
});
</script>
@endsection
