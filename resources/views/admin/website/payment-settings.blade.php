@extends('admin.main')

@section('content')
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
            <div>
                <h4 class="mb-1"><i class="fa fa-credit-card me-2"></i>Payment Settings - {{ $website->name }}</h4>
                <p class="text-muted mb-0">Configure payment gateway credentials and Platform Fees for this website</p>
            </div>
            <div class="mt-3 mt-md-0">
                <a href="{{ route('admin.website.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left me-1"></i> Back to Websites
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center bg-light">
                        <h5 class="mb-0">
                            <i class="bx bx-cog me-2"></i>
                            Payment Gateway Configuration
                        </h5>
                        @if($paymentSettings)
                            <button type="button" class="btn btn-primary btn-sm" onclick="testConnection()">
                                <i class="fa fa-plug"></i> Test Connection
                            </button>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('admin.websites.payment.update', $website) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="card-body">
                            <!-- Platform Fee -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <i class="bx bx-info-circle me-2"></i>
                                        <strong>Website-Specific Platform Fee:</strong> Set a custom Platform Fee for this website. This fee will be used for all transactions on <strong>{{ $website->name }}</strong>.
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="fee" class="form-label"><strong>Platform Fee (%)</strong> <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" step="0.01" min="0" max="100" class="form-control @error('fee') is-invalid @enderror" 
                                                   id="fee" name="fee" 
                                                   value="{{ old('fee', $paymentSettings->fee ?? 2.9) }}"
                                                   placeholder="2.9" required>
                                            <span class="input-group-text">%</span>
                                        </div>
                                        <small class="form-text text-muted">
                                            Platform Fee percentage charged on transactions (e.g., 2.9 for 2.9%)
                                        </small>
                                        @error('fee')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label d-block"><strong>Fee Calculation Example</strong></label>
                                    <div class="p-3 bg-light rounded">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Donation Amount:</span>
                                            <strong>$100.00</strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Platform Fee (<span id="feeDisplay">{{ $paymentSettings->fee ?? 2.9 }}</span>%):</span>
                                            <strong id="feeAmount">${{ number_format((100 * ($paymentSettings->fee ?? 2.9)) / 100, 2) }}</strong>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between">
                                            <span><strong>Total:</strong></span>
                                            <strong class="text-primary" id="totalAmount">${{ number_format(100 + (100 * ($paymentSettings->fee ?? 2.9)) / 100, 2) }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="form-group mb-4">
                                <label class="form-label"><strong>Primary Payment Gateway</strong></label>
                                <small class="d-block text-muted mb-3">Choose the main payment processor for this website</small>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="payment_method" id="stripe" value="stripe" 
                                           {{ old('payment_method', $paymentSettings->payment_method ?? 'authorize') === 'stripe' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="stripe">
                                        <strong>Stripe</strong> - Credit card processing
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="payment_method" id="authorize" value="authorize" 
                                           {{ old('payment_method', $paymentSettings->payment_method ?? 'authorize') === 'authorize' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="authorize">
                                        <strong>Authorize.net</strong> - Payment processing
                                    </label>
                                </div>
                            </div>

                            <!-- Stripe Settings -->
                            <div id="stripe-settings" class="payment-settings" style="display: none;">
                                <h5 class="mb-3"><i class="fab fa-stripe me-2"></i> Stripe Configuration</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label class="form-label">Publishable Key</label>
                                            <input type="text" class="form-control" name="stripe_publishable_key" 
                                                   value="{{ old('stripe_publishable_key', $paymentSettings->stripe_publishable_key ?? '') }}"
                                                   placeholder="pk_test_... or pk_live_...">
                                            <small class="form-text text-muted">Your Stripe publishable key</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label class="form-label">Secret Key</label>
                                            <input type="password" class="form-control" name="stripe_secret_key" 
                                                   value="{{ old('stripe_secret_key', $paymentSettings->stripe_secret_key ?? '') }}"
                                                   placeholder="sk_test_... or sk_live_...">
                                            <small class="form-text text-muted">Your Stripe secret key</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">Webhook Secret (Optional)</label>
                                    <input type="password" class="form-control" name="stripe_webhook_secret" 
                                           value="{{ old('stripe_webhook_secret', $paymentSettings->stripe_webhook_secret ?? '') }}"
                                           placeholder="whsec_...">
                                    <small class="form-text text-muted">Webhook endpoint secret for Stripe events</small>
                                </div>
                            </div>

                            <!-- Authorize.net Settings -->
                            <div id="authorize-settings" class="payment-settings" style="display: none;">
                                <h5 class="mb-3"><i class="fa fa-shield-alt me-2"></i> Authorize.net Configuration</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label class="form-label">API Login ID</label>
                                            <input type="text" class="form-control" name="authorize_login_id" 
                                                   value="{{ old('authorize_login_id', $paymentSettings->authorize_login_id ?? '') }}"
                                                   placeholder="Your API Login ID">
                                            <small class="form-text text-muted">Authorize.net API Login ID</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label class="form-label">Transaction Key</label>
                                            <input type="password" class="form-control" name="authorize_transaction_key" 
                                                   value="{{ old('authorize_transaction_key', $paymentSettings->authorize_transaction_key ?? '') }}"
                                                   placeholder="Your Transaction Key">
                                            <small class="form-text text-muted">Authorize.net Transaction Key</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="authorize_sandbox" id="authorize_sandbox" value="1"
                                               {{ old('authorize_sandbox', $paymentSettings->authorize_sandbox ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="authorize_sandbox">
                                            Use Sandbox Mode (Test Environment)
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Coinbase Commerce Settings (Always Visible, Optional) -->
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h5 class="mb-1"><i class="fab fa-bitcoin me-2"></i> Coinbase Commerce (Optional)</h5>
                                        <small class="text-muted">Enable cryptocurrency payments alongside your primary gateway</small>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="coinbase_enabled" id="coinbase_enabled" value="1"
                                               {{ old('coinbase_enabled', $paymentSettings->coinbase_enabled ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="coinbase_enabled">
                                            <strong>Enable Coinbase</strong>
                                        </label>
                                    </div>
                                </div>

                                <div id="coinbase-settings-fields" style="display: {{ old('coinbase_enabled', $paymentSettings->coinbase_enabled ?? false) ? 'block' : 'none' }};">
                                    <div class="alert alert-info mb-3">
                                        <i class="bx bx-info-circle me-2"></i>
                                        <strong>Note:</strong> Coinbase payments work alongside your primary gateway. Users will have the option to pay with cryptocurrency (BTC, ETH, USDC, etc.) or credit card.
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="form-label">API Key <span class="text-danger" id="coinbase-required">*</span></label>
                                                <input type="password" class="form-control" name="coinbase_api_key" id="coinbase_api_key"
                                                       value="{{ old('coinbase_api_key', $paymentSettings->coinbase_api_key ?? '') }}"
                                                       placeholder="Your Coinbase Commerce API Key">
                                                <small class="form-text text-muted">Get this from Coinbase Commerce dashboard → Settings → API Keys</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="form-label">Webhook Secret (Optional)</label>
                                                <input type="password" class="form-control" name="coinbase_webhook_secret" 
                                                       value="{{ old('coinbase_webhook_secret', $paymentSettings->coinbase_webhook_secret ?? '') }}"
                                                       placeholder="Your Webhook Shared Secret">
                                                <small class="form-text text-muted">Get this from Coinbase Commerce → Settings → Webhook subscriptions</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="alert alert-success">
                                        <strong><i class="fa fa-check-circle me-1"></i> Webhook URL:</strong><br>
                                        <code>{{ url('/webhook/coinbase') }}</code><br>
                                        <small>Configure this URL in your Coinbase Commerce dashboard under Webhook subscriptions</small>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                                           {{ old('is_active', $paymentSettings->is_active ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        <strong>Enable payment processing for this website</strong>
                                    </label>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Tipping Option -->
                            <div class="form-group mb-4">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h5 class="mb-1"><i class="fa fa-gift me-2"></i> Tipping Option</h5>
                                        <p class="text-muted mb-0">Allow donors to add optional tips to their donations</p>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="tipping_enabled" id="tipping_enabled" value="1"
                                               {{ old('tipping_enabled', $paymentSettings->tipping_enabled ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="tipping_enabled">
                                            <strong>Enable Tips</strong>
                                        </label>
                                    </div>
                                </div>
                                <div class="alert alert-info mt-3">
                                    <i class="bx bx-info-circle me-2"></i>
                                    <small>When enabled, donors will see a tipping section on all checkout pages (Authorize.Net, Stripe) and QR donation pages. When disabled, the tipping option will be completely hidden from checkout pages.</small>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer d-flex flex-wrap gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save me-1"></i> Save Payment Settings
                            </button>
                            @if($paymentSettings)
                                <button type="button" class="btn btn-danger" onclick="deleteSettings()">
                                    <i class="fa fa-trash me-1"></i> Delete Settings
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="bx bx-help-circle me-2"></i>
                            Setup Guide
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="stripe-guide" class="setup-guide" style="display: none;">
                            <h6 class="mb-3"><i class="fab fa-stripe me-2"></i> Stripe Setup</h6>
                            <ol class="small">
                                <li class="mb-2">Log in to your <a href="https://dashboard.stripe.com" target="_blank">Stripe Dashboard</a></li>
                                <li class="mb-2">Go to Developers → API keys</li>
                                <li class="mb-2">Copy your Publishable key and Secret key</li>
                                <li class="mb-2">For webhooks, create an endpoint in Developers → Webhooks</li>
                            </ol>
                            <div class="alert alert-info">
                                <small><strong>Tip:</strong> Test keys start with "pk_test_" and "sk_test_", live keys start with "pk_live_" and "sk_live_"</small>
                            </div>
                        </div>

                        <div id="authorize-guide" class="setup-guide" style="display: none;">
                            <h6 class="mb-3"><i class="fa fa-shield-alt me-2"></i> Authorize.net Setup</h6>
                            <ol class="small">
                                <li class="mb-2">Log in to your <a href="https://account.authorize.net" target="_blank">Authorize.net Account</a></li>
                                <li class="mb-2">Go to Account → Settings → Security Settings → General Security Settings</li>
                                <li class="mb-2">Generate an API Login ID and Transaction Key</li>
                                <li class="mb-2">For production, uncheck "Sandbox Mode"</li>
                            </ol>
                            <div class="alert alert-warning">
                                <small><strong>Note:</strong> Always test in sandbox mode before going live</small>
                            </div>
                        </div>

                        <div id="coinbase-guide" class="setup-guide" style="display: none;">
                            <h6 class="mb-3"><i class="fab fa-bitcoin me-2"></i> Coinbase Commerce Setup</h6>
                            <ol class="small">
                                <li class="mb-2">Sign up at <a href="https://commerce.coinbase.com" target="_blank">Coinbase Commerce</a></li>
                                <li class="mb-2">Go to Settings → API Keys</li>
                                <li class="mb-2">Click "Create an API Key" and copy it</li>
                                <li class="mb-2">Go to Settings → Webhook subscriptions</li>
                                <li class="mb-2">Copy the "Webhook Shared Secret"</li>
                                <li class="mb-2">Add webhook endpoint: <code>{{ url('/webhook/coinbase') }}</code></li>
                            </ol>
                            <div class="alert alert-success">
                                <small><strong>Supported Currencies:</strong> Bitcoin (BTC), Ethereum (ETH), USDC, USDT, DAI, Litecoin (LTC)</small>
                            </div>
                        </div>
                    </div>
                </div>

                @if($paymentSettings)
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="bx bx-check-circle me-2"></i>
                            Connection Status
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="connection-status">
                            <p class="text-muted mb-0">Click "Test Connection" to verify your settings</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the payment settings for this website?</p>
                <p class="text-danger"><strong>This will disable payment processing until new settings are configured.</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ route('admin.websites.payment.destroy', $website) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Settings</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle payment method toggle
    const paymentMethodRadios = document.querySelectorAll('input[name="payment_method"]');
    const stripeSettings = document.getElementById('stripe-settings');
    const authorizeSettings = document.getElementById('authorize-settings');
    const stripeGuide = document.getElementById('stripe-guide');
    const authorizeGuide = document.getElementById('authorize-guide');
    const coinbaseGuide = document.getElementById('coinbase-guide');

    function toggleSettings() {
        const selectedMethod = document.querySelector('input[name="payment_method"]:checked').value;
        
        // Hide all settings
        stripeSettings.style.display = 'none';
        authorizeSettings.style.display = 'none';
        stripeGuide.style.display = 'none';
        authorizeGuide.style.display = 'none';
        
        // Show selected method settings and guide
        if (selectedMethod === 'stripe') {
            stripeSettings.style.display = 'block';
            stripeGuide.style.display = 'block';
        } else {
            authorizeSettings.style.display = 'block';
            authorizeGuide.style.display = 'block';
        }
    }

    paymentMethodRadios.forEach(radio => {
        radio.addEventListener('change', toggleSettings);
    });

    // Initialize on page load
    toggleSettings();

    // Handle coinbase enable/disable toggle
    const coinbaseEnabled = document.getElementById('coinbase_enabled');
    const coinbaseSettingsFields = document.getElementById('coinbase-settings-fields');
    const coinbaseApiKey = document.getElementById('coinbase_api_key');
    
    if (coinbaseEnabled && coinbaseSettingsFields) {
        coinbaseEnabled.addEventListener('change', function() {
            if (this.checked) {
                coinbaseSettingsFields.style.display = 'block';
                coinbaseGuide.style.display = 'block';
                if (coinbaseApiKey) {
                    coinbaseApiKey.setAttribute('required', 'required');
                }
            } else {
                coinbaseSettingsFields.style.display = 'none';
                coinbaseGuide.style.display = 'none';
                if (coinbaseApiKey) {
                    coinbaseApiKey.removeAttribute('required');
                }
            }
        });
        
        // Show coinbase guide if enabled on load
        if (coinbaseEnabled.checked && coinbaseGuide) {
            coinbaseGuide.style.display = 'block';
        }
    }

    // Handle fee calculation
    const feeInput = document.getElementById('fee');
    const feeDisplay = document.getElementById('feeDisplay');
    const feeAmount = document.getElementById('feeAmount');
    const totalAmount = document.getElementById('totalAmount');
    
    if (feeInput && feeDisplay && feeAmount && totalAmount) {
        feeInput.addEventListener('input', function() {
            const fee = parseFloat(this.value) || 0;
            const baseAmount = 100;
            const calculatedFee = (baseAmount * fee) / 100;
            const total = baseAmount + calculatedFee;
            
            feeDisplay.textContent = fee.toFixed(2);
            feeAmount.textContent = '$' + calculatedFee.toFixed(2);
            totalAmount.textContent = '$' + total.toFixed(2);
        });
    }
});

function testConnection() {
    const statusDiv = document.getElementById('connection-status');
    statusDiv.innerHTML = '<div class="spinner-border spinner-border-sm me-2" role="status"></div> Testing connection...';
    
    fetch(`{{ route('admin.websites.payment.test', $website) }}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            statusDiv.innerHTML = `
                <div class="alert alert-success mb-0">
                    <i class="fa fa-check-circle me-1"></i> ${data.message}
                    ${data.details && Object.keys(data.details).length > 0 ? 
                        '<br><small>' + Object.entries(data.details).map(([key, value]) => `${key}: ${value}`).join('<br>') + '</small>' 
                        : ''}
                </div>
            `;
        } else {
            statusDiv.innerHTML = `
                <div class="alert alert-danger mb-0">
                    <i class="fa fa-exclamation-triangle me-1"></i> ${data.message}
                </div>
            `;
        }
    })
    .catch(error => {
        statusDiv.innerHTML = `
            <div class="alert alert-danger mb-0">
                <i class="fa fa-exclamation-triangle me-1"></i> Error testing connection: ${error.message}
            </div>
        `;
    });
}

function deleteSettings() {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>

@endsection