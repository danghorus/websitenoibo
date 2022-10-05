<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use App\Models\Work;
use App\Models\Petition;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $users = User::where('user_status', 1)->get();
        $works = Work::all();
        $projects=Project::all();
        $tasks=Task::where('status', 5)->get();
        $petitions = Petition::where('petition_status', 1)->get();

        $user_id = Auth::user()->id;

        $myTask = Task::where('task_performer','=', $user_id)->get();

        return view('home',
        compact(
            'users',
            'works',
            'petitions',
            'projects',
            'tasks',
            'myTask'
        ));
        
    }

}
