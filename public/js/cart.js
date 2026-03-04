/**
 * Shopping Cart Management System
 * Handles all frontend cart operations
 */

console.log('🛒 [CART.JS] Script starting to execute...');

// Make sure global queue exists
if (!window._cartQueue) {
    window._cartQueue = [];
}

// Define global addToCart FIRST before ShoppingCart object
window.addToCart = function(itemData) {
    // If ShoppingCart exists and is ready, add immediately
    if (window.ShoppingCart && typeof window.ShoppingCart.addItem === 'function') {
        console.log('Adding item to cart:', itemData);
        return window.ShoppingCart.addItem(itemData);
    } else {
        // Queue the item if ShoppingCart isn't ready yet
        console.warn('ShoppingCart not initialized, queueing item:', itemData);
        window._cartQueue.push(itemData);
        return true;
    }
};

console.log('🛒 [CART.JS] About to define window.ShoppingCart...');

window.ShoppingCart = {
    // Configuration
    config: {
        apiBaseUrl: '/api/cart',
        cartStorageKey: 'shopping_cart_ui_state'
    },

    // State
    state: {
        cart: null,
        cartOpen: false,
        loading: false
    },

    /**
     * Initialize cart system
     */
    async init() {
        console.log('🛒 [CART] Initializing Shopping Cart system...');
        console.log('🛒 [CART] document.readyState:', document.readyState);
        console.log('🛒 [CART] document.body exists:', !!document.body);
        
        try {
            // Create cart drawer HTML if it doesn't exist
            console.log('🛒 [CART] Creating cart drawer...');
            this.createCartDrawer();
            
            // Create floating cart button - MOVE TO FRONT (HIGHEST PRIORITY)
            console.log('🛒 [CART] Creating floating cart button...');
            this.createFloatingCartButton();
            
            // Load cart from server - DON'T BLOCK BUTTON CREATION
            console.log('🛒 [CART] Loading cart from server...');
            await this.loadCart();
            console.log('🛒 [CART] ✅ Cart loaded');
            
            // Setup event listeners
            console.log('🛒 [CART] Setting up event listeners...');
            this.setupEventListeners();
            console.log('🛒 [CART] ✅ Event listeners set up');
            
            // Update cart display
            console.log('🛒 [CART] Updating cart display...');
            this.updateCartDisplay();
            console.log('🛒 [CART] ✅ Cart display updated');
            
            // Process any queued items
            console.log('🛒 [CART] Processing ' + (window._cartQueue ? window._cartQueue.length : 0) + ' queued items...');
            await this.processQueuedItems();
            console.log('🛒 [CART] ✅ Queued items processed');
            
            console.log('✅ [CART] Shopping Cart initialized successfully');
        } catch (error) {
            console.error('❌ [CART] Error during initialization:', error);
            console.error('❌ [CART] Stack:', error.stack);
        }
    },

    /**
     * Process items that were queued before initialization
     */
    async processQueuedItems() {
        if (window._cartQueue && window._cartQueue.length > 0) {
            console.log('🛒 [INIT] Processing queued items:', window._cartQueue.length);
            // Process each queued item directly
            for (const item of window._cartQueue) {
                await this.addItem(item);
            }
            window._cartQueue = [];
        }
    },

    /**
     * Create cart drawer HTML if it doesn't exist
     */
    createCartDrawer() {
        console.log('🛒 [CART] createCartDrawer() called');
        
        // Check if cart drawer already exists
        if (document.getElementById('cartDrawer')) {
            console.log('✅ [CART] Cart drawer already exists, skipping');
            return;
        }

        // Check if body exists - if not, wait for DOM to be ready
        if (!document.body) {
            console.error('❌ [CART] Body not ready yet, cannot create drawer');
            return;
        }

        try {
            console.log('🛒 [CART] Creating cart drawer HTML...');

            // Create drawer HTML
            const drawerHTML = `
                <div id="cartOverlay"></div>
                <div id="cartDrawer">
                    <div id="cartHeader">
                        <h3>Shopping Cart</h3>
                        <button id="cartCloseBtn">×</button>
                    </div>
                    <div id="cartContent"></div>
                    <div id="cartFooter"></div>
                </div>
            `;

            // Inject into body
            document.body.insertAdjacentHTML('beforeend', drawerHTML);
            console.log('✅ [CART] Cart drawer HTML injected');
            
            // Verify elements were created
            const drawerEl = document.getElementById('cartDrawer');
            const overlayEl = document.getElementById('cartOverlay');
            
            if (!drawerEl) {
                console.error('❌ [CART] cartDrawer element was not created');
                return;
            }
            if (!overlayEl) {
                console.error('❌ [CART] cartOverlay element was not created');
                return;
            }
            
            console.log('✅ [CART] Both drawer and overlay elements verified in DOM');
            
            // Apply inline styles as backup (in case CSS doesn't work)
            drawerEl.style.cssText = `
                position: fixed !important;
                top: 0 !important;
                right: -500px !important;
                width: 400px !important;
                max-width: 90vw !important;
                height: 100vh !important;
                background: white !important;
                z-index: 10000 !important;
                box-shadow: -2px 0 8px rgba(0, 0, 0, 0.15) !important;
                transition: right 0.35s ease-out !important;
                overflow-y: auto !important;
                display: flex !important;
                flex-direction: column !important;
            `;
            
            console.log('✅ [CART] Inline styles applied to drawer');

        
        if (overlayEl) {
            overlayEl.style.cssText = `
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                right: 0 !important;
                bottom: 0 !important;
                background: rgba(0, 0, 0, 0.5) !important;
                z-index: 9999 !important;
                display: none !important;
                opacity: 0 !important;
                transition: opacity 0.3s ease !important;
            `;
        }
        
        console.log('Inline styles applied to drawer and overlay');

        // Add CSS for cart drawer
        const style = document.createElement('style');
        style.id = 'cart-drawer-styles';
        style.textContent = `
            /* Cart Overlay - CRITICAL: Must be display: none by default */
            #cartOverlay {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                right: 0 !important;
                bottom: 0 !important;
                background: rgba(0, 0, 0, 0.5) !important;
                z-index: 9999 !important;
                display: none !important;
                opacity: 0 !important;
                transition: opacity 0.3s ease !important;
                visibility: hidden !important;
            }
            
            /* CRITICAL: When overlay has open class, MUST override display: none */
            #cartOverlay.open {
                display: block !important;
                opacity: 1 !important;
                visibility: visible !important;
            }
            
            /* Cart Drawer */
            #cartDrawer {
                position: fixed !important;
                top: 0 !important;
                right: -500px !important;
                width: 400px !important;
                max-width: 90vw !important;
                height: 100vh !important;
                background: white !important;
                z-index: 10000 !important;
                box-shadow: -2px 0 8px rgba(0, 0, 0, 0.15) !important;
                transition: right 0.35s ease-out !important;
                overflow-y: auto !important;
                display: flex !important;
                flex-direction: column !important;
            }
            
            /* CRITICAL: Drawer must slide to right: 0 when open */
            #cartDrawer.open {
                right: 0 !important;
            }

            #cartHeader {
                padding: 20px;
                border-bottom: 1px solid #eee;
                display: flex;
                justify-content: space-between;
                align-items: center;
                background: white;
                position: sticky;
                top: 0;
                z-index: 10;
                flex-shrink: 0;
            }
            
            #cartHeader h3 {
                margin: 0;
                font-size: 18px;
                font-weight: 600;
            }
            
            #cartCloseBtn {
                background: none !important;
                border: none !important;
                font-size: 28px !important;
                cursor: pointer !important;
                color: #333 !important;
                padding: 0 !important;
                width: 30px !important;
                height: 30px !important;
                pointer-events: all !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
            }
            
            #cartCloseBtn:hover {
                color: #d32f2f !important;
                transform: scale(1.2) !important;
            }
            
            #cartContent {
                flex: 1;
                overflow-y: auto;
                padding: 20px;
            }
            
            #cartFooter {
                padding: 20px;
                border-top: 1px solid #eee;
                background: #f9f9f9;
                flex-shrink: 0;
            }
            
            .cart-empty {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                height: 300px;
                color: #999;
                text-align: center;
            }
            
            .cart-empty i {
                font-size: 48px;
                margin-bottom: 20px;
            }
            
            .cart-items {
                display: flex;
                flex-direction: column;
                gap: 15px;
            }
            
            .cart-item {
                padding: 15px;
                border: 1px solid #eee;
                border-radius: 4px;
                background: #fafafa;
            }
            
            .cart-item-header {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                margin-bottom: 10px;
            }
            
            .cart-item-header h4 {
                margin: 0;
                font-size: 14px;
                font-weight: 600;
                flex: 1;
            }
            
            .btn-remove {
                background: none;
                border: none;
                color: #999;
                cursor: pointer;
                padding: 5px;
            }
            
            .btn-remove:hover {
                color: #d32f2f;
            }
            
            .cart-item-controls {
                margin: 10px 0;
                font-size: 13px;
            }
            
            .cart-item-controls label {
                display: block;
                margin-bottom: 5px;
                font-weight: 500;
                color: #555;
            }
            
            .quantity-control {
                display: flex;
                align-items: center;
                gap: 5px;
            }
            
            .btn-qty {
                background: #f0f0f0;
                border: 1px solid #ddd;
                padding: 5px 8px;
                cursor: pointer;
                border-radius: 3px;
                width: 30px;
                height: 30px;
            }
            
            .btn-qty:hover {
                background: #e0e0e0;
            }
            
            .cart-item-total {
                font-weight: bold;
                margin-top: 10px;
                color: #667eea;
            }
            
            .cart-summary {
                margin: 20px 0;
                padding-bottom: 20px;
                border-bottom: 1px solid #eee;
            }
            
            .cart-summary-row {
                display: flex;
                justify-content: space-between;
                margin-bottom: 10px;
                font-size: 14px;
            }
            
            .cart-summary-total {
                display: flex;
                justify-content: space-between;
                font-size: 16px;
                font-weight: bold;
                color: #667eea;
            }
            
            .cart-actions {
                display: flex;
                gap: 10px;
                flex-direction: column;
            }
            
            .btn-checkout {
                background: #667eea;
                color: white;
                border: none;
                padding: 12px 20px;
                border-radius: 4px;
                cursor: pointer;
                font-weight: bold;
            }
            
            .btn-checkout:hover {
                background: #5568d3;
            }
            
            .btn-clear {
                background: #f0f0f0;
                color: #333;
                border: none;
                padding: 12px 20px;
                border-radius: 4px;
                cursor: pointer;
            }
            
            .btn-clear:hover {
                background: #e0e0e0;
            }
            
            @media (max-width: 768px) {
                #cartDrawer {
                    width: 100%;
                    right: -100%;
                }
            }
        `;
        document.head.appendChild(style);
        console.log('✅ [CART] Cart drawer CSS added to page');
        console.log('✅ [CART] Style tag ID:', style.id);
        console.log('✅ [CART] Style tag content length:', style.textContent.length);
        
        } catch (error) {
            console.error('❌ [CART] Error creating cart drawer:', error);
            console.error('❌ [CART] Stack:', error.stack);
        }
    },

    /**
     * Load cart from server
     */
    async loadCart() {
        try {
            console.log('🛒 [CART] Loading cart from:', this.config.apiBaseUrl);
            const response = await fetch(`${this.config.apiBaseUrl}`);
            
            if (!response.ok) {
                console.warn('⚠️ [CART] Cart API returned status:', response.status);
                return null;
            }
            
            const data = await response.json();
            
            console.log('🛒 [CART] Cart loaded, response:', data);
            
            if (data.success && data.cart) {
                this.state.cart = data.cart;
                console.log('✅ [CART] Cart state set');
                return data.cart;
            } else {
                console.warn('⚠️ [CART] Cart load unsuccessful:', data);
                return null;
            }
        } catch (error) {
            console.error('⚠️ [CART] Error loading cart (non-blocking):', error.message);
            return null;
        }
    },

    /**
     * Create floating cart button in bottom right
     */
    createFloatingCartButton() {
        console.log('🎯 createFloatingCartButton() called');
        
        // Check if already exists
        if (document.getElementById('floatingCartButton')) {
            console.log('✅ Cart button already exists, skipping');
            return;
        }

        // Make absolutely sure body exists
        if (!document.body) {
            console.error('❌ document.body does not exist yet');
            return;
        }

        try {
            const cartBtn = document.createElement('div');
            cartBtn.id = 'floatingCartButton';
            cartBtn.style.cssText = `
                position: fixed;
                bottom: 30px;
                right: 30px;
                width: 60px;
                height: 60px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
                z-index: 9998;
                transition: all 0.3s ease;
                font-size: 24px;
            `;

            // Add HTML content
            cartBtn.innerHTML = `
                <i class="fas fa-shopping-cart" style="color: white;"></i>
                <div id="cartBadge" style="
                    position: absolute;
                    top: -8px;
                    right: -8px;
                    background: #ff6b6b;
                    color: white;
                    border-radius: 50%;
                    width: 28px;
                    height: 28px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 12px;
                    font-weight: bold;
                    border: 2px solid white;
                ">0</div>
            `;

            // Add hover effects
            cartBtn.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.1)';
                this.style.boxShadow = '0 6px 16px rgba(102, 126, 234, 0.6)';
            });

            cartBtn.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
                this.style.boxShadow = '0 4px 12px rgba(102, 126, 234, 0.4)';
            });

            // Click handler to navigate to cart page
            cartBtn.addEventListener('click', function(e) {
                console.log('🛒 Cart button clicked - navigating to cart page');
                e.preventDefault();
                e.stopPropagation();
                // Navigate to cart page
                window.location.href = '/cart';
            });

            // Append to body
            document.body.appendChild(cartBtn);
            console.log('✅ Cart button created and appended to body');
            
            // Verify it was actually added
            if (document.getElementById('floatingCartButton')) {
                console.log('✅ Cart button verified in DOM');
            } else {
                console.error('❌ Cart button was not added to DOM');
            }
        } catch (error) {
            console.error('❌ Error creating cart button:', error);
        }
    },

    /**
     * Update floating cart badge with count
     */
    updateCartBadge() {
        const badge = document.getElementById('cartBadge');
        if (badge && this.state.cart) {
            // Use item_count from cart API response, which properly counts items
            let count = this.state.cart.item_count || 0;
            
            // Fallback: count items if item_count not available
            if (count === 0) {
                if (Array.isArray(this.state.cart.items)) {
                    count = this.state.cart.items.length;
                } else if (typeof this.state.cart.items === 'object') {
                    count = Object.keys(this.state.cart.items).length;
                }
            }
            
            badge.textContent = count;
            console.log('🔄 Cart badge updated - Item count:', count);
            
            // Show/hide badge
            if (count > 0) {
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        }
    },

    /**
     * Add item to cart - direct API call
     */
    async addItem(itemData) {
        if (!itemData.type || !itemData.id || !itemData.name) {
            console.error('Invalid item data for cart', itemData);
            return false;
        }

        console.log('🛒 Adding item to cart:', itemData.name);

        try {
            const response = await fetch(`${this.config.apiBaseUrl}/add`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCSRFToken()
                },
                body: JSON.stringify(itemData)
            });

            if (!response.ok) {
                console.error('❌ HTTP Error:', response.status);
                this.showNotification(`Failed to add ${itemData.name}`, 'error');
                return false;
            }

            const data = await response.json();

            if (data.success && data.cart) {
                this.state.cart = data.cart;
                this.updateCartDisplay();
                this.updateCartBadge();
                console.log('✅ Added:', itemData.name);
                this.showNotification(`${itemData.name} added!`, 'success');
                return true;
            }

            console.error('❌ API returned error:', data.message);
            this.showNotification(data.message || 'Failed to add item', 'error');
            return false;
        } catch (error) {
            console.error('❌ Error adding item:', error);
            this.showNotification('Error adding item', 'error');
            return false;
        }
    },




    /**
     * Remove item from cart
     */
    async removeItem(itemKey) {
        try {
            this.state.loading = true;
            console.log('🗑️ Removing item with key:', itemKey);

            const response = await fetch(`${this.config.apiBaseUrl}/item/${itemKey}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': this.getCSRFToken()
                }
            });

            const data = await response.json();
            console.log('Remove item response:', data);

            if (data.success) {
                this.state.cart = data.cart;
                this.updateCartDisplay();
                this.showNotification('Item removed from cart', 'success');
                return true;
            } else {
                console.error('❌ Remove failed:', data.message);
                this.showNotification(data.message || 'Failed to remove item', 'error');
            }
        } catch (error) {
            console.error('Error removing item:', error);
            this.showNotification('Error removing item', 'error');
        } finally {
            this.state.loading = false;
        }

        return false;
    },

    /**
     * Update item in cart
     */
    async updateItem(itemKey, updates) {
        try {
            this.state.loading = true;

            const response = await fetch(`${this.config.apiBaseUrl}/item/${itemKey}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCSRFToken()
                },
                body: JSON.stringify(updates)
            });

            const data = await response.json();

            if (data.success) {
                this.state.cart = data.cart;
                this.updateCartDisplay();
                return true;
            }
        } catch (error) {
            console.error('Error updating item:', error);
        } finally {
            this.state.loading = false;
        }

        return false;
    },

    /**
     * Clear entire cart
     */
    async clearCart() {
        if (!confirm('Are you sure you want to clear your cart?')) {
            return false;
        }

        try {
            this.state.loading = true;

            const response = await fetch(`${this.config.apiBaseUrl}/clear`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': this.getCSRFToken()
                }
            });

            const data = await response.json();

            if (data.success) {
                this.state.cart = data.cart;
                this.updateCartDisplay();
                this.showNotification('Cart cleared', 'success');
                return true;
            }
        } catch (error) {
            console.error('Error clearing cart:', error);
        } finally {
            this.state.loading = false;
        }

        return false;
    },

    /**
     * Validate cart for checkout
     */
    async validateForCheckout() {
        try {
            const response = await fetch(`${this.config.apiBaseUrl}/validate`);
            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Error validating cart:', error);
            return { valid: false, message: 'Validation failed' };
        }
    },

    /**
     * Toggle cart drawer open/close
     */
    toggleCartDrawer() {
        this.state.cartOpen = !this.state.cartOpen;
        console.log('🎯 Toggle cart drawer - cartOpen:', this.state.cartOpen);
        
        const drawer = document.getElementById('cartDrawer');
        const overlay = document.getElementById('cartOverlay');
        
        if (!drawer || !overlay) {
            console.error('❌ Cart drawer or overlay not found!');
            return;
        }
        
        if (this.state.cartOpen) {
            console.log('📂 OPENING drawer');
            drawer.classList.add('open');
            overlay.classList.add('open');
        } else {
            console.log('📁 CLOSING drawer');
            drawer.classList.remove('open');
            overlay.classList.remove('open');
        }
    },

    /**
     * Update cart UI display
     */
    updateCartDisplay() {
        // Log cart state for debugging
        console.log('Updating cart display. Cart state:', this.state.cart);
        
        // Update cart count badge
        const cartIcon = document.getElementById('cartIcon');
        const cartCount = document.getElementById('cartCount');
        
        // Calculate item count from items_by_type
        let itemCount = this.state.cart?.item_count || 0;
        console.log('Item count from API:', itemCount);

        if (cartIcon && itemCount > 0) {
            cartIcon.classList.add('has-items');
        } else if (cartIcon) {
            cartIcon.classList.remove('has-items');
        }

        if (cartCount) {
            cartCount.textContent = itemCount;
            cartCount.style.display = itemCount > 0 ? 'flex' : 'none';
        }

        // Update floating cart badge
        this.updateCartBadge();

        // Update cart drawer content
        this.updateCartDrawer();
    },

    /**
     * Update cart drawer/modal content
     */
    updateCartDrawer() {
        const contentDiv = document.getElementById('cartContent');
        const footerDiv = document.getElementById('cartFooter');
        
        if (!contentDiv || !footerDiv) {
            console.error('Cart content or footer div not found');
            return;
        }

        console.log('=== UPDATING CART DRAWER ===');
        console.log('Full cart state:', this.state.cart);
        console.log('items_by_type:', this.state.cart?.items_by_type);

        // Get items from items_by_type (which contains student, ticket, product, auction items)
        const itemsByType = this.state.cart?.items_by_type || {};
        console.log('Items by type:', itemsByType);

        // Flatten all items from all types into a single list
        let allItems = {};
        let itemIndex = 0;
        
        for (const [type, typeItems] of Object.entries(itemsByType)) {
            console.log(`Processing type: ${type}`, typeItems);
            if (Array.isArray(typeItems)) {
                typeItems.forEach(item => {
                    // Add type to item if it doesn't have it
                    if (!item.type) item.type = type;
                    allItems[`${type}-${itemIndex}`] = item;
                    itemIndex++;
                });
            }
        }

        const total = this.state.cart?.total || 0;
        console.log('Total price:', total);
        console.log('Total items flattened:', Object.keys(allItems).length);

        // Empty cart message
        if (Object.keys(allItems).length === 0) {
            console.log('Cart is EMPTY - showing empty state');
            contentDiv.innerHTML = `
                <div class="cart-empty">
                    <i class="fas fa-shopping-cart"></i>
                    <p>Your cart is empty</p>
                    <p class="text-muted">Add items to get started</p>
                </div>
            `;
            footerDiv.innerHTML = '';
            return;
        }

        console.log('Cart has items - building display');

        // Build cart items list
        let itemsHTML = '<div class="cart-items">';

        for (const [key, item] of Object.entries(allItems)) {
            const itemTotal = this.calculateItemTotal(item);
            console.log(`Item ${key}:`, item, 'Total:', itemTotal);
            itemsHTML += `
                <div class="cart-item" data-item-key="${item.key}">
                    <div class="cart-item-header">
                        <h4>${item.name}</h4>
                        <button class="btn-remove" onclick="window.ShoppingCart.removeItem('${item.key}')" title="Remove">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    
                    ${this.getItemControlsHTML(item, key)}
                    
                    <div class="cart-item-total">
                        $${itemTotal.toFixed(2)}
                    </div>
                </div>
            `;
        }

        itemsHTML += '</div>';

        // Update content area
        contentDiv.innerHTML = itemsHTML;

        // Build summary and checkout buttons in footer
        const footerHTML = `
            <div class="cart-summary">
                <div class="cart-summary-row">
                    <span>Subtotal:</span>
                    <span>$${total.toFixed(2)}</span>
                </div>
                <div class="cart-summary-total">
                    <span>Total:</span>
                    <span class="total-amount">$${total.toFixed(2)}</span>
                </div>
            </div>
            <div class="cart-actions">
                <button class="btn btn-primary btn-checkout" onclick="window.ShoppingCart.proceedToCheckout()">
                    Proceed to Checkout
                </button>
                <button class="btn btn-secondary btn-clear" onclick="window.ShoppingCart.clearCart()">
                    Clear Cart
                </button>
            </div>
        `;

        footerDiv.innerHTML = footerHTML;
        console.log('Cart drawer updated');
    },

    /**
     * Get item controls HTML based on type
     */
    getItemControlsHTML(item, itemKey) {
        if (item.type === 'student') {
            // Student donation amount input
            return `
                <div class="cart-item-controls">
                    <label>Donation Amount:</label>
                    <div class="input-group">
                        <span class="input-addon">$</span>
                        <input type="number" 
                               class="form-control" 
                               value="${item.amount}" 
                               step="0.01" 
                               min="0"
                               onchange="ShoppingCart.updateItem('${itemKey}', { amount: this.value })">
                    </div>
                </div>
            `;
        } else {
            // Quantity input for other items
            return `
                <div class="cart-item-controls">
                    <label>Quantity:</label>
                    <div class="quantity-control">
                        <button onclick="window.ShoppingCart.updateItem('${itemKey}', { quantity: Math.max(1, ${item.quantity} - 1) })" class="btn-qty">−</button>
                        <input type="number" 
                               class="qty-input" 
                               value="${item.quantity}" 
                               min="1"
                               onchange="window.ShoppingCart.updateItem('${itemKey}', { quantity: this.value })">
                        <button onclick="window.ShoppingCart.updateItem('${itemKey}', { quantity: ${item.quantity} + 1 })" class="btn-qty">+</button>
                    </div>
                    <small class="price-per-item">
                        ${item.type === 'student' ? 
                            `$${(item.amount || 0).toFixed(2)}` : 
                            `$${(item.price || item.current_bid || 0).toFixed(2)}`
                        } each
                    </small>
                </div>
            `;
        }
    },

    /**
     * Calculate item total
     */
    calculateItemTotal(item) {
        let price = 0;
        
        if (item.type === 'student') {
            price = item.amount || 0;
        } else if (item.type === 'ticket' || item.type === 'product') {
            price = item.price || 0;
        } else if (item.type === 'auction') {
            price = item.current_bid || item.price || 0;
        }

        return price * (item.quantity || 1);
    },

    /**
     * Proceed to checkout
     */
    async proceedToCheckout() {
        console.log('🛒 CHECKOUT CLICKED - Validating cart...');
        
        // ============================================================================
        // CONFIGURATION FLAG: Set to true to require authentication before checkout
        // Set to false to allow guests to proceed directly to checkout form
        // ============================================================================
        const REQUIRE_AUTH_FOR_CHECKOUT = false;  // Change to true to re-enable auth modal
        
        // Validate cart
        const validation = await this.validateForCheckout();
        console.log('✅ Validation result:', validation);

        if (!validation.valid) {
            console.error('❌ Cart validation failed:', validation.message);
            this.showNotification(validation.message, 'error');
            return;
        }

        // Optional: Check if user is authenticated (only if REQUIRE_AUTH_FOR_CHECKOUT is true)
        if (REQUIRE_AUTH_FOR_CHECKOUT) {
            try {
                const authCheck = await fetch('/ajax/ticket-auth/check', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                const authStatus = await authCheck.json();
                
                console.log('Auth status:', authStatus);
                
                if (!authStatus.authenticated) {
                    // User NOT authenticated - open auth modal and STOP
                    console.log('🔐 User NOT authenticated, opening auth modal on current page...');
                    
                    // Store the checkout URL for redirect after successful login
                    window.checkoutRedirectUrl = '/checkout';
                    
                    // Open the auth modal (prefer custom handler, fallback to Bootstrap or inline display)
                    const authModal = document.getElementById('authModal');
                    if (typeof window.openAuthModal === 'function') {
                        window.openAuthModal();
                    } else if (authModal && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                        const modal = new bootstrap.Modal(authModal);
                        modal.show();
                    } else if (authModal) {
                        authModal.classList.remove('hidden');
                        authModal.style.display = 'flex';
                    }
                    // IMPORTANT: Return here to prevent redirect
                    return;
                } else {
                    // User IS authenticated, safe to proceed
                    console.log('✅ User authenticated, proceeding to checkout...');
                }
            } catch (error) {
                console.error('❌ Error checking authentication:', error);
                this.showNotification('Error checking authentication. Please try again.', 'error');
                return;
            }
        } else {
            // Auth check disabled - allowing guest checkout
            console.log('✅ Guest checkout enabled, proceeding directly to checkout form...');
        }

        // Redirect to checkout (authenticated users or guests)
        console.log('🎯 Redirecting to checkout page...');
        window.location.href = '/checkout';
    },

    /**
     * Setup event listeners
     */
    setupEventListeners() {
        // Cart icon button
        const cartIcon = document.getElementById('cartIcon');
        if (cartIcon) {
            cartIcon.addEventListener('click', () => this.toggleCartDrawer());
        }

        // Close drawer when clicking outside
        document.addEventListener('click', (e) => {
            const drawer = document.getElementById('cartDrawer');
            if (drawer && this.state.cartOpen && 
                !drawer.contains(e.target) && 
                !document.getElementById('cartIcon')?.contains(e.target)) {
                this.state.cartOpen = false;
                drawer.classList.remove('open');
            }
        });

        // Close drawer on ESC key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.state.cartOpen) {
                this.state.cartOpen = false;
                const drawer = document.getElementById('cartDrawer');
                if (drawer) {
                    drawer.classList.remove('open');
                }
            }
        });
    },

    /**
     * Get CSRF token from meta tag or input
     */
    getCSRFToken() {
        const token = document.querySelector('meta[name="csrf-token"]')?.content ||
                      document.querySelector('input[name="_token"]')?.value;
        return token || '';
    },

    /**
     * Show notification
     */
    showNotification(message, type = 'info') {
        // Check if there's a toast/notification container
        let container = document.getElementById('cartNotifications');
        if (!container) {
            container = document.createElement('div');
            container.id = 'cartNotifications';
            container.style.cssText = `
                position: fixed;
                top: 80px;
                right: 20px;
                z-index: 9999;
                max-width: 400px;
            `;
            document.body.appendChild(container);
        }

        const notification = document.createElement('div');
        notification.className = `cart-notification notification-${type}`;
        notification.style.cssText = `
            background: ${type === 'success' ? '#d4edda' : type === 'error' ? '#f8d7da' : '#d1ecf1'};
            color: ${type === 'success' ? '#155724' : type === 'error' ? '#721c24' : '#0c5460'};
            border: 1px solid ${type === 'success' ? '#c3e6cb' : type === 'error' ? '#f5c6cb' : '#bee5eb'};
            padding: 12px 16px;
            border-radius: 4px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideIn 0.3s ease;
        `;

        const icon = type === 'success' ? '✓' : type === 'error' ? '✕' : 'ℹ';
        notification.innerHTML = `<span>${icon}</span> <span>${message}</span>`;

        container.appendChild(notification);

        // Auto remove after 4 seconds
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }, 4000);
    }
};

