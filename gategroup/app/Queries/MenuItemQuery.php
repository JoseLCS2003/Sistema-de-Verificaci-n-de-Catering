<?php

namespace App\Queries;

use Illuminate\Support\Facades\DB;

class MenuItemQuery
{
    public static function allWithMenuAndProduct()
    {
       return DB::table('menu_items as mi')
            ->join('menus as m', 'mi.menu_id', '=', 'm.id')
            ->join('products as p', 'mi.product_id', '=', 'p.id')
            ->join('categories as c', 'p.category_id', '=', 'c.id')
            ->select(
                'mi.id as menu_item_id',
                'mi.quantity as menu_item_quantity',
                'm.id as menu_id',
                'm.name as menu_name',
                'm.tray_type as menu_tray_type',
                'p.id as product_id',
                'p.name as product_name',
                'c.id as category_id',
                'c.name as category_name'
            )
            ->get();
    }
}
