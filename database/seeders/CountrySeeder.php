<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            ['name' => 'Indonesia', 'iso_code' => 'IDN', 'currency_code' => 'IDR', 'region' => 'Asia'],
            ['name' => 'Singapore', 'iso_code' => 'SGP', 'currency_code' => 'SGD', 'region' => 'Asia'],
            ['name' => 'Malaysia', 'iso_code' => 'MYS', 'currency_code' => 'MYR', 'region' => 'Asia'],
            ['name' => 'Japan', 'iso_code' => 'JPN', 'currency_code' => 'JPY', 'region' => 'Asia'],
            ['name' => 'China', 'iso_code' => 'CHN', 'currency_code' => 'CNY', 'region' => 'Asia'],
            ['name' => 'United States', 'iso_code' => 'USA', 'currency_code' => 'USD', 'region' => 'Americas'],
            ['name' => 'Germany', 'iso_code' => 'DEU', 'currency_code' => 'EUR', 'region' => 'Europe'],
            ['name' => 'United Kingdom', 'iso_code' => 'GBR', 'currency_code' => 'GBP', 'region' => 'Europe'],
        ];

        foreach ($countries as $c) {
            DB::table('countries')->updateOrInsert(
                ['iso_code' => $c['iso_code']],
                [
                    'name' => $c['name'],
                    'currency_code' => $c['currency_code'],
                    'region' => $c['region'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }
    }
}