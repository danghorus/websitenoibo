<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\TimeKeepingService;

use App\Models\Petition;
use App\Models\User;
use Illuminate\Http\Request;

class PetitionController extends Controller
{
     /**
     * @var TimeKeepingService
     */
    private $timeKeepingService;

    public function __construct(
        TimeKeepingService $timeKeepingService
    )
    {
        $this->timeKeepingService = $timeKeepingService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $petitions = Petition::latest()->paginate(50);
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
        return view('petitions.create',compact('users'));
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
            'reason' => 'required',

        ]);

        Petition::create($request->all());
        $users = User::all();


        return redirect()->route('petitions.index', compact('users'))
                        ->with('success','Petition created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Petition  $Petition
     * @return \Illuminate\Http\Response
     */
    public function show(Petition $petition)
    {
        $users = User::all();
        return view('petitions.show',compact('petition', 'users'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Petition  $petition
     * @return \Illuminate\Http\Response
     */
    public function edit(Petition $petition)
    {
        $users = User::all();
        return view('petitions.edit',compact('petition', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Petition  $petition
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Petition $petition)
    {

        $petition -> update([
            'petition_status' => $request->petition_status,
        ]);
        $users = User::all();


        return redirect()->route('petitions.index', compact('users'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Petition  $petition
     * @return \Illuminate\Http\Response
     */
    public function destroy(Petition $petition)
    {
        $petition->delete();
        $users = User::all();

        return redirect()->route('petitions.index', compact('users'))
                        ->with('success','Petition deleted successfully');
    }

    public function petition(Request $request)
    {
        $data = $request->all();

        $result = $this->timeKeepingService->petition($data);

        return [
            'code' => 200,
        ];

    }

}
