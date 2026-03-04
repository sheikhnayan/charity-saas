@php
    // Get website data based on current domain
    $url = url()->current();
    $domain = parse_url($url, PHP_URL_HOST);
    $check = \App\Models\Website::where('domain', $domain)->first();

    if ($check) {
        $user_id = $check->user_id;
        $setting = \App\Models\Setting::where('user_id', $user_id)->first();
        $header = \App\Models\Header::where('user_id', $user_id)->first();
        $footer = \App\Models\Footer::where('user_id', $user_id)->first();
    } else {
        $setting = null;
        $header = null;
        $footer = null;
    }
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        {{ $setting && $setting->company_name ? $setting->company_name . ' | Investment Checkout' : 'Investment Checkout' }}
    </title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Tailwind CSS for modals -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: #f9fafb;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('auction.css') }}">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts - Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Original invest page metadata -->
    <meta
        content="{{ $setting && $setting->company_name ? 'Invest in ' . $setting->company_name . ' and become part of our growing success story. Secure your shares today through our regulated investment platform.' : 'Secure investment platform offering regulated investment opportunities.' }}"
        name="description" />
    <meta
        content="{{ $setting && $setting->company_name ? $setting->company_name . ' | Investment Checkout' : 'Investment Checkout' }}"
        property="og:title" />
    <meta
        content="{{ $setting && $setting->company_name ? 'Invest in ' . $setting->company_name . ' and become part of our growing success story. Secure your shares today through our regulated investment platform.' : 'Secure investment platform offering regulated investment opportunities.' }}"
        property="og:description" />
    <meta
        content="{{ $setting && $setting->logo ? asset('uploads/' . $setting->logo) : asset('investment/images/default-investment-image.jpg') }}"
        property="og:image" />
    <meta
        content="{{ $setting && $setting->company_name ? $setting->company_name . ' | Investment Checkout' : 'Investment Checkout' }}"
        property="twitter:title" />
    <meta
        content="{{ $setting && $setting->company_name ? 'Invest in ' . $setting->company_name . ' and become part of our growing success story. Secure your shares today through our regulated investment platform.' : 'Secure investment platform offering regulated investment opportunities.' }}"
        property="twitter:description" />
    <meta
        content="{{ $setting && $setting->logo ? asset('uploads/' . $setting->logo) : asset('investment/images/default-investment-image.jpg') }}"
        property="twitter:image" />
    <meta property="og:type" content="website" />
    <meta content="summary_large_image" name="twitter:card" />
    <meta content="noindex" name="robots" />

    <!-- Investment page specific styles -->
    <link href="{{ asset('investment/css/main.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('investment/css/investment-utilities.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset('investment/js/webfont-loader.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        ! function(o, c) {
            var n = c.documentElement,
                t = " w-mod-";
            n.className += t + "js", ("ontouchstart" in o || o.DocumentTouch && c instanceof DocumentTouch) && (n
                .className += t + "touch")
        }(window, document);
    </script>

    @if ($setting && $setting->favicon)
        <link href="{{ asset('uploads/' . $setting->favicon) }}" rel="shortcut icon" type="image/x-icon" />
        <link href="{{ asset('uploads/' . $setting->favicon) }}" rel="apple-touch-icon" />
    @endif
    <link href="{{ url()->current() }}" rel="canonical" /><!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-W7328S2C');
    </script>
    <!-- End Google Tag Manager -->

    <!-- Custom Fonts CSS -->
    <link href="{{ route('fonts.css') }}" rel="stylesheet">


    <!-- Keep this css code to improve the font quality-->
    <style>
        /* Custom Fonts @font-face declarations */
        @if (isset($customFonts) && $customFonts->count() > 0)
            /* DEBUG: {{ $customFonts->count() }} custom fonts loaded */
            @foreach ($customFonts as $font)
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
        @if (isset($header) && $header && $header->menu_font_family)
            .navbar .nav-link,
            .navbar .navbar-brand,
            .navbar .btn {
                font-family: '{{ $header->menu_font_family }}', sans-serif !important;
            }
        @endif

        /* Contact Topbar Font Family Styling */
        @if (isset($header) && $header && $header->contact_topbar_font_family)
            .contact-topbar,
            .contact-topbar *:not(i):not(.fas):not(.fa):not(.far):not(.fab):not(.fal):not(.fad) {
                font-family: '{{ $header->contact_topbar_font_family }}', sans-serif !important;
            }
        @endif

        /* Investor Exclusives Font Family Styling */
        @if (isset($header) && $header && $header->investor_exclusives_font_family)
            .investor-exclusives-bar,
            .investor-exclusives-bar *:not(i):not(.fas):not(.fa):not(.far):not(.fab):not(.fal):not(.fad) {
                font-family: '{{ $header->investor_exclusives_font_family }}', sans-serif !important;
            }
        @endif

        * {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            -o-font-smoothing: antialiased;
        }

        .modal-content {
            top: 100px !important;
        }

        .dmr-checkout-wrapper {
            padding-top: 0px !important;
        }

        /* Custom Investment Form Styles */
        .investment-form-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 30px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            color: #ffffff !important;
        }

        .investment-form-title {
            font-size: 28px;
            font-weight: 700;
            color: #1a1a1a !important;
            text-align: center;
            margin-bottom: 30px;
        }

        .investment-step {
            transition: all 0.3s ease;
        }

        .investment-step.hidden {
            display: none;
        }

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

        .investment-step h3 {
            font-size: 22px;
            font-weight: 600;
            color: #333 !important;
            margin-bottom: 25px;
            text-align: center;
        }

        /* Amount Tiers */
        .amount-tiers {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 25px;
            justify-content: center;
            max-width: 280px;
            margin-left: auto;
            margin-right: auto;
        }

        .tier-option {
            border: 2px solid #e5e5e5;
            border-radius: 8px;
            padding: clamp(8px, 2vw, 20px) clamp(5px, 1.5vw, 15px);
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f9f9f9;
            color: #ffffff !important;
            max-width: 114.5px;
            width: 100%;
            box-sizing: border-box;
        }

        .tier-option:hover {
            border-color: #007bff;
            background: #f0f8ff;
            color: #ffffff !important;
        }

        .tier-option.selected {
            border-color: #007bff;
            background: #007bff;
            color: white !important;
        }

        .tier-amount {
            font-size: clamp(0.8rem, 2.5vw, 1.5rem);
            font-weight: 700;
            margin-bottom: 8px;
            word-break: break-word;
            overflow-wrap: break-word;
        }

        .tier-shares {
            font-size: clamp(10px, 1.5vw, 14px);
            opacity: 0.8;
        }

        /* Additional responsive rules for tier amounts */
        @media (max-width: 768px) {
            .tier-option {
                max-width: 114.6px;
                min-width: 114.6px;
                padding: clamp(6px, 1.8vw, 16px) clamp(4px, 1.2vw, 12px);
            }

            .tier-amount {
                font-size: clamp(0.7rem, 3vw, 1.2rem);
                line-height: 1.1;
            }

            .amount-tiers {
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
                max-width: 250px;
            }
        }

        @media (max-width: 480px) {
            .tier-option {
                padding: clamp(4px, 1.5vw, 12px) clamp(3px, 1vw, 8px);
            }

            .tier-amount {
                font-size: clamp(0.6rem, 3.5vw, 1rem);
            }

            .tier-shares {
                font-size: clamp(8px, 2vw, 12px);
            }

            .navbar-brand {
                margin-left: 1rem !important;
                margin-top: 0.3rem !important;
                margin-bottom: 0.3rem !important;
            }
        }

        .custom-amount-wrapper {
            grid-column: 1 / -1;
            margin-top: 20px;
            padding: 20px;
            border: 2px dashed #ddd;
            border-radius: 8px;
            background: #fafafa;
        }

        .custom-amount-wrapper label {
            display: block;
            font-weight: 600;
            color: #333 !important;
            margin-bottom: 10px;
        }

        .dmr-common-stock-2 {
            color: #fff !important;
        }

        .custom-amount-wrapper input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            background: white !important;
            color: #ffffff !important;
        }

        .custom-shares-display {
            margin-top: 10px;
            font-size: 14px;
            color: #666 !important;
            font-weight: 500;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: #333 !important;
            margin-bottom: 8px;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.3s ease;
            background: white !important;
            color: #ffffff !important;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
            background: white !important;
            color: #000 !important;
        }

        .checkbox-group label {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-weight: normal;
            line-height: 1.5;
            color: #ffffff !important;
        }

        .checkbox-group input[type="checkbox"] {
            width: auto;
            margin: 0;
            flex-shrink: 0;
        }

        /* Buttons */
        .btn-continue,
        .btn-submit,
        .btn-back {
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-continue,
        .btn-submit {
            background: #007bff;
            color: white;
            width: 100%;
        }

        .btn-continue:hover:not(:disabled),
        .btn-submit:hover {
            background: #0056b3;
            transform: translateY(-2px);
        }

        .btn-continue:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .btn-back {
            background: #6c757d;
            color: white;
            margin-right: 15px;
        }

        nav {
            box-shadow: unset !important;
        }

        .btn-back:hover {
            background: #545b62;
        }

        .form-actions {
            display: flex;
            align-items: center;
            margin-top: 30px;
        }

        /* Success Message */
        .success-message {
            text-align: center;
            padding: 40px 20px;
        }

        .success-message h3 {
            color: #28a745 !important;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .investment-summary {
            background: rgba(248, 249, 250, 0.95);
            border-radius: 8px;
            padding: 20px;
            margin-top: 25px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #dee2e6;
        }

        .summary-item:last-child {
            border-bottom: none;
        }

        .summary-item .label {
            font-weight: 600;
            color: #495057 !important;
        }

        .summary-item .value {
            font-weight: 700;
            color: #007bff !important;
        }

        /* Override any inherited white text */
        * {
            color: inherit;
        }

        .investment-form-container *,
        .investment-form-wrapper,
        .page-wrapper * {
            color: #000 !important;
        }

        .investment-form-container input,
        .investment-form-container select,
        .investment-form-container textarea {
            background: white !important;
            color: #000 !important;
        }

        /* Address Fields Styling */
        .address-fields-container {
            margin-bottom: 20px;
        }

        .address-fields-container .form-group {
            margin-bottom: 15px;
        }

        .address-fields-container label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #000 !important;
        }

        .address-fields-container input,
        .address-fields-container select {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            color: #000 !important;
            background-color: #fff !important;
        }

        .address-fields-container input:focus,
        .address-fields-container select:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
        }

        .address-fields-container select:disabled {
            background-color: #f8f9fa !important;
            color: #6c757d !important;
        }

        .address-fields-container .row {
            margin: 0 -7.5px;
        }

        .address-fields-container .col-md-6 {
            padding: 0 7.5px;
        }

        /* SSN Help Link Styling */
        .ssn-help-link {
            font-size: 12px !important;
            color: #007bff !important;
            text-decoration: none !important;
            float: right;
            font-weight: 400 !important;
        }

        .ssn-help-link:hover {
            color: #0056b3 !important;
            text-decoration: underline !important;
        }

        /* Modal Styling Override */
        .modal-content {
            border-radius: 10px;
        }

        .modal-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }

        .modal-title {
            font-size: 18px;
            font-weight: 600;
        }

        .modal-body {
            font-size: 14px;
        }

        .modal-body strong {
            color: #000 !important;
        }

        .modal-body p {
            margin-bottom: 15px;
        }

        .tier-option.selected * {
            color: white !important;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .investment-form-container {
                margin: 20px;
                padding: 20px;
            }

            .amount-tiers {
                grid-template-columns: repeat(2, 1fr);
            }

            .form-actions {
                flex-direction: column;
                gap: 15px;
            }

            .btn-back {
                margin-right: 0;
                width: 100%;
            }
        }

        /* Sticky Footer Styles */
        .sticky-footer-invest {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: {{ $website && $website->sticky_footer_bg_color ? $website->sticky_footer_bg_color : '#f8f9fa' }};
            border-top: 2px solid #e9ecef;
            padding: 15px 20px;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            display: flex;
            justify-content: center;
        }

        .sticky-footer-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            max-width: 1200px;
        }

        .sticky-footer-text {
            display: flex;
            flex-direction: column;
            color: {{ $website && $website->sticky_footer_text_color ? $website->sticky_footer_text_color : '#333333' }};
        }

        .investment-call {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .investment-subtext {
            font-size: 14px;
            opacity: 0.8;
        }

        .sticky-footer-button {
            background: {{ $website && $website->sticky_footer_button_bg ? $website->sticky_footer_button_bg : '#007bff' }};
            color: {{ $website && $website->sticky_footer_button_text ? $website->sticky_footer_button_text : '#ffffff' }};
            border: none;
            padding: 12px 30px;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .sticky-footer-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            opacity: 0.9;
        }

        .sticky-footer-button:active {
            transform: translateY(0);
        }

        /* Mobile responsiveness for sticky footer */
        @media (max-width: 768px) {
            .sticky-footer-invest {
                padding: 12px 15px;
            }

            .sticky-footer-content {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }

            .sticky-footer-button {
                width: 100%;
                padding: 15px 20px;
            }

            .investment-call {
                font-size: 16px;
            }

            .investment-subtext {
                font-size: 13px;
            }
        }

        /* Contact Top Bar Styles */
        .contact-topbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
            z-index: 1030;
            /* Above navbar but below modals */
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
            font-family: Outfit, sans-serif;
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

            .contact-topbar {
                height: 28px !important;
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
        .contact-topbar+nav.navbar.fixed-top {
            top: 34px !important;
            /* Position navbar directly below contact bar - no gap */
            z-index: 1020;
        }

        @media (max-width: 768px) {
            .contact-topbar+nav.navbar.fixed-top {
                top: 27px !important;
                /* Adjust for mobile - no gap */
            }
        }

        /* Investor Exclusives Bar Styles - Dynamic Positioning */
        .investor-exclusives-bar {
            padding: 0px 0px;
            text-align: center;
            position: fixed;
            top: calc(var(--navbar-total-height, 6rem) - 0.23rem);
            /* Dynamic position minus gap adjustment */
            left: 0;
            right: 0;
            width: 100%;
            z-index: 999;
            /* Just below navbar but above content */
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
            .contact-topbar+nav.navbar.fixed-top {
                top: 27px !important;
            }

            .investor-exclusives-bar {
                position: fixed;
                top: calc(var(--navbar-total-height-mobile, 9.5rem) - 0.23rem);
                /* Dynamic mobile position minus gap adjustment */
                padding-bottom: 0px;
            }

            .investor-exclusives-content {
                flex-direction: row;
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
                top: calc(var(--navbar-total-height-small, 1.7rem) - 0.23rem);
                /* Dynamic small mobile position minus gap adjustment */
                padding-bottom: 0px;
            }

            .close-on-mobile {
                display: none !important;
            }

            .invest-mobile {
                padding: 0px !important;
            }

            .section_header3 {
                padding: 0px !important;
            }
        }



        .navbar {
            padding-top: 0px !important;
            padding-bottom: 0px !important;
        }

        .text-style-eyebrow {
            font-family: Outfit, sans-serif !important;
        }

        .link_wrap div {
            font-family: Outfit, sans-serif !important;
        }

        .footer_content_wrap div h1 strong {
            font-family: Outfit, sans-serif !important;
        }

        .footer_content_wrap div p {
            font-family: Outfit, sans-serif !important;
        }

        .investor-exclusives-link:hover {
            background: rgba(255, 255, 255, 0.25);
            text-decoration: none;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            border-color: rgba(255, 255, 255, 0.5);
        }

        /* Quill.js Class-based Font Styles for Frontend */
        .ql-size-10px {
            font-size: 10px !important;
        }

        .ql-size-12px {
            font-size: 12px !important;
        }

        .ql-size-14px {
            font-size: 14px !important;
        }

        .ql-size-16px {
            font-size: 16px !important;
        }

        .ql-size-18px {
            font-size: 18px !important;
        }

        .ql-size-20px {
            font-size: 20px !important;
        }

        .ql-size-24px {
            font-size: 24px !important;
        }

        .ql-size-28px {
            font-size: 28px !important;
        }

        .ql-size-32px {
            font-size: 32px !important;
        }

        .ql-size-36px {
            font-size: 36px !important;
        }

        .ql-size-40px {
            font-size: 40px !important;
        }

        .ql-size-48px {
            font-size: 48px !important;
        }

        .ql-font-arial {
            font-family: Arial, sans-serif !important;
        }

        .ql-font-helvetica {
            font-family: 'Helvetica Neue', Helvetica, sans-serif !important;
        }

        .ql-font-times {
            font-family: 'Times New Roman', Times, serif !important;
        }

        .ql-font-georgia {
            font-family: Georgia, serif !important;
        }

        .ql-font-verdana {
            font-family: Verdana, sans-serif !important;
        }

        .ql-font-courier {
            font-family: 'Courier New', Courier, monospace !important;
        }

        .ql-font-outfit {
            font-family: 'Outfit', sans-serif !important;
        }

        /* SEO-friendly semantic heading styles for frontend */
        h1,
        .ql-header-1 {
            font-size: 2.5rem !important;
            font-weight: bold !important;
            line-height: 1.2 !important;
            margin: 1rem 0 0.5rem 0 !important;
        }

        h2,
        .ql-header-2 {
            font-size: 2rem !important;
            font-weight: bold !important;
            line-height: 1.3 !important;
            margin: 0.8rem 0 0.4rem 0 !important;
        }

        h3,
        .ql-header-3 {
            font-size: 1.75rem !important;
            font-weight: bold !important;
            line-height: 1.4 !important;
            margin: 0.6rem 0 0.3rem 0 !important;
        }

        h4,
        .ql-header-4 {
            font-size: 1.5rem !important;
            font-weight: bold !important;
            line-height: 1.4 !important;
            margin: 0.5rem 0 0.25rem 0 !important;
        }

        h5,
        .ql-header-5 {
            font-size: 1.25rem !important;
            font-weight: bold !important;
            line-height: 1.5 !important;
            margin: 0.4rem 0 0.2rem 0 !important;
        }

        @media (min-width: 1400px) {

            .container,
            .container-lg,
            .container-md,
            .container-sm,
            .container-xl,
            .container-xxl {
                max-width: 1320px !important;
            }
        }

        .investor-exclusives-bar {
            padding: 0px !important;
        }
    </style>

    <!-- Google Search Console -->
    <meta name="google-site-verification" content="FogpITTz954DYojTNNL7jxXDuO_9x8i4D4ni0Wibtzg" />

    <!-- begin Convert Experiences code
{{-- 
NOTE: External script removed - using local assets instead
<script type="text/javascript" src="//cdn-4.convertexperiments.com/js/1004905-100416832.js"></script>
--}}
end Convert Experiences code --><!-- Checkout Security Measure -->
    <style>
        #step1>div.opacity-100:first-child {
            display: none !important;
        }
    </style>

    <style>
        /* body{background:#f9fafb;} */

        /* Custom Fonts @font-face declarations */
        @if (isset($customFonts) && $customFonts->count() > 0)
            /* DEBUG: {{ $customFonts->count() }} custom fonts loaded */
            @foreach ($customFonts as $font)
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
        @if (isset($header) && $header && $header->menu_font_family)
            .navbar .nav-link,
            .navbar .navbar-brand,
            .navbar .btn {
                font-family: '{{ $header->menu_font_family }}', sans-serif !important;
            }
        @endif

        /* Contact Topbar Font Family Styling */
        @if (isset($header) && $header && $header->contact_topbar_font_family)
            .contact-topbar,
            .contact-topbar *:not(i):not(.fas):not(.fa):not(.far):not(.fab):not(.fal):not(.fad) {
                font-family: '{{ $header->contact_topbar_font_family }}', sans-serif !important;
            }
        @endif

        /* Investor Exclusives Font Family Styling */
        @if (isset($header) && $header && $header->investor_exclusives_font_family)
            .investor-exclusives-bar,
            .investor-exclusives-bar *:not(i):not(.fas):not(.fa):not(.far):not(.fab):not(.fal):not(.fad) {
                font-family: '{{ $header->investor_exclusives_font_family }}', sans-serif !important;
            }
        @endif
    </style>

</head>
@php
    $url2 = url()->current();
    $domain2 = parse_url($url2, PHP_URL_HOST);
    $website = \App\Models\Website::where('domain', $domain2)->first();
@endphp


<body
    style="background-color: {{ $website && $website->background_color ? $website->background_color : ($setting && $setting->background_color ? $setting->background_color : '#f9fafb') }}; margin: 0; padding: 0; color: {{ $website && $website->text_color ? $website->text_color : ($setting && $setting->text_color ? $setting->text_color : '#ffffff') }};">
    @php
        $url = url()->current();
        $domain = parse_url($url, PHP_URL_HOST);
        $check = \App\Models\Website::where('domain', $domain)->first();

        if ($check) {
            $header = \App\Models\Header::where('website_id', $check->id)->first();
            $footer = \App\Models\Footer::where('website_id', $check->id)->first();
        }

        // Get dynamic background and text colors
        $pageBackgroundColor = $website->pages[0]->background_color;
        $pageTextColor =
            $website && $website->text_color
                ? $website->text_color
                : ($setting && $setting->text_color
                    ? $setting->text_color
                    : '#ffffff');
    @endphp

    @if ($header && $header->status == 1)
        {{-- Contact Information Top Bar - Only for Investment Websites --}}
        @if ($header && $header->show_contact_topbar)
            <div class="contact-topbar"
                style="background: {{ $header->contact_topbar_bg_color ?? '#000000' }}; padding: 8px 0; font-size: 14px; height: 35px;">
                <div class="container">
                    <div class="row align-items-center justify-content-center">
                        @if ($header->contact_phone)
                            <div class="col-3 col-md-auto">
                                <div class="contact-item me-4 mb-1">
                                    <i class="fas fa-phone me-2"
                                        style="color: {{ $header->contact_topbar_text_color ?? '#ffffff' }};"></i>
                                    <a href="tel:{{ $header->contact_phone }}"
                                        style="color: {{ $header->contact_topbar_text_color ?? '#ffffff' }};">
                                        {{ $header->contact_phone }}
                                    </a>
                                </div>
                            </div>
                        @endif
                        @if ($header->contact_email)
                            <div class="col-6 col-md-auto">
                                <div class="contact-item me-4 mb-1">
                                    <i class="fas fa-envelope me-2"
                                        style="color: {{ $header->contact_topbar_text_color ?? '#ffffff' }};"></i>
                                    <a href="mailto:{{ $header->contact_email }}"
                                        style="color: {{ $header->contact_topbar_text_color ?? '#ffffff' }};">
                                        {{ $header->contact_email }}
                                    </a>
                                </div>
                            </div>
                        @endif
                        @if ($header->contact_address)
                            <div class="col-3 col-md-auto">
                                <div class="contact-item mb-1">
                                    <i class="fas fa-map-marker-alt me-2"
                                        style="color: {{ $header->contact_topbar_text_color ?? '#ffffff' }};"></i>
                                    <span
                                        style="color: {{ $header->contact_topbar_text_color ?? '#ffffff' }}; text-decoration : underline !important;">
                                        {{ $header->contact_address }}
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        @include('layouts.nav')

        {{-- Investor Exclusives Top Bar - Only for Investment Websites --}}
        @if ($check && $check->isInvestment() && $header && $header->show_investor_exclusives)
            <div class="investor-exclusives-bar"
                style="background: {{ $header->topbar_background_color ?? '#1e3a8a' }};">
                <div class="investor-exclusives-content">
                    <a href="{{ $header->investor_exclusives_url ?? '#' }}" style="text-decoration: none;">
                        <p class="investor-exclusives-text"
                            style="color: {{ $header->topbar_text_color ?? '#ffffff' }}; font-size: 13px; padding-top: 5px; font-family: Outfit,sans-serif;text-transform: uppercase; padding-bottom: 4px;">
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
                        // For mobile, use the same base calculation but account for responsive changes
                        const totalHeightRemMobile = totalNavHeight / 16;
                        const totalHeightRemSmall = totalNavHeight / 16;

                        // Main content margin should account for investor bar if present
                        const mainContentMargin = totalWithInvestorBar / 16 - 0.3; // Extra space for clean separation

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
                            mainMargin: mainContentMargin,
                            totalHeightRem: totalHeightRem,
                            totalHeightRemMobile: totalHeightRemMobile,
                            totalHeightRemSmall: totalHeightRemSmall,
                            windowWidth: window.innerWidth
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

    <main
        style="margin-top: var(--main-content-margin-top, {{ $header &&
        $header->show_contact_topbar &&
        $check &&
        $check->isInvestment() &&
        $header->show_investor_exclusives
            ? '14.2rem'
            : ($header && $header->show_contact_topbar
                ? '10.5rem'
                : ($check && $check->isInvestment() && $header && $header->show_investor_exclusives
                    ? '10.6rem'
                    : '6.9rem')) }}); background-color: {{ $pageBackgroundColor }};"
        class="{{ $header &&
        $header->show_contact_topbar &&
        $check &&
        $check->isInvestment() &&
        $header &&
        $header->show_investor_exclusives
            ? 'with-contact-and-investor-bars'
            : ($header && $header->show_contact_topbar
                ? 'with-contact-bar'
                : ($check && $check->isInvestment() && $header && $header->show_investor_exclusives
                    ? 'with-investor-bar'
                    : '')) }}">
        <div class="container-fluid" style="background-color: {{ $pageBackgroundColor }};">
            <div class="row justify-content-center">
                @include('partials.ticket-auth-modal')
                @include('partials.investor-info-modal')
                <div class="col-12" style="padding-left: 0px !important; padding-right: 0px !important;">
                    <!-- Investment Form Container -->
                    <div class="investment-form-wrapper"
                        style="min-height: calc(100vh - 6.9rem); background-color: {{ $pageBackgroundColor }};">

                        <div class="page-wrapper"
                            style="background-color: {{ $pageBackgroundColor }}; color: {{ $pageTextColor }};"<div
                            class="global-styles w-embed">
                            <style>
                                /* Set color style to inherit */
                                .inherit-color * {
                                    color: inherit;
                                }

                                /* Focus state style for keyboard navigation for the focusable elements */
                                *[tabindex]:focus-visible,
                                input[type="file"]:focus-visible {
                                    outline: 0.125rem solid #4d65ff;
                                    outline-offset: 0.125rem;
                                }

                                /* Get rid of top margin on first element in any rich text element */
                                .w-richtext> :not(div):first-child,
                                .w-richtext>div:first-child> :first-child {
                                    margin-top: 0 !important;
                                }

                                /* Get rid of bottom margin on last element in any rich text element */
                                .w-richtext>:last-child,
                                .w-richtext ol li:last-child,
                                .w-richtext ul li:last-child {
                                    margin-bottom: 0 !important;
                                }

                                /* Prevent all click and hover interaction with an element */
                                .pointer-events-off {
                                    pointer-events: none;
                                }

                                /* Enables all click and hover interaction with an element */
                                .pointer-events-on {
                                    pointer-events: auto;
                                }

                                /* Create a class of .div-square which maintains a 1:1 dimension of a div */
                                .div-square::after {
                                    content: "";
                                    display: block;
                                    padding-bottom: 100%;
                                }

                                /* Make sure containers never lose their center alignment */
                                .container-medium,
                                .container-small,
                                .container-large {
                                    margin-right: auto !important;
                                    margin-left: auto !important;
                                }

                                /*
Make the following elements inherit typography styles from the parent and not have hardcoded values.
Important: You will not be able to style for example "All Links" in Designer with this CSS applied.
Uncomment this CSS to use it in the project. Leave this message for future hand-off.
*/
                                /*
a,
.w-input,
.w-select,
.w-tab-link,
.w-nav-link,
.w-dropdown-btn,
.w-dropdown-toggle,
.w-dropdown-link {
  color: inherit;
  text-decoration: inherit;
  font-size: inherit;
}
*/

                                /* Apply "..." after 3 lines of text */
                                .text-style-3lines {
                                    display: -webkit-box;
                                    overflow: hidden;
                                    -webkit-line-clamp: 3;
                                    -webkit-box-orient: vertical;
                                }

                                /* Apply "..." after 2 lines of text */
                                .text-style-2lines {
                                    display: -webkit-box;
                                    overflow: hidden;
                                    -webkit-line-clamp: 2;
                                    -webkit-box-orient: vertical;
                                }

                                /* Adds inline flex display */
                                .display-inlineflex {
                                    display: inline-flex;
                                }

                                /* These classes are never overwritten */
                                .hide {
                                    display: none !important;
                                }

                                @media screen and (max-width: 991px) {

                                    .hide,
                                    .hide-tablet {
                                        display: none !important;
                                    }
                                }

                                @media screen and (max-width: 767px) {
                                    .hide-mobile-landscape {
                                        display: none !important;
                                    }
                                }

                                @media screen and (max-width: 479px) {
                                    .hide-mobile {
                                        display: none !important;
                                    }
                                }

                                /* NOTE: Utility classes moved to external CSS file to prevent conflicts */
                                /* See: /investment/css/investment-utilities.css */

                                /* Apply "..." at 100% width */
                                .truncate-width {
                                    width: 100%;
                                    white-space: nowrap;
                                    overflow: hidden;
                                    text-overflow: ellipsis;
                                }

                                /* Removes native scrollbar */
                                .no-scrollbar {
                                    -ms-overflow-style: none;
                                    overflow: -moz-scrollbars-none;
                                }

                                .no-scrollbar::-webkit-scrollbar {
                                    display: none;
                                }
                            </style>
                        </div>
                        <main class="main-wrapper" style="background-color: {{ $pageBackgroundColor }}">
                            <header id="home" class="section_header3 checkout-hero">
                                <div class="padding-global z-index-2">
                                    <div class="container-2">
                                        <div class="dmr-checkout-wrapper">
                                            <div>
                                                <div class="hero-text-2 _2">
                                                    <h1 class="heading-style-h1 is-checkout"
                                                        style="color: #fff !important">
                                                        {!! $website && $website->investment_title
                                                            ? $website->investment_title
                                                            : ($setting && $setting->investment_title
                                                                ? $setting->investment_title
                                                                : '<strong style="color: #fff !important">' .
                                                                    ($setting && $setting->company_name ? $setting->company_name : 'Investment') .
                                                                    '</strong> Investment Opportunity') !!}<br />
                                                    </h1>
                                                    <div class="spacer-small"></div>
                                                    <div class="dmr-details-mobile-show">
                                                        <div class="div-block-132">
                                                            <div id="w-node-d76ce5db-1098-4f05-302a-51e614ef1974-4eda2ff4"
                                                                class="div-block-155">
                                                                <div id="w-node-f2ed6c7b-d6cb-dd1d-1486-c534a22a22d5-a22a22d5"
                                                                    class="div-block-155">
                                                                    <div
                                                                        class="dmr-common-stock dmr-larger-t text-color-white">
                                                                        <strong
                                                                            style="color: #fff !important">Investment
                                                                            Details</strong>
                                                                    </div>
                                                                    <div class="div-block-132">
                                                                        <div id="w-node-f2ed6c7b-d6cb-dd1d-1486-c534a22a22d9-a22a22d5"
                                                                            class="w-layout-layout quick-stack-5 wf-layout-layout">
                                                                            <div id="w-node-f2ed6c7b-d6cb-dd1d-1486-c534a22a22da-a22a22d5"
                                                                                class="w-layout-cell">
                                                                                <div class="div-block-67">
                                                                                    <div
                                                                                        class="dmr-details-padding no-l">
                                                                                        <div class="dmr-common-stock-2 small"
                                                                                            style="color: #fff !important">
                                                                                            {{ $website && $website->share_price_label ? $website->share_price_label : ($setting && $setting->share_price_label ? $setting->share_price_label : 'SHARE PRICE') }}
                                                                                        </div>
                                                                                        <div
                                                                                            class="dmr-common-stock-2 fixed-height">
                                                                                            <strong
                                                                                                style="color: #fff !important">${{ $website && $website->share_price ? number_format($website->share_price, 2) : ($setting && $setting->share_price ? number_format($setting->share_price, 2) : '1.00') }}
                                                                                                USD</strong>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div
                                                                                        class="dmr-details-padding no-l">
                                                                                        <div class="dmr-common-stock-2 small"
                                                                                            style="color: #fff !important">
                                                                                            MIN INVESTMENT</div>
                                                                                        <div
                                                                                            class="dmr-common-stock-2 fixed-height">
                                                                                            <strong
                                                                                                style="color: #fff !important">${{ $website && $website->min_investment ? number_format($website->min_investment, 2) : ($setting && $setting->min_investment ? number_format($setting->min_investment, 2) : '1000.00') }}
                                                                                                USD</strong>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="div-block-46 _3"><a
                                                                                            href="#"
                                                                                            class="close-2 w-inline-block">
                                                                                            <div>X</div>
                                                                                        </a>
                                                                                        <div>
                                                                                            {{ $website && $website->investment_note ? $website->investment_note : ($setting && $setting->investment_note ? $setting->investment_note : 'Minimum investment amount plus applicable transaction fees') }}
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div id="w-node-f2ed6c7b-d6cb-dd1d-1486-c534a22a22ee-a22a22d5"
                                                                                class="w-layout-cell">
                                                                                <div class="div-block-67">
                                                                                    <div
                                                                                        class="dmr-details-padding no-l">
                                                                                        <div class="dmr-common-stock-2 small"
                                                                                            style="color: #fff !important">
                                                                                            {{ $website && $website->offering_type_label ? $website->offering_type_label : ($setting && $setting->offering_type_label ? $setting->offering_type_label : 'OFFERING TYPE') }}
                                                                                        </div>
                                                                                        <div
                                                                                            class="dmr-common-stock-2 fixed-height">
                                                                                            <strong
                                                                                                style="color: #fff !important">{{ $website && $website->offering_type ? $website->offering_type : ($setting && $setting->offering_type ? $setting->offering_type : 'Equity') }}</strong>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div
                                                                                        class="dmr-details-padding no-l">
                                                                                        <div class="dmr-common-stock-2 small"
                                                                                            style="color: #fff !important">

                                                                                            {{ $website && $website->asset_type_label ? $website->asset_type_label : ($setting && $setting->asset_type_label ? $setting->asset_type_label : 'ASSET TYPE') }}
                                                                                        </div>
                                                                                        <div class="dmr-common-stock-2 fixed-height"
                                                                                            style="color: #fff !important">
                                                                                            <strong
                                                                                                style="color: #fff !important">{{ $website && $website->asset_type ? $website->asset_type : ($setting && $setting->asset_type ? $setting->asset_type : 'Common Stock') }}</strong>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="div-block-130">
                                                                        @if (($website && $website->investment_documents) || ($setting && $setting->investment_documents))
                                                                            <div
                                                                                class="investor_info_wrap text-size-xsmall link-light">
                                                                                @php $docs = $website && $website->investment_documents ? json_decode($website->investment_documents, true) : json_decode($setting->investment_documents, true); @endphp @if (is_array($docs))
                                                                                    @foreach ($docs as $doc)
                                                                                        <a aria-label="{{ $doc['name'] ?? 'Investment Document' }}"
                                                                                            href="{{ $doc['url'] ?? '#' }}"
                                                                                            target="_blank"
                                                                                            class="investor_info_link text-link-inherit">{{ $doc['name'] ?? 'Document' }}</a>
                                                                                    @endforeach
                                                                                @endif
                                                                            </div>
                                                                            @endif @if (($website && $website->investment_deadline) || ($setting && $setting->investment_deadline))
                                                                                <div
                                                                                    class="countdown_checkout_wrapper">
                                                                                    <div countdown-wrapper="1"
                                                                                        class="countdown_wrapper is_checkout">
                                                                                        <div
                                                                                            class="countdown_title is_checkout">
                                                                                            <strong>{{ $website && $website->deadline_text ? $website->deadline_text : ($setting && $setting->deadline_text ? $setting->deadline_text : 'Investment Deadline') }}</strong>
                                                                                        </div>
                                                                                        <div id="js-clock"
                                                                                            class="timer_wrap">
                                                                                            <div id="w-node-_20a0a116-730f-65a2-befe-802bf563b40a-a22a22d5"
                                                                                                class="timer_cell">
                                                                                                <div id="days"
                                                                                                    class="timer_number smaller">
                                                                                                    0</div>
                                                                                                <div
                                                                                                    class="timer_label _3">
                                                                                                    Days</div>
                                                                                            </div>
                                                                                            <div class="timer_cell">
                                                                                                <div id="hours"
                                                                                                    class="timer_number smaller">
                                                                                                    0</div>
                                                                                                <div
                                                                                                    class="timer_label _3">
                                                                                                    Hours</div>
                                                                                            </div>
                                                                                            <div
                                                                                                class="timer_cell last-m">
                                                                                                <div id="minutes"
                                                                                                    class="timer_number smaller">
                                                                                                    0</div>
                                                                                                <div
                                                                                                    class="timer_label _3">
                                                                                                    Minutes</div>
                                                                                            </div>
                                                                                            <div
                                                                                                class="timer_cell mobil-hide">
                                                                                                <div id="seconds"
                                                                                                    class="timer_number smaller">
                                                                                                    0</div>
                                                                                                <div
                                                                                                    class="timer_label _3">
                                                                                                    Seconds</div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="w-layout-grid virtuix-checkout-grid">
                                                    <!-- Custom Investment Form (replacing DealMaker React component) -->
                                                    <div
                                                        class="investment-form-container w-node-d76ce5db-1098-4f05-302a-51e614ef19a6-4eda2ff4">
                                                        <div class="investment-form-wrapper">
                                                            <h2 class="investment-form-title"
                                                                style="color: #000 !important;">
                                                                {{ $website && $website->invest_page_title ? $website->invest_page_title : ($setting && $setting->invest_page_title ? $setting->invest_page_title : 'Complete Your Investment') }}
                                                            </h2>

                                                            <!-- SSN/EIN Explanation Modal -->
                                                            <div class="modal" id="ssnExplanationModal"
                                                                tabindex="-1"
                                                                aria-labelledby="ssnExplanationModalLabel"
                                                                aria-hidden="true" data-bs-backdrop="false">
                                                                <div class="modal-dialog modal-lg">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title"
                                                                                id="ssnExplanationModalLabel"
                                                                                style="color: #000 !important;">
                                                                                Government-required identity &
                                                                                anti-fraud checks secure all
                                                                                transactions. Why Do We Need This?
                                                                            </h5>
                                                                            <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal"
                                                                                aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body"
                                                                            style="color: #000 !important; line-height: 1.6;">
                                                                            <p>Since this is a financial transaction we
                                                                                are required by regulators like the SEC
                                                                                & US Department of Treasury to perform
                                                                                AML (Anti Money Laundering) & KYC (Know
                                                                                Your Customer) verification in order to
                                                                                avoid money laundering, fraud, and
                                                                                identity theft.</p>

                                                                            <p>Our broker-dealer, DealMaker Securities,
                                                                                LLC uses a Taxpayer Identification
                                                                                Number (TIN), for example Social
                                                                                Security Number (SSN), Employment
                                                                                Identification Number (EIN), Individual
                                                                                Tax Identification Number (ITIN) to
                                                                                fulfill its responsibilities with its
                                                                                Anti-Money Laundering (AML) Program as
                                                                                required by the Bank Secrecy Act (BSA)
                                                                                and its implementing regulations and
                                                                                FINRA Rule 3310 (AML Compliance Program)
                                                                                by requesting, reviewing, and verifying
                                                                                data and documentation provided during
                                                                                securities transactions, prior to
                                                                                acceptance.</p>

                                                                            <p><strong>Here's why they are required for
                                                                                    startup investments:</strong></p>

                                                                            <div class="mb-3">
                                                                                <strong>1. Preventing Illegal
                                                                                    Activities:</strong> Money
                                                                                laundering involves the concealment or
                                                                                disguise of money derived from criminal
                                                                                origins by processing it through a
                                                                                single or series of transactions to make
                                                                                it appear as if it comes from a legal,
                                                                                legitimate source or constitute
                                                                                legitimate assets. Having a verification
                                                                                process, whereby investors are reviewed,
                                                                                checked against governmental databases,
                                                                                and all investment funds are evaluated,
                                                                                startups can feel confident they are
                                                                                protecting themselves from civil and
                                                                                criminal penalties and preventing
                                                                                terrorist financing, drug trafficking,
                                                                                tax evasion, corruption, fraud, and
                                                                                other financial crimes.
                                                                            </div>

                                                                            <div class="mb-3">
                                                                                <strong>2. Identity
                                                                                    Verification/Data:</strong> KYC
                                                                                processes help collect essential pieces
                                                                                of data and verify the identity and
                                                                                authority of the investors, ensuring
                                                                                that they are indeed who they claim to
                                                                                be and are authorized to process the
                                                                                transaction they seek to make. This
                                                                                protects against identity theft and
                                                                                fraud.
                                                                            </div>

                                                                            <div class="mb-3">
                                                                                <strong>3. Regulatory
                                                                                    Compliance:</strong> Compliance with
                                                                                AML and KYC requirements is mandatory in
                                                                                many jurisdictions. Failure to comply
                                                                                can lead to severe civil penalties,
                                                                                including heavy fines, and even criminal
                                                                                penalties.
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button"
                                                                                class="btn btn-primary"
                                                                                data-bs-dismiss="modal">I
                                                                                Understand</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Investment Amount Selection -->
                                                            <div class="investment-step" id="amount-step">
                                                                <h3>{{ $website && $website->invest_amount_title ? $website->invest_amount_title : ($setting && $setting->invest_amount_title ? $setting->invest_amount_title : 'Select Investment Amount') }}
                                                                </h3>
                                                                <div class="amount-tiers">
                                                                    @php
                                                                        // Handle JSON format for investment tiers
                                                                        $tiersData = null;

                                                                        // Debug: Check what we're working with
