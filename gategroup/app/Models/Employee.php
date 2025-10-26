<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['id', 'name', 'role'];

    public function trayRefills()
    {
        return $this->hasMany(TrayRefill::class);
    }

    public function stockMerges()
    {
        return $this->hasMany(StockMerge::class, 'merged_by');
    }
}

