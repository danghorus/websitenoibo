<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\PetitionService;
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
    private PetitionService $petitionService;

    public function __construct(
        TimeKeepingService $timeKeepingService,
        PetitionService $petitionService
    )
    {
        $this->timeKeepingService = $timeKeepingService;
        $this->petitionService = $petitionService;
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
            'user_id' => 'required',
            'petition_reason' => 'required',
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

        //if (($petition->petition_type == 4 || $petition->petition_type == 5) && $request->petition_status == 2) {
        if ($petition->petition_type == 4 && $request->petition_status == 2) {
            $dataUpdate = [];
            $dataUpdate['user_id'] = $petition->user_id;
            $dataUpdate['date'] = $petition->date_from;
            $dataUpdate['reason'] = $petition->petition_reason;
            $dataUpdate['checkin'] = $petition->time_from;
            $dataUpdate['checkout'] =$petition->time_to;
            $dataUpdate['petition_type'] = 4;
            //$dataUpdate['checkin'] = $petition->petition_type == 4? $petition->time_to: '';
            //$dataUpdate['checkout'] = $petition->petition_type == 5? $petition->time_to: '';

            $this->timeKeepingService->update($dataUpdate);
        }
        if ($petition->petition_type != 4 && $request->petition_status == 2) {
            $dataUpdate = [];
            $dataUpdate['user_id'] = $petition->user_id;
            $dataUpdate['date'] = $petition->date_from;
            $dataUpdate['date_to'] = $petition->date_to;
            $dataUpdate['reason'] = $petition->petition_reason;
            $dataUpdate['time_from'] = $petition->time_from;
            $dataUpdate['time_to'] =$petition->time_to;
            $dataUpdate['petition_type'] =$petition->petition_type;
            $dataUpdate['type_leave'] =$petition->type_leave;
            //$dataUpdate['checkin'] = $petition->petition_type == 4? $petition->time_to: '';
            //$dataUpdate['checkout'] = $petition->petition_type == 5? $petition->time_to: '';

            $this->timeKeepingService->update($dataUpdate);
        }

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

    /**
     * @param Request $request
     */
    public function create_petition_time_keeping(Request $request) {
        $request->validate([
            'user_id' => 'required',
            'user_fullname' => 'required|string',
        ]);

        $isCreated = $this->petitionService->createPetition($request->all());

        if ($isCreated) {
            return [
                'code' => 200
            ];
        }
        return [
            'code' => 400
        ];
    }
}
