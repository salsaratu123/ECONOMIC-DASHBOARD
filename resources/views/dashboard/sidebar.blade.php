<div class="sidebar bg-dark text-white p-4 vh-100 d-flex flex-column" style="min-wdt: 260px;">
    <div class="logo mb-5 text-center text-xl-start">
        <div class="d-flex align-items-center gap-2 justify-content-center justify-content-xl-start">
            <i class="bi bi-globe2 fs-2 text-primary"></i>
            <div>
                <h5 class="mb-0 fw-bold tracking-wide text-white">RISK INTEL</h5>
                <small class="text-muted text-uppercase" style="font-size: 0.7rem; letter-spacing: 1px;">Supply Chain Platform</small>
            </div>
        </div>
    </div>

    <ul class="nav flex-column gap-2 mb-auto w-100">
        <li class="nav-item">
            <a href="{{ route('dashboard.index') }}" class="nav-link text-white rounded-3 px-3 py-2 d-flex align-items-center gap-3 {{ Request::is('/') ? 'bg-primary active fw-semibold' : 'opacity-75 hover-bg-light' }}">
                <i class="bi bi-speedometer2 fs-5"></i> <span>Core Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('weather.index') }}" class="nav-link text-white rounded-3 px-3 py-2 d-flex align-items-center gap-3 {{ Request::is('weather') ? 'bg-primary active fw-semibold' : 'opacity-75 hover-bg-light' }}">
                <i class="bi bi-cloud-sun fs-5"></i> <span>Weather Monitoring</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('economy.index') }}" class="nav-link text-white rounded-3 px-3 py-2 d-flex align-items-center gap-3 {{ Request::is('economy') ? 'bg-primary active fw-semibold' : 'opacity-75 hover-bg-light' }}">
                <i class="bi bi-graph-up fs-5"></i> <span>Economic Indicators</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('exchange.index') }}" class="nav-link text-white rounded-3 px-3 py-2 d-flex align-items-center gap-3 {{ Request::is('exchange') ? 'bg-primary active fw-semibold' : 'opacity-75 hover-bg-light' }}">
                <i class="bi bi-currency-exchange fs-5"></i> <span>Currency Impact</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('marine.index') }}" class="nav-link text-white rounded-3 px-3 py-2 d-flex align-items-center gap-3 {{ Request::is('marine') ? 'bg-primary active fw-semibold' : 'opacity-75 hover-bg-light' }}">
                <i class="bi bi-anchor fs-5"></i> <span>Marine Ports</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('news.index') }}" class="nav-link text-white rounded-3 px-3 py-2 d-flex align-items-center gap-3 {{ Request::is('news') ? 'bg-primary active fw-semibold' : 'opacity-75 hover-bg-light' }}">
                <i class="bi bi-newspaper fs-5"></i> <span>News Intelligence</span>
            </a>
        </li>
        
        <li class="nav-item">
            <a href="{{ route('countries.comparison') }}" class="nav-link text-white rounded-3 px-3 py-2 d-flex align-items-center gap-3 {{ Request::is('comparison') ? 'bg-primary active fw-semibold' : 'opacity-75 hover-bg-light' }}">
                <i class="bi bi-arrow-left-right fs-5"></i> <span>Country Comparison</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('countries.watchlist') }}" class="nav-link text-white rounded-3 px-3 py-2 d-flex align-items-center gap-3 {{ Request::is('watchlist') ? 'bg-primary active fw-semibold' : 'opacity-75 hover-bg-light' }}">
                <i class="bi bi-star fs-5"></i> <span>Watchlist Matrix</span>
            </a>
        </li>
    </ul>

    <div class="pt-3 border-top border-secondary mt-3">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-light w-100 d-flex align-items-center justify-content-center gap-2 py-2 border-secondary opacity-75">
            <i class="bi bi-gear-fill"></i> Admin Console
        </a>
    </div>
</div>