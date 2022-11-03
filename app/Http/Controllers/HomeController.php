<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Project;
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
        $users = User::where('user_status', 1)->get() ;
        $projects = Project::all();
        $petitions = Petition::where('petition_status', 1)->get();
		$petitions1 = Petition::where('petition_status', 1)->get();
		$userId = Auth::user()->id;
		$petitions01 = Petition::where('petition_status', 1)->where('user_id', '=', $userId)->get();
        
                foreach ($users as $user) {

                    $user->birthday_month = 0;
                    $this_month = date('m', strtotime(time()));
                    $birthday = date('m', strtotime($user->birthday));
                    if( $birthday == $this_month){
                            $user->birthday_month += 1;
                    }
                }


        return view('home', compact('users', 'projects', 'petitions', 'petitions1', 'petitions01'));
    }

}
