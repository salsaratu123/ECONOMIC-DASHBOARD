<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ExchangeService
{
    public function getExchange()
    {
        return Cache::remember('exchange:latest-usd', now()->addMinutes(30), function () {
        try {
            $response = Http::timeout(15)->get(
                'https://open.er-api.com/v6/latest/USD'
            );
        } catch (\Throwable) {
            return $this->fallback();
        }

        if (!$response->successful()) {
            return $this->fallback();
        }

        $json = $response->json();

        return [
            'USD' => 1,
            'IDR' => $json['rates']['IDR'] ?? 0,
            'EUR' => $json['rates']['EUR'] ?? 0,
            'JPY' => $json['rates']['JPY'] ?? 0,
        ];
        });
    }

    private function fallback(): array
    {
        return [
            'USD' => 1,
            'IDR' => 0,
            'EUR' => 0,
            'JPY' => 0,
        ];
    }
}
