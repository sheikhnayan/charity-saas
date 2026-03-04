<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Session Replay - {{ $recording->page_title ?? 'Recording' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/rrweb-player@2.0.0-alpha.13/dist/style.css">
    <style>
        body { margin: 0; padding: 0; background: #1a1a1a; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .header { background: #2c2c2c; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 8px rgba(0,0,0,0.3); }
        .header h4 { margin: 0; font-size: 18px; }
        .header-meta { display: flex; gap: 30px; align-items: center; font-size: 13px; color: #aaa; }
        .header-meta i { color: #4caf50; margin-right: 5px; }
        .player-container { padding: 20px; max-width: 1600px; margin: 0 auto; }
        .rr-player { margin: 0 auto; box-shadow: 0 4px 20px rgba(0,0,0,0.5) !important; }
        .metadata-panel { background: #2c2c2c; border-radius: 8px; padding: 20px; margin-top: 20px; color: white; }
        .metadata-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; }
        .metadata-item { background: #3a3a3a; padding: 15px; border-radius: 6px; }
        .metadata-label { color: #aaa; font-size: 12px; text-transform: uppercase; margin-bottom: 5px; }
        .metadata-value { font-size: 18px; font-weight: 600; color: white; }
        .badge-rage { background: #ff4444; color: white; padding: 5px 10px; border-radius: 4px; font-size: 12px; }
        .badge-error { background: #ff9800; color: white; padding: 5px 10px; border-radius: 4px; font-size: 12px; }
        .action-buttons { display: flex; gap: 10px; }
        .btn-action { background: #4caf50; border: none; color: white; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-size: 14px; transition: all 0.3s; }
        .btn-action:hover { background: #45a049; }
        .btn-action.btn-star { background: #ffc107; }
        .btn-action.btn-star:hover { background: #ffb300; }
        .btn-action.btn-delete { background: #f44336; }
        .btn-action.btn-delete:hover { background: #da190b; }
        .notes-section { margin-top: 20px; }
        .notes-textarea { width: 100%; background: #3a3a3a; color: white; border: 1px solid #555; border-radius: 6px; padding: 10px; min-height: 80px; }
        .tags-input { width: 100%; background: #3a3a3a; color: white; border: 1px solid #555; border-radius: 6px; padding: 10px; }
        .back-link { color: #4caf50; text-decoration: none; }
        .back-link:hover { color: #45a049; text-decoration: underline; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <a href="/hotjar/recordings" class="back-link"><i class="fas fa-arrow-left"></i> Back to Recordings</a>
            <h4 class="mt-2">{{ $recording->page_title ?? 'Untitled Page' }}</h4>
        </div>
        <div class="header-meta">
            <span><i class="fas fa-clock"></i> {{ $recording->getDurationFormatted() }}</span>
            <span><i class="fas fa-calendar"></i> {{ $recording->started_at->format('M d, Y H:i:s') }}</span>
            <span><i class="fas fa-{{ $recording->device_type === 'mobile' ? 'mobile-alt' : ($recording->device_type === 'tablet' ? 'tablet-alt' : 'desktop') }}"></i> {{ ucfirst($recording->device_type ?? 'Unknown') }}</span>
            <span><i class="fas fa-map-marker-alt"></i> {{ $recording->country ?? 'Unknown' }}</span>
            @if($recording->has_rage_clicks)
                <span class="badge-rage"><i class="fas fa-angry"></i> Rage Clicks</span>
            @endif
            @if($recording->has_errors)
                <span class="badge-error"><i class="fas fa-exclamation-triangle"></i> Errors</span>
            @endif
        </div>
    </div>

    <div class="player-container">
        <!-- rrweb Player -->
        <div id="player"></div>

        <!-- Metadata Panel -->
        <div class="metadata-panel">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <h5>Session Details</h5>
                <div class="action-buttons">
                    <button class="btn-action btn-star" onclick="toggleStar()">
                        <i class="fas fa-star"></i> {{ $recording->is_starred ? 'Starred' : 'Star' }}
                    </button>
                    <button class="btn-action" onclick="downloadRecording()">
                        <i class="fas fa-download"></i> Download
                    </button>
                    <button class="btn-action btn-delete" onclick="deleteRecording()">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </div>

            <div class="metadata-grid">
                <div class="metadata-item">
                    <div class="metadata-label">Session ID</div>
                    <div class="metadata-value">{{ substr($recording->session_id, 0, 12) }}...</div>
                </div>
                <div class="metadata-item">
                    <div class="metadata-label">Visitor ID</div>
                    <div class="metadata-value">{{ substr($recording->visitor_id, 0, 12) }}...</div>
                </div>
                <div class="metadata-item">
                    <div class="metadata-label">Browser</div>
                    <div class="metadata-value">{{ $recording->browser ?? 'Unknown' }}</div>
                </div>
                <div class="metadata-item">
                    <div class="metadata-label">OS</div>
                    <div class="metadata-value">{{ $recording->os ?? 'Unknown' }}</div>
                </div>
                <div class="metadata-item">
                    <div class="metadata-label">Viewport</div>
                    <div class="metadata-value">{{ $recording->viewport_width }}x{{ $recording->viewport_height }}</div>
                </div>
                <div class="metadata-item">
                    <div class="metadata-label">Events</div>
                    <div class="metadata-value">{{ $recording->event_count ?? 0 }}</div>
                </div>
            </div>

            <div class="notes-section">
                <h6>Notes</h6>
                <textarea class="notes-textarea" id="notesInput" placeholder="Add notes about this session...">{{ $recording->notes }}</textarea>
                <button class="btn-action mt-2" onclick="saveNotes()">
                    <i class="fas fa-save"></i> Save Notes
                </button>
            </div>

            <div class="notes-section">
                <h6>Tags</h6>
                <input type="text" class="tags-input" id="tagsInput" placeholder="Add tags (comma separated)" value="{{ is_array($recording->tags) ? implode(', ', $recording->tags) : '' }}">
                <button class="btn-action mt-2" onclick="saveTags()">
                    <i class="fas fa-tags"></i> Save Tags
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/rrweb-player@2.0.0-alpha.13/dist/index.js"></script>
    <script>
        const recordingId = {{ $recording->id }};
        let player;
        let isStarred = {{ $recording->is_starred ? 'true' : 'false' }};

        // Configure rrweb to allow scripts in iframe
        window.rrwebPlayerConfig = {
            insertStyleRules: [
                'iframe { border: none; }',
            ]
        };

        async function loadPlayer() {
            try {
                const response = await fetch(`/api/session-recording/${recordingId}`, {
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();

                if (!data.events || data.events.length === 0) {
                    alert('No events found for this recording');
                    return;
                }

                // CRITICAL FIX: Clean "null" text nodes from events before playback
                const cleanEvents = data.events.map(event => {
                    if (event.type === 2 && event.data?.node) {
                        // Clone the event to avoid mutating original
                        const cleanedEvent = JSON.parse(JSON.stringify(event));
                        
                        // Recursively remove "null" text nodes
                        const cleanNode = (node) => {
                            if (node.type === 3 && node.textContent === 'null') {
                                node.textContent = '';
                            }
                            if (node.childNodes && Array.isArray(node.childNodes)) {
                                node.childNodes.forEach(child => cleanNode(child));
                            }
                        };
                        
                        cleanNode(cleanedEvent.data.node);
                        return cleanedEvent;
                    }
                    return event;
                });
                
                console.log('Cleaned events, checking for null text...');
                
                // Initialize rrweb player with proper configuration for rendering
                player = new rrwebPlayer({
                    target: document.getElementById('player'),
                    props: {
                        events: cleanEvents,
                        width: 1280,
                        height: 720,
                        autoPlay: false,
                        showController: true,
                        skipInactive: true,
                        speed: 1,
                        unpackFn: rrwebPlayer.unpack,
                        UNSAFE_replayCanvas: true,
                        mouseTail: {
                            duration: 500,
                            lineCap: 'round',
                            lineWidth: 2,
                            strokeStyle: 'red',
                        },
                        // Insert style rules to ensure proper rendering
                        insertStyleRules: [
                            'iframe { pointer-events: auto !important; border: none !important; }',
                            '.replayer-wrapper { background: #fff !important; }',
                            '.replayer-wrapper iframe { background: #fff !important; }',
                        ],
                        // Inlined stylesheets
                        inlineStylesheet: true,
                        // CRITICAL: Disable ALL text masking during replay
                        maskAllText: false,
                        maskTextClass: null,
                        maskTextSelector: null,
                        // Block unwanted elements
                        blockClass: 'rr-block',
                        ignoreClass: 'rr-ignore',
                        tags: {
                            'rage-click': 'Rage Click',
                            'error': 'Error'
                        }
                    }
                });

                // Log player info
                console.log('Player initialized with', data.events.length, 'events');
                console.log('First event:', data.events[0]);
                
                // Debug: Check if the first event has proper HTML structure
                const firstSnapshot = data.events.find(e => e.type === 2);
                if (firstSnapshot) {
                    console.log('Snapshot event found:', {
                        hasData: !!firstSnapshot.data,
                        hasNode: !!firstSnapshot.data?.node,
                        nodeType: firstSnapshot.data?.node?.type,
                        childCount: firstSnapshot.data?.node?.childNodes?.length
                    });
                    
                    // Check for HTML/body
                    const htmlNode = firstSnapshot.data?.node?.childNodes?.find(n => n.tagName === 'html');
                    if (htmlNode) {
                        const headNode = htmlNode.childNodes?.find(n => n.tagName === 'head');
                        const bodyNode = htmlNode.childNodes?.find(n => n.tagName === 'body');
                        
                        console.log('HTML structure in player:', {
                            hasHead: !!headNode,
                            headChildren: headNode?.childNodes?.length,
                            hasBody: !!bodyNode,
                            bodyChildren: bodyNode?.childNodes?.length
                        });
                        
                        // Check for style/link tags in head
                        if (headNode) {
                            const styleTags = headNode.childNodes?.filter(n => n.tagName === 'style' || n.tagName === 'link');
                            console.log('Styles in head:', {
                                styleCount: styleTags?.length,
                                hasInlineStyles: styleTags?.some(s => s.tagName === 'style'),
                                hasLinkTags: styleTags?.some(s => s.tagName === 'link')
                            });
                        }
                    }
                } else {
                    console.error('❌ No snapshot event (type 2) found in events!');
                }
                
                // Fix iframe sandboxing issue - allow scripts
                setTimeout(() => {
                    const iframe = document.querySelector('.rr-player iframe');
                    if (iframe) {
                        // Remove sandbox attribute or add allow-scripts
                        iframe.removeAttribute('sandbox');
                        // Or add proper permissions
                        // iframe.setAttribute('sandbox', 'allow-scripts allow-same-origin');
                        console.log('Iframe sandbox attribute removed');
                    }
                }, 100);
            } catch (error) {
                console.error('Failed to load recording:', error);
                alert('Failed to load recording. Please try again.');
            }
        }

        async function toggleStar() {
            try {
                const response = await fetch(`/api/session-recording/${recordingId}/star`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();
                if (data.success) {
                    isStarred = data.is_starred;
                    const btn = document.querySelector('.btn-star');
                    btn.innerHTML = `<i class="fas fa-star"></i> ${isStarred ? 'Starred' : 'Star'}`;
                }
            } catch (error) {
                console.error('Failed to toggle star:', error);
            }
        }

        async function saveNotes() {
            const notes = document.getElementById('notesInput').value;
            try {
                const response = await fetch(`/api/session-recording/${recordingId}/meta`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ notes })
                });

                if (response.ok) {
                    alert('Notes saved successfully!');
                }
            } catch (error) {
                console.error('Failed to save notes:', error);
            }
        }

        async function saveTags() {
            const tagsInput = document.getElementById('tagsInput').value;
            const tags = tagsInput.split(',').map(t => t.trim()).filter(t => t);
            
            try {
                const response = await fetch(`/api/session-recording/${recordingId}/meta`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ tags })
                });

                if (response.ok) {
                    alert('Tags saved successfully!');
                }
            } catch (error) {
                console.error('Failed to save tags:', error);
            }
        }

        function downloadRecording() {
            window.location.href = `/api/session-recording/${recordingId}?download=1`;
        }

        async function deleteRecording() {
            if (!confirm('Are you sure you want to delete this recording? This action cannot be undone.')) {
                return;
            }

            try {
                const response = await fetch(`/api/session-recording/${recordingId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (response.ok) {
                    alert('Recording deleted successfully!');
                    window.location.href = '/hotjar/recordings';
                }
            } catch (error) {
                console.error('Failed to delete recording:', error);
            }
        }

        // Load player on page load
        loadPlayer();
    </script>
</body>
</html>
