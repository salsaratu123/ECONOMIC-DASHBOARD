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

/*
|--------------------------------------------------------------------------
| Web Routes - Global Supply Chain Risk Intelligence Platform
|--------------------------------------------------------------------------
*/

Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
Route::get('/api/dashboard', [DashboardController::class, 'data']);

// Route Simpan Pengaturan Dashboard Khusus User (Login Required)
Route::middleware('auth')->group(function () {
    Route::post('/user/preferences', [UserPreferenceController::class, 'update'])->name('user.preferences.update');
});

// Modul View Blade Multi-API
Route::get('/weather', [WeatherController::class, 'index'])->name('weather.index');
Route::get('/economy', [EconomyController::class, 'index'])->name('economy.index');
Route::get('/exchange', [ExchangeController::class, 'index'])->name('exchange.index');
Route::get('/countries', [CountryController::class, 'index'])->name('countries.index');
Route::get('/news', [NewsController::class, 'index'])->name('news.index');

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
| Admin Dashboard Group
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', IsAdmin::class])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Management Pengontrol Tampilan & API Keys Admin
    Route::get('/settings', [AdminDashboardController::class, 'settings'])->name('settings');
    Route::post('/settings', [AdminDashboardController::class, 'updateSettings'])->name('settings.update');
    
    // Alias Route untuk Pengaturan API Key (Memperbaiki RouteNotFoundException)
    Route::post('/settings/apikeys', [AdminDashboardController::class, 'updateApiKeys'])->name('settings.apikeys');
    
    Route::get('/users', [AdminDashboardController::class, 'users'])->name('users');
    Route::get('/ports', [AdminDashboardController::class, 'ports'])->name('ports');
    Route::get('/articles', [AdminDashboardController::class, 'articles'])->name('articles');
});