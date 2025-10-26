<?php

namespace App\Queries;

use Illuminate\Support\Facades\DB;

class TrayMovementQuery
{
    public static function allWithTrayAndStop()
    {
        return DB::table('tray_movements as tm')
            ->join('trays as tr', 'tm.tray_id', '=', 'tr.id')
            ->join('stops as s', 'tm.stop_id', '=', 's.id')
            ->join('trips as t', 's.trip_id', '=', 't.id')
            ->select(
                'tm.id as tray_movement_id',
                'tm.movement_type',
                'tm.timestamp as movement_timestamp',
                'tr.id as tray_id',
                'tr.tray_code',
                's.id as stop_id',
                's.stop_number',
                's.airport_code',
                's.scheduled_time',
                't.id as trip_id',
                't.flight_number'
            )
            ->get();
    }
}
