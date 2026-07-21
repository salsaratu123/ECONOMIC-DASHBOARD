<?php

namespace App\Http\Controllers;

use App\Services\MarineService;

class MarineController extends Controller
{
    public function __construct(private MarineService $marine)
    {
    }

    public function index()
    {
        return view('marine.index');
    }

    public function current()
    {
        $rawPorts = $this->marine->ports();

        // Safe Mapping untuk menangkap semua kemungkinan key dari berbagai API Maritim
        $formattedPorts = collect($rawPorts)->map(function ($item) {
            return [
                'name'        => $item['ship_name'] ?? $item['vessel_name'] ?? $item['name'] ?? $item['SHIPNAME'] ?? 'Vessel Unit',
                'port_code'   => $item['mmsi'] ?? $item['imo'] ?? $item['port_code'] ?? 'MMSI-' . rand(1000, 9999),
                'country_iso' => $item['flag'] ?? $item['country'] ?? $item['country_iso'] ?? 'GLOBAL',
                'latitude'    => (float) ($item['latitude'] ?? $item['lat'] ?? $item['LAT'] ?? 0),
                'longitude'   => (float) ($item['longitude'] ?? $item['lng'] ?? $item['lon'] ?? $item['LON'] ?? 0),
                'congestion'  => (int) ($item['speed'] ?? $item['sog'] ?? $item['congestion'] ?? 0),
            ];
        })->filter(function ($item) {
            return $item['latitude'] != 0 && $item['longitude'] != 0;
        })->values();

        return response()->json($formattedPorts);
    }
}