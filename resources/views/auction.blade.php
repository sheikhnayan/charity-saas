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
<body style="background-color: {{ $data->page_bg_color }};">
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
        <div class="header text-center mb-4" style="margin-top: 12rem;">
            <h1 style="font-size: 2.75rem; line-height: 1.4; font-weight: bold; font-family: AvenirLTPro-Black,sans-serif; color: #355159">{{ $check->name }} Auction</h1>
        </div>
        <div class="o-layout o-layout--large o-layout--huge@lg">


            <div class="o-layout__item u-2/3@md">




                <div class="c-node-ap__intro">
                    <h3 style="font-size: 1.25rem; line-height: 1.2; color: #d9b730; font-weight: bold; margin-bottom: 1.5rem;">Welcome to the 2025 fundraising auction&nbsp;by {{ $check->name }}.&nbsp;</h3>
                    {{-- <p>Following our hugely successful online auction last year, this year we will once again be raising funds
                        for the {{$check->name}}, a charitable trust benefiting disadvantaged children in our city.</p> --}}
                    <p>The auction is open to all, so please share&nbsp;this page with your friends and family - the aim is
                        simply to raise as much money as possible.</p>
                    <p>Screens in the office will show&nbsp;the <strong>live bid monitor</strong> so you can watch the bids come in.</p>
                    <h4 style="font-family: AvenirLTPro-Black,sans-serif; font-size: 1rem; line-height: 1.5; font-weight: bold;">----- This is an example auction for&nbsp;demonstration purposes only. Feel free to bid! ----</h4>
                </div>

                <div class="c-node-ap__auction-summary" data-live-id="total" data-updated="1750712944">
                    <div class="c-node-ap__auction-results">
                        <div class="c-node-ap__auction-total">
                            <div class="c-node-ap__fundraising-target">
                                <div class="c-node-ap__auction-total-label">Fundraising target</div>
                                <div class="c-node-ap__auction-total-amount">${{ $setting->goal }}</div>
                                <progress class="c-node-ap__totalizer" value="{{ $user->donations->sum('amount') }}" max="{{ $setting->goal }}" title="251% raised"></progress>
                            </div>
                            <div class="c-node-ap__auction-total-label" data-live-item="label">Running total</div>
                            <div class="c-node-ap__auction-total-amount u-tc--highlight" data-live-item="amount">${{ $user->donations->sum('amount') }}
                            </div>
                            <div class="c-node-ap__totalizer-wrapper"></div>
                            <div class="c-node-ap__auction-total-components">
                                <div class="c-node-ap__auction-total-component-auction"><span
                                        class="c-node-ap__auction-total-component-label">Bids: </span>
                                    <span class="c-node-ap__auction-total-component-amount u-tc--highlight"
                                        data-live-item="auction">$0</span>
                                </div>
                                <div class="c-node-ap__auction-total-component-donations"><span
                                        class="c-node-ap__auction-total-component-label">Donations: </span>
                                    <span class="c-node-ap__auction-total-component-amount u-tc--highlight"
                                        data-live-item="donations">$1,000.00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="c-node-ap__timer-wrapper">
                        <div class="c-node-ap__timer c-node-ap__timer--auction-summary">
                            <div class="js-timer-wrapper c-timer c-timer--small-block c-timer--next-expiry u-hide-no-js">
                                <div class="c-timer__title" data-live-item="next_expiry_label">Time remaining</div>
                                @php
