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

        $sticker->save();

        return [
            'code' => 200
        ];
    }

    public function update($stickerId, Request $request) {
        $sticker = Sticker::find($stickerId);
        $sticker->sticker_name = $request->input('sticker_name');

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
