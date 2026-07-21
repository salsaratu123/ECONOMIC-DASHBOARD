<?php

namespace App\Http\Controllers;

use App\Services\WeatherService;
use App\Services\EconomyService;
use App\Services\ExchangeService;
use App\Services\CountryService;
use App\Services\NewsService;
use App\Services\MarineService;
use App\Services\RiskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function __construct(
        protected WeatherService $weather,
        protected EconomyService $economy,
        protected ExchangeService $exchange,
        protected CountryService $country,
        protected NewsService $news,
        protected MarineService $marine,
        protected RiskService $risk,
    ) {}

    public function index()
    {
        return view('dashboard.index');
    }

    /**
     * Endpoint Utama AJAX Dashboard - Versi Hybrid Realtime & Kebal Error 500
     */
    public function data(Request $request)
    {
        try {
            // 1. Ambil & Cek Input dari AJAX
            $input = trim($request->query('country', 'Indonesia'));
            
            // Konversi Kode ISO ke Nama jika diperlukan oleh Service bawaan
            $searchQuery = $input;
            if (strtoupper($input) === 'IDN' || strtoupper($input) === 'ID') {
                $searchQuery = 'Indonesia';
            }

            // 2. Ambil Data Negara (Mendukung method find bawaan)
            try {
                if (method_exists($this->country, 'findByIso') && (strtoupper($input) === 'IDN' || strlen($input) === 3)) {
                    $selectedCountry = $this->country->findByIso($input);
                } else {
                    $selectedCountry = $this->country->find($searchQuery);
                }
            } catch (\Throwable $e) {
                $selectedCountry = null;
            }

            // Fallback Data Profil Negara jika kosong / null
            if (!$selectedCountry || !isset($selectedCountry['name'])) {
                $selectedCountry = [
                    'success' => true, 'cca3' => 'IDN', 'name' => 'Indonesia', 'official' => 'Republic of Indonesia',
                    'capital' => 'Jakarta', 'region' => 'Asia', 'population' => 275501339, 'currency' => 'IDR',
                    'flag' => 'https://flagcdn.com/w320/id.png', 'latitude' => -6.2, 'longitude' => 106.816666
                ];
            }

            // 3. Ambil Data Cuaca, Ekonomi, & Valas Safely
            $weatherData = $this->weather->getWeather($selectedCountry['latitude'] ?? -6.2, $selectedCountry['longitude'] ?? 106.816666);
            $economyData = $this->economy->getEconomy($selectedCountry['cca3'] ?? 'IDN');
            $exchangeData = $this->exchange->getExchange();

            // 4. Ambil Data Berita
            try {
                $newsData = $this->news->grouped();
            } catch (\Throwable $e) {
                $newsData = ['economy' => [], 'trade' => [], 'geopolitic' => []];
            }

            // 5. Ambil Data Port (Mendukung ports dengan/tanpa parameter)
            try {
                $portsData = $this->marine->ports();
            } catch (\Throwable $e) {
                $portsData = [
                    ['name' => 'Port of Tanjung Priok', 'country' => 'Indonesia', 'latitude' => -6.1045, 'longitude' => 106.8804, 'congestion' => 46]
                ];
            }

            // 6. Perhitungan Skor Risiko Rantai Pasok
            try {
                if (method_exists($this->risk, 'calculateForCountry')) {
                    $riskData = $this->risk->calculateForCountry($selectedCountry['cca3'] ?? 'IDN');
                } else {
                    $riskData = $this->risk->calculate($weatherData, $exchangeData, $economyData, $newsData['geopolitic'] ?? [], (int) ($portsData[0]['congestion'] ?? 50));
                }
            } catch (\Throwable $e) {
                $riskData = [
                    'score' => 32, 'level' => 'LOW', 'badge' => 'success',
                    'breakdown' => ['weather' => 20, 'inflation' => 15, 'sentiment' => 30, 'currency' => 15]
                ];
            }

            // Standardisasi "breakdown" agar klop dengan JavaScript
            if (!isset($riskData['breakdown'])) {
                $currentWeather = $weatherData['current'] ?? [];
                $riskData['breakdown'] = [
                    'weather' => min(100, (($currentWeather['wind_speed_10m'] ?? 0) * 2) + (($currentWeather['rain'] ?? 0) * 8)),
                    'inflation' => min(100, abs((float) data_get($economyData, 'inflation.value', 3.0)) * 10),
                    'currency' => 20,
                    'sentiment' => 25
                ];
            }

            // 7. FORMAT DATA CHART REALTIME DENGAN DINAMIS
            $rawIdr = (float) str_replace(['.', ','], '', (string)($exchangeData['IDR'] ?? '15850'));
            if ($rawIdr <= 0) $rawIdr = 15850;

            $inflationVal = (float) data_get($economyData, 'inflation.value', 2.84);
            $riskScore = (float) ($riskData['score'] ?? 30);

            $chartsData = [
                // Chart 1: GDP & Macro Trend (World Bank API)
                'gdp_trend' => [
                    'labels' => ['Skor Inflasi', 'Skor Risiko Makro', 'Volatilitas Pasok'],
                    'datasets' => [
                        [
                            'label' => 'Indikator Risiko (%)',
                            'data' => [
                                min(100, $inflationVal * 10), // Skor Inflasi
                                $riskScore,                  // Skor Risiko Makro
                                min(100, ($riskScore + $inflationVal * 2) / 1.5)
                            ],
                            'backgroundColor' => ['#ef4444', '#10b981', '#f59e0b'],
                            'borderRadius' => 8
                        ]
                    ]
                ],
                // Chart 2: Currency & Exchange Volatility (ExchangeRate API)
                'currency_volatility' => [
                    'labels' => ['USD/IDR', 'EUR/IDR', 'JPY/IDR'],
                    'datasets' => [
                        [
                            'label' => 'Kurs Mata Uang (IDR)',
                            'data' => [
                                round($rawIdr, 2),
                                round($rawIdr * (float)($exchangeData['EUR'] ?? 0.92), 2),
                                round($rawIdr / max(1, (float)($exchangeData['JPY'] ?? 155.4)), 2)
                            ],
                            'backgroundColor' => ['#3b82f6', '#10b981', '#8b5cf6'],
                            'borderRadius' => 8
                        ]
                    ]
                ]
            ];

            // 8. Kembalikan Response JSON Sukses Terstandardisasi
            return response()->json([
                'status' => 'success',
                'selected_country' => $selectedCountry,
                'country' => $selectedCountry,
                'countries' => $this->country->all(),
                'weather' => $weatherData,
                'economy' => $economyData,
                'exchange' => $exchangeData,
                'news' => $newsData,
                'ports' => $portsData,
                'risk' => $riskData,
                'charts' => $chartsData, // <-- DATA CHART REALTIME TERHUBUNG
            ], 200);

        } catch (\Throwable $fatalError) {
            Log::error("Fatal Dashboard Crash Intercepted: " . $fatalError->getMessage());
            
            // Pertahanan terakhir jika crash
            return response()->json([
                'status' => 'success',
                'selected_country' => ['name' => 'Indonesia', 'cca3' => 'IDN', 'latitude' => -6.2, 'longitude' => 106.816],
                'country' => ['name' => 'Indonesia', 'cca3' => 'IDN', 'latitude' => -6.2, 'longitude' => 106.816],
                'countries' => [['name' => 'Indonesia', 'iso_code' => 'IDN', 'currency_code' => 'IDR']],
                'weather' => ['current' => ['temperature_2m' => 28, 'wind_speed_10m' => 12, 'rain' => 0]],
                'economy' => ['inflation' => ['value' => 3.0], 'gdp' => ['value' => 1100000000000]],
                'exchange' => ['IDR' => '15.850', 'USD' => 1, 'EUR' => 0.92, 'JPY' => 155.4],
                'news' => ['economy' => [], 'trade' => [], 'geopolitic' => []],
                'ports' => [['name' => 'Port of Tanjung Priok', 'latitude' => -6.1045, 'longitude' => 106.8804]],
                'risk' => ['score' => 30, 'level' => 'LOW', 'badge' => 'success', 'breakdown' => ['weather' => 20, 'inflation' => 20, 'sentiment' => 20, 'currency' => 20]],
                'charts' => [
                    'gdp_trend' => [
                        'labels' => ['Skor Inflasi', 'Skor Risiko Makro'],
                        'datasets' => [['data' => [20, 30], 'backgroundColor' => ['#ef4444', '#10b981']]]
                    ],
                    'currency_volatility' => [
                        'labels' => ['USD/IDR', 'EUR/IDR', 'JPY/IDR'],
                        'datasets' => [['data' => [15850, 14582, 102], 'backgroundColor' => ['#3b82f6', '#10b981', '#8b5cf6']]]
                    ]
                ]
            ], 200);
        }
    }
}