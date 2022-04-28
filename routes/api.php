<?php

use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\EmployeeDataController;
use App\Http\Controllers\Api\PartnerController;
use App\Http\Controllers\Api\TimeKeepingController;
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

Route::get('/employees/countries', [EmployeeDataController::class, 'countries']);
Route::get('/employees/{country}/states', [EmployeeDataController::class, 'states']);
Route::get('/employees/departments', [EmployeeDataController::class, 'departments']);
Route::get('/employees/{state}/cities', [EmployeeDataController::class, 'cities']);

// Route::get('/employees', [EmployeeController::class, 'index']);
// Route::post('/employees', [EmployeeController::class, 'store']);
// Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy']);

Route::apiResource('employees', EmployeeController::class);

Route::post('/partner/connect', [PartnerController::class, 'connect']);
Route::get('/partner/get_auth_code', [PartnerController::class, 'getAuthCode']);
Route::get('/partner/get_devices', [PartnerController::class, 'getDevices']);
Route::get('/partner/get_config', [PartnerController::class, 'getConfig']);
Route::get('/partner/sync_device', [PartnerController::class, 'syncDevice']);
Route::get('/partner/get_device_info', [PartnerController::class, 'getDeviceInfo']);
Route::post('/partner/update_device', [PartnerController::class, 'updateDevice']);
Route::post('/partner/update_user', [PartnerController::class, 'updateUser']);
Route::get('/partner/get_users', [PartnerController::class, 'getUsers']);


Route::get('/time-keeping/get', [TimeKeepingController::class, 'get']);
