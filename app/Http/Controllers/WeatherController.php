<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WeatherController extends Controller
{
    /**
     * Menampilkan halaman dashboard pemantauan cuaca global (Global Weather Monitoring)
     */
    public function index()
    {
        return view('weather.index');
    }
}