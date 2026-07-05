<?php

namespace App\Http\Controllers;

use App\Services\WorldBankService;

class EconomyController extends Controller
{
    protected $worldbank;

    public function __construct(WorldBankService $worldbank)
    {
        $this->worldbank = $worldbank;
    }

    public function index()
    {
        return view('economy.index');
    }

    public function current()
    {
        return response()->json(
            $this->worldbank->getEconomy()
        );
    }
}