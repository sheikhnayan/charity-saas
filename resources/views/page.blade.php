@php
$state = $data && $data->state ? (is_string($data->state) ? json_decode($data->state, true) : $data->state) : [];
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $data->name ?? 'Page' }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>body{background:#f9fafb;}</style>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('auction.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom Fonts CSS -->
    <link href="{{ route('fonts.css') }}" rel="stylesheet">
    <!-- Shopping Cart CSS -->
    <link rel="stylesheet" href="{{ asset('css/cart.css') }}">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    
    <!-- Payment Funnel Tracking -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/payment-funnel-tracking.js') }}"></script>
    <style>
    /* Base Styles for Components */
    #studentTable {
        background-color: #fff !important;
        border: none !important;
    }

    #studentTable th, #studentTable td {
        background-color: #fff !important;
        border: none !important;
    }

    #studentTable tbody tr {
        background-color: #fff !important;
    }

    #studentTable_filter, #studentTable_length {
        display: none;
    }

    #studentTable thead {
        display: none;
    }

    .non-float{
        margin-bottom: -111px;
    }

    /* Auction Components Styles */
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

    /* Universal Inner Section Wrapper - Clean & Invisible */
    .page-inner-section {
        width: 100%;
        margin: 0;
        padding: 0;
        background: transparent;
        border: none;
        box-sizing: border-box;
    }

    .page-inner-section .inner-column {
        padding: 0;
        margin: 0;
        box-sizing: border-box;
    }
    
    /* Inner section styling support */
    .inner-section-wrapper {
        width: 100%;
        box-sizing: border-box;
        position: relative;
    }
    
    .inner-section-wrapper.full-width {
        width: 100vw;
        margin-left: calc(-50vw + 50%);
        margin-right: calc(-50vw + 50%);
    }
    
    /* Parallax background support for inner sections */
    .inner-section-wrapper.has-parallax {
        background-attachment: fixed;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        min-height: 100vh;
    }
    
    /* Enhanced Mobile Margin/Border Fixes */
    @media (max-width: 768px) {
        /* Parallax fixes */
        .inner-section-wrapper.has-parallax {
            background-attachment: scroll;
            min-height: 50vh;
        }
        
        .inner-section-wrapper.full-width {
            width: 100%;
            margin-left: 0;
            margin-right: 0;
        }
        
        /* Mobile Edge-to-Edge Improvements */
        body {
            margin: 0 !important;
            padding: 0 !important;
            overflow-x: hidden !important;
        }
        
        /* Bootstrap Container Mobile Overrides - Bring content closer to edges */
        .container {
            padding-left: 8px !important;
            padding-right: 8px !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
            max-width: 100% !important;
            width: 100% !important;
        }
        
        .container-fluid {
            padding-left: 8px !important;
            padding-right: 8px !important;
        }
        
        /* Row and Column Mobile Spacing */
        .row {
            margin-left: -4px !important;
            margin-right: -4px !important;
        }
        
        .row > [class*="col-"] {
            padding-left: 4px !important;
            padding-right: 4px !important;
        }
        
        /* Component Mobile Margin Improvements */
        .component {
            margin-left: 0 !important;
            margin-right: 0 !important;
            max-width: 100% !important;
            overflow-x: hidden !important;
        }
        
        /* Main content mobile adjustments */
        main {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
        
        /* Component content mobile spacing */
        .component-content {
            padding-left: 8px !important;
            padding-right: 8px !important;
        }
        
        /* Inner sections mobile edge fixes */
        .inner-section-frontend,
        .inner-section-fullwidth {
            margin-left: 0 !important;
            margin-right: 0 !important;
            padding-left: 8px !important;
            padding-right: 8px !important;
        }
        
        /* Remove default margins from page elements on mobile */
        .my-5 {
            margin-top: 1rem !important;
            margin-bottom: 1rem !important;
        }
        
        .mb-4 {
            margin-bottom: 1rem !important;
        }
    }

    /* Component Styling - All components get consistent spacing */
    .page-component {
        width: 100%;
        box-sizing: border-box;
        position: relative;
    }

    /* Responsive Grid System for Inner Sections */
    .inner-section-grid {
        display: grid;
        width: 100%;
        gap: 0;
        grid-template-columns: 1fr;
    }

    .inner-section-grid.cols-2 {
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .inner-section-grid.cols-3 {
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }

    .inner-section-grid.cols-4 {
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
    }

    .inner-section-grid.cols-5 {
        grid-template-columns: repeat(5, 1fr);
        gap: 15px;
    }

    .inner-section-grid.cols-6 {
        grid-template-columns: repeat(6, 1fr);
        gap: 10px;
    }

    /* Responsive breakpoints for grid columns */
    @media (max-width: 1200px) {
        .inner-section-grid.cols-6 {
            grid-template-columns: repeat(4, 1fr);
        }
        .inner-section-grid.cols-5 {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    @media (max-width: 992px) {
        .inner-section-grid.cols-6,
        .inner-section-grid.cols-5,
        .inner-section-grid.cols-4 {
            grid-template-columns: repeat(3, 1fr);
        }
        .inner-section-grid.cols-3 {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .inner-section-grid.cols-6,
        .inner-section-grid.cols-5,
        .inner-section-grid.cols-4,
        .inner-section-grid.cols-3,
        .inner-section-grid.cols-2 {
            grid-template-columns: 1fr;
            gap: 15px;
        }
    }

    @media (max-width: 576px) {
        .inner-section-grid {
            gap: 10px;
        }
    }

    /* Video component responsive styles */
    .video-component {
        width: 100%;
        max-width: 100%;
        position: relative;
    }
    
    .video-component iframe,
    .video-component video {
        width: 100% !important;
        height: auto !important;
        max-width: 100%;
        aspect-ratio: 16/9;
    }
    
    @media (max-width: 768px) {
        .video-component iframe,
        .video-component video {
            width: 100% !important;
            height: auto !important;
            min-height: 200px;
            max-height: 300px;
        }
    }
    
    @media (max-width: 480px) {
        .video-component iframe,
        .video-component video {
            width: 100% !important;
            height: auto !important;
            min-height: 180px;
            max-height: 220px;
        }
    }

    @php
        // Generate comprehensive responsive CSS for all components and nested components
        function generateResponsiveStyles($state) {
            $css = '';
            
            if (!is_array($state)) return $css;
            
            foreach ($state as $index => $component) {
                // Handle main components (including auto-wrapped ones)
                if (isset($component['responsiveStyles'])) {
                    $componentId = "component-{$index}";
                    $css .= generateComponentResponsiveCSS($componentId, $component['responsiveStyles']);
                }
                
                // Handle auto-wrapped components
                if (isset($component['type']) && $component['type'] === 'inner-section') {
                    // Check for nested components in auto-wrapped inner-sections
                    if (isset($component['nestedComponents']) && is_array($component['nestedComponents'])) {
                        foreach ($component['nestedComponents'] as $columnIndex => $columnComponents) {
                            if (is_array($columnComponents)) {
                                foreach ($columnComponents as $nestedIndex => $nestedComponent) {
                                    if (isset($nestedComponent['responsiveStyles'])) {
                                        $nestedId = "nested-{$columnIndex}-{$nestedIndex}";
                                        $css .= generateComponentResponsiveCSS($nestedId, $nestedComponent['responsiveStyles']);
                                    }
                                }
                            }
                        }
                    }
                } else {
                    // Handle legacy components that might have been auto-wrapped
                    if (isset($component['responsiveStyles'])) {
                        $autoWrappedId = "auto-wrapped-{$index}";
                        $css .= generateComponentResponsiveCSS($autoWrappedId, $component['responsiveStyles']);
                    }
                }
            }
            
            return $css;
        }
        
        function generateComponentResponsiveCSS($componentId, $styles) {
            $css = "/* Component {$componentId} responsive styles */\n";
            
            // Desktop styles (default - 992px and up)
            if (isset($styles['desktop']) && is_array($styles['desktop'])) {
                $desktopStyles = [];
                foreach ($styles['desktop'] as $prop => $value) {
                    if (!empty($value) && trim($value) !== '') {
                        $desktopStyles[] = "{$prop}: {$value}";
                    }
                }
                if (!empty($desktopStyles)) {
                    $css .= "#{$componentId} { " . implode('; ', $desktopStyles) . "; }\n";
                }
            }
            
            // Tablet styles (768px to 991px)
            if (isset($styles['tablet']) && is_array($styles['tablet'])) {
                $tabletStyles = [];
                foreach ($styles['tablet'] as $prop => $value) {
                    if (!empty($value) && trim($value) !== '') {
                        $tabletStyles[] = "{$prop}: {$value} !important";
                    }
                }
                if (!empty($tabletStyles)) {
                    $css .= "@media screen and (max-width: 991px) and (min-width: 768px) {\n";
                    $css .= "  #{$componentId} { " . implode('; ', $tabletStyles) . "; }\n";
                    $css .= "}\n";
                }
            }
            
            // Mobile styles (up to 767px)
            if (isset($styles['mobile']) && is_array($styles['mobile'])) {
                $mobileStyles = [];
                foreach ($styles['mobile'] as $prop => $value) {
                    if (!empty($value) && trim($value) !== '') {
                        $mobileStyles[] = "{$prop}: {$value} !important";
                    }
                }
                if (!empty($mobileStyles)) {
                    $css .= "@media screen and (max-width: 767px) {\n";
                    $css .= "  #{$componentId} { " . implode('; ', $mobileStyles) . "; }\n";
                    $css .= "}\n";
                }
            }
            
            return $css;
        }
        
        echo generateResponsiveStyles($state);
    @endphp
</style>
</head>
<body style="overflow-x: hidden; background-color: {{ $data->background_color ?? '#fff'}};">
    <!-- Shopping Cart System - Load with explicit completion handler -->
    <script>
        // Flag to track if cart.js has loaded
        window._cartJsLoaded = false;
        window._cartInitQueue = [];
        
        // Initialize cart after verification - DEFINE BEFORE LOADING cart.js
        function initCartNow() {
            console.log('🛒 [Page] Cart system initializing...');
            console.log('🛒 [Page] cart.js loaded:', window._cartJsLoaded);
            console.log('🛒 [Page] jQuery available:', typeof jQuery !== 'undefined');
            console.log('🛒 [Page] window.ShoppingCart:', typeof window.ShoppingCart);
            console.log('🛒 [Page] All globals:', Object.keys(window).filter(k => k.includes('Cart') || k.includes('cart')));
            
            if (window.ShoppingCart && typeof window.ShoppingCart.init === 'function') {
                try {
                    console.log('✅ [Page] ShoppingCart found, initializing...');
                    const initPromise = window.ShoppingCart.init();
                    if (initPromise && typeof initPromise.catch === 'function') {
                        console.log('✅ [Page] ShoppingCart.init() is async, handling as promise...');
                        initPromise.catch(error => {
                            console.error('❌ [Page] ShoppingCart.init() promise rejected:', error);
                        });
                    } else {
                        console.log('✅ [Page] ShoppingCart.init() called');
                    }
                } catch (error) {
                    console.error('❌ [Page] Error calling ShoppingCart.init():', error);
                    console.log('Stack trace:', error.stack);
                }
            } else {
                if (!window._cartJsLoaded) {
                    console.warn('⚠️ [Page] cart.js not loaded yet, will retry...');
                    setTimeout(initCartNow, 300);
                } else {
                    console.error('❌ [Page] cart.js loaded but ShoppingCart not defined');
                    console.log('❌ [Page] Checking window object for cart-related properties...');
                    const cartKeys = Object.keys(window).filter(k => k.toLowerCase().includes('cart'));
                    console.log('❌ [Page] Cart-related keys found:', cartKeys);
                }
            }
        }
        
        // Define cart loading complete function
        window._onCartLoaded = function() {
            console.log('✅ [Page] cart.js script loaded');
            window._cartJsLoaded = true;
            
            // Try to initialize immediately
            initCartNow();
        };
    </script>
    <script src="{{ asset('js/cart.js') }}" onload="window._onCartLoaded()"></script>
    <script>
        // Start initialization
        setTimeout(initCartNow, 100);
    </script>
    @php
        $url = url()->current();
        $domain = parse_url($url, PHP_URL_HOST);
        $check = \App\Models\Website::where('domain', $domain)->first();
        $groups = \App\Models\User::where('website_id', $check->id)->where('role','group_leader')->get();
        $teachers = \App\Models\Teacher::where('website_id', $check->id)->where('is_active', true)->get();
        $auction = \App\Models\Auction::where('website_id', $check->id)->where('status',1)->latest()->get();

        $header = \App\Models\Header::where('website_id', $check->id)->first();
        $footer = \App\Models\Footer::where('website_id', $check->id)->first();
    @endphp
    
    @if ($header && $header->status == 1)
        @include('layouts.nav')
    @endif
    
    <main style="margin-top: 6.9rem;">
        @session('success')
            <div class="alert alert-success mt-4" role="alert">
                {{ $value }}
            </div>
        @endsession

        @session('error')
            <div class="alert alert-danger mt-4" role="alert">
                {{ $value }}
            </div>
        @endsession
        @session('errors')
            <div class="alert alert-danger mt-4" role="alert">
                @foreach($errors->all() as $value)
                    <div>{{ $value }}</div>
                @endforeach
            </div>
        @endsession
        @foreach($state as $key => $data)
                @php $type = $data['type'] ?? ''; @endphp
                @if($key == 0 && $data['type'] == 'custom-banner')
                    @switch($type)
                        @case('custom-banner')
                        @php
                            $banner = $data['customBannerData'] ?? [];
                        @endphp
                        <div style="position:relative; text-align:{{ $banner['textAlign'] ?? 'center' }};
                        @if($header->floating == 1)
                            margin-top: -7px;
                        @endif
                        ">
                            @if(!empty($banner['imgSrc']))
                                <img src="{{ $banner['imgSrc'] }}" style="width:100%;height:auto;">
                            @endif
                            @if(!empty($banner['title']))
                                <h3 style="
                                    position:absolute;
                                    top:40%;
                                    left:50%;
                                    transform:translate(-50%,-50%);
                                    color:{{ $banner['titleColor'] ?? '#fff' }};
                                    text-shadow:{{ $banner['titleShadow'] ?? '0 2px 8px rgba(0,0,0,0.5)' }};
                                    font-size:{{ $banner['titleFontSize'] ?? '2em' }};
                                    width: 90%;
                                    text-align:{{ $banner['textAlign'] ?? 'center' }};
                                " class="custom-banner-title">
                                    {{ $banner['title'] }}
                                </h3>
                            @endif
                            @if(!empty($banner['subtitle']))
                                <p style="
                                    position:absolute;
                                    top:45%;
                                    left:50%;
                                    transform:translate(-50%,-50%);
                                    color:{{ $banner['subtitleColor'] ?? '#fff' }};
                                    text-shadow:{{ $banner['subtitleShadow'] ?? '0 2px 8px rgba(0,0,0,0.5)' }};
                                    font-size:{{ $banner['subtitleFontSize'] ?? '1.2em' }};
                                    width: 90%;
                                    text-align:{{ $banner['textAlign'] ?? 'center' }};
                                    margin-top: {{ $data['customBannerData']['subtitleMarginTop'] }}
                                ">
                                    {{ $banner['subtitle'] }}
                                </p>
                            @endif
                        </div>
                        @break
                    @endswitch
                @endif
        @endforeach
    <div id="rendered-page">
        @foreach($state as $index =>  $data)
            @php 
                $type = $data['type'] ?? ''; 
                // Skip if this component is nested inside an inner-section
                $isNested = isset($data['isNested']) && $data['isNested'];
                
                // Check if component should be full-width
                $isFullWidth = false;
                $wrapperStyle = $data['wrapperStyle'] ?? [];
                $style = $data['style'] ?? [];
                
                // Check for full-width in various component types
                if ($type === 'inner-section') {
                    $innerSectionData = $data['innerSectionData'] ?? [];
                    $isFullWidth = isset($innerSectionData['fullWidth']) && $innerSectionData['fullWidth'];
                }
                
                // Build component styles
                $componentStyles = '';
                foreach ($wrapperStyle as $k => $v) {
                    if ($v) $componentStyles .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
                }
                foreach ($style as $k => $v) {
                    if ($v) $componentStyles .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
                }
                
                // Add parallax background support
                $hasParallax = false;
                if (!empty($style['backgroundImage']) && isset($style['backgroundAttachment']) && $style['backgroundAttachment'] === 'fixed') {
                    $hasParallax = true;
                }
            @endphp
            
            @if(!$isNested)
                @if($isFullWidth)
                    {{-- Full-width component (no container) --}}
                    <div class="component mb-4 {{ $hasParallax ? 'has-parallax' : '' }}" data-type="{{ $type }}" id="component-{{ $index }}" style="{{ $componentStyles }}">
                @else
                    {{-- Regular component (with container) --}}
                    <div class="container my-5">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="component mb-4" data-type="{{ $type }}" id="component-{{ $index }}" style="{{ $componentStyles }}">
                @endif
                        <div class="component-content">
                                @switch($type)

                                @case('custom-banner')

                                    @if($k != 0)
                                        @php
                                            $banner = $data['customBannerData'] ?? [];
                                        @endphp
                                        <div style="position:relative; text-align:{{ $banner['textAlign'] ?? 'center' }};">
                                            @if(!empty($banner['imgSrc']))
                                                <img src="{{ $banner['imgSrc'] }}" style="width:100%;height:auto;">
                                            @endif
                                            @if(!empty($banner['title']))
                                                <h3 style="
                                                    position:absolute;
                                                    top:40%;
                                                    left:50%;
                                                    transform:translate(-50%,-50%);
                                                    color:{{ $banner['titleColor'] ?? '#fff' }};
                                                    text-shadow:{{ $banner['titleShadow'] ?? '0 2px 8px rgba(0,0,0,0.5)' }};
                                                    font-size:{{ $banner['titleFontSize'] ?? '2em' }};
                                                    width: 90%;
                                                    text-align:{{ $banner['textAlign'] ?? 'center' }};
                                                " class="custom-banner-title">
                                                    {{ $banner['title'] }}
                                                </h3>
                                            @endif
                                            @if(!empty($banner['subtitle']))
                                                <p style="
                                                    position:absolute;
                                                    top:45%;
                                                    left:50%;
                                                    transform:translate(-50%,-50%);
                                                    color:{{ $banner['subtitleColor'] ?? '#fff' }};
                                                    text-shadow:{{ $banner['subtitleShadow'] ?? '0 2px 8px rgba(0,0,0,0.5)' }};
                                                    font-size:{{ $banner['subtitleFontSize'] ?? '1.2em' }};
                                                    width: 90%;
                                                    text-align:{{ $banner['textAlign'] ?? 'center' }};
                                                    margin-top: {{ $data['customBannerData']['subtitleMarginTop'] }}
                                                ">
                                                    {{ $banner['subtitle'] }}
                                                </p>
                                            @endif
                                        </div>
                                    @endif

                                @break

                                @case('donor-list')
                                @php
                                        $style = $data['style'] ?? [];
                                        $wrapperStyle = $data['wrapperStyle'] ?? [];
                                        $wrapperStyleStr = '';
                                        foreach ($wrapperStyle as $k => $v) {
                                            if ($v) $wrapperStyleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
                                        }
                                        $alertStyleStr = '';
                                        foreach ($style as $k => $v) {
                                            if ($v) $alertStyleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
                                        }
                                        // dd($alertStyleStr);
                                    @endphp
                                    @php
                                            // Ensure background color is applied to the wrapper
                                            if (!empty($style['backgroundColor'])) {
                                                $wrapperStyleStr .= 'background-color:' . $style['backgroundColor'] . ';';
                                            }
                                        @endphp
                                    <div style="{{ $alertStyleStr }} {{ $wrapperStyleStr }}">
                                        <div class="col-12 mt-4 donor-list-component">
                                            <div class="col-12 mt-4">
                                                <div class="table-responsive">
                                                    <table id="studentTable" class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Name</th>
                                                                <th>Grade</th>
                                                                <th>Grade</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody style="background-color: {{ $style['backgroundColor'] ?? '#fff'}} !important;">
                                                            @php
                                                                $donation = App\Models\Donation::where('website_id', $check->id)->get();
                                                            @endphp
                                                            @foreach($donation->chunk(3) as $donate)
                                                                <tr>
                                                                    @foreach($donate as $don)
                                                                        <td data-label="Donor Info" style="background-color: {{ $style['backgroundColor'] ?? '#fff'}} !important;">
                                                                            <div class="col-lg-12" style="font-size: 12px;">
                                                                                <div class="p-3 rounded text-center position-relative" style="background: #ebebeb">
                                                                                    <h4 class="fw-semibold">
                                                                                        $ {{ $don->amount }}
                                                                                    </h4>
                                                                                    <small class="d-block opacity-75 mt-2">
                                                                                        @if($don->hide == 0)
                                                                                            <span title="Donor">{{ $don->first_name }} {{ $don->last_name }}</span>
                                                                                        @endif
                                                                                        <i class="fa-solid fa-arrow-right-long fa-fw mx-1 text-success" aria-hidden="true"></i>
                                                                                        @if($don->type == 'student')
                                                                                            <span title="Participant">{{ $don->user->name }}</span>
                                                                                        @else
                                                                                            <span title="Participant">{{ $check->name }}</span>
                                                                                        @endif
                                                                                    </small>
                                                                                    <small class="d-block opacity-75 mt-3 p-2 rounded" style="backdrop-filter: brightness(1.5);">
                                                                                        <i class="fa-solid fa-calendar-days me-1" aria-hidden="true"></i>
                                                                                        {{ $don->created_at->format('M d, Y') }}
                                                                                    </small>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    @endforeach
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @break

                                @case('section-title')
                                @php
                                        $style = $data['style'] ?? [];
                                        $wrapperStyle = $data['wrapperStyle'] ?? [];
                                        $wrapperStyleStr = '';
                                        foreach ($wrapperStyle as $k => $v) {
                                            if ($v) $wrapperStyleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
                                        }
                                        $alertStyleStr = '';
                                        foreach ($style as $k => $v) {
                                            if ($v) {
                                                $cssKey = strtolower(preg_replace('/([A-Z])/', '-$1', $k));
                                                // Add !important to text-align to override any default styles
                                                if ($cssKey === 'text-align') {
                                                    $alertStyleStr .= $cssKey . ":" . $v . " !important;";
                                                } else {
                                                    $alertStyleStr .= $cssKey . ":" . $v . ";";
                                                }
                                            }
                                        }
                                        // dd($alertStyleStr);
                                    @endphp
                                    @php
                                            // Ensure background color is applied to the wrapper
                                            if (!empty($style['backgroundColor'])) {
                                                $wrapperStyleStr .= 'background-color:' . $style['backgroundColor'] . ';';
                                            }
                                        @endphp
                                    <h2 style="{{ $alertStyleStr }} {{ $wrapperStyleStr }}">{{ $data['text'] ?? '' }}</h2>
                                    @break
                                @case('divider')
                                    <hr style="height:{{ $data['style']['height'] ?? '2px' }};background:{{ $data['style']['backgroundColor'] ?? '#eee' }};border:none;">
                                    @break

                                @case('alert-message')
                                    @php
                                        $style = $data['style'] ?? [];
                                        $wrapperStyle = $data['wrapperStyle'] ?? [];
                                        $wrapperStyleStr = '';
                                        foreach ($wrapperStyle as $k => $v) {
                                            if ($v) $wrapperStyleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
                                        }
                                        $alertStyleStr = '';
                                        foreach ($style as $k => $v) {
                                            if ($v) $alertStyleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
                                        }
                                    @endphp
                                    <div class="alert-message-component mb-3" style="{{ $wrapperStyleStr }}">
                                        <div style="{{ $alertStyleStr }}">
                                            {{ $data['html'] ?? '' }}
                                        </div>
                                    </div>
                                @break

@case('social-share')
    @php
        $style = $data['style'] ?? [];
        $wrapperStyle = $data['wrapperStyle'] ?? [];
        $platforms = $data['socialPlatforms'] ?? [
            'facebook' => ['enabled' => true, 'url' => '#'],
            'x' => ['enabled' => false, 'url' => '#'],
            'linkedin' => ['enabled' => false, 'url' => '#'],
            'pinterest' => ['enabled' => false, 'url' => '#'],
            'instagram' => ['enabled' => false, 'url' => '#'],
        ];
        // Build wrapper style string (for margin, etc.)
        $wrapperStyleStr = '';
        foreach ($wrapperStyle as $k => $v) {
            if ($v) $wrapperStyleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
        }
        // Build card style string (for background, etc.)
        $cardStyleStr = '';
        foreach ($style as $k => $v) {
            if ($v) $cardStyleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
        }
    @endphp
    <section class="social-share-component" style="{{ $wrapperStyleStr }}">
        <div class="row gy-3 gy-md-5 justify-content-center align-items-center" style="{{ $cardStyleStr }}">
            @if(!empty($platforms['facebook']['enabled']))
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="d-flex justify-content-center align-items-center">
                        <a class="text-center btn-facebook-share" href="{{ $platforms['facebook']['url'] ?? '#' }}" target="_blank" style="color: #3b5998">
                            <i class="fab fa-facebook-square fs-4 text-facebook" style="font-size: 4rem !important"></i>
                            <h4 style="color: {{ $style['color'] ?? '#000'}} !important" class="text-dark mt-2 mt-md-3 fs-1.5">Share on Facebook</h4>
                        </a>
                    </div>
                </div>
            @endif
            @if(!empty($platforms['x']['enabled']))
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="d-flex justify-content-center align-items-center">
                        <a class="text-center btn-x-share" href="{{ $platforms['x']['url'] ?? '#' }}" target="_blank" style="color: #000">
                            <img src="{{ asset('x.png') }}" width="64px">
                            <h4 style="color: {{ $style['color'] ?? '#000'}} !important" class="text-dark mt-2 mt-md-3 fs-1.5">Share on X</h4>
                        </a>
                    </div>
                </div>
            @endif
            @if(!empty($platforms['linkedin']['enabled']))
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="d-flex justify-content-center align-items-center">
                        <a class="text-center btn-linkedin-share" href="{{ $platforms['linkedin']['url'] ?? '#' }}" target="_blank" style="color: #0077b5">
                            <i class="fab fa-linkedin fs-4 text-linkedin" style="font-size: 4rem !important"></i>
                            <h4 style="color: {{ $style['color'] ?? '#000'}} !important" class="text-dark mt-2 mt-md-3 fs-1.5">Share on LinkedIn</h4>
                        </a>
                    </div>
                </div>
            @endif
            @if(!empty($platforms['pinterest']['enabled']))
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="d-flex justify-content-center align-items-center">
                        <a class="text-center btn-pinterest-share" href="{{ $platforms['pinterest']['url'] ?? '#' }}" target="_blank" style="color: #e60023">
                            <i class="fab fa-pinterest fs-4" style="font-size: 4rem !important"></i>
                            <h4 style="color: {{ $style['color'] ?? '#000'}} !important" class="text-dark mt-2 mt-md-3 fs-1.5">Share on Pinterest</h4>
                        </a>
                    </div>
                </div>
            @endif
            @if(!empty($platforms['instagram']['enabled']))
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="d-flex justify-content-center align-items-center">
                        <a class="text-center btn-instagram-share" href="{{ $platforms['instagram']['url'] ?? '#' }}" target="_blank" style="color: #e1306c">
                            <i class="fab fa-instagram fs-4" style="font-size: 4rem !important"></i>
                            <h4 style="color: {{ $style['color'] ?? '#000'}} !important" class="text-dark mt-2 mt-md-3 fs-1.5">Share on Instagram</h4>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </section>
@break

@case('auction-list')
@php
    // $data: array of auction items
    // $style: array of CSS properties for inner content
    // $wrapperStyle: array of CSS properties for outer wrapper

    // Build wrapper style string (for margin, etc.)
    $wrapperStyleStr = '';
    foreach (($wrapperStyle ?? []) as $k => $v) {
        if ($v) $wrapperStyleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
    }
    // Build card style string (for background, etc.)
    $cardStyleStr = '';
    foreach (($style ?? []) as $k => $v) {
        if ($v) $cardStyleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
    }
@endphp

<div class="c-content__bottom" style="{{ $wrapperStyleStr }}">
    <div class="u-wrap--auction-main">
        <div id="ai-display" class="c-ai-display c-ai-display--full"><span></span>
            <div class="o-wrapper c-ai-display__items c-ai-display__items--full" style="{{ $cardStyleStr }}">
                <div class="view-dom-id-ad4934c196a50f6f72cd5a8f4b22c874 js-view js-view-air-auction-items c-view c-view--air-auction-items c-view--display_teaser c-view--display-handler_block c-view--style_default jquery-once-2-processed jqo-vr-processed"
                    data-view-name="air_auction_items" data-view-display="teaser" data-view-page="0">
                    <div class="row">
                            @foreach ($auction as $item)
                            <div class="col-md-4 mt-4 mb-4">
                                <div class="c-view__item c-view__item--teaser">
                                    <div id="node-{{ $item->id }}"
                                        class="c-node-ai c-node-ai--teaser js-ai js-ai--teaser js-eq js-ai--teaser-view c-node-ai--teaser-view"
                                        about="/auction/{{ $item->id }}" typeof="sioc:Item foaf:Document"
                                        data-entity-id="{{ $item->id }}" data-unmet-reserve="0" data-live-id="{{ $item->id }}"
                                        data-updated="{{ \Carbon\Carbon::parse($item->updated_at)->timestamp }}"
                                        data-leader="{{ $item->leader_id ?? '' }}" data-status="bidding" data-lec="false"
                                        data-expiry="{{ \Carbon\Carbon::parse($item->dead_line)->timestamp }}">
                                        <div class="c-node-ai__content">
                                            <div id="air-ai-status-indicator-{{ $item->id }}"
                                                class="js-ai-status-indicator c-node-ai__status c-node-ai__status--teaser c-tooltip c-tooltip--n"
                                                aria-label="Bidding is under way."></div>
                                            <div class="c-node-ai__image-wrap">
                                                <div class="c-node-ai__image">
                                                    <svg viewBox="0 0 100 100"></svg>
                                                    <a href="/auction/{{ $item->id }}" class="">
                                                        <img alt="{{ $item->title }}"
                                                            sizes="(min-width: 110em) 420px, (min-width: 90em) 25vw, (min-width: 60em) 33vw, (min-width: 30em) 50vw, 100vw"
                                                            data-src="{{ asset('/uploads/'.$item->images[0]->image ?? '') }}"
                                                            data-srcset="{{ asset('/uploads/'.$item->images[0]->image ?? '') }}"
                                                            class="jqo-io-processed"
                                                            src="{{ asset('/uploads/'.$item->images[0]->image ?? '') }}"
                                                            srcset="{{ asset('/uploads/'.$item->images[0]->image ?? '') }}">
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="c-node-ai__details-wrap">
                                                <h3 class="c-node-ai__title c-heading--gamma">
                                                    <a href="/auction/{{ $item->id }}" data-mousetrap-trigger="4">
                                                        {{ $item->title }}
                                                    </a>
                                                </h3>
                                                <div class="c-node-ai__bidding-details">
                                                    <div class="o-layout">
                                                        <div class="o-layout__item u-7/12">
                                                            <div class="c-node-ai__timer">
                                                                <div id="ai-timer-{{ $item->id }}"
                                                                    class="js-timer-wrapper c-timer c-timer--small-block u-hide-no-js">
                                                                    <div class="c-timer__title"><span class="js-timer-title">Time remaining</span></div>
                                                                    <span class="c-timer__body">
                                                                        <span class="js-timer"
                                                                            data-timer_id="ai-{{ $item->id }}-long-small-block"
                                                                            data-type="expiry"
                                                                            data-timeout="{{ \Carbon\Carbon::parse($item->dead_line)->timestamp }}"
                                                                            data-format_num="long"
                                                                            data-deadline="{{ $item->dead_line }}"
                                                                            id="auction-timer-{{ $item->id }}">
                                                                            <span class="js-timer-element-days c-timer__element">
                                                                                <span class="c-timer__value" id="days-{{ $item->id }}">0</span>
                                                                                <span class="c-timer__period">Days</span>
                                                                            </span>
                                                                            <span class="c-timer__element">
                                                                                <span class="c-timer__value" id="hours-{{ $item->id }}">0</span>
                                                                                <span class="c-timer__period">Hrs</span>
                                                                            </span>
                                                                            <span class="c-timer__element">
                                                                                <span class="c-timer__value" id="minutes-{{ $item->id }}">0</span>
                                                                                <span class="c-timer__period">Mins</span>
                                                                            </span>
                                                                        </span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="o-layout__item u-5/12">
                                                            <div class="c-node-ai__price">
                                                                <div id="ai-price-{{ $item->id }}" class="c-price  c-price--small-block">
                                                                    <div class="c-price__title"><span class="js-price-title">Current bid</span></div>
                                                                    <div class="c-price__wrapper">
                                                                        <div class="c-price__value js-resize-bid-text u-tc--highlight-bg"
                                                                            id="auction-price-{{ $item->id }}"
                                                                            data-live-item="price"
                                                                            data-tcid="{{ $item->id }}:price"
                                                                            style="font-size: 16px;">
                                                                            ${{ $item->starting_price ?? 0 }}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@break

@case('whos-coming')
    @php
        // Build wrapper style (for margin, etc.)
        $wrapperStyle = '';
        foreach (($data['wrapperStyle'] ?? []) as $k => $v) {
            if ($v) $wrapperStyle .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
        }
        // Build inner style (for color, background, padding, etc.)
        $innerStyle = '';
        foreach (($data['style'] ?? []) as $k => $v) {
            if ($v) $innerStyle .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
        }
        $people = $data['whosComingData'] ?? [];
    @endphp
    <div class="whos-coming-component mb-3" style="{{ $wrapperStyle }}">
        <div class="whos-coming-list p-4" style="{{ $innerStyle }}">
            {{-- <h4 class="mb-3" style="font-weight:bold;">Who's Coming</h4> --}}
            <ol class="mb-0" style="list-style: disc;">
                @foreach($people as $person)
                    <li class="mb-2" style="font-size:1.1em;">
                        {{ $person }}
                    </li>
                @endforeach
            </ol>
        </div>
    </div>
@break

@case('faq')
    @php
        // Build wrapper style (for margin, etc.)
        $wrapperStyle = '';
        foreach (($data['wrapperStyle'] ?? []) as $k => $v) {
            if ($v) $wrapperStyle .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
        }
        // Build FAQ box style (for border, padding, background, etc.)
        $faqStyle = '';
        foreach (($data['style'] ?? []) as $k => $v) {
            if ($v) $faqStyle .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
        }
    @endphp
    <div class="faq-component mb-3" style="{{ $wrapperStyle }}">
        <div class="faq-list" style="{{ $faqStyle }}">
            @if(isset($data['faqData']) && is_array($data['faqData']))
                @foreach($data['faqData'] as $entry)
                    @php
                        $qColor = $entry['labelColor'] ?? null;
                        $aColor = $entry['textColor'] ?? null;
                        $entryBg = $entry['backgroundColor'] ?? null;
                    @endphp
                    <div class="mb-3" style="@if($entryBg)background-color:{{ $entryBg }};@endif">
                        <strong style="@if($qColor)color:{{ $qColor }};@endif">{{ $entry['question'] ?? '' }}</strong>
                        <div style="@if($aColor)color:{{ $aColor }};@endif">{!! nl2br(e($entry['answer'] ?? '')) !!}</div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@break

@case('video')
    @php
        $wrapperStyle = '';
        foreach (($data['wrapperStyle'] ?? []) as $k => $v) {
            if ($v) $wrapperStyle .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
        }
        $style = '';
        foreach (($data['style'] ?? []) as $k => $v) {
            if ($v) $style .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
        }
        $videoHtml = $data['videoHtml'] ?? '';
        $videoWidth = $data['videoWidth'] ?? '100%';
        $videoHeight = $data['videoHeight'] ?? 'auto';
    @endphp
    <div class="video-component mb-3" style="{{ $wrapperStyle }}">
        <div class="video-container" style="width:100%;max-width:{{ $videoWidth }};{{ $style }}">
            <div class="responsive-video-wrapper">
                {!! $videoHtml !!}
            </div>
        </div>
    </div>
@break

@case('button')
    @php
        // Merge style and buttonStyle for the button
        $btnStyleArr = array_merge(
            ($data['style'] ?? []),
            ($data['buttonStyle'] ?? [])
        );
        $btnStyle = '';
        $wrapperStyle = '';
        $textAlign = $btnStyleArr['textAlign'] ?? null;
        foreach ($btnStyleArr as $k => $v) {
            if ($k === 'textAlign') continue; // skip textAlign for button itself
            if ($v) $btnStyle .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
        }
        foreach (($data['wrapperStyle'] ?? []) as $k => $v) {
            if ($v) $wrapperStyle .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
        }
        if ($textAlign) $wrapperStyle .= "text-align:$textAlign;";
        $href = $data['buttonHref'] ?? '#';
        $target = $data['buttonTarget'] ?? '_self';
        $text = $data['buttonText'] ?? 'Click Me';
        // dd($data);
    @endphp
    <div class="button-component mb-3" style="{{ $wrapperStyle }}">
        <a href="{{ $href }}" target="{{ $target }}" style="text-decoration:none;">
            <button type="button" class="btn btn-primary"
                style="{{ $btnStyle }}">
                {{ $text }}
            </button>
        </a>
    </div>
@break

        @case('custom-html')
            @php
                $style = $data['style'] ?? [];
                $wrapperStyle = $data['wrapperStyle'] ?? [];
                $wrapperStyleStr = '';
                foreach ($wrapperStyle as $k => $v) {
                    if ($v) $wrapperStyleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
                }
            @endphp
            <div class="custom-html-component mb-3" style="{{ $wrapperStyleStr }}">
                <iframe width="100%" style="min-height:300px;border:none;" srcdoc="{{ $data['customHtml'] ?? '' }}"></iframe>
            </div>
        @break
        @case('faq')
            @if(isset($data['faqData']) && is_array($data['faqData']))
                <div class="faq-list">
                @foreach($data['faqData'] as $entry)
                    <div class="mb-3">
                        <strong>{{ $entry['question'] ?? '' }}</strong>
                        <div>{!! nl2br(e($entry['answer'] ?? '')) !!}</div>
                    </div>
                @endforeach
                </div>
            @endif
            @break


    @case('contact-form')
    @php
        $emails = $data['contactEmails'] ?? [];
    @endphp
    @php
        $style = $data['style'] ?? [];
        $wrapperStyle = $data['wrapperStyle'] ?? [];
        $wrapperStyleStr = '';
        foreach ($wrapperStyle as $k => $v) {
            if ($v) $wrapperStyleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
        }
        $alertStyleStr = '';
        foreach ($style as $k => $v) {
            if ($v) $alertStyleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
        }
        // dd($alertStyleStr);
    @endphp
    @php
            // Ensure background color is applied to the wrapper
            if (!empty($style['backgroundColor'])) {
                $wrapperStyleStr .= 'background-color:' . $style['backgroundColor'] . ';';
            }
        @endphp
    <form method="POST" action="/contact-form" class="contact-form-component" style="{{ $wrapperStyleStr }}  {{ $alertStyleStr }}">
        @csrf
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8 col-xl-6">
                <div class="row gy-3">
                    <div class="col-12">
                        <label for="name" class="form-label fw-semibold">
                            Your name
                        </label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
                    <div class="col-12">
                        <label for="email" class="form-label fw-semibold">
                            Email address
                        </label>
                        <input type="text" class="form-control" id="email" name="email">
                    </div>
                    <div class="col-12">
                        <label for="message" class="form-label fw-semibold">
                            Message
                        </label>
                        <textarea class="form-control" id="message" name="message" rows="8"></textarea>
                    </div>
                    <input type="hidden" name="template" value="e7d0b613d125406ea714907d6507c2a9">
                    @foreach($emails as $email)
                        <input type="hidden" name="notification_emails[]" value="{{ $email }}">
                    @endforeach
                    <div class="col-12">
                        <small class="text-muted">This form is protected by reCAPTCHA and the Google <a
                                href="https://policies.google.com/privacy" style="color: #2e4053">Privacy Policy</a>
                            and <a href="https://policies.google.com/terms" style="color: #2e4053">Terms of Service</a>
                            apply.</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-center mt-3 mt-md-4">
            <button type="submit" class="btn btn-primary btn-lg text-white" style="background-color: #2e4053; border-color: #2e4053">
                Submit
            </button>
        </div>
    </form>
@break

@case('student-leaderboard')
                @php
                    $st = App\Models\User::limit(5)->whereIn('role',['individual','group_leader','member'])->where('website_id',$check->id)->get();
                    $sortedStudents = $st->sortByDesc(function($student) {
                        return $student->donations->sum('amount');
                    });
                    $key = 0 ;
                @endphp
                @php
                    $style = $data['style'] ?? [];
                    $wrapperStyle = $data['wrapperStyle'] ?? [];
                    $wrapperStyleStr = '';
                    foreach ($wrapperStyle as $k => $v) {
                        if ($v) $wrapperStyleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
                    }
                    $alertStyleStr = '';
                    foreach ($style as $k => $v) {
                        if ($v) $alertStyleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
                    }
                    // dd($alertStyleStr);
                @endphp
                @php
                        // Ensure background color is applied to the wrapper
                        if (!empty($style['backgroundColor'])) {
                            $wrapperStyleStr .= 'background-color:' . $style['backgroundColor'] . ';';
                        }
                        // dd($style);
                    @endphp
<div class="col-md-12 mt-4" style="{{ $alertStyleStr }} {{ $wrapperStyleStr }}">

                @foreach($sortedStudents as $student)
                    <div class="col-lg-12" style="font-size: 12px; margin-bottom: 1rem; ">
                        <div class="position-relative bg- p-4 rounded-3 shadow-sm border"
                            style="width: 100%; max-width: 580px; margin-inline: auto; background: #ebebeb;">
                            <a href="/profile/{{ $student->id }}-{{ $student->name }}-{{ $student->last_name }}" style="color: {{ $style['color'] ?? '#000'}}; text-decoration: none;" target="_blank">
                            <div class="row gy-3 ">
                                <div class="col-lg-3 d-flex align-items-center">
                                    <span class="jk" style="font-size: 1.5rem !important; font-weight: bold; margin-right: 1rem;">{{ $key + 1}}</span>
                                    <div class="rounded-profile-picture border border-3 border-primary mx-auto" style="border-radius: 50%; border-color: #2e4053 !important">
                                        <img src="{{ asset($student->photo) }}" style="border-radius: 50%; width: 70px; min-width: 70px; height: 70px; min-height: 70px;">
                                    </div>
                                </div>

                                <div class="col-lg-7 d-flex flex-column justify-content-center" style="margin-top: 0px !important;">
                                    <h2 class="fs-1.25 fw-semibold text-center text-lg-start break-all" style="font-size: 1.25rem;">
                                        {{ $student->name }}
                                    </h2>

                                    {{-- <span class="opacity-75 text-center text-lg-start mt-2"></span> --}}

                                    <div class="progress" role="progressbar" aria-valuenow="{{ $student->donations->sum('amount') }}"
                                        aria-valuemin="0" aria-valuemax="{{ $student->goal }}" data-primary-color="#2e4053"
                                        data-secondary-color="#28a745" data-duration="5"
                                        data-goal-reached="true" style="height: 14px; border: 1px solid #28a745">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary fs-1"
                                            style="width:@if($student->goal > 0){{ ($student->donations->sum('amount') / $student->goal)*100 }}@else 1 @endif%; background-color: #28a745 !important;" > <span style="font-size: 13px; font-weight: bold; margin-top: -2px;"> @if($student->goal > 0){{ round(($student->donations->sum('amount') / $student->goal)*100) }}@else 1 @endif% </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <span class="position-absolute top-0 end-0 m-2 opacity-50 small">
                                <i class="fa-solid fa-award fa-2xl fa-fw position-absolute" aria-hidden="true" style="
                                @if($key == 0)
                                    color: #FFDf01;
                                @elseif($key == 1)
                                    color: #c0c0c0;
                                @elseif($key == 2)
                                    color: #996515;
                                @else
                                    display: none;
                                @endif
                                    top: 30px; right: 25px; font-size: 2.5rem !important;"></i>
                                <span class="small fw-bold" style="top: 57px; position: relative; left: -36px; right: unset; font-size: 0.74rem; color: #000;">
                                    $ {{ $student->donations->sum('amount') }}
                                </span>
                            </span>
                            </a>
                        </div>
                    </div>
                    @php
                        $key +=1;
                    @endphp
                @endforeach
            </div>
            <div class="col-md-12 mt-4">
                <p class="lead text-center mt-3" style="color: {{ $style['color'] }} !important">
                    @php
                        $count = App\Models\Donation::where('website_id',$check->id)->count();
                    @endphp
                    {{ $count }} donations have been made to this site
                </p>
            </div>

@break

@case('student-listing')
@php
                    $style = $data['style'] ?? [];
                    $wrapperStyle = $data['wrapperStyle'] ?? [];
                    $wrapperStyleStr = '';
                    foreach ($wrapperStyle as $k => $v) {
                        if ($v) $wrapperStyleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
                    }
                    $alertStyleStr = '';
                    foreach ($style as $k => $v) {
                        if ($v) $alertStyleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
                    }
                @endphp
                @php
                        // Ensure background color is applied to the wrapper
                        if (!empty($style['backgroundColor'])) {
                            $alertStyleStr .= 'background-color:' . $style['backgroundColor'] . ' !important;';
                        }
                    // dd($alertStyleStr);

                    @endphp
        <div class="row" style="{{ $wrapperStyleStr }}">
                <div class="col-12 col-md-11 col-lg-9 col-xl-7 d-flex align-items-center" style="margin: auto;">
                    <div class="input-group input-group-lg">
                        <span class="input-group-text">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <input type="text" class="form-control" id="search" name="search" placeholder="Search">
                    </div>
                </div>
                <div class="col-12 mt-4">
                        <table id="studentTable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Grade</th>
                                </tr>
                            </thead>
                            <tbody>
    @php
        $students = App\Models\User::limit(10)->whereIn('role', ['individual', 'group_leader', 'member'])->where('website_id', $check->id)->latest()->get();
    @endphp

    @foreach ($students->chunk(2) as $item)
        <tr>
            @foreach ($item as $key => $student)
                <td style="{{ $alertStyleStr }}">
                    <!-- full student content here -->
                    <div class="row">
                        <div class="col-lg-12 klklklk" style="font-size: 12px;">
                            <div class="position-relative rounded-3 shadow-sm border listingg"
                                style="width: 100%; max-width: 580px; margin-inline: auto;">
                                <a href="/profile/{{ $student->id }}-{{ $student->name }}-{{ $student->last_name }}" style="color: {{ $style['color'] ?? '#000'}}; text-decoration: none;" target="_blank">
                                    <div class="row lsls gy-3" style="padding: 0.5rem;">
                                        <div class="col-lg-2 d-flex align-items-center">
                                            <div class="rounded-profile-picture border border-3 border-primary mx-auto" style="border-radius: 50%; border-color: #2e4053 !important; overflow: hidden;">
                                                <img src="{{ asset($student->photo) }}" style="width: 80px; min-width: 80px; height: 80px; min-height: 80px;">
                                            </div>
                                        </div>

                                        <div class="col-lg-8 d-flex flex-column justify-content-center">
                                            <h2 class="fs-1.25 fw-semibold text-center text-lg-start break-all" style="font-size: 1.25rem;">
                                                {{ $student->name }}
                                            </h2>
                                            <span class="opacity-75 text-center text-lg-start mt-2"></span>
                                            <div class="progress mt-3" role="progressbar"
                                                aria-valuenow="{{ $student->donations->sum('amount') }}"
                                                aria-valuemin="0"
                                                aria-valuemax="{{ $student->goal }}"
                                                data-primary-color="#2e4053"
                                                data-secondary-color="#b7bcc4"
                                                data-duration="5"
                                                data-goal-reached="true"
                                                style="height: 14px">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary fs-1"
                                                    style="width: @if($student->goal > 0){{ ($student->donations->sum('amount') / $student->goal) * 100 }}@else 0 @endif%;">
                                                    <span style="font-size: 13px; font-weight: bold;">@if($student->goal > 0){{ round(($student->donations->sum('amount') / $student->goal) * 100) }}@else 1 @endif%</span>
                                                </div>
                                            </div>
                                            <span class="fw-semibold d-block text-center mt-2">
                                                @php $to = $student->donations->sum('amount'); @endphp
                                                ${{ $to }} <small class="opacity-75 fw-light">of</small> ${{ $student->goal ?? 0 }} <small class="opacity-75 fw-light">raised</small>
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    {{-- {{ $key }} --}}
                </td>
            @endforeach

            {{-- Add one empty <td> only if this is the last row and has only one student --}}
            @if ($loop->last && count($item) < 2)
                <td></td>
            @endif
        </tr>
    @endforeach
</tbody>

                        </table>
                </div>
            </div>
@break

@case('donation-form')
    @php
        // Extract styles
        $style = $data['style'] ?? [];
        $wrapperStyle = $data['wrapperStyle'] ?? [];
        
        // Extract donation form data for customizable text and colors
        $donationFormData = $data['donationFormData'] ?? [];
        $formTitle = $donationFormData['formTitle'] ?? 'Make a general donation to ' . $check->name;
        $secondaryTitle = $donationFormData['secondaryTitle'] ?? 'Donate to the';
        $buttonText = $donationFormData['buttonText'] ?? 'Donate';
        $feeText = $donationFormData['feeText'] ?? 'I elect to pay the fees';
        $feeTooltip = $donationFormData['feeTooltip'] ?? 'By selecting this option, you elect to pay the credit card and transaction fees for this donation. The fees will be displayed in the next step.';
        $anonymousText = $donationFormData['anonymousText'] ?? 'Anonymous';
        $anonymousDescription = $donationFormData['anonymousDescription'] ?? 'Choose to make your donation anonymous';
        $anonymousTooltip = $donationFormData['anonymousTooltip'] ?? 'Selecting this option will hide your name from everyone but the organizer.';
        
        // Extract color settings
        $backgroundColor = $donationFormData['backgroundColor'] ?? '#ffffff';
        $headerColor = $donationFormData['headerColor'] ?? '#2e4053';
        $headerTextColor = $donationFormData['headerTextColor'] ?? '#ffffff';
        $borderColor = $donationFormData['borderColor'] ?? '#2e4053';
        
        // Build wrapper style string (for margin, padding, etc.)
        $wrapperStyleStr = '';
        foreach ($wrapperStyle as $k => $v) {
            if ($v) $wrapperStyleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
        }
        // Build card style string (for background, etc.)
        $cardStyleStr = '';
        foreach ($style as $k => $v) {
            if ($v) {
                $cssKey = strtolower(preg_replace('/([A-Z])/', '-$1', $k));
                $cardStyleStr .= "$cssKey:$v;";
                if (in_array($cssKey, ['margin', 'margin-top', 'margin-bottom', 'margin-left', 'margin-right', 'padding', 'padding-top', 'padding-bottom', 'padding-left', 'padding-right'])) {
                    $wrapperStyleStr .= "$cssKey:$v;";
                }
            }
        }
    @endphp
    <section class="donation-form-component" style="{{ $wrapperStyleStr }}">
        <div class="block-container container" style="{{ $cardStyleStr }}">
            <form method="POST" action="/donation-general" class="donation-form-block">
                @csrf
                <!-- Improved mobile responsiveness: wider on mobile, closer to edges -->
                <div class="col-12 col-sm-11 col-md-10 col-lg-8 col-xl-6 mx-auto px-2 px-sm-3">
                    <div class="card shadow" style="border-width: 3px; border-color: {{ $borderColor }} !important;">
                        <div class="card-header rounded-0 text-center fs-2"
                            style="border-width: 3px !important; border-color: {{ $headerColor }} !important; background-color: {{ $headerColor }} !important; color: {{ $headerTextColor }} !important;">
                            {{ $formTitle }}
                        </div>
                        <div class="card-body" style="background-color: {{ $backgroundColor }} !important;">
                            <input type="hidden" name="profile_uuid" value="">
                            <input type="hidden" name="team_uuid" value="">

                            <div class="row gy-3">
                                <div class="col-12 d-flex flex-column justify-content-center align-items-center">
                                    <input type="hidden" name="type" id="type" value="general">
                                    <div></div>
                                </div>

                                <div class="col-12">
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text fw-light fs-1.5 fs-lg-2 border-primary"
                                            style="border-width: 2px; border-right-width: 0; border-color: {{ $borderColor }} !important;">$</span>
                                        <input type="number" placeholder="0"
                                            class="form-control fs-2 fs-lg-4 text-center border-primary"
                                            style="border-width: 2px; border-color: {{ $borderColor }} !important;" name="donation_amount" value="" required>
                                        <span class="input-group-text fw-light fs-1.5 fs-lg-2 border-primary"
                                            style="border-width: 2px; border-left-width: 0; border-color: {{ $borderColor }} !important;">.00</span>
                                    </div>
                                    <input type="hidden" name="amount" value="">
                                    <div class="text-center">
                                        <small class="form-text text-muted">
                                            * The minimum donation amount is 8.
                                        </small>
                                    </div>
                                </div>

                                <div class="col-12 d-flex justify-content-center align-items-center">
                                    <div class="card border-primary shadow p-2" style="border-width: 2px; border-color: {{ $borderColor }} !important;">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                id="pay_fees" name="pay_fees" checked="">
                                            <label class="form-check-label fw-semibold" for="pay_fees">
                                                {{ $feeText }}
                                            </label>
                                            <i role="button"
                                                class="fa-solid fa-circle-info text-info btn-modal-info ms-2"
                                                data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="{{ $feeTooltip }}"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="first_name" class="form-label fw-semibold required">
                                        First name
                                    </label>
                                    <input type="text" class="form-control" id="first_name"
                                        name="first_name" value="" required>
                                </div>

                                <div class="col-12">
                                    <label for="last_name" class="form-label fw-semibold required">
                                        Last name
                                    </label>
                                    <input type="text" class="form-control" id="last_name"
                                        name="last_name" value="" required>
                                </div>

                                <div class="col-12">
                                    <label for="email" class="form-label fw-semibold required">
                                        Email address
                                    </label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="" required>
                                </div>

                                <div class="col-12">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch"
                                            id="anonymous_donation" name="anonymous_donation">
                                        <label class="form-check-label fw-semibold" for="anonymous_donation">
                                            {{ $anonymousText }}
                                        </label>
                                        <i role="button"
                                            class="fa-solid fa-circle-info text-info btn-modal-info ms-2"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="{{ $anonymousTooltip }}"></i>
                                        <small class="text-muted d-block mt-1">{{ $anonymousDescription }}</small>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="leave_comment" class="form-label fw-semibold text-capitalize">
                                        comment
                                    </label>
                                    <textarea class="form-control" id="leave_comment" name="leave_comment" rows="6"></textarea>
                                </div>

                                <div class="col-12">
                                    <small class="text-muted">This form is protected by reCAPTCHA and the
                                        Google <a href="https://policies.google.com/privacy">Privacy Policy</a>
                                        and <a href="https://policies.google.com/terms">Terms of Service</a>
                                        apply.</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer rounded-0 p-0"
                            style="border-width: 3px !important; border-color: {{ $headerColor }} !important; background-color: {{ $headerColor }} !important;">
                            <button type="submit"
                                class="btn btn-lg w-100 h-100 rounded-0 shadow-none"
                                style="background: {{ $headerColor }} !important; border-color: {{ $headerColor }} !important; color: {{ $headerTextColor }} !important;">
                                {{ $buttonText }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@break

@case('sell-tickets')
    @php
        // Extract styles
        $style = $data['style'] ?? [];
        $wrapperStyle = $data['wrapperStyle'] ?? [];
        // Build wrapper style string (for margin, etc.)
        $wrapperStyleStr = '';
        foreach ($wrapperStyle as $k => $v) {
            if ($v) $wrapperStyleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
        }
        // Build card style string (for background, etc.)
        $cardStyleStr = '';
        foreach ($style as $k => $v) {
            if ($v) $cardStyleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
        }
    @endphp
     <section style="{{ $wrapperStyleStr }} {{ $cardStyleStr }}" class="mt-2 mb-2">
            @php
                $tickets = \App\Models\Ticket::where('website_id',$check->id)->where('status',1)->latest()->get();
            @endphp
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <form action="/tickets" method="POST">
                        @csrf
                            @foreach ($tickets as $item)
                            <div class="card ticket-mask mt-2 mb-2">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-2 col-2">
                                                <img src="{{ asset($item->image) }}" width="64px" height="64px;">
                                            </div>
                                            <div class="col-md-10 col-10">
                                                <h4 style="margin-bottom: 2px;">{{ $item->name }} (${{ $item->price }})</h4>
                                                <p style="margin-bottom: 2px;">{{ $item->description }}</p>
                                                <span>Only {{ $item->quantity }} left!</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="hidden" name="ticket[{{ $item->id }}][id]" value="{{ $item->id }}">
                                        <select name="ticket[{{ $item->id }}][quantity]" class="form-control tickets">
                                            <option value="null">Select a option</option>
                                            @for ($i = 1; $i <= $item->quantity; $i++)
                                                <option value="{{ $i }}">You selected a total of {{ $i }} {{ $item->name }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="col-md-12 text-center mt-4 mb-4">
                            <button type="submit" class="btn btn-primary"> Buy </button>
                        </div>
                    </form>
            </div>
        </section>
@break

            @case('full-width-text-image')
    @php
        $fwti = $data['fwtiData'] ?? [];
        $style = $data['style'] ?? [];
        $wrapperStyle = $data['wrapperStyle'] ?? [];
        $wrapperStyleStr = '';
        foreach ($wrapperStyle as $k => $v) {
            if ($v) $wrapperStyleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
        }
    @endphp
    <div class="full-width-text-image-component mb-4" style="width:100%;{{ $wrapperStyleStr }};">
        <div style="width:100%;display:flex;flex-direction:column;">
            @if(!empty($fwti['text1']))
                <div style="color:{{ $fwti['color1'] ?? '#222' }};font-size:{{ $fwti['fontSize1'] ?? '60px' }};font-weight:bold;margin-top:24px;text-align: {{ $style['textAlign'] }};">
                    {{ $fwti['text1'] }}
                </div>
            @endif
            @if(!empty($fwti['text2']))
                <div style="color:{{ $fwti['color2'] ?? '#444' }};font-size:{{ $fwti['fontSize2'] ?? '18px' }};margin-top:12px;text-align: {{ $style['textAlign'] }};">
                    {{ $fwti['text2'] }}
                </div>
            @endif
            @if(!empty($fwti['imgSrc']) && $fwti['imgSrc'] != 'https://via.placeholder.com/1200x400')
                <img
                    src="{{ $fwti['imgSrc'] }}"
                    alt="{{ $fwti['imgAlt'] ?? '' }}"
                    style="width:{{ $fwti['imgCustomWidth'] ?? '100%' }};height:{{ $fwti['imgCustomHeight'] ?? 'auto' }};margin-top:12px; object-fit:{{ $fwti['imgObjectFit'] ?? 'cover' }};max-height:{{ $fwti['imgHeight'] ?? '400' }}px;border-radius:8px;">
            @endif
        </div>
    </div>
@break
                                @case('event-countdown')
                                    @if(isset($data['countdownData']))
                                        @php
                                            $label = $data['countdownData']['label'] ?? '';
                                            $date = $data['countdownData']['date'] ?? '';
                                            $fontWeight = $data['countdownData']['fontWeight'] ?? 'bold';
                                            // Build wrapper style (for margin, etc.)
                                            $wrapperStyle = '';
                                            $backgroundColor = '';
                                            foreach (($data['wrapperStyle'] ?? []) as $k => $v) {
                                                if ($v) $wrapperStyle .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
                                            }
                                            // Build countdown style (for color, background, padding, etc.)
                                            $countdownStyle = '';
                                            $color = '#000';
                                            foreach (($data['style'] ?? []) as $k => $v) {
                                                if ($v) {
                                                    $cssKey = strtolower(preg_replace('/([A-Z])/', '-$1', $k));
                                                    $countdownStyle .= "$cssKey:$v;";
                                                    if ($cssKey === 'color') $color = $v;
                                                    if ($cssKey === 'background-color') $backgroundColor = $v;
                                                }
                                            }
                                            // Ensure background color is applied to the wrapper
                                            if ($backgroundColor) {
                                                $wrapperStyle .= 'background-color:' . $backgroundColor . ';';
                                            }
                                        @endphp
                                        <div class="event-countdown" style="padding:24px 16px;border-radius:8px;text-align:center;margin-bottom:24px;{{ $wrapperStyle }}">
                                            <div class="timer text-center mt-5" style="{{ $countdownStyle }}">
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <div class="mx-3 counters">
                                                        <h1 id="months" class="display-4" style="font-weight:{{ $fontWeight == 'normal' ? 400 : 600 }};color:{{ $color }}">0</h1>
                                                        <p style="color:{{ $color }}">Months</p>
                                                    </div>
                                                    <div class="mx-3 counters">
                                                        <h1 id="days" class="display-4" style="font-weight:{{ $fontWeight == 'normal' ? 400 : 600 }};color:{{ $color }}">0</h1>
                                                        <p style="color:{{ $color }}">Days</p>
                                                    </div>
                                                    <div class="mx-3 counters">
                                                        <h1 id="hours" class="display-4" style="font-weight:{{ $fontWeight == 'normal' ? 400 : 600 }};color:{{ $color }}">0</h1>
                                                        <p style="color:{{ $color }}">Hours</p>
                                                    </div>
                                                    <div class="mx-3 counters">
                                                        <h1 id="minutes" class="display-4" style="font-weight:{{ $fontWeight == 'normal' ? 400 : 600 }};color:{{ $color }}">0</h1>
                                                        <p style="color:{{ $color }}">Minutes</p>
                                                    </div>
                                                    <div class="mx-3 counters">
                                                        <h1 id="seconds" class="display-4" style="font-weight:{{ $fontWeight == 'normal' ? 400 : 600 }};color:{{ $color }}">0</h1>
                                                        <p style="color:{{ $color }}">Seconds</p>
                                                    </div>
                                                </div>
                                                <p style="font-size: .8em; font-weight:{{ $fontWeight == 'normal' ? 400 : 600 }}; color:{{ $color }}">{{ $label }}</p>
                                            </div>
                                            <input type="hidden" id="timer" class="date-countdown" value="{{ $date }}" style="font-weight:{{ $fontWeight == 'normal' ? 400 : 600 }}">
                                        </div>
                                        <script>
                                            da = document.getElementById("timer").value;
                                            const targetDate = new Date(da).getTime();
                                            function updateCountdown() {
                                                const now = new Date().getTime();
                                                const timeLeft = targetDate - now;
                                                if (timeLeft <= 0) {
                                                    document.getElementById("months").textContent = 0;
                                                    document.getElementById("days").textContent = 0;
                                                    document.getElementById("hours").textContent = 0;
                                                    document.getElementById("minutes").textContent = 0;
                                                    document.getElementById("seconds").textContent = 0;
                                                    return;
                                                }
                                                const months = Math.floor(timeLeft / (1000 * 60 * 60 * 24 * 30));
                                                const days = Math.floor((timeLeft % (1000 * 60 * 60 * 24 * 30)) / (1000 * 60 * 60 * 24));
                                                const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                                const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                                                const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
                                                document.getElementById("months").textContent = months;
                                                document.getElementById("days").textContent = days;
                                                document.getElementById("hours").textContent = hours;
                                                document.getElementById("minutes").textContent = minutes;
                                                document.getElementById("seconds").textContent = seconds;
                                            }
                                            setInterval(updateCountdown, 1000);
                                        </script>
                                    @endif
                                @break
                                   @case('image')
                                        @php
                                            $img = $data['imageData'] ?? [];
                                            $displayMode = $img['displayMode'] ?? 'preview'; // default to preview
                                            $style = $data['style'] ?? [];
                                            $wrapperStyle = $data['wrapperStyle'] ?? [];
                                            $wrapperStyleStr = '';
                                            foreach ($wrapperStyle as $k => $v) {
                                                if ($v) $wrapperStyleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
                                            }
                                            $imgStyle = '';
                                            foreach ($style as $k => $v) {
                                                if ($v) $imgStyle .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
                                            }
                                            if (!empty($img['width'])) $imgStyle .= "width:{$img['width']};";
                                            if (!empty($img['height'])) $imgStyle .= "height:{$img['height']};";
                                            if (!empty($img['objectFit'])) $imgStyle .= "object-fit:{$img['objectFit']};";
                                        @endphp
                                        @php
                                            // Ensure background color is applied to the wrapper
                                            if (!empty($style['backgroundColor'])) {
                                                $wrapperStyleStr .= 'background-color:' . $style['backgroundColor'] . ';';
                                            }
                                        @endphp
                                        <div class="single-image-component mb-3" style="{{ $wrapperStyleStr }} @if($data['style']['textAlign'] == 'center') text-align: center @endif">
                                            @if($displayMode === 'link' && !empty($img['link']))
                                                <a href="{{ $img['link'] }}" @if(!empty($img['openInNewTab'])) target="_blank" @endif>
                                                    <img src="{{ $img['src'] ?? '' }}"
                                                        alt="{{ $img['alt'] ?? '' }}"
                                                        style="{{ $imgStyle }}border-radius:8px;cursor:pointer;transition:box-shadow .2s;"
                                                        data-img="{{ $img['src'] ?? '' }}"
                                                    />
                                                </a>
                                            @else
                                                <a href="javascript:void(0);" class="image-link" style="display:inline-block;">
                                                    <img src="{{ $img['src'] ?? '' }}"
                                                        alt="{{ $img['alt'] ?? '' }}"
                                                        style="{{ $imgStyle }}border-radius:8px;cursor:pointer;transition:box-shadow .2s;"
                                                        class="img-preview"
                                                        data-img="{{ $img['src'] ?? '' }}"
                                                    />
                                                </a>
                                            @endif
                                        </div>
                                    @break
                               @case('event-information')
    @if(isset($data['eventInfoData']))
        @php
            $info = $data['eventInfoData'];
            // Get color from style array
            $color = '#3B82F6'; // default fallback
            if (!empty($data['style']['color'])) {
                $color = $data['style']['color'];
            }
            // Build wrapper style if needed
            $wrapperStyle = '';
            $backgroundColor = '';
            foreach (($data['wrapperStyle'] ?? []) as $k => $v) {
                if ($v) $wrapperStyle .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
            }
            foreach (($data['style'] ?? []) as $k => $v) {
                if ($v) {
                    $cssKey = strtolower(preg_replace('/([A-Z])/', '-$1', $k));
                    if ($cssKey === 'background-color') $backgroundColor = $v;
                }
            }
            // Ensure background color is applied to the wrapper
            if ($backgroundColor) {
                $wrapperStyle .= 'background-color:' . $backgroundColor . ';';
            }
        @endphp
        <div class="event-information" style="padding:20px 16px;border-radius:8px;margin-bottom:24px;{{ $wrapperStyle }}">
            <div class="icons">
                <div class="row gy-3 gy-md-4 row-cols-1 flex-column">
                    <div class="col">
                        <div class="row gy-3 justify-content-center text-center text-">
                            <div class="col-md-4 col-xl-2">
                                <div class="bg- py-3 rounded h-100 d-flex flex-column justify-content-center align-items-center">
                                    <i class="fa-solid fa-calendar-days fa-fw fs-3 mb-3" aria-hidden="true" style="color:{{ $color }} !important"></i>
                                    <h4 class="fs-1.5 fw-light mb-1" style="color:{{ $color }}">When</h4>
                                    <p class="fs-.75 opacity-75 fw-light" style="color:{{ $color }}">{{ $info['date'] }}</p>
                                </div>
                            </div>
                            <div class="col-md-4 col-xl-3">
                                <div class="bg- py-3 rounded h-100 d-flex flex-column justify-content-center align-items-center">
                                    <i class="fa-solid fa-signs-post fa-fw fs-3 mb-3" aria-hidden="true" style="color:{{ $color }} !important"></i>
                                    <h4 class="fs-1.5 fw-light mb-1" style="color:{{ $color }}">Where</h4>
                                    <p class="fs-.75 opacity-75 fw-light" style="color:{{ $color }}">{{ $info['address'] }}</p>
                                </div>
                            </div>
                            <div class="col-md-4 col-xl-2">
                                <div class="bg- py-3 rounded h-100 d-flex flex-column justify-content-center align-items-center">
                                    <i class="fa-solid fa-clock fa-fw fs-3 mb-3" aria-hidden="true" style="color:{{ $color }} !important"></i>
                                    <h4 class="fs-1.5 fw-light mb-1" style="color:{{ $color }}">Time</h4>
                                    <p class="fs-.75 opacity-75 fw-light" style="color:{{ $color }}">{{ $info['time'] }} PST</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            content = document.querySelector('.event-information');
            const mapEmbed = "{{ $info['mapEmbed'] ?? '' }}";
            const showMap = "{{ $data['eventInfoData']['showMap'] }}";
            const mapPosition = "{{ $info['mapPosition'] ?? 'down' }}";
            const infoHtml = document.querySelector('.event-information').innerHTML;
            // Map HTML
            const mapHtml = showMap ? `<div class="event-map" style="margin:16px 0;"><iframe class="map embed-responsive-item rounded border border-2 border-" style="height: 300px; width: 100%; position: relative; overflow: hidden;" src="${mapEmbed}" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe></div>` : '';
            // Layout by mapPosition
            let finalHtml = '';
            if (showMap) {
                if (mapPosition === 'up') {
                    finalHtml = mapHtml + infoHtml;
                } else if (mapPosition === 'down') {
                    finalHtml = infoHtml + mapHtml;
                } else if (mapPosition === 'left') {
                    finalHtml = `<div style='display:flex;gap:24px;align-items:flex-start;'><div style='flex:1;max-width:50%'>${mapHtml}</div><div style='flex:1;'>${infoHtml}</div></div>`;
                } else {
                    finalHtml = `<div style='display:flex;gap:24px;align-items:flex-start;'><div style='flex:1;'>${infoHtml}</div><div style='flex:1;max-width:50%'>${mapHtml}</div></div>`;
                }
            } else {
                finalHtml = infoHtml;
            }
            content.innerHTML = finalHtml;
        </script>
    @endif
@break
                                @case('site-goal')
                                    @if(isset($data['goalData']))
                                        @php
                                        // Example data for the new site-goal component (replace with your dynamic data as needed)
                                        $goal = isset($data['goalData']['goal']) ? (float)$data['goalData']['goal'] : 10000;
                                        $raised = isset($data['goalData']['raised']) ? (float)$data['goalData']['raised'] : 3500;
                                        $percent = $goal > 0 ? min(100, round(($raised / $goal) * 100, 2)) : 0;
                                        $label = $data['goalData']['label'] ?? 'Fundraising Goal';
                                        $showTicks = true;
                                        $ticks = $data['goalData']['ticks'] ?? [0, 0.25, 0.5, 0.75, 1];
                                        @endphp

                                        @php
                                            $style = $data['style'] ?? [];
                                            $wrapperStyle = $data['wrapperStyle'] ?? [];
                                            $wrapperStyleStr = '';
                                            foreach ($wrapperStyle as $k => $v) {
                                                if ($v) $wrapperStyleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
                                            }
                                            $alertStyleStr = '';
                                            foreach ($style as $k => $v) {
                                                if ($v) $alertStyleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
                                            }
                                            // dd($alertStyleStr);
                                        @endphp
                                        @php
                                                // Ensure background color is applied to the wrapper
                                                if (!empty($style['backgroundColor'])) {
                                                    $wrapperStyleStr .= 'background-color:' . $style['backgroundColor'] . ';';
                                                }
                                            @endphp

                                        <div class="site-goal-modernmb-4" style="{{ $wrapperStyleStr }} {{ $alertStyleStr }}">
                                            <div class="p-4">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    {{-- <button type="button" class="btn-close" style="font-size: 1.1rem; opacity: 0.7;" aria-label="Close" onclick="this.closest('.site-goal-modern').style.display='none';"></button> --}}
                                                </div>
                                                <div class="d-flex align-items-center mb-2">
                                                    <span class="fw-semibold" style="color: {{ $data['style']['color'] }};">${{ number_format($raised, 2) }}</span>
                                                    <span class="mx-2" style="color: {{ $data['style']['color'] }};">/</span>
                                                    <span style="color: {{ $data['style']['color'] }};">${{ number_format($goal, 2) }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center mt-2">
                                                    <span class="text-muted small"></span>
                                                    <span class="text-muted small" style="font-weight: bold; padding-bottom: 10px; color: {{ $data['style']['color'] }} !important">${{ $raised }} Raised</span>
                                                </div>
                                                <div class="progress position-relative" style="height: 35px; background: #e5e7eb; border-radius: 9px;">
                                                @php $barId = 'siteGoalProgressBar_' . uniqid(); @endphp
                                                <div class="progress-bar" role="progressbar"
                                                    style="background-color: {{ $data['goalData']['barColor'] ?? '#0d6efd'}}; width:0%; border-radius: 9px; transition: width 0.8s cubic-bezier(0.4,0,0.2,1);"
                                                    aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"
                                                    id="{{ $barId }}">
                                                </div>
                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                    var bar = document.getElementById('{{ $barId }}');
                                                    if (bar) {
                                                        setTimeout(function() {
                                                        bar.style.width = '{{ $percent }}%';
                                                        }, 150);
                                                    }
                                                    });
                                                </script>
                                                <div class="site-goal-ticks position-absolute w-100" style="top: 100%; left: 0; height: 24px; pointer-events: none; z-index: 10;">
                                                    @foreach($ticks as $tick)
                                                        @php
                                                            $tickPercent = 0;
                                                            $tickValue = 0;
                                                            if (is_numeric($tick)) {
                                                                if ($tick <= 1) {
                                                                    $tickPercent = $tick * 100;
                                                                    $tickValue = $tick * $goal;
                                                                } else {
                                                                    $tickPercent = min($tick / $goal, 1) * 100;
                                                                    $tickValue = $tick;
                                                                }
                                                            }
                                                        @endphp
                                                        @if($tickPercent >= 0 && $tickPercent <= 100)
                                                        <div class="site-goal-tick" style="position: absolute; left: {{ $tickPercent }}%; top: 0; width: 2px; height: 24px; background: #6f7c8b; z-index: 11;">
                                                            <div class="site-goal-tick-label" style="position: absolute; top: 22px; left: 50%; transform: translateX(-50%); font-size: 12px; color: {{ $data['style']['color'] ?? '#222' }}; white-space: nowrap; background: #fff; padding: 0 2px; border-radius: 2px; z-index: 12;">
                                                                ${{ number_format($tickValue, 0) }}
                                                            </div>
                                                        </div>
                                                        @endif
                                                    @endforeach
                                                </div>
{{-- @if($showTicks && !empty($ticks) && $goal > 0)
@endif --}}
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center mt-2">
                                                    <span class="text-muted small"></span>
                                                    <span class="text-muted small" style="font-weight: bold; color: {{ $data['style']['color'] }} !important">${{ $goal }} Goal</span>
                                                </div>
                                                <div class="mt-3" style="font-size: 1.1rem; color: {{ $data['style']['color'] }};">
                                                    <span class="fw-bold">{{ $percent }}%</span> of goal reached
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @break
                                @case('text-images')
                                    @if(isset($data['textImagesData']))
                                        @php
                                            $img = $data['textImagesData']['imgSrc'] ?? '';
                                            $imgSize = $data['textImagesData']['imgSize'] ?? 200;
                                            $imgPosition = $data['textImagesData']['imgPosition'] ?? 'left';
                                            $text = $data['textImagesData']['text'] ?? '';
                                            $showImage = array_key_exists('showImage', $data['textImagesData']) ? (bool)$data['textImagesData']['showImage'] : true;
                                        @endphp
                                        @php
                                            $style = $data['style'] ?? [];
                                            $wrapperStyle = $data['wrapperStyle'] ?? [];
                                            $wrapperStyleStr = '';
                                            foreach ($wrapperStyle as $k => $v) {
                                                if ($v) $wrapperStyleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
                                            }
                                            $alertStyleStr = '';
                                            foreach ($style as $k => $v) {
                                                if ($v) $alertStyleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
                                            }
                                            // dd($alertStyleStr);
                                        @endphp
                                        @php
                                            // Ensure background color is applied to the wrapper
                                            if (!empty($style['backgroundColor'])) {
                                                $wrapperStyleStr .= 'background-color:' . $style['backgroundColor'] . ';';
                                            }
                                        @endphp
                                        <div class="text-images-component" style="{{ $wrapperStyleStr }} {{ $alertStyleStr }}display:flex;align-items:center;flex-direction:{{ in_array($imgPosition,['up','down']) ? 'column' : ($imgPosition=='right'?'row-reverse':'row') }};">
                                            @if($showImage && $img)
                                                <img src="{{ $img }}" class="text-images-img" style="width:{{ $imgSize }}px;margin:8px;">
                                            @endif
                                            <div class="responsive-text-block" style="
                                                font-size : {{ $data['style']['fontSize'] ? $data['style']['fontSize'] : '16px'}};
                                                width: 100%;
                                                word-break: break-word;
                                                overflow-wrap: break-word;
                                                line-height:1.5;
                                                padding: 0.5rem 1rem;
                                                color : {{ $data['style']['color'] ? $data['style']['color'] : '#000'}};
                                            ">{!! nl2br(e($text)) !!}</div>
                                            <style>
                                            @media (max-width: 768px) {
                                                .text-images-component {
                                                    flex-direction: column !important;
                                                    align-items: stretch !important;
                                                    /* Remove any margin from wrapper in mobile */
                                                    margin: 0 !important;
                                                }
                                                .text-images-img {
                                                    margin: 0 auto 8px auto !important;
                                                    display: block;
                                                }
                                                .responsive-text-block {
                                                    font-size: clamp(0.95rem, 4vw, 1.1rem) !important;
                                                    padding: 0.5rem 0.5rem !important;
                                                    /* Always put text below image */
                                                    order: 2 !important;
                                                    text-align: center !important;
                                                }
                                                .text-images-img {
                                                    order: 1 !important;
                                                }
                                            }
                                            @media (max-width: 480px) {
                                                .responsive-text-block {
                                                    font-size: clamp(0.9rem, 5vw, 1rem) !important;
                                                    padding: 0.25rem 0.25rem !important;
                                                    text-align: center !important;
                                                }
                                            }
                                            </style>
                                        </div>
                                    @endif
                                    @break
                                @case('auth-form')
    @php
        $style = $data['style'] ?? [];
        $wrapperStyle = $data['wrapperStyle'] ?? [];
        $titles = $data['authFormTitles'] ?? ['register' => 'Register', 'login' => 'Login'];
        // Build wrapper style string (for margin, etc.)
        $wrapperStyleStr = '';
        foreach ($wrapperStyle as $k => $v) {
            if ($v) $wrapperStyleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
        }
        // Build inner style string (for color, background, etc.)
        $innerStyleStr = '';
        foreach ($style as $k => $v) {
            if ($v) $innerStyleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
        }
    @endphp
    <div class="auth-form-component mb-4" style="{{ $wrapperStyleStr }} {{ $innerStyleStr }}">
        <div class="row">
            <div class="col-md-12 mt-4 mb-4 text-center">
                <i class="fa-solid fa-circle-user fa-fw text-primary mb-3" aria-hidden="true" style="font-size: 8rem; color: #2e4053 !important;"></i>
                <h2 class="display-6 tit">{{ $titles['register'] ?? 'Register' }}</h2>
            </div>
        </div>
        <div class="register">
            <div class="container">
                <form action="/register" method="POST">
                    @csrf
                    <input type="hidden" name="website_id" value="{{ $check->id }}">
                    <div class="row justify-content-center">
                        <div class="col-md-4">
                            <label for="first_name" class="form-label">First name</label>
                            <input type="text" class="form-control" id="first_name" name="name">
                        </div>
                        <div class="col-md-4">
                            <label for="first_name" class="form-label">Last name</label>
                            <input type="text" class="form-control" id="first_name" name="last_name">
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-4">
                            <label for="first_name" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="first_name" name="email">
                        </div>
                        <div class="col-md-4">
                            <label for="first_name" class="form-label">Confirm email address</label>
                            <input type="email" class="form-control" id="first_name" name="confirm_email">
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-4">
                            <label for="register_as" class="form-label">Register as</label>
                            <select class="form-select" id="register_as" name="register_as" onchange="toggleGroupSelect(this)">
                                <option value="individual">Individual</option>
                                <option value="member">Group Member</option>
                                <option value="group_leader">Group Leader</option>
                            </select>
                        </div>
                        <div class="col-md-4" id="group_select_wrapper" style="display:none;">
                            <label for="group_id" class="form-label">Select Group</label>
                            <select class="form-select" id="group_id" name="group_id">
                                <option value="">Select a group</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}">{{ $group->group_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4" id="group_name_wrapper" style="display:none;">
                            <label for="group_name" class="form-label">Group Name</label>
                            <input type="text" class="form-control" id="group_name" name="group_name">
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-4">
                            <label for="first_name" class="form-label">Password</label>
                            <input type="password" class="form-control" id="first_name" name="password">
                        </div>
                        <div class="col-md-4">
                            <label for="first_name" class="form-label">Confirm password</label>
                            <input type="password" class="form-control" id="first_name" name="confirm_password">
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-8">
                            <div class="d-grid gap-3 mt-2">
                                <button class="btn btn-primary btn-lg text-white" type="submit" style="background-color: #2e4053 !important; border-color: transparent;">
                                    <i class="fa-solid fa-door-open me-1" aria-hidden="true"></i>
                                    {{ $titles['register'] ?? 'Register' }}
                                </button>
                                <button class="btn text-primary btn-lg p-0 shadow-none view-login-form" type="button" style="color: #2e4053 !important;">
                                    {{ $titles['login'] ?? 'Login' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="login" style="display: none;">
            <div class="container">
                <form action="/login" method="POST">
                    @csrf
                    <div class="row justify-content-center">
                        <div class="col-md-4">
                            <label for="first_name" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="first_name" name="email">
                        </div>
                        <div class="col-md-4">
                            <label for="first_name" class="form-label">Password</label>
                            <input type="password" class="form-control" id="first_name" name="password">
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-8">
                            <div class="d-grid gap-3 mt-2">
                                <button class="btn btn-primary btn-lg text-white" type="submit" style="background-color: #2e4053 !important; border-color: transparent;">
                                    <i class="fa-solid fa-door-open me-1" aria-hidden="true"></i>
                                    {{ $titles['login'] ?? 'Login' }}
                                </button>
                                <button class="btn text-primary btn-lg p-0 shadow-none view-register-form" type="button" style="color: #2e4053 !important;">
                                    {{ $titles['register'] ?? 'Register' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <script>
            $('.view-login-form').click(function() {
                $('.register').toggle();
                $('.login').toggle();
                $('.tit').html(`{{ $titles['login'] ?? 'Login' }}`);
            });
            $('.view-register-form').click(function() {
                $('.login').toggle();
                $('.register').toggle();
                $('.tit').html(`{{ $titles['register'] ?? 'Register' }}`);
            });
        </script>
    </div>
@break
                                @case('custom-form')
                                @php
                                    $emails = $data['contactEmails'] ?? [];
                                @endphp
                                @php
                                        $style = $data['style'] ?? [];
                                        $wrapperStyle = $data['wrapperStyle'] ?? [];
                                        $wrapperStyleStr = '';
                                        foreach ($wrapperStyle as $k => $v) {
                                            if ($v) $wrapperStyleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
                                        }
                                        $alertStyleStr = '';
                                        foreach ($style as $k => $v) {
                                            if ($v) $alertStyleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
                                        }
                                        // dd($alertStyleStr);
                                    @endphp
                                    @php
                                            // Ensure background color is applied to the wrapper
                                            if (!empty($style['backgroundColor'])) {
                                                $wrapperStyleStr .= 'background-color:' . $style['backgroundColor'] . ';';
                                            }
                                        @endphp
                                    @if(isset($data['customFormFields']) && is_array($data['customFormFields']))
                                        <form method="POST" action="/custom-form" class="custom-form-component" style="{{ $wrapperStyleStr }} {{ $alertStyleStr }}">
                                            @csrf
                                            @foreach($data['customFormFields'] as $field)
                                                <div class="mb-3">
                                                    <label class="form-label">{{ $field['label'] ?? '' }}@if(!empty($field['required'])) <span style="color:red">*</span>@endif</label>
                                                    @if(($field['type'] ?? 'text') === 'textarea')
                                                        <textarea class="form-control" name="{{ $field['name'] ?? '' }}" @if(!empty($field['required'])) required @endif>{{ $field['value'] ?? '' }}</textarea>
                                                    @else
                                                        <input class="form-control" type="{{ $field['type'] ?? 'text' }}" name="{{ $field['name'] ?? '' }}" value="{{ $field['value'] ?? '' }}" @if(!empty($field['required'])) required @endif />
                                                    @endif
                                                </div>
                                            @endforeach
                                            @foreach($emails as $email)
                                                <input type="hidden" name="notification_emails[]" value="{{ $email }}">
                                            @endforeach
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </form>
                                    @endif
                                    @break
                                @case('gallery')
                                    @php
                                        $gallery = $data['galleryData'] ?? [];
                                        $images = $gallery['images'] ?? [];
                                        $columns = $gallery['columns'] ?? 3;
                                        $style = $data['style'] ?? [];
                                        $wrapperStyle = $data['wrapperStyle'] ?? [];
                                        // Build inline style for the gallery wrapper
                                        $wrapperStyleStr = '';
                                        foreach ($wrapperStyle as $k => $v) {
                                            if ($v) $wrapperStyleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
                                        }
                                        // Ensure background color is applied to the wrapper
                                        if (!empty($style['backgroundColor'])) {
                                            $wrapperStyleStr .= 'background-color:' . $style['backgroundColor'] . ';';
                                        }
                                    @endphp
                                    <div class="desktop gallery-component mb-4" style="display:grid;grid-template-columns:repeat({{ $columns }},1fr);gap:16px;{{ $wrapperStyleStr }}">
                                        @foreach($images as $img)
                                            <div style="width:100%;overflow:hidden;border-radius:8px;">
                                                <img src="{{ $img }}" alt="Gallery Image" class="gallery-img-preview" data-img="{{ $img }}" style="width:100%;height:auto;display:block;object-fit:cover;cursor:pointer;">
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="mobile row gallery-component mb-4" style="display:none;grid-template-columns:repeat({{ $columns }},1fr);gap:16px;{{ $wrapperStyleStr }}">
                                        @foreach($images as $img)
                                            <div class="col-md-12 mt-4" style="width:100%;overflow:hidden;border-radius:8px;">
                                                <img src="{{ $img }}" alt="Gallery Image" class="gallery-img-preview" data-img="{{ $img }}" style="width:100%;height:auto;display:block;object-fit:cover;cursor:pointer;">
                                            </div>
                                        @endforeach
                                    </div>
                                @break
                                @case('slider')
                                    @php
                                        $slider = $data['sliderData'] ?? [];
                                        $images = $slider['images'] ?? [];
                                        $slidesToShow = $slider['slidesToShow'] ?? 1;
                                        $slideSpeed = $slider['slideSpeed'] ?? 2000;
                                        $sliderId = 'sliderPreview_' . uniqid();
                                        $wrapperStyle = $data['wrapperStyle'] ?? [];
                                        $wrapperStyleStr = '';
                                        foreach ($wrapperStyle as $k => $v) {
                                            if ($v) $wrapperStyleStr .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
                                        }
                                    @endphp
                                    <div class="slider-component mb-4" style="{{ $wrapperStyleStr }}">
                                        <div id="{{ $sliderId }}" class="owl-carousel owl-theme" data-slides-to-show="{{ $slidesToShow }}" data-slide-speed="{{ $slideSpeed }}">
                                            @foreach($images as $img)
                                                <div class="item">
                                                    <img src="{{ $img }}" alt="Slider Image" style="width:100%;height:auto;object-fit:cover;border-radius:8px;">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            $('#{{ $sliderId }}').owlCarousel({
                                                items: {{ $slidesToShow }},
                                                loop: true,
                                                margin: 10,
                                                autoplay: true,
                                                autoplayTimeout: {{ $slideSpeed }},
                                                responsive: {
                                                    0: {
                                                        items: 1
                                                    },
                                                    600: {
                                                        items: Math.max(1, Math.min({{ $slidesToShow }}, 2))
                                                    },
                                                    1000: {
                                                        items: {{ $slidesToShow }}
                                                    }
                                                }
                                                // dots: true,
                                                // nav: true
                                            });
                                        });
                                    </script>
                                @break

                                @case('inner-section')
                                    @php
                                        $innerSectionData = $data['innerSectionData'] ?? [];
                                        $nestedComponents = $data['nestedComponents'] ?? [];
                                        $columns = $innerSectionData['columns'] ?? 2;
                                        $fullWidth = isset($innerSectionData['fullWidth']) && $innerSectionData['fullWidth'];
                                        
                                        // Build inner-section styles from wrapperStyle and style
                                        $innerSectionStyles = '';
                                        $wrapperStyle = $data['wrapperStyle'] ?? [];
                                        $style = $data['style'] ?? [];
                                        
                                        // Process wrapper styles (margin, padding, etc.)
                                        foreach ($wrapperStyle as $k => $v) {
                                            if ($v) $innerSectionStyles .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
                                        }
                                        
                                        // Process component styles (background, border, etc.)
                                        foreach ($style as $k => $v) {
                                            if ($v) $innerSectionStyles .= strtolower(preg_replace('/([A-Z])/', '-$1', $k)) . ":$v;";
                                        }
                                        
                                        // Check for parallax background
                                        $hasParallax = !empty($style['backgroundImage']) && 
                                                      isset($style['backgroundAttachment']) && 
                                                      $style['backgroundAttachment'] === 'fixed';
                                        
                                        // Calculate Bootstrap column classes
                                        $bootstrapClass = '';
                                        switch($columns) {
                                            case 1: $bootstrapClass = 'col-12'; break;
                                            case 2: $bootstrapClass = 'col-lg-6 col-md-6 col-sm-12'; break;
                                            case 3: $bootstrapClass = 'col-lg-4 col-md-6 col-sm-12'; break;
                                            case 4: $bootstrapClass = 'col-lg-3 col-md-6 col-sm-12'; break;
                                            case 5: $bootstrapClass = 'col-lg-2 col-md-4 col-sm-6 col-12'; break;
                                            case 6: $bootstrapClass = 'col-lg-2 col-md-4 col-sm-6 col-12'; break;
                                            default: $bootstrapClass = 'col-lg-4 col-md-6 col-sm-12';
                                        }
                                        
                                        // Build CSS classes
                                        $wrapperClasses = 'inner-section-wrapper';
                                        if ($fullWidth) $wrapperClasses .= ' full-width';
                                        if ($hasParallax) $wrapperClasses .= ' has-parallax';
                                    @endphp
                                    
                                    {{-- Inner section wrapper with styling support --}}
                                    <div class="{{ $wrapperClasses }}" style="{{ $innerSectionStyles }}">
                                        @if($fullWidth)
                                            {{-- Full width layout --}}
                                            <div class="inner-section-component">
                                                <div class="row">
                                        @else
                                            {{-- Regular containerized layout --}}
                                            <div class="container">
                                                <div class="inner-section-component">
                                                    <div class="row">
                                        @endif
                                            @for($i = 0; $i < $columns; $i++)
                                                <div class="inner-column {{ $bootstrapClass }}">
                                                    {{-- Render nested components for this column with their normal styling --}}
                                                    @if(isset($nestedComponents[$i]) && is_array($nestedComponents[$i]))
                                                        @foreach($nestedComponents[$i] as $nestedIndex => $nestedComponent)
                                                            @if(isset($nestedComponent['type']))
                                                                @php $nestedComponentId = "nested-{$i}-{$nestedIndex}"; @endphp
                                                                {{-- Each component renders with its own styling like any other component --}}
                                                                <div class="component mb-4" data-type="{{ $nestedComponent['type'] }}" id="{{ $nestedComponentId }}">
                                                                    <div class="component-content">
                                                                @switch($nestedComponent['type'])
                                                                    @case('heading')
                                                                        @php
                                                                            $headingLevel = $nestedComponent['data']['level'] ?? 'h2';
                                                                            $headingText = $nestedComponent['data']['html'] ?? 'Heading';
                                                                            $headingStyle = '';
                                                                            if(isset($nestedComponent['data']['style'])) {
                                                                                foreach($nestedComponent['data']['style'] as $key => $value) {
                                                                                    $headingStyle .= $key . ':' . $value . ';';
                                                                                }
                                                                            }
                                                                        @endphp
                                                                        <{{ $headingLevel }} style="{{ $headingStyle }}">
                                                                            {!! $headingText !!}
                                                                        </{{ $headingLevel }}>
                                                                    @break
                                                                    
                                                                    @case('text')
                                                                        @php
                                                                            $textContent = $nestedComponent['data']['html'] ?? 'Text content';
                                                                            $textStyle = '';
                                                                            if(isset($nestedComponent['data']['style'])) {
                                                                                foreach($nestedComponent['data']['style'] as $key => $value) {
                                                                                    $textStyle .= $key . ':' . $value . ';';
                                                                                }
                                                                            }
                                                                        @endphp
                                                                        <div style="{{ $textStyle }}">
                                                                            {!! $textContent !!}
                                                                        </div>
                                                                    @break
                                                                    
                                                                    @case('button')
                                                                        @php
                                                                            $buttonText = $nestedComponent['data']['text'] ?? 'Button';
                                                                            $buttonHref = $nestedComponent['data']['href'] ?? '#';
                                                                            $buttonTarget = $nestedComponent['data']['target'] ?? '_self';
                                                                            $buttonStyle = '';
                                                                            if(isset($nestedComponent['data']['style'])) {
                                                                                foreach($nestedComponent['data']['style'] as $key => $value) {
                                                                                    $buttonStyle .= $key . ':' . $value . ';';
                                                                                }
                                                                            }
                                                                        @endphp
                                                                        <button 
                                                                            class="btn btn-primary"
                                                                            style="{{ $buttonStyle }}"
                                                                            data-href="{{ $buttonHref }}"
                                                                            data-target="{{ $buttonTarget }}"
                                                                        >
                                                                            {{ $buttonText }}
                                                                        </button>
                                                                    @break
                                                                    
                                                                    @case('image')
                                                                        @php
                                                                            // Use imageData structure like the main image component
                                                                            $imageData = $nestedComponent['imageData'] ?? [];
                                                                            $imageSrc = $imageData['src'] ?? '';
                                                                            $imageAlt = $imageData['alt'] ?? 'Image';
                                                                            $imageWidth = $imageData['width'] ?? '';
                                                                            $imageHeight = $imageData['height'] ?? '';
                                                                            $imageObjectFit = $imageData['objectFit'] ?? '';
                                                                            $imageLink = $imageData['link'] ?? '';
                                                                            $imageOpenInNewTab = $imageData['openInNewTab'] ?? false;
                                                                            $displayMode = $imageData['displayMode'] ?? 'preview';
                                                                            
                                                                            $imageStyle = '';
                                                                            if(isset($nestedComponent['style'])) {
                                                                                foreach($nestedComponent['style'] as $key => $value) {
                                                                                    if($value) $imageStyle .= $key . ':' . $value . ';';
                                                                                }
                                                                            }
                                                                            if($imageWidth) $imageStyle .= "width:{$imageWidth};";
                                                                            if($imageHeight) $imageStyle .= "height:{$imageHeight};";
                                                                            if($imageObjectFit) $imageStyle .= "object-fit:{$imageObjectFit};";
                                                                            $imageStyle .= "border-radius:8px;cursor:pointer;transition:box-shadow .2s;";
                                                                        @endphp
                                                                        @if($imageSrc)
                                                                            @if($displayMode === 'link' && $imageLink)
                                                                                <a href="{{ $imageLink }}" @if($imageOpenInNewTab) target="_blank" @endif>
                                                                                    <img 
                                                                                        src="{{ $imageSrc }}" 
                                                                                        alt="{{ $imageAlt }}" 
                                                                                        style="{{ $imageStyle }}"
                                                                                        data-img="{{ $imageSrc }}"
                                                                                    >
                                                                                </a>
                                                                            @else
                                                                                <a href="javascript:void(0);" class="image-link" style="display:inline-block;">
                                                                                    <img 
                                                                                        src="{{ $imageSrc }}" 
                                                                                        alt="{{ $imageAlt }}" 
                                                                                        class="img-preview"
                                                                                        style="{{ $imageStyle }}"
                                                                                        data-img="{{ $imageSrc }}"
                                                                                    >
                                                                                </a>
                                                                            @endif
                                                                        @endif
                                                                    @break
                                                                    
                                                                    @case('section-title')
                                                                        @php
                                                                            $titleText = $nestedComponent['data']['text'] ?? 'Section Title';
                                                                            $titleStyle = '';
                                                                            if(isset($nestedComponent['data']['style'])) {
                                                                                foreach($nestedComponent['data']['style'] as $key => $value) {
                                                                                    // Add !important to text-align to override any default styles
                                                                                    if ($key === 'text-align') {
                                                                                        $titleStyle .= $key . ':' . $value . ' !important;';
                                                                                    } else {
                                                                                        $titleStyle .= $key . ':' . $value . ';';
                                                                                    }
                                                                                }
                                                                            }
                                                                        @endphp
                                                                        <h3 style="{{ $titleStyle }}">
                                                                            {{ $titleText }}
                                                                        </h3>
                                                                    @break
                                                                    
                                                                    @case('divider')
                                                                        @php
                                                                            $dividerStyle = '';
                                                                            if(isset($nestedComponent['data']['style'])) {
                                                                                foreach($nestedComponent['data']['style'] as $key => $value) {
                                                                                    $dividerStyle .= $key . ':' . $value . ';';
                                                                                }
                                                                            }
                                                                        @endphp
                                                                        <hr style="{{ $dividerStyle }}">
                                                                    @break
                                                                    
                                                                    @default
                                                                        {{-- Render any other component type with full styling --}}
                                                                        <div class="component-wrapper">
                                                                            @switch($nestedComponent['type'])
                                                                                @case('site-banner')
                                                                                    @php
                                                                                        $bannerSrc = $nestedComponent['data']['src'] ?? '';
                                                                                        $bannerAlt = $nestedComponent['data']['alt'] ?? 'Site Banner';
                                                                                        $bannerStyle = '';
                                                                                        if(isset($nestedComponent['data']['style'])) {
                                                                                            foreach($nestedComponent['data']['style'] as $key => $value) {
                                                                                                $bannerStyle .= $key . ':' . $value . ';';
                                                                                            }
                                                                                        }
                                                                                    @endphp
                                                                                    @if($bannerSrc)
                                                                                        <div class="site-banner" style="{{ $bannerStyle }}">
                                                                                            <img src="{{ $bannerSrc }}" alt="{{ $bannerAlt }}" class="img-fluid w-100">
                                                                                        </div>
                                                                                    @endif
                                                                                @break
                                                                                
                                                                                @case('gallery')
                                                                                    @php
                                                                                        $gallery = $nestedComponent['data']['galleryData'] ?? [];
                                                                                        $images = $gallery['images'] ?? [];
                                                                                        $columns = $gallery['columns'] ?? 3;
                                                                                        $wrapperStyle = $nestedComponent['data']['wrapperStyle'] ?? [];
                                                                                        $wrapperStyleStr = '';
                                                                                        foreach ($wrapperStyle as $k => $v) {
                                                                                            if ($v) $wrapperStyleStr .= $k . ':' . $v . ';';
                                                                                        }
                                                                                    @endphp
                                                                                    <div class="gallery-component mb-4" style="display:grid;grid-template-columns:repeat({{ $columns }},1fr);gap:16px;{{ $wrapperStyleStr }}">
                                                                                        @foreach($images as $img)
                                                                                            <div style="width:100%;overflow:hidden;border-radius:8px;">
                                                                                                <img src="{{ $img['src'] ?? '' }}" alt="{{ $img['alt'] ?? 'Gallery Image' }}" class="img-fluid">
                                                                                            </div>
                                                                                        @endforeach
                                                                                    </div>
                                                                                @break
                                                                                
                                                                                @default
                                                                                    {{-- Silent fallback - no placeholder text displayed --}}
                                                                                    <div style="display: none;"></div>
                                                                            @endswitch
                                                                        </div>
                                                                @endswitch
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </div>
                                            @endfor
                                        @if($fullWidth)
                                            {{-- Close full-width layout --}}
                                                </div>
                                            </div>
                                        @else
                                            {{-- Close regular containerized layout --}}
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                @break

                                @case('heading')
                                    @php
                                        $headingLevel = 'h2'; // Default heading level
                                        $headingText = $data['html'] ?? 'Heading';
                                        $headingStyle = '';
                                        if(isset($data['style'])) {
                                            $styleArray = [];
                                            foreach($data['style'] as $key => $value) {
                                                $styleArray[] = $key . ':' . $value;
                                            }
                                            $headingStyle = implode(';', $styleArray);
                                        }
                                    @endphp
                                    <{{ $headingLevel }} style="{{ $headingStyle }}">
                                        {!! $headingText !!}
                                    </{{ $headingLevel }}>
                                @break

                                @case('text')
                                    @php
                                        $textContent = $data['html'] ?? 'Text content';
                                        $textStyle = '';
                                        if(isset($data['style'])) {
                                            $styleArray = [];
                                            foreach($data['style'] as $key => $value) {
                                                $styleArray[] = $key . ':' . $value;
                                            }
                                            $textStyle = implode(';', $styleArray);
                                        }
                                    @endphp
                                    <p style="{{ $textStyle }}">
                                        {!! $textContent !!}
                                    </p>
                                @break

                                @case('site-banner')
                                    @php
                                        $bannerSrc = $data['src'] ?? '';
                                        $bannerAlt = $data['alt'] ?? 'Site Banner';
                                        $bannerStyle = '';
                                        if(isset($data['style'])) {
                                            $styleArray = [];
                                            foreach($data['style'] as $key => $value) {
                                                $styleArray[] = $key . ':' . $value;
                                            }
                                            $bannerStyle = implode(';', $styleArray);
                                        }
                                    @endphp
                                    @if($bannerSrc)
                                        <div class="site-banner" style="{{ $bannerStyle }}">
                                            <img src="{{ $bannerSrc }}" alt="{{ $bannerAlt }}" class="img-fluid w-100">
                                        </div>
                                    @endif
                                @break

                                @case('visitor-upload')
                                    <div class="visitor-upload-component">
                                        <h4>Upload Your Photos</h4>
                                        <form method="POST" enctype="multipart/form-data" action="/upload-visitor-photo">
                                            @csrf
                                            <div class="mb-3">
                                                <input type="file" class="form-control" name="visitor_photo" accept="image/*" required>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Upload Photo</button>
                                        </form>
                                    </div>
                                @break

                                @case('display-assets')
                                    <div class="display-assets-component">
                                        <h4>Assets & Downloads</h4>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="card">
                                                    <div class="card-body text-center">
                                                        <i class="fas fa-file-pdf fa-3x text-danger mb-2"></i>
                                                        <h5>Document 1</h5>
                                                        <a href="#" class="btn btn-outline-primary">Download</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="card">
                                                    <div class="card-body text-center">
                                                        <i class="fas fa-file-image fa-3x text-success mb-2"></i>
                                                        <h5>Image 1</h5>
                                                        <a href="#" class="btn btn-outline-primary">Download</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="card">
                                                    <div class="card-body text-center">
                                                        <i class="fas fa-file-video fa-3x text-info mb-2"></i>
                                                        <h5>Video 1</h5>
                                                        <a href="#" class="btn btn-outline-primary">Download</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @break

                                @case('cards')
                                    <div class="cards-component">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="card mb-3">
                                                    <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Card Image">
                                                    <div class="card-body">
                                                        <h5 class="card-title">Card Title 1</h5>
                                                        <p class="card-text">Some quick example text to build on the card title.</p>
                                                        <a href="#" class="btn btn-primary">Learn More</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="card mb-3">
                                                    <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Card Image">
                                                    <div class="card-body">
                                                        <h5 class="card-title">Card Title 2</h5>
                                                        <p class="card-text">Some quick example text to build on the card title.</p>
                                                        <a href="#" class="btn btn-primary">Learn More</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="card mb-3">
                                                    <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Card Image">
                                                    <div class="card-body">
                                                        <h5 class="card-title">Card Title 3</h5>
                                                        <p class="card-text">Some quick example text to build on the card title.</p>
                                                        <a href="#" class="btn btn-primary">Learn More</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @break

                                @case('donation-slider')
                                    <div class="donation-slider-component">
                                        <h4>Recent Donations</h4>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <p class="text-muted">Donation tracking slider would be displayed here.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @break

                                @case('updates')
                                    <div class="updates-component">
                                        <h4>Latest Updates</h4>
                                        <div class="list-group">
                                            <div class="list-group-item">
                                                <div class="d-flex w-100 justify-content-between">
                                                    <h5 class="mb-1">Update Title 1</h5>
                                                    <small>3 days ago</small>
                                                </div>
                                                <p class="mb-1">Some placeholder content for the update.</p>
                                                <small>Posted by Admin</small>
                                            </div>
                                            <div class="list-group-item">
                                                <div class="d-flex w-100 justify-content-between">
                                                    <h5 class="mb-1">Update Title 2</h5>
                                                    <small>1 week ago</small>
                                                </div>
                                                <p class="mb-1">Some placeholder content for another update.</p>
                                                <small>Posted by Admin</small>
                                            </div>
                                        </div>
                                    </div>
                                @break

                                @case('facebook-comments')
                                    <div class="facebook-comments-component">
                                        <h4>Comments</h4>
                                        <div class="fb-comments" data-href="{{ url()->current() }}" data-width="100%" data-numposts="5"></div>
                                        <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v18.0"></script>
                                    </div>
                                @break

                                @case('sponsorships')
                                    <div class="sponsorships-component">
                                        <h4>Our Sponsors</h4>
                                        <div class="row">
                                            <div class="col-md-3 col-6 mb-3">
                                                <div class="sponsor-logo text-center">
                                                    <img src="https://via.placeholder.com/150x100" class="img-fluid" alt="Sponsor 1">
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-6 mb-3">
                                                <div class="sponsor-logo text-center">
                                                    <img src="https://via.placeholder.com/150x100" class="img-fluid" alt="Sponsor 2">
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-6 mb-3">
                                                <div class="sponsor-logo text-center">
                                                    <img src="https://via.placeholder.com/150x100" class="img-fluid" alt="Sponsor 3">
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-6 mb-3">
                                                <div class="sponsor-logo text-center">
                                                    <img src="https://via.placeholder.com/150x100" class="img-fluid" alt="Sponsor 4">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @break

                                @case('contact-us')
                                    <div class="contact-us-component">
                                        <h4>Contact Us</h4>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="contact-info">
                                                    <h5>Get in Touch</h5>
                                                    <p><i class="fas fa-phone"></i> +1 (555) 123-4567</p>
                                                    <p><i class="fas fa-envelope"></i> contact@example.com</p>
                                                    <p><i class="fas fa-map-marker-alt"></i> 123 Main St, City, State 12345</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <form>
                                                    <div class="mb-3">
                                                        <input type="text" class="form-control" placeholder="Your Name" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <input type="email" class="form-control" placeholder="Your Email" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <textarea class="form-control" rows="4" placeholder="Your Message" required></textarea>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Send Message</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @break

                                @default
                                    {!! $data['html'] ?? '' !!}
                            @endswitch
                        </div>
                        
                @if($isFullWidth)
                    {{-- Close full-width component --}}
                    </div>
                @else
                    {{-- Close regular component with container --}}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @endif
            @endforeach
    </div>
    </main>
    <!-- Gallery Image Modal -->
<div class="modal" id="galleryImageModal" tabindex="-1" aria-labelledby="galleryImageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-transparent border-0">
      <div class="modal-body text-center p-0">
        <img id="galleryImageModalImg" src="" alt="Gallery Preview" style="max-width:100%;max-height:80vh;border-radius:12px;">
      </div>
    </div>
  </div>
</div>
@if ($footer)
@if ($footer->status == 1)
<footer class="standard-client-footer text-white bg-primary" data-footer="" style="
background-color: {{ $footer->background }} !important;
">
    <div class="container">

                    <p class="lead text-center pt-4" style="color: {{ $footer->color }} !important">
                {{ $footer->message }}
            </p>
                    @if ($footer->menu == 1)
                        <div class="nav justify-content-center">
                            @foreach ($check->pages->sortBy('position') as $item)

                            @if($item->status == 1)

                            <div class="nav-item">
                                <a class="nav-link active" href="/page/{{ str_replace(' ', '-', strtolower($item->name)) }}" style="color:{{ $footer->color }} !important" aria-current="page">
                                {{ $item->name }}
                                </a>
                            </div>
                            @endif

                            @endforeach
                                                    </div>
                    @endif

                    @if ($footer->social == 1)
                        <ul class="nav justify-content-center footer-socials mt-4 mb-4">
                            @if ($footer->facebook)
                                <li class="nav-item">
                                    <a href="{{ $footer->facebook }}" target="_blank">
                                        <i class="fa-brands fa-facebook fa-fw" role="img" aria-hidden="true" style="color: {{ $footer->color }} !important"></i>
                                        <span class="visually-hidden">facebook</span>
                                    </a>
                                </li>
                            @endif

                            @if ($footer->instagram)
                                <li class="nav-item">
                                    <a href="{{ $footer->instagram }}" target="_blank">
                                        <i class="fa-brands fa-instagram fa-fw" role="img" aria-hidden="true" style="color: {{ $footer->color }} !important"></i>
                                        <span class="visually-hidden">instagram</span>
                                    </a>
                                </li>
                            @endif

                            @if ($footer->linkedin)
                                <li class="nav-item">
                                    <a href="{{ $footer->linkedin }}" target="_blank">
                                        <i class="fa-brands fa-linkedin fa-fw" role="img" aria-hidden="true" style="color: {{ $footer->color }} !important"></i>
                                        <span class="visually-hidden">linkedin</span>
                                    </a>
                                </li>
                            @endif

                            @if ($footer->pinterest)
                                <li class="nav-item">
                                    <a href="{{ $footer->pinterest }}" target="_blank">
                                        <i class="fa-brands fa-pinterest fa-fw" role="img" aria-hidden="true" style="color: {{ $footer->color }} !important"></i>
                                        <span class="visually-hidden">pinterest</span>
                                    </a>
                                </li>
                            @endif

                            @if ($footer->x)
                                <li class="nav-item">
                                    <a href="{{ $footer->x }}" target="_blank">
                                        <i class="fa-brands fa-x-twitter fa-fw" role="img" aria-hidden="true" style="color: {{ $footer->color }} !important"></i>
                                        <span class="visually-hidden">x</span>
                                    </a>
                                </li>
                            @endif

                            @if ($footer->youtube)
                                <li class="nav-item">
                                    <a href="{{ $footer->youtube }}" target="_blank">
                                        <i class="fa-brands fa-youtube fa-fw" role="img" aria-hidden="true" style="color: {{ $footer->color }} !important"></i>
                                        <span class="visually-hidden">youtube</span>
                                    </a>
                                </li>
                            @endif

                            @if ($footer->blue_sky)
                                <li class="nav-item">
                                    <a href="{{ $footer->blue_sky }}" target="_blank">
                                        <i class="fa-solid fa-cloud fa-fw" role="img" aria-hidden="true" style="color: {{ $footer->color }} !important"></i>
                                        <span class="visually-hidden">blue sky</span>
                                    </a>
                                </li>
                            @endif

                            @if ($footer->tiktok)
                                <li class="nav-item">
                                    <a href="{{ $footer->tiktok }}" target="_blank">
                                        <i class="fa-brands fa-tiktok fa-fw" role="img" aria-hidden="true" style="color: {{ $footer->color }} !important"></i>
                                        <span class="visually-hidden">tiktok</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    @endif

                @if ($footer->copy_right != null)
                    <p class="text-center" style="margin-bottom: 0px;">
                        <small style="color: {{ $footer->color }}">
                            {{ $footer->copy_right }}
                        </small>
                    </p>
                @endif
    </div>
    @if ($footer->privacy == 1)
        <div class="row mt-4">
            <div class="col-md-12 text-center">
                <ul style="display: inline-flex; list-style: none; margin-left: 0px; margin-top: 20px; margin-bottom: 5px;">
                        <li style="margin-right: 1rem;">
                            <a style="color: #1773b0; text-decoration: underline;" href="/page/{{ str_replace(' ', '-', strtolower($setting->refund ? $setting->refund_page->name : '#')) }}">Refund Policy</a>
                        </li>
                        <li style="margin-right: 1rem;">
                            <a style="color: #1773b0; text-decoration: underline;" href="/page/{{ str_replace(' ', '-', strtolower($setting->privacy ? $setting->privacy_page->name : '#')) }}">Privacy Policy</a>
                        </li>
                        <li style="margin-right: 1rem;">
                            <a style="color: #1773b0; text-decoration: underline;" href="/page/{{ str_replace(' ', '-', strtolower($setting->terms ? $setting->terms_page->name : '#')) }}">Terms of service</a>
                        </li>
                    </ul>
            </div>
        </div>
    @endif
</footer>
@endif 
@endif


</body>


<script>
        const goal = document.getElementById('goal').value;
        const raised = document.getElementById('raised').value;

        const fill = document.getElementById('fill');
        const bar = document.querySelector('.bar');

        function updateFill() {
        const barWidth = bar.clientWidth;
        const fillWidth = Math.min(raised / goal, 1) * barWidth;
        fill.style.width = `${fillWidth}px`;
        }

        updateFill();
        window.addEventListener('resize', updateFill);

        document.getElementById('goal-label').textContent = `Goal: $${goal.toLocaleString(undefined, {minimumFractionDigits: 2})}`;
        document.getElementById('raised-label').textContent = `Raised: $${raised.toLocaleString(undefined, {minimumFractionDigits: 2})}`;
</script>

@if(isset($data->enable_confetti) && $data->enable_confetti)
<script>
    (() => {
        "use strict";

        // Utility functions grouped into a single object
        const Utils = {
            // Parse pixel values to numeric values
            parsePx: (value) => parseFloat(value.replace(/px/, "")),

            // Generate a random number between two values, optionally with a fixed precision
            getRandomInRange: (min, max, precision = 0) => {
            const multiplier = Math.pow(10, precision);
            const randomValue = Math.random() * (max - min) + min;
            return Math.floor(randomValue * multiplier) / multiplier;
            },

            // Pick a random item from an array
            getRandomItem: (array) => array[Math.floor(Math.random() * array.length)],

            // Scaling factor based on screen width
            getScaleFactor: () => Math.log(window.innerWidth) / Math.log(1920),

            // Debounce function to limit event firing frequency
            debounce: (func, delay) => {
            let timeout;
            return (...args) => {
                clearTimeout(timeout);
                timeout = setTimeout(() => func(...args), delay);
            };
            },
        };

        // Precomputed constants
        const DEG_TO_RAD = Math.PI / 180;

        // Centralized configuration for default values
        const defaultConfettiConfig = {
            confettiesNumber: 120,
            confettiRadius: 4,
            confettiColors: [
            "#2e4053", "#b7bcc4"
            ],
            emojies: [],
            svgIcon: null, // Example SVG link
        };

        // Confetti class representing individual confetti pieces
        class Confetti {
            constructor({ initialPosition, direction, radius, colors, emojis, svgIcon }) {
            const speedFactor = Utils.getRandomInRange(0.9, 1.7, 3) * Utils.getScaleFactor();
            this.speed = { x: speedFactor, y: speedFactor };
            this.finalSpeedX = Utils.getRandomInRange(0.2, 0.6, 3);
            this.rotationSpeed = emojis.length || svgIcon ? 0.01 : Utils.getRandomInRange(0.03, 0.07, 3) * Utils.getScaleFactor();
            this.dragCoefficient = Utils.getRandomInRange(0.0005, 0.0009, 6);
            this.radius = { x: radius, y: radius };
            this.initialRadius = radius;
            this.rotationAngle = direction === "left" ? Utils.getRandomInRange(0, 0.2, 3) : Utils.getRandomInRange(-0.2, 0, 3);
            this.emojiRotationAngle = Utils.getRandomInRange(0, 2 * Math.PI);
            this.radiusYDirection = "down";

            const angle = direction === "left" ? Utils.getRandomInRange(82, 15) * DEG_TO_RAD : Utils.getRandomInRange(-15, -82) * DEG_TO_RAD;
            this.absCos = Math.abs(Math.cos(angle));
            this.absSin = Math.abs(Math.sin(angle));

            const offset = Utils.getRandomInRange(-150, 0);
            const position = {
                x: initialPosition.x + (direction === "left" ? -offset : offset) * this.absCos,
                y: initialPosition.y - offset * this.absSin
            };

            this.position = { ...position };
            this.initialPosition = { ...position };
            this.color = emojis.length || svgIcon ? null : Utils.getRandomItem(colors);
            this.emoji = emojis.length ? Utils.getRandomItem(emojis) : null;
            this.svgIcon = null;

            // Preload SVG if provided
            if (svgIcon) {
                this.svgImage = new Image();
                this.svgImage.src = svgIcon;
                this.svgImage.onload = () => {
                this.svgIcon = this.svgImage; // Mark as ready once loaded
                };
            }

            this.createdAt = Date.now();
            this.direction = direction;
            }

            draw(context) {
            const { x, y } = this.position;
            const { x: radiusX, y: radiusY } = this.radius;
            const scale = window.devicePixelRatio;

            if (this.svgIcon) {
                context.save();
                context.translate(scale * x, scale * y);
                context.rotate(this.emojiRotationAngle);
                context.drawImage(this.svgIcon, -radiusX, -radiusY, radiusX * 2, radiusY * 2);
                context.restore();
            } else if (this.color) {
                context.fillStyle = this.color;
                context.beginPath();
                context.ellipse(x * scale, y * scale, radiusX * scale, radiusY * scale, this.rotationAngle, 0, 2 * Math.PI);
                context.fill();
            } else if (this.emoji) {
                context.font = `${radiusX * scale}px serif`;
                context.save();
                context.translate(scale * x, scale * y);
                context.rotate(this.emojiRotationAngle);
                context.textAlign = "center";
                context.fillText(this.emoji, 0, radiusY / 2); // Adjust vertical alignment
                context.restore();
            }
            }

            updatePosition(deltaTime, currentTime) {
            const elapsed = currentTime - this.createdAt;

            if (this.speed.x > this.finalSpeedX) {
                this.speed.x -= this.dragCoefficient * deltaTime;
            }

            this.position.x += this.speed.x * (this.direction === "left" ? -this.absCos : this.absCos) * deltaTime;
            this.position.y = this.initialPosition.y - this.speed.y * this.absSin * elapsed + 0.00125 * Math.pow(elapsed, 2) / 2;

            if (!this.emoji && !this.svgIcon) {
                this.rotationSpeed -= 1e-5 * deltaTime;
                this.rotationSpeed = Math.max(this.rotationSpeed, 0);

                if (this.radiusYDirection === "down") {
                this.radius.y -= deltaTime * this.rotationSpeed;
                if (this.radius.y <= 0) {
                    this.radius.y = 0;
                    this.radiusYDirection = "up";
                }
                } else {
                this.radius.y += deltaTime * this.rotationSpeed;
                if (this.radius.y >= this.initialRadius) {
                    this.radius.y = this.initialRadius;
                    this.radiusYDirection = "down";
                }
                }
            }
            }

            isVisible(canvasHeight) {
            return this.position.y < canvasHeight + 100;
            }
        }

        class ConfettiManager {
            constructor() {
            this.canvas = document.createElement("canvas");
            this.canvas.style = "position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 1000; pointer-events: none;";
            document.body.appendChild(this.canvas);
            this.context = this.canvas.getContext("2d");
            this.confetti = [];
            this.lastUpdated = Date.now();
            window.addEventListener("resize", Utils.debounce(() => this.resizeCanvas(), 200));
            this.resizeCanvas();
            requestAnimationFrame(() => this.loop());
            }

            resizeCanvas() {
            this.canvas.width = window.innerWidth * window.devicePixelRatio;
            this.canvas.height = window.innerHeight * window.devicePixelRatio;
            }

            addConfetti(config = {}) {
            const { confettiesNumber, confettiRadius, confettiColors, emojies, svgIcon } = {
                ...defaultConfettiConfig,
                ...config,
            };

            const baseY = (5 * window.innerHeight) / 7;
            for (let i = 0; i < confettiesNumber / 2; i++) {
                this.confetti.push(new Confetti({
                initialPosition: { x: 0, y: baseY },
                direction: "right",
                radius: confettiRadius,
                colors: confettiColors,
                emojis: emojies,
                svgIcon,
                }));
                this.confetti.push(new Confetti({
                initialPosition: { x: window.innerWidth, y: baseY },
                direction: "left",
                radius: confettiRadius,
                colors: confettiColors,
                emojis: emojies,
                svgIcon,
                }));
            }
            }

            resetAndStart(config = {}) {
            // Clear existing confetti
            this.confetti = [];
            // Add new confetti
            this.addConfetti(config);
            }

            loop() {
            const currentTime = Date.now();
            const deltaTime = currentTime - this.lastUpdated;
            this.lastUpdated = currentTime;

            this.context.clearRect(0, 0, this.canvas.width, this.canvas.height);

            this.confetti = this.confetti.filter((item) => {
                item.updatePosition(deltaTime, currentTime);
                item.draw(this.context);
                return item.isVisible(this.canvas.height);
            });

            requestAnimationFrame(() => this.loop());
            }
        }

        // Trigger confetti 5 times
        function triggerConfettiMultipleTimes(times, delay) {
            let count = 0;
            const intervalId = setInterval(() => {
                const manager = new ConfettiManager();
        // manager.addConfetti();
                manager.addConfetti(); // Trigger confetti
                count++;
                if (count >= times) {
                    clearInterval(intervalId); // Stop after triggering 5 times
                }
            }, delay);
        }

        triggerConfettiMultipleTimes(5, 500);



        const triggerButton = document.getElementById("show-again");
        if (triggerButton) {
            triggerButton.addEventListener("click", () => manager.addConfetti());
        }

        const resetInput = document.getElementById("reset");
        if (resetInput) {
            resetInput.addEventListener("input", () => manager.resetAndStart());
        }
        })();
</script>
@endif

<script>
da = document.getElementById("time").value;
// Set the target date for the countdown
const targetDate = new Date(da).getTime();

function updateCountdown() {
const now = new Date().getTime();
const timeLeft = targetDate - now;

if (timeLeft <= 0) {
    document.getElementById("months").textContent = 0;
    document.getElementById("days").textContent = 0;
    document.getElementById("hours").textContent = 0;
    document.getElementById("minutes").textContent = 0;
    document.getElementById("seconds").textContent = 0;
    return;
}

// Calculate time components
const months = Math.floor(timeLeft / (1000 * 60 * 60 * 24 * 30));
const days = Math.floor((timeLeft % (1000 * 60 * 60 * 24 * 30)) / (1000 * 60 * 60 * 24));
const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

// Update the HTML
document.getElementById("months").textContent = months;
document.getElementById("days").textContent = days;
document.getElementById("hours").textContent = hours;
document.getElementById("minutes").textContent = minutes;
document.getElementById("seconds").textContent = seconds;
}

// Update the countdown every second
setInterval(updateCountdown, 1000);
</script>
<!-- Include DataTables and jQuery CDN -->
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<script>
    // Global function to safely initialize DataTables
    function initStudentTable() {
        // Student listing search is now handled by inline JavaScript in the component
        // This function is kept for backwards compatibility but does nothing
    }

    $(document).ready(function() {
        $.fn.dataTable.ext.errMode = 'none';
        
        // Initialize the table
        initStudentTable();
        
        // Re-initialize when new content is loaded (for dynamic components)
        $(document).on('DOMContentLoaded', function() {
            initStudentTable();
        });

    });
</script>

<script>
    slidesToShow = $('#sliderPreview').data('slides-to-show') || 12; // Default to 3 if not set
    $('#sliderPreview').owlCarousel({
        items: slidesToShow,
        loop: true,
        margin: 10,
        // nav: true,
        // dots: true,
        autoplay: true,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: Math.max(1, Math.min(slidesToShow, 2))
            },
            1000: {
                items: slidesToShow
            }
        }
    });
</script>

<script>
    function toggleGroupSelect(sel) {
        var wrapper = document.getElementById('group_select_wrapper');
        var wrapper_name = document.getElementById('group_name_wrapper');
        if (sel.value === 'member') {
            wrapper.style.display = '';
            wrapper_name.style.display = 'none';
        }else if (sel.value === 'group_leader') {
            wrapper_name.style.display = '';
            wrapper.style.display = 'none';
        } else {
            wrapper.style.display = 'none';
            wrapper_name.style.display = 'none';
        }
    }
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.gallery-img-preview').forEach(function(img) {
        img.addEventListener('click', function() {
            var src = this.getAttribute('data-img');
            var modalImg = document.getElementById('galleryImageModalImg');
            modalImg.src = src;
            var modal = new bootstrap.Modal(document.getElementById('galleryImageModal'));
            modal.show();
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.img-preview').forEach(function(img) {
        img.addEventListener('click', function() {
            var src = this.getAttribute('data-img');
            var modalImg = document.getElementById('galleryImageModalImg');
            modalImg.src = src;
            var modal = new bootstrap.Modal(document.getElementById('galleryImageModal'));
            modal.show();
        });
    });
});
</script>

<script>
function startAuctionTimer(deadline, id) {
    function update() {
        const now = new Date().getTime();
        const target = new Date(deadline).getTime();
        let timeLeft = target - now;

        if (timeLeft <= 0) {
            document.getElementById('days-' + id).textContent = 0;
            document.getElementById('hours-' + id).textContent = 0;
            document.getElementById('minutes-' + id).textContent = 0;
            return;
        }

        const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
        const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

        document.getElementById('days-' + id).textContent = days;
        document.getElementById('hours-' + id).textContent = hours;
        document.getElementById('minutes-' + id).textContent = minutes;
    }
    update();
    setInterval(update, 1000);
}

document.addEventListener('DOMContentLoaded', function() {
    @foreach ($auction as $item)
        startAuctionTimer("{{ $item->dead_line }}", "{{ $item->id }}");
    @endforeach
});
</script>

<script type="module">
import { initializeApp } from "https://www.gstatic.com/firebasejs/11.9.1/firebase-app.js";
import { getFirestore, collection, query, where, orderBy, getDocs, limit } from "https://www.gstatic.com/firebasejs/11.9.1/firebase-firestore.js";

// Your Firebase config
const firebaseConfig = {
    apiKey: "AIzaSyD0QsLeSIAFeBBUouzhgUQ3WEGfM1MAYA4",
    authDomain: "charity-390ca.firebaseapp.com",
    projectId: "charity-390ca",
    storageBucket: "charity-390ca.firebasestorage.app",
    messagingSenderId: "875958450032",
    appId: "1:875958450032:web:338aeac86307e5ab3e41b5",
    measurementId: "G-FC73HL5XF3"
};

const app = initializeApp(firebaseConfig);
const firestore = getFirestore(app);

document.addEventListener('DOMContentLoaded', async function() {
    @foreach ($auction as $item)
        {
            const auctionId = "{{ $item->id }}";
            const priceDiv = document.getElementById('auction-price-{{ $item->id }}');
            if (priceDiv) {
                const bidsRef = collection(firestore, "bid");
                const q = query(
                    bidsRef,
                    where("auction_id", "==", auctionId),
                    orderBy("amount", "desc"),
                    limit(1)
                );
                const querySnapshot = await getDocs(q);
                if (!querySnapshot.empty) {
                    const doc = querySnapshot.docs[0];
                    const latestAmount = doc.data().amount;
                    priceDiv.textContent = '$' + latestAmount;
                }
            }
        }
    @endforeach
});

// Inner Section and Component Functionality
document.addEventListener('DOMContentLoaded', function() {
    // Initialize responsive behavior for inner sections
    initializeInnerSections();
    
    // Handle image clicks for galleries
    initializeImageGalleries();
    
    // Handle button clicks
    initializeButtons();
});

function initializeInnerSections() {
    const innerSections = document.querySelectorAll('.inner-section-component');
    
    innerSections.forEach(section => {
        // Add hover effects for columns
        const columns = section.querySelectorAll('.inner-column');
        columns.forEach(column => {
            const dropzone = column.querySelector('.column-dropzone');
            const nestedComponents = column.querySelectorAll('.nested-component');
            
            // Hide dropzone if column has components
            if (nestedComponents.length > 0 && dropzone) {
                dropzone.style.display = 'none';
            }
            
            // Add interactive hover effects
            column.addEventListener('mouseenter', function() {
                if (nestedComponents.length === 0 && dropzone) {
                    dropzone.style.opacity = '1';
                }
            });
            
            column.addEventListener('mouseleave', function() {
                if (nestedComponents.length === 0 && dropzone) {
                    dropzone.style.opacity = '0.7';
                }
            });
        });
        
        // Add hover effects for nested components
        const nestedComponents = section.querySelectorAll('.nested-component');
        nestedComponents.forEach(component => {
            component.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-1px)';
                this.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
            });
            
            component.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '0 2px 8px rgba(0,0,0,0.1)';
            });
        });
    });
}

