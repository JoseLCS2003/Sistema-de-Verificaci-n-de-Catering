<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'tray_type', 'description'];

    public function items()
    {
        return $this->hasMany(MenuItem::class);
    }
}
