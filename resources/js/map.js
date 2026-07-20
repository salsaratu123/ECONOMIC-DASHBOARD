let map = null;
let countryLayer = null;
let portLayer = null;

export function initializeMap() {
    const mapElement = document.getElementById('map');
    if (!mapElement || map) return;

    map = L.map('map', { zoomControl: false }).setView([1.3521, 103.8198], 3);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    // Tambahkan kontrol zoom di posisi kanan bawah agar UI terlihat bersih
    L.control.zoom({ position: 'bottomright' }).addTo(map);

    countryLayer = L.layerGroup().addTo(map);
    portLayer = L.layerGroup().addTo(map);
}

export function updateWorldMap(data) {
    if (!map) return;

    countryLayer.clearLayers();
    portLayer.clearLayers();

    if (data.selected_country) {
        addCountryMarker(data.selected_country, data.risk);
    }

    // Ambil referensi elemen tabel di halaman maritim
    const tableBody = document.getElementById('marinePortTableBody');
    if (tableBody) {
        tableBody.innerHTML = ''; // Bersihkan loader loading
    }

    if (data.ports && data.ports.length) {
        data.ports.forEach((port) => {
            // 1. Gambar titik koordinat di Peta Leaflet
            addPortMarker(port);
            
            // 2. Tulis data ke dalam tabel HTML jika elemen tabel ditemukan di halaman
            if (tableBody) {
                const congestionVal = port.congestion ?? 50;
                const badgeColor = congestionVal > 70 ? 'danger' : (congestionVal > 40 ? 'warning' : 'success');
                
                const rowHtml = `
                    <tr>
                        <td class="fw-bold text-dark"><i class="bi bi-anchor text-secondary me-2"></i>${port.name}</td>
                        <td class="text-muted font-monospace small">${port.latitude}</td>
                        <td class="text-muted font-monospace small">${port.longitude}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2" style="max-width: 200px;">
                                <div class="progress flex-grow-1" style="height: 6px;">
                                    <div class="progress-bar bg-${badgeColor}" style="width: ${congestionVal}%"></div>
                                </div>
                                <span class="fw-bold text-dark small">${congestionVal}%</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-${badgeColor}-subtle text-${badgeColor} border border-${badgeColor}-subtle rounded-pill px-3 py-1 fw-semibold small">
                                ${congestionVal > 70 ? 'HEAVY CONGESTION' : (congestionVal > 40 ? 'MODERATE FLOW' : 'CLEAR ROUTE')}
                            </span>
                        </td>
                    </tr>
                `;
                tableBody.insertAdjacentHTML('beforeend', rowHtml);
            }
        });
    } else {
        if (tableBody) {
            tableBody.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-3">Tidak ada data aktivitas logistik pelabuhan terdeteksi.</td></tr>';
        }
    }

    const lat = parseFloat(data.selected_country?.latitude);
    const lng = parseFloat(data.selected_country?.longitude);
    if (lat && lng) {
        map.panTo([lat, lng]);
    }
}

function addCountryMarker(country, risk) {
    const lat = parseFloat(country.latitude);
    const lng = parseFloat(country.longitude);
    if (!lat || !lng) return;

    const markerIcon = L.divIcon({
        className: 'custom-div-icon',
        html: `<div style='background-color:#1e3c72; width:14px; height:14px; border:2px solid #fff; border-radius:50%; shadow:0 0 8px rgba(0,0,0,0.3)'></div>`,
        iconSize: [14, 14],
        iconAnchor: [7, 7]
    });

    L.marker([lat, lng], { icon: markerIcon }).addTo(countryLayer).bindPopup(`
        <div class="p-2" style="font-family:'Plus Jakarta Sans', sans-serif;">
            <h6 class="fw-bold mb-1 text-primary">${country.name}</h6>
            <small class="d-block text-muted mb-2">Wilayah: ${country.region}</small>
            <span class="badge bg-dark mb-1">Skor Risiko: ${risk?.score ?? 0} PTS</span>
        </div>
    `);
}

function addPortMarker(port) {
    const marker = L.circleMarker([port.latitude, port.longitude], {
        radius: 8,
        color: '#f97316',
        fillColor: '#fdba74',
        fillOpacity: 0.9,
        weight: 2
    }).addTo(portLayer);

    marker.bindPopup(`
        <div style="font-family:'Plus Jakarta Sans', sans-serif;">
            <strong class="text-dark"><i class="bi bi-anchor-fill text-warning me-1"></i> ${port.name}</strong><br>
            <small>Kode Port: ${port.code ?? '-'}</small><br>
            <small class="text-success fw-semibold">Simulasi Kepadatan: ${port.congestion ?? 50}%</small>
        </div>
    `);
}