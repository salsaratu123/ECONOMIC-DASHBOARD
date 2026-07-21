<?php

namespace App\Services;

use App\Models\ApiLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WeatherService
{
    public function getWeather(float $latitude = -6.2, float $longitude = 106.816666)
    {
        $cacheKey = sprintf('weather:%s:%s', round($latitude, 3), round($longitude, 3));

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($latitude, $longitude) {
            try {
                $response = Http::timeout(15)->get('https://api.open-meteo.com/v1/forecast', [
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'current' => 'temperature_2m,wind_speed_10m,rain',
                ]);

                if ($response->successful()) {
                    $this->logActivity('Weather Risk Fetching Success', 200);
                    return $response->json();
                }

                $this->logActivity('Open-Meteo API Failed Response', $response->status());

            } catch (\Throwable $e) {
                $this->logActivity('Open-Meteo Connection Timeout', 500);
            }

            return $this->fallback();
        });
    }

    private function logActivity(string $status, int $code = 200)
    {
        try {
            ApiLog::create([
                'target_service' => 'Open-Meteo Engine',
                'status_request' => $status,
                'response_code' => $code,
            ]);
        } catch (\Throwable $e) {}
    }

    private function fallback(): array
    {
        return [
            'current' => [
                'temperature_2m' => 0,
                'wind_speed_10m' => 0,
                'rain' => 0,
            ],
        ];
    }
}