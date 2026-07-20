import { setText, numberValue } from './utils';

let weatherChart = null;

export function updateWeatherSummary(breakdown) {
    if (!breakdown) return;

    // Menampilkan simulasi data metrik cuaca logistik yang proporsional dengan persentase risiko
    const simulatedTemp = 28.5;
    const simulatedRain = (breakdown.weather / 8).toFixed(1);
    const simulatedWind = (breakdown.weather / 2).toFixed(1);

    setText('temperature', `${simulatedTemp} °C`);
    setText('rain', `${simulatedRain} mm`);
    setText('wind', `${simulatedWind} km/h`);
    
    setText('panelTemp', `${simulatedTemp} °C`);
    setText('panelRain', `${simulatedRain} mm`);
    setText('panelWind', `${simulatedWind} km/h`);
}

export function updateWeatherChart(breakdown) {
    const canvas = document.getElementById('weatherChart');
    if (!canvas) return;

    if (weatherChart) {
        weatherChart.destroy();
    }

    weatherChart = new Chart(canvas, {
        type: 'bar',
        data: {
            labels: ['Indeks Cuaca', 'Ambang Batas Logistik'],
            datasets: [{
                data: [numberValue(breakdown.weather), 65],
                backgroundColor: ['#2563eb', '#cbd5e1'],
                borderRadius: 6,
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