import { setText, numberValue } from './utils';

let exchangeChart = null;

export function updateExchangeSummary(breakdown, country, fullExchangeData) {
    if (!country || !fullExchangeData) return;
    
    const currencyCode = country.currency || country.currency_code || 'IDR';
    
    // Cek dulu apakah element pembungkus teksnya ada di halaman saat ini
    const labelEl = document.getElementById('exchangeLabel');
    if (labelEl) {
        labelEl.textContent = `USD / ${currencyCode}`;
    }

    const currentRate = numberValue(fullExchangeData[currencyCode]);
    if (currentRate > 0) {
        const formattedRate = currentRate > 100 
            ? currentRate.toLocaleString('id-ID', { maximumFractionDigits: 2 }) 
            : currentRate.toFixed(4);
            
        setText('exchange', `${formattedRate} ${currencyCode}`);
    } else {
        setText('exchange', '--');
    }
}

export function updateExchangeChart(breakdown, fullExchangeData) {
    const canvas = document.getElementById('exchangeChart');
    if (!canvas || !fullExchangeData) return; // Jika canvas tidak ada (seperti di halaman marine), langsung berhenti aman

    if (exchangeChart) {
        exchangeChart.destroy();
    }

    const usdRate = 1;
    const idrRate = numberValue(fullExchangeData['IDR']);
    const eurRate = numberValue(fullExchangeData['EUR']);
    const jpyRate = numberValue(fullExchangeData['JPY']);
    const sgdRate = numberValue(fullExchangeData['SGD']);

    exchangeChart = new Chart(canvas, {
        type: 'bar',
        data: {
            labels: ['USD Baseline', 'IDR (Rupiah)', 'EUR (Euro)', 'JPY (Yen)', 'SGD (Dollar SG)'],
            datasets: [{
                label: 'Nilai Tukar per 1 USD',
                data: [usdRate, idrRate, eurRate, jpyRate, sgdRate],
                backgroundColor: ['#334155', '#f59e0b', '#2563eb', '#ef4444', '#0f766e'],
                borderRadius: 6
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    type: 'logarithmic',
                    title: { display: true, text: 'Skala Nilai Tukar (Log)' }
                }
            }
        },
    });
}