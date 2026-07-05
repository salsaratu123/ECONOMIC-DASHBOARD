import './bootstrap';

// Bootstrap
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';

// Bootstrap Icons
import 'bootstrap-icons/font/bootstrap-icons.css';

// Dashboard CSS
import '../css/dashboard.css';

// Leaflet
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

window.L = L;

// Chart.js
import Chart from 'chart.js/auto';

window.Chart = Chart;

// Dashboard JS
import './dashboard';

// Fix icon Leaflet
delete L.Icon.Default.prototype._getIconUrl;

L.Icon.Default.mergeOptions({
    iconRetinaUrl:
        'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',

    iconUrl:
        'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',

    shadowUrl:
        'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png'
});

console.log("✅ Global Supply Chain Dashboard Loaded");