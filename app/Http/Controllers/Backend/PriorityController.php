<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Priority;
use Illuminate\Http\Request;

class PriorityController extends Controller
{
    public function index() {

        $priorities = Priority::all();

        return [
            'code' => 200,
            'data' => $priorities
        ];
    }

    public function create(Request $request) {
        $priority = new Priority();
        $priority->priority_label = $request->input('priority_label');

        $priority->save();

        return [
            'code' => 200
        ];
    }

    public function update($priorityId, Request $request) {
        $priority = Priority::find($priorityId);
        $priority->priority_label = $request->input('priority_label');

        $priority->save();

        return [
            'code' => 200
        ];
    }

    public function delete($priorityId) {
        Priority::query()->where('id', '=', $priorityId)->delete();

        return [
            'code' => 200
        ];
    }
}
