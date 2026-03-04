@php
    // Debug: Check what properties are available
    // dd($component['properties'] ?? 'No properties found');
    
    
    $buttonText = $component['properties']['button_text'] ?? 'INVEST NOW';
    $buttonUrl = $component['properties']['button_url'] ?? '#';
    $buttonTarget = $component['properties']['button_target'] ?? '_self';
    $leftValue = $component['properties']['left_value'] ?? '$2.13';
    $leftLabel = $component['properties']['left_label'] ?? 'Share Price';
    $rightValue = $component['properties']['right_value'] ?? '$1001.10';
    $rightLabel = $component['properties']['right_label'] ?? 'Min. Investment';
    
    // Background and styling
    $backgroundColor = $component['properties']['background_color'] ?? '#ffffff';
    $buttonText = $component['properties']['button_text'] ?? 'INVEST NOW';
    $buttonUrl = $component['properties']['button_url'] ?? '#';
    $buttonTarget = $component['properties']['button_target'] ?? '_self';
    $leftValue = $component['properties']['left_value'] ?? '$2.13';
    $leftLabel = $component['properties']['left_label'] ?? 'Share Price';
    $rightValue = $component['properties']['right_value'] ?? '$1001.10';
    $rightLabel = $component['properties']['right_label'] ?? 'Min. Investment';
    
    // Background and styling
    $backgroundColor = $component['properties']['background_color'] ?? '#ffffff';
    $borderRadius = $component['properties']['border_radius'] ?? '0';
    $padding = $component['properties']['padding'] ?? '20';
    
    // Button styling
    $buttonBgColor = $component['properties']['button_bg_color'] ?? '#2e7d3e';
    $buttonTextColor = $component['properties']['button_text_color'] ?? '#ffffff';
    $buttonBorderRadius = $component['properties']['button_border_radius'] ?? '4';
    $buttonPadding = $component['properties']['button_padding'] ?? '15';
    $buttonFontSize = $component['properties']['button_font_size'] ?? '14';
    $buttonFontWeight = $component['properties']['button_font_weight'] ?? '600';
    
    // Values styling
    $valueColor = $component['properties']['value_color'] ?? '#333333';
    $valueFontSize = $component['properties']['value_font_size'] ?? '16';
    $valueFontWeight = $component['properties']['value_font_weight'] ?? '600';
    
    // Labels styling
    $labelColor = $component['properties']['label_color'] ?? '#666666';
    $labelFontSize = $component['properties']['label_font_size'] ?? '14';
    $labelFontWeight = $component['properties']['label_font_weight'] ?? '400';
    
    // Divider styling
    $dividerColor = $component['properties']['divider_color'] ?? '#e0e0e0';
    $dividerWidth = $component['properties']['divider_width'] ?? '1';
    
    // Responsive spacing
    $desktopMargin = $component['properties']['desktop_margin'] ?? '';
    $tabletMargin = $component['properties']['tablet_margin'] ?? '';
    $mobileMargin = $component['properties']['mobile_margin'] ?? '';
    $desktopPadding = $component['properties']['desktop_padding'] ?? '';
    $tabletPadding = $component['properties']['tablet_padding'] ?? '';
    $mobilePadding = $component['properties']['mobile_padding'] ?? '';
    
    // Generate responsive CSS
    $componentId = 'invest-cta-' . uniqid();
    $responsiveCSS = generateComponentResponsiveCSS($componentId, $component['properties'] ?? []);
@endphp

<style>
.invest-cta-wrapper {
    background-color: {{ $backgroundColor }};
    border-radius: {{ $borderRadius }}px;
    padding: {{ $padding }}px;
    display: flex;
    align-items: center;
    gap: 20px;
    max-width: 500px;
    margin: 0 auto;
}

.invest-cta-button {
    display: inline-block;
    background-color: {{ $buttonBgColor }};
    color: {{ $buttonTextColor }};
    text-decoration: none;
    padding: {{ $buttonPadding }}px 30px;
    border-radius: {{ $buttonBorderRadius }}px;
    font-size: {{ $buttonFontSize }}px;
    font-weight: {{ $buttonFontWeight }};
    text-align: center;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    white-space: nowrap;
    flex-shrink: 0;
}

.invest-cta-button:hover {
    background-color: {{ adjustBrightness($buttonBgColor, -20) }};
    text-decoration: none;
    color: {{ $buttonTextColor }};
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.investment-info-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 20px;
    flex: 1;
}

.investment-info-item {
    text-align: center;
    flex: 1;
}

.investment-value {
    color: {{ $valueColor }};
    font-size: {{ $valueFontSize }}px;
    font-weight: {{ $valueFontWeight }};
    line-height: 1.2;
    margin-bottom: 5px;
}

.investment-label {
    color: {{ $labelColor }};
    font-size: {{ $labelFontSize }}px;
    font-weight: {{ $labelFontWeight }};
    line-height: 1.2;
}

.investment-divider {
    width: {{ $dividerWidth }}px;
    height: 40px;
    background-color: {{ $dividerColor }};
    flex-shrink: 0;
}

/* Responsive Design */
@media (max-width: 767px) {
    .invest-cta-wrapper {
        flex-direction: column;
        padding: 15px;
        gap: 15px;
        text-align: center;
    }
    
    .investment-info-wrapper {
        gap: 15px;
    }
    
    .invest-cta-button {
        padding: 12px 25px;
        font-size: 13px;
    }
    
    .investment-value {
        font-size: 14px;
    }
    
    .investment-label {
        font-size: 12px;
    }
    
    .investment-divider {
        height: 35px;
    }
}

@media (min-width: 768px) and (max-width: 991px) {
    .invest-cta-wrapper {
        padding: 18px;
        gap: 18px;
    }
    
    .investment-info-wrapper {
        gap: 18px;
    }
}

{!! $responsiveCSS !!}
</style>

<div class="invest-cta-wrapper {{ $componentId }}" id="{{ $componentId }}">
    <div class="invest-cta-button-wrap">
        <a href="{{ $buttonUrl }}" 
           target="{{ $buttonTarget }}" 
           class="invest-cta-button"
           aria-label="{{ $buttonText }}">
            {{ $buttonText }}
        </a>
    </div>
    
    <div class="investment-info-wrapper">
        <div class="investment-info-item">
            <div class="investment-value">{{ $leftValue }}</div>
            <div class="investment-label">{{ $leftLabel }}</div>
        </div>
        
        <div class="investment-divider"></div>
        
        <div class="investment-info-item">
            <div class="investment-value">{{ $rightValue }}</div>
            <div class="investment-label">{{ $rightLabel }}</div>
        </div>
    </div>
</div>

@php
function adjustBrightness($hex, $percent) {
    // Remove # if present
    $hex = str_replace('#', '', $hex);
    
    // Convert to RGB
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    
    // Adjust brightness
    $r = max(0, min(255, $r + ($r * $percent / 100)));
    $g = max(0, min(255, $g + ($g * $percent / 100)));
    $b = max(0, min(255, $b + ($b * $percent / 100)));
    
    // Convert back to hex
    return '#' . str_pad(dechex($r), 2, '0', STR_PAD_LEFT) . 
                  str_pad(dechex($g), 2, '0', STR_PAD_LEFT) . 
                  str_pad(dechex($b), 2, '0', STR_PAD_LEFT);
}
@endphp
