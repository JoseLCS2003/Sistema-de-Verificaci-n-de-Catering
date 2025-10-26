<?php

namespace App\Queries;

use Illuminate\Support\Facades\DB;

class ProductQuery
{
    public static function allWithCategory()
    {
        return DB::table('products as p')
            ->join('categories as c', 'p.category_id', '=', 'c.id')
            ->select(
                'p.id as product_id',
                'p.name as product_name',
                'p.requires_expiration',
                'p.is_mergeable',
                'p.unit_volume',
                'c.id as category_id',
                'c.name as category_name',
                'c.description as category_description',
                'c.handling_type'
            )
            ->get();
    }
}
