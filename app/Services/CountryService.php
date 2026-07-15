<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CountryService
{
    private string $baseUrl = 'https://api.restcountries.com/countries/v5';

    private function client()
    {
        return Http::withToken(env('RESTCOUNTRIES_API_KEY'))
            ->acceptJson()
            ->timeout(30);
    }

    public function all()
    {
        $response = $this->client()->get($this->baseUrl);

        if (!$response->successful()) {
            return $response->json();
        }

        return $response->json()['data']['objects'] ?? [];
    }

    public function find(string $country)
    {
        $response = $this->client()->get($this->baseUrl, [
            'q' => $country
        ]);

        if (!$response->successful()) {
            return [
                'success' => false,
                'message' => 'Country not found'
            ];
        }

        $json = $response->json();

        if (
            !isset($json['data']['objects']) ||
            count($json['data']['objects']) == 0
        ) {
            return [
                'success' => false,
                'message' => 'Country not found'
            ];
        }

        $c = $json['data']['objects'][0];

        return [

            'success' => true,

            'name' => $c['names']['common'] ?? '-',

            'official' => $c['names']['official'] ?? '-',

            'capital' => $c['capitals'][0]['name'] ?? '-',

            'region' => $c['region'] ?? '-',

            'population' => $c['population'] ?? 0,

            'currency' => $c['currencies'][0]['code'] ?? '-',

            'currency_name' => $c['currencies'][0]['name'] ?? '-',

            'language' => $c['languages'][0]['name'] ?? '-',

            'flag' => $c['flag']['url_png'] ?? '',

            'latitude' => $c['coordinates']['lat'] ?? 0,

            'longitude' => $c['coordinates']['lng'] ?? 0,

            'google_maps' => $c['links']['google_maps'] ?? '',

            'timezone' => $c['timezones'][0] ?? '-'

        ];
    }
}