<?php

use App\Http\Controllers\Backend\ChangePassword;
use App\Http\Controllers\Backend\ChangePasswordController;
use App\Http\Controllers\Backend\ChangeAvatarController;
use App\Http\Controllers\Backend\CityController;
use App\Http\Controllers\Backend\CountryController;
use App\Http\Controllers\Backend\DepartmentController;
use App\Http\Controllers\Backend\StateController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\YeuCauController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UploadImageController;

use App\Http\Controllers\Backend\PartnerController;
use App\Http\Controllers\Backend\TimeKeepingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/home');
});

Auth::routes();
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::resource('users', UserController::class);
    Route::post('save', [UserController::class, 'save']);
    Route::get('time-keeping', [TimeKeepingController::class, 'index']);
    Route::resource('request', YeuCauController::class);

    Route::post('users/{user}/change-password', [ChangePasswordController::class, 'change_password'])->name('users.change.password');
    Route::post('users/{user}/change-avatar', [ChangeAvatarController::class, 'change_avatar'])->name('users.change.avatar');

    Route::post('/partner/connect', [PartnerController::class, 'connect']);
    Route::get('/partner/get_devices', [PartnerController::class, 'getDevices']);
    Route::get('/partner/get_config', [PartnerController::class, 'getConfig']);
    Route::get('/partner/sync_device', [PartnerController::class, 'syncDevice']);
    Route::get('/partner/get_device_info', [PartnerController::class, 'getDeviceInfo']);
    Route::post('/partner/update_device', [PartnerController::class, 'updateDevice']);
    Route::post('/partner/update_user', [PartnerController::class, 'updateUser']);
    Route::get('/partner/get_users', [PartnerController::class, 'getUsers']);
    Route::get('/partner/get_config_time', [PartnerController::class, 'getConfigTime']);
    Route::post('/partner/update_config', [PartnerController::class, 'updateConfig']);

    Route::get('/time-keeping/get', [TimeKeepingController::class, 'get']);
    Route::get('/time-keeping/get_user', [TimeKeepingController::class, 'getUser']);
    Route::get('/time-keeping/detail', [TimeKeepingController::class, 'detail']);
    Route::post('/time-keeping/checkin', [TimeKeepingController::class, 'checkin']);
    Route::post('/time-keeping/update', [TimeKeepingController::class, 'update']);
    Route::get('/time-keeping/export', [TimeKeepingController::class, 'export']);
});



Route::get('{any}', function () {
    return view('employees.index');
})->where('any', '.*');
