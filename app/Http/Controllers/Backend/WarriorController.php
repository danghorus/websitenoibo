<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Warrior;
use Illuminate\Http\Request;

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

        return view('petitions.index',compact('petitions','users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all();
        return view('petitions.index',compact('users'));
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


        return redirect()->route('petitions.index', compact('users'))
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
