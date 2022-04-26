<?php

use App\Http\Controllers\Backend\ChangePassword;
use App\Http\Controllers\Backend\ChangePasswordController;
use App\Http\Controllers\Backend\ChangeAvatarController;
use App\Http\Controllers\Backend\CityController;
use App\Http\Controllers\Backend\CountryController;
use App\Http\Controllers\Backend\DepartmentController;
use App\Http\Controllers\Backend\StateController;
use App\Http\Controllers\Backend\TimeKeepingController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\YeuCauController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UploadImageController;

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

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::resource('users', UserController::class);
Route::post('save', [UserController::class, 'save']);
Route::resource('time-keeping', TimeKeepingController::class);
Route::resource('request', YeuCauController::class);


Route::post('users/{user}/change-password', [ChangePasswordController::class, 'change_password'])->name('users.change.password');
Route::post('users/{user}/change-avatar', [ChangeAvatarController::class, 'change_avatar'])->name('users.change.avatar');


Route::get('{any}', function () {
    return view('employees.index');
})->where('any', '.*');