$label = $data['countdownData']['label'] ?? '';
$date = $setting->date;
                                        @endphp
                                        <div class="event-countdown" style="border-radius:8px;text-align:center;margin-bottom:24px;">
                                            <div class="timer text-center mt-5">
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <div class="mx-3"><h1 id="months" class="display-4">0</h1><p>Months</p></div>
                                                    <div class="mx-3"><h1 id="days" class="display-4">0</h1><p>Days</p></div>
                                                    <div class="mx-3"><h1 id="hours" class="display-4">0</h1><p>Hours</p></div>
                                                    <div class="mx-3"><h1 id="minutes" class="display-4">0</h1><p>Minutes</p></div>
                                                    <div class="mx-3"><h1 id="seconds" class="display-4">0</h1><p>Seconds</p></div>
                                                </div>
                                                <p style="font-size: .8em;">{{ $label }}</p>
                                            </div>
                                            <input type="hidden" id="timer" class="date-countdown" value="{{ $date }}">
                                        </div>

                                        <script>
                                            da = document.getElementById("timer").value;
                                            // Set the target date for the countdown
                                            const targetDate = new Date(da).getTime();

                                            function updateCountdown() {
                                                const now = new Date().getTime();
                                                const timeLeft = targetDate - now;

                                                if (timeLeft <= 0) {
                                                    document.getElementById("months").textContent = 0;
                                                    document.getElementById("days").textContent = 0;
                                                    document.getElementById("hours").textContent = 0;
                                                    document.getElementById("minutes").textContent = 0;
                                                    document.getElementById("seconds").textContent = 0;
                                                    return;
                                                }

                                                // Calculate time components
                                                const months = Math.floor(timeLeft / (1000 * 60 * 60 * 24 * 30));
                                                const days = Math.floor((timeLeft % (1000 * 60 * 60 * 24 * 30)) / (1000 * 60 * 60 * 24));
                                                const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                                const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                                                const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

                                                // Update the HTML
                                                document.getElementById("months").textContent = months;
                                                document.getElementById("days").textContent = days;
                                                document.getElementById("hours").textContent = hours;
                                                document.getElementById("minutes").textContent = minutes;
                                                document.getElementById("seconds").textContent = seconds;
                                            }

                                            // Update the countdown every second
                                            setInterval(updateCountdown, 1000);
                                        </script>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="c-node-ap__donate-now c-node-ap__donate-now--bottom u-hide@md">
                    <div id="air-donate-now--2"
                        class="c-donate-now c-donate-now--bottom c-donate-now--both c-donate-now--stacked">
                        <form
                            class="c-form js-form js-form-air-donation-donate-now-form c-form--small c-donate-now__form js-prefix-input c-form--air-donation-donate-now-form jqo-fs-processed"
                            autocomplete="off" data-cache-expiry="1750734544" action="/example/grid-small" method="post"
                            id="air-donation-donate-now-form--2" accept-charset="UTF-8" novalidate="novalidate">
                            <div class="js-form-wrapper c-form__wrapper">
                                <div class="js-form-container c-content__form-container c-form__container c-donate-now__wrapper c-form__container--clearfix"
                                    id="edit-wrapper--2">
                                    <div class="js-form-container c-content__form-container c-form__container c-donate-now__icon c-form__container--clearfix"
                                        id="edit-icon--2"><svg class="c-icon c-icon--gift-m">
                                            <use xlink:href="/assets/symbols/symbol-defs-06e142b2b5.svg#icon-gift-m"></use>
                                        </svg></div>
                                    <h5 class="c-donate-now__title">Pledge a donation</h5>
                                    <div id="edit-donate-now-buttons--2"
                                        class="c-donate-now__buttons js-form-checkboxes c-form__checkboxes">
                                        <div
                                            class="js-form-group c-donate-now__button c-form__group c-form__group--type_checkbox c-form__group--name_donate-now-buttons[10] c-form__group--element_donate-now-buttons">
                                            <div
                                                class="js-form-control-wrapper c-form__control-wrapper c-form__control-wrapper--checkbox">
                                                <input
                                                    class="js-form-control c-form__control c-form__control--checkbox js-no-icheck c-form__control--checkbox-button jqo-sf-processed"
                                                    type="checkbox" id="edit-donate-now-buttons-10--2"
                                                    name="donate_now_buttons[10]" value="10"> <label
                                                    class="js-form-label c-form__label c-form__label--after"
                                                    for="edit-donate-now-buttons-10--2"><span
                                                        class="js-label-text c-form__label-text">$10</span></label>
                                            </div>
                                        </div>
                                        <div
                                            class="js-form-group c-donate-now__button c-form__group c-form__group--type_checkbox c-form__group--name_donate-now-buttons[25] c-form__group--element_donate-now-buttons">
                                            <div
                                                class="js-form-control-wrapper c-form__control-wrapper c-form__control-wrapper--checkbox">
                                                <input
                                                    class="js-form-control c-form__control c-form__control--checkbox js-no-icheck c-form__control--checkbox-button jqo-sf-processed"
                                                    type="checkbox" id="edit-donate-now-buttons-25--2"
                                                    name="donate_now_buttons[25]" value="25"> <label
                                                    class="js-form-label c-form__label c-form__label--after"
                                                    for="edit-donate-now-buttons-25--2"><span
                                                        class="js-label-text c-form__label-text">$25</span></label>
                                            </div>
                                        </div>
                                        <div
                                            class="js-form-group c-donate-now__button c-form__group c-form__group--type_checkbox c-form__group--name_donate-now-buttons[50] c-form__group--element_donate-now-buttons">
                                            <div
                                                class="js-form-control-wrapper c-form__control-wrapper c-form__control-wrapper--checkbox">
                                                <input
                                                    class="js-form-control c-form__control c-form__control--checkbox js-no-icheck c-form__control--checkbox-button jqo-sf-processed"
                                                    type="checkbox" id="edit-donate-now-buttons-50--2"
                                                    name="donate_now_buttons[50]" value="50"> <label
                                                    class="js-form-label c-form__label c-form__label--after"
                                                    for="edit-donate-now-buttons-50--2"><span
                                                        class="js-label-text c-form__label-text">$50</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="js-form-container c-content__form-container c-form__container js-prefix-input c-donate-now__amount-wrapper c-form__container--no-clearfix"
                                        id="edit-input-wrapper--2">
                                        <div
                                            class="js-form-item c-donate-now__input-wrapper c-form__item c-form__item--type_numberfield c-form__item--name_donate-now-amount c-form__item--element_donate-now-amount c-form__item--no-title">
                                            <div class="c-form__control-wrapper c-form__control-wrapper--numberfield">
                                                <div class="js-prefixed-input c-form__prefixed-input-js"><span
                                                        class="js-input-prefix c-form__input-prefix-js u-fade-js in">$</span><input
                                                        class="js-form-control c-input c-input--number c-form__control js-form-control c-input c-input--numberfield c-form__control js-input-to-prefix js-checkboxes-or-input jqo-cboi-processed"
                                                        placeholder="Other amount" id="edit-donate-now-amount--2"
                                                        name="donate_now_amount" value="" step="any" min="1" type="number"
                                                        style="padding-left: 17px;"></div>
                                            </div>
                                        </div>
                                    </div><button
                                        class="c-button js-button js-submit-button c-button--submit c-form__control--submit-button c-form__control--button c-form__control js-form-control c-button--small c-donate-now__submit ajax-processed"
                                        type="submit" id="edit-submit--3" name="op" value="Pledge">Pledge<span
                                            class="js-ajax-progress c-ajax-progress c-ajax-progress--submit c-ajax-progress--small c-button__ajax-progress"></span>
                                    </button>
                                </div><input type="hidden" name="form_build_id"
                                    value="form-IdPAIIPnw55m_1HRePBVaYSJSooBGtkP_PpzDqqVocE">
                                <input type="hidden" name="form_id" value="air_donation_donate_now_form">
                            </div>
                        </form>
                    </div>
                </div>




                <div class="js-collapse-container c-collapse">
                    <h3 class="c-heading--gamma">
                        <span class="js-collapse-toggle c-collapse__toggle jqo-ic-processed">
                            Auction terms and conditions <svg
                                class="c-icon c-icon--chevron js-collapse-indicator c-collapse__indicator u-margin-left-small">
                                <use xlink:href="/assets/symbols/symbol-defs-06e142b2b5.svg#icon-chevron"></use>
                            </svg> </span>
                    </h3>
                    <div class="js-collapse-target c-collapse__target">
                        <div class="c-node-ap__terms">
                            <p>All the items here are listed for demonstration purposes only.</p>
                        </div>
                    </div>
                </div>




                <div class="c-node-ap__contact">
                    <div class="c-node-ap__contact-details">
                        <h5 class="c-heading--epsilon">
                            Items in this auction are being sold by {{ $check->name }} </h5>
                        <div class="u-margin-bottom-small">
                            Contact name: <strong>{{ $check->name }}</strong><br>
                            Email: <strong><a
                                    href="mailto:{{ $user->email }}?subject={{ $check->main }}"
                                    class="c-link--quiet">{{ $user->email }}</a></strong><br>
                        </div>
                    </div>
                </div>

                <div class="c-node-ap__notes">
                    <div class="c-node-ap__note">Items in this auction are subject to an automatic extension feature. If a bid
                        is
                        received within
                        10 minutes of expiry, bidding is extended by 10 minutes. These times, and the
                        number of extensions which may take place, may be changed by the auction administrator without notice.
                    </div>
                    <div class="c-node-ap__note">Items in this auction may be subject to an undisclosed reserve price.
                        Item prices which do not meet the reserve are marked <sup>‡</sup>.
                        Should the leading bid at expiry not meet the reserve price, the item will not be sold.</div>
                    <div class="c-node-ap__note">Bids for items in this auction are in US Dollars.</div>
                </div>

            </div>

            <div class="o-layout__item u-hide@lmd u-1/3@md">
                <div class="c-node-ap__sidebar">
                    <div class="c-node-ap__sidebar-image">
                        <img style="max-width: 2003px;"
                            src="https://airauctioneer-live.s3.us-west-1.amazonaws.com/styles/air_scaled_440/s3/u4/auction-sidebar-images/aegir.png?itok=QdQWfhzP"
                            srcset="https://airauctioneer-live.s3.us-west-1.amazonaws.com/styles/air_scaled_100/s3/u4/auction-sidebar-images/aegir.png?itok=wiVlfN-V 100w, https://airauctioneer-live.s3.us-west-1.amazonaws.com/styles/air_scaled_200/s3/u4/auction-sidebar-images/aegir.png?itok=i_36c4Gm 200w, https://airauctioneer-live.s3.us-west-1.amazonaws.com/styles/air_scaled_440/s3/u4/auction-sidebar-images/aegir.png?itok=QdQWfhzP 440w, https://airauctioneer-live.s3.us-west-1.amazonaws.com/styles/air_scaled_620/s3/u4/auction-sidebar-images/aegir.png?itok=ptXpCysr 620w, https://airauctioneer-live.s3.us-west-1.amazonaws.com/styles/air_scaled_840/s3/u4/auction-sidebar-images/aegir.png?itok=OKp7cwVa 840w, https://airauctioneer-live.s3.us-west-1.amazonaws.com/styles/air_scaled_1240/s3/u4/auction-sidebar-images/aegir.png?itok=Tnutebki 1240w, https://airauctioneer-live.s3.us-west-1.amazonaws.com/styles/air_scaled_1800/s3/u4/auction-sidebar-images/aegir.png?itok=q84UQkA1 1800w, https://airauctioneer-live.s3.us-west-1.amazonaws.com/styles/air_scaled_3600/s3/u4/auction-sidebar-images/aegir.png?itok=-KFlZQB_ 2003w"
                            alt="" sizes="(min-width: 60em) 25vw, 0px">
                    </div>
                    <div class="c-node-ap__donate-now c-node-ap__donate-now--sidebar">
                        <div id="air-donate-now"
                            class="c-donate-now c-donate-now--sidebar c-donate-now--both c-donate-now--stacked">
                            <form
                                class="c-form js-form js-form-air-donation-donate-now-form c-form--small c-donate-now__form js-prefix-input c-form--air-donation-donate-now-form jqo-fs-processed"
                                autocomplete="off" data-cache-expiry="1750734544" action="/donation-general" method="post"
                                id="air-donation-donate-now-form" accept-charset="UTF-8" novalidate="novalidate">
                                @csrf

                                <input type="hidden" name="website_id" value="{{ $check->id }}">
                                <div class="js-form-wrapper c-form__wrapper">
                                    <div class="js-form-container c-content__form-container c-form__container c-donate-now__wrapper c-form__container--clearfix"
                                        id="edit-wrapper">
                                        <div class="js-form-container c-content__form-container c-form__container c-donate-now__icon c-form__container--clearfix"
                                            id="edit-icon"><svg class="c-icon c-icon--gift-m">
                                                <use xlink:href="/assets/symbols/symbol-defs-06e142b2b5.svg#icon-gift-m"></use>
                                            </svg></div>
                                        <h5 class="c-donate-now__title">Pledge a donation</h5>
                                        <div class="js-form-container c-content__form-container c-form__container js-prefix-input c-donate-now__amount-wrapper c-form__container--no-clearfix"
                                            id="edit-input-wrapper">
                                            <div
                                                class="js-form-item c-donate-now__input-wrapper c-form__item c-form__item--type_numberfield c-form__item--name_donate-now-amount c-form__item--element_donate-now-amount c-form__item--no-title">
                                                <div class="c-form__control-wrapper c-form__control-wrapper--numberfield">
                                                    <div class="js-prefixed-input c-form__prefixed-input-js"><span
                                                            class="js-input-prefix c-form__input-prefix-js u-fade-js in">$</span><input
                                                            class="js-form-control c-input c-input--number c-form__control js-form-control c-input c-input--numberfield c-form__control js-input-to-prefix js-checkboxes-or-input jqo-cboi-processed"
                                                            placeholder="Amount" id="edit-donate-now-amount"
                                                            name="donate_now_amount" value="" step="any" min="1" type="number"
                                                            style="padding-left: 25px;"></div>
                                                </div>
                                            </div>
                                        </div><button
                                            class="c-button js-button js-submit-button c-button--submit c-form__control--submit-button c-form__control--button c-form__control js-form-control c-button--small c-donate-now__submit ajax-processed"
                                            type="submit" id="edit-submit--2" name="op" value="Pledge">Pledge<span
                                                class="js-ajax-progress c-ajax-progress c-ajax-progress--submit c-ajax-progress--small c-button__ajax-progress"></span>
                                        </button>
                                    </div><input type="hidden" name="form_build_id"
                                        value="form-rgDz_q-hAYGhKUrTRm365lyoovgacO4Uttd1rZ7DZgE">
                                    <input type="hidden" name="form_id" value="air_donation_donate_now_form">
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="c-node-ap__sharing c-node-ap__sharing--sidebar">
                        <div class="c-share c-share--ap c-share--sidebar">
                            <div class="c-node-ap__sharing-text c-share__text">
                                <h2>Sharing</h2>
                                <p>Please share with your family, friends and colleagues - the auction is open to all</p>
                            </div>
                            <div class="">
                                <ul class="o-list-bare c-share__list">
                                    <li class="c-share__item"><a href="/auction"
                                            class="c-share__link c-share__link--link js-copy-to-clipboard" target="_blank"
                                            data-ctc-url="/auction" data-ctc-text="Copy link"><svg
                                                class="c-icon c-icon--link-disc c-share__icon">
                                                <use xlink:href="/assets/symbols/symbol-defs-06e142b2b5.svg#icon-link-disc">
                                                </use>
                                            </svg><span class="c-share__link-text">Copy link</span></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="c-content__bottom">
            <div class="u-wrap--auction-main">
                <div id="ai-display" class="c-ai-display c-ai-display--full"><span></span>

                    <div class="o-wrapper c-ai-display__items c-ai-display__items--full">
                        <div class="view-dom-id-ad4934c196a50f6f72cd5a8f4b22c874 js-view js-view-air-auction-items c-view c-view--air-auction-items c-view--display_teaser c-view--display-handler_block c-view--style_default jquery-once-2-processed jqo-vr-processed"
                            data-view-name="air_auction_items" data-view-display="teaser" data-view-page="0">




                            <div class="js-view-content c-view__content">
                                @foreach ($data as $item)
                                    <div class="c-view__item c-view__item--teaser">
                                        <div id="node-203924"
                                            class="c-node-ai c-node-ai--teaser js-ai js-ai--teaser js-eq js-ai--teaser-view c-node-ai--teaser-view"
                                            about="/example/shortbread-biscuits" typeof="sioc:Item foaf:Document"
                                            data-entity-id="203924" data-unmet-reserve="0" data-live-id="203924"
                                            data-updated="1750859597" data-leader="264581" data-status="bidding" data-lec="false"
                                            data-expiry="1767168000">
                                            <div class="c-node-ai__content">
                                                <div id="air-ai-status-indicator-203924"
                                                    class="js-ai-status-indicator c-node-ai__status c-node-ai__status--teaser c-tooltip c-tooltip--n"
                                                    aria-label="Bidding is under way."></div>
                                                <div class="c-node-ai__image-wrap">
                                                    <div class="c-node-ai__image">
                                                        <svg viewBox="0 0 100 100"></svg>
                                                        <a  href="/auction/{{ $item->id }}"
                                                            class=""><img alt=""
                                                                sizes="(min-width: 110em) 420px, (min-width: 90em) 25vw, (min-width: 60em) 33vw, (min-width: 30em) 50vw, 100vw"
                                                                data-src="{{ asset('/uploads/'.$item->images[0]->image) }}"
                                                                data-srcset="{{ asset('/uploads/'.$item->images[0]->image) }}"
                                                                class="jqo-io-processed"
                                                                src="{{ asset('/uploads/'.$item->images[0]->image) }}"
                                                                srcset="{{ asset('/uploads/'.$item->images[0]->image) }}"></a>
                                                    </div>
                                                </div>

                                                <div class="c-node-ai__details-wrap">
                                                    <h3 class="c-node-ai__title c-heading--gamma">
                                                        <a href="/auction/{{ $item->id }}"
                                                            data-mousetrap-trigger="4">
                                                            {{ $item->title }} </a>
                                                    </h3>

                                                    <div class="c-node-ai__bidding-details">
                                                        <div class="o-layout">
                                                            <div class="o-layout__item u-7/12">
                                                                <div class="c-node-ai__timer">
                                                                    <div id="ai-timer-203924"
                                                                        class="js-timer-wrapper c-timer c-timer--small-block u-hide-no-js">
                                                                        <div class="c-timer__title"><span
                                                                                class="js-timer-title">Time remaining</span></div>
                                                                        <span class="c-timer__body">
                                                                            <span
                                                                                class="js-timer"
                                                                                data-timer_id="ai-{{ $item->id }}-long-small-block"
                                                                                data-type="expiry"
                                                                                data-timeout="{{ \Carbon\Carbon::parse($item->dead_line)->timestamp }}"
                                                                                data-format_num="long"
                                                                                data-deadline="{{ $item->dead_line }}"
                                                                                id="auction-timer-{{ $item->id }}"
                                                                            >
                                                                                <span class="js-timer-element-days c-timer__element">
                                                                                    <span class="c-timer__value" id="days-{{ $item->id }}">0</span>
                                                                                    <span class="c-timer__period">Days</span>
                                                                                </span>
                                                                                <span class="c-timer__element">
                                                                                    <span class="c-timer__value" id="hours-{{ $item->id }}">0</span>
                                                                                    <span class="c-timer__period">Hrs</span>
                                                                                </span>
                                                                                <span class="c-timer__element">
                                                                                    <span class="c-timer__value" id="minutes-{{ $item->id }}">0</span>
                                                                                    <span class="c-timer__period">Mins</span>
                                                                                </span>
                                                                                {{-- <span class="js-timer-element-secs u-hide-js c-timer__element">
                                                                                    <span class="c-timer__value" id="seconds-{{ $item->id }}">0</span>
                                                                                    <span class="c-timer__period">Secs</span>
                                                                                </span> --}}
                                                                            </span>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="o-layout__item u-5/12">
                                                                <div class="c-node-ai__price">
                                                                    <div id="ai-price-203924" class="c-price  c-price--small-block">
                                                                        <div class="c-price__title"><span
                                                                                class="js-price-title">Current bid</span></div>
                                                                        <div class="c-price__wrapper">
                                                                            <div class="c-price__value js-resize-bid-text u-tc--highlight-bg"
                                                                                id="auction-price-{{ $item->id }}"
                                                                                data-live-item="price"
                                                                                data-tcid="{{ $item->id }}:price"
                                                                                style="font-size: 16px;">
                                                                                ${{ $item->starting_price ?? 0 }}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>




                            <div class="c-view__footer">
                                <div class="c-view__footer-item">* Bidding to be continued at the live event.</div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

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
    @foreach ($data as $item)
        startAuctionTimer("{{ $item->dead_line }}", "{{ $item->id }}");
    @endforeach
});
</script>

