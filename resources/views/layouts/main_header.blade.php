<!DOCTYPE html>
<!-- Last Published: Thu Aug 28 2025 16:28:18 GMT+0000 (Coordinated Universal Time) -->
<html data-wf-page="68547489207784144a773f3e" data-wf-site="656f55af4b70f4ce7ae4b997"
    lang="en">
@php
    $main_setting = \App\Models\DealmakerConfig::getInstance();;
@endphp
<head>
    <meta charset="utf-8" />
    <title>{{ ($main_setting && $main_setting->meta_title) ? $main_setting->meta_title : 'DealMaker | Raise Capital Online' }}</title>
    <meta
        content="{{ ($main_setting && $main_setting->meta_description) ? $main_setting->meta_description : 'DealMaker empowers founders to raise capital online via Reg A, CF, and D. Our tools help companies reach investors and build community from seed to IPO.' }}"
        name="description" />
    <meta content="{{ ($main_setting && $main_setting->meta_title) ? $main_setting->meta_title : 'DealMaker | Raise Capital Online' }}" property="og:title" />
    <meta
        content="{{ ($main_setting && $main_setting->meta_description) ? $main_setting->meta_description : 'DealMaker empowers founders to raise capital online via Reg A, CF, and D. Our tools help companies reach investors and build community from seed to IPO.' }}"
        property="og:description" />
    <meta
        content="{{ ($main_setting && $main_setting->uploaded_og_image) ? asset($main_setting->uploaded_og_image) : (($main_setting && $main_setting->og_image) ? $main_setting->og_image : 'https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/685d988c9d3abae4ca097302_opengraphimage.png') }}"
        property="og:image" />
    <meta content="{{ ($main_setting && $main_setting->meta_title) ? $main_setting->meta_title : 'DealMaker | Raise Capital Online' }}" property="twitter:title" />
    <meta
        content="{{ ($main_setting && $main_setting->meta_description) ? $main_setting->meta_description : 'DealMaker empowers founders to raise capital online via Reg A, CF, and D. Our tools help companies reach investors and build community from seed to IPO.' }}"
        property="twitter:description" />
    <meta property="og:type" content="website" />
    <meta content="summary_large_image" name="twitter:card" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    @if ($main_setting && $main_setting->meta_keywords)
        <meta name="keywords" content="{{ $main_setting->meta_keywords }}" />
    @endif
    <meta content="google-site-verification=cfRfTejLrKY67Lsv3uWZ-Dt1WC9ny_7amMPApbAw-fc"
        name="google-site-verification" />
    <link href="{{ asset('css/dealmaker-main.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/dealmaker-fonts.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset('js/webfont.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        WebFont.load({
            google: {
                families: ["Inconsolata:400,700", "Inter:300,regular,500,600,700,800", "Graduate:regular"]
            }
        });
    </script>
    <script type="text/javascript">
        ! function(o, c) {
            var n = c.documentElement,
                t = " w-mod-";
            n.className += t + "js", ("ontouchstart" in o || o.DocumentTouch && c instanceof DocumentTouch) && (n
                .className += t + "touch")
        }(window, document);
    </script>
    <link href="https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/685d9bb6592d754fd6f30eca_fav.png"
        rel="shortcut icon" type="image/x-icon" />
    <link href="https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/685d9c0817e01979b38b7550_fav_256.png"
        rel="apple-touch-icon" />
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-MFLTGJ2');
    </script>
    <!-- End Google Tag Manager -->

    @if ($main_setting && $main_setting->custom_head_code)
        {!! $main_setting->custom_head_code !!}
    @endif

    @if ($main_setting && $main_setting->custom_css)
        <style>
            {!! $main_setting->custom_css !!}
        </style>
    @endif



    <!-- Please keep this css code to improve the font quality-->
    <style>
        * {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            -o-font-smoothing: antialiased;
        }

        .header-update-video {
            display: block !important;
        }
    </style><!-- Include Swiper CSS -->
    <link rel="stylesheet" href="{{ asset('css/swiper-bundle.min.css') }}" />
    <style>
        .swiper_pagination {
            position: relative;
        }

        .swiper_pagination_fraction {
            bottom: 0px;
        }

        .swiper-pagination-bullet-active {
            background: #f31cb6;
        }


        .w-container {
            max-width: 1024px !important;
        }

        .w-slider-dot.w-active {
            width: 50px;
            background-color: #8EE8DF !important;
            border-radius: 10px !important;
            -webkit-transition: all 642ms cubic-bezier(.23, 1, .32, 1);
            transition: all 642ms cubic-bezier(.23, 1, .32, 1);
        }

        .w-slider-dot {
            width: 0.8em;
            height: 0.8em;
            background-color: #FFFFFF !important;
            -webkit-transition: all 642ms cubic-bezier(.23, 1, .32, 1);
            transition: all 642ms cubic-bezier(.23, 1, .32, 1);
        }
        
        /* Apply primary button color to all non-transparent buttons */
        .n_button:not(.is-ghost),
        .hero-cta-button,
        .w-button {
            background-color: {{ ($main_setting && $main_setting->button_primary_color) ? $main_setting->button_primary_color : '#f31cb6' }} !important;
            border-color: {{ ($main_setting && $main_setting->button_primary_color) ? $main_setting->button_primary_color : '#f31cb6' }} !important;
            color: {{ ($main_setting && $main_setting->button_text_color) ? $main_setting->button_text_color : '#ffffff' }} !important;
        }
        
        /* Hover and active states for buttons */
        .n_button:not(.is-ghost):hover,
        .n_button:not(.is-ghost):active,
        .n_button:not(.is-ghost):focus,
        .hero-cta-button:hover,
        .hero-cta-button:active,
        .hero-cta-button:focus,
        .w-button:hover,
        .w-button:active,
        .w-button:focus {
            background-color: {{ ($main_setting && $main_setting->button_hover_color) ? $main_setting->button_hover_color : '#d1179a' }} !important;
            border-color: {{ ($main_setting && $main_setting->button_hover_color) ? $main_setting->button_hover_color : '#d1179a' }} !important;
            color: {{ ($main_setting && $main_setting->button_text_color) ? $main_setting->button_text_color : '#ffffff' }} !important;
        }
        
        /* Include all button variations except is-ghost */
        .n_button.is-small:not(.is-ghost),
        .n_button.is-darker:not(.is-ghost),
        .n_button.is-alternate:not(.is-ghost) {
            background-color: {{ $main_setting->button_primary_color ?? '#f31cb6' }} !important;
            border-color: {{ $main_setting->button_primary_color ?? '#f31cb6' }} !important;
            color: {{ $main_setting->button_text_color ?? '#ffffff' }} !important;
        }
        
        .n_button.is-small:not(.is-ghost):hover,
        .n_button.is-small:not(.is-ghost):active,
        .n_button.is-small:not(.is-ghost):focus,
        .n_button.is-darker:not(.is-ghost):hover,
        .n_button.is-darker:not(.is-ghost):active,
        .n_button.is-darker:not(.is-ghost):focus,
        .n_button.is-alternate:not(.is-ghost):hover,
        .n_button.is-alternate:not(.is-ghost):active,
        .n_button.is-alternate:not(.is-ghost):focus {
            background-color: {{ $main_setting->button_hover_color ?? '#d1179a' }} !important;
            border-color: {{ $main_setting->button_hover_color ?? '#d1179a' }} !important;
            color: {{ $main_setting->button_text_color ?? '#ffffff' }} !important;
        }
        
        /* Keep transparent buttons (is-ghost) unchanged */
        .n_button.is-ghost {
            background-color: transparent !important;
            border-color: currentColor !important;
        }
        
        .n_button.is-ghost:hover,
        .n_button.is-ghost:active,
        .n_button.is-ghost:focus {
            background-color: transparent !important;
            border-color: currentColor !important;
        }
        
        /* Social Icon Styling */
        .footer3_social-link {
            background-color: {{ $main_setting->social_icon_bg_color ?? '#f31cb6' }} !important;
            border-radius: 8px !important;
            padding: 12px !important;
            transition: all 0.3s ease !important;
        }
        
        .footer3_social-link:hover {
            background-color: {{ $main_setting->social_icon_hover_color ?? '#d1179a' }} !important;
        }
        
        .footer3_social-link svg,
        .footer3_social-link .footer-icon svg {
            color: {{ $main_setting->social_icon_color ?? '#ffffff' }} !important;
            fill: {{ $main_setting->social_icon_color ?? '#ffffff' }} !important;
        }
    </style>
