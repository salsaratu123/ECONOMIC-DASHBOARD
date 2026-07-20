<?php

namespace App\Http\Controllers;

use App\Services\EconomyService;
use Illuminate\Http\Request;

class EconomyController extends Controller
{
    protected $worldbank;

    public function __construct(EconomyService $worldbank)
    {
        $this->worldbank = $worldbank;
    }

    public function index()
    {
        return view('economy.index');
    }

    public function current(Request $request)
    {
        return response()->json(
            $this->worldbank->getEconomy($request->query('country', 'IDN'))
        );
    }
}
