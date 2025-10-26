<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMerge extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'source_stock_1',
        'source_stock_2',
        'result_stock',
        'merged_by',
        'merge_date',
        'notes'
    ];

    public function sourceStock1()
    {
        return $this->belongsTo(Stock::class, 'source_stock_1');
    }

    public function sourceStock2()
    {
        return $this->belongsTo(Stock::class, 'source_stock_2');
    }

    public function resultStock()
    {
        return $this->belongsTo(Stock::class, 'result_stock');
    }

    public function mergedBy()
    {
        return $this->belongsTo(Employee::class, 'merged_by');
    }
}

