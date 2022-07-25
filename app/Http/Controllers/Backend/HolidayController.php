<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function index() {

        $holiday = Holiday::all();

        return [
            'code' => 200,
            'data' => $holiday
        ];
    }

    public function create(Request $request) {
        $holiday = new Holiday();
        $holiday->holiday_name = $request->input('holiday_name');
        $holiday->holiday_date_start = $request->input('holiday_date_start');
        $holiday->holiday_date_end = $request->input('holiday_date_end');

        $holiday->save();

        return [
            'code' => 200
        ];
    }

    public function update($holidayId, Request $request) {
        $holiday = Holiday::find($holidayId);
        $holiday->holiday_name = $request->input('holiday_name');
        $holiday->holiday_date_start = $request->input('holiday_date_start');
        $holiday->holiday_date_end = $request->input('holiday_date_end');

        $holiday->save();

        return [
            'code' => 200
        ];
    }

    public function delete($holidayId) {
        Holiday::query()->where('id', '=', $holidayId)->delete();

        return [
            'code' => 200
        ];
    }
}
