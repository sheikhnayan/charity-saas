<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart Debug - Real cart.js Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { padding: 20px; }
        .container { max-width: 900px; margin: auto; }
        .test-box { background: white; padding: 20px; margin: 10px 0; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .debug-log { background: #f8f9fa; padding: 10px; border-radius: 3px; font-family: monospace; font-size: 11px; max-height: 400px; overflow-y: auto; }
        .log-entry { margin: 2px 0; padding: 2px 5px; border-left: 3px solid #ccc; }
        .log-entry.error { border-left-color: #dc3545; color: #dc3545; }
        .log-entry.success { border-left-color: #28a745; color: #28a745; }
        .log-entry.info { border-left-color: #0066cc; color: #0066cc; }
        .alert { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🛒 Cart Drawer - Real Debug Test</h1>
        
        <div class="test-box">
            <h5>📊 Status Monitor</h5>
            <div id="status">
                <p><strong>Cart Drawer Exists:</strong> <span id="drawerExists">Checking...</span></p>
                <p><strong>Drawer Visible:</strong> <span id="drawerVisible">Checking...</span></p>
                <p><strong>Drawer Position:</strong> <span id="drawerPosition">Checking...</span></p>
                <p><strong>Drawer Classes:</strong> <span id="drawerClasses">Checking...</span></p>
                <p><strong>Drawer Open State:</strong> <span id="drawerOpenState">Checking...</span></p>
            </div>
        </div>

        <div class="test-box">
            <h5>🧪 Test Controls</h5>
            <button class="btn btn-primary" onclick="testAddItem()">Add Item to Cart</button>
            <button class="btn btn-success" onclick="testToggleDrawer()">🎯 Toggle Drawer</button>
            <button class="btn btn-danger" onclick="testCloseButton()">❌ Test Close Button</button>
            <button class="btn btn-info" onclick="testRemoveItem()">Remove Item</button>
            <button class="btn btn-secondary" onclick="clearLogs()">Clear Logs</button>
        </div>

        <div class="test-box">
            <h5>📝 Console Log</h5>
            <div class="debug-log" id="logArea"></div>
        </div>

        <div class="alert alert-info">
            <strong>What to test:</strong>
            <ol>
                <li>Check "Status Monitor" - Drawer should exist and be visible</li>
                <li>Click "Add Item to Cart"</li>
                <li>Click "🎯 Toggle Drawer" and watch the drawer slide in from right</li>
                <li>Click the × button in the drawer to close it</li>
                <li>Check console logs for any errors</li>
            </ol>
        </div>
    </div>

    <!-- jQuery (must be before cart.js) -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    
    <!-- Cart System -->
    <script src="/js/cart.js"></script>

    <script>
        const logArea = document.getElementById('logArea');
        let logCount = 0;
        
        // Intercept console.log
        const originalLog = console.log;
        console.log = function(...args) {
            originalLog.apply(console, args);
            const message = args.map(arg => typeof arg === 'object' ? JSON.stringify(arg) : String(arg)).join(' ');
            if (message.includes('Toggle') || message.includes('drawer') || message.includes('Drawer') || 
                message.includes('OPENING') || message.includes('CLOSING') || message.includes('click')) {
                addLog(message, 'info');
            }
        };
        
        // Intercept console.error
        const originalError = console.error;
        console.error = function(...args) {
            originalError.apply(console, args);
            const message = args.map(arg => typeof arg === 'object' ? JSON.stringify(arg) : String(arg)).join(' ');
            addLog(message, 'error');
        };
        
        function addLog(message, type = 'info') {
            logCount++;
            const entry = document.createElement('div');
            entry.className = `log-entry log-entry-${type}`;
            entry.textContent = `[${logCount}] ${message}`;
            logArea.insertBefore(entry, logArea.firstChild);
            
            if (logArea.children.length > 50) {
                logArea.removeChild(logArea.lastChild);
            }
        }
        
        function clearLogs() {
            logArea.innerHTML = '';
            logCount = 0;
            addLog('Logs cleared', 'info');
        }
        
        function updateStatus() {
            const drawer = document.getElementById('cartDrawer');
            const overlay = document.getElementById('cartOverlay');
            
            document.getElementById('drawerExists').textContent = drawer ? '✅ YES' : '❌ NO';
            document.getElementById('drawerVisible').textContent = drawer && drawer.offsetHeight > 0 ? '✅ YES' : '❌ NO';
            document.getElementById('drawerPosition').textContent = drawer ? window.getComputedStyle(drawer).right : 'N/A';
            document.getElementById('drawerClasses').textContent = drawer ? drawer.className : 'N/A';
            document.getElementById('drawerOpenState').textContent = window.ShoppingCart?.state?.cartOpen ? '✅ OPEN' : '❌ CLOSED';
        }
        
        function testToggleDrawer() {
            addLog('Testing drawer toggle...', 'info');
            if (window.ShoppingCart && typeof window.ShoppingCart.toggleCartDrawer === 'function') {
                window.ShoppingCart.toggleCartDrawer();
                setTimeout(updateStatus, 100);
            } else {
                addLog('toggleCartDrawer function not found!', 'error');
            }
        }
        
        function testAddItem() {
            addLog('Adding test item...', 'info');
            if (window.ShoppingCart && typeof window.ShoppingCart.addItem === 'function') {
                const item = {
                    id: Date.now(),
                    name: 'Test Item',
                    type: 'product',
                    price: 99,
                    quantity: 1
                };
                window.ShoppingCart.addItem(item);
                setTimeout(updateStatus, 500);
            } else {
                addLog('addItem function not found!', 'error');
            }
        }
        
        function testRemoveItem() {
            addLog('Testing item removal...', 'info');
            const drawer = document.getElementById('cartDrawer');
            if (drawer) {
                const removeBtn = drawer.querySelector('.btn-remove');
                if (removeBtn) {
                    addLog('Found remove button, clicking...', 'info');
                    removeBtn.click();
                } else {
                    addLog('No items in cart to remove', 'error');
                }
            }
        }
        
        function testCloseButton() {
            addLog('Testing close button...', 'info');
            const closeBtn = document.getElementById('cartCloseBtn');
            if (closeBtn) {
                addLog('Close button found, clicking...', 'info');
                closeBtn.click();
                setTimeout(updateStatus, 100);
            } else {
                addLog('Close button not found!', 'error');
            }
        }
        
        // Update status every 500ms
        setInterval(updateStatus, 500);
        
        // Initial check
        setTimeout(() => {
            addLog('Page loaded - testing cart system', 'info');
            updateStatus();
        }, 1000);
    </script>
</body>
</html>