</head>

<body class="body">
    <div class="page-wrapper">
        <div class="global-styles w-embed">
            <style>
                /* Focus state style for keyboard navigation for the focusable elements */
                *[tabindex]:focus-visible,
                input[type="file"]:focus-visible {
                    outline: 0.125rem solid #4d65ff;
                    outline-offset: 0.125rem;
                }

                /* Get rid of top margin on first element in any rich text element */
                .w-richtext> :not(div):first-child,
                .w-richtext>div:first-child> :first-child {
                    margin-top: 0 !important;
                }

                /* Get rid of bottom margin on last element in any rich text element */
                .w-richtext>:last-child,
                .w-richtext ol li:last-child,
                .w-richtext ul li:last-child {
                    margin-bottom: 0 !important;
                }

                /* Prevent all click and hover interaction with an element */
                .pointer-events-off {
                    pointer-events: none;
                }

                /* Enables all click and hover interaction with an element */
                .pointer-events-on {
                    pointer-events: auto;
                }

                /* Create a class of .div-square which maintains a 1:1 dimension of a div */
                .div-square::after {
                    content: "";
                    display: block;
                    padding-bottom: 100%;
                }

                /* Make sure containers never lose their center alignment */
                .container-medium,
                .container-small,
                .container-large {
                    margin-right: auto !important;
                    margin-left: auto !important;
                }

                /*
Make the following elements inherit typography styles from the parent and not have hardcoded values.
Important: You will not be able to style for example "All Links" in Designer with this CSS applied.
Uncomment this CSS to use it in the project. Leave this message for future hand-off.
*/
                /*
a,
.w-input,
.w-select,
.w-tab-link,
.w-nav-link,
.w-dropdown-btn,
.w-dropdown-toggle,
.w-dropdown-link {
  color: inherit;
  text-decoration: inherit;
  font-size: inherit;
}
*/

                /* Apply "..." after 3 lines of text */
                .text-style-3lines {
                    display: -webkit-box;
                    overflow: hidden;
                    -webkit-line-clamp: 3;
                    -webkit-box-orient: vertical;
                }

                /* Apply "..." after 2 lines of text */
                .text-style-2lines {
                    display: -webkit-box;
                    overflow: hidden;
                    -webkit-line-clamp: 2;
                    -webkit-box-orient: vertical;
                }

                /* Adds inline flex display */
                .display-inlineflex {
                    display: inline-flex;
                }

                /* These classes are never overwritten */
                .hide {
                    display: none !important;
                }

                @media screen and (max-width: 991px) {

                    .hide,
                    .hide-tablet {
                        display: none !important;
                    }
                }

                @media screen and (max-width: 767px) {
                    .hide-mobile-landscape {
                        display: none !important;
                    }
                }

                @media screen and (max-width: 479px) {
                    .hide-mobile {
                        display: none !important;
                    }
                }

                .margin-0 {
                    margin: 0rem !important;
                }

                .padding-0 {
                    padding: 0rem !important;
                }

                .spacing-clean {
                    padding: 0rem !important;
                    margin: 0rem !important;
                }

                .margin-top {
                    margin-right: 0rem !important;
                    margin-bottom: 0rem !important;
                    margin-left: 0rem !important;
                }

                .padding-top {
                    padding-right: 0rem !important;
                    padding-bottom: 0rem !important;
                    padding-left: 0rem !important;
                }

                .margin-right {
                    margin-top: 0rem !important;
                    margin-bottom: 0rem !important;
                    margin-left: 0rem !important;
                }

                .padding-right {
                    padding-top: 0rem !important;
                    padding-bottom: 0rem !important;
                    padding-left: 0rem !important;
                }

                .margin-bottom {
                    margin-top: 0rem !important;
                    margin-right: 0rem !important;
                    margin-left: 0rem !important;
                }

                .padding-bottom {
                    padding-top: 0rem !important;
                    padding-right: 0rem !important;
                    padding-left: 0rem !important;
                }

                .margin-left {
                    margin-top: 0rem !important;
                    margin-right: 0rem !important;
                    margin-bottom: 0rem !important;
                }

                .padding-left {
                    padding-top: 0rem !important;
                    padding-right: 0rem !important;
                    padding-bottom: 0rem !important;
                }

                .margin-horizontal {
                    margin-top: 0rem !important;
                    margin-bottom: 0rem !important;
                }

                .padding-horizontal {
                    padding-top: 0rem !important;
                    padding-bottom: 0rem !important;
                }

                .margin-vertical {
                    margin-right: 0rem !important;
                    margin-left: 0rem !important;
                }

                .padding-vertical {
                    padding-right: 0rem !important;
                    padding-left: 0rem !important;
                }

                /* Apply "..." at 100% width */
                .truncate-width {
                    width: 100%;
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                }

                /* Removes native scrollbar */
                .no-scrollbar {
                    -ms-overflow-style: none;
                    overflow: -moz-scrollbars-none;
                }

                .no-scrollbar::-webkit-scrollbar {
                    display: none;
                }
            </style>
        </div>
        <div data-animation="default" class="n_navbar-25 w-variant-a52fa8b8-c65e-a715-d103-8890e469ceb8 w-nav"
            data-easing2="ease" data-wf--main-nav--variant="announcement" data-easing="ease" data-collapse="medium"
            data-w-id="111ce1d9-5d52-f117-6d9c-068c2f584c20" role="banner" data-no-scroll="1" data-duration="400">
            @if ($main_setting && ($main_setting->show_announcement ?? true))
                <div class="div-block-20 w-variant-a52fa8b8-c65e-a715-d103-8890e469ceb8" style="background-color: {{ $main_setting->getSectionBackgroundColor('announcement', '#f8f9fa') }};">
                    <div class="div-block-21 w-variant-a52fa8b8-c65e-a715-d103-8890e469ceb8">
                        <div class="div-block-22 w-variant-a52fa8b8-c65e-a715-d103-8890e469ceb8">
                            <div class="breaking-news-wr w-variant-a52fa8b8-c65e-a715-d103-8890e469ceb8" style="background: #e63cb8 !important">
                                {{-- <img
                                    src="https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/6873ea75996d3b8bd33a2d13_Rectangle%2066.svg"
                                    loading="lazy" alt=""
                                    class="image-76 w-variant-a52fa8b8-c65e-a715-d103-8890e469ceb8" /> --}}
                                <div class="breaking-text w-variant-a52fa8b8-c65e-a715-d103-8890e469ceb8">
                                    {{ $main_setting->announcement_badge ?? 'GET READY' }}</div>
                            </div>
                            <div class="breaking-follow-tect w-variant-a52fa8b8-c65e-a715-d103-8890e469ceb8"><a
                                    href="{{ $main_setting->announcement_url ?? '/assetclassconference' }}"
                                    class="link-no-underline is-regular w-variant-a52fa8b8-c65e-a715-d103-8890e469ceb8 w-inline-block">
                                    <div>
                                        {{ $main_setting->announcement_text ?? 'Announcing Sports As An Asset Class Summit, October 16th. Learn More' }}
                                    </div><img
                                        src="https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/68750a1f71b4822899d82d1b_Vectorarrw.png"
                                        loading="lazy" alt=""
                                        class="code-embed-11 w-variant-a52fa8b8-c65e-a715-d103-8890e469ceb8" />
                                </a></div>
                        </div><a data-w-id="49705535-c337-0f16-396e-4ea55fe2c828" href="#"
                            class="link-block-8 w-variant-a52fa8b8-c65e-a715-d103-8890e469ceb8 w-inline-block">
                            <div class="code-embed-12 w-variant-a52fa8b8-c65e-a715-d103-8890e469ceb8 w-embed"><svg
                                    width="auto" height="auto" viewBox="0 0 342 342" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M200.867 171L335.267 36.5997C343.8 28.0663 343.8 15.2663 335.267 6.73301C326.733 -1.80033 313.933 -1.80033 305.4 6.73301L171 141.133L36.5999 6.73301C28.0666 -1.80033 15.2666 -1.80033 6.73326 6.73301C-1.80008 15.2663 -1.80008 28.0663 6.73326 36.5997L141.133 171L6.73326 305.4C2.46659 309.666 0.333252 313.933 0.333252 320.333C0.333252 333.133 8.86659 341.666 21.6666 341.666C28.0666 341.666 32.3332 339.533 36.5999 335.266L171 200.866L305.4 335.266C309.667 339.533 313.933 341.666 320.333 341.666C326.733 341.666 331 339.533 335.267 335.266C343.8 326.733 343.8 313.933 335.267 305.4L200.867 171Z"
                                        fill="currentColor" />
                                </svg></div>
                        </a>
                    </div>
            @endif
            <div class="navbar_container n-new w-variant-a52fa8b8-c65e-a715-d103-8890e469ceb8"><a href="/"
                    aria-current="page" class="navbar_logo-link w-nav-brand w--current">
                    @if($main_setting && $main_setting->uploaded_logo)
                        <img src="{{ asset($main_setting->uploaded_logo) }}" alt="Site Logo" class="navbar_logo" style="height: 40px; width: auto;" />
                    @elseif($main_setting && $main_setting->site_logo)
                        <img src="{{ asset($main_setting->site_logo) }}" alt="Site Logo" class="navbar_logo" style="height: 40px; width: auto;" />
                    @else
                        <div class="navbar_logo w-embed"><svg width="auto" height="auto" viewBox="0 0 1345 237"
                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_8230_71)">
                                    <path
                                        d="M869.37 158.33V235.07H848.21V159.61C848.21 133.81 836.86 120.39 816.99 120.39C795.06 120.39 781.64 136.9 781.64 163.73V235.06H760.48V159.6C760.48 133.8 748.87 120.38 728.75 120.38C707.08 120.38 693.92 138.44 693.92 164.76V235.06H672.76V102.6H691.08L693.92 120.66C700.88 111.12 711.98 101.05 732.36 101.05C750.68 101.05 766.42 109.31 773.9 126.08C781.9 111.89 796.09 101.05 819.57 101.05C846.92 101.05 869.36 116.79 869.36 158.33H869.37Z"
                                        fill="white" />
                                    <path
                                        d="M182.43 54.41L121.51 0V105.71L60.78 51.18V181.13L121.76 235.56V180.53L182.43 235.07V54.41Z"
                                        fill="white" />
                                    <path
                                        d="M323.75 54.0098H344.94V234.87H326.59L323.75 213.68C314.96 225.82 300.75 236.42 278.53 236.42C242.1 236.42 215.23 211.87 215.23 168.99C215.23 128.68 242.1 101.55 278.53 101.55C300.75 101.55 315.74 110.6 323.75 123.26V54.0098ZM324 169.5C324 140.56 306.43 120.41 280.6 120.41C254.77 120.41 236.93 140.31 236.93 168.99C236.93 197.67 254.5 217.56 280.6 217.56C306.7 217.56 324 197.66 324 169.5Z"
                                        fill="white" />
                                    <path
                                        d="M357.99 168.99C357.99 128.94 383.31 101.55 420.51 101.55C457.71 101.55 482 125.06 483.04 164.08C483.04 166.92 482.78 170.02 482.52 173.12H380.2V174.93C380.98 199.99 396.74 217.56 421.8 217.56C440.41 217.56 454.87 207.74 459.27 190.69H480.72C475.55 217.05 453.85 236.42 423.36 236.42C383.83 236.42 357.99 209.29 357.99 168.99ZM460.3 155.55C458.23 132.81 442.73 120.15 420.77 120.15C401.39 120.15 383.56 134.1 381.5 155.55H460.31H460.3Z"
                                        fill="white" />
                                    <path
                                        d="M998.02 149.09C998.02 118.34 978.64 101.55 945.06 101.55C913.28 101.55 892.35 116.8 889.25 142.63H910.44C913.02 129.19 925.43 120.41 944.03 120.41C964.7 120.41 976.84 130.75 976.84 147.8V156.84H938.09C903.47 156.84 885.12 171.57 885.12 197.92C885.12 221.95 904.76 236.42 933.69 236.42C955.89 236.42 968.97 226.81 977.27 215.29L980.01 234.57L998.1 234.67L998.03 149.09H998.02ZM976.84 181.13C976.84 203.09 961.6 218.34 935.24 218.34C917.67 218.34 906.56 209.55 906.56 196.64C906.56 181.65 917.15 174.67 936.01 174.67H976.83V181.13H976.84Z"
                                        fill="white" />
                                    <path
                                        d="M607.67 149.09C607.67 118.34 588.29 101.55 554.71 101.55C522.93 101.55 502 116.8 498.9 142.63H520.09C522.67 129.19 535.08 120.41 553.68 120.41C574.35 120.41 586.49 130.75 586.49 147.8V156.84H547.74C513.12 156.84 494.77 171.57 494.77 197.92C494.77 221.95 514.41 236.42 543.34 236.42C565.54 236.42 578.62 226.81 586.92 215.29L589.66 234.57L607.75 234.67L607.68 149.09H607.67ZM586.48 181.13C586.48 203.09 571.24 218.34 544.88 218.34C527.31 218.34 516.2 209.55 516.2 196.64C516.2 181.65 526.79 174.67 545.65 174.67H586.47V181.13H586.48Z"
                                        fill="white" />
                                    <path d="M650.83 54.0098H629.64V234.87H650.83V54.0098Z" fill="white" />
                                    <path
                                        d="M1019.85 54.0098H1041.04V173.12L1107.18 103.1H1133.28L1081.86 157.62L1136.89 234.88H1111.31L1067.65 172.87L1041.04 200.26V234.88H1019.85V54.0098Z"
                                        fill="white" />
                                    <path
                                        d="M1131.83 168.99C1131.83 128.94 1157.15 101.55 1194.35 101.55C1231.55 101.55 1255.84 125.06 1256.88 164.08C1256.88 166.92 1256.62 170.02 1256.36 173.12H1154.04V174.93C1154.82 199.99 1170.58 217.56 1195.64 217.56C1214.25 217.56 1228.71 207.74 1233.11 190.69H1254.56C1249.39 217.05 1227.69 236.42 1197.2 236.42C1157.67 236.42 1131.83 209.29 1131.83 168.99ZM1234.15 155.55C1232.08 132.81 1216.58 120.15 1194.62 120.15C1175.24 120.15 1157.41 134.1 1155.35 155.55H1234.16H1234.15Z"
                                        fill="white" />
                                    <path
                                        d="M1344.03 103.1V123.77H1333.43C1305.78 123.77 1298.29 146.77 1298.29 167.69V234.87H1277.1V103.1H1295.44L1298.29 123C1304.49 112.92 1314.57 103.1 1338.08 103.1H1344.03Z"
                                        fill="white" />
                                    <path d="M60.78 235.01L0 180.8V126.88L60.78 181.12V235.01Z" fill="#8EE8DF" />
                                </g>
                                <defs>
                                    <clipPath id="clip0_8230_71">
                                        <rect width="1344.03" height="236.42" fill="white" />
                                    </clipPath>
                                </defs>
                            </svg></div>
                    @endif
                </a>
                <article role="navigation" class="navbar_menu is-page-height-tablet w-nav-menu">
                 
                    <div class="navbar_menu-buttons"><a
                      style="display: none;"
                            href="{{ $main_setting->signin_url ?? '/login' }}"
                            class="n_button is-ghost w-inline-block">
                            <div class="signin_icon w-embed"><svg width="auto" height="auto" viewBox="0 0 38 38"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M19 0C29.4934 0 38 8.50659 38 19C38 24.7669 35.4304 29.9334 31.374 33.418C31.3393 33.4526 31.3007 33.4832 31.2607 33.5127C27.9515 36.3113 23.6734 38 19 38C8.50659 38 0 29.4934 0 19C0 8.50659 8.50659 0 19 0ZM15 28C13.4949 27.9997 12.0291 28.4848 10.8213 29.3828C9.78697 30.1519 8.98792 31.1887 8.50781 32.376C11.3975 34.6458 15.0405 36 19 36C22.9588 36 26.6008 34.646 29.4902 32.377C29.0109 31.1914 28.2159 30.1546 27.1836 29.3857C25.9749 28.4857 24.5078 27.9997 23.001 28H15ZM19 2C9.61116 2 2 9.61116 2 19C2 23.6763 3.88886 27.9108 6.94434 30.9844C7.5717 29.726 8.48812 28.6249 9.62793 27.7773C11.1809 26.623 13.0649 25.9996 15 26H23C24.9373 25.9995 26.824 26.6242 28.3779 27.7812C29.5151 28.6281 30.4283 29.7282 31.0547 30.9844C34.1104 27.9108 36 23.6765 36 19C36 9.61116 28.3888 2 19 2ZM19 8C22.866 8 26 11.134 26 15C26 18.866 22.866 22 19 22C15.134 22 12 18.866 12 15C12 11.134 15.134 8 19 8ZM19 10C16.2386 10 14 12.2386 14 15C14 17.7614 16.2386 20 19 20C21.7614 20 24 17.7614 24 15C24 12.2386 21.7614 10 19 10Z"
                                        fill="currentColor" />
                                </svg></div>
                            <div>{{ $main_setting->signin_text ?? 'Sign In' }}</div>
                        </a><a 
                            href="{{ $main_setting->main_cta_url ?? '/connect' }}"
                            class="n_button is-small w-inline-block">
                            <div>{{ $main_setting->main_cta_text ?? 'Get Started' }}</div>
                        </a></div>
                </article>
                <div class="navbar_menu-button w-nav-button">
                    <div class="menu-icon1">
                        <div class="menu-icon1_line-top"></div>
                        <div class="menu-icon1_line-middle">
                            <div class="menu-icon_line-middle-inner"></div>
                        </div>
                        <div class="menu-icon1_line-bottom"></div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <div class="code-embed-6 w-embed w-script">
            <script>
                (function() {
                    const navbar = document.querySelector('.n_navbar');
                    if (!navbar) return;

                    const BASE_PADDING = '0.5rem';
                    const SCROLLED_PADDING = '0';
                    const DESKTOP_Y = 500;
                    const MOBILE_Y = 200;
                    const MOBILE_BP = 767;

                    const SCROLLED_BG = 'rgba(30, 58, 76)'; // #1e3a4c with 80% opacity
                    const TRANSPARENT_BG = 'transparent';

                    function applyStyles(scrolled) {
                        navbar.style.backgroundColor = scrolled ? SCROLLED_BG : TRANSPARENT_BG;
                        navbar.style.paddingTop = scrolled ? SCROLLED_PADDING : BASE_PADDING;
                        navbar.style.paddingBottom = scrolled ? SCROLLED_PADDING : BASE_PADDING;
                    }

                    function evaluate() {
                        const y = window.pageYOffset || document.documentElement.scrollTop;
                        const isMobile = window.innerWidth <= MOBILE_BP;
                        const threshold = isMobile ? MOBILE_Y : DESKTOP_Y;
                        applyStyles(y > threshold);
                    }

                    window.addEventListener('scroll', evaluate, {
                        passive: true
                    });
                    window.addEventListener('resize', evaluate);
                    window.addEventListener('load', evaluate);
                })();
            </script>
        </div>