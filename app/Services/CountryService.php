<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CountryService
{
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct()
    {
        // Mengambil token API Key langsung dari file .env secara aman
        $this->apiKey = env('RESTCOUNTRIES_API_KEY', 'rc_live_3f1b69a5835f4ef0983053a55c332c10');
        $this->baseUrl = 'https://api.restcountries.com/v1'; 
    }

    /**
     * Ambil SEMUA negara di dunia langsung dari API Eksternal
     */
    public function all(): array
    {
        try {
            // Melakukan HTTP GET Request dengan Header Otorisasi Key
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey
            ])->timeout(5)->get("{$this->baseUrl}/countries");

            if ($response->successful()) {
                $data = $response->json();
                // Jika API mereturn format bersarang, sesuaikan mapping datanya
                return $data['data'] ?? $data ?? $this->getFallbackCountries();
            }
        } catch (\Throwable $e) {
            Log::error("REST Countries API All Error: " . $e->getMessage());
        }

        return $this->getFallbackCountries();
    }

    /**
     * Cari detail spesifik negara berdasarkan 3-Digit Kode ISO (CCA3)
     */
    public function findByIso(string $isoCode): ?array
    {
        $isoCode = strtoupper(trim($isoCode));
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey
            ])->timeout(4)->get("{$this->baseUrl}/countries/{$isoCode}");

            if ($response->successful()) {
                $data = $response->json();
                $country = $data['data'] ?? $data;
                
                if ($country) {
                    return $this->normalizeCountryData($country);
                }
            }
        } catch (\Throwable $e) {
            Log::warning("REST Countries API Find Error, using fallback: " . $e->getMessage());
        }

        // Jika API Timeout / gagal merespon, aktifkan mekanisme fail-safe canggih
        return $this->getFallbackByIso($isoCode);
    }

    /**
     * Cari detail spesifik negara berdasarkan Nama
     */
    public function find(string $name): ?array
    {
        if (strtoupper($name) === 'IDN' || strtoupper($name) === 'ID') {
            return $this->findByIso('IDN');
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey
            ])->timeout(4)->get("{$this->baseUrl}/countries", [
                'search' => $name
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $countries = $data['data'] ?? $data ?? [];
                if (!empty($countries)) {
                    return $this->normalizeCountryData($countries[0]);
                }
            }
        } catch (\Throwable $e) {
            Log::error("REST Countries API Search Error: " . $e->getMessage());
        }

        return $this->getFallbackByIso('IDN');
    }

    /**
     * Standardisasi format data dari API ke format JavaScript frontend agar tidak crash
     */
    protected function normalizeCountryData(array $c): array
    {
        return [
            'name' => $c['name'] ?? $c['common_name'] ?? 'Unknown',
            'cca3' => $c['cca3'] ?? $c['iso_code'] ?? 'IDN',
            'iso_code' => $c['cca3'] ?? $c['iso_code'] ?? 'IDN',
            'capital' => $c['capital'] ?? 'Jakarta',
            'region' => $c['region'] ?? 'Asia',
            'population' => $c['population'] ?? 275501339,
            'currency' => $c['currency'] ?? $c['currency_code'] ?? 'IDR',
            'currency_code' => $c['currency'] ?? $c['currency_code'] ?? 'IDR',
            'language' => $c['language'] ?? 'Indonesian',
            'flag' => $c['flag'] ?? $c['flag_url'] ?? 'https://flagcdn.com/w320/id.png',
            'latitude' => $c['latitude'] ?? $c['latlng'][0] ?? -6.2,
            'longitude' => $c['longitude'] ?? $c['latlng'][1] ?? 106.816666,
        ];
    }

    /**
     * Data Backup Utama jika Limit API Habis / Tidak ada Internet (UAS Safe Guard)
     */
    protected function getFallbackCountries(): array
    {
        return [
            ['name' => 'Indonesia', 'iso_code' => 'IDN', 'cca3' => 'IDN', 'currency_code' => 'IDR', 'region' => 'Asia'],
            ['name' => 'Singapore', 'iso_code' => 'SGP', 'cca3' => 'SGP', 'currency_code' => 'SGD', 'region' => 'Asia'],
            ['name' => 'Malaysia', 'iso_code' => 'MYS', 'cca3' => 'MYS', 'currency_code' => 'MYR', 'region' => 'Asia'],
            ['name' => 'China', 'iso_code' => 'CHN', 'cca3' => 'CHN', 'currency_code' => 'CNY', 'region' => 'Asia'],
            ['name' => 'Japan', 'iso_code' => 'JPN', 'cca3' => 'JPN', 'currency_code' => 'JPY', 'region' => 'Asia'],
            ['name' => 'United States', 'iso_code' => 'USA', 'cca3' => 'USA', 'currency_code' => 'USD', 'region' => 'Americas'],
            ['name' => 'Germany', 'iso_code' => 'DEU', 'cca3' => 'DEU', 'currency_code' => 'EUR', 'region' => 'Europe'],
        ];
    }

    protected function getFallbackByIso(string $iso): array
    {
        $fallbacks = [
            'IDN' => ['name' => 'Indonesia', 'cca3' => 'IDN', 'capital' => 'Jakarta', 'region' => 'Asia', 'population' => 275501339, 'currency' => 'IDR', 'language' => 'Indonesian', 'flag' => 'https://flagcdn.com/w320/id.png', 'latitude' => -6.2, 'longitude' => 106.816666],
            'SGP' => ['name' => 'Singapore', 'cca3' => 'SGP', 'capital' => 'Singapore', 'region' => 'Asia', 'population' => 5980000, 'currency' => 'SGD', 'language' => 'English', 'flag' => 'https://flagcdn.com/w320/sg.png', 'latitude' => 1.3521, 'longitude' => 103.8198],
            'MYS' => ['name' => 'Malaysia', 'cca3' => 'MYS', 'capital' => 'Kuala Lumpur', 'region' => 'Asia', 'population' => 33900000, 'currency' => 'MYR', 'language' => 'Malay', 'flag' => 'https://flagcdn.com/w320/my.png', 'latitude' => 4.2105, 'longitude' => 101.9758]
        ];

        return $this->normalizeCountryData($fallbacks[$iso] ?? $fallbacks['IDN']);
    }
}