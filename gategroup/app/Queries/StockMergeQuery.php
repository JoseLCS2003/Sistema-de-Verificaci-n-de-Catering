<?php

namespace App\Queries;

use Illuminate\Support\Facades\DB;

class StockMergeQuery
{
    public static function allWithStocksAndEmployee()
    {
        return DB::table('stock_merges as sm')
            ->join('stocks as s1', 'sm.source_stock_1', '=', 's1.id')
            ->join('products as p1', 's1.product_id', '=', 'p1.id')
            ->join('stocks as s2', 'sm.source_stock_2', '=', 's2.id')
            ->join('products as p2', 's2.product_id', '=', 'p2.id')
            ->join('stocks as s3', 'sm.result_stock', '=', 's3.id')
            ->join('products as p3', 's3.product_id', '=', 'p3.id')
            ->join('employees as e', 'sm.merged_by', '=', 'e.id')
            ->select(
                'sm.id as stock_merge_id',
                'sm.merge_date',
                'sm.notes',
                's1.id as source_stock_1_id',
                's1.amount as source_stock_1_amount',
                'p1.name as source_product_1_name',
                's2.id as source_stock_2_id',
                's2.amount as source_stock_2_amount',
                'p2.name as source_product_2_name',
                's3.id as result_stock_id',
                's3.amount as result_stock_amount',
                'p3.name as result_product_name',
                'e.id as employee_id',
                'e.name as merged_by'
            )
            ->get();
    }
}
