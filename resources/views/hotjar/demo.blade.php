<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $website->title ?? 'My Website' }}</title>
    
    <!-- rrweb library (REQUIRED for session recording) -->
    <script src="https://cdn.jsdelivr.net/npm/rrweb@2.0.0-alpha.11/dist/rrweb.min.js"></script>
    
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        button {
            background: #2196f3;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            margin: 5px;
        }
        button:hover {
            background: #1976d2;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Hotjar Integration Demo Page</h1>
        <p>This page demonstrates session recording and heatmap tracking.</p>
        
        <div class="alert">
            <strong>✅ Hotjar tracking is active!</strong> Your session is being recorded.
        </div>

        <h2>Try These Interactions:</h2>
        
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin: 20px 0;">
            <button onclick="handleClick('Button 1')">Click Me</button>
            <button onclick="handleClick('Button 2')">Click Me Too</button>
            <button onclick="handleClick('Button 3')">And Me!</button>
            <button onclick="handleClick('Button 4')">Don't Forget Me</button>
            <button onclick="handleClick('Button 5')">Click Here</button>
            <button onclick="handleClick('Button 6')">Last One</button>
        </div>

        <h3>Test Rage Clicks</h3>
        <p>Click this button 3+ times rapidly to trigger rage click detection:</p>
        <button id="rageClickBtn" onclick="handleRageClick()">Rage Click Me!</button>
        <p id="rageClickCount" style="color: #f44336; font-weight: bold;"></p>

        <h3>Form Interaction (Privacy Masked)</h3>
        <form onsubmit="handleSubmit(event)">
            <div class="form-group">
                <label>Name:</label>
                <input type="text" name="name" placeholder="Your name">
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" placeholder="your@email.com">
            </div>
            <div class="form-group">
                <label>Password (will be masked in recording):</label>
                <input type="password" name="password" placeholder="••••••••">
            </div>
            <div class="form-group">
                <label>Message:</label>
                <textarea name="message" rows="4" placeholder="Your message..."></textarea>
            </div>
            <button type="submit">Submit Form</button>
        </form>

        <h3>Scroll Tracking</h3>
        <p>Scroll down this page to generate scroll depth data for heatmaps.</p>
        <div style="height: 800px; background: linear-gradient(to bottom, #e3f2fd, #bbdefb, #90caf9); padding: 20px; border-radius: 8px;">
            <h4>Section 1</h4>
            <p>This is content at the top of the scrollable area.</p>
            <div style="margin-top: 200px;">
                <h4>Section 2</h4>
                <p>Content at 25% scroll depth.</p>
            </div>
            <div style="margin-top: 200px;">
                <h4>Section 3</h4>
                <p>Content at 50% scroll depth.</p>
            </div>
            <div style="margin-top: 200px;">
                <h4>Section 4</h4>
                <p>Content at 75% scroll depth.</p>
            </div>
            <div style="margin-top: 200px;">
                <h4>Section 5 (Bottom)</h4>
                <p>You've reached 100% scroll depth!</p>
            </div>
        </div>

        <h3>Sensitive Data (Blocked from Recording)</h3>
        <div data-block style="background: #ffebee; padding: 20px; border-radius: 8px;">
            <p><strong>⚠️ This entire section is blocked from recording</strong></p>
            <p>Credit Card: 4532-1234-5678-9010</p>
            <p>SSN: 123-45-6789</p>
            <p>This content will NOT appear in session recordings.</p>
        </div>

        <h3>Session Info</h3>
        <div id="sessionInfo" style="background: #f5f5f5; padding: 15px; border-radius: 4px; font-family: monospace; font-size: 12px;"></div>
    </div>

    <!-- Hotjar Tracker (automatically initializes) -->
    <div data-hotjar-tracker data-website-id="{{ $website->id ?? 1 }}"></div>
    <script src="{{ asset('js/hotjar-tracker.js') }}"></script>

    <script>
        let rageClickCount = 0;
        let rageClickTimer = null;

        function handleClick(buttonName) {
            console.log('Button clicked:', buttonName);
            alert('You clicked: ' + buttonName);
        }

        function handleRageClick() {
            rageClickCount++;
            document.getElementById('rageClickCount').textContent = 
                `Click count: ${rageClickCount} ${rageClickCount >= 3 ? '🔥 RAGE CLICK DETECTED!' : ''}`;
            
            clearTimeout(rageClickTimer);
            rageClickTimer = setTimeout(() => {
                rageClickCount = 0;
                document.getElementById('rageClickCount').textContent = '';
            }, 1000);
        }

        function handleSubmit(event) {
            event.preventDefault();
            alert('Form submitted! (Check the recording to see masked inputs)');
        }

        // Display session info
        if (window.hotjarTracker) {
            document.getElementById('sessionInfo').innerHTML = `
                <strong>Tracking Status:</strong> Active<br>
                <strong>Session ID:</strong> ${window.hotjarTracker.sessionId}<br>
                <strong>Visitor ID:</strong> ${window.hotjarTracker.visitorId}<br>
                <strong>Recording ID:</strong> ${window.hotjarTracker.recordingId || 'Pending...'}<br>
                <strong>Heatmap Tracking:</strong> ${window.hotjarTracker.shouldTrackHeatmap ? 'Yes (10% sample)' : 'No'}<br>
                <strong>Website ID:</strong> {{ $website->id ?? 1 }}
            `;
        }

        // Update recording ID after it's assigned
        setTimeout(() => {
            if (window.hotjarTracker && window.hotjarTracker.recordingId) {
                document.getElementById('sessionInfo').innerHTML = document.getElementById('sessionInfo').innerHTML.replace(
                    'Pending...',
                    window.hotjarTracker.recordingId
                );
            }
        }, 2000);
    </script>
</body>
</html>
