<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WorldBankService
{
    public function getEconomy($country = 'IDN')
    {
        $indicators = [
            'gdp' => 'NY.GDP.MKTP.CD',
            'inflation' => 'FP.CPI.TOTL.ZG',
            'population' => 'SP.POP.TOTL',
        ];

        $result = [];

        foreach ($indicators as $key => $indicator) {

            $url = "https://api.worldbank.org/v2/country/{$country}/indicator/{$indicator}";

            $response = Http::get($url, [
                'format' => 'json',
                'per_page' => 10
            ]);

            if (!$response->successful()) {
                $result[$key] = null;
                continue;
            }

            $json = $response->json();

            $result[$key] = null;

            if (isset($json[1])) {

                foreach ($json[1] as $row) {

                    if (!is_null($row['value'])) {

                        $result[$key] = [
                            'year' => $row['date'],
                            'value' => $row['value']
                        ];

                        break;
                    }
                }
            }
        }

        return $result;
    }
}