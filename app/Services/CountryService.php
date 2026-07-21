<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\ApiLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CountryService
{
    // Base URL sesuai Dokumentasi REST Countries Pro v5
    protected string $baseUrl = 'https://api.restcountries.com/countries/v5'; 

    /**
     * Ambil API Key Dinamis dari Web Admin (Tabel Settings)
     */
    protected function getApiKey(): string
    {
        return Setting::get('restcountries_api_key', env('RESTCOUNTRIES_API_KEY', 'rc_live_3f1b69a5835f4ef0983053a55c332c10'));
    }

    /**
     * Helper Pencatatan Activity Logs
     */
    protected function logActivity(string $status, int $code = 200)
    {
        try {
            ApiLog::create([
                'target_service' => 'REST Countries Pro v5',
                'status_request' => $status,
                'response_code' => $code,
            ]);
        } catch (\Throwable $e) {}
    }

    /**
     * Ambil SEMUA negara dari API v5
     */
    public function all(): array
    {
        try {
            $apiKey = $this->getApiKey();
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey
            ])->timeout(5)->get($this->baseUrl);

            if ($response->successful()) {
                $this->logActivity('ISO Country Profile Sync Success', 200);
                $data = $response->json();
                return $data['data'] ?? $data ?? $this->getFallbackCountries();
            }

            $this->logActivity('API Key Invalid / Pro Query Failed (' . $response->reason() . ')', $response->status());
        } catch (\Throwable $e) {
            $this->logActivity('Connection Timeout / Service Down', 500);
            Log::error("REST Countries API All Error: " . $e->getMessage());
        }

        return $this->getFallbackCountries();
    }

    /**
     * Cari detail spesifik berdasarkan Kode ISO (CCA3) via v5 API
     */
    public function findByIso(string $isoCode): ?array
    {
        $isoCode = strtoupper(trim($isoCode));
        $apiKey = $this->getApiKey();
        
        try {
            // Menggunakan query search v5 sesuai contoh dokumentasi: ?q=CODE
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey
            ])->timeout(5)->get($this->baseUrl, [
                'q' => $isoCode
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $countries = $data['data'] ?? $data ?? [];
                
                if (!empty($countries)) {
                    $this->logActivity("ISO Query Success ({$isoCode})", 200);
                    $target = is_array($countries) && isset($countries[0]) ? $countries[0] : $countries;
                    return $this->normalizeCountryData($target);
                }
            }

            $this->logActivity("ISO Query Failed ({$isoCode})", $response->status() > 0 ? $response->status() : 404);
        } catch (\Throwable $e) {
            $this->logActivity("ISO Query Timeout ({$isoCode})", 500);
            Log::warning("REST Countries API Find Error, using fallback: " . $e->getMessage());
        }

        return $this->getFallbackByIso($isoCode);
    }

    /**
     * Cari detail spesifik berdasarkan Nama
     */
    public function find(string $name): ?array
    {
        if (strtoupper($name) === 'IDN' || strtoupper($name) === 'ID') {
            return $this->findByIso('IDN');
        }

        $apiKey = $this->getApiKey();

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey
            ])->timeout(5)->get($this->baseUrl, [
                'q' => $name
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $countries = $data['data'] ?? $data ?? [];
                if (!empty($countries)) {
                    $this->logActivity("Search Country Success ({$name})", 200);
                    $target = is_array($countries) && isset($countries[0]) ? $countries[0] : $countries;
                    return $this->normalizeCountryData($target);
                }
            }

            $this->logActivity("Search Country Failed ({$name})", $response->status() > 0 ? $response->status() : 404);
        } catch (\Throwable $e) {
            $this->logActivity("Search Country Timeout ({$name})", 500);
            Log::error("REST Countries API Search Error: " . $e->getMessage());
        }

        return $this->getFallbackByIso('IDN');
    }

    /**
     * Standardisasi format data dari API ke format JavaScript frontend
     */
    protected function normalizeCountryData(array $c): array
    {
        return [
            'name' => $c['name'] ?? $c['common_name'] ?? $c['official_name'] ?? 'Indonesia',
            'cca3' => $c['cca3'] ?? $c['iso_code'] ?? $c['iso3'] ?? 'IDN',
            'iso_code' => $c['cca3'] ?? $c['iso_code'] ?? $c['iso3'] ?? 'IDN',
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
     * Fallback Data jika internet/API terputus
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