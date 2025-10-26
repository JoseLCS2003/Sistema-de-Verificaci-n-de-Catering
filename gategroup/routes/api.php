<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use App\Http\Controllers\GenericCrudController;

Route::prefix('crud/{model}')->group(function () {
    Route::get('/', [GenericCrudController::class, 'index']);
    Route::get('/{id}', [GenericCrudController::class, 'show']);
    Route::post('/', [GenericCrudController::class, 'store']);
    Route::put('/{id}', [GenericCrudController::class, 'update']);
    Route::delete('/{id}', [GenericCrudController::class, 'destroy']);
});


use App\Http\Controllers\ReportsController;

Route::prefix('reports')->group(function () {
    Route::get('/products', [ReportsController::class, 'products']);
    Route::get('/stocks', [ReportsController::class, 'stocks']);
    Route::get('/stock-merges', [ReportsController::class, 'stockMerges']);
    Route::get('/trays', [ReportsController::class, 'trays']);
    Route::get('/tray-contents', [ReportsController::class, 'trayContents']);
    Route::get('/tray-movements', [ReportsController::class, 'trayMovements']);
    Route::get('/tray-refills', [ReportsController::class, 'trayRefills']);
    Route::get('/menu-items', [ReportsController::class, 'menuItems']);
    Route::get('/stops', [ReportsController::class, 'stops']);
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
