@php
    $url = url()->current();
    $domain = parse_url($url, PHP_URL_HOST);
    $check = \App\Models\Website::where('domain', $domain)->first();
    $header = $check ? \App\Models\Header::where('website_id', $check->id)->first() : null;
    $footer = $check ? \App\Models\Footer::where('website_id', $check->id)->first() : null;
    $setting = $check ? \App\Models\Setting::where('user_id', $check->user_id)->first() : null;
    $customFonts = \App\Models\CustomFont::get();
    $website = $check;
    // Respect website-specific payment settings (with fallback to global settings)
    $rawPaymentMethod = $website ? ($website->getPaymentMethod() ?? 'stripe') : 'stripe';
    // Normalize legacy values to the template expectation
    $normalized = strtolower($rawPaymentMethod);
    $paymentMethod = in_array($normalized, ['authorize', 'authorize.net', 'authorize_net', 'authnet']) ? 'authorize_net' : $rawPaymentMethod;
    $processingFee = $website ? $website->getProcessingFee() : 2.9;
    $paymentSettings = $website?->paymentSettings ?? \App\Models\PaymentSetting::find(1);
    $tippingEnabled = $paymentSettings?->tipping_enabled ?? true;
    $coinbaseEnabled = $paymentSettings?->coinbase_enabled ?? false;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $check->name ?? 'Checkout' }}</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('auction.css') }}">
    <link rel="stylesheet" href="{{ asset('checkout.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts - Outfit to match page-investment -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="{{ route('fonts.css') }}" rel="stylesheet">
    
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

    <style>
    body{background:#f9fafb;}
    .form-control{
        margin-bottom: 0.5rem;
        padding: 0.8rem;
    }
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

    /* Menu Font Family Styling (match page-investment) */
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
        .checkout-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .checkout-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .checkout-header h1 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
            color: #2c3e50;
        }

        .checkout-content {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 40px;
        }

        .checkout-summary {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 8px;
            height: fit-content;
            position: sticky;
            top: 20px;
        }

        .checkout-summary h2 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .order-items {
            display: flex;
            flex-direction: column;
            gap: 16px;
            margin-bottom: 20px;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 12px;
            background: white;
            border-radius: 6px;
            border: 1px solid #e9ecef;
        }

        .item-info h4 {
            margin: 0 0 4px 0;
            font-size: 14px;
            font-weight: 600;
            color: #2c3e50;
        }

        .item-amount {
            font-size: 16px;
            font-weight: 700;
            color: #667eea;
        }

        .order-summary-divider {
            height: 1px;
            background: #e9ecef;
            margin: 20px 0;
        }

        .order-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .total-amount {
            font-size: 28px;
            font-weight: 700;
            color: #667eea;
        }

        .checkout-payment h2 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 30px;
            color: #2c3e50;
        }

        .form-section {
            border: none;
            padding: 0;
            margin-bottom: 30px;
        }

        .form-section h3 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 16px;
            color: #2c3e50;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }

        .payment-methods {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .payment-form-section {
            margin-bottom: 24px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 6px;
        }

        .checkout-terms {
            margin-bottom: 30px;
            padding: 16px;
            background: #ecf0f1;
            border-radius: 6px;
        }

        .checkout-actions {
            display: flex;
            gap: 16px;
            margin-bottom: 30px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            flex: 1;
        }

        .btn-lg {
            padding: 14px 32px;
            font-size: 16px;
        }

        .security-notice {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px;
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 6px;
            font-size: 13px;
        }

        @media (max-width: 768px) {
            .checkout-content {
                grid-template-columns: 1fr;
            }
            .checkout-summary {
                position: relative;
                top: auto;
            }
            .checkout-grid {
                grid-template-columns: 1fr !important;
            }
            .checkout-left,
            .checkout-right {
                padding: 20px !important;
            }
            .checkout-right > div {
                max-width: 100% !important;
            }
            /* Prevent price overflow on mobile */
            .order-item-grid {
                grid-template-columns: 60px 1fr 90px !important;
                gap: 8px !important;
            }
            .order-item-image {
                width: 60px !important;
                height: 60px !important;
                font-size: 30px !important;
            }
            .order-item-content h4 {
                font-size: 14px !important;
                line-height: 1.3;
            }
            .order-item-content p {
                font-size: 12px !important;
            }
            .order-item-price {
                font-size: 15px !important;
                word-break: break-word;
            }
            /* Make card logos wrap and scale on mobile */
            .payment-card-logos {
                flex-wrap: wrap;
                gap: 4px !important;
                max-width: 140px;
            }
            .payment-card-logos img {
                width: 32px !important;
                height: 20px !important;
            }
        }
    </style>

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
            // Payment loader helpers shared by all payment methods
            function showPaymentLoader() {
                const loader = document.getElementById('payment-loader');
                if (loader) {
                    loader.style.display = 'flex';
                    // Disable forms to prevent double submit while processing
                    const authorizeForm = document.getElementById('authorize-form');
                    const stripeForm = document.getElementById('stripe-form');
                    if (authorizeForm) {
                        authorizeForm.style.pointerEvents = 'none';
                        authorizeForm.style.opacity = '0.5';
                    }
                    if (stripeForm) {
                        stripeForm.style.pointerEvents = 'none';
                        stripeForm.style.opacity = '0.5';
                    }
                }
            }

            function hidePaymentLoader() {
                const loader = document.getElementById('payment-loader');
                if (loader) {
                    loader.style.display = 'none';
                    const authorizeForm = document.getElementById('authorize-form');
                    const stripeForm = document.getElementById('stripe-form');
                    if (authorizeForm) {
                        authorizeForm.style.pointerEvents = 'auto';
                        authorizeForm.style.opacity = '1';
                    }
                    if (stripeForm) {
                        stripeForm.style.pointerEvents = 'auto';
                        stripeForm.style.opacity = '1';
                    }
                }
            }
            </script>

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

    <main style="margin-top: 6rem !important">
        @if ($check && ($check->type ?? null) === 'investment')
        <style>
            /* Match page-investment navbar compact padding */
            .navbar-expand-xl{
                padding-top: 0px !important;
                padding-bottom: 0px !important;
            }
        </style>
        @endif

<div class="checkout-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:0;min-height:calc(100vh - var(--main-content-margin-top, 7rem));margin:0;">
    <!-- Left Section: Order Items -->
    <div class="checkout-left" style="background:#fff;padding:40px;overflow-y:auto;">
        <div style="max-width:100%;">
            <h3 style="font-size:24px;font-weight:700;margin-bottom:30px;color:#2c3e50;">Order Summary</h3>
            
            <div style="border:1px solid #eee;border-radius:8px;padding:20px;margin-bottom:30px;">
                @foreach($items as $item)
                    <div style="margin-bottom:20px;">
                        <div class="order-item-grid" style="display:grid;grid-template-columns:80px 1fr 120px;gap:15px;align-items:start;">
                            <div class="order-item-image" style="width:80px;height:80px;background:#f0f0f0;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:40px;color:#ccc;overflow:hidden;">
                                @php
                                    $imagePath = null;
                                    if ($item['type'] === 'ticket') {
                                        // Ticket: fetch from database
                                        $ticket = \App\Models\Ticket::find($item['id']);
                                        $imagePath = $ticket ? asset($ticket->image) : null;
                                    } elseif ($item['type'] === 'auction') {
                                        // Auction: fetch first image
                                        $auctionItem = \App\Models\Auction::find($item['id']);
                                        $imagePath = $auctionItem && $auctionItem->images->count() > 0 
                                            ? asset('/uploads/' . $auctionItem->images[0]->image) 
                                            : null;
                                    } elseif ($item['type'] === 'student' || $item['type'] === 'donation') {
                                        // Student/Donation: use student photo with fallback to website logo
                                        $student = \App\Models\User::where('id', $item['id'])->first();
                                        $studentPhoto = $student ? $student->photo : null;
                                        $websiteLogo = $check && $check->user && $check->user->setting && $check->user->setting->logo
                                            ? '/uploads/' . $check->user->setting->logo
                                            : null;
                                        
                                        $imagePath = $studentPhoto && file_exists(public_path($studentPhoto))
                                            ? asset($studentPhoto)
                                            : ($websiteLogo ? asset($websiteLogo) : null);
                                    } elseif ($item['type'] === 'product') {
                                        // Product: fetch from database
                                        $product = \App\Models\Ticket::find($item['id']);
                                        $imagePath = $product && $product->image 
                                            ? asset($product->image)
                                            : null;
                                    }
                                @endphp
                                @if($imagePath)
                                    <img src="{{ $imagePath }}" alt="{{ $item['name'] }}" style="width:100%;height:100%;object-fit:cover;">
                                @else
                                    <i class="fas fa-image"></i>
                                @endif
                            </div>
                            <div class="order-item-content">
                                <h4 style="margin:0 0 8px 0;font-size:16px;font-weight:600;color:#2c3e50;">{{ $item['name'] }}</h4>
                                <p style="margin:0;font-size:13px;color:#95a5a6;">{{ ucfirst($item['type']) }}</p>
                                @if($item['quantity'] > 1)
                                    <p style="margin:4px 0 0 0;font-size:13px;color:#95a5a6;">Qty: {{ $item['quantity'] }}</p>
                                @endif
                            </div>
                            <div style="text-align:right;">
                                <div class="order-item-price" style="font-size:18px;font-weight:700;color:#667eea;">${{ number_format($item['total'], 2) }}</div>
                            </div>
                        </div>
                    </div>
                    @if(!$loop->last)
                        <div style="height:1px;background:#eee;margin:20px 0;"></div>
                    @endif
                @endforeach
            </div>

            <!-- Pricing Summary -->
            <div style="border-top:2px solid #eee;padding-top:20px;">
                <div style="display:grid;grid-template-columns:1fr 120px;gap:15px;margin-bottom:12px;">
                    <span style="color:#2c3e50;font-weight:500;">Subtotal</span>
                    <span style="text-align:right;font-weight:600;color:#2c3e50;">${{ number_format($subtotal ?? $total, 2) }}</span>
                </div>
                <div style="display:grid;grid-template-columns:1fr 120px;gap:15px;margin-bottom:20px;">
                    <span style="color:#2c3e50;font-weight:500;">Platform Fee</span>
                    <span id="processing-fee-amount" style="text-align:right;font-weight:600;color:#2c3e50;">${{ preg_replace('/\.00$/', '', number_format((($subtotal ?? $total) / 100) * ($processingFee ?? 2.9), 2, '.', ',')) }}</span>
                </div>
                @if($tippingEnabled)
                <!-- Tip row in summary (managed by tipping component) -->
                <div id="tip-row" style="display:none;grid-template-columns:1fr 120px;gap:15px;margin-bottom:20px;">
                    <span style="color:#2c3e50;font-weight:500;">Tip</span>
                    <span id="tip-amount-display" style="text-align:right;font-weight:600;color:#667eea;">$0.00</span>
                </div>
                @endif
                <div style="display:grid;grid-template-columns:1fr 120px;gap:15px;border-top:2px solid #eee;padding-top:15px;">
                    <span style="font-size:18px;font-weight:700;color:#2c3e50;">Total</span>
                    <span id="checkout-total" style="text-align:right;font-size:20px;font-weight:700;color:#667eea;">${{ preg_replace('/\.00$/', '', number_format(((($subtotal ?? $total) / 100) * ($processingFee ?? 2.9)) + ($subtotal ?? $total), 2, '.', '')) }}</span>
                </div>
            </div>
            
            <!-- Global hidden tip fields used by tipping component -->
            <input type="hidden" id="tip-amount-field" value="0">
            <input type="hidden" id="tip-percentage-field" value="0">
            <input type="hidden" id="tip-enabled-field" value="0">
            
            @if($tippingEnabled)
            {{-- Tipping Component (left side) --}}
            @include('components.tipping', [
                'baseAmount' => $total,
                'primaryColor' => '#667eea',
                'processingFee' => $processingFee ?? 2.9
            ])
            @endif
        </div>
    </div>

    <!-- Right Section: Payment Form (matches stripe/authorize sections) -->
    <div class="checkout-right" style="background:#f9fafb;padding:40px;overflow-y:auto;">
        <div style="max-width:100%;">
            <h3 style="font-size:24px;font-weight:700;margin-bottom:20px;color:#2c3e50;">Payment Details</h3>

            @php $payment = \App\Models\PaymentSetting::find(1); @endphp

            @if($paymentMethod === 'authorize_net')
                <div style="background:#fff;border:1px solid #dedede;border-radius:10px;overflow:hidden;">
                    <div style="padding:1rem 1rem 0.5rem 1rem;border-bottom:1px solid #dedede;">
                        <div style="display:flex;align-items:center;justify-content:space-between;">
                            <h5 style="margin:0;font-weight:700;">Pay with card</h5>
                            <div class="payment-card-logos" style="display:inline-flex;gap:6px;">
                                <img src="https://cdn.shopify.com/shopifycloud/checkout-web/assets/c1.en/assets/visa.sxIq5Dot.svg" alt="VISA" width="38" height="24">
                                <img src="https://cdn.shopify.com/shopifycloud/checkout-web/assets/c1.en/assets/mastercard.1c4_lyMp.svg" alt="MASTERCARD" width="38" height="24">
                                <img src="https://cdn.shopify.com/shopifycloud/checkout-web/assets/c1.en/assets/amex.Csr7hRoy.svg" alt="AMEX" width="38" height="24">
                                <img src="https://cdn.shopify.com/shopifycloud/checkout-web/assets/c1.en/assets/discover.C7UbFpNb.svg" alt="DISCOVER" width="38" height="24">
                            </div>
                        </div>
                    </div>
                    <form action="{{ route('checkout.process') }}" method="POST" id="authorize-form" style="padding:1rem;background:#f4f4f4;border-bottom-left-radius:10px;border-bottom-right-radius:10px;">
                        @csrf
                        <input type="hidden" name="payment_method" value="authorize_net">
                        <input type="hidden" name="payment_token" id="authorize_payment_token" value="">
                        <!-- Per-form tip fields (synced from global on submit) -->
                        <input type="hidden" name="tip_amount" id="tip-amount-authorize" value="0">
                        <input type="hidden" name="tip_percentage" id="tip-percentage-authorize" value="0">
                        <input type="hidden" name="tip_enabled" id="tip-enabled-authorize" value="0">
                        <div data-testid="form-field-wrapper" class="sc-jnLVoO gJUOyx">
                            <div class="sc-hUpaCq iQeRTc">
                                <div class="sc-bkkeKt cNnlrr sc-ieecCq hEbWVQ position-relative" style="width:100%;">
                                    <input type="text" class="form-control pr-5" name="card_number" autocomplete="off" maxlength="60" placeholder="Card number" required style="padding:0.8rem;height:auto;">
                                    <span class="position-absolute" style="right:15px;top:50%;transform:translateY(-50%);pointer-events:none;">
                                        <i class="fa fa-lock" aria-hidden="true" style="color:#888;"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="sc-hUpaCq iQeRTc vvv" style="display:inline-flex;width:100%;gap:11px;">
                                <div style="width:49%;" class="expiry sc-bkkeKt cNnlrr sc-ieecCq hEbWVQ">
                                    <input type="text" class="form-control" name="expiration_date" maxlength="15" placeholder="Expiration date (MM / YY)" pattern="^(0[1-9]|1[0-2])\ / \d{2}$" autocomplete="off" required style="padding:0.8rem;height:auto;" oninput="formatExpiryDate(this)">
                                </div>
                                <div style="width:49%;" class="security sc-bkkeKt cNnlrr sc-ieecCq hEbWVQ position-relative">
                                    <input type="text" class="form-control pr-5" name="cvv" placeholder="Security code" autocomplete="off" required style="padding:0.8rem;height:auto;padding-right:2.5rem;">
                                    <span class="position-absolute" style="right:13px;top:42%;transform:translateY(-50%);cursor:pointer;" tabindex="0" data-bs-toggle="tooltip" data-bs-placement="left" title="3-digit security code usually found on the back of your card. American Express cards have a 4-digit code located on the front.">
                                        <i class="fa fa-question-circle" aria-hidden="true" style="color:#888;"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="sc-hUpaCq iQeRTc">
                                <div class="sc-bkkeKt cNnlrr sc-ieecCq hEbWVQ position-relative" style="width:100%;">
                                    <input type="text" class="form-control pr-5" name="name_on_card" autocomplete="off" maxlength="60" placeholder="Name on card" required style="padding:0.8rem;height:auto;">
                                </div>
                            </div>
                            <h3 style="margin-top:1.2rem;margin-bottom:0.5rem;font-size:15px;font-weight:bold;padding:10px 7px 10px 7px;">Billing address</h3>
                            <div class="sc-hUpaCq iQeRTc">
                                <div class="row">
                                    <div class="col-md-12 mb-2 position-relative">
                                        <div class="form-floating">
                                            <select class="form-select" name="country" id="country" required aria-label="Country/Region"></select>
                                            <label for="country">Country/Region</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <input type="text" class="form-control" placeholder="First name" name="first_name" id="first_name" value="{{ Auth::user()->first_name ?? ''}}" required>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <input type="text" class="form-control" placeholder="Last name" name="last_name" id="last_name" value="{{ Auth::user()->last_name ?? ''}}" required>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <input type="email" class="form-control" placeholder="Email" name="email" id="email" value="{{ $requiresEmail ? '' : (Auth::user()->email ?? '')}}" {{ $requiresEmail ? 'required' : 'readonly' }}>
                                    </div>
                                    <div class="col-md-12 mb-2 position-relative">
                                        <input type="text" class="form-control" placeholder="Address" name="address" id="address" required>
                                        <span class="position-absolute" style="right:26px;top:45%;transform:translateY(-50%);cursor:pointer;">
                                            <i class="fa fa-search" aria-hidden="true" style="color:#888;"></i>
                                        </span>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <input type="text" class="form-control" placeholder="Apartment, suite, etc. (optional)" name="apartment" id="apartment">
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <input type="text" class="form-control" placeholder="City" name="city" id="city" required>
                                    </div>
                                    <div class="col-md-4 mb-2 position-relative">
                                        <div class="form-floating">
                                            <select class="form-select" name="state" id="state" required aria-label="State"></select>
                                            <label for="state">State</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <input type="text" class="form-control" placeholder="ZIP code" name="zip" id="zipcode" required>
                                    </div>
                                    <div class="col-md-12 mb-2 position-relative">
                                        <input type="tel" class="form-control pr-5" placeholder="Phone" name="phone" id="phone" required>
                                        <span class="position-absolute" style="right:26px;top:45%;transform:translateY(-50%);cursor:pointer;" tabindex="0" data-bs-toggle="tooltip" data-bs-placement="left" title="In case we need to contact you about your order">
                                            <i class="fa fa-question-circle" aria-hidden="true" style="color:#888;"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" value="1" id="terms-authorize" required>
                            <label class="form-check-label" for="terms-authorize">
                                I agree to the
                                @if($footer && $footer->terms_page_id && $footer->terms_page)
                                    <a href="/page/{{ str_replace(' ', '-', strtolower($footer->terms_page->name)) }}" target="_blank">Terms of Service</a>
                                @else
                                    <a href="/terms-of-service" target="_blank">Terms of Service</a>
                                @endif,
                                @if($footer && $footer->privacy_page_id && $footer->privacy_page)
                                    <a href="/page/{{ str_replace(' ', '-', strtolower($footer->privacy_page->name)) }}" target="_blank">Privacy Policy</a>
                                @else
                                    <a href="/privacy-policy" target="_blank">Privacy Policy</a>
                                @endif, and
                                @if($footer && $footer->refund_page_id && $footer->refund_page)
                                    <a href="/page/{{ str_replace(' ', '-', strtolower($footer->refund_page->name)) }}" target="_blank">Refund Policy</a>
                                @else
                                    <a href="/refund-policy" target="_blank">Refund Policy</a>
                                @endif.
                            </label>
                        </div>
                        <div class="sc-gyZVQB fWNGEI mt-4">
                            <div class="sc-cVAmsi cvolSU"><button type="submit" class="btn btn-primary" style="width:100%;height:45px;">Pay Now <span id="authorize-pay-btn-amount" style="margin-left:8px;">${{ number_format($total, 2) }}</span></button></div>
                        </div>
                        @if($coinbaseEnabled)
                        <div class="sc-gyZVQB fWNGEI mt-3">
                            <div class="sc-cVAmsi cvolSU">
                                <button type="button" class="btn btn-outline-warning" id="coinbase-pay-btn" style="width:100%;height:45px;border-width:2px;">Pay with Crypto</button>
                            </div>
                        </div>
                        @endif
                    </form>
                </div>
            @else
                <div style="background:#fff;border:1px solid #dedede;border-radius:10px;overflow:hidden;">
                    <div style="padding:1rem 1rem 0.5rem 1rem;border-bottom:1px solid #dedede;">
                        <div style="display:flex;align-items:center;justify-content:space-between;">
                            <h5 style="margin:0;font-weight:700;">Pay with card </h5>
                            <div class="payment-card-logos" style="display:inline-flex;gap:6px;">
                                <img src="https://cdn.shopify.com/shopifycloud/checkout-web/assets/c1.en/assets/visa.sxIq5Dot.svg" alt="VISA" width="38" height="24">
                                <img src="https://cdn.shopify.com/shopifycloud/checkout-web/assets/c1.en/assets/mastercard.1c4_lyMp.svg" alt="MASTERCARD" width="38" height="24">
                                <img src="https://cdn.shopify.com/shopifycloud/checkout-web/assets/c1.en/assets/amex.Csr7hRoy.svg" alt="AMEX" width="38" height="24">
                                <img src="https://cdn.shopify.com/shopifycloud/checkout-web/assets/c1.en/assets/discover.C7UbFpNb.svg" alt="DISCOVER" width="38" height="24">
                            </div>
                        </div>
                    </div>
                    <form action="{{ route('checkout.process') }}" method="POST" id="stripe-form" style="padding:1rem;background:#f4f4f4;border-bottom-left-radius:10px;border-bottom-right-radius:10px;">
                        @csrf
                        <input type="hidden" name="payment_method" value="stripe">
                        <input type="hidden" name="payment_token" id="stripe_payment_token" value="">
                        <!-- Per-form tip fields (synced from global on submit) -->
                        <input type="hidden" name="tip_amount" id="tip-amount-stripe" value="0">
                        <input type="hidden" name="tip_percentage" id="tip-percentage-stripe" value="0">
                        <input type="hidden" name="tip_enabled" id="tip-enabled-stripe" value="0">
                        <div data-testid="form-field-wrapper" class="sc-jnLVoO gJUOyx">
                            <div class="sc-hUpaCq iQeRTc">
                                <div class="sc-bkkeKt cNnlrr sc-ieecCq hEbWVQ position-relative" style="width:100%;">
                                    <div id="card_number" class="form-control" style="padding:0.8rem;height:auto;background:white;"></div>
                                    <span class="position-absolute" style="right:15px;top:50%;transform:translateY(-50%);pointer-events:none;">
                                        <i class="fa fa-lock" aria-hidden="true" style="color:#888;"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="sc-hUpaCq iQeRTc vvv" style="display:inline-flex;width:100%;gap:11px;">
                                <div style="width:49%;" class="expiry sc-bkkeKt cNnlrr sc-ieecCq hEbWVQ">
                                    <div id="expiration_date" class="form-control" style="padding:0.8rem;height:auto;background:white;"></div>
                                </div>
                                <div style="width:49%;" class="security sc-bkkeKt cNnlrr sc-ieecCq hEbWVQ position-relative">
                                    <div id="cvv" class="form-control" style="padding:0.8rem;height:auto;background:white;padding-right:2.5rem;"></div>
                                    <span class="position-absolute" style="right:13px;top:42%;transform:translateY(-50%);cursor:pointer;" tabindex="0" data-bs-toggle="tooltip" data-bs-placement="left" title="3-digit security code usually found on the back of your card. American Express cards have a 4-digit code located on the front.">
                                        <i class="fa fa-question-circle" aria-hidden="true" style="color:#888;"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="sc-hUpaCq iQeRTc">
                                <div class="sc-bkkeKt cNnlrr sc-ieecCq hEbWVQ position-relative" style="width:100%;">
                                    <input type="text" class="form-control pr-5" name="name_on_card" autocomplete="off" maxlength="100" placeholder="Name on card" required style="padding:0.8rem;height:auto;">
                                </div>
                            </div>
                            <h3 style="margin-top:1.2rem;margin-bottom:0.5rem;font-size:15px;font-weight:bold;padding:10px 7px 10px 7px;">Billing address</h3>
                            <div class="sc-hUpaCq iQeRTc">
                                <div class="row">
                                    <div class="col-md-12 mb-2 position-relative">
                                        <div class="form-floating">
                                            <select class="form-select" name="country" id="country" required aria-label="Country/Region"></select>
                                            <label for="country">Country/Region</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <input type="text" class="form-control" placeholder="First name" name="first_name" id="first_name" value="{{ Auth::user()->first_name ?? ''}}" required>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <input type="text" class="form-control" placeholder="Last name" name="last_name" id="last_name" value="{{ Auth::user()->last_name ?? ''}}" required>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <input type="email" class="form-control" placeholder="Email" name="email" id="email" value="{{ $requiresEmail ? '' : (Auth::user()->email ?? '')}}" {{ $requiresEmail ? 'required' : 'readonly' }}>
                                    </div>
                                    <div class="col-md-12 mb-2 position-relative">
                                        <input type="text" class="form-control" placeholder="Address" name="address" id="address" required>
                                        <span class="position-absolute" style="right:26px;top:45%;transform:translateY(-50%);cursor:pointer;">
                                            <i class="fa fa-search" aria-hidden="true" style="color:#888;"></i>
                                        </span>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <input type="text" class="form-control" placeholder="Apartment, suite, etc. (optional)" name="apartment" id="apartment">
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <input type="text" class="form-control" placeholder="City" name="city" id="city" required>
                                    </div>
                                    <div class="col-md-4 mb-2 position-relative">
                                        <div class="form-floating">
                                            <select class="form-select" name="state" id="state" required aria-label="State"></select>
                                            <label for="state">State</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <input type="text" class="form-control" placeholder="ZIP code" name="zip" id="zipcode" required>
                                    </div>
                                    <div class="col-md-12 mb-2 position-relative">
                                        <input type="tel" class="form-control pr-5" placeholder="Phone" name="phone" id="phone" required>
                                        <span class="position-absolute" style="right:26px;top:45%;transform:translateY(-50%);cursor:pointer;" tabindex="0" data-bs-toggle="tooltip" data-bs-placement="left" title="In case we need to contact you about your order">
                                            <i class="fa fa-question-circle" aria-hidden="true" style="color:#888;"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" value="1" id="terms-stripe" required>
                            <label class="form-check-label" for="terms-stripe">
                                I agree to the
                                @if($footer && $footer->terms_page_id && $footer->terms_page)
                                    <a href="/page/{{ str_replace(' ', '-', strtolower($footer->terms_page->name)) }}" target="_blank">Terms of Service</a>
                                @else
                                    <a href="/terms-of-service" target="_blank">Terms of Service</a>
                                @endif,
                                @if($footer && $footer->privacy_page_id && $footer->privacy_page)
                                    <a href="/page/{{ str_replace(' ', '-', strtolower($footer->privacy_page->name)) }}" target="_blank">Privacy Policy</a>
                                @else
                                    <a href="/privacy-policy" target="_blank">Privacy Policy</a>
                                @endif, and
                                @if($footer && $footer->refund_page_id && $footer->refund_page)
                                    <a href="/page/{{ str_replace(' ', '-', strtolower($footer->refund_page->name)) }}" target="_blank">Refund Policy</a>
                                @else
                                    <a href="/refund-policy" target="_blank">Refund Policy</a>
                                @endif.
                            </label>
                        </div>
                        
                        <div class="sc-gyZVQB fWNGEI mt-4">
                            <div class="sc-cVAmsi cvolSU"><button type="submit" class="btn btn-primary" id="stripe-pay-btn" style="width:100%;height:45px;">Pay Now <span id="stripe-pay-btn-amount" style="margin-left:8px;">${{ number_format($total, 2) }}</span></button></div>
                        </div>
                        @if($coinbaseEnabled)
                        <div class="sc-gyZVQB fWNGEI mt-3">
                            <div class="sc-cVAmsi cvolSU">
                                <button type="button" class="btn btn-outline-warning" id="coinbase-pay-btn" style="width:100%;height:45px;border-width:2px;">Pay with Crypto</button>
                            </div>
                        </div>
                        @endif
                    </form>
                </div>
            @endif

            <div style="margin-top:20px;padding:15px;background:#d4edda;border:1px solid #c3e6cb;border-radius:6px;display:flex;align-items:flex-start;gap:10px;font-size:13px;color:#155724;">
                <i class="fas fa-shield-alt" style="margin-top:2px;font-size:16px;"></i>
                <p style="margin:0;">Your payment information is secure and encrypted. We never store credit card data.</p>
            </div>
        </div>
    </div>
    </main>



<!-- Cart Script -->
<script src="{{ asset('js/cart.js') }}"></script>

<script>
    // Diagnostic logging for checkout
    console.log('🔍 === CHECKOUT PAGE DIAGNOSTICS ===');
    console.log('🔍 jQuery loaded:', typeof jQuery !== 'undefined' ? '✅ YES' : '❌ NO');
    console.log('🔍 $ available:', typeof $ !== 'undefined' ? '✅ YES' : '❌ NO');
    console.log('🔍 window.ShoppingCart exists:', typeof window.ShoppingCart !== 'undefined' ? '✅ YES' : '❌ NO');
    console.log('🔍 document.body exists:', document.body !== null ? '✅ YES' : '❌ NO');
    console.log('🔍 document.readyState:', document.readyState);
    
    // Check if cart button exists
    setTimeout(() => {
        const cartBtn = document.getElementById('floatingCartButton');
        console.log('🔍 Cart button in DOM after 500ms:', cartBtn ? '✅ YES' : '❌ NO');
        if (cartBtn) {
            console.log('🔍 Cart button details:', {
                id: cartBtn.id,
                display: window.getComputedStyle(cartBtn).display,
                zIndex: window.getComputedStyle(cartBtn).zIndex,
                position: window.getComputedStyle(cartBtn).position
            });
        }
    }, 500);
</script>

<script>
    function formatExpiryDate(input) {
        let value = input.value.replace(/[^0-9]/g, '');
        if (value.length > 4) value = value.slice(0, 4);
        if (value.length > 2) {
            value = value.slice(0, 2) + ' / ' + value.slice(2, 4);
        }
        input.value = value;
    }

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
        "Spain": [
            "A Coruna","Álava","Ávila","Albacete","Alicante","Almería","Asturias","Badajoz","Balearic Islands","Barcelona","Burgos","Cáceres","Cádiz","Cantabria","Castellón","Ciudad Real","Córdoba","Cuenca","Girona","Granada","Guadalajara","Guipúzcoa","Huelva","Huesca","Jaén","La Coruña","La Rioja","Las Palmas","León","Lérida","Lugo","Madrid","Málaga","Murcia","Navarra","Ourense","Palencia","Pontevedra","Salamanca","Santa Cruz de Tenerife","Segovia","Seville","Soria","Tarragona","Teruel","Toledo","Valencia","Valladolid","Vizcaya","Zamora","Zaragoza"
        ]
    };
    const countryList = Object.keys(countryStateData).concat(["United Kingdom", "Germany", "France", "Spain", "Other"]).filter((v, i, a) => a.indexOf(v) === i);

    function detectCountry() {
        let country = "United States";
        if (navigator.language) {
            if (navigator.language.startsWith('en-GB')) country = "United Kingdom";
            if (navigator.language.startsWith('en-CA')) country = "Canada";
            if (navigator.language.startsWith('en-AU')) country = "Australia";
            if (navigator.language.startsWith('fr-FR')) country = "France";
            if (navigator.language.startsWith('de-DE')) country = "Germany";
            if (navigator.language.startsWith('en-IN')) country = "India";
        }
        return country;
    }

    function setCountryValue() {
        const countrySelect = document.getElementById('country');
        if (!countrySelect) return;
        const detected = detectCountry();
        countrySelect.innerHTML = '<option value="" disabled selected hidden></option>';
        countryList.forEach(function(country) {
            const option = document.createElement('option');
            option.value = country;
            option.text = country;
            countrySelect.appendChild(option);
        });
        if (detected && countryList.includes(detected)) {
            countrySelect.value = detected;
        }
        countrySelect.dispatchEvent(new Event('change'));
    }

    function syncTipFields(targetForm) {
        if (!targetForm) return;
        const gTipAmount = document.getElementById('tip-amount-field');
        const gTipPercent = document.getElementById('tip-percentage-field');
        const gTipEnabled = document.getElementById('tip-enabled-field');
        const fTipAmount = targetForm.querySelector('[id^="tip-amount-"]');
        const fTipPercent = targetForm.querySelector('[id^="tip-percentage-"]');
        const fTipEnabled = targetForm.querySelector('[id^="tip-enabled-"]');
        if (fTipAmount && gTipAmount) fTipAmount.value = gTipAmount.value;
        if (fTipPercent && gTipPercent) fTipPercent.value = gTipPercent.value;
        if (fTipEnabled && gTipEnabled) fTipEnabled.value = gTipEnabled.value;
    }

    function populateStatesAndFields() {
        const country = document.getElementById('country');
        if (!country) return;
        const stateWrapper = document.querySelector('.form-floating select#state')?.closest('.col-md-4, .col-md-12, .col-md-6');
        const stateSelect = document.getElementById('state');
        const stateLabel = document.querySelector('label[for="state"]');
        const zipcodeInput = document.getElementById('zipcode');
        stateSelect.innerHTML = '<option value="" disabled selected hidden></option>';

        if (country.value === 'United States' || country.value === 'Canada' || country.value === 'Australia' || country.value === 'India' || country.value === 'Spain') {
            countryStateData[country.value].forEach(function(entry) {
                const option = document.createElement('option');
                option.value = entry;
                option.text = entry;
                stateSelect.appendChild(option);
            });
            stateSelect.disabled = false;
            if (stateWrapper) stateWrapper.style.display = '';
            if (stateLabel) stateLabel.textContent = country.value === 'Canada' ? 'Province' : (country.value === 'Spain' ? 'Province' : country.value === 'Australia' ? 'State/territory' : 'State');
        } else {
            if (stateWrapper) stateWrapper.style.display = 'none';
            stateSelect.disabled = true;
        }

        if (!zipcodeInput) return;
        if (country.value === 'United States') {
            zipcodeInput.placeholder = 'ZIP code';
            zipcodeInput.pattern = '\\d{5}(-\\d{4})?';
            zipcodeInput.required = true;
            zipcodeInput.parentElement.style.display = '';
        } else if (country.value === 'Canada') {
            zipcodeInput.placeholder = 'Postal code';
            zipcodeInput.pattern = '[A-Za-z]\\d[A-Za-z][ -]?\\d[A-Za-z]\\d';
            zipcodeInput.required = true;
            zipcodeInput.parentElement.style.display = '';
        } else if (country.value === 'Australia') {
            zipcodeInput.placeholder = 'Postcode';
            zipcodeInput.pattern = '\\d{4}';
            zipcodeInput.required = true;
            zipcodeInput.parentElement.style.display = '';
        } else if (country.value === 'United Kingdom') {
            zipcodeInput.placeholder = 'Postcode';
            zipcodeInput.pattern = '';
            zipcodeInput.required = true;
            zipcodeInput.parentElement.style.display = '';
        } else if (country.value === 'India') {
            zipcodeInput.placeholder = 'PIN Code';
            zipcodeInput.pattern = '\\d{6}';
            zipcodeInput.required = true;
            zipcodeInput.parentElement.style.display = '';
        } else {
            zipcodeInput.placeholder = 'Postal code';
            zipcodeInput.pattern = '';
            zipcodeInput.required = false;
            zipcodeInput.parentElement.style.display = '';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Pay Now button amounts with Platform Fee
        const checkoutTotal = document.getElementById('checkout-total');
        if (checkoutTotal) {
            const totalText = checkoutTotal.textContent;
            const authorizeBtnAmount = document.getElementById('authorize-pay-btn-amount');
            const stripeBtnAmount = document.getElementById('stripe-pay-btn-amount');
            if (authorizeBtnAmount) authorizeBtnAmount.textContent = totalText;
            if (stripeBtnAmount) stripeBtnAmount.textContent = totalText;
        }

        setCountryValue();
        populateStatesAndFields();
        const countryField = document.getElementById('country');
        if (countryField) countryField.addEventListener('change', populateStatesAndFields);

        const authorizeForm = document.getElementById('authorize-form');
        if (authorizeForm) {
            authorizeForm.addEventListener('submit', function(e) {
                e.preventDefault();
                syncTipFields(authorizeForm);

                const tokenField = document.getElementById('authorize_payment_token');
                const cardNumber = (authorizeForm.querySelector('input[name="card_number"]')?.value || '').replace(/\s+/g, '');
                tokenField.value = cardNumber ? `auth_${cardNumber}` : `auth_${Date.now()}`;
                
                // Show payment processing loader
                showPaymentLoader();
                
                // AJAX submission to handle JSON response
                const submitBtn = authorizeForm.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
                }
                
                fetch(authorizeForm.action, {
                    method: 'POST',
                    body: new FormData(authorizeForm),
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        hidePaymentLoader();
                        alert(data.message || 'Payment failed. Please try again.');
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            const authAmount = document.getElementById('authorize-pay-btn-amount');
                            submitBtn.innerHTML = 'Pay Now <span id="authorize-pay-btn-amount" style="margin-left:8px;">' + (authAmount ? authAmount.textContent : '${{ number_format($total, 2) }}') + '</span>';
                        }
                    }
                })
                .catch(error => {
                    hidePaymentLoader();
                    alert('An error occurred. Please try again.');
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        const authAmount = document.getElementById('authorize-pay-btn-amount');
                        submitBtn.innerHTML = 'Pay Now <span id="authorize-pay-btn-amount" style="margin-left:8px;">' + (authAmount ? authAmount.textContent : '${{ number_format($total, 2) }}') + '</span>';
                    }
                });
            });
        }

        const coinbaseButton = document.getElementById('coinbase-pay-btn');
        if (coinbaseButton) {
            coinbaseButton.addEventListener('click', async function() {
                const activeForm = document.getElementById('authorize-form') || document.getElementById('stripe-form');
                if (!activeForm) return;

                syncTipFields(activeForm);

                const formData = new FormData(activeForm);
                formData.set('payment_method', 'coinbase');
                formData.delete('payment_token');
                formData.delete('card_number');
                formData.delete('expiration_date');
                formData.delete('cvv');

                const requiredFields = ['email', 'first_name', 'last_name'];
                for (const field of requiredFields) {
                    const value = formData.get(field);
                    if (!value) {
                        alert('Please complete your contact information before paying with crypto.');
                        return;
                    }
                }

                coinbaseButton.disabled = true;
                coinbaseButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Redirecting...';

                try {
                    const response = await fetch(activeForm.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    });
                    const data = await response.json();
                    if (data.success && data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        throw new Error(data.message || 'Unable to start crypto payment.');
                    }
                } catch (err) {
                    alert(err.message || 'Unable to start crypto payment.');
                    coinbaseButton.disabled = false;
                    coinbaseButton.innerHTML = 'Pay with Crypto';
                }
            });
        }
    });
