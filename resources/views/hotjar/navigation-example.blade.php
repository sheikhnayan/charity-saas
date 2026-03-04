<!-- Add this to your admin navigation menu -->

<!-- Hotjar Menu Items -->
<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('hotjar.*') ? 'active' : '' }}" href="#" data-bs-toggle="collapse" data-bs-target="#hotjarSubmenu">
        <i class="fas fa-fire"></i>
        <span>Hotjar Analytics</span>
    </a>
    <div id="hotjarSubmenu" class="collapse {{ request()->routeIs('hotjar.*') ? 'show' : '' }}">
        <ul class="nav flex-column ps-3">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('hotjar.recordings') ? 'active' : '' }}" href="{{ route('hotjar.recordings') }}">
                    <i class="fas fa-video"></i> Session Recordings
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('hotjar.heatmaps') ? 'active' : '' }}" href="{{ route('hotjar.heatmaps') }}">
                    <i class="fas fa-fire-alt"></i> Heatmaps
                </a>
            </li>
        </ul>
    </div>
</li>

<!-- Or Simple Links -->
<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('hotjar.recordings') ? 'active' : '' }}" href="{{ route('hotjar.recordings') }}">
        <i class="fas fa-video"></i>
        <span>Session Recordings</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('hotjar.heatmaps') ? 'active' : '' }}" href="{{ route('hotjar.heatmaps') }}">
        <i class="fas fa-fire"></i>
        <span>Heatmaps</span>
    </a>
</li>
