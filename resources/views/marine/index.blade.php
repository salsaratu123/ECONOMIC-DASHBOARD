@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-4">Marine Traffic Live Monitor</h1>

    <!-- Container Peta -->
    <div id="map" class="w-full h-96 rounded-lg shadow-md mb-6 bg-gray-100 relative z-0"></div>

    <!-- Tabel Monitoring Pelabuhan/Kapal -->
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full leading-normal">
            <thead>
                <tr class="bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    <th class="px-5 py-3">Nama / Kapal</th>
                    <th class="px-5 py-3">Kode / MMSI</th>
                    <th class="px-5 py-3">Negara</th>
                    <th class="px-5 py-3">Koordinat</th>
                    <th class="px-5 py-3">Speed / Congestion</th>
                </tr>
            </thead>
            <tbody id="marine-table-body">
                <tr>
                    <td colspan="5" class="px-5 py-5 text-center text-gray-500">Memuat data live dari ShipFinder API...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Dependency Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Safe Reset Container Leaflet
        const mapContainer = L.DomUtil.get('map');
        if (mapContainer != null) {
            mapContainer._leaflet_id = null;
        }

        const map = L.map('map').setView([0.0, 115.0], 4);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Fetch Live Data dari API
        fetch('/api/marine')
            .then(res => res.json())
            .then(data => {
                const tableBody = document.getElementById('marine-table-body');
                if (!tableBody) return;

                tableBody.innerHTML = '';

                if (!data || data.length === 0) {
                    tableBody.innerHTML = `<tr><td colspan="5" class="px-5 py-5 text-center text-red-500 font-semibold">Tidak ada data kapal terdeteksi saat ini.</td></tr>`;
                    return;
                }

                data.forEach(item => {
                    if (item.latitude && item.longitude) {
                        L.marker([item.latitude, item.longitude])
                            .addTo(map)
                            .bindPopup(`<b>${item.name}</b><br>Negara: ${item.country_iso}<br>Kecepatan: ${item.congestion} knots`);
                    }

                    const row = `
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-5 py-4 text-sm font-semibold text-gray-900">${item.name}</td>
                            <td class="px-5 py-4 text-sm text-gray-600">${item.port_code}</td>
                            <td class="px-5 py-4 text-sm text-gray-600">${item.country_iso}</td>
                            <td class="px-5 py-4 text-sm text-gray-600">${item.latitude}, ${item.longitude}</td>
                            <td class="px-5 py-4 text-sm text-gray-600">${item.congestion} knots</td>
                        </tr>
                    `;
                    tableBody.innerHTML += row;
                });
            })
            .catch(err => {
                console.error('Fetch Error:', err);
                const tableBody = document.getElementById('marine-table-body');
                if (tableBody) {
                    tableBody.innerHTML = `<tr><td colspan="5" class="px-5 py-5 text-center text-red-500 font-semibold">Gagal memuat data dari server.</td></tr>`;
                }
            });
    });
</script>
@endsection