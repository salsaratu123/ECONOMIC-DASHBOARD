<?php

namespace App\Http\Controllers;

use App\Services\CountryService;

class CountryController extends Controller
{
    protected CountryService $country;

    public function __construct(CountryService $country)
    {
        $this->country = $country;
    }

    public function index()
    {
        return view('countries.index');
    }

    public function all()
    {
        return response()->json(
            $this->country->all()
        );
    }

    public function detail($name)
    {
        return response()->json(
            $this->country->find($name)
        );
    }
}