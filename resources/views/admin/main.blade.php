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
            <a href="index.html" class="app-brand-link">
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
  <!-- Configuration Section -->
  <li class="menu-header small text-uppercase">
    <span class="menu-header-text">Configuration</span>
  </li>

  <li class="menu-item {{ request()->is('admins', 'admins/payment', 'admins/payout-methods', 'admin/dealmaker-settings', 'admin/notification-settings', 'admin/fonts*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
      <i class="menu-icon tf-icons bx bx-cog"></i>
      <div class="text-truncate">Settings</div>
    </a>
    <ul class="menu-sub">
      <li class="menu-item {{ request()->is('admins') ? 'active' : '' }}"><a href="/admins" class="menu-link"><div class="text-truncate">General Settings</div></a></li>
      <li class="menu-item {{ request()->is('admins/payment') ? 'active' : '' }}"><a href="/admins/payment" class="menu-link"><div class="text-truncate">Payment Setting</div></a></li>
      <li class="menu-item {{ request()->is('admins/payout-methods') ? 'active' : '' }}"><a href="/admins/payout-methods" class="menu-link"><div class="text-truncate">Payout Methods</div></a></li>
      <li class="menu-item {{ request()->is('admin/dealmaker-settings') ? 'active' : '' }}"><a href="/admins/dealmaker-settings" class="menu-link"><div class="text-truncate">Homepage Settings</div></a></li>
      <li class="menu-item {{ request()->is('admin/notification-settings') ? 'active' : '' }}"><a href="/admin/notification-settings" class="menu-link"><div class="text-truncate">Notifications</div></a></li>
      <li class="menu-item {{ request()->is('admin/fonts*') ? 'active' : '' }}"><a href="{{ route('admin.fonts.index') }}" class="menu-link"><div class="text-truncate">Fonts</div></a></li>
    </ul>
  </li>

  <!-- Website Section -->
  <li class="menu-item {{ request()->is('admins/website', 'admins/page', 'admins/templates*', 'admins/menu', 'admins/footer', 'admins/newsletter*', 'admins/comments') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
      <i class="menu-icon tf-icons bx bx-globe"></i>
      <div class="text-truncate">Website</div>
    </a>
    <ul class="menu-sub">
      <li class="menu-item {{ request()->is('admins/website') ? 'active' : '' }}"><a href="/admins/website" class="menu-link"><div class="text-truncate">Home</div></a></li>
      <li class="menu-item {{ request()->is('admins/page') ? 'active' : '' }}"><a href="/admins/page" class="menu-link"><div class="text-truncate">Page</div></a></li>
      <li class="menu-item {{ request()->is('admins/templates*') ? 'active' : '' }}"><a href="/admins/templates" class="menu-link"><div class="text-truncate">Page Template</div></a></li>
      <li class="menu-item {{ request()->is('admins/menu') ? 'active' : '' }}"><a href="/admins/menu" class="menu-link"><div class="text-truncate">Header</div></a></li>
      <li class="menu-item {{ request()->is('admins/footer') ? 'active' : '' }}"><a href="/admins/footer" class="menu-link"><div class="text-truncate">Footer</div></a></li>
      <li class="menu-item {{ request()->is('admins/newsletter*') ? 'active' : '' }}"><a href="/admins/newsletter" class="menu-link"><div class="text-truncate">Newsletter</div></a></li>
      <li class="menu-item {{ request()->is('admins/comments') ? 'active' : '' }}"><a href="/admins/comments" class="menu-link"><div class="text-truncate">Comments</div></a></li>
      <li class="menu-item {{ request()->is('admins/student') ? 'active' : '' }}"><a href="/admins/student" class="menu-link"><div class="text-truncate">Registrations</div></a></li>
    </ul>
  </li>

  <!-- Features Section -->
  <li class="menu-item {{ request()->is('admins/ticket', 'admins/auction', 'admins/sponsor', 'admins/teachers*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
      <i class="menu-icon tf-icons bx bx-star"></i>
      <div class="text-truncate">Features</div>
    </a>
    <ul class="menu-sub">
      <li class="menu-item {{ request()->is('admins/ticket') ? 'active' : '' }}"><a href="/admins/ticket" class="menu-link"><div class="text-truncate">Ticket</div></a></li>
      <li class="menu-item {{ request()->is('admins/auction') ? 'active' : '' }}"><a href="/admins/auction" class="menu-link"><div class="text-truncate">Auction</div></a></li>
      <li class="menu-item {{ request()->is('admins/sponsor') ? 'active' : '' }}"><a href="/admins/sponsor" class="menu-link"><div class="text-truncate">Sponsor</div></a></li>
      <li class="menu-item {{ request()->is('admins/teachers*') ? 'active' : '' }}"><a href="{{ route('admin.teachers.websites') }}" class="menu-link"><div class="text-truncate">Teachers</div></a></li>
    </ul>
  </li>

  <!-- Account Section -->
  <li class="menu-item {{ request()->is('admins/change-password') ? 'active' : '' }}">
    <a href="/admins/change-password" class="menu-link">
      <i class="menu-icon tf-icons bx bx-lock"></i>
      <div class="text-truncate">Change Password</div>
    </a>
  </li>

  <!-- Reports & Analytics Section -->
  <li class="menu-header small text-uppercase">
    <span class="menu-header-text">Reports & Analytics</span>
  </li>

  <li class="menu-item {{ request()->is('analytics*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
      <i class="menu-icon tf-icons bx bx-chart"></i>
      <div class="text-truncate">Analytics</div>
    </a>
    <ul class="menu-sub">
      <li class="menu-item {{ request()->is('analytics') && !request()->is('analytics/*') ? 'active' : '' }}"><a href="/analytics" class="menu-link"><div class="text-truncate">Dashboard</div></a></li>
      <li class="menu-item {{ request()->is('analytics/utm') ? 'active' : '' }}"><a href="/analytics/utm" class="menu-link"><div class="text-truncate">UTM Attribution</div></a></li>
    </ul>
  </li>

  <li class="menu-item {{ request()->is('hotjar*', 'heatmaps*', 'recordings*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
      <i class="menu-icon tf-icons bx bx-map"></i>
      <div class="text-truncate">User Behavior</div>
    </a>
    <ul class="menu-sub">
      <li class="menu-item {{ request()->is('hotjar/heatmaps') ? 'active' : '' }}"><a href="/hotjar/heatmaps" class="menu-link"><div class="text-truncate">Heatmaps</div></a></li>
      <li class="menu-item {{ request()->is('hotjar/recordings') ? 'active' : '' }}"><a href="/hotjar/recordings" class="menu-link"><div class="text-truncate">Session Recordings</div></a></li>
    </ul>
  </li>

  <li class="menu-item {{ request()->is('qr-codes*') ? 'active' : '' }}">
    <a href="/qr-codes" class="menu-link">
      <i class="menu-icon tf-icons bx bx-qr"></i>
      <div class="text-truncate">QR Codes</div>
    </a>
  </li>

  <!-- Transactions Section -->
  <li class="menu-item {{ request()->is('admins/donation', 'admins/tax-list', 'admins/tax-receipt', 'admins/student') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
      <i class="menu-icon tf-icons bx bx-transfer"></i>
      <div class="text-truncate">Transactions</div>
    </a>
    <ul class="menu-sub">
      <li class="menu-item {{ request()->is('admins/donation') ? 'active' : '' }}"><a href="/admins/donation" class="menu-link"><div class="text-truncate">Home</div></a></li>
      <li class="menu-item {{ request()->is('admins/tax-list') ? 'active' : '' }}"><a href="/admins/tax-list" class="menu-link"><div class="text-truncate">1099-K Tax</div></a></li>
      <li class="menu-item {{ request()->is('admins/tax-receipt') ? 'active' : '' }}"><a href="/admins/tax-receipt-list" class="menu-link"><div class="text-truncate">Tax Receipt</div></a></li>
    </ul>
  </li>

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
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-none d-lg-block">
              <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
                <i class="icon-base bx bx-menu icon-md"></i>
              </a>
            </div>

            {{-- <div class="navbar-nav-right d-flex align-items-center justify-content-between w-100" id="navbar-collapse">
              <!-- Left Side: Sidebar Toggle & Welcome -->
              <div class="d-flex align-items-center gap-3">
                <button id="sidebarToggle" class="btn btn-sm" style="background:rgba(99,102,241,0.12);color:var(--primary);border:1px solid rgba(99,102,241,0.2);padding:8px 12px;border-radius:8px;">
                  <i class="bx bx-menu"></i>
                </button>
                <div>
                  <h5 class="mb-0">@yield('page-title', 'Dashboard')</h5>
                  <small style="color: rgba(0,0,0,0.5)">Welcome back — insights updated</small>
                </div>
              </div>

              <!-- Right Side: Theme Controls -->
              <div class="d-flex align-items-center gap-2">
                <select id="themeSelect" class="form-select form-select-sm" style="background:rgba(99,102,241,0.12);color:var(--primary);border:1px solid rgba(99,102,241,0.2);padding:8px 12px;border-radius:8px;width:auto;">
                  <option value="theme-purple">Purple</option>
                  <option value="theme-cyan">Cyan</option>
                  <option value="theme-amber">Amber</option>
                  <option value="theme-emerald">Emerald</option>
                </select>
                <button id="darkToggle" class="btn btn-sm" title="Toggle dark mode" style="background:rgba(99,102,241,0.12);color:var(--primary);border:1px solid rgba(99,102,241,0.2);padding:8px 12px;border-radius:8px;">
                  <i class="bx bx-moon"></i>
                </button>
              </div>
            </div> --}}
          </nav>

          {{-- <nav
            class="layout-navbar container-xxl navbar-detached navbar navbar-expand-xl align-items-center bg-navbar-theme"
            id="layout-navbar">
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
              <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
                <i class="icon-base bx bx-menu icon-md"></i>
              </a>
            </div>

            <div class="navbar-nav-right d-flex align-items-center justify-content-end" id="navbar-collapse">
              <!-- Search -->
              <div class="navbar-nav align-items-center me-auto">
                <div class="nav-item d-flex align-items-center">
                  <span class="w-px-22 h-px-22"><i class="icon-base bx bx-search icon-md"></i></span>
                  <input
                    type="text"
                    class="form-control border-0 shadow-none ps-1 ps-sm-2 d-md-block d-none"
                    placeholder="Search..."
                    aria-label="Search..." />
                </div>
              </div>
              <!-- /Search -->

              <ul class="navbar-nav flex-row align-items-center ms-md-auto">
                <!-- Notifications -->
                <li class="nav-item navbar-dropdown dropdown me-3">
                  <a
                    class="nav-link dropdown-toggle hide-arrow p-0"
                    href="javascript:void(0);"
                    data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="bx bx-bell bx-md"></i>
                    <span class="badge rounded-pill bg-danger badge-notifications" id="notification-badge" style="display: none;">0</span>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end" style="width: 380px; max-height: 450px; overflow-y: auto;">
                    <li>
                      <div class="dropdown-header d-flex justify-content-between align-items-center py-3">
                        <h6 class="mb-0">Notifications</h6>
                        <a href="/admin/notification-settings" class="small text-muted">
                          <i class="bx bx-cog"></i> Settings
                        </a>
                      </div>
                    </li>
                    <li>
                      <div class="dropdown-divider my-0"></div>
                    </li>
                    <li>
                      <div id="notifications-list" class="px-3 py-2">
                        <div class="text-center text-muted py-4">
                          <i class="bx bx-bell-off bx-lg mb-2"></i>
                          <p class="mb-0">No notifications yet</p>
                        </div>
                      </div>
                    </li>
                    <li>
                      <div class="dropdown-divider my-0"></div>
                    </li>
                    <li>
                      <a class="dropdown-item text-center py-2" href="javascript:void(0);" id="mark-all-read">
                        <small>Mark all as read</small>
                      </a>
                    </li>
                  </ul>
                </li>

                <!-- User -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                  <a
                    class="nav-link dropdown-toggle hide-arrow p-0"
                    href="javascript:void(0);"
                    data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                      <img src="{{ asset('user/assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle" />
                    </div>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                      <a class="dropdown-item" href="#">
                        <div class="d-flex">
                          <div class="flex-shrink-0 me-3">
                            <div class="avatar avatar-online">
                              <img src="{{ asset('user/assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle" />
                            </div>
                          </div>
                          <div class="flex-grow-1">
                            <h6 class="mb-0">John Doe</h6>
                            <small class="text-body-secondary">Admin</small>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-divider my-1"></div>
                    </li>
                    <li>
                      <a class="dropdown-item" href="#">
                        <i class="icon-base bx bx-user icon-md me-3"></i><span>My Profile</span>
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="#">
                        <i class="icon-base bx bx-cog icon-md me-3"></i><span>Settings</span>
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="#">
                        <span class="d-flex align-items-center align-middle">
                          <i class="flex-shrink-0 icon-base bx bx-credit-card icon-md me-3"></i
                          ><span class="flex-grow-1 align-middle">Billing Plan</span>
                          <span class="flex-shrink-0 badge rounded-pill bg-danger">4</span>
                        </span>
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-divider my-1"></div>
                    </li>
                    <li>
                      <a class="dropdown-item" href="javascript:void(0);">
                        <i class="icon-base bx bx-power-off icon-md me-3"></i><span>Log Out</span>
                      </a>
                    </li>
                  </ul>
                </li>
                <!--/ User -->
              </ul>
            </div>
          </nav> --}}

          <!-- / Navbar -->

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

    <!-- Custom Notification Styles -->
    <style>
    .badge-notifications {
        position: absolute;
        top: -5px;
        right: -8px;
        min-width: 18px;
        height: 18px;
        padding: 2px 5px;
        font-size: 10px;
        font-weight: 600;
        line-height: 14px;
    }
    
    .dropdown-menu .dropdown-item:hover {
        background-color: #f8f9fa;
    }
    
    .notification-unread {
        background-color: #f0f7ff;
    }
    </style>

    <!-- Vendors CSS -->
    <script src="{{asset('user/assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>

    <!-- Main JS -->

    <script src="{{asset('user/assets/js/main.js')}}"></script>

    <!-- Page JS -->
        <!-- Page JS -->
    <script src="{{asset('user/assets/js/dashboards-analytics.js')}}"></script>

    <!-- Push Notifications -->
    <script src="{{asset('js/push-notifications.js')}}"></script>
    
    <!-- Notification Bell Handler -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const notificationBadge = document.getElementById('notification-badge');
        const notificationsList = document.getElementById('notifications-list');
        const markAllReadBtn = document.getElementById('mark-all-read');
        
        // Exit if notification elements don't exist on this page
        if (!notificationBadge || !notificationsList || !markAllReadBtn) {
            return;
        }
        
        // Load unread count
        async function loadUnreadCount() {
            try {
                const response = await fetch('/api/notifications/unread-count', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    const count = data.unread_count || 0;
                    
                    if (count > 0) {
                        notificationBadge.textContent = count > 99 ? '99+' : count;
                        notificationBadge.style.display = 'block';
                    } else {
                        notificationBadge.style.display = 'none';
                    }
                }
            } catch (error) {
                console.error('Failed to load unread count:', error);
            }
        }
        
        // Load notification list
        async function loadNotifications() {
            try {
                const response = await fetch('/api/notifications/list?limit=10', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    
                    if (data.notifications && data.notifications.length > 0) {
                        notificationsList.innerHTML = '';
                        
                        data.notifications.forEach(notification => {
                            const notifItem = document.createElement('div');
                            notifItem.className = 'dropdown-item d-flex align-items-start p-3 ' + (notification.read_at ? '' : 'bg-light');
                            notifItem.style.cursor = 'pointer';
                            
                            const icon = getNotificationIcon(notification.type);
                            const timeAgo = formatTimeAgo(notification.created_at);
                            
                            notifItem.innerHTML = `
                                <div class="me-3">
                                    <i class="bx ${icon} bx-md text-primary"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 ${notification.read_at ? 'text-muted' : ''}">${notification.title}</h6>
                                    <p class="mb-1 small ${notification.read_at ? 'text-muted' : ''}">${notification.body}</p>
                                    <small class="text-muted">${timeAgo}</small>
                                </div>
                                ${!notification.read_at ? '<span class="badge bg-primary">New</span>' : ''}
                            `;
                            
                            notifItem.addEventListener('click', () => handleNotificationClick(notification));
                            notificationsList.appendChild(notifItem);
                        });
                    } else {
                        notificationsList.innerHTML = `
                            <div class="text-center text-muted py-4">
                                <i class="bx bx-bell-off bx-lg mb-2"></i>
                                <p class="mb-0">No notifications yet</p>
                            </div>
                        `;
                    }
                }
            } catch (error) {
                console.error('Failed to load notifications:', error);
            }
        }
        
        // Handle notification click
        async function handleNotificationClick(notification) {
            // Mark as read
            if (!notification.read_at) {
                try {
                    await fetch(`/api/notifications/${notification.id}/read`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });
                    
                    loadUnreadCount();
                    loadNotifications();
                } catch (error) {
                    console.error('Failed to mark as read:', error);
                }
            }
            
            // Navigate to URL if provided
            if (notification.data && notification.data.url) {
                window.location.href = notification.data.url;
            }
        }
        
        // Mark all as read
        markAllReadBtn.addEventListener('click', async function() {
            try {
                const response = await fetch('/api/notifications/mark-all-read', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                if (response.ok) {
                    loadUnreadCount();
                    loadNotifications();
                }
            } catch (error) {
                console.error('Failed to mark all as read:', error);
            }
        });
        
        // Helper functions
        function getNotificationIcon(type) {
            const icons = {
                'donation': 'bx-donate-heart',
                'auction_outbid': 'bx-gavel',
                'auction_won': 'bx-trophy',
                'goal_reached': 'bx-target-lock',
                'campaign_update': 'bx-news',
                'investment_milestone': 'bx-trending-up',
                'ticket_purchased': 'bx-receipt'
            };
            return icons[type] || 'bx-bell';
        }
        
        function formatTimeAgo(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const seconds = Math.floor((now - date) / 1000);
            
            if (seconds < 60) return 'Just now';
            if (seconds < 3600) return `${Math.floor(seconds / 60)}m ago`;
            if (seconds < 86400) return `${Math.floor(seconds / 3600)}h ago`;
            if (seconds < 604800) return `${Math.floor(seconds / 86400)}d ago`;
            return date.toLocaleDateString();
        }
        
        // Load data on page load
        loadUnreadCount();
        
        // Reload when dropdown is opened
        const dropdownTrigger = notificationBadge.closest('.nav-item').querySelector('[data-bs-toggle="dropdown"]');
        if (dropdownTrigger) {
            dropdownTrigger.addEventListener('click', function() {
                loadNotifications();
            });
        }
        
        // Refresh every 30 seconds
        setInterval(loadUnreadCount, 30000);
    });
    </script>
    
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

        // Keyboard shortcuts disabled
        // document.addEventListener('keydown', (e) => {
        //   // Only if not in an input/textarea
        //   if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
          
        //   if (e.key === 'd') darkToggle.click();
        //   if (e.key === 's') sidebarToggle.click();
        //   if (e.key === 't') themeSelect.focus();
        // });
      })();
    </script>

    <!-- Place this tag before closing body tag for github widget button. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>

