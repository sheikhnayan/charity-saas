<?php
// Standalone Investment CTA Component for Testing

$buttonText = $component['properties']['button_text'] ?? 'INVEST NOW';
$buttonUrl = $component['properties']['button_url'] ?? '#';
$buttonTarget = $component['properties']['button_target'] ?? '_self';
$leftValue = $component['properties']['left_value'] ?? '$2.13';
$leftLabel = $component['properties']['left_label'] ?? 'Share Price';
$rightValue = $component['properties']['right_value'] ?? '$1001.10';
$rightLabel = $component['properties']['right_label'] ?? 'Min. Investment';

// Background and styling
$backgroundColor = $component['properties']['background_color'] ?? 'transparent';
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

// Generate responsive CSS
$componentId = 'invest-cta-' . uniqid();
$responsiveCSS = generateComponentResponsiveCSS($componentId, $component['properties'] ?? []);

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
?>

<style>
.invest-cta-wrapper {
    background-color: <?php echo $backgroundColor; ?>;
    border-radius: <?php echo $borderRadius; ?>px;
    padding: <?php echo $padding; ?>px;
    display: flex;
    align-items: center;
    gap: 20px;
    max-width: 500px;
    margin: 0 auto;
    box-sizing: border-box;
    width: 100%;
}

.invest-cta-button {
    display: inline-block;
    background-color: <?php echo $buttonBgColor; ?>;
    color: <?php echo $buttonTextColor; ?>;
    text-decoration: none;
    padding: <?php echo $buttonPadding; ?>px 30px;
    border-radius: <?php echo $buttonBorderRadius; ?>px;
    font-size: <?php echo $buttonFontSize; ?>px;
    font-weight: <?php echo $buttonFontWeight; ?>;
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
    background-color: <?php echo adjustBrightness($buttonBgColor, -20); ?>;
    text-decoration: none;
    color: <?php echo $buttonTextColor; ?>;
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
    color: <?php echo $valueColor; ?>;
    font-size: <?php echo $valueFontSize; ?>px;
    font-weight: <?php echo $valueFontWeight; ?>;
    line-height: 1.2;
    margin-bottom: 5px;
}

.investment-label {
    color: <?php echo $labelColor; ?>;
    font-size: <?php echo $labelFontSize; ?>px;
    font-weight: <?php echo $labelFontWeight; ?>;
    line-height: 1.2;
}

.investment-divider {
    width: <?php echo $dividerWidth; ?>px;
    height: 40px;
    background-color: <?php echo $dividerColor; ?>;
    flex-shrink: 0;
}

/* Responsive Design */
@media (max-width: 767px) {
    .invest-cta-wrapper {
        flex-direction: column;
        padding: 15px;
        gap: 15px;
        text-align: center;
        max-width: 100% !important;
        margin: 0 auto;
        box-sizing: border-box;
        width: calc(100% - 20px);
        margin-left: 10px;
        margin-right: 10px;
    }
    
    .investment-info-wrapper {
        gap: 15px;
        width: 100%;
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .investment-info-item {
        min-width: 0;
        flex: 1;
    }
    
    .invest-cta-button {
        padding: 12px 25px;
        font-size: 13px;
        width: 100%;
        max-width: 200px;
        box-sizing: border-box;
    }
    
    .investment-value {
        font-size: 14px;
    }
    
    .investment-label {
        font-size: 12px;
    }
    
    .investment-divider {
        height: 35px;
        width: 2px;
        min-width: 2px;
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

<?php echo $responsiveCSS; ?>
</style>

<div class="invest-cta-wrapper <?php echo $componentId; ?>" id="<?php echo $componentId; ?>">
    <div class="invest-cta-button-wrap">
        <a href="<?php echo $buttonUrl; ?>" 
           target="<?php echo $buttonTarget; ?>" 
           class="invest-cta-button"
           aria-label="<?php echo $buttonText; ?>">
            <?php echo $buttonText; ?>
        </a>
    </div>
    
    <div class="investment-info-wrapper">
        <div class="investment-info-item">
            <div class="investment-value"><?php echo $leftValue; ?></div>
            <div class="investment-label"><?php echo $leftLabel; ?></div>
        </div>
        
        <div class="investment-divider"></div>
        
        <div class="investment-info-item">
            <div class="investment-value"><?php echo $rightValue; ?></div>
            <div class="investment-label"><?php echo $rightLabel; ?></div>
        </div>
    </div>
</div>
