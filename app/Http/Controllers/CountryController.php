<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CountryService;

class CountryController extends Controller
{
    protected CountryService $countryService;

    public function __construct(CountryService $countryService)
    {
        $this->countryService = $countryService;
    }

    /**
     * Menampilkan Halaman Utama Data Negara
     */
    public function index()
    {
        return view('country.index');
    }

    /**
     * Menampilkan Halaman Perbandingan Negara (Fitur No 8 dari PDF)
     */
    public function comparison()
    {
        return view('country.comparison');
    }

    /**
     * Menampilkan Halaman Watchlist / Favorit (Fitur No 9 dari PDF)
     */
    public function watchlist()
    {
        return view('country.watchlist');
    }
}