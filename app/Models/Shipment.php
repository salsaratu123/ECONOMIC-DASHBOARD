<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    protected $fillable = [
        'container_number',
        'origin_country',
        'destination_country',
        'origin_port',
        'destination_port',
        'ship_name',
        'eta',
        'status',
        'risk_level',
    ];

    protected $casts = [
        'eta' => 'date',
    ];
}
