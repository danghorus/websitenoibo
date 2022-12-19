<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Sticker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class StickerController extends Controller
{
    public function index(Request $request) {

        $department = $request->input('task_department');

        $AuthDepartment = Auth::user()->department;

        $builder = Sticker::query()->select('*');

        if($department == 12){
            $builder->where('sticker_department', '=', $AuthDepartment);
        }else if($department == 20){
            $builder;
        }else if($department != null){
           $builder->where('sticker_department', '=', $department);
        }else{
            $builder;
        }
        $stickers = $builder->get();

        foreach ($stickers as $sticker) {
            if($sticker->sticker_department == 2){
                $sticker->sticker_department_label = "Dev";
            }else if($sticker->sticker_department == 3){
                $sticker->sticker_department_label = "Game Design";
            } else if($sticker->sticker_department == 4){
                $sticker->sticker_department_label = "Art";
            }else if($sticker->sticker_department == 5){
                $sticker->sticker_department_label = "Tester";
            } else if($sticker->sticker_department == 11){
                $sticker->sticker_department_label = "Marketing";
            }
        }
        return [
            'code' => 200,
            'data' => $stickers
        ];
    }
    public function get_all_myWork(Request $request) {

        $department = $request->input('task_department');

        //$AuthDepartment = Auth::user()->department;

        $builder = Sticker::query()->select('*');
        
        $builder->where('sticker_department', '=', $department);

        //$builder->where('sticker_department', '=', $AuthDepartment);
        
        $stickers = $builder->get();

        foreach ($stickers as $sticker) {
            if($sticker->sticker_department == 2){
                $sticker->sticker_department_label = "Dev";
            }else if($sticker->sticker_department == 3){
                $sticker->sticker_department_label = "Game Design";
            } else if($sticker->sticker_department == 4){
                $sticker->sticker_department_label = "Art";
            }else if($sticker->sticker_department == 5){
                $sticker->sticker_department_label = "Tester";
            } else if($sticker->sticker_department == 11){
                $sticker->sticker_department_label = "Marketing";
            }
        }
        return [
            'code' => 200,
            'data' => $stickers
        ];
    }

    public function changeStickerName($stickerId, Request $request) {
        $sticker_name = $request->input('sticker_name');

        $sticker = Sticker::find($stickerId);

        $sticker->sticker_name = $sticker_name;

        $sticker->save();

        return [
            'code' => 200,
            'message' => 'Cập nhật thành công'
        ];
    }
    public function changeStickerDepartment($stickerId, Request $request) {
        $sticker_department = $request->input('sticker_department');

        $sticker = Sticker::find($stickerId);

        $sticker->sticker_department = $sticker_department;

        $sticker->save();

        return [
            'code' => 200,
            'message' => 'Cập nhật thành công'
        ];
    }
    public function changeLevel1($stickerId, Request $request) {
        $level_1 = $request->input('level_1');

        $sticker = Sticker::find($stickerId);

        $sticker->level_1 = $level_1;

        $sticker->save();

        return [
            'code' => 200,
            'message' => 'Cập nhật thành công'
        ];
    }

    public function changeLevel2($stickerId, Request $request) {
        $level_2 = $request->input('level_2');

        $sticker = Sticker::find($stickerId);

        $sticker->level_2 = $level_2;

        $sticker->save();

        return [
            'code' => 200,
            'message' => 'Cập nhật thành công'
        ];
    }
    public function changeLevel3($stickerId, Request $request) {
        $level_3 = $request->input('level_3');

        $sticker = Sticker::find($stickerId);

        $sticker->level_3 = $level_3;

        $sticker->save();

        return [
            'code' => 200,
            'message' => 'Cập nhật thành công'
        ];
    }
    public function changeLevel4($stickerId, Request $request) {
        $level_4 = $request->input('level_4');

        $sticker = Sticker::find($stickerId);

        $sticker->level_4 = $level_4;

        $sticker->save();

        return [
            'code' => 200,
            'message' => 'Cập nhật thành công'
        ];
    }
    public function changeLevel5($stickerId, Request $request) {
        $level_5 = $request->input('level_5');

        $sticker = Sticker::find($stickerId);

        $sticker->level_5 = $level_5;

        $sticker->save();

        return [
            'code' => 200,
            'message' => 'Cập nhật thành công'
        ];
    }
    public function changeLevel6($stickerId, Request $request) {
        $level_6 = $request->input('level_6');

        $sticker = Sticker::find($stickerId);

        $sticker->level_6 = $level_6;

        $sticker->save();

        return [
            'code' => 200,
            'message' => 'Cập nhật thành công'
        ];
    }
    public function changeLevel7($stickerId, Request $request) {
        $level_7 = $request->input('level_7');

        $sticker = Sticker::find($stickerId);

        $sticker->level_7 = $level_7;

        $sticker->save();

        return [
            'code' => 200,
            'message' => 'Cập nhật thành công'
        ];
    }
    public function changeLevel8($stickerId, Request $request) {
        $level_8 = $request->input('level_8');

        $sticker = Sticker::find($stickerId);

        $sticker->level_8 = $level_8;

        $sticker->save();

        return [
            'code' => 200,
            'message' => 'Cập nhật thành công'
        ];
    }
    public function changeLevel9($stickerId, Request $request) {
        $level_9 = $request->input('level_9');

        $sticker = Sticker::find($stickerId);

        $sticker->level_9 = $level_9;

        $sticker->save();

        return [
            'code' => 200,
            'message' => 'Cập nhật thành công'
        ];
    }
    public function changeLevel10($stickerId, Request $request) {
        $level_10 = $request->input('level_10');

        $sticker = Sticker::find($stickerId);

        $sticker->level_10 = $level_10;

        $sticker->save();

        return [
            'code' => 200,
            'message' => 'Cập nhật thành công'
        ];
    }

    public function create(Request $request) {
        $sticker = new Sticker();
        $sticker->sticker_name = $request->input('sticker_name');
        $sticker->sticker_department = $request->input('sticker_department');
        $sticker->level_1 = $request->input('level_1');
        $sticker->level_2 = $request->input('level_2');
        $sticker->level_3 = $request->input('level_3');
        $sticker->level_4 = $request->input('level_4');
        $sticker->level_5 = $request->input('level_5');
        $sticker->level_6 = $request->input('level_6');
        $sticker->level_7 = $request->input('level_7');
        $sticker->level_8 = $request->input('level_8');
        $sticker->level_9 = $request->input('level_9');
        $sticker->level_10 = $request->input('level_10');


        $sticker->save();

        return [
            'code' => 200
        ];
    }

    public function update($stickerId, Request $request) {
        $sticker = Sticker::find($stickerId);
        $sticker->sticker_name = $request->input('sticker_name');
        $sticker->sticker_department = $request->input('sticker_department');
        $sticker->level_1 = $request->input('level_1');
        $sticker->level_2 = $request->input('level_2');
        $sticker->level_3 = $request->input('level_3');
        $sticker->level_4 = $request->input('level_4');
        $sticker->level_5 = $request->input('level_5');
        $sticker->level_6 = $request->input('level_6');
        $sticker->level_7 = $request->input('level_7');
        $sticker->level_8 = $request->input('level_8');
        $sticker->level_9 = $request->input('level_9');
        $sticker->level_10 = $request->input('level_10');

        $sticker->save();

        return [
            'code' => 200
        ];
    }

    public function delete($priorityId) {
        Sticker::query()->where('id', '=', $priorityId)->delete();

        return [
            'code' => 200
        ];
    }
}
