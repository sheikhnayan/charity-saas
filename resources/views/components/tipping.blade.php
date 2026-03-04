{{-- Tipping Component - Include this in donation forms --}}
{{-- Usage: @include('components.tipping', ['baseAmount' => $amount, 'primaryColor' => '#28a745']) --}}

@php
    $tipService = new \App\Services\TipService();
    $baseAmount = $baseAmount ?? 0;
    $primaryColor = $primaryColor ?? '#28a745';
    $suggestedTips = $baseAmount > 0 ? $tipService->getSuggestedTips($baseAmount) : [];
    $tipMessage = $baseAmount > 0 ? $tipService->getTipMessage($baseAmount) : '';
    $optimalTip = $baseAmount > 0 ? $tipService->getOptimalTipPercentage($baseAmount) : 15;
@endphp

<style>
    .tipping-section {
        background: #f8f9fa;
        border: 2px dashed #e0e0e0;
        border-radius: 12px;
        padding: 20px;
        margin: 20px 0;
        transition: all 0.3s ease;
    }
    
    .tipping-section.active {
        border-color: {{ $primaryColor }};
        border-style: solid;
        background: {{ $primaryColor }}10;
    }
    
    .tip-toggle {
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        padding: 10px;
        border-radius: 8px;
        transition: background 0.2s;
    }
    
    .tip-toggle:hover {
        background: rgba(0,0,0,0.02);
    }
    
    .tip-switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 26px;
    }
    
    .tip-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    
    .tip-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 34px;
    }
    
    .tip-slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }
    
    input:checked + .tip-slider {
        background-color: {{ $primaryColor }};
    }
    
    input:checked + .tip-slider:before {
        transform: translateX(24px);
    }
    
    .tip-options {
        display: none;
        margin-top: 15px;
        animation: slideDown 0.3s ease;
    }
    
    .tip-options.show {
        display: block;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .tip-percentage-btns {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
        margin-bottom: 15px;
    }
    
    .tip-btn {
        padding: 12px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        background: white;
        cursor: pointer;
        text-align: center;
        transition: all 0.2s;
        font-weight: 500;
    }
    
    .tip-btn:hover {
        border-color: {{ $primaryColor }};
        transform: translateY(-2px);
    }
    
    .tip-btn.active {
        border-color: {{ $primaryColor }};
        background: {{ $primaryColor }};
        color: white;
    }
    
    .tip-btn.recommended {
        position: relative;
    }
    
    .tip-btn.recommended:after {
        content: "⭐";
        position: absolute;
        top: -8px;
        right: -8px;
        font-size: 16px;
    }
    
    .custom-tip-input {
        position: relative;
        margin-top: 10px;
    }
    
    .custom-tip-input .dollar-sign {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #666;
        font-weight: bold;
    }
    
    .tip-summary {
        background: white;
        padding: 15px;
        border-radius: 8px;
        margin-top: 15px;
        border-left: 4px solid {{ $primaryColor }};
    }
    
    .tip-summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: 14px;
    }
    
    .tip-summary-total {
        font-size: 18px;
        font-weight: bold;
        color: {{ $primaryColor }};
        padding-top: 10px;
        border-top: 2px solid #e0e0e0;
        margin-top: 10px;
    }
</style>

