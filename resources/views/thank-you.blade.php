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
        $website = $check;
        $groups = \App\Models\User::where('website_id', $check->id)->where('role', 'group_leader')->get();
        $user = \App\Models\User::where('id', $check->user_id)->first();
    } else {
        $setting = null;
        $header = null;
        $footer = null;
        $website = null;
        $groups = collect();
        $user = null;
    }
@endphp

<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $setting && $setting->company_name ? $setting->company_name . ' | Thank You!' : 'Thank You!' }}</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Custom Fonts CSS -->
    <link href="{{ route('fonts.css') }}" rel="stylesheet">
    
    <link href='https://fonts.googleapis.com/css?family=Lato:300,400|Montserrat:700' rel='stylesheet' type='text/css'>
    <style>
        @import url(//cdnjs.cloudflare.com/ajax/libs/normalize/3.0.1/normalize.min.css);
        @import url(//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css);
    </style>
    <link rel="stylesheet" href="https://2-22-4-dot-lead-pages.appspot.com/static/lp918/min/default_thank_you.css">
    <script src="https://2-22-4-dot-lead-pages.appspot.com/static/lp918/min/jquery-1.9.1.min.js"></script>
    <script src="https://2-22-4-dot-lead-pages.appspot.com/static/lp918/min/html5shiv.js"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <style>
        /* Custom Fonts @font-face declarations */
        @php
            $customFonts = \App\Models\CustomFont::active()->get();
        @endphp
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
    </style>
</head>

<body style="padding: 0px">
    @endphp
    @if ($header->status == 1)
        @include('layouts.nav')
    @endif
    <header class="site-header" id="header" style="padding-top: 8rem;">
        <h1 class="site-header__title" data-lead-id="site-header-title" style="text-align: center;">
            @if(isset($type) && $type == 'student')
                Thank You for Your Donation!
            @elseif(isset($type) && $type == 'general')
                THANK YOU FOR YOUR DONATION!
            @else
                THANK YOU!
            @endif 
        </h1>
    </header>

    <div class="main-content" style="text-align: center; padding-bottom: 4.3rem;">
        <i class="fa fa-check main-content__checkmark" id="checkmark"></i>
        
        @if(isset($type) && $type == 'donation')
            <p class="main-content__body p-4" data-lead-id="main-content-body">
                Your contribution has been successfully received and is already making an impact. A confirmation email with your donation details has been sent to the email address you provided (please check your spam folder if you don't see it).
            </p>
            <p class="main-content__body p-4" data-lead-id="main-content-body">
                We truly appreciate your generosity and support.
            </p>
            <p class="main-content__body p-4" data-lead-id="main-content-body">
                We'll send your donation confirmation and receipt to this email address.
            </p>
        @elseif(isset($type) && $type == 'donation')
            <p class="main-content__body p-4" data-lead-id="main-content-body">
                Your contribution has been successfully received and is already making an impact. A confirmation email with your donation details has been sent to the email address you provided (please check your spam folder if you don't see it).
            </p>
            <p class="main-content__body p-4" data-lead-id="main-content-body">
                We truly appreciate your generosity and support.
            </p>
        @else
            <p class="main-content__body p-4" data-lead-id="main-content-body">Your Transaction Is Complete. <br>
            Thank you for your purchase and a confirmation email with the details has been sent to you.
            Your order will appear in your dashboard shortly. <br>
            If you need help or have any questions, our support team is always here to assist.</p>
            <p class="main-content__body p-4" data-lead-id="main-content-body">Please check your dashboard for your purchase details.</p>
        @endif
    </div>

    <!-- Footer -->
    @if ($footer && $footer->status == 1)
        @include('layouts.new-footer')
    @endif

    @if(isset($type) && $type == 'auction')
    <!-- Firebase Integration for Auction Bids -->
    <script type="module">
        import { initializeApp, getApps } from "https://www.gstatic.com/firebasejs/11.9.1/firebase-app.js";
        import { getFirestore, collection, addDoc } from "https://www.gstatic.com/firebasejs/11.9.1/firebase-firestore.js";

        // Firebase config
        const firebaseConfig = {
            apiKey: "AIzaSyD0QsLeSIAFeBBUouzhgUQ3WEGfM1MAYA4",
            authDomain: "charity-390ca.firebaseapp.com",
            projectId: "charity-390ca",
            storageBucket: "charity-390ca.firebasestorage.app",
            messagingSenderId: "875958450032",
            appId: "1:875958450032:web:338aeac86307e5ab3e41b5",
            measurementId: "G-FC73HL5XF3"
        };

        // Initialize Firebase
        let app;
        if (!getApps().length) {
            app = initializeApp(firebaseConfig);
        } else {
            app = getApps()[0];
        }
        const firestore = getFirestore(app);

        // Check for pending bid and save to Firebase
        const pendingBid = sessionStorage.getItem('pendingBid');
        if (pendingBid) {
            try {
                const bidData = JSON.parse(pendingBid);
                
                // Save bid to Firebase
                await addDoc(collection(firestore, "bid"), {
                    auction_id: bidData.auction_id,
                    name: bidData.name,
                    email: bidData.email,
                    amount: bidData.amount,
                    timestamp: new Date(bidData.timestamp)
                });

                console.log('Bid successfully saved to Firebase after payment completion');
                
                // Clear the pending bid from session storage
                sessionStorage.removeItem('pendingBid');
            } catch (error) {
                console.error('Error saving bid to Firebase:', error);
            }
        }
    </script>
    @endif

</body>

</html>
