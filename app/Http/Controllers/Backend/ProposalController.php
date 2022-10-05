<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;

class ProposalController extends Controller
{
    public function index() {

        $proposals = Proposal::all();

        $users = User::all();

        $projects = Project::all();

        return view('proposals.index', compact('users','projects','proposals'));
    }

    public function list_proposals() {
        $builder = DB::table('proposals', 'pp');
        $builder->join('users as u', 'pp.user_id', '=', 'u.id', 'left');

        $proposals = $builder->get();

        foreach ($proposals as $pp) {
            if ($pp->petition_type == 1) {
                $pp->petition_type_title = 'Đi muộn về sớm';
            } elseif ($pp->petition_type == 2) {
                $pp->petition_type_title = 'Nghỉ phép';
            } elseif ($pp->petition_type == 3) {
                $pp->petition_type_title = 'Nghỉ việc';
            } elseif ($pp->petition_type == 4) {
                $pp->petition_type_title = 'Thay đổi giờ công';
            } elseif ($pp->petition_type == 5) {
                $pp->petition_type_title = 'Đăng ký làm thêm';
            } elseif ($pp->petition_type == 6) {
                $pp->petition_type_title = 'Đăng ký làm nỗ lực';
            } elseif ($pp->petition_type == 7) {
                $pp->petition_type_title = 'Nghỉ tiêu chuẩn công ty';
            }

            if($pp->type_leave == 1){
                $pp->type_leave_title = 'nửa ngày(sáng)';
            } else if($pp->type_leave == 2){
                $pp->type_leave_title == 'nửa ngày(chiều)';
            } else if($pp->type_leave == 3){
                $pp->type_leave_title = 'một ngày';
            } else if($pp->type_leave == 4){
                $pp->type_leave_title = 'nhiều ngày';
            } else {
                $pp->type_leave_title = '';
            }

            $pp->date_from_title =  date('d-m-Y', strtotime($pp->date_from));
            $pp->time_send =  date('d-m-Y', strtotime($pp->created_at));
        }

        return [
            'code' => 200,
            'proposals' =>  $proposals
        ];
    }

    public function create(Request $request) {

        $proposal = new Proposal();

        $proposal->user_id = $request->input('user_id');
        $proposal->time_from = $request->input('time_from');
        $proposal->time_to = $request->input('time_to');
        $proposal->date_from = $request->input('date_from');
        $proposal->date_to = $request->input('date_to');
        $proposal->petition_reason = $request->input('petition_reason');
        $proposal->petition_type = $request->input('petition_type');
        $proposal->type_leave = $request->input('type_leave');
        $proposal->petition_status = $request->input('petition_status');

        $proposal->save();


        return [
            'code' => 200,
        ];
    }

    public function update($proposalId, Request $request) {
        $proposal = Proposal::find($proposalId);

        $proposal->user_id = $request->input('user_id');
        $proposal->time_from = $request->input('time_from');
        $proposal->time_to = $request->input('time_to');
        $proposal->date_from = $request->input('date_from');
        $proposal->date_to = $request->input('date_to');
        $proposal->petition_reason = $request->input('petition_reason');
        $proposal->petition_type = $request->input('petition_type');
        $proposal->type_leave = $request->input('type_leave');
        $proposal->petition_status = $request->input('petition_status');

        $proposal->save();

        return [
            'code' => 200
        ];
    }

    public function delete($proposalId) {

        Proposal::query()->where('id', '=', $proposalId )->delete();

        return [
            'code' => 200
        ];
    }
}
