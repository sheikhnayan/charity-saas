@php
    $type = $componentData['type'] ?? null;

    $styleToString = function ($styleArray): string {
        if (!is_array($styleArray)) {
            return '';
        }

        $pairs = [];
        foreach ($styleArray as $key => $value) {
            if ($value === null || $value === '') {
                continue;
            }

            // Builder state stores style keys in camelCase; convert to CSS kebab-case.
            $cssKey = strtolower(preg_replace('/([A-Z])/', '-$1', (string) $key));
            $pairs[] = $cssKey . ': ' . $value;
        }

        return implode('; ', $pairs);
    };
@endphp

@if($type === 'inner-section')
    @php
        $innerData = $componentData['innerSectionData'] ?? [];
        $nested = $componentData['nestedComponents'] ?? [[]];
        $wrapperStyle = $styleToString($componentData['wrapperStyle'] ?? []);
        $innerStyleArray = is_array($componentData['style'] ?? null) ? $componentData['style'] : [];

        // Respect explicit inner-section background settings saved in builder data.
        if (!empty($innerData['backgroundType'])) {
            if ($innerData['backgroundType'] === 'color' && !empty($innerData['backgroundColor'])) {
                $innerStyleArray['backgroundColor'] = trim($innerData['backgroundColor']) . ' !important';
            }

            if ($innerData['backgroundType'] === 'image' && !empty($innerData['backgroundImage'])) {
                $innerStyleArray['backgroundImage'] = "url('" . $innerData['backgroundImage'] . "')";
                $innerStyleArray['backgroundSize'] = $innerData['backgroundSize'] ?? 'cover';
                $innerStyleArray['backgroundPosition'] = $innerData['backgroundPosition'] ?? 'center center';
                $innerStyleArray['backgroundRepeat'] = 'no-repeat';
            }
        }

        if (($innerData['stickyMode'] ?? 'normal') === 'sticky') {
            $innerStyleArray['position'] = 'sticky';
            $innerStyleArray['top'] = $innerData['stickyTop'] ?? '0px';
            $innerStyleArray['zIndex'] = $innerStyleArray['zIndex'] ?? '20';
        }

        $innerStyle = $styleToString($innerStyleArray);
        $isFullWidth = (bool) ($innerData['fullWidth'] ?? false);
        $contentWidth = $innerData['contentWidth'] ?? 'boxed';
        $useFluidContainer = $isFullWidth || $contentWidth === 'full';
        $columnCount = max(count($nested), 1);
        $colClass = 'col-12 col-md-' . max((int) floor(12 / $columnCount), 1);
    @endphp

    <section class="component inner-section-component" style="{{ $wrapperStyle }}">
        <div class="inner-section-frontend {{ $isFullWidth ? 'inner-section-fullwidth' : '' }}" style="{{ $innerStyle }}">
            <div class="{{ $useFluidContainer ? '' : 'container' }}">
                <div class="row g-0">
                    @foreach($nested as $column)
                        <div class="{{ $colClass }}">
                            @if(is_array($column))
                                @foreach($column as $nestedComponent)
                                    @include('builders.render-builder-component', [
                                        'componentData' => $nestedComponent,
                                        'header' => $header,
                                        'footer' => $footer,
                                        'check' => $check,
                                        'data' => $data,
                                        'menuSections' => $menuSections,
                                    ])
                                @endforeach
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@elseif($type === 'header-contact-topbar')
    @php
        $contactData = $componentData['headerContactTopbarData'] ?? [];
        $contactProps = $componentData['properties'] ?? [];
        $contactComponentStyle = is_array($componentData['style'] ?? null) ? $componentData['style'] : [];
        $contactWrapperStyle = $styleToString($componentData['wrapperStyle'] ?? []);

        $showContactTopbar = isset($header->show_contact_topbar) ? ((int) $header->show_contact_topbar === 1) : true;
        $contactPhone = $contactData['phone'] ?? ($contactProps['contact_phone'] ?? ($header->contact_phone ?? ''));
        $contactEmail = $contactData['email'] ?? ($contactProps['contact_email'] ?? ($header->contact_email ?? ''));
        $contactCtaText = $contactData['ctaText'] ?? ($contactProps['contact_cta_text'] ?? ($header->contact_cta_text ?? ''));
        $contactCtaUrl = $contactData['ctaUrl'] ?? ($contactProps['contact_cta_url'] ?? '#');
        $contactBgColor = $contactData['bgColor'] ?? ($contactProps['background_color'] ?? ($header->contact_topbar_bg_color ?? '#000000'));
        $contactTextColor = $contactData['textColor'] ?? ($contactProps['text_color'] ?? ($header->contact_topbar_text_color ?? '#ffffff'));
        $contactFontSize = $contactData['fontSize'] ?? ($contactProps['font_size'] ?? '14px');
        $contactFontFamily = $contactData['fontFamily'] ?? ($contactProps['font_family'] ?? 'Outfit');
        $contactFontWeight = $contactData['fontWeight'] ?? ($contactProps['font_weight'] ?? '500');
        $contactTextTransform = $contactData['textTransform'] ?? ($contactProps['text_transform'] ?? 'none');
        $contactLetterSpacing = $contactData['letterSpacing'] ?? ($contactProps['letter_spacing'] ?? '0px');
        $contactTextDecoration = $contactData['textDecoration'] ?? ($contactProps['text_decoration'] ?? 'none');
        $contactPaddingTop = $contactData['paddingTop'] ?? ($contactProps['padding_top'] ?? '8px');
        $contactPaddingBottom = $contactData['paddingBottom'] ?? ($contactProps['padding_bottom'] ?? '8px');
        $contactTopOffset = $contactData['topOffset'] ?? ($contactProps['top_offset'] ?? '0px');
        $contactComponentStyleString = $styleToString($contactComponentStyle);
    @endphp
    @if($showContactTopbar)
        <div style="{{ $contactWrapperStyle }}">
            <div class="contact-topbar" style="background: {{ $contactBgColor }}; padding: {{ $contactPaddingTop }} 0 {{ $contactPaddingBottom }}; font-size: {{ $contactFontSize }}; position: static !important; top: auto !important; margin-top: {{ $contactTopOffset }}; {{ $contactComponentStyleString }};">
                <div class="container">
                    <div class="row align-items-center justify-content-center">
                        @if(!empty($contactPhone))
                            <div class="col-12 col-md-auto">
                                <div class="contact-item me-md-4 mb-1 text-center">
                                    <i class="fas fa-phone me-2" style="color: {{ $contactTextColor }};"></i>
                                    <a href="tel:{{ $contactPhone }}" style="color: {{ $contactTextColor }}; font-family: '{{ $contactFontFamily }}', sans-serif; font-weight: {{ $contactFontWeight }}; text-transform: {{ $contactTextTransform }}; letter-spacing: {{ $contactLetterSpacing }}; text-decoration: {{ $contactTextDecoration }};">
                                        {{ $contactPhone }}
                                    </a>
                                </div>
                            </div>
                        @endif
                        @if(!empty($contactEmail))
                            <div class="col-12 col-md-auto">
                                <div class="contact-item me-md-4 mb-1 text-center">
                                    <i class="fas fa-envelope me-2" style="color: {{ $contactTextColor }};"></i>
                                    <a href="mailto:{{ $contactEmail }}" style="color: {{ $contactTextColor }}; font-family: '{{ $contactFontFamily }}', sans-serif; font-weight: {{ $contactFontWeight }}; text-transform: {{ $contactTextTransform }}; letter-spacing: {{ $contactLetterSpacing }}; text-decoration: {{ $contactTextDecoration }};">
                                        {{ $contactEmail }}
                                    </a>
                                </div>
                            </div>
                        @endif
                        @if(!empty($contactCtaText))
                            <div class="col-12 col-md-auto">
                                <div class="contact-item mb-1 text-center">
                                    <i class="fas fa-map-marker-alt me-2" style="color: {{ $contactTextColor }};"></i>
                                    <a href="{{ $contactCtaUrl }}" style="color: {{ $contactTextColor }}; font-family: '{{ $contactFontFamily }}', sans-serif; font-weight: {{ $contactFontWeight }}; text-transform: {{ $contactTextTransform }}; letter-spacing: {{ $contactLetterSpacing }}; text-decoration: {{ $contactTextDecoration }};">
                                        {{ $contactCtaText }}
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
@elseif($type === 'header-nav')
    @include('layouts.nav')
