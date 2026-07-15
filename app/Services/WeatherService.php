<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WeatherService
{
    public function getWeather()
    {
        $latitude = -6.200000;   // Jakarta (sementara)
        $longitude = 106.816666;

        $url = "https://api.open-meteo.com/v1/forecast";

        $response = Http::get($url, [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'current' => 'temperature_2m,wind_speed_10m,rain',
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }
}