<!DOCTYPE html>
<!-- Last Published: Thu Aug 28 2025 16:28:18 GMT+0000 (Coordinated Universal Time) -->
<html data-wf-page="68547489207784144a773f3e" data-wf-site="656f55af4b70f4ce7ae4b997"
    lang="en">

<head>
    <meta charset="utf-8" />
    <title>{{ $setting->meta_title ?? 'DealMaker | Raise Capital Online' }}</title>
    <meta
        content="{{ $setting->meta_description ?? 'DealMaker empowers founders to raise capital online via Reg A, CF, and D. Our tools help companies reach investors and build community from seed to IPO.' }}"
        name="description" />
    <meta content="{{ $setting->meta_title ?? 'DealMaker | Raise Capital Online' }}" property="og:title" />
    <meta
        content="{{ $setting->meta_description ?? 'DealMaker empowers founders to raise capital online via Reg A, CF, and D. Our tools help companies reach investors and build community from seed to IPO.' }}"
        property="og:description" />
    <meta
        content="{{ $setting->uploaded_og_image ? asset($setting->uploaded_og_image) : $setting->og_image ?? 'https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/685d988c9d3abae4ca097302_opengraphimage.png' }}"
        property="og:image" />
    <meta content="{{ $setting->meta_title ?? 'DealMaker | Raise Capital Online' }}" property="twitter:title" />
    <meta
        content="{{ $setting->meta_description ?? 'DealMaker empowers founders to raise capital online via Reg A, CF, and D. Our tools help companies reach investors and build community from seed to IPO.' }}"
        property="twitter:description" />
    <meta property="og:type" content="website" />
    <meta content="summary_large_image" name="twitter:card" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    @if ($setting->meta_keywords)
        <meta name="keywords" content="{{ $setting->meta_keywords }}" />
    @endif
    <meta content="google-site-verification=cfRfTejLrKY67Lsv3uWZ-Dt1WC9ny_7amMPApbAw-fc"
        name="google-site-verification" />
    <link href="{{ asset('css/dealmaker-main.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/dealmaker-fonts.css') }}" rel="stylesheet" type="text/css" />
    
    <!-- Button Color Customization -->
    <style>
        /* Apply primary button color to all non-transparent buttons */
        .n_button:not(.is-ghost),
        .hero-cta-button,
        .w-button {
            background-color: {{ $setting->button_primary_color ?? '#f31cb6' }} !important;
            border-color: {{ $setting->button_primary_color ?? '#f31cb6' }} !important;
            color: {{ $setting->button_text_color ?? '#ffffff' }} !important;
        }
        
        .trans{
            background-color: transparent !important;
            color: white !important;
            border-color: white !important;
        }


        /* Hover and active states for ALL buttons including .is-alternate */
        .n_button:not(.is-ghost):hover,
        .n_button:not(.is-ghost):active,
        .n_button:not(.is-ghost):focus,
        .n_button.is-alternate:hover,
        .n_button.is-alternate:active,
        .n_button.is-alternate:focus,
        .hero-cta-button:hover,
        .hero-cta-button:active,
        .hero-cta-button:focus,
        .w-button:hover,
        .w-button:active,
        .w-button:focus {
            background-color: {{ $setting->button_hover_color ?? '#d1179a' }} !important;
            border-color: {{ $setting->button_hover_color ?? '#d1179a' }} !important;
            color: {{ $setting->button_text_color ?? '#ffffff' }} !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2) !important;
            transition: all 0.3s ease !important;
        }
        
        /* Include all button variations except is-ghost */
        .n_button.is-small:not(.is-ghost),
        .n_button.is-darker:not(.is-ghost){
            background-color: {{ $setting->button_primary_color ?? '#f31cb6' }} !important;
            border-color: {{ $setting->button_primary_color ?? '#f31cb6' }} !important;
            color: {{ $setting->button_text_color ?? '#ffffff' }} !important;
        }
        
        .n_button.is-small:not(.is-ghost):hover,
        .n_button.is-small:not(.is-ghost):active,
        .n_button.is-small:not(.is-ghost):focus,
        .n_button.is-darker:not(.is-ghost):hover,
        .n_button.is-darker:not(.is-ghost):active,
        .n_button.is-darker:not(.is-ghost):focus {
            background-color: {{ $setting->button_hover_color ?? '#d1179a' }} !important;
            border-color: {{ $setting->button_hover_color ?? '#d1179a' }} !important;
            color: {{ $setting->button_text_color ?? '#ffffff' }} !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2) !important;
            transition: all 0.3s ease !important;
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

        /* Modern Header Styles */
        .modern-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Announcement Bar */
        .announcement-bar {
            @php
                $announcementColor = $setting->getSectionBackgroundColor('announcement', '#2563eb');
                // Remove # if present and convert to RGB
                $hex = ltrim($announcementColor, '#');
                if (strlen($hex) === 3) {
                    $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
                }
                
                // Convert hex to RGB
                $r = hexdec(substr($hex, 0, 2));
                $g = hexdec(substr($hex, 2, 2));
                $b = hexdec(substr($hex, 4, 2));
                
                // Generate second color by shifting hue and adjusting brightness
                $hsl = rgbToHsl($r, $g, $b);
                $hsl[0] = ($hsl[0] + 30) % 360; // Shift hue by 30 degrees
                $hsl[2] = min(1, $hsl[2] * 1.2); // Brighten by 20%
                $rgb2 = hslToRgb($hsl[0], $hsl[1], $hsl[2]);
                
                $secondColor = sprintf("#%02x%02x%02x", $rgb2[0], $rgb2[1], $rgb2[2]);
                
                function rgbToHsl($r, $g, $b) {
                    $r /= 255; $g /= 255; $b /= 255;
                    $max = max($r, $g, $b); $min = min($r, $g, $b);
                    $h = $s = $l = ($max + $min) / 2;
                    
                    if ($max == $min) {
                        $h = $s = 0;
                    } else {
                        $d = $max - $min;
                        $s = $l > 0.5 ? $d / (2 - $max - $min) : $d / ($max + $min);
                        switch ($max) {
                            case $r: $h = ($g - $b) / $d + ($g < $b ? 6 : 0); break;
                            case $g: $h = ($b - $r) / $d + 2; break;
                            case $b: $h = ($r - $g) / $d + 4; break;
                        }
                        $h /= 6;
                    }
                    return [$h * 360, $s, $l];
                }
                
                function hslToRgb($h, $s, $l) {
                    $h /= 360;
                    if ($s == 0) {
                        $r = $g = $b = $l;
                    } else {
                        $hue2rgb = function($p, $q, $t) {
                            if ($t < 0) $t += 1;
                            if ($t > 1) $t -= 1;
                            if ($t < 1/6) return $p + ($q - $p) * 6 * $t;
                            if ($t < 1/2) return $q;
                            if ($t < 2/3) return $p + ($q - $p) * (2/3 - $t) * 6;
                            return $p;
                        };
                        $q = $l < 0.5 ? $l * (1 + $s) : $l + $s - $l * $s;
                        $p = 2 * $l - $q;
                        $r = $hue2rgb($p, $q, $h + 1/3);
                        $g = $hue2rgb($p, $q, $h);
                        $b = $hue2rgb($p, $q, $h - 1/3);
                    }
                    return [round($r * 255), round($g * 255), round($b * 255)];
                }
            @endphp
            background: linear-gradient(135deg, {{ $announcementColor }}e6 0%, {{ $secondColor }}e6 100%);
            color: white;
            padding: 12px 0;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .announcement-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .announcement-content {
            display: flex;
            align-items: center;
            gap: 16px;
            flex: 1;
        }

        .announcement-badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .announcement-text {
            flex: 1;
        }

        .announcement-link {
            color: inherit;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            transition: opacity 0.2s ease;
        }

        .announcement-link:hover {
            opacity: 0.9;
        }

        .announcement-arrow {
            width: 16px;
            height: 16px;
            transition: transform 0.2s ease;
        }

        .announcement-link:hover .announcement-arrow {
            transform: translate(2px, -2px);
        }

        .announcement-close {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            padding: 8px;
            border-radius: 4px;
            transition: background-color 0.2s ease;
        }

        .announcement-close:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        /* Main Navbar - Dynamic Colors */
        .navbar {
            background: {{ $setting->getSectionBackgroundColor('navbar', 'rgba(255, 255, 255, 0.95)') }};
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            background: {{ $setting->getSectionBackgroundColor('navbar_scrolled', 'rgba(255, 255, 255, 0.98)') }};
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .navbar-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Logo */
        .navbar-logo {
            display: flex;
            align-items: center;
            text-decoration: none;
            z-index: 10;
        }

        .logo-image {
            height: 40px;
            width: auto;
            transition: transform 0.2s ease;
        }

        .navbar-logo:hover .logo-image {
            transform: scale(1.05);
        }

        .logo-svg {
            height: 40px;
            width: auto;
            color: {{ $setting->primary_color ?? '#2563eb' }};
        }

        .logo-svg svg {
            height: 100%;
            width: auto;
        }

        /* Desktop Menu */
        .desktop-menu {
            display: flex;
            align-items: center;
            gap: 40px;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 32px;
        }

        .nav-link {
            color: {{ $setting->nav_text_color ?? '#374151' }};
            text-decoration: none;
            font-weight: 500;
            font-size: 15px;
            padding: 8px 0;
            position: relative;
            transition: color 0.2s ease;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: {{ $setting->primary_color ?? '#2563eb' }};
            transition: width 0.3s ease;
        }

        .nav-link:hover {
            color: {{ $setting->button_primary_color ?? $setting->primary_color ?? '#2563eb' }};
        }

        .nav-link:hover::after {
            width: 100%;
            background: {{ $setting->button_primary_color ?? $setting->primary_color ?? '#2563eb' }};
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .nav-signin {
            display: flex;
            align-items: center;
            gap: 8px;
            color: {{ $setting->nav_text_color ?? '#6b7280' }};
            text-decoration: none;
            font-weight: 500;
            padding: 10px 16px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .nav-signin:hover {
            color: {{ $setting->button_primary_color ?? $setting->primary_color ?? '#2563eb' }};
            background-color: {{ $setting->button_hover_bg_color ?? '#f3f4f6' }};
        }

        .signin-icon {
            width: 20px;
            height: 20px;
        }

        .nav-cta {
            background: {{ $setting->button_primary_color ?? 'linear-gradient(135deg, #2563eb 0%, #7c3aed 100%)' }};
            color: {{ $setting->button_text_color ?? 'white' }};
            text-decoration: none;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(37, 99, 235, 0.2);
        }

        .nav-cta:hover {
            background: {{ $setting->button_hover_color ?? $setting->button_primary_color ?? '#2563eb' }};
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.3);
        }

        /* Mobile Menu Button */
        .mobile-menu-btn {
            display: none;
            flex-direction: column;
            justify-content: space-around;
            width: 32px;
            height: 24px;
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 0;
            z-index: 1001;
        }

        .hamburger-line {
            width: 100%;
            height: 3px;
            background-color: {{ $setting->nav_text_color ?? '#374151' }};
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .mobile-menu-btn.active .hamburger-line:nth-child(1) {
            transform: rotate(45deg) translate(6px, 6px);
        }

        .mobile-menu-btn.active .hamburger-line:nth-child(2) {
            opacity: 0;
        }

        .mobile-menu-btn.active .hamburger-line:nth-child(3) {
            transform: rotate(-45deg) translate(6px, -6px);
        }

        /* Mobile Menu Overlay */
        .mobile-menu-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.95);
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .mobile-menu-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .mobile-menu-content {
            background: white;
            height: 100%;
            max-width: 400px;
            margin-left: auto;
            padding: 32px;
            transform: translateX(100%);
            transition: transform 0.3s ease;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .mobile-menu-overlay.active .mobile-menu-content {
            transform: translateX(0);
        }

        .mobile-nav-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 48px;
            padding-bottom: 24px;
            border-bottom: 1px solid #e5e7eb;
        }

        .mobile-logo img {
            height: 32px;
            width: auto;
        }

        .mobile-logo-text {
            font-size: 20px;
            font-weight: 700;
            color: {{ $setting->primary_color ?? '#2563eb' }};
        }

        .mobile-close-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
            color: {{ $setting->nav_text_color ?? '#6b7280' }};
        }

        .mobile-nav-links {
            display: flex;
            flex-direction: column;
            gap: 24px;
            margin-bottom: 48px;
        }

        .mobile-nav-link {
            color: {{ $setting->nav_text_color ?? '#111827' }};
            text-decoration: none;
            font-size: 18px;
            font-weight: 500;
            padding: 12px 0;
            border-bottom: 1px solid #f3f4f6;
            transition: color 0.2s ease;
        }

        .mobile-nav-link:hover {
            color: {{ $setting->button_primary_color ?? $setting->primary_color ?? '#2563eb' }};
        }

        .mobile-nav-actions {
            display: flex;
            flex-direction: column;
            gap: 16px;
            margin-top: auto;
        }

        .mobile-signin {
            display: flex;
            align-items: center;
            gap: 12px;
            color: {{ $setting->nav_text_color ?? '#6b7280' }};
            text-decoration: none;
            font-weight: 500;
            padding: 16px;
            border-radius: 8px;
            background-color: {{ $setting->button_bg_color ?? '#f9fafb' }};
            transition: all 0.2s ease;
        }

        .mobile-signin:hover {
            color: {{ $setting->button_primary_color ?? $setting->primary_color ?? '#2563eb' }};
            background-color: {{ $setting->button_hover_bg_color ?? '#f3f4f6' }};
        }

        .mobile-cta {
            background: {{ $setting->button_primary_color ?? 'linear-gradient(135deg, #2563eb 0%, #7c3aed 100%)' }};
            color: {{ $setting->button_text_color ?? 'white' }};
            text-decoration: none;
            font-weight: 600;
            padding: 16px 24px;
            border-radius: 8px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .mobile-cta:hover {
            background: {{ $setting->button_hover_color ?? $setting->button_primary_color ?? '#2563eb' }};
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.3);
        }

        /* Responsive Design */
        @media (max-width: 991px) {
            .desktop-menu {
                display: none;
            }
            
            .mobile-menu-btn {
                display: flex;
            }
            
            .navbar-container {
                height: 70px;
                padding: 0 20px;
            }
            
            .announcement-container {
                padding: 0 20px;
            }
        }

        @media (max-width: 767px) {
            .navbar-container {
                height: 60px;
                padding: 0 16px;
            }
            
            .announcement-container {
                padding: 0 16px;
            }
            
            .announcement-content {
                gap: 12px;
            }
            
            .announcement-badge {
                display: none;
            }
            
            .logo-image {
                height: 32px;
            }
            
            .logo-svg {
                height: 32px;
            }
        }

        @media (max-width: 480px) {
            .mobile-menu-content {
                padding: 24px;
                max-width: 100%;
            }
            
            .announcement-text {
                font-size: 13px;
            }
        }
        
        /* Social Icon Styling */
        .footer3_social-link {
            background-color: {{ $setting->social_icon_bg_color ?? '#f31cb6' }} !important;
            border-radius: 8px !important;
            padding: 12px !important;
            transition: all 0.3s ease !important;
        }
        
        .footer3_social-link:hover {
            background-color: {{ $setting->social_icon_hover_color ?? '#d1179a' }} !important;
        }
        
        .footer3_social-link svg,
        .footer3_social-link .footer-icon svg {
            color: {{ $setting->social_icon_color ?? '#ffffff' }} !important;
            fill: {{ $setting->social_icon_color ?? '#ffffff' }} !important;
        }
        
        /* Smooth scroll behavior for navigation links */
        html {
            scroll-behavior: smooth;
        }
        
        .smooth-scroll {
            scroll-behavior: smooth;
        }
        
        /* Navbar link styling for section navigation */
        .navbar_link {
            color: inherit;
            text-decoration: none;
            padding: 0.5rem 1rem;
            display: inline-block;
            transition: all 0.3s ease;
        }
        
        .navbar_link:hover {
            color: {{ $setting->button_primary_color ?? '#f31cb6' }};
        }

        
    </style>
    
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

    @if ($setting->custom_head_code)
        {!! $setting->custom_head_code !!}
    @endif

    @if ($setting->custom_css)
        <style>
            {!! $setting->custom_css !!}
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

        /* Ensure hero section takes full height */
        .n_video_bg {
            min-height: 100vh !important;
            position: relative !important;
            overflow: hidden;
        }

        /* Ensure video container covers full area */
        .hero-video-container {
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: 100% !important;
            z-index: 0 !important;
        }

        /* Ensure content is above video */
        .hero-overlay {
            position: relative !important;
            z-index: 10 !important;
            min-height: 100vh !important;
        }

        /* Prevent main content from overlapping */
        main.main {
            position: relative;
            z-index: 5;
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

                .tab-link-tab-4.w--current{
                    background-color: {{ $setting->button_primary_color ?? '#f31cb6' }};
                    color: {{ $setting->button_text_color ?? '#ffffff' }};
                }

                .text-color-pink{
                    color: {{ $setting->button_text_color ?? '#ffffff' }};
                }

                .n_testimonial-text{
                    color: {{ $setting->button_hover_color ?? '#d1179a' }};
                }
            </style>
        </div>
        <!-- Modern Header Section with Glassmorphism Design -->
        <header class="modern-header" data-animation="default" role="banner">
            @if ($setting->show_announcement ?? true)
                <div class="announcement-bar">
                    <div class="announcement-container">
                        <div class="announcement-content">
                            <div class="announcement-badge">
                                <span class="badge-text">{{ $setting->announcement_badge ?? 'NEW' }}</span>
                            </div>
                            <div class="announcement-text">
                                <a href="{{ $setting->announcement_url ?? '/assetclassconference' }}" class="announcement-link">
                                    {{ $setting->announcement_text ?? 'Announcing Sports As An Asset Class Summit, October 16th. Learn More' }}
                                    <svg class="announcement-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none">
                                        <path d="M7 17L17 7M17 7H7M17 7V17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                        <button class="announcement-close" onclick="this.parentElement.parentElement.style.display='none'">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                                <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            <nav class="navbar" id="modernNavbar">
                <div class="navbar-container">
                    <!-- Logo Section -->
                    <a href="/" class="navbar-logo" aria-current="page">
                        @if ($setting->uploaded_logo)
                            <img src="{{ asset($setting->uploaded_logo) }}" alt="Site Logo" class="logo-image" />
                        @elseif($setting->site_logo)
                            <img src="{{ asset($setting->site_logo) }}" alt="Site Logo" class="logo-image" />
                        @else
                            <div class="logo-svg">
                                <svg width="auto" height="auto" viewBox="0 0 1345 237" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_8230_71)">
                                        <path d="M869.37 158.33V235.07H848.21V159.61C848.21 133.81 836.86 120.39 816.99 120.39C795.06 120.39 781.64 136.9 781.64 163.73V235.06H760.48V159.6C760.48 133.8 748.87 120.38 728.75 120.38C707.08 120.38 693.92 138.44 693.92 164.76V235.06H672.76V102.6H691.08L693.92 120.66C700.88 111.12 711.98 101.05 732.36 101.05C750.68 101.05 766.42 109.31 773.9 126.08C781.9 111.89 796.09 101.05 819.57 101.05C846.92 101.05 869.36 116.79 869.36 158.33H869.37Z" fill="currentColor" />
                                        <path d="M182.43 54.41L121.51 0V105.71L60.78 51.18V181.13L121.76 235.56V180.53L182.43 235.07V54.41Z" fill="currentColor" />
                                        <path d="M323.75 54.0098H344.94V234.87H326.59L323.75 213.68C314.96 225.82 300.75 236.42 278.53 236.42C242.1 236.42 215.23 211.87 215.23 168.99C215.23 128.68 242.1 101.55 278.53 101.55C300.75 101.55 315.74 110.6 323.75 123.26V54.0098ZM324 169.5C324 140.56 306.43 120.41 280.6 120.41C254.77 120.41 236.93 140.31 236.93 168.99C236.93 197.67 254.5 217.56 280.6 217.56C306.7 217.56 324 197.66 324 169.5Z" fill="currentColor" />
                                        <path d="M357.99 168.99C357.99 128.94 383.31 101.55 420.51 101.55C457.71 101.55 482 125.06 483.04 164.08C483.04 166.92 482.78 170.02 482.52 173.12H380.2V174.93C380.98 199.99 396.74 217.56 421.8 217.56C440.41 217.56 454.87 207.74 459.27 190.69H480.72C475.55 217.05 453.85 236.42 423.36 236.42C383.83 236.42 357.99 209.29 357.99 168.99ZM460.3 155.55C458.23 132.81 442.73 120.15 420.77 120.15C401.39 120.15 383.56 134.1 381.5 155.55H460.31H460.3Z" fill="currentColor" />
                                        <path d="M998.02 149.09C998.02 118.34 978.64 101.55 945.06 101.55C913.28 101.55 892.35 116.8 889.25 142.63H910.44C913.02 129.19 925.43 120.41 944.03 120.41C964.7 120.41 976.84 130.75 976.84 147.8V156.84H938.09C903.47 156.84 885.12 171.57 885.12 197.92C885.12 221.95 904.76 236.42 933.69 236.42C955.89 236.42 968.97 226.81 977.27 215.29L980.01 234.57L998.1 234.67L998.03 149.09H998.02ZM976.84 181.13C976.84 203.09 961.6 218.34 935.24 218.34C917.67 218.34 906.56 209.55 906.56 196.64C906.56 181.65 917.15 174.67 936.01 174.67H976.83V181.13H976.84Z" fill="currentColor" />
                                        <path d="M607.67 149.09C607.67 118.34 588.29 101.55 554.71 101.55C522.93 101.55 502 116.8 498.9 142.63H520.09C522.67 129.19 535.08 120.41 553.68 120.41C574.35 120.41 586.49 130.75 586.49 147.8V156.84H547.74C513.12 156.84 494.77 171.57 494.77 197.92C494.77 221.95 514.41 236.42 543.34 236.42C565.54 236.42 578.62 226.81 586.92 215.29L589.66 234.57L607.75 234.67L607.68 149.09H607.67ZM586.48 181.13C586.48 203.09 571.24 218.34 544.88 218.34C527.31 218.34 516.2 209.55 516.2 196.64C516.2 181.65 526.79 174.67 545.65 174.67H586.47V181.13H586.48Z" fill="currentColor" />
                                        <path d="M650.83 54.0098H629.64V234.87H650.83V54.0098Z" fill="currentColor" />
                                        <path d="M1019.85 54.0098H1041.04V173.12L1107.18 103.1H1133.28L1081.86 157.62L1136.89 234.88H1111.31L1067.65 172.87L1041.04 200.26V234.88H1019.85V54.0098Z" fill="currentColor" />
                                        <path d="M1131.83 168.99C1131.83 128.94 1157.15 101.55 1194.35 101.55C1231.55 101.55 1255.84 125.06 1256.88 164.08C1256.88 166.92 1256.62 170.02 1256.36 173.12H1154.04V174.93C1154.82 199.99 1170.58 217.56 1195.64 217.56C1214.25 217.56 1228.71 207.74 1233.11 190.69H1254.56C1249.39 217.05 1227.69 236.42 1197.2 236.42C1157.67 236.42 1131.83 209.29 1131.83 168.99ZM1234.15 155.55C1232.08 132.81 1216.58 120.15 1194.62 120.15C1175.24 120.15 1157.41 134.1 1155.35 155.55H1234.16H1234.15Z" fill="currentColor" />
                                        <path d="M1344.03 103.1V123.77H1333.43C1305.78 123.77 1298.29 146.77 1298.29 167.69V234.87H1277.1V103.1H1295.44L1298.29 123C1304.49 112.92 1314.57 103.1 1338.08 103.1H1344.03Z" fill="currentColor" />
                                        <path d="M60.78 235.01L0 180.8V126.88L60.78 181.12V235.01Z" fill="#8EE8DF" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_8230_71">
                                            <rect width="1344.03" height="236.42" fill="white" />
                                        </clipPath>
                                    </defs>
                                </svg>
                            </div>
                        @endif
                    </a>

                    <!-- Desktop Navigation -->
                    <div class="navbar-menu desktop-menu">
                        <nav class="nav-links">
                            @php
                                $enabledMenuItems = $setting->getEnabledMenuItems();
                            @endphp
                            @if(count($enabledMenuItems) > 0)
                                @foreach($enabledMenuItems as $menuItem)
                                    <a href="{{ $menuItem['anchor'] }}" class="nav-link smooth-scroll">
                                        {{ $menuItem['title'] }}
                                    </a>
                                @endforeach
                            @endif
                        </nav>
                        
                        <div class="nav-actions">
                            <a href="{{ $setting->signin_url ?? '/login' }}" 
                               class="nav-signin" style="display: none;">
                                <svg class="signin-icon" width="20" height="20" viewBox="0 0 24 24" fill="none">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span>{{ $setting->signin_text ?? 'Sign In' }}</span>
                            </a>
                            <a href="{{ $setting->main_cta_url ?? '/connect' }}" class="nav-cta">
                                {{ $setting->main_cta_text ?? 'Get Started' }}
                            </a>
                        </div>
                    </div>

                    <!-- Mobile Menu Button -->
                    <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Toggle mobile menu">
                        <span class="hamburger-line"></span>
                        <span class="hamburger-line"></span>
                        <span class="hamburger-line"></span>
                    </button>
                </div>

                <!-- Mobile Navigation Overlay -->
                <div class="mobile-menu-overlay" id="mobileMenuOverlay">
                    <div class="mobile-menu-content">
                        <div class="mobile-nav-header">
                            <div class="mobile-logo">
                                @if ($setting->uploaded_logo)
                                    <img src="{{ asset($setting->uploaded_logo) }}" alt="Site Logo" />
                                @elseif($setting->site_logo)
                                    <img src="{{ asset($setting->site_logo) }}" alt="Site Logo" />
                                @else
                                    <div class="mobile-logo-text">{{ config('app.name', 'DealMaker') }}</div>
                                @endif
                            </div>
                            <button class="mobile-close-btn" id="mobileCloseBtn">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </div>
                        
                        <nav class="mobile-nav-links">
                            @if(count($enabledMenuItems) > 0)
                                @foreach($enabledMenuItems as $menuItem)
                                    <a href="{{ $menuItem['anchor'] }}" class="mobile-nav-link smooth-scroll">
                                        {{ $menuItem['title'] }}
                                    </a>
                                @endforeach
                            @endif
                        </nav>
                        
                        <div class="mobile-nav-actions">
                            <a href="{{ $setting->signin_url ?? '/login' }}" 
                               class="mobile-signin" style="display: none;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                {{ $setting->signin_text ?? 'Sign In' }}
                            </a>
                            <a href="{{ $setting->main_cta_url ?? '/connect' }}" class="mobile-cta">
                                {{ $setting->main_cta_text ?? 'Get Started' }}
                            </a>
                        </div>
                    </div>
                </div>
            </nav>
        </header>
        <div class="code-embed-6 w-embed w-script">
            <script>
                // Modern Header JavaScript
                document.addEventListener('DOMContentLoaded', function() {
                    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
                    const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
                    const mobileCloseBtn = document.getElementById('mobileCloseBtn');
                    const navbar = document.querySelector('.navbar');
                    const header = document.querySelector('.modern-header');
                    
                    // Mobile menu toggle
                    if (mobileMenuBtn && mobileMenuOverlay) {
                        mobileMenuBtn.addEventListener('click', function() {
                            mobileMenuBtn.classList.toggle('active');
                            mobileMenuOverlay.classList.toggle('active');
                            document.body.style.overflow = mobileMenuOverlay.classList.contains('active') ? 'hidden' : '';
                        });
                        
                        if (mobileCloseBtn) {
                            mobileCloseBtn.addEventListener('click', function() {
                                mobileMenuBtn.classList.remove('active');
                                mobileMenuOverlay.classList.remove('active');
                                document.body.style.overflow = '';
                            });
                        }
                        
                        // Close on link click
                        const mobileLinks = document.querySelectorAll('.mobile-nav-link');
                        mobileLinks.forEach(link => {
                            link.addEventListener('click', function() {
                                mobileMenuBtn.classList.remove('active');
                                mobileMenuOverlay.classList.remove('active');
                                document.body.style.overflow = '';
                            });
                        });
                        
                        // Close on overlay click
                        mobileMenuOverlay.addEventListener('click', function(e) {
                            if (e.target === mobileMenuOverlay) {
                                mobileMenuBtn.classList.remove('active');
                                mobileMenuOverlay.classList.remove('active');
                                document.body.style.overflow = '';
                            }
                        });
                    }
                    
                    // Scroll effects
                    let lastScrollTop = 0;
                    window.addEventListener('scroll', function() {
                        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                        
                        if (navbar) {
                            if (scrollTop > 100) {
                                navbar.classList.add('scrolled');
                            } else {
                                navbar.classList.remove('scrolled');
                            }
                        }
                        
                        // Header hide/show on scroll (optional)
                        if (header && scrollTop > 200) {
                            if (scrollTop > lastScrollTop && scrollTop > 300) {
                                // Scrolling down
                                header.style.transform = 'translateY(-100%)';
                            } else {
                                // Scrolling up
                                header.style.transform = 'translateY(0)';
                            }
                        }
                        
                        lastScrollTop = scrollTop;
                    });
                    
                    // Smooth scroll for anchor links
                    document.querySelectorAll('.smooth-scroll').forEach(anchor => {
                        anchor.addEventListener('click', function (e) {
                            const href = this.getAttribute('href');
                            if (href && href.startsWith('#')) {
                                e.preventDefault();
                                const target = document.querySelector(href);
                                if (target) {
                                    const headerHeight = header ? header.offsetHeight : 80;
                                    const targetPosition = target.offsetTop - headerHeight;
                                    
                                    window.scrollTo({
                                        top: targetPosition,
                                        behavior: 'smooth'
                                    });
                                }
                            }
                        });
                    });
                });
                
                // Legacy navbar fallback (keeping existing functionality)
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
                @if ($setting->show_hero ?? true)
                    <
                    header class = "n_section_hero dm-main" > < div class = "n_padding_hero" > < div class =
                    "container-large is-hero" > < div class = "n_padding-section-hero" > < div class = "rl_header44_component" > <
                    div class = "w-layout-vflex" > < div class = "rl_header44_spacing-block-2" > <
                    /div><div class="rl_header44_dmn-flex space margin-bottom margin-medium"><div class="dmn-line max-width-full"></div >
                    <
                    div class = "text-block-85" > {{ $setting->announcement_text ?? 'The Future Of Retail Capital.' }} < a href =
                        "{{ $setting->announcement_url ?? '/connect' }}"
                    class = "link-4" > < span class = "text-color-brand-green" > < strong >
                        {{ $setting->hero_subtitle ?? 'Raise Boldly' }} < /strong></span > <
                        /a><span class="text-color-brand-green"><strong>.</strong > < /span></div > < /div>if ( / *
                        ___directives_script_1___ *
                        / ) {<div class="w-layout-grid grid-15 on-home-page"><div id="w-node-_7ba4bfb3-22be-9448-20ac-427d4bb0aec6-4a773f3e" class="rl_header44_number-wrapper less-opacity"><div class="counter_number"><div class="n_large-numbers text-color-white">$</div >
                        <
                        div fs - numbercount - threshold = "0"
                    fs - numbercount - element = "number"
                    fs - numbercount - start = "1"
                    fs - numbercount - end = "{{ $setting->stat_1_number ?? '2' }}"
                    class = "n_large-numbers text-color-white" > {{ $setting->stat_1_number ?? '2' }} <
                        /div><div class="n_large-numbers text-color-white">B+</div > <
                        /div><div class="rl_header44_dmn-flex space"><div class="dmn-line"></div > < div >
                        {{ $setting->stat_1_text ?? 'Raised by customers' }} < /div></div > <
                        /div><div id="w-node-_7ba4bfb3-22be-9448-20ac-427d4bb0aed2-4a773f3e" class="rl_header44_number-wrapper less-opacity"><div class="counter_number"><div class="n_large-numbers text-color-white">{{ substr($setting->stat_2_number ?? '1.5', 0, strpos($setting->stat_2_number ?? '1.5', '.') ?: 1) }}.</div >
                        <
                        div fs - numbercount - threshold = "0"
                    fs - numbercount - element = "number"
                    fs - numbercount - start = "0"
                    fs - numbercount - end =
                        "{{ substr($setting->stat_2_number ?? '1.5', strpos($setting->stat_2_number ?? '1.5', '.') + 1) ?: '5' }}"
                    class = "n_large-numbers text-color-white" >
                    {{ substr($setting->stat_2_number ?? '1.5', strpos($setting->stat_2_number ?? '1.5', '.') + 1) ?: '0' }} <
                        /div><div class="n_large-numbers text-color-white">M+</div > <
                        /div><div class="rl_header44_dmn-flex space"><div class="dmn-line"></div > < div >
                        {{ $setting->stat_2_text ?? 'Investments processed' }} < /div></div > <
                        /div><div id="w-node-_7ba4bfb3-22be-9448-20ac-427d4bb0aede-4a773f3e" class="rl_header44_number-wrapper less-opacity"><div class="counter_number"><div fs-numbercount-threshold="0" fs-numbercount-element="number" fs-numbercount-start="624" fs-numbercount-end="{{ $setting->stat_3_number ?? '900' }}" class="n_large-numbers text-color-white">{{ $setting->stat_3_number ?? '100' }}</div >
                        <
                        div class = "n_large-numbers text-color-white" > + < /div></div > < div class =
                        "rl_header44_dmn-flex space" > < div class = "dmn-line" > <
                        /div><div>{{ $setting->stat_3_text ?? 'Offerings' }}</div > < /div></div > < /div>/ ** *
                        script_placeholder ** * /} / * ___directives_script_2___ * /</div > < /div></div > < /div></div >
                @endif < div id = "videoModal"
                class = "video-modal hidden" > < div class = "html-embed-7 w-embed w-iframe w-script" > < style >
                    #vimeo - mobile {
                        display: none;
                    }
                #vimeo - desktop {
                    display: block;
                }

                @media only screen and(max - width: 479 px) {
                        #vimeo - mobile {
                            display: block;
                        }
                        #vimeo - desktop {
                            display: none;
                        }
                    } <
                    /style>



                    <
                    !--Desktop Video-- >
                    <
                    div id = "vimeo-desktop"
                style = "padding:56.25% 0 0 0;position:relative;width:100%" >

                    <
                    !--Replace only this-- >
                    <
                    iframe src =
                    "{{ $setting->modal_video_desktop ?? 'https://player.vimeo.com/video/927222983' }}?badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479"
                frameborder = "0"
                allow = "autoplay; fullscreen; picture-in-picture; clipboard-write"
                style = "position:absolute;top:0;left:0;width:100%;height:100%;"
                title = "Sizzle Reel" > < /iframe> <!--Replace only this-- >

                    <
                    /div>

                    <
                    !--Mobile Video-- >
                    <
                    div id = "vimeo-mobile"
                style = "padding:177.78% 0 0 0;position:relative;width:100%" >

                    <
                    !--Replace only this-- >
                    <
                    iframe src =
                    "{{ $setting->modal_video_mobile ?? 'https://player.vimeo.com/video/927222983' }}?badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479"
                frameborder = "0"
                allow = "autoplay; fullscreen; picture-in-picture; clipboard-write"
                style = "position:absolute;top:0;left:0;width:100%;height:100%;"
                title = "Sizzle Reel" > < /iframe> <!--Replace only this-- > <
                    /div>

                    <
                    script src = "{{ asset('js/vimeo-player.js') }}" >
            </script>
        </div>
        <div class="bg-behind bg-closed" style="display: none;"></div><a style="display: none;" href="#"
            class="bg-closed video-close w-inline-block"><img
                src="https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/6601bb203ce160061a094c39_close-pop.svg"
                loading="lazy" alt="close video button" class="image-8" /></a>
    </div>
    <div id="hero" class="n_video_bg" style="background-color: {{ $setting->getSectionBackgroundColor('hero', '#000000') }}; min-height: 100vh; position: relative; display: flex; align-items: center;">
        @php
            $videoUrl = $setting->bg_video_url ?? ($setting->hero_background_video ?? '');
            // dd($videoUrl);
            $posterUrl =
                $setting->bg_video_poster_url ??
                'https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997%2F686d6096b46c58223d7cc59b_homepage_loop5_1-poster-00001.jpg';
            $isYouTube = str_contains($videoUrl, 'youtube.com') || str_contains($videoUrl, 'youtu.be');
            $isVimeo = str_contains($videoUrl, 'vimeo.com');

            // Convert YouTube URL to embed format
            if ($isYouTube) {
                // dd($videoUrl);
                if (str_contains($videoUrl, 'watch?v=')) {
                    parse_str(parse_url($videoUrl, PHP_URL_QUERY), $params);
                    $videoId = $params['v'] ?? '';
                } elseif (str_contains($videoUrl, 'youtu.be/')) {
                    $videoId = basename(parse_url($videoUrl, PHP_URL_PATH));
                } elseif (str_contains($videoUrl, 'embed/')) {
                    // Already an embed URL, extract video ID
                    $parts = explode('embed/', $videoUrl);
                    $videoId = explode('?', $parts[1] ?? '')[0];
                } else {
                    $videoId = '';
                }

                if ($videoId) {
                    // Use more compatible YouTube embed parameters
                    $embedUrl =
                        "https://www.youtube.com/embed/{$videoId}?autoplay=1&mute=1&loop=1&playlist={$videoId}&controls=0&disablekb=1&fs=0&modestbranding=1&rel=0&showinfo=0&enablejsapi=1&origin=" .
                        urlencode(request()->getSchemeAndHttpHost());

                    // Alternative embed URL for restricted videos
                    $altEmbedUrl = "https://www.youtube-nocookie.com/embed/{$videoId}?autoplay=1&mute=1&loop=1&playlist={$videoId}&controls=0&modestbranding=1&rel=0";
                } else {
                    $embedUrl = $videoUrl;
                    $altEmbedUrl = $videoUrl;
                }
            } elseif ($isVimeo) {
                $videoId = basename(parse_url($videoUrl, PHP_URL_PATH));
                $embedUrl = "https://player.vimeo.com/video/{$videoId}?autoplay=1&muted=1&loop=1&background=1&controls=0";
            } else {
                $embedUrl = $videoUrl;
            }
        @endphp

        <div class="hero-video-container" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden; z-index: 0;">
            @if ($isYouTube || $isVimeo)
                {{-- YouTube or Vimeo embedded video --}}
                <iframe src="{{ $embedUrl }}" frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    referrerpolicy="strict-origin-when-cross-origin" allowfullscreen loading="lazy"
                    onload="this.style.opacity=1;"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"
                    style="position: absolute; top: 50%; left: 50%; min-width: 100%; min-height: 100%; width: auto; height: auto; z-index: -1000; transform: translateX(-50%) translateY(-50%); background: url('{{ $posterUrl }}') center center; background-size: cover; opacity: 0; transition: opacity 0.5s;">
                </iframe>
                {{-- Fallback for failed video loading --}}
                <div
                    style="display: none; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: url('{{ $posterUrl }}') center center; background-size: cover; z-index: -1000;">
                </div>
            @elseif($videoUrl)
                {{-- Direct video file --}}
                <video autoplay loop muted playsinline
                    style="position: absolute; top: 50%; left: 50%; min-width: 100%; min-height: 100%; width: auto; height: auto; z-index: -1000; transform: translateX(-50%) translateY(-50%); object-fit: cover;"
                    poster="{{ $posterUrl }}">
                    <source src="{{ $videoUrl }}" type="video/mp4">
                </video>
            @else
                {{-- Fallback background image --}}
                <div
                    style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: url('{{ $posterUrl }}') center center; background-size: cover; z-index: -1000;">
                </div>
            @endif

            {{-- Hero content overlay using existing hero section settings --}}
            <div class="hero-overlay"
                style="position: relative; width: 100%; min-height: 100vh; background: rgba(0, 0, 0, 0.4); display: flex; align-items: center; justify-content: center; text-align: center; color: white; z-index: 10;">
                <div class="hero-content" style="max-width: 800px; padding: 2rem;">
                    {{-- Use existing hero section data --}}
                    <div class="dmn-line max-width-full"
                        style="background: #8EE8DF; height: 2px; width: 100px; margin: 0 auto 1rem;"></div>
                    <div class="text-block-85" style="font-size: 1rem; margin-bottom: 1rem; color: #8EE8DF;">
                        {{ $setting->announcement_text ?? 'The Future Of Retail Capital.' }}
                        <a href="{{ $setting->announcement_url ?? '/connect' }}"
                            style="color: #8EE8DF; text-decoration: none;">
                            <strong>{{ $setting->hero_subtitle ?? 'Raise Boldly' }}</strong>
                        </a>
                    </div>
                    <h1 style="font-size: 3.5rem; font-weight: 700; margin-bottom: 1.5rem; line-height: 1.1;">
                        {{ $setting->hero_title ?? 'The Future Of Retail Capital' }}
                    </h1>
                    @if ($setting->hero_cta_text && $setting->hero_cta_url)
                        <a href="{{ $setting->hero_cta_url }}" class="hero-cta-button"
                            style="display: inline-block; background: #f31cb6; color: white; padding: 1rem 2rem; border-radius: 1rem; text-decoration: none; font-weight: 600; font-size: 1.1rem; transition: all 0.3s ease;">
                            {{ $setting->hero_cta_text }}
                        </a>
                    @endif
                </div>
            </div>
        </div>

        @if ($isYouTube)
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const iframe = document.querySelector('.hero-video-container iframe');
                    const fallbackDiv = document.querySelector('.hero-video-container iframe + div');

                    // Set a timeout to check if video loads
                    setTimeout(function() {
                        // If iframe is still not visible (opacity 0), try alternative embed URL
                        if (iframe && iframe.style.opacity === '0') {
                            console.log('Primary YouTube embed failed, trying alternative...');
                            iframe.src = '{{ $altEmbedUrl ?? '' }}';

                            // Give alternative URL time to load
                            setTimeout(function() {
                                if (iframe.style.opacity === '0') {
                                    console.log(
                                        'Alternative YouTube embed failed, showing fallback image...');
                                    iframe.style.display = 'none';
                                    if (fallbackDiv) {
                                        fallbackDiv.style.display = 'block';
                                    }
                                }
                            }, 3000);
                        }
                    }, 5000);

                    // Handle iframe error
                    iframe.addEventListener('error', function() {
                        console.log('YouTube embed error, showing fallback...');
                        this.style.display = 'none';
                        if (fallbackDiv) {
                            fallbackDiv.style.display = 'block';
                        }
                    });
                });
            </script>
        @endif
    </div>
    </header>
    <main magic-video="true" class="main" style="margin-top: 0;">
        <div data-w-id="85d6703c-aa1b-21a4-683e-686ff485134c" class="new-nav-trigger"></div>
        <header id="services" class="n_section_platform_app" style="display:none;">
            <div class="n_padding-global">
                <div class="container-large">
                    <div class="padding-section-small">
                        <div data-delay="4000" data-animation="cross" class="slider is-longer w-slider"
                            data-autoplay="false" data-easing="ease" style="opacity:0" data-hide-arrows="false"
                            data-disable-swipe="false" data-w-id="e4917551-3308-14df-9683-05b8de647123"
                            data-autoplay-limit="0" data-nav-spacing="3" data-duration="500" data-infinite="true"
                            id="w-node-e4917551-3308-14df-9683-05b8de647123-4a773f3e">
                            <div class="w-slider-mask">
                                @if ($setting && $setting->slider_images && is_array($setting->slider_images) && count($setting->slider_images) > 0)
                                    @foreach ($setting->slider_images as $index => $slide)
                                        <div class="slide w-slide">
                                            <div class="w-layout-grid grid-18 is-2-column">
                                                <img src="{{ $slide['image'] ?? 'https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/685561045749461ab86204c2_homepage_phone-02.webp' }}"
                                                    loading="lazy" width="285.5"
                                                    sizes="(max-width: 479px) 100vw, 285.5px"
                                                    alt="{{ $slide['title'] ?? '' }}"
                                                    srcset="{{ $slide['image'] ?? 'https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/685561045749461ab86204c2_homepage_phone-02-p-500.webp 500w, https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/685561045749461ab86204c2_homepage_phone-02.webp 570w' }}"
                                                    class="image-75" />
                                                <div id="w-node-_6baa1412-cc0c-e604-943d-32a5710fea0a-4a773f3e"
                                                    class="n_platform_texts max-width-small">
                                                    <img src="https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/685c3250128602cb8c4f0743_dm-p.svg"
                                                        loading="lazy" alt="" class="platform_logo" />
                                                    <div class="spacer-large"></div>
                                                    <h2 class="n_heading-size-h2 text-color-white max-width-small">
                                                        {{ $slide['title'] ?? 'Funding Ambition. Powering Growth.' }}
                                                    </h2>
                                                    <div class="spacer-small"></div>
                                                    <p>{{ $slide['description'] ?? 'DealMaker is the future of capital raising. We provide an end-to-end platform to raise capital directly from individual investors. Attract investors, process funds and manage investors on one platform.' }}
                                                    </p>
                                                    <div class="spacer-small"></div>
                                                    <div class="n_button_wrapper">
                                                        <a href="{{ $slide['cta_url'] ?? '/connect' }}"
                                                            class="n_button w-button">{{ $slide['cta_text'] ?? 'Start Now' }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <!-- Default slides if no slider_images configured -->
                                    <div class="slide w-slide">
                                        <div class="w-layout-grid grid-18 is-2-column">
                                            <img src="https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/685561045749461ab86204c2_homepage_phone-02.webp"
                                                loading="lazy" width="285.5"
                                                sizes="(max-width: 479px) 100vw, 285.5px" alt=""
                                                srcset="https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/685561045749461ab86204c2_homepage_phone-02-p-500.webp 500w, https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/685561045749461ab86204c2_homepage_phone-02.webp 570w"
                                                class="image-75" />
                                            <div id="w-node-_6baa1412-cc0c-e604-943d-32a5710fea0a-4a773f3e"
                                                class="n_platform_texts max-width-small">
                                                <img src="https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/685c3250128602cb8c4f0743_dm-p.svg"
                                                    loading="lazy" alt="" class="platform_logo" />
                                                <div class="spacer-large"></div>
                                                <h2 class="n_heading-size-h2 text-color-white max-width-small">Funding
                                                    Ambition. Powering Growth.</h2>
                                                <div class="spacer-small"></div>
                                                <p>DealMaker is the future of capital raising. We provide an end-to-end
                                                    platform to raise capital directly from individual investors.
                                                    Attract investors, process funds and manage investors on one
                                                    platform.</p>
                                                <div class="spacer-small"></div>
                                                <div class="n_button_wrapper">
                                                    <a href="/connect" class="n_button w-button">Start Now</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="slide w-slide">
                                        <div class="w-layout-grid grid-18 is-2-column">
                                            <img src="https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/6855610466fede381344c563_homepage_phone-03.webp"
                                                loading="lazy" width="285.5"
                                                sizes="(max-width: 479px) 100vw, 285.5px" alt=""
                                                srcset="https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/6855610466fede381344c563_homepage_phone-03-p-500.webp 500w, https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/6855610466fede381344c563_homepage_phone-03.webp 571w"
                                                class="image-75" />
                                            <div id="w-node-_8cbac0c2-7a31-ab3d-e798-985b404dc05e-4a773f3e"
                                                class="n_platform_texts max-width-small">
                                                <img src="https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/685c3250128602cb8c4f0743_dm-p.svg"
                                                    loading="lazy" alt="" class="platform_logo" />
                                                <div class="spacer-large"></div>
                                                <h2 class="n_heading-size-h2 text-color-white">
                                                    <strong>{{ $setting->slide_2_title ?? 'Raise Boldly.<br />Own Your Future.' }}</strong>
                                                </h2>
                                                <div class="spacer-small"></div>
                                                <p>{{ $setting->slide_2_description ?? 'Unlock the power of retail capital. Raise the capital you need to drive growth while building your brand and community. And unlike venture capital or private equity - you control the terms.' }}
                                                </p>
                                                <div class="spacer-small"></div>
                                                <div class="n_button_wrapper">
                                                    <a href="{{ $setting->slide_2_cta_url ?? '/connect' }}"
                                                        class="n_button w-button">{{ $setting->slide_2_cta_text ?? 'Start Now' }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="slide w-slide">
                                        <div class="w-layout-grid grid-18 is-2-column">
                                            <img src="https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/6855610465b5ca9a46afe153_homepage_phone-04.webp"
                                                loading="lazy" width="285.5"
                                                sizes="(max-width: 479px) 100vw, 285.5px" alt=""
                                                srcset="https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/6855610465b5ca9a46afe153_homepage_phone-04-p-500.webp 500w, https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/6855610465b5ca9a46afe153_homepage_phone-04.webp 571w"
                                                class="image-75" />
                                            <div id="w-node-_7633c9cf-c276-6d08-08ca-0d788b3318c0-4a773f3e"
                                                class="n_platform_texts max-width-small">
                                                <img src="https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/685c3250128602cb8c4f0743_dm-p.svg"
                                                    loading="lazy" alt="" class="platform_logo" />
                                                <div class="spacer-large"></div>
                                                <h2 class="n_heading-size-h2 text-color-white">
                                                    <strong>{{ $setting->slide_3_title ?? 'Real Capital.<br />Retail Experience.' }}</strong>
                                                </h2>
                                                <div class="spacer-small"></div>
                                                <p>{{ $setting->slide_3_description ?? 'Raise up to $75M annually with Reg A offerings. The capital you need - no road shows, no trips to Sand Hill Road, no waiting for a term sheet. Digital capital raising is changing the game.' }}
                                                </p>
                                                <div class="spacer-small"></div>
                                                <div class="n_button_wrapper">
                                                    <a href="{{ $setting->slide_3_cta_url ?? '/connect' }}"
                                                        class="n_button w-button">{{ $setting->slide_3_cta_text ?? 'Start Now' }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="left-arrow w-slider-arrow-left">
                                <div class="w-icon-slider-left"></div>
                            </div>
                            <div class="right-arrow w-slider-arrow-right">
                                <div class="w-icon-slider-right"></div>
                            </div>
                            <div class="slide-nav closer-to-slider w-slider-nav w-round"></div>
                        </div>
                        <div class="w-layout-grid grid-18 is-flex">
                            <h2 id="w-node-e1ef9db3-d16a-2372-20b1-54ec616ba536-4a773f3e"
                                class="n_text_custom max-width-small">
                                {{ $setting->platform_section_title ?? 'Capital Redefined' }}</h2>
                            <div class="n_platform_texts max-width-small mobile-show">
                                <div class="div-block-18"></div>
                                <div class="spacer-medium"></div>
                                <div class="w-layout-hflex flex-block-25">
                                    <p class="n_platform_line">
                                        {{ $setting->platform_section_description ?? 'Capture the power of individual investors with our guide to the new capital stack.' }}
                                    </p><a href="{{ $setting->platform_cta_url ?? '/new-capital-stack' }}"
                                        class="link-block-6 w-inline-block">
                                        <div class="w-layout-vflex flex-block-24 flex-cta">
                                            <div class="n_circle_text">
                                                {{ $setting->platform_cta_text ?? 'Download Now' }}</div>
                                            <div class="n_icon in-circle w-embed"><svg width="auto" height="auto"
                                                    viewBox="0 0 38 38" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M34.2598 18.8799C34.2597 10.3858 27.374 3.5 18.8799 3.5C10.3858 3.50006 3.50006 10.3858 3.5 18.8799C3.5 27.374 10.3858 34.2597 18.8799 34.2598C27.374 34.2598 34.2598 27.374 34.2598 18.8799ZM37.2598 18.8799C37.2598 29.0309 29.0309 37.2598 18.8799 37.2598C8.72894 37.2597 0.5 29.0308 0.5 18.8799C0.500063 8.72898 8.72898 0.500063 18.8799 0.5C29.0308 0.5 37.2597 8.72894 37.2598 18.8799Z"
                                                        fill="currentColor" />
                                                    <path
                                                        d="M16.9915 27.59C16.4062 28.1761 15.4566 28.1762 14.8704 27.591C14.2843 27.0056 14.2832 26.0561 14.8685 25.4699L16.9915 27.59ZM14.8695 11.0792C15.4552 10.4934 16.4048 10.4934 16.9905 11.0792L24.181 18.2687C24.7665 18.8542 24.7661 19.8039 24.181 20.3898L16.9915 27.59L14.8685 25.4699L20.9984 19.3302L14.8695 13.2003C14.2837 12.6145 14.2837 11.665 14.8695 11.0792Z"
                                                        fill="currentColor" />
                                                </svg></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div id="w-node-e1ef9db3-d16a-2372-20b1-54ec616ba538-4a773f3e"
                                class="n_platform_texts max-width-small mobile-hide">
                                <div class="div-block-18"></div>
                                <div class="spacer-medium"></div>
                                <div class="w-layout-hflex flex-block-25">
                                    <p class="n_platform_line">
                                        {{ $setting->platform_section_description ?? 'Capture the power of individual investors with our guide to the new capital stack.' }}
                                    </p><a href="{{ $setting->platform_cta_url ?? '/new-capital-stack' }}"
                                        class="link-block-6 w-inline-block">
                                        <div class="w-layout-vflex flex-block-24 flex-cta">
                                            <div class="n_circle_text">
                                                {{ $setting->platform_cta_text ?? 'Download Now' }}</div>
                                            <div class="n_icon in-circle w-embed"><svg width="auto" height="auto"
                                                    viewBox="0 0 38 38" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M34.2598 18.8799C34.2597 10.3858 27.374 3.5 18.8799 3.5C10.3858 3.50006 3.50006 10.3858 3.5 18.8799C3.5 27.374 10.3858 34.2597 18.8799 34.2598C27.374 34.2598 34.2598 27.374 34.2598 18.8799ZM37.2598 18.8799C37.2598 29.0309 29.0309 37.2598 18.8799 37.2598C8.72894 37.2597 0.5 29.0308 0.5 18.8799C0.500063 8.72898 8.72898 0.500063 18.8799 0.5C29.0308 0.5 37.2597 8.72894 37.2598 18.8799Z"
                                                        fill="currentColor" />
                                                    <path
                                                        d="M16.9915 27.59C16.4062 28.1761 15.4566 28.1762 14.8704 27.591C14.2843 27.0056 14.2832 26.0561 14.8685 25.4699L16.9915 27.59ZM14.8695 11.0792C15.4552 10.4934 16.4048 10.4934 16.9905 11.0792L24.181 18.2687C24.7665 18.8542 24.7661 19.8039 24.181 20.3898L16.9915 27.59L14.8685 25.4699L20.9984 19.3302L14.8695 13.2003C14.2837 12.6145 14.2837 11.665 14.8695 11.0792Z"
                                                        fill="currentColor" />
                                                </svg></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Giant dealmaker logo moved to bottom of video section -->
        <div data-w-id="ce1ae134-d5ae-014c-088a-2cd8272fab23"
            style="display: none; -webkit-transform:translate3d(0, 218px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-moz-transform:translate3d(0, 218px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);-ms-transform:translate3d(0, 218px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0);transform:translate3d(0, 218px, 0) scale3d(1, 1, 1) rotateX(0) rotateY(0) rotateZ(0) skew(0, 0)"
            class="giant-logo w-embed"><svg width="auto" height="auto" viewBox="0 0 1345 237" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip0_8230_71)">
                    <path
                        d="M869.37 158.33V235.07H848.21V159.61C848.21 133.81 836.86 120.39 816.99 120.39C795.06 120.39 781.64 136.9 781.64 163.73V235.06H760.48V159.6C760.48 133.8 748.87 120.38 728.75 120.38C707.08 120.38 693.92 138.44 693.92 164.76V235.06H672.76V102.6H691.08L693.92 120.66C700.88 111.12 711.98 101.05 732.36 101.05C750.68 101.05 766.42 109.31 773.9 126.08C781.9 111.89 796.09 101.05 819.57 101.05C846.92 101.05 869.36 116.79 869.36 158.33H869.37Z"
                        fill="white" />
                    <path d="M182.43 54.41L121.51 0V105.71L60.78 51.18V181.13L121.76 235.56V180.53L182.43 235.07V54.41Z"
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

        <!-- About section placeholder -->
        {{-- <section id="about" style="background-color: {{ $setting->getSectionBackgroundColor('about', '#f8f9fa') }}; padding: 60px 0;">
            <div class="n_padding-global">
                <div class="container-large">
                    <div class="padding-section-large">
                        <div class="text-align-center">
                            <h2 class="n_heading-size-h2">{{ $setting->about_title ?? 'About Our Company' }}</h2>
                            <div class="spacer-medium"></div>
                            <p class="text-color-black max-width-medium">
                                {{ $setting->about_description ?? 'We are dedicated to revolutionizing capital raising through innovative technology solutions.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section> --}}

        
@if ($setting->show_difference_section ?? true)
            <div class="spacer-large" style="display:none;"></div>
            <header id="why-us" class="n_section_capital" style="background-color: {{ $setting->getSectionBackgroundColor('difference_section', '#ffffff') }};">
                <div class="n_padding-global">
                    <div class="container-large">
                        <div class="padding-section-large-4">
                            <div class="w-layout-grid tabs_content">
                                <div id="w-node-_62e3c04a-cb96-1942-ae5a-f611d7b10a0d-4a773f3e"
                                    class="eyebrow text-color-black">
                                    {{ $setting->difference_eyebrow_text ?? 'DealMaker Difference' }}</div>
                                <h2 id="w-node-_9a129288-d4b4-e9d9-03da-7a83b78c9166-4a773f3e"
                                    class="n_text_custom max-width-small is-absolut">
                                    {{ $setting->difference_section_title ?? '#1 in capital raising' }}</h2>
                            </div>
                            <div data-current="Tab 1" data-easing="ease" data-duration-in="300"
                                data-duration-out="100" class="tabs-3 max-width-large w-tabs">
                                <div class="tabs-menu-2 w-tab-menu"><a data-w-tab="Tab 1"
                                        class="tab-link-tab-4 w-inline-block w-tab-link w--current">
                                        <div>Plan</div>
                                    </a><a data-w-tab="Tab 2" class="tab-link-tab-4 w-inline-block w-tab-link">
                                        <div>Raise</div>
                                    </a><a data-w-tab="Tab 3" class="tab-link-tab-4 w-inline-block w-tab-link">
                                        <div>Engage</div>
                                    </a><a data-w-tab="Tab 4" class="tab-link-tab-4 w-inline-block w-tab-link">
                                        <div>Repeat</div>
                                    </a></div>
                                <div class="tabs-content-3 w-tab-content">
                                    <div data-w-tab="Tab 1" class="w-tab-pane w--tab-active">
                                        <div class="w-layout-grid tabs_content is-rev">
                                            <div id="w-node-faa7e19f-d2bb-7a65-a229-60b66d5d1c30-4a773f3e"
                                                class="code-embed-8 w-embed"><svg width="auto" height="auto"
                                                    viewBox="0 0 636 500" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path opacity="0.57"
                                                        d="M41.5901 303.42V260.7H274.1V303.42H302.3V474.39C302.3 487.26 291.82 497.76 278.93 497.76H37.1901C24.2901 497.76 13.8201 487.26 13.8201 474.39V303.42H41.5901Z"
                                                        fill="#B7E4E3" />
                                                    <path opacity="0.57"
                                                        d="M41.5901 196.29V239.01H274.1V196.29H302.3V25.3C302.3 12.42 291.82 1.94 278.93 1.94H37.1901C24.3001 1.94 13.8201 12.42 13.8201 25.3V196.28H41.5901V196.29Z"
                                                        fill="#B7E4E3" />
                                                    <path opacity="0.57"
                                                        d="M188.88 260.93V350.89C188.88 397.22 172.97 401.1 158.06 401.1C141.98 401.1 131.09 395.02 128.1 367.93L177.92 260.93H134.09L127.22 275.68V260.93H76.1799V352.93C76.1799 413.13 106.79 449.07 158.05 449.07C209.31 449.07 239.92 413.13 239.92 352.93V260.93H188.86H188.88Z"
                                                        fill="white" />
                                                    <path opacity="0.57"
                                                        d="M158.07 50.93C106.81 50.93 76.2 86.87 76.2 147.08V239.09H127.24V149.13C127.24 107.33 139.04 98.93 158.08 98.93C170.91 98.93 184.45 101.88 187.98 132.18L138.2 239.09H182.03L188.9 224.34V239.09H239.96V147.08C239.96 86.87 209.35 50.93 158.09 50.93H158.07Z"
                                                        fill="white" />
                                                    <path opacity="0.57" d="M30.27 209.06H1.46997V292.07H30.27V209.06Z"
                                                        fill="#B7E4E3" />
                                                    <path opacity="0.57"
                                                        d="M314.89 209.06H286.09V292.07H314.89V209.06Z"
                                                        fill="#B7E4E3" />
                                                    <path opacity="0.57"
                                                        d="M361.33 303.41V260.69H593.84V303.41H622.04V474.39C622.04 487.26 611.56 497.76 598.67 497.76H356.92C344.04 497.76 333.55 487.26 333.55 474.39V303.41H361.33Z"
                                                        fill="#B7E4E3" />
                                                    <path opacity="0.57"
                                                        d="M361.33 196.27V238.99H593.84V196.27H622.04V25.3C622.04 12.42 611.56 1.92999 598.67 1.92999H356.92C344.04 1.92999 333.55 12.42 333.55 25.3V196.27H361.33Z"
                                                        fill="#B7E4E3" />
                                                    <path opacity="0.57"
                                                        d="M504.88 239.41V49.93H457.05L404.05 110.31V148.14L452.62 148.19V239.41H504.88Z"
                                                        fill="white" />
                                                    <path opacity="0.57" d="M504.56 260.93H452.3V450.42H504.56V260.93Z"
                                                        fill="white" />
                                                    <path opacity="0.57"
                                                        d="M348.54 209.06H319.74V292.07H348.54V209.06Z"
                                                        fill="#B7E4E3" />
                                                    <path opacity="0.57"
                                                        d="M633.15 209.06H604.35V292.07H633.15V209.06Z"
                                                        fill="#B7E4E3" />
                                                </svg></div>
                                            <div class="w-layout-vflex flex-block-22">
                                                <h2 class="n_heading-size-h2">
                                                    <strong>{{ $setting->plan_title ?? 'Personalized Raise Strategy' }}</strong>
                                                </h2>
                                                <div class="spacer-medium"></div>
                                                <p class="text-color-black">{!! $setting->plan_description ??
                                                    'Craft the perfect offering with control over raise amount, valuation, voting rights, and beyond. With us, your strategy takes center stage.' !!}</p>
                                                <div class="spacer-medium"></div>
                                                <div class="spacer-medium"></div>
                                                <div class="n_button_wrapper"><a
                                                        href="{{ $setting->plan_button_url ?? '#' }}"
                                                        class="n_button is-darker w-button">{{ $setting->plan_button_text }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div data-w-tab="Tab 2" class="w-tab-pane">
                                        <div class="w-layout-grid tabs_content">
                                            <div id="w-node-_45a96ddd-17d8-e8b3-9659-28669d866e35-4a773f3e"
                                                class="code-embed-8 w-embed"><svg width="auto" height="auto"
                                                    viewBox="0 0 636 500" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path opacity="0.57"
                                                        d="M41.5901 303.42V260.7H274.1V303.42H302.3V474.39C302.3 487.26 291.82 497.76 278.93 497.76H37.1901C24.2901 497.76 13.8201 487.26 13.8201 474.39V303.42H41.5901Z"
                                                        fill="#B7E4E3" />
                                                    <path opacity="0.57"
                                                        d="M41.5901 196.29V239.01H274.1V196.29H302.3V25.3C302.3 12.42 291.82 1.94 278.93 1.94H37.1901C24.3001 1.94 13.8201 12.42 13.8201 25.3V196.28H41.5901V196.29Z"
                                                        fill="#B7E4E3" />
                                                    <path opacity="0.57"
                                                        d="M188.88 260.93V350.89C188.88 397.22 172.97 401.1 158.06 401.1C141.98 401.1 131.09 395.02 128.1 367.93L177.92 260.93H134.09L127.22 275.68V260.93H76.1799V352.93C76.1799 413.13 106.79 449.07 158.05 449.07C209.31 449.07 239.92 413.13 239.92 352.93V260.93H188.86H188.88Z"
                                                        fill="white" />
                                                    <path opacity="0.57"
                                                        d="M158.07 50.93C106.81 50.93 76.2 86.87 76.2 147.08V239.09H127.24V149.13C127.24 107.33 139.04 98.93 158.08 98.93C170.91 98.93 184.45 101.88 187.98 132.18L138.2 239.09H182.03L188.9 224.34V239.09H239.96V147.08C239.96 86.87 209.35 50.93 158.09 50.93H158.07Z"
                                                        fill="white" />
                                                    <path opacity="0.57" d="M30.27 209.06H1.46997V292.07H30.27V209.06Z"
                                                        fill="#B7E4E3" />
                                                    <path opacity="0.57"
                                                        d="M314.89 209.06H286.09V292.07H314.89V209.06Z"
                                                        fill="#B7E4E3" />
                                                    <path opacity="0.57"
                                                        d="M361.33 303.41V260.69H593.84V303.41H622.04V474.39C622.04 487.26 611.56 497.76 598.67 497.76H356.92C344.04 497.76 333.55 487.26 333.55 474.39V303.41H361.33Z"
                                                        fill="#B7E4E3" />
                                                    <path opacity="0.57"
                                                        d="M361.33 196.27V238.99H593.84V196.27H622.04V25.3C622.04 12.42 611.56 1.92999 598.67 1.92999H356.92C344.04 1.92999 333.55 12.42 333.55 25.3V196.27H361.33Z"
                                                        fill="#B7E4E3" />
                                                    <path opacity="0.57"
                                                        d="M348.54 209.06H319.74V292.07H348.54V209.06Z"
                                                        fill="#B7E4E3" />
                                                    <path opacity="0.57"
                                                        d="M633.15 209.06H604.35V292.07H633.15V209.06Z"
                                                        fill="#B7E4E3" />
                                                    <path opacity="0.57"
                                                        d="M443.05 161.27C443.05 105.12 461.16 100.41 478.14 100.41C498.24 100.41 508.44 108.17 508.44 150.54C508.44 176.93 502.98 197.69 489.59 222.23L480.68 238.75H539.84C553.74 210.95 562.67 184.31 562.67 150.54C562.67 82.83 535.02 49.91 478.14 49.91C437.88 49.91 389.89 69.24 389.89 161.27V169.61H443.04V161.27H443.05Z"
                                                        fill="white" />
                                                    <path opacity="0.57"
                                                        d="M528.28 260.77H468.79L389.9 407.02V451.82H559.46V400.79H453.45L528.28 260.77Z"
                                                        fill="white" />
                                                </svg></div>
                                            <div class="w-layout-vflex flex-block-22">
                                                <h2 class="n_heading-size-h2">
                                                    <strong>{{ $setting->raise_title ?? 'End-To-End<br/>Raise Platform' }}</strong>
                                                </h2>
                                                <div class="spacer-medium"></div>
                                                <p class="text-color-black">{!! $setting->raise_description ??
                                                    'Successful capital raises start with the right strategy. DealMaker works with you to plan every aspect of your raise strategy - whether it&#x27;s your first retail raise or you’re a multiple raise professional.' !!}</p>
                                                <div class="spacer-medium"></div>
                                                <div class="spacer-medium"></div>
                                                <div class="n_button_wrapper"><a
                                                        href="{{ $setting->raise_button_url ?? '/connect' }}"
                                                        class="n_button is-darker w-button">{{ $setting->raise_button_text ?? 'Learn More' }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div data-w-tab="Tab 3" class="w-tab-pane">
                                        <div class="w-layout-grid tabs_content">
                                            <div id="w-node-f21d570b-88d2-231a-36f6-ca273dd21e94-4a773f3e"
                                                class="code-embed-8 w-embed"><svg width="auto" height="auto"
                                                    viewBox="0 0 636 500" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path opacity="0.57"
                                                        d="M41.5901 303.42V260.7H274.1V303.42H302.3V474.39C302.3 487.26 291.82 497.76 278.93 497.76H37.1901C24.2901 497.76 13.8201 487.26 13.8201 474.39V303.42H41.5901Z"
                                                        fill="#B7E4E3" />
                                                    <path opacity="0.57"
                                                        d="M41.5901 196.29V239.01H274.1V196.29H302.3V25.2999C302.3 12.4199 291.82 1.93994 278.93 1.93994H37.1901C24.3001 1.93994 13.8201 12.4199 13.8201 25.2999V196.28H41.5901V196.29Z"
                                                        fill="#B7E4E3" />
                                                    <path opacity="0.57"
                                                        d="M188.88 260.93V350.89C188.88 397.22 172.97 401.1 158.06 401.1C141.98 401.1 131.09 395.02 128.1 367.93L177.92 260.93H134.09L127.22 275.68V260.93H76.1799V352.93C76.1799 413.13 106.79 449.07 158.05 449.07C209.31 449.07 239.92 413.13 239.92 352.93V260.93H188.86H188.88Z"
                                                        fill="white" />
                                                    <path opacity="0.57"
                                                        d="M158.07 50.9301C106.81 50.9301 76.2 86.8701 76.2 147.08V239.09H127.24V149.13C127.24 107.33 139.04 98.9301 158.08 98.9301C170.91 98.9301 184.45 101.88 187.98 132.18L138.2 239.09H182.03L188.9 224.34V239.09H239.96V147.08C239.96 86.8701 209.35 50.9301 158.09 50.9301H158.07Z"
                                                        fill="white" />
                                                    <path opacity="0.57" d="M30.27 209.06H1.46997V292.07H30.27V209.06Z"
                                                        fill="#B7E4E3" />
                                                    <path opacity="0.57"
                                                        d="M314.89 209.06H286.09V292.07H314.89V209.06Z"
                                                        fill="#B7E4E3" />
                                                    <path opacity="0.57"
                                                        d="M361.33 303.41V260.69H593.84V303.41H622.04V474.39C622.04 487.26 611.56 497.76 598.67 497.76H356.92C344.04 497.76 333.55 487.26 333.55 474.39V303.41H361.33Z"
                                                        fill="#B7E4E3" />
                                                    <path opacity="0.57"
                                                        d="M361.33 196.27V238.99H593.84V196.27H622.04V25.3001C622.04 12.4201 611.56 1.93005 598.67 1.93005H356.92C344.04 1.93005 333.55 12.4201 333.55 25.3001V196.27H361.33Z"
                                                        fill="#B7E4E3" />
                                                    <path opacity="0.57"
                                                        d="M348.54 209.06H319.74V292.07H348.54V209.06Z"
                                                        fill="#B7E4E3" />
                                                    <path opacity="0.57"
                                                        d="M633.15 209.06H604.35V292.07H633.15V209.06Z"
                                                        fill="#B7E4E3" />
                                                    <path opacity="0.57"
                                                        d="M450.18 260.77V273.15H467.11C498.19 273.15 509.75 287.9 509.75 327.61V347.98C509.75 395.29 496.48 404.56 473.55 404.56C455.52 404.56 436.28 400.19 436.28 347.98V334.82H382.57V351.19C382.57 437.02 432.05 455.03 473.55 455.03C515.05 455.03 563.45 437.2 563.45 352.26V324.38C563.45 296.86 557.41 275.63 545.58 260.78H450.18V260.77Z"
                                                        fill="white" />
                                                    <path opacity="0.57"
                                                        d="M439.52 152.15C439.52 107.95 450.8 99.86 473.02 99.86C491.7 99.86 504.92 103.6 504.92 152.15V171.45C504.92 208.32 494.31 222.67 467.1 222.67H450.17V238.75H536.38C550.98 224.09 558.62 202.09 558.62 174.16V148.41C558.62 82.7 529.82 49.4 473.01 49.4C433.23 49.4 385.79 66.95 385.79 150.55V168.01H439.51V152.16L439.52 152.15Z"
                                                        fill="white" />
                                                </svg></div>
                                            <div class="w-layout-vflex flex-block-22">
                                                <h2 class="n_heading-size-h2">
                                                    <strong>{{ $setting->engage_title ?? 'Community <br/>Engagement' }}</strong>
                                                </h2>
                                                <div class="spacer-medium"></div>
                                                <p class="text-color-black">{!! $setting->engage_description ??
                                                    'Engage your community with targeted marketing campaigns, investor updates, and seamless communication tools. Build lasting relationships that drive growth and success.' !!}
                                                </p>
                                                <div class="spacer-medium"></div>
                                                <div class="spacer-medium"></div>
                                                <div class="n_button_wrapper"><a
                                                        href="{{ $setting->engage_button_url ?? '/connect' }}"
                                                        class="n_button is-darker w-button">{{ $setting->engage_button_text ?? 'Learn More' }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div data-w-tab="Tab 4" class="w-tab-pane">
                                        <div class="w-layout-grid tabs_content">
                                            <div id="w-node-_79e54765-f396-6d24-81ac-961715d16b58-4a773f3e"
                                                class="code-embed-8 w-embed"><svg width="auto" height="auto"
                                                    viewBox="0 0 636 500" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path opacity="0.57"
                                                        d="M361.8 303.57V260.85H594.31V303.57H622.51V474.55C622.51 487.42 612.03 497.92 599.14 497.92H357.39C344.51 497.92 334.02 487.42 334.02 474.55V303.57H361.8Z"
                                                        fill="#B7E4E3" />
                                                    <path opacity="0.57"
                                                        d="M361.8 196.43V239.15H594.31V196.43H622.51V25.4601C622.51 12.5801 612.03 2.09009 599.14 2.09009H357.39C344.51 2.09009 334.02 12.5801 334.02 25.4601V196.43H361.8Z"
                                                        fill="#B7E4E3" />
                                                    <path opacity="0.57"
                                                        d="M349.01 209.21H320.21V292.22H349.01V209.21Z"
                                                        fill="#B7E4E3" />
                                                    <path opacity="0.57"
                                                        d="M633.62 209.21H604.82V292.22H633.62V209.21Z"
                                                        fill="#B7E4E3" />
                                                    <path opacity="0.57"
                                                        d="M546.3 340.72V260.77H493.14V340.72H443.29L469.98 260.77H414.96L385.83 348.15V391.19H493.14V451.82H546.3V391.19H572.04V340.72H546.3Z"
                                                        fill="white" />
                                                    <g opacity="0.57">
                                                        <path
                                                            d="M477.34 239.75L539.32 54.1399H484.2L422.31 239.75H477.34Z"
                                                            fill="white" />
                                                        <path d="M546.3 208.82H493.14V239.74H546.3V208.82Z"
                                                            fill="white" />
                                                    </g>
                                                    <path opacity="0.57"
                                                        d="M41.5901 303.42V260.7H274.1V303.42H302.3V474.39C302.3 487.26 291.82 497.76 278.93 497.76H37.1901C24.2901 497.76 13.8201 487.26 13.8201 474.39V303.42H41.5901Z"
                                                        fill="#B7E4E3" />
                                                    <path opacity="0.57"
                                                        d="M41.5901 196.29V239.01H274.1V196.29H302.3V25.2999C302.3 12.4199 291.82 1.93994 278.93 1.93994H37.1901C24.3001 1.93994 13.8201 12.4199 13.8201 25.2999V196.28H41.5901V196.29Z"
                                                        fill="#B7E4E3" />
                                                    <path opacity="0.57"
                                                        d="M188.88 260.93V350.89C188.88 397.22 172.97 401.1 158.06 401.1C141.98 401.1 131.09 395.02 128.1 367.93L177.92 260.93H134.09L127.22 275.68V260.93H76.1799V352.93C76.1799 413.13 106.79 449.07 158.05 449.07C209.31 449.07 239.92 413.13 239.92 352.93V260.93H188.86H188.88Z"
                                                        fill="white" />
                                                    <path opacity="0.57"
                                                        d="M158.07 50.9299C106.81 50.9299 76.2 86.8699 76.2 147.08V239.09H127.24V149.13C127.24 107.33 139.04 98.9299 158.08 98.9299C170.91 98.9299 184.45 101.88 187.98 132.18L138.2 239.09H182.03L188.9 224.34V239.09H239.96V147.08C239.96 86.8699 209.35 50.9299 158.09 50.9299H158.07Z"
                                                        fill="white" />
                                                    <path opacity="0.57" d="M30.27 209.06H1.46997V292.07H30.27V209.06Z"
                                                        fill="#B7E4E3" />
                                                    <path opacity="0.57"
                                                        d="M314.89 209.06H286.09V292.07H314.89V209.06Z"
                                                        fill="#B7E4E3" />
                                                </svg></div>
                                            <div class="w-layout-vflex flex-block-22">
                                                <h2 class="n_heading-size-h2">
                                                    <strong>{{ $setting->repeat_title ?? 'Capitalize On<br/>Multiple Raises' }}</strong>
                                                </h2>
                                                <div class="spacer-medium"></div>
                                                <p class="text-color-black">{!! $setting->repeat_description ??
                                                    'With DealMaker, every raise builds momentum for the next. Leverage your growing investor base and streamline future raises with our repeatable, efficient processes.' !!}
                                                </p>
                                                <div class="spacer-medium"></div>
                                                <div class="n_button_wrapper"><a
                                                        href="{{ $setting->repeat_button_url ?? '/connect' }}"
                                                        class="n_button is-darker w-button">{{ $setting->repeat_button_text ?? 'Learn More' }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
        @endif

        @if ($setting->show_client_logos ?? true)
            <!-- Client logos section moved to bottom of video section -->
            <section id="partners" class="n_section_logos" style="background-color: {{ $setting->getSectionBackgroundColor('client_logos', '#ffffff') }}; background-image: linear-gradient({{ $setting->getSectionBackgroundColor('client_logos', '#ffffff') }}, #000)">
                <div class="n_padding-global">
                    <div class="container-large">
                        <div class="w-layout-grid grid-17">
                            @foreach ($setting->client_logos as $item)
                                <img src="{{ $item['image'] }}"
                                    loading="lazy" width="105.5" id="w-node-_32bbbadd-6d60-ddee-c912-389243844261-4a773f3e"
                                    alt="{{ $item['name'] }}"
                                    srcset="{{ $item['image'] }}"
                                    sizes="105.5px" class="logo-news" />      
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
        @endif
        
        

        
        <header id="testimonials" class="n_section_testimonial" style="background-color: {{ $setting->getSectionBackgroundColor('testimonials', '#f8f9fa') }};">
            <div class="n_padding-global">
                <div class="container-large">
                    <div swp-enabled="true" swp-m-enabled="true" class="swiper_container" swp-autoplay="false"
                        swp-loop="false" swp-m-space="30" swp-m-loop="true" swp-space="45" swp-s-enabled="true"
                        swp-s-space="10" swp-s-centered="false" swp-slide-count="1" swp-m-slide-count="1"
                        swp-centered="false" swp-m-centered="false" swp-s-slide-count="1" swp-s-loop="true">
                        <div class="swiper-wrapper is-testimonial" style="background-color: {{ $setting->getSectionBackgroundColor('testimonials', '#f8f9fa') }};">
                            @if ($setting->testimonials && count($setting->testimonials) > 0)
                                @foreach ($setting->testimonials as $testimonial)
                                    <div class="swiper-slide is-testimonial" style="background-color: {{ $setting->getSectionBackgroundColor('testimonials', '#f8f9fa') }};">
                                        <div class="code-embed-9 w-embed"><svg width="auto" height="auto"
                                                viewBox="0 0 651 291" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <g clip-path="url(#clip0_8239_122)">
                                                    <g opacity="0.45">
                                                        <path
                                                            d="M127.66 70.48V290.76H0V163.1L127.66 70.48ZM302.87 70.48V290.76H175.21V163.1L302.87 70.48Z"
                                                            fill="white" />
                                                        <path
                                                            d="M347.84 220.28V0H475.5V127.66L347.84 220.28ZM523.05 220.28V0H650.71V127.66L523.05 220.28Z"
                                                            fill="white" />
                                                    </g>
                                                </g>
                                                <defs>
                                                    <clipPath id="clip0_8239_122">
                                                        <rect width="650.71" height="290.76" fill="white" />
                                                    </clipPath>
                                                </defs>
                                            </svg></div>
                                        <div class="n_testimonial-texts">
                                            <div class="n_testimonial-text">
                                                &quot;{{ $testimonial['quote'] ?? 'Testimonial quote here' }}&quot;
                                            </div>
                                            <div class="w-layout-vflex flex-block-23">
                                                @if (isset($testimonial['image']) && $testimonial['image'])
                                                    <img src="{{ strpos($testimonial['image'], 'http') === 0 ? $testimonial['image'] : asset($testimonial['image']) }}"
                                                        loading="lazy" width="109"
                                                        alt="{{ $testimonial['name'] ?? 'Client' }}"
                                                        class="n_testimonial-png" />
                                                @endif
                                                <div style="color: {{ $setting->button_hover_color ?? '#d1179a' }}">{{ strtoupper($testimonial['name'] ?? 'CLIENT NAME') }}</div>
                                                <div class="text-color-pink text-style-allcaps">
                                                    {{ strtoupper($testimonial['company'] ?? 'COMPANY') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <!-- Default testimonials when none are configured -->
                                <div class="swiper-slide is-testimonial">
                                    <div class="code-embed-9 w-embed"><svg width="auto" height="auto"
                                            viewBox="0 0 651 291" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <g clip-path="url(#clip0_8239_122)">
                                                <g opacity="0.45">
                                                    <path
                                                        d="M127.66 70.48V290.76H0V163.1L127.66 70.48ZM302.87 70.48V290.76H175.21V163.1L302.87 70.48Z"
                                                        fill="white" />
                                                    <path
                                                        d="M347.84 220.28V0H475.5V127.66L347.84 220.28ZM523.05 220.28V0H650.71V127.66L523.05 220.28Z"
                                                        fill="white" />
                                                </g>
                                            </g>
                                            <defs>
                                                <clipPath id="clip0_8239_122">
                                                    <rect width="650.71" height="290.76" fill="white" />
                                                </clipPath>
                                            </defs>
                                        </svg></div>
                                    <div class="n_testimonial-texts">
                                        <div class="n_testimonial-text">&quot;DealMaker provides very stable and
                                            efficient
                                            technology that our venture studio can rely on. It&#x27;s error-free and
                                            seamless.&quot;</div>
                                        <div class="w-layout-vflex flex-block-23"><img
                                                src="https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/685d8dbff75155fa8d89578e_testimonial__1.png"
                                                loading="lazy" width="109" alt=""
                                                class="n_testimonial-png" />
                                            <div>Kevin Morris</div>
                                            <div class="text-color-pink text-style-allcaps">Atlas RD</div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="swiper_elements">
                            <div class="swiper_navigation is-full"><button aria-label="Previous slide"
                                    class="swiper-btn swiper_btn_prev is-abs">
                                    <div class="icon-embed-xxsmall-3 w-embed"><svg width="100%" height="100%"
                                            viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M3.31066 8.75001L9.03033 14.4697L7.96967 15.5303L0.439339 8.00001L7.96967 0.469676L9.03033 1.53034L3.31066 7.25001L15.5 7.25L15.5 8.75L3.31066 8.75001Z"
                                                fill="currentColor" />
                                        </svg></div>
                                </button><button aria-label="Next slide" class="swiper-btn swiper_btn_next abs">
                                    <div class="icon-embed-xxsmall-3 w-embed"><svg width="100%" height="100%"
                                            viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M12.6893 7.25L6.96967 1.53033L8.03033 0.469666L15.5607 8L8.03033 15.5303L6.96967 14.4697L12.6893 8.75H0.5V7.25H12.6893Z"
                                                fill="currentColor" />
                                        </svg></div>
                                </button></div>
                            <div class="swiper_pagination align-center"></div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <div class="iframe-embed">
            <div class="padding-global">
                <div class="container-large"></div>
            </div>
        </div>
        @if ($setting->show_case_studies ?? true)
            <div class="spacer-large menu-icon1_line-middle d-none" style="display: none;"></div>
            <section id="case-studies" class="section_casestudies" style="background-color: {{ $setting->getSectionBackgroundColor('case_studies', '#f8f9fa') }};">
                <div class="w-layout-grid grid-16">
                    @if (is_array($setting->case_studies) && count($setting->case_studies) > 0)
                        @foreach ($setting->case_studies as $index => $case_study)
                            <div id="w-node-_6e3e58cf-7d87-bdef-4976-78bb8065337{{ sprintf('%x', $index + 1) }}-4a773f3e"
                                class="casestudies_boxes {{ $index === 0 ? 'is-full' : 'is-smaller' }}">
                                <div class="casestudies_text-wrapper bottom-aligned">
                                    <div>
                                        <div class="rl_header44_dmn-flex">
                                            <a href="{{ $case_study['learn_more_url'] ?? '#' }}"
                                                class="casestudies_logo for-rises w-inline-block">
                                                <img src="{{ $case_study['logo'] ?? 'https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/6855710fa0a2d2ed60bd3663_energyx_logo_e7aea357.png' }}"
                                                    loading="lazy" alt="{{ $case_study['name'] ?? 'Case Study' }}"
                                                    class="casestudies_image-size" />
                                            </a>
                                            <div class="spacer-medium"></div>
                                            <div class="text-size-small">
                                                {{ $case_study['description'] ?? 'Company description.' }}</div>
                                            <div class="spacer-medium"></div>
                                            @if ($index === 0)
                                                <div class="spacer-medium"></div>
                                            @endif
                                            <div class="rl_header44_number-wrapper">
                                                <div class="rl_header44_dmn-flex space">
                                                    <div class="dmn-line is-full"></div>
                                                    <div class="n_text-size-tiny">
                                                        {{ $setting->case_study_capital_raised_label ?? 'Capital Raised' }}
                                                    </div>
                                                    <div class="w-layout-hflex">
                                                        <div class="n_large-numbers text-color-white">$</div>
                                                        <div fs-numbercount-threshold="0"
                                                            fs-numbercount-element="number" fs-numbercount-start="1"
                                                            fs-numbercount-end="{{ $case_study['capital_raised'] ?? '0' }}"
                                                            class="n_large-numbers text-color-white">
                                                            {{ $case_study['capital_raised'] ?? '0' }}</div>
                                                        <div class="n_large-numbers text-color-white">M+</div>
                                                    </div>
                                                </div>
                                                <div class="rl_header44_dmn-flex space">
                                                    <div class="dmn-line is-full"></div>
                                                    <div class="n_text-size-tiny">
                                                        {{ $setting->case_study_investors_label ?? 'Investors' }}</div>
                                                    <div class="w-layout-hflex">
                                                        <div fs-numbercount-threshold="0"
                                                            fs-numbercount-element="number" fs-numbercount-start="1"
                                                            fs-numbercount-end="{{ $case_study['investors'] ?? '0' }}"
                                                            class="n_large-numbers text-color-white">
                                                            {{ $case_study['investors'] ?? '0' }}</div>
                                                        <div class="n_large-numbers text-color-white">K+</div>
                                                    </div>
                                                </div>
                                            </div>
                                            @if ($index === 1)
                                                <div class="spacer-medium"></div>
                                            @endif
                                            <div class="button-wrapper-new margin-top margin-small">
                                                <a data-w-id="ecd82eb6-55e6-b64f-6fe7-4b8c58107d5{{ $index + 4 }}"
                                                    href="{{ $case_study['learn_more_url'] ?? '#' }}"
                                                    class="n_button is-small w-inline-block">
                                                    <div>{{ $setting->case_study_learn_more_text ?? 'Learn More' }}
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="casestudies_image">
                                    <img src="{{ $case_study['image'] ?? 'https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/685561057298b4672c414ad9_section%203-01.webp' }}"
                                        loading="lazy" sizes="(max-width: 852px) 100vw, 852px"
                                        alt="{{ $case_study['name'] ?? 'Case Study' }}"
                                        class="casestudies_image-inside" />
                                    <div class="n_video_overlay no-radius">
                                        <div class="n-play-icon-wrapper"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <!-- Fallback to default case studies if none configured -->
                        <div id="w-node-_6e3e58cf-7d87-bdef-4976-78bb8065337f-4a773f3e"
                            class="casestudies_boxes is-full">
                            <div class="casestudies_text-wrapper bottom-aligned">
                                <div>
                                    <div class="rl_header44_dmn-flex">
                                        <a href="#" class="casestudies_logo for-rises w-inline-block">
                                            <img src="https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/6855710fa0a2d2ed60bd3663_energyx_logo_e7aea357.png"
                                                loading="lazy" alt="" class="casestudies_image-size" />
                                        </a>
                                        <div class="spacer-medium"></div>
                                        <div class="text-size-small">The Lithium Industry Transformed.</div>
                                        <div class="spacer-medium"></div>
                                        <div class="spacer-medium"></div>
                                        <div class="rl_header44_number-wrapper">
                                            <div class="rl_header44_dmn-flex space">
                                                <div class="dmn-line is-full"></div>
                                                <div class="n_text-size-tiny">Capital Raised</div>
                                                <div class="w-layout-hflex">
                                                    <div class="n_large-numbers text-color-white">$</div>
                                                    <div fs-numbercount-threshold="0" fs-numbercount-element="number"
                                                        fs-numbercount-start="1" fs-numbercount-end="88"
                                                        class="n_large-numbers text-color-white">31</div>
                                                    <div class="n_large-numbers text-color-white">M+</div>
                                                </div>
                                            </div>
                                            <div class="rl_header44_dmn-flex space">
                                                <div class="dmn-line is-full"></div>
                                                <div class="n_text-size-tiny">Investors</div>
                                                <div class="w-layout-hflex">
                                                    <div fs-numbercount-threshold="0" fs-numbercount-element="number"
                                                        fs-numbercount-start="1" fs-numbercount-end="31"
                                                        class="n_large-numbers text-color-white">13</div>
                                                    <div class="n_large-numbers text-color-white">K+</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="button-wrapper-new margin-top margin-small">
                                            <a data-w-id="ecd82eb6-55e6-b64f-6fe7-4b8c58107d54"
                                                href="/content/energyx-case-study"
                                                class="n_button is-small w-inline-block">
                                                <div>Learn More</div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="casestudies_image">
                                <img src="https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/685561057298b4672c414ad9_section%203-01.webp"
                                    loading="lazy" sizes="(max-width: 852px) 100vw, 852px" alt=""
                                    class="casestudies_image-inside" />
                                <div class="n_video_overlay no-radius">
                                    <div class="n-play-icon-wrapper"></div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </section>
        @endif
        <header id="solutions" class="solutions-section" style="background-color: {{ $setting->getSectionBackgroundColor('capital_revolutionized', '#ffffff') }}; padding: 80px 0;">
            <div class="n_padding-global">
                <div class="container-large">
                    <div class="text-align-center" style="margin-bottom: 60px;">
                        <h2 class="n_heading-size-h2 text-color-black">
                            {{ $setting->capital_revolutionized_title ?? 'Capital raising, revolutionized' }}</h2>
                        <div class="spacer-medium"></div>
                        <p class="text-color-black max-width-medium" style="margin: 0 auto;">
                            {{ $setting->capital_revolutionized_description ?? 'Craft the perfect offering with control over raise amount, valuation, voting rights, and beyond. With us, your strategy takes center stage.' }}
                        </p>
                    </div>
                    
                    <!-- Statistics Metric Cards Container -->
                    <div class="stats-container" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 40px; max-width: 1400px; margin: 0 auto;">
                        
                        <!-- Reg CF Metric Card -->
                        <div class="statistics-metric-card" style="background: {{ $setting->getRegulationColor('reg_cf', 'bg_color', '#1F2937') }}; 
                                            padding: 3rem 2rem; 
                                            border-radius: 12px; 
                                            text-align: center; 
                                            max-width: 400px; 
                                            margin: 0 auto;
                                            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
                                            transition: all 0.3s ease;">
                                    
                                    <div class="metric-number" style="font-size: 4rem; 
                                                font-weight: 900; 
                                                color: {{ $setting->getRegulationColor('reg_cf', 'bold_text_color', '#14B8A6') }}; 
                                                line-height: 1; 
                                                margin-bottom: 1.5rem; 
                                                font-family: 'Outfit', -apple-system, BlinkMacSystemFont, sans-serif;">
                                        {{ $setting->reg_cf_title ?? 'CF' }}
                                    </div>
                                    
                                    <div class="metric-description" style="font-size: 1.25rem; 
                                                color: {{ $setting->getRegulationColor('reg_cf', 'text_color', '#FFFFFF') }}; 
                                                line-height: 1.5; 
                                                font-weight: 400; 
                                                margin: 0; 
                                                font-family: 'Outfit', -apple-system, BlinkMacSystemFont, sans-serif;">
                                        {{ $setting->reg_cf_subtitle ?? 'Crowdfunding regulation for public investment' }}
                                    </div>
                        </div>

                        <!-- Reg A Metric Card -->
                        <div class="statistics-metric-card" style="background: {{ $setting->getRegulationColor('reg_a', 'bg_color', '#1F2937') }}; 
                                            padding: 3rem 2rem; 
                                            border-radius: 12px; 
                                            text-align: center; 
                                            max-width: 400px; 
                                            margin: 0 auto;
                                            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
                                            transition: all 0.3s ease;">
                                    
                                    <div class="metric-number" style="font-size: 4rem; 
                                                font-weight: 900; 
                                                color: {{ $setting->getRegulationColor('reg_a', 'bold_text_color', '#14B8A6') }}; 
                                                line-height: 1; 
                                                margin-bottom: 1.5rem; 
                                                font-family: 'Outfit', -apple-system, BlinkMacSystemFont, sans-serif;">
                                        {{ $setting->reg_a_title ?? 'A+' }}
                                    </div>
                                    
                                    <div class="metric-description" style="font-size: 1.25rem; 
                                                color: {{ $setting->getRegulationColor('reg_a', 'text_color', '#FFFFFF') }}; 
                                                line-height: 1.5; 
                                                font-weight: 400; 
                                                margin: 0; 
                                                font-family: 'Outfit', -apple-system, BlinkMacSystemFont, sans-serif;">
                                        {{ $setting->reg_a_subtitle ?? 'Mini-IPO regulation for larger offerings' }}
                                    </div>
                        </div>

                        <!-- Reg D Metric Card -->
                        <div class="statistics-metric-card" style="background: {{ $setting->getRegulationColor('reg_d', 'bg_color', '#1F2937') }}; 
                                            padding: 3rem 2rem; 
                                            border-radius: 12px; 
                                            text-align: center; 
                                            max-width: 400px; 
                                            margin: 0 auto;
                                            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
                                            transition: all 0.3s ease;">
                                    
                                    <div class="metric-number" style="font-size: 4rem; 
                                                font-weight: 900; 
                                                color: {{ $setting->getRegulationColor('reg_d', 'bold_text_color', '#14B8A6') }}; 
                                                line-height: 1; 
                                                margin-bottom: 1.5rem; 
                                                font-family: 'Outfit', -apple-system, BlinkMacSystemFont, sans-serif;">
                                        {{ $setting->reg_d_title ?? '506' }}
                                    </div>
                                    
                                    <div class="metric-description" style="font-size: 1.25rem; 
                                                color: {{ $setting->getRegulationColor('reg_d', 'text_color', '#FFFFFF') }}; 
                                                line-height: 1.5; 
                                                font-weight: 400; 
                                                margin: 0; 
                                                font-family: 'Outfit', -apple-system, BlinkMacSystemFont, sans-serif;">
                                        {{ $setting->reg_d_subtitle ?? 'Private placement for accredited investors' }}
                                    </div>
                        </div>
                    </div>

                    <!-- Add responsive styles for statistics metric cards -->
                    <style>
                        @media (max-width: 1200px) {
                            .stats-container {
                                grid-template-columns: repeat(2, 1fr) !important;
                                gap: 30px !important;
                            }
                        }
                        @media (max-width: 768px) {
                            .stats-container {
                                grid-template-columns: 1fr !important;
                                gap: 30px !important;
                            }
                            .statistics-metric-card {
                                padding: 2rem 1.5rem !important;
                            }
                            .metric-number {
                                font-size: 3rem !important;
                            }
                            .metric-description {
                                font-size: 0.9rem !important;
                            }
                        }
                        
                        @media (max-width: 480px) {
                            .statistics-metric-card {
                                padding: 1.5rem 1rem !important;
                            }
                            .metric-number {
                                font-size: 2.5rem !important;
                            }
                        }
                        
                        .statistics-metric-card:hover {
                            transform: translateY(-5px);
                            box-shadow: 0 20px 40px rgba(0,0,0,0.15) !important;
                        }
                    </style>

                    <!-- Statistics Metric Responsive Styles -->
                    <style>
                        .statistics-metric-card:hover {
                            transform: translateY(-5px);
                            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
                        }
                        
                        @media (max-width: 768px) {
                            .statistics-metric-card {
                                padding: 2rem 1.5rem !important;
                                max-width: 350px !important;
                            }
                            
                            .metric-number {
                                font-size: 3rem !important;
                            }
                            
                            .metric-description {
                                font-size: 1.1rem !important;
                            }
                        }
                        
                        @media (max-width: 480px) {
                            .statistics-metric-card {
                                padding: 1.5rem 1rem !important;
                                max-width: 300px !important;
                            }
                            
                            .metric-number {
                                font-size: 2.5rem !important;
                            }
                            
                            .metric-description {
                                font-size: 1rem !important;
                            }
                        }
                    </style>
                </div>
            </div>
        </header>
        <header id="get-started" class="n_final-section _2" style="background-color: {{ $setting->getSectionBackgroundColor('final_cta', '#000000') }};">
            <div data-w-id="16f9ff2b-4fe9-b9b5-8b71-e8b50186f420" class="n_parallax-section _2"><img
                    src="{{ $setting->final_cta_growth_image ? asset($setting->final_cta_growth_image) : 'https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/6859b193cf00b21417f73cd1_growth.png' }}"
                    loading="lazy" sizes="100vw"
                    srcset=" {{  $setting->final_cta_growth_image ? asset($setting->final_cta_growth_image) : 'https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/6859b193cf00b21417f73cd1_growth.png'  }}"
                    alt="" class="n_parallax-text _2" /><img
                    src="{{ $setting->final_cta_sky_image ? asset($setting->final_cta_sky_image) : 'https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/6859b163237d41bfac95cd80_city-sky.jpg' }}"
                    loading="lazy" sizes="100vw"
                    srcset="{{ $setting->final_cta_sky_image ? asset($setting->final_cta_sky_image) : 'https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/6859b163237d41bfac95cd80_city-sky.jpg' }}"
                    alt="" class="n_sky _2" /><img
                    src="{{ $setting->final_cta_city_image ? asset($setting->final_cta_city_image) : 'https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/6859b2b0aa44fdff048f17f3_city.png' }}"
                    loading="lazy" sizes="100vw"
                    srcset="{{ $setting->final_cta_city_image ? asset($setting->final_cta_city_image) : 'https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/6859b2b0aa44fdff048f17f3_city.png' }}"
                    alt="" class="n_base _2" />
                <div class="n_text-wrapper max-width-medium on-home">
                    <h2 class="n_heading-size-h2 text-align-center text-color-white larger">
                        {{ $setting->final_cta_main_title ?? 'Your vision. Your terms.' }}
                    </h2>
                    <div class="spacer-medium"></div>
                    <p>{{ $setting->final_cta_main_description ?? 'Craft the perfect offering with control over raise amount, valuation, voting rights, and beyond. With us, your strategy takes center stage.' }}
                    </p>
                    <div class="spacer-medium"></div>
                    <div class="w-layout-hflex button-wrapper-new is-flex"><a dmr-track="Clicked-Demo-CTA"
                            data-w-id="16f9ff2b-4fe9-b9b5-8b71-e8b50186f42c"
                            href="{{ $setting->final_cta_primary_button_url ?? '/connect' }}" target="_blank"
                            class="n_button is-small w-inline-block">
                            <div>{{ $setting->final_cta_primary_button_text ?? 'Book a Call' }}</div>
                        </a><a dmr-track="Clicked-Demo-CTA" data-w-id="16f9ff2b-4fe9-b9b5-8b71-e8b50186f42f"
                            href="{{ $setting->final_cta_secondary_button_url ?? '/category/case-studies' }}"
                            target="_blank" class="n_button is-alternate trans w-inline-block">
                            <div>{{ $setting->final_cta_secondary_button_text ?? 'View Case Studies' }}</div>
                        </a></div>
                </div>
            </div>
        </header>
    </main>

    @if ($config->show_footer ?? true)
        <div dark-bg="1" class="footer" style="background-color: {{ $setting->getSectionBackgroundColor('footer', '#000000') }};">
            <footer dark-bg="1" class="footer_component" style="background-color: {{ $setting->getSectionBackgroundColor('footer', '#000000') }};">
                <div id="footer" class="padding-global">
                    <div class="container-large">
                        <div class="padding-section-large">
                            <div class="w-layout-grid footer3_top-wrapper">
                                <div class="footer3_left-wrapper"><a href="/old-home-4"
                                        class="footer3_logo-link w-nav-brand">
                                        @if ($setting->uploaded_logo)
                                            <img src="{{ asset($setting->uploaded_logo) }}" alt="Site Logo"
                                                class="navbar_logo" style="height: 40px; width: auto;" />
                                        @elseif($setting->site_logo)
                                            <img src="{{ asset($setting->site_logo) }}" alt="Site Logo"
                                                class="navbar_logo" style="height: 40px; width: auto;" />
                                        @else
                                            <div class="navbar_logo w-embed"><svg width="auto" height="auto"
                                                    viewBox="0 0 1345 237" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
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
                                                        <path d="M650.83 54.0098H629.64V234.87H650.83V54.0098Z"
                                                            fill="white" />
                                                        <path
                                                            d="M1019.85 54.0098H1041.04V173.12L1107.18 103.1H1133.28L1081.86 157.62L1136.89 234.88H1111.31L1067.65 172.87L1041.04 200.26V234.88H1019.85V54.0098Z"
                                                            fill="white" />
                                                        <path
                                                            d="M1131.83 168.99C1131.83 128.94 1157.15 101.55 1194.35 101.55C1231.55 101.55 1255.84 125.06 1256.88 164.08C1256.88 166.92 1256.62 170.02 1256.36 173.12H1154.04V174.93C1154.82 199.99 1170.58 217.56 1195.64 217.56C1214.25 217.56 1228.71 207.74 1233.11 190.69H1254.56C1249.39 217.05 1227.69 236.42 1197.2 236.42C1157.67 236.42 1131.83 209.29 1131.83 168.99ZM1234.15 155.55C1232.08 132.81 1216.58 120.15 1194.62 120.15C1175.24 120.15 1157.41 134.1 1155.35 155.55H1234.16H1234.15Z"
                                                            fill="white" />
                                                        <path
                                                            d="M1344.03 103.1V123.77H1333.43C1305.78 123.77 1298.29 146.77 1298.29 167.69V234.87H1277.1V103.1H1295.44L1298.29 123C1304.49 112.92 1314.57 103.1 1338.08 103.1H1344.03Z"
                                                            fill="white" />
                                                        <path d="M60.78 235.01L0 180.8V126.88L60.78 181.12V235.01Z"
                                                            fill="#8EE8DF" />
                                                    </g>
                                                    <defs>
                                                        <clipPath id="clip0_8230_71">
                                                            <rect width="1344.03" height="236.42"
                                                                fill="white" />
                                                        </clipPath>
                                                    </defs>
                                                </svg></div>
                                        @endif
                                        <div class="spacer-medium"></div>
                                    </a>
                                    <div class="spacer-small">
                                        <div class="text-size-small">
                                            {{ $config->footer_company_description ?? 'DealMaker provides comprehensive capital raising technology that transforms how companies raise funds, engage investors, and build community.' }}<br /><br />{{ $config->footer_company_address ?? '30 East 23rd St. Fl. 2 New York, NY 10010' }}
                                        </div>
                                        <div class="spacer-medium"></div>
                                        <div class="w-layout-grid footer3_social-list">
                                            @if ($setting->show_linkedin && $setting->linkedin_url)
                                                <a aria-label="LinkedIn (opens in a new tab)"
                                                    href="{{ $setting->linkedin_url }}" target="_blank"
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

                                            @if ($setting->show_twitter && $setting->twitter_url)
                                                <a aria-label="Twitter/X (opens in a new tab)"
                                                    href="{{ $setting->twitter_url }}" target="_blank"
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

                                            @if ($setting->show_facebook && $setting->facebook_url)
                                                <a aria-label="Facebook (opens in a new tab)"
                                                    href="{{ $setting->facebook_url }}" target="_blank"
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

                                            @if ($setting->show_instagram && $setting->instagram_url)
                                                <a aria-label="Instagram (opens in a new tab)"
                                                    href="{{ $setting->instagram_url }}" target="_blank"
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
                                        src="{{ $setting->footer_award_image ?? 'https://cdn.prod.website-files.com/656f55af4b70f4ce7ae4b997/65784614f244b62e543d68de_Deloitte%20Companies%20to%20watch%20award%20(Facebook%20Cover)%20(4)%201.png' }}" />
                                </div>
                                <div class="footer3_right-wrapper">
                                    <div class="w-layout-grid footer3_menu-wrapper">
                                        {{-- Dynamic footer menu columns --}}
                                        @if ($setting->footer_menu_columns && count($setting->footer_menu_columns) > 0)
                                            @foreach ($setting->footer_menu_columns as $column)
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
                                            @if ($setting->footer_menu_raise_capital && count($setting->footer_menu_raise_capital) > 0)
                                                <div class="footer3_link-list">
                                                    <div class="text-size-medium capitalize text-color-gray">RAISE
                                                        CAPITAL
                                                    </div>
                                                    <div class="line-sepearation is-small"></div>
                                                    <div class="spacer-small"></div>
                                                    @foreach ($setting->footer_menu_raise_capital as $link)
                                                        <a href="{{ $link['url'] ?? '#' }}"
                                                            class="footer3_link">{{ $link['title'] ?? 'Link' }}</a>
                                                    @endforeach
                                                </div>
                                            @endif

                                            @if ($setting->footer_menu_solutions && count($setting->footer_menu_solutions) > 0)
                                                <div class="footer3_link-list">
                                                    <div class="text-size-medium capitalize text-color-gray">OUR
                                                        SOLUTIONS
                                                    </div>
                                                    <div class="line-sepearation is-small"></div>
                                                    <div class="spacer-small"></div>
                                                    @foreach ($setting->footer_menu_solutions as $link)
                                                        <a href="{{ $link['url'] ?? '#' }}"
                                                            class="footer3_link">{{ $link['title'] ?? 'Link' }}</a>
                                                    @endforeach
                                                </div>
                                            @endif

                                            @if ($setting->footer_menu_company && count($setting->footer_menu_company) > 0)
                                                <div class="footer3_link-list">
                                                    <div class="text-size-medium capitalize text-color-gray">COMPANY
                                                    </div>
                                                    <div class="line-sepearation is-small"></div>
                                                    <div class="spacer-small"></div>
                                                    @foreach ($setting->footer_menu_company as $link)
                                                        <a href="{{ $link['url'] ?? '#' }}"
                                                            class="footer3_link">{{ $link['title'] ?? 'Link' }}</a>
                                                    @endforeach
                                                </div>
                                            @endif

                                            @if ($setting->footer_menu_resources && count($setting->footer_menu_resources) > 0)
                                                <div class="footer3_link-list">
                                                    <div class="text-size-medium capitalize text-color-gray">RESOURCES
                                                    </div>
                                                    <div class="line-sepearation is-small"></div>
                                                    <div class="spacer-small"></div>
                                                    @foreach ($setting->footer_menu_resources as $link)
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
                                                {{ $config->footer_newsletter_description ?? 'Subscribe to our newsletter for the latest updates and insights on capital raising.' }}
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
                                                        background-color: {{ $setting->getSectionBackgroundColor('footer', '#000000') }};;
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
                                                        background-color: {{ $setting->getSectionBackgroundColor('footer', '#000000') }};;
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
                                                        color: {{ $setting->button_text_color ?? '#ffffff' }} !important;
                                                        border: 0;
                                                        background-color: {{ $setting->button_primary_color ?? '#f31cb6' }} !important;
                                                    }

                                                    /* Fix for the thank you message */
                                                    .submitted-message,
                                                    .submitted-message p,
                                                    .submitted-message span {
                                                        color: white !important;
                                                        /* Force all thank you message text to be white */
                                                    }

                                                    .swiper-btn.swiper_btn_next.abs{
                                                        color: {{ $setting->button_primary_color ?? '#f31cb6' }} !important;
                                                        border-color: {{ $setting->button_primary_color ?? '#f31cb6' }} !important;
                                                    }
                                                    .swiper-btn.swiper_btn_next.abs:hover{
                                                        background-color: {{ $setting->button_hover_color ?? '#f31cb6' }} !important;
                                                        color: {{ $setting->button_text_color ?? '#ffffff' }} !important;
                                                        border-color: {{ $setting->button_primary_color ?? '#f31cb6' }} !important;
                                                    }

                                                    .swiper-btn.swiper_btn_prev.is-abs{
                                                        color: {{ $setting->button_primary_color ?? '#f31cb6' }} !important;
                                                        border-color: {{ $setting->button_primary_color ?? '#f31cb6' }} !important;
                                                    }
                                                    .swiper-btn.swiper_btn_prev.is-abs:hover{
                                                        background-color: {{ $setting->button_hover_color ?? '#f31cb6' }} !important;
                                                        color: {{ $setting->button_text_color ?? '#ffffff' }} !important;
                                                        border-color: {{ $setting->button_primary_color ?? '#f31cb6' }} !important;
                                                    }

                                                    .line-sepearation.is-small{
                                                        border-color: {{ $setting->button_primary_color ?? '#f31cb6' }} !important;
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
            <div class="footer-lower" style="background-color: {{ $setting->getSectionBackgroundColor('footer', '#000000') }};">
                <div class="padding-global">
                    <div class="container-large">
                        <div class="padding-section-xsmall">
                            <div class="footer-lower_grid">
                                {{-- <div class="w-layout-hflex flex-block-18"><a
                                        href="{{ $config->footer_terms_url ?? '/terms' }}"
                                        class="footer-lower_link">Terms of Service</a><a
                                        href="{{ $config->footer_privacy_url ?? '/privacy' }}"
                                        class="footer-lower_link">Privacy Policy</a><a
                                        href="{{ $config->footer_cookies_url ?? '/cookies' }}"
                                        class="footer-lower_link">Cookies</a><a
                                        href="{{ $config->footer_security_url ?? '/security' }}"
                                        class="footer-lower_link">Security</a><a
                                        href="{{ $config->footer_accessibility_url ?? '/accessibility' }}"
                                        class="footer-lower_link">Accessibility</a></div> --}}
                                <div>{{ $config->footer_copyright_text ?? '© 2025 DealMaker. All rights reserved.' }}
                                </div>
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

    @if ($setting->custom_js)
        <script>
            {!! $setting->custom_js !!}
        </script>
    @endif

</body>

</html>
