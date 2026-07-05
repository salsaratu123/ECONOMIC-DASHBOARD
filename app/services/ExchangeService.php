<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ExchangeService
{
    public function getExchange()
    {
        $response = Http::get(
            'https://open.er-api.com/v6/latest/USD'
        );

        if (!$response->successful()) {
            return null;
        }

        $json = $response->json();

        return [
            'USD' => 1,
            'IDR' => $json['rates']['IDR'],
            'EUR' => $json['rates']['EUR'],
            'JPY' => $json['rates']['JPY']
        ];
    }
}