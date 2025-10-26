<?php

namespace App\Queries;

use Illuminate\Support\Facades\DB;

class TrayRefillQuery
{
    public static function allWithEmployeeAndTray()
    {
        return DB::table('tray_refills as tr')
            ->join('employees as e', 'tr.employee_id', '=', 'e.id')
            ->join('trays as t', 'tr.tray_id', '=', 't.id')
            ->select('tr.id', 'tr.refill_start', 'tr.refill_end', 'tr.total_items_added', 'e.name as employee_name', 't.tray_code')
            ->get();
    }
}
