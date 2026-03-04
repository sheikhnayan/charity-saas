<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{$data->title}} - Auction</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <!-- Google Fonts - Outfit -->
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <!-- Custom Fonts CSS -->
  <link href="{{ route('fonts.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  
  <style>
    @php
        $url = url()->current();
        $domain = parse_url($url, PHP_URL_HOST);
        $check = \App\Models\Website::where('domain', $domain)->first();
        $customFonts = \App\Models\CustomFont::get();
    @endphp
    
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
    
    /* ---- Reset & Base (EXACT MATCH TO PRODUCT-DETAILS) ---- */
    :root{
      --pd-bg: {{ json_encode($data->page_bg_color ?? $check->property_details_bg_color ?? '#f5f6f7') }};
      --pd-text: {{ json_encode($check->property_details_text_color ?? '#111827') }};
      --pd-muted: {{ json_encode($check->property_details_muted_color ?? '#6b7280') }};
      --pd-price: {{ json_encode($check->property_details_price_color ?? '#111827') }};
      
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

    /* ---- Main layout ---- */
    .container{max-width:var(--page-max);margin:0 auto;padding:0 18px}
    .grid{margin-top:12px}
    .grid .row{align-items:flex-start}
    
    .grid .col-lg-8 {
        padding-right: 14px;
    }
    .grid .col-lg-4 {
        padding-left: 14px;
    }
    
    @media (min-width: 992px) {
        .grid .col-lg-4 {
            flex: 0 0 360px;
            max-width: 360px;
        }
        .grid .col-lg-8 {
            flex: 1;
            max-width: calc(100% - 360px);
        }
    }

    /* ---- Left column - gallery + auction details ---- */
    .gallery-wrap{background:var(--card);border-radius:12px;padding:18px;border:1px solid #e9e9ea}
    .gallery-top{display:flex;gap:18px}
    .thumbs{width:84px;display:flex;flex-direction:column;gap:12px}
    .thumbs button{background:transparent;border:0;padding:0;cursor:pointer}
    .thumbs img{width:72px;height:72px;object-fit:cover;border-radius:8px;border:2px solid transparent}
    .thumbs img.active{border-color:#0066cc}
    .main-media{flex:1;background:#fff;border-radius:10px;padding:18px;display:flex;align-items:center;justify-content:center;border:1px solid #f0f0f1}
    .main-media img{max-width:100%;max-height:540px;border-radius:8px}
    .media-controls{display:flex;align-items:center;gap:8px;margin-top:10px}
    .media-controls button{padding:8px 10px;border-radius:8px;border:1px solid #e6e6e8;background:#fff;cursor:pointer}

    /* ---- Right column - bid panel ---- */
    .panel{background:var(--card);border-radius:12px;padding:18px;border:1px solid #e9e9ea;position:sticky;top:20px}
    .title{font-size:20px;font-weight:700;margin-bottom:6px}
    .subtitle{color:var(--muted);font-size:13px;margin-bottom:12px}
    .price{font-size:32px;color:#111;font-weight:800;margin-bottom:8px}
    .condition{font-size:13px;color:var(--muted);margin-bottom:10px}

    .btn{display:inline-flex;align-items:center;justify-content:center;padding:12px 14px;border-radius:10px;font-weight:700;cursor:pointer}
    .btn.primary{background:#0066cc;color:#fff;border:0;width:100%}
    .btn.ghost{background:#fff;border:1px solid #d9d9db;color:#0066cc}

    .panel .small{font-size:13px;color:var(--muted);margin-top:10px}

    /* ---- Auction Timer Styling (Styled to match product panel) ---- */
    .timer-box{
        background:linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color:#fff;
        padding:16px;
        border-radius:10px;
        margin-bottom:16px;
        text-align:center
    }
    .timer-box .timer-label{font-size:12px;text-transform:uppercase;margin-bottom:8px;opacity:0.9}
    .timer-box .timer-display{display:flex;justify-content:center;gap:12px}
    .timer-box .timer-unit{display:flex;flex-direction:column;align-items:center}
    .timer-box .timer-value{font-size:28px;font-weight:800;line-height:1}
    .timer-box .timer-period{font-size:10px;text-transform:uppercase;margin-top:4px;opacity:0.8}

    /* ---- Similar / explore sections ---- */
    .section{margin-top:20px; margin-bottom: 20px;}
    .section h3{font-size:16px;margin:0 0 12px}
    .cards{display:flex;gap:12px;overflow:auto;padding-bottom:6px}
    .card{background:#fff;padding:10px;border-radius:10px;min-width:200px;border:1px solid #eee}
    .card img{height:120px;object-fit:cover;border-radius:8px}
    .card .meta{padding-top:10px;font-size:13px;color:var(--muted)}

    /* ---- Detailed description / long content ---- */
    .desc{background:#fff;padding:18px;border-radius:10px;border:1px solid #efeff0;margin-top:18px}
    .desc h4{margin-top:0}

    /* ---- Bid History Table (Styled to match product design) ---- */
    .bid-history{background:#fff;padding:18px;border-radius:10px;border:1px solid #efeff0;margin-top:18px}
    .bid-history h4{margin-top:0;margin-bottom:16px;font-size:18px;font-weight:700}
    .bid-history table{width:100%;border-collapse:collapse}
    .bid-history thead{background:#f8f9fa;border-radius:8px}
    .bid-history th{padding:12px;text-align:left;font-size:13px;font-weight:600;color:#6b7280}
    .bid-history td{padding:12px;border-bottom:1px solid #f0f0f1;font-size:14px}
    .bid-history tbody tr:last-child td{border-bottom:none}
    .bid-history tbody tr:hover{background:#f8f9fa}

    /* ---- Utilities / responsive ---- */
    .muted{color:var(--muted)}
    .small{font-size:13px;color:var(--muted)}

    @media (max-width:520px){.thumbs{display:none}.main-media img{max-height:320px}}
    
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
    
    .contact-topbar .contact-item a {
        transition: all 0.3s ease;
        font-family: Outfit,sans-serif;
        text-decoration: underline !important;
    }
    
    @media (max-width: 768px) {
        .contact-topbar {
            padding: 8px 0 !important;
            font-size: 12px !important;
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
        .contact-topbar{
            height: 28px !important;
        }
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
  </style>
</head>
<body class="auction-details-page" style="background-color: {{ $data->page_bg_color ?? '#f5f6f7' }} !important;">

    
    @php
        $groups = \App\Models\User::where('website_id', $check->id)->where('role','group_leader')->get();
        
        $user_id = $check->user_id;
        $header = \App\Models\Header::where('user_id', $user_id)->first();
        $footer = \App\Models\Footer::where('user_id', $user_id)->first();
        $setting = \App\Models\Setting::where('user_id', $user_id)->first();
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
                        
                        const totalHeightRem = totalNavHeight / 16;
                        const totalHeightRemMobile = (totalNavHeight + (contactTopbar ? 8 : 0)) / 16;
                        const totalHeightRemSmall = (totalNavHeight - (contactTopbar ? contactTopbarHeight * 0.3 : 0)) / 16;
                        const mainContentMargin = totalWithInvestorBar / 16 + 0.5;
                        
                        document.documentElement.style.setProperty('--navbar-total-height', `${totalHeightRem}rem`);
                        document.documentElement.style.setProperty('--navbar-total-height-mobile', `${totalHeightRemMobile}rem`);
                        document.documentElement.style.setProperty('--navbar-total-height-small', `${totalHeightRemSmall}rem`);
                        document.documentElement.style.setProperty('--main-content-margin-top', `${mainContentMargin}rem`);
                    }
                }
                
                document.addEventListener('DOMContentLoaded', function() {
                    setTimeout(updateNavbarHeights, 50);
                });
                window.addEventListener('resize', updateNavbarHeights);
                if (document.fonts) {
                    document.fonts.ready.then(updateNavbarHeights);
                }
                setTimeout(updateNavbarHeights, 100);
                setTimeout(updateNavbarHeights, 300);
                setTimeout(updateNavbarHeights, 500);
                setTimeout(updateNavbarHeights, 1000);
            </script>
        @endif
    @endif

  <main class="container" style="margin-top: 14rem;">
      <div style="">
    @include('partials.back-button')
  </div>
    <div class="grid">
      <div class="row">
        <!-- LEFT: Gallery, description, bid history -->
        <div class="col-12 col-lg-8">
          <section>
            <div class="gallery-wrap" id="galleryWrap">
              <div class="gallery-top">
                <div class="thumbs" id="thumbsCol">
                  @foreach ($data->images as $item)
                      <button aria-label="thumbnail {{ $loop->index + 1 }}">
                          <img src="{{ asset('uploads/'.$item->image) }}" data-full="{{ asset('uploads/'.$item->image) }}" {{ $loop->index == 0 ? 'class=active' : '' }} alt="thumb{{ $loop->index + 1 }}">
                      </button>
                  @endforeach
                </div>

                <div class="main-media" id="mainMedia">
                  <img id="mainImg" src="{{ asset('uploads/'.$data->images[0]->image) }}" alt="auction item" />
                </div>
              </div>

              <div class="media-controls">
                <button id="zoomBtn">🔍 Zoom</button>
                <button id="prevBtn">◀</button>
                <button id="nextBtn">▶</button>
              </div>

              <!-- Similar auctions -->
              <div class="section">
                <h3>Other Auctions from {{ $check->name }}</h3>
                <div class="cards" id="similarCards" style="max-width: 710px;">
                  @php
                    $similar = \App\Models\Auction::where('website_id',$check->id)->where('status',1)->get();
                  @endphp
                  @foreach ($similar as $item)
                  @if ($item->id != $data->id)
                      <a href="/product/{{ Str::slug($item->title) }}">
                        <div class="card" style="max-width: 178px;"><img src="{{ asset('uploads/'.$item->images[0]->image) }}" alt="{{$item->title}}" style="width: 100%"><div class="meta">{{$item->title}}<br><strong>Current: ${{ number_format($item->starting_price, 2) }}</strong></div></div>
                      </a>
                  @endif
                  @endforeach
                </div>
              </div>

              <!-- Item description -->
              <div class="desc" id="desc">
                <h4>Auction Description</h4>
                {!! $data->description !!}
              </div>

              <!-- Bid History (LIVE BIDDING SECTION) -->
              <div class="bid-history">
                <h4><i class="fas fa-history me-2"></i>Live Bid History</h4>
                <div style="overflow-x: auto;">
                  <table>
                    <thead>
                      <tr>
                        <th>Bidder Name</th>
                        <th>Date & Time</th>
                        <th>Bid Amount</th>
                      </tr>
                    </thead>
                    <tbody id="bid-history-body">
                      <tr>
                        <td colspan="3" style="text-align:center;color:#999">Loading bid history...</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </section>
        </div>

        <!-- RIGHT: Auction bid panel -->
        <div class="col-12 col-lg-4">
          <aside>
            <div class="panel" role="region" aria-label="bid panel">
              <!-- Auction Timer -->
              <div class="timer-box">
                <div class="timer-label">Time Remaining</div>
                <div class="timer-display">
                  <div class="timer-unit">
                    <div class="timer-value" id="days-{{ $data->id }}">0</div>
                    <div class="timer-period">Days</div>
                  </div>
                  <div class="timer-unit">
                    <div class="timer-value" id="hours-{{ $data->id }}">0</div>
                    <div class="timer-period">Hrs</div>
                  </div>
                  <div class="timer-unit">
                    <div class="timer-value" id="minutes-{{ $data->id }}">0</div>
                    <div class="timer-period">Mins</div>
                  </div>
                  <div class="timer-unit">
                    <div class="timer-value" id="seconds-{{ $data->id }}">0</div>
                    <div class="timer-period">Secs</div>
                  </div>
                </div>
              </div>

              <div class="title">{{$data->title}}</div>
              <div class="subtitle">Auction by <strong>{{$check->name}}</strong></div>
              
              <!-- Current Bid Price -->
              <div class="condition" style="font-size:12px;color:#666;margin-bottom:4px">Current Bid</div>
              <div class="price" id="auction-price-{{ $data->id }}" style="color:#0066cc">${{ number_format($data->starting_price, 2) }}</div>

              <div style="height:16px"></div>
              
              <!-- Place Bid Button -->
              <button class="btn primary" id="placeBidBtn" type="button">
                <i class="fas fa-gavel me-2"></i>Place Your Bid
              </button>

              <div style="margin-top:16px;padding:14px;background:#f8f9fa;border-radius:8px;border:1px solid #e9ecef">
                <div class="form-check d-flex align-items-start">
                  <input class="form-check-input mt-1" type="checkbox" id="bindingBidAgreement" required style="cursor:pointer">
                  <label class="form-check-label ms-2" for="bindingBidAgreement" style="font-size:13px;color:#111;cursor:pointer">
                    I agree my bid is binding and authorizes a temporary payment hold (up to 30 days).
                    <i class="fas fa-info-circle ms-1" style="color:#6b7280;cursor:help" 
                       data-bs-toggle="tooltip" 
                       data-bs-placement="top" 
                       title="Binding Bid Notice: Bids are binding. A temporary authorization hold may be placed on your payment method for up to 30 days. If you win, payment is required."></i>
                  </label>
                </div>
              </div>

              {{-- <div style="height:12px;border-top:1px solid #f0f0f1;margin-top:16px;padding-top:12px">
                <div class="small muted">Payment methods</div>
                <div style="display:flex;gap:8px;margin-top:8px">
                  <span style="background:#fff;padding:6px 8px;border-radius:8px;border:1px solid #efeff0;font-size:12px">VISA</span>
                  <span style="background:#fff;padding:6px 8px;border-radius:8px;border:1px solid #efeff0;font-size:12px">Mastercard</span>
                  <span style="background:#fff;padding:6px 8px;border-radius:8px;border:1px solid #efeff0;font-size:12px">PayPal</span>
                  <span style="background:#fff;padding:6px 8px;border-radius:8px;border:1px solid #efeff0;font-size:12px">Apple Pay</span>
                </div>
              </div> --}}
            </div>

            <!-- Website Info -->
            <div class="section">
              <div style="background:#fff;padding:14px;border-radius:10px;border:1px solid #efeff0;margin-top:16px">
                <div style="display:flex;gap:12px;align-items:center">
                  <div style="width:64px;height:64px;border-radius:999px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;overflow:hidden">
                    <img src="{{ asset('uploads/'.$check->setting->logo) }}" alt="{{$check->name}}" style="width:100%;height:100%;object-fit:cover">
                  </div>
                  <div style="font-size:14px">
                    <div style="font-weight:700;margin-bottom:4px">{{$check->name}}</div>
                    <div class="small muted">Auction Host</div>
                  </div>
                </div>
              </div>
            </div>

          </aside>
        </div>
      </div>
    </div>
  </main>

  <!-- Footer -->
  @if ($footer && $footer->status == 1)
     @include('layouts.new-footer')
  @endif

  <!-- Bid Modal -->
  <div class="modal fade" id="bidModal" tabindex="-1" aria-labelledby="bidModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form id="bidForm" class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="bidModalLabel">Place Your Bid</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3" id="bidderNameGroup">
            <label for="bidderName" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="bidderName">
          </div>
          <div class="mb-3" id="bidderEmailGroup">
            <label for="bidderEmail" class="form-label">Email Address</label>
            <input type="email" class="form-control" id="bidderEmail">
          </div>
          <div class="mb-3">
            <label for="bidAmount" class="form-label">Your Bid Amount</label>
            <input type="number" class="form-control" id="bidAmount" min="1" step="0.01" required>
            <div class="invalid-feedback" id="bidAmountError"></div>
            <div class="form-text">Minimum bid must be higher than current bid</div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Submit Bid</button>
        </div>
      </form>
    </div>
  </div>

  <input type="hidden" id="product-id" value="{{ $data->id }}">

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // --- Gallery thumbnail interactions (EXACT MATCH TO PRODUCT-DETAILS) ---
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
        window.open(mainImg.src, '_blank');
      });
    })();

    // --- Auction Timer ---
    function startAuctionTimer(deadline, id) {
      function update() {
        const now = new Date().getTime();
        const target = new Date(deadline).getTime();
        let timeLeft = target - now;

        if (timeLeft <= 0) {
          document.getElementById('days-' + id).textContent = 0;
          document.getElementById('hours-' + id).textContent = 0;
          document.getElementById('minutes-' + id).textContent = 0;
          document.getElementById('seconds-' + id).textContent = 0;
          return;
        }

        const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
        const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

        document.getElementById('days-' + id).textContent = days;
        document.getElementById('hours-' + id).textContent = hours;
        document.getElementById('minutes-' + id).textContent = minutes;
        document.getElementById('seconds-' + id).textContent = seconds;
      }
      update();
      setInterval(update, 1000);
    }

    document.addEventListener('DOMContentLoaded', function() {
      startAuctionTimer("{{ $data->dead_line }}", "{{ $data->id }}");
      
      // Initialize Bootstrap tooltips
      const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
      tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
      });
    });
  </script>

  <script type="module">
    import { initializeApp, getApps } from "https://www.gstatic.com/firebasejs/11.9.1/firebase-app.js";
    import { getFirestore, collection, addDoc, query, where, orderBy, getDocs, limit } from "https://www.gstatic.com/firebasejs/11.9.1/firebase-firestore.js";

    // Number format helper
    const formatMoney = (num, decimals = 0) =>
        Number(num).toLocaleString('en-US', {
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals
        });

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

    const auctionId = "{{ $data->id }}";
    let lastBid = Number("{{ $data->starting_price ?? 0 }}");
    const priceDiv = document.getElementById('auction-price-{{ $data->id }}');
    const bidAmountInput = document.getElementById('bidAmount');

    // Show latest bid from Firebase
    async function showLatestBid() {
        const q = query(
            collection(firestore, "bid"),
            where("auction_id", "==", auctionId),
            orderBy("amount", "desc"),
            limit(1)
        );
        const querySnapshot = await getDocs(q);
        if (!querySnapshot.empty) {
            const amount = querySnapshot.docs[0].data().amount;
            lastBid = Number(amount);
            priceDiv.textContent = '$' + formatMoney(lastBid, 2);
            if (bidAmountInput) bidAmountInput.min = lastBid + 0.01;
        }
    }

    // Load bid history from Firebase
    async function loadBidHistory() {
        const q = query(
            collection(firestore, "bid"),
            where("auction_id", "==", auctionId),
            orderBy("timestamp", "desc")
        );
        const querySnapshot = await getDocs(q);

        const tbody = document.getElementById('bid-history-body');
        tbody.innerHTML = '';

        if (querySnapshot.empty) {
            tbody.innerHTML = `<tr><td colspan="3" style="text-align:center;color:#999;padding:20px">No bids yet. Be the first to bid!</td></tr>`;
            return;
        }

        querySnapshot.forEach(doc => {
            const bid = doc.data();
            const date = bid.timestamp && bid.timestamp.toDate
                         ? bid.timestamp.toDate()
                         : new Date(bid.timestamp);

            const formattedDate = date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) + ' ' +
                                  date.toLocaleDateString([], { day: '2-digit', month: 'short', year: 'numeric' });

            tbody.innerHTML += `
                <tr>
                    <td style="font-weight:600">${bid.name || 'Anonymous'}</td>
                    <td style="color:#6b7280">${formattedDate}</td>
                    <td style="font-weight:700;color:#0066cc">$${formatMoney(bid.amount, 2)}</td>
                </tr>
            `;
        });
    }

    // Poll for new bids every 5 seconds (LIVE UPDATES)
    function startBidPolling() {
      setInterval(async () => {
        try {
          await showLatestBid();
        } catch (error) {
          console.log('Polling error:', error);
        }
      }, 5000);
    }

    // Function to open bid modal
    function openBidModal(authStatus = null) {
      // If user is authenticated, hide name/email fields and populate them
      if (authStatus && authStatus.authenticated) {
        document.getElementById('bidderNameGroup').style.display = 'none';
        document.getElementById('bidderEmailGroup').style.display = 'none';
        
        // Populate hidden fields with auth user data
        if (authStatus.user) {
          document.getElementById('bidderName').value = authStatus.user.name || '';
          document.getElementById('bidderEmail').value = authStatus.user.email || '';
        }
      } else {
        // Show fields for non-authenticated users
        document.getElementById('bidderNameGroup').style.display = 'block';
        document.getElementById('bidderEmailGroup').style.display = 'block';
        document.getElementById('bidderName').setAttribute('required', 'required');
        document.getElementById('bidderEmail').setAttribute('required', 'required');
      }
      
      const modal = new bootstrap.Modal(document.getElementById('bidModal'));
      modal.show();
    }

    // Monitor auth modal close to open bid modal if login was successful
    function setupAuthModalListener() {
      const authModal = document.getElementById('authModal');
      if (authModal) {
        authModal.addEventListener('hidden.bs.modal', async function () {
          if (window._isAuctionBid && window._auctionId === '{{ $data->id }}') {
            // Check if user is now authenticated
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            if (csrfToken) {
              try {
                const authCheck = await fetch('/ajax/ticket-auth/check', {
                  method: 'POST',
                  headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                  },
                  body: JSON.stringify({})
                });
                
                if (authCheck.ok) {
                  const authStatus = await authCheck.json();
                  
                  if (authStatus.authenticated && authStatus.verified) {
                    // User successfully logged in - open bid modal
                    openBidModal(authStatus);
                    window._isAuctionBid = false;
                  }
                }
              } catch (error) {
                console.log('Error checking auth after modal close:', error);
              }
            }
          }
        });
      }
    }
    
    // Call this when page loads
    document.addEventListener('DOMContentLoaded', () => {
        setupAuthModalListener();
        showLatestBid();
        loadBidHistory();
        startBidPolling();
    });

    // Listen for successful login to auto-open bid modal
    window.addEventListener('authSuccess', function(event) {
      if (window._isAuctionBid && window._auctionId === '{{ $data->id }}') {
        // User just logged in and wanted to place a bid
        setTimeout(async () => {
          const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
          
          // Create a default authenticated status
          let authStatus = {
            authenticated: true,
            verified: true,
            user: {
              name: '',
              email: ''
            }
          };
          
          if (csrfToken) {
            try {
              const authCheck = await fetch('/ajax/ticket-auth/check', {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({})
              });
              
              if (authCheck.ok) {
                authStatus = await authCheck.json();
              }
            } catch (error) {
              console.log('Using default auth status due to error:', error);
            }
          }
          
          openBidModal(authStatus);
          // Reset the flag
          window._isAuctionBid = false;
        }, 500);
      }
    });

    // Also check periodically if user authenticated (fallback)
    let authCheckInterval;
    let authCheckAttempts = 0;
    const maxAuthCheckAttempts = 60; // Stop after 60 seconds
    
    function startAuthCheck() {
      authCheckAttempts = 0;
      authCheckInterval = setInterval(async () => {
        if (window._isAuctionBid && window._auctionId === '{{ $data->id }}') {
          authCheckAttempts++;
          
          // Stop polling after max attempts
          if (authCheckAttempts > maxAuthCheckAttempts) {
            clearInterval(authCheckInterval);
            window._isAuctionBid = false;
            return;
          }
          
          const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
          if (!csrfToken) {
            return; // Skip if no CSRF token available
          }
          
          try {
            const authCheck = await fetch('/ajax/ticket-auth/check', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
              },
              body: JSON.stringify({})
            });
            
            // Check for CSRF token expiration (419)
            if (authCheck.status === 419) {
              // CSRF token expired - stop polling
              clearInterval(authCheckInterval);
              window._isAuctionBid = false;
              return;
            }
            
            if (!authCheck.ok) {
              return; // Skip this attempt on other errors
            }
            
            const authStatus = await authCheck.json();
            
            if (authStatus.authenticated && authStatus.verified) {
              // User is now authenticated - open bid modal
              clearInterval(authCheckInterval);
              openBidModal(authStatus);
              window._isAuctionBid = false;
            }
          } catch (error) {
            // Silently skip errors during polling
            if (authCheckAttempts % 10 === 0) {
              console.log('Auth check still running...', authCheckAttempts);
            }
          }
        } else {
          clearInterval(authCheckInterval);
        }
      }, 1000);
    }

    // Open bid modal with auth check
    document.getElementById('placeBidBtn').addEventListener('click', async function(e) {
      e.preventDefault();
      
      // Check binding agreement checkbox first
      const bindingCheckbox = document.getElementById('bindingBidAgreement');
      if (!bindingCheckbox.checked) {
        bindingCheckbox.classList.add('is-invalid');
        bindingCheckbox.parentElement.parentElement.style.borderColor = '#dc3545';
        alert('Please agree to the binding bid terms before placing your bid.');
        return;
      }
      bindingCheckbox.classList.remove('is-invalid');
      bindingCheckbox.parentElement.parentElement.style.borderColor = '#e9ecef';
      
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      
      try {
        // Check if user is authenticated and verified
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
          window._isAuctionBid = true;
          window._auctionId = '{{ $data->id }}';
          
          // Start polling for auth success
          startAuthCheck();
          
          // Open auth modal
          if (typeof setAuthMode === 'function') {
            setAuthMode('login');
          }
          if (typeof openAuthModal === 'function') {
            openAuthModal();
          }
          return;
        }

        // User is authenticated - show bid modal
        openBidModal(authStatus);
        
      } catch (error) {
        console.error('Authentication check failed:', error);
        // Fallback - show bid modal anyway
        openBidModal();
      }
    });

    // Submit bid to Firebase
    document.getElementById('bidForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        const name = document.getElementById('bidderName').value.trim();
        const email = document.getElementById('bidderEmail').value.trim();
        const amount = Number(document.getElementById('bidAmount').value);

        // Validate amount
        if (isNaN(amount) || amount <= lastBid) {
            const bidInput = document.getElementById('bidAmount');
            bidInput.classList.add('is-invalid');
            document.getElementById('bidAmountError').textContent =
                `Bid must be greater than $${formatMoney(lastBid, 2)}`;
            return;
        } else {
            document.getElementById('bidAmount').classList.remove('is-invalid');
            document.getElementById('bidAmountError').textContent = '';
        }

        // Store bid data in sessionStorage for after payment
        sessionStorage.setItem('pendingBid', JSON.stringify({
            auction_id: auctionId,
            name: name,
            email: email,
            amount: amount,
            timestamp: new Date().toISOString()
        }));

        // Close modal
        const modalEl = document.getElementById('bidModal');
        let modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
        modal.hide();

        // Redirect to payment - bid will be saved after successful payment
        const id = document.getElementById('product-id').value;
        window.location.href = `/authorize/payment/auction/${id}?amount=${amount}&name=${encodeURIComponent(name)}&email=${encodeURIComponent(email)}`;
    });
  </script>
  
  @include('partials.ticket-auth-modal')
</body>
</html>
