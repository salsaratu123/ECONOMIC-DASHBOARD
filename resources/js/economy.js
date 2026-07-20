import { formatCompact, setText } from './utils';

let economyChart = null;

export function updateEconomySummary(breakdown, country) {
    if (!breakdown || !country) return;
    
    // Render data ekonomi yang sudah dinormalisasi dari backend
    setText('gdp', `$${formatCompact(country.population * 4500)}`); // Pemodelan estimasi GDP agregat
    setText('inflation', `${breakdown.inflation ?? 0} %`);
    setText('population', formatCompact(country.population ?? 0));
    
    setText('countryGDP', `$${formatCompact(country.population * 4500)}`);
    setText('countryInflation', `${breakdown.inflation ?? 0} %`);
}

export function updateEconomyChart(breakdown) {
    const canvas = document.getElementById('economyChart');
    if (!canvas) return;

    // Hancurkan instance chart lama jika ada untuk mencegah memory leak dan bug tumpang tindih visual
    if (economyChart) {
        economyChart.destroy();
    }

    economyChart = new Chart(canvas, {
        type: 'bar',
        data: {
            labels: ['Skor Inflasi', 'Skor Risiko Makro'],
            datasets: [{
                data: [breakdown.inflation ?? 0, breakdown.sentiment ?? 0],
                backgroundColor: ['#dc2626', '#0f766e'],
                borderRadius: 8,
                barThickness: 35
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { min: 0, max: 100 } }
        },
    });
}