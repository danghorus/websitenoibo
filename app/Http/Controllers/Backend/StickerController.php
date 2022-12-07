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
