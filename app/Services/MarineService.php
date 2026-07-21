<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;

class MarineService
{
    public function ports()
    {
        $apiKey = config('services.shipfinder.key') ?? env('SHIPFINDER_API_KEY');

        Log::info('MarineService Hit Start', ['apiKey' => $apiKey ? 'PRESENT' : 'MISSING']);

        if (!empty($apiKey)) {
            try {
                // Try Endpoint 1: Bounding Box
                $response = Http::withoutVerifying()->timeout(10)->get('https://open.shipfinder.com/v1/vessels', [
                    'api_key' => $apiKey,
                    'min_lat' => -10,
                    'max_lat' => 10,
                    'min_lon' => 95,
                    'max_lon' => 140,
                ]);

                // Try Endpoint 2 Fallback: Single Vessel List Endpoint
                if (!$response->successful() || empty($response->json())) {
                    $response = Http::withoutVerifying()->timeout(10)->get("https://open.shipfinder.com/v1/vessel?api_key={$apiKey}");
                }

                Log::info('ShipFinder API Response Log', [
                    'status' => $response->status(),
                    'body'   => $response->body()
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    
                    // Dynamic extractor untuk menangkap array vessel di mana pun posisinya
                    $vessels = $this->extractVessels($data);

                    if (!empty($vessels)) {
                        return $vessels;
                    }
                }
            } catch (Exception $e) {
                Log::error('ShipFinder API Exception: ' . $e->getMessage());
            }
        }

        // FALLBACK Terkuat: Jika API Kosong / Trial Limit, Ambil dari DB Local
        Log::warning('ShipFinder API empty, returning active local DB ports');

        return DB::table('ports')->get()->map(function ($port) {
            return [
                'name'      => $port->name,
                'mmsi'      => $port->port_code,
                'country'   => $port->country_iso,
                'latitude'  => (float) $port->latitude,
                'longitude' => (float) $port->longitude,
                'speed'     => (int) $port->congestion,
            ];
        })->toArray();
    }

    private function extractVessels($data)
    {
        if (!is_array($data)) return [];

        if (isset($data['vessels']) && is_array($data['vessels'])) return $data['vessels'];
        if (isset($data['data']) && is_array($data['data'])) return $data['data'];
        if (isset($data['list']) && is_array($data['list'])) return $data['list'];

        // Jika array asosiatif tanpa key pembungkus
        if (array_is_list($data) && count($data) > 0) return $data;

        return [];
    }
}