<?php

namespace App\Http\Controllers;

use App\Services\ExchangeService;

class ExchangeController extends Controller
{
    protected $exchange;

    public function __construct(
        ExchangeService $exchange
    ){
        $this->exchange=$exchange;
    }

    public function index()
    {
        return view('exchange.index');
    }

    public function current()
    {
        return response()->json(
            $this->exchange->getExchange()
        );
    }
}