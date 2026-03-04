@php
    $main_setting = \App\Models\DealmakerConfig::getInstance();
@endphp

@if ($main_setting->show_footer ?? true)
        <div dark-bg="1" class="footer" style="background-color: {{ $main_setting ? $main_setting->getSectionBackgroundColor('footer', '#000000') : '#000000' }};">
            <footer dark-bg="1" class="footer_component" style="background-color: {{ $main_setting ? $main_setting->getSectionBackgroundColor('footer', '#000000') : '#000000' }};">
                <div id="footer" class="padding-global">
                    <div class="container-large">
                        <div class="padding-section-large">
                            <div class="w-layout-grid footer3_top-wrapper">
                                <div class="footer3_left-wrapper"><a href="/old-home-4"
                                        class="footer3_logo-link w-nav-brand">
                                        @if($main_setting->uploaded_logo)
                        <img src="{{ asset($main_setting->uploaded_logo) }}" alt="Site Logo" class="navbar_logo" style="height: 40px; width: auto;" />
                    @elseif($main_setting->site_logo)
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
                                        <div class="spacer-medium"></div>
                                    </a>
                                    <div class="spacer-small">
                                        <div class="text-size-small">
                                            {{ $main_setting->footer_company_description ?? 'DealMaker provides comprehensive capital raising technology that transforms how companies raise funds, engage investors, and build community.' }}<br /><br />{{ $main_setting->footer_company_address ?? '30 East 23rd St. Fl. 2 New York, NY 10010' }}
                                        </div>
                                        <div class="spacer-medium"></div>
                                        <div class="w-layout-grid footer3_social-list">
                                            @if($main_setting->show_linkedin && $main_setting->linkedin_url)
                                                <a aria-label="LinkedIn (opens in a new tab)"
                                                    href="{{ $main_setting->linkedin_url }}" target="_blank"
                                                    class="footer3_social-link w-inline-block">
                                                    <div class="icon-embed-xxsmall footer-icon w-embed">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink"
                                                            aria-hidden="true" role="img"
                                                            class="iconify iconify--bx" width="100%"
                                                            height="100%" preserveAspectRatio="xMidYMid meet"
                                                            viewBox="0 0 24 24">
                                                            <circle cx="4.983" cy="5.009" r="2.188"
                                                                fill="currentColor"></circle>
                                                            <path
                                                                d="M9.237 8.855v12.139h3.769v-6.003c0-1.584.298-3.118 2.262-3.118c1.937 0 1.961 1.811 1.961 3.218v5.904H21v-6.657c0-3.27-.704-5.783-4.526-5.783c-1.835 0-3.065 1.007-3.568 1.96h-.051v-1.66H9.237zm-6.142 0H6.87v12.139H3.095z"
                                                                fill="currentColor"></path>
                                                        </svg>
                                                    </div>
                                                </a>
                                            @endif
                                            
                                            @if($main_setting->show_twitter && $main_setting->twitter_url)
                                                <a aria-label="Twitter/X (opens in a new tab)"
                                                    href="{{ $main_setting->twitter_url }}" target="_blank"
                                                    class="footer3_social-link w-inline-block">
                                                    <div class="icon-embed-xxsmall is-small footer-icon w-embed">
                                                        <svg width="auto" height="auto" viewBox="0 0 336 328"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M335.188 327.598H227.629L146.794 209.935L45.6173 327.598H0.945312L126.984 181.084L2.6737 0.401855H110.232L186.68 111.55L282.406 0.401855H327.078L206.623 140.401L335.321 327.598H335.188ZM234.543 314.303H309.927L189.738 139.47L297.961 13.5642H288.389L185.483 133.221L103.186 13.5642H27.8017L143.47 181.882L29.6631 314.17H39.2356L147.725 187.998L234.41 314.17L234.543 314.303ZM298.227 308.187H240.393L39.3686 20.7436H97.2029L298.094 308.187H298.227ZM247.306 294.892H272.7L90.2894 34.0388H64.8955L247.173 294.892H247.306Z"
                                                                fill="currentColor" />
                                                        </svg>
                                                    </div>
                                                </a>
                                            @endif
                                            
                                            @if($main_setting->show_facebook && $main_setting->facebook_url)
                                                <a aria-label="Facebook (opens in a new tab)"
                                                    href="{{ $main_setting->facebook_url }}" target="_blank"
                                                    class="footer3_social-link w-inline-block">
                                                    <div class="icon-embed-xxsmall footer-icon w-embed">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink"
                                                            aria-hidden="true" role="img"
                                                            class="iconify iconify--bx" width="100%"
                                                            height="100%" preserveAspectRatio="xMidYMid meet"
                                                            viewBox="0 0 24 24">
                                                            <path
                                                                d="M13.397 20.997v-8.196h2.765l.411-3.209h-3.176V7.548c0-.926.258-1.56 1.587-1.56h1.684V3.127A22.336 22.336 0 0 0 14.201 3c-2.444 0-4.122 1.492-4.122 4.231v2.355H7.332v3.209h2.753v8.202h3.312z"
                                                                fill="currentColor"></path>
                                                        </svg>
                                                    </div>
                                                </a>
                                            @endif
                                            
                                            @if($main_setting->show_instagram && $main_setting->instagram_url)
                                                <a aria-label="Instagram (opens in a new tab)"
                                                    href="{{ $main_setting->instagram_url }}" target="_blank"
                                                    class="footer3_social-link w-inline-block">
                                                    <div class="icon-embed-xxsmall footer-icon w-embed">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink"
                                                            aria-hidden="true" role="img"
                                                            class="iconify iconify--bx" width="100%"
                                                            height="100%" preserveAspectRatio="xMidYMid meet"
                                                            viewBox="0 0 24 24">
                                                            <path
                                                                d="M11.999 7.377a4.623 4.623 0 1 0 0 9.248a4.623 4.623 0 0 0 0-9.248zm0 7.627a3.004 3.004 0 1 1 0-6.008a3.004 3.004 0 0 1 0 6.008z"
                                                                fill="currentColor"></path>
                                                            <circle cx="16.806" cy="7.207" r="1.078"
                                                                fill="currentColor"></circle>
                                                            <path
                                                                d="M20.533 6.111A4.605 4.605 0 0 0 17.9 3.479a6.606 6.606 0 0 0-2.186-.42c-.963-.042-1.268-.054-3.71-.054s-2.755 0-3.71.054a6.554 6.554 0 0 0-2.184.42a4.6 4.6 0 0 0-2.633 2.632a6.585 6.585 0 0 0-.419 2.186c-.043.962-.056 1.267-.056 3.71c0 2.442 0 2.753.056 3.71c.015.748.156 1.486.419 2.187a4.61 4.61 0 0 0 2.634 2.632a6.584 6.584 0 0 0 2.185.45c.963.042 1.268.055 3.71.055s2.755 0 3.71-.055a6.615 6.615 0 0 0 2.186-.419a4.613 4.613 0 0 0 2.633-2.633c.263-.7.404-1.438.419-2.186c.043-.962.056-1.267.056-3.71s0-2.753-.056-3.71a6.581 6.581 0 0 0-.421-2.217zm-1.218 9.532a5.043 5.043 0 0 1-.311 1.688a2.987 2.987 0 0 1-1.712 1.711a4.985 4.985 0 0 1-1.67.311c-.95.044-1.218.055-3.654.055c-2.438 0-2.687 0-3.655-.055a4.96 4.96 0 0 1-1.669-.311a2.985 2.985 0 0 1-1.719-1.711a5.08 5.08 0 0 1-.311-1.669c-.043-.95-.053-1.218-.053-3.654c0-2.437 0-2.686.053-3.655a5.038 5.038 0 0 1 .311-1.687c.305-.789.93-1.41 1.719-1.712a5.01 5.01 0 0 1 1.669-.311c.951-.043 1.218-.055 3.655-.055s2.687 0 3.654.055a4.96 4.96 0 0 1 1.67.311a2.991 2.991 0 0 1 1.712 1.712a5.08 5.08 0 0 1 .311 1.669c.043.951.054 1.218.054 3.655c0 2.436 0 2.698-.043 3.654h-.011z"
                                                                fill="currentColor"></path>
                                                        </svg>
                                                    </div>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="spacer-medium"></div><img width="205" loading="lazy"
                                        alt="Award"
                                        src="{{ $main_setting->footer_award_image ?? 'https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/65784614f244b62e543d68de_Deloitte%20Companies%20to%20watch%20award%20(Facebook%20Cover)%20(4)%201.png' }}" />
                                </div>
                                <div class="footer3_right-wrapper">
                                    <div class="w-layout-grid footer3_menu-wrapper">
                                        {{-- Dynamic footer menu columns --}}
                                        @if ($main_setting->footer_menu_columns && count($main_setting->footer_menu_columns) > 0)
                                            @foreach ($main_setting->footer_menu_columns as $column)
                                                <div class="footer3_link-list">
                                                    <div class="text-size-medium capitalize text-color-gray">
                                                        {{ strtoupper($column['title'] ?? 'MENU') }}
                                                    </div>
                                                    <div class="line-sepearation is-small"></div>
                                                    <div class="spacer-small"></div>
                                                    @if (isset($column['links']) && count($column['links']) > 0)
                                                        @foreach ($column['links'] as $link)
                                                            <a href="{{ $link['url'] ?? '#' }}"
                                                                class="footer3_link">{{ $link['title'] ?? 'Link' }}</a>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            @endforeach
                                        @else
                                            {{-- Fallback to existing static footer menu columns when none configured --}}
                                            @if ($main_setting->footer_menu_raise_capital && count($main_setting->footer_menu_raise_capital) > 0)
                                                <div class="footer3_link-list">
                                                    <div class="text-size-medium capitalize text-color-gray">RAISE CAPITAL
                                                    </div>
                                                    <div class="line-sepearation is-small"></div>
                                                    <div class="spacer-small"></div>
                                                    @foreach ($main_setting->footer_menu_raise_capital as $link)
                                                        <a href="{{ $link['url'] ?? '#' }}"
                                                            class="footer3_link">{{ $link['title'] ?? 'Link' }}</a>
                                                    @endforeach
                                                </div>
                                            @endif

                                            @if ($main_setting->footer_menu_solutions && count($main_setting->footer_menu_solutions) > 0)
                                                <div class="footer3_link-list">
                                                    <div class="text-size-medium capitalize text-color-gray">OUR SOLUTIONS
                                                    </div>
                                                    <div class="line-sepearation is-small"></div>
                                                    <div class="spacer-small"></div>
                                                    @foreach ($main_setting->footer_menu_solutions as $link)
                                                        <a href="{{ $link['url'] ?? '#' }}"
                                                            class="footer3_link">{{ $link['title'] ?? 'Link' }}</a>
                                                    @endforeach
                                                </div>
                                            @endif

                                            @if ($main_setting->footer_menu_company && count($main_setting->footer_menu_company) > 0)
                                                <div class="footer3_link-list">
                                                    <div class="text-size-medium capitalize text-color-gray">COMPANY</div>
                                                    <div class="line-sepearation is-small"></div>
                                                    <div class="spacer-small"></div>
                                                    @foreach ($main_setting->footer_menu_company as $link)
                                                        <a href="{{ $link['url'] ?? '#' }}"
                                                            class="footer3_link">{{ $link['title'] ?? 'Link' }}</a>
                                                    @endforeach
                                                </div>
                                            @endif

                                            @if ($main_setting->footer_menu_resources && count($main_setting->footer_menu_resources) > 0)
                                                <div class="footer3_link-list">
                                                    <div class="text-size-medium capitalize text-color-gray">RESOURCES
                                                    </div>
                                                    <div class="line-sepearation is-small"></div>
                                                    <div class="spacer-small"></div>
                                                    @foreach ($main_setting->footer_menu_resources as $link)
                                                        <a href="{{ $link['url'] ?? '#' }}"
                                                            class="footer3_link">{{ $link['title'] ?? 'Link' }}</a>
                                                    @endforeach
                                                </div>
                                            @endif
                                        @endif

                                        <div class="footer3_link-list">
                                            <div class="text-size-medium capitalize text-color-gray">STAY UPDATED
                                            </div>
                                            <div class="line-sepearation is-small"></div>
                                            <div class="spacer-small"></div>
                                            <div class="text-size-small">
                                                {{ $main_setting->footer_newsletter_description ?? 'Subscribe to our newsletter for the latest updates and insights on capital raising.' }}
                                            </div>
                                            <div class="spacer-medium"></div>
                                            <div class="w-embed w-script">
                                                <script charset="utf-8" type="text/javascript" src="//js.hsforms.net/forms/embed/v2.js"></script>
                                                <script>
                                                    hbspt.forms.create({
                                                        portalId: "7493765",
                                                        formId: "d2e71af5-bdca-4d2e-8f73-f00a0ff6a539",
                                                        region: "na1",
                                                        onFormSubmit: function($form) {
                                                            // Make sure any thank you message has white text
                                                            setTimeout(function() {
                                                                var thankYouMessage = document.querySelector(".submitted-message span");
                                                                if (thankYouMessage) {
                                                                    thankYouMessage.style.color = "#ffffff";
                                                                }
                                                            }, 100);
                                                        }
                                                    });
                                                </script>
                                                <script>
                                                    document.addEventListener("DOMContentLoaded", function() {
                                                        setTimeout(() => {
                                                            let emailField = document.querySelector(".hs-input");
                                                            if (emailField && !emailField.placeholder) {
                                                                emailField.placeholder = "Your email address";
                                                            }
                                                        }, 2000); // Delay to ensure HubSpot form is loaded
                                                    });
                                                </script>
                                                <style>
                                                    .hs-input {
                                                        border-radius: 5px;
                                                        font-size: 14px;
                                                        background-color: #ededed;
                                                        border: none;
                                                        padding: 10px;
                                                        color: black !important;
                                                        /* Make input text white */
                                                    }

                                                    .hs-richtext p {
                                                        font-size: 12px;
                                                        line-height: 1em;
                                                        color: white !important;
                                                        /* Ensure rich text is white */
                                                    }

                                                    .hs_email .input {
                                                        background-color: {{ $main_setting ? $main_setting->getSectionBackgroundColor('footer', '#000000') : '#000000' }};
                                                        border: 0 solid #ededed;
                                                        border-bottom: 0;
                                                        height: auto !important;
                                                        color: white !important;
                                                        /* Override the previous gray color */
                                                    }

                                                    .legal-consent-container {
                                                        display: none !important;
                                                    }

                                                    .hs_email label {
                                                        margin-bottom: .25rem;
                                                        font-weight: 500;
                                                        color: white !important;
                                                        /* Ensure labels are white */
                                                    }

                                                    .hs_email ul {
                                                        background-color: {{ $main_setting ? $main_setting->getSectionBackgroundColor('footer', '#000000') : '#000000' }};
                                                        font-size: 14px;
                                                        border-radius: 10px;
                                                        color: white !important;
                                                        /* Ensure list text is white */
                                                    }

                                                    /* Add margin to the email field container */
                                                    .hs_email {
                                                        margin-bottom: 15px !important;
                                                    }

                                                    /* Add margin to submit button container */
                                                    .hs-submit {
                                                        margin-top: 15px !important;
                                                    }

                                                    .hs-button {
                                                        border-radius: 5px;
                                                        white-space: pre-wrap;
                                                        width: 100%;
                                                        padding: 8px;
                                                        color: {{ $main_setting->button_text_color ?? '#ffffff' }} !important;
                                                        border: 0;
                                                        background-color: {{ $main_setting->button_primary_color ?? '#f31cb6' }} !important;
                                                    }

                                                    /* Fix for the thank you message */
                                                    .submitted-message,
                                                    .submitted-message p,
                                                    .submitted-message span {
                                                        color: white !important;
                                                        /* Force all thank you message text to be white */
                                                    }
                                                </style>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
            <div class="footer-lower" style="background-color: {{ $main_setting ? $main_setting->getSectionBackgroundColor('footer', '#000000') : '#000000' }};">
                <div class="padding-global">
                    <div class="container-large">
                        <div class="padding-section-xsmall">
                            <div class="footer-lower_grid">
                                {{-- <div class="w-layout-hflex flex-block-18"><a href="{{ $main_setting->footer_terms_url ?? '/terms' }}"
                                        class="footer-lower_link">Terms of Service</a><a href="{{ $main_setting->footer_privacy_url ?? '/privacy' }}"
                                        class="footer-lower_link">Privacy Policy</a><a href="{{ $main_setting->footer_cookies_url ?? '/cookies' }}"
                                        class="footer-lower_link">Cookies</a><a href="{{ $main_setting->footer_security_url ?? '/security' }}"
                                        class="footer-lower_link">Security</a><a href="{{ $main_setting->footer_accessibility_url ?? '/accessibility' }}"
                                        class="footer-lower_link">Accessibility</a></div> --}}
                                <div>{{ $main_setting->footer_copyright_text ?? '© 2025 DealMaker. All rights reserved.' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    @endif
    <script src="https://d3e54v103j8qbb.cloudfront.net/js/jquery-3.5.1.min.dc5e7f18c8.js?site=656f55af4b70f4ce7ae4b997"
        type="text/javascript" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous">
    </script>
    <script
        src="https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/js/dealmaker-2-0-staging.schunk.36b8fb49256177c8.js"
        type="text/javascript"></script>
    <script
        src="https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/js/dealmaker-2-0-staging.schunk.5a589c4c56aacf4c.js"
        type="text/javascript"></script>
    <script
        src="https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/js/dealmaker-2-0-staging.5513f45d.5230b3752ee77221.js"
        type="text/javascript"></script><!-- Tracks custom events on click -->
    <script>
        [...document.querySelectorAll('[dmr-track]')].forEach(el => {
            el.addEventListener('click', (e) => {
                const event = el.getAttribute('dmr-track');
                if (!event) {
                    console.error('Event value missing in:', el);
                    e.stopPropagation();
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    return false;
                }
                window.dataLayer.push({
                    event
                });
            })
        });
    </script>

    <script>
        backgroundColorCheck()

        function backgroundColorCheck() {
            var lightBg = $('[light-bg]');
            var detectedOverlapCount = 0;
            lightBg.each(function(i, el) {
                var lightBgRect = el.getBoundingClientRect();


                if (lightBgRect.top < window.innerHeight / 2 && lightBgRect.bottom > window.innerHeight / 2) {
                    detectedOverlapCount += 1;
                }
            });
            if (detectedOverlapCount) {
                $('.black-background-color').addClass('on-light')
                $('.side-link').addClass('on-light')
            } else {
                $('.black-background-color').removeClass('on-light')
                $('.side-link').removeClass('on-light')
            }
        }

        window.onscroll = function() {
            backgroundColorCheck()
        };
    </script>

    <style>
        .cta_component {
            backdrop-filter: blur(40px);
        }
        
        /* Apply primary button color to all non-transparent buttons */
        .n_button:not(.is-ghost),
        .hero-cta-button,
        .w-button {
            background-color: {{ $main_setting->button_primary_color ?? '#f31cb6' }} !important;
            border-color: {{ $main_setting->button_primary_color ?? '#f31cb6' }} !important;
            color: {{ $main_setting->button_text_color ?? '#ffffff' }} !important;
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
            background-color: {{ $main_setting->button_hover_color ?? '#d1179a' }} !important;
            border-color: {{ $main_setting->button_hover_color ?? '#d1179a' }} !important;
            color: {{ $main_setting->button_text_color ?? '#ffffff' }} !important;
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
    </style><!-- [Attributes by Finsweet] Number Count -->
    <script defer src="https://cdn.jsdelivr.net/npm/@finsweet/attributes-numbercount@1/numbercount.js"></script>



    <!-- Magic Video for Vimeo -->

    <script>
        const sizeSelector = window.innerWidth <= 479 ? 'mobile' : 'desktop';

        [...document.querySelectorAll('[magic-video="true"]')].forEach(videoWrapper => {
            const videoModal = videoWrapper.querySelector('#videoModal');
            const playBackIcon = videoWrapper.querySelector('[magic-video="play"]');
            const vimeoIframe = videoWrapper.querySelector(`#vimeo-${sizeSelector} iframe`);
            const vimeoPlayer = new Vimeo.Player(vimeoIframe);

            if (!videoModal) {
                console.warn('Video modal not found.');
            }

            if (!playBackIcon) {
                console.error('No playback icon found.');
                return;
            }

            if (!vimeoIframe) {
                console.error('No embedded Vimeo player fouind.');
                return;
            }

            playBackIcon.addEventListener('click', () => {
                if (videoModal) {
                    videoModal.classList.remove('hidden');
                    vimeoPlayer.play();
                }
            });

            const videoModalClose = document.querySelectorAll('.bg-closed');
            if (!videoModalClose) {
                console.warn('Video modal close element not found.')
            }
            videoModalClose && videoModalClose.forEach((item) => {
                item.addEventListener('click', () => {
                    vimeoPlayer.pause();
                    vimeoPlayer.setCurrentTime(0);
                    videoModal.classList.add('hidden');
                });
            });
        })
    </script>

    <!-- ✅ Load Swiper JS (Must be before your script) -->
    <script src="{{ asset('js/swiper-bundle.min.js') }}"></script>


    <script>
        function parseBoolean(string) {
            return string === "true";
        }

        let swiperContainers = document.querySelectorAll(".swiper_container");
        let swiperIsOn = false;
        let swipers = [];

        mobileSwiperInit(5000); // Initial execution

        function mobileSwiperInit(res) {
            if (window.innerWidth < res && !swiperIsOn) {
                swiperIsOn = true;

                swiperContainers.forEach(function(el) {
                    // ✅ Ensure the Swiper wrapper .swiper_wrapper exists
                    let swiperWrapper = el.querySelector(".swiper-wrapper");
                    if (!swiperWrapper) {
                        console.warn("❌ Missing .swiper-wrapper inside:", el);
                        return;
                    }

                    // ✅ Ensure Navigation & Pagination Elements Exist
                    let nextButton = el.querySelector(".swiper_btn_next");
                    let prevButton = el.querySelector(".swiper_btn_prev");
                    let pagination = el.querySelector(".swiper_pagination");

                    // ✅ Get attributes from Webflow
                    let swpEnabled = parseBoolean(el.getAttribute("swp-enabled")) ?? true;
                    let swpMEnabled = parseBoolean(el.getAttribute("swp-m-enabled")) ?? true;
                    let swpSEnabled = parseBoolean(el.getAttribute("swp-s-enabled")) ?? true;

                    let swpSlideCount = parseFloat(el.getAttribute("swp-slide-count")) || 1;
                    let swpMSlideCount = parseFloat(el.getAttribute("swp-m-slide-count")) || 1;
                    let swpSSlideCount = parseFloat(el.getAttribute("swp-s-slide-count")) || 1;

                    let swpSpace = parseFloat(el.getAttribute("swp-space")) || 0;
                    let swpMSpace = parseFloat(el.getAttribute("swp-m-space")) || 0;
                    let swpSSpace = parseFloat(el.getAttribute("swp-s-space")) || 0;

                    let swpCentered = parseBoolean(el.getAttribute("swp-centered")) ?? false;
                    let swpMCentered = parseBoolean(el.getAttribute("swp-m-centered")) ?? false;
                    let swpSCentered = parseBoolean(el.getAttribute("swp-s-centered")) ?? false;

                    let swpLoop = parseBoolean(el.getAttribute("swp-loop")) ?? false;
                    let swpMLoop = parseBoolean(el.getAttribute("swp-m-loop")) ?? false;
                    let swpSLoop = parseBoolean(el.getAttribute("swp-s-loop")) ?? false;

                    let swpAutoplay = parseBoolean(el.getAttribute("swp-autoplay")) ?? false;
                    let swpMAutoplay = parseBoolean(el.getAttribute("swp-m-autoplay")) ?? false;
                    let swpSAutoplay = parseBoolean(el.getAttribute("swp-s-autoplay")) ?? false;

                    console.log("✅ Swiper Enabled:", swpEnabled);

                    // ✅ Collect labels from data-label attributes
                    let slides = Array.from(el.querySelectorAll(".swiper-slide"));
                    let labels = slides.map((slide, index) =>
                        slide.getAttribute("data-label") || `Slide ${index + 1}`
                    );

                    let swiper = new Swiper(el, {
                        wrapperClass: "swiper-wrapper", // ✅ Uses .swiper_wrapper instead of default .swiper-wrapper
                        slideClass: "swiper-slide", // ✅ Ensures .swiper-slide is recognized

                        slidesPerView: swpSlideCount,
                        spaceBetween: swpSpace,
                        centeredSlides: swpCentered,
                        loop: swpLoop,

                        mousewheel: {
                            forceToAxis: true
                        },
                        keyboard: {
                            enabled: true,
                            onlyInViewport: true
                        },

                        pagination: pagination ? {
                            el: pagination,
                            clickable: true,
                            type: "bullets",

                        } : false,

                        navigation: nextButton && prevButton ? {
                            nextEl: nextButton,
                            prevEl: prevButton,
                            disabledClass: "nn",
                            lockClass: "nns",
                        } : false,

                        enabled: swpEnabled,

                        breakpoints: {
                            320: {
                                slidesPerView: swpSSlideCount,
                                spaceBetween: swpSSpace,
                                centeredSlides: swpSCentered,
                                loop: swpSLoop,
                                autoplay: swpSAutoplay,
                                enabled: swpSEnabled,
                                navigation: nextButton && prevButton ? {
                                    enabled: true,
                                    nextEl: nextButton,
                                    prevEl: prevButton,
                                } : false,
                            },
                            767: {
                                slidesPerView: swpMSlideCount,
                                spaceBetween: swpMSpace,
                                centeredSlides: swpMCentered,
                                loop: swpMLoop,
                                enabled: swpMEnabled,
                            },
                            991: {
                                slidesPerView: swpSlideCount,
                                spaceBetween: swpSpace,
                                centeredSlides: swpCentered,
                                loop: swpLoop,
                                enabled: swpEnabled,
                            },
                        },
                    });

                    swipers.push(swiper);
                });

            } else if (swiperIsOn && window.innerWidth > 4567 && swipers.length) {
                swipers.forEach(function(el) {
                    console.log("✅ Destroying Swiper:", el);
                    el.destroy(true, true);
                });

                swiperContainers.forEach((el) => {
                    el.querySelectorAll(".swiper-wrapper, .swiper-slide").forEach((slide) => {
                        slide.style = ""; // ✅ Clears inline styles
                    });
                });

                swipers = [];
                swiperIsOn = false;
                console.log("✅ Swiper on:", swiperIsOn);
            }
        }

        window.addEventListener("resize", function() {
            mobileSwiperInit(4567);
        });
    </script>

    @if ($main_setting->custom_js)
        <script>
            {!! $main_setting->custom_js !!}
        </script>
    @endif

</body>

</html>
