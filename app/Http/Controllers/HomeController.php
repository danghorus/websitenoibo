<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Work;
use App\Models\Petition;

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
        $works = Work::all();
        $petitions = Petition::where('petition_status', 1)->get();

        return view('home', compact('users', 'works', 'petitions'));
    }

}
