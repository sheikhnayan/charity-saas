<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{$data->title}} - Auction</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <!-- Tailwind CSS for modals -->
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
        #studentTable {
            background-color: #fff !important;
            /* Set the table background to white */
            border: none !important;
            /* Remove the table border */
        }

        #studentTable th,
        #studentTable td {
            background-color: #fff !important;
            /* Set the background of table cells to white */
            border: none !important;
            /* Remove borders from table cells */
        }

        #studentTable tbody tr {
            background-color: #fff !important;
            /* Set the background of table rows to white */
        }

        #studentTable_filter {
            display: none;
        }

        #studentTable_length {
            display: none;
        }

        #studentTable thead {
            display: none;
            /* Hide the table header */
        }

        .non-float {
            margin-bottom: -111px;
        }

        p {
            font-size: 1rem;
            line-height: 1.5;
            font-family: AvenirLTPro-Black, sans-serif;
            margin-bottom: 1.5rem;
        }

        .c-node-ap__auction-results {
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

        .c-node-ap__fundraising-target {
            margin-bottom: 12px;
        }

        .c-node-ap__auction-total-label {
            margin-bottom: 12px;
            font-size: 1.25rem;
            line-height: 1.2;
            font-weight: bold;
            font-family: AvenirLTPro-Black, sans-serif;
            color: #355159
        }

        .c-node-ap__auction-total-amount {
            font-size: 2rem;
            line-height: 1.5;
            color: #d9b730;
            font-weight: bold;
            font-family: AvenirLTPro-Black, sans-serif;
        }

        .c-node-ap__totalizer {
            height: 18px;
            border-radius: 12px;
            --color-ui: #d9b730;
        }

        .c-node-ap__auction-total-component-label {
            color: #6d6e71
        }

        .c-node-ap__auction-total-component-amount {
            font-size: 1rem;
            line-height: 1.2;
            font-weight: bold;
            font-family: AvenirLTPro-Black, sans-serif;
            color: #000
        }

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
    </style>
</head>

@php
    $url = url()->current();
    $doamin = parse_url($url, PHP_URL_HOST);
    $check = \App\Models\Website::where('domain', $doamin)->first();
    $pageBackgroundColor = $data->page_bg_color ?? ($check->property_details_bg_color ?? '#ffffff');
@endphp
<body style="background-color: {{ $pageBackgroundColor }} !important;">
    <div style="max-width:1180px;margin:12px auto;padding:0 18px;">
        @include('partials.back-button')
    </div>
    @php
        
        $groups = \App\Models\User::where('website_id', $check->id)->where('role', 'group_leader')->get();
        $header = \App\Models\Header::where('website_id', $check->id)->first();
        $footer = \App\Models\Footer::where('website_id', $check->id)->first();
        $setting = \App\Models\Setting::where('user_id', $check->user_id)->first();
        $user = \App\Models\User::where('id', $check->user_id)->first();
    @endphp
    @if ($header->status == 1)
        @include('layouts.nav')
    @endif
    <main class="c-content c-content--anon c-content--ai-full" id="main" style="margin-top: 7rem; background-color: {{ $pageBackgroundColor }} !important;">





        <div class="c-content__main c-content__main--ai-full" id="content-main" style="background-color: {{ $pageBackgroundColor }} !important;">
            <div class="o-wrapper--tight-1@md">


                <article id="node-203923" class="c-node-ai c-node-ai--full js-ai js-ai--full js-eq"
                    about="/example/headmaster-for-the-day-5" typeof="sioc:Item foaf:Document" data-entity-id="203923"
                    data-unmet-reserve="0" data-live-id="203923" data-updated="1750865265" data-leader="275462"
                    data-status="bidding" data-lec="false" data-expiry="1767168000">
                    <span></span>
                    <div class="is-sticky jqo-st-processed is-stuck" style="top: 0px; z-index: 1;">
                        <div id="air-ai-status-indicator-203923"
                            class="js-ai-status-indicator c-node-ai__status c-node-ai__status--full c-tooltip c-tooltip--n"
                            aria-label="Bidding is under way."></div>
                        <div
                            class="js-ai-page-nav c-node-ai__page-nav c-page-nav c-page-nav--ai c-page-nav--full c-node-ai__page-nav--header c-page-nav--header">
                            {{-- <ul class="o-list-bare c-page-nav__list">
                                <li class="c-page-nav__item c-page-nav__item--back"><a
                                        href="/auction"
                                        class="c-page-nav__link c-page-nav__link--back js-ai-page-nav-back jso-pnb-processed"
                                        rel="nofollow" data-mousetrap-trigger="m" data-ajax-type="GET"><svg
                                            class="c-icon c-icon--chevron-double--h c-page-nav__icon">
                                            <use
                                                xlink:href="/assets/symbols/symbol-defs-06e142b2b5.svg#icon-chevron-double--h">
                                            </use>
                                        </svg>All items</a></li>
                            </ul> --}}
                        </div>
                    </div>
                    <header class="c-node-ai__header">
                        <h1 id="air-content-title" class="js-resize js-max-lines-3 c-heading--alpha c-node-ai__title">
                            {{ $data->title }} </h1>
                    </header>

                    <div class="c-node-ai__summary-wrap">
                        <div class="c-node-ai__summary-item c-node-ai__summary-item--timer">
                            <div class="c-node-ai__timer">
                                <div id="ai-timer-203924"
                                    class="js-timer-wrapper c-timer c-timer--small-block u-hide-no-js">
                                    <div class="c-timer__title"><span
                                            class="js-timer-title">Time remaining</span></div>
                                    <span class="c-timer__body">
                                        <span
                                            class="js-timer"
                                            data-timer_id="ai-{{ $data->id }}-long-small-block"
                                            data-type="expiry"
                                            data-timeout="{{ \Carbon\Carbon::parse($data->dead_line)->timestamp }}"
                                            data-format_num="long"
                                            data-deadline="{{ $data->dead_line }}"
                                            id="auction-timer-{{ $data->id }}"
                                        >
                                            <span class="js-timer-element-days c-timer__element">
                                                <span class="c-timer__value" id="days-{{ $data->id }}">0</span>
                                                <span class="c-timer__period">Days</span>
                                            </span>
                                            <span class="c-timer__element">
                                                <span class="c-timer__value" id="hours-{{ $data->id }}">0</span>
                                                <span class="c-timer__period">Hrs</span>
                                            </span>
                                            <span class="c-timer__element">
                                                <span class="c-timer__value" id="minutes-{{ $data->id }}">0</span>
                                                <span class="c-timer__period">Mins</span>
                                            </span>
                                            <span class="js-timer-element-secs u-hide-js c-timer__element">
                                                <span class="c-timer__value" id="seconds-{{ $data->id }}">0</span>
                                                <span class="c-timer__period">Secs</span>
                                            </span>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="c-node-ai__summary-item c-node-ai__summary-item--price">
                            <div class="c-node-ai__price">
                                <div id="ai-price-203923" class="c-price  c-price--block">
                                    <div class="c-price__title"><span class="js-price-title">Current bid</span></div>
                                    <div class="c-price__wrapper">
                                        <div class="c-price__value u-tc--highlight-bg" id="auction-price-{{ $data->id }}" data-tcid="{{ $data->id }}:price">
                                            ${{ $data->starting_price ?? '0' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="c-node-ai__summary-item c-node-ai__summary-item--bid">
                            <div class="c-node-ai__bidding">
                                <h3 class="c-heading--gamma c-node-ai__bidding-title">Bidding</h3>
                                <p class="u-margin-bottom-small">
                                    Place a bid. <br>It's free and simple to do. </p>
                                <a href="javascript:void(0);" class="c-button c-button--small js-button open-modal">
                                    <span class="c-button__label">
                                        <span class="c-button__text">Place Bid</span>
                                        <span class="c-button__icon c-button__icon--left">
                                            <svg class="c-icon c-icon--person-tick c-icon--button-label">
                                                <use xlink:href="/assets/symbols/symbol-defs-06e142b2b5.svg#icon-person-tick"></use>
                                            </svg>
                                        </span>
                                    </span>
                                </a>
                            </div>
                        </div>
                        {{-- <div class="c-node-ai__summary-item c-node-ai__summary-item--sharing">
                            <div class="c-node-ai__sharing">
                                <div class="c-share c-share--ai">
                                    <ul class="o-list-bare c-share__list">
                                        <li class="c-share__item"><a
                                                href="https://airauctioneer.com/example/headmaster-for-the-day-5"
                                                class="c-share__link c-share__link--link js-copy-to-clipboard"
                                                target="_blank"
                                                data-ctc-url="https://airauctioneer.com/example/headmaster-for-the-day-5"
                                                data-ctc-text="Copy link"><svg
                                                    class="c-icon c-icon--link-disc c-share__icon">
                                                    <use
                                                        xlink:href="/assets/symbols/symbol-defs-06e142b2b5.svg#icon-link-disc">
                                                    </use>
                                                </svg><span class="c-share__link-text">Copy link</span></a></li>
                                        <li class="c-share__item"><a
                                                href="https://twitter.com/intent/tweet?text=Headmaster+for+the+day&amp;url=https%3A%2F%2Fairauctioneer.com%2Fexample%2Fheadmaster-for-the-day-5"
                                                class="c-share__link c-share__link--twitter" target="_blank"><svg
                                                    class="c-icon c-icon--twitter-disc c-share__icon">
                                                    <use
                                                        xlink:href="/assets/symbols/symbol-defs-06e142b2b5.svg#icon-twitter-disc">
                                                    </use>
                                                </svg><span class="c-share__link-text"></span></a></li>
                                        <li class="c-share__item"><a
                                                href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fairauctioneer.com%2Fexample%2Fheadmaster-for-the-day-5"
                                                class="c-share__link c-share__link--facebook" target="_blank"><svg
                                                    class="c-icon c-icon--facebook-disc c-share__icon">
                                                    <use
                                                        xlink:href="/assets/symbols/symbol-defs-06e142b2b5.svg#icon-facebook-disc">
                                                    </use>
                                                </svg><span class="c-share__link-text"></span></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div> --}}
                    </div>

                    <div class="c-node-ai__details-wrap">
                        <div class="o-layout">
                            <div class="o-layout__item u-1/2@md">
                                <div class="c-node-ai__slideshow">
                                    <div class="js-ai-slider--main c-slider c-slider--ai-main">
                                        <div class="js-ai-slides c-slider__slides slick-initialized slick-slider"
                                            data-slick-auto-play="" data-slick-auto-play-speed=""
                                            data-slick-fade-timing="250" data-slick-adaptive-height="">
                                            <div class="slick-list draggable">
                                                <div class="slick-track" style="opacity: 1; width: 620px;">
                                                    <div class="slick-slide slick-current slick-active"
                                                        data-slick-index="0" aria-hidden="false"
                                                        style="width: 620px; position: relative; left: 0px; top: 0px; z-index: 999; opacity: 1;">
                                                        <div>
                                                            <div class="js-ai-slide c-slider__slide"
                                                                style="width: 100%; display: inline-block; height: auto;">
                                                                <div class="c-slider__image"><a
                                                                        href=""
                                                                        data-fancybox="ai-images"
                                                                        data-srcset=""
                                                                        class="c-slider__full-size-link jqo-fbi-processed"
                                                                        tabindex="0"><img
                                                                            class="img-fluid rounded mx-auto d-block"
                                                                            style="max-width:100%;height:auto;"
                                                                            src="{{ asset('/uploads/'.$data->images[0]->image) }}"
                                                                            srcset="{{ asset('/uploads/'.$data->images[0]->image) }}"
                                                                            alt="Auction image"
                                                                            sizes="(min-width: 85em) 620px, (min-width: 60em) 50vw, 100vw"></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="js-ai-slider-controls c-slider__controls u-hide-no-js"
                                            style="display: none;"><button
                                                class="c-button js-button c-button--link c-slider__control js-ai-slider-prev c-slider__control--prev slick-arrow slick-hidden"
                                                type="button" value="" aria-disabled="true" tabindex="-1"><span
                                                    class="c-slider__control-label"><svg
                                                        class="c-icon c-icon--chevron--h u-reflect--x">
                                                        <use
                                                            xlink:href="/assets/symbols/symbol-defs-06e142b2b5.svg#icon-chevron--h">
                                                        </use>
                                                    </svg></span></button><button
                                                class="c-button js-button c-button--link c-slider__control js-ai-slider-next c-slider__control--next slick-arrow slick-hidden"
                                                type="button" value="" aria-disabled="true" tabindex="-1"><span
                                                    class="c-slider__control-label"><svg
                                                        class="c-icon c-icon--chevron--h">
                                                        <use
                                                            xlink:href="/assets/symbols/symbol-defs-06e142b2b5.svg#icon-chevron--h">
                                                        </use>
                                                    </svg></span></button></div>
                                    </div>
                                </div>
                            </div>
                            <div class="o-layout__item u-1/2@md">
                                <div class="c-node-ai__about">
                                    <h2 class="c-heading--beta">About this item</h2>
                                    <div class="c-node-ai__description">
                                        {!! $data->description !!}
                                    </div>
                                    <div id="air-ai-timeout-detail-203923" class="">
                                        <div class="c-node-ai__end-time c-node-ai__end-time--with-extension"><span
                                                class="c-node-ai__end-time-title">Bidding ends: </span><span
                                                class="c-node-ai__end-time-time">{{ $data->dead_line }}<sup>†</sup></span><span
                                                class="c-node-ai__end-time-time c-node-ai__end-time-time--long">{{$data->dead_line}}<sup></sup></span></div>
                                        <div class="c-node-ai__footnote"><span
                                                class="c-node-ai__footnote-marker"><sup></sup></span> <span
                                                class="c-node-ai__footnote-text">Subject to an automatic extension of 10
                                                minutes, if a bid is received within 10 minutes of expiry.</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="c-node-ai__additional-wrap">
                        <div class="o-layout">
                            <div class="o-layout__item u-2/3@mlg">
                                <div class="c-node-ai__bids">
                                    <h2 class="c-heading--beta">
                                        Bidding </h2>
                                    <div id="ai-bid-list-203923" class="c-node-ai__bid-list ">
                                        <div class="view-dom-id-595bb3ce48a74617db4939e8533a681d js-view js-view-air-auction-bids c-view c-view--air-auction-bids c-view--display_auction-item-bids-list c-view--large c-view--display-handler_block c-view--style_table jquery-once-2-processed jqo-vr-processed"
                                            data-view-name="air_auction_bids" data-view-display="auction_item_bids_list"
                                            data-view-page="0">




                                            <div class="js-view-content c-view__content">
                                                <table
                                                    class="views-table cols-3 o-table o-table--view_air-auction-bids o-table--rows o-table--large js-views-table c-views-table c-views-table--view_air-auction-bids c-views-table--display_auction-item-bids-list"
                                                    data-leader="275462">
                                                    <thead>
                                                        <tr>
                                                            <th class="js-view-table-header-field c-views-table__header c-views-table__header--name"
                                                                scope="col" align="bottom">
                                                                Bidder </th>
                                                            <th class="js-view-table-header-field c-views-table__header c-views-table__header--created"
                                                                scope="col" align="bottom">
                                                                Time </th>
                                                            <th class="js-view-table-header-field c-views-table__header c-views-table__header--bid-amount"
                                                                scope="col" align="bottom">
                                                                Bid </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="bid-history-body">
                                                        <tr>
                                                            <td colspan="3" class="text-center">Loading...</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- <footer class="js-ai-footer c-node-ai__footer">
                        <div
                            class="js-ai-page-nav c-node-ai__page-nav c-page-nav c-page-nav--ai c-page-nav--full c-page-nav--footer c-node-ai__page-nav--footer">
                            <ul class="o-list-bare c-page-nav__list">
                                <li class="c-page-nav__item c-page-nav__item--back"><a
                                        href="/views_navigation/ryNUCsD4Ww3L238Ez_b8WXwT917ky-6NkrtYA4uh0Ew/back"
                                        class="c-page-nav__link c-page-nav__link--back js-ai-page-nav-back jso-pnb-processed"
                                        rel="nofollow" data-mousetrap-trigger="m" data-ajax-type="GET"><svg
                                            class="c-icon c-icon--chevron-double--h c-page-nav__icon">
                                            <use
                                                xlink:href="/assets/symbols/symbol-defs-06e142b2b5.svg#icon-chevron-double--h">
                                            </use>
                                        </svg>All items</a></li>
                                <li class="c-page-nav__item c-page-nav__item--next"><a
                                        href="/views_navigation/ryNUCsD4Ww3L238Ez_b8WXwT917ky-6NkrtYA4uh0Ew/1/nojs"
                                        id="air-page-nav-next-footer"
                                        class="c-page-nav__link c-page-nav__link--next js-nav-link-ajax use-ajax ajax-processed"
                                        rel="nofollow" data-mousetrap-trigger="." data-ajax-wrapper="#node-203923"
                                        data-dir="next" data-ajax-type="GET">Next<span class="u-hide@lmd">
                                            item</span><svg class="c-icon c-icon--chevron--h c-page-nav__icon">
                                            <use
                                                xlink:href="/assets/symbols/symbol-defs-06e142b2b5.svg#icon-chevron--h">
                                            </use>
                                        </svg></a></li>
                            </ul>
                        </div>
                    </footer> --}}

                </article>

            </div>
        </div>

        <!-- Bid Modal -->
        <div class="modal fade" id="bidModal" tabindex="-1" aria-labelledby="bidModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="bidForm" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bidModalLabel">Place a Bid</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="bidderName" class="form-label">Name</label>
                    <input type="text" class="form-control" id="bidderName" required>
                </div>
                <div class="mb-3">
                    <label for="bidderEmail" class="form-label">Email</label>
                    <input type="email" class="form-control" id="bidderEmail" required>
                </div>
                <div class="mb-3">
                    <label for="bidAmount" class="form-label">Bid Amount</label>
                    <input type="number" class="form-control" id="bidAmount" min="1" required>
                    <div class="invalid-feedback" id="bidAmountError"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Submit Bid</button>
            </div>
            </form>
        </div>
        </div>


    </main>
@if ($footer->status == 1)
<footer class="standard-client-footer text-white bg-primary" data-footer="" style="
background-color: {{ $footer->background }} !important;
">
    <div class="container">

                    <p class="lead text-center pt-4" style="color: {{ $footer->color }} !important">
                {{ $footer->message }}
            </p>
                    @if ($footer->menu == 1)
                        <div class="nav justify-content-center">
                            @foreach ($check->pages->sortBy('position') as $item)

                            @if($item->status == 1)

                            <div class="nav-item">
                                <a class="nav-link active" href="/page/{{ str_replace(' ', '-', strtolower($item->name)) }}" style="color:{{ $header->color }} !important" aria-current="page">
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
                                        <i class="fa-brands fa-facebook fa-fw" role="img" aria-hidden="true" style="color: {{ $footer->color }} !important"></i>
                                        <span class="visually-hidden">facebook</span>
                                    </a>
                                </li>
                            @endif

                            @if ($footer->instagram)
                                <li class="nav-item">
                                    <a href="{{ $footer->instagram }}" target="_blank">
                                        <i class="fa-brands fa-instagram fa-fw" role="img" aria-hidden="true" style="color: {{ $footer->color }} !important"></i>
                                        <span class="visually-hidden">instagram</span>
                                    </a>
                                </li>
                            @endif

                            @if ($footer->linkedin)
                                <li class="nav-item">
                                    <a href="{{ $footer->linkedin }}" target="_blank">
                                        <i class="fa-brands fa-linkedin fa-fw" role="img" aria-hidden="true" style="color: {{ $footer->color }} !important"></i>
                                        <span class="visually-hidden">linkedin</span>
                                    </a>
                                </li>
                            @endif

                            @if ($footer->pinterest)
                                <li class="nav-item">
                                    <a href="{{ $footer->pinterest }}" target="_blank">
                                        <i class="fa-brands fa-pinterest fa-fw" role="img" aria-hidden="true" style="color: {{ $footer->color }} !important"></i>
                                        <span class="visually-hidden">pinterest</span>
                                    </a>
                                </li>
                            @endif

                            @if ($footer->x)
                                <li class="nav-item">
                                    <a href="{{ $footer->x }}" target="_blank">
                                        <i class="fa-brands fa-x-twitter fa-fw" role="img" aria-hidden="true" style="color: {{ $footer->color }} !important"></i>
                                        <span class="visually-hidden">x</span>
                                    </a>
                                </li>
                            @endif

                            @if ($footer->youtube)
                                <li class="nav-item">
                                    <a href="{{ $footer->youtube }}" target="_blank">
                                        <i class="fa-brands fa-youtube fa-fw" role="img" aria-hidden="true" style="color: {{ $footer->color }} !important"></i>
                                        <span class="visually-hidden">youtube</span>
                                    </a>
                                </li>
                            @endif

                            @if ($footer->blue_sky)
                                <li class="nav-item">
                                    <a href="{{ $footer->blue_sky }}" target="_blank">
                                        <i class="fa-solid fa-cloud fa-fw" role="img" aria-hidden="true" style="color: {{ $footer->color }} !important"></i>
                                        <span class="visually-hidden">blue sky</span>
                                    </a>
                                </li>
                            @endif

                            @if ($footer->tiktok)
                                <li class="nav-item">
                                    <a href="{{ $footer->tiktok }}" target="_blank">
                                        <i class="fa-brands fa-tiktok fa-fw" role="img" aria-hidden="true" style="color: {{ $footer->color }} !important"></i>
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
<input type="hidden" id="product-id" value="{{ $data->id }}">
<script>
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
});



