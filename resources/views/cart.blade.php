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
    <title>{{ $check->name ?? 'Cart' }}</title>
    
    <!-- Cart Queue Stub for consistency with investment page -->
    <script>
      if (!window._cartQueue) { window._cartQueue = []; }
      window.addToCart = function(itemData) {
        if (window.ShoppingCart && typeof window.ShoppingCart.addItem === 'function') {
          return window.ShoppingCart.addItem(itemData);
        }
        window._cartQueue.push(itemData);
        return true;
      };
    </script>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('auction.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cart.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts - Outfit to match page-investment -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="{{ route('fonts.css') }}" rel="stylesheet">
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

    .link_wrap div{
        font-family: Outfit,sans-serif !important;
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
    </style>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>    <style>
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
    </style></head>
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
        @if ($check && ($check->type ?? null) === 'investment')
        <style>
            /* Match page-investment navbar compact padding */
            .navbar-expand-xl{
                padding-top: 0px !important;
                padding-bottom: 0px !important;
            }
        </style>
        @endif
        <div class="container my-5" style="margin-top: 6rem !important;">
            <div class="row">
                <div class="col-lg-8">
                    <h1 class="mb-4">🛒 Cart</h1>
                    <div id="cartItemsContainer" class="card shadow-sm">
                        <div id="cartEmpty" class="card-body p-5 text-center">
                            <i class="fas fa-shopping-cart" style="font-size: 48px; color: #999; margin-bottom: 20px;"></i>
                            <h4 class="text-muted">Your cart is empty</h4>
                            <p class="text-muted mb-0">Add some items to get started!</p>
                        </div>
                        <div id="cartItems" class="card-body" style="display: none;"></div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card shadow-sm position-sticky" style="margin-top: 87px;">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Order Summary</h5>
                            
                            <!-- Items Breakdown -->
                            <div id="summaryItemsBreakdown" style="margin-bottom: 20px;">
                                <!-- Items will be populated here by JavaScript -->
                            </div>

                            <hr style="margin: 15px 0;">
                            
                            <!-- Total -->
                            <div class="d-flex justify-content-between mb-4">
                                <strong>Total:</strong>
                                <strong id="summaryTotal" style="color: #667eea; font-size: 18px;">$0.00</strong>
                            </div>
                            
                            <button class="btn btn-primary w-100 mb-2" id="checkoutBtn">
                                <i class="fas fa-lock me-2"></i> Proceed to Checkout
                            </button>
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-arrow-left me-2"></i> Continue Exploring
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <template id="cartItemTemplate">
            <div class="cart-item border-bottom pb-4 mb-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="mb-1 fw-bold item-name"></h6>
                        <small class="text-muted">Item ID: <span class="item-id"></span></small>
                    </div>
                    <button type="button" class="btn-remove btn btn-sm btn-outline-danger">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="mb-3 item-price-section">
                    <small class="text-muted d-block mb-2">Price: <strong class="item-price text-dark"></strong></small>
                </div>
                <div class="d-flex align-items-center gap-2 mb-3 quantity-controls">
                    <span class="text-muted">Quantity:</span>
                    <button type="button" class="btn-qty btn btn-sm btn-outline-secondary" data-action="decrease">−</button>
                    <input type="number" class="form-control item-quantity" style="width: 60px; text-align: center;" min="1" value="1">
                    <button type="button" class="btn-qty btn btn-sm btn-outline-secondary" data-action="increase">+</button>
                </div>
                <div class="donation-amount-section mb-3" style="display: none;">
                    <label class="form-label text-muted mb-2">
                        <i class="fas fa-heart text-danger"></i> Donation Amount
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" class="form-control donation-amount" placeholder="0.00" min="0" step="0.01" value="0">
                    </div>
                </div>
                <div class="text-end donation-total-section" style="display: none;">
                    <span class="text-muted">Donation: </span>
                    <strong class="donation-total text-danger">$0.00</strong>
                </div>
            </div>
        </template>
    </main>

    @if ($check && ($check->is_main_site ?? 0) == 1)
        @include('layouts.main_footer')
    @else
        @if ($footer && $footer->status == 1)
            @include('layouts.new-footer')
        @endif
    @endif

    @php
        $cartSession = session('shopping_cart');
        $availabilityMap = [];
        if (!empty($cartSession['items'])) {
            foreach ($cartSession['items'] as $cartItem) {
                if (isset($cartItem['type'], $cartItem['id']) && in_array($cartItem['type'], ['ticket', 'product'], true)) {
                    $ticket = \App\Models\Ticket::find($cartItem['id']);
                    if ($ticket && $ticket->quantity !== null && $ticket->quantity !== '') {
                        $availabilityMap[$cartItem['id']] = (int) $ticket->quantity;
                    }
                }
            }
        }
    @endphp

    <script>
        window._cartAvailability = @json($availabilityMap);
    </script>

<style>
    .cart-item { animation: slideIn 0.3s ease; }
    @keyframes slideIn { from { opacity: 0; transform: translateY(10px);} to { opacity: 1; transform: translateY(0);} }
    .btn-qty { padding: 4px 10px; font-size: 14px; }
    .btn-remove { padding: 4px 8px; }
    .btn-remove:hover { background-color: #f8d7da !important; color: #d32f2f !important; }
    #checkoutBtn { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; font-weight: 500; transition: all 0.3s ease; }
    #checkoutBtn:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4); }
    .form-control { border: 1px solid #ddd; padding: 6px; font-size: 14px; }
    .form-control:focus { border-color: #667eea; box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25); }
    #floatingCartButton, #cartIcon { display: none !important; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('🛒 [CART PAGE] Initializing cart page...');
    loadCartDataFromAPI();
});

function loadCartDataFromAPI() {
    fetch('/api/cart')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.cart) { renderCart(data.cart); }
        })
        .catch(error => { console.error('❌ [CART PAGE] Error loading cart:', error); });
}

