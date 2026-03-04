@extends('admin.main')

@section('page_title', 'Notification Settings')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card mb-6">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Push Notification Settings</h5>
                    <button type="button" class="btn btn-sm btn-primary" id="test-notification-btn">
                        <i class="bx bx-test-tube me-1"></i> Send Test Notification
                    </button>
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
                                <p class="card-text mb-3">Get real-time alerts for donations, auctions, and campaign updates directly in your browser.</p>
                                <button type="button" class="btn btn-primary btn-lg" id="enable-notifications-btn">
                                    <i class="bx bx-bell me-2"></i> Enable Notifications
                                </button>
                            </div>
                        </div>
                    </div>

                    <form id="notification-settings-form">
                        @csrf
                        
                        <!-- Notification Types -->
                        <div class="mb-4">
                            <h6 class="mb-3">Notification Types</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="donation_enabled" name="donation_enabled" checked>
                                        <label class="form-check-label" for="donation_enabled">
                                            <strong>Donation Notifications</strong>
                                            <small class="d-block text-muted">Get notified when you receive donations</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="auction_outbid_enabled" name="auction_outbid_enabled" checked>
                                        <label class="form-check-label" for="auction_outbid_enabled">
                                            <strong>Auction Outbid Alerts</strong>
                                            <small class="d-block text-muted">Know when someone outbids you</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="auction_won_enabled" name="auction_won_enabled" checked>
                                        <label class="form-check-label" for="auction_won_enabled">
                                            <strong>Auction Won</strong>
                                            <small class="d-block text-muted">Celebrate when you win an auction</small>
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
                                            <small class="d-block text-muted">Stay informed about campaign progress</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="investment_milestone_enabled" name="investment_milestone_enabled" checked>
                                        <label class="form-check-label" for="investment_milestone_enabled">
                                            <strong>Investment Milestones</strong>
                                            <small class="d-block text-muted">Track important investment progress</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="ticket_purchased_enabled" name="ticket_purchased_enabled" checked>
                                        <label class="form-check-label" for="ticket_purchased_enabled">
                                            <strong>Ticket Purchases</strong>
                                            <small class="d-block text-muted">Confirm ticket purchases and event reminders</small>
                                        </label>
                                    </div>
                                </div>
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
            <div class="card">
                <div class="card-header">
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
    console.log('Notification settings page loaded');
    
    const form = document.getElementById('notification-settings-form');
    const permissionStatus = document.getElementById('permission-status');
    const permissionText = document.getElementById('permission-text');
    const enableSection = document.getElementById('enable-section');
    const enableBtn = document.getElementById('enable-notifications-btn');
    const testBtn = document.getElementById('test-notification-btn');
    const resetBtn = document.getElementById('reset-btn');
    
    console.log('Elements found:', {
        form: !!form,
        permissionStatus: !!permissionStatus,
        permissionText: !!permissionText,
        enableSection: !!enableSection,
        enableBtn: !!enableBtn
    });

    // Check notification permission
    function checkPermission() {
        console.log('=== checkPermission function called ===');
        console.log('enableSection element:', enableSection);
        console.log('enableSection display before:', enableSection ? enableSection.style.display : 'null');
        
        if (!('Notification' in window)) {
            console.log('ERROR: Browser does not support notifications');
            permissionText.textContent = 'Your browser does not support notifications';
            permissionStatus.classList.remove('alert-info');
            permissionStatus.classList.add('alert-warning');
            enableSection.style.display = 'none';
            return;
        }

        const permission = Notification.permission;
        console.log('Notification.permission =', permission);
        
        if (permission === 'granted') {
            console.log('Permission already GRANTED');
            permissionText.innerHTML = 'Push notifications are enabled ✓ <button class="btn btn-sm btn-outline-primary ms-2" id="refresh-token-btn"><i class="bx bx-refresh"></i> Re-register Device</button>';
            permissionStatus.classList.remove('alert-info', 'alert-warning');
            permissionStatus.classList.add('alert-success');
            enableSection.style.display = 'none';
            console.log('Hiding enable button, loading settings...');
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
            console.log('Permission DENIED by user');
            permissionText.textContent = 'Push notifications are blocked. Please enable them in your browser settings.';
            permissionStatus.classList.remove('alert-info');
            permissionStatus.classList.add('alert-danger');
            enableSection.style.display = 'none';
        } else {
            // 'default' permission - not yet requested
            console.log('Permission is DEFAULT - showing enable button');
            permissionText.innerHTML = 'Push notifications are not enabled. <strong>Click the button below to enable them.</strong>';
            permissionStatus.classList.remove('alert-info');
            permissionStatus.classList.add('alert-warning');
            enableSection.style.display = 'block';
            console.log('enableSection display after:', enableSection.style.display);
            console.log('enableSection visible?', enableSection.offsetParent !== null);
        }
        
        console.log('=== checkPermission function complete ===');
    }

    // Enable notifications
    enableBtn.addEventListener('click', async function() {
        try {
            console.log('Enable button clicked');
            enableBtn.disabled = true;
            enableBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Requesting permission...';
            
            // Request permission directly first
            const permission = await Notification.requestPermission();
            console.log('Permission result:', permission);
            
            if (permission === 'granted') {
                // Initialize push notification manager
                if (typeof PushNotificationManager !== 'undefined') {
                    const manager = new PushNotificationManager();
                    await manager.init();
                    await manager.getAndSaveToken();
                }
                
                checkPermission();
                loadDevices();
                
                // Show success message
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
            enableBtn.innerHTML = '<i class="bx bx-bell me-2"></i> Enable Push Notifications';
            
        } catch (error) {
            console.error('Error enabling notifications:', error);
            alert('Failed to enable notifications: ' + error.message);
            enableBtn.disabled = false;
            enableBtn.innerHTML = '<i class="bx bx-bell me-2"></i> Enable Push Notifications';
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
                    // Set checkboxes
                    Object.keys(data.preferences).forEach(key => {
                        const checkbox = document.getElementById(key);
                        if (checkbox && typeof data.preferences[key] === 'boolean') {
                            checkbox.checked = data.preferences[key];
                        }
                    });
                    
                    // Set frequency
                    if (data.preferences.frequency) {
                        document.getElementById('frequency').value = data.preferences.frequency;
                    }
                    
                    // Set quiet hours
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
                // Convert checkbox values to boolean
                if (key.includes('_enabled')) {
                    settings[key] = true;
                } else {
                    settings[key] = value;
                }
            }
        }
        
        // Add unchecked checkboxes as false
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
            // Check all notification types
            form.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = true);
            
            // Reset frequency
            document.getElementById('frequency').value = 'realtime';
            
            // Reset quiet hours
            document.getElementById('quiet_hours_start').value = '22:00';
            document.getElementById('quiet_hours_end').value = '08:00';
        }
    });

    // Initialize - call after a small delay to ensure everything is loaded
    setTimeout(function() {
        console.log('Calling checkPermission now...');
        checkPermission();
    }, 100);
});
</script>
@endsection
