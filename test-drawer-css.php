<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart Drawer Debug</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding: 20px; }
        #testArea { max-width: 1200px; margin: auto; }
        #mockDrawer {
            position: fixed;
            top: 0;
            right: -500px;
            width: 400px;
            height: 100vh;
            background: white;
            z-index: 10000;
            box-shadow: -2px 0 8px rgba(0, 0, 0, 0.15);
            transition: right 0.35s ease-out;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            border: 5px solid red;
        }
        
        #mockDrawer.open {
            right: 0;
        }
        
        #mockOverlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        #mockOverlay.open {
            display: block;
            opacity: 1;
        }
        
        .debug-log { background: #f0f0f0; padding: 10px; margin: 10px 0; border-radius: 5px; font-family: monospace; font-size: 12px; max-height: 300px; overflow-y: auto; }
        .log-entry { margin: 3px 0; }
    </style>
</head>
<body>
    <div id="testArea">
        <h1>🧪 Cart Drawer CSS Test</h1>
        <button class="btn btn-primary" onclick="testToggle()">Toggle Drawer</button>
        <button class="btn btn-danger" onclick="clearLog()">Clear Log</button>
        
        <div class="debug-log" id="debugLog"></div>
        
        <p style="margin-top: 50px; color: #999;">
            <strong>This test simulates the cart drawer CSS without cart.js.</strong><br>
            The drawer should slide in from the right when you click the button.
        </p>
    </div>
    
    <!-- Mock drawer -->
    <div id="mockOverlay"></div>
    <div id="mockDrawer">
        <div style="padding: 20px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0;">Shopping Cart</h3>
            <button onclick="testToggle()" style="background: none; border: none; font-size: 28px; cursor: pointer;">×</button>
        </div>
        <div style="flex: 1; padding: 20px; overflow-y: auto;">
            <p style="color: #999; text-align: center;">Test drawer content</p>
        </div>
    </div>

    <script>
        let isOpen = false;
        const log = document.getElementById('debugLog');
        
        function addLog(message) {
            const entry = document.createElement('div');
            entry.className = 'log-entry';
            entry.textContent = `[${new Date().toLocaleTimeString()}] ${message}`;
            log.insertBefore(entry, log.firstChild);
        }
        
        function clearLog() {
            log.innerHTML = '';
        }
        
        function testToggle() {
            const drawer = document.getElementById('mockDrawer');
            const overlay = document.getElementById('mockOverlay');
            
            isOpen = !isOpen;
            addLog(`Toggle: ${isOpen ? 'OPENING' : 'CLOSING'}`);
            
            const beforeRight = window.getComputedStyle(drawer).right;
            addLog(`Before - right: ${beforeRight}`);
            
            if (isOpen) {
                drawer.classList.add('open');
                overlay.classList.add('open');
                addLog('Added .open class');
            } else {
                drawer.classList.remove('open');
                overlay.classList.remove('open');
                addLog('Removed .open class');
            }
            
            setTimeout(() => {
                const afterRight = window.getComputedStyle(drawer).right;
                addLog(`After - right: ${afterRight}`);
                addLog(`Drawer classes: ${drawer.className}`);
                addLog(`Drawer visible: ${drawer.offsetParent !== null ? 'YES' : 'NO'}`);
            }, 50);
        }
        
        addLog('Page loaded - CSS test ready');
    </script>
</body>
</html>
