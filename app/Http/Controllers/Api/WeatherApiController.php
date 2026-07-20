<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WeatherService;
use Illuminate\Http\Request;

class WeatherApiController extends Controller
{
    protected WeatherService $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    /**
     * GET /api/v1/weather
     * Mengambil data cuaca real-time berdasarkan koordinat latitude & longitude
     */
    public function current(Request $request)
    {
        // Validasi input koordinat dengan fallback default ke Jakarta jika kosong
        $lat = (float) $request->query('lat', -6.2000);
        $lon = (float) $request->query('lon', $request->query('lng', 106.8167));

        try {
            $data = $this->weatherService->getWeather($lat, $lon);
            return response()->json($data, 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data cuaca dari Open-Meteo: ' . $e->getMessage()
            ], 500);
        }
    }
}