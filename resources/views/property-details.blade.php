<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $ticket->name }} | Investment Details</title>
    <meta name="description" content="Invest in {{ $ticket->name }} for as little as ${{ number_format($ticket->price_per_share, 2) }} per share!">
    
    <!-- Cart Queue Stub - Initialize before any scripts use addToCart -->
    <script>
      if (!window._cartQueue) {
        window._cartQueue = [];
      }
      window.addToCart = function(itemData) {
        if (window.ShoppingCart && typeof window.ShoppingCart.addItem === 'function') {
          console.log('Adding item to cart:', itemData);
          return window.ShoppingCart.addItem(itemData);
        } else {
          console.log('Queueing item for cart:', itemData);
          window._cartQueue.push(itemData);
          return true;
        }
      };
    </script>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- jQuery - Required for cart.js -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <link rel="stylesheet" href="{{ asset('auction.css') }}">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Chart.js for graphs -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Custom Fonts CSS -->
    <link href="{{ route('fonts.css') }}" rel="stylesheet">
    
    <style>
        #authError{
            display: none !important;
        }
        /* Custom Fonts @font-face declarations */
        @if(isset($customFonts) && $customFonts->count() > 0)
        @foreach($customFonts as $font)
        @font-face {
            font-family: '{{ $font->font_family }}';
            src: url('{{ asset('storage/' . $font->file_path) }}') format('{{ $font->file_format == 'ttf' ? 'truetype' : ($font->file_format == 'otf' ? 'opentype' : $font->file_format) }}');
            font-weight: normal;
            font-style: normal;
            font-display: swap;
        }
        @endforeach
        @endif
        
        /* Menu Font Family Styling */
        @if(isset($header) && $header && $header->menu_font_family)
        nav.navbar .nav-link,
        nav.navbar .navbar-brand,
        nav.navbar .btn,
        .navbar .nav-item a,
        .navbar ul li a {
            font-family: '{{ $header->menu_font_family }}', sans-serif !important;
        }
        @endif
        
        /* Contact Topbar Font Family Styling */
        @if(isset($header) && $header && $header->contact_topbar_font_family)
        .contact-topbar,
        .contact-topbar a,
        .contact-topbar span,
        .contact-topbar .contact-item,
        .contact-topbar *:not(i):not(.fas):not(.fa):not(.far):not(.fab):not(.fal):not(.fad) {
            font-family: '{{ $header->contact_topbar_font_family }}', sans-serif !important;
        }
        @endif
        
        /* Investor Exclusives Font Family Styling */
        @if(isset($header) && $header && $header->investor_exclusives_font_family)
        .investor-exclusives-bar,
        .investor-exclusives-bar p,
        .investor-exclusives-bar a,
        .investor-exclusives-bar .investor-exclusives-text,
        .investor-exclusives-bar *:not(i):not(.fas):not(.fa):not(.far):not(.fab):not(.fal):not(.fad) {
            font-family: '{{ $header->investor_exclusives_font_family }}', sans-serif !important;
        }
        @endif
        
        /* Property Details Color Variables (from Website settings) */
        :root{
            --pd-bg: {{ json_encode($ticket->page_bg_color ?? $website->property_details_bg_color ?? '#ffffff') }};
            --pd-text: {{ json_encode($website->property_details_text_color ?? '#111827') }};
            --pd-muted: {{ json_encode($website->property_details_muted_color ?? '#6b7280') }};
            --pd-heading: {{ json_encode($website->property_details_heading_color ?? '#1e293b') }};
            --pd-price: {{ json_encode($website->property_details_price_color ?? '#111827') }};
            --pd-accent: {{ json_encode($website->property_details_accent_color ?? '#667eea') }};
        }

        .modal-backdrop.show {
            display: none !important;
        }

        p a {
            color: {{  $website->property_details_muted_color }} !important;
        }

        nav a {
            color: var(--pd-muted) !important;
            font-size: 17px !important;
            }

            .collapse{
                visibility: visible !important;
            }
        .property-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .stat-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        /* Responsive number sizing for stat cards */
        .responsive-number {
            font-size: 1.5rem;
            line-height: 1.2;
            /* Prevent large currency/number strings from wrapping to the next line */
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        /* Adjust font size based on content length */
        @media (min-width: 768px) {
            .responsive-number {
                font-size: clamp(1rem, 4vw, 1.5rem);
            }
        }
        
        @media (max-width: 767px) {
            .responsive-number {
                font-size: clamp(0.9rem, 3.5vw, 1.25rem);
            }
        }
        
        /* Container constraints for stat cards */
        .stat-card {
            min-height: 120px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .stat-card > div {
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .investment-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
        }
        
        .investment-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
        }
        
        .markdown-content h2 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-top: 2rem;
            margin-bottom: 1rem;
            color: #1e293b;
        }
        
        .markdown-content ul {
            list-style-type: disc;
            margin-left: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .markdown-content li {
            margin-bottom: 0.5rem;
        }
        
        .markdown-content a {
            color: #667eea;
            text-decoration: underline;
        }
        
        .markdown-content p {
            margin-bottom: 1rem;
            line-height: 1.7;
        }
        
        /* Contact Topbar Styles */
        .contact-topbar {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }
        
        .contact-item a {
            text-decoration: none;
        }
        
        .contact-item a:hover {
            opacity: 0.8;
        }
        
        /* Investor Exclusives Bar */
        .investor-exclusives-bar {
            width: 100%;
            text-align: center;
        }
        
        .investor-exclusives-content {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 8px 0;
        }
        
        .investor-exclusives-text {
            margin: 0;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .invest-button-section {
            flex-shrink: 0;
        }

        .invest-mobile{
            max-width: 1320px !important;
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
                margin-bottom: 100px !important;
            }
        }
        

        .sssssttttt{
            padding: 1.25rem 2.7rem !important;
            border-radius: 0px !important;
            font-family: sans-serif !important;
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

        /* Investor Exclusives Bar Styles - Dynamic Positioning */
    .investor-exclusives-bar {
        padding: 0px 0px;
        text-align: center;
        position: fixed;
        top: calc(var(--navbar-total-height, 6rem) - 0.23rem); /* Dynamic position minus gap adjustment */
        left: 0;
        right: 0;
        width: 100%;
        z-index: 999; /* Just below navbar but above content */
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }


        /* Custom Fonts @font-face declarations */
    @if(isset($customFonts) && $customFonts->count() > 0)
    /* DEBUG: {{ $customFonts->count() }} custom fonts loaded */
    @foreach($customFonts as $font)
    @font-face {
        font-family: '{{ $font->font_family }}';
        src: url('{{ asset('storage/' . $font->file_path) }}') format('{{ $font->file_format == 'ttf' ? 'truetype' : ($font->file_format == 'otf' ? 'opentype' : $font->file_format) }}');
        font-weight: normal;
        font-style: normal;
        font-display: swap;
    }
    
    /* Apply custom font classes (for Quill editor content) */
    .ql-font-{{ $font->font_family }} {
        font-family: '{{ $font->font_family }}', sans-serif !important;
    }
    @endforeach
    @else
    /* DEBUG: No custom fonts available */
    @endif
    
    /* System font classes (for Quill editor content) */
    .ql-font-arial {
        font-family: Arial, sans-serif !important;
    }
    .ql-font-helvetica {
        font-family: Helvetica, sans-serif !important;
    }
    .ql-font-times {
        font-family: 'Times New Roman', serif !important;
    }
    .ql-font-georgia {
        font-family: Georgia, serif !important;
    }
    .ql-font-verdana {
        font-family: Verdana, sans-serif !important;
    }
    .ql-font-courier {
        font-family: 'Courier New', monospace !important;
    }
    .ql-font-outfit {
        font-family: 'Outfit', sans-serif !important;
    }
    
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
    
    /* Footer Font Styling - Ensure Quill editor font classes work in footer */
    @if(isset($footer) && $footer)
    /* Override hardcoded footer fonts and ensure custom font classes work */
    .footer_content_wrap .ql-font-arial,
    .footer-section .ql-font-arial,
    .new-footer .ql-font-arial,
    footer .ql-font-arial {
        font-family: Arial, sans-serif !important;
    }
    
    .footer_content_wrap .ql-font-helvetica,
    .footer-section .ql-font-helvetica,
    .new-footer .ql-font-helvetica,
    footer .ql-font-helvetica {
        font-family: Helvetica, sans-serif !important;
    }
    
    .footer_content_wrap .ql-font-times,
    .footer-section .ql-font-times,
    .new-footer .ql-font-times,
    footer .ql-font-times {
        font-family: 'Times New Roman', serif !important;
    }
    
    .footer_content_wrap .ql-font-georgia,
    .footer-section .ql-font-georgia,
    .new-footer .ql-font-georgia,
    footer .ql-font-georgia {
        font-family: Georgia, serif !important;
    }
    
    .footer_content_wrap .ql-font-verdana,
    .footer-section .ql-font-verdana,
    .new-footer .ql-font-verdana,
    footer .ql-font-verdana {
        font-family: Verdana, sans-serif !important;
    }
    
    .footer_content_wrap .ql-font-courier,
    .footer-section .ql-font-courier,
    .new-footer .ql-font-courier,
    footer .ql-font-courier {
        font-family: 'Courier New', monospace !important;
    }
    
    .footer_content_wrap .ql-font-outfit,
    .footer-section .ql-font-outfit,
    .new-footer .ql-font-outfit,
    footer .ql-font-outfit {
        font-family: 'Outfit', sans-serif !important;
    }
    
    /* Custom font classes in footer */
    @if(isset($customFonts) && $customFonts->count() > 0)
    @foreach($customFonts as $font)
    .footer_content_wrap .ql-font-{{ $font->font_family }},
    .footer-section .ql-font-{{ $font->font_family }},
    .new-footer .ql-font-{{ $font->font_family }},
    footer .ql-font-{{ $font->font_family }} {
        font-family: '{{ $font->font_family }}', sans-serif !important;
    }
    @endforeach
    @endif
    @endif


        /* Contact Top Bar Styles */
    .contact-topbar {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        width: 100%;
        z-index: 1001; /* Above navbar */
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .contact-topbar .contact-info {
        gap: 0;
    }
    
    .contact-topbar .contact-item {
        font-size: 14px;
        font-weight: 400 !important;
    }
    
    .contact-topbar .contact-item a {
        transition: all 0.3s ease;
        font-family: Outfit,sans-serif;
        text-decoration: underline !important;
    }
    
    .contact-topbar .contact-item a:hover {
        opacity: 0.8;
        text-decoration: none !important;
    }
    
    .contact-topbar .contact-item i {
        font-size: 12px;
        opacity: 0.9;
        display: inline-block;
        width: auto;
        min-width: 14px;
        text-align: center;
        font-style: normal;
        font-variant: normal;
        text-rendering: auto;
        -webkit-font-smoothing: antialiased;
    }
    
    /* Ensure FontAwesome icons are visible */
    .contact-topbar i.fas,
    .contact-topbar i.fa {
        font-family: "Font Awesome 6 Free" !important;
        font-weight: 900 !important;
    }
    
    .contact-topbar .btn:hover {
        opacity: 0.9;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }
    
    /* Responsive design for contact top bar */
    @media (max-width: 768px) {
        .contact-topbar {
            padding: 8px 0 !important;
            font-size: 12px !important;
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
        
        .contact-topbar .btn {
            font-size: 11px;
            padding: 4px 12px !important;
            margin-top: 2px;
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
        
        .contact-topbar .btn {
            font-size: 10px;
            padding: 3px 10px !important;
            margin-top: 2px;
        }
    }
    
    /* Adjust navbar when contact top bar is present */
    .contact-topbar + nav.navbar {
        top: 2rem; /* Position navbar below contact bar */
    }
    
    @media (max-width: 768px) {
        .contact-topbar + nav.navbar {
            top: 1.7rem; /* Adjust for mobile */
        }

        .contact-topbar{
            height: 28px !important;
        }

        .close-on-mobile{
            display: none !important;
        }
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

    .marquee-content .item img{
        height: 64px !important;
        /* width: 64px !important; */
    }
    
    .investor-exclusives-text {
        font-size: 16px;
        font-weight: 600;
        margin: 0;
        letter-spacing: 0.5px;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }
    
    .investor-exclusives-link {
        background: rgba(255, 255, 255, 0.15);
        text-decoration: none;
        padding: 8px 20px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.3);
        backdrop-filter: blur(10px);
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .text-style-eyebrow{
        font-family: Outfit,sans-serif !important;
    }

    .jqo-io-processed{
        padding: 0.3rem !important;
        padding-top: 0.5rem !important;
    }

    .link_wrap div{
        font-family: Outfit,sans-serif !important;
    }

    .footer_content_wrap div h1 strong {
        font-family: Outfit,sans-serif !important;
    }

    .footer_content_wrap div p {
        font-family: Outfit,sans-serif !important;
    }
    
    .investor-exclusives-link:hover {
        background: rgba(255, 255, 255, 0.25);
        text-decoration: none;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        border-color: rgba(255, 255, 255, 0.5);
    }
    
    /* Icon styling */
    .investor-exclusives-link i {
        margin-left: 8px;
    }
    
    /* Responsive design */
    @media (max-width: 768px) {
        .investor-exclusives-bar {
            position: fixed;
            top: calc(var(--navbar-total-height-mobile, 9.5rem) - 0.23rem); /* Dynamic mobile position minus gap adjustment */
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
        
        .investor-exclusives-link {
            font-size: 13px;
            padding: 6px 16px;
        }

        .mobile-padding{
            padding: 0.2rem !important;
        }
    }
    
    @media (max-width: 480px) {
        /* Contact topbar mobile responsive */
        .contact-topbar {
            font-size: 12px !important;
            height: auto !important;
            padding: 4px 0 !important;
        }
        
        .contact-topbar .row {
            margin: 0 !important;
        }
        
        .contact-topbar .col-3, .contact-topbar .col-6 {
            padding: 2px 4px !important;
            font-size: 11px !important;
        }
        
        .contact-topbar .contact-item {
            margin: 0 !important;
            text-align: center !important;
        }
        
        .contact-topbar .contact-item i {
            margin-right: 4px !important;
        }
        
        .investor-exclusives-bar {
            padding: 10px 0;
            top: calc(var(--navbar-total-height-small, 1.7rem) - 0.23rem); /* Dynamic small mobile position minus gap adjustment */
            /* margin-top: 4rem; */
            padding-bottom: 0px;

        }
        
        .investor-exclusives-text {
            font-size: 13px;
            line-height: 1.4;
        }
        
        .investor-exclusives-link {
            font-size: 12px;
            padding: 5px 14px;
        }

        .navbar-brand{
            margin-left: 1rem !important;
            margin-top: 0.3rem !important;
            margin-bottom: 0.3rem !important;
        }

        .ticket-mask .row .col-md-10{
            text-align: center !important;
        }

        .ticket-mask .row .col-md-2 img{
            width: 100% !important;
        }

        .mobile-padding{
            padding: 0.2rem !important;
        }
    }

    /* Custom Range Slider Styles */
    .slider-purple::-webkit-slider-thumb {
        appearance: none;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #ffffff;
        border: 3px solid #667eea;
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    .slider-purple::-moz-range-thumb {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #ffffff;
        border: 3px solid #667eea;
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    .slider-purple::-webkit-slider-runnable-track {
        width: 100%;
        height: 8px;
        cursor: pointer;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 4px;
    }

    .slider-purple::-moz-range-track {
        width: 100%;
        height: 8px;
        cursor: pointer;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 4px;
    }
    
    /* Tab Navigation Responsive Styles */
    .tab-navigation {
        overflow-x: auto;
        overflow-y: hidden;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none; /* Firefox */
        -ms-overflow-style: none; /* IE and Edge */
    }
    
    .tab-navigation::-webkit-scrollbar {
        display: none; /* Chrome, Safari, Opera */
    }
    
    .tab-navigation nav {
        display: flex;
        flex-wrap: nowrap;
        min-width: min-content;
    }
    
    .tab-btn {
        white-space: nowrap;
        flex-shrink: 0;
    }
    
    /* Mobile specific tab styles */
    @media (max-width: 768px) {
        .tab-btn {
            padding: 12px 16px !important;
            font-size: 13px !important;
        }
        
        .tab-btn i {
            font-size: 12px;
        }
    }
    
    @media (max-width: 480px) {
        .tab-btn {
            padding: 10px 12px !important;
            font-size: 12px !important;
        }
        
        .tab-btn i {
            display: none; /* Hide icons on very small screens to save space */
        }
    }
    
        /* Global overrides using variables */
        body.property-details-page{background-color: var(--pd-bg) !important; color: var(--pd-text) !important;}
        h1,h2,h3,h4,h5,h6,.title,.markdown-content h2,.investor-exclusives-text{color: var(--pd-heading) !important;}
        .muted,.subtitle,.condition,.small,.text-muted,.card .meta,.seller-meta,.rating-row span,.markdown-content p{color: var(--pd-muted) !important;}
        .price,.price-value{color: var(--pd-price) !important;}
        a,.markdown-content a,.btn.ghost{color: {{ $website->property_details_muted_color }} !important;}
    </style>
    <!-- Shopping Cart System - Load early before components use addToCart -->
    <script src="{{ asset('js/cart.js') }}"></script>
</head>
<body class="property-details-page" style="background-color: {{ $ticket->page_bg_color ?? '#ffffff' }} !important;">
    <div style="max-width:1180px;margin:12px auto;padding:0 18px;">
        @include('partials.back-button')
    </div>
    
    @php
        $url = url()->current();
        $domain = parse_url($url, PHP_URL_HOST);
        $check = \App\Models\Website::where('domain', $domain)->first();
        $groups = \App\Models\User::where('website_id', $check->id)->where('role','group_leader')->get();
        $auction = \App\Models\Auction::where('website_id', $check->id)->where('status',1)->latest()->get();
        
        // Use user_id to fetch header, footer, setting to match controller
        $user_id = $check->user_id;
        $header = \App\Models\Header::where('user_id', $user_id)->first();
        $footer = \App\Models\Footer::where('user_id', $user_id)->first();
        $setting = \App\Models\Setting::where('user_id', $user_id)->first();
        $menuSections = [];
        // dd($header->show_contact_topbar);
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
                    {{-- <a href="{{ $header->investor_exclusives_url ?? '#' }}" class="investor-exclusives-link" style="color: {{ $header->topbar_text_color ?? '#ffffff' }};">
                        Learn More
                        <i class="fas fa-arrow-right ms-2"></i>
                    </a> --}}
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
                        
                        console.log('Dynamic Heights Updated:', {
                            navbar: navbarHeight,
                            contactTopbar: contactTopbarHeight,
                            investorBar: investorBarHeight,
                            totalNavHeight: totalNavHeight,
                            totalWithInvestor: totalWithInvestorBar,
                            mainMargin: mainContentMargin
                        });
                    }
                }
                
                // Run on load
                document.addEventListener('DOMContentLoaded', function() {
                    // Wait a bit for all elements to render
                    setTimeout(updateNavbarHeights, 50);
                });
                
                // Run on resize
                window.addEventListener('resize', updateNavbarHeights);
                
                // Run after fonts load (as this can affect navbar height)
                if (document.fonts) {
                    document.fonts.ready.then(updateNavbarHeights);
                }
                
                // Fallback: run after delays to catch any dynamic changes
                setTimeout(updateNavbarHeights, 100);
                setTimeout(updateNavbarHeights, 300);
                setTimeout(updateNavbarHeights, 500);
                setTimeout(updateNavbarHeights, 1000);
            </script>
        @endif
    @endif
    
    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" style="padding-left: 0.5rem !important; padding-right: 0.5rem !important; margin-top: var(--main-content-margin-top, {{ 
        ($header && $header->show_contact_topbar && $check && $check->isInvestment() && $header->show_investor_exclusives) ? '14.2rem' : 
        (($header && $header->show_contact_topbar) ? '10.5rem' : 
        (($check && $check->isInvestment() && $header && $header->show_investor_exclusives) ? '10.6rem' : '6.9rem'))
    }});" 
          class="{{ 
            ($header && $header->show_contact_topbar && $check && $check->isInvestment() && $header && $header->show_investor_exclusives) ? 'with-contact-and-investor-bars' : 
            (($header && $header->show_contact_topbar) ? 'with-contact-bar' : 
            (($check && $check->isInvestment() && $header && $header->show_investor_exclusives) ? 'with-investor-bar' : ''))
        }}">
        
        <!-- Breadcrumb -->
        <nav class="flex mb-6 text-sm" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" style="color:{{  $website->property_details_text_color }} !important" class="text-gray-600 hover:text-gray-900">
                        <i class="fas fa-home mr-2"></i>Home
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i style="color:{{  $website->property_details_text_color }} !important" class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span style="color:{{  $website->property_details_muted_color }} !important" class="text-gray-900 font-medium">{{ $ticket->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>
        
        <!-- Back Button -->
        <div class="mb-4">
            <button onclick="window.history.back()" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-900 transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Previous Page
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Column - Property Images & Details -->
            <div class="lg:col-span-2">
                
                <!-- Property Status Badge -->
                <div class="mb-4">
                    <span class="property-badge inline-block px-4 py-2 rounded-full text-white text-sm font-semibold">
                        <i class="fas fa-check-circle mr-2"></i>Active Investment
                    </span>
                </div>

                <!-- Property Title -->
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4" style="color:{{  $website->property_details_text_color }} !important">{{ $ticket->name }}</h1>
                
                <!-- Property Location -->
                <div class="flex items-center text-gray-600 mb-6">
                    <i style="color:{{  $website->property_details_text_color }} !important" class="fas fa-map-marker-alt mr-2"></i>
                    <span style="color:{{  $website->property_details_text_color }} !important">{{ $ticket->website->name }}</span>
                </div>

                <!-- Image Gallery -->
                <div class="mb-8">
                    <div class="relative rounded-lg overflow-hidden shadow-lg">
                        <img src="{{ asset($ticket->image) }}" alt="{{ $ticket->name }}" 
                             class="w-full h-96 object-contain bg-gray-100" id="mainImage">
                    </div>
                    
                    <!-- Thumbnail Gallery -->
                    @if($ticket->images && count($ticket->images) > 0)
                    <div class="grid grid-cols-4 md:grid-cols-6 gap-2 mt-4" id="thumbnailGallery">
                        <div class="thumbnail-item cursor-pointer rounded-lg overflow-hidden border-2 border-purple-500 bg-gray-100" onclick="changeImage('{{ asset($ticket->image) }}', this)">
                            <img src="{{ asset($ticket->image) }}" alt="Main" class="w-full h-20 object-contain">
                        </div>
                        @foreach($ticket->images as $key => $image)
                        @if ($key != 0)
                            <div class="thumbnail-item cursor-pointer rounded-lg overflow-hidden border-2 border-transparent hover:border-purple-500 transition bg-gray-100" 
                                onclick="changeImage('{{ asset($image->image_path) }}', this)">
                                <img src="{{ asset($image->image_path) }}" alt="Property image" class="w-full h-20 object-contain">
                            </div>
                        @endif
                        @endforeach
                    </div>
                    @endif
                </div>

                <!-- Key Metrics Cards -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    <div class="stat-card bg-white p-4 rounded-lg shadow-md">
                        <div class="text-gray-600 text-sm mb-1">Starting Price</div>
                        <div class="text-2xl font-bold text-purple-600 break-words responsive-number js-metric-number">${{ number_format($ticket->price_per_share, 2) }}</div>
                        <div class="text-xs text-gray-500 mt-1">per share</div>
                    </div>
                    
                    <div class="stat-card bg-white p-4 rounded-lg shadow-md">
                        <div class="text-gray-600 text-sm mb-1">Total Shares</div>
                        <div class="text-2xl font-bold text-gray-900 break-words responsive-number js-metric-number">{{ number_format($ticket->total_shares) }}</div>
                        <div class="text-xs text-gray-500 mt-1">shares total</div>
                    </div>
                    
                    <div class="stat-card bg-white p-4 rounded-lg shadow-md">
                        <div class="text-gray-600 text-sm mb-1">Available</div>
                        <div class="text-2xl font-bold text-green-600 break-words responsive-number js-metric-number">{{ number_format($ticket->available_shares) }}</div>
                        <div class="text-xs text-gray-500 mt-1">shares left</div>
                    </div>
                    
                    <div class="stat-card bg-white p-4 rounded-lg shadow-md">
                        <div class="text-gray-600 text-sm mb-1">Total Value</div>
                        <div class="text-2xl font-bold text-gray-900 break-words responsive-number js-metric-number js-metric-leader">${{ number_format($ticket->price) }}</div>
                        <div class="text-xs text-gray-500 mt-1">of investment</div>
                    </div>
                </div>

                <!-- Property Features -->
                @php
                    $validFeatures = $ticket->features->filter(function($feature) {
                        return !empty(trim($feature->name)) && !empty(trim($feature->value));
                    });
                @endphp
                @if($validFeatures->count() > 0)
                <div class="bg-white p-6 rounded-lg shadow-md mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ $ticket->features_heading ?? 'Investment Features' }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($validFeatures as $feature)
                        <div class="flex justify-between items-center py-3 border-b border-gray-100">
                            <span class="text-gray-600 font-medium">{{ $feature->name }}</span>
                            <span class="text-gray-900 font-semibold">{{ $feature->value }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Ownership Progress Bar -->
                <div class="bg-white p-6 rounded-lg shadow-md mb-8">
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="text-lg font-semibold text-gray-900">Ownership Progress</h3>
                        <span class="text-sm font-medium text-purple-600">
                            {{ number_format((($ticket->total_shares - $ticket->available_shares) / $ticket->total_shares) * 100, 1) }}% Sold
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-4">
                        <div class="bg-gradient-to-r from-purple-500 to-purple-700 h-4 rounded-full transition-all duration-500" 
                             style="width: {{ (($ticket->total_shares - $ticket->available_shares) / $ticket->total_shares) * 100 }}%">
                        </div>
                    </div>
                    <div class="flex justify-between mt-2 text-sm text-gray-600">
                        <span>{{ number_format($ticket->total_shares - $ticket->available_shares) }} shares sold</span>
                        <span>{{ number_format($ticket->available_shares) }} shares remaining</span>
                    </div>
                </div>

                <!-- Tabs Section -->
                <div class="bg-white rounded-lg shadow-md mb-8">
                    <div class="border-b border-gray-200 tab-navigation">
                        <nav class="flex -mb-px">
                            <button class="tab-btn active px-6 py-4 text-sm font-medium border-b-2" data-tab="overview">
                                <i class="fas fa-info-circle mr-2"></i>Overview
                            </button>
                            <button class="tab-btn px-6 py-4 text-sm font-medium border-b-2" data-tab="financials">
                                <i class="fas fa-chart-line mr-2"></i>Financials
                            </button>
                            @if($ticket->type === 'property' && !empty($ticket->market))
                            <button class="tab-btn px-6 py-4 text-sm font-medium border-b-2" data-tab="market">
                                <i class="fas fa-chart-area mr-2"></i>Market
                            </button>
                            @endif
                            <button class="tab-btn px-6 py-4 text-sm font-medium border-b-2" data-tab="documents">
                                <i class="fas fa-file-alt mr-2"></i>Documents
                            </button>
                        </nav>
                    </div>

                    <div class="mobile-padding p-6">
                        <!-- Overview Tab -->
                        <div class="tab-content active" id="overview">
                            <h2 class="text-xl font-bold text-gray-900 mb-4">About This Investment</h2>
                            <div class="markdown-content text-gray-700">
                                {!! $ticket->description !!}
                            </div>
                        </div>

                        <!-- Financials Tab -->
                        <div class="tab-content hidden" id="financials">
                            <h2 class="text-xl font-bold text-gray-900 mb-4">Financial Details</h2>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="text-gray-600 text-sm mb-2">{{ $ticket->price_per_share_label ?? 'Price Per Share' }}</div>
                                    <div class="text-3xl font-bold text-purple-600">${{ number_format($ticket->price_per_share, 2) }}</div>
                                </div>
                                
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="text-gray-600 text-sm mb-2">Total Investment Value</div>
                                    <div class="text-3xl font-bold text-gray-900">${{ number_format($ticket->price) }}</div>
                                </div>
                            </div>

                            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                                <div class="flex items-start">
                                    <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                                    <div>
                                        <h4 class="font-semibold text-blue-900 mb-1">Investment Calculation</h4>
                                        <p class="text-sm text-blue-800">
                                            @php
                                                $remainingShares = $ticket->available_shares;
                                                $soldShares = $ticket->total_shares - $remainingShares;
                                                $remainingCost = $remainingShares * $ticket->price_per_share;
                                                $fullCost = $ticket->price; // already stored total value
                                            @endphp
                                            @if($remainingShares > 0)
                                                There are <strong>{{ number_format($remainingShares) }}</strong> shares remaining ({{ number_format($soldShares) }} sold). Purchasing all remaining shares would cost <strong>${{ number_format($remainingCost) }}</strong>.
                                            @else
                                                <strong>All shares have been sold.</strong> Total investment value was <strong>${{ number_format($fullCost) }}</strong>.
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            @if($ticket->financials)
                            @php
                                $fin = $ticket->financials;
                            @endphp

                            <!-- Total Investment Value Section -->
                            @if($fin->show_total_investment && $fin->total_investment_value)
                            <div class="rounded-xl shadow-xl mt-6 border border-purple-700" style="background: linear-gradient(135deg, #764ba2 0%, #667eea 100%)">
                                <div class="flex justify-between p-6 text-white border-b border-purple-700 last:border-b-0">
                                    <span class="font-bold text-xl md:text-2xl">{{ $fin->total_investment_label }}</span>
                                    <span class="text-purple-300 text-xl md:text-2xl">{{ $fin->total_investment_value }}</span>
                                </div>
                                @if(is_array($fin->custom_total_investment_items))
                                    @foreach($fin->custom_total_investment_items as $ci)
                                        @php $label = $ci['label'] ?? null; $val = $ci['value'] ?? null; $tip = $ci['tooltip'] ?? null; @endphp
                                        @if($label && $val !== null)
                                        <div class="flex justify-between p-6 text-white border-b border-purple-700 last:border-b-0">
                                            <div class="flex items-center">
                                                <span class="font-bold md:text-lg">{{ $label }}</span>
                                                @if($tip)
                                                <div class="relative group ml-2">
                                                    <i class="fas fa-info-circle text-sm inline-flex items-center justify-center rounded-full w-5 h-5 text-purple-300 bg-purple-800 cursor-pointer"></i>
                                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block w-64 p-3 bg-purple-800 text-white text-sm rounded-lg border border-purple-600 z-10">{{ $tip }}</div>
                                                </div>
                                                @endif
                                            </div>
                                            <span class="md:text-lg">{{ $val }}</span>
                                        </div>
                                        @endif
                                    @endforeach
                                @endif
                                
                                @if($fin->show_underlying_asset && $fin->underlying_asset_price)
                                <div class="flex justify-between p-6 text-white border-b border-purple-700 last:border-b-0">
                                    <div class="flex items-center">
                                        <span class="font-bold md:text-lg">{{ $fin->underlying_asset_label }}</span>
                                        @if($fin->show_underlying_asset_tooltip && $fin->underlying_asset_tooltip)
                                        <div class="relative group ml-2">
                                            <i class="fas fa-info-circle text-sm inline-flex items-center justify-center rounded-full w-5 h-5 text-purple-300 bg-purple-800 cursor-pointer"></i>
                                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block w-64 p-3 bg-purple-800 text-white text-sm rounded-lg border border-purple-600 z-10">
                                                {{ $fin->underlying_asset_tooltip }}
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <span class="md:text-lg">{{ $fin->underlying_asset_price }}</span>
                                </div>
                                @endif

                                @if($fin->show_closing_costs && $fin->closing_costs !== null)
                                <div class="flex justify-between p-6 text-white border-b border-purple-700 last:border-b-0">
                                    <div class="flex items-center">
                                        <span class="font-bold md:text-lg">{{ $fin->closing_costs_label }}</span>
                                        @if($fin->show_closing_costs_tooltip && $fin->closing_costs_tooltip)
                                        <div class="relative group ml-2">
                                            <i class="fas fa-info-circle text-sm inline-flex items-center justify-center rounded-full w-5 h-5 text-purple-300 bg-purple-800 cursor-pointer"></i>
                                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block w-64 p-3 bg-purple-800 text-white text-sm rounded-lg border border-purple-600 z-10">
                                                {{ $fin->closing_costs_tooltip }}
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <span class="md:text-lg">{{ $fin->closing_costs }}</span>
                                </div>
                                @endif

                                @if($fin->show_upfront_fees && $fin->upfront_fees !== null)
                                <div class="flex justify-between p-6 text-white border-b border-purple-700 last:border-b-0">
                                    <div class="flex items-center">
                                        <span class="font-bold md:text-lg">{{ $fin->upfront_fees_label }}</span>
                                        @if($fin->show_upfront_fees_tooltip && $fin->upfront_fees_tooltip)
                                        <div class="relative group ml-2">
                                            <i class="fas fa-info-circle text-sm inline-flex items-center justify-center rounded-full w-5 h-5 text-purple-300 bg-purple-800 cursor-pointer"></i>
                                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block w-64 p-3 bg-purple-800 text-white text-sm rounded-lg border border-purple-600 z-10">
                                                {{ $fin->upfront_fees_tooltip }}
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <span class="md:text-lg">{{ $fin->upfront_fees }}</span>
                                </div>
                                @endif

                                @if($fin->show_operating_reserve && $fin->operating_reserve_value)
                                <div class="flex justify-between p-6 text-white border-b border-purple-700 last:border-b-0">
                                    <div class="flex items-center">
                                        <span class="font-bold md:text-lg">{{ $fin->operating_reserve_label }}</span>
                                        @if($fin->show_operating_reserve_tooltip && $fin->operating_reserve_tooltip)
                                        <div class="relative group ml-2">
                                            <i class="fas fa-info-circle text-sm inline-flex items-center justify-center rounded-full w-5 h-5 text-purple-300 bg-purple-800 cursor-pointer"></i>
                                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block w-64 p-3 bg-purple-800 text-white text-sm rounded-lg border border-purple-600 z-10">
                                                {{ $fin->operating_reserve_tooltip }}
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <span class="md:text-lg">{{ $fin->operating_reserve_value }}</span>
                                </div>
                                @endif
                            </div>
                            @endif

                            <!-- Projected Annual Return Section -->
                            @if($fin->show_projected_annual_return && $fin->projected_annual_return !== null)
                            <div class="rounded-xl shadow-xl mt-6" style="background: linear-gradient(135deg, #764ba2 0%, #667eea 100%)">
                                <div class="flex justify-between p-6 text-white border-b border-purple-700 last:border-b-0">
                                    <span class="font-bold text-xl md:text-2xl">{{ $fin->projected_annual_return_label }}</span>
                                    <span class="text-purple-300 text-xl md:text-2xl">{{ $fin->projected_annual_return }}</span>
                                </div>
                                @if(is_array($fin->custom_projected_annual_return_items))
                                    @foreach($fin->custom_projected_annual_return_items as $ci)
                                        @php $label = $ci['label'] ?? null; $val = $ci['value'] ?? null; $tip = $ci['tooltip'] ?? null; @endphp
                                        @if($label && $val !== null)
                                        <div class="flex justify-between p-6 text-white border-b border-purple-700 last:border-b-0">
                                            <div class="flex items-center">
                                                <span class="font-bold md:text-lg">{{ $label }}</span>
                                                @if($tip)
                                                <div class="relative group ml-2">
                                                    <i class="fas fa-info-circle text-sm inline-flex items-center justify-center rounded-full w-5 h-5 text-purple-300 bg-purple-800 cursor-pointer"></i>
                                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block w-64 p-3 bg-purple-800 text-white text-sm rounded-lg border border-purple-600 z-10">{{ $tip }}</div>
                                                </div>
                                                @endif
                                            </div>
                                            <span class="md:text-lg">{{ $val }}</span>
                                        </div>
                                        @endif
                                    @endforeach
                                @endif

                                @if($fin->show_projected_rental_yield && $fin->projected_rental_yield !== null)
                                <div class="flex justify-between p-6 text-white border-b border-purple-700 last:border-b-0">
                                    <div class="flex items-center">
                                        <span class="font-bold md:text-lg">{{ $fin->projected_rental_yield_label }}</span>
                                        @if($fin->show_projected_rental_yield_tooltip && $fin->projected_rental_yield_tooltip)
                                        <div class="relative group ml-2">
                                            <i class="fas fa-info-circle text-sm inline-flex items-center justify-center rounded-full w-5 h-5 text-purple-300 bg-purple-800 cursor-pointer"></i>
                                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block w-64 p-3 bg-purple-800 text-white text-sm rounded-lg border border-purple-600 z-10">
                                                {{ $fin->projected_rental_yield_tooltip }}
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <span class="md:text-lg">{{ $fin->projected_rental_yield }}</span>
                                </div>
                                @endif

                                @if($fin->show_projected_appreciation && $fin->projected_appreciation !== null)
                                <div class="flex justify-between p-6 text-white border-b border-purple-700 last:border-b-0">
                                    <div class="flex items-center">
                                        <span class="font-bold md:text-lg">{{ $fin->projected_appreciation_label }}</span>
                                        @if($fin->show_projected_appreciation_tooltip && $fin->projected_appreciation_tooltip)
                                        <div class="relative group ml-2">
                                            <i class="fas fa-info-circle text-sm inline-flex items-center justify-center rounded-full w-5 h-5 text-purple-300 bg-purple-800 cursor-pointer"></i>
                                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block w-64 p-3 bg-purple-800 text-white text-sm rounded-lg border border-purple-600 z-10">
                                                {{ $fin->projected_appreciation_tooltip }}
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <span class="md:text-lg">{{ $fin->projected_appreciation }}</span>
                                </div>
                                @endif

                                @if($fin->show_rental_yield && $fin->rental_yield !== null)
                                <div class="flex justify-between p-6 text-white border-b border-purple-700 last:border-b-0">
                                    <div class="flex items-center">
                                        <span class="font-bold md:text-lg">{{ $fin->rental_yield_label }}</span>
                                        @if($fin->show_rental_yield_tooltip && $fin->rental_yield_tooltip)
                                        <div class="relative group ml-2">
                                            <i class="fas fa-info-circle text-sm inline-flex items-center justify-center rounded-full w-5 h-5 text-purple-300 bg-purple-800 cursor-pointer"></i>
                                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block w-64 p-3 bg-purple-800 text-white text-sm rounded-lg border border-purple-600 z-10">
                                                {{ $fin->rental_yield_tooltip }}
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <span class="md:text-lg">{{ $fin->rental_yield }}</span>
                                </div>
                                @endif
                            </div>
                            @endif

                            <!-- Annual Details Section -->
                            @if($fin->show_annual_gross_rents && $fin->annual_gross_rents !== null)
                            <div class="rounded-xl shadow-xl mt-6" style="background: linear-gradient(135deg, #764ba2 0%, #667eea 100%)">
                                <div class="flex justify-between p-6 text-white border-b border-purple-700 last:border-b-0">
                                    <span class="font-bold text-xl md:text-2xl">{{ $fin->annual_gross_rents_label }}</span>
                                    <span class="text-purple-300 text-xl md:text-2xl">{{ $fin->annual_gross_rents }}</span>
                                </div>
                                @if(is_array($fin->custom_annual_gross_rents_items))
                                    @foreach($fin->custom_annual_gross_rents_items as $ci)
                                        @php $label = $ci['label'] ?? null; $val = $ci['value'] ?? null; $tip = $ci['tooltip'] ?? null; @endphp
                                        @if($label && $val !== null)
                                        <div class="flex justify-between p-6 text-white border-b border-purple-700 last:border-b-0">
                                            <div class="flex items-center">
                                                <span class="font-bold md:text-lg">{{ $label }}</span>
                                                @if($tip)
                                                <div class="relative group ml-2">
                                                    <i class="fas fa-info-circle text-sm inline-flex items-center justify-center rounded-full w-5 h-5 text-purple-300 bg-purple-800 cursor-pointer"></i>
                                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block w-64 p-3 bg-purple-800 text-white text-sm rounded-lg border border-purple-600 z-10">{{ $tip }}</div>
                                                </div>
                                                @endif
                                            </div>
                                            <span class="md:text-lg">{{ $val }}</span>
                                        </div>
                                        @endif
                                    @endforeach
                                @endif

                                @if($fin->show_property_taxes && $fin->property_taxes !== null)
                                <div class="flex justify-between p-6 text-white border-b border-purple-700 last:border-b-0">
                                    <div class="flex items-center">
                                        <span class="font-bold md:text-lg">{{ $fin->property_taxes_label }}</span>
                                        @if($fin->show_property_taxes_tooltip && $fin->property_taxes_tooltip)
                                        <div class="relative group ml-2">
                                            <i class="fas fa-info-circle text-sm inline-flex items-center justify-center rounded-full w-5 h-5 text-purple-300 bg-purple-800 cursor-pointer"></i>
                                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block w-64 p-3 bg-purple-800 text-white text-sm rounded-lg border border-purple-600 z-10">
                                                {{ $fin->property_taxes_tooltip }}
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <span class="md:text-lg">{{ $fin->property_taxes }}</span>
                                </div>
                                @endif

                                @if($fin->show_homeowners_insurance && $fin->homeowners_insurance !== null)
                                <div class="flex justify-between p-6 text-white border-b border-purple-700 last:border-b-0">
                                    <div class="flex items-center">
                                        <span class="font-bold md:text-lg">{{ $fin->homeowners_insurance_label }}</span>
                                        @if($fin->show_homeowners_insurance_tooltip && $fin->homeowners_insurance_tooltip)
                                        <div class="relative group ml-2">
                                            <i class="fas fa-info-circle text-sm inline-flex items-center justify-center rounded-full w-5 h-5 text-purple-300 bg-purple-800 cursor-pointer"></i>
                                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block w-64 p-3 bg-purple-800 text-white text-sm rounded-lg border border-purple-600 z-10">
                                                {{ $fin->homeowners_insurance_tooltip }}
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <span class="md:text-lg">{{ $fin->homeowners_insurance }}</span>
                                </div>
                                @endif

                                @if($fin->show_property_management && $fin->property_management !== null)
                                <div class="flex justify-between p-6 text-white border-b border-purple-700 last:border-b-0">
                                    <div class="flex items-center">
                                        <span class="font-bold md:text-lg">{{ $fin->property_management_label }}</span>
                                        @if($fin->show_property_management_tooltip && $fin->property_management_tooltip)
                                        <div class="relative group ml-2">
                                            <i class="fas fa-info-circle text-sm inline-flex items-center justify-center rounded-full w-5 h-5 text-purple-300 bg-purple-800 cursor-pointer"></i>
                                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block w-64 p-3 bg-purple-800 text-white text-sm rounded-lg border border-purple-600 z-10">
                                                {{ $fin->property_management_tooltip }}
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <span class="md:text-lg">{{ $fin->property_management }}</span>
                                </div>
                                @endif

                                @if($fin->show_annual_llc_fees && $fin->annual_llc_fees !== null)
                                <div class="flex justify-between p-6 text-white border-b border-purple-700 last:border-b-0">
                                    <div class="flex items-center">
                                        <span class="font-bold md:text-lg">{{ $fin->annual_llc_fees_label }}</span>
                                        @if($fin->show_annual_llc_fees_tooltip && $fin->annual_llc_fees_tooltip)
                                        <div class="relative group ml-2">
                                            <i class="fas fa-info-circle text-sm inline-flex items-center justify-center rounded-full w-5 h-5 text-purple-300 bg-purple-800 cursor-pointer"></i>
                                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block w-64 p-3 bg-purple-800 text-white text-sm rounded-lg border border-purple-600 z-10">
                                                {{ $fin->annual_llc_fees_tooltip }}
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <span class="md:text-lg">{{ $fin->annual_llc_fees }}</span>
                                </div>
                                @endif

                                @if($fin->show_annual_cash_flow && $fin->annual_cash_flow !== null)
                                <div class="flex justify-between p-6 text-white border-b border-purple-700 last:border-b-0">
                                    <div class="flex items-center">
                                        <span class="font-bold md:text-lg">{{ $fin->annual_cash_flow_label }}</span>
                                        @if($fin->show_annual_cash_flow_tooltip && $fin->annual_cash_flow_tooltip)
                                        <div class="relative group ml-2">
                                            <i class="fas fa-info-circle text-sm inline-flex items-center justify-center rounded-full w-5 h-5 text-purple-300 bg-purple-800 cursor-pointer"></i>
                                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block w-64 p-3 bg-purple-800 text-white text-sm rounded-lg border border-purple-600 z-10">
                                                {{ $fin->annual_cash_flow_tooltip }}
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <span class="md:text-lg">{{ $fin->annual_cash_flow }}</span>
                                </div>
                                @endif

                                @if($fin->show_cap_rate && $fin->cap_rate !== null)
                                <div class="flex justify-between p-6 text-white border-b border-purple-700 last:border-b-0">
                                    <div class="flex items-center">
                                        <span class="font-bold md:text-lg">{{ $fin->cap_rate_label }}</span>
                                        @if($fin->show_cap_rate_tooltip && $fin->cap_rate_tooltip)
                                        <div class="relative group ml-2">
                                            <i class="fas fa-info-circle text-sm inline-flex items-center justify-center rounded-full w-5 h-5 text-purple-300 bg-purple-800 cursor-pointer"></i>
                                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block w-64 p-3 bg-purple-800 text-white text-sm rounded-lg border border-purple-600 z-10">
                                                {{ $fin->cap_rate_tooltip }}
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <span class="md:text-lg">{{ $fin->cap_rate }}</span>
                                </div>
                                @endif

                                @if($fin->show_monthly_cash_flow && $fin->monthly_cash_flow !== null)
                                <div class="flex justify-between p-6 text-white border-b border-purple-700 last:border-b-0">
                                    <div class="flex items-center">
                                        <span class="font-bold md:text-lg">{{ $fin->monthly_cash_flow_label }}</span>
                                        @if($fin->show_monthly_cash_flow_tooltip && $fin->monthly_cash_flow_tooltip)
                                        <div class="relative group ml-2">
                                            <i class="fas fa-info-circle text-sm inline-flex items-center justify-center rounded-full w-5 h-5 text-purple-300 bg-purple-800 cursor-pointer"></i>
                                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block w-64 p-3 bg-purple-800 text-white text-sm rounded-lg border border-purple-600 z-10">
                                                {{ $fin->monthly_cash_flow_tooltip }}
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <span class="md:text-lg">{{ $fin->monthly_cash_flow }}</span>
                                </div>
                                @endif

                                @if($fin->show_projected_annual_cash_flow && $fin->projected_annual_cash_flow !== null)
                                <div class="flex justify-between p-6 text-white border-b border-purple-700 last:border-b-0">
                                    <div class="flex items-center">
                                        <span class="font-bold md:text-lg">{{ $fin->projected_annual_cash_flow_label }}</span>
                                        @if($fin->show_projected_annual_cash_flow_tooltip && $fin->projected_annual_cash_flow_tooltip)
                                        <div class="relative group ml-2">
                                            <i class="fas fa-info-circle text-sm inline-flex items-center justify-center rounded-full w-5 h-5 text-purple-300 bg-purple-800 cursor-pointer"></i>
                                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block w-64 p-3 bg-purple-800 text-white text-sm rounded-lg border border-purple-600 z-10">
                                                {{ $fin->projected_annual_cash_flow_tooltip }}
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <span class="md:text-lg">{{ $fin->projected_annual_cash_flow }}</span>
                                </div>
                                @endif

                                @if($fin->show_current_loan && $fin->current_loan !== null)
                                <div class="flex justify-between p-6 text-white border-b border-purple-700 last:border-b-0">
                                    <div class="flex items-center">
                                        <span class="font-bold md:text-lg">{{ $fin->current_loan_label }}</span>
                                        @if($fin->show_current_loan_tooltip && $fin->current_loan_tooltip)
                                        <div class="relative group ml-2">
                                            <i class="fas fa-info-circle text-sm inline-flex items-center justify-center rounded-full w-5 h-5 text-purple-300 bg-purple-800 cursor-pointer"></i>
                                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block w-64 p-3 bg-purple-800 text-white text-sm rounded-lg border border-purple-600 z-10">
                                                {{ $fin->current_loan_tooltip }}
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <span class="md:text-lg">{{ $fin->current_loan }}</span>
                                </div>
                                @endif
                            </div>
                            @endif

                            @endif

                            <!-- Advanced Investment Calculator -->
                            <div class="mt-10">
                                <h3 class="text-xl font-bold text-gray-900 mb-6">Projected Returns Per Share</h3>
                                
                                <div class="rounded-xl border border-purple-300 p-6 mb-10 mobile-padding" style="background: linear-gradient(135deg, #764ba2 0%, #667eea 100%)">
                                    <!-- Shares Purchased Slider -->
                                    <div class="mb-8">
                                        <div class="flex justify-between items-center text-white mb-2">
                                            <p class="text-base font-medium">Shares Purchased</p>
                                            <p class="font-semibold"><span id="sharesValue">1</span> / $<span id="sharesCost">{{ number_format($ticket->price_per_share, 2) }}</span></p>
                                        </div>
                                        <div class="relative pt-1">
                                            <input type="range" id="sharesSlider" min="1" max="{{ $ticket->available_shares }}" value="1" 
                                                   class="w-full h-2 bg-purple-800 rounded-lg appearance-none cursor-pointer slider-purple"
                                                   oninput="updateCalculations()">
                                        </div>
                                    </div>

                                    <!-- Appreciation Rate Slider -->
                                    <div class="mb-8">
                                        <div class="flex justify-between items-center text-white mb-2">
                                            <p class="text-base font-medium">Annual Appreciation Rate</p>
                                            <p class="font-semibold"><span id="appreciationValue">{{ $ticket->financials && $ticket->financials->projected_appreciation ? $ticket->financials->projected_appreciation : '3.0' }}</span></p>
                                        </div>
                                        <div class="relative pt-1">
                                            <input type="range" id="appreciationSlider" min="0" max="100" step="1" value="{{ $ticket->financials && $ticket->financials->projected_appreciation ? $ticket->financials->projected_appreciation : 3 }}" 
                                                   class="w-full h-2 bg-purple-800 rounded-lg appearance-none cursor-pointer slider-purple"
                                                   oninput="updateCalculations()">
                                        </div>
                                    </div>

                                    <!-- Cash on Cash Return Slider -->
                                    <div class="mb-8">
                                        <div class="flex justify-between items-center text-white mb-2">
                                            <p class="text-base font-medium">Cash on Cash Return</p>
                                            <p class="font-semibold"><span id="cashReturnValue">{{ $ticket->financials && $ticket->financials->rental_yield ? $ticket->financials->rental_yield : '8.00' }}</span></p>
                                        </div>
                                        <div class="relative pt-1">
                                            <input type="range" id="cashReturnSlider" min="0" max="100" step="1" value="{{ $ticket->financials && $ticket->financials->rental_yield ? $ticket->financials->rental_yield : 8 }}" 
                                                   class="w-full h-2 bg-purple-800 rounded-lg appearance-none cursor-pointer slider-purple"
                                                   oninput="updateCalculations()">
                                        </div>
                                    </div>

                                    <!-- Chart Title -->
                                    <h3 class="text-sm md:text-base leading-6 mb-3 border-b border-purple-300 pb-2 font-bold text-white">
                                        Est. Investment Value Over Time Based on Above Assumptions
                                    </h3>

                                    <!-- Chart Container -->
                                    <div class="bg-white rounded-xl border border-purple-300 p-2 md:p-4">
                                        <div class="relative w-full" style="height: 300px; min-height: 300px;">
                                            <canvas id="investmentChart"></canvas>
                                        </div>
                                    </div>

                                    <!-- Projection Table -->
                                    <div class="mt-10">
                                        <table class="w-full text-white table-auto">
                                            <thead>
                                                <tr class="border-b border-purple-300">
                                                    <th class="text-left py-2 md:py-3 px-1 md:px-2 text-[10px] sm:text-xs md:text-sm lg:text-base font-semibold">Metric</th>
                                                    <th class="text-center py-2 md:py-3 px-1 md:px-2 text-[10px] sm:text-xs md:text-sm lg:text-base font-semibold">Year 5</th>
                                                    <th class="text-center py-2 md:py-3 px-1 md:px-2 text-[10px] sm:text-xs md:text-sm lg:text-base font-semibold">Year 10</th>
                                                    <th class="text-center py-2 md:py-3 px-1 md:px-2 text-[10px] sm:text-xs md:text-sm lg:text-base font-semibold">Year 20</th>
                                                    <th class="text-center py-2 md:py-3 px-1 md:px-2 text-[10px] sm:text-xs md:text-sm lg:text-base font-semibold">Year 30</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="border-b border-purple-400">
                                                    <td class="font-bold text-[9px] sm:text-xs md:text-sm lg:text-base py-2 md:py-4 px-1 md:px-2">Cumulative Net Cash Flow</td>
                                                    <td class="text-center py-2 md:py-4 px-1 md:px-2 text-[9px] sm:text-xs md:text-sm lg:text-base" id="cashFlow5">$0</td>
                                                    <td class="text-center py-2 md:py-4 px-1 md:px-2 text-[9px] sm:text-xs md:text-sm lg:text-base" id="cashFlow10">$0</td>
                                                    <td class="text-center py-2 md:py-4 px-1 md:px-2 text-[9px] sm:text-xs md:text-sm lg:text-base" id="cashFlow20">$0</td>
                                                    <td class="text-center py-2 md:py-4 px-1 md:px-2 text-[9px] sm:text-xs md:text-sm lg:text-base" id="cashFlow30">$0</td>
                                                </tr>
                                                <tr class="border-b border-purple-400">
                                                    <td class="font-bold text-[9px] sm:text-xs md:text-sm lg:text-base py-2 md:py-4 px-1 md:px-2">Cumulative Appreciation Gain</td>
                                                    <td class="text-center py-2 md:py-4 px-1 md:px-2 text-[9px] sm:text-xs md:text-sm lg:text-base" id="appreciation5">$0</td>
                                                    <td class="text-center py-2 md:py-4 px-1 md:px-2 text-[9px] sm:text-xs md:text-sm lg:text-base" id="appreciation10">$0</td>
                                                    <td class="text-center py-2 md:py-4 px-1 md:px-2 text-[9px] sm:text-xs md:text-sm lg:text-base" id="appreciation20">$0</td>
                                                    <td class="text-center py-2 md:py-4 px-1 md:px-2 text-[9px] sm:text-xs md:text-sm lg:text-base" id="appreciation30">$0</td>
                                                </tr>
                                                <tr class="border-b border-purple-400">
                                                    <td class="font-bold text-[9px] sm:text-xs md:text-sm lg:text-base py-2 md:py-4 px-1 md:px-2">Your Investment</td>
                                                    <td class="text-center py-2 md:py-4 px-1 md:px-2 text-[9px] sm:text-xs md:text-sm lg:text-base" id="investment5">$0</td>
                                                    <td class="text-center py-2 md:py-4 px-1 md:px-2 text-[9px] sm:text-xs md:text-sm lg:text-base" id="investment10">$0</td>
                                                    <td class="text-center py-2 md:py-4 px-1 md:px-2 text-[9px] sm:text-xs md:text-sm lg:text-base" id="investment20">$0</td>
                                                    <td class="text-center py-2 md:py-4 px-1 md:px-2 text-[9px] sm:text-xs md:text-sm lg:text-base" id="investment30">$0</td>
                                                </tr>
                                                <tr class="bg-purple-700 bg-opacity-50">
                                                    <td class="font-bold text-[9px] sm:text-xs md:text-sm lg:text-base py-2 md:py-4 px-1 md:px-2">Total Investment Value</td>
                                                    <td class="text-center py-2 md:py-4 px-1 md:px-2 text-[9px] sm:text-xs md:text-sm lg:text-base font-bold" id="total5">$0</td>
                                                    <td class="text-center py-2 md:py-4 px-1 md:px-2 text-[9px] sm:text-xs md:text-sm lg:text-base font-bold" id="total10">$0</td>
                                                    <td class="text-center py-2 md:py-4 px-1 md:px-2 text-[9px] sm:text-xs md:text-sm lg:text-base font-bold" id="total20">$0</td>
                                                    <td class="text-center py-2 md:py-4 px-1 md:px-2 text-[9px] sm:text-xs md:text-sm lg:text-base font-bold" id="total30">$0</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Market Tab -->
                        @if($ticket->type === 'property' && !empty($ticket->market))
                        <div class="tab-content hidden" id="market">
                            <h2 class="text-xl font-bold text-gray-900 mb-4">Market Analysis</h2>
                            <div class="markdown-content text-gray-700">
                                {!! $ticket->market !!}
                            </div>
                        </div>
                        @endif

                        <!-- Documents Tab -->
                        <div class="tab-content hidden" id="documents">
                            <h2 class="text-xl font-bold text-gray-900 mb-4">Investment Documents</h2>
                            <div class="space-y-3">
                                @if(isset($ticket->documents) && is_array($ticket->documents) && count($ticket->documents) > 0)
                                    @foreach($ticket->documents as $document)
                                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                            <div class="flex items-center">
                                                @php
                                                    $extension = strtolower($document['type'] ?? 'file');
                                                    $iconClass = 'fa-file';
                                                    $iconColor = 'text-gray-500';
                                                    
                                                    if (in_array($extension, ['pdf'])) {
                                                        $iconClass = 'fa-file-pdf';
                                                        $iconColor = 'text-red-500';
                                                    } elseif (in_array($extension, ['doc', 'docx'])) {
                                                        $iconClass = 'fa-file-word';
                                                        $iconColor = 'text-blue-500';
                                                    } elseif (in_array($extension, ['xls', 'xlsx'])) {
                                                        $iconClass = 'fa-file-excel';
                                                        $iconColor = 'text-green-500';
                                                    }
                                                @endphp
                                                <i class="fas {{ $iconClass }} {{ $iconColor }} text-2xl mr-4"></i>
                                                <div>
                                                    <div class="font-medium text-gray-900">{{ $document['name'] ?? 'Document' }}</div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ strtoupper($document['type'] ?? 'FILE') }} 
                                                        @if(isset($document['size']))
                                                            • {{ number_format($document['size'] / 1024, 2) }} KB
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <a href="{{ asset($document['path']) }}" download class="text-purple-600 hover:text-purple-700">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center py-12">
                                        <i class="fas fa-folder-open text-gray-400 text-5xl mb-4"></i>
                                        <p class="text-gray-500">No documents available for this investment yet.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Column - Investment Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-lg p-6 sticky top-8">
                    
                    <!-- Investment Card Header -->
                    <div class="mb-6">
                        <div class="text-sm text-gray-600 mb-2">Starting Price</div>
                        <div class="text-4xl font-bold text-purple-600 mb-1">
                            ${{ number_format($ticket->price_per_share, 2) }}
                        </div>
                        <div class="text-sm text-gray-500">per share</div>
                    </div>

                    <hr class="my-6">

                    <!-- Investment Stats -->
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Total Shares</span>
                            <span class="font-semibold text-gray-900">{{ number_format($ticket->total_shares) }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Available Shares</span>
                            <span class="font-semibold text-green-600">{{ number_format($ticket->available_shares) }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Investment Value</span>
                            <span class="font-semibold text-gray-900">${{ number_format($ticket->price) }}</span>
                        </div>
                    </div>

                    <hr class="my-6">

                    <!-- Investment Input -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Number of Shares
                        </label>
                        <input type="number" max="{{ $ticket->available_shares }}" min="1" placeholder="Enter number of shares"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-lg"
                               id="investShares" oninput="updateInvestmentCard(); validatePurchaseShares(this)" onblur="validatePurchaseSharesBlur(this)" onfocus="clearInputIfOne(this)">
                        <div class="mt-2 text-sm text-gray-500">
                            Min: 1 share • Max: {{ number_format($ticket->available_shares) }} shares
                        </div>
                    </div>

                    <!-- Total Investment Display -->
                    <div class="bg-purple-50 rounded-lg p-4 mb-6">
                        <div class="text-sm text-gray-600 mb-1">Your Investment</div>
                        <div class="text-3xl font-bold text-purple-600" id="cardTotalInvestment">
                            ${{ number_format($ticket->price_per_share, 2) }}
                        </div>
                    </div>

                    <!-- Action Buttons -->

                        <!-- Modal Triggered Form -->
                        <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                            <form action="{{ route('tickets') }}" method="POST" id="buySharesForm" style="flex: 1;">
                                @csrf
                                <input type="hidden" name="ticket[{{ $ticket->id }}][id]" value="{{ $ticket->id }}">
                                <input type="hidden" name="ticket[{{ $ticket->id }}][quantity]" id="formQuantity" value="1">
                                <button type="button" class="investment-btn w-full text-white py-4 rounded-lg font-semibold text-lg" id="buySharesButton">
                                    <i class="fas fa-shopping-cart mr-2"></i>Buy Shares
                                </button>
                            </form>
                            <button type="button" class="btn btn-outline-primary py-4 rounded-lg font-semibold text-lg" id="addTicketToCartButton" style="flex: 1; border-width: 2px;">
                                <i class="fas fa-cart-plus mr-2"></i>Add to Cart
                            </button>
                        </div>

                        @include('partials.ticket-auth-modal')
                        @include('partials.investor-info-modal')

                        <script>
                        // Open modal or proceed depending on auth state
                        document.getElementById('buySharesButton').addEventListener('click', function(e) {
                            e.preventDefault();
                            // call check endpoint
                            (async function() {
                                try {
                                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                                    const res = await fetch('/ajax/ticket-auth/check', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token } });
                                    const json = await res.json();
                                    
                                    // Store the form for later submission
                                    window._ticketAuthPendingForm = document.getElementById('buySharesForm');
                                    
                                    if (json.authenticated && json.verified) {
                                        // User is logged in - show investor modal directly
                                        showInvestorModalForLoggedInUser();
                                    } else {
                                        // Not logged in - show auth modal first
                                        // Always show login first, then let the modal logic handle other states
                                        setAuthMode('login');
                                        openAuthModal();
                                        // Only switch mode if user is already authenticated but not verified
                                        if (json.authenticated && !json.verified) {
                                            setTimeout(() => setAuthMode('verify'), 100);
                                        }
                                    }
                                } catch (e) {
                                    // fallback
                                    setAuthMode('login');
                                    openAuthModal();
                                }
                            })();
                        });

                        // Function to show investor modal for logged-in users
                        async function showInvestorModalForLoggedInUser() {
                            try {
                                // Fetch existing profile if any
                                const profileResp = await fetch('/users/investor-profile', {
                                    headers: { 
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                    }
                                });
                                const profileData = await profileResp.json();
                                
                                console.log('Profile data received:', profileData);
                                
                                // Wait a bit to ensure modal is ready
                                setTimeout(() => {
                                    // If profile exists, load it into modal
                                    if (profileData.success && profileData.profile) {
                                        if (typeof window.loadInvestorProfile === 'function') {
                                            window.loadInvestorProfile(profileData.profile);
                                        }
                                    }
                                    
                                    // Show investor info modal
                                    const modalElement = document.getElementById('investorInfoModal');
                                    if (modalElement) {
                                        const investorModal = new bootstrap.Modal(modalElement);
                                        investorModal.show();
                                        console.log('Investor modal displayed for logged-in user');
                                    } else {
                                        console.error('Investor modal element not found!');
                                        // If modal not found, proceed with form submission
                                        if (window._ticketAuthPendingForm) {
                                            window._ticketAuthPendingForm.submit();
                                        }
                                    }
                                    
                                    // Store pending form for later submission
                                    window._investorProfilePendingForm = window._ticketAuthPendingForm;
                                }, 300);
                                
                            } catch (error) {
                                console.error('Failed to load investor profile:', error);
                                // If profile check fails, proceed with form submission anyway
                                if (window._ticketAuthPendingForm) {
                                    window._ticketAuthPendingForm.submit();
                                }
                            }
                        }

                        // Listen for investor profile completion events
                        window.addEventListener('investorProfileSaved', function() {
                            console.log('Investor profile saved, proceeding to checkout');
                            if (window._investorProfilePendingForm) {
                                window._investorProfilePendingForm.submit();
                            }
                        });

                        window.addEventListener('investorProfileSkipped', function() {
                            console.log('Investor profile skipped, proceeding to checkout');
                            if (window._investorProfilePendingForm) {
                                window._investorProfilePendingForm.submit();
                            }
                        });

                        // Add to Cart button for tickets
                        document.getElementById('addTicketToCartButton').addEventListener('click', function(e) {
                            e.preventDefault();
                            if (typeof window.addToCart === 'function') {
                                const quantity = parseInt(document.getElementById('formQuantity').value) || 1;
                                window.addToCart({
                                    id: {{ $ticket->id }},
                                    name: '{{ $ticket->name }}',
                                    type: 'ticket',
                                    price: {{ $ticket->price_per_share }},
                                    quantity: quantity
                                });
                            } else {
                                console.error('addToCart function not available on window');
                            }
                        });

                        // Auth modal state
                        let authMode = 'login'; // or 'register' or 'verify'
                        function setAuthMode(mode) {
                            authMode = mode;
                            document.getElementById('authError').textContent = '';
                            document.getElementById('verificationField').classList.add('hidden');
                            document.getElementById('passwordField').classList.remove('hidden');
                            document.getElementById('confirmPasswordField').classList.remove('hidden');
                            document.getElementById('authName').closest('div').classList.remove('hidden');
                            document.getElementById('authSubmitBtn').textContent = (mode === 'register') ? 'Register' : (mode === 'verify' ? 'Verify' : 'Login');
                            if (mode === 'verify') {
                                document.getElementById('verificationField').classList.remove('hidden');
                                document.getElementById('passwordField').classList.add('hidden');
                                document.getElementById('confirmPasswordField').classList.add('hidden');
                                document.getElementById('authName').closest('div').classList.add('hidden');
                            }
                            if (mode === 'login') {
                                document.getElementById('confirmPasswordField').classList.add('hidden');
                                document.getElementById('authName').closest('div').classList.add('hidden');
                            }
                        }
                        document.getElementById('switchToRegister').addEventListener('click', function(e) {
                            e.preventDefault();
                            setAuthMode('register');
                        });
                        document.getElementById('switchToLogin').addEventListener('click', function(e) {
                            e.preventDefault();
                            setAuthMode('login');
                        });
                        setAuthMode('login');

                        // AJAX logic
                        document.getElementById('authForm').addEventListener('submit', async function(e) {
                            e.preventDefault();
                            const email = document.getElementById('authEmail').value.trim();
                            const password = document.getElementById('authPassword').value;
                            const code = document.getElementById('verificationCode').value.trim();
                            const errorDiv = document.getElementById('authError');
                            errorDiv.textContent = '';
                            let url = '';
                            let data = { email };
                            if (authMode === 'register') {
                                url = '/ajax/ticket-auth/register';
                                data.password = password;
                            } else if (authMode === 'login') {
                                url = '/ajax/ticket-auth/login';
                                data.password = password;
                            } else if (authMode === 'verify') {
                                url = '/ajax/ticket-auth/verify';
                                data.code = code;
                            }
                            // Retrieve CSRF token safely
                            const tokenMeta = document.querySelector('meta[name="csrf-token"]');
                            const csrfToken = tokenMeta ? tokenMeta.getAttribute('content') : (document.querySelector('input[name="_token"]') ? document.querySelector('input[name="_token"]').value : '');
                            if (authMode === 'register' && data.password) {
                                // Validate password confirmation
                                const confirm = document.getElementById('authConfirmPassword').value;
                                if (data.password !== confirm) {
                                    errorDiv.textContent = 'Passwords do not match.';
                                    return;
                                }
                                data.name = document.getElementById('authName').value.trim();
                                if (!data.name) { errorDiv.textContent = 'Name is required.'; return; }
                            }
                            try {
                                const resp = await fetch(url, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': csrfToken
                                    },
                                    body: JSON.stringify(data)
                                });
                                const result = await resp.json();
                                if (result.success) {
                                    // Update CSRF token if provided (after login/register)
                                    if (result.csrf_token) {
                                        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                                        if (csrfMeta) {
                                            csrfMeta.setAttribute('content', result.csrf_token);
                                        }
                                        // Also update any hidden CSRF inputs in forms
                                        document.querySelectorAll('input[name="_token"]').forEach(input => {
                                            input.value = result.csrf_token;
                                        });
                                    }
                                    
                                    // If registration/login returned success for sending verification
                                    if (authMode === 'register') {
                                        errorDiv.textContent = 'Verification code sent to ' + email + '. Check spam folder if missing.';
                                        setAuthMode('verify');
                                        return;
                                    }
                                    if (authMode === 'register' || authMode === 'login') {
                                        setAuthMode('verify');
                                    } else {
                                        // Success: close modal and submit form
                                        closeAuthModal();
                                        document.getElementById('buySharesForm').submit();
                                    }
                                } else {
                                    if (result.require_verification) {
                                        setAuthMode('verify');
                                    }
                                    errorDiv.textContent = result.message || 'An error occurred.';
                                }
                            } catch (err) {
                                errorDiv.textContent = 'Server error. Please try again.';
                            }
                        });
                        </script>
                        <hr class="my-6">

                    <!-- Additional Info -->
                    <div class="space-y-3 text-sm">
                        <div class="flex items-start">
                            <i class="fas fa-shield-alt text-green-500 mt-1 mr-3"></i>
                            <span class="text-gray-600">Secure, transparent ownership</span>
                        </div>
                        
                        <div class="flex items-start">
                            <i class="fas fa-chart-line text-blue-500 mt-1 mr-3"></i>
                            <span class="text-gray-600">Track your investment in real-time</span>
                        </div>
                        
                        <div class="flex items-start">
                            <i class="fas fa-users text-purple-500 mt-1 mr-3"></i>
                            @php
                                // Get unique number of people who purchased this property
                                $uniqueInvestors = \App\Models\TicketSellDetail::where('ticket_id', $ticket->id)
                                    ->whereHas('ticketSell', function($query) {
                                        $query->where('status', 1);
                                    })
                                    ->distinct('ticket_sell_id')
                                    ->count('ticket_sell_id');
                            @endphp
                            <span class="text-gray-600">Join {{ number_format($uniqueInvestors) }} other investors</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        // Tab Switching
        document.querySelectorAll('.tab-btn').forEach(button => {
            button.addEventListener('click', () => {
                const tabName = button.getAttribute('data-tab');
                
                // Remove active class from all tabs and buttons
                document.querySelectorAll('.tab-btn').forEach(btn => {
                    btn.classList.remove('active', 'border-purple-600', 'text-purple-600');
                    btn.classList.add('border-transparent', 'text-gray-500');
                });
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.add('hidden');
                    content.classList.remove('active');
                });
                
                // Add active class to clicked tab and content
                button.classList.add('active', 'border-purple-600', 'text-purple-600');
                button.classList.remove('border-transparent', 'text-gray-500');
                document.getElementById(tabName).classList.remove('hidden');
                document.getElementById(tabName).classList.add('active');
            });
        });

        // Set initial active state
        document.querySelector('.tab-btn.active').classList.add('border-purple-600', 'text-purple-600');
        document.querySelectorAll('.tab-btn:not(.active)').forEach(btn => {
            btn.classList.add('border-transparent', 'text-gray-500');
        });

        // Image Gallery
        function changeImage(src, element) {
            // Update main image
            document.getElementById('mainImage').src = src;
            
            // Remove border from all thumbnails
            document.querySelectorAll('.thumbnail-item').forEach(item => {
                item.classList.remove('border-purple-500');
                item.classList.add('border-transparent');
            });
            
            // Add border to clicked thumbnail
            if (element) {
                element.classList.remove('border-transparent');
                element.classList.add('border-purple-500');
            }
        }

        // Investment Calculator
        // Note: pricePerShare already defined in advanced calculator section below
        const totalShares = {{ $ticket->total_shares }};

        function calculateInvestment() {
            const shares = document.getElementById('shareCalc').value || 1;
            const total = shares * pricePerShare;
            const ownership = (shares / totalShares) * 100;
            
            document.getElementById('totalInvestment').textContent = '$' + total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            document.getElementById('ownershipPercent').textContent = ownership.toFixed(4) + '%';
        }

        function updateInvestmentCard() {
            const shareInput = document.getElementById('investShares');
            const shares = shareInput.value || 1;
            const total = shares * pricePerShare;
            
            document.getElementById('cardTotalInvestment').textContent = '$' + total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            
            // Update the hidden form quantity field
            document.getElementById('formQuantity').value = shares;
            
            // Also update the calculator if it exists
            if (document.getElementById('shareCalc')) {
                document.getElementById('shareCalc').value = shares;
                calculateInvestment();
            }
        }

        // Clear input if it contains the default value of 1 when focused
        function clearInputIfOne(input) {
            if (input.value === '1') {
                input.value = '';
            }
        }

        // Initialize
        calculateInvestment();
        updateInvestmentCard();
        
        // Share validation functions
        const availableShares = {{ $ticket->available_shares }};
        
        function validateShares(input) {
            // Only show validation messages, don't force values during typing
            const quantity = parseInt(input.value) || 0;
            const messageDiv = document.getElementById('shareValidationMessage');
            
            if (input.value === '') {
                messageDiv.style.display = 'none';
                input.style.borderColor = '#d1d5db';
                return;
            }
            
            if (quantity > availableShares) {
                messageDiv.textContent = `Maximum ${availableShares} shares available`;
                messageDiv.style.display = 'block';
                input.style.borderColor = 'red';
            } else if (quantity < 1) {
                messageDiv.textContent = 'Minimum 1 share required';
                messageDiv.style.display = 'block';
                input.style.borderColor = 'red';
            } else {
                messageDiv.style.display = 'none';
                input.style.borderColor = '#d1d5db';
            }
            
            calculateInvestment();
        }
        
        function validateSharesBlur(input) {
            // Force valid values only when user finishes editing (on blur)
            const quantity = parseInt(input.value) || 0;
            const messageDiv = document.getElementById('shareValidationMessage');
            
            if (input.value === '' || quantity < 1) {
                input.value = 1;
            } else if (quantity > availableShares) {
                input.value = availableShares;
            }
            
            messageDiv.style.display = 'none';
            input.style.borderColor = '#d1d5db';
            calculateInvestment();
        }
        
        function validatePurchaseShares(input) {
            // Only show validation, don't force values during typing
            const quantity = parseInt(input.value) || 0;
            
            if (input.value === '') {
                // Allow empty during typing
                return;
            }
            
            if (quantity > availableShares) {
                // Show warning but don't change value yet
                input.style.borderColor = 'red';
            } else {
                input.style.borderColor = '#d1d5db';
            }
            
            updateInvestmentCard();
        }
        
        function validatePurchaseSharesBlur(input) {
            // Force valid values only when user finishes editing (on blur)
            const quantity = parseInt(input.value) || 0;
            const buyButton = document.getElementById('buySharesButton');
            const formQuantity = document.getElementById('formQuantity');
            
            if (input.value === '' || quantity < 1) {
                input.value = 1;
            } else if (quantity > availableShares) {
                alert(`Only ${availableShares} shares available for purchase`);
                input.value = availableShares;
            }
            
            input.style.borderColor = '#d1d5db';
            
            // Update form quantity
            if (formQuantity) {
                formQuantity.value = input.value;
            }
            
            // Disable buy button if no shares available
            if (availableShares <= 0) {
                buyButton.disabled = true;
                buyButton.innerHTML = '<i class="fas fa-ban mr-2"></i>Sold Out';
                buyButton.style.backgroundColor = '#6c757d';
                buyButton.style.cursor = 'not-allowed';
            } else {
                buyButton.disabled = false;
                buyButton.innerHTML = '<i class="fas fa-shopping-cart mr-2"></i>Buy Shares';
                buyButton.style.backgroundColor = '';
                buyButton.style.cursor = '';
            }
            
            updateInvestmentCard();
        }
        
        // Form submission validation
        document.getElementById('buySharesForm').addEventListener('submit', function(e) {
            const quantity = parseInt(document.getElementById('formQuantity').value) || 0;
            
            if (quantity > availableShares) {
                e.preventDefault();
                alert(`Cannot purchase ${quantity} shares. Only ${availableShares} shares available.`);
                return false;
            }
            
            if (quantity < 1) {
                e.preventDefault();
                alert('Must purchase at least 1 share.');
                return false;
            }
            
            if (availableShares <= 0) {
                e.preventDefault();
                alert('This investment is sold out.');
                return false;
            }
        });
        
        // Initialize validation on page load
        document.addEventListener('DOMContentLoaded', function() {
            const investShares = document.getElementById('investShares');
            if (investShares) {
                validatePurchaseShares(investShares);
            }
        });
    </script>

    <!-- Advanced Investment Calculator Script -->
    <script>
        // Initialize variables
        let pricePerShare = parseFloat({{ $ticket->price_per_share }}) || 0;
        const totalSharesAvailable = {{ $ticket->available_shares }};
        let investmentChart = null;

        // Initialize calculator on page load
        document.addEventListener('DOMContentLoaded', function() {
            const chartElement = document.getElementById('investmentChart');
            if (chartElement) {
                initializeChart();
                updateCalculations();
            }
        });

        function updateCalculations() {
            const sharesSlider = document.getElementById('sharesSlider');
            const appreciationSlider = document.getElementById('appreciationSlider');
            const cashReturnSlider = document.getElementById('cashReturnSlider');
            
            if (!sharesSlider || !appreciationSlider || !cashReturnSlider) return;
            
            const shares = parseInt(sharesSlider.value);
            const appreciation = parseFloat(appreciationSlider.value);
            const cashReturn = parseFloat(cashReturnSlider.value);

            // Update display values
            const sharesValue = document.getElementById('sharesValue');
            const sharesCost = document.getElementById('sharesCost');
            const appreciationValue = document.getElementById('appreciationValue');
            const cashReturnValue = document.getElementById('cashReturnValue');
            
            if (sharesValue) sharesValue.textContent = shares;
            if (sharesCost) sharesCost.textContent = (shares * pricePerShare).toFixed(2);
            if (appreciationValue) appreciationValue.textContent = appreciation.toFixed(1) + '%';
            if (cashReturnValue) cashReturnValue.textContent = cashReturn.toFixed(2) + '%';

            // Calculate projections
            const initialInvestment = shares * pricePerShare;
            const projections = calculateProjections(initialInvestment, appreciation, cashReturn);

            // Update table
            updateTable(projections, initialInvestment);

            // Update chart
            updateChart(projections, initialInvestment);
        }

        function calculateProjections(investment, appreciationRate, cashReturnRate) {
            const years = 30;
            const data = {
                cashFlow: [],
                appreciation: [],
                investment: [],
                total: []
            };

            let cumulativeCashFlow = 0;

            for (let year = 1; year <= years; year++) {
                // Calculate annual cash flow (simple interest each year)
                const annualCashFlow = investment * (cashReturnRate / 100);
                cumulativeCashFlow += annualCashFlow;

                // Calculate appreciation using SIMPLE interest (not compound)
                // Total appreciation = investment × rate × years
                const totalAppreciation = investment * (appreciationRate / 100) * year;

                // Store values - all rounded to 2 decimal places
                data.cashFlow.push(Math.round(cumulativeCashFlow * 100) / 100);
                data.appreciation.push(Math.round(totalAppreciation * 100) / 100);
                data.investment.push(Math.round(investment * 100) / 100);
                data.total.push(Math.round((investment + cumulativeCashFlow + totalAppreciation) * 100) / 100);
            }

            return data;
        }

        function updateTable(projections, investment) {
            const years = [5, 10, 20, 30];
            years.forEach(year => {
                const index = year - 1;
                const cashFlowEl = document.getElementById(`cashFlow${year}`);
                const appreciationEl = document.getElementById(`appreciation${year}`);
                const investmentEl = document.getElementById(`investment${year}`);
                const totalEl = document.getElementById(`total${year}`);
                
                if (cashFlowEl) cashFlowEl.textContent = '$' + Math.round(projections.cashFlow[index]).toLocaleString();
                if (appreciationEl) appreciationEl.textContent = '$' + Math.round(projections.appreciation[index]).toLocaleString();
                if (investmentEl) investmentEl.textContent = '$' + Math.round(investment).toLocaleString();
                if (totalEl) totalEl.textContent = '$' + Math.round(projections.total[index]).toLocaleString();
            });
        }

        function initializeChart() {
            const chartElement = document.getElementById('investmentChart');
            if (!chartElement) return;
            
            const ctx = chartElement.getContext('2d');
            
            investmentChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: Array.from({length: 30}, (_, i) => i + 1),
                    datasets: [
                        {
                            label: 'Cumulative Net Cash Flow',
                            data: [],
                            backgroundColor: '#a6cee3',
                            borderColor: '#5e7480',
                            borderWidth: 0
                        },
                        {
                            label: 'Cumulative Appreciation',
                            data: [],
                            backgroundColor: '#1f78b4',
                            borderColor: '#124466',
                            borderWidth: 0
                        },
                        {
                            label: 'Your Investment',
                            data: [],
                            backgroundColor: '#b2df8a',
                            borderColor: '#657e4e',
                            borderWidth: 0
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            stacked: true,
                            title: {
                                display: window.innerWidth > 640,
                                text: 'Year',
                                color: '#1e293b',
                                font: {
                                    size: window.innerWidth > 640 ? 12 : 10
                                }
                            },
                            ticks: {
                                color: '#475569',
                                font: {
                                    size: window.innerWidth > 640 ? 11 : 9
                                },
                                maxRotation: 45,
                                minRotation: 0
                            }
                        },
                        y: {
                            stacked: true,
                            title: {
                                display: window.innerWidth > 640,
                                text: 'Value ($)',
                                color: '#1e293b',
                                font: {
                                    size: window.innerWidth > 640 ? 12 : 10
                                }
                            },
                            ticks: {
                                color: '#475569',
                                font: {
                                    size: window.innerWidth > 640 ? 11 : 9
                                },
                                callback: function(value) {
                                    if (window.innerWidth <= 640) {
                                        // Shorter format for mobile
                                        if (value >= 1000000) {
                                            return '$' + (value / 1000000).toFixed(1) + 'M';
                                        } else if (value >= 1000) {
                                            return '$' + (value / 1000).toFixed(0) + 'K';
                                        }
                                        return '$' + value;
                                    }
                                    return '$' + value.toLocaleString();
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom',
                            labels: {
                                color: '#1e293b',
                                padding: window.innerWidth > 640 ? 15 : 8,
                                font: {
                                    size: window.innerWidth > 640 ? 11 : 9
                                },
                                boxWidth: window.innerWidth > 640 ? 40 : 20,
                                boxHeight: window.innerWidth > 640 ? 12 : 8
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    label += '$' + context.parsed.y.toLocaleString(undefined, {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    });
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        }

        function updateChart(projections, investment) {
            if (investmentChart) {
                investmentChart.data.datasets[0].data = projections.cashFlow;
                investmentChart.data.datasets[1].data = projections.appreciation;
                investmentChart.data.datasets[2].data = Array(30).fill(investment);
                investmentChart.update();
            }
        }
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Mobile Menu Auto-Close Fix -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('.nav-link');
            const navbarToggler = document.querySelector('.navbar-toggler');
            const navbarNav = document.getElementById('navbarNav');
            
            if (navLinks && navbarToggler && navbarNav) {
                navLinks.forEach(function(link) {
                    link.addEventListener('click', function() {
                        navbarToggler.classList.add('collapsed');
                        navbarNav.classList.remove('show');
                    });
                });
            }
        });
    </script>

    <!-- Footer -->
    @if ($footer && $footer->status == 1)
        @include('layouts.new-footer')
    @endif

    
    <script>
        // Auto-fit large numbers so they never wrap or overflow
        (function() {
            function fitNumber(el) {
                if (!el) return;
                // Cache the max font size the first time we see this element
                const computed = window.getComputedStyle(el);
                const maxPx = parseFloat(el.dataset.maxFontPx || computed.fontSize);
                if (!el.dataset.maxFontPx) el.dataset.maxFontPx = String(maxPx);

                // Reset to max before measuring
                el.style.fontSize = maxPx + 'px';

                // Safety bounds
                const minPx = 12; // do not go smaller than 12px for readability
                let current = maxPx;

                // Ensure measurement reflects latest layout
                const containerWidth = el.clientWidth; // width at current font size

                // If content overflows, shrink until it fits or hits min
                // Use a simple decrement loop to avoid oscillation; guard with iteration cap
                let guard = 40;
                while (el.scrollWidth > el.clientWidth && current > minPx && guard-- > 0) {
                    current -= 1;
                    el.style.fontSize = current + 'px';
                }
            }

            function adjustResponsiveNumbers() {
                // First, fit all responsive numbers individually
                const allNumbers = document.querySelectorAll('.responsive-number');
                allNumbers.forEach(fitNumber);

                // Then, sync the three peer metrics to the leader (Total Value) if present
                const leader = document.querySelector('.js-metric-leader');
                if (leader) {
                    const leaderSize = parseFloat(window.getComputedStyle(leader).fontSize);
                    const peers = document.querySelectorAll('.js-metric-number:not(.js-metric-leader)');
                    peers.forEach(el => {
                        const maxPx = parseFloat(el.dataset.maxFontPx || window.getComputedStyle(el).fontSize);
                        const target = Math.min(leaderSize, maxPx);
                        el.style.fontSize = target + 'px';
                        // Ensure it still fits after applying target size
                        if (el.scrollWidth > el.clientWidth) {
                            fitNumber(el);
                        }
                    });
                }
            }

            // Run at appropriate lifecycle points
            window.addEventListener('load', adjustResponsiveNumbers);
            window.addEventListener('resize', adjustResponsiveNumbers);
            if (document.fonts && document.fonts.ready) {
                document.fonts.ready.then(adjustResponsiveNumbers).catch(() => {});
            }

            // Expose for any dynamic updates elsewhere
            window.adjustResponsiveNumbers = adjustResponsiveNumbers;
        })();
    </script>

</body>
</html>

