{{-- <link rel="stylesheet" href="{{ asset('css/new-footer.css') }}"> --}}

<style>
/* Font Face Definitions - These affect the entire document but are necessary */
@font-face {
    font-family: Acumin Rpro;
    src: url(https://cdn.prod.website-files.com/615c7704bf83fe0f0bb27c0b/68423da0651204d978723e70_Acumin-RPro.woff2) format("woff2");
    font-weight: 400;
    font-style: normal;
    font-display: swap;
}

@font-face {
    font-family: "Acumin Pro Condensed 2";
    src: url(https://cdn.prod.website-files.com/615c7704bf83fe0f0bb27c0b/68423da09c81e7f0cf58c689_Acumin-Pro-Condensed-Bold-2.woff2) format("woff2");
    font-weight: 700;
    font-style: normal;
    font-display: swap;
}

@font-face {
    font-family: Acumin Bdpro;
    src: url(https://cdn.prod.website-files.com/615c7704bf83fe0f0bb27c0b/68423da08f7ad33947af4c5d_Acumin-BdPro.woff2) format("woff2");
    font-weight: 400;
    font-style: normal;
    font-display: swap;
}

/* CSS Variables - Essential for Footer */
:root {
    --reach-template-2024---base-color-neutrals--white: white;
    --reach-template-2024---max-width--main: 80rem;
    --reach-template-2024---padding-vertical--main: var(--reach-template-2024---size--5rem);
    --reach-template-2024---padding-horizontal--main: var(--reach-template-2024---size--2rem);
    --reach-template-2024---padding-vertical--small: var(--reach-template-2024---size--3rem);
    --reach-template-2024---padding-vertical--xsmall: var(--reach-template-2024---size--2rem);
    --reach-template-2024---padding-horizontal--small: var(--reach-template-2024---size--1-5rem);
    --reach-template-2024---grid-gap--main: var(--reach-template-2024---size--1-5rem);
    --reach-template-2024---brand-color--white: var(--reach-template-2024---base-color-neutrals--neutral-200);
    --reach-template-2024---brand-color--black: var(--reach-template-2024---base-color-neutrals--neutral-900);
    --reach-template-2024---base-color-neutrals--neutral-300: #ccc;
    --reach-template-2024---border-radius--small: var(--reach-template-2024---size--0-5rem);
    --reach-template-2024---brand-color--brand: #8ee8df;
    --reach-template-2024---base-color-neutrals--neutral-600: #444;
    
    --_theme---background: var(--brand-color--white);
    --_theme---text: var(--brand-color--black);
    --_theme---action: var(--brand-color--action-color);
    --_theme---border: var(--base-color-neutrals--neutral-400);
    --_theme---background-2: var(--base-color-neutrals--neutral-800);
    --_theme---action-dark: var(--brand-color--action-color-dark);
    --_theme---text-2: var(--base-color-neutrals--neutral-600);
    --_theme---border-size: 2px;
    --_theme---glow-color: #00d4fe;
    
    --max-width--main: 80rem;
    --padding-vertical--large: var(--size--8rem);
    --padding-horizontal--main: var(--size--2rem);
    --padding-vertical--small: var(--size--3rem);
    --padding-vertical--xsmall: var(--size--2rem);
    --padding-horizontal--small: var(--size--1-5rem);
    --border-radius--xsmall: var(--size--0-25rem);
    --brand-color--teal: #479ea4;
    --brand-color--green: #00e09d;
    --brand-color--action-color: var(--brand-color--teal);
    --brand-color--action-color-dark: #008189;
    --brand-color--black: var(--base-color-neutrals--black);
    --brand-color--white: var(--base-color-neutrals--white);
    
    --base-color-neutrals--transparent: #fff0;
    --base-color-neutrals--white: white;
    --base-color-neutrals--neutral-200: #eee;
    --base-color-neutrals--neutral-300: #ccc;
    --base-color-neutrals--neutral-400: #aaa;
    --base-color-neutrals--neutral-500: #989898;
    --base-color-neutrals--neutral-600: #424242;
    --base-color-neutrals--neutral-700: #323232;
    --base-color-neutrals--neutral-800: #212121;
    --base-color-neutrals--neutral-900: #121212;
    --base-color-neutrals--black: black;
    
    --reach-template-2024---base-color-neutrals--black: black;
    --reach-template-2024---base-color-neutrals--neutral-200: #eee;
    --reach-template-2024---base-color-neutrals--neutral-400: #aaa;
    --reach-template-2024---base-color-neutrals--neutral-500: #666;
    --reach-template-2024---base-color-neutrals--neutral-700: #222;
    --reach-template-2024---base-color-neutrals--neutral-800: #111;
    --reach-template-2024---base-color-neutrals--neutral-900: #0a0a0a;
    
    --reach-template-2024---size--0rem: 0rem;
    --reach-template-2024---size--0-125rem: .125rem;
    --reach-template-2024---size--0-25rem: .25rem;
    --reach-template-2024---size--0-5rem: .5rem;
    --reach-template-2024---size--0-75rem: .75rem;
    --reach-template-2024---size--1rem: 1rem;
    --reach-template-2024---size--1-25rem: 1.25rem;
    --reach-template-2024---size--1-5rem: 1.5rem;
    --reach-template-2024---size--2rem: 2rem;
    --reach-template-2024---size--2-5rem: 2.5rem;
    --reach-template-2024---size--3rem: 3rem;
    --reach-template-2024---size--3-5rem: 3.5rem;
    --reach-template-2024---size--4rem: 4rem;
    --reach-template-2024---size--4-5rem: 4.5rem;
    --reach-template-2024---size--5rem: 5rem;
    --reach-template-2024---size--5-5rem: 5.5rem;
    --reach-template-2024---size--6rem: 6rem;
    --reach-template-2024---size--6-5rem: 6.5rem;
    --reach-template-2024---size--7rem: 7rem;
    --reach-template-2024---size--7-5rem: 7.5rem;
    --reach-template-2024---size--8rem: 8rem;
    --reach-template-2024---size--8-5rem: 8.5rem;
    --reach-template-2024---size--9rem: 9rem;
    --reach-template-2024---size--9-5rem: 9.5rem;
    --reach-template-2024---size--10rem: 10rem;
    
    --size--0rem: 0rem;
    --size--0-125rem: .125rem;
    --size--0-25rem: .25rem;
    --size--0-5rem: .5rem;
    --size--0-75rem: .75rem;
    --size--1rem: 1rem;
    --size--1-25rem: 1.25rem;
    --size--1-5rem: 1.5rem;
    --size--2rem: 2rem;
    --size--2-5rem: 2.5rem;
    --size--3rem: 3rem;
    --size--3-5rem: 3.5rem;
    --size--4rem: 4rem;
    --size--4-5rem: 4.5rem;
    --size--5rem: 5rem;
    --size--5-5rem: 5.5rem;
    --size--6rem: 6rem;
    --size--6-5rem: 6.5rem;
    --size--7rem: 7rem;
    --size--8rem: 8rem;
    --size--9rem: 9rem;
    --size--10rem: 10rem;
    
    /* Navbar Height Variables - Default fallbacks for footer independence */
    --navbar-total-height: 6rem;
    --navbar-total-height-mobile: 9.5rem;
    --navbar-total-height-small: 1.7rem;
}

/* Footer Component Styles */
.footer_component {
    margin-top: var(--_theme---border-size);
    background-color: var(--_theme---background);
    color: var(--_theme---text);
    overflow-wrap: anywhere;
    --_theme---background: var(--brand-color--black);
    --_theme---text: var(--brand-color--white);
    --_theme---action: var(--brand-color--action-color);
    --_theme---border: var(--base-color-neutrals--neutral-700);
    --_theme---background-2: var(--base-color-neutrals--neutral-800);
    --_theme---action-dark: var(--brand-color--action-color-dark);
    --_theme---text-2: var(--base-color-neutrals--neutral-500);
    --_theme---border-size: 2px;
    --_theme---glow-color: #00d4fe;
    position: relative;
}

.footer_component.no-border {
    margin-top: 0;
}

.footer_top_wrapper {
    grid-column-gap: 5rem;
    grid-row-gap: 5rem;
    grid-template-rows: auto;
    grid-template-columns: .3fr .5fr .25fr;
    grid-auto-columns: 1fr;
    justify-content: space-between;
    place-items: start stretch;
    margin-bottom: 2rem;
    display: flex;
}

.footer_logo_link {
    justify-content: flex-start;
    align-items: center;
    width: 8.5rem;
    height: 2.5rem;
    padding-left: 0;
}

.footer_logo {
    object-fit: contain;
    object-position: 0% 50%;
    width: 100%;
    max-width: none;
}

.footer_link_list {
    grid-column-gap: .75rem;
    grid-row-gap: 0rem;
    white-space: normal;
    grid-template-rows: auto;
    grid-template-columns: max-content;
    grid-auto-columns: max-content;
    grid-auto-flow: column;
    justify-content: flex-start;
    place-items: center start;
    margin-top: 0;
    margin-bottom: 0;
    padding-left: 0;
    list-style-type: none;
    display: flex;
}

.footer_social_link {
    color: var(--_theme---text);
    align-items: center;
    display: flex;
}

.social-icon {
    width: 1.5rem;
    height: 1.5rem;
}

.disclaimer_wrap {
    grid-column-gap: 1rem;
    grid-row-gap: 1rem;
    flex-flow: column;
    padding-top: 1rem;
    padding-bottom: 1rem;
    display: flex;
}

.disclaimer_wrap.text-size-tiny.text-color-secondary {
    color: var(--_theme---text-2);
}

.footer_line_divider {
    background-color: var(--reach-template-2024---brand-color--white, #eee);
    opacity: .16;
    width: 100%;
    height: 1px;
    margin-top: 1rem;
    margin-bottom: 1rem;
}

.footer_bottom_wrapper {
    grid-column-gap: var(--reach-template-2024---grid-gap--main, 1.5rem);
    grid-row-gap: var(--reach-template-2024---grid-gap--main, 1.5rem);
    white-space: normal;
    grid-template-rows: auto;
    grid-template-columns: max-content;
    grid-auto-columns: max-content;
    grid-auto-flow: column;
    justify-content: space-between;
    align-items: flex-end;
    display: flex;
}

.footer_credit_text {
    color: var(--_theme---text-2);
    font-size: .875rem;
}

/* Reset all elements to allow inline styles to work */
.footer_credit_text * {
    color: unset !important;
    font-size: unset !important;
    margin: 0;
    padding: 0;
}

/* Apply default styling only to unstyled elements */
.footer_credit_text p:not([style]),
.footer_credit_text div:not([style]),
.footer_credit_text span:not([style]),
.footer_credit_text strong:not([style]),
.footer_credit_text em:not([style]) {
    color: inherit;
}

.footer_legal_link {
    color: var(--_theme---action);
    font-size: .875rem;
    text-decoration: underline;
}

.footer_link_item {
    margin-top: 0;
    margin-bottom: 0;
    padding-left: 0;
}

.footer_content_wrap {
    grid-column-gap: .5rem;
    grid-row-gap: .5rem;
    flex-flow: column;
    display: flex;
}

.footer_content_wrap._1 {
    width: 19rem;
}

.footer_content_wrap._2 {
    margin-left: auto;
}

.footer_content_heading {
    margin-bottom: .5rem;
}

.footer_bg_image_wrap {
    z-index: 0;
    width: 100%;
    position: absolute;
    inset: 0%;
    overflow: clip;
}

.footer_bg_overlay {
    z-index: 1;
    background-color: var(--_theme---background);
    opacity: .64;
    object-fit: cover;
    width: 100%;
    height: 100%;
    position: absolute;
    inset: 0%;
}

.footer_bg_image_desktop {
    z-index: 0;
    aspect-ratio: 2.39;
    object-fit: cover;
    object-position: 50% 0%;
    width: 100%;
    max-width: none;
    height: 100%;
    position: absolute;
    inset: 0%;
}

.footer_bg_image_mobile {
    z-index: 0;
    aspect-ratio: 2.39;
    object-fit: unset;
    object-position: 50% 0%;
    visibility: hidden;
    width: 100%;
    max-width: none;
    height: 100%;
    display: none;
    position: absolute;
    inset: 0%;
}

/* Supporting Classes */
.text-style-eyebrow {
    text-transform: uppercase;
    font-family: "Acumin Pro Condensed 2", Arial, sans-serif !important;
    font-size: 1.25rem;
    font-weight: 700;
    line-height: 1;
}

.link_wrap {
    grid-column-gap: .25rem;
    color: var(--_theme---action);
    text-transform: uppercase;
    white-space: nowrap;
    flex-flow: row;
    flex: 0 auto;
    justify-content: flex-start;
    align-items: center;
    font-family: Acumin Bdpro, Arial, sans-serif !important;
    font-size: .875rem;
    font-weight: 400;
    line-height: 1;
    display: flex;
}

.link_wrap:hover {
    text-decoration: underline;
}

.link_wrap.not-allcaps {
    text-transform: none;
}

.link_icon {
    flex: none;
}

.link_icon.icon-embed-xxsmall {
    margin-top: -.3rem;
}

.icon-embed-xxsmall {
    flex-direction: column;
    justify-content: center;
    align-items: center;
    width: 1rem;
    height: 1rem;
    display: flex;
}

.spacer-small {
    width: 100%;
    padding-top: 1.5rem;
}

.powered_by_dm_wrap {
    color: #ccc;
    display: none;
    background-color: #1a1a1a;
    flex-flow: column;
    justify-content: center;
    align-items: center;
    padding-top: 1rem;
    padding-bottom: 1rem;
    display: flex;
}

.powered_by_dm_link {
    aspect-ratio: 3.11;
    width: 12rem;
    position: relative;
}

.powered_by_dm_logo {
    width: 100%;
    max-width: none;
    height: 100%;
    position: absolute;
    inset: 0%;
    overflow: clip;
}

.sources_wrap {
    padding-top: 1rem;
    padding-bottom: 1rem;
}

.sources_text {
    font-size: inherit;
    line-height: inherit;
}

.regcf,
.reservation_regcf,
.reservation_rega,
.reservation_regd,
.reservation_non-specific {
    font-size: inherit;
    line-height: inherit;
    margin-top: 1rem;
}

.rega {
    font-size: inherit;
    line-height: inherit;
}

.forward_statements {
    font-size: inherit;
    line-height: inherit;
    margin-top: 1rem;
}

.u-container {
    max-width: var(--max-width--main, 80rem);
    padding: var(--padding-vertical--large, 8rem) var(--padding-horizontal--main, 2rem);
    width: 100%;
    margin-left: auto;
    margin-right: auto;
}

.z-index-1 {
    z-index: 1;
    position: relative;
}

.w-inline-block {
    max-width: 100%;
    display: inline-block;
}

.w-nav-brand {
    float: left;
    color: #333;
    text-decoration: none;
    position: relative;
}

.w-richtext:before,
.w-richtext:after {
    content: " ";
    grid-area: 1/1/2/2;
    display: table;
}

.w-richtext:after {
    clear: both;
}

.w-richtext ol,
.w-richtext ul {
    margin-bottom: 0;
}

/* Basic Element Styles */
img {
    vertical-align: middle;
    max-width: 100%;
    display: inline-block;
}

/* Text Utility Classes */
.text-size-tiny {
    font-size: .75rem;
}

.text-color-secondary {
    color: var(--_theme---text-2);
}

.rich_content {
    text-align: left;
    font-size: 1.125rem;
}

.mobile-show{
        display: none !important;
    }

/* Mobile Responsive Styles */
@media screen and (max-width: 991px) {
    .footer_bg_image_mobile {
        visibility: visible;
        display: inline-block;
    }
    
    .footer_bg_image_desktop {
        visibility: hidden;
        display: none;
    }
    
    .footer_top_wrapper {
        grid-template-columns: 1fr;
        grid-row-gap: 2rem;
        text-align: start;
    }

    .mobile-hide{
        display: none !important;
    }

    .mobile-show{
        display: block !important;
        margin-top: 2rem !important
    }
    
    .footer_content_wrap._1 {
        width: 100%;
    }
    
    .footer_content_wrap._2 {
        margin-left: 0;
    }
}

@media screen and (max-width: 767px) {
    .footer_link_list {
        grid-auto-flow: row;
        grid-template-columns: 1fr;
        text-align: start;
        margin-left: 0px !important;
    }
    
    .footer_bottom_wrapper {
        flex-direction: column;
        align-items: start;
        grid-row-gap: 1rem;
    }

    .footer_bg_image_wrap{
        display: none !important;
    }
}
</style>

@if($footer)
<style>
    .footer_component {
        @if(($footer->background_type ?? 'color') == 'color')
        background-color: {{ $footer->background ?? '#000000' }} !important;
        @else
        /* Background will be set by image */
        background-color: transparent !important;
        @endif
    }
    
    @if(($footer->background_type ?? 'color') == 'image')
    /* Show background images when type is image */
    .footer_bg_image_wrap {
        display: block !important;
    }
    @else
    /* Hide background images when type is color */
    .footer_bg_image_wrap {
        display: none !important;
    }
    @endif
    
    .social-icon svg path{
        color: {{ $footer->color ?? '#ffffff' }} !important;
        fill: {{ $footer->color ?? '#ffffff' }} !important;
    }

    .sources_text span {
        color: {{ $footer->color ?? '#ffffff' }} !important;
    }

    .sources_text {
        color: {{ $footer->color ?? '#ffffff' }} !important;
    }

    .text-style-eyebrow{
        color: {{ $footer->color ?? '#ffffff' }} !important;
    }

    .rega{
        color: {{ $footer->color ?? '#ffffff' }} !important;
    }

    .forward_statements{
        color: {{ $footer->color ?? '#ffffff' }} !important;
    }

    .footer_link_item a{
        color: {{ $footer->color ?? '#ffffff' }} !important;
    }

    .footer_legal_link{
        color: {{ $footer->color ?? '#ffffff' }} !important;
    }

    .footer_credit_text{
        color: {{ $footer->color ?? '#ffffff' }} !important;
    }

    /* Default footer color for unstyled elements */
    .footer_credit_text {
        color: {{ $footer->color ?? '#ffffff' }} !important;
    }
    
    /* Force ALL elements to respect their inline styles with maximum specificity */
    .footer_credit_text * {
        color: unset !important;
        font-size: unset !important;
    }
    
    /* Re-apply footer color only to elements without inline styles */
    .footer_credit_text p:not([style]),
    .footer_credit_text div:not([style]),  
    .footer_credit_text span:not([style]),
    .footer_credit_text strong:not([style]),
    .footer_credit_text em:not([style]) {
        color: {{ $footer->color ?? '#ffffff' }} !important;
    }

    .footer_logo{
        object-fit: unset !important;
    }

    .link_wrap div, .link_wrap svg path {
        color: {{ $footer->color ?? '#ffffff' }} !important;
        fill: {{ $footer->color ?? '#ffffff' }} !important;
    }

    .disclaimer_wrap {
        color: {{ $footer->color ?? '#ffffff' }} !important;
    }

    .jkkjjkkj p{
        margin-bottom: 0px !important;
    }
</style>
@endif

@php
    $url = request()->getHost();
    // Fetch footer based on domain
    $website = \App\Models\Website::where('domain', $url)->first(); 
    $footer = \App\Models\Footer::where('website_id', $website ? $website->id : null)->first();
    $setting = \App\Models\Setting::where('user_id', $website ? $website->user_id : null)->first();

    // dd($setting);

@endphp
@if ($website && $website->isInvestment())
    <style>
        @media (max-width: 360px) {
            footer{
                margin-bottom: 6rem !important;
            }
        }
    </style>
@endif

<footer id="footer" class="footer_component no-border">
    <div class="footer_container u-container z-index-1" style="padding-bottom: 0px;">
        <div class="footer_top_wrapper">
            <div class="footer_content_wrap _1"><a
                    href="https://{{ $website->domain }}"
                    dmr-utm-forward="1" aria-label="Go to EnergyX's homepage"
                    id="w-node-_1a8f52e2-9bf4-f242-e723-3b1fe0e365c7-e0e365c4" target="_blank"
                    class="footer_logo_link w-nav-brand" style="height: auto;"><img width="Auto" loading="lazy" alt="{{ $website->name }}"
                        src="{{ asset('/uploads/' . $setting->logo) }}"
                        class="footer_logo"></a>
                @if(!empty($footer->disclaimer_text) && strip_tags($footer->disclaimer_text) != '')
                <div class="jkkjjkkj" style="color: {{ $footer->color ?? '#ffffff' }} !important; margin-top: 40px !important;">
                    {!! $footer->disclaimer_text !!}
                </div>
                @endif
                <div class="spacer-small"></div>
                @if ($footer->social == 1)
                    <ul id="w-node-_1a8f52e2-9bf4-f242-e723-3b1fe0e365c9-e0e365c4" role="list" class="footer_link_list">
                        @if($footer && $footer->facebook != '#')
                        <li class="footer_link_item"><a aria-label="Facebook"
                                href="{{ $footer->facebook }}" target="_blank"
                                class="footer_social_link w-inline-block">
                                <div class="social-icon w-embed"><svg width="100%" height="100%" viewBox="0 0 24 24"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M22 12.0611C22 6.50451 17.5229 2 12 2C6.47715 2 2 6.50451 2 12.0611C2 17.0828 5.65684 21.2452 10.4375 22V14.9694H7.89844V12.0611H10.4375V9.84452C10.4375 7.32296 11.9305 5.93012 14.2146 5.93012C15.3088 5.93012 16.4531 6.12663 16.4531 6.12663V8.60261H15.1922C13.95 8.60261 13.5625 9.37822 13.5625 10.1739V12.0611H16.3359L15.8926 14.9694H13.5625V22C18.3432 21.2452 22 17.083 22 12.0611Z"
                                            fill="CurrentColor"></path>
                                    </svg></div>
                            </a></li>
                        @endif
                        @if($footer && $footer->twitter != '#')
                        <li class="footer_link_item"><a aria-label="X (former twitter)" href="{{ $footer->twitter }}"
                                target="_blank" class="footer_social_link w-inline-block">
                                <div class="social-icon w-embed"><svg width="100%" height="100%" viewBox="0 0 24 24"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M17.1761 4H19.9362L13.9061 10.7774L21 20H15.4456L11.0951 14.4066L6.11723 20H3.35544L9.80517 12.7508L3 4H8.69545L12.6279 9.11262L17.1761 4ZM16.2073 18.3754H17.7368L7.86441 5.53928H6.2232L16.2073 18.3754Z"
                                            fill="CurrentColor"></path>
                                    </svg></div>
                            </a></li>
                        @endif
                        @if($footer && $footer->instagram != '#')
                        <li class="footer_link_item"><a aria-label="Instagram" href="{{ $footer->instagram }}"
                                target="_blank" class="footer_social_link w-inline-block">
                                <div class="social-icon w-embed"><svg width="100%" height="100%" viewBox="0 0 24 24"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M16 3H8C5.23858 3 3 5.23858 3 8V16C3 18.7614 5.23858 21 8 21H16C18.7614 21 21 18.7614 21 16V8C21 5.23858 18.7614 3 16 3ZM19.25 16C19.2445 17.7926 17.7926 19.2445 16 19.25H8C6.20735 19.2445 4.75549 17.7926 4.75 16V8C4.75549 6.20735 6.20735 4.75549 8 4.75H16C17.7926 4.75549 19.2445 6.20735 19.25 8V16ZM16.75 8.25C17.3023 8.25 17.75 7.80228 17.75 7.25C17.75 6.69772 17.3023 6.25 16.75 6.25C16.1977 6.25 15.75 6.69772 15.75 7.25C15.75 7.80228 16.1977 8.25 16.75 8.25ZM12 7.5C9.51472 7.5 7.5 9.51472 7.5 12C7.5 14.4853 9.51472 16.5 12 16.5C14.4853 16.5 16.5 14.4853 16.5 12C16.5027 10.8057 16.0294 9.65957 15.1849 8.81508C14.3404 7.97059 13.1943 7.49734 12 7.5ZM9.25 12C9.25 13.5188 10.4812 14.75 12 14.75C13.5188 14.75 14.75 13.5188 14.75 12C14.75 10.4812 13.5188 9.25 12 9.25C10.4812 9.25 9.25 10.4812 9.25 12Z"
                                            fill="CurrentColor"></path>
                                    </svg></div>
                            </a></li>
                        @endif
                        @if($footer && $footer->linkedin != '#')
                        <li class="footer_link_item"><a aria-label="Linkedin"
                                href="{{ $footer->linkedin }}" target="_blank"
                                class="footer_social_link w-inline-block">
                                <div class="social-icon w-embed"><svg width="100%" height="100%" viewBox="0 0 24 24"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M4.5 3C3.67157 3 3 3.67157 3 4.5V19.5C3 20.3284 3.67157 21 4.5 21H19.5C20.3284 21 21 20.3284 21 19.5V4.5C21 3.67157 20.3284 3 19.5 3H4.5ZM8.52076 7.00272C8.52639 7.95897 7.81061 8.54819 6.96123 8.54397C6.16107 8.53975 5.46357 7.90272 5.46779 7.00413C5.47201 6.15897 6.13998 5.47975 7.00764 5.49944C7.88795 5.51913 8.52639 6.1646 8.52076 7.00272ZM12.2797 9.76176H9.75971H9.7583V18.3216H12.4217V18.1219C12.4217 17.742 12.4214 17.362 12.4211 16.9819V16.9818V16.9816V16.9815V16.9812C12.4203 15.9674 12.4194 14.9532 12.4246 13.9397C12.426 13.6936 12.4372 13.4377 12.5005 13.2028C12.7381 12.3253 13.5271 11.7586 14.4074 11.8979C14.9727 11.9864 15.3467 12.3141 15.5042 12.8471C15.6013 13.1803 15.6449 13.5389 15.6491 13.8863C15.6605 14.9339 15.6589 15.9815 15.6573 17.0292V17.0294C15.6567 17.3992 15.6561 17.769 15.6561 18.1388V18.3202H18.328V18.1149C18.328 17.6629 18.3278 17.211 18.3275 16.7591V16.759V16.7588C18.327 15.6293 18.3264 14.5001 18.3294 13.3702C18.3308 12.8597 18.276 12.3563 18.1508 11.8627C17.9638 11.1286 17.5771 10.5211 16.9485 10.0824C16.5027 9.77019 16.0133 9.5691 15.4663 9.5466C15.404 9.54401 15.3412 9.54062 15.2781 9.53721L15.2781 9.53721L15.2781 9.53721C14.9984 9.52209 14.7141 9.50673 14.4467 9.56066C13.6817 9.71394 13.0096 10.0641 12.5019 10.6814C12.4429 10.7522 12.3852 10.8241 12.2991 10.9314L12.2991 10.9315L12.2797 10.9557V9.76176ZM5.68164 18.3244H8.33242V9.76733H5.68164V18.3244Z"
                                            fill="CurrentColor"></path>
                                    </svg></div>
                            </a></li>
                        @endif
                        @if($footer && $footer->youtube != '#')
                        <li class="footer_link_item"><a aria-label="YouTube"
                                href="{{ $footer->youtube }}" target="_blank"
                                class="footer_social_link w-inline-block">
                                <div class="social-icon w-embed"><svg width="100%" height="100%" viewBox="0 0 24 24"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"
                                            fill="CurrentColor"></path>
                                    </svg></div>
                            </a></li>
                        @endif
                        @if($footer && $footer->tiktok != '#')
                        <li class="footer_link_item"><a aria-label="TikTok"
                                href="{{ $footer->tiktok }}" target="_blank"
                                class="footer_social_link w-inline-block">
                                <div class="social-icon w-embed"><svg width="100%" height="100%" viewBox="0 0 24 24"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-.88-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z"
                                            fill="CurrentColor"></path>
                                    </svg></div>
                            </a></li>
                        @endif
                        @if($footer && $footer->pinterest != '#')
                        <li class="footer_link_item"><a aria-label="Pinterest"
                                href="{{ $footer->pinterest }}" target="_blank"
                                class="footer_social_link w-inline-block">
                                <div class="social-icon w-embed"><svg width="100%" height="100%" viewBox="0 0 24 24"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.219-.359-1.219c0-1.142.662-1.997 1.482-1.997.699 0 1.037.219 1.037 1.142 0 .695-.219 1.735-.359 2.692-.199.937.219 1.697 1.142 1.697 1.367 0 2.426-1.459 2.426-3.561 0-1.855-1.337-3.158-3.244-3.158-2.219 0-3.518 1.657-3.518 3.378 0 .662.255 1.378.574 1.774.062.074.07.139.052.215-.057.239-.184.749-.209.854-.033.139-.107.169-.246.102-1.268-.588-2.07-2.426-2.07-3.913 0-2.447 1.774-4.692 5.11-4.692 2.686 0 4.774 1.915 4.774 4.468 0 2.665-1.678 4.81-4.009 4.81-.784 0-1.522-.408-1.774-.896 0 0-.388 1.478-.483 1.84-.175.675-.647 1.522-.963 2.035.726.225 1.497.345 2.292.345 6.624 0 11.99-5.367 11.99-11.987C24.007 5.367 18.641.001 12.017.001z"
                                            fill="CurrentColor"></path>
                                    </svg></div>
                            </a></li>
                        @endif
                        @if($footer && $footer->blue_sky != '#')
                        <li class="footer_link_item"><a aria-label="BlueSky"
                                href="{{ $footer->blue_sky }}" target="_blank"
                                class="footer_social_link w-inline-block">
                                <div class="social-icon w-embed"><svg width="100%" height="100%" viewBox="0 0 24 24"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M2.4 5.2C4.8 2.8 7.2 1.6 9.6 2.4c2.4.8 3.6 2.8 3.6 5.2 0 2.4-1.2 4.4-3.6 5.2-2.4.8-4.8-.4-7.2-2.8zM21.6 5.2c-2.4-2.4-4.8-3.6-7.2-2.8-2.4.8-3.6 2.8-3.6 5.2 0 2.4 1.2 4.4 3.6 5.2 2.4.8 4.8-.4 7.2-2.8z"
                                            fill="CurrentColor"></path>
                                    </svg></div>
                            </a></li>
                        @endif
                    </ul>
                @endif
                <div class="footer_content_wrap _2 mobile-show">
                <div class="footer_content_heading">
                    <div class="text-style-eyebrow" style="color: {{ $footer->contact_heading_color ?? $footer->color ?? '#ffffff' }} !important; font-family: '{{ $footer->contact_heading_font ?? 'outfit' }}', sans-serif !important; font-size: {{ $footer->contact_heading_size ?? '14px' }} !important;">{{ $footer->contact_heading ?? 'Contact Us' }}</div>
                </div><a aria-label="Email {{ $website->name }}" href="mailto:{{$website->user->email}}"
                    class="link_wrap not-allcaps w-inline-block">
                    <div style="color: {{ $footer->contact_email_color ?? $footer->color ?? '#ffffff' }} !important; font-family: '{{ $footer->contact_email_font ?? 'outfit' }}', sans-serif !important; font-size: {{ $footer->contact_email_size ?? '14px' }} !important;">{{$website->user->email}}</div>
                </a>
            </div>
            </div>
            <div class="footer_content_wrap _2 mobile-hide">
                <div class="footer_content_heading">
                    <div class="text-style-eyebrow" style="color: {{ $footer->contact_heading_color ?? $footer->color ?? '#ffffff' }} !important; font-family: '{{ $footer->contact_heading_font ?? 'outfit' }}', sans-serif !important; font-size: {{ $footer->contact_heading_size ?? '14px' }} !important;">{{ $footer->contact_heading ?? 'Contact Us' }}</div>
                </div><a aria-label="Email {{ $website->name }}" href="mailto:{{$website->user->email}}"
                    class="link_wrap not-allcaps w-inline-block">
                    <div style="color: {{ $footer->contact_email_color ?? $footer->color ?? '#ffffff' }} !important; font-family: '{{ $footer->contact_email_font ?? 'outfit' }}', sans-serif; font-size: {{ $footer->contact_email_size ?? '14px' }} !important;">{{$website->user->email}}</div>
                </a>
            </div>
        </div>
        
        @if(!empty($footer->description_text) && strip_tags($footer->description_text) != '')
        {{-- <div class="footer_line_divider"></div> --}}
        <div class="disclaimer_wrap text-size-tiny text-color-secondary" style="text-align: center;">
           {!! $footer->description_text !!}
        </div>
        @endif
        
        {{-- <div class="footer_line_divider"></div> --}}
        <div class="footer_bottom_wrapper" style="padding-bottom: 10px;">
            <ul id="w-node-_1a8f52e2-9bf4-f242-e723-3b1fe0e36600-e0e365c4" role="list" class="footer_link_list">
                @if ($footer && $footer->privacy == 1)
                    @if ($footer->refund_page_id && $footer->refund_page)
                        <li class="footer_link_item"><a href="/page/{{ str_replace(' ', '-', strtolower($footer->refund_page->name)) }}"
                                aria-label="Read refund policy" class="footer_legal_link">Refund policy</a>
                        </li>
                    @endif
                    @if ($footer->privacy_page_id && $footer->privacy_page)
                        <li class="footer_link_item"><a href="/page/{{ str_replace(' ', '-', strtolower($footer->privacy_page->name)) }}"
                                aria-label="Read privacy policy" class="footer_legal_link">Privacy policy</a>
                        </li>
                    @endif
                    @if ($footer->terms_page_id && $footer->terms_page)
                        <li class="footer_link_item"><a href="/page/{{ str_replace(' ', '-', strtolower($footer->terms_page->name)) }}"
                                aria-label="Read terms of service" class="footer_legal_link">Terms of service</a>
                        </li>
                    @endif
                @endif
            </ul>
            @if(!empty($footer->investment_disclaimer) && strip_tags($footer->investment_disclaimer) != '')
            <div id="w-node-_1a8f52e2-9bf4-f242-e723-3b1fe0e365fe-e0e365c4" class="footer_credit_text" style="color: {{ $footer->color ?? '#ffffff' }} !important;
            @if($footer && $footer->privacy != 1)
                width: 100%; text-align: center;
            @else
            @endif
            ">
                <div style="color: inherit !important; font-family: Outfit,sans-serif !important;">
                    {!! $footer->investment_disclaimer ?? '' !!}
                </div>
            </div>
            @endif
        </div>
    </div>
    <div class="footer_bg_image_wrap"><img width="1920" sizes="(max-width: 1920px) 100vw, 1920px"
            alt="Footer background image for desktop"
            src="{{ $footer->background_image_desktop ? asset('uploads/' . $footer->background_image_desktop) : 'https://cdn.prod.website-files.com/615c7704bf83fe0f0bb27c0b/685affc19009a2f3c9ecd550_iStock-520365936_a.webp' }}"
            loading="lazy"
            class="footer_bg_image_desktop"><img width="720" sizes="(max-width: 720px) 100vw, 720px"
            alt="Footer background image for mobile"
            src="{{ $footer->background_image_mobile ? asset('uploads/' . $footer->background_image_mobile) : 'https://cdn.prod.website-files.com/615c7704bf83fe0f0bb27c0b/688060eaaffeb257c1461eca_Sky-Mobile.webp' }}"
            loading="lazy"
            class="footer_bg_image_mobile">
        <div class="footer_bg_overlay"></div>
    </div>

</footer>
