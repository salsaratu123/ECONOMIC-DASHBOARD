<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\EconomyController;
use App\Http\Controllers\ExchangeController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\MarineController;
use App\Http\Controllers\Admin\AdminDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes - Global Supply Chain Risk Intelligence Platform
|--------------------------------------------------------------------------
*/

// Halaman View Utama Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');

// ENDPOINT AJAX UTAMA: Dipanggil langsung oleh dashboard.js ke /api/dashboard
Route::get('/api/dashboard', [DashboardController::class, 'data']);

// Modul View Blade Lintas Multi-API [cite: 91, 115, 120, 125, 131]
Route::get('/weather', [WeatherController::class, 'index'])->name('weather.index');
Route::get('/economy', [EconomyController::class, 'index'])->name('economy.index');
Route::get('/exchange', [ExchangeController::class, 'index'])->name('exchange.index');
Route::get('/countries', [CountryController::class, 'index'])->name('countries.index');
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/marine', [MarineController::class, 'index'])->name('marine.index');

// Target Fitur: Country Comparison Engine & Watchlist View [cite: 144, 152]
Route::get('/comparison', [CountryController::class, 'comparison'])->name('countries.comparison');
Route::get('/watchlist', [CountryController::class, 'watchlist'])->name('countries.watchlist');

// Target Fitur: Admin Dashboard Group [cite: 154]
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [AdminDashboardController::class, 'users'])->name('users');
    Route::get('/ports', [AdminDashboardController::class, 'ports'])->name('ports');
    Route::get('/articles', [AdminDashboardController::class, 'articles'])->name('articles');
});