<?php

namespace App\Http\Controllers;

use App\Services\WeatherService;

class WeatherController extends Controller
{
    protected $weather;

    public function __construct(WeatherService $weather)
    {
        $this->weather = $weather;
    }

    public function index()
    {
        return view('weather.index');
    }

    public function current()
    {
        return response()->json(
            $this->weather->getWeather()
        );
    }
}