function renderCart(cartData) {
    const cartItemsContainer = document.getElementById('cartItems');
    const cartEmptyContainer = document.getElementById('cartEmpty');
    let itemsArray = [];
    
    // UPDATED: Use items_by_type structure (consistent with cart.js)
    if (cartData.items_by_type && typeof cartData.items_by_type === 'object') {
        // Flatten items_by_type into a single array
        Object.keys(cartData.items_by_type).forEach(type => {
            const typeItems = cartData.items_by_type[type];
            if (Array.isArray(typeItems)) {
                itemsArray = itemsArray.concat(typeItems);
            }
        });
    }
    // Fallback for old format (items as object)
    else if (cartData.items && typeof cartData.items === 'object' && !Array.isArray(cartData.items)) {
        itemsArray = Object.entries(cartData.items).map(([key, item]) => ({ ...item, key }));
    } else if (Array.isArray(cartData.items)) { 
        itemsArray = cartData.items; 
    }
    
    if (!itemsArray || itemsArray.length === 0) {
        cartEmptyContainer.style.display = 'block';
        cartItemsContainer.style.display = 'none';
        updateSummary([]);
        return;
    }
    cartItemsContainer.innerHTML = '';
    cartEmptyContainer.style.display = 'none';
    cartItemsContainer.style.display = 'block';
    itemsArray.forEach((item, index) => { cartItemsContainer.appendChild(createCartItemElement(item, index)); });
    updateSummary(itemsArray);
}

