<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    public function index()
    {
        try {
            return response()->json(Shipment::latest()->get());
        } catch (QueryException) {
            return response()->json($this->fallbackShipments());
        }
    }

    public function store(Request $request)
    {
        $shipment = Shipment::create($this->validated($request));

        return response()->json($shipment, 201);
    }

    public function show(Shipment $shipment)
    {
        return response()->json($shipment);
    }

    public function update(Request $request, Shipment $shipment)
    {
        $shipment->update($this->validated($request));

        return response()->json($shipment);
    }

    public function destroy(Shipment $shipment)
    {
        $shipment->delete();

        return response()->json(['deleted' => true]);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'container_number' => ['required', 'string', 'max:50'],
            'origin_country' => ['required', 'string', 'max:100'],
            'destination_country' => ['required', 'string', 'max:100'],
            'origin_port' => ['required', 'string', 'max:150'],
            'destination_port' => ['required', 'string', 'max:150'],
            'ship_name' => ['required', 'string', 'max:150'],
            'eta' => ['required', 'date'],
            'status' => ['required', 'string', 'max:50'],
            'risk_level' => ['required', 'in:LOW,MEDIUM,HIGH'],
        ]);
    }

    private function fallbackShipments(): array
    {
        return [[
            'container_number' => 'CONT001',
            'origin_country' => 'China',
            'destination_country' => 'Indonesia',
            'origin_port' => 'Shanghai',
            'destination_port' => 'Tanjung Priok',
            'ship_name' => 'MV Nusantara Express',
            'eta' => '2026-07-10',
            'status' => 'On Voyage',
            'risk_level' => 'MEDIUM',
        ]];
    }
}
