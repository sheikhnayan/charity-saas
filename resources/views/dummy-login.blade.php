<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auction</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>body{background:#f9fafb;}</style>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('auction.css') }}">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <style>

    .non-float{
        margin-bottom: -111px;
    }
    p {
        font-size: 1rem;
        line-height: 1.5;
        font-family: AvenirLTPro-Black,sans-serif;
        margin-bottom: 1.5rem;
    }

    .c-node-ap__auction-results{
        margin-right: 36px;
        margin-bottom: 24px;
        display: inline-block;
        background-color: #f8f9fa;
        border-color: #DBDCDD;
        border: 1px solid;
        border-radius: 4px;
        padding: 24px;
        font-size: 1rem;
    }

    .c-node-ap__fundraising-target{
        margin-bottom: 12px;
    }

    .c-node-ap__auction-total-label {
        margin-bottom: 12px;
        font-size: 1.25rem;
        line-height: 1.2;
        font-weight: bold;
        font-family: AvenirLTPro-Black,sans-serif;
        color: #355159
    }
    .c-node-ap__auction-total-amount {
        font-size: 2rem;
        line-height: 1.5;
        color: #d9b730;
        font-weight: bold;
        font-family: AvenirLTPro-Black,sans-serif;
    }

    .c-node-ap__totalizer{
        height: 18px;
        border-radius: 12px;
        --color-ui: #d9b730;
    }

    .c-node-ap__auction-total-component-label{
        color: #6d6e71
    }

    .c-node-ap__auction-total-component-amount{
        font-size: 1rem;
        line-height: 1.2;
        font-weight: bold;
        font-family: AvenirLTPro-Black,sans-serif;
        color: #000
    }
    .c-view__item.c-view__item--teaser {
        width: 100% !important;
        max-width: 100% !important;
        flex-basis: 100% !important;
        min-width: 330px !important;
    }
</style>
</head>
<body style="background-color: #fff;">
    @php
$url = url()->current();
$doamin = parse_url($url, PHP_URL_HOST);
$check = \App\Models\Website::where('domain', $doamin)->first();
$groups = \App\Models\User::where('website_id', $check->id)->where('role', 'group_leader')->get();
$header = \App\Models\Header::where('website_id', $check->id)->first();
$setting = \App\Models\Setting::where('user_id', $check->user_id)->first();
$user = \App\Models\User::where('id', $check->user_id)->first();
    @endphp
    @if ($header->status == 1)
        @include('layouts.nav')
    @endif
    <main style="padding: 5rem; padding-top: 0rem; margin-top: 7rem; max-width: 90em; margin-left: auto; margin-right: auto; background-color: #fff;">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <form>
                        <h4>Lady O Registry Member Sign In</h4>

                        <div class="mb-3">
                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Login</button>

                    </form>
                </div>
            </div>
        </div>
    </main>




