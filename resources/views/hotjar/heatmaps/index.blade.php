@extends('admin.main')

@section('content')
<style>
    .controls-card { background: white; border-radius: 8px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    .page-list { background: white; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    .page-item-custom { padding: 15px; border-bottom: 1px solid #eee; cursor: pointer; transition: all 0.3s; }
    .page-item-custom:hover { background: #f8f9fa; }
    .page-item-custom.active { background: #e3f2fd; border-left: 4px solid #2196f3; }
    .heatmap-container { position: relative; background: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden; }
    .heatmap-canvas { position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 10; }
    .heatmap-iframe { width: 100%; height: 800px; border: none; }
    .heatmap-legend { position: absolute; top: 20px; right: 20px; background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.2); z-index: 20; }
    .legend-item { display: flex; align-items: center; gap: 10px; margin-bottom: 8px; }
    .legend-color { width: 30px; height: 20px; border-radius: 4px; }
    .heatmap-type-btn { margin-right: 10px; margin-bottom: 10px; }
    .heatmap-type-btn.active { background: #696cff; color: white; border-color: #696cff; }
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 20px; }
    .stat-box { background: #f8f9fa; padding: 15px; border-radius: 6px; text-align: center; }
    .stat-value { font-size: 28px; font-weight: 700; color: #696cff; }
    .stat-label { color: #666; font-size: 13px; margin-top: 5px; }
    .scroll-depth-bar { height: 30px; background: linear-gradient(to right, #4caf50, #ffeb3b, #ff5722); border-radius: 4px; position: relative; margin: 10px 0; }
    .scroll-marker { position: absolute; top: -5px; width: 3px; height: 40px; background: #333; }
    .element-stats-table { max-height: 400px; overflow-y: auto; }
    .element-row { padding: 10px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; }
    .element-row:hover { background: #f8f9fa; }
</style>

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">User Behavior /</span> Heatmaps
    </h4>

    <div class="row">
        <div class="row">
            <!-- Page List Sidebar -->
            <div class="col-md-3">
                <div class="page-list">
                    <h5 class="mb-3">Popular Pages</h5>
                    <div class="mb-3">
                        <select class="form-select" id="websiteSelect">
                            <option value="">Select Website</option>
                            @foreach($websites as $website)
                                <option value="{{ $website->id }}">{{ $website->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="pagesList">
                        <p class="text-muted text-center py-4">Select a website to view pages</p>
                    </div>
                </div>
            </div>

            <!-- Heatmap Display -->
            <div class="col-md-9">
                <!-- Controls -->
                <div class="controls-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Heatmap Controls</h5>
                        <div>
                            <select class="form-select d-inline-block w-auto" id="deviceFilter">
                                <option value="">All Devices</option>
                                <option value="desktop">Desktop</option>
                                <option value="mobile">Mobile</option>
                                <option value="tablet">Tablet</option>
                            </select>
                            <select class="form-select d-inline-block w-auto ms-2" id="dateFilter">
                                <option value="7">Last 7 days</option>
                                <option value="30">Last 30 days</option>
                                <option value="90">Last 90 days</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <button class="btn btn-outline-primary heatmap-type-btn active" data-type="click" onclick="changeHeatmapType('click')">
                            <i class="fas fa-mouse-pointer"></i> Click Heatmap
                        </button>
                        <button class="btn btn-outline-primary heatmap-type-btn" data-type="move" onclick="changeHeatmapType('move')">
                            <i class="fas fa-arrows-alt"></i> Move Heatmap
                        </button>
                        <button class="btn btn-outline-primary heatmap-type-btn" data-type="scroll" onclick="changeHeatmapType('scroll')">
                            <i class="fas fa-arrows-alt-v"></i> Scroll Depth
                        </button>
                        <button class="btn btn-outline-success" onclick="refreshHeatmap()">
                            <i class="fas fa-sync"></i> Refresh
                        </button>
                    </div>
                </div>

                <!-- Stats -->
                <div id="heatmapStats" class="stats-grid" style="display: none;"></div>

                <!-- Heatmap Display -->
                <div id="heatmapDisplay" class="text-center py-5">
                    <i class="fas fa-fire fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Select a page from the sidebar to view heatmap</p>
                </div>

                <!-- Element Click Stats -->
                <div id="elementStats" class="controls-card mt-3" style="display: none;">
                    <h5 class="mb-3">Top Clicked Elements</h5>
                    <div class="element-stats-table" id="elementStatsList"></div>
                </div>
            </div>
        </div>

    <script>
        let currentWebsiteId = null;
        let currentPagePath = null;
        let currentType = 'click';
        let heatmapInstance = null;

        document.getElementById('websiteSelect').addEventListener('change', function() {
            currentWebsiteId = this.value;
            if (currentWebsiteId) {
                loadPopularPages();
            }
        });

        document.getElementById('deviceFilter').addEventListener('change', refreshHeatmap);
        document.getElementById('dateFilter').addEventListener('change', refreshHeatmap);

        async function loadPopularPages() {
            try {
                const response = await fetch(`/api/heatmap/popular-pages?website_id=${currentWebsiteId}`, {
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
                
                const container = document.getElementById('pagesList');
                if (data.pages.length === 0) {
                    container.innerHTML = '<p class="text-muted text-center py-4">No pages found</p>';
                    return;
                }

                container.innerHTML = data.pages.map(page => `
                    <div class="page-item-custom" onclick="selectPage('${page.page_path}', '${page.page_url}')">
                        <div class="fw-bold">${page.page_path}</div>
                        <div class="small text-muted">${page.visitors} visitors</div>
                    </div>
                `).join('');
            } catch (error) {
                console.error('Failed to load pages:', error);
            }
        }

        function selectPage(pagePath, pageUrl) {
            currentPagePath = pagePath;
            
            // Update active state
            document.querySelectorAll('.page-item-custom').forEach(item => {
                item.classList.remove('active');
            });
            event.target.closest('.page-item-custom').classList.add('active');
            
            loadHeatmap();
        }

        async function loadHeatmap() {
            if (!currentWebsiteId || !currentPagePath) return;

            const device = document.getElementById('deviceFilter').value;
            const days = document.getElementById('dateFilter').value;

            try {
                if (currentType === 'scroll') {
                    await loadScrollDepth(device, days);
                } else {
                    await loadClickMoveHeatmap(device, days);
                }

                // Load element stats for click heatmap
                if (currentType === 'click') {
                    await loadElementStats();
                }
            } catch (error) {
                console.error('Failed to load heatmap:', error);
            }
        }

        async function loadClickMoveHeatmap(device, days) {
            const endpoint = currentType === 'click' ? '/api/heatmap/click' : '/api/heatmap/move';
            const params = new URLSearchParams({
                website_id: currentWebsiteId,
                page_path: currentPagePath,
                days: days
            });
            if (device) params.append('device_type', device);

            console.log('Loading heatmap:', endpoint, params.toString());

            const response = await fetch(`${endpoint}?${params}`, {
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const result = await response.json();
            console.log('API response:', result);

            if (!result.data || result.data.length === 0) {
                document.getElementById('heatmapDisplay').innerHTML = `
                    <div class="text-center py-5">
                        <i class="fas fa-chart-area fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No ${currentType} data available for this page</p>
                    </div>
                `;
                return;
            }

            renderHeatmap(result.data);
        }

        async function loadScrollDepth(device, days) {
            const params = new URLSearchParams({
                website_id: currentWebsiteId,
                page_path: currentPagePath,
                days: days
            });
            if (device) params.append('device_type', device);

            const response = await fetch(`/api/heatmap/scroll?${params}`, {
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const result = await response.json();

            renderScrollDepth(result.data);
        }

        async function renderHeatmap(data) {
            console.log('renderHeatmap called with data:', data);
            const display = document.getElementById('heatmapDisplay');
            
            // Fetch screenshot for this page
            let screenshotUrl = null;
            let screenshotDimensions = { width: null, height: null };
            try {
                const response = await fetch(`/api/heatmap/screenshot?website_id=${currentWebsiteId}&page_path=${encodeURIComponent(currentPagePath)}`, {
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                if (response.ok) {
                    const result = await response.json();
                    screenshotUrl = result.screenshot_url || result.screenshot_path; // Try both field names
                    screenshotDimensions = {
                        width: result.viewport_width,
                        height: result.viewport_height
                    };
                    console.log('Screenshot found:', screenshotUrl);
                    console.log('Screenshot dimensions:', screenshotDimensions);
                }
            } catch (e) {
                console.log('No screenshot available:', e);
            }
            
            // Create container with screenshot as background and canvas on top
            // Calculate aspect ratio for wrapper height
            let wrapperHeight = screenshotUrl ? Math.round((screenshotDimensions.height / screenshotDimensions.width) * 100) : 60;
            let containerMaxWidth = screenshotDimensions.width ? Math.min(1200, screenshotDimensions.width) : 1200;
            
            display.innerHTML = `
                <div class="heatmap-wrapper" style="position: relative; margin: 0 auto; width: 100%; max-width: ${containerMaxWidth}px; aspect-ratio: ${screenshotDimensions.width || 1920} / ${screenshotDimensions.height || 1080}; background-color: #f5f5f5; ${screenshotUrl ? `background-image: url('${screenshotUrl}'); background-size: 100% 100%; background-repeat: no-repeat;` : ''} overflow: hidden;">
                    ${screenshotUrl ? `
                        <div id="heatmapCanvasContainer" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 10;"></div>
                    ` : `
                        <div id="heatmapCanvasContainer" style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                            <p class="text-muted">No screenshot available for this page. Screenshots are captured when pages are saved from the builder.</p>
                        </div>
                    `}
                    <div class="heatmap-legend" style="position: absolute; top: 20px; right: 20px; background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.2); z-index: 1000; pointer-events: auto;">
                        <div class="legend-item">
                            <div class="legend-color" style="background: rgba(255, 0, 0, 0.8); width: 30px; height: 20px; border-radius: 4px; display: inline-block;"></div>
                            <span style="margin-left: 10px;">High</span>
                        </div>
                        <div class="legend-item" style="margin-top: 8px;">
                            <div class="legend-color" style="background: rgba(255, 165, 0, 0.6); width: 30px; height: 20px; border-radius: 4px; display: inline-block;"></div>
                            <span style="margin-left: 10px;">Medium</span>
                        </div>
                        <div class="legend-item" style="margin-top: 8px;">
                            <div class="legend-color" style="background: rgba(0, 255, 0, 0.4); width: 30px; height: 20px; border-radius: 4px; display: inline-block;"></div>
                            <span style="margin-left: 10px;">Low</span>
                        </div>
                    </div>
                </div>
            `;

            // Check for empty data
            if (!data || data.length === 0) {
                console.log('No heatmap data to render');
                display.innerHTML += '<div class="alert alert-info mt-3">No heatmap data available for this page yet.</div>';
                return;
            }

            console.log('Processing', data.length, 'data points for heatmap');

            // Initialize heatmap - let heatmap.js create canvas inside div container
            window.initHeatmapAfterImageLoad = () => {
                console.log('🎨 Initializing heatmap overlay...');
                
                const container = document.getElementById('heatmapCanvasContainer');
                const wrapper = document.querySelector('.heatmap-wrapper');
                
                if (!container) {
                    console.error('❌ Canvas container not found!');
                    return;
                }
                
                console.log('✓ Container element found:', container);
                
                // Get wrapper dimensions immediately (aspect-ratio should have been set)
                let displayWidth = wrapper.offsetWidth;
                let displayHeight = wrapper.offsetHeight;
                
                console.log('Wrapper offsetWidth:', displayWidth);
                console.log('Wrapper offsetHeight:', displayHeight);
                
                if (!displayWidth || !displayHeight) {
                    console.error('❌ Invalid wrapper dimensions:', displayWidth, 'x', displayHeight);
                    // Fallback: compute from aspect ratio
                    displayWidth = wrapper.parentElement.offsetWidth || 1000;
                    displayHeight = Math.round((screenshotDimensions.height / screenshotDimensions.width) * displayWidth);
                    console.log('Using fallback dimensions:', displayWidth, 'x', displayHeight);
                }
                
                console.log('✓ Display dimensions:', displayWidth, 'x', displayHeight);
                
                // Set container dimensions - heatmap.js will create canvas inside
                container.style.width = displayWidth + 'px';
                container.style.height = displayHeight + 'px';
                
                console.log('✓ Container dimensions set to:', displayWidth, 'x', displayHeight);
                
                // Create heatmap instance - pass DIV, not canvas
                try {
                    if (typeof h337 === 'undefined') {
                        console.error('❌ heatmap.js library not loaded!');
                        return;
                    }
                    
                    console.log('Creating heatmap.js instance with DIV container...');
                    heatmapInstance = h337.create({
                        container: container,  // Pass the DIV, not a canvas!
                        radius: currentType === 'click' ? 25 : 40,
                        maxOpacity: 0.85,
                        minOpacity: 0.05,
                        blur: 0.85,
                        gradient: {
                            '.0': 'blue',
                            '.25': 'cyan',
                            '.50': 'yellow',
                            '.75': 'orange',
                            '1.0': 'red'
                        }
                    });
                    
                    console.log('✓ Heatmap instance created');
                    console.log('Container children:', container.children.length);
                    
                } catch (e) {
                    console.error('❌ Error creating heatmap:', e);
                    return;
                }
                
                // Transform and set data
                let storedWidth = screenshotDimensions.width || 1920;
                let storedHeight = screenshotDimensions.height || 1080;
                
                console.log('Stored viewport dimensions:', storedWidth, 'x', storedHeight);
                console.log('Display viewport dimensions:', displayWidth, 'x', displayHeight);
                
                const scaleX = displayWidth / storedWidth;
                const scaleY = displayHeight / storedHeight;
                
                console.log('Scale factors:', scaleX.toFixed(4), '×', scaleY.toFixed(4));
                
                // Transform data points
                const points = data.map(point => {
                    const x = Math.round(point.x * scaleX);
                    const y = Math.round(point.y * scaleY);
                    const value = point.click_count || point.move_count || 1;
                    return { x, y, value };
                });
                
                console.log(`✓ Transformed ${points.length} points`);
                
                if (points.length > 0) {
                    const maxValue = Math.max(...points.map(p => p.value));
                    console.log('Max value:', maxValue);
                    console.log('First 3 points:', points.slice(0, 3));
                    
                    try {
                        heatmapInstance.setData({
                            max: maxValue,
                            data: points
                        });
                        console.log('✅ Heatmap data set successfully! Heatmap overlay visible now!');
                    } catch (e) {
                        console.error('❌ Error setting heatmap data:', e);
                    }
                } else {
                    console.warn('⚠️ No data points to render');
                }
            };
            
            // Call init immediately - use requestAnimationFrame to ensure layout is done
            requestAnimationFrame(() => {
                setTimeout(window.initHeatmapAfterImageLoad, 0);
            });
            
            // Show stats
            displayStats(data);
        }

        function renderScrollDepth(scrollData) {
            const display = document.getElementById('heatmapDisplay');
            
            const percentages = scrollData.scroll_percentages;
            let html = `
                <div class="controls-card">
                    <h5 class="mb-4">Scroll Depth Analysis</h5>
                    <div class="mb-4">
                        <div class="scroll-depth-bar">`;
            
            // Add markers at key depths
            Object.keys(percentages).forEach(depth => {
                const percentage = percentages[depth];
                html += `<div class="scroll-marker" style="left: ${depth}%" title="${percentage}% reached"></div>`;
            });
            
            html += `</div></div><div class="stats-grid">`;
            
            // Display percentage at each depth
            [0, 25, 50, 75, 100].forEach(depth => {
                html += `
                    <div class="stat-box">
                        <div class="stat-value">${percentages[depth] || 0}%</div>
                        <div class="stat-label">${depth}% Scroll</div>
                    </div>
                `;
            });
            
            html += `</div>
                <div class="mt-4">
                    <p><strong>Total Users:</strong> ${scrollData.total_users}</p>
                    <p><strong>Average Scroll:</strong> ${Math.round(scrollData.average_scroll)}%</p>
                </div>
            </div>`;
            
            display.innerHTML = html;
        }

        async function loadElementStats() {
            try {
                const response = await fetch(`/api/heatmap/element-stats?website_id=${currentWebsiteId}&page_path=${currentPagePath}`, {
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const result = await response.json();
                
                const container = document.getElementById('elementStats');
                const list = document.getElementById('elementStatsList');
                
                if (result.elements.length === 0) {
                    container.style.display = 'none';
                    return;
                }
                
                container.style.display = 'block';
                list.innerHTML = result.elements.map(el => `
                    <div class="element-row">
                        <div>
                            <div class="fw-bold">${el.element_selector || 'Unknown'}</div>
                            <div class="small text-muted">${el.element_text ? el.element_text.substring(0, 50) : 'No text'}</div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-primary">${el.clicks} clicks</div>
                            <div class="small text-muted">${el.unique_users} users</div>
                        </div>
                    </div>
                `).join('');
            } catch (error) {
                console.error('Failed to load element stats:', error);
            }
        }

        function displayStats(data) {
            const statsContainer = document.getElementById('heatmapStats');
            const totalInteractions = data.reduce((sum, point) => sum + (point.click_count || point.move_count || 0), 0);
            const uniquePoints = data.length;
            
            statsContainer.innerHTML = `
                <div class="stat-box">
                    <div class="stat-value">${totalInteractions}</div>
                    <div class="stat-label">Total ${currentType === 'click' ? 'Clicks' : 'Moves'}</div>
                </div>
                <div class="stat-box">
                    <div class="stat-value">${uniquePoints}</div>
                    <div class="stat-label">Unique Points</div>
                </div>
                <div class="stat-box">
                    <div class="stat-value">${Math.round(totalInteractions / uniquePoints)}</div>
                    <div class="stat-label">Avg per Point</div>
                </div>
            `;
            statsContainer.style.display = 'grid';
        }

        function changeHeatmapType(type) {
            currentType = type;
            
            // Update button states
            document.querySelectorAll('.heatmap-type-btn').forEach(btn => {
                btn.classList.remove('active');
                if (btn.dataset.type === type) {
                    btn.classList.add('active');
                }
            });
            
            // Hide element stats for non-click types
            if (type !== 'click') {
                document.getElementById('elementStats').style.display = 'none';
            }
            
            loadHeatmap();
        }

        function refreshHeatmap() {
            if (currentPagePath) {
                loadHeatmap();
            }
        }

        async function captureScreenshot() {
            if (!currentWebsiteId || !currentPagePath) {
                alert('Please select a website and page first');
                return;
            }

            const pageUrl = prompt('Enter the full URL of the page to capture:', window.location.origin + currentPagePath);
            if (!pageUrl) return;

            // Open page in new window for screenshot
            const win = window.open(pageUrl, '_blank', 'width=1920,height=1080');
            
            alert('Please wait for the page to load fully, then click OK to capture the screenshot.');
            
            // Note: Actual screenshot capture would require server-side tool like Puppeteer
            // For now, show instructions
            alert('Screenshot capture requires server-side setup with Puppeteer or similar tool. For now, use browser screenshot and upload manually.');
            
            if (win) win.close();
        }
    </script>
</div>

<script src="https://cdn.jsdelivr.net/npm/heatmap.js@2.0.5/build/heatmap.min.js"></script>
@endsection