// Expose ShoppingCart as both window.cart and window.ShoppingCart for easier access
window.cart = window.ShoppingCart;

console.log('✅ [CART.JS] window.ShoppingCart object defined successfully');
console.log('🛒 [CART.JS] ShoppingCart methods:', Object.keys(window.ShoppingCart));

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

// Initialize cart when DOM is ready - BULLETPROOF VERSION
console.log('🛒 [CART] Script loaded, document.readyState:', document.readyState);

function initializeCart() {
    console.log('🛒 [CART] === INITIALIZING CART ===');
    
    // Verify jQuery is loaded
    if (typeof jQuery === 'undefined' && typeof $ === 'undefined') {
        console.warn('⚠️ [CART] jQuery not detected, retrying in 100ms...');
        setTimeout(initializeCart, 100);
        return;
    }
    
    // Verify ShoppingCart exists
    if (!window.ShoppingCart) {
        console.error('❌ [CART] ShoppingCart object not found on window');
        return;
    }
    
    // Verify init method exists
    if (typeof window.ShoppingCart.init !== 'function') {
        console.error('❌ [CART] ShoppingCart.init is not a function');
        return;
    }
    
    // Verify body exists
    if (!document.body) {
        console.warn('⚠️ [CART] document.body does not exist yet, retrying in 100ms...');
        setTimeout(initializeCart, 100);
        return;
    }
    
    console.log('✅ [CART] All checks passed, calling ShoppingCart.init()');
    window.ShoppingCart.init();
}

// Execute initialization based on document state
console.log('🛒 [CART.JS] Reached end of file, about to set up initialization');
console.log('🛒 [CART.JS] document.readyState:', document.readyState);
console.log('🛒 [CART.JS] window.ShoppingCart exists:', !!window.ShoppingCart);
console.log('🛒 [CART.JS] window.ShoppingCart.init exists:', !!(window.ShoppingCart && window.ShoppingCart.init));

if (document.readyState === 'loading') {
    console.log('🛒 [CART] DOM still loading, waiting for DOMContentLoaded...');
    document.addEventListener('DOMContentLoaded', initializeCart);
} else {
    console.log('🛒 [CART] DOM already loaded, initializing immediately...');
    // Use setTimeout to ensure the script is fully parsed
    setTimeout(initializeCart, 10);
}

console.log('🛒 [CART.JS] Script execution complete');
