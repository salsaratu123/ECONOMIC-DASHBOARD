<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Port extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'country', 'latitude', 'longitude', 'status'];

    public function originRoutes()
    {
        return $this->hasMany(Route::class, 'origin_port_id');
    }

    public function destinationRoutes()
    {
        return $this->hasMany(Route::class, 'destination_port_id');
    }
}