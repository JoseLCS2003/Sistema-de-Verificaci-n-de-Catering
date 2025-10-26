<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrayMovement extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'tray_id',
        'stop_id',
        'movement_type',
        'timestamp'
    ];

    public function tray()
    {
        return $this->belongsTo(Tray::class);
    }

    public function stop()
    {
        return $this->belongsTo(Stop::class);
    }
}
