<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Charity</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
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
    
    <!-- Google Analytics -->
    @php
        // Get website by domain
        $currentDomain = request()->host();
        $website = \App\Models\Website::where('domain', $currentDomain)->first();
        $gaTrackingId = $website->google_analytics_id ?? null;
    @endphp
    @if($gaTrackingId)
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaTrackingId }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ $gaTrackingId }}');
    </script>
    @endif
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-xl fixed-top bg-primary" style="background-color: #283748 !important;">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" width="60" height="60" class="d-inline-block align-text-top">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/login">Student Registration/Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/donate">Donate</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/leader-board">Leaderboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/volunteer">Volunteer!</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/photo">Photos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/about">Who We Are - SHPS PTO</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/contact">Contact Us</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <!-- Shopping Cart Component -->
    @include('components.cart-drawer')

    <!-- Shopping Cart JavaScript -->
    <script src="{{ asset('js/cart.js') }}"></script>

</body>
</html>

