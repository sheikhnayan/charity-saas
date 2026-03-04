<!-- Cart Icon and Drawer Component -->
<style>
    /* Inline styles for cart icon/drawer - will be overridden by public/css/cart.css -->
    #cartIcon {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 998;
    }

    #cartDrawer {
        position: fixed;
        top: 0;
        right: -400px;
        z-index: 999;
        transition: right 0.3s ease;
    }
</style>

<!-- Floating Cart Icon -->
<button id="cartIcon" class="cart-icon" title="Shopping Cart">
    <i class="fas fa-shopping-cart"></i>
    <span id="cartCount" class="cart-count">0</span>
</button>

<!-- Cart Drawer / Modal -->
<div id="cartDrawer" class="cart-drawer">
    <div class="cart-drawer-header">
        <h3>Shopping Cart</h3>
        <button class="cart-drawer-close" onclick="ShoppingCart.toggleCartDrawer()" title="Close">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <!-- Cart items will be rendered here by JavaScript -->
    <div class="cart-empty">
        <i class="fas fa-shopping-cart"></i>
        <p>Your cart is empty</p>
        <p class="text-muted">Add items to get started</p>
    </div>
</div>

<!-- Overlay to close drawer -->
<div id="cartOverlay" class="cart-overlay" style="
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 997;
    display: none;
    transition: display 0.3s ease;
    cursor: pointer;
" onclick="ShoppingCart.toggleCartDrawer()"></div>

<script>
    // Show/hide overlay when cart drawer opens/closes
    const originalToggle = ShoppingCart.toggleCartDrawer;
    ShoppingCart.toggleCartDrawer = function() {
        originalToggle.call(this);
        const overlay = document.getElementById('cartOverlay');
        if (overlay) {
            overlay.style.display = this.state.cartOpen ? 'block' : 'none';
        }
    };
</script>
