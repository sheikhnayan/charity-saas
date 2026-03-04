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
        
        // Load custom fonts for dynamic font support
        $customFonts = \App\Models\CustomFont::get();
    } else {
        $setting = null;
        $header = null;
        $footer = null;
        $website = null;
        $customFonts = collect();
    }
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $setting && $setting->company_name ? $setting->company_name . ' | Checkout' : 'Checkout' }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>body{background:#f9fafb;}</style>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('auction.css') }}">
    <link rel="stylesheet" href="{{ asset('checkout.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Custom Fonts CSS -->
    <link href="{{ route('fonts.css') }}" rel="stylesheet">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

    <style>
        .form-control{
            margin-bottom: 0.5rem;
            padding: 0.8rem;
        }

        .body-checkout {
        position: relative;
        /* ...other styles... */
        }
        .body-checkout::after {
        content: "";
        position: absolute;
        top: 0; right: 0; bottom: 0;
        width: 50%; /* adjust as needed */
        background: #f5f5f5; /* your right-side color */
        z-index: 0;
        border-radius: inherit; /* if you want rounded corners */
        }
        .body-checkout > * {
        position: relative;
        z-index: 1;
        }

        .footer-socials .nav-item {
        margin-right: 1rem !important;
    }

    .footer-socials .nav-item a i {
        font-size: 1.5rem;
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
    </style>
</head>
<body class="body-checkout">
     @php
        $payment = \App\Models\PaymentSetting::find(1);

    @endphp
    @php
        $url = url()->current();
        $doamin = parse_url($url, PHP_URL_HOST);
        $check = \App\Models\Website::where('domain', $doamin)->first();
        $header = \App\Models\Header::where('website_id', $check->id)->first();
        $footer = \App\Models\Footer::where('website_id', $check->id)->first();
        $setting = \App\Models\Setting::where('user_id', $check->user_id)->first();
        // dd($data->amount);

    @endphp
    @if ($header->status == 1)
        @include('layouts.nav')
    @endif
    <div class="container mb-4">
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
        
        <!-- Back Button -->
        <div class="container mt-3">
            <div class="row">
                <div class="col-md-6">
                    <button onclick="window.history.back()" class="btn btn-outline-secondary mb-3">
                        <i class="fas fa-arrow-left me-2"></i>Back to Previous Page
                    </button>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12 mobile mt-4" style="display: none;">
                <h1 class="mt-4">Checkout</h1>
            </div>
            <div class="col-md-6 mobile mb-4 items" style="display: none;">
                <h3>@if ($type == 'donation') Summary @else Items @endif</h3>
                @if ($type == 'donation')
                    <div class="row">
                        <div class="col-md-2 col-2">
                            @if ($data->type == 'general')
                                <img src="{{ asset('/uploads/' . $data->website->user->setting->logo) }}" width="64px"
                                    style="border-radius: 5px; border: 1px solid #eee;">
                            @else
                                <img src="{{ asset('/uploads/' . $data->user->image) }}" width="64px"
                                    style="border-radius: 5px; border: 1px solid #eee;">
                            @endif
                            {{-- <span
                                style="position: relative; left: 30px; top: -75px; background: #666; padding: 3px 9px; border-radius: 50%; color: #fff;">{{
                                $item->quantity }}</span> --}}
                        </div>
                        <div class="col-md-6 col-6 text-start" style="padding-top: 20px; font-weight: bold;">
                            @if ($data->type == 'general')
                                {{ $data->website->name }}
                            @else
                                {{ $data->user->name }}
                            @endif
                        </div>
                        <div class="col-md-2 col-2" style="padding-top: 20px; font-weight: bold;">
                            ${{ $data->amount }}
                        </div>
                    </div>
                @elseif($type == 'ticket')
                    @foreach ($data->details as $item)
                        <div class="row">
                            <div class="col-md-2 col-2">
                                <img src="{{ asset($item->ticket->image) }}" width="64px"
                                    style="border-radius: 5px; border: 1px solid #eee;">
                                    @if ($item->ticket->type == 'property')
                                        
                                    @else
                                        <span
                                            style="position: relative; left: 50px; top: -75px; background: #666; padding: 3px 9px; border-radius: 50%; color: #fff;">{{ $item->quantity }}</span>
                                    @endif
                            </div>
                            <div class="col-md-6 col-6 text-start" style="padding-top: 7px; font-weight: bold;">
                                {{ $item->ticket->name }}
                                @if ($item->ticket->type != 'property')
                                <p style="font-weight: 400">{{ $item->ticket->description }}</p>
                                @else
                                <p style="font-weight: 400">{{ $item->quantity }} shares bought at ${{ $item->ticket->price_per_share }} per share</p>
                                @endif
                            </div>
                            <div class="col-md-2 col-2" style="padding-top: 20px; font-weight: bold;">
                                ${{ $item->amount }}
                            </div>
                        </div>
                    @endforeach
                @elseif($type == 'auction')

                    <div class="row">
                        <div class="col-md-2 col-2">
                            <img src="{{ asset('/uploads/'.$data->images[0]->image) }}" width="64px"
                                style="border-radius: 5px; border: 1px solid #eee;">
                        </div>
                        <div class="col-md-6 col-6 text-start" style="padding-top: 7px; font-weight: bold;">
                            {{ $data->title }}
                            {!! $data->description !!}
                        </div>
                        <div class="col-md-2 col-2" style="padding-top: 20px; font-weight: bold;">
                            ${{ $data->amount }}
                        </div>
                    </div>
                @elseif($type == 'investment')
                    <div class="row">
                        <div class="col-md-2 col-2">
                            <img src="{{ asset('/uploads/' . $data->website->user->setting->logo) }}" width="64px"
                                style="border-radius: 5px; border: 1px solid #eee;">
                        </div>
                        <div class="col-md-6 col-6 text-start" style="padding-top: 20px; font-weight: bold;">
                            Investment - {{ $data->website->name }}
                            @if($data->shares > 0)
                                <p style="font-weight: 400; margin-top: 5px;">{{ number_format($data->shares) }} shares</p>
                            @endif
                        </div>
                        <div class="col-md-2 col-2" style="padding-top: 20px; font-weight: bold;">
                            ${{ number_format($data->amount, 2) }}
                        </div>
                    </div>
                @endif
                <div class="row mt-4" style="padding-left: 10px;">
                    <div class="col-md-8 col-8 text-start">
                        Subtotal
                    </div>
                    <div class="col-md-4 col-4">
                        ${{ $data->amount }}
                    </div>

                    <div class="col-md-8 col-8 text-start mt-2">
                        Platform Fee
                    </div>
                    <div class="col-md-4 col-4 mt-2">
                        ${{ number_format((($data->amount / 100) * $payment->fee), 2) }}
                    </div>

                    @if ($type == 'donation')
                    <div class="col-md-8 col-8 text-start mt-2" id="tip-row" style="display: none;">
                        Tip
                    </div>
                    <div class="col-md-4 col-4 mt-2" id="tip-amount-display" style="display: none;">
                        $0.00
                    </div>
                    @endif

                    <div class="col-md-8 col-8 text-start mt-4">
                        <h5 style="font-weight: bold;">Total</h5>
                    </div>
                    <div class="col-md-4 col-4 mt-4">
                        <h5 style="font-weight: bold;" id="checkout-total">${{ number_format((($data->amount / 100) * $payment->fee) + $data->amount, 2) }}
                        </h5>
                    </div>
                </div>
                {{-- <p>
                    <a href="#" onclick="switchPlan()"><b>Switch plan</b></a>
                </p> --}}
            </div>
            <div class="col-md-6">
                <h1 class="mt-4 desktop">Checkout</h1>
                <h2 style="margin-bottom: 10px;">Payment</h2>
                <p style="margin-bottom: 10px;">All transactions are secure and encrypted.</p>
                <div class="ca-header"
                    style="border: 1px solid #1773b0; border-top-left-radius: 10px; border-top-right-radius: 10px; padding: 15px; background: #f0f5ff;">
                    <div class="row">
                        <div class="col-md-6 col-6">
                            <h3 style="font-size: 0.9rem; text-align: start; margin-bottom: 0.5rem; padding-top: 5px;">Credit Card</h3>
                        </div>
                        <div class="col-md-6 col-6">
                            <div style="display: inline-flex; margin-left: 112px;"
                                class="_5uqybw1 _1fragem28 _1fragemkp _1fragemo4 _1fragemmf _1fragemmm _1fragem3c _1fragem55 _1fragem7s">
                                <span id="vt-34fdb6d598fde109f598048c89fcfcfe-VISA"
                                    style="display: flex; margin-right: 6px;" class="_6f3AR">
                                    <img alt="VISA"
                                        src="https://cdn.shopify.com/shopifycloud/checkout-web/assets/c1.en/assets/visa.sxIq5Dot.svg"
                                        role="img" width="38" height="24"
                                        class="_1tgdqw61 _1tgdqw60 _1fragemsx _1fragemss _1fragemt7 _1tgdqw66">
                                </span>
                                <span id="vt-34fdb6d598fde109f598048c89fcfcfe-MASTERCARD"
                                    style="display: flex; margin-right: 6px;" class="_6f3AR">
                                    <img alt="MASTERCARD"
                                        src="https://cdn.shopify.com/shopifycloud/checkout-web/assets/c1.en/assets/mastercard.1c4_lyMp.svg"
                                        role="img" width="38" height="24"
                                        class="_1tgdqw61 _1tgdqw60 _1fragemsx _1fragemss _1fragemt7 _1tgdqw66">
                                </span>
                                <span id="vt-34fdb6d598fde109f598048c89fcfcfe-AMEX"
                                    style="display: flex; margin-right: 6px;" class="_6f3AR">
                                    <img alt="AMEX"
                                        src="https://cdn.shopify.com/shopifycloud/checkout-web/assets/c1.en/assets/amex.Csr7hRoy.svg"
                                        role="img" width="38" height="24"
                                        class="_1tgdqw61 _1tgdqw60 _1fragemsx _1fragemss _1fragemt7 _1tgdqw66">
                                </span>
                                <div class="_123qrzt0 _1fragem23 _123qrzt1 _123qrzt2">
                                    <span id="vt-34fdb6d598fde109f598048c89fcfcfe-DISCOVER"
                                        style="display: flex; margin-right: 6px;" class="_6f3AR">
                                        <img alt="DISCOVER"
                                            src="https://cdn.shopify.com/shopifycloud/checkout-web/assets/c1.en/assets/discover.C7UbFpNb.svg"
                                            role="img" width="38" height="24"
                                            class="_1tgdqw61 _1tgdqw60 _1fragemsx _1fragemss _1fragemt7 _1tgdqw66">
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <form action="{{ route('stripe.post') }}" method="POST" id="payment-form"
                    style="padding: 1rem; background: #f4f4f4; border: 1px solid #dedede; border-top-left-radius: 0px; border-top-right-radius: 0px; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
                    @csrf
                    <input type="hidden" name="donation_id" value="{{ $data->id }}">
                    <input type="hidden" name="type" value="{{ $type }}">
                    <input type="hidden" name="amount"
                        value="{{ (($data->amount / 100) * $payment->fee) + $data->amount }}">
                    <div data-testid="form-field-wrapper" class="sc-jnLVoO gJUOyx">
                        <div class="sc-hUpaCq iQeRTc">
                            <div color="#2B2A35" data-testid="authenticationEmailInputContainer" id="card_number_wrapper"
                                width="100%" value="" aria-required="true" aria-invalid="false"
                                font-family="Lato, Helvetica Neue, HelveticaNeue, Helvetica, Arial, sans-serif"
                                font-size="14px" class="sc-bkkeKt cNnlrr sc-ieecCq hEbWVQ position-relative">
                                <!-- Stripe Element will be mounted here -->
                                <div id="card_number" class="form-control" style="padding: 0.8rem; height: auto; background: white;"></div>
                                <span class="position-absolute" style="right: 15px; top: 50%; transform: translateY(-50%); pointer-events: none;">
                                    <i class="fa fa-lock" aria-hidden="true" style="color: #888;"></i>
                                </span>
                            </div>
                        </div>
                        <div class="sc-hUpaCq iQeRTc vvv" style="display: inline-flex; width: 100%;">
                            <div style="width: 49%;" color="#2B2A35" data-testid="authenticationEmailInputContainer" id="email" width="100%"
                                value="" aria-required="true" aria-invalid="false"
                                font-family="Lato, Helvetica Neue, HelveticaNeue, Helvetica, Arial, sans-serif"
                                font-size="14px" class=" expiry sc-bkkeKt cNnlrr sc-ieecCq hEbWVQ">
                                <!-- Stripe Expiry Element will be mounted here -->
                                <div id="expiration_date" class="form-control" style="padding: 0.8rem; height: auto; background: white;"></div>
                            </div>



                            <div style="width: 49%; margin-left: 11px; position: relative;" color="#2B2A35" data-testid="authenticationEmailInputContainer" id="email" width="100%"
                                value="" aria-required="true" aria-invalid="false"
                                font-family="Lato, Helvetica Neue, HelveticaNeue, Helvetica, Arial, sans-serif"
                                font-size="14px" class="security sc-bkkeKt cNnlrr sc-ieecCq hEbWVQ">
                                <!-- Stripe CVC Element will be mounted here -->
                                <div id="cvv" class="form-control" style="padding: 0.8rem; height: auto; background: white; padding-right: 2.5rem;"></div>
                                <span class="position-absolute" style="right: 13px; top: 42%; transform: translateY(-50%); cursor: pointer;" tabindex="0" data-bs-toggle="tooltip" data-bs-placement="left" title="3-digit security code usually found on the back of your card. American Express cards have a 4-digit code located on the front.">
                                    <i class="fa fa-question-circle" aria-hidden="true" style="color: #888;"></i>
                                </span>
                            </div>
                        </div>
                        <div class="sc-hUpaCq iQeRTc">
                            <div color="#2B2A35" data-testid="authenticationEmailInputContainer" id="card_number"
                                width="100%" value="" aria-required="true" aria-invalid="false"
                                font-family="Lato, Helvetica Neue, HelveticaNeue, Helvetica, Arial, sans-serif"
                                font-size="14px" class="sc-bkkeKt cNnlrr sc-ieecCq hEbWVQ position-relative">
                                <input notranslate="true" type="text" class="form-control pr-5"
                                    @error('card_number') is-invalid @enderror name="name_on_card" required
                                    autocomplete="off" maxlength="16" placeholder="Name on card" color="#2B2A35"
                                    id="card_number" width="100%"
                                    font-family="Lato, Helvetica Neue, HelveticaNeue, Helvetica, Arial, sans-serif"
                                    font-size="14px" class="sc-hBUSln kIfaoz" value="" style="width: 100%; padding-right: 2.5rem;" required>
                            </div>
                        </div>
                        <h3
                            style="margin-top: 1.5rem; margin-bottom: 0.5rem;font-size: 15px; font-weight: bold; padding-left: 7px; padding-bottom: 10px; padding-top: 10px;">
                            Billing address</h3>
                        <div class="sc-hUpaCq iQeRTc">
                            <div class="row">
                                <div class="col-md-12 mb-2 position-relative">
                                    <div class="form-floating">
                                        <select class="form-select" name="country" id="country" required aria-label="Country/Region">
                                            <option value="" disabled selected hidden></option>
                                        </select>
                                        <label for="country">Country/Region</label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <input notranslate="true" type="text" class="form-control" placeholder="First name" name="first_name" color="#2B2A35" id="first_name" value="{{ $data->first_name ?? ''}}" required>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <input notranslate="true" type="text" class="form-control" placeholder="Last name" name="last_name" color="#2B2A35" id="last_name" value="{{ $data->last_name ?? ''}}" required>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <input notranslate="true" type="email" class="form-control" placeholder="Email" name="email" autocomplete="email" color="#2B2A35" id="email" value="{{ $data->email ?? ''}}" required>
                                </div>
                                <div class="col-md-12 mb-2 position-relative">
                                    <input notranslate="true" type="text" class="form-control" placeholder="Address" name="address" id="address" required>
                                    <span class="position-absolute" style="right: 26px; top: 45%; transform: translateY(-50%); cursor: pointer;">
                                        <i class="fa fa-search" aria-hidden="true" style="color: #888;"></i>
                                    </span>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <input notranslate="true" type="text" class="form-control" placeholder="Apartment, suite, etc. (optional)" name="apartment" id="apartment">
                                </div>
                                <div class="col-md-4 mb-2">
                                    <input notranslate="true" type="text" class="form-control" placeholder="City" name="city" id="city" required>
                                </div>
                                <div class="col-md-4 mb-2 position-relative">
                                    <div class="form-floating">
                                        <select class="form-select" name="state" id="state" required aria-label="State">
                                            <option value="" disabled selected hidden></option>
                                        </select>
                                        <label for="state">State</label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <input notranslate="true" type="text" class="form-control" placeholder="ZIP code" name="zipcode" id="zipcode" required>
                                </div>
                                <div class="col-md-12 mb-2 position-relative">
                                    <input notranslate="true" type="tel" class="form-control pr-5" placeholder="Phone" name="phone" id="phone" required>
                                    <span class="position-absolute" style="right: 26px; top: 45%; transform: translateY(-50%); cursor: pointer;" tabindex="0" data-bs-toggle="tooltip" data-bs-placement="left" title="In case we need to contact you about your order">
                                        <i class="fa fa-question-circle" aria-hidden="true" style="color: #888;"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($type == 'donation' && $website->paymentSettings?->tipping_enabled ?? true)
                    {{-- Tipping Component --}}
                    @include('components.tipping', [
                        'baseAmount' => $data->amount,
                        'primaryColor' => '#1773b0',
                        'processingFee' => $payment->fee
                    ])
                    @endif
                    
                    <div class="sc-gyZVQB fWNGEI mt-4">
                        <div class="sc-cVAmsi cvolSU"><button type="submit"
                                data-testid="combinedAuthenticationLocationFormSubmitButton" height="45px"
                                font-family="Lato, Helvetica Neue, HelveticaNeue, Helvetica, Arial, sans-serif"
                                font-size="18px" color="#0E1414" width="100%" style="width: 100%;"
                                class="btn btn-primary">Pay Now</button>
                        </div>
                    </div>
                    
                    <!-- Pay with Crypto Button -->
                    <div class="sc-gyZVQB fWNGEI mt-3">
                        <div class="sc-cVAmsi cvolSU">
                            <a href="{{ route('crypto.payment') }}?amount={{ $data->amount }}&type={{ $data->type }}&reference_id={{ $data->id }}&website_id={{ $data->website_id ?? '' }}&session_id={{ session()->getId() }}" 
                               class="btn btn-outline-warning" 
                               style="width: 100%; height: 45px; font-family: Lato, Helvetica Neue, HelveticaNeue, Helvetica, Arial, sans-serif; font-size: 18px; border: 2px solid #f39c12; color: #f39c12; background-color: transparent; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-bitcoin-sign me-2"></i>Pay with Crypto
                            </a>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <ul style="display: inline-flex; list-style: none; margin-left: 0px; margin-top: 20px; margin-bottom: 5px;">
                                <li style="margin-right: 1rem;">
                                    <a style="color: #1773b0; text-decoration: underline;" href="/page/{{ str_replace(' ', '-', strtolower($setting->refund ? $setting->refund_page->name : '#')) }}">Refund policy</a>
                                </li>
                                <li style="margin-right: 1rem;">
                                    <a style="color: #1773b0; text-decoration: underline;" href="/page/{{ str_replace(' ', '-', strtolower($setting->privacy ? $setting->privacy_page->name : '#')) }}">Privacy policy</a>
                                </li>
                                <li style="margin-right: 1rem;">
                                    <a style="color: #1773b0; text-decoration: underline;" href="/page/{{ str_replace(' ', '-', strtolower($setting->terms ? $setting->terms_page->name : '#')) }}">Terms of service</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-6 desktop">
                <h3 style="padding-top: 6rem;">@if ($type == 'donation') Summary @else Items @endif</h3>
                @if ($type == 'donation')
                    <div class="row">
                        <div class="col-md-2 col-2">
                            @if ($data->type == 'general')
                                <img src="{{ asset('/uploads/' . $data->website->user->setting->logo) }}" width="64px"
                                    style="border-radius: 5px; border: 1px solid #eee;">
                            @else
                                <img src="{{ asset('/uploads/' . $data->user->image) }}" width="64px"
                                    style="border-radius: 5px; border: 1px solid #eee;">
                            @endif
                            {{-- <span
                                style="position: relative; left: 30px; top: -75px; background: #666; padding: 3px 9px; border-radius: 50%; color: #fff;">{{
                                $item->quantity }}</span> --}}
                        </div>
                        <div class="col-md-6 col-6 text-start" style="padding-top: 20px; font-weight: bold;">
                            @if ($data->type == 'general')
                                {{ $data->website->name }}
                            @else
                                {{ $data->user->name }}
                            @endif
                        </div>
                        <div class="col-md-2 col-2" style="padding-top: 20px; font-weight: bold;">
                            ${{ $data->amount }}
                        </div>
                    </div>
                @elseif($type == 'ticket')
                    @foreach ($data->details as $item)
                        <div class="row">
                            <div class="col-md-2 col-2">
                                <img src="{{ asset($item->ticket->image) }}" width="64px"
                                    style="border-radius: 5px; border: 1px solid #eee;">
                                    @if ($item->ticket->type == 'property')
                                        
                                    @else
                                        <span
                                            style="position: relative; left: 50px; top: -75px; background: #666; padding: 3px 9px; border-radius: 50%; color: #fff;">{{ $item->quantity }}</span>
                                    @endif
                            </div>
                            <div class="col-md-6 col-6 text-start" style="padding-top: 7px; font-weight: bold;">
                                {{ $item->ticket->name }}
                                @if ($item->ticket->type != 'property')
                                <p style="font-weight: 400">{{ $item->ticket->description }}</p>
                                @else
                                <p style="font-weight: 400">{{ $item->quantity }} shares bought at ${{ $item->ticket->price_per_share }} per share</p>
                                @endif
                            </div>
                            <div class="col-md-2 col-2" style="padding-top: 20px; font-weight: bold;">
                                ${{ $item->amount }}
                            </div>
                        </div>
                    @endforeach
                @elseif($type == 'auction')

                    <div class="row">
                        <div class="col-md-2 col-2">
                            <img src="{{ asset('/uploads/'.$data->images[0]->image) }}" width="64px"
                                style="border-radius: 5px; border: 1px solid #eee;">
                        </div>
                        <div class="col-md-6 col-6 text-start" style="padding-top: 7px; font-weight: bold;">
                            {{ $data->title }}
                            {!! $data->description !!}
                        </div>
                        <div class="col-md-2 col-2" style="padding-top: 20px; font-weight: bold;">
                            ${{ $data->amount }}
                        </div>
                    </div>
                @elseif($type == 'investment')
                    <div class="row">
                        <div class="col-md-2 col-2">
                            <img src="{{ asset('/uploads/' . $data->website->user->setting->logo) }}" width="64px"
                                style="border-radius: 5px; border: 1px solid #eee;">
                        </div>
                        <div class="col-md-6 col-6 text-start" style="padding-top: 20px; font-weight: bold;">
                            Investment - {{ $data->website->name }}
                            @if($data->shares > 0)
                                <p style="font-weight: 400; margin-top: 5px;">{{ number_format($data->shares) }} shares</p>
                            @endif
                        </div>
                        <div class="col-md-2 col-2" style="padding-top: 20px; font-weight: bold;">
                            ${{ number_format($data->amount, 2) }}
                        </div>
                    </div>
                @endif
                <div class="row mt-4" style="padding-left: 10px;">
                    <div class="col-md-8 col-8 text-start">
                        Subtotal
                    </div>
                    <div class="col-md-4 col-4">
                        ${{ $data->amount }}
                    </div>

                    <div class="col-md-8 col-8 text-start mt-2">
                        Platform Fee
                    </div>
                    <div class="col-md-4 col-4 mt-2">
                        ${{ ($data->amount / 100) * $payment->fee }}
                    </div>

                    @if ($type == 'donation')
                    <div class="col-md-8 col-8 text-start mt-2" id="tip-row-desktop" style="display: none;">
                        Tip
                    </div>
                    <div class="col-md-4 col-4 mt-2" id="tip-amount-display-desktop" style="display: none;">
                        $0.00
                    </div>
                    @endif

                    <div class="col-md-8 col-8 text-start mt-4">
                        <h5 style="font-weight: bold;">Total</h5>
                    </div>
                    <div class="col-md-4 col-4 mt-4">
                        <h5 style="font-weight: bold;" id="checkout-total-desktop">${{ (($data->amount / 100) * $payment->fee) + $data->amount }}
                        </h5>
                    </div>
                </div>
                {{-- <p>
                    <a href="#" onclick="switchPlan()"><b>Switch plan</b></a>
                </p> --}}
            </div>
        </div>
    </div>
    @if ($footer && $footer->status == 1)
        @include('layouts.new-footer')
    @elseif ($footer && $footer->status == 1)
            <footer class="standard-client-footer text-white bg-primary" data-footer="" style="
        background-color: {{ $footer->background }} !important;
        max-width: 100%;
        ">
                <div class="container">

                    <p class="lead text-center pt-4" style="color: {{ $footer->color }} !important">
                        {{ $footer->message }}
                    </p>
                    @if ($footer->menu == 1)
                        <div class="nav justify-content-center">
                            @foreach ($check->pages->sortBy('position') as $item)

                                @if($item->status == 1 && $item->show_in_menu)

                                    <div class="nav-item">
                                        <a class="nav-link active" href="/page/{{ str_replace(' ', '-', strtolower($item->name)) }}"
                                            style="color:{{ $footer->color }} !important" aria-current="page">
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
                                        <i class="fa-brands fa-facebook fa-fw" role="img" aria-hidden="true"
                                            style="color: {{ $footer->color }} !important"></i>
                                        <span class="visually-hidden">facebook</span>
                                    </a>
                                </li>
                            @endif

                            @if ($footer->instagram)
                                <li class="nav-item">
                                    <a href="{{ $footer->instagram }}" target="_blank">
                                        <i class="fa-brands fa-instagram fa-fw" role="img" aria-hidden="true"
                                            style="color: {{ $footer->color }} !important"></i>
                                        <span class="visually-hidden">instagram</span>
                                    </a>
                                </li>
                            @endif

                            @if ($footer->linkedin)
                                <li class="nav-item">
                                    <a href="{{ $footer->linkedin }}" target="_blank">
                                        <i class="fa-brands fa-linkedin fa-fw" role="img" aria-hidden="true"
                                            style="color: {{ $footer->color }} !important"></i>
                                        <span class="visually-hidden">linkedin</span>
                                    </a>
                                </li>
                            @endif

                            @if ($footer->pinterest)
                                <li class="nav-item">
                                    <a href="{{ $footer->pinterest }}" target="_blank">
                                        <i class="fa-brands fa-pinterest fa-fw" role="img" aria-hidden="true"
                                            style="color: {{ $footer->color }} !important"></i>
                                        <span class="visually-hidden">pinterest</span>
                                    </a>
                                </li>
                            @endif

                            @if ($footer->x)
                                <li class="nav-item">
                                    <a href="{{ $footer->x }}" target="_blank">
                                        <i class="fa-brands fa-x-twitter fa-fw" role="img" aria-hidden="true"
                                            style="color: {{ $footer->color }} !important"></i>
                                        <span class="visually-hidden">x</span>
                                    </a>
                                </li>
                            @endif

                            @if ($footer->youtube)
                                <li class="nav-item">
                                    <a href="{{ $footer->youtube }}" target="_blank">
                                        <i class="fa-brands fa-youtube fa-fw" role="img" aria-hidden="true"
                                            style="color: {{ $footer->color }} !important"></i>
                                        <span class="visually-hidden">youtube</span>
                                    </a>
                                </li>
                            @endif

                            @if ($footer->blue_sky)
                                <li class="nav-item">
                                    <a href="{{ $footer->blue_sky }}" target="_blank">
                                        <i class="fa-solid fa-cloud fa-fw" role="img" aria-hidden="true"
                                            style="color: {{ $footer->color }} !important"></i>
                                        <span class="visually-hidden">blue sky</span>
                                    </a>
                                </li>
                            @endif

                            @if ($footer->tiktok)
                                <li class="nav-item">
                                    <a href="{{ $footer->tiktok }}" target="_blank">
                                        <i class="fa-brands fa-tiktok fa-fw" role="img" aria-hidden="true"
                                            style="color: {{ $footer->color }} !important"></i>
                                        <span class="visually-hidden">tiktok</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    @endif

                    @if ($footer->copy_right != null)
                        <p class="text-center">
                            <small style="color: {{ $footer->color }}">
                                {{ $footer->copy_right }}
                            </small>
                        </p>
                    @endif

                </div>
                @if ($footer->privacy == 1)
                    <div class="row">
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
</body>

<script>
    // Enable Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Format Expiry Date as MM/YY
    function formatExpiryDate(input) {
        let value = input.value.replace(/[^0-9]/g, '');
        if (value.length > 4) value = value.slice(0, 4);
        if (value.length > 2) {
            value = value.slice(0, 2) + ' / ' + value.slice(2, 4);
        }
        input.value = value;
    }

    // Country and State Dropdowns
    const countryStateData = {
        "United States": [
            "Alabama","Alaska","Arizona","Arkansas","California","Colorado","Connecticut","Delaware","Florida","Georgia","Hawaii","Idaho","Illinois","Indiana","Iowa","Kansas","Kentucky","Louisiana","Maine","Maryland","Massachusetts","Michigan","Minnesota","Mississippi","Missouri","Montana","Nebraska","Nevada","New Hampshire","New Jersey","New Mexico","New York","North Carolina","North Dakota","Ohio","Oklahoma","Oregon","Pennsylvania","Rhode Island","South Carolina","South Dakota","Tennessee","Texas","Utah","Vermont","Virginia","Washington","West Virginia","Wisconsin","Wyoming"
        ].sort(),
        "Canada": [
            "Alberta",
            "British Columbia",
            "Manitoba",
            "New Brunswick",
            "Newfoundland and Labrador",
            "Northwest Territories",
            "Nova Scotia",
            "Nunavut",
            "Ontario",
            "Prince Edward Island",
            "Quebec",
            "Saskatchewan",
            "Yukon"
        ].sort(),
        "Australia": [
            "Australian Capital Territory","New South Wales","Northern Territory","Queensland","South Australia","Tasmania","Victoria","Western Australia"
        ].sort(),
        "India": [
            "Andaman and Nicobar Islands","Andhra Pradesh","Arunachal Pradesh","Assam","Bihar","Chandigarh","Chhattisgarh","Dadra and Nagar Haveli and Daman and Diu","Delhi","Goa","Gujarat","Haryana","Himachal Pradesh","Jammu and Kashmir","Jharkhand","Karnataka","Kerala","Ladakh","Lakshadweep","Madhya Pradesh","Maharashtra","Manipur","Meghalaya","Mizoram","Nagaland","Odisha","Puducherry","Punjab","Rajasthan","Sikkim","Tamil Nadu","Telangana","Tripura","Uttar Pradesh","Uttarakhand","West Bengal"
        ].sort(),
        "Spain": [
            "A Coruna", // should be at the top
            "Álava","Ávila","Albacete","Alicante","Almería","Asturias","Badajoz","Balearic Islands","Barcelona","Burgos","Cáceres","Cádiz","Cantabria","Castellón","Ciudad Real","Córdoba","Cuenca","Girona","Granada","Guadalajara","Guipúzcoa","Huelva","Huesca","Jaén","La Coruña","La Rioja","Las Palmas","León","Lérida","Lugo","Madrid","Málaga","Murcia","Navarra","Ourense","Palencia","Pontevedra","Salamanca","Santa Cruz de Tenerife","Segovia","Seville","Soria","Tarragona","Teruel","Toledo","Valencia","Valladolid","Vizcaya","Zamora","Zaragoza"
        ]
    };
    const countryList = Object.keys(countryStateData).concat(["United Kingdom", "Germany", "France", "Spain", "Other"]).filter((v, i, a) => a.indexOf(v) === i);
    function setCountryValue() {
        const detected = detectCountry();
        const countrySelect = document.getElementById('country');
        countrySelect.innerHTML = '<option value="" disabled selected hidden></option>';
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
        const country = document.getElementById('country').value;
        const stateWrapper = document.querySelector('.form-floating select#state').closest('.col-md-4, .col-md-12, .col-md-6');
        const stateSelect = document.getElementById('state');
        const stateLabel = document.querySelector('label[for="state"]');
        const zipcodeInput = document.getElementById('zipcode');
        // State/Province/Region logic
        stateSelect.innerHTML = '<option value="" disabled selected hidden></option>';
        if (country === 'United States') {
            // US: State
            countryStateData[country].forEach(function(state) {
                const option = document.createElement('option');
                option.value = state;
                option.text = state;
                stateSelect.appendChild(option);
            });
            stateSelect.disabled = false;
            if(stateWrapper) stateWrapper.style.display = '';
            if(stateLabel) stateLabel.textContent = 'State';
        } else if (country === 'Canada') {
            // Canada: Province
            countryStateData[country].forEach(function(province) {
                const option = document.createElement('option');
                option.value = province;
                option.text = province;
                stateSelect.appendChild(option);
            });
            stateSelect.disabled = false;
            if(stateWrapper) stateWrapper.style.display = '';
            if(stateLabel) stateLabel.textContent = 'Province';
        } else if (country === 'Australia') {
            // Australia: State/Territory
            countryStateData[country].forEach(function(region) {
                const option = document.createElement('option');
                option.value = region;
                option.text = region;
                stateSelect.appendChild(option);
            });
            stateSelect.disabled = false;
            if(stateWrapper) stateWrapper.style.display = '';
            if(stateLabel) stateLabel.textContent = 'State/territory';
        } else if (country === 'India') {
            // India: State (dropdown)
            countryStateData[country].forEach(function(state) {
                const option = document.createElement('option');
                option.value = state;
                option.text = state;
                stateSelect.appendChild(option);
            });
            stateSelect.disabled = false;
            if(stateWrapper) stateWrapper.style.display = '';
            if(stateLabel) stateLabel.textContent = 'State';
        } else if (country === 'Spain') {
            // Spain: Province (dropdown)
            // Custom sort: 'A Coruna' always first, then rest alphabetically (including La Coruña, Zamora, Zaragoza)
            let provinces = countryStateData[country].slice();
            let aCoruna = provinces.splice(provinces.findIndex(p => p === 'A Coruna'), 1)[0];
            // Sort the rest alphabetically
            provinces = provinces.sort((a, b) => a.localeCompare(b, 'es', {sensitivity: 'base'}));
            // Ensure the last two are in correct order
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
            if(stateWrapper) stateWrapper.style.display = '';
            if(stateLabel) stateLabel.textContent = 'Province';
        } else {
            // Hide state/province/region field if not needed
            if(stateWrapper) stateWrapper.style.display = 'none';
            stateSelect.disabled = true;
        }
        // Zip/Postal code logic and field order
        // Germany, France, Spain: Postal Code - City
        if (country === 'Germany' || country === 'France' || country === 'Spain') {
            // Move Postal Code before City
            const cityInput = document.getElementById('city');
            const zipcodeInputDiv = zipcodeInput.parentElement;
            const cityInputDiv = cityInput.parentElement;
            if (zipcodeInputDiv && cityInputDiv && zipcodeInputDiv !== cityInputDiv.previousElementSibling) {
                cityInputDiv.parentNode.insertBefore(zipcodeInputDiv, cityInputDiv);
            }
            zipcodeInput.placeholder = 'Postal code';
            zipcodeInput.pattern = '';
            zipcodeInput.title = 'Enter a valid Postal code';
            zipcodeInput.required = true;
            zipcodeInput.parentElement.style.display = '';
        } else if (country === 'Other') {
            // For Other: Postcode beside City
            const cityInput = document.getElementById('city');
            const zipcodeInputDiv = zipcodeInput.parentElement;
            const cityInputDiv = cityInput.parentElement;
            if (zipcodeInputDiv && cityInputDiv && zipcodeInputDiv !== cityInputDiv.nextElementSibling) {
                cityInputDiv.parentNode.insertBefore(zipcodeInputDiv, cityInputDiv.nextElementSibling);
            }
            zipcodeInput.placeholder = 'Postcode';
            zipcodeInput.pattern = '';
            zipcodeInput.title = 'Enter a valid Postcode';
            zipcodeInput.required = false;
            zipcodeInput.parentElement.style.display = '';
        } else if (country === 'United States') {
            zipcodeInput.placeholder = 'ZIP code';
            zipcodeInput.pattern = '\\d{5}(-\\d{4})?';
            zipcodeInput.title = 'Enter a valid US ZIP code';
            zipcodeInput.required = true;
            zipcodeInput.parentElement.style.display = '';
        } else if (country === 'Canada') {
            zipcodeInput.placeholder = 'Postal code';
            zipcodeInput.pattern = '[A-Za-z]\\d[A-Za-z][ -]?\\d[A-Za-z]\\d';
            zipcodeInput.title = 'Enter a valid Canadian Postal code';
            zipcodeInput.required = true;
            zipcodeInput.parentElement.style.display = '';
        } else if (country === 'Australia') {
            zipcodeInput.placeholder = 'Postcode';
            zipcodeInput.pattern = '\\d{4}';
            zipcodeInput.title = 'Enter a valid Australian Postcode';
            zipcodeInput.required = true;
            zipcodeInput.parentElement.style.display = '';
        } else if (country === 'United Kingdom') {
            zipcodeInput.placeholder = 'Postcode';
            zipcodeInput.pattern = '';
            zipcodeInput.title = 'Enter a valid UK Postcode';
            zipcodeInput.required = true;
            zipcodeInput.parentElement.style.display = '';
        } else if (country === 'India') {
            zipcodeInput.placeholder = 'PIN Code';
            zipcodeInput.pattern = '\\d{6}';
            zipcodeInput.title = 'Enter a valid Indian PIN Code';
            zipcodeInput.required = true;
            zipcodeInput.parentElement.style.display = '';
        } else {
            zipcodeInput.placeholder = 'Postal code';
            zipcodeInput.pattern = '';
            zipcodeInput.title = 'Enter a valid Postal code';
            zipcodeInput.required = false;
            zipcodeInput.parentElement.style.display = 'none';
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
        }
        return country;
    }
    document.addEventListener('DOMContentLoaded', function() {
        setCountryValue();
        populateStatesAndFields();
        document.getElementById('country').addEventListener('change', populateStatesAndFields);
    });
</script>

<!-- Stripe JS -->
<script src="https://js.stripe.com/v3/"></script>
<script>
    // Initialize Stripe with publishable key
    @php
        $paymentConfig = \App\Models\Payment::where('user_id', auth()->check() ? auth()->id() : $user_id)->first();
    @endphp
    
    const stripe = Stripe("{{ $paymentConfig && isset($paymentConfig->config['publishable_key']) ? $paymentConfig->config['publishable_key'] : env('STRIPE_KEY') }}");
    const elements = stripe.elements();
    
    // Define style to match the authorize.net design
    const style = {
        base: {
            fontSize: '14px',
            color: '#2B2A35',
            fontFamily: 'Lato, Helvetica Neue, HelveticaNeue, Helvetica, Arial, sans-serif',
            '::placeholder': {
                color: '#aab7c4'
            }
        },
        invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
        }
    };
    
    // Create Stripe Elements
    const cardNumber = elements.create('cardNumber', {
        style: style,
        placeholder: 'Card number'
    });
    
    const cardExpiry = elements.create('cardExpiry', {
        style: style,
        placeholder: 'MM / YY'
    });
    
    const cardCvc = elements.create('cardCvc', {
        style: style,
        placeholder: 'CVV'
    });
    
    // Mount Elements to DOM
    cardNumber.mount('#card_number');
    cardExpiry.mount('#expiration_date');
    cardCvc.mount('#cvv');
    
    // Handle form submission
    const form = document.getElementById('payment-form');
    const submitButton = document.getElementById('pay-btn');
    
    form.addEventListener('submit', async function(event) {
        event.preventDefault();
        
        // Disable submit button to prevent multiple submissions
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        }
        
        // Create token with Stripe
        const {token, error} = await stripe.createToken(cardNumber);
        
        if (error) {
            // Show error to user
            const errorElement = document.getElementById('card-errors');
            if (errorElement) {
                errorElement.textContent = error.message;
            } else {
                alert(error.message);
            }
            
            // Re-enable submit button
            if (submitButton) {
                submitButton.disabled = false;
                submitButton.innerHTML = 'Pay Now <i class="fas fa-arrow-right" style="margin-left: 0.5rem;"></i>';
            }
        } else {
            // Add token to form as hidden input
            const tokenInput = document.createElement('input');
            tokenInput.setAttribute('type', 'hidden');
            tokenInput.setAttribute('name', 'stripeToken');
            tokenInput.setAttribute('value', token.id);
            form.appendChild(tokenInput);
            
            // Show payment processing loader
            showPaymentLoader();
            
            // Submit the form
            form.submit();
        }
    });
    
    // Add error display element if not exists
    if (!document.getElementById('card-errors')) {
        const errorDiv = document.createElement('div');
        errorDiv.id = 'card-errors';
        errorDiv.style.color = '#fa755a';
        errorDiv.style.marginTop = '10px';
        errorDiv.style.fontSize = '14px';
        form.insertBefore(errorDiv, form.firstChild);
    }
    
    // Payment Loader Functions
    function showPaymentLoader() {
        const loader = document.getElementById('payment-loader');
        if (loader) {
            loader.style.display = 'flex';
            // Disable form submission to prevent double-submit
            document.getElementById('payment-form').style.pointerEvents = 'none';
            document.getElementById('payment-form').style.opacity = '0.5';
        }
    }
    
    function hidePaymentLoader() {
        const loader = document.getElementById('payment-loader');
        if (loader) {
            loader.style.display = 'none';
            document.getElementById('payment-form').style.pointerEvents = 'auto';
            document.getElementById('payment-form').style.opacity = '1';
        }
    }
</script>

{{-- @if($footer && $website)
    @include('layouts.new-footer')
@endif --}}

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
