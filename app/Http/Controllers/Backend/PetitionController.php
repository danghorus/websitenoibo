<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\PetitionService;
use App\Services\TimeKeepingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\Petition;
use App\Models\Project;
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
        //$petitions = DB::table('petitions', 'p')->select('p.*')->get();

        $user_id = Auth::user()->id;
        //foreach ($petitions as $petition) {
        //    $time_approved = strtotime($petition->updated_at) + 86400;
            //dd($time_approved - time());
        //}
        //$time =  date('Y-m-d H:i:s', (time() - 86400));

        $petitions = Petition::get();
        $petitions2 = Petition::where('petition_status', 2)->where('read','=', 0)->where('user_id', $user_id)->get();
        $petitions0 = Petition::where('petition_status', 0)->where('read','=', 0)->where('user_id', $user_id)->get();
        $projects = Project::all();
        $users = User::all();

        return view('petitions.index',compact('petitions', 'petitions2', 'petitions0', 'users', 'projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all();
        
        $projects = Project::all();
        return view('petitions.create',compact('users', 'projects'));
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
        $projects = Project::all();
        $users = User::all();


        return redirect()->route('petitions.index', compact('users', 'projects'))
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
        $projects = Project::all();
        return view('petitions.show',compact('petition', 'users', 'projects'));
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
        $projects = Project::all();
        return view('petitions.edit',compact('petition', 'users', 'projects'));
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
        if($request->petition_status != null){
            $petition -> update([
                'petition_status' => $request->petition_status,
                'read' => 0,
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
                $dataUpdate['type_OT'] =$petition->type_OT;
                //$dataUpdate['checkin'] = $petition->petition_type == 4? $petition->time_to: '';
                //$dataUpdate['checkout'] = $petition->petition_type == 5? $petition->time_to: '';

                $this->timeKeepingService->update($dataUpdate);
            }
            $users = User::all();
            $projects = Project::all();


            return redirect()->route('petitions.index', compact('users', 'projects'));
        }
        else {
            Petition::query()->update(['read' => 2]);
        }
    }


    public function approved(Request $request)
    {

        $petitions = Petition::where('petition_status', 2)->get();
        $projects = Project::all();
        $users = User::all();
       // $user_id = Auth::user()->id;

        Petition::query()->where('petition_status', 2)->update(['read' => 1]);
        foreach ($petitions as $petition) {
            if( $petition->petition_type == 1){
                $petition_type_title =  "Đi muộn về sớm";
            } else if($petition->petition_type == 2){
                $petition_type_title =  "Nghỉ phép";
            }else if($petition->petition_type == 3){
                $petition_type_title =  "Nghỉ việc";
            }else if($petition->petition_type == 4){
                $petition_type_title =  "Thay đổi giờ công";
            }else if($petition->petition_type == 5){
                $petition_type_title =  "Đăng ký làm thêm";
            }else if($petition->petition_type == 6){
                $petition_type_title =  "Đăng ký nỗ lực";
            }
        }

        if ($request->search != '' ) {
           
            $petitions = Petition::where( 'petition_type', 'like', "%{$request->search}%")->get();

        } else {

           $petitions = Petition::where('petition_status', 2)->get();
           
        }

        return view('petitions.approved',compact('petitions', 'users', 'projects'));
    }
    public function unapproved()
    {
       
        $petitions = Petition::where('petition_status', 0)->get();
        $projects = Project::all();
        $users = User::all();

        Petition::query()->where('petition_status', 0)->update(['read' => 1]);

        return view('petitions.unapproved',compact('petitions', 'users', 'projects'));
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
        $projects = Project::all();

        return redirect()->route('petitions.index', compact('users', 'projects'))
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
