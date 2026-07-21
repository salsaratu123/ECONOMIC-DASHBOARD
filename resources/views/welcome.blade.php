<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Global Supply Chain Risk Intelligence Dashboard</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Leaflet CSS (Map) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        body { background-color: #0f172a; color: #f8fafc; font-family: 'Inter', sans-serif; }
        .sidebar { background-color: #1e293b; min-height: 100vh; border-right: 1px solid #334155; }
        .card-custom { background-color: #1e293b; border: 1px solid #334155; border-radius: 10px; }
        .announcement-bar { background-color: #f59e0b; color: #000; font-weight: 600; font-size: 0.9rem; }
        #map { height: 350px; border-radius: 8px; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Navigation -->
        <div class="col-md-2 sidebar p-3">
            <h4 class="fw-bold text-primary mb-4"><i class="fa-solid fa-globe me-2"></i>RISK INTEL</h4>
            <div class="nav flex-column nav-pills">
                <a class="nav-link active mb-2" href="#"><i class="fa-solid fa-gauge me-2"></i>Core Dashboard</a>
                <a class="nav-link text-light mb-2" href="/weather"><i class="fa-solid fa-cloud-sun me-2"></i>Weather Monitoring</a>
                <a class="nav-link text-light mb-2" href="/economy"><i class="fa-solid fa-chart-line me-2"></i>Economic Indicators</a>
                <a class="nav-link text-light mb-2" href="/exchange"><i class="fa-solid fa-coins me-2"></i>Currency Impact</a>
                <a class="nav-link text-light mb-2" href="/marine"><i class="fa-solid fa-ship me-2"></i>Marine Ports</a>
                <a class="nav-link text-light mb-2" href="/news"><i class="fa-solid fa-newspaper me-2"></i>News Intelligence</a>
                <a class="nav-link text-light mb-2" href="/comparison"><i class="fa-solid fa-code-compare me-2"></i>Country Comparison</a>
                <a class="nav-link text-light mb-2" href="/watchlist"><i class="fa-solid fa-star me-2"></i>Watchlist Matrix</a>
            </div>
            <div class="mt-5 pt-5">
                <a href="/admin/dashboard" class="btn btn-outline-secondary w-100 text-light"><i class="fa-solid fa-gear me-2"></i>Admin Console</a>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="col-md-10 p-0">
            <!-- Announcement Bar -->
            <div class="announcement-bar p-2 text-center">
                <i class="fa-solid fa-bullhorn me-2"></i>Sistem berjalan normal. Semua data live terhubung.
            </div>

            <div class="p-4">
                <!-- Header Card -->
                <div class="card card-custom p-4 mb-4">
                    <h2 class="fw-bold text-white">Global Supply Chain Risk Intelligence Dashboard</h2>
                    <p class="text-secondary mb-0">Use the dashboard navigation to monitor weather, economy, exchange, news, marine ports, shipment, and risk intelligence.</p>
                </div>

                <!-- KPI Metric Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card card-custom p-3 border-start border-4 border-info">
                            <span class="text-secondary small">Global Traffic Status</span>
                            <h3 class="fw-bold text-info my-1">Normal</h3>
                            <span class="small text-success"><i class="fa-solid fa-arrow-up me-1"></i>98.4% Operational</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-custom p-3 border-start border-4 border-warning">
                            <span class="text-secondary small">Port Congestion Index</span>
                            <h3 class="fw-bold text-warning my-1">2.4 / 5.0</h3>
                            <span class="small text-secondary">Moderate Activity</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-custom p-3 border-start border-4 border-danger">
                            <span class="text-secondary small">Disruption Risk Alerts</span>
                            <h3 class="fw-bold text-danger my-1">3 Active</h3>
                            <span class="small text-danger"><i class="fa-solid fa-triangle-exclamation me-1"></i>Weather & Route Delays</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-custom p-3 border-start border-4 border-success">
                            <span class="text-secondary small">Monitored Vessels</span>
                            <h3 class="fw-bold text-success my-1">1,420</h3>
                            <span class="small text-success">Live Tracking Active</span>
                        </div>
                    </div>
                </div>

                <!-- Charts & Map Section -->
                <div class="row mb-4">
                    <!-- Vessel Traffic Chart -->
                    <div class="col-md-6">
                        <div class="card card-custom p-3">
                            <h5 class="fw-bold text-white mb-3"><i class="fa-solid fa-chart-area me-2 text-primary"></i>Maritime Traffic Trend</h5>
                            <canvas id="trafficChart" height="200"></canvas>
                        </div>
                    </div>
                    <!-- Live Map -->
                    <div class="col-md-6">
                        <div class="card card-custom p-3">
                            <h5 class="fw-bold text-white mb-3"><i class="fa-solid fa-map-location-dot me-2 text-success"></i>Global Port Hubs</h5>
                            <div id="map"></div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="d-flex justify-content-between text-secondary border-top border-secondary pt-3 mt-4 small">
                    <span>© 2026 Global Supply Chain Risk Intelligence Dashboard</span>
                    <span>Enterprise Analytics Engine | Laravel 12 & Bootstrap 5 & Chart.js & Leaflet</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JS Dependencies -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Initialize Chart.js
        const ctx = document.getElementById('trafficChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                datasets: [{
                    label: 'Vessel Movement Volume',
                    data: [1200, 1350, 1250, 1420, 1500, 1480, 1620],
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { labels: { color: '#cbd5e1' } } },
                scales: {
                    x: { ticks: { color: '#94a3b8' }, grid: { color: '#334155' } },
                    y: { ticks: { color: '#94a3b8' }, grid: { color: '#334155' } }
                }
            }
        });

        // Initialize Leaflet Map
        const map = L.map('map').setView([-2.5489, 118.0149], 3); // Indonesia / Southeast Asia View
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Fetch Ports from API Endpoint
        fetch('/api/ports')
            .then(res => res.json())
            .then(resData => {
                if(resData.data && resData.data.length > 0) {
                    resData.data.forEach(port => {
                        if(port.latitude && port.longitude) {
                            L.marker([port.latitude, port.longitude])
                                .addTo(map)
                                .bindPopup(`<b>${port.name}</b><br>Code: ${port.code}<br>Status: ${port.status}`);
                        }
                    });
                }
            })
            .catch(err => console.log('Map Ports Fetch Error:', err));
    });
</script>

</body>
</html>