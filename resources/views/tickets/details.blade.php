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
        {{ $setting && $setting->company_name ? $setting->company_name . ' | ' . $ticket->name : $ticket->name }}
    </title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
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

    <!-- Meta tags -->
    <meta
        content="{{ $setting && $setting->company_name ? $setting->company_name . ' - ' . $ticket->name : $ticket->name }}"
        name="description" />
    <meta
        content="{{ $setting && $setting->company_name ? $setting->company_name . ' | ' . $ticket->name : $ticket->name }}"
        property="og:title" />
    <meta
        content="{{ $ticket->description ?? 'Check out this amazing ticket!' }}"
        property="og:description" />
    <meta
        content="{{ $ticket->image ? asset('uploads/' . $ticket->image) : asset('images/default-ticket-image.jpg') }}"
        property="og:image" />
    <meta
        content="{{ $setting && $setting->company_name ? $setting->company_name . ' | ' . $ticket->name : $ticket->name }}"
        property="twitter:title" />
    <meta
        content="{{ $ticket->description ?? 'Check out this amazing ticket!' }}"
        property="twitter:description" />
    <meta
        content="{{ $ticket->image ? asset('uploads/' . $ticket->image) : asset('images/default-ticket-image.jpg') }}"
        property="twitter:image" />
    <meta property="og:type" content="website" />
    <meta content="summary_large_image" name="twitter:card" />

    <!-- Investment page specific styles -->
    <link href="{{ asset('investment/css/main.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('investment/css/investment-utilities.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset('investment/js/webfont-loader.js') }}" type="text/javascript"></script>

    @if ($setting && $setting->favicon)
        <link href="{{ asset('uploads/' . $setting->favicon) }}" rel="shortcut icon" type="image/x-icon" />
        <link href="{{ asset('uploads/' . $setting->favicon) }}" rel="apple-touch-icon" />
    @endif
</head>

<body>
    @if($header)
        {!! $header->header !!}
    @endif

    <div class="container py-5">
        <div class="row">
            <div class="col-md-6">
                @if($ticket->image)
                    <img src="{{ asset('uploads/' . $ticket->image) }}" alt="{{ $ticket->name }}" class="img-fluid rounded">
                @endif
            </div>
            <div class="col-md-6">
                <h1 class="mb-4">{{ $ticket->name }}</h1>
                <p class="lead mb-4">${{ number_format($ticket->price, 2) }}</p>
                <div class="mb-4">
                    {!! $ticket->description !!}
                </div>
                @if($ticket->status == 'available')
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                        <div class="form-group mb-3">
                            <label for="quantity">Quantity</label>
                            <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" max="{{ $ticket->quantity }}">
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg">Add to Cart</button>
                    </form>
                @else
                    <div class="alert alert-warning">
                        This ticket is currently unavailable
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if($footer)
        {!! $footer->footer !!}
    @endif

    <!-- Additional Scripts -->
    <script>
        window.intercomSettings = {
            api_base: "https://api-iam.intercom.io",
            app_id: "bj5c2bb6"
        };
    </script>
    <script>
        (function() {
            var w = window;
            var ic = w.Intercom;
            if (typeof ic === "function") {
                ic('reattach_activator');
                ic('update', w.intercomSettings);
            } else {
                var d = document;
                var i = function() {
                    i.c(arguments);
                };
                i.q = [];
                i.c = function(args) {
                    i.q.push(args);
                };
                w.Intercom = i;
                var l = function() {
                    var s = d.createElement('script');
                    s.type = 'text/javascript';
                    s.async = true;
                    s.src = 'https://widget.intercom.io/widget/bj5c2bb6';
                    var x = d.getElementsByTagName('script')[0];
                    x.parentNode.insertBefore(s, x);
                };
                if (document.readyState === 'complete') {
                    l();
                } else if (w.attachEvent) {
                    w.attachEvent('onload', l);
                } else {
                    w.addEventListener('load', l, false);
                }
            }
        })();
    </script>
</body>
</html>