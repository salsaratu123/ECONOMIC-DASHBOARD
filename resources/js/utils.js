export function setText(id, value) {
    const element = document.getElementById(id);
    if (element) {
        element.textContent = value ?? '-';
    }
}

export function setLoading(active) {
    const loader = document.getElementById('loadingOverlay');
    if (loader) {
        loader.classList.toggle('d-none', !active);
    }
}

export function showToast(title, message, variant = 'primary') {
    const toastElement = document.getElementById('appToast');
    if (!toastElement || !window.bootstrap) return;

    const titleEl = document.getElementById('toastTitle');
    const msgEl = document.getElementById('toastMessage');
    
    if (titleEl) titleEl.textContent = title;
    if (msgEl) msgEl.textContent = message;
    
    toastElement.className = `toast border-0 shadow-lg text-bg-${variant}`;
    
    // Cegah error jika halaman tidak memuat struktur toast dengan lengkap
    try {
        const toast = window.bootstrap.Toast.getOrCreateInstance(toastElement, { delay: 3500 });
        toast.show();
    } catch (e) {
        console.warn("Toast trigger ignored: ", e.getMessage());
    }
}

export function exportDashboardPdf() {
    window.print();
}

export function exportDashboardExcel(data) {
    if (!data) return;
    
    const country = data.selected_country ?? {};
    const risk = data.risk ?? {};
    const breakdown = risk.breakdown ?? {};

    const rows = [
        ['Parameter Intelijen Bisnis', 'Nilai Metrik'],
        ['Nama Negara', country.name ?? '-'],
        ['Region Logistik', country.region ?? '-'],
        ['Ibu Kota', country.capital ?? '-'],
        ['Skor Risiko Rantai Pasok', risk.score ?? 0],
        ['Status Tingkat Risiko', risk.level ?? '-'],
    ];

    const csv = rows.map((row) => row.map(csvValue).join(',')).join('\n');
    const blob = new Blob([csv], { type: 'application/vnd.ms-excel;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = `Risk_Snapshot_${country.name ?? 'Global'}_2026.csv`;
    link.click();
    URL.revokeObjectURL(link.href);
}

function csvValue(value) {
    return `"${String(value).replaceAll('"', '""')}"`;
}

export function numberValue(value) {
    const number = Number(value);
    return Number.isFinite(number) ? number : 0;
}

export function formatNumber(value) {
    return numberValue(value).toLocaleString('en-US');
}

export function formatCompact(value) {
    return numberValue(value).toLocaleString('en-US', {
        notation: 'compact',
        maximumFractionDigits: 2,
    });
}