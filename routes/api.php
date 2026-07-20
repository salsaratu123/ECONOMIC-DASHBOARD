<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CountryApiController;
use App\Http\Controllers\Api\RiskApiController;
use App\Http\Controllers\Api\PortApiController;
use App\Http\Controllers\Api\NewsApiController;
use App\Http\Controllers\Api\CurrencyApiController;
use App\Http\Controllers\ShipmentController;

/*
|--------------------------------------------------------------------------
| API Routes - Global Supply Chain Risk Intelligence Platform
|--------------------------------------------------------------------------
|
| Semua route di file ini otomatis mendapatkan prefix "/api" oleh framework.
| Route di bawah di-group di bawah prefix "v1" untuk standardisasi REST API.
|
*/

Route::prefix('v1')->group(function () {
    
    // ==========================================
    // 5 REST API UTAMA WAJIB (PROJECT FINAL.pdf)
    // ==========================================
    
    // 1. GET /api/v1/countries (Mendapatkan semua data negara)
    Route::get('/countries', [CountryApiController::class, 'getCountries']);
    
    // 2. GET /api/v1/risk (Mendapatkan skor & prediksi risiko berdasarkan negara)
    Route::get('/risk', [RiskApiController::class, 'getRiskAnalytics']);
    
    // 3. GET /api/v1/ports (Mendapatkan data koordinat & pelabuhan logistik maritim)
    Route::get('/ports', [PortApiController::class, 'getPorts']);
    
    // 4. GET /api/v1/news (Mendapatkan berita ekonomi, logistik, geopolitik & sentimen)
    Route::get('/news', [NewsApiController::class, 'getNews']);
    
    // 5. GET /api/v1/currency (Mendapatkan data live kurs forex)
    Route::get('/currency', [CurrencyApiController::class, 'getCurrency']);


    // ==========================================
    // EXTENDED FEATURES & CRUD ENDPOINTS
    // ==========================================
    
    // Detail negara spesifik berdasarkan kode ISO (Contoh: /api/v1/countries/ID)
    Route::get('/countries/{iso_code}', [CountryApiController::class, 'show']);
    
    // Fitur Tambahan: Toggle Simpan/Hapus Negara Favorit dari Watchlist
    Route::post('/watchlist/toggle', [CountryApiController::class, 'toggleWatchlist']);
    
    // Core Shipping Management (RESTful API Resource untuk data Shipment)
    Route::apiResource('shipments', ShipmentController::class);
});