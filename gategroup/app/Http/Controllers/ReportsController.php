<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Importar las Queries
use App\Queries\ProductQuery;
use App\Queries\StockQuery;
use App\Queries\StockMergeQuery;
use App\Queries\TrayQuery;
use App\Queries\TrayContentQuery;
use App\Queries\TrayMovementQuery;
use App\Queries\TrayRefillQuery;
use App\Queries\MenuItemQuery;
use App\Queries\StopQuery;

class ReportsController extends Controller
{
    // Productos con Categoría
    public function products()
    {
        return response()->json(ProductQuery::allWithCategory());
    }

    // Stocks con Productos
    public function stocks()
    {
        return response()->json(StockQuery::allWithProduct());
    }

    // Stock Merges con Stocks y Empleados
    public function stockMerges()
    {
        return response()->json(StockMergeQuery::allWithStocksAndEmployee());
    }

    // Bandejas con viaje y último refill
    public function trays()
    {
        return response()->json(TrayQuery::allWithTripAndLastRefill());
    }

    // Contenido de bandejas con stock y productos
    public function trayContents()
    {
        return response()->json(TrayContentQuery::allWithStockAndProduct());
    }

    // Movimientos de bandejas con parada
    public function trayMovements()
    {
        return response()->json(TrayMovementQuery::allWithTrayAndStop());
    }

    // Rellenos de bandejas con empleado
    public function trayRefills()
    {
        return response()->json(TrayRefillQuery::allWithEmployeeAndTray());
    }

    // Items de menú con menú y producto
    public function menuItems()
    {
        return response()->json(MenuItemQuery::allWithMenuAndProduct());
    }

    // Escalas con vuelo
    public function stops()
    {
        return response()->json(StopQuery::allWithTrip());
    }
}
