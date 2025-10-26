<?php

namespace App\Queries;

use Illuminate\Support\Facades\DB;

class TrayQuery
{
    public static function allWithTripAndLastRefill()
    {
        return DB::table('trays as tr')
            ->leftJoin('trips as t_current', 'tr.current_trip', '=', 't_current.id')
            ->leftJoin('trips as t_assigned', 'tr.assigned_trip', '=', 't_assigned.id')
            ->leftJoin('tray_refills as refill', 'tr.id', '=', 'refill.tray_id')
            ->leftJoin('employees as e', 'refill.employee_id', '=', 'e.id')
            ->leftJoin('tray_contents as tc', 'tr.id', '=', 'tc.tray_id')
            ->leftJoin('stocks as s', 'tc.stock_id', '=', 's.id')
            ->leftJoin('products as p', 's.product_id', '=', 'p.id')
            ->select(
                'tr.id as tray_id',
                'tr.tray_code',
                'tr.tray_type',
                'tr.capacity',
                'tr.status as tray_status',
                't_current.id as current_trip_id',
                't_current.flight_number as current_trip_number',
                't_assigned.id as assigned_trip_id',
                't_assigned.flight_number as assigned_trip_number',
                'refill.id as refill_id',
                'refill.refill_start',
                'refill.refill_end',
                'refill.total_items_added',
                'e.id as employee_id',
                'e.name as employee_name',
                'tc.id as tray_content_id',
                'tc.quantity as content_quantity',
                'tc.original_quantity as content_original_quantity',
                's.id as stock_id',
                's.amount as stock_amount',
                's.current_volume',
                's.batch_number',
                'p.id as product_id',
                'p.name as product_name'
            )
            ->get();
    }
}
