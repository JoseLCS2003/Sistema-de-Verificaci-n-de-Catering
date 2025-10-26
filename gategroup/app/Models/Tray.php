<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tray extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'tray_code',
        'tray_type',
        'capacity',
        'status',
        'current_trip',
        'last_refill',
        'assigned_trip'
    ];

    public function contents()
    {
        return $this->hasMany(TrayContent::class);
    }

    public function movements()
    {
        return $this->hasMany(TrayMovement::class);
    }

    public function refills()
    {
        return $this->hasMany(TrayRefill::class);
    }

    public function currentTrip()
    {
        return $this->belongsTo(Trip::class, 'current_trip');
    }

    public function assignedTrip()
    {
        return $this->belongsTo(Trip::class, 'assigned_trip');
    }
}