$investmentTiersRaw = null;

if ($website && $website->investment_tiers) {
    $investmentTiersRaw =
        $website->investment_tiers;
    $tiersData = json_decode(
        $website->investment_tiers,
        true,
    );
} elseif (
    $setting &&
    $setting->investment_tiers
) {
    $investmentTiersRaw =
        $setting->investment_tiers;
    $tiersData = json_decode(
        $setting->investment_tiers,
        true,
    );
}

// Debug output (remove this in production)
echo '<!-- DEBUG: Raw investment_tiers: ' .
    htmlspecialchars($investmentTiersRaw) .
    ' -->';
echo '<!-- DEBUG: Decoded tiersData: ' .
    htmlspecialchars(
        print_r($tiersData, true),
    ) .
    ' -->';

// Extract amounts from tier data or use defaults
$tiers = [1000, 2500, 5000, 10000]; // Default fallback

if ($tiersData && is_array($tiersData)) {
    $extractedTiers = [];
    foreach ($tiersData as $tier) {
        if (is_array($tier)) {
            // Handle object format: [{"amount": 1000}, {"amount": 2500}]
            if (isset($tier['amount'])) {
                $extractedTiers[] =
                    (float) $tier['amount'];
            }
        } elseif (is_numeric($tier)) {
            // Handle simple array format: [1000, 2500, 5000]
            $extractedTiers[] = (float) $tier;
        }
    }
    if (!empty($extractedTiers)) {
        $tiers = $extractedTiers;
    }
} elseif (
    $investmentTiersRaw &&
    is_string($investmentTiersRaw)
) {
    // Handle comma-separated string format: "1000,2500,5000,10000"
    $stringTiers = explode(
        ',',
                                                                                $investmentTiersRaw,
                                                                            );
                                                                            $extractedTiers = [];
                                                                            foreach ($stringTiers as $tier) {
                                                                                $tier = trim($tier);
                                                                                if (is_numeric($tier)) {
                                                                                    $extractedTiers[] = (float) $tier;
                                                                                }
                                                                            }
                                                                            if (!empty($extractedTiers)) {
                                                                                $tiers = $extractedTiers;
                                                                            }
                                                                        }

                                                                        $sharePrice =
                                                                            $website && $website->share_price
                                                                                ? (float) $website->share_price
                                                                                : ($setting && $setting->share_price
                                                                                    ? (float) $setting->share_price
                                                                                    : 1.0);
                                                                        $minInvestment =
                                                                            $website && $website->min_investment
                                                                                ? (float) $website->min_investment
                                                                                : ($setting && $setting->min_investment
                                                                                    ? (float) $setting->min_investment
                                                                                    : 1000);
                                                                    @endphp

                                                                    @foreach ($tiers as $tier)
                                                                        <div class="tier-option"
                                                                            data-amount="{{ $tier }}">
                                                                            <div class="tier-amount">
                                                                                {{ number_format($tier / $sharePrice) }}
                                                                                shares</div>
                                                                            <div class="tier-shares">
                                                                                ${{ $tier }}
                                                                            </div>
                                                                        </div>
                                                                    @endforeach

                                                                    <div class="custom-amount-wrapper">
                                                                        <label for="custom-amount">Custom Amount (Min:
                                                                            ${{ $minInvestment }})</label>
                                                                        <input type="number" id="custom-amount"
                                                                            min="{{ $minInvestment }}" step="1"
                                                                            placeholder="Enter amount">
                                                                        <div class="custom-shares-display"></div>
                                                                    </div>
                                                                </div>

                                                                <button class="btn-continue" id="amount-continue"
                                                                    disabled>Continue</button>
                                                            </div>

                                                            <!-- Investor Information Step -->
                                                            <div class="investment-step hidden" id="info-step">
                                                                <h3>Investor Information</h3>
                                                                <form id="investor-form">
                                                                    <div class="form-group">
                                                                        <label for="investor_type">Pick an investor
                                                                            type *</label>
                                                                        <select id="investor_type"
                                                                            name="investor_type" required>
                                                                            <option value="">Select investor type
                                                                            </option>
                                                                            <option value="individual">Myself/an
                                                                                individual</option>
                                                                            <option value="joint">Joint (more than one
                                                                                individual)</option>
                                                                            <option value="corporation">Corporation
                                                                            </option>
                                                                            <option value="trust">Trust</option>
                                                                            <option value="ira">IRA</option>
                                                                        </select>
                                                                    </div>

                                                                    <!-- Individual Investor Fields -->
                                                                    <div id="individual-fields"
                                                                        class="investor-type-fields"
                                                                        style="display: none;">
                                                                        <div class="form-group">
                                                                            <label for="investor_name">Full Name
                                                                                *</label>
                                                                            <input type="text" id="investor_name"
                                                                                name="individual_name">
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label for="date_of_birth">Date of Birth
                                                                                *</label>
                                                                            <input type="date" id="date_of_birth"
                                                                                name="date_of_birth">
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label for="ssn">Social Security
                                                                                Number *
                                                                                <a href="#"
                                                                                    class="ssn-help-link"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#ssnExplanationModal"
                                                                                    style="font-size: 12px; color: #007bff; text-decoration: none; float: right;">Why
                                                                                    do we need this?</a>
                                                                            </label>
                                                                            <input type="text" id="ssn"
                                                                                name="ssn"
                                                                                placeholder="XXX-XX-XXXX">
                                                                        </div>
                                                                    </div>

                                                                    <!-- Joint Account Fields -->
                                                                    <div id="joint-fields"
                                                                        class="investor-type-fields"
                                                                        style="display: none;">
                                                                        <div class="form-group">
                                                                            <label for="primary_name">Primary Account
                                                                                Holder Name *</label>
                                                                            <input type="text" id="primary_name"
                                                                                name="primary_name">
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label for="primary_dob">Primary Holder
                                                                                Date of Birth *</label>
                                                                            <input type="date" id="primary_dob"
                                                                                name="primary_dob">
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label for="primary_ssn">Primary Holder SSN
                                                                                *
                                                                                <a href="#"
                                                                                    class="ssn-help-link"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#ssnExplanationModal"
                                                                                    style="font-size: 12px; color: #007bff; text-decoration: none; float: right;">Why
                                                                                    do we need this?</a>
                                                                            </label>
                                                                            <input type="text" id="primary_ssn"
                                                                                name="primary_ssn"
                                                                                placeholder="XXX-XX-XXXX">
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label for="secondary_name">Secondary
                                                                                Account Holder Name *</label>
                                                                            <input type="text" id="secondary_name"
                                                                                name="secondary_name">
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label for="secondary_dob">Secondary Holder
                                                                                Date of Birth *</label>
                                                                            <input type="date" id="secondary_dob"
                                                                                name="secondary_dob">
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label for="secondary_ssn">Secondary Holder
                                                                                SSN *
                                                                                <a href="#"
                                                                                    class="ssn-help-link"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#ssnExplanationModal"
                                                                                    style="font-size: 12px; color: #007bff; text-decoration: none; float: right;">Why
                                                                                    do we need this?</a>
                                                                            </label>
                                                                            <input type="text" id="secondary_ssn"
                                                                                name="secondary_ssn"
                                                                                placeholder="XXX-XX-XXXX">
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label for="joint_type">Joint Account Type
                                                                                *</label>
                                                                            <select id="joint_type" name="joint_type">
                                                                                <option value="">Select joint
                                                                                    type</option>
                                                                                <option value="jtwros">Joint Tenants
                                                                                    with Rights of Survivorship</option>
                                                                                <option value="tenants_common">Tenants
                                                                                    in Common</option>
                                                                                <option value="community_property">
                                                                                    Community Property</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Corporation Fields -->
                                                                    <div id="corporation-fields"
                                                                        class="investor-type-fields"
                                                                        style="display: none;">
                                                                        <div class="form-group">
                                                                            <label for="corporation_name">Corporation
                                                                                Name *</label>
                                                                            <input type="text"
                                                                                id="corporation_name"
                                                                                name="corporation_name">
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label for="ein">Federal Tax ID (EIN)
                                                                                *
                                                                                <a href="#"
                                                                                    class="ssn-help-link"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#ssnExplanationModal"
                                                                                    style="font-size: 12px; color: #007bff; text-decoration: none; float: right;">Why
                                                                                    do we need this?</a>
                                                                            </label>
                                                                            <input type="text" id="ein"
                                                                                name="ein"
                                                                                placeholder="XX-XXXXXXX">
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label for="incorporation_state">State of
                                                                                Incorporation *</label>
                                                                            <input type="text"
                                                                                id="incorporation_state"
                                                                                name="incorporation_state">
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label
                                                                                for="authorized_signatory">Authorized
                                                                                Signatory Name *</label>
                                                                            <input type="text"
                                                                                id="authorized_signatory"
                                                                                name="authorized_signatory">
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label for="signatory_title">Signatory
                                                                                Title *</label>
                                                                            <input type="text" id="signatory_title"
                                                                                name="signatory_title"
                                                                                placeholder="e.g., CEO, President">
                                                                        </div>
                                                                    </div>

                                                                    <!-- Trust Fields -->
                                                                    <div id="trust-fields"
                                                                        class="investor-type-fields"
                                                                        style="display: none;">
                                                                        <div class="form-group">
                                                                            <label for="trust_name">Trust Name
                                                                                *</label>
                                                                            <input type="text" id="trust_name"
                                                                                name="trust_name">
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label for="trust_date">Trust Date
                                                                                *</label>
                                                                            <input type="date" id="trust_date"
                                                                                name="trust_date">
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label for="trustee_name">Trustee Name
                                                                                *</label>
                                                                            <input type="text" id="trustee_name"
                                                                                name="trustee_name">
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label for="trustee_ssn">Trustee SSN/EIN *
                                                                                <a href="#"
                                                                                    class="ssn-help-link"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#ssnExplanationModal"
                                                                                    style="font-size: 12px; color: #007bff; text-decoration: none; float: right;">Why
                                                                                    do we need this?</a>
                                                                            </label>
                                                                            <input type="text" id="trustee_ssn"
                                                                                name="trustee_ssn"
                                                                                placeholder="XXX-XX-XXXX or XX-XXXXXXX">
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label for="trust_type">Trust Type
                                                                                *</label>
                                                                            <select id="trust_type" name="trust_type">
                                                                                <option value="">Select trust
                                                                                    type</option>
                                                                                <option value="revocable">Revocable
                                                                                    Trust</option>
                                                                                <option value="irrevocable">Irrevocable
                                                                                    Trust</option>
                                                                                <option value="charitable">Charitable
                                                                                    Trust</option>
                                                                                <option value="other">Other</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <!-- IRA Fields -->
                                                                    <div id="ira-fields" class="investor-type-fields"
                                                                        style="display: none;">
                                                                        <div class="form-group">
                                                                            <label for="ira_holder_name">IRA Account
                                                                                Holder Name *</label>
                                                                            <input type="text" id="ira_holder_name"
                                                                                name="ira_holder_name">
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label for="ira_holder_dob">Account Holder
                                                                                Date of Birth *</label>
                                                                            <input type="date" id="ira_holder_dob"
                                                                                name="ira_holder_dob">
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label for="ira_holder_ssn">Account Holder
                                                                                SSN *
                                                                                <a href="#"
                                                                                    class="ssn-help-link"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#ssnExplanationModal"
                                                                                    style="font-size: 12px; color: #007bff; text-decoration: none; float: right;">Why
                                                                                    do we need this?</a>
                                                                            </label>
                                                                            <input type="text" id="ira_holder_ssn"
                                                                                name="ira_holder_ssn"
                                                                                placeholder="XXX-XX-XXXX">
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label for="ira_type">IRA Type *</label>
                                                                            <select id="ira_type" name="ira_type">
                                                                                <option value="">Select IRA type
                                                                                </option>
                                                                                <option value="traditional">Traditional
                                                                                    IRA</option>
                                                                                <option value="roth">Roth IRA
                                                                                </option>
                                                                                <option value="sep">SEP IRA</option>
                                                                                <option value="simple">SIMPLE IRA
                                                                                </option>
                                                                            </select>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label for="custodian_name">IRA Custodian
                                                                                Name *</label>
                                                                            <input type="text" id="custodian_name"
                                                                                name="custodian_name">
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label for="ira_account_number">IRA Account
                                                                                Number *</label>
                                                                            <input type="text"
                                                                                id="ira_account_number"
                                                                                name="ira_account_number">
                                                                        </div>
                                                                    </div>

                                                                    <!-- Common Fields for All Types -->
                                                                    <div class="common-fields">
                                                                        <!-- Hidden field for main investor name (populated by JavaScript) -->
                                                                        <input type="hidden" id="main_investor_name"
                                                                            name="investor_name">

                                                                        <div class="form-group">
                                                                            <label for="investor_email">Email Address
                                                                                *</label>
                                                                            <input type="email" id="investor_email"
                                                                                name="investor_email" required>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label for="investor_phone">Phone
                                                                                Number</label>
                                                                            <input type="tel" id="investor_phone"
                                                                                name="investor_phone">
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label for="investment_amount">Investment
                                                                                Amount *</label>
                                                                            <input type="number"
                                                                                id="investment_amount"
                                                                                name="investment_amount" readonly>
                                                                        </div>

                                                                        <!-- Complete Address Fields with Country/State Logic -->
                                                                        <div data-testid="address-inputs"
                                                                            class="address-fields-container">
                                                                            <div class="form-group">
                                                                                <label for="addressLine1">Address Line
                                                                                    1 *</label>
                                                                                <input id="addressLine1"
                                                                                    name="address"
                                                                                    class="form-control"
                                                                                    placeholder="Address"
                                                                                    data-testid="input-addressLine1"
                                                                                    autocomplete="off"
                                                                                    inputmode="text" type="text"
                                                                                    required>
                                                                            </div>

                                                                            <div class="form-group">
                                                                                <label for="addressLine2">Address Line
                                                                                    2</label>
                                                                                <input id="addressLine2"
                                                                                    name="apartment"
                                                                                    class="form-control"
                                                                                    placeholder="Apartment, suite, etc. (optional)"
                                                                                    data-testid="input-addressLine2"
                                                                                    autocomplete="address-line2"
                                                                                    inputmode="text" type="text">
                                                                            </div>

                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            for="country">Country/Region
                                                                                            *</label>
                                                                                        <select class="form-control"
                                                                                            name="country"
                                                                                            id="country" required
                                                                                            aria-label="Country/Region">
                                                                                            <option value=""
                                                                                                disabled selected
                                                                                                hidden>Select Country
                                                                                            </option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label for="state"
                                                                                            id="state-label">State
                                                                                            *</label>
                                                                                        <select class="form-control"
                                                                                            name="state"
                                                                                            id="state" required
                                                                                            aria-label="State">
                                                                                            <option value=""
                                                                                                disabled selected
                                                                                                hidden>Select State
                                                                                            </option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label for="city">City
                                                                                            *</label>
                                                                                        <input id="city"
                                                                                            name="city"
                                                                                            class="form-control"
                                                                                            placeholder="City"
                                                                                            data-testid="input-city"
                                                                                            autocomplete="address-level2"
                                                                                            inputmode="text"
                                                                                            type="text" required>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-6">
                                                                                    <div class="form-group">
                                                                                        <label for="zipcode"
                                                                                            id="zipcode-label">ZIP Code
                                                                                            *</label>
                                                                                        <input id="zipcode"
                                                                                            name="postalCode"
                                                                                            class="form-control"
                                                                                            placeholder="ZIP code"
                                                                                            data-testid="input-postalCode"
                                                                                            autocomplete="postal-code"
                                                                                            inputmode="text"
                                                                                            type="text" required>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group checkbox-group">
                                                                            <label style="color: #000 !important;">
                                                                                <input type="checkbox"
                                                                                    id="terms_accepted"
                                                                                    name="terms_accepted" required>
                                                                                By proceeding, I acknowledge that I have
                                                                                read, understood, and agree to be bound
                                                                                by the Terms and Conditions, and I
                                                                                expressly consent to receive all
                                                                                investment-related disclosures, notices,
                                                                                updates, and other
                                                                                communications electronically.
                                                                            </label>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-actions">
                                                                        <button type="button" class="btn-back"
                                                                            id="info-back">Back</button>
                                                                        <button type="submit"
                                                                            class="btn-submit">Continue</button>
                                                                    </div>
                                                                </form>
                                                            </div>

                                                            <!-- Success Step -->
                                                            <div class="investment-step hidden" id="success-step">
                                                                <div class="success-message">
                                                                    <h3>Investment Submitted Successfully!</h3>
                                                                    <p>Thank you for your investment. You will receive a
                                                                        confirmation email shortly.</p>
                                                                    <div class="investment-summary">
                                                                        <div class="summary-item">
                                                                            <span class="label">Investment
                                                                                Amount:</span>
                                                                            <span class="value"
                                                                                id="final-amount"></span>
                                                                        </div>
                                                                        <div class="summary-item">
                                                                            <span class="label">Number of
                                                                                Shares:</span>
                                                                            <span class="value"
                                                                                id="final-shares"></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="w-node-d76ce5db-1098-4f05-302a-51e614ef19a7-4eda2ff4"
                                                        class="dmr-investment-details">
                                                        <div class="w-layout-grid grid-35">
                                                            <div id="w-node-f2ed6c7b-d6cb-dd1d-1486-c534a22a22d5-a22a22d5"
                                                                class="div-block-155">
                                                                <div
                                                                    class="dmr-common-stock dmr-larger-t text-color-white">
                                                                    <strong style="color: #fff !important">Investment
                                                                        Details</strong>
                                                                </div>
                                                                <div class="div-block-132">
                                                                    <div id="w-node-f2ed6c7b-d6cb-dd1d-1486-c534a22a22d9-a22a22d5"
                                                                        class="w-layout-layout quick-stack-5 wf-layout-layout">
                                                                        <div id="w-node-f2ed6c7b-d6cb-dd1d-1486-c534a22a22da-a22a22d5"
                                                                            class="w-layout-cell">
                                                                            <div class="div-block-67">
                                                                                <div class="dmr-details-padding no-l">
                                                                                    <div class="dmr-common-stock-2 small"
                                                                                        style="color: #fff !important">
                                                                                        {{ $website && $website->share_price_label ? $website->share_price_label : ($setting && $setting->share_price_label ? $setting->share_price_label : 'SHARE PRICE') }}
                                                                                    </div>
                                                                                    <div
                                                                                        class="dmr-common-stock-2 fixed-height">
                                                                                        <strong
                                                                                            style="color: #fff !important">${{ $website && $website->share_price ? number_format($website->share_price, 2) : ($setting && $setting->share_price ? number_format($setting->share_price, 2) : '1.00') }}
                                                                                            USD</strong>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="dmr-details-padding no-l">
                                                                                    <div class="dmr-common-stock-2 small"
                                                                                        style="color: #fff !important">
                                                                                        MIN INVESTMENT</div>
                                                                                    <div
                                                                                        class="dmr-common-stock-2 fixed-height">
                                                                                        <strong
                                                                                            style="color: #fff !important">${{ $website && $website->min_investment ? number_format($website->min_investment, 2) : ($setting && $setting->min_investment ? number_format($setting->min_investment, 2) : '1000.00') }}
                                                                                            USD</strong>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="div-block-46 _3"><a
                                                                                        href="#"
                                                                                        class="close-2 w-inline-block">
                                                                                        <div>X</div>
                                                                                    </a>
                                                                                    <div>
                                                                                        {{ $website && $website->investment_note ? $website->investment_note : ($setting && $setting->investment_note ? $setting->investment_note : 'Minimum investment amount plus applicable transaction fees') }}
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div id="w-node-f2ed6c7b-d6cb-dd1d-1486-c534a22a22ee-a22a22d5"
                                                                            class="w-layout-cell">
                                                                            <div class="div-block-67">
                                                                                <div class="dmr-details-padding no-l">
                                                                                    <div class="dmr-common-stock-2 small"
                                                                                        style="color: #fff !important">
                                                                                        {{ $website && $website->offering_type_label ? $website->offering_type_label : ($setting && $setting->offering_type_label ? $setting->offering_type_label : 'OFFERING TYPE') }}
                                                                                    </div>
                                                                                    <div
                                                                                        class="dmr-common-stock-2 fixed-height">
                                                                                        <strong
                                                                                            style="color: #fff !important">{{ $website && $website->offering_type ? $website->offering_type : ($setting && $setting->offering_type ? $setting->offering_type : 'Equity') }}</strong>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="dmr-details-padding no-l">
                                                                                    <div class="dmr-common-stock-2 small"
                                                                                        style="color: #fff !important">
                                                                                        {{ $website && $website->asset_type_label ? $website->asset_type_label : ($setting && $setting->asset_type_label ? $setting->asset_type_label : 'ASSET TYPE') }}
                                                                                    </div>
                                                                                    <div
                                                                                        class="dmr-common-stock-2 fixed-height">
                                                                                        <strong
                                                                                            style="color: #fff !important">{{ $website && $website->asset_type ? $website->asset_type : ($setting && $setting->asset_type ? $setting->asset_type : 'Common Stock') }}</strong>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="div-block-130">
                                                                    @if (($website && $website->investment_documents) || ($setting && $setting->investment_documents))
                                                                        <div
                                                                            class="investor_info_wrap text-size-xsmall link-light">
                                                                            @php $docs = $website && $website->investment_documents ? json_decode($website->investment_documents, true) : json_decode($setting->investment_documents, true); @endphp @if (is_array($docs))
                                                                                @foreach ($docs as $doc)
                                                                                    <a aria-label="{{ $doc['name'] ?? 'Investment Document' }}"
                                                                                        href="{{ $doc['url'] ?? '#' }}"
                                                                                        target="_blank"
                                                                                        class="investor_info_link text-link-inherit">{{ $doc['name'] ?? 'Document' }}</a>
                                                                                @endforeach
                                                                            @endif
                                                                        </div>
                                                                        @endif @if (($website && $website->investment_deadline) || ($setting && $setting->investment_deadline))
                                                                            <div class="countdown_checkout_wrapper">
                                                                                <div countdown-wrapper="1"
                                                                                    class="countdown_wrapper is_checkout">
                                                                                    <div
                                                                                        class="countdown_title is_checkout">
                                                                                        <strong>{{ $website && $website->deadline_text ? $website->deadline_text : ($setting && $setting->deadline_text ? $setting->deadline_text : 'Investment Deadline') }}</strong>
                                                                                    </div>
                                                                                    <div id="js-clock"
                                                                                        class="timer_wrap">
                                                                                        <div id="w-node-_20a0a116-730f-65a2-befe-802bf563b40a-a22a22d5"
                                                                                            class="timer_cell">
                                                                                            <div id="days"
                                                                                                class="timer_number smaller">
                                                                                                0</div>
                                                                                            <div
                                                                                                class="timer_label _3">
                                                                                                Days</div>
                                                                                        </div>
                                                                                        <div class="timer_cell">
                                                                                            <div id="hours"
                                                                                                class="timer_number smaller">
                                                                                                0</div>
                                                                                            <div
                                                                                                class="timer_label _3">
                                                                                                Hours</div>
                                                                                        </div>
                                                                                        <div class="timer_cell last-m">
                                                                                            <div id="minutes"
                                                                                                class="timer_number smaller">
                                                                                                0</div>
                                                                                            <div
                                                                                                class="timer_label _3">
                                                                                                Minutes</div>
                                                                                        </div>
                                                                                        <div
                                                                                            class="timer_cell mobil-hide">
                                                                                            <div id="seconds"
                                                                                                class="timer_number smaller">
                                                                                                0</div>
                                                                                            <div
                                                                                                class="timer_label _3">
                                                                                                Seconds</div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                </div>
                                                            </div>
                                                            <div class="dmr-details-padding last-list">
                                                                <div class="dmr-common-stock text-color-white"><strong
                                                                        style="color: #fff !important">Additional
                                                                        Information</strong></div>
                                                                <ul role="list" class="list-3"></ul>
                                                                <div class="disclaimer-dmr"
                                                                    style="color: #fff !important">
                                                                    {!! $website && $website->additional_information
                                                                        ? $website->additional_information
                                                                        : ($setting && $setting->additional_information
                                                                            ? $setting->additional_information
                                                                            : 'Investment details and disclosures are available in the offering documents.') !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </header>
                        </main>
                        {{-- Use the new dynamic footer for all website types --}}
                        @if ($footer && $footer->status == 1)
                            @include('layouts.new-footer')
                        @elseif ($footer && $footer->status == 1)
                            {{-- Original footer for non-investment websites --}}
                            <footer class="standard-client-footer text-white bg-primary" data-footer=""
                                style="background-color: {{ $footer->background }} !important; ">
                                <div class="container">

                                    <p class="lead text-center pt-4" style="color: {{ $footer->color }} !important">
                                        {{ $footer->message }}
                                    </p>
                                    @if ($footer->menu == 1)
                                        <div class="nav justify-content-center">
                                            @foreach ($check->pages->sortBy('position') as $item)
                                                @if ($item->status == 1 && $item->show_in_menu)
                                                    <div class="nav-item">
                                                        <a class="nav-link active"
                                                            href="/page/{{ str_replace(' ', '-', strtolower($item->name)) }}"
                                                            style="color:{{ $footer->color }} !important"
                                                            aria-current="page">
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
                                                        <i class="fa-brands fa-facebook fa-fw" role="img"
                                                            aria-hidden="true"
                                                            style="color: {{ $footer->color }} !important"></i>
                                                        <span class="visually-hidden">facebook</span>
                                                    </a>
                                                </li>
                                            @endif

                                            @if ($footer->instagram)
                                                <li class="nav-item">
                                                    <a href="{{ $footer->instagram }}" target="_blank">
                                                        <i class="fa-brands fa-instagram fa-fw" role="img"
                                                            aria-hidden="true"
                                                            style="color: {{ $footer->color }} !important"></i>
                                                        <span class="visually-hidden">instagram</span>
                                                    </a>
                                                </li>
                                            @endif

                                            @if ($footer->linkedin)
                                                <li class="nav-item">
                                                    <a href="{{ $footer->linkedin }}" target="_blank">
                                                        <i class="fa-brands fa-linkedin fa-fw" role="img"
                                                            aria-hidden="true"
                                                            style="color: {{ $footer->color }} !important"></i>
                                                        <span class="visually-hidden">linkedin</span>
                                                    </a>
                                                </li>
                                            @endif

                                            @if ($footer->pinterest)
                                                <li class="nav-item">
                                                    <a href="{{ $footer->pinterest }}" target="_blank">
                                                        <i class="fa-brands fa-pinterest fa-fw" role="img"
                                                            aria-hidden="true"
                                                            style="color: {{ $footer->color }} !important"></i>
                                                        <span class="visually-hidden">pinterest</span>
                                                    </a>
                                                </li>
                                            @endif

                                            @if ($footer->x)
                                                <li class="nav-item">
                                                    <a href="{{ $footer->x }}" target="_blank">
                                                        <i class="fa-brands fa-x-twitter fa-fw" role="img"
                                                            aria-hidden="true"
                                                            style="color: {{ $footer->color }} !important"></i>
                                                        <span class="visually-hidden">x</span>
                                                    </a>
                                                </li>
                                            @endif

                                            @if ($footer->youtube)
                                                <li class="nav-item">
                                                    <a href="{{ $footer->youtube }}" target="_blank">
                                                        <i class="fa-brands fa-youtube fa-fw" role="img"
                                                            aria-hidden="true"
                                                            style="color: {{ $footer->color }} !important"></i>
                                                        <span class="visually-hidden">youtube</span>
                                                    </a>
                                                </li>
                                            @endif

                                            @if ($footer->blue_sky)
                                                <li class="nav-item">
                                                    <a href="{{ $footer->blue_sky }}" target="_blank">
                                                        <i class="fa-solid fa-cloud fa-fw" role="img"
                                                            aria-hidden="true"
                                                            style="color: {{ $footer->color }} !important"></i>
                                                        <span class="visually-hidden">blue sky</span>
                                                    </a>
                                                </li>
                                            @endif

                                            @if ($footer->tiktok)
                                                <li class="nav-item">
                                                    <a href="{{ $footer->tiktok }}" target="_blank">
                                                        <i class="fa-brands fa-tiktok fa-fw" role="img"
                                                            aria-hidden="true"
                                                            style="color: {{ $footer->color }} !important"></i>
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
                                            <ul
                                                style="display: inline-flex; list-style: none; margin-left: 0px; margin-top: 20px; margin-bottom: 5px;">
                                                <li style="margin-right: 1rem;">
                                                    <a style="color: #1773b0; text-decoration: underline;"
                                                        href="/page/{{ str_replace(' ', '-', strtolower($setting->refund ? $setting->refund_page->name : '#')) }}">Refund
                                                        Policy</a>
                                                </li>
                                                <li style="margin-right: 1rem;">
                                                    <a style="color: #1773b0; text-decoration: underline;"
                                                        href="/page/{{ str_replace(' ', '-', strtolower($setting->privacy ? $setting->privacy_page->name : '#')) }}">Privacy
                                                        Policy</a>
                                                </li>
                                                <li style="margin-right: 1rem;">
                                                    <a style="color: #1773b0; text-decoration: underline;"
                                                        href="/page/{{ str_replace(' ', '-', strtolower($setting->terms ? $setting->terms_page->name : '#')) }}">Terms
                                                        of service</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                @endif
                            </footer>
                        @endif



                        {{-- <div class="w-layout-grid footer2_legal-list">
                            <div class="w-layout-grid grid-4">
                                <div class="w-layout-grid grid-2">
                                    <div id="w-node-_09590d8d-0d99-05d6-cbe7-c946f15c2b26-f15c2b06"
                                        class="footer-contact">
                                        <div class="w-embed"><svg xmlns="http://www.w3.org/2000/svg" height="32"
                                                width="32"
                                                viewBox="0 0 512 512"><!--!Font Awesome Pro 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2024 Fonticons, Inc.-->
                                                <path fill="currentColor"
                                                    d="M64 96c-17.7 0-32 14.3-32 32v39.9L227.6 311.3c16.9 12.4 39.9 12.4 56.8 0L480 167.9V128c0-17.7-14.3-32-32-32H64zM32 207.6V384c0 17.7 14.3 32 32 32H448c17.7 0 32-14.3 32-32V207.6L303.3 337.1c-28.2 20.6-66.5 20.6-94.6 0L32 207.6zM0 128C0 92.7 28.7 64 64 64H448c35.3 0 64 28.7 64 64V384c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V128z" />
                                            </svg></div>
                                        <div></div>
                                        <div class="reason-text">
                                            <h3 class="heading-style-h5-2"><a href="#"
                                                    aria-label="Mail {{ $setting && $setting->company_name ? $setting->company_name : 'Company' }}"
                                                    class="link-2 text-color-white">{{ $setting && $setting->company_email ? $setting->company_email : 'invest@company.com' }}</a>
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                <div id="w-node-_09590d8d-0d99-05d6-cbe7-c946f15c2b2e-f15c2b06"
                                    class="w-layout-grid footer4_social-list">
                                    @if ($setting && $setting->facebook_url)
                                        <a aria-label="Go to Facebook" href="{{ $setting->facebook_url }}"
                                            target="_blank" class="footer4_social-link w-inline-block">
                                        @else
                                            <a aria-label="Go to Facebook" href="#"
                                                class="footer4_social-link w-inline-block" style="display: none;">
                                    @endif
                                    <div class="icon-embed-xsmall w-embed">
                                        <svg width="100%" height="100%" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M22 12.0611C22 6.50451 17.5229 2 12 2C6.47715 2 2 6.50451 2 12.0611C2 17.0828 5.65684 21.2452 10.4375 22V14.9694H7.89844V12.0611H10.4375V9.84452C10.4375 7.32296 11.9305 5.93012 14.2146 5.93012C15.3088 5.93012 16.4531 6.12663 16.4531 6.12663V8.60261H15.1922C13.95 8.60261 13.5625 9.37822 13.5625 10.1739V12.0611H16.3359L15.8926 14.9694H13.5625V22C18.3432 21.2452 22 17.083 22 12.0611Z"
                                                fill="CurrentColor" />
                                        </svg>
                                    </div></a>
                                    @if ($setting && $setting->instagram_url)
                                        <a aria-label="Go to Instagram" href="{{ $setting->instagram_url }}"
                                            target="_blank" class="footer4_social-link w-inline-block">
                                        @else
                                            <a aria-label="Go to Instagram" href="#"
                                                class="footer4_social-link w-inline-block" style="display: none;">
                                    @endif
                                    <div class="icon-embed-xsmall w-embed">
                                        <svg width="100%" height="100%" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M16 3H8C5.23858 3 3 5.23858 3 8V16C3 18.7614 5.23858 21 8 21H16C18.7614 21 21 18.7614 21 16V8C21 5.23858 18.7614 3 16 3ZM19.25 16C19.2445 17.7926 17.7926 19.2445 16 19.25H8C6.20735 19.2445 4.75549 17.7926 4.75 16V8C4.75549 6.20735 6.20735 4.75549 8 4.75H16C17.7926 4.75549 19.2445 6.20735 19.25 8V16ZM16.75 8.25C17.3023 8.25 17.75 7.80228 17.75 7.25C17.75 6.69772 17.3023 6.25 16.75 6.25C16.1977 6.25 15.75 6.69772 15.75 7.25C15.75 7.80228 16.1977 8.25 16.75 8.25ZM12 7.5C9.51472 7.5 7.5 9.51472 7.5 12C7.5 14.4853 9.51472 16.5 12 16.5C14.4853 16.5 16.5 14.4853 16.5 12C16.5027 10.8057 16.0294 9.65957 15.1849 8.81508C14.3404 7.97059 13.1943 7.49734 12 7.5ZM9.25 12C9.25 13.5188 10.4812 14.75 12 14.75C13.5188 14.75 14.75 13.5188 14.75 12C14.75 10.4812 13.5188 9.25 12 9.25C10.4812 9.25 9.25 10.4812 9.25 12Z"
                                                fill="CurrentColor" />
                                        </svg>
                                    </div></a>
                                    @if ($setting && $setting->twitter_url)
                                        <a aria-label="Go to X" href="{{ $setting->twitter_url }}" target="_blank"
                                            class="footer4_social-link w-inline-block">
                                        @else
                                            <a aria-label="Go to X" href="#"
                                                class="footer4_social-link w-inline-block" style="display: none;">
                                    @endif
                                    <div class="icon-embed-xsmall w-embed"><svg width="100%" height="100%"
                                            viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M17.1761 4H19.9362L13.9061 10.7774L21 20H15.4456L11.0951 14.4066L6.11723 20H3.35544L9.80517 12.7508L3 4H8.69545L12.6279 9.11262L17.1761 4ZM16.2073 18.3754H17.7368L7.86441 5.53928H6.2232L16.2073 18.3754Z"
                                                fill="CurrentColor" />
                                        </svg></div></a>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
        </div>
        </footer>
        </div>

        {{-- Replace external scripts with local jQuery --}}
        <script src="{{ asset('investment/js/jquery-3.5.1.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('investment/js/error-handler.js') }}" type="text/javascript"></script>
        {{-- 
NOTE: External Death & Co scripts removed and replaced with local functionality
<script src="https://cdn.prod.website-files.com/65bbbcaef2927fbb7ef5844d/js/death-co-second-version.schunk.36b8fb49256177c8.js" type="text/javascript"></script>
<script src="https://cdn.prod.website-files.com/65bbbcaef2927fbb7ef5844d/js/death-co-second-version.schunk.6b1166717f10a265.js" type="text/javascript"></script>
<script src="https://cdn.prod.website-files.com/65bbbcaef2927fbb7ef5844d/js/death-co-second-version.schunk.05120ed059607970.js" type="text/javascript"></script>
<script src="https://cdn.prod.website-files.com/65bbbcaef2927fbb7ef5844d/js/death-co-second-version.131f10d9.67664f9d2286fe2e.js" type="text/javascript"></script>
--}}

        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-W7328S2C" height="0"
                width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->

        <script>
            // COUNTDOWN [UPDATED ON 07/2025]
            // Set the date we're counting down to
            const countDownDate = new Date("JULY 22, 2025 23:59:59 PDT").getTime();
            const countdownWrappers = [...document.querySelectorAll('[countdown-wrapper]')];

            countdownWrappers.forEach(wrapper => {

                // Update the count down every 1 second
                const x = setInterval(function() {

                    // Get today's date and time
                    const now = new Date().getTime();

                    // Find the distance between now and the count down date
                    const distance = countDownDate - now;

                    // Time calculations for days, hours, minutes and seconds
                    let days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    let seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    // If the countdown is over
                    if (distance < 0) {
                        clearInterval(x);
                        wrapper.querySelector("#ended") && (wrapper.querySelector("#ended").innerHTML =
                            "Campaign Ended");
                        days = 0;
                        hours = 0;
                        minutes = 0;
                        seconds = 0;

                        // Hide the wrapper when the countdown ends.
                        wrapper.style.display = 'none';

                    }

                    // Output the result in an element with id="demo"

                    wrapper.querySelector("#days").innerHTML = days;
                    wrapper.querySelector("#hours").innerHTML = hours;
                    wrapper.querySelector("#minutes").innerHTML = minutes;
                    wrapper.querySelector("#seconds").innerHTML = seconds;

                }, 1000);

            });
        </script>

        {{-- 
NOTE: External DealMaker Utils script removed - functionality integrated locally
--}}

        <!-- Custom Investment Form JavaScript -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Investment form configuration
                const config = {
                    sharePrice: {{ $website && $website->share_price ? $website->share_price : ($setting && $setting->share_price ? $setting->share_price : 1.0) }},
                    minInvestment: {{ $website && $website->min_investment ? $website->min_investment : ($setting && $setting->min_investment ? $setting->min_investment : 1000) }},
                    companyName: '{{ $setting && $setting->company_name ? addslashes($setting->company_name) : 'Investment Platform' }}'
                };

                let selectedAmount = {{ isset($amount) && !empty($amount) ? $amount : 'null' }};

                // DOM elements
                const amountStep = document.getElementById('amount-step');
                const infoStep = document.getElementById('info-step');
                const successStep = document.getElementById('success-step');
                const tierOptions = document.querySelectorAll('.tier-option');
                const customAmountInput = document.getElementById('custom-amount');
                const customSharesDisplay = document.querySelector('.custom-shares-display');
                const amountContinueBtn = document.getElementById('amount-continue');
                const investmentAmountField = document.getElementById('investment_amount');
                const investorForm = document.getElementById('investor-form');
                const backBtn = document.getElementById('info-back');

                // Initialize with prefilled amount if available
                if (selectedAmount) {
                    updateSelectedAmount(selectedAmount);
                    showStep('info');
                }

                // Tier selection
                tierOptions.forEach(option => {
                    option.addEventListener('click', function() {
                        const amount = parseInt(this.dataset.amount);
                        selectTier(this, amount);
                    });
                });

                // Custom amount input
                customAmountInput.addEventListener('input', function() {
                    const amount = parseFloat(this.value);
                    if (amount >= config.minInvestment) {
                        clearTierSelections();
                        updateSelectedAmount(amount);
                        updateCustomShares(amount);
                    } else {
                        selectedAmount = null;
                        customSharesDisplay.textContent = '';
                        amountContinueBtn.disabled = true;
                    }
                });

                // Continue to info step
                amountContinueBtn.addEventListener('click', function() {
                    if (selectedAmount) {
                        showStep('info');
                    }
                });

                // Back to amount step
                backBtn.addEventListener('click', function() {
                    showStep('amount');
                });

                // Form submission
                investorForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    submitInvestment();
                });

                // Investor type handling
                const investorTypeSelect = document.getElementById('investor_type');
                const investorTypeFields = document.querySelectorAll('.investor-type-fields');

                investorTypeSelect.addEventListener('change', function() {
                    const selectedType = this.value;

                    // Hide all investor type fields first
                    investorTypeFields.forEach(field => {
                        field.style.display = 'none';
                        // Clear required attributes from hidden fields
                        const inputs = field.querySelectorAll('input, select');
                        inputs.forEach(input => {
                            input.removeAttribute('required');
                        });
                    });

                    // Show the selected investor type fields
                    if (selectedType) {
                        const selectedFields = document.getElementById(selectedType + '-fields');
                        if (selectedFields) {
                            selectedFields.style.display = 'block';
                            // Add required attributes to visible fields
                            const requiredInputs = selectedFields.querySelectorAll('input, select');
                            requiredInputs.forEach(input => {
                                if (input.type !== 'hidden') {
                                    input.setAttribute('required', 'required');
                                }
                            });
                        }
                    }

                    // Update the main investor_name field based on type
                    updateInvestorNameField(selectedType);
                });

                function updateInvestorNameField(investorType) {
                    // Set the main investor_name field based on the selected type and its specific fields
                    const mainNameField = document.getElementById('main_investor_name');
                    if (!mainNameField) return;

                    switch (investorType) {
                        case 'individual':
                            const individualName = document.getElementById('investor_name');
                            if (individualName && individualName.value) {
                                mainNameField.value = individualName.value;
                            }
                            break;
                        case 'joint':
                            const primaryName = document.getElementById('primary_name');
                            const secondaryName = document.getElementById('secondary_name');
                            if (primaryName && secondaryName) {
                                mainNameField.value = `${primaryName.value} & ${secondaryName.value}`;
                            }
                            break;
                        case 'corporation':
                            const corpName = document.getElementById('corporation_name');
                            if (corpName && corpName.value) {
                                mainNameField.value = corpName.value;
                            }
                            break;
                        case 'trust':
                            const trustName = document.getElementById('trust_name');
                            if (trustName && trustName.value) {
                                mainNameField.value = trustName.value;
                            }
                            break;
                        case 'ira':
                            const iraHolderName = document.getElementById('ira_holder_name');
                            if (iraHolderName && iraHolderName.value) {
                                mainNameField.value = `${iraHolderName.value} (IRA)`;
                            }
                            break;
                    }
                }

                // Add event listeners to update main name field when specific fields change
                document.addEventListener('input', function(e) {
                    const selectedType = investorTypeSelect.value;
                    if (!selectedType) return;

                    const fieldId = e.target.id;
                    const relevantFields = {
                        'individual': ['investor_name'],
                        'joint': ['primary_name', 'secondary_name'],
                        'corporation': ['corporation_name'],
                        'trust': ['trust_name'],
                        'ira': ['ira_holder_name']
                    };

                    if (relevantFields[selectedType] && relevantFields[selectedType].includes(fieldId)) {
                        updateInvestorNameField(selectedType);
                    }
                });

                // Functions
                function selectTier(element, amount) {
                    clearTierSelections();
                    element.classList.add('selected');
                    customAmountInput.value = '';
                    customSharesDisplay.textContent = '';
                    updateSelectedAmount(amount);
                }

                function clearTierSelections() {
                    tierOptions.forEach(option => option.classList.remove('selected'));
                }

                function updateSelectedAmount(amount) {
                    selectedAmount = amount;
                    amountContinueBtn.disabled = false;

                    // Update both the visible readonly field and ensure form data is set
                    if (investmentAmountField) {
                        investmentAmountField.value = amount;
                    }

                    // Also update any hidden fields or other amount inputs
                    const allAmountFields = document.querySelectorAll('input[name="investment_amount"]');
                    allAmountFields.forEach(field => {
                        field.value = amount;
                    });

                    // Update button text
                    const shares = Math.floor(amount / config.sharePrice);
                    amountContinueBtn.textContent =
                        `Continue with $${amount.toLocaleString()} (${shares.toLocaleString()} shares)`;
                }

                function updateCustomShares(amount) {
                    const shares = Math.floor(amount / config.sharePrice);
                    customSharesDisplay.textContent =
                        `≈ ${shares.toLocaleString()} shares at $${config.sharePrice} per share`;
                }

                function showStep(step) {
                    // Hide all steps
                    amountStep.classList.add('hidden');
                    infoStep.classList.add('hidden');
                    successStep.classList.add('hidden');

                    // Show selected step
                    switch (step) {
                        case 'amount':
                            amountStep.classList.remove('hidden');
                            break;
                        case 'info':
                            infoStep.classList.remove('hidden');
                            break;
                        case 'success':
                            successStep.classList.remove('hidden');
                            break;
                    }
                }

                async function submitInvestment() {
                    // Make sure investor name field is properly populated before submission
                    const selectedType = investorTypeSelect.value;
                    if (selectedType) {
                        updateInvestorNameField(selectedType);
                    }

                    const formData = new FormData(investorForm);
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    // Add CSRF token
                    formData.append('_token', csrfToken);

                    // Show loading state
                    const submitBtn = investorForm.querySelector('button[type="submit"]');
                    const originalText = submitBtn.textContent;
                    submitBtn.textContent = 'Checking authentication...';
                    submitBtn.disabled = true;

                    try {
                        // Check if user is authenticated
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

                            // Store form data in global variable for later submission
                            window._investmentFormData = formData;
                            window._investmentOriginalBtn = {
                                submitBtn,
                                originalText
                            };

                            // Open auth modal
                            if (typeof setAuthMode === 'function') {
                                setAuthMode('login');
                            }
                            if (typeof openAuthModal === 'function') {
                                openAuthModal();
                            }
                            return;
                        }

                        // User is authenticated - proceed with investment submission
                        submitBtn.textContent = 'Processing...';

                        // Create a regular form and submit it
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '/invest/save-info';
                        form.style.display = 'none';

                        // Add all form data as hidden inputs
                        for (let [key, value] of formData.entries()) {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = key;
                            input.value = value;
                            form.appendChild(input);
                        }

                        // Submit the form
                        document.body.appendChild(form);
                        form.submit();

                    } catch (error) {
                        console.error('Authentication check failed:', error);
                        submitBtn.textContent = originalText;
                        submitBtn.disabled = false;
                        alert('An error occurred. Please try again.');
                    }
                }

                // Handle auth completion for invest page
                window.addEventListener('investorProfileSaved', function() {
                    proceedWithInvestmentSubmission();
                });

                window.addEventListener('investorProfileSkipped', function() {
                    proceedWithInvestmentSubmission();
                });

                function proceedWithInvestmentSubmission() {
                    if (window._investmentFormData) {
                        const formData = window._investmentFormData;
                        const {
                            submitBtn,
                            originalText
                        } = window._investmentOriginalBtn || {};

                        if (submitBtn) {
                            submitBtn.textContent = 'Processing...';
                            submitBtn.disabled = true;
                        }

                        // Create a regular form and submit it
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '/invest/save-info';
                        form.style.display = 'none';

                        // Add all form data as hidden inputs
                        for (let [key, value] of formData.entries()) {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = key;
                            input.value = value;
                            form.appendChild(input);
                        }

                        // Submit the form
                        document.body.appendChild(form);
                        form.submit();

                        // Clear stored data
                        window._investmentFormData = null;
                        window._investmentOriginalBtn = null;
                    }
                }

                function showSuccessStep(data) {
                    const shares = Math.floor(selectedAmount / config.sharePrice);
                    document.getElementById('final-amount').textContent = `$${selectedAmount.toLocaleString()}`;
                    document.getElementById('final-shares').textContent = `${shares.toLocaleString()} shares`;
                }

                // Address Fields Logic - Country and State Dropdowns
                const countryStateData = {
                    "United States": [
                        "Alabama", "Alaska", "Arizona", "Arkansas", "California", "Colorado", "Connecticut",
                        "Delaware", "Florida", "Georgia", "Hawaii", "Idaho", "Illinois", "Indiana", "Iowa",
                        "Kansas", "Kentucky", "Louisiana", "Maine", "Maryland", "Massachusetts", "Michigan",
                        "Minnesota", "Mississippi", "Missouri", "Montana", "Nebraska", "Nevada",
                        "New Hampshire", "New Jersey", "New Mexico", "New York", "North Carolina",
                        "North Dakota", "Ohio", "Oklahoma", "Oregon", "Pennsylvania", "Rhode Island",
                        "South Carolina", "South Dakota", "Tennessee", "Texas", "Utah", "Vermont", "Virginia",
                        "Washington", "West Virginia", "Wisconsin", "Wyoming"
                    ].sort(),
                    "Canada": [
                        "Alberta", "British Columbia", "Manitoba", "New Brunswick", "Newfoundland and Labrador",
                        "Northwest Territories", "Nova Scotia", "Nunavut", "Ontario", "Prince Edward Island",
                        "Quebec", "Saskatchewan", "Yukon"
                    ].sort(),
                    "Australia": [
                        "Australian Capital Territory", "New South Wales", "Northern Territory", "Queensland",
                        "South Australia", "Tasmania", "Victoria", "Western Australia"
                    ].sort(),
                    "India": [
                        "Andaman and Nicobar Islands", "Andhra Pradesh", "Arunachal Pradesh", "Assam", "Bihar",
                        "Chandigarh", "Chhattisgarh", "Dadra and Nagar Haveli and Daman and Diu", "Delhi",
                        "Goa", "Gujarat", "Haryana", "Himachal Pradesh", "Jammu and Kashmir", "Jharkhand",
                        "Karnataka", "Kerala", "Ladakh", "Lakshadweep", "Madhya Pradesh", "Maharashtra",
                        "Manipur", "Meghalaya", "Mizoram", "Nagaland", "Odisha", "Puducherry", "Punjab",
                        "Rajasthan", "Sikkim", "Tamil Nadu", "Telangana", "Tripura", "Uttar Pradesh",
                        "Uttarakhand", "West Bengal"
                    ].sort(),
                    "Spain": [
                        "A Coruna", "Álava", "Ávila", "Albacete", "Alicante", "Almería", "Asturias", "Badajoz",
                        "Balearic Islands", "Barcelona", "Burgos", "Cáceres", "Cádiz", "Cantabria", "Castellón",
                        "Ciudad Real", "Córdoba", "Cuenca", "Girona", "Granada", "Guadalajara", "Guipúzcoa",
                        "Huelva", "Huesca", "Jaén", "La Coruña", "La Rioja", "Las Palmas", "León", "Lérida",
                        "Lugo", "Madrid", "Málaga", "Murcia", "Navarra", "Ourense", "Palencia", "Pontevedra",
                        "Salamanca", "Santa Cruz de Tenerife", "Segovia", "Seville", "Soria", "Tarragona",
                        "Teruel", "Toledo", "Valencia", "Valladolid", "Vizcaya", "Zamora", "Zaragoza"
                    ]
                };

                const countryList = Object.keys(countryStateData).concat(["United Kingdom", "Germany", "France",
                    "Spain", "Other"
                ]).filter((v, i, a) => a.indexOf(v) === i);

                function setCountryValue() {
                    const detected = detectCountry();
                    const countrySelect = document.getElementById('country');
                    if (!countrySelect) return;

                    countrySelect.innerHTML = '<option value="" disabled selected hidden>Select Country</option>';
                    countryList.forEach(function(country) {
                        const option = document.createElement('option');
                        option.value = country;
                        option.text = country;
                        countrySelect.appendChild(option);
                    });

                    // Try to auto-detect country from browser or fallback
                    if (detected && countryList.includes(detected)) {
                        countrySelect.value = detected;
                    } else {
                        countrySelect.selectedIndex = 0;
                    }
                    countrySelect.dispatchEvent(new Event('change'));
                }

                function populateStatesAndFields() {
                    const countrySelect = document.getElementById('country');
                    const stateSelect = document.getElementById('state');
                    const stateLabel = document.getElementById('state-label');
                    const zipcodeInput = document.getElementById('zipcode');
                    const zipcodeLabel = document.getElementById('zipcode-label');

                    if (!countrySelect || !stateSelect) return;

                    const country = countrySelect.value;
                    const stateWrapper = stateSelect.closest('.col-md-6');

                    // State/Province/Region logic
                    stateSelect.innerHTML = '<option value="" disabled selected hidden>Select State</option>';

                    if (country === 'United States') {
                        // US: State
                        countryStateData[country].forEach(function(state) {
                            const option = document.createElement('option');
                            option.value = state;
                            option.text = state;
                            stateSelect.appendChild(option);
                        });
                        stateSelect.disabled = false;
                        if (stateWrapper) stateWrapper.style.display = '';
                        if (stateLabel) stateLabel.textContent = 'State *';
                    } else if (country === 'Canada') {
                        // Canada: Province
                        countryStateData[country].forEach(function(province) {
                            const option = document.createElement('option');
                            option.value = province;
                            option.text = province;
                            stateSelect.appendChild(option);
                        });
                        stateSelect.disabled = false;
                        if (stateWrapper) stateWrapper.style.display = '';
                        if (stateLabel) stateLabel.textContent = 'Province *';
                    } else if (country === 'Australia') {
                        // Australia: State/Territory
                        countryStateData[country].forEach(function(region) {
                            const option = document.createElement('option');
                            option.value = region;
                            option.text = region;
                            stateSelect.appendChild(option);
                        });
                        stateSelect.disabled = false;
                        if (stateWrapper) stateWrapper.style.display = '';
                        if (stateLabel) stateLabel.textContent = 'State/Territory *';
                    } else if (country === 'India') {
                        // India: State (dropdown)
                        countryStateData[country].forEach(function(state) {
                            const option = document.createElement('option');
                            option.value = state;
                            option.text = state;
                            stateSelect.appendChild(option);
                        });
                        stateSelect.disabled = false;
                        if (stateWrapper) stateWrapper.style.display = '';
                        if (stateLabel) stateLabel.textContent = 'State *';
                    } else if (country === 'Spain') {
                        // Spain: Province (dropdown)
                        let provinces = countryStateData[country].slice();
                        let aCoruna = provinces.splice(provinces.findIndex(p => p === 'A Coruna'), 1)[0];
                        provinces = provinces.sort((a, b) => a.localeCompare(b, 'es', {
                            sensitivity: 'base'
                        }));
                        if (provinces.length > 1) {
                            let last = provinces[provinces.length - 1];
                            let secondLast = provinces[provinces.length - 2];
                            if (secondLast > last) {
                                provinces[provinces.length - 2] = last;
                                provinces[provinces.length - 1] = secondLast;
                            }
                        }
                        provinces.unshift(aCoruna);
                        provinces.forEach(function(province) {
                            const option = document.createElement('option');
                            option.value = province;
                            option.text = province;
                            stateSelect.appendChild(option);
                        });
                        stateSelect.disabled = false;
                        if (stateWrapper) stateWrapper.style.display = '';
                        if (stateLabel) stateLabel.textContent = 'Province *';
                    } else {
                        // Hide state/province/region field if not needed
                        stateSelect.disabled = true;
                    }

                    // Zip/Postal code logic and field order
                    if (zipcodeInput && zipcodeLabel) {
                        const cityInput = document.getElementById('city');
                        const zipcodeInputDiv = zipcodeInput.closest('.col-md-6');
                        const cityInputDiv = cityInput ? cityInput.closest('.col-md-6') : null;

                        // Germany, France, Spain: Postal Code - City
                        if (country === 'Germany' || country === 'France' || country === 'Spain') {
                            // Move Postal Code before City
                            if (zipcodeInputDiv && cityInputDiv && zipcodeInputDiv !== cityInputDiv
                                .previousElementSibling) {
                                cityInputDiv.parentNode.insertBefore(zipcodeInputDiv, cityInputDiv);
                            }
                            zipcodeInput.placeholder = 'Postal code';
                            zipcodeLabel.textContent = 'Postal Code *';
                            zipcodeInput.pattern = '';
                            zipcodeInput.title = 'Enter a valid Postal code';
                            zipcodeInput.required = true;
                            if (zipcodeInputDiv) zipcodeInputDiv.style.display = '';
                        } else if (country === 'Other') {
                            // For Other: Postcode beside City
                            if (zipcodeInputDiv && cityInputDiv && zipcodeInputDiv !== cityInputDiv
                                .nextElementSibling) {
                                cityInputDiv.parentNode.insertBefore(zipcodeInputDiv, cityInputDiv.nextElementSibling);
                            }
                            zipcodeInput.placeholder = 'Postcode';
                            zipcodeLabel.textContent = 'Postcode';
                            zipcodeInput.pattern = '';
                            zipcodeInput.title = 'Enter a valid Postcode';
                            zipcodeInput.required = false;
                            if (zipcodeInputDiv) zipcodeInputDiv.style.display = '';
                        } else if (country === 'United States') {
                            zipcodeInput.placeholder = 'ZIP code';
                            zipcodeLabel.textContent = 'ZIP Code *';
                            zipcodeInput.pattern = '\\d{5}(-\\d{4})?';
                            zipcodeInput.title = 'Enter a valid US ZIP code';
                            zipcodeInput.required = true;
                            if (zipcodeInputDiv) zipcodeInputDiv.style.display = '';
                        } else if (country === 'Canada') {
                            zipcodeInput.placeholder = 'Postal code';
                            zipcodeLabel.textContent = 'Postal Code *';
                            zipcodeInput.pattern = '[A-Za-z]\\d[A-Za-z][ -]?\\d[A-Za-z]\\d';
                            zipcodeInput.title = 'Enter a valid Canadian Postal code';
                            zipcodeInput.required = true;
                            if (zipcodeInputDiv) zipcodeInputDiv.style.display = '';
                        } else if (country === 'Australia') {
                            zipcodeInput.placeholder = 'Postcode';
                            zipcodeLabel.textContent = 'Postcode *';
                            zipcodeInput.pattern = '\\d{4}';
                            zipcodeInput.title = 'Enter a valid Australian Postcode';
                            zipcodeInput.required = true;
                            if (zipcodeInputDiv) zipcodeInputDiv.style.display = '';
                        } else if (country === 'United Kingdom') {
                            zipcodeInput.placeholder = 'Postcode';
                            zipcodeLabel.textContent = 'Postcode *';
                            zipcodeInput.pattern = '';
                            zipcodeInput.title = 'Enter a valid UK Postcode';
                            zipcodeInput.required = true;
                            if (zipcodeInputDiv) zipcodeInputDiv.style.display = '';
                        } else if (country === 'India') {
                            zipcodeInput.placeholder = 'PIN Code';
                            zipcodeLabel.textContent = 'PIN Code *';
                            zipcodeInput.pattern = '\\d{6}';
                            zipcodeInput.title = 'Enter a valid Indian PIN Code';
                            zipcodeInput.required = true;
                            if (zipcodeInputDiv) zipcodeInputDiv.style.display = '';
                        } else {
                            zipcodeInput.placeholder = 'Postal code';
                            zipcodeLabel.textContent = 'Postal Code';
                            zipcodeInput.pattern = '';
                            zipcodeInput.title = 'Enter a valid Postal code';
                            zipcodeInput.required = false;
                            if (zipcodeInputDiv) zipcodeInputDiv.style.display = 'none';
                        }
                    }
                }

                function detectCountry() {
                    // Try to detect country using browser locale or IP (simple fallback)
                    let country = "United States";
                    if (navigator.language) {
                        if (navigator.language.startsWith('en-GB')) country = "United Kingdom";
                        if (navigator.language.startsWith('en-CA')) country = "Canada";
                        if (navigator.language.startsWith('en-AU')) country = "Australia";
                        if (navigator.language.startsWith('fr-FR')) country = "France";
                        if (navigator.language.startsWith('de-DE')) country = "Germany";
                        if (navigator.language.startsWith('en-IN')) country = "India";
                        if (navigator.language.startsWith('es-ES')) country = "Spain";
                    }
                    return country;
                }

                // Initialize address fields when info step is shown
                const infoStepElement = document.getElementById('info-step');
                if (infoStepElement) {
                    const observer = new MutationObserver(function(mutations) {
                        mutations.forEach(function(mutation) {
                            if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                                if (infoStepElement.style.display !== 'none') {
                                    setTimeout(() => {
                                        setCountryValue();
                                        populateStatesAndFields();
                                        const countrySelect = document.getElementById(
                                        'country');
                                        if (countrySelect) {
                                            countrySelect.addEventListener('change',
                                                populateStatesAndFields);
                                        }
                                    }, 100);
                                }
                            }
                        });
                    });
                    observer.observe(infoStepElement, {
                        attributes: true
                    });
                }

                // Also initialize immediately if info step is already visible
                setTimeout(() => {
                    if (infoStepElement && infoStepElement.style.display !== 'none') {
                        setCountryValue();
                        populateStatesAndFields();
                        const countrySelect = document.getElementById('country');
                        if (countrySelect) {
                            countrySelect.addEventListener('change', populateStatesAndFields);
                        }
                    }
                }, 500);

                // SSN/EIN Help Modal Event Listeners
                document.addEventListener('click', function(e) {
                    if (e.target && e.target.classList.contains('ssn-help-link')) {
                        e.preventDefault();
                        e.stopPropagation();
                        // Bootstrap 5 modal trigger - the data-bs-toggle and data-bs-target attributes handle the modal
                    }
                });

                // Prevent form submission when clicking help links
                const ssnHelpLinks = document.querySelectorAll('.ssn-help-link');
                ssnHelpLinks.forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                    });
                });

                // Auto-fill investor data if user is logged in
                (async function loadExistingInvestorProfile() {
                    try {
                        const authCheck = await fetch('/ajax/ticket-auth/check', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            }
                        });
                        const authStatus = await authCheck.json();

                        if (authStatus.authenticated && authStatus.verified) {
                            // User is logged in, try to load their investor profile
                            const profileResp = await fetch('/users/investor-profile', {
                                headers: {
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .getAttribute('content')
                                }
                            });
                            const profileData = await profileResp.json();

                            console.log('Invest page - Profile data received:', profileData);

                            if (profileData.success && profileData.profile) {
                                // Load profile data into the form
                                const profile = profileData.profile;
                                const data = profile.investor_data || {};

                                // Set investor type
                                if (profile.investor_type) {
                                    investorTypeSelect.value = profile.investor_type;
                                    investorTypeSelect.dispatchEvent(new Event('change'));
                                }

                                // Helper to set field value
                                const setField = (id, value) => {
                                    const field = document.getElementById(id);
                                    if (field && value) {
                                        field.value = value;
                                    }
                                };

                                // Fill in the fields based on investor type
                                setTimeout(() => {
                                    // Individual fields
                                    setField('individual_name', data.individual_name);
                                    setField('date_of_birth', data.date_of_birth);
                                    setField('ssn', data.ssn);

                                    // Joint fields
                                    setField('primary_name', data.primary_name);
                                    setField('primary_ssn', data.primary_ssn);
                                    setField('secondary_name', data.secondary_name);
                                    setField('secondary_ssn', data.secondary_ssn);

                                    // Corporation fields
                                    setField('company_name', data.company_name);
                                    setField('ein', data.ein);
                                    setField('authorized_person', data.authorized_person);

                                    // Trust fields
                                    setField('trust_name', data.trust_name);
                                    setField('trust_ein', data.trust_ein);
                                    setField('trustee_name', data.trustee_name);

                                    // IRA fields
                                    setField('ira_holder_name', data.ira_holder_name);
                                    setField('ira_account_number', data.ira_account_number);
                                    setField('ira_custodian', data.ira_custodian);

                                    // Address fields
                                    setField('address', data.address);
                                    setField('city', data.city);
                                    setField('zipcode', data.zipcode);

                                    // Country and state need special handling
                                    const countryField = document.getElementById('country');
                                    if (countryField && data.country) {
                                        countryField.value = data.country;
                                        countryField.dispatchEvent(new Event('change'));

                                        // Set state after country change populates states
                                        setTimeout(() => {
                                            setField('state', data.state);
                                        }, 100);
                                    }

                                    // Contact fields
                                    setField('email', data.email);
                                    setField('phone', data.phone);

                                    console.log('Investor profile auto-filled successfully');
                                }, 200);
                            }
                        }
                    } catch (error) {
                        console.log('Could not load investor profile:', error);
                        // Not a critical error, user can still fill form manually
                    }
                })();

            });
        </script>

        </div>
        </div>
        </div>
        </div>
    </main>

    {{-- @if ($footer && $footer->status == 1)
        {!! $footer->content !!}
    @endif --}}

    <!-- CSRF Test Button (for debugging) -->
    {{-- <div style="position: fixed; bottom: 10px; right: 10px; z-index: 9999;">
        <button id="csrf-test-btn" style="padding: 5px 10px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 12px;">Test CSRF</button>
    </div> --}}

    <script>
        document.getElementById('csrf-test-btn').addEventListener('click', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            console.log('Testing CSRF with token:', csrfToken);

            fetch('/test-csrf', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    test: 'data',
                    _token: csrfToken
                })
            }).then(response => {
                console.log('CSRF Test Response Status:', response.status);
                return response.json();
            }).then(data => {
                console.log('CSRF Test Response:', data);
                alert('CSRF Test: ' + data.message);
            }).catch(error => {
                console.error('CSRF Test Error:', error);
                alert('CSRF Test Failed: ' + error.message);
            });
        });
    </script>



</body>

</html>
