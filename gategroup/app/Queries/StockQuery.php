<?php

namespace App\Queries;

use Illuminate\Support\Facades\DB;

class StockQuery
{
    public static function allWithProduct()
    {
        return DB::table('stocks as s')
            ->join('products as p', 's.product_id', '=', 'p.id')
            ->join('categories as c', 'p.category_id', '=', 'c.id')
            ->select(
                's.id as stock_id',
                's.amount',
                's.used',
                's.current_volume',
                's.batch_number',
                's.expiration',
                'p.id as product_id',
                'p.name as product_name',
                'p.unit_volume',
                'c.id as category_id',
                'c.name as category_name'
            )
            ->get();
    }
}