</script>

<!-- Stripe Elements only when Stripe is active -->
@if($paymentMethod !== 'authorize_net')
<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe("{{ env('STRIPE_KEY') }}");
    const stripeElements = stripe.elements();
    const stripeStyle = {
        base: {
            fontSize: '14px',
            color: '#2B2A35',
            fontFamily: 'Lato, Helvetica Neue, HelveticaNeue, Helvetica, Arial, sans-serif',
            '::placeholder': { color: '#aab7c4' }
        },
        invalid: { color: '#fa755a', iconColor: '#fa755a' }
    };

    const stripeCardNumber = stripeElements.create('cardNumber', { style: stripeStyle, placeholder: 'Card number' });
    const stripeCardExpiry = stripeElements.create('cardExpiry', { style: stripeStyle, placeholder: 'MM / YY' });
    const stripeCardCvc = stripeElements.create('cardCvc', { style: stripeStyle, placeholder: 'CVV' });

    stripeCardNumber.mount('#card_number');
    stripeCardExpiry.mount('#expiration_date');
    stripeCardCvc.mount('#cvv');

    const stripeForm = document.getElementById('stripe-form');
    const stripeButton = document.getElementById('stripe-pay-btn');

    if (stripeForm) {
        stripeForm.addEventListener('submit', async function(event) {
            event.preventDefault();
            
            // Show payment processing loader
            showPaymentLoader();
            
            if (stripeButton) {
                stripeButton.disabled = true;
                stripeButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            }

            const {token, error} = await stripe.createToken(stripeCardNumber);

            if (error) {
                hidePaymentLoader();
                alert(error.message || 'Unable to tokenize card');
                if (stripeButton) {
                    stripeButton.disabled = false;
                    const stripeAmount = document.getElementById('stripe-pay-btn-amount');
                    stripeButton.innerHTML = `Pay Now <span id="stripe-pay-btn-amount" style="margin-left:8px;">${stripeAmount ? stripeAmount.textContent : '${{ number_format($total, 2) }}'}</span>`;
                }
                return;
            }

            const tokenInput = document.getElementById('stripe_payment_token');
            if (tokenInput) {
                tokenInput.value = token.id;
            }

            // Sync tip values from global fields into this form
            syncTipFields(stripeForm);

            // AJAX submission to handle JSON response
            fetch(stripeForm.action, {
                method: 'POST',
                body: new FormData(stripeForm),
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    hidePaymentLoader();
                    alert(data.message || 'Payment failed. Please try again.');
                    if (stripeButton) {
                        stripeButton.disabled = false;
                        const stripeAmount = document.getElementById('stripe-pay-btn-amount');
                        stripeButton.innerHTML = `Pay Now <span id="stripe-pay-btn-amount" style="margin-left:8px;">${stripeAmount ? stripeAmount.textContent : '${{ number_format($total, 2) }}'}</span>`;
                    }
                }
            })
            .catch(error => {
                hidePaymentLoader();
                alert('An error occurred. Please try again.');
                if (stripeButton) {
                    stripeButton.disabled = false;
                    const stripeAmount = document.getElementById('stripe-pay-btn-amount');
                    stripeButton.innerHTML = `Pay Now <span id="stripe-pay-btn-amount" style="margin-left:8px;">${stripeAmount ? stripeAmount.textContent : '${{ number_format($total, 2) }}'}</span>`;
                }
            });
        });
    }
    