<div class="tipping-section active" id="tippingSection">
    <!-- Tip Toggle -->
    <div class="tip-toggle" onclick="toggleTipping()">
        <div style="flex: 1;">
            <div style="display: flex; align-items: center; margin-bottom: 5px;">
                <i class="fas fa-heart" style="color: {{ $primaryColor }}; margin-right: 8px; font-size: 18px;"></i>
                <strong>Add a tip to support us</strong>
            </div>
            <small class="text-muted">{{ $tipMessage }}</small>
        </div>
        <label class="tip-switch">
            <input type="checkbox" id="tipEnabled" name="tip_enabled" onchange="toggleTippingOptions()" checked>
            <span class="tip-slider"></span>
        </label>
    </div>
    
    <!-- Tip Options (Open by default) -->
    <div class="tip-options show" id="tipOptions">
        <div class="mt-3">
            <label class="form-label fw-semibold">Select tip amount:</label>
            
            <!-- Percentage Buttons -->
            <div class="tip-percentage-btns">
                @foreach($suggestedTips as $index => $tip)
                    <button type="button" 
                            class="tip-btn {{ $tip['percentage'] == $optimalTip ? 'recommended' : '' }}" 
                            data-percentage="{{ $tip['percentage'] }}"
                            data-amount="{{ $tip['amount'] }}"
                            onclick="selectTipPercentage(this)">
                        <div style="font-size: 16px; font-weight: bold;">{{ $tip['percentage'] }}%</div>
                        <div style="font-size: 12px; color: #666;">${{ number_format($tip['amount'], 2) }}</div>
                    </button>
                @endforeach
            </div>
            
            <!-- Custom Tip Input -->
            <div class="custom-tip-input">
                <label class="form-label">Or enter custom tip amount:</label>
                <div style="position: relative;">
                    <span class="dollar-sign">$</span>
                    <input type="number" 
                           class="form-control" 
                           style="padding-left: 35px;"
                           id="customTipAmount" 
                           name="tip_amount"
                           placeholder="0.00" 
                           step="0.01" 
                           min="0"
                           value="0"
                           oninput="updateCustomTip()">
                </div>
                <input type="hidden" id="tipPercentage" name="tip_percentage" value="0">
            </div>
            
            <!-- Tip Summary -->
            <div class="tip-summary" id="tipSummary">
                <div class="tip-summary-row">
                    <span>Base Amount:</span>
                    <span id="summaryBase">${{ number_format($baseAmount, 2) }}</span>
                </div>
                <div class="tip-summary-row">
                    <span>Tip Amount:</span>
                    <span id="summaryTip" style="color: {{ $primaryColor }};">$0.00</span>
                </div>
                <div class="tip-summary-row tip-summary-total">
                    <span>Total:</span>
                    <span id="summaryTotal">${{ number_format($baseAmount, 2) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let baseAmount = {{ $baseAmount }};
let currentTipAmount = 0;
let currentTipPercentage = 0;
let processingFeePercent = {{ $processingFee ?? 2.9 }};

// Auto-select 10% tip on page load
document.addEventListener('DOMContentLoaded', function() {
    const tenPercentBtn = document.querySelector('.tip-btn[data-percentage="10"]');
    if (tenPercentBtn) {
        selectTipPercentage(tenPercentBtn);
    }
});

function toggleTipping() {
    const checkbox = document.getElementById('tipEnabled');
    checkbox.checked = !checkbox.checked;
    toggleTippingOptions();
}

function toggleTippingOptions() {
    const checkbox = document.getElementById('tipEnabled');
    const section = document.getElementById('tippingSection');
    const options = document.getElementById('tipOptions');
    
    if (checkbox.checked) {
        section.classList.add('active');
        options.classList.add('show');
        
        // Select recommended tip by default
        const recommendedBtn = document.querySelector('.tip-btn.recommended');
        if (recommendedBtn) {
            selectTipPercentage(recommendedBtn);
        }
    } else {
        section.classList.remove('active');
        options.classList.remove('show');
        clearTip();
    }
}

function selectTipPercentage(button) {
    // Remove active class from all buttons
    document.querySelectorAll('.tip-btn').forEach(btn => btn.classList.remove('active'));
    
    // Add active class to selected button
    button.classList.add('active');
    
    // Get tip data
    const percentage = parseFloat(button.getAttribute('data-percentage'));
    const amount = parseFloat(button.getAttribute('data-amount'));
    
    // Update hidden inputs
    document.getElementById('customTipAmount').value = amount.toFixed(2);
    document.getElementById('tipPercentage').value = percentage;
    
    // Update globals
    currentTipAmount = amount;
    currentTipPercentage = percentage;
    
    // Update summary
    updateTipSummary();
}

function updateCustomTip() {
    // Clear percentage button selection
    document.querySelectorAll('.tip-btn').forEach(btn => btn.classList.remove('active'));
    
    const tipAmount = parseFloat(document.getElementById('customTipAmount').value) || 0;
    currentTipAmount = tipAmount;
    
    // Calculate percentage
    if (baseAmount > 0) {
        currentTipPercentage = (tipAmount / baseAmount) * 100;
        document.getElementById('tipPercentage').value = currentTipPercentage.toFixed(2);
    }
    
    updateTipSummary();
}

function updateTipSummary() {
    const summary = document.getElementById('tipSummary');
    const totalAmount = baseAmount + currentTipAmount;
    
    document.getElementById('summaryBase').textContent = '$' + baseAmount.toFixed(2);
    document.getElementById('summaryTip').textContent = '$' + currentTipAmount.toFixed(2);
    document.getElementById('summaryTotal').textContent = '$' + totalAmount.toFixed(2);
    
    summary.style.display = currentTipAmount > 0 ? 'block' : 'none';
    
    // Update parent form's hidden fields
    const tipAmountField = document.getElementById('tip-amount-field');
    const tipPercentageField = document.getElementById('tip-percentage-field');
    const tipEnabledField = document.getElementById('tip-enabled-field');
    
    if (tipAmountField) tipAmountField.value = currentTipAmount.toFixed(2);
    if (tipPercentageField) tipPercentageField.value = currentTipPercentage.toFixed(2);
    if (tipEnabledField) tipEnabledField.value = currentTipAmount > 0 ? '1' : '0';
    
    // Update checkout page total
    updateCheckoutTotal();
}

function updateCheckoutTotal() {
    // Calculate Platform Fee amount
    const processingFee = (baseAmount / 100) * processingFeePercent;
    const newTotal = baseAmount + processingFee + currentTipAmount;
    
    // Update mobile total
    const checkoutTotal = document.getElementById('checkout-total');
    if (checkoutTotal) {
        checkoutTotal.textContent = '$' + newTotal.toFixed(2);
    }
    
    // Update desktop total
    const checkoutTotalDesktop = document.getElementById('checkout-total-desktop');
    if (checkoutTotalDesktop) {
        checkoutTotalDesktop.textContent = '$' + newTotal.toFixed(2);
    }
    
    // Update Pay Now button amounts
    const authorizeBtnAmount = document.getElementById('authorize-pay-btn-amount');
    if (authorizeBtnAmount) {
        authorizeBtnAmount.textContent = '$' + newTotal.toFixed(2);
    }
    const stripeBtnAmount = document.getElementById('stripe-pay-btn-amount');
    if (stripeBtnAmount) {
        stripeBtnAmount.textContent = '$' + newTotal.toFixed(2);
    }
    
    // Show/hide tip rows
    const tipRow = document.getElementById('tip-row');
    const tipAmountDisplay = document.getElementById('tip-amount-display');
    const tipRowDesktop = document.getElementById('tip-row-desktop');
    const tipAmountDisplayDesktop = document.getElementById('tip-amount-display-desktop');
    
    if (currentTipAmount > 0) {
        if (tipRow) tipRow.style.display = 'block';
        if (tipAmountDisplay) {
            tipAmountDisplay.style.display = 'block';
            tipAmountDisplay.textContent = '$' + currentTipAmount.toFixed(2);
        }
        if (tipRowDesktop) tipRowDesktop.style.display = 'block';
        if (tipAmountDisplayDesktop) {
            tipAmountDisplayDesktop.style.display = 'block';
            tipAmountDisplayDesktop.textContent = '$' + currentTipAmount.toFixed(2);
        }
    } else {
        if (tipRow) tipRow.style.display = 'none';
        if (tipAmountDisplay) tipAmountDisplay.style.display = 'none';
        if (tipRowDesktop) tipRowDesktop.style.display = 'none';
        if (tipAmountDisplayDesktop) tipAmountDisplayDesktop.style.display = 'none';
    }
}

function clearTip() {
    document.getElementById('customTipAmount').value = '0';
    document.getElementById('tipPercentage').value = '0';
    document.querySelectorAll('.tip-btn').forEach(btn => btn.classList.remove('active'));
    currentTipAmount = 0;
    currentTipPercentage = 0;
    document.getElementById('tipSummary').style.display = 'none';
}

// Update base amount dynamically if donation amount changes
function updateBaseAmount(newAmount) {
    baseAmount = parseFloat(newAmount) || 0;
    document.getElementById('summaryBase').textContent = '$' + baseAmount.toFixed(2);
    
    // Recalculate tip if percentage is selected
    if (currentTipPercentage > 0) {
        currentTipAmount = (baseAmount * currentTipPercentage) / 100;
        document.getElementById('customTipAmount').value = currentTipAmount.toFixed(2);
        updateTipSummary();
    }
}
</script>
