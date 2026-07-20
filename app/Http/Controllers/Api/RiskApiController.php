<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\RiskService;
use App\Services\CountryService;
use Illuminate\Http\Request;

class RiskApiController extends Controller
{
    protected RiskService $riskService;
    protected CountryService $countryService;

    /**
     * Dependency Injection untuk memasukkan Service Layer yang dibutuhkan
     */
    public function __construct(RiskService $riskService, CountryService $countryService)
    {
        $this->riskService = $riskService;
        $this->countryService = $countryService;
    }

    /**
     * GET /api/v1/risk
     * Mengambil data skor analitik dan prediksi risiko rantai pasok global per negara
     */
    public function getRiskAnalytics(Request $request)
    {
        // Ambil kode negara dari query parameter, fallback default ke IDN (Indonesia) jika kosong
        $isoCode = strtoupper($request->query('iso', 'IDN'));

        try {
            // Validasi apakah negara dengan kode ISO tersebut valid atau terdaftar
            $countryExists = $this->countryService->findByIso($isoCode);
            
            if (!$countryExists) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Data analisis untuk negara dengan kode ISO '{$isoCode}' tidak ditemukan."
                ], 404);
            }

            // Eksekusi kalkulasi algoritma Weighted Risk Model (Cuaca, Inflasi, Forex, Sentimen Berita)
            $analytics = $this->riskService->calculateForCountry($isoCode);
            
            return response()->json([
                'status' => 'success',
                'timestamp' => now()->toIso8601String(),
                'data' => $analytics
            ], 200);

        } catch (\Exception $e) {
            // Log error jika terjadi kegagalan sistem saat runtime
            \Log::error("RiskApiController Error: " . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memproses kalkulasi data risiko: ' . $e->getMessage()
            ], 500);
        }
    }
}