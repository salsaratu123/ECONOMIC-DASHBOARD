<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\EconomyController;
use App\Http\Controllers\ExchangeController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\MarineController;
use App\Http\Controllers\UserPreferenceController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes - Global Supply Chain Risk Intelligence Platform
|--------------------------------------------------------------------------
*/

// Halaman Utama Public Dashboard & AJAX Data API Engine
Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
Route::get('/api/dashboard', [DashboardController::class, 'data']);

// Route Simpan Pengaturan Dashboard Khusus User (Login Required)
Route::middleware('auth')->group(function () {
    Route::post('/user/preferences', [UserPreferenceController::class, 'update'])->name('user.preferences.update');
});

// Modul View Blade Multi-API Public
Route::get('/weather', [WeatherController::class, 'index'])->name('weather.index');
Route::get('/economy', [EconomyController::class, 'index'])->name('economy.index');
Route::get('/exchange', [ExchangeController::class, 'index'])->name('exchange.index');
Route::get('/countries', [CountryController::class, 'index'])->name('countries.index');
Route::get('/news', [NewsController::class, 'index'])->name('news.index');

// =========================================================================
// API ENDPOINTS (Penyokong Utama Frontend JS Dashboard)
// =========================================================================
Route::prefix('api')->group(function () {
    // API Data Negara
    Route::get('/countries', [CountryController::class, 'getCountries']);
    Route::get('/countries/{code}', [CountryController::class, 'getCountryDetail']);
    Route::post('/countries/compare', [CountryController::class, 'compare']);

    // API Cuaca & Indikator Ekonomi
    Route::get('/weather/data', [WeatherController::class, 'data']);
    Route::get('/economy/data', [EconomyController::class, 'data']);
    Route::get('/exchange/data', [ExchangeController::class, 'data']);

    // API Ports (Penentu Utama Status Dashboard)
    Route::get('/ports', function () {
        try {
            $ports = DB::table('ports')->get();
            return response()->json([
                'status' => 'success',
                'data' => $ports
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    });

    // API Settings (Konfigurasi Dynamic Dashboard)
    Route::get('/settings', function () {
        try {
            $settings = DB::table('settings')->pluck('value', 'key');
            return response()->json([
                'status' => 'success',
                'data' => $settings
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    });
});

// Direct Route Fallbacks & Alternative Endpoints
Route::get('/countries/list', [CountryController::class, 'getCountries']);
Route::get('/ports', function () { return redirect('/api/ports'); });
Route::get('/settings', function () { return redirect('/api/settings'); });

// Modul Marine Traffic View & AJAX Endpoints
Route::get('/marine', [MarineController::class, 'index'])->name('marine.index');
Route::get('/api/marine', [MarineController::class, 'current'])->name('marine.current');
Route::get('/marine/current', [MarineController::class, 'current']);

// Target Fitur: Country Comparison Engine & Watchlist View
Route::get('/comparison', [CountryController::class, 'comparison'])->name('countries.comparison');
Route::get('/watchlist', [CountryController::class, 'watchlist'])->name('countries.watchlist');

/*
|--------------------------------------------------------------------------
| Admin Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [AdminDashboardController::class, 'showLoginForm'])->name('login');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminDashboardController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminDashboardController::class, 'login'])->name('login.submit');
    Route::get('/register', [AdminDashboardController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AdminDashboardController::class, 'register'])->name('register.submit');
    Route::post('/logout', [AdminDashboardController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Admin Dashboard & Management Group
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', IsAdmin::class])->group(function () {
    // Dashboard Utama Admin
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Management Grafik Dinamis Admin
    Route::post('/charts/update', [AdminDashboardController::class, 'updateChartsConfig'])->name('charts.update');
    Route::post('/charts/reset', [AdminDashboardController::class, 'resetChartsConfig'])->name('charts.reset');
    Route::post('/charts/add', [AdminDashboardController::class, 'addChart'])->name('charts.add');

    // Management Pelabuhan & Rute Maritim
    Route::get('/ports', [AdminDashboardController::class, 'ports'])->name('ports');
    Route::post('/ports', [AdminDashboardController::class, 'storePort'])->name('ports.store');
    Route::post('/routes', [AdminDashboardController::class, 'storeRoute'])->name('routes.store');

    // Management Pengontrol Tampilan & API Keys Admin
    Route::get('/settings', [AdminDashboardController::class, 'settings'])->name('settings');
    Route::post('/settings', [AdminDashboardController::class, 'updateSettings'])->name('settings.update');
    Route::post('/settings/apikeys', [AdminDashboardController::class, 'updateApiKeys'])->name('settings.apikeys');
    Route::post('/apikeys', [AdminDashboardController::class, 'updateApiKeys'])->name('apikeys.update');
    
    // User & Article Management
    Route::get('/users', [AdminDashboardController::class, 'users'])->name('users');
    Route::get('/articles', [AdminDashboardController::class, 'articles'])->name('articles');
});