function createCartItemElement(item, index) {
    const template = document.getElementById('cartItemTemplate');
    const clone = template.content.cloneNode(true);
    const itemKey = item.key || (item.type + '_' + item.id);
    const isStudent = item.type === 'student';
    clone.querySelector('.item-name').textContent = item.name || 'Unknown Item';
    clone.querySelector('.item-id').textContent = item.id || 'N/A';
    clone.querySelector('.item-price').textContent = '$' + (item.price || item.amount || 0).toFixed(2);
    const quantityInput = clone.querySelector('.item-quantity');
    const quantityControls = clone.querySelector('.quantity-controls');
    const itemPriceSection = clone.querySelector('.item-price-section');
    const donationSection = clone.querySelector('.donation-amount-section');
    const donationInput = clone.querySelector('.donation-amount');
    const donationTotalSection = clone.querySelector('.donation-total-section');
    const donationTotal = clone.querySelector('.donation-total');
    const availabilityMap = window._cartAvailability || {};
    const maxQty = (item.type === 'ticket' || item.type === 'product')
        ? Number(availabilityMap[item.id] ?? item.available_quantity ?? 0)
        : 0;

    quantityInput.value = item.quantity || 1;
    if (maxQty > 0) {
        quantityInput.setAttribute('max', String(maxQty));
        if (parseInt(quantityInput.value) > maxQty) {
            quantityInput.value = maxQty;
            updateItemQuantity(itemKey, maxQty);
        }
    }
    if (isStudent) {
        if (quantityControls) { quantityControls.style.cssText = 'display: none !important;'; quantityControls.classList.add('hidden'); }
        if (itemPriceSection) { itemPriceSection.style.cssText = 'display: none !important;'; }
        if (donationSection) { donationSection.style.display = 'block'; }
        if (donationTotalSection) { donationTotalSection.style.display = 'block'; }
        if (donationInput) { donationInput.value = item.amount || 0; }
    } else {
        if (quantityControls) { quantityControls.style.cssText = 'display: flex !important;'; }
        if (itemPriceSection) { itemPriceSection.style.cssText = 'display: block !important;'; }
        if (donationSection) { donationSection.style.display = 'none'; }
        if (donationTotalSection) { donationTotalSection.style.display = 'none'; }
    }
    const itemElement = { quantityInput, donationInput, donationTotal, itemKey, isStudent, basePrice: item.price || item.amount || 0, item }; 
    const updateItemTotal = () => {
        const donation = itemElement.isStudent ? (parseFloat(itemElement.donationInput.value) || 0) : 0;
        if (itemElement.isStudent && itemElement.donationTotal) { itemElement.donationTotal.textContent = '$' + donation.toFixed(2); }
        itemElement.item.amount = donation;
        updateSummary();
        return { donation };
    };
    const decreaseBtn = clone.querySelector('[data-action="decrease"]');
    const increaseBtn = clone.querySelector('[data-action="increase"]');
    const removeBtn = clone.querySelector('.btn-remove');
    if (!isStudent) {
        if (decreaseBtn) {
            decreaseBtn.addEventListener('click', function() {
                const currentQty = parseInt(quantityInput.value) || 1;
                if (currentQty > 1) {
                    quantityInput.value = currentQty - 1;
                    updateItemQuantity(itemKey, currentQty - 1);
                }
            });
        }
        if (increaseBtn) {
            increaseBtn.addEventListener('click', function() {
                const currentQty = parseInt(quantityInput.value) || 1;
                const nextQty = maxQty > 0 ? Math.min(maxQty, currentQty + 1) : currentQty + 1;
                quantityInput.value = nextQty;
                updateItemQuantity(itemKey, nextQty);
            });
        }
        quantityInput.addEventListener('change', function() {
            let newQty = parseInt(this.value) || 1;
            if (newQty < 1) newQty = 1;
            if (maxQty > 0 && newQty > maxQty) newQty = maxQty;
            this.value = newQty;
            updateItemQuantity(itemKey, newQty);
        });
    }
    if (isStudent && donationInput) {
        donationInput.addEventListener('change', function() {
            // Restrict to 2 decimal places
            const value = parseFloat(this.value) || 0;
            this.value = value.toFixed(2);
            updateItemTotal(); 
            // Save amount to API
            const amount = parseFloat(this.value) || 0;
            updateItemAmount(itemKey, amount);
        });
        donationInput.addEventListener('input', function() { 
            // Prevent more than 2 decimal places during input
            const value = this.value;
            if (value.includes('.')) {
                const parts = value.split('.');
                if (parts[1] && parts[1].length > 2) {
                    this.value = parseFloat(value).toFixed(2);
                }
            }
            updateItemTotal(); 
        });
    }
    if (removeBtn) { removeBtn.addEventListener('click', function() { removeCartItem(itemKey); }); }
    updateItemTotal();
    return clone;
}

