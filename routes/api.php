<?php

use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\EmployeeDataController;
use App\Http\Controllers\Backend\PartnerController;
use App\Http\Controllers\Backend\TimeKeepingController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::get('/employees/countries', [EmployeeDataController::class, 'countries']);
//Route::get('/employees/{country}/states', [EmployeeDataController::class, 'states']);
//Route::get('/employees/departments', [EmployeeDataController::class, 'departments']);
//Route::get('/employees/{state}/cities', [EmployeeDataController::class, 'cities']);


Route::post('/time-keeping/checkin_data', [TimeKeepingController::class, 'timeKeeping']);
Route::get('/partner/get_auth_code', [PartnerController::class, 'getAuthCode']);

// Route::get('/employees', [EmployeeController::class, 'index']);
// Route::post('/employees', [EmployeeController::class, 'store']);
// Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy']);

//Route::apiResource('employees', EmployeeController::class);
