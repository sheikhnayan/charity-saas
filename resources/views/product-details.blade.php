<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{$ticket->name}}</title>
  
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
  
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  
  <link rel="stylesheet" href="{{ asset('auction.css') }}">
  
  <!-- Tailwind CSS for modals -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- jQuery - Required for cart.js -->
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts - Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Custom Fonts CSS -->
    <link href="{{ route('fonts.css') }}" rel="stylesheet">
    
    <!-- Shopping Cart CSS -->
    <link rel="stylesheet" href="{{ asset('css/cart.css') }}">
    
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <style>
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
    @if(isset($header) && $header->investor_exclusives_font_family)
    .investor-exclusives-bar,
    .investor-exclusives-bar p,
    .investor-exclusives-bar a,
    .investor-exclusives-bar .investor-exclusives-text,
    .investor-exclusives-bar *:not(i):not(.fas):not(.fa):not(.far):not(.fab):not(.fal):not(.fad) {
        font-family: '{{ $header->investor_exclusives_font_family }}', sans-serif !important;
    }
    @endif
    
    /* ---- Reset & Base ---- */
    /* Product Details Color Variables (from Website settings) */
        :root{
            --pd-bg: {{ json_encode($ticket->page_bg_color ?? $ticket->user->website->property_details_bg_color ?? '#f5f6f7') }};
      --pd-text: {{ json_encode($ticket->user->website->property_details_text_color ?? '#111827') }};
      --pd-muted: {{ json_encode($ticket->user->website->property_details_muted_color ?? '#6b7280') }};
      /* --pd-heading: {{ json_encode($ticket->user->website->property_details_heading_color ?? '#1e293b') }}; */
      --pd-price: {{ json_encode($ticket->user->website->property_details_price_color ?? '#111827') }};
      /* --pd-accent: {{ json_encode($ticket->user->website->property_details_accent_color ?? '#0066cc') }}; */
      
      /* --accent:var(--pd-accent);
      --muted:var(--pd-muted); */
      --bg:var(--pd-bg);
      --card:#ffffff;
      --radius:12px;
      --page-max:1180px
    }
    *{box-sizing:border-box}
    html,body{height:100%}
    body{font-family:Inter, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; background:var(--bg); color:var(--pd-text);margin:0;-webkit-font-smoothing:antialiased}
    a{color:inherit;text-decoration:none}
    img{display:block;max-width:100%}

    /* ---- Top global header (like ebay top navigation) ---- */
    .topbar{background:#fff;border-bottom:1px solid #e7e7ea;padding:10px 18px;display:flex;align-items:center;gap:14px}
    .brand{font-weight:700;color:var(--accent);font-size:20px}
    .top-search{flex:1;display:flex;gap:8px;align-items:center}
    .top-search input{flex:1;padding:10px 12px;border:1px solid #eaeaec;border-radius:8px}
    .top-actions{display:flex;gap:14px;align-items:center;color:var(--muted);font-size:14px}

    /* ---- Breadcrumb / utility row ---- */
    .utility{max-width:var(--page-max);margin:12px auto;padding:0 18px;display:flex;justify-content:space-between;align-items:center;font-size:13px;color:var(--muted)}

    /* ---- Main layout ---- */
    .container{max-width:var(--page-max);margin:0 auto;padding:0 18px}
    .grid{margin-top:12px}
    .grid .row{align-items:flex-start} /* Ensure columns align to top */
    
    /* Bootstrap grid adjustments for exact visual match */
    .grid .col-lg-8 {
        padding-right: 14px; /* Half of 28px gap */
    }
    .grid .col-lg-4 {
        padding-left: 14px; /* Half of 28px gap */
    }
    
    @media (min-width: 992px) {
        .grid .col-lg-4 {
            flex: 0 0 360px; /* Fixed width on desktop */
            max-width: 360px; /* Maintain exact right column width */
        }
        .grid .col-lg-8 {
            flex: 1; /* Take remaining space */
            max-width: calc(100% - 360px); /* Ensure proper left column width */
        }
    }

    @media screen and (max-width: 767px) {
        .invest-mobile{
            padding-left: 5px !important;
            padding-right: 5px !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
        }
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

        .sssssttttt {
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


        @media (max-width: 360px) {
            
            .invest-now-btn {
                padding: 10px 24px;
                font-size: 13px;
                min-width: 120px;
            }

            .sssssttttt{
                font-family: sans-serif !important;
                font-size: 100%;
                line-height: 1.15 !important;
                margin: 0 !important;
            }

            .navbar-nav{
                margin-left: 1.5rem !important;
            }
        }

    /* ---- Left column - gallery + product details ---- */
    .gallery-wrap{background:var(--card);border-radius:12px;padding:18px;border:1px solid #e9e9ea}
    .gallery-top{display:flex;gap:18px}
    .thumbs{width:84px;display:flex;flex-direction:column;gap:12px}
    .thumbs button{background:transparent;border:0;padding:0;cursor:pointer}
    .thumbs img{width:72px;height:72px;object-fit:cover;border-radius:8px;border:2px solid transparent}
    .thumbs img.active{border-color:var(--accent)}
    .main-media{flex:1;background:#fff;border-radius:10px;padding:18px;display:flex;align-items:center;justify-content:center;border:1px solid #f0f0f1}
    .main-media img{max-width:100%;max-height:540px;border-radius:8px}
    .media-controls{display:flex;align-items:center;gap:8px;margin-top:10px}
    .media-controls button{padding:8px 10px;border-radius:8px;border:1px solid #e6e6e8;background:#fff;cursor:pointer}

    /* ---- Right column - product panel ---- */
    .panel{background:var(--card);border-radius:12px;padding:18px;border:1px solid #e9e9ea;position:sticky;top:20px}
    .title{font-size:20px;font-weight:700;margin-bottom:6px}
    .subtitle{color:var(--muted);font-size:13px;margin-bottom:12px}
    .price{font-size:32px;color:#111;font-weight:800;margin-bottom:8px}
    .condition{font-size:13px;color:var(--muted);margin-bottom:10px}

    .qty-row{display:flex;gap:12px;align-items:center;margin-bottom:12px}
    .qty-row input{width:72px;padding:8px;border-radius:8px;border:1px solid #e6e6e8;text-align:center}

    .btn{display:inline-flex;align-items:center;justify-content:center;padding:12px 14px;border-radius:10px;font-weight:700;cursor:pointer}
    .btn.primary{background:#0066cc;color:#fff;border:0}
    .btn.ghost{background:#fff;border:1px solid #d9d9db;color:#0066cc}

    .panel .small{font-size:13px;color:var(--muted);margin-top:10px}
    .payment-icons{display:flex;gap:8px;margin-top:8px}
    .payment-icons span{background:#fff;padding:6px 8px;border-radius:8px;border:1px solid #efeff0;font-size:12px}

    /* ---- Badge / stats row ---- */
    .stats{display:flex;gap:12px;align-items:center;margin-top:12px}
    .stat{background:#fbfbfc;padding:8px;border-radius:8px;border:1px solid #f0f0f1;font-size:13px}

    /* ---- Similar / explore sections ---- */
    .section{margin-top:20px; margin-bottom: 20px;}
    .section h3{font-size:16px;margin:0 0 12px}
    .cards{display:flex;gap:12px;overflow:auto;padding-bottom:6px}
    .card{background:#fff;padding:10px;border-radius:10px;min-width:200px;border:1px solid #eee}
    .card img{height:120px;object-fit:cover;border-radius:8px}
    .card .meta{padding-top:10px;font-size:13px;color:var(--muted)}

    /* ---- Item specifics ---- */
    .specs{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-top:18px}
    .spec{background:#fff;padding:14px;border-radius:8px;border:1px solid #efeff0}
    .spec strong{display:block;margin-bottom:6px}

    /* ---- Detailed description / long content ---- */
    .desc{background:#fff;padding:18px;border-radius:10px;border:1px solid #efeff0;margin-top:18px}
    .desc h4{margin-top:0}

    /* ---- Seller box + ratings ---- */
    .seller-panel{background:#fff;padding:14px;border-radius:10px;border:1px solid #efeff0}
    .seller-box{display:flex;gap:12px;align-items:center}
    .avatar{width:64px;height:64px;border-radius:999px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:20px}
    .seller-meta{font-size:14px}
    .rating-bars{margin-top:12px}
    .rating-row{display:flex;align-items:center;gap:8px;margin-bottom:6px}
    .rating-row .bar{height:8px;background:#f0f1f3;border-radius:8px;flex:1;overflow:hidden}
    .rating-row .bar .fill{height:100%;background:var(--accent);width:60%}
    .rating-row span{width:48px;text-align:right;font-size:13px;color:var(--muted)}

    /* ---- Extra banner / similar items from stores ---- */
    .promo{background:#fff;padding:18px;border-radius:12px;border:1px solid #efeff0;margin-top:18px;display:flex;align-items:center;justify-content:space-between}
    .promo .thumbs{display:flex;gap:8px}
    .promo img{width:84px;height:84px;object-fit:cover;border-radius:8px}

    /* ---- Footer ---- */
    footer{margin-top:26px;padding:22px 0;text-align:left;color:var(--muted);font-size:13px}

    /* ---- Utilities / responsive ---- */
    .muted{color:var(--muted)}
    .small{font-size:13px;color:var(--muted)}

    @media (max-width:520px){.thumbs{display:none}.main-media img{max-height:320px}}
    
    /* Global overrides using dynamic color variables */
    body.product-details-page{background-color: var(--pd-bg) !important; color: var(--pd-text) !important;}
    h1,h2,h3,h4,h5,h6,.title{color: var(--pd-heading) !important;}
    .muted,.subtitle,.condition,.small,.text-muted,.card .meta,.seller-meta{color: var(--pd-muted) !important;}
    .price{color: var(--pd-price) !important;}
    a,.btn.ghost{color: var(--pd-accent) !important;}
    /* .btn.primary{background: var(--pd-accent) !important;} */
    
    /* Mobile responsive adjustments */
    @media (max-width: 991.98px) {
        .panel{position:static} /* Make panel non-sticky on mobile */
        .grid .col-lg-8,
        .grid .col-lg-4 {
            padding-left: 15px;
            padding-right: 15px;
            max-width: 100%;
        }
        .grid .col-lg-4 {
            margin-top: 20px; /* Add space between columns on mobile */
        }
    }
    
    /* Ensure sticky behavior is maintained on desktop */
    @media (min-width: 992px) {
        .panel{position:sticky;top:20px} /* Ensure sticky on desktop */
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

    .owl-dots{
        display: none !important;
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
    }
    
    @media (max-width: 480px) {
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
    }
    
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
        font-weight: 400;
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
    }
    
    /* Adjust main content margin when investor exclusives bar is present */
    @media (max-width: 768px) {
        main.with-investor-bar {
            margin-top: 8.5rem !important;
        }
    }
    
    @media (max-width: 480px) {
        main.with-investor-bar {
            margin-top: 8rem !important;
        }
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
    
    /* Ensure modals are hidden by default and positioned correctly */
    #authModal {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 99999 !important;
    }

    #authModal.hidden {
        display: none;
    }

    #authModal:not(.hidden) {
        display: flex;
    }

    /* Ensure Bootstrap modal appears on top */
    .modal-backdrop {
        z-index: 99998 !important;
    }

    .modal {
        z-index: 99999 !important;
    }
  </style>
  <!-- Shopping Cart System - Load with explicit completion handler -->
  <script>
    // Flag to track if cart.js has loaded
    window._cartJsLoaded = false;
    window._cartInitQueue = [];
    
    // Initialize cart after verification - DEFINE BEFORE LOADING cart.js
    function initCartNow() {
        console.log('🛒 [Product Details] Cart system initializing...');
        console.log('🛒 [Product Details] cart.js loaded:', window._cartJsLoaded);
        console.log('🛒 [Product Details] jQuery available:', typeof jQuery !== 'undefined');
        console.log('🛒 [Product Details] window.ShoppingCart:', typeof window.ShoppingCart);
        console.log('🛒 [Product Details] All globals:', Object.keys(window).filter(k => k.includes('Cart') || k.includes('cart')));
        
        if (window.ShoppingCart && typeof window.ShoppingCart.init === 'function') {
            try {
                console.log('✅ [Product Details] ShoppingCart found, initializing...');
                const initPromise = window.ShoppingCart.init();
                if (initPromise && typeof initPromise.catch === 'function') {
                    console.log('✅ [Product Details] ShoppingCart.init() is async, handling as promise...');
                    initPromise.catch(error => {
                        console.error('❌ [Product Details] ShoppingCart.init() promise rejected:', error);
                    });
                } else {
                    console.log('✅ [Product Details] ShoppingCart.init() called');
                }
            } catch (error) {
                console.error('❌ [Product Details] Error calling ShoppingCart.init():', error);
                console.log('Stack trace:', error.stack);
            }
        } else {
            if (!window._cartJsLoaded) {
                console.warn('⚠️ [Product Details] cart.js not loaded yet, will retry...');
                setTimeout(initCartNow, 300);
            } else {
                console.error('❌ [Product Details] cart.js loaded but ShoppingCart not defined');
                console.log('❌ [Product Details] Checking window object for cart-related properties...');
                const cartKeys = Object.keys(window).filter(k => k.toLowerCase().includes('cart'));
                console.log('❌ [Product Details] Cart-related keys found:', cartKeys);
            }
        }
    }
    
    // Define cart loading complete function
    window._onCartLoaded = function() {
        console.log('✅ [Product Details] cart.js script loaded');
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
</head>
<body class="product-details-page" style="background-color: {{ $ticket->page_bg_color ?? '#f5f6f7' }} !important;">
    <div style="max-width:1180px;margin:12px auto;padding:0 18px;">
        @include('partials.back-button')
    </div>
    
    @php
        $url = url()->current();
        $domain = parse_url($url, PHP_URL_HOST);
        $check = \App\Models\Website::where('domain', $domain)->first();
        $groups = \App\Models\User::where('website_id', $check->id)->where('role','group_leader')->get();
        $auction = \App\Models\Auction::where('website_id', $check->id)->where('status',1)->latest()->get();
        
        // Use user_id to fetch header, footer, setting to match property-details
        $user_id = $check->user_id;
        $header = \App\Models\Header::where('user_id', $user_id)->first();
        $footer = \App\Models\Footer::where('user_id', $user_id)->first();
        $setting = \App\Models\Setting::where('user_id', $user_id)->first();
        $customFonts = \App\Models\CustomFont::get();
        $menuSections = [];
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

  <main class="container" style="margin-top: 5rem;">
    <div class="grid">
      <div class="row">
        <!-- LEFT: Gallery, similar, specifics, description -->
        <div class="col-12 col-lg-8">
          <section>
        <div class="gallery-wrap" id="galleryWrap">
          <div class="gallery-top">
            <div class="thumbs" id="thumbsCol">
              <!-- thumbnails (use same source multiple times in demo) -->
              @foreach ($ticket->images as $item)
                  <button aria-label="thumbnail {{ $loop->index + 1 }}">
                      <img src="/{{ $item->image_path }}" data-full="/{{ $item->image_path }}" {{ $loop->index == 0 ? 'class=active' : '' }} alt="thumb{{ $loop->index + 1 }}">
                  </button>
              @endforeach
            </div>

            <div class="main-media" id="mainMedia">
              <img id="mainImg" src="/{{ $ticket->images[0]->image_path }}" alt="main product" />
            </div>
          </div>

          <div class="media-controls">
            <button id="zoomBtn">🔍 Zoom</button>
            <button id="prevBtn">◀</button>
            <button id="nextBtn">▶</button>
          </div>

          <!-- Similar items (carousel) -->
          <div class="section">
            <h3>Products from {{ $ticket->user->website->name }}</h3>
            <div class="cards" id="similarCards" style="max-width: 710px;">
              <!-- example repeated cards to match screenshot -> in real page these would be separate images/text -->
              @php
                $similar = \App\Models\Ticket::where('user_id',$ticket->user->id)->where('type','product')->get();
              @endphp
              @foreach ($similar as $item)
              @if ($item->id != $ticket->id)
                  <a href="/product/{{ $item->slug }}">
                    <div class="card" style="max-width: 178px;"><img src="/{{ $item->image }}" alt="{{$item->name}}" style="width: 100%"><div class="meta">{{$item->name}}<br><strong>${{ number_format($item->price, 2) }}</strong></div></div>
                  </a>
              @endif
              @endforeach
            </div>
          </div>

          <!-- Explore related items (grid) -->
          {{-- <div class="section">
            <h3>Explore related items</h3>
            <div class="cards" style="gap:18px">
              <div class="card" style="min-width:160px"><img src="/mnt/data/46999463-cb2c-41ef-862e-36d2ea98234d.png"><div class="meta">Gold Tone Ring<br><strong>$12.99</strong></div></div>
              <div class="card" style="min-width:160px"><img src="/mnt/data/46999463-cb2c-41ef-862e-36d2ea98234d.png"><div class="meta">Blue Gem Ring<br><strong>$14.99</strong></div></div>
              <div class="card" style="min-width:160px"><img src="/mnt/data/46999463-cb2c-41ef-862e-36d2ea98234d.png"><div class="meta">Rose Design<br><strong>$10.50</strong></div></div>
              <div class="card" style="min-width:160px"><img src="/mnt/data/46999463-cb2c-41ef-862e-36d2ea98234d.png"><div class="meta">Vintage Leaf Ring<br><strong>$11.20</strong></div></div>
            </div>
          </div> --}}

          <!-- Item specifics table matching screenshot columns -->
          @php
              $validFeatures = $ticket->features->filter(function($feature) {
                  return !empty(trim($feature->name)) && !empty(trim($feature->value));
              });
          @endphp
          @if($validFeatures->count() > 0)
          <div class="specs" aria-label="Item specifics">
            @foreach ($validFeatures as $item)
                <div class="spec"><strong>{{ $item->name }}</strong>{{ $item->value }}</div>
            @endforeach
          </div>
          @endif

          <!-- Item description (long) -->
          <div class="desc" id="desc">
            <h4>Item description</h4>
            {!! $ticket->description !!}
          </div>

        </div>

        <!-- More long sections to match full page length: seller feedback, similar from stores, etc. -->
        <div style="height:18px"></div>


        <!-- RIGHT: Product purchase panel + seller quick box -->
      </div>
      <div class="col-12 col-lg-4">
        <aside>
          <div class="panel" role="region" aria-label="purchase panel">
            <div class="title">{{$ticket->name}}</div>
            <div class="subtitle">Sold by <strong>{{$ticket->user->website->name}}</strong></div>
            <div class="price">US ${{ number_format($ticket->price, 2) }}</div>

            <form action="/tickets" method="post" id="ticketPurchaseForm" data-requires-auth="{{ $ticket->type === 'property' ? '1' : '0' }}">
              @csrf
              <div style="height:8px"></div>
              <input type="hidden" name="ticket[{{ $ticket->id }}][id]" value="{{ $ticket->id }}">
              @if($ticket->size != null)
              <div class="qty-row">
                <label for="qty" class="small">Size</label>
                <div style="display:flex;align-items:center;gap:8px">
                  @php
                    $sizes = explode(',',$ticket->size);
                    // dd($sizes);
                  @endphp
                  <button type="button" class="btn" id="sizeToggleBtn"> </button>

                  <select name="ticket[{{ $ticket->id }}][size]" id="" class="form-control" style="margin-left: 2rem; width: 4.5rem; text-align: center;">
                    @foreach($sizes as $size)
                      <option value="{{ trim($size) }}">{{ trim($size) }}</option>
                    @endforeach
                  </select>
                  {{-- <input id="qty" type="number" min="1" value="1" max="{{$ticket->quantity}}" aria-label="quantity" name="ticket[{{ $ticket->id }}][quantity]"> --}}
                </div>
              </div>
              @endif
              <div class="qty-row">
                <label for="qty" class="small">Quantity</label>
                <div style="display:flex;align-items:center;gap:8px">
                  <button type="button" class="btn" id="qtyDec">-</button>
                  <input id="qty" type="number" min="1" value="1" max="{{$ticket->quantity}}" aria-label="quantity" name="ticket[{{ $ticket->id }}][quantity]">
                  <button type="button" class="btn" id="qtyInc">+</button>
                </div>
              </div>
                            <div id="stockStatus" class="small" style="color:#dc2626 !important; font-weight: bold; margin-top:-6px; margin-bottom:10px; display:none;">Out of stock</div>
              
              <div style="display:flex;gap:8px;margin-bottom:10px">
                                <button type="submit" class="btn primary" id="buyNowBtn" style="flex: 1;">Buy It Now</button>
                <button type="button" class="btn ghost" id="addProductToCartBtn" style="flex: 1;">
                  <i class="fa fa-cart-plus mr-2"></i>Add to Cart
                </button>
              </div>
            </form>

            {{-- <div class="small muted">People watch: 334 people are watching this.</div>
            <div style="height:10px"></div>

            <div class="small muted">Shipping: <strong>US $0.00</strong> • Estimated delivery: 7-18 Oct</div> --}}

            <div style="height:12px"></div>

            {{-- <div style="height:12px;border-top:1px solid #f0f0f1;margin-top:12px;padding-top:12px">
              <div class="small muted">Delivery</div>
              <div class="small muted">Ships from: China • Import charges may apply</div>
            </div> --}}
          </div>

          <div class="section">
            <div class="seller-panel" style="margin-top:16px">
              <div class="seller-box">
                <div class="avatar"> <img src="/uploads/{{$ticket->user->website->setting->logo}}" alt="{{$ticket->user->website->name}}'s avatar"> </div>
                <div class="seller-meta">
                  <div style="font-weight:700">{{$ticket->user->website->name}}</div>
                  {{-- <div class="small muted">Feedback: {{$ticket->user->feedback_count}}</div> --}}
                  {{-- <div style="margin-top:8px"><button class="btn ghost">Visit store</button></div> --}}
                </div>
              </div>
            </div>
          </div>

        </aside>
      </div>
    </div>

  </main>
  @if ($footer && $footer->status == 1)
     @include('layouts.new-footer')
 @endif

  <script>
    // --- Gallery thumbnail interactions ---
    (function(){
      const thumbs = document.querySelectorAll('#thumbsCol img');
      const mainImg = document.getElementById('mainImg');
      let current = 0;
      thumbs.forEach((t,i)=>{
        t.addEventListener('click', ()=>{
          thumbs[current].classList.remove('active');
          t.classList.add('active');
          mainImg.src = t.dataset.full || t.src;
          current = i;
        });
      });

      document.getElementById('prevBtn').addEventListener('click', ()=>{
        const next = (current - 1 + thumbs.length) % thumbs.length;
        thumbs[next].click();
      });
      document.getElementById('nextBtn').addEventListener('click', ()=>{
        const next = (current + 1) % thumbs.length;
        thumbs[next].click();
      });

      document.getElementById('zoomBtn').addEventListener('click', ()=>{
        const url = mainImg.src;
        // open image in new tab for zoom demo
        window.open(url, '_blank');
      });
    })();

    // --- Quantity controls ---
    (function(){
            const inc = document.getElementById('qtyInc');
            const dec = document.getElementById('qtyDec');
            const qty = document.getElementById('qty');
            const addBtn = document.getElementById('addProductToCartBtn');
            const buyBtn = document.getElementById('buyNowBtn');
            const stockStatus = document.getElementById('stockStatus');
            if (!inc || !dec || !qty || !addBtn || !buyBtn || !stockStatus) return;

            const maxQty = parseInt(qty.max || '0', 10) || 0;

            const setOutOfStock = (isOut) => {
                stockStatus.style.display = isOut ? 'block' : 'none';
                inc.disabled = isOut;
                dec.disabled = isOut;
                addBtn.disabled = isOut;
                buyBtn.disabled = isOut;
            };

            const normalizeQty = () => {
                let current = parseInt(qty.value, 10);
                if (Number.isNaN(current)) current = 0;

                if (maxQty < 1 || current < 1) {
                    setOutOfStock(true);
                    return;
                }

                setOutOfStock(false);
                if (current > maxQty) current = maxQty;
                qty.value = current;
            };

            if (maxQty < 1) {
                qty.value = 0;
                qty.min = 0;
                qty.max = 0;
                setOutOfStock(true);
            } else {
                qty.value = Math.min(Math.max(parseInt(qty.value || '1', 10) || 1, 1), maxQty);
                setOutOfStock(false);
            }

            inc.addEventListener('click', () => {
                if (maxQty < 1) return;
                const current = parseInt(qty.value || '1', 10) || 1;
                qty.value = Math.min(maxQty, current + 1);
                normalizeQty();
            });

            dec.addEventListener('click', () => {
                if (maxQty < 1) return;
                const current = parseInt(qty.value || '1', 10) || 1;
                qty.value = current - 1;
                normalizeQty();
            });

            qty.addEventListener('input', normalizeQty);
    })();

    // --- Add to Cart button ---
    (function(){
      const addBtn = document.getElementById('addProductToCartBtn');
            const qtyInput = document.getElementById('qty');
            if (!addBtn || !qtyInput) return;
      
      addBtn.addEventListener('click', function(e) {
        e.preventDefault();
                const qty = parseInt(qtyInput.value, 10) || 0;
                if (qty < 1 || addBtn.disabled) {
                    return;
                }
        
        if (typeof window.addToCart === 'function') {
          window.addToCart({
            id: {{ $ticket->id }},
            name: '{{ $ticket->name }}',
            type: 'product',
            price: {{ $ticket->price }},
            quantity: qty
          });
        } else {
          console.error('addToCart function not available on window');
        }
      });
    })();

    // --- Similar cards keyboard nav ---
    (function(){
      document.addEventListener('keydown', (e)=>{
        const sc = document.getElementById('similarCards');
        if(!sc) return;
        if(e.key === 'ArrowLeft') sc.scrollBy({left:-220,behavior:'smooth'});
        if(e.key === 'ArrowRight') sc.scrollBy({left:220,behavior:'smooth'});
      });
    })();

    // --- Ticket Purchase Authentication Flow ---
    (function(){
      const form = document.getElementById('ticketPurchaseForm');
      if (!form) return;

      form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const submitBtn = form.querySelector('button[type="submit"]');
                const qtyInput = document.getElementById('qty');
                const selectedQty = parseInt(qtyInput ? qtyInput.value : '0', 10) || 0;
                if (selectedQty < 1 || submitBtn.disabled) {
                    return;
                }
        const originalText = submitBtn.textContent;
        
        try {
          // CONDITIONAL_AUTH_MODAL: Only require auth for property/investment type products
          const isPropertyType = '{{ $ticket->type }}' === 'property';
          
          if (!isPropertyType) {
            // Simple ticket/product/auction - no auth required, submit directly
            submitBtn.textContent = 'Processing...';
            form.submit();
            return;
          }
          
          submitBtn.textContent = 'Checking...';
          submitBtn.disabled = true;

          // Check if user is authenticated (only for property/investment types)
          const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
          const authCheck = await fetch('/ajax/ticket-auth/check', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({})
          });
          const authStatus = await authCheck.json();

          if (!authStatus.authenticated || !authStatus.verified) {
            // User not authenticated - show auth modal
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;

            // Store form reference for later submission (use the variable name the modal expects)
            window._ticketAuthPendingForm = form;
            // Mark this as a simple ticket purchase (not investment) to skip investor modal
            window._isSimpleTicketPurchase = true;

            // Open auth modal
            if (typeof setAuthMode === 'function') {
              setAuthMode('login');
            }
            if (typeof openAuthModal === 'function') {
              openAuthModal();
            }
            return;
          }

          // User is authenticated - submit the form
          submitBtn.textContent = 'Processing...';
          form.submit();

        } catch (error) {
          console.error('Authentication check failed:', error);
          submitBtn.textContent = originalText;
          submitBtn.disabled = false;
          /* alert('An error occurred. Please try again.'); */
        }
      });
    })();
  </script>
  
  @include('partials.ticket-auth-modal')
  @include('partials.investor-info-modal')
  
  <!-- Bootstrap JS Bundle - Required for navbar collapse toggle functionality -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