function updateItemQuantity(itemKey, quantity) {
    fetch('/api/cart/item/' + itemKey, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
        body: JSON.stringify({ quantity })
    })
    .then(response => response.json())
    .then(data => {
        // Reload cart data from API to reflect changes
        loadCartDataFromAPI();
    })
    .catch(() => loadCartDataFromAPI());
}

function updateItemAmount(itemKey, amount) {
    fetch('/api/cart/item/' + itemKey, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
        body: JSON.stringify({ amount })
    })
    .then(response => response.json())
    .then(data => {
        console.log('Amount updated:', data);
        // Don't reload - just update summary from DOM (which already has the updated value)
        updateSummary();
    })
    .catch(error => console.error('Error updating amount:', error));
}

function removeCartItem(itemKey) {
    if (!confirm('Are you sure you want to remove this item from your cart?')) return;
    fetch('/api/cart/item/' + itemKey, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
    })
    .then(response => response.json())
    .then(() => loadCartDataFromAPI())
    .catch(() => loadCartDataFromAPI());
}

function updateSummary(items) {
    let totalDonation = 0;
    let totalItems = 0;
    let totalAmount = 0;
    let itemsBreakdown = [];

    if (!items) {
        // Calculate from DOM if items not provided
        document.querySelectorAll('.cart-item').forEach(cartItem => {
            const donationSection = cartItem.querySelector('.donation-amount-section');
            const donationInput = cartItem.querySelector('.donation-amount');
            const quantityInput = cartItem.querySelector('.item-quantity');
            const itemNameEl = cartItem.querySelector('.item-name');
            const priceText = cartItem.querySelector('.item-price')?.textContent;
            
            if (itemNameEl && priceText) {
                const itemName = itemNameEl.textContent || 'Unknown Item';
                const price = parseFloat(priceText.replace(/[$,]/g, '')) || 0;
                const qty = quantityInput ? (parseInt(quantityInput.value) || 1) : 1;
                // Check if this is a student item by seeing if donation section is VISIBLE
                const isStudent = donationSection && donationSection.style.display !== 'none';
                
                if (isStudent && donationInput) {
                    const donationAmt = parseFloat(donationInput.value) || 0;
                    totalDonation += donationAmt;
                    if (donationAmt > 0) {
                        itemsBreakdown.push({
                            name: itemName,
                            amount: donationAmt,
                            qty: 1,
                            isStudent: true
                        });
                    }
                } else {
                    const lineTotal = price * qty;
                    totalItems += lineTotal;
                    itemsBreakdown.push({
                        name: itemName,
                        price: price,
                        qty: qty,
                        lineTotal: lineTotal,
                        isStudent: false
                    });
                }
            }
        });
        totalAmount = totalDonation + totalItems;
    } else if (items && items.length > 0) {
        // Calculate from items array
        items.forEach(item => {
            if (item.type === 'student') {
                const amount = item.amount || 0;
                totalDonation += amount;
                if (amount > 0) {
                    itemsBreakdown.push({
                        name: item.name,
                        amount: amount,
                        qty: 1,
                        isStudent: true
                    });
                }
            } else {
                const price = item.price || item.current_bid || 0;
                const qty = item.quantity || 1;
                const lineTotal = price * qty;
                totalItems += lineTotal;
                itemsBreakdown.push({
                    name: item.name,
                    price: price,
                    qty: qty,
                    lineTotal: lineTotal,
                    isStudent: false
                });
            }
        });
        totalAmount = totalDonation + totalItems;
    }

    // Render items breakdown
    const breakdownContainer = document.getElementById('summaryItemsBreakdown');
    if (breakdownContainer) {
        if (itemsBreakdown.length === 0) {
            breakdownContainer.innerHTML = '';
        } else {
            let breakdownHTML = '';
            itemsBreakdown.forEach(item => {
                if (item.isStudent) {
                    breakdownHTML += `
                        <div class="d-flex justify-content-between mb-2" style="padding: 6px 0; border-bottom: 1px solid #f0f0f0;">
                            <span style="color: #666; font-size: 13px;">
                                <i class="fas fa-heart text-danger"></i> ${item.name}
                            </span>
                            <span style="font-weight: 600; color: #e74c3c;">$${item.amount.toFixed(2)}</span>
                        </div>
                    `;
                } else {
                    const qtyText = item.qty > 1 ? ` × ${item.qty}` : '';
                    breakdownHTML += `
                        <div class="d-flex justify-content-between mb-2" style="padding: 6px 0; border-bottom: 1px solid #f0f0f0;">
                            <span style="color: #666; font-size: 13px;">
                                ${item.name}${qtyText}
                            </span>
                            <span style="font-weight: 600; color: #2c3e50;">$${item.lineTotal.toFixed(2)}</span>
                        </div>
                    `;
                }
            });
            breakdownContainer.innerHTML = breakdownHTML;
        }
    }

    // Update total - correctly shows sum of all products + all donations
    document.getElementById('summaryTotal').textContent = '$' + totalAmount.toFixed(2);
    
    // Disable/enable checkout button based on total
    const checkoutBtn = document.getElementById('checkoutBtn');
    if (checkoutBtn) {
        if (totalAmount > 0) {
            checkoutBtn.disabled = false;
            checkoutBtn.style.opacity = '1';
            checkoutBtn.style.cursor = 'pointer';
        } else {
            checkoutBtn.disabled = true;
            checkoutBtn.style.opacity = '0.5';
            checkoutBtn.style.cursor = 'not-allowed';
        }
    }
}

