<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrayRefill extends Model
{
    use HasFactory;

    protected $fillable = [
        'tray_id',
        'employee_id',
        'refill_start',
        'refill_end',
        'total_items_added'
    ];

    public function tray()
    {
        return $this->belongsTo(Tray::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
