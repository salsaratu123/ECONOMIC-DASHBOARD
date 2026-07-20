<?php

namespace App\Services;

use App\Models\Country;
use Illuminate\Support\Facades\DB;

class RiskService
{
    protected WeatherService $weather;
    protected ExchangeService $exchange;
    protected WorldBankService $worldBank;
    protected NewsService $news;

    public function __construct(
        WeatherService $weather,
        ExchangeService $exchange,
        WorldBankService $worldBank,
        NewsService $news
    ) {
        $this->weather = $weather;
        $this->exchange = $exchange;
        $this->worldBank = $worldBank;
        $this->news = $news;
    }

    /**
     * Menghitung total skor risiko secara dinamis berbasis data negara
     */
    public function calculateForCountry(string $isoCode): array
    {
        $country = Country::where('iso_code', strtoupper($isoCode))->first();
        
        // 1. Weather Risk (Bobot 30%) - default koordinat Indonesia jika record DB kosong
        $lat = $country ? -6.2 : -6.2; 
        $lng = $country ? 106.8166 : 106.8166;
        $weatherData = $this->weather->getWeather($lat, $lng);
        $currentWeather = $weatherData['current'] ?? [];
        $weatherScore = min(100, (($currentWeather['wind_speed_10m'] ?? 0) * 2) + (($currentWeather['rain'] ?? 0) * 8));

        // 2. Inflation Risk (Bobot 20%)
        $economyData = $this->worldBank->getEconomy($isoCode);
        $inflationValue = (float) data_get($economyData, 'inflation.value', 3.0);
        $inflationScore = $inflationValue > 10 ? 85 : ($inflationValue > 5 ? 50 : 20);

        // 3. News Sentiment Risk (Bobot 40% - Sesuai Mandat Halaman 8 Dokumen PDF)
        $articles = $this->news->latest($country ? $country->name : 'economy', 3);
        $sentimentMetrics = $this->analyzeCollectionSentiment($articles);
        $sentimentScore = $sentimentMetrics['risk_score'];

        // 4. Currency Risk (Bobot 10%)
        $exchangeData = $this->exchange->getExchange();
        $currencyCode = $country ? $country->currency_code : 'IDR';
        $rate = (float) ($exchangeData[$currencyCode] ?? 0);
        $exchangeScore = $rate > 1000 ? 40 : 15;

        // Perhitungan Bobot Akhir (Weighted Risk Model)
        $totalScore = round(
            ($weatherScore * 0.30) +
            ($inflationScore * 0.20) +
            ($sentimentScore * 0.40) +
            ($exchangeScore * 0.10)
        );

        $level = $totalScore >= 65 ? 'HIGH' : ($totalScore >= 35 ? 'MEDIUM' : 'LOW');

        // Simpan historical data skor ke database risk_scores
        if ($country) {
            DB::table('risk_scores')->updateOrInsert(
                ['country_id' => $country->id],
                [
                    'weather_risk' => $weatherScore,
                    'inflation_risk' => $inflationScore,
                    'currency_risk' => $exchangeScore,
                    'sentiment_risk' => $sentimentScore,
                    'total_risk_score' => $totalScore,
                    'risk_level' => $level,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }

        return [
            'country' => $country ? $country->name : 'Unknown',
            'score' => $totalScore,
            'level' => $level,
            'badge' => $level === 'HIGH' ? 'danger' : ($level === 'MEDIUM' ? 'warning' : 'success'),
            'breakdown' => [
                'weather' => $weatherScore,
                'inflation' => $inflationScore,
                'sentiment' => $sentimentScore,
                'currency' => $exchangeScore
            ],
            'sentiment_analysis' => $sentimentMetrics
        ];
    }

    /**
     * Lexicon-Based Sentiment Analysis Engine (PHP murni)
     */
    private function analyzeCollectionSentiment(array $articles): array
    {
        // Fallback Kata Positif & Negatif jika table lexicon belum di-seed
        $posWords = ['growth', 'increase', 'profit', 'stable', 'improve', 'strengthen', 'recovery'];
        $negWords = ['war', 'crisis', 'inflation', 'delay', 'disaster', 'conflict', 'decrease', 'drop'];

        try {
            $dbPos = DB::table('lexicon_dictionary')->where('type', 'positive')->pluck('word')->toArray();
            $dbNeg = DB::table('lexicon_dictionary')->where('type', 'negative')->pluck('word')->toArray();
            if (!empty($dbPos)) $posWords = $dbPos;
            if (!empty($dbNeg)) $negWords = $dbNeg;
        } catch (\Throwable $e) {}

        $posCount = 0;
        $negCount = 0;

        foreach ($articles as $article) {
            $text = strtolower(($article['title'] ?? '') . ' ' . ($article['description'] ?? ''));
            $words = str_word_count($text, 1);

            foreach ($words as $word) {
                if (in_array($word, $posWords)) $posCount++;
                if (in_array($word, $negWords)) $negCount++;
            }
        }

        $total = $posCount + $negCount;
        if ($total === 0) {
            return ['positive' => 0, 'neutral' => 100, 'negative' => 0, 'risk_score' => 30, 'label' => 'Neutral'];
        }

        $posPercent = round(($posCount / $total) * 100);
        $negPercent = round(($negCount / $total) * 100);
        $neutralPercent = 100 - ($posPercent + $negPercent);

        $riskScore = $negPercent; 
        $label = $posCount > $negCount ? 'Positive' : ($negCount > $posCount ? 'Negative' : 'Neutral');

        return [
            'positive' => $posPercent,
            'neutral' => $neutralPercent,
            'negative' => $negPercent,
            'risk_score' => $riskScore === 0 ? 20 : $riskScore,
            'label' => $label
        ];
    }
}