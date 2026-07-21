<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\ApiLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ExchangeService
{
    public function getExchange()
    {
        return Cache::remember('exchange:latest-usd', now()->addMinutes(30), function () {
            $apiKey = Setting::get('exchangerate_api_key', config('services.exchangerate.key'));
            $url = $apiKey ? "https://v6.exchangerate-api.com/v6/{$apiKey}/latest/USD" : 'https://open.er-api.com/v6/latest/USD';

            try {
                $response = Http::timeout(10)->get($url);

                if ($response->successful()) {
                    $this->logActivity('Live FX Rate Query (USD/IDR) Success', 200);
                    $json = $response->json();
                    $idrRate = $json['rates']['IDR'] ?? 15850;

                    return [
                        'USD' => 1,
                        'IDR' => number_format($idrRate, 0, ',', '.'),
                        'EUR' => number_format($json['rates']['EUR'] ?? 0.92, 2),
                        'JPY' => number_format($json['rates']['JPY'] ?? 155.4, 2),
                    ];
                }

                $this->logActivity('ExchangeRate API Key Invalid', $response->status());

            } catch (\Throwable $e) {
                $this->logActivity('ExchangeRate Connection Timeout', 500);
            }

            return $this->fallback();
        });
    }

    private function logActivity(string $status, int $code = 200)
    {
        try {
            ApiLog::create([
                'target_service' => 'ExchangeRate API',
                'status_request' => $status,
                'response_code' => $code,
            ]);
        } catch (\Throwable $e) {}
    }

    private function fallback(): array
    {
        return [
            'USD' => 1,
            'IDR' => '15.850',
            'EUR' => '0.92',
            'JPY' => '155.40',
        ];
    }
}