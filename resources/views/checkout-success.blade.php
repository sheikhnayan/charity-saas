@php
    $url = url()->current();
    $domain = parse_url($url, PHP_URL_HOST);
    $check = \App\Models\Website::where('domain', $domain)->first();
    $header = $check ? \App\Models\Header::where('website_id', $check->id)->first() : null;
    $footer = $check ? \App\Models\Footer::where('website_id', $check->id)->first() : null;
    $setting = $check ? \App\Models\Setting::where('user_id', $check->user_id)->first() : null;
    $customFonts = \App\Models\CustomFont::get();
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $check->name ?? 'Checkout Success' }}</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('auction.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts - Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="{{ route('fonts.css') }}" rel="stylesheet">
    
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<style>
        /* Sticky Bottom Investment CTA Styles */
        #sticky-investment-cta {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 9999;
            background: #000000;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            /* border-top: 1px solid #e0e0e0; */
        }

        .sticky-cta-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            max-width: 100%;
        }

        .share-price-section {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            width: 100%;
            padding-left: 14%;

        }

        .price-value {
            color: #ffffff;
            font-size: 18px;
            font-weight: 700;
            line-height: 1.2;
            margin: 0;
        }

        .price-label {
            color: #ffffff;
            font-size: 12px;
            font-weight: 400;
            line-height: 1.2;
            margin: 0;
            opacity: 0.8;
        }

        .invest-button-section {
            flex-shrink: 0;
        }

        .invest-now-btn {
            background: #28a745;
            color: #ffffff;
            border: none;
            padding: 12px 32px;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.5px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            min-width: 140px;
        }

        .sssssttttt{
            padding: 1.25rem 2.7rem !important;
            border-radius: 0px !important;
        }

        .invest-now-btn:hover {
            background: #218838;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
        }

        .invest-now-btn:active {
            transform: translateY(0);
            box-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);
        }

        strong {
            font-weight: bold;
        }

        /* Add bottom padding to body to prevent content overlap - Investment websites only */
        @if($check && $check->isInvestment())
        body {
            padding-bottom: 70px;
        }
        @endif

        /* Remove bottom padding on desktop */
        @media (min-width: 768px) {
            body {
                padding-bottom: 0;
            }
            
            #sticky-investment-cta {
                display: none !important;
            }

        }

        /* Responsive adjustments for smaller screens */
        @media (max-width: 360px) {
            .sticky-cta-content {
                padding: 10px 12px;
            }
            
            .price-value {
                font-size: 16px;
            }
            
            .invest-now-btn {
                padding: 10px 24px;
                font-size: 13px;
                min-width: 120px;
            }

            footer{
                margin-bottom: 0px !important;
            }
        }

        /* System font classes (for Quill editor content) */
    .ql-font-arial { font-family: Arial, sans-serif !important; }
    .ql-font-helvetica { font-family: Helvetica, sans-serif !important; }
    .ql-font-times { font-family: 'Times New Roman', serif !important; }
    .ql-font-georgia { font-family: Georgia, serif !important; }
    .ql-font-verdana { font-family: Verdana, sans-serif !important; }
    .ql-font-courier { font-family: 'Courier New', monospace !important; }
    .ql-font-outfit { font-family: 'Outfit', sans-serif !important; }

    /* Quill size classes (align with Quill editor defaults) */
    .ql-size-small { font-size: 0.75em !important; }
    .ql-size-large { font-size: 1.5em !important; }
    .ql-size-huge  { font-size: 2.5em !important; }

    /* Quill.js Class-based Font Styles for Frontend */
    .ql-size-6px { font-size: 6px !important; }
    .ql-size-8px { font-size: 8px !important; }
    .ql-size-9px { font-size: 9px !important; }
    .ql-size-10px { font-size: 10px !important; }
    .ql-size-12px { font-size: 12px !important; }
    .ql-size-14px { font-size: 14px !important; }
    .ql-size-16px { font-size: 16px !important; }
    .ql-size-18px { font-size: 18px !important; }
    .ql-size-20px { font-size: 20px !important; }
    .ql-size-24px { font-size: 24px !important; }
    .ql-size-28px { font-size: 28px !important; }
    .ql-size-32px { font-size: 32px !important; }
    .ql-size-36px { font-size: 36px !important; }
    .ql-size-40px { font-size: 40px !important; }
    .ql-size-48px { font-size: 48px !important; }

    .ql-font-arial { font-family: Arial, sans-serif !important; }
    .ql-font-helvetica { font-family: 'Helvetica Neue', Helvetica, sans-serif !important; }
    .ql-font-times { font-family: 'Times New Roman', Times, serif !important; }
    .ql-font-georgia { font-family: Georgia, serif !important; }
    .ql-font-verdana { font-family: Verdana, sans-serif !important; }
    .ql-font-courier { font-family: 'Courier New', Courier, monospace !important; }
    .ql-font-outfit { font-family: 'Outfit', sans-serif !important; }

    /* SEO-friendly semantic heading styles for frontend */
    h1, .ql-header-1 {
        font-size: 2.5rem !important;
        font-weight: bold !important;
        line-height: 1.2 !important;
        margin: 1rem 0 0.5rem 0 !important;
    }
    h2, .ql-header-2 {
        font-size: 2rem !important;
        font-weight: bold !important;
        line-height: 1.3 !important;
        margin: 0.8rem 0 0.4rem 0 !important;
    }
    h3, .ql-header-3 {
        font-size: 1.75rem !important;
        font-weight: bold !important;
        line-height: 1.4 !important;
        margin: 0.6rem 0 0.3rem 0 !important;
    }
    h4, .ql-header-4 {
        font-size: 1.5rem !important;
        font-weight: bold !important;
        line-height: 1.4 !important;
        margin: 0.5rem 0 0.25rem 0 !important;
    }
    h5, .ql-header-5 {
        font-size: 1.25rem !important;
        font-weight: bold !important;
        line-height: 1.5 !important;
        margin: 0.4rem 0 0.2rem 0 !important;
    }

    .link_wrap div{
        font-family: Outfit,sans-serif !important;
    }
    </style>

    <style>
    body{background:#f9fafb;}
    @if(isset($customFonts) && $customFonts->count() > 0)
        @foreach($customFonts as $font)
        @font-face {
            font-family: '{{ $font->font_family }}';
            src: url('{{ asset('storage/' . $font->file_path) }}') format('{{ $font->file_format == 'ttf' ? 'truetype' : ($font->file_format == 'otf' ? 'opentype' : $font->file_format) }}');
            font-weight: normal;
            font-style: normal;
            font-display: swap;
        }
        .ql-font-{{ $font->font_family }} { font-family: '{{ $font->font_family }}', sans-serif !important; }
        @endforeach
    @endif

    /* System font classes */
    .ql-font-arial { font-family: Arial, sans-serif !important; }
    .ql-font-helvetica { font-family: Helvetica, sans-serif !important; }
    .ql-font-times { font-family: 'Times New Roman', serif !important; }
    .ql-font-georgia { font-family: Georgia, serif !important; }
    .ql-font-verdana { font-family: Verdana, sans-serif !important; }
    .ql-font-courier { font-family: 'Courier New', monospace !important; }
    .ql-font-outfit { font-family: 'Outfit', sans-serif !important; }

    /* Quill size classes */
    .ql-size-small { font-size: 0.75em !important; }
    .ql-size-large { font-size: 1.5em !important; }
    .ql-size-huge  { font-size: 2.5em !important; }

    /* Menu Font Family Styling */
    @if(isset($header) && $header && $header->menu_font_family)
    .navbar .nav-link,
    .navbar .navbar-brand,
    .navbar .btn {
        font-family: '{{ $header->menu_font_family }}', sans-serif !important;
    }
    @endif

    /* Contact Topbar Font Family Styling */
    @if(isset($header) && $header && $header->contact_topbar_font_family)
    .contact-topbar,
    .contact-topbar *:not(i):not(.fas):not(.fa):not(.far):not(.fab):not(.fal):not(.fad) {
        font-family: '{{ $header->contact_topbar_font_family }}', sans-serif !important;
    }
    @endif

    /* Investor Exclusives Font Family Styling */
    @if(isset($header) && $header && $header->investor_exclusives_font_family)
    .investor-exclusives-bar,
    .investor-exclusives-bar *:not(i):not(.fas):not(.fa):not(.far):not(.fab):not(.fal):not(.fad) {
        font-family: '{{ $header->investor_exclusives_font_family }}', sans-serif !important;
    }
    @endif
    </style>
    <style>
        html, body {
            height: 100%;
        }
        body {
            display: flex;
            flex-direction: column;
        }
        main {
            flex: 1;
        }
        footer {
            margin-top: auto;
        }
    </style>
</head>

<body style="background-color:#f9fafb; margin:0; padding:0;">
    @if ($header && $header->status == 1)
        @if($header->show_contact_topbar)
            <div class="contact-topbar" style="background: {{ $header->contact_topbar_bg_color ?? '#000000' }}; padding: 8px 0; font-size: 14px; height: 35px;">
                <div class="container">
                    <div class="row align-items-center justify-content-center">
                        @if($header->contact_phone)
                        <div class="col-3 col-md-auto">
                            <div class="contact-item me-4 mb-1">
                                <i class="fas fa-phone me-2" style="color: {{ $header->contact_topbar_text_color ?? '#ffffff' }};"></i>
                                <a href="tel:{{ $header->contact_phone }}" style="color: {{ $header->contact_topbar_text_color ?? '#ffffff' }};">
                                    {{ $header->contact_phone }}
                                </a>
                            </div>
                        </div>
                        @endif
                        @if($header->contact_email)
                        <div class="col-3 col-md-auto">
                            <div class="contact-item me-4 mb-1">
                                <i class="fas fa-envelope me-2" style="color: {{ $header->contact_topbar_text_color ?? '#ffffff' }};"></i>
                                <a href="mailto:{{ $header->contact_email }}" style="color: {{ $header->contact_topbar_text_color ?? '#ffffff' }};">
                                    {{ $header->contact_email }}
                                </a>
                            </div>
                        </div>
                        @endif
                        @if($header->contact_cta_text)
                        <div class="col-3 col-md-auto">
                            <div class="contact-item mb-1">
                                <i class="fas fa-map-marker-alt me-2" style="color: {{ $header->contact_topbar_text_color ?? '#ffffff' }};"></i>
                                <span style="color: {{ $header->contact_topbar_text_color ?? '#ffffff' }}; text-decoration : underline !important;">
                                    {{ $header->contact_cta_text }}
                                </span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        @if ($check && ($check->is_main_site ?? 0) == 1)
            @include('layouts.main_header')
        @else
            @include('layouts.nav')
        @endif

        @if($check && $header && $header->show_investor_exclusives)
            <div class="investor-exclusives-bar" style="background: {{ $header->topbar_background_color ?? '#1e3a8a' }};">
                <div class="investor-exclusives-content">
                    <a href="{{ $header->investor_exclusives_url ?? '#' }}" style="text-decoration: none;">
                        <p class="investor-exclusives-text" style="color: {{ $header->topbar_text_color ?? '#ffffff' }}; font-size: 13px; padding-top: 5px; font-family: Outfit,sans-serif;text-transform: uppercase; padding-bottom: 4px;">
                            {{ $header->investor_exclusives_text ?? 'Exclusive access for investors' }}
                        </p>
                    </a>
                </div>
            </div>
            <script>
                function updateNavbarHeights() {
                    const navbar = document.querySelector('.navbar');
                    const contactTopbar = document.querySelector('.contact-topbar');
                    const investorBar = document.querySelector('.investor-exclusives-bar');
                    if (navbar) {
                        const navbarHeight = navbar.offsetHeight;
                        const contactTopbarHeight = contactTopbar ? contactTopbar.offsetHeight : 0;
                        const investorBarHeight = investorBar ? investorBar.offsetHeight : 0;
                        const totalNavHeight = navbarHeight + contactTopbarHeight;
                        const totalWithInvestorBar = totalNavHeight + investorBarHeight;
                        const totalHeightRem = totalNavHeight / 16;
                        const mainContentMargin = (investorBar ? totalWithInvestorBar : totalNavHeight) / 16 + 0.5;
                        document.documentElement.style.setProperty('--navbar-total-height', `${totalHeightRem}rem`);
                        document.documentElement.style.setProperty('--main-content-margin-top', `${mainContentMargin}rem`);
                    }
                }
                document.addEventListener('DOMContentLoaded', () => setTimeout(updateNavbarHeights, 50));
                window.addEventListener('resize', updateNavbarHeights);
            </script>
        @endif
    @endif

    <main>
<div class="checkout-success-container">
    <div class="success-card">
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>

        <h1>Thank You!</h1>
        <p class="success-message">Your purchase has been completed successfully.</p>

        <div class="order-details">
            <div class="detail-row">
                <span>Order Date:</span>
                <span>{{ $transaction['timestamp']->format('M d, Y H:i A') }}</span>
            </div>
            <div class="detail-row">
                <span>Email:</span>
                <span>{{ $transaction['email'] }}</span>
            </div>
            <div class="detail-row highlight">
                <span>Order Total:</span>
                <span>${{ number_format($transaction['total'], 2) }}</span>
            </div>
        </div>

        <div class="order-items">
            <h3>Summary</h3>
            <div class="items-list">
                @php
                    $subtotal = $transaction['subtotal'] ?? 0;
                @endphp
                @foreach($transaction['items'] as $item)
                    <div class="item-row">
                        <div>
                            <p class="item-name">{{ $item['name'] }}</p>
                            <p class="item-type">{{ ucfirst($item['type']) }}</p>
                        </div>
                        <p class="item-amount">${{ number_format($item['total'] ?? $item['amount'] ?? 0, 2) }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Payment Summary -->
        <div class="payment-summary" style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 8px; text-align: left;">
            <div class="summary-row">
                <span>Subtotal:</span>
                <span>${{ number_format($subtotal, 2) }}</span>
            </div>
            <div class="summary-row">
                <span>Platform Fee:</span>
                <span>${{ number_format($transaction['processing_fee'] ?? 0, 2) }}</span>
            </div>
            @if(($transaction['tip_amount'] ?? 0) > 0)
            <div class="summary-row">
                <span>Tip:</span>
                <span>${{ number_format($transaction['tip_amount'], 2) }}</span>
            </div>
            @endif
            <div class="summary-row highlight" style="border-top: 1px solid #dee2e6; padding-top: 10px; margin-top: 10px; font-weight: bold;">
                <span>Total Paid:</span>
                <span>${{ number_format($transaction['total'], 2) }}</span>
            </div>
        </div>

        <div class="success-actions">
            <a href="/" class="btn btn-primary">
                <i class="fas fa-home"></i> Back to Home
            </a>
            {{-- <a href="/donate" class="btn btn-secondary">
                <i class="fas fa-heart"></i> Make Another Donation
            </a> --}}
        </div>

        <div class="support-note">
            <p>A confirmation email has been sent to <strong>{{ $transaction['email'] }}</strong></p>
            <p>If you have any questions, please <a href="/contact">contact us</a>.</p>
        </div>
    </div>
</div>

<style>
    .checkout-success-container {
        min-height: calc(100vh - 200px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        margin-top: 80px;
    }

    .success-card {
        background: white;
        border-radius: 12px;
        padding: 60px 40px;
        max-width: 600px;
        width: 100%;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        text-align: center;
    }

    .success-icon {
        margin-bottom: 30px;
    }

    .success-icon i {
        font-size: 80px;
        color: #27ae60;
        display: block;
        animation: scaleIn 0.5s ease;
    }

    @keyframes scaleIn {
        from {
            transform: scale(0);
            opacity: 0;
        }
        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    .success-card h1 {
        font-size: 32px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 10px;
    }

    .success-message {
        font-size: 16px;
        color: #7f8c8d;
        margin-bottom: 30px;
    }

    /* Order Details */
    .order-details {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 30px;
        text-align: left;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #e9ecef;
        font-size: 14px;
        color: #2c3e50;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-row.highlight {
        background: white;
        padding: 12px;
        margin: 0 -12px;
        border-bottom: none;
        font-weight: 600;
        border-radius: 6px;
        border: 2px solid #667eea;
    }

    .detail-row.highlight span:last-child {
        color: #667eea;
        font-size: 18px;
    }

    /* Order Items */
    .order-items {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 30px;
        text-align: left;
    }

    .order-items h3 {
        font-size: 16px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 16px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .items-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .item-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 12px;
        background: white;
        border-radius: 6px;
        border: 1px solid #e9ecef;
    }

    .item-name {
        margin: 0 0 4px 0;
        font-weight: 600;
        color: #2c3e50;
        font-size: 14px;
    }

    .item-type {
        margin: 0;
        font-size: 12px;
        color: #95a5a6;
    }

    .item-amount {
        font-size: 16px;
        font-weight: 700;
        color: #667eea;
        margin: 0;
    }

    /* Payment Summary */
    .payment-summary {
        display: flex;
        flex-direction: column;
        gap: 0;
        text-align: left;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #dee2e6;
        font-size: 14px;
        color: #2c3e50;
    }

    .summary-row:last-child {
        border-bottom: none;
    }

    .summary-row span:last-child {
        font-weight: 600;
        color: #2c3e50;
    }

    .summary-row.highlight span:last-child {
        color: #667eea;
        font-size: 16px;
        font-weight: 700;
    }

    /* Actions */
    .success-actions {
        display: flex;
        gap: 16px;
        margin-bottom: 30px;
        justify-content: center;
    }

    .btn {
        padding: 12px 24px;
        border-radius: 6px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        color: white;
        text-decoration: none;
    }

    .btn-secondary {
        background: #ecf0f1;
        color: #2c3e50;
    }

    .btn-secondary:hover {
        background: #dfe6e9;
        color: #2c3e50;
        text-decoration: none;
    }

    /* Support Note */
    .support-note {
        padding: 16px;
        background: #e3f2fd;
        border-left: 4px solid #2196f3;
        border-radius: 4px;
        text-align: left;
        font-size: 13px;
        color: #1565c0;
    }

    .support-note p {
        margin: 8px 0;
    }

    .support-note a {
        color: #1565c0;
        text-decoration: underline;
    }

    .support-note a:hover {
        color: #0d47a1;
    }

    /* Responsive */
    @media (max-width: 600px) {
        .success-card {
            padding: 40px 24px;
        }

        .success-card h1 {
            font-size: 24px;
        }

        .success-icon i {
            font-size: 60px;
        }

        .success-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>
    </main>

    @if ($check && ($check->is_main_site ?? 0) == 1)
        @include('layouts.main_footer')
    @else
        @if ($footer && $footer->status == 1)
            @include('layouts.new-footer')
        @endif
    @endif

</body>
</html>
