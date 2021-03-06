<?php

use App\Http\Controllers\Backend\ChangePasswordController;
use App\Http\Controllers\Backend\PasswordController;
use App\Http\Controllers\Backend\ChangeAvatarController;
use App\Http\Controllers\Backend\PriorityController;
use App\Http\Controllers\Backend\HolidayController;
use App\Http\Controllers\Backend\StickerController;
use App\Http\Controllers\Backend\TaskController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\PetitionController;
use App\Http\Controllers\Backend\WarriorController;
use App\Http\Controllers\Backend\SettingController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Backend\PartnerController;
use App\Http\Controllers\Backend\TimeKeepingController;
use App\Http\Controllers\Backend\WorkController;
use App\Http\Controllers\Backend\ProjectController;


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
    Route::resource('petitions', PetitionController::class);
    Route::resource('warriors', WarriorController::class);
    Route::resource('works', WorkController::class);
    Route::resource('settings', SettingController::class);

    Route::post('users/{user}/change-password', [ChangePasswordController::class, 'change_password'])->name('users.change.password');
    Route::post('users/{user}/change-avatar', [ChangeAvatarController::class, 'change_avatar'])->name('users.change.avatar');
    Route::get('user/all_user', [UserController::class, 'all_user']);
    Route::get('user/all_user_by_group', [UserController::class, 'all_user_by_group']);

    Route::get('change_password', [PasswordController::class, 'index']);
    Route::post('change_password', [PasswordController::class, 'changePassword'])->name('change.password');

    //Petition
    Route::post('/petition/create_petition_time_keeping', [PetitionController::class, 'create_petition_time_keeping']);
    Route::post('/petition/create_holiday', [PetitionController::class, 'create_holiday']);
    Route::post('/petition/warrior', [PetitionController::class, 'warrior']);

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
    Route::get('/partner/disconnect', [PartnerController::class, 'disconnect']);
    Route::get('/partner/sync_users', [PartnerController::class, 'syncUsers']);
    Route::get('/partner/get-sync-timekeeping', [PartnerController::class, 'getSyncTimekeeping']);

    Route::get('time-keeping', [TimeKeepingController::class, 'index']);
    Route::get('/time-keeping/get', [TimeKeepingController::class, 'get']);
    Route::get('/time-keeping/get_user', [TimeKeepingController::class, 'getUser']);
    Route::get('/time-keeping/detail', [TimeKeepingController::class, 'detail']);
    Route::post('/time-keeping/checkin', [TimeKeepingController::class, 'checkin']);
    Route::post('/time-keeping/update', [TimeKeepingController::class, 'update']);

    Route::post('/time-keeping/petition', [TimeKeepingController::class, 'petition']);
    Route::post('/petition/petition', [PetitionController::class, 'petition']);

    Route::get('/time-keeping/export', [TimeKeepingController::class, 'export']);
    Route::get('/time-keeping/get-report', [TimeKeepingController::class, 'getReport']);
    Route::get('/time-keeping/get-wage', [TimeKeepingController::class, 'getWage']);
    Route::get('/time-keeping/get-bonus', [TimeKeepingController::class, 'getBonus']);

    Route::get('time-keeping-report', [TimeKeepingController::class, 'report']);

    Route::get('time-keeping-wage', [TimeKeepingController::class, 'wage']);
    Route::get('time-keeping-bonus', [TimeKeepingController::class, 'bonus']);

    Route::get('time-keeping-report/export', [TimeKeepingController::class, 'export_report']);

    //projects
    Route::get('projects', [ProjectController::class, 'index']);
    Route::post('projects/create', [ProjectController::class, 'create']);
    Route::post('projects/{id}/update', [ProjectController::class, 'update']);
    Route::get('projects/get_all', [ProjectController::class, 'getAll']);
    Route::get('projects/{id}', [ProjectController::class, 'getInfo']);
    Route::get('projects/{id}/get_detail', [ProjectController::class, 'getDetail']);
    Route::get('project-report', [ProjectController::class, 'report']);

    //Priority
    Route::post('priorities/create', [PriorityController::class, 'create']);
    Route::post('priorities/update/{id}', [PriorityController::class, 'update']);
    Route::post('priorities/delete/{id}', [PriorityController::class, 'delete']);
    Route::get('/priorities/get_all', [PriorityController::class, 'index']);

    //Holiday
    Route::post('holiday/create', [HolidayController::class, 'create']);
    Route::post('holiday/update/{id}', [HolidayController::class, 'update']);
    Route::post('holiday/delete/{id}', [HolidayController::class, 'delete']);
    Route::get('/holiday/get_all', [HolidayController::class, 'index']);

    //Sticker
    Route::post('stickers/create', [StickerController::class, 'create']);
    Route::post('stickers/update/{id}', [StickerController::class, 'update']);
    Route::post('stickers/delete/{id}', [StickerController::class, 'delete']);
    Route::get('/stickers/get_all', [StickerController::class, 'index']);


    //Task
    Route::post('tasks/create', [TaskController::class, 'create']);
    Route::post('tasks/update/{id}', [TaskController::class, 'update']);
    Route::get('tasks/get_all', [TaskController::class, 'getAll']);
    Route::get('tasks/index', [TaskController::class, 'index']);
    Route::get('tasks/timeline', [TaskController::class, 'timeline']);
    Route::get('tasks/detail/{id}', [TaskController::class, 'detail']);
    Route::post('tasks/delete/{id}', [TaskController::class, 'delete']);
    Route::post('tasks/change-status/{id}', [TaskController::class, 'changeStatus']);
    Route::get('tasks/get-report', [TaskController::class, 'getReport']);

    //My Work
    Route::get('my_work', [ProjectController::class, 'my_work']);
    Route::get('my-tasks', [TaskController::class, 'myTasks']);

});



Route::get('{any}', function () {
    return view('employees.index');
})->where('any', '.*');
