<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'flight_number',
        'origin',
        'destination',
        'no_stops',
        'service_class'
    ];

    public function stops()
    {
        return $this->hasMany(Stop::class);
    }

    public function traysAssigned()
    {
        return $this->hasMany(Tray::class, 'assigned_trip');
    }

    public function traysCurrent()
    {
        return $this->hasMany(Tray::class, 'current_trip');
    }
}
