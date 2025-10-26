<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'product_id',
        'expiration',
        'amount',
        'used',
        'batch_number',
        'current_volume'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function trayContents()
    {
        return $this->hasMany(TrayContent::class);
    }

    public function sourceMerges()
    {
        return $this->hasMany(StockMerge::class, 'source_stock_1')
                    ->orWhere('source_stock_2', $this->id);
    }

    public function resultMerges()
    {
        return $this->hasMany(StockMerge::class, 'result_stock');
    }
}
