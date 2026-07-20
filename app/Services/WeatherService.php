<?php

namespace App\Services;

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
        } catch (\Throwable) {
            return $this->fallback();
        }

        if ($response->successful()) {
            return $response->json();
        }

        return $this->fallback();
        });
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
