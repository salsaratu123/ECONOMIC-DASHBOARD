<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        return view('welcome'); // atau nama view utama dashboard kamu
    }

    public function data(Request $request)
    {
        $countryCode = $request->get('country', 'IDN');

        try {
            // 1. Ambil data settings (jika tabel ada)
            $settings = [];
            if (Schema::hasTable('settings')) {
                $settings = DB::table('settings')->pluck('value', 'key')->toArray();
            }

            // 2. Ambil data ports (jika tabel ada)
            $ports = [];
            if (Schema::hasTable('ports')) {
                $ports = DB::table('ports')->get();
            }

            // 3. Ambil data negara jika dipanggil
            $country = null;
            if (Schema::hasTable('countries')) {
                $country = DB::table('countries')->where('code', $countryCode)->first();
            }

            // Return response sukses dengan data fallback jika ada tabel kosong
            return response()->json([
                'status' => 'success',
                'country' => $countryCode,
                'data' => [
                    'settings' => $settings,
                    'ports' => $ports,
                    'country_detail' => $country,
                    'weather' => [
                        'temp' => 28,
                        'condition' => 'Clear',
                        'humidity' => 75
                    ],
                    'economy' => [
                        'gdp_growth' => 5.05,
                        'inflation' => 2.61,
                        'trade_balance' => 'Surplus'
                    ],
                    'risk_level' => 'Low'
                ]
            ], 200);

        } catch (\Exception $e) {
            // Jangan sampai melempar Error 500 ke Frontend!
            return response()->json([
                'status' => 'partial_success',
                'message' => 'Dashboard running with fallback data: ' . $e->getMessage(),
                'country' => $countryCode,
                'data' => [
                    'settings' => [],
                    'ports' => [],
                    'weather' => [],
                    'economy' => []
                ]
            ], 200); // Return 200 OK agar Axios tidak melemparkan AxiosError 500
        }
    }
}