</script>

<script>
    $('.open-modal').on('click', function(){
        $('#bidModal').modal('show');
        $('#bidModal').removeClass('fade');
        $('#bidModal').addClass('show');
    })
</script>

<script>
    const auctionId = "{{ $data->id }}";
    let lastBid = parseFloat("{{ $data->starting_price ?? 0 }}");
    const priceDiv = document.getElementById('auction-price-{{ $data->id }}');
    const bidAmountInput = document.getElementById('bidAmount');

    // Show latest bid on page load using Laravel API
    async function showLatestBid() {
        try {
            const response = await fetch(`/api/auction/${auctionId}/latest-bid`);
            const data = await response.json();
            
            if (data.success && data.amount) {
                priceDiv.textContent = '$' + data.amount;
                lastBid = parseFloat(data.amount);
                if (bidAmountInput) bidAmountInput.min = lastBid + 1;
            }
        } catch (error) {
            console.error('Error fetching latest bid:', error);
        }
    }

    // Load bid history using Laravel API
    async function loadBidHistory() {
        try {
            const response = await fetch(`/api/auction/${auctionId}/bids`);
            const data = await response.json();
            const tbody = document.getElementById('bid-history-body');
            tbody.innerHTML = '';
            
            if (!data.success || data.bids.length === 0) {
                tbody.innerHTML = `<tr><td colspan="3" class="text-center">No bids yet.</td></tr>`;
                return;
            }
            
            data.bids.forEach(bid => {
                const date = new Date(bid.created_at);
                const formattedDate = date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) + ' ' +
                                      date.toLocaleDateString([], { day: '2-digit', month: 'short', year: 'numeric' });
                tbody.innerHTML += `
                    <tr>
                        <td class="c-views-table__field c-views-table__field--name">${bid.name || ''}</td>
                        <td class="c-views-table__field c-views-table__field--created">${formattedDate}</td>
                        <td class="c-views-table__field c-views-table__field--bid-amount">$${bid.amount}</td>
                    </tr>
                `;
            });
        } catch (error) {
            console.error('Error loading bid history:', error);
        }
    }

    // Poll for new bids every 5 seconds
    function startBidPolling() {
        setInterval(async () => {
            try {
                const response = await fetch(`/api/auction/${auctionId}/latest-bid`);
                const data = await response.json();
                
                if (data.success && data.amount && data.amount > lastBid) {
                    priceDiv.textContent = '$' + data.amount;
                    lastBid = parseFloat(data.amount);
                    if (bidAmountInput) bidAmountInput.min = lastBid + 1;
                    
                    // Refresh bid history to show new bid
                    loadBidHistory();
                }
            } catch (error) {
                console.log('Polling error:', error);
            }
        }, 5000); // Poll every 5 seconds
    }

    // On page load
    document.addEventListener('DOMContentLoaded', function() {
        showLatestBid();
        loadBidHistory();
        startBidPolling();
    });

    // Modal form logic
    document.getElementById('bidForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const name = document.getElementById('bidderName').value.trim();
        const email = document.getElementById('bidderEmail').value.trim();
        const amount = parseFloat(document.getElementById('bidAmount').value);

        // Validate bid amount
        if (isNaN(amount) || amount <= lastBid) {
            document.getElementById('bidAmount').classList.add('is-invalid');
            document.getElementById('bidAmountError').textContent = `Bid must be greater than $${lastBid}`;
            return;
        } else {
            document.getElementById('bidAmount').classList.remove('is-invalid');
            document.getElementById('bidAmountError').textContent = '';
        }

        try {
            // Save to Laravel backend
            const response = await fetch('/api/auction/bid', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    auction_id: auctionId,
                    name: name,
                    email: email,
                    amount: amount
                })
            });

            const result = await response.json();

            if (result.success) {
                // Update UI
                await showLatestBid();
                await loadBidHistory();

                // Close modal
                const modalEl = document.getElementById('bidModal');
                let modal = bootstrap.Modal.getInstance(modalEl);
                if (!modal) {
                    modal = new bootstrap.Modal(modalEl);
                }
                modal.hide();

                var id = document.getElementById('product-id').value;
                window.location.href = '/authorize/payment/auction/'+id+'?amount='+amount;
            } else {
                alert('Error saving bid: ' + (result.message || 'Unknown error'));
            }

        } catch (error) {
            console.error('Error saving bid:', error);
            alert('Error saving bid: ' + error.message);
        }
    });
</script>
