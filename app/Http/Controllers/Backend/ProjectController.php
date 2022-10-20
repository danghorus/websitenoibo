<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Petition;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $users = User::all();
		$petitions1 = Petition::where('petition_status', 1)->get();
		$userId = Auth::user()->id;
		$petitions01 = Petition::where('petition_status', 1)->where('user_id', '=', $userId)->get();
        return view('projects.index', compact('users','petitions1', 'petitions01'));
    }

    public function my_work(Request $request)
    {
        $users = User::all();
		$petitions1 = Petition::where('petition_status', 1)->get();
		$userId = Auth::user()->id;
		$petitions01 = Petition::where('petition_status', 1)->where('user_id', '=', $userId)->get();
        return view('projects.my_work',  compact('users','petitions1', 'petitions01'));
    }
    public function list_work(Request $request)
    {
        $users = User::all();
		$petitions1 = Petition::where('petition_status', 1)->get();
		$userId = Auth::user()->id;
		$petitions01 = Petition::where('petition_status', 1)->where('user_id', '=', $userId)->get();
        return view('projects.list_work',  compact('users','petitions1', 'petitions01'));
    }
	
	public function list_work_done(Request $request)
    {
        $users = User::all();
		$petitions1 = Petition::where('petition_status', 1)->get();
		$userId = Auth::user()->id;
		$petitions01 = Petition::where('petition_status', 1)->where('user_id', '=', $userId)->get();
        return view('projects.list_work_done',  compact('users','petitions1', 'petitions01'));
    }

    public function report(Request $request)
    {
        $users = User::all();
		$petitions1 = Petition::where('petition_status', 1)->get();
		$userId = Auth::user()->id;
		$petitions01 = Petition::where('petition_status', 1)->where('user_id', '=', $userId)->get();
        return view('projects.report',  compact('users','petitions1', 'petitions01'));
    }

    public function warrior(Request $request)
    {
        $users = User::all();
		$petitions1 = Petition::where('petition_status', 1)->get();
		$userId = Auth::user()->id;
		$petitions01 = Petition::where('petition_status', 1)->where('user_id', '=', $userId)->get();
        return view('projects.warrior',  compact('users','petitions1', 'petitions01'));
    }

    public function getAll(Request $request) {
        $projects = Project::query()->where('id', '!=', 1)->select(['id', 'project_name', 'project_start_date', 'project_end_date'])->get();

        return [
            'projects' => $projects
        ];
    }

    public function getInfo($projectId) {
        $project = Project::find($projectId);

        return [
            'code' => 200,
            'project' => $project
        ];
    }

    public function getDetail($projectId) {
        $project = Project::find($projectId);
        $users = ProjectUser::query()->where('project_id', '=', $projectId)->get();
        $usersRelated = [];

        foreach ($users as $user) {
            $usersRelated[] = $user->user_id;
        }

        return [
            'code' => 200,
            'project' => $project,
            'users_related' => $usersRelated
        ];
    }

    public function update($projectId, Request $request) {
        $info = $request->all();

        $data = $info['project'];

        $project = Project::find($projectId);
        $project->project_name = $data['project_name'] ?? '';
        $project->project_code = $data['project_code'] ?? '';
        $project->project_start_date = $data['project_start_date'] ?? null;
        $project->project_end_date = $data['project_end_date'] ?? null;
        $project->project_day = $data['project_day'] ?? '';
        $project->description = $data['description'] ?? '';
        $project->project_manager = isset($data['project_manager']) && $data['project_manager'] ? $data['project_manager']['id']: null;
        $isUpdated = $project->save();

        if ($isUpdated) {
            ProjectUser::query()->where('project_id', '=', $projectId)->delete();

            $userIds = $info['user_related'];
            if ($project->project_manager) {
                $userIds[] = $project->project_manager;
            }
            $userIds = array_unique($userIds);

            foreach ($userIds as $userId) {
                $projectUser = new ProjectUser();
                $projectUser->user_id = $userId;
                $projectUser->project_id = $project->id;
                $projectUser->save();
            }

        }

        return [
            'code' => 200,
            'message' => 'Cập nhật thành công',
        ];
    }

    public function create(Request $request) {
        $info = $request->all();

        $data = $info['project'];

        $project = new Project();

        $project->project_name = $data['project_name'] ?? '';
        $project->project_code = $data['project_code'] ?? '';
        $project->project_start_date = $data['project_start_date'] ?? null;
        $project->project_end_date = $data['project_end_date'] ?? null;
        $project->project_day = $data['project_day'] ?? '';
        $project->description = $data['description'] ?? '';
        $project->project_manager = isset($data['project_manager']) && $data['project_manager'] ? $data['project_manager']['id']: null;
        $isCreated = $project->save();

        $userIds = $info['user_related'];
        if ($project->project_manager) {
            $userIds[] = $project->project_manager;
        }
        $userIds = array_unique($userIds);
        if ($isCreated) {
            foreach ($userIds as $userId) {
                $projectUser = new ProjectUser();
                $projectUser->user_id = $userId;
                $projectUser->project_id = $project->id;
                $projectUser->save();
            }
        }

        return [
            'code' => 200,
            'message' => 'Thêm mới thành công',
        ];
    }

    public function delete($projectId) {
        Project::query()->where('id', '=', $projectId)->delete();

        Task::query()->where('project_id', '=', $projectId)->delete();

        return [
            'code' => 200,
            'message' => 'Xóa thành công'
        ];
    }
}
