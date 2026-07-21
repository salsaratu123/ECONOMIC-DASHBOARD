<?php

namespace App\Services;

use App\Models\ApiLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WorldBankService
{
    public function getEconomy($country = 'IDN')
    {
        return Cache::remember('economy:' . strtoupper($country), now()->addHours(12), function () use ($country) {
            $indicators = [
                'gdp' => 'NY.GDP.MKTP.CD',
                'inflation' => 'FP.CPI.TOTL.ZG',
                'population' => 'SP.POP.TOTL',
            ];

            $result = [];
            $hasSuccess = false;

            foreach ($indicators as $key => $indicator) {
                $url = "https://api.worldbank.org/v2/country/{$country}/indicator/{$indicator}";

                try {
                    $response = Http::timeout(15)->get($url, [
                        'format' => 'json',
                        'per_page' => 10
                    ]);

                    if ($response->successful()) {
                        $json = $response->json();
                        $result[$key] = null;

                        if (isset($json[1])) {
                            foreach ($json[1] as $row) {
                                if (!is_null($row['value'])) {
                                    $result[$key] = [
                                        'year' => $row['date'],
                                        'value' => $row['value']
                                    ];
                                    $hasSuccess = true;
                                    break;
                                }
                            }
                        }
                        continue;
                    }
                } catch (\Throwable $e) {}

                $result[$key] = null;
            }

            if ($hasSuccess) {
                $this->logActivity('Macro Economic Data Ingestion Success', 200);
            } else {
                $this->logActivity('World Bank Query Failed / Empty', 404);
            }

            return $result;
        });
    }

    private function logActivity(string $status, int $code = 200)
    {
        try {
            ApiLog::create([
                'target_service' => 'World Bank API',
                'status_request' => $status,
                'response_code' => $code,
            ]);
        } catch (\Throwable $e) {}
    }
}