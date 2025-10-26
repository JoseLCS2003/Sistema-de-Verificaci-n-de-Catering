<?php

namespace App\Queries;

use Illuminate\Support\Facades\DB;

class TrayContentQuery
{
    public static function allWithStockAndProduct()
    {
        return DB::table('tray_contents as tc')
            ->join('stocks as s', 'tc.stock_id', '=', 's.id')
            ->join('products as p', 's.product_id', '=', 'p.id')
            ->select('tc.id', 'tc.tray_id', 'tc.quantity', 'tc.original_quantity', 's.id as stock_id', 'p.name as product_name')
            ->get();
    }
}
