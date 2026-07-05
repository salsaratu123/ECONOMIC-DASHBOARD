<?php

namespace App\Http\Controllers;

use App\Services\WeatherService;
use App\Services\WorldBankService;

class DashboardController extends Controller
{
    protected $weather;
    protected $economy;

    public function __construct(
        WeatherService $weather,
        WorldBankService $economy
    ) {
        $this->weather = $weather;
        $this->economy = $economy;
    }

    public function index()
    {
        return view('dashboard.index');
    }

        public function data()
    {
        return response()->json([
            'weather' => $this->weather->getWeather(),
            'economy' => $this->economy->getEconomy(),
            'exchange' => app(\App\Services\ExchangeService::class)->getExchange(),
        ]);
    }
}