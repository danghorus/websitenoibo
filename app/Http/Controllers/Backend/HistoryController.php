<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\History;
use App\Models\ProjectUser;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Petition;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $users = User::all();
		$petitions1 = Petition::where('petition_status', 1)->get();
		$userId = Auth::user()->id;
		$petitions01 = Petition::where('petition_status', 1)->where('user_id', '=', $userId)->get();
        $petitions02 = Petition::where('petition_status', 2)->where('user_id', '=', $userId)->get();
        $petitions03 = Petition::where('petition_status', 3)->where('user_id', '=', $userId)->get();

        return view('histories.index', compact('users','petitions1', 'petitions01', 'petitions02', 'petitions03'));
    }
    public function get(Request $request) {
        $histories = History::query()->get();

        return [
            'histories' => $histories
        ];
    }

}
