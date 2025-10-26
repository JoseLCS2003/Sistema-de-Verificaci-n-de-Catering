<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stop extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'trip_id',
        'stop_number',
        'airport_code',
        'scheduled_time'
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function trayMovements()
    {
        return $this->hasMany(TrayMovement::class);
    }
}

