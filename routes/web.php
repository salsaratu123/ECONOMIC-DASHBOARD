<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\EconomyController;
use App\Http\Controllers\ExchangeController;
use App\Http\Controllers\CountryController;

Route::get('/', [DashboardController::class,'index']);

Route::get('/weather', [WeatherController::class,'index']);
Route::get('/economy', [EconomyController::class,'index']);
Route::get('/exchange', [ExchangeController::class,'index']);
Route::get('/countries', [CountryController::class,'index']);

Route::get('/api/dashboard', [DashboardController::class,'data']);
Route::get('/api/economy', [EconomyController::class,'current']);
Route::get('/api/exchange', [ExchangeController::class,'current']);

Route::get('/api/countries', [CountryController::class,'all']);
Route::get('/api/countries/{name}', [CountryController::class,'detail']);

Route::get('/api/country/{country}', [CountryController::class,'detail']);