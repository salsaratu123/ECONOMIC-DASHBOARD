<?php

namespace App\Helpers;

use App\Models\ApiLog;

class ApiLogger
{
    public static function log(string $service, string $status, int $code = 200)
    {
        try {
            ApiLog::create([
                'target_service' => $service,
                'status_request' => $status,
                'response_code' => $code,
            ]);
        } catch (\Throwable $e) {
            // Abaikan error log agar tidak mengganggu aliran data utama
        }
    }
}