function initializeImageGalleries() {
    // Handle images in nested components and regular galleries
    const galleryImages = document.querySelectorAll('.nested-component img, .gallery img, .img-preview, .gallery-img-preview');
    
    galleryImages.forEach(img => {
        img.style.cursor = 'pointer';
        img.addEventListener('click', function() {
            const src = this.src || this.getAttribute('data-img');
            const alt = this.alt || 'Image Preview';
            openImageModal(src, alt);
        });
    });
}

function initializeButtons() {
    // Handle buttons in nested components and regular buttons
    const componentButtons = document.querySelectorAll('.nested-component button, .component button');
    
    componentButtons.forEach(button => {
        if (button.dataset.href) {
            button.style.cursor = 'pointer';
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const url = this.dataset.href;
                const target = this.dataset.target || '_self';
                
                if (url) {
                    if (target === '_blank') {
                        window.open(url, '_blank');
                    } else {
                        window.location.href = url;
                    }
                }
            });
        }
    });
}

function openImageModal(src, alt) {
    const modal = document.getElementById('galleryImageModal');
    if (modal && src) {
        const modalImg = document.getElementById('galleryImageModalImg');
        if (modalImg) {
            modalImg.src = src;
            modalImg.alt = alt || 'Image Preview';
        }
        
        // Use Bootstrap modal if available
        if (typeof bootstrap !== 'undefined') {
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
        } else {
            modal.style.display = 'block';
        }
    }
}

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    const modal = document.getElementById('galleryImageModal');
    if (e.target === modal) {
        if (typeof bootstrap !== 'undefined') {
            const bsModal = bootstrap.Modal.getInstance(modal);
            if (bsModal) {
                bsModal.hide();
            }
        } else {
            modal.style.display = 'none';
        }
    }
});

// Initialize Bootstrap tooltips
document.addEventListener('DOMContentLoaded', function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(function (tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>

</html>
