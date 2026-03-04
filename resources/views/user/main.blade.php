<!doctype html>

<html
  lang="en"
  class="layout-menu-fixed layout-compact"
  data-assets-path="{{ asset('user/assets/') }}"
  data-template="vertical-menu-template-free">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Raise Builder</title>

    <meta name="description" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @auth
    <meta name="user-id" content="{{ Auth::id() }}" />
    @endauth
    
    <!-- Firebase Configuration -->
    <meta name="firebase-api-key" content="{{ env('FIREBASE_API_KEY') }}">
    <meta name="firebase-auth-domain" content="{{ env('FIREBASE_AUTH_DOMAIN') }}">
    <meta name="firebase-project-id" content="{{ env('FIREBASE_PROJECT_ID') }}">
    <meta name="firebase-storage-bucket" content="{{ env('FIREBASE_STORAGE_BUCKET') }}">
    <meta name="firebase-messaging-sender-id" content="{{ env('FIREBASE_MESSAGING_SENDER_ID') }}">
    <meta name="firebase-app-id" content="{{ env('FIREBASE_APP_ID') }}">
    <meta name="firebase-vapid-key" content="{{ env('FIREBASE_VAPID_KEY') }}">

    <!-- PWA Manifest -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#667eea">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Fundably">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('user/assets/img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet" />

    <link rel="stylesheet" href="{{asset('user/assets/vendor/fonts/iconify-icons.css')}}" />

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Core CSS -->
    <!-- build:css assets/vendor/css/theme.css  -->

    <link rel="stylesheet" href="{{asset('user/assets/vendor/css/core.css')}}" />

    <!-- Vendors CSS -->

    <link rel="stylesheet" href="{{asset('user/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css')}}" />

    <!-- endbuild -->

    <link rel="stylesheet" href="{{asset('user/assets/vendor/libs/apex-charts/apex-charts.css')}}" />

    <!-- Page CSS -->
    
    <!-- Custom Fonts CSS (Dynamically generated from uploaded fonts) -->
    <link rel="stylesheet" href="{{ route('fonts.css') }}">

    <!-- Helpers -->
    <script src="{{asset('user/assets/vendor/js/helpers.js')}}"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->

    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->

    <script src="{{asset('user/assets/js/config.js')}}"></script>

    <!-- Modern Admin Styles -->
    <style>
      :root {
        --primary: #6366f1;
        --secondary: #f472b6;
        --accent: #22d3ee;
        --bg: #fdfbff;
        --text: #1e1b4b;
      }

      /* Theme palettes */
      .theme-purple { --primary: #7c3aed; --secondary: #a78bfa; --accent: #60a5fa; --bg: #fdf6ff; --text: #0f172a; }
      .theme-cyan { --primary: #0891b2; --secondary: #67e8f9; --accent: #7dd3fc; --bg: #f0f9ff; --text: #04272f; }
      .theme-amber { --primary: #d97706; --secondary: #f59e0b; --accent: #fbbf24; --bg: #fff8ed; --text: #2a1f00; }
      .theme-emerald { --primary: #10b981; --secondary: #34d399; --accent: #6ee7b7; --bg: #f0fff4; --text: #042c23; }

      body {
        background: linear-gradient(135deg, var(--bg), #eef2ff);
        color: var(--text);
      }

      @if(Auth::check() && Auth::user()->role == 'parents')
      .parent-portal-locked {
        pointer-events: none;
        user-select: none;
      }
      #parent-portal-loader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
      }
      #parent-portal-loader .loader-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.6);
      }
      #parent-portal-loader .loader-container {
        position: relative;
        z-index: 1;
        background: #fff;
        padding: 32px;
        border-radius: 12px;
        max-width: 520px;
        width: calc(100% - 40px);
        text-align: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
      }
      @endif

      /* Sidebar Modern Styling */
      .layout-menu {
        background: linear-gradient(135deg, var(--primary), var(--accent)) !important;
        color: #fff !important;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1) !important;
        transition: width .35s ease, padding .35s ease;
      }

      .layout-menu.collapsed {
        width: 72px;
      }

      .app-brand-text {
        color: #fff !important;
      }

      .menu-inner .menu-item .menu-link {
        color: rgba(255,255,255,0.85) !important;
        border-radius: 8px;
        margin: 4px 8px;
        transition: all 0.3s ease;
      }

      .menu-inner .menu-item .menu-link:hover {
        background: rgba(255,255,255,0.15) !important;
        color: #fff !important;
        transform: translateX(5px);
      }

      .menu-inner .menu-item.active > .menu-link {
        background: rgba(255,255,255,0.2) !important;
        color: #fff !important;
        font-weight: 600;
      }

      .menu-icon {
        color: rgba(255,255,255,0.9) !important;
      }

      .menu-header-text {
        color: rgba(255,255,255,0.7) !important;
        font-weight: 600;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
      }

      /* Mobile spacing for navbar title area */
      @media (max-width: 768px) {
        .layout-navbar {
          background: linear-gradient(135deg, var(--primary), var(--accent)) !important;
          border-bottom: 1px solid rgba(255,255,255,0.1) !important;
          color: #fff !important;
        }

        .layout-navbar.container-xxl {
          padding-left: 12px !important;
          padding-right: 12px !important;
        }

        .layout-navbar .nav-item .nav-link,
        .layout-navbar .navbar-brand {
          color: rgba(255,255,255,0.9) !important;
        }

        .layout-navbar .nav-item .nav-link:hover {
          color: #fff !important;
        }

        .layout-navbar .bx {
          color: rgba(255,255,255,0.9) !important;
        }

        .layout-navbar .navbar-nav-right {
          padding-left: 4px;
          padding-right: 4px;
        }
      }

      /* Sidebar collapse behavior */
      .layout-menu.collapsed .menu-header-text,
      .layout-menu.collapsed .app-brand-text {
        display: none;
      }

      .layout-menu.collapsed .menu-link .text-truncate {
        opacity: 0;
        width: 0;
        overflow: hidden;
      }

      /* Content Cards */
      .card {
        background: #fff;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 6px 20px rgba(0,0,0,0.08);
        border: none;
      }

      /* Buttons */
      .btn-primary {
        background: var(--primary);
        color: #fff;
        border-radius: 0.6rem;
        border: none;
        box-shadow: 0 4px 12px rgba(99,102,241,0.4);
        transition: all 0.3s ease;
      }

      .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(99,102,241,0.5);
      }

      /* Table Headers */
      table thead th {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: #fff;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 0.5px;
      }

      /* Dark mode */
      body.dark {
        background: #0f172a;
        color: #e2e8f0;
      }

      body.dark .layout-menu {
        background: linear-gradient(135deg, #312e81, #1e40af) !important;
        box-shadow: 0 4px 20px rgba(0,0,0,0.5) !important;
      }

      body.dark .card {
        background: #1e293b;
        box-shadow: 0 6px 20px rgba(0,0,0,0.4);
        color: #e2e8f0;
      }

      body.dark .content-wrapper {
        background: #0f172a;
      }

      /* Responsive tweaks */
      @media(max-width: 768px) {
        .layout-menu { 
          width: 100%; 
          position: relative; 
        }
      }

      /* Logout button sticky positioning */
      .layout-menu .menu-inner {
        display: flex;
        flex-direction: column;
        height: calc(100vh - 80px);
        overflow-y: auto;
      }

      .layout-menu .menu-inner > .menu-inner-shadow {
        flex-shrink: 0;
      }

      .menu-inner > ul {
        display: flex;
        flex-direction: column;
        flex: 1;
      }

      .menu-logout-sticky {
        margin-top: auto !important;
        position: sticky !important;
        bottom: 0 !important;
        background: transparent !important;
        padding: 10px 8px !important;
        z-index: 10;
      }

      .menu-logout-sticky .menu-link {
        background: rgba(220, 38, 38, 0.9) !important;
        color: #fff !important;
        font-weight: 600 !important;
        border-radius: 8px !important;
      }

      .menu-logout-sticky .menu-link:hover {
        background: rgba(220, 38, 38, 1) !important;
        transform: translateX(0) !important;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.4) !important;
      }

      .menu-back-home {
        margin: 8px 12px 12px;
      }

      .menu-back-home .menu-link {
        background: linear-gradient(135deg, #374151, #111827) !important;
        color: #fff !important;
        font-weight: 800 !important;
        border: 1px solid rgba(255,255,255,0.35) !important;
        box-shadow: 0 8px 18px rgba(0, 0, 0, 0.5);
        border-radius: 10px !important;
      }

      .menu-back-home .menu-link:hover {
        transform: translateX(4px);
        background: linear-gradient(135deg, #4b5563, #1f2937) !important;
        box-shadow: 0 10px 22px rgba(0, 0, 0, 0.6);
      }

      /* Layout menu toggle icon */
      .layout-menu-toggle {
        color: rgba(255,255,255,0.9) !important;
      }

      .layout-menu-toggle:hover {
        color: #fff !important;
      }
    </style>
  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->

        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
          <div class="app-brand demo">
            <a href="/users/donation" class="app-brand-link">
              <span class="app-brand-logo demo">
                <span class="text-primary">
                  <svg
                    width="25"
                    viewBox="0 0 25 42"
                    version="1.1"
                    xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink">
                    <defs>
                      <path
                        d="M13.7918663,0.358365126 L3.39788168,7.44174259 C0.566865006,9.69408886 -0.379795268,12.4788597 0.557900856,15.7960551 C0.68998853,16.2305145 1.09562888,17.7872135 3.12357076,19.2293357 C3.8146334,19.7207684 5.32369333,20.3834223 7.65075054,21.2172976 L7.59773219,21.2525164 L2.63468769,24.5493413 C0.445452254,26.3002124 0.0884951797,28.5083815 1.56381646,31.1738486 C2.83770406,32.8170431 5.20850219,33.2640127 7.09180128,32.5391577 C8.347334,32.0559211 11.4559176,30.0011079 16.4175519,26.3747182 C18.0338572,24.4997857 18.6973423,22.4544883 18.4080071,20.2388261 C17.963753,17.5346866 16.1776345,15.5799961 13.0496516,14.3747546 L10.9194936,13.4715819 L18.6192054,7.984237 L13.7918663,0.358365126 Z"
                        id="path-1"></path>
                      <path
                        d="M5.47320593,6.00457225 C4.05321814,8.216144 4.36334763,10.0722806 6.40359441,11.5729822 C8.61520715,12.571656 10.0999176,13.2171421 10.8577257,13.5094407 L15.5088241,14.433041 L18.6192054,7.984237 C15.5364148,3.11535317 13.9273018,0.573395879 13.7918663,0.358365126 C13.5790555,0.511491653 10.8061687,2.3935607 5.47320593,6.00457225 Z"
                        id="path-3"></path>
                      <path
                        d="M7.50063644,21.2294429 L12.3234468,23.3159332 C14.1688022,24.7579751 14.397098,26.4880487 13.008334,28.506154 C11.6195701,30.5242593 10.3099883,31.790241 9.07958868,32.3040991 C5.78142938,33.4346997 4.13234973,34 4.13234973,34 C4.13234973,34 2.75489982,33.0538207 2.37032616e-14,31.1614621 C-0.55822714,27.8186216 -0.55822714,26.0572515 -4.05231404e-15,25.8773518 C0.83734071,25.6075023 2.77988457,22.8248993 3.3049379,22.52991 C3.65497346,22.3332504 5.05353963,21.8997614 7.50063644,21.2294429 Z"
                        id="path-4"></path>
                      <path
                        d="M20.6,7.13333333 L25.6,13.8 C26.2627417,14.6836556 26.0836556,15.9372583 25.2,16.6 C24.8538077,16.8596443 24.4327404,17 24,17 L14,17 C12.8954305,17 12,16.1045695 12,15 C12,14.5672596 12.1403557,14.1461923 12.4,13.8 L17.4,7.13333333 C18.0627417,6.24967773 19.3163444,6.07059163 20.2,6.73333333 C20.3516113,6.84704183 20.4862915,6.981722 20.6,7.13333333 Z"
                        id="path-5"></path>
                    </defs>
                    <g id="g-app-brand" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                      <g id="Brand-Logo" transform="translate(-27.000000, -15.000000)">
                        <g id="Icon" transform="translate(27.000000, 15.000000)">
                          <g id="Mask" transform="translate(0.000000, 8.000000)">
                            <mask id="mask-2" fill="white">
                              <use xlink:href="#path-1"></use>
                            </mask>
                            <use fill="currentColor" xlink:href="#path-1"></use>
                            <g id="Path-3" mask="url(#mask-2)">
                              <use fill="currentColor" xlink:href="#path-3"></use>
                              <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-3"></use>
                            </g>
                            <g id="Path-4" mask="url(#mask-2)">
                              <use fill="currentColor" xlink:href="#path-4"></use>
                              <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-4"></use>
                            </g>
                          </g>
                          <g
                            id="Triangle"
                            transform="translate(19.000000, 11.000000) rotate(-300.000000) translate(-19.000000, -11.000000) ">
                            <use fill="currentColor" xlink:href="#path-5"></use>
                            <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-5"></use>
                          </g>
                        </g>
                      </g>
                    </g>
                  </svg>
                </span>
              </span>
              <span class="app-brand-text demo menu-text fw-bold ms-2" style="font-size: 1rem;">Raise Builder</span>
            </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
              <i class="bx bx-chevron-left align-middle"></i>
            </a>
          </div>

          <div class="menu-divider mt-0"></div>

          {{-- <div class="menu-inner-shadow"></div> --}}

          <ul class="menu-inner py-1">

              <li class="menu-item menu-back-home">
                <a href="{{ url('/') }}" class="menu-link">
                  <i class="menu-icon tf-icons bx bx-globe"></i>
                  <div class="text-truncate">Back to Website</div>
                </a>
              </li>


                @if (Auth::user()->role != 'user')
                @if (Auth::user()->role != 'parents' && Auth::user()->role != 'parent' && Auth::user()->role != 'Parents')
                <!-- Dashboard -->
                <li class="menu-header small text-uppercase ">
                  <span class="menu-header-text">
                    Dashboard
                  </span>
                </li>
                <li class="menu-item {{ request()->is('users') ? 'active' : '' }}">
                  <a
                  href="/users"
                  class="menu-link">
                  <i class="menu-icon tf-icons bx bx-home"></i>
                  <div class="text-truncate" data-i18n="Email">
                    Dashboard
                  </div>
                  </a>
                </li>
                @endif
                <!-- Information -->
                <li class="menu-header small text-uppercase ">
                    <span class="menu-header-text">Information</span>
                </li>
                <li class="menu-item {{ request()->is('users/profile') ? 'active' : '' }}" id="profile-menu-item">
                    <a
                    href="/users/profile"
                    class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user-circle"></i>
                    <div class="text-truncate" data-i18n="Email">
                        @if(Auth::user()->role == 'parents')
                        Your Profile
                        @else
                        Profile
                        @endif
                    </div>
                    </a>
                </li>
                @if (Auth::user()->role == 'user' || Auth::user()->role == 'group_leader' || Auth::user()->role == 'parent' || Auth::user()->role == 'Parents' || Auth::user()->role == 'parents')
                <li class="menu-item {{ request()->is('users/student') ? 'active' : '' }}" id="students-menu-item">
                    <a
                    href="/users/student"
                    class="menu-link">
                    <i class="menu-icon tf-icons bx bx bx-group"></i>
                    <div class="text-truncate" data-i18n="Email">
                        @if (Auth::user()->role == 'user')
                        {{ Auth::user()->setting->participant_name }}
                        @elseif (Auth::user()->role == 'parents')
                        Participants
                        @else
                        Group Member
                        @endif
                    </div>
                    </a>
                </li>
              @endif
            @endif


              <!-- Reports -->
              @if (Auth::user()->role != 'parents')
            <li class="menu-header small text-uppercase ">
                <span class="menu-header-text">Reports</span>
              </li>
              <li class="menu-item {{ request()->is('users/donation') ? 'active' : '' }}">
                <a
                  href="/users/donation"
                  class="menu-link">
                  <i class="menu-icon tf-icons bx bx-transfer"></i>
                  <div class="text-truncate" data-i18n="Email">Transactions</div>
                </a>
              </li>
              @endif

              <!-- Payments (Parents Only) -->
              @if (Auth::user()->role == 'parents')
              <li class="menu-header small text-uppercase ">
                <span class="menu-header-text">Donations</span>
              </li>
              <li class="menu-item {{ request()->is('users/donation') ? 'active' : '' }}" id="donation-menu-item">
                <a
                  href="/users/donation"
                  class="menu-link">
                  <i class="menu-icon tf-icons bx bx-transfer"></i>
                  <div class="text-truncate" data-i18n="Email">Received</div>
                </a>
              </li>
              <li class="menu-item {{ request()->is('users/payments') ? 'active' : '' }}" id="payments-menu-item">
                <a
                  href="/users/payments"
                  class="menu-link">
                  <i class="menu-icon tf-icons bx bx-credit-card"></i>
                  <div class="text-truncate" data-i18n="Email">Paid</div>
                </a>
              </li>
              @endif
            @if (Auth::user()->role == 'user')
                <!-- Setting -->
                <li class="menu-header small text-uppercase ">
                    <span class="menu-header-text">Site Settings</span>
                </li>

                <li class="menu-item {{ request()->is('users/setting') ? 'active' : '' }}">
                    <a
                    href="/users/setting"
                    class="menu-link">
                    <i class="menu-icon tf-icons bx bx-cog"></i>
                    <div class="text-truncate" data-i18n="Email">Settings</div>
                    </a>
                </li>

                <li class="menu-item {{ request()->is('users/notifications') ? 'active' : '' }}">
                    <a
                    href="/users/notifications"
                    class="menu-link">
                    <i class="menu-icon tf-icons bx bx-bell"></i>
                    <div class="text-truncate" data-i18n="Email">Notifications</div>
                    </a>
                </li>

                <li class="menu-item {{ request()->is('users/tax') ? 'active' : '' }}">
                    <a
                    href="/users/tax"
                    class="menu-link">
                    <i class="menu-icon tf-icons bx bx-file-find"></i>
                    <div class="text-truncate" data-i18n="Email">1099-K Tax</div>
                    </a>
                </li>

                <li class="menu-item {{ request()->is('users/tax-receipt') ? 'active' : '' }}">
                    <a
                    href="/users/tax-receipt"
                    class="menu-link">
                    <i class="menu-icon tf-icons bx bx-receipt"></i>
                    <div class="text-truncate" data-i18n="Email">Tax Receipt</div>
                    </a>
                </li>

                <li class="menu-item {{ request()->is('users/direct_deposit') ? 'active' : '' }}">
                        <a
                        href="/users/direct_deposit"
                        class="menu-link">
                        <i class="menu-icon tf-icons bx bx-credit-card"></i>
                        <div class="text-truncate" data-i18n="Email">Direct Deposit Settings</div>
                        </a>
                </li>

                <li class="menu-item {{ request()->is('users/mailed_deposit') ? 'active' : '' }}">
                        <a
                        href="/users/mailed_deposit"
                        class="menu-link">
                        <i class="menu-icon tf-icons bx bx-envelope"></i>
                        <div class="text-truncate" data-i18n="Email">Mailed Check Settings</div>
                        </a>
                </li>

                <li class="menu-item {{ request()->is('users/wire_transfer') ? 'active' : '' }}">
                        <a
                        href="/users/wire_transfer"
                        class="menu-link">
                        <i class="menu-icon tf-icons bx bx-wallet"></i>
                        <div class="text-truncate" data-i18n="Email">Wire Transfer Setting</div>
                        </a>
                </li>
            @endif

            
            @if(auth()->user()->role !== 'individual' && auth()->user()->role !== 'parents')
            @if (auth()->user()->role !== 'user')
            <!-- Analytics -->
            <li class="menu-header small text-uppercase">
              <span class="menu-header-text">Analytics</span>
              </li>
                <li class="menu-item {{ request()->is('users/analytics') || request()->is('users/analytics/*') ? 'active open' : '' }}">
                  <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-chart"></i>
                    <div class="text-truncate">Analytics</div>
                  </a>
                  <ul class="menu-sub">
                    <li class="menu-item {{ request()->is('users/analytics') && !request()->is('users/analytics/*') ? 'active' : '' }}">
                      <a href="/users/analytics" class="menu-link">
                        <div class="text-truncate">Dashboard</div>
                      </a>
                    </li>
                    <li class="menu-item {{ request()->is('users/analytics/utm') ? 'active' : '' }}">
                      <a href="/users/analytics/utm" class="menu-link">
                        <div class="text-truncate">UTM Attribution</div>
                      </a>
                    </li>
                  </ul>
                </li>
                

                <li class="menu-item {{ request()->is('users/qr-codes*') ? 'active' : '' }}">
                  <a href="/users/qr-codes" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-qr"></i>
                    <div class="text-truncate">QR Codes</div>
                  </a>
                </li>

                <!-- User Behavior -->
                <li class="menu-item {{ request()->is('users/hotjar*') || request()->is('users/heatmaps*') || request()->is('users/recordings*') ? 'active open' : '' }}">
                  <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-map"></i>
                    <div class="text-truncate">User Behavior</div>
                  </a>
                  <ul class="menu-sub">
                    <li class="menu-item {{ request()->is('users/hotjar/heatmaps') ? 'active' : '' }}">
                      <a href="/users/hotjar/heatmaps" class="menu-link">
                        <div class="text-truncate">Heatmaps</div>
                      </a>
                    </li>
                    <li class="menu-item {{ request()->is('users/hotjar/recordings') ? 'active' : '' }}">
                      <a href="/users/hotjar/recordings" class="menu-link">
                        <div class="text-truncate">Session Recordings</div>
                      </a>
                    </li>
                  </ul>
                </li>
              @endif


              <!-- User Management -->
              <li class="menu-header small text-uppercase">
                <span class="menu-header-text">User Management</span>
              </li>

              <li class="menu-item {{ request()->is('users/manage-users*') ? 'active' : '' }}">
                <a href="/users/manage-users" class="menu-link">
                  <i class="menu-icon tf-icons bx bx-user"></i>
                  <div class="text-truncate">Users</div>
                </a>
              </li>
              @if (auth()->user()->role !== 'user')
                <li class="menu-item {{ request()->is('users/roles*') ? 'active' : '' }}">
                  <a href="/users/roles" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-shield-alt-2"></i>
                    <div class="text-truncate">Roles</div>
                  </a>
                </li>

                <li class="menu-item {{ request()->is('users/permissions*') ? 'active' : '' }}">
                  <a href="/users/permissions" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-check-shield"></i>
                    <div class="text-truncate">Permissions</div>
                  </a>
                </li>
              @endif
              @endif
              <li class="menu-item menu-logout-sticky">
                <a href="{{ url('/logout') }}" class="menu-link">
                  <i class="menu-icon tf-icons bx bx-power-off"></i>
                  <div class="text-truncate">Logout</div>
                </a>
              </li>
          </ul>
        </aside>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

          <nav class="layout-navbar container-xxl navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar" style="background: transparent; border-bottom: 1px solid rgba(0,0,0,0.05);">
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
              <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
                <i class="icon-base bx bx-menu icon-md"></i>
                Menu
              </a>
            </div>

 
          </nav>

          <!-- / Navbar -->

          <!-- Success/Error Alerts -->
          @if(session('success'))
          <div class="container-xxl mt-3">
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
              <div class="d-flex align-items-center gap-2">
                <i class="bx bx-check-circle fs-5"></i>
                <div>
                  <strong>Success!</strong> {{ session('success') }}
                </div>
              </div>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          </div>
          @endif

          @if(session('error'))
          <div class="container-xxl mt-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <div class="d-flex align-items-center gap-2">
                <i class="bx bx-x-circle fs-5"></i>
                <div>
                  <strong>Error!</strong> {{ session('error') }}
                </div>
              </div>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          </div>
          @endif

          @yield('content')

        <!-- Footer -->
    <footer class="content-footer footer bg-footer-theme">
        <div class="container-xxl">
        </div>
    </footer>
    <!-- / Footer -->

    <div class="content-backdrop fade"></div>
    </div>
    <!-- Content wrapper -->
    </div>
    <!-- / Layout page -->
    </div>

          <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->

    <script src="{{asset('user/assets/vendor/libs/jquery/jquery.js')}}"></script>

    <script src="{{asset('user/assets/vendor/libs/popper/popper.js')}}"></script>
    <script src="{{asset('user/assets/vendor/js/bootstrap.js')}}"></script>

    <script src="{{asset('user/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>

    <script src="{{asset('user/assets/vendor/js/menu.js')}}"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{asset('user/assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>

    <!-- Main JS -->

    <script src="{{asset('user/assets/js/main.js')}}"></script>

    <!-- Page JS -->
    <script src="{{asset('user/assets/js/dashboards-analytics.js')}}"></script>

    <!-- Push Notifications -->
    <script src="{{asset('js/push-notifications.js')}}"></script>
    @stack('scripts')
    
    <!-- PWA Install Prompt -->
    <script>
        let deferredPrompt;
        
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            
            // Show install button/banner (you can customize this)
            console.log('PWA install prompt available');
        });
        
        window.addEventListener('appinstalled', () => {
            console.log('PWA was installed');
            deferredPrompt = null;
        });
    </script>

    <!-- Custom Fonts for CKEditor -->
    <script src="{{ asset('js/ckeditor-custom-fonts.js') }}"></script>

    <!-- Modern Admin Theme JavaScript -->
    <script>
      // Persisted UI controls: dark mode, theme, sidebar collapse
      (function(){
        const body = document.body;
        const darkToggle = document.getElementById('darkToggle');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const themeSelect = document.getElementById('themeSelect');
        const sidebar = document.querySelector('.layout-menu');

        if (!darkToggle || !sidebarToggle || !themeSelect || !sidebar) {
          console.warn('Modern theme controls not found on this page');
          return;
        }

        // Load saved preferences
        try {
          if (localStorage.getItem('ui:dark') === '1') {
            body.classList.add('dark');
            darkToggle.querySelector('i').classList.replace('bx-moon', 'bx-sun');
          }
          
          const theme = localStorage.getItem('ui:theme') || 'theme-purple';
          body.classList.add(theme);
          themeSelect.value = theme;
          
          if (localStorage.getItem('ui:sidebarCollapsed') === '1') {
            sidebar.classList.add('collapsed');
          }
        } catch(e) {
          console.error('Failed to load UI preferences:', e);
        }

        // Dark mode toggle
        darkToggle.addEventListener('click', () => {
          body.classList.toggle('dark');
          const isDark = body.classList.contains('dark');
          localStorage.setItem('ui:dark', isDark ? '1' : '0');
          
          const icon = darkToggle.querySelector('i');
          if (isDark) {
            icon.classList.replace('bx-moon', 'bx-sun');
          } else {
            icon.classList.replace('bx-sun', 'bx-moon');
          }
        });

        // Sidebar toggle
        sidebarToggle.addEventListener('click', () => {
          sidebar.classList.toggle('collapsed');
          localStorage.setItem('ui:sidebarCollapsed', sidebar.classList.contains('collapsed') ? '1' : '0');
        });

        // Theme select
        themeSelect.addEventListener('change', (e) => {
          // Remove existing theme classes
          document.body.classList.remove('theme-purple','theme-cyan','theme-amber','theme-emerald');
          document.body.classList.add(e.target.value);
          localStorage.setItem('ui:theme', e.target.value);
        });
      })();
    </script>

    <!-- Add Participant Modal (Parents) - Available on All Pages -->
    @if(Auth::user() && Auth::user()->role === 'parents')
    <div class="modal fade" id="addStudentModal" tabindex="-1" style="margin-top: 70px;" aria-labelledby="addStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="margin-top: 20px !important">
            <div class="modal-content">
                <form action="{{ route('parent.add-student') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addStudentModalLabel">Add Participant</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                        <div class="mb-3">
                            <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="first_name" name="first_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="last_name" name="last_name" required>
                            <div class="form-text">Credentials are automatically generated for system use only and are not shared or tracked outside the fundraiser.</div>
                        </div>
                        <div class="mb-3">
                            <label for="teacher_id" class="form-label">Select Teacher <span class="text-danger">*</span></label>
                            <select class="form-select teacher-select" id="teacher_id" name="teacher_id" required>
                                <option value="">Loading teachers...</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="modal_goal" class="form-label">Fundraising Goal</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="modal_goal" name="goal" min="0" step="0.01">
                                <span class="input-group-text">.00 USD</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="modal_tshirt_size" class="form-label">T-Shirt Size</label>
                            <select class="form-select" id="modal_tshirt_size" name="tshirt_size">
                                <option value="">Select a size</option>
                              <option value="Youth XS">Youth XS</option>
                              <option value="Youth Small">Youth Small</option>
                              <option value="Youth Medium">Youth Medium</option>
                              <option value="Youth Large">Youth Large</option>
                                <option value="Adult Small">Adult Small</option>
                                <option value="Adult Medium">Adult Medium</option>
                                <option value="Adult Large">Adult Large</option>
                              <option value="Adult XL">Adult XL</option>
                              <option value="Adult XXL">Adult XXL</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="modal_description" class="form-label">Profile Description</label>
                            <textarea class="form-control" id="modal_description" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="modal_photo" class="form-label">Upload Photo</label>
                            <input class="form-control" type="file" id="modal_photo" name="photo" accept="image/png, image/gif, image/jpeg, image/jpg, image/pjpeg">
                            <div class="form-text">Maximum file size: <strong>5MB</strong> | Accepted formats: <strong>JPG, JPEG, PNG, GIF</strong> | Recommended: Square format</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    @if(Auth::check() && Auth::user()->role == 'parents')
    <!-- Parent Portal Processing Loader -->
    <div id="parent-portal-loader" aria-hidden="true">
      <div class="loader-overlay"></div>
      <div class="loader-container">
        <div class="loader-content">
          <div class="spinner-border text-primary mb-4" role="status">
            <span class="visually-hidden">Processing...</span>
          </div>
          <h3 class="mb-3">Processing</h3>
          <p class="loader-message">Please wait while your request is being completed...</p>
          <div class="loader-warnings mt-4">
            <p class="warning-item"><i class="fas fa-exclamation-circle me-2"></i> Do not refresh the page</p>
            <p class="warning-item"><i class="fas fa-exclamation-circle me-2"></i> Do not close this window</p>
            <p class="warning-item"><i class="fas fa-exclamation-circle me-2"></i> Do not navigate away</p>
          </div>
          <p class="loader-subtext mt-4">This may take a few moments...</p>
        </div>
      </div>
    </div>
    @endif

    <script>
      @if(Auth::check() && Auth::user()->role == 'parents')
      function showParentPortalLoader() {
        const loader = document.getElementById('parent-portal-loader');
        if (loader) {
          loader.style.display = 'flex';
        }
        document.body.classList.add('parent-portal-locked');
      }

      document.addEventListener('submit', function(event) {
        const form = event.target;
        if (!form) return;
        // Only handle real form submissions (not prevented)
        showParentPortalLoader();
      }, true);

      document.addEventListener('click', function(event) {
        if (!document.body.classList.contains('parent-portal-locked')) return;
        const link = event.target.closest('a');
        if (link) {
          event.preventDefault();
        }
      }, true);
      @endif

      // Populate teachers dropdown dynamically via API
      function loadTeachersForModal() {
        const teacherSelect = document.getElementById('teacher_id');
        if (!teacherSelect) return;

        fetch('/api/teachers')
          .then(response => response.json())
          .then(data => {
            console.log('📚 Teachers loaded:', data);

            const stripPrefix = (name) => {
              if (!name) return '';
              return name.replace(/^(Mr|Ms|Mrs|Dr)\.?\s*/i, '');
            };
            
            // Clear existing options except placeholder
            while (teacherSelect.options.length > 1) {
              teacherSelect.remove(1);
            }
            
            // Add teachers from API
            if (data.teachers && data.teachers.length > 0) {
              data.teachers
                .slice()
                .sort((a, b) => stripPrefix(a.name).localeCompare(stripPrefix(b.name), undefined, { sensitivity: 'base' }))
                .forEach(teacher => {
                const option = document.createElement('option');
                option.value = teacher.id;
                option.textContent = teacher.name;
                teacherSelect.appendChild(option);
              });
              
              // Update placeholder
              teacherSelect.options[0].textContent = 'Choose a teacher';
              console.log('✅ Teachers populated:', data.teachers.length);
            } else {
              teacherSelect.options[0].textContent = 'No teachers available';
            }
          })
          .catch(error => {
            console.error('❌ Error loading teachers:', error);
            teacherSelect.options[0].textContent = 'Error loading teachers';
          });
      }

      // Load teachers when modal is opened
      const addStudentModal = document.getElementById('addStudentModal');
      if (addStudentModal) {
        addStudentModal.addEventListener('show.bs.modal', loadTeachersForModal);
      }

      // Also load on page load for immediate access
      document.addEventListener('DOMContentLoaded', function() {
        loadTeachersForModal();
        
        // Reset form and close modal after successful submission
        const addStudentForm = document.querySelector('form[action="{{ route('parent.add-student') }}"]');
        if (addStudentForm) {
          // Listen for modal close - which happens after page reload with success
          addStudentModal?.addEventListener('hide.bs.modal', function() {
            // Clear form when modal closes (after page reload with success message)
            if (session('success')) {
              addStudentForm.reset();
              const teacherSelect = document.getElementById('teacher_id');
              if (teacherSelect) {
                teacherSelect.value = '';
              }
            }
          });
        }
      });

      document.addEventListener('click', function (event) {
        const trigger = event.target.closest('[data-bs-target="#addStudentModal"]');
        if (!trigger) return;

        const modalEl = document.getElementById('addStudentModal');
        if (!modalEl || typeof bootstrap === 'undefined' || !bootstrap.Modal) return;

        const modalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
        modalInstance.show();
      });
    </script>

    <!-- Place this tag before closing body tag for github widget button. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>

