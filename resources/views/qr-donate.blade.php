<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $website->name }} - Donate via QR</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2e4053;
            --accent-color: #28a745;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        
        .qr-container {
            max-width: 500px;
            margin: 0 auto;
        }
        
        .qr-header {
            background: white;
            border-radius: 20px 20px 0 0;
            padding: 30px 20px 20px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .qr-logo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
            border: 3px solid var(--primary-color);
        }
        
        .qr-title {
            font-size: 24px;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 5px;
        }
        
        .qr-type-badge {
            font-size: 14px;
            color: #666;
            margin-bottom: 15px;
        }
        
        .qr-body {
            background: white;
            padding: 25px 20px;
            border-radius: 0 0 20px 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .type-selector {
            margin-bottom: 25px;
        }
        
        .type-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            border-bottom: 2px solid #e0e0e0;
        }
        
        .type-tab {
            flex: 1;
            padding: 12px;
            border: none;
            background: none;
            font-size: 14px;
            font-weight: 600;
            color: #666;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
        }
        
        .type-tab.active {
            color: var(--accent-color);
            border-bottom-color: var(--accent-color);
        }
        
        .type-content {
            display: none;
        }
        
        .type-content.active {
            display: block;
        }
        
        .selection-list {
            max-height: 250px;
            overflow-y: auto;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .selection-item {
            padding: 12px 15px;
            border-bottom: 1px solid #f0f0f0;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
        }
        
        .selection-item:last-child {
            border-bottom: none;
        }
        
        .selection-item:hover {
            background-color: #f8f9fa;
            transform: translateX(2px);
        }
        
        .selection-item.selected {
            background-color: var(--accent-color);
            color: white;
        }
        
        .selection-item-radio {
            width: 20px;
            height: 20px;
            border: 2px solid #ccc;
            border-radius: 50%;
            margin-right: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        
        .selection-item.selected .selection-item-radio {
            border-color: white;
            background-color: white;
        }
        
        .selection-item.selected .selection-item-radio::after {
            content: '✓';
            color: var(--accent-color);
            font-weight: bold;
        }
        
        .selection-item-label {
            flex: 1;
        }
        
        .selection-item-sublabel {
            font-size: 12px;
            opacity: 0.8;
            margin-top: 2px;
        }
        
        .selection-item.selected .selection-item-sublabel {
            opacity: 0.9;
        }
        
        .amount-buttons {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .amount-btn {
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            background: white;
            font-size: 18px;
            font-weight: bold;
            color: var(--primary-color);
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .amount-btn:hover, .amount-btn.active {
            border-color: var(--accent-color);
            background: var(--accent-color);
            color: white;
            transform: scale(1.05);
        }
        
        .custom-amount {
            position: relative;
            margin-bottom: 20px;
        }
        
        .custom-amount input {
            font-size: 28px;
            padding: 15px 15px 15px 45px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            text-align: center;
        }
        
        .custom-amount .dollar-sign {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 28px;
            font-weight: bold;
            color: #666;
        }
        
        .form-control {
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            margin-bottom: 15px;
        }
        
        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }
        
        .donate-btn {
            width: 100%;
            padding: 18px;
            font-size: 20px;
            font-weight: bold;
            background: linear-gradient(135deg, var(--accent-color) 0%, #20c997 100%);
            border: none;
            border-radius: 15px;
            color: white;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.4);
            transition: all 0.3s;
        }
        
        .donate-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.6);
        }
        
        .donate-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        
        .secure-badge {
            text-align: center;
            margin-top: 15px;
            color: #666;
            font-size: 13px;
        }
        
        .secure-badge i {
            color: var(--accent-color);
            margin-right: 5px;
        }
        
        .form-check-input:checked {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }
        
        .qr-footer {
            text-align: center;
            padding: 20px;
            color: white;
            font-size: 12px;
        }

        .required-label {
            color: #dc3545;
        }

        .hidden-input {
            display: none;
        }

        .selection-required {
            display: none;
            color: #dc3545;
            font-size: 12px;
            margin-top: -10px;
            margin-bottom: 10px;
        }

        .selection-required.show {
            display: block;
        }
    </style>
</head>
<body>
    <div class="qr-container">
        <div class="qr-header">
            @if($website->logo ?? null)
                <img src="{{ asset('uploads/' . $website->logo) }}" alt="{{ $website->name }}" class="qr-logo">
            @else
                <div class="qr-logo" style="background: var(--primary-color); display: flex; align-items: center; justify-content: center; color: white; font-size: 32px;">
                    <i class="fas fa-heart"></i>
                </div>
            @endif
            <div class="qr-title">{{ $website->name }}</div>
            <div class="qr-type-badge">
                <i class="fas fa-mobile-alt"></i> Secure QR Donation
            </div>
        </div>
        
        <div class="qr-body">
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            <form action="{{ route('qr.donate.process') }}" method="POST" id="qrDonateForm">
                @csrf
                <input type="hidden" name="website_id" value="{{ $website->id }}">
                <input type="hidden" name="qr_identifier" value="{{ $qrIdentifier }}">
                <input type="hidden" name="type" id="typeInput" value="{{ $type }}">
                <input type="hidden" name="auction_id" id="auctionIdInput" class="hidden-input">
                <input type="hidden" name="ticket_id" id="ticketIdInput" class="hidden-input">
                <input type="hidden" name="student_id" id="studentIdInput" class="hidden-input">
                
                <!-- Type Selection -->
                <div class="type-selector">
                    <label class="form-label fw-semibold mb-2">
                        <i class="fas fa-cubes me-1"></i> What would you like to donate for? <span class="required-label">*</span>
                    </label>
                    <div class="type-tabs">
                        <button type="button" class="type-tab {{ $type === 'donation' ? 'active' : '' }}" data-type="donation">
                            <i class="fas fa-heart me-1"></i> Donation
                        </button>
                        <button type="button" class="type-tab {{ $type === 'auction' ? 'active' : '' }}" data-type="auction">
                            <i class="fas fa-gavel me-1"></i> Auction
                        </button>
                        <button type="button" class="type-tab {{ $type === 'sales' ? 'active' : '' }}" data-type="sales">
                            <i class="fas fa-ticket me-1"></i> Tickets
                        </button>
                    </div>
                </div>
                
                <!-- Donation Type Content -->
                <div class="type-content {{ $type === 'donation' ? 'active' : '' }}" data-type-content="donation">
                    <!-- Student Details (when selected) -->
                    <div id="studentDetailsSection" style="display: none;" class="mb-4 p-3 bg-light rounded">
                        <h6 class="fw-bold mb-2"><i class="fas fa-user me-2"></i> Beneficiary</h6>
                        <div id="studentDetails"></div>
                    </div>
                    
                    <div class="text-center mb-3">
                        <small class="text-muted">Choose an amount or enter your own</small>
                    </div>
                    
                    <div class="amount-buttons">
                        <button type="button" class="amount-btn" data-amount="25">$25</button>
                        <button type="button" class="amount-btn" data-amount="50">$50</button>
                        <button type="button" class="amount-btn" data-amount="100">$100</button>
                        <button type="button" class="amount-btn" data-amount="250">$250</button>
                        <button type="button" class="amount-btn" data-amount="500">$500</button>
                        <button type="button" class="amount-btn" data-amount="1000">$1K</button>
                    </div>
                    
                    <div class="custom-amount">
                        <span class="dollar-sign">$</span>
                        <input type="number" 
                               class="form-control" 
                               id="donationAmount" 
                               name="amount" 
                               placeholder="Enter Amount" 
                               step="0.01" 
                               min="1"
                               required>
                    </div>
                </div>
                
                <!-- Auction Type Content -->
                <div class="type-content {{ $type === 'auction' ? 'active' : '' }}" data-type-content="auction">
                    <label class="form-label fw-semibold mb-2">
                        <i class="fas fa-gavel me-1"></i> Select Auction <span class="required-label">*</span>
                    </label>
                    <div class="selection-required auction-required">Please select an auction</div>
                    <div class="selection-list" id="auctionList">
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-spinner fa-spin"></i> Loading auctions...
                        </div>
                    </div>
                    <input type="hidden" name="auction_id_temp" id="auctionSelected" value="{{ $selectedId ?? '' }}">
                    
                    <!-- Auction Details (when selected) -->
                    <div id="auctionDetailsSection" style="display: none;" class="mt-4 p-3 bg-light rounded">
                        <h6 class="fw-bold mb-2"><i class="fas fa-gavel me-2"></i> Auction Details</h6>
                        <div id="auctionDetails"></div>
                    </div>
                </div>
                
                <!-- Sales/Tickets Type Content -->
                <div class="type-content {{ $type === 'sales' ? 'active' : '' }}" data-type-content="sales">
                    <label class="form-label fw-semibold mb-2">
                        <i class="fas fa-ticket me-1"></i> Select Ticket <span class="required-label">*</span>
                    </label>
                    <div class="selection-required sales-required">Please select a ticket</div>
                    <div class="selection-list" id="ticketList">
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-spinner fa-spin"></i> Loading tickets...
                        </div>
                    </div>
                    <input type="hidden" name="ticket_id_temp" id="ticketSelected" value="{{ $selectedId ?? '' }}">
                </div>

                <!-- Student/Donation Beneficiary Content -->
                <div class="type-content {{ $type === 'donation' ? 'active' : '' }}" data-type-content="donation-student" style="display: none;">
                    <label class="form-label fw-semibold mb-2">
                        <i class="fas fa-user me-1"></i> Select Student Beneficiary <span class="required-label">*</span>
                    </label>
                    <div class="selection-required donation-student-required">Please select a student</div>
                    <div class="selection-list" id="studentList">
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-spinner fa-spin"></i> Loading participants...
                        </div>
                    </div>
                    <input type="hidden" name="student_id_temp" id="studentSelected" value="">
                </div>
                
                <!-- Personal Information -->
                <label class="form-label fw-semibold mb-3 mt-4">
                    <i class="fas fa-user-circle me-1"></i> Your Information <span class="required-label">*</span>
                </label>
                <div class="row g-2">
                    <div class="col-6">
                        <input type="text" 
                               class="form-control" 
                               name="first_name" 
                               placeholder="First Name"
                               value="{{ old('first_name') }}"
                               required>
                    </div>
                    <div class="col-6">
                        <input type="text" 
                               class="form-control" 
                               name="last_name" 
                               placeholder="Last Name"
                               value="{{ old('last_name') }}"
                               required>
                    </div>
                </div>
                
                <input type="email" 
                       class="form-control" 
                       name="email" 
                       placeholder="Email Address"
                       value="{{ old('email') }}"
                       required>
                
                <input type="tel" 
                       class="form-control" 
                       name="phone" 
                       placeholder="Phone Number (Optional)"
                       value="{{ old('phone') }}">
                
                <!-- Optional Comment -->
                <textarea class="form-control" 
                          name="comment" 
                          rows="2" 
                          placeholder="Leave a message (Optional)"></textarea>
                
                <!-- Anonymous Option -->
                <div class="form-check mb-3">
                    <input class="form-check-input" 
                           type="checkbox" 
                           id="anonymous" 
                           name="anonymous_donation">
                    <label class="form-check-label" for="anonymous">
                        Make this donation anonymous
                    </label>
                </div>
                
                <!-- Tipping Component (Donation Type Only) -->
                <div id="tippingContainer" style="display: {{ $website->paymentSettings?->tipping_enabled ?? true ? 'none' : 'none' }};" class="mt-4">
                    @if ($website->paymentSettings?->tipping_enabled ?? true)
                    @include('components.tipping', [
                        'baseAmount' => 25,
                        'primaryColor' => '#28a745',
                        'processingFee' => 2.9
                    ])
                    @endif
                </div>
                
                <!-- Payment Section -->
                <div class="payment-section mt-4 p-3 bg-light rounded">
                    <h5 class="fw-bold mb-3"><i class="fas fa-credit-card me-2"></i> Payment Details</h5>
                    <p class="text-muted small mb-3">All transactions are secure and encrypted.</p>
                    
                    <!-- Card Icons Header -->
                    <div class="card-icons-header p-3 mb-3" style="border: 1px solid #1773b0; border-radius: 10px; background: #f0f5ff;">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0" style="font-size: 0.95rem;">Credit Card</h5>
                            <div class="d-flex gap-2">
                                <img alt="VISA" src="https://cdn.shopify.com/shopifycloud/checkout-web/assets/c1.en/assets/visa.sxIq5Dot.svg" width="38" height="24">
                                <img alt="MASTERCARD" src="https://cdn.shopify.com/shopifycloud/checkout-web/assets/c1.en/assets/mastercard.1c4_lyMp.svg" width="38" height="24">
                                <img alt="AMEX" src="https://cdn.shopify.com/shopifycloud/checkout-web/assets/c1.en/assets/amex.Csr7hRoy.svg" width="38" height="24">
                                <img alt="DISCOVER" src="https://cdn.shopify.com/shopifycloud/checkout-web/assets/c1.en/assets/discover.C7UbFpNb.svg" width="38" height="24">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Card Number -->
                    <div class="mb-3 position-relative">
                        <input type="text" 
                               class="form-control" 
                               name="card_number" 
                               id="card_number"
                               placeholder="Card number" 
                               maxlength="19"
                               required>
                        <i class="fa fa-lock position-absolute" style="right: 15px; top: 50%; transform: translateY(-50%); color: #888;"></i>
                    </div>
                    
                    <!-- Expiry and CVV -->
                    <div class="row g-2 mb-3">
                        <div class="col-8">
                            <input type="text" 
                                   class="form-control" 
                                   name="expiration_date" 
                                   id="expiration_date"
                                   placeholder="Expiration date (MM / YY)" 
                                   maxlength="7"
                                   required
                                   oninput="formatExpiryDate(this)">
                        </div>
                        <div class="col-4 position-relative">
                            <input type="text" 
                                   class="form-control" 
                                   name="cvv" 
                                   id="cvv"
                                   placeholder="Security code" 
                                   maxlength="4"
                                   required>
                            <i class="fa fa-question-circle position-absolute" style="right: 13px; top: 50%; transform: translateY(-50%); color: #888; cursor: help;" 
                               data-bs-toggle="tooltip" 
                               title="3-digit security code usually found on the back of your card. American Express cards have a 4-digit code located on the front."></i>
                        </div>
                    </div>
                    
                    <!-- Name on Card -->
                    <div class="mb-3 position-relative">
                        <input type="text" 
                               class="form-control" 
                               name="name_on_card" 
                               id="name_on_card"
                               placeholder="Name on card" 
                               required>
                    </div>
                    
                    <!-- Billing Address -->
                    <h6 class="fw-bold mb-3 mt-4">Billing Address</h6>
                    
                    <!-- Country -->
                    <div class="mb-3">
                        <select class="form-select" name="billing_country" id="billing_country" required>
                            <option value="" disabled selected hidden>Country/Region</option>
                        </select>
                    </div>
                    
                    <!-- First and Last Name -->
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <input type="text" 
                                   class="form-control" 
                                   name="billing_first_name" 
                                   id="billing_first_name"
                                   placeholder="First name" 
                                   required>
                        </div>
                        <div class="col-6">
                            <input type="text" 
                                   class="form-control" 
                                   name="billing_last_name" 
                                   id="billing_last_name"
                                   placeholder="Last name" 
                                   required>
                        </div>
                    </div>
                    
                    <!-- Email -->
                    <div class="mb-3">
                        <input type="email" 
                               class="form-control" 
                               name="billing_email" 
                               id="billing_email"
                               placeholder="Email" 
                               required>
                    </div>
                    
                    <!-- Address -->
                    <div class="mb-3 position-relative">
                        <input type="text" 
                               class="form-control" 
                               name="billing_address" 
                               id="billing_address"
                               placeholder="Address" 
                               required>
                        <i class="fa fa-search position-absolute" style="right: 15px; top: 50%; transform: translateY(-50%); color: #888;"></i>
                    </div>
                    
                    <!-- Apartment/Suite -->
                    <div class="mb-3">
                        <input type="text" 
                               class="form-control" 
                               name="billing_apartment" 
                               id="billing_apartment"
                               placeholder="Apartment, suite, etc. (optional)">
                    </div>
                    
                    <!-- City, State, ZIP -->
                    <div class="row g-2 mb-3">
                        <div class="col-md-4">
                            <input type="text" 
                                   class="form-control" 
                                   name="billing_city" 
                                   id="billing_city"
                                   placeholder="City" 
                                   required>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select" name="billing_state" id="billing_state" required>
                                <option value="" disabled selected hidden>State/Province</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" 
                                   class="form-control" 
                                   name="billing_zipcode" 
                                   id="billing_zipcode"
                                   placeholder="ZIP code" 
                                   required>
                        </div>
                    </div>
                    
                    <!-- Phone -->
                    <div class="mb-3 position-relative">
                        <input type="tel" 
                               class="form-control" 
                               name="billing_phone" 
                               id="billing_phone"
                               placeholder="Phone" 
                               required>
                        <i class="fa fa-question-circle position-absolute" style="right: 15px; top: 50%; transform: translateY(-50%); color: #888; cursor: help;" 
                           data-bs-toggle="tooltip" 
                           title="In case we need to contact you about your order"></i>
                    </div>
                </div>
                
                <!-- Order Summary -->
                <div class="order-summary mt-4 p-3 bg-light rounded">
                    <h6 class="fw-bold mb-3"><i class="fas fa-receipt me-2"></i> Order Summary</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span id="orderSummaryAmount" class="fw-bold">$0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Platform Fee:</span>
                        <span id="orderSummaryFee" class="fw-bold">$0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3" id="orderSummaryTipRow" style="display: none;">
                        <span>Tip:</span>
                        <span id="orderSummaryTip" class="fw-bold">$0.00</span>
                    </div>
                    <hr/>
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold">Total:</span>
                        <span id="orderSummaryTotal" class="fw-bold" style="font-size: 18px; color: var(--accent-color);">$0.00</span>
                    </div>
                </div>
                
                <!-- Payment Options -->
                <div class="payment-options mt-4">
                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold mb-2" name="payment_method" value="authorize_net">
                        <i class="fas fa-lock me-2"></i> Pay with Card
                    </button>
                    
                    <button type="button" class="btn btn-outline-warning w-100 py-3 fw-bold" onclick="handleCryptoPayment()">
                        <i class="fab fa-bitcoin me-2"></i> Pay with Crypto
                    </button>
                </div>
                
                <!-- Policy Links -->
                {{-- <div class="policy-links text-center mt-4">
                    <p style="font-size: 0.85rem;">
                        <a href="#" style="color: #1773b0; text-decoration: underline;">Refund policy</a> • 
                        <a href="#" style="color: #1773b0; text-decoration: underline;">Privacy</a> • 
                        <a href="#" style="color: #1773b0; text-decoration: underline;">Terms</a>
                    </p>
                </div> --}}
                
                <div class="secure-badge mt-3">
                    <i class="fas fa-lock"></i> Secure payment via Authorize.Net
                </div>
            </form>
        </div>
        
        <div class="qr-footer">
            Powered by {{ config('app.name') }} • Tax-deductible donation
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Payment Funnel Tracking -->
    <script src="{{ asset('js/payment-funnel-tracking.js') }}"></script>
    
    <script>
        const websiteId = {{ $website->id }};
        const currentType = '{{ $type }}';
        const selectedIdFromUrl = '{{ $selectedId ?? "" }}';
        const isQRScanned = selectedIdFromUrl !== ''; // True if QR was scanned with pre-selection
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // If QR was scanned, hide type tabs and show only selected content
            if (isQRScanned) {
                hideTypeSelectionTabs();
            }
            
            setupTypeSelection();
            loadDataForType(currentType);
            setupAmountButtons();
            
            // Show tipping component for donation type
            if (currentType === 'donation') {
                showTippingComponent();
            }
            
            // Auto-select 10% tip for donations
            if (currentType === 'donation') {
                autoSelectTip();
                // Call updateOrderSummary after tip is selected AND rendered
                setTimeout(function() {
                    updateOrderSummary();
                }, 800);
            }
            
            // Setup card number formatting
            const cardNumberInput = document.getElementById('card_number');
            if (cardNumberInput) {
                cardNumberInput.addEventListener('input', formatCardNumber);
            }

            trackFormView();
        });

        // Hide type selection tabs when QR code pre-selected item
        function hideTypeSelectionTabs() {
            const typeSelector = document.querySelector('.type-selector');
            const typeTabs = document.querySelector('.type-tabs');
            
            if (typeTabs) {
                typeTabs.style.display = 'none';
            }
            
            // Add info banner instead
            if (typeSelector && !document.getElementById('qrModeInfo')) {
                const infoBanner = document.createElement('div');
                infoBanner.id = 'qrModeInfo';
                infoBanner.className = 'alert alert-info mb-3';
                infoBanner.innerHTML = `<i class="fas fa-qrcode me-2"></i> <strong>QR Mode:</strong> Showing selected ${currentType}`;
                typeSelector.insertBefore(infoBanner, typeSelector.firstChild);
            }
        }

        // Setup type selection tabs
        function setupTypeSelection() {
            document.querySelectorAll('.type-tab').forEach(tab => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();
                    // Don't allow type switching if QR scanned
                    if (isQRScanned) {
                        return;
                    }
                    const newType = this.getAttribute('data-type');
                    switchType(newType);
                });
            });
        }

        // Auto-select 10% tip
        function autoSelectTip() {
            setTimeout(() => {
                // Find 10% button in tipping component
                const tipButton = document.querySelector('.tip-btn[data-percentage="10"]');
                if (tipButton) {
                    tipButton.click();
                    // Ensure tip change listener is set up
                    setupTipChangeListener();
                }
            }, 300);
        }

        // Setup listener for tip changes
        function setupTipChangeListener() {
            setTimeout(() => {
                const tipAmountInput = document.querySelector('[name="tip_amount"]');
                if (tipAmountInput && !tipAmountInput._hasListener) {
                    tipAmountInput._hasListener = true;
                    tipAmountInput.addEventListener('input', updateOrderSummary);
                    tipAmountInput.addEventListener('change', updateOrderSummary);
                }
                setupTipButtonListeners();
            }, 100);
        }

        // Ensure tip buttons trigger summary recalculation
        function setupTipButtonListeners() {
            document.querySelectorAll('.tip-btn').forEach(btn => {
                if (!btn._hasListener) {
                    btn._hasListener = true;
                    btn.addEventListener('click', () => {
                        setTimeout(updateOrderSummary, 150);
                    });
                }
            });
        }

        // Switch between types
        function switchType(type) {
            // Update hidden input
            document.getElementById('typeInput').value = type;
            
            // Update active tab
            document.querySelectorAll('.type-tab').forEach(tab => {
                tab.classList.remove('active');
                if (tab.getAttribute('data-type') === type) {
                    tab.classList.add('active');
                }
            });
            
            // Update content visibility
            document.querySelectorAll('.type-content').forEach(content => {
                content.classList.remove('active');
            });
            document.querySelector(`[data-type-content="${type}"]`).classList.add('active');
            
            // Show student picker for donation type
            if (type === 'donation') {
                document.querySelector('[data-type-content="donation-student"]').style.display = 'block';
                loadStudentsForWebsite();
                showTippingComponent();
            } else {
                document.querySelector('[data-type-content="donation-student"]').style.display = 'none';
                hideTippingComponent();
            }
            
            // Load data for new type
            loadDataForType(type);
            
            // Clear previous selections
            clearSelections();
        }

        // Load data based on type
        function loadDataForType(type) {
            if (type === 'auction') {
                loadAuctionsForWebsite();
            } else if (type === 'sales') {
                loadTicketsForWebsite();
            } else if (type === 'donation') {
                loadStudentsForWebsite();
            }
        }

        // Load auctions
        function loadAuctionsForWebsite() {
            // This would be an AJAX call to get auctions
            // For now, we'll assume the frontend handles this
            fetch(`/api/auctions?website_id=${websiteId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.auctions) {
                        renderAuctionList(data.auctions);
                    }
                })
                .catch(error => {
                    console.error('Error loading auctions:', error);
                    document.getElementById('auctionList').innerHTML = 
                        '<div class="text-center py-5 text-danger"><i class="fas fa-exclamation"></i> Failed to load auctions</div>';
                });
        }

        // Load tickets
        function loadTicketsForWebsite() {
            fetch(`/api/tickets?website_id=${websiteId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.tickets) {
                        renderTicketList(data.tickets);
                    }
                })
                .catch(error => {
                    console.error('Error loading tickets:', error);
                    document.getElementById('ticketList').innerHTML = 
                        '<div class="text-center py-5 text-danger"><i class="fas fa-exclamation"></i> Failed to load tickets</div>';
                });
        }

        // Load students
        function loadStudentsForWebsite() {
            fetch(`/api/students?website_id=${websiteId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.students) {
                        renderStudentList(data.students);
                    }
                })
                .catch(error => {
                    console.error('Error loading students:', error);
                    document.getElementById('studentList').innerHTML = 
                        '<div class="text-center py-5 text-danger"><i class="fas fa-exclamation"></i> Failed to load students</div>';
                });
        }

        // Render auction list
        function renderAuctionList(auctions) {
            const list = document.getElementById('auctionList');
            if (auctions.length === 0) {
                list.innerHTML = '<div class="text-center py-5 text-muted"><i class="fas fa-inbox"></i> No auctions available</div>';
                return;
            }
            
            // If QR scanned, show only selected auction (no clickable list)
            if (isQRScanned && selectedIdFromUrl) {
                const selected = auctions.find(a => a.id == selectedIdFromUrl);
                if (selected) {
                    list.innerHTML = `
                        <div class="selection-item selected" style="cursor: default;">
                            <div class="selection-item-radio"></div>
                            <div class="selection-item-label">
                                <div>${selected.title}</div>
                                <div class="selection-item-sublabel">Value: $${parseFloat(selected.value).toFixed(2)}</div>
                            </div>
                        </div>
                    `;
                    document.getElementById('auctionIdInput').value = selected.id;
                    displayAuctionDetails(selected);
                    return;
                }
            }
            
            list.innerHTML = auctions.map(auction => `
                <div class="selection-item ${selectedIdFromUrl == auction.id ? 'selected' : ''}" data-id="${auction.id}">
                    <div class="selection-item-radio"></div>
                    <div class="selection-item-label">
                        <div>${auction.title}</div>
                        <div class="selection-item-sublabel">Value: $${parseFloat(auction.value).toFixed(2)}</div>
                    </div>
                </div>
            `).join('');
            
            attachSelectionListeners('auction');
        }

        // Render ticket list
        function renderTicketList(tickets) {
            const list = document.getElementById('ticketList');
            if (tickets.length === 0) {
                list.innerHTML = '<div class="text-center py-5 text-muted"><i class="fas fa-inbox"></i> No tickets available</div>';
                return;
            }
            
            // If QR scanned, show only selected ticket (no clickable list)
            if (isQRScanned && selectedIdFromUrl) {
                const selected = tickets.find(t => t.id == selectedIdFromUrl);
                if (selected) {
                    list.innerHTML = `
                        <div class="selection-item selected" style="cursor: default;">
                            <div class="selection-item-radio"></div>
                            <div class="selection-item-label">
                                <div>${selected.name}</div>
                                <div class="selection-item-sublabel">${selected.category_name || 'Ticket'} • $${parseFloat(selected.price).toFixed(2)}</div>
                            </div>
                        </div>
                    `;
                    document.getElementById('ticketIdInput').value = selected.id;
                    displayTicketDetails(selected);
                    return;
                }
            }
            
            list.innerHTML = tickets.map(ticket => `
                <div class="selection-item ${selectedIdFromUrl == ticket.id ? 'selected' : ''}" data-id="${ticket.id}">
                    <div class="selection-item-radio"></div>
                    <div class="selection-item-label">
                        <div>${ticket.name}</div>
                        <div class="selection-item-sublabel">${ticket.category_name || 'Ticket'} • $${parseFloat(ticket.price).toFixed(2)}</div>
                    </div>
                </div>
            `).join('');
            
            attachSelectionListeners('sales');
        }

        // Render student list
        function renderStudentList(students) {
            const list = document.getElementById('studentList');
            if (students.length === 0) {
                list.innerHTML = '<div class="text-center py-5 text-muted"><i class="fas fa-inbox"></i> No students available</div>';
                return;
            }
            
            // If QR scanned, show only selected student (no clickable list)
            if (isQRScanned && selectedIdFromUrl) {
                const selected = students.find(s => s.id == selectedIdFromUrl);
                if (selected) {
                    list.innerHTML = `
                        <div class="selection-item selected" style="cursor: default;">
                            <div class="selection-item-radio"></div>
                            <div class="selection-item-label">
                                <div>${selected.name} ${selected.last_name}</div>
                                <div class="selection-item-sublabel">${selected.email}</div>
                            </div>
                        </div>
                    `;
                    document.getElementById('studentIdInput').value = selected.id;
                    displayStudentDetails(selected);
                    return;
                }
            }
            
            list.innerHTML = students.map(student => `
                <div class="selection-item ${selectedIdFromUrl == student.id ? 'selected' : ''}" data-id="${student.id}">
                    <div class="selection-item-radio"></div>
                    <div class="selection-item-label">
                        <div>${student.name} ${student.last_name}</div>
                        <div class="selection-item-sublabel">${student.email}</div>
                    </div>
                </div>
            `).join('');
            
            attachSelectionListeners('donation');
        }

        // Display auction details
        function displayAuctionDetails(auction) {
            const section = document.getElementById('auctionDetailsSection');
            const details = document.getElementById('auctionDetails');
            details.innerHTML = `
                <p class="mb-2"><strong>${auction.title}</strong></p>
                <p class="text-muted small mb-0">Starting Value: <strong>$${parseFloat(auction.value).toFixed(2)}</strong></p>
            `;
            section.style.display = 'block';
        }

        // Display ticket details
        function displayTicketDetails(ticket) {
            const details = document.getElementById('ticketList');
            // Store ticket price for checkout
            document.getElementById('ticketIdInput').dataset.price = ticket.price;
            updateOrderSummary();
        }

        // Display student details
        function displayStudentDetails(student) {
            const section = document.getElementById('studentDetailsSection');
            const details = document.getElementById('studentDetails');
            details.innerHTML = `
                <p class="mb-1"><strong>${student.name} ${student.last_name}</strong></p>
                <p class="text-muted small mb-0">${student.email}</p>
            `;
            section.style.display = 'block';
        }

        // Attach selection listeners
        function attachSelectionListeners(type) {
            let listContainer, inputElement, requiredElement;
            
            if (type === 'auction') {
                listContainer = document.getElementById('auctionList');
                inputElement = document.getElementById('auctionIdInput');
                requiredElement = document.querySelector('.auction-required');
            } else if (type === 'sales') {
                listContainer = document.getElementById('ticketList');
                inputElement = document.getElementById('ticketIdInput');
                requiredElement = document.querySelector('.sales-required');
            } else if (type === 'donation') {
                listContainer = document.getElementById('studentList');
                inputElement = document.getElementById('studentIdInput');
                requiredElement = document.querySelector('.donation-student-required');
            }
            
            // If QR scanned, don't attach click listeners (make items non-selectable)
            if (isQRScanned) {
                return;
            }
            
            listContainer.querySelectorAll('.selection-item').forEach(item => {
                item.addEventListener('click', function() {
                    // Remove previous selection
                    listContainer.querySelectorAll('.selection-item').forEach(i => i.classList.remove('selected'));
                    
                    // Add selection to clicked item
                    this.classList.add('selected');
                    
                    // Update hidden input
                    const id = this.getAttribute('data-id');
                    inputElement.value = id;
                    
                    // Hide required message
                    if (requiredElement) {
                        requiredElement.classList.remove('show');
                    }
                });
                
                // Auto-select if matching URL parameter
                if (selectedIdFromUrl && item.getAttribute('data-id') == selectedIdFromUrl) {
                    item.classList.add('selected');
                    inputElement.value = selectedIdFromUrl;
                    if (requiredElement) {
                        requiredElement.classList.remove('show');
                    }
                }
            });
        }

        // Amount button selection
        function setupAmountButtons() {
            document.querySelectorAll('.amount-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.querySelectorAll('.amount-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    const amount = this.getAttribute('data-amount');
                    const finalAmount = amount === '1000' ? 1000 : parseInt(amount);
                    document.getElementById('donationAmount').value = finalAmount;
                    updateOrderSummary();
                });
            });
            
            document.getElementById('donationAmount').addEventListener('input', function() {
                document.querySelectorAll('.amount-btn').forEach(b => b.classList.remove('active'));
                updateOrderSummary();
            });
        }

        // Update order summary with amount and tip
        function updateOrderSummary() {
            const amount = parseFloat(document.getElementById('donationAmount').value) || 0;
            console.log('[Order Summary] Base amount:', amount);
            
            // Update tipping component's base amount FIRST (this updates the tip calculation)
            if (typeof updateBaseAmount === 'function') {
                updateBaseAmount(amount);
            }
            
            // WAIT for the tipping component to finish updating
            setTimeout(() => {
                // Read the tip amount - prioritize currentTipAmount global variable
                let tipAmount = 0;
                
                console.log('[Order Summary] currentTipAmount:', typeof currentTipAmount !== 'undefined' ? currentTipAmount : 'undefined');
                console.log('[Order Summary] currentTipPercentage:', typeof currentTipPercentage !== 'undefined' ? currentTipPercentage : 'undefined');
                
                if (typeof currentTipAmount !== 'undefined' && currentTipAmount > 0) {
                    tipAmount = currentTipAmount;
                } else {
                    // Fallback: read from input field
                    const tipInput = document.querySelector('[name="tip_amount"]');
                    console.log('[Order Summary] Tip input field value:', tipInput ? tipInput.value : 'not found');
                    if (tipInput && tipInput.value) {
                        tipAmount = parseFloat(tipInput.value) || 0;
                    }
                }
                
                // Calculate Platform Fee
                const processingFeePercent = {{ $paymentFee ?? 2.9 }};
                const processingFee = (amount / 100) * processingFeePercent;
                const total = amount + processingFee + tipAmount;
                
                console.log('[Order Summary] Amount:', amount, '| Fee:', processingFee, '| Tip:', tipAmount, '| Total:', total);
                
                // Update ORDER summary display (not tipping component summary)
                const summaryAmount = document.getElementById('orderSummaryAmount');
                const summaryFee = document.getElementById('orderSummaryFee');
                const summaryTotal = document.getElementById('orderSummaryTotal');
                const summaryTipRow = document.getElementById('orderSummaryTipRow');
                const summaryTip = document.getElementById('orderSummaryTip');
                
                if (summaryAmount) summaryAmount.textContent = '$' + amount.toFixed(2);
                if (summaryFee) summaryFee.textContent = '$' + processingFee.toFixed(2);
                if (summaryTotal) summaryTotal.textContent = '$' + total.toFixed(2);
                
                // Show tip row if donation type and tip is selected
                if (currentType === 'donation' && tipAmount > 0) {
                    if (summaryTipRow) summaryTipRow.style.display = 'flex';
                    if (summaryTip) summaryTip.textContent = '$' + tipAmount.toFixed(2);
                } else {
                    if (summaryTipRow) summaryTipRow.style.display = 'none';
                }
            }, 200); // Wait 200ms for tipping component to update
        }

        // Show tipping component
        function showTippingComponent() {
            document.getElementById('tippingContainer').style.display = 'block';
            // Listen for tip changes to update summary
            setTimeout(() => {
                const tipInput = document.querySelector('[name="tip_amount"]');
                if (tipInput) {
                    tipInput.addEventListener('change', updateOrderSummary);
                }
            }, 500);
        }

        // Hide tipping component
        function hideTippingComponent() {
            document.getElementById('tippingContainer').style.display = 'none';
        }

        // Clear selections
        function clearSelections() {
            document.getElementById('auctionIdInput').value = '';
            document.getElementById('ticketIdInput').value = '';
            document.getElementById('studentIdInput').value = '';
        }

        // Track form view
        function trackFormView() {
            if (typeof window.trackFunnelEvent === 'function') {
                window.trackFunnelEvent('form_view', {
                    form_type: 'qr_donation',
                    source: 'qr_code',
                    type: currentType
                });
            }
        }

        // Handle Crypto Payment
        function handleCryptoPayment() {
            alert('Crypto payment functionality will be available soon. Please use Card payment for now.');
        }

        // Format card number with spaces every 4 digits
        function formatCardNumber(input) {
            let value = input.value.replace(/\s/g, '');
            let formatted = value.match(/.{1,4}/g);
            input.value = formatted ? formatted.join(' ') : value;
        }

        // Format expiry date to MM / YY
        function formatExpiryDate(input) {
            let value = input.value.replace(/\s/g, '').replace(/\//g, '');
            if (value.length >= 2) {
                value = value.slice(0, 2) + ' / ' + value.slice(2, 4);
            }
            input.value = value;
        }

        // Populate hidden payment fields on form submit
        function populatePaymentFields() {
            const type = document.getElementById('typeInput').value;
            const amount = parseFloat(document.getElementById('donationAmount').value) || 0;
            
            // Get tip values
            const tipInput = document.querySelector('[name="tip_amount"]');
            const tipPercInput = document.querySelector('[name="tip_percentage"]');
            const tipEnabledInput = document.querySelector('[name="tip_enabled"]');
            
            // Get selected item IDs
            if (type === 'donation') {
                const studentId = document.getElementById('studentIdInput').value || '';
                // Can set hidden fields here if needed for additional processing
            } else if (type === 'auction') {
                const auctionId = document.getElementById('auctionIdInput').value || '';
                // Can set hidden fields here if needed for additional processing
            } else if (type === 'sales') {
                const ticketId = document.getElementById('ticketIdInput').value || '';
                // Can set hidden fields here if needed for additional processing
            }
        }

        // Form submission
        document.getElementById('qrDonateForm').addEventListener('submit', function(e) {
            const type = document.getElementById('typeInput').value;
            let isValid = true;
            let errorMsg = '';
            
            // Validate based on type
            if (type === 'donation') {
                const amount = document.getElementById('donationAmount').value;
                const studentId = document.getElementById('studentIdInput').value;
                if (!amount) {
                    isValid = false;
                    errorMsg = 'Please enter a donation amount';
                }
                if (!studentId) {
                    isValid = false;
                    document.querySelector('.donation-student-required').classList.add('show');
                    errorMsg = 'Please select a student beneficiary';
                }
            } else if (type === 'auction') {
                const auctionId = document.getElementById('auctionIdInput').value;
                if (!auctionId) {
                    isValid = false;
                    document.querySelector('.auction-required').classList.add('show');
                    errorMsg = 'Please select an auction';
                }
            } else if (type === 'sales') {
                const ticketId = document.getElementById('ticketIdInput').value;
                if (!ticketId) {
                    isValid = false;
                    document.querySelector('.sales-required').classList.add('show');
                    errorMsg = 'Please select a ticket';
                }
            }
            
            // Validate payment fields
            if (isValid) {
                const cardNumber = document.getElementById('card_number').value;
                const expiryDate = document.getElementById('expiration_date').value;
                const cvv = document.getElementById('cvv').value;
                const nameOnCard = document.getElementById('name_on_card').value;
                const billingAddress = document.getElementById('billing_address').value;
                const billingCity = document.getElementById('billing_city').value;
                const billingState = document.getElementById('billing_state').value;
                const billingZip = document.getElementById('billing_zipcode').value;
                
                if (!cardNumber || cardNumber.replace(/\s/g, '').length < 13) {
                    isValid = false;
                    errorMsg = 'Please enter a valid card number';
                }
                if (!expiryDate || !expiryDate.includes('/')) {
                    isValid = false;
                    errorMsg = 'Please enter a valid expiration date (MM / YY)';
                }
                if (!cvv || cvv.length < 3) {
                    isValid = false;
                    errorMsg = 'Please enter a valid security code';
                }
                if (!nameOnCard) {
                    isValid = false;
                    errorMsg = 'Please enter name on card';
                }
                if (!billingAddress) {
                    isValid = false;
                    errorMsg = 'Please enter billing address';
                }
                if (!billingCity) {
                    isValid = false;
                    errorMsg = 'Please enter billing city';
                }
                if (!billingState) {
                    isValid = false;
                    errorMsg = 'Please select billing state';
                }
                if (!billingZip) {
                    isValid = false;
                    errorMsg = 'Please enter billing ZIP code';
                }
            }
            
            if (!isValid) {
                e.preventDefault();
                if (errorMsg) {
                    alert(errorMsg);
                }
            } else {
                populatePaymentFields();
            }
        });

        // ============== Country and State Dropdowns ==============
        const countryStateData = {
            "United States": [
                "Alabama","Alaska","Arizona","Arkansas","California","Colorado","Connecticut","Delaware","Florida","Georgia","Hawaii","Idaho","Illinois","Indiana","Iowa","Kansas","Kentucky","Louisiana","Maine","Maryland","Massachusetts","Michigan","Minnesota","Mississippi","Missouri","Montana","Nebraska","Nevada","New Hampshire","New Jersey","New Mexico","New York","North Carolina","North Dakota","Ohio","Oklahoma","Oregon","Pennsylvania","Rhode Island","South Carolina","South Dakota","Tennessee","Texas","Utah","Vermont","Virginia","Washington","West Virginia","Wisconsin","Wyoming"
            ].sort(),
            "Canada": [
                "Alberta","British Columbia","Manitoba","New Brunswick","Newfoundland and Labrador","Northwest Territories","Nova Scotia","Nunavut","Ontario","Prince Edward Island","Quebec","Saskatchewan","Yukon"
            ].sort(),
            "Australia": [
                "Australian Capital Territory","New South Wales","Northern Territory","Queensland","South Australia","Tasmania","Victoria","Western Australia"
            ].sort(),
            "India": [
                "Andaman and Nicobar Islands","Andhra Pradesh","Arunachal Pradesh","Assam","Bihar","Chandigarh","Chhattisgarh","Dadra and Nagar Haveli and Daman and Diu","Delhi","Goa","Gujarat","Haryana","Himachal Pradesh","Jammu and Kashmir","Jharkhand","Karnataka","Kerala","Ladakh","Lakshadweep","Madhya Pradesh","Maharashtra","Manipur","Meghalaya","Mizoram","Nagaland","Odisha","Puducherry","Punjab","Rajasthan","Sikkim","Tamil Nadu","Telangana","Tripura","Uttar Pradesh","Uttarakhand","West Bengal"
            ].sort(),
            "United Kingdom": ["England","Scotland","Wales","Northern Ireland"],
            "Germany": ["Baden-Württemberg","Bavaria","Berlin","Brandenburg","Bremen","Hamburg","Hesse","Lower Saxony","Mecklenburg-Vorpommern","North Rhine-Westphalia","Rhineland-Palatinate","Saarland","Saxony","Saxony-Anhalt","Schleswig-Holstein","Thuringia"],
            "France": ["Auvergne-Rhône-Alpes","Bourgogne-Franche-Comté","Brittany","Centre-Val de Loire","Corsica","Grand Est","Guadeloupe","Guyana","Hauts-de-France","Île-de-France","La Réunion","Martinique","Mayotte","Normandy","Nouvelle-Aquitaine","Occitanie","Pays de la Loire","Provence-Alpes-Côte d'Azur"]
        };
        
        const countryList = Object.keys(countryStateData).concat(["Spain","Italy","Japan","South Korea","Mexico","Other"]).filter((v, i, a) => a.indexOf(v) === i);
        
        // Initialize country dropdown on page load
        document.addEventListener('DOMContentLoaded', function() {
            populateCountries();
            
            // Add event listener to country select
            const countrySelect = document.getElementById('billing_country');
            if (countrySelect) {
                countrySelect.addEventListener('change', function() {
                    populateStates(this.value);
                });
            }
        });
        
        function populateCountries() {
            const countrySelect = document.getElementById('billing_country');
            if (!countrySelect) return;
            
            // Clear existing options except the first placeholder
            countrySelect.innerHTML = '<option value="" disabled selected hidden>Country/Region</option>';
            
            countryList.forEach(function(country) {
                const option = document.createElement('option');
                option.value = country;
                option.text = country;
                countrySelect.appendChild(option);
            });
        }
        
        function populateStates(country) {
            const stateSelect = document.getElementById('billing_state');
            if (!stateSelect) return;
            
            stateSelect.innerHTML = '<option value="" disabled selected hidden>State/Province</option>';
            
            if (countryStateData[country]) {
                countryStateData[country].forEach(function(state) {
                    const option = document.createElement('option');
                    option.value = state;
                    option.text = state;
                    stateSelect.appendChild(option);
                });
            }
        }
        
        // Add form submit handler to show loader
        document.addEventListener('DOMContentLoaded', function() {
            const qrForm = document.getElementById('qrDonateForm');
            if (qrForm) {
                qrForm.addEventListener('submit', function() {
                    showPaymentLoader();
                });
            }
        });
        
        // Payment Loader Functions
        function showPaymentLoader() {
            const loader = document.getElementById('payment-loader');
            if (loader) {
                loader.style.display = 'flex';
                document.getElementById('qrDonateForm').style.pointerEvents = 'none';
                document.getElementById('qrDonateForm').style.opacity = '0.5';
            }
        }
        
        function hidePaymentLoader() {
            const loader = document.getElementById('payment-loader');
            if (loader) {
                loader.style.display = 'none';
                document.getElementById('qrDonateForm').style.pointerEvents = 'auto';
                document.getElementById('qrDonateForm').style.opacity = '1';
            }
        }
    </script>

<!-- Payment Processing Loader -->
<div id="payment-loader" style="display: none;">
    <div class="payment-loader-overlay"></div>
    <div class="payment-loader-container">
        <div class="payment-loader-content">
            <div class="spinner-border text-primary mb-4" role="status">
                <span class="visually-hidden">Processing...</span>
            </div>
            <h3 class="mb-3">Processing Your Payment</h3>
            <p class="loader-message">Please wait while your transaction is being completed...</p>
            <div class="loader-warnings mt-4">
                <p class="warning-item"><i class="fas fa-exclamation-circle me-2"></i> Do not refresh the page</p>
                <p class="warning-item"><i class="fas fa-exclamation-circle me-2"></i> Do not close this window</p>
                <p class="warning-item"><i class="fas fa-exclamation-circle me-2"></i> Do not navigate away</p>
            </div>
            <p class="loader-subtext mt-4">This may take a few moments...</p>
        </div>
    </div>
</div>

<style>
    #payment-loader {
        display: none;
        justify-content: center;
        align-items: center;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 9999;
    }
    
    .payment-loader-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
    }
    
    .payment-loader-container {
        position: relative;
        z-index: 10000;
        width: 90%;
        max-width: 450px;
    }
    
    .payment-loader-content {
        background: white;
        border-radius: 12px;
        padding: 40px 30px;
        text-align: center;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        animation: slideUp 0.3s ease-out;
    }
    
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .payment-loader-content h3 {
        color: #333;
        font-weight: 600;
        font-size: 20px;
        margin: 0 0 10px 0;
    }
    
    .loader-message {
        color: #666;
        font-size: 14px;
        margin-bottom: 0;
    }
    
    .loader-warnings {
        background: #f8f9fa;
        border-left: 4px solid #ffc107;
        border-radius: 6px;
        padding: 15px;
        margin: 20px 0;
        text-align: left;
    }
    
    .warning-item {
        color: #666;
        font-size: 13px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
    }
    
    .warning-item:last-child {
        margin-bottom: 0;
    }
    
    .warning-item i {
        color: #ffc107;
    }
    
    .loader-subtext {
        color: #999;
        font-size: 12px;
        margin-bottom: 0;
        font-style: italic;
    }
    
    .spinner-border {
        width: 50px;
        height: 50px;
        border-width: 4px;
    }
</style>

</body>
</html>