@elseif($type === 'header-investor-bar')
    @if($check && $header && $header->show_investor_exclusives)
        @php
            $investorData = $componentData['headerInvestorBarData'] ?? [];
            $investorProps = $componentData['properties'] ?? [];
            $investorComponentStyle = is_array($componentData['style'] ?? null) ? $componentData['style'] : [];
            $investorWrapperStyle = $styleToString($componentData['wrapperStyle'] ?? []);

            $investorText = $investorData['text'] ?? ($investorProps['investor_text'] ?? ($header->investor_exclusives_text ?? 'Exclusive access for investors'));
            $investorUrl = $investorData['url'] ?? ($investorProps['investor_url'] ?? ($header->investor_exclusives_url ?? '#'));
            $investorBgColor = $investorData['bgColor'] ?? ($investorProps['background_color'] ?? ($header->topbar_background_color ?? '#1e3a8a'));
            $investorTextColor = $investorData['textColor'] ?? ($investorProps['text_color'] ?? ($header->topbar_text_color ?? '#ffffff'));
            $investorFontSize = $investorData['fontSize'] ?? ($investorProps['font_size'] ?? '13px');
            $investorFontFamily = $investorData['fontFamily'] ?? ($investorProps['font_family'] ?? 'Outfit');
            $investorFontWeight = $investorData['fontWeight'] ?? ($investorProps['font_weight'] ?? '500');
            $investorTextTransform = $investorData['textTransform'] ?? ($investorProps['text_transform'] ?? 'uppercase');
            $investorLetterSpacing = $investorData['letterSpacing'] ?? ($investorProps['letter_spacing'] ?? '0px');
            $investorTextDecoration = $investorData['textDecoration'] ?? ($investorProps['text_decoration'] ?? 'none');
            $investorPaddingTop = $investorData['paddingTop'] ?? ($investorProps['padding_top'] ?? '5px');
            $investorPaddingBottom = $investorData['paddingBottom'] ?? ($investorProps['padding_bottom'] ?? '4px');
            $investorTopOffset = $investorData['topOffset'] ?? ($investorProps['top_offset'] ?? '0px');

            $investorComponentStyleString = $styleToString($investorComponentStyle);
        @endphp
        <div style="{{ $investorWrapperStyle }}">
            <div class="investor-exclusives-bar" style="background: {{ $investorBgColor }}; {{ $investorComponentStyleString }}; position: static !important; top: auto !important; margin-top: {{ $investorTopOffset }};">
                <div class="investor-exclusives-content">
                    <a href="{{ $investorUrl }}" style="text-decoration: {{ $investorTextDecoration }};">
                        <p class="investor-exclusives-text" style="color: {{ $investorTextColor }}; font-size: {{ $investorFontSize }}; padding-top: {{ $investorPaddingTop }}; font-family: '{{ $investorFontFamily }}',sans-serif; font-weight: {{ $investorFontWeight }}; text-transform: {{ $investorTextTransform }}; letter-spacing: {{ $investorLetterSpacing }}; padding-bottom: {{ $investorPaddingBottom }}; text-decoration: {{ $investorTextDecoration }};">
                            {{ $investorText }}
                        </p>
                    </a>
                </div>
            </div>
        </div>
    @endif
