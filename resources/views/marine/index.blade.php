@extends('layouts.app')

@section('content')
@php
    use App\Models\Setting;
@endphp

<div class="container-fluid px-0">
    <!-- Header Title Section Dinamis -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">{{ Setting::get('hero_heading', 'Marine Traffic Live Monitor') }}</h3>
            <p class="text-muted mb-0">{{ Setting::get('hero_subheading', 'Pantau pergerakan kapal dan risiko maritim secara real-time.') }}</p>
        </div>
        <span class="badge bg-primary px-3 py-2 rounded-pill">
            <i class="bi bi-broadcast me-1"></i> Live Stream Active
        </span>
    </div>

    <!-- Map Card Container -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
        <div class="card-body p-0">
            <div id="map"></div>
        </div>
    </div>

    <!-- Data Table Container -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white py-3 border-0">
            <h5 class="fw-bold mb-0 text-dark">
                <i class="bi bi-ship text-primary me-2"></i>Daftar Kapal & Pelabuhan Terdeteksi
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Nama / Kapal</th>
                            <th>Kode / MMSI</th>
                            <th>Negara</th>
                            <th>Koordinat</th>
                            <th>Kecepatan / Congestion</th>
                        </tr>
                    </thead>
                    <tbody id="marine-table-body">
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                                Memuat data maritim live...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Reset Map Container
        const mapContainer = L.DomUtil.get('map');
        if (mapContainer != null) {
            mapContainer._leaflet_id = null;
        }

        const map = L.map('map').setView([0.0, 115.0], 4);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Fetch Data AJAX
        fetch('/api/marine')
            .then(res => res.json())
            .then(data => {
                const tableBody = document.getElementById('marine-table-body');
                if (!tableBody) return;

                tableBody.innerHTML = '';

                if (!data || data.length === 0) {
                    tableBody.innerHTML = `<tr><td colspan="5" class="text-center py-4 text-danger font-semibold">Tidak ada data kapal terdeteksi saat ini.</td></tr>`;
                    return;
                }

                data.forEach(item => {
                    if (item.latitude && item.longitude) {
                        L.marker([item.latitude, item.longitude])
                            .addTo(map)
                            .bindPopup(`<b>${item.name}</b><br>Negara: ${item.country_iso}<br>Kecepatan: ${item.congestion} knots`);
                    }

                    const row = `
                        <tr>
                            <td class="ps-4 fw-bold text-dark">${item.name}</td>
                            <td><span class="badge bg-light text-dark border">${item.port_code}</span></td>
                            <td><span class="badge bg-info text-dark">${item.country_iso}</span></td>
                            <td class="text-muted">${item.latitude}, ${item.longitude}</td>
                            <td><span class="fw-bold text-primary">${item.congestion} knots</span></td>
                        </tr>
                    `;
                    tableBody.innerHTML += row;
                });
            })
            .catch(err => {
                console.error('Fetch Error:', err);
                const tableBody = document.getElementById('marine-table-body');
                if (tableBody) {
                    tableBody.innerHTML = `<tr><td colspan="5" class="text-center py-4 text-danger font-semibold">Gagal memuat data dari server.</td></tr>`;
                }
            });
    });
</script>
@endpush