<script type="module">
import { initializeApp } from "https://www.gstatic.com/firebasejs/11.9.1/firebase-app.js";
import { getFirestore, collection, query, where, orderBy, getDocs, limit } from "https://www.gstatic.com/firebasejs/11.9.1/firebase-firestore.js";

// Your Firebase config
const firebaseConfig = {
    apiKey: "AIzaSyD0QsLeSIAFeBBUouzhgUQ3WEGfM1MAYA4",
    authDomain: "charity-390ca.firebaseapp.com",
    projectId: "charity-390ca",
    storageBucket: "charity-390ca.firebasestorage.app",
    messagingSenderId: "875958450032",
    appId: "1:875958450032:web:338aeac86307e5ab3e41b5",
    measurementId: "G-FC73HL5XF3"
};

const app = initializeApp(firebaseConfig);
const firestore = getFirestore(app);

document.addEventListener('DOMContentLoaded', async function() {
    @foreach ($data as $item)
        {
            const auctionId = "{{ $item->id }}";
            const priceDiv = document.getElementById('auction-price-{{ $item->id }}');
            if (priceDiv) {
                const bidsRef = collection(firestore, "bid");
                const q = query(
                    bidsRef,
                    where("auction_id", "==", auctionId),
                    orderBy("amount", "desc"),
                    limit(1)
                );
                const querySnapshot = await getDocs(q);
                if (!querySnapshot.empty) {
                    const doc = querySnapshot.docs[0];
                    const latestAmount = doc.data().amount;
                    priceDiv.textContent = '$' + latestAmount;
                }
            }
        }
    @endforeach
});
</script>

<!-- Payment Funnel Tracking -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="{{ asset('js/payment-funnel-tracking.js') }}"></script>
