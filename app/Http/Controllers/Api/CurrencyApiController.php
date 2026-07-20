<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ExchangeService;

class CurrencyApiController extends Controller
{
    protected ExchangeService $exchangeService;

    public function __construct(ExchangeService $exchangeService)
    {
        $this->exchangeService = $exchangeService;
    }

    public function getCurrency()
    {
        $rates = $this->exchangeService->getExchange();
        return response()->json($rates, 200);
    }
}