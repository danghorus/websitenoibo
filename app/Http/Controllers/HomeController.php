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
        $users = User::all();
        $works = Work::all();
        $petitions = Petition::all();

        return view('home', compact('users', 'works', 'petitions'));
    }
    
}
