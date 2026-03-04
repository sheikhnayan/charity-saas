@php
    $url = url()->current();
    $doamin = parse_url($url, PHP_URL_HOST);
    $check = \App\Models\Website::where('domain', $doamin)->first();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $check->name ?? 'Page' }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body{background:#f9fafb;}

        .btn-x-share {
            /* background-color: #1DA1F2; */
            color: #fff;
            border-color: #000;
        }

        .btn-x-share:hover {
            background-color: #000;
            color: #fff;
        }

        .btn-x-share:hover i{
            /* background-color: #000; */
            color: #fff;
        }

        .btn-twitter{
            background: #000 !important;
        }

        .fa-x-twitter{
            color: #000;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('auction.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <style>
    #studentTable {
        background-color: #fff !important; /* Set the table background to white */
        border: none !important; /* Remove the table border */
    }

    #studentTable th, #studentTable td {
        background-color: #fff !important; /* Set the background of table cells to white */
        border: none !important; /* Remove borders from table cells */
    }

    #studentTable tbody tr {
        background-color: #fff !important; /* Set the background of table rows to white */
    }

    #studentTable_filter {
        display: none;
    }

    #studentTable_length {
        display: none;
    }

    #studentTable thead {
        display: none; /* Hide the table header */
    }

    .non-float{
        margin-bottom: -111px;
    }

    .c-node-ap__auction-results{
        margin-right: 36px;
        margin-bottom: 24px;
        display: inline-block;
        background-color: #f8f9fa;
        border-color: #DBDCDD;
        border: 1px solid;
        border-radius: 4px;
        padding: 24px;
        font-size: 1rem;
    }

    .c-node-ap__fundraising-target{
        margin-bottom: 12px;
    }

    .c-node-ap__auction-total-label {
        margin-bottom: 12px;
        font-size: 1.25rem;
        line-height: 1.2;
        font-weight: bold;
        font-family: AvenirLTPro-Black,sans-serif;
        color: #355159
    }
    .c-node-ap__auction-total-amount {
        font-size: 2rem;
        line-height: 1.5;
        color: #d9b730;
        font-weight: bold;
        font-family: AvenirLTPro-Black,sans-serif;
    }

    .c-node-ap__totalizer{
        height: 18px;
        border-radius: 12px;
        --color-ui: #d9b730;
    }

    .c-node-ap__auction-total-component-label{
        color: #6d6e71
    }

    .c-node-ap__auction-total-component-amount{
        font-size: 1rem;
        line-height: 1.2;
        font-weight: bold;
        font-family: AvenirLTPro-Black,sans-serif;
        color: #000
    }
    .c-view__item.c-view__item--teaser {
        width: 100% !important;
        max-width: 100% !important;
        flex-basis: 100% !important;
        min-width: 330px !important;
    }

    .c-content__bottom{
        background-color: #f9fafb;
    }
    .gallery-img-preview {
        height: 421px !important;
    }

    .owl-item .item img{
        height: 425px !important;
    }

    .footer-socials .nav-item {
        margin-right: 1rem !important;
    }

    .footer-socials .nav-item a i {
        font-size: 1.5rem;
    }

    footer{
        position: relative;
        width: 100%;
        bottom: 0;
        margin-top: 2rem;
    }

    .ticket-mask {
        --mask: conic-gradient(from 45deg at left,#0000,#000 1deg 89deg,#0000 90deg) left/51% 16.00px repeat-y,conic-gradient(from -135deg at right,#0000,#000 1deg 89deg,#0000 90deg) 100% calc(50% + 8px)/51% 16.00px repeat-y;
        -webkit-mask: var(--mask);
        mask: var(--mask);
        padding: 1.5rem;
        background-color: #eee;
        border: unset;
    }

    /* Investor Exclusives Bar Styles - Dynamic Positioning */
    .investor-exclusives-bar {
        padding: 0px 0px;
        text-align: center;
        position: fixed;
        top: calc(var(--navbar-total-height, 6rem) - 0.23rem);
        left: 0;
        right: 0;
        width: 100%;
        z-index: 999;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .investor-exclusives-content {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 20px;
    }
    
    .investor-exclusives-text {
        font-size: 16px;
        font-weight: 600;
        margin: 0;
        letter-spacing: 0.5px;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    /* Contact Top Bar Styles */
    .contact-topbar {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        width: 100%;
        z-index: 1001;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .contact-topbar .contact-info {
        gap: 0;
    }
    
    .contact-topbar .contact-item {
        font-size: 14px;
        font-weight: 400;
    }
    
    .contact-topbar .contact-item a {
        transition: all 0.3s ease;
        text-decoration: underline !important;
    }
    
    .contact-topbar .contact-item a:hover {
        opacity: 0.8;
        text-decoration: none !important;
    }
    
    .contact-topbar .contact-item i {
        font-size: 12px;
        opacity: 0.9;
    }

    /* Responsive design for contact top bar */
    @media (max-width: 768px) {
        .contact-topbar {
            padding: 8px 0 !important;
            font-size: 12px !important;
            height: 28px !important;
        }
        
        .contact-topbar .contact-item {
            font-size: 11px;
            margin-right: 8px !important;
            margin-bottom: 0 !important;
            text-align: center;
            display: inline-flex;
            align-items: center;
            white-space: nowrap;
        }
        
        .contact-topbar .contact-item:last-child {
            margin-right: 0 !important;
        }

        .investor-exclusives-bar {
            position: fixed;
            top: calc(var(--navbar-total-height-mobile, 9.5rem) - 0.23rem);
            padding-bottom: 0px;
        }
        
        .investor-exclusives-content {
            flex-direction: column;
            gap: 12px;
        }
        
        .investor-exclusives-text {
            font-size: 14px;
            text-align: center;
        }
    }
    
    @media (max-width: 576px) {
        .contact-topbar {
            padding: 6px 0 !important;
        }
        
        .contact-topbar .contact-item {
            margin-right: 6px !important;
            margin-bottom: 0 !important;
            text-align: center;
            display: inline-flex;
            align-items: center;
            font-size: 10px;
            white-space: nowrap;
        }
        
        .contact-topbar .contact-item:last-child {
            margin-right: 0 !important;
        }
    }

    @media (max-width: 480px) {
        .investor-exclusives-bar {
            padding: 10px 0;
            top: calc(var(--navbar-total-height-small, 1.7rem) - 0.23rem);
            padding-bottom: 0px;
        }
        
        .investor-exclusives-text {
            font-size: 13px;
            line-height: 1.4;
        }
    }
    
    /* Adjust navbar when contact top bar is present */
    .contact-topbar + nav.navbar {
        top: 2rem;
    }
    
    @media (max-width: 768px) {
        .contact-topbar + nav.navbar {
            top: 1.7rem;
        }
    }

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
</style>


</head>
<body>
    
    @php
        // Use user_id to fetch header, footer, setting to match product-details
        $user_id = $check->user_id;
        $header = \App\Models\Header::where('user_id', $user_id)->first();
        $footer = \App\Models\Footer::where('user_id', $user_id)->first();
        $setting = \App\Models\Setting::where('user_id', $user_id)->first();
        $customFonts = \App\Models\CustomFont::get();
    @endphp
    
    <!-- Header -->
    
    @if ($header && $header->status == 1)
        {{-- Contact Information Top Bar --}}
        @if($header && $header->show_contact_topbar)
            <div class="contact-topbar" style="background: {{ $header->contact_topbar_bg_color ?? '#000000' }}; padding: 8px 0; font-size: 14px; height: 35px;">
                <div class="container">
                    <div class="row align-items-center justify-content-center">
                        @if($header->contact_phone)
                        <div class="col-3 col-md-auto">
                            <div class="contact-item me-4 mb-1">
                                <i class="fas fa-phone me-2" style="color: {{ $header->contact_topbar_text_color ?? '#ffffff' }} !important;"></i>
                                <a href="tel:{{ $header->contact_phone }}" style="color: {{ $header->contact_topbar_text_color ?? '#ffffff' }} !important;">
                                    {{ $header->contact_phone }}
                                </a>
                            </div>
                        </div>
                        @endif
                        @if($header->contact_email)
                        <div class="col-6 col-md-auto" style="text-align: center;">
                            <div class="contact-item me-4 mb-1">
                                <i class="fas fa-envelope me-2" style="color: {{ $header->contact_topbar_text_color ?? '#ffffff' }} !important;"></i>
                                <a href="mailto:{{ $header->contact_email }}" style="color: {{ $header->contact_topbar_text_color ?? '#ffffff' }} !important;">
                                    {{ $header->contact_email }}
                                </a>
                            </div>
                        </div>
                        @endif
                        @if($header->contact_cta_text)
                        <div class="col-3 col-md-auto">
                            <div class="contact-item mb-1">
                                <i class="fas fa-map-marker-alt me-2" style="color: {{ $header->contact_topbar_text_color ?? '#ffffff' }} !important;"></i>
                                <span style="color: {{ $header->contact_topbar_text_color ?? '#ffffff' }} !important; text-decoration : underline !important;">
                                    {{ $header->contact_cta_text }}
                                </span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif


            @include('layouts.nav')

        
        
        {{-- Investor Exclusives Top Bar - Investment Websites Only --}}
        @if($check && $check->isInvestment() && $header && $header->show_investor_exclusives)
            <div class="investor-exclusives-bar" style="background: {{ $header->topbar_background_color ?? '#1e3a8a' }};">
                <div class="investor-exclusives-content">
                    <a href="{{ $header->investor_exclusives_url ?? '#' }}" style="text-decoration: none;">
                    <p class="investor-exclusives-text" style="color: {{ $header->topbar_text_color ?? '#ffffff' }}; font-size: 13px; padding-top: 5px; text-transform: uppercase; padding-bottom: 4px;">
                        {{ $header->investor_exclusives_text ?? 'Exclusive access for investors' }}
                    </p>
                    </a>
                </div>
            </div>

            {{-- Dynamic Navbar Height Calculator Script --}}
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
                        
                        // Convert to rem (assuming 16px base font size)
                        const totalHeightRem = totalNavHeight / 16;
                        const totalHeightRemMobile = (totalNavHeight + (contactTopbar ? 8 : 0)) / 16;
                        const totalHeightRemSmall = (totalNavHeight - (contactTopbar ? contactTopbarHeight * 0.3 : 0)) / 16;
                        
                        // Main content margin should account for investor bar if present
                        const mainContentMargin = totalWithInvestorBar / 16 + 0.5; // Extra space for clean separation
                        
                        // Set CSS custom properties
                        document.documentElement.style.setProperty('--navbar-total-height', `${totalHeightRem}rem`);
                        document.documentElement.style.setProperty('--navbar-total-height-mobile', `${totalHeightRemMobile}rem`);
                        document.documentElement.style.setProperty('--navbar-total-height-small', `${totalHeightRemSmall}rem`);
                        document.documentElement.style.setProperty('--main-content-margin-top', `${mainContentMargin}rem`);
                    }
                }
                
                // Run on load
                document.addEventListener('DOMContentLoaded', function() {
                    setTimeout(updateNavbarHeights, 50);
                });
                
                // Run on resize
                window.addEventListener('resize', updateNavbarHeights);
                
                // Run after fonts load
                if (document.fonts) {
                    document.fonts.ready.then(updateNavbarHeights);
                }
                
                // Fallback: run after delays
                setTimeout(updateNavbarHeights, 100);
                setTimeout(updateNavbarHeights, 300);
                setTimeout(updateNavbarHeights, 500);
                setTimeout(updateNavbarHeights, 1000);
            </script>
        @endif
    @endif

    <main style="margin-top: 6.5rem">
        <div class="banner" style="background-image: url({{ asset('/uploads/'.$check->user->setting->banner) }}); background-size: cover; background-position: center; background-repeat: no-repeat; min-height: 480px; width: 100%;">
            <div class="client-banner-content">
                <h1 class="display-3 fw-semibold text-shadow">
                    <a href="/" class="text-light">
                        {{ $check->user->setting->title }}
                    </a>
                </h1>
                <h2 class="text-light text-shadow mt-2">
                    {{ $check->user->setting->sub_title }}
                </h2>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mt-4 mb-4" style="font-size: 12px; padding-left: 20px; padding-right: 20px;">
                <div class="position-relative bg- p-4 rounded-3 shadow-sm border"
                    style="width: 100%; max-width: 930px; margin-inline: auto;">
                    <div class="row gy-3 ">
                        <div class="col-lg-3 d-flex align-items-center">
                            <div class="rounded-profile-picture border border-3 border-primary mx-auto"
                                style="border-radius: 50%; border-color: #2e4053 !important; overflow: hidden;">
                                <img src="{{ asset($data->photo ?? null) }}"
                                    style="width: 80px; min-width: 80px; height: 80px; min-height: 80px; object-fit: contain;"
                                    onerror="this.src='{{ asset('uploads/'.$check->setting->logo) }}';">
                            </div>
                        </div>

                        <div class="col-lg-9 d-flex flex-column justify-content-center">
                            <h2 class="fs-1.25 fw-semibold text-center text-lg-start break-all" style="font-size: 1.25rem;">
                                {{ $data->name }} {{ $data->last_name }}
                            </h2>
                            <span class="opacity-75 text-center text-lg-start mt-2"></span>
                            <div class="progress mt-3" role="progressbar"
                                aria-valuenow="{{ $data->donations->where('status', 1)->sum('amount') }}" aria-valuemin="0"
                                aria-valuemax="{{ $data->goal }}" data-primary-color="#2e4053"
                                data-secondary-color="#b7bcc4" data-duration="5" data-goal-reached="true"
                                style="height: 14px">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary fs-1"
                                    style="width: @if($data->goal > 0){{ ($data->donations->where('status', 1)->sum('amount') / $data->goal)*100 }}@else 1 @endif%">
                                    <span style="font-size: 13px; font-weight: bold; margin-top: -2px;"> @if($data->goal > 0){{ round(($data->donations->where('status', 1)->sum('amount') / $data->goal)*100) }}@else 1 @endif% </span>
                                </div>
                            </div>
                            <span class="fw-semibold d-block text-center mt-2">
                                @php
                                    $to = $data->donations->where('status', 1)->sum('amount');
                                @endphp
                                ${{ $to }} <small class="opacity-75 fw-light">of</small> ${{ $data->goal ?? 0 }}
                                <small class="opacity-75 fw-light">raised</small>
                            </span>
                            
                            <!-- Share Icons -->
                            <div class="d-flex justify-content-center gap-2 mt-3">
                                @php
                                    $profileUrl = url('/profile/' . $data->id . '-' . str_replace(' ', '-', $data->name) . '-' . str_replace(' ', '-', $data->last_name));
                                    $shareText = 'Check out ' . $data->name . '\'s fundraising page!';
                                @endphp
                                
                                <!-- Facebook Share -->
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($profileUrl) }}" 
                                   target="_blank" rel="noopener noreferrer" 
                                   class="btn btn-sm btn-outline-primary" title="Share on Facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                
                                <!-- X Share -->
                                          <a href="https://twitter.com/intent/tweet?url={{ urlencode($profileUrl) }}&text={{ urlencode($shareText) }}" 
                                              target="_blank" rel="noopener noreferrer" 
                                              class="btn btn-sm btn-x-share" title="Share on X">
                                    <i class="fa-brands fa-x-twitter"></i>
                                </a>
                                
                                <!-- LinkedIn Share -->
                                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($profileUrl) }}" 
                                   target="_blank" rel="noopener noreferrer" 
                                   class="btn btn-sm btn-outline-primary" title="Share on LinkedIn">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                                
                                <!-- WhatsApp Share -->
                                <a href="https://wa.me/?text={{ urlencode($shareText . ' ' . $profileUrl) }}" 
                                   target="_blank" rel="noopener noreferrer" 
                                   class="btn btn-sm btn-outline-success" title="Share on WhatsApp">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                                
                                <!-- Copy Link -->
                                <button type="button" class="btn btn-sm btn-outline-secondary" 
                                        onclick="copyToClipboard('{{ $profileUrl }}')" 
                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Copy URL">
                                    <i class="fas fa-link"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <span class="position-absolute top-0 end-0 m-2 opacity-50 small">
                        Last updated {{ $data->updated_at->diffForHumans() }}
                    </span>
                    <a href="/profile/{{ $data->id }}-{{ $data->name }}-{{ $data->last_name }}"
                        class="" target="_blank"></a>
                </div>
            </div>
        </div>

        <section>
            <div class="container">
                <div class="row justify-content-center mb-5">
                    <div class="col-md-10">
                        <div class="row justify-content-center gy-3">

                            <div class="col-6 col-md-3 text-center position-relative">
                                <i class="fas fa-hand-holding-usd fs-4" role="img" aria-hidden="true" style="font-size: 4rem !important;"></i>
                                <a href="#profile-donation-form" class="stretched-link d-block text-center mt-4"
                                    style="white-space:nowrap; color: #2e4053">
                                    Donate
                                    <i class="fas fa-arrow-down ms-1" role="img" aria-hidden="true"></i>
                                </a>
                            </div>

                            <div class="col-6 col-md-3 text-center position-relative">
                                <i class="fas fa-comments fs-4" role="img" aria-hidden="true" style="font-size: 4rem !important;"></i>
                                <button type="button"
                                    class="btn btn-link stretched-link d-block mx-auto p-0 mt-4"
                                    data-bs-toggle="modal" data-bs-target="#sendMessageModal"
                                    style="white-space:nowrap; color: #2e4053">
                                    Send message
                                    <i class="fas fa-arrow-down ms-1" role="img" aria-hidden="true"></i>
                                </button>
                            </div>




                        </div>
                    </div>
                </div>
                <div
                    class="d-flex flex-column justify-content-center text-center p-5 h-100 text-dark rounded-4 bg-light lead w-md-85 mx-auto break-all" style="background-color: #ebebeb !important;">
                    {!! $data->description !!}
                </div>
            </div>
        </section>

        <section class="text- bg- section-border- " id="b2dd141f-e084-45c7-ba93-d8b6158d65af" data-section=""
                    style="background-image: url(); --overlay-color: ; --overlay-opacity: %; --section-name: '';">
                    <div class="block-container container " id="block-086fc842-f2e9-4d56-af2e-be42317d11e7"
                        data-block="" data-template="7e729e7e3c534cbf918a45b5540afa84"
                        data-action=""
                        style="margin-top: 3rem;">


                        <form method="POST" action="/donations" class="donation-form-block" method="POST" id="profile-donation-form">
                            @csrf
                            <div class="col-12 col-md-10 col-lg-8 col-xl-6 mx-auto">
                                <div class="card border-primary shadow" style="border-width: 3px; border-color: #2e4053 !important;">
                                    <div class="card-header bg-primary border-primary rounded-0 text-center text-white fs-2"
                                        style="border-width: 3px; border-color: #2e4053 !important; background-color: #2e4053 !important;">
                                        Make a donation
                                    </div>
                                    <div class="card-body">
                                        <input type="hidden" name="profile_uuid" value="">

                                        <input type="hidden" name="team_uuid" value="">

                                        <div class="row gy-3">
                                            <div
                                                class="col-12 d-flex flex-column justify-content-center align-items-center">
                                                <label
                                                    for="178bb66b-0348-4581-8bee-2b14bc8b1949-4e963109-9506-49a8-b609-a0929944c1b2"
                                                    class="form-label " style="color: #000; font-weight: bold;">
                                                    Donate To {{$data->name}}
                                                </label>
                                                <div></div>

                                                <div class="d-flex justify-content-center flex-wrap">
                                                    <input type="hidden" data-change-amount="1"
                                                        data-name="4e963109-9506-49a8-b609-a0929944c1b2" data-amount="500"
                                                        class="form-check btn-check select-amount"
                                                        name="user_id"
                                                        id="178bb66b-0348-4581-8bee-2b14bc8b1949-4e963109-9506-49a8-b609-a0929944c1b24479f3e5-aac8-4044-ac77-7c3192197e63"
                                                        value="{{ $data->id }}" autocomplete="off">
                                                    {{-- <label class="btn btn-outline-primary m-1"
                                                    style="color: #2e4053 !important; border-color: #2e4053 !important;"
                                                        for="178bb66b-0348-4581-8bee-2b14bc8b1949-4e963109-9506-49a8-b609-a0929944c1b24479f3e5-aac8-4044-ac77-7c3192197e63">Donate
                                                        to the PTO</label> --}}
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="input-group input-group-lg">
                                                    <span class="input-group-text fw-light fs-1.5 fs-lg-2 border-primary"
                                                        style="border-width: 2px; border-right-width: 0; border-color: #2e4053 !important;">$</span>
                                                    <input type="number" placeholder="0"
                                                        class="form-control fs-2 fs-lg-4 text-center border-primary"
                                                        style="border-width: 2px; border-color: #2e4053 !important;" name="donation_amount" value="">
                                                    <span class="input-group-text fw-light fs-1.5 fs-lg-2 border-primary"
                                                        style="border-width: 2px; border-left-width: 0; border-color: #2e4053 !important;">.00</span>
                                                </div>
                                                <input type="hidden" name="amount" value="">
                                                <div class="text-center">
                                                    <small class="form-text text-muted">
                                                        * The minimum donation amount is 8.
                                                    </small>
                                                </div>
                                            </div>

                                            <div class="col-12 d-flex justify-content-center align-items-center">
                                                <div class="card border-primary shadow p-2" style="border-width: 2px; border-color: #2e4053 !important;">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" role="switch"
                                                            id="pay_fees" name="pay_fees" checked="">
                                                        <label class="form-check-label fw-semibold" for="pay_fees">
                                                            I elect to pay the fees
                                                        </label>
                                                        <i role="button"
                                                class="fa-solid fa-circle-info text-info btn-modal-info"
                                                data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="By selecting this option, you elect to pay the credit card and transaction fees for this donation. The fees will be displayed in the next step."></i>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(function (tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-12">
                                                <label for="first_name" class="form-label fw-semibold required">
                                                    First name
                                                </label>
                                                <input type="text" class="form-control" id="first_name"
                                                    name="first_name" value="">
                                            </div>

                                            <div class="col-12">
                                                <label for="last_name" class="form-label fw-semibold required">
                                                    Last name
                                                </label>
                                                <input type="text" class="form-control" id="last_name"
                                                    name="last_name" value="">
                                            </div>


                                            <div class="col-12">
                                                <label for="email" class="form-label fw-semibold required">
                                                    Email address
                                                </label>
                                                <input type="email" class="form-control" id="email" name="email"
                                                    value="">
                                            </div>

                                            <div class="col-12">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        id="anonymous_donation" name="anonymous_donation">
                                                    <label class="form-check-label fw-semibold" for="anonymous_donation">
                                                        Anonymous
                                                    </label>
                                                    <i role="button"
                                            class="fa-solid fa-circle-info text-info btn-modal-info"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="Selecting this option will hide your name from everyone but the organizer."></i>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <label for="leave_comment" class="form-label fw-semibold text-capitalize">
                                                    comment
                                                </label>
                                                <textarea class="form-control" id="leave_comment" name="leave_comment" rows="6"></textarea>
                                            </div>



                                            <input type="hidden" name="template"
                                                value="7e729e7e3c534cbf918a45b5540afa84">

                                            <div class="col-12">
                                                <small class="text-muted">This form is protected by reCAPTCHA and the
                                                    Google <a href="https://policies.google.com/privacy">Privacy Policy</a>
                                                    and <a href="https://policies.google.com/terms">Terms of Service</a>
                                                    apply.</small>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="card-footer bg-primary border-primary rounded-0 p-0"
                                        style="border-width: 3px; border-color: #2e4053 !important;">
                                        <button type="submit"
                                            class="btn btn-primary btn-lg w-100 h-100 text-white rounded-0 shadow-none" style="background: #2e4053 !important; border-color: #2e4053 !important;">
                                            Donate
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
        </section>

        <div class="row justify-content-center">
            <div class="col-md-8 mt-4">
                <p class="lead text-center mt-3">
                    {{ $donations->count() }} donations have been made to this User
                </p>
            </div>
            <div class="col-8 mt-4">
                <div class="row">
                    @foreach ($donations as $item)
                        <div class="col-lg-4 mt-2" style="font-size: 12px;">
                            <div class="p-3 rounded text-center position-relative" style="background: #ebebeb">
                                <h4 class="fw-semibold">
                                    ${{ $item->amount }}
                                </h4>

                                <small class="d-block opacity-75 mt-2">
                                    @if ($item->hide != 1)
                                    <span title="Donor">{{ $item->first_name }} {{ $item->last_name }}</span>
                                    @endif
                                    <i class="fa-solid fa-arrow-right-long fa-fw mx-1 text-success" aria-hidden="true"></i>
                                    <span title="Participant">{{ $item->user->name }} {{ $item->user->last_name }}</span>
                                </small>

                                @if ($item->comment)
                                    <span style="position: absolute; top: 10px; right: 10px; font-size: 17px; cursor:pointer;" data-bs-toggle="modal" data-bs-target="#donationMessageModal-{{ $item->id }}">
                                        <i style="color: #000 !important" class="fa-solid fa-message fa-fw mx-1 text-primary" aria-hidden="true" title="Message"></i>
                                    </span>
                                    <!-- Modal for donation message -->
                                    <div class="modal" id="donationMessageModal-{{ $item->id }}" tabindex="-1" aria-labelledby="donationMessageModalLabel-{{ $item->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="donationMessageModalLabel-{{ $item->id }}">
                                            {{ $item->first_name }} {{ $item->last_name }} - ${{ number_format($item->amount, 2) }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <h5>{{ $item->comment ?? 'No message.' }}</h5>
                                        </div>
                                        </div>
                                    </div>
                                    </div>
                                @endif


                                <small class="d-block opacity-75 mt-3 p-2 rounded" style="backdrop-filter: brightness(1.5);">
                                    <i class="fa-solid fa-calendar-days me-1" aria-hidden="true"></i>
                                        {{ $item->created_at->diffForHumans() }}
                                </small>

                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Messages Section -->
        <div class="row justify-content-center mt-5 mb-5">
            <div class="col-md-8">
                <p class="lead text-center mt-3">
                    {{ isset($messages) ? $messages->count() : 0 }} messages have been sent to {{ $data->name }}
                </p>
            </div>
            <div class="col-8 mt-4">
                <div class="row">
                    @if(isset($messages))
                        @foreach ($messages as $message)
                            <div class="col-lg-6 mt-2" style="font-size: 12px;">
                                <div class="p-3 rounded position-relative" style="background: #ebebeb">
                                    <h5 class="fw-semibold text-primary">
                                        {{ $message->sender_name }}
                                    </h5>
                                    <small class="text-muted d-block mb-2">{{ $message->sender_email }}</small>
                                    
                                    <p class="mt-3">{{ $message->message }}</p>

                                    <small class="d-block opacity-75 mt-3 p-2 rounded" style="backdrop-filter: brightness(1.5);">
                                        <i class="fa-solid fa-calendar-days me-1" aria-hidden="true"></i>
                                        {{ $message->created_at->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

    </main>

    <!-- Send Message Modal -->
    <div class="modal" id="sendMessageModal" tabindex="-1" aria-labelledby="sendMessageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #2e4053; color: white;">
                    <h5 class="modal-title" id="sendMessageModalLabel">Send Message to {{ $data->name }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('student.message') }}" method="POST">
                    @csrf
                    <input type="hidden" name="student_id" value="{{ $data->id }}">
                    
                    <div class="modal-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        
                        <div class="mb-3">
                            <label for="sender_name" class="form-label fw-semibold">Your Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="sender_name" name="sender_name" required maxlength="100">
                        </div>
                        
                        <div class="mb-3">
                            <label for="sender_email" class="form-label fw-semibold">Your Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="sender_email" name="sender_email" required maxlength="150">
                        </div>
                        
                        <div class="mb-3">
                            <label for="message" class="form-label fw-semibold">Message <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="message" name="message" rows="5" required maxlength="5000" placeholder="Write your message here..."></textarea>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" style="background-color: #2e4053; border-color: #2e4053;">
                            <i class="fas fa-paper-plane me-1"></i> Send Message
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('layouts.new-footer')

    <!-- Include DataTables and jQuery CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<!-- Payment Funnel Tracking -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="{{ asset('js/payment-funnel-tracking.js') }}"></script>

<script>
    $(document).ready(function() {
        $.fn.dataTable.ext.errMode = 'none';
        // Initialize DataTable with default search disabled
        const table = $('#studentTable').DataTable({
            paging: true,
            searching: true,
            ordering: true,
            info: true,
            pageLength: 25
        });

        // Link the custom search input to the DataTable search
        $('#search').on('keyup', function() {
            const value = $(this).val();
            table.search(value).draw();
        });
    });
    
    // Copy to Clipboard Function
    function copyToClipboard(text) {
        const tempInput = document.createElement('input');
        tempInput.value = text;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);
        
        // Show success feedback
        const btn = event.currentTarget;
        const originalTitle = btn.title;
        btn.title = 'Link copied!';
        btn.classList.add('active');

        const tooltipInstance = bootstrap.Tooltip.getOrCreateInstance(btn);
        tooltipInstance.setContent({ '.tooltip-inner': 'Link copied!' });
        tooltipInstance.show();
        
        setTimeout(() => {
            btn.title = originalTitle;
            tooltipInstance.setContent({ '.tooltip-inner': originalTitle });
            tooltipInstance.hide();
            btn.classList.remove('active');
        }, 2000);
    }
</script>

