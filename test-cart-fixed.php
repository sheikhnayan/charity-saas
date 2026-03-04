<!DOCTYPE html>
<html>
<head>
    <title>Cart.js Test - Verify ShoppingCart Object</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="public/css/cart.css">
    <style>
        body { padding: 20px; background: #f5f5f5; }
        .test-container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .status { padding: 15px; margin: 15px 0; border-radius: 4px; font-family: monospace; }
        .status.success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .status.error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .status.info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
        code { display: block; margin-top: 10px; padding: 10px; background: #f8f9fa; border-radius: 3px; overflow-x: auto; }
        h3 { margin-top: 30px; color: #333; }
    </style>
</head>
<body>
    <div class="test-container">
        <h1>🛒 Cart.js Verification Test</h1>
        <p>This page tests if cart.js is properly loaded and ShoppingCart object is defined.</p>
        
        <div id="results"></div>
        
        <h3>Test Results:</h3>
        <div id="status-container"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="public/js/cart.js"></script>

    <script>
        const resultsDiv = document.getElementById('results');
        const statusDiv = document.getElementById('status-container');
        
        function addStatus(message, type = 'info') {
            const div = document.createElement('div');
            div.className = `status ${type}`;
            div.textContent = message;
            statusDiv.appendChild(div);
            console.log(`[${type.toUpperCase()}] ${message}`);
        }

        // Wait a bit for scripts to load
        setTimeout(() => {
            console.log('=== CART.JS TEST START ===');
            console.log('window.ShoppingCart:', window.ShoppingCart);
            console.log('window.addToCart:', window.addToCart);
            console.log('window._cartQueue:', window._cartQueue);

            // Test 1: Check if jQuery is loaded
            if (typeof jQuery !== 'undefined' && typeof $ !== 'undefined') {
                addStatus('✅ jQuery is loaded', 'success');
            } else {
                addStatus('❌ jQuery is NOT loaded', 'error');
            }

            // Test 2: Check if ShoppingCart object exists
            if (window.ShoppingCart && typeof window.ShoppingCart === 'object') {
                addStatus('✅ ShoppingCart object exists', 'success');
                
                // Test 3: Check ShoppingCart.init method
                if (typeof window.ShoppingCart.init === 'function') {
                    addStatus('✅ ShoppingCart.init() method exists', 'success');
                } else {
                    addStatus('❌ ShoppingCart.init() method does NOT exist', 'error');
                }
                
                // List all methods
                const methods = Object.keys(window.ShoppingCart);
                addStatus(`✅ ShoppingCart methods (${methods.length}): ${methods.join(', ')}`, 'success');
                
            } else {
                addStatus('❌ ShoppingCart object does NOT exist on window', 'error');
                console.log('Window keys:', Object.keys(window).filter(k => k.includes('cart') || k.includes('Cart')).join(', '));
            }

            // Test 4: Check if addToCart function exists
            if (typeof window.addToCart === 'function') {
                addStatus('✅ addToCart() global function exists', 'success');
            } else {
                addStatus('❌ addToCart() global function does NOT exist', 'error');
            }

            // Test 5: Check cart queue
            if (Array.isArray(window._cartQueue)) {
                addStatus(`✅ Cart queue exists (${window._cartQueue.length} items)`, 'success');
            } else {
                addStatus('❌ Cart queue does NOT exist', 'error');
            }

            console.log('=== CART.JS TEST END ===');
        }, 1000);
    </script>
</body>
</html>