function handleCheckout() {
    // Validate student donation amounts before checkout
    const studentDonationInputs = document.querySelectorAll('.cart-item .donation-amount-section[style*="display: block"] .donation-amount, .cart-item .donation-amount-section:not([style*="display: none"]) .donation-amount');
    let hasInvalidStudentDonation = false;

    studentDonationInputs.forEach(input => {
        // Only validate if the parent donation-amount-section is visible (student items)
        const donationSection = input.closest('.donation-amount-section');
        if (donationSection && donationSection.style.display !== 'none') {
            const value = parseFloat(input.value);
            if (isNaN(value) || value <= 0) {
                hasInvalidStudentDonation = true;
            }
        }
    });

    if (hasInvalidStudentDonation) {
        alert('Please enter an amount greater than 0 or remove the participant from the cart instead.');
        return;
    }

    // Use the Cart class's proceedToCheckout method which handles authentication
    if (typeof cart !== 'undefined' && cart.proceedToCheckout) {
        cart.proceedToCheckout();
    } else {
        console.error('Cart object not found');
        alert('Error: Cart system not initialized');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const checkoutBtn = document.getElementById('checkoutBtn');
    if (checkoutBtn) { 
        // Initialize as disabled
        checkoutBtn.disabled = true;
        checkoutBtn.style.opacity = '0.5';
        checkoutBtn.style.cursor = 'not-allowed';
        checkoutBtn.addEventListener('click', handleCheckout); 
    }
});
</script>

<!-- Include Auth Modal for checkout -->
@include('partials.ticket-auth-modal')

<!-- Load Cart JavaScript -->
<script src="{{ asset('js/cart.js') }}"></script>

</body>
</html>
