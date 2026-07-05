<?php

use App\Http\Controllers\WeatherController;
use App\Http\Controllers\EconomyController;
use App\Http\Controllers\ExchangeController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\MarineController;

Route::get('/weather',[WeatherController::class,'current']);
Route::get('/economy',[EconomyController::class,'current']);
Route::get('/exchange',[ExchangeController::class,'current']);
Route::get('/country',[CountryController::class,'current']);
Route::get('/news',[NewsController::class,'current']);
Route::get('/marine',[MarineController::class,'current']);
