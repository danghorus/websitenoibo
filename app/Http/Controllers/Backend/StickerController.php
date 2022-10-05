<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Sticker;
use Illuminate\Http\Request;

class StickerController extends Controller
{
    public function index() {

        $stickers = Sticker::all();

        return [
            'code' => 200,
            'data' => $stickers
        ];
    }

    public function create(Request $request) {
        $sticker = new Sticker();
        $sticker->sticker_name = $request->input('sticker_name');
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
