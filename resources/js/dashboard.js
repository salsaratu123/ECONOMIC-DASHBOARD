import { updateCountrySelect, updateCountryPanel } from './country';
import { updateWeatherSummary, updateWeatherChart } from './weather';
import { updateEconomySummary, updateEconomyChart } from './economy';
import { updateExchangeSummary, updateExchangeChart } from './exchange';
import { initializeMap, updateWorldMap } from './map';
import { updateNews } from './news';
import { updateShipments } from './shipment';
import { updateRisk } from './risk';
import { exportDashboardExcel, exportDashboardPdf, setLoading, showToast } from './utils';

// Mengambil value awal langsung dari DOM selectbox (supaya dinamis membaca kode ISO seperti IDN)
const countrySelectEl = document.getElementById('countrySelect');
let selectedCountryIso = countrySelectEl ? countrySelectEl.value : 'IDN'; 
let latestDashboardData = null;

document.addEventListener('DOMContentLoaded', () => {
    initializeMap();
    bindCountryControls();
    bindToolbarControls();
    loadDashboard();
    
    // Auto-refresh data monitoring setiap 60 detik (Real-time Simulation)
    window.setInterval(loadDashboard, 60000);
});

function bindCountryControls() {
    const countrySelect = document.getElementById('countrySelect');
    const countrySearch = document.getElementById('countrySearch');

    countrySelect?.addEventListener('change', (event) => {
        selectedCountryIso = event.target.value;
        loadDashboard();
    });

    countrySearch?.addEventListener('keyup', (event) => {
        if (event.key === 'Enter' && event.target.value.trim()) {
            // Jika user mengetik manual, ubah ke uppercase untuk standardisasi parameter ISO backend
            selectedCountryIso = event.target.value.trim().toUpperCase();
            loadDashboard();
        }
    });
}

function bindToolbarControls() {
    document.getElementById('darkModeToggle')?.addEventListener('click', () => {
        document.body.classList.toggle('dark-mode');
        localStorage.setItem('dashboard-dark-mode', document.body.classList.contains('dark-mode') ? '1' : '0');
    });

    if (localStorage.getItem('dashboard-dark-mode') === '1') {
        document.body.classList.add('dark-mode');
    }

    document.getElementById('exportPdfBtn')?.addEventListener('click', () => {
        exportDashboardPdf();
        showToast('Ekspor PDF', 'Dialog cetak dokumen sistem logistik dibuka.');
    });

    document.getElementById('exportExcelBtn')?.addEventListener('click', () => {
        exportDashboardExcel(latestDashboardData);
        showToast('Ekspor Excel', 'Snapshot analisis risiko berhasil diunduh.');
    });
}

async function loadDashboard() {
    setLiveStatus('SYNCING', 'warning');
    setLoading(true);

    try {
        // Memanfaatkan Axios Engine terpusat bawaan Laravel untuk mengambil data terintegrasi
        const response = await window.axios.get(`/api/dashboard?country=${encodeURIComponent(selectedCountryIso)}`);
        const data = response.data;
        
        latestDashboardData = data;
        
        if (data.selected_country && data.selected_country.cca3) {
            selectedCountryIso = data.selected_country.cca3;
        }

        // Distribusi data terpusat ke seluruh komponen widget dashboard via AJAX
        updateCountrySelect(data.countries ?? [], selectedCountryIso);
        updateCountryPanel(data.selected_country);
        
        if (data.risk && data.risk.breakdown) {
            updateWeatherSummary(data.risk.breakdown);
            updateWeatherChart(data.risk.breakdown);
            updateEconomySummary(data.risk.breakdown, data.selected_country);
            updateEconomyChart(data.risk.breakdown);
            updateExchangeSummary(data.risk.breakdown, data.selected_country);
            updateExchangeChart(data.risk.breakdown);
        }
        
        updateWorldMap(data);
        updateNews(data.news);
        updateRisk(data.risk);
        
        await loadShipments();

        // Kembalikan status ke LIVE hijau jika sinkronisasi sukses
        setLiveStatus('LIVE', 'success');
        
    } catch (error) {
        console.error("Dashboard Sync Error:", error);
        setLiveStatus('OFFLINE', 'danger');
        
        // Tampilkan peringatan toast error merah persis seperti di screenshot kamu
        const toastElement = document.getElementById('appToast');
        if (toastElement) {
            document.getElementById('toastTitle').textContent = 'Dashboard Offline';
            document.getElementById('toastMessage').textContent = 'Gagal menyinkronkan data multi-API atau database lokal belum di-seed.';
            toastElement.className = `toast border-0 shadow text-bg-danger show`;
        }
    } finally {
        setLoading(false);
    }
}

async function loadShipments() {
    try {
        const response = await window.axios.get('/api/v1/shipments');
        updateShipments(response.data ?? []);
    } catch (error) {
        updateShipments([]);
    }
}

function setLiveStatus(label, color) {
    const badge = document.getElementById('liveStatus');
    if (badge) {
        badge.innerHTML = `<span class="spinner-grow spinner-grow-sm me-1" role="status" aria-hidden="true"></span> ${label}`;
        badge.className = `badge bg-${color}-subtle text-${color} border border-${color}-subtle px-3 py-2 rounded-pill fw-bold`;
    }
}