<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart System Test - Final</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { padding: 20px; background: #f5f5f5; }
        .test-container { max-width: 800px; margin: auto; }
        .test-box { background: white; padding: 20px; margin: 10px 0; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .test-title { font-weight: bold; color: #333; margin-bottom: 10px; font-size: 16px; }
        .test-log { background: #f8f9fa; padding: 10px; border-radius: 3px; margin: 5px 0; font-family: monospace; font-size: 12px; max-height: 300px; overflow-y: auto; }
        .log-info { color: #0066cc; }
        .log-success { color: #28a745; }
        .log-error { color: #dc3545; }
        .log-warn { color: #ff6600; }
        button { margin: 5px; }
        .alert { margin-top: 10px; }
    </style>
</head>
<body>
    <div class="test-container">
        <h1>🛒 Shopping Cart System - Animation & Removal Test</h1>
        
        <div class="test-box">
            <div class="test-title">📝 Console Log (Last 20 events)</div>
            <div id="testLog" class="test-log"></div>
        </div>

        <div class="test-box">
            <div class="test-title">🧪 Test Actions</div>
            <button class="btn btn-primary" onclick="addTestItem('student')">Add Student</button>
            <button class="btn btn-info" onclick="addTestItem('ticket')">Add Ticket</button>
            <button class="btn btn-warning" onclick="addTestItem('product')">Add Product</button>
            <hr/>
            <button class="btn btn-success" onclick="testCartDrawer()">🎯 CLICK CART BUTTON</button>
            <button class="btn btn-danger" onclick="testRemoveItem()">🗑️ TEST REMOVE ITEM</button>
            <button class="btn btn-secondary" onclick="clearLog()">Clear Log</button>
        </div>

        <div class="test-box">
            <div class="test-title">📊 Cart Status</div>
            <div id="cartStatus">Waiting for initialization...</div>
        </div>

        <div class="alert alert-info">
            <strong>Testing Cart Drawer Animation:</strong>
            <ol>
                <li>Click "Add Student" to add items to cart</li>
                <li>Click "🎯 CLICK CART BUTTON" to toggle the drawer</li>
                <li>Watch for "Drawer classes after open:" in the log - should contain "open" class</li>
                <li>The drawer should slide in from the right</li>
            </ol>
        </div>

        <div class="alert alert-warning">
            <strong>Testing Item Removal:</strong>
            <ol>
                <li>Click "🗑️ TEST REMOVE ITEM" after adding items</li>
                <li>Watch console - should show the item.key being sent (like "ticket_14", not "ticket-2")</li>
                <li>Item should be removed from cart</li>
            </ol>
        </div>
    </div>

    <!-- jQuery (must be before cart.js) -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    
    <!-- Cart System -->
    <script src="/js/cart.js"></script>

    <script>
        const testLog = document.getElementById('testLog');
        let logEntries = [];
        const MAX_LOGS = 20;
        
        function log(message, type = 'info') {
            const timestamp = new Date().toLocaleTimeString();
            const className = `log-${type}`;
            const logEntry = `[${timestamp}] ${message}`;
            logEntries.unshift({ text: logEntry, type: className });
            
            if (logEntries.length > MAX_LOGS) {
                logEntries.pop();
            }
            
            renderLogs();
            console.log(`[${type.toUpperCase()}] ${message}`);
        }

        function renderLogs() {
            testLog.innerHTML = logEntries.map(entry => 
                `<div class="test-log ${entry.type}">${entry.text}</div>`
            ).join('');
        }

        function clearLog() {
            logEntries = [];
            testLog.innerHTML = '';
            log('Log cleared', 'info');
        }

        function updateCartStatus() {
            const status = document.getElementById('cartStatus');
            if (window.ShoppingCart && window.ShoppingCart.state && window.ShoppingCart.state.cart) {
                const cart = window.ShoppingCart.state.cart;
                status.innerHTML = `
                    <strong>Items:</strong> ${cart.item_count || 0}<br>
                    <strong>Total:</strong> $${(cart.total || 0).toFixed(2)}<br>
                    <strong>Drawer Open:</strong> ${window.ShoppingCart.state.cartOpen ? '✅ YES' : '❌ NO'}<br>
                    <strong>Status:</strong> Ready ✅
                `;
            } else {
                status.innerHTML = '<strong>Status:</strong> Initializing... ⏳';
            }
        }

        function addTestItem(type) {
            if (!window.ShoppingCart) {
                log('ShoppingCart not initialized yet!', 'error');
                return;
            }

            const items = {
                student: { id: Math.random().toString().slice(2, 5), name: 'Test Student', type: 'student', price: 0, quantity: 1 },
                ticket: { id: Math.random().toString().slice(2, 5), name: 'Test Ticket', type: 'ticket', price: 50, quantity: 1 },
                product: { id: Math.random().toString().slice(2, 5), name: 'Test Product', type: 'product', price: 30, quantity: 1 }
            };

            const item = items[type];
            if (item) {
                log(`Adding ${type} item: ${item.name}`, 'info');
                window.ShoppingCart.addItem(item);
                setTimeout(updateCartStatus, 500);
            }
        }

        function testCartDrawer() {
            log('Testing cart drawer toggle...', 'info');
            if (window.ShoppingCart) {
                window.ShoppingCart.toggleCartDrawer();
                setTimeout(updateCartStatus, 300);
            } else {
                log('ShoppingCart not ready', 'error');
            }
        }

        function testRemoveItem() {
            log('Testing item removal...', 'info');
            
            if (!window.ShoppingCart || !window.ShoppingCart.state.cart) {
                log('Cart not ready', 'error');
                return;
            }

            const itemsByType = window.ShoppingCart.state.cart.items_by_type;
            if (!itemsByType) {
                log('No items in cart', 'warn');
                return;
            }

            // Try to remove first item
            let removed = false;
            for (const [type, items] of Object.entries(itemsByType)) {
                if (Array.isArray(items) && items.length > 0) {
                    const firstItem = items[0];
                    log(`Removing first ${type} item: ${firstItem.key}`, 'info');
                    window.ShoppingCart.removeItem(firstItem.key);
                    removed = true;
                    setTimeout(updateCartStatus, 500);
                    break;
                }
            }

            if (!removed) {
                log('No items to remove', 'warn');
            }
        }

        // Monitor initialization
        log('Page loaded, waiting for ShoppingCart...', 'info');
        
        setInterval(updateCartStatus, 1000);

        // Monitor console logs from cart.js
        const originalLog = console.log;
        const originalError = console.error;

        console.log = function(...args) {
            originalLog.apply(console, args);
            if (args[0] && typeof args[0] === 'string' && 
                (args[0].includes('Drawer') || args[0].includes('drawer') || 
                 args[0].includes('Removing') || args[0].includes('Remove'))) {
                log(args[0], 'info');
            }
        };

        console.error = function(...args) {
            originalError.apply(console, args);
            if (args[0] && typeof args[0] === 'string') {
                log(args[0], 'error');
            }
        };
    </script>
</body>
</html>