@endif

<script>
// Payment loader helpers shared by all payment methods
function showPaymentLoader() {
    const loader = document.getElementById('payment-loader');
    if (loader) {
        loader.style.display = 'flex';
        // Disable forms to prevent double-submit while processing
        const authorizeForm = document.getElementById('authorize-form');
        const stripeForm = document.getElementById('stripe-form');
        if (authorizeForm) {
            authorizeForm.style.pointerEvents = 'none';
            authorizeForm.style.opacity = '0.5';
        }
        if (stripeForm) {
            stripeForm.style.pointerEvents = 'none';
            stripeForm.style.opacity = '0.5';
        }
    }
}

function hidePaymentLoader() {
    const loader = document.getElementById('payment-loader');
    if (loader) {
        loader.style.display = 'none';
        const authorizeForm = document.getElementById('authorize-form');
        const stripeForm = document.getElementById('stripe-form');
        if (authorizeForm) {
            authorizeForm.style.pointerEvents = 'auto';
            authorizeForm.style.opacity = '1';
        }
        if (stripeForm) {
            stripeForm.style.pointerEvents = 'auto';
            stripeForm.style.opacity = '1';
        }
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

    @if ($check && ($check->is_main_site ?? 0) == 1)
        @include('layouts.main_footer')
    @else
        @if ($footer && $footer->status == 1)
            @include('layouts.new-footer')
        @endif
    @endif

</body>
</html>
