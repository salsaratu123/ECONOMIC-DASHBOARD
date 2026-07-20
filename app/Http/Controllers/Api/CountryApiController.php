<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CountryService;
use Illuminate\Support\Facades\Auth;

class CountryApiController extends Controller
{
    protected CountryService $countryService;

    public function __construct(CountryService $countryService)
    {
        $this->countryService = $countryService;
    }

    /**
     * GET /api/v1/countries
     * Mengambil semua data negara yang tersedia
     */
    public function getCountries()
    {
        try {
            $data = $this->countryService->all();
            return response()->json($data, 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data negara: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/v1/countries/{iso_code}
     * Mengambil detail satu negara berdasarkan Kode ISO (Contoh: ID, SG)
     */
    public function show($iso_code)
    {
        try {
            $data = $this->countryService->findByIso(strtoupper($iso_code));
            
            if (!$data) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Negara dengan kode ISO tersebut tidak ditemukan.'
                ], 404);
            }

            return response()->json($data, 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil detail negara: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * POST /api/v1/watchlist/toggle
     * Menambahkan atau menghapus negara dari watchlist user saat ini
     */
    public function toggleWatchlist(Request $request)
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id',
        ]);

        try {
            // Menggunakan user ID sementara/mocking jika fitur auth opsional saat UAS, atau Auth::id() jika sudah login
            $userId = Auth::id() ?? 1; 
            
            $result = $this->countryService->toggleWatchlist($userId, $request->country_id);

            return response()->json([
                'status' => 'success',
                'message' => $result['attached'] ? 'Berhasil ditambahkan ke watchlist' : 'Berhasil dihapus dari watchlist',
                'attached' => $result['attached']
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengubah status watchlist: ' . $e->getMessage()
            ], 500);
        }
    }
}