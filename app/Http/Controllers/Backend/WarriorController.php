<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Petition;
use App\Models\Warrior;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WarriorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $petitions = Warrior::latest()->paginate(50);
        $users = User::all();
		$petitions1 = Petition::where('petition_status', 1)->get();
		$userId = Auth::user()->id;
		$petitions01 = Petition::where('petition_status', 1)->where('user_id', '=', $userId)->get();

        return view('projects.warrior',compact('petitions','users', 'petitions1', 'petitions01'));
    }

    public function warrior(Request $request)
    {
        $petitions = Warrior::latest()->paginate(50);
        $users = User::all();
		$petitions1 = Petition::where('petition_status', 1)->get();
		$userId = Auth::user()->id;
		$petitions01 = Petition::where('petition_status', 1)->where('user_id', '=', $userId)->get();
        return view('projects.warrior', compact('petitions','users', 'petitions1', 'petitions01'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all();
		$petitions1 = Petition::where('petition_status', 1)->get();
		$userId = Auth::user()->id;
		$petitions01 = Petition::where('petition_status', 1)->where('user_id', '=', $userId)->get();
        return view('petitions.index',compact('users', 'petitions1', 'petitions01'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
        ]);

        Warrior::create($request->all());
        $users = User::all();
		$petitions1 = Petition::where('petition_status', 1)->get();
		$userId = Auth::user()->id;
		$petitions01 = Petition::where('petition_status', 1)->where('user_id', '=', $userId)->get();


        return redirect()->route('petitions.index', compact('users', 'petitions1', 'petitions01'))
                        ->with('success','Petition created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Warrior  $warrior
     * @return \Illuminate\Http\Response
     */
    public function show(Warrior $warrior)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Warrior  $warrior
     * @return \Illuminate\Http\Response
     */
    public function edit(Warrior $warrior)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Warrior  $warrior
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Warrior $warrior)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Warrior  $warrior
     * @return \Illuminate\Http\Response
     */
    public function destroy(Warrior $warrior)
    {
        //
    }
}
