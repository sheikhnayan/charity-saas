<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Session Replay - {{ $recording->session_id }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { margin: 0; padding: 0; background: #1a1a1a; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; overflow: hidden; }
        .header { background: #2c2c2c; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 8px rgba(0,0,0,0.3); z-index: 1000; position: relative; }
        .header h4 { margin: 0; font-size: 18px; }
        .header-meta { display: flex; gap: 30px; align-items: center; font-size: 13px; color: #aaa; }
        .header-actions { display: flex; gap: 15px; align-items: center; }
        .btn-icon { background: #3a3a3a; border: none; color: white; padding: 8px 12px; border-radius: 5px; cursor: pointer; transition: 0.2s; }
        .btn-icon:hover { background: #4a4a4a; }
        .btn-icon.active { background: #007bff; }
        
        #player-container { position: relative; width: 100%; height: calc(100vh - 120px); background: #000; overflow: auto; }
        #page-iframe { width: 100%; min-height: 300vh; border: none; pointer-events: none; display: block; }
        
        #cursor-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 10; }
        #cursor { position: absolute; width: 20px; height: 20px; border-radius: 50%; background: rgba(255, 0, 0, 0.6); border: 2px solid rgba(255, 255, 255, 0.8); transform: translate(-50%, -50%); display: none; pointer-events: none; transition: all 0.05s ease-out; }
        #cursor-trail { position: absolute; width: 0; height: 0; pointer-events: none; }
        
        .controls { background: #2c2c2c; padding: 15px 30px; display: flex; align-items: center; gap: 20px; }
        .play-btn { background: #007bff; border: none; color: white; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: 600; }
        .play-btn:hover { background: #0056b3; }
        .timeline { flex: 1; height: 8px; background: #3a3a3a; border-radius: 4px; position: relative; cursor: pointer; }
        .timeline-progress { height: 100%; background: #007bff; border-radius: 4px; width: 0%; }
        .timeline-thumb { position: absolute; width: 16px; height: 16px; background: white; border-radius: 50%; top: -4px; left: 0%; transform: translateX(-50%); cursor: pointer; }
        .time-display { color: #aaa; font-size: 14px; min-width: 100px; text-align: right; }
        .speed-control { color: white; font-size: 14px; }
        .speed-btn { background: #3a3a3a; border: none; color: white; padding: 5px 10px; border-radius: 3px; cursor: pointer; margin-left: 5px; }
        .speed-btn.active { background: #007bff; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <h4><i class="fas fa-video me-2"></i>Session Replay</h4>
            <small class="text-muted">{{ $recording->session_id }}</small>
        </div>
        <div class="header-meta">
            <span><i class="fas fa-clock me-1"></i>{{ $recording->created_at->format('M d, Y H:i') }}</span>
            <span><i class="fas fa-stopwatch me-1"></i>{{ number_format($recording->duration_ms / 1000, 1) }}s</span>
            <span><i class="fas fa-mouse-pointer me-1"></i>{{ $recording->event_count }} events</span>
        </div>
        <div class="header-actions">
            <button class="btn-icon" onclick="window.location.href='/hotjar/recordings'">
                <i class="fas fa-arrow-left"></i>
            </button>
        </div>
    </div>

    <div id="player-container">
        <iframe id="page-iframe" src="{{ $recording->url }}" sandbox="allow-same-origin"></iframe>
        <div id="cursor-overlay">
            <div id="cursor"></div>
            <svg id="cursor-trail" width="100%" height="100%">
                <polyline id="trail-path" fill="none" stroke="rgba(255, 0, 0, 0.3)" stroke-width="2" points=""/>
            </svg>
        </div>
    </div>

    <div class="controls">
        <button class="play-btn" id="playBtn" onclick="togglePlay()">
            <i class="fas fa-play"></i> Play
        </button>
        <div class="timeline" id="timeline" onclick="seekTimeline(event)">
            <div class="timeline-progress" id="progress"></div>
            <div class="timeline-thumb" id="thumb"></div>
        </div>
        <div class="time-display" id="timeDisplay">0:00 / 0:00</div>
        <div class="speed-control">
            Speed:
            <button class="speed-btn" onclick="setSpeed(0.5)">0.5x</button>
            <button class="speed-btn active" onclick="setSpeed(1)">1x</button>
            <button class="speed-btn" onclick="setSpeed(2)">2x</button>
            <button class="speed-btn" onclick="setSpeed(4)">4x</button>
        </div>
    </div>

    <script>
        const recordingId = {{ $recording->id }};
        let events = [];
        let currentEventIndex = 0;
        let isPlaying = false;
        let playInterval = null;
        let currentSpeed = 1;
        let startTime = 0;
        let duration = 0;

        const cursor = document.getElementById('cursor');
        const iframe = document.getElementById('page-iframe');
        const playBtn = document.getElementById('playBtn');
        const progress = document.getElementById('progress');
        const thumb = document.getElementById('thumb');
        const timeDisplay = document.getElementById('timeDisplay');

        async function loadRecording() {
            try {
                console.log('Fetching recording:', recordingId);
                
                // Bypass service worker by adding cache control headers
                const response = await fetch(`/api/session-recording/${recordingId}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Cache-Control': 'no-cache, no-store, must-revalidate'
                    },
                    credentials: 'same-origin',
                    cache: 'no-store' // Bypass service worker cache
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                
                console.log('Raw API response:', data);
                console.log('Total events received:', data.events.length);
                console.log('First 5 events:', data.events.slice(0, 5));
                
                // Use ALL events, not just type 3
                events = data.events;
                
                console.log('All events:', events.length);
                
                // Group by type to see what we have
                const byType = {};
                events.forEach(e => {
                    const type = e.type || 'unknown';
                    byType[type] = (byType[type] || 0) + 1;
                });
                console.log('Events by type:', byType);
                
                if (events.length > 0) {
                    startTime = events[0].timestamp;
                    const lastTimestamp = events[events.length - 1].timestamp;
                    duration = lastTimestamp - startTime;
                    
                    console.log('Start time:', startTime);
                    console.log('Last timestamp:', lastTimestamp);
                    console.log('Duration (ms):', duration);
                    console.log('Duration (sec):', duration / 1000);
                    
                    updateTimeDisplay(0);
                } else {
                    console.error('No events found!');
                    alert('No events found in this recording. The recording may be corrupted.');
                }
                
                // Wait for iframe to load
                iframe.addEventListener('load', () => {
                    console.log('Iframe loaded, ready to replay');
                    cursor.style.display = 'block';
                });
            } catch (error) {
                console.error('Failed to load recording:', error);
                alert('Failed to load recording: ' + error.message + '\n\nTry refreshing the page or unregistering the service worker.');
            }
        }

        function togglePlay() {
            isPlaying = !isPlaying;
            if (isPlaying) {
                playBtn.innerHTML = '<i class="fas fa-pause"></i> Pause';
                startPlayback();
            } else {
                playBtn.innerHTML = '<i class="fas fa-play"></i> Play';
                stopPlayback();
            }
        }

        function startPlayback() {
            const startIndex = currentEventIndex;
            const playStartTime = Date.now();
            const eventStartTime = events[startIndex]?.timestamp || startTime;
            
            playInterval = setInterval(() => {
                const elapsed = (Date.now() - playStartTime) * currentSpeed;
                const targetTime = eventStartTime + elapsed;
                
                // Find events to process
                while (currentEventIndex < events.length && events[currentEventIndex].timestamp <= targetTime) {
                    processEvent(events[currentEventIndex]);
                    currentEventIndex++;
                }
                
                // Update progress
                const progressPercent = ((targetTime - startTime) / duration) * 100;
                progress.style.width = Math.min(progressPercent, 100) + '%';
                thumb.style.left = Math.min(progressPercent, 100) + '%';
                updateTimeDisplay(targetTime - startTime);
                
                // End of recording
                if (currentEventIndex >= events.length) {
                    stopPlayback();
                    isPlaying = false;
                    playBtn.innerHTML = '<i class="fas fa-play"></i> Play';
                    currentEventIndex = 0;
                }
            }, 16); // ~60fps
        }

        function stopPlayback() {
            if (playInterval) {
                clearInterval(playInterval);
                playInterval = null;
            }
        }

        function processEvent(event) {
            console.log('Processing event:', event.type, event);
            
            if (event.type !== 3) return; // Only process IncrementalSnapshot events for interactions
            
            const data = event.data;
            
            // Source types in rrweb:
            // 0 = Mutation, 1 = MouseMove, 2 = MouseInteraction, 3 = Scroll, 4 = ViewportResize, etc
            
            // Mouse movement (source 1 in rrweb v2)
            if (data.source === 1 || data.source === 2) {
                const positions = data.positions || [];
                if (positions.length > 0) {
                    const lastPos = positions[positions.length - 1];
                    cursor.style.left = lastPos.x + 'px';
                    cursor.style.top = lastPos.y + 'px';
                    cursor.style.display = 'block';
                }
            }
            
            // Mouse interaction (source 2 or 3) - clicks, etc
            if (data.source === 2 || data.source === 3) {
                if (data.type === 2 || data.type === 0) { // Click (MouseUp or MouseDown)
                    // Position cursor at click location
                    if (data.x !== undefined && data.y !== undefined) {
                        cursor.style.left = data.x + 'px';
                        cursor.style.top = data.y + 'px';
                        cursor.style.display = 'block';
                    }
                    
                    // Show click animation
                    cursor.style.transform = 'translate(-50%, -50%) scale(1.5)';
                    cursor.style.background = 'rgba(255, 0, 0, 0.9)';
                    setTimeout(() => {
                        cursor.style.transform = 'translate(-50%, -50%) scale(1)';
                        cursor.style.background = 'rgba(255, 0, 0, 0.6)';
                    }, 200);
                }
            }
            
            // Scroll (source 3 or 4)
            if (data.source === 3 || data.source === 4) {
                // Scroll the container instead of iframe
                const container = document.getElementById('player-container');
                const scrollX = data.x || 0;
                const scrollY = data.y || 0;
                container.scrollTo(scrollX, scrollY);
            }
        }

        function seekTimeline(e) {
            const rect = e.currentTarget.getBoundingClientRect();
            const percent = (e.clientX - rect.left) / rect.width;
            const targetTime = startTime + (duration * percent);
            
            // Find nearest event
            currentEventIndex = events.findIndex(ev => ev.timestamp >= targetTime);
            if (currentEventIndex === -1) currentEventIndex = events.length - 1;
            
            progress.style.width = (percent * 100) + '%';
            thumb.style.left = (percent * 100) + '%';
            updateTimeDisplay(duration * percent);
            
            if (isPlaying) {
                stopPlayback();
                startPlayback();
            }
        }

        function setSpeed(speed) {
            currentSpeed = speed;
            document.querySelectorAll('.speed-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            
            if (isPlaying) {
                stopPlayback();
                startPlayback();
            }
        }

        function updateTimeDisplay(elapsed) {
            const current = formatTime(elapsed / 1000);
            const total = formatTime(duration / 1000);
            timeDisplay.textContent = `${current} / ${total}`;
        }

        function formatTime(seconds) {
            const mins = Math.floor(seconds / 60);
            const secs = Math.floor(seconds % 60);
            return `${mins}:${secs.toString().padStart(2, '0')}`;
        }

        // Initialize
        loadRecording();
    </script>
</body>
</html>
