<?php

namespace App\Queries;

use Illuminate\Support\Facades\DB;

class StopQuery
{
    public static function allWithTrip()
    {
        return DB::table('stops as s')
            ->join('trips as t', 's.trip_id', '=', 't.id')
            ->select(
                's.id as stop_id',
                's.stop_number',
                's.airport_code',
                's.scheduled_time',
                't.id as trip_id',
                't.flight_number',
                't.origin',
                't.destination',
                't.no_stops',
                't.service_class'
            )
            ->get();
    }
}
