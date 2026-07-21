<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CountryService;
use Exception;

class CountryController extends Controller
{
    protected CountryService $countryService;

    public function __construct(CountryService $countryService)
    {
        $this->countryService = $countryService;
    }

    /**
     * Menampilkan Halaman Utama Data Negara
     */
    public function index()
    {
        return view('country.index');
    }

    /**
     * Menampilkan Halaman Perbandingan Negara (Fitur No 8 dari PDF)
     */
    public function comparison()
    {
        return view('country.comparison');
    }

    /**
     * Menampilkan Halaman Watchlist / Favorit (Fitur No 9 dari PDF)
     */
    public function watchlist()
    {
        return view('country.watchlist');
    }

    /* =========================================================================
     * API ENDPOINTS (Dipanggil oleh JavaScript / Frontend untuk memuat data live)
     * ========================================================================= */

    /**
     * Mengambil daftar seluruh negara (JSON) untuk Dropdown / Select
     */
    public function getCountries()
    {
        try {
            $countries = $this->countryService->getAllCountries();

            return response()->json([
                'status' => 'success',
                'data'   => $countries
            ]);
        } catch (Exception $e) {
            // Return fallback data agar Javascript frontend tidak crash
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
                'data'    => [
                    ['name' => 'Indonesia', 'iso2' => 'ID', 'iso3' => 'IDN', 'capital' => 'Jakarta'],
                    ['name' => 'United States', 'iso2' => 'US', 'iso3' => 'USA', 'capital' => 'Washington, D.C.'],
                    ['name' => 'China', 'iso2' => 'CN', 'iso3' => 'CHN', 'capital' => 'Beijing'],
                    ['name' => 'Singapore', 'iso2' => 'SG', 'iso3' => 'SGP', 'capital' => 'Singapore'],
                ]
            ]);
        }
    }

    /**
     * Mengambil detail indikator negara tertentu (Cuaca, Ekonomi, Populasi, dll)
     */
    public function getCountryDetail($code)
    {
        try {
            $data = $this->countryService->getCountryByCode($code);

            return response()->json([
                'status' => 'success',
                'data'   => $data
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal mengambil detail data negara: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mengambil data komparasi antara dua atau lebih negara
     */
    public function compare(Request $request)
    {
        try {
            $countries = $request->input('countries', []); // Array ISO code misal: ['ID', 'US']
            $comparisonData = $this->countryService->getComparisonData($countries);

            return response()->json([
                'status' => 'success',
                'data'   => $comparisonData
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}