<!-- Add to Cart Button Component -->
<!-- 
    Usage:
    @include('components.add-to-cart-btn', [
        'itemType' => 'student',           // student, ticket, auction, product
        'itemId' => 5,
        'itemName' => 'John Doe',
        'amount' => 100,                   // for students
        'price' => 25,                     // for tickets, products, auctions
        'currentBid' => 150,               // for auctions (optional)
        'buttonClass' => 'btn-primary',    // optional CSS class
        'buttonText' => 'Donate Now'       // optional custom text
    ])
-->

@php
    $buttonText = $buttonText ?? ($itemType === 'student' ? 'Donate Now' : 'Add to Cart');
    $buttonClass = $buttonClass ?? 'btn-add-to-cart';
    $itemType = $itemType ?? 'product';
    $itemId = $itemId ?? null;
    $itemName = $itemName ?? 'Item';
@endphp

<button class="add-to-cart-btn {{ $buttonClass }}" 
        type="button"
        onclick="ShoppingCart.addItem({
            type: '{{ $itemType }}',
            id: {{ $itemId }},
            name: '{{ addslashes($itemName) }}',
            @if($itemType === 'student')
                amount: {{ $amount ?? 100 }}
            @else
                price: {{ $price ?? 0 }},
                quantity: 1
                @if($itemType === 'auction')
                    , current_bid: {{ $currentBid ?? $price ?? 0 }}
                @endif
            @endif
        })"
        title="Add {{ $itemName }} to cart">
    <i class="fas fa-shopping-cart"></i>
    <span>{{ $buttonText }}</span>
</button>

<style>
    .add-to-cart-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .add-to-cart-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }

    .add-to-cart-btn:active {
        transform: translateY(0);
    }

    .add-to-cart-btn i {
        font-size: 16px;
    }

    .add-to-cart-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    /* Alternative button styles */
    .add-to-cart-btn.btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .add-to-cart-btn.btn-secondary {
        background: #ecf0f1;
        color: #7f8c8d;
    }

    .add-to-cart-btn.btn-secondary:hover {
        background: #dfe6e9;
        color: #2c3e50;
    }

    .add-to-cart-btn.btn-outline {
        background: transparent;
        border: 2px solid #667eea;
        color: #667eea;
    }

    .add-to-cart-btn.btn-outline:hover {
        background: #667eea;
        color: white;
    }

    .add-to-cart-btn.btn-sm {
        padding: 8px 12px;
        font-size: 12px;
    }

    .add-to-cart-btn.btn-lg {
        padding: 12px 28px;
        font-size: 16px;
    }
</style>