@elseif($type === 'header-logo')
    <div class="container py-2">
        <a href="https://{{ $check->domain ?? '#' }}" class="d-inline-block text-decoration-none">
            <img src="{{ isset($setting->logo) ? asset('uploads/' . $setting->logo) : '' }}" alt="Logo"
                 style="width: {{ $header->logo_size ?? 100 }}px; height: {{ $header->logo_height ?? 60 }}px; object-fit: contain;">
        </a>
    </div>
@elseif($type === 'header-menu-links')
    @php
        $menuCollapseId = 'navbarNavMenuLinks';
        $submenuBgColor = $header->submenu_background_color ?? 'rgba(255, 255, 255, 0.98)';
        $componentStyle = is_array($componentData['style'] ?? null) ? $componentData['style'] : [];
        $menuLinkColor = !empty($componentStyle['color']) ? $componentStyle['color'] : ($header->color ?? '#000000');
        $menuFontFamily = !empty($componentStyle['fontFamily']) ? $componentStyle['fontFamily'] : ($header->menu_font_family ?? 'Outfit');
        $menuFontSize = !empty($componentStyle['fontSize']) ? $componentStyle['fontSize'] : ($header->menu_font_size ?? '14px');
        $menuFontWeight = !empty($componentStyle['fontWeight']) ? $componentStyle['fontWeight'] : '500';
        $menuLetterSpacing = !empty($componentStyle['letterSpacing']) ? $componentStyle['letterSpacing'] : '0px';
        $menuTextTransform = !empty($componentStyle['textTransform']) ? $componentStyle['textTransform'] : 'uppercase';
        $menuTextDecoration = !empty($componentStyle['textDecoration']) ? $componentStyle['textDecoration'] : 'none';
    @endphp
    <style>
        @media (min-width: 1200px) {
            .navbar-expand-xl .navbar-collapse {
                display: flex !important;
                flex-basis: auto;
            }

            .navbar-expand-xl .navbar-collapse.collapse {
                display: flex !important;
            }

            .navbar-nav .dropdown:hover > .dropdown-menu,
            .navbar-nav .dropdown.show > .dropdown-menu {
                display: block;
            }
        }

        .navbar-nav .nav-link {
            padding: 0.5rem 1rem;
        }

        .navbar-nav .dropdown-menu {
            background: {{ $submenuBgColor }};
            border: none;
            border-radius: 8px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            margin-top: 0.25rem;
            min-width: 200px;
        }

        .navbar-nav .dropdown-item {
            color: #333;
            padding: 0.75rem 1.25rem;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }

        .navbar-nav .dropdown-item:hover,
        .navbar-nav .dropdown-item:focus {
            background-color: rgba(0, 123, 255, 0.1);
            color: #007bff;
        }
    </style>

    <nav class="navbar navbar-expand-xl bg-transparent" style="background-color: transparent !important; box-shadow: none !important;">
        <div class="container invest-mobile">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $menuCollapseId }}" aria-controls="{{ $menuCollapseId }}" aria-expanded="false" aria-label="Toggle navigation" style="color:{{ $menuLinkColor }} !important; border-color: transparent !important;">
                <i class="fa fa-bars" style="color: {{ $menuLinkColor }}; font-size: 1.5rem;"></i>
            </button>

            <div class="collapse navbar-collapse justify-content-center" id="{{ $menuCollapseId }}">
                <ul class="navbar-nav" style="font-family: '{{ $menuFontFamily }}', sans-serif; font-size: {{ $menuFontSize }}; font-weight: {{ $menuFontWeight }}; letter-spacing: {{ $menuLetterSpacing }};">
            @if($check && $check->type == 'investment')
                {{-- Investment website: section-based menu links (same class/style structure) --}}
                @if(isset($menuSections) && is_array($menuSections) && count($menuSections) > 0)
                    @foreach ($menuSections as $section)
                        @php
                            $sectionId = $section['sectionId'] ?? ($section['id'] ?? null);
                            $sectionTitle = $section['title'] ?? ($section['label'] ?? 'Section');
                        @endphp
                        <li class="nav-item">
                            <a class="nav-link active scroll-to-section" aria-current="page" href="#{{ $sectionId }}" data-section="{{ $sectionId }}" style="color:{{ $menuLinkColor }} !important; text-transform: {{ $menuTextTransform }}; text-decoration: {{ $menuTextDecoration }}; font-weight: {{ $menuFontWeight }}; letter-spacing: {{ $menuLetterSpacing }};">
                                {{ $sectionTitle }}
                            </a>
                        </li>
                    @endforeach
                @else
                    <li class="nav-item">
                        <a class="nav-link active" href="/" style="color:{{ $menuLinkColor }} !important; text-transform: {{ $menuTextTransform }}; text-decoration: {{ $menuTextDecoration }}; font-weight: {{ $menuFontWeight }}; letter-spacing: {{ $menuLetterSpacing }};">Home</a>
                    </li>
                @endif
            @else
                {{-- Fundraiser website: use Menu Builder structure first, then fallback to pages --}}
                @php
                    $primaryMenu = \App\Models\Menu::where('website_id', $check->id)
                        ->where('location', 'primary')
                        ->where('status', 1)
                        ->with(['items.children', 'items.page'])
                        ->first();
                @endphp

                @if($primaryMenu && $primaryMenu->items->count() > 0)
                    @foreach($primaryMenu->items as $menuItem)
                        @if($menuItem->children->count() > 0)
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle {{ $menuItem->css_classes }}"
                                   href="{{ $menuItem->url ?: '#' }}"
                                   id="navbarDropdown{{ $menuItem->id }}"
                                   role="button"
                                   data-bs-toggle="dropdown"
                                   aria-expanded="false"
                                   target="{{ $menuItem->target }}"
                                   style="color:{{ $menuLinkColor }} !important; text-transform: {{ $menuTextTransform }}; text-decoration: {{ $menuTextDecoration }}; font-weight: {{ $menuFontWeight }}; letter-spacing: {{ $menuLetterSpacing }};">
                                    {{ $menuItem->title }}
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown{{ $menuItem->id }}">
                                    @foreach($menuItem->children as $child)
                                        <li>
                                            <a class="dropdown-item {{ $child->css_classes }}" href="{{ $child->url }}" target="{{ $child->target }}">
                                                {{ $child->title }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link {{ $menuItem->css_classes }}"
                                   href="{{ $menuItem->url }}"
                                   target="{{ $menuItem->target }}"
                                   style="color:{{ $menuLinkColor }} !important; text-transform: {{ $menuTextTransform }}; text-decoration: {{ $menuTextDecoration }}; font-weight: {{ $menuFontWeight }}; letter-spacing: {{ $menuLetterSpacing }};">
                                    {{ $menuItem->title }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @else
                    @php
                        $fallbackPages = ($check && isset($check->pages)) ? $check->pages->sortBy('position') : collect();
                    @endphp
                    @foreach($fallbackPages as $pageItem)
                        @if($pageItem->status == 1 && $pageItem->show_in_menu)
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="/page/{{ str_replace(' ', '-', strtolower($pageItem->name)) }}" style="color:{{ $menuLinkColor }} !important; text-transform: {{ $menuTextTransform }}; text-decoration: {{ $menuTextDecoration }}; font-weight: {{ $menuFontWeight }}; letter-spacing: {{ $menuLetterSpacing }};">
                                    {{ $pageItem->name }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endif
                </ul>
            </div>
        </div>
    </nav>
@elseif($type === 'header-auth-button')
    @php
        $authData = $componentData['headerAuthButtonData'] ?? [];
        $authProps = $componentData['properties'] ?? [];

        $showAuthButton = isset($header->show_auth_button) ? ((int) $header->show_auth_button === 1) : true;

        $loginText = $authData['loginText'] ?? ($authProps['login_text'] ?? ($header->auth_button_text ?? 'Login / Register'));
        $dashboardText = $authData['dashboardText'] ?? ($authProps['dashboard_text'] ?? 'DASHBOARD');
        $bgColor = $authData['bgColor'] ?? ($authProps['button_bg_color'] ?? ($header->auth_button_bg_color ?? '#007bff'));
        $textColor = $authData['textColor'] ?? ($authProps['button_text_color'] ?? ($header->auth_button_text_color ?? '#ffffff'));
        $fontSize = $authData['fontSize'] ?? ($authProps['font_size'] ?? '0.9rem');
        $fontFamily = $authData['fontFamily'] ?? ($authProps['font_family'] ?? 'Outfit');
        $padding = $authData['padding'] ?? ($authProps['button_padding'] ?? '0.6rem');
        $borderRadius = $authData['borderRadius'] ?? ($authProps['border_radius'] ?? '4px');

        $buttonStyle = sprintf(
            'background-color: %s !important; color: %s !important; font-size: %s; font-family: "%s", sans-serif; padding: %s !important; border-radius: %s !important;',
            e($bgColor),
            e($textColor),
            e($fontSize),
            e($fontFamily),
            e($padding),
            e($borderRadius)
        );
    @endphp
    @if($showAuthButton)
        <div class="container py-2 text-end">
            @if(auth()->check())
                <button class="invest-now-btn sssssttttt" onclick="window.location.href='/users/profile'" style="{{ $buttonStyle }}">
                    {{ $dashboardText }}
                </button>
            @else
                <button class="invest-now-btn sssssttttt" onclick="window.location.href='/login'" style="{{ $buttonStyle }}">
                    {{ $loginText }}
                </button>
            @endif
        </div>
    @endif
@elseif($type === 'header-invest-button')
    <div class="container py-2 text-end">
        <a href="/invest" class="btn btn-success">{{ $header->invest_now_button_text ?? 'INVEST NOW' }}</a>
    </div>
@elseif($type === 'footer-legacy-main')
    @if($footer && $footer->status == 1)
        @include('layouts.new-footer')
    @endif
@elseif($type === 'footer-logo')
    <div class="container py-3 text-center">
        <img src="{{ isset($setting->logo) ? asset('uploads/' . $setting->logo) : '' }}" alt="Footer Logo"
             style="max-width: 180px; height: auto; object-fit: contain;">
    </div>
@elseif($type === 'footer-description')
    @if(!empty($footer->description_text) && strip_tags($footer->description_text) != '')
        <div class="container py-2" style="color: {{ $footer->color ?? '#ffffff' }};">
            {!! $footer->description_text !!}
        </div>
    @endif
@elseif($type === 'footer-social-links')
    @if($footer && $footer->social == 1)
        <div class="container py-2">
            <div class="d-flex flex-wrap justify-content-center gap-3">
                @foreach(['facebook','twitter','instagram','linkedin','youtube','tiktok','pinterest','blue_sky'] as $socialKey)
                    @if(!empty($footer->$socialKey) && $footer->$socialKey !== '#')
                        <a href="{{ $footer->$socialKey }}" target="_blank" style="color: {{ $footer->color ?? '#ffffff' }}; text-transform: capitalize;">{{ str_replace('_', ' ', $socialKey) }}</a>
                    @endif
                @endforeach
            </div>
        </div>
    @endif
@elseif($type === 'footer-contact-block')
    <div class="container py-2 text-center" style="color: {{ $footer->color ?? '#ffffff' }};">
        <div style="color: {{ $footer->contact_heading_color ?? $footer->color ?? '#ffffff' }}; font-family: '{{ $footer->contact_heading_font ?? 'outfit' }}', sans-serif; font-size: {{ $footer->contact_heading_size ?? '14px' }};">
            {{ $footer->contact_heading ?? 'Contact Us' }}
        </div>
        <div style="color: {{ $footer->contact_email_color ?? $footer->color ?? '#ffffff' }}; font-family: '{{ $footer->contact_email_font ?? 'outfit' }}', sans-serif; font-size: {{ $footer->contact_email_size ?? '14px' }};">
            {{ $check->user->email ?? '' }}
        </div>
    </div>
@elseif($type === 'footer-policy-links')
    @if($footer && $footer->privacy == 1)
        <div class="container py-2">
            <div class="d-flex flex-wrap justify-content-center gap-3">
                @if($footer->refund_page_id && $footer->refund_page)
                    <a href="/page/{{ str_replace(' ', '-', strtolower($footer->refund_page->name)) }}" style="color: {{ $footer->color ?? '#ffffff' }};">Refund policy</a>
                @endif
                @if($footer->privacy_page_id && $footer->privacy_page)
                    <a href="/page/{{ str_replace(' ', '-', strtolower($footer->privacy_page->name)) }}" style="color: {{ $footer->color ?? '#ffffff' }};">Privacy policy</a>
                @endif
                @if($footer->terms_page_id && $footer->terms_page)
                    <a href="/page/{{ str_replace(' ', '-', strtolower($footer->terms_page->name)) }}" style="color: {{ $footer->color ?? '#ffffff' }};">Terms of service</a>
                @endif
            </div>
        </div>
    @endif
@elseif($type === 'footer-disclaimer')
    @if(!empty($footer->disclaimer_text) && strip_tags($footer->disclaimer_text) != '')
        <div class="container py-2" style="color: {{ $footer->color ?? '#ffffff' }};">
            {!! $footer->disclaimer_text !!}
        </div>
    @endif
@elseif($type === 'footer-investment-disclaimer')
    @if(!empty($footer->investment_disclaimer) && strip_tags($footer->investment_disclaimer) != '')
        <div class="container py-2" style="color: {{ $footer->color ?? '#ffffff' }};">
            {!! $footer->investment_disclaimer !!}
        </div>
    @endif
@elseif($type === 'footer-background-images')
    <div class="container py-3">
        <div class="row g-3">
            <div class="col-12 col-md-6 text-center">
                <div style="font-size: 12px; color: {{ $footer->color ?? '#ffffff' }}; margin-bottom: 6px;">Desktop Background</div>
                <img src="{{ $footer->background_image_desktop ? asset('uploads/' . $footer->background_image_desktop) : 'https://cdn.prod.website-files.com/615c7704bf83fe0f0bb27c0b/685affc19009a2f3c9ecd550_iStock-520365936_a.webp' }}"
                     alt="Footer Desktop Background" style="width: 100%; max-height: 140px; object-fit: cover; border-radius: 8px;">
            </div>
            <div class="col-12 col-md-6 text-center">
                <div style="font-size: 12px; color: {{ $footer->color ?? '#ffffff' }}; margin-bottom: 6px;">Mobile Background</div>
                <img src="{{ $footer->background_image_mobile ? asset('uploads/' . $footer->background_image_mobile) : 'https://cdn.prod.website-files.com/615c7704bf83fe0f0bb27c0b/688060eaaffeb257c1461eca_Sky-Mobile.webp' }}"
                     alt="Footer Mobile Background" style="width: 100%; max-height: 140px; object-fit: cover; border-radius: 8px;">
            </div>
        </div>
    </div>
@else
    @include('page-components.render-component', [
        'component' => $componentData,
        'componentId' => $componentData['id'] ?? ('builder-' . uniqid()),
        'isNested' => true,
    ])
@endif
