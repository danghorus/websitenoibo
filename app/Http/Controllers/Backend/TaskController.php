<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Petition;
use App\Models\Priority;
use App\Models\Project;
use App\Models\Sticker;
use App\Models\Task;
use App\Models\TaskUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    public function index(Request $request) {

        $projectId = $request->input('project_id');
        $taskParent = $request->input('parent_task');
        $startTime = $request->input('start_time');
        $startTimeDay = date('Y-m-d', strtotime($request->input('start_time')));
        $search = $request->input('search');
        $taskPerformer = $request->input('task_performer');
        $taskDepartment = $request->input('task_department');
        $taskStatus = $request->input('status');

        $builder = DB::table('tasks', 'tt')->select('tt.*')
            ->selectRaw("(SELECT count(t.id) total_child FROM tasks as t WHERE t.task_parent = tt.id) total_child")
            ->selectRaw('p.project_name, u.fullname');
        $builder->join('projects as p', 'tt.project_id', '=', 'p.id');
        $builder->join('users as u', 'tt.task_performer', '=', 'u.id', 'left');
        $builder->where('tt.project_id', '=',$projectId);
        $builder->where('tt.valid', '=', 1);

        if ($taskParent) {
            $builder->where('task_parent', '=', $taskParent);
        } else {
            $builder->where('task_parent', '=', NULL);
        }

        if ($taskPerformer && $taskPerformer > 0) {
            $builder->where('task_performer', '=', $taskPerformer);
        }

        if ($taskDepartment && $taskDepartment > 0) {
            $builder->where('task_department', '=', $taskDepartment);
        }

        if ($taskStatus >= 0) {
            $builder->where('status', '=', $taskStatus);
        }

        if ($search && $search != '') {
            $builder->where('task_name', 'LIKE', "%$search%");
        }

        if ($startTime && $startTime != '') {
            $builder->whereDate('start_time', '=', $startTime);
        }

        $tasks = $builder->get();

        foreach ($tasks as $key => $value) {
            $indexKey = (int)$key + 1;
//            $value->key_label = $isFirst? $indexKey: $keyLabel. '.'.$indexKey;

            $value->department_label = $value->task_department? Task::DEPARTMENTS[$value->task_department]: '';

            if (($value->status == 0 || $value->status == 1) && (strtotime($value->end_time) < time())) {
                $value->status_title = 'Đã quá hạn';
            } elseif ($value->status == 4 && (strtotime($value->real_end_time) > strtotime($value->end_time))) {
                $value->status_title = 'Hoàn thành chậm';
            }elseif ($value->status == 5) {
                $value->status_title = 'Chờ feedback';
            }elseif ($value->status == 6) {
                $value->status_title = 'làm lại';
            } else {
                $value->status_title = $value->status >= 0 ? Task::ARR_STATUS[$value->status]: '';
            }
			$value->progress_label = " $value->progress %";
//            $value->fullname = $value->taskUser? $value->taskUser->fullname: '';
            $value->_hasChildren = $value->total_child > 0;
            $value->_children = [];
//            $value->task_name_label = $value->key_label . '   ' . $value->task_name;

//            if (count($value->children) > 0) {
//                self::taskChildrentFormat($value->children, false, $value->key_label);
//            }
        }

        return [
            'code' => 200,
            'data' => $tasks,
            'currentUser' => Auth::user()
        ];
    }

    public function timeline(Request $request) {

        $projectId = $request->input('project_id');
        $startTime = $request->input('start_time');
        $search = $request->input('search');
        $taskPerformer = $request->input('task_performer');
        $taskDepartment = $request->input('task_department');
        $taskStatus = $request->input('status');

        $builder = DB::table('tasks', 'tt')->select('tt.*')
            ->selectRaw("(SELECT count(t.id) total_child FROM tasks as t WHERE t.task_parent = tt.id) total_child")
            ->selectRaw('p.project_name, u.fullname');
        $builder->join('projects as p', 'tt.project_id', '=', 'p.id');
        $builder->join('users as u', 'tt.task_performer', '=', 'u.id', 'left');

        $builder->where('tt.status', '!=', '4');
        $builder->where('tt.valid', '=', 1);

        if ($projectId > 0) {
            $builder->where('tt.project_id', '=',$projectId);
        }

        if ($startTime && $startTime != '') {
            $builder->whereDate('tt.start_time', '=', $startTime);
        } else {
            $builder->whereDate('tt.start_time', '<=', date('Y-m-d', time()));
            //$builder->whereDate('tt.end_time', '>=', date('Y-m-d', time()));
        }

        if ($taskPerformer && $taskPerformer > 0) {
            $builder->where('tt.task_performer', '=', $taskPerformer);
        }

        if ($taskDepartment && $taskDepartment > 0) {
            $builder->where('tt.task_department', '=', $taskDepartment);
        }

        if ($taskStatus >= 0) {
            $builder->where('tt.status', '=', $taskStatus);
        }

        if ($search && $search != '') {
            $builder->where('tt.task_name', 'LIKE', "%$search%");
        }
        $tasks = $builder->get();

        foreach ($tasks as $task) {
            $task->department_label = $task->task_department? Task::DEPARTMENTS[$task->task_department]: '';

            if (($task->status == 0 || $task->status == 1) && (strtotime($task->end_time) < time())) {
                $task->status_title = 'Đã quá hạn';
            } elseif ($task->status == 4 && (strtotime($task->real_end_time) > strtotime($task->end_time))) {
                $task->status_title = 'Hoàn thành chậm';

            } elseif ($task->status == 5) {
                $task->status_title = 'Chờ feedback';

            }
            elseif ($task->status == 6) {
                $task->status_title = 'Làm lại';

            } else {
                $task->status_title = $task->status >= 0 ? Task::ARR_STATUS[$task->status]: '';
            }
        }

        return [
            'code' => 200,
            'data' => $tasks
        ];
    }

    public function create(Request $request) {
        $data = $request->all();

        $taskInfo = $data['task'];

        $task = new Task();

        $task->task_name = $taskInfo['task_name'] ?? '';
        $task->task_code = $taskInfo['task_code'] ?? '';
        $task->start_time = $taskInfo['start_time'] ?? null;
        $task->time = $taskInfo['time'] ?? null;
        $task->end_time = $taskInfo['end_time'] ?? null;
        $task->description = $taskInfo['description'] ?? '';
        $task->task_priority = isset($taskInfo['task_priority']) && $taskInfo['task_priority'] ? $taskInfo['task_priority']['id']: null;
        $task->task_sticker = isset($taskInfo['task_sticker']) && $taskInfo['task_sticker'] ?$taskInfo['task_sticker']['id']: null;
        $task->task_department = isset($taskInfo['task_department']) && $taskInfo['task_department']? $taskInfo['task_department']['value']: null;
        $task->weight = $taskInfo['weight'] ?? null;
        $task->project_id = isset($taskInfo['project_id']) && $taskInfo['project_id'] ?$taskInfo['project_id']['id']: null;
        $task->task_predecessor = isset($taskInfo['task_predecessor']) && $taskInfo['task_predecessor'] ?$taskInfo['task_predecessor']['id']: null;
        $task->task_parent = isset($taskInfo['task_parent']) && $taskInfo['task_parent'] ?$taskInfo['task_parent']: null;
//        $task->level = isset($taskInfo['task_parent']) && $taskInfo['task_parent'] ?$taskInfo['task_parent']['level'] + 1: 1;
        $task->task_performer = isset($taskInfo['task_performer']) && $taskInfo['task_performer'] ?$taskInfo['task_performer']['id']: null;

        if ($task->task_predecessor) {
            $taskPredecessor = Task::find($task->task_predecessor);
            $task->status = $taskPredecessor && $taskPredecessor->status == Task::TASK_COMPLETED? Task::TASK_WAITING: Task::TASK_NEW;
        } else {
            $task->status = Task::TASK_WAITING;
        }

        $isCreated = $task->save();

        $userIds = $data['user_related'];

        if ($task->task_performer) {
            $userIds[] = $task->task_performer;
        }

        $userIds = array_unique($userIds);
        if ($isCreated) {
            foreach ($userIds as $userId) {
                $taskUser = new TaskUser();
                $taskUser->user_id = $userId;
                $taskUser->task_id = $task->id;
                $taskUser->save();
            }
        }

        if ($task->project_id) {
            $project = Project::find($task->project_id);
            if ($task->start_time
                && (! $project->project_start_date || strtotime($project->project_start_date. ' 23:59:59') > strtotime($task->start_time))) {
                $project->project_start_date = date('Y-m-d', strtotime($task->start_time));
            }
            if ($task->end_time
                && (! $project->project_end_date || strtotime($project->project_end_date. ' 23:59:59') > strtotime($task->end_time))) {
                $project->project_end_date = date('Y-m-d', strtotime($task->end_time));
            }

            $project->save();
        }

        $arrParent = [];

        if ($task->task_parent) {
            $tasks = Task::query()->with(['parent'])->where('id', '=', $task->id)->get();
            foreach ($tasks as $value) {
                if ($value->parent) {
                    $item = $value->parent;
                    while ($item) {
                        $arrParent[] = $item->id;
                        $item = $item->parent;
                    }
                }
            }
        }

        $newTasks = Task::query()->with(['taskUser'])->where('id', '=', $task->id)->first();
        if ($newTasks) {
            $newTasks->department_label = $newTasks->task_department? Task::DEPARTMENTS[$newTasks->task_department]: '';

            if (($newTasks->status == 0 || $newTasks->status == 1) && (strtotime($newTasks->end_time) < time())) {
                $newTasks->status_title = 'Đã quá hạn';
            } elseif ($newTasks->status == 4 && (strtotime($newTasks->real_end_time) > strtotime($newTasks->end_time))) {
                $newTasks->status_title = 'Hoàn thành chậm';
            } else {
                $newTasks->status_title = $newTasks->status >= 0 ? Task::ARR_STATUS[$newTasks->status]: '';
            }
            $newTasks->fullname = $newTasks->taskUser? $newTasks->taskUser->fullname: '';
            $newTasks->_hasChildren = false;
            $newTasks->_children = [];
        }

        return [
            'code' => 200,
            'message' => 'Thêm mới thành công',
            'new_task' => $newTasks,
            'arr_parent' => array_reverse($arrParent),
        ];
    }

    public function update($taskId, Request $request) {
        $data = $request->all();

        $taskInfo = $data['task'];

        $task = Task::find($taskId);

        $changeParent = false;
        $arrNewParent = [];
        $arrOldParent = [];

        if ($task->task_parent) {
            $tasks = Task::query()->with(['parent'])->where('id', '=', $task->id)->get();
            foreach ($tasks as $value) {
                if ($value->parent) {
                    $item = $value->parent;
                    while ($item) {
                        $arrOldParent[] = $item->id;
                        $item = $item->parent;
                    }
                }
            }
        }

        if ((isset($taskInfo['task_parent']) && $task->task_parent != $taskInfo['task_parent']) || ($task->task_parent && !isset($taskInfo['task_parent']))) {
            $changeParent = true;
        }

        $task->task_name = $taskInfo['task_name'] ?? '';
        $task->task_code = $taskInfo['task_code'] ?? '';
        $task->start_time = $taskInfo['start_time'] ?? null;
        $task->time = $taskInfo['time'] ?? null;
        $task->end_time = $taskInfo['end_time'] ?? null;
        $task->description = $taskInfo['description'] ?? '';
        $task->task_priority = isset($taskInfo['task_priority']) && $taskInfo['task_priority'] ? $taskInfo['task_priority']['id']: null;
        $task->task_sticker = isset($taskInfo['task_sticker']) && $taskInfo['task_sticker'] ?$taskInfo['task_sticker']['id']: null;
        $task->task_department = isset($taskInfo['task_department']) && $taskInfo['task_department']? $taskInfo['task_department']['value']: null;
        $task->weight = $taskInfo['weight'] ?? null;
        $task->project_id = isset($taskInfo['project_id']) && $taskInfo['project_id'] ?$taskInfo['project_id']['id']: null;
        $task->task_parent = isset($taskInfo['task_parent']) && $taskInfo['task_parent'] ?$taskInfo['task_parent']: null;
//        $task->level = isset($taskInfo['task_parent']) && $taskInfo['task_parent'] ?$taskInfo['task_parent']['level'] + 1: 1;
        $task->task_performer = isset($taskInfo['task_performer']) && $taskInfo['task_performer'] ?$taskInfo['task_performer']['id']: null;

        if (isset($taskInfo['task_predecessor']) && $task->task_predecessor != $taskInfo['task_predecessor']) {
            $taskPredecessor = Task::find($taskInfo['task_predecessor']);
            $task->status = $taskPredecessor && $taskPredecessor->status == Task::TASK_COMPLETED? Task::TASK_WAITING: Task::TASK_NEW;
        }

        $task->task_predecessor = isset($taskInfo['task_predecessor']) && $taskInfo['task_predecessor'] ?$taskInfo['task_predecessor']['id']: null;

        $isCreated = $task->save();

        $userIds = $data['user_related'];

        if ($task->task_performer) {
            $userIds[] = $task->task_performer;
        }

        $userIds = array_unique($userIds);
        if ($isCreated) {
            TaskUser::query()->where('task_id', '=', $taskId)->delete();
            foreach ($userIds as $userId) {
                $taskUser = new TaskUser();
                $taskUser->user_id = $userId;
                $taskUser->task_id = $task->id;
                $taskUser->save();
            }
        }

        if ($task->project_id) {
            $project = Project::find($task->project_id);
            if ($task->start_time
                && (! $project->project_start_date || strtotime($project->project_start_date. ' 23:59:59') > strtotime($task->start_time))) {
                $project->project_start_date = date('Y-m-d', strtotime($task->start_time));
            }
            if ($task->end_time
                && (! $project->project_end_date || strtotime($project->project_end_date. ' 23:59:59') > strtotime($task->end_time))) {
                $project->project_end_date = date('Y-m-d', strtotime($task->end_time));
            }

            $project->save();
        }

        $newTasks = Task::query()->with(['taskUser'])->where('id', '=', $task->id)->first();

        if ($newTasks) {
            $newTasks->department_label = $newTasks->task_department? Task::DEPARTMENTS[$newTasks->task_department]: '';

            if (($newTasks->status == 0 || $newTasks->status == 1) && (strtotime($newTasks->end_time) < time())) {
                $newTasks->status_title = 'Đã quá hạn';
            } elseif ($newTasks->status == 4 && (strtotime($newTasks->real_end_time) > strtotime($newTasks->end_time))) {
                $newTasks->status_title = 'Hoàn thành chậm';
            }elseif ($newTasks->status == 5) {
                $newTasks->status_title = 'Chờ feedback';
            }elseif ($newTasks->status == 6) {
                $newTasks->status_title = 'Làm lại';
            }			else {
                $newTasks->status_title = $newTasks->status >= 0 ? Task::ARR_STATUS[$newTasks->status]: '';
            }
            $newTasks->fullname = $newTasks->taskUser? $newTasks->taskUser->fullname: '';
            $totalChild = Task::query()->where('task_parent', '=', $taskId)->count();
            //$newTasks->_hasChildren = $totalChild > 0;
            //$newTasks->_children = [];
            if($changeParent){
                $newTasks->_hasChildren = $totalChild > 0;
                $newTasks->_chilfren = [];
            }
        }

        if ($changeParent) {
            if ($task->task_parent) {
                $tasks = Task::query()->with(['parent'])->where('id', '=', $task->id)->get();
                foreach ($tasks as $value) {
                    if ($value->parent) {
                        $item = $value->parent;
                        while ($item) {
                            $arrNewParent[] = $item->id;
                            $item = $item->parent;
                        }
                    }
                }
            }
        }

        return [
            'code' => 200,
            'message' => 'Cập nhật thành công',
            'change_parent' => $changeParent,
            'new_task' => $newTasks,
            'arr_old_parent' => array_reverse($arrOldParent),
            'arr_new_parent' => array_reverse($arrNewParent),
        ];
    }
    public function all_task(Request $request)
    {
        $projectId = 14;

        $tasks = Task::query()->where('project_id','=',$projectId)->select(['id', 'task_name'])->get();

        return [
            'code' => 200,
            'data' => $tasks
        ];
    }

    public function getAll(Request $request) {
        $projectId = $request->input('project_id');
        $taskParent = $request->input('task_parent');

        $taskId = $request->input('task_id');

        $builder = Task::query()
            ->where('project_id', '=', $projectId)
            ->where('valid', '=', 1);
        if ($taskParent) {
            $builder->where('task_parent', '=', $taskParent);
        } else {
            $builder->whereRaw('(task_parent = 0 OR task_parent IS NULL)');
        }
        $tasks = $builder->get();
        if ($taskId > 0) {
            $task = Task::query()->with(['parent'])->where('id', '=', $taskId)->first();

            $id = $task->id;
            $item = $task->parent;
            while ($item) {
                $id = $item->id;
                $item = $item->parent;
            }
            $taskParent = Task::query()->with(['children' => function ($q) {
                $q->where('valid', '=', 1);
            }])
                ->where('id', '=', $id)->first();
        }

        foreach ($tasks as $key => $task) {
            if ($taskId > 0 && $task->id == $id) {
                $task->label = $task->task_name;
                $task->children = Task::taskChildrentFormat($taskParent->children);
            } else {
                $task->label = $task->task_name;
                $task->children = null;
            }
        }

        return [
            'code' => 200,
            'data' => $tasks
        ];
    }

    public function delete($taskId) {
        $task = Task::find($taskId);

        $tasks = Task::query()->with(['parent'])->where('id', '=', $taskId)->get();
        $arrParent = [];
        foreach ($tasks as $value) {
            if ($value->parent) {
                $item = $value->parent;
                while ($item) {
                    $arrParent[] = $item->id;
                    $item = $item->parent;
                }
            }
        }

        if ($task) {
            $tasks = Task::query()->with(['children' => function ($q) {
                $q->where('valid', '=', 1);
            }])->where('id', '=', $taskId)->first();

            Task::taskChildrenInvalid([$tasks]);
        }

        return [
            'code' => 200,
            'task_id' => $taskId,
            'arr_parent' => array_reverse($arrParent)
        ];
    }

    public function detail($taskId) {
        $task = Task::find($taskId);
        $detail = [];

        $detail['task_name'] = $task->task_name ?? '';
        $detail['task_code'] = $task->task_code ?? '';
        $detail['start_time'] = $task->start_time ?? '';
        //$detail['start_time_day'] = $task->start_time_day ?? '';
        $detail['time'] = $task->time ?? 0;
        $detail['end_time'] = $task->end_time ?? '';
        $detail['description'] = $task->description ?? '';
        $detail['weight'] = $task->weight ?? null;

        if ($task->task_priority) {
            $detail['task_priority'] = Priority::find($task->task_priority);
        }

        if ($task->task_sticker) {
            $detail['task_sticker'] = Sticker::find($task->task_sticker);
        }

        $detail['task_department'] = isset($taskInfo['task_department']) && $taskInfo['task_department']? $taskInfo['task_department']['value']: null;

        if ($task->project_id) {
            $detail['project_id'] = Project::find($task->project_id);
        }

        if ($task->task_predecessor) {
            $taskPre = Task::query()->with(['parent'])->where('id', '=', $task->task_predecessor)->first();
            $label = '';
            if ($taskPre->parent) {
                $item = $taskPre->parent;
                while ($item) {
                    $label .= $item->task_name . ' > ';
                    $item = $item->parent;
                }
            }
            $taskPre->label = $label. $taskPre->task_name;
            $detail['task_predecessor'] = $taskPre;
        }

        if ($task->task_parent) {

//            $taskParent = Task::query()->with(['parent'])->where('id', '=', $task->task_parent)->first();
//            $label = '';
//            if ($taskParent->parent) {
//                $item = $taskParent->parent;
//                while ($item) {
//                    $label = $item->task_name . ' > ' . $label;
//                    $item = $item->parent;
//                }
//            }
//            $taskParent->label = $label .$taskParent->task_name;

            $detail['task_parent'] = $task->task_parent;
        }

        if ($task->task_performer) {
            $detail['task_performer'] = User::find($task->task_performer);
        }

        if ($task->task_department) {
            $detail['task_department'] = [
                'value' => $task->task_department,
                'label' => Task::DEPARTMENTS[$task->task_department]
            ];
        }

        $userRelated = DB::table('users as u')->select('*')
            ->join('task_users as tu', 'u.id', '=', 'tu.user_id')
            ->where('tu.task_id', '=', $taskId)->get();

        return [
            'code' => 200,
            'data' => $detail,
            'user_related' => $userRelated
        ];
    }

    public function myTasks(Request $request) {
        $filters = $request->all();
        $taskPerformer = $request->input('task_performer');
        $Status2 = $request->input('status2');
        $startTime = $request->input('start_time');
        $endTime = $request->input('end_time');

        $builder = DB::table('tasks', 'tt')->select('tt.*')
            ->where('tt.valid','=',1);
            //->selectRaw('p.project_name');

        $builder//->join('projects as p', 'tt.project_id', '=', 'p.id')
        ->orderBy('start_time')
        ->where('task_performer', '=', Auth::id());
         if ($taskPerformer && $taskPerformer > 0) {
            $builder->where('tt.task_performer', '=', $taskPerformer);
        }

         if ($startTime && $startTime != '') {
            $builder->whereDate('tt.start_time', '=', $startTime);
        }
         if ($endTime && $endTime != '') {
            $builder->whereDate('tt.end_time', '=', $endTime);
        }

        if (isset($filters['project_id']) && $filters['project_id'] > 0) {
            $builder->where('project_id', '=', $filters['project_id']);
        }

        if (isset($filters['status'])) {
            $builder->where('status', '=', $filters['status']);
        }
        
        if ($Status2 == 1) {
            $builder->where('status', '!=', 4)->where('status', '!=', 5);
        }else if($Status2 == 2){
            $builder->where('status', '=', 4);
        }else if($Status2 == 5){
            $builder->where('status', '=', 5);
        }

        $tasks = $builder->get();

        $totalTaskProcessing = 0;
        $totalTaskPause = 0;
        $totalTaskComplete = 0;
         $totalWaitFeedback = 0;

        foreach ($tasks as $task) {
            if ($task->status == 0) {
                $task->status_title = 'Đã quá hạn';
            } elseif ($task->status == 1) {
                $task->status_title = 'Đang chờ';
            } elseif ($task->status == 2) {
                $task->status_title = 'Đang làm';
            } elseif ($task->status == 1) {
                $task->status_title = 'Tạm dừng';
            } elseif ($task->status == 4 && (((strtotime($task->real_end_time) - strtotime($task->end_time))/3600 - $task->time_pause) > $task->time )) {
                $task->status_title = 'Hoàn thành chậm';
            } elseif ($task->status == 4 && (((strtotime($task->real_end_time) - strtotime($task->end_time))/3600 - $task->time_pause) < $task->time )) {
                $task->status_title = 'Hoàn thành';
            } elseif ($task->status == 5) {
                $task->status_title = 'Chờ feedback';
            } elseif ($task->status == 6) {
                $task->status_title = 'Làm lại';
            } else {
                $task->status_title = $task->status >= 0 ? Task::ARR_STATUS[$task->status]: '';
            }

            switch ($task->status) {
                case 0:
                    $totalTaskProcessing++;
                    break;
                case 1:
                    $totalTaskProcessing++;
                    break;
                case 2:
                    $totalTaskProcessing++;
                    break;
                case 3:
                    $totalTaskPause++;
                    break;
                case 4:
                    $totalTaskComplete++;
                    break;
                case 5:
                    $totalWaitFeedback++;
                    break;
                case 6:
                    $totalTaskProcessing++;
                    break;
            }
            $task->time_real = 0;
            $task->time_now = date('Y-m-d', strtotime(now()));

            if ($task->status == 4 || $task->status == 3 || $task->status == 5 || $task->status == 6) {
                $task->time_real = $task->real_end_time?
                    round(((strtotime($task->real_end_time) - strtotime($task->real_start_time))/3600 - $task->time_pause), 2): 0;

            } else if ($task->status == 2){
                $task->time_real = round(((time() - strtotime($task->real_start_time))/3600 - $task->time_pause), 2);
            }
        }

        return [
            'code' => 200,
            'tasks' => $tasks,
            'summary' => [
                'total' => count($tasks),
                'total_processing' => $totalTaskProcessing,
                'total_pause' => $totalTaskPause,
                'total_complete' => $totalTaskComplete,
            ]
        ];
    }

    public function ListWork(Request $request) {
        $filters = $request->all();
        $projectId = $request->input('project_id');
        $startTime = $request->input('start_time');
        $endTime = $request->input('end_time');
        $search = $request->input('search');
        $taskPerformer = $request->input('task_performer');
        $taskDepartment = $request->input('task_department');
        $Status2 = $request->input('status2');
        //$department = Auth::user()->department;

        $builder = DB::table('tasks', 'tt')
            ->where('tt.valid', '=', 1)
            ->whereNotNull( 'tt.task_department')
            //->where('tt.task_department', '=', $department)
            ->select('tt.*')
            ->selectRaw("(SELECT count(t.id) total_child FROM tasks as t WHERE t.task_parent = tt.id) total_child");
            //->selectRaw('p.project_name, u.fullname');
        if( $Status2 != 10){
            $builder->join('projects as p', 'tt.project_id', '=', 'p.id');
        }
        $builder->join('users as u', 'tt.task_performer', '=', 'u.id', 'left');


        if ($startTime && $startTime != '') {
            $builder->whereDate('tt.start_time', '=', $startTime);
        }
         if ($endTime && $endTime != '') {
            $builder->whereDate('tt.end_time', '=', $endTime);
        }
        if ($projectId > 0) {
            $builder->where('tt.project_id', '=',$projectId);
        }

        if ($taskPerformer && $taskPerformer > 0) {
            $builder->where('tt.task_performer', '=', $taskPerformer);
        }

        if ($taskDepartment && $taskDepartment > 0) {
            $builder->where('tt.task_department', '=', $taskDepartment);
        }

        if (isset($filters['project_id']) && $filters['project_id'] > 0) {
            $builder->where('project_id', '=', $filters['project_id']);
        }

        if (isset($filters['departments']) && $filters['departments'] > 0) {
            $builder->where('task_department', '=', $filters['departments']);
        }
        if (isset($filters['performer'])) {
            $builder->where('task_performer', '=', $filters['performer']);
        }

        if (isset($filters['status'])) {
            $builder->where('status', '=', $filters['status']);
        }
         if ($search && $search != '') {
            $builder->where('task_name', 'LIKE', "%$search%");
        }
        if ($Status2 == 1) {
            $builder->where('status', '!=', 4)->where('status', '!=', 5);
        }else if($Status2 == 2){
            $builder->where('status', '=', 4);
        }else if($Status2 == 5){
            $builder->where('status', '=', 5);
        }else if($Status2 == 10){
            $builder->Where('task_parent', '=', null)->where('task_performer', '!=', null);
        }

        $tasks = $builder->get();

        $totalTaskProcessing = 0;
        $totalTaskPause = 0;
        $totalTaskComplete = 0;
        $totalWaitFeedback= 0;

        foreach ($tasks as $task) {
            $task->department_label = $task->task_department? Task::DEPARTMENTS[$task->task_department]: '';
            if ($task->status == 0) {
                $task->status_title = 'Đã quá hạn';
            } elseif ($task->status == 1) {
                $task->status_title = 'Đang chờ';
            } elseif ($task->status == 2) {
                $task->status_title = 'Đang làm';
            } elseif ($task->status == 1) {
                $task->status_title = 'Tạm dừng';
            } elseif ($task->status == 4 && (((strtotime($task->real_end_time) - strtotime($task->end_time))/3600 - $task->time_pause) > $task->time )) {
                $task->status_title = 'Hoàn thành chậm';
            } elseif ($task->status == 4 && (((strtotime($task->real_end_time) - strtotime($task->end_time))/3600 - $task->time_pause) < $task->time )) {
                $task->status_title = 'Hoàn thành';
            } elseif ($task->status == 5) {
                $task->status_title = 'Chờ feedback';
            } elseif ($task->status == 6) {
                $task->status_title = 'Làm lại';
            } else {
                $task->status_title = $task->status >= 0 ? Task::ARR_STATUS[$task->status]: '';
            }

            switch ($task->status) {
                case 0:
                    $totalTaskProcessing++;
                    break;
                case 1:
                    $totalTaskProcessing++;
                    break;
                case 2:
                    $totalTaskProcessing++;
                    break;
                case 3:
                    $totalTaskPause++;
                    break;
                case 4:
                    $totalTaskComplete++;
                    break;
                case 5:
                    $totalWaitFeedback++;
                    break;
                case 6:
                    $totalTaskProcessing++;
                    break;
            }
            $task->time_real = 0;

            if ($task->status == 4 || $task->status == 3 || $task->status == 5 || $task->status == 6) {
                $task->time_real = $task->real_end_time?
                    round(((strtotime($task->real_end_time) - strtotime($task->real_start_time))/3600 - $task->time_pause), 8): 0;
            } else if ($task->status == Task::TASK_PROCESSING){
                $task->time_real = round(((time() - strtotime($task->real_start_time))/3600 - $task->time_pause), 8);
            }
        }

        return [
            'code' => 200,
            'tasks' => $tasks,
            'summary' => [
                'total' => count($tasks),
                'total_processing' => $totalTaskProcessing,
                'total_pause' => $totalTaskPause,
                'total_complete' => $totalTaskComplete,
            ]
        ];
    }

	public function ListWorkDone(Request $request) {
         $filters = $request->all();
        $projectId = $request->input('project_id');
        $startTime = $request->input('start_time');
        $taskPerformer = $request->input('task_performer');
        $taskDepartment = $request->input('task_department');

        $builder = DB::table('tasks', 'tt')
			->where('tt.status', '=', 4)
			->where('tt.task_department', '!=', null)
			->where('tt.valid', '=', 1)
			->select('tt.*')
            ->selectRaw("(SELECT count(t.id) total_child FROM tasks as t WHERE t.task_parent = tt.id) total_child")
            ->selectRaw('p.project_name, u.fullname');
        $builder->join('projects as p', 'tt.project_id', '=', 'p.id');
        $builder->join('users as u', 'tt.task_performer', '=', 'u.id', 'left');

        //$builder = DB::table('tasks', 'tt')->select('tt.*')
        //   ->selectRaw('p.project_name');

        //$builder->join('projects as p', 'tt.project_id', '=', 'p.id');


        if ($startTime && $startTime != '') {
            $builder->whereDate('tt.start_time', '=', $startTime);
        }
        if ($projectId > 0) {
            $builder->where('tt.project_id', '=',$projectId);
        }

        if ($taskPerformer && $taskPerformer > 0) {
            $builder->where('tt.task_performer', '=', $taskPerformer);
        }

        if ($taskDepartment && $taskDepartment > 0) {
            $builder->where('tt.task_department', '=', $taskDepartment);
        }

        if (isset($filters['project_id']) && $filters['project_id'] > 0) {
            $builder->where('project_id', '=', $filters['project_id']);
        }

        if (isset($filters['departments']) && $filters['departments'] > 0) {
            $builder->where('task_department', '=', $filters['departments']);
        }

        if (isset($filters['status']) && $filters['status'] > 1) {
            $builder->where('status', '=', $filters['status']);
        }

        $tasks = $builder->get();

        $totalTaskProcessing = 0;
        $totalTaskPause = 0;
        $totalTaskComplete = 0;
        $totalWaitFeedback= 0;

        foreach ($tasks as $task) {
            $task->department_label = $task->task_department? Task::DEPARTMENTS[$task->task_department]: '';
            if (($task->status == 0 || $task->status == 1) && (strtotime($task->end_time) < time())) {
                $task->status_title = 'Nhanh chóng làm việc';
            }elseif ($task->status == 4 && ((strtotime($task->real_end_time) - strtotime($task->real_start_time))/3600 - $task->time_pause) < $task->time) {
                $task->status_title = 'Hoàn thành';
            }elseif ($task->status == 4 && ((strtotime($task->real_end_time) - strtotime($task->real_start_time))/3600 - $task->time_pause) > $task->time) {
                $task->status_title = 'Hoàn thành chậm';
            }elseif ($task->status == 5) {
                $task->status_title = 'Chờ feedback';
            }elseif ($task->status == 6) {
                $task->status_title = 'Làm lại';
            } else {
                $task->status_title = $task->status >= 0 ? Task::ARR_STATUS[$task->status]: '';
            }

            switch ($task->status) {
                case 0:
                    $totalTaskProcessing++;
                    break;
                case 1:
                    $totalTaskProcessing++;
                    break;
                case 2:
                    $totalTaskProcessing++;
                    break;
                case 3:
                    $totalTaskPause++;
                    break;
                case 4:
                    $totalTaskComplete++;
                    break;
                case 5:
                    $totalWaitFeedback++;
                    break;
                case 6:
                    $totalTaskProcessing++;
                    break;
            }
            $task->time_real = 0;

            if ($task->status == 4 || $task->status == 3 || $task->status == 5 || $task->status == 6) {
                $task->time_real = $task->real_end_time?
                    round(((strtotime($task->real_end_time) - strtotime($task->real_start_time))/3600 - $task->time_pause), 8): 0;
            } else if ($task->status == Task::TASK_PROCESSING){
                $task->time_real = round(((time() - strtotime($task->real_start_time))/3600 - $task->time_pause), 8);
            }
        }

        return [
            'code' => 200,
            'tasks' => $tasks,
            'summary' => [
                'total' => count($tasks),
                'total_processing' => $totalTaskProcessing,
                'total_pause' => $totalTaskPause,
                'total_complete' => $totalTaskComplete,
            ]
        ];
    }

    public function listTasks(Request $request) {
        $filters = $request->all();

        $builder = DB::table('tasks', 'tt')->select('tt.*')
            ->selectRaw('p.project_name');

        $builder->join('projects as p', 'tt.project_id', '=', 'p.id');

        if (isset($filters['project_id']) && $filters['project_id'] > 0) {
            $builder->where('project_id', '=', $filters['project_id']);
        }

        if (isset($filters['status']) && $filters['status'] > 1) {
            $builder->where('status', '=', $filters['status']);
        }

        $tasks = $builder->get();

        $totalTaskProcessing = 0;
        $totalTaskPause = 0;
        $totalTaskComplete = 0;
        $totalWaitFeedback= 0;

        foreach ($tasks as $task) {
            if (($task->status == 0 || $task->status == 1) && (strtotime($task->end_time) < time())) {
                $task->status_title = 'Đã quá hạn';
            } elseif ($task->status == 4 && (strtotime($task->real_end_time) > strtotime($task->end_time))) {
                $task->status_title = 'Hoàn thành chậm';
            } else {
                $task->status_title = $task->status >= 0 ? Task::ARR_STATUS[$task->status]: '';
            }

            switch ($task->status) {
                case 0:
                    $totalTaskProcessing++;
                    break;
                case 1:
                    $totalTaskProcessing++;
                    break;
                case 2:
                    $totalTaskProcessing++;
                    break;
                case 3:
                    $totalTaskPause++;
                    break;
                case 4:
                    $totalTaskComplete++;
                    break;
                case 5:
                    $totalWaitFeedback++;
                    break;
                case 6:
                    $totalTaskProcessing++;
                    break;
            }
            $task->time_real = 0;

            if ($task->status == Task::TASK_COMPLETED || $task->status == Task::TASK_PAUSE) {
                $task->time_real = $task->real_end_time?
                    round(((strtotime($task->real_end_time) - strtotime($task->real_start_time))/3600 - $task->time_pause), 4): 0;
            } else if ($task->status == Task::TASK_PROCESSING){
                $task->time_real = round((time() - strtotime($task->real_start_time))/3600, 2) - $task->time_pause;
            }
        }

        return [
            'code' => 200,
            'tasks' => $tasks,
            'summary' => [
                'total' => count($tasks),
                'total_processing' => $totalTaskProcessing,
                'total_pause' => $totalTaskPause,
                'total_complete' => $totalTaskComplete,
            ]
        ];
    }

    public function changeStatus($taskId, Request $request) {
        $status = $request->input('status');

        $task = Task::find($taskId);

        if($status == 5){
            $task->progress  = 100;
        }

        switch ($task->status) {
            case 0:
                $task->status = $status;
                break;
            case 1:
                $task->status = $status;
                $task->real_start_time = date('Y-m-d H:i:s', time());
                break;
            case 2:
                $task->status = $status;
                $task->real_end_time = date('Y-m-d H:i:s', time());
                /*if ($status == 4) {
                    Task::query()->where('task_predecessor', '=', $taskId)->update([
                        'status' => 1
                    ]);
                }*/
                break;
            case 3:
                $task->status = $status;
                $pause = (time() - strtotime($task->real_end_time))/3600;
                $task->time_pause += $pause;
                $task->real_end_time = date('Y-m-d H:i:s', time());
                break;
            case 4:
                $task->status = $status;
                $pause = (time() - strtotime($task->real_end_time))/3600;
                $task->time_pause += $pause;
                $task->real_end_time = date('Y-m-d H:i:s', time());
                break;
            case 5:
                $task->status = $status;
                $pause = (time() - strtotime($task->real_end_time))/3600;
                $task->time_pause += $pause;
                $task->real_end_time = date('Y-m-d H:i:s', time());
                break;
            case 6:
                $task->status = $status;
                $pause = (time() - strtotime($task->real_end_time))/3600;
                $task->time_pause += $pause;
                $task->real_end_time = date('Y-m-d H:i:s', time());
                break;
        }

        $task->save();

        return [
            'code' => 200,
            'message' => 'Cập nhật thành công'
        ];
    }

	public function changeProgress($taskId, Request $request) {
        $progress = $request->input('progress');

        $task = Task::find($taskId);

        $task->progress = $progress;

        if($progress == 100){
            $task->status = 5;
        }

        $task->save();

        return [
            'code' => 200,
            'message' => 'Cập nhật thành công'
        ];
    }

    public function changeTaskName($taskId, Request $request) {
        $task_name = $request->input('task_name');

        $task = Task::find($taskId);

        $task->task_name = $task_name;

        $task->save();

        return [
            'code' => 200,
            'message' => 'Cập nhật thành công'
        ];
    }
    public function changeDescription($taskId, Request $request) {
        $description = $request->input('description');

        $task = Task::find($taskId);

        $task->description = $description;

        $task->save();

        return [
            'code' => 200,
            'message' => 'Cập nhật thành công'
        ];
    }
    public function changeTime($taskId, Request $request) {
        $time = $request->input('time');

        $task = Task::find($taskId);

        $task->time = $time;

        $task->save();

        return [
            'code' => 200,
            'message' => 'Cập nhật thành công'
        ];
    }
	public function changePause($taskId, Request $request) {
        $pause = $request->input('time_pause');

        $task = Task::find($taskId);

        $task->time_pause = $pause;
        
        $task->save();

        return [
            'code' => 200,
            'message' => 'Cập nhật thành công'
        ];
    }
    public function changeStartTime($taskId, Request $request) {
        $start_time = $request->input('start_time');

        $task = Task::find($taskId);

        $task->start_time = $start_time;

        $task->save();

        return [
            'code' => 200,
            'message' => 'Cập nhật thành công'
        ];
    }
    public function changeEndTime($taskId, Request $request) {
        $end_time = $request->input('end_time');

        $task = Task::find($taskId);

        $task->end_time = $end_time;

        $task->save();

        return [
            'code' => 200,
            'message' => 'Cập nhật thành công'
        ];
    }
    public function changeRealTime($taskId, Request $request) {
        $real_time = $request->input('real_time');

        $task = Task::find($taskId);

        $task->real_time = $real_time;

        $task->save();

        return [
            'code' => 200,
            'message' => 'Cập nhật thành công'
        ];
    }
    public function changeDepartment($taskId, Request $request) {
        $task_department = $request->input('task_department');

        $task = Task::find($taskId);

        $task->task_department = $task_department;

        $task->save();

        return [
            'code' => 200,
            'message' => 'Cập nhật thành công'
        ];
    }
    public function changeProject($taskId, Request $request) {
        $task_project = $request->input('project_id');

        $task = Task::find($taskId);

        $task->project_id = $task_project;

        $task->save();

        return [
            'code' => 200,
            'message' => 'Cập nhật thành công'
        ];
    }
    public function changeTaskParent($taskId, Request $request) {
        
        $task_parent = $request->input('task_parent');

        $task = Task::find($taskId);

        $task->task_parent = $task_parent;

        $task->save();

        return [
            'code' => 200,
            'message' => 'Cập nhật thành công'
        ];
    }
    public function changePerformer($taskId, Request $request) {
        $task_performer = $request->input('task_performer');;

        $task = Task::find($taskId);

        $task->task_performer = $task_performer;

        $task->save();

        return [
            'code' => 200,
            'message' => 'Cập nhật thành công'
        ];
    }
    public function changeWeight($taskId, Request $request) {
        $weight = $request->input('weight');;

        $task = Task::find($taskId);

        $task->weight = $weight;

        $task->save();

        return [
            'code' => 200,
            'message' => 'Cập nhật thành công'
        ];
    }
	public function changeSticker($taskId, Request $request) {
        $sticker = $request->input('task_sticker');

        $task = Task::find($taskId);
        
        $priority = 'level_'.$task->task_priority;
		if($task->task_priority != null){
			$weight = DB::table('stickers as s')->where('sticker_name', '=', $sticker)->value($priority);
		}else{
			$weight = 0;
		}
        $task->task_sticker = $sticker;

        $task->weight = $weight;
     
        $task->save();

        return [
            'code' => 200,
            'message' => 'Cập nhật thành công'
        ];
    }
    public function changePriority($taskId, Request $request) {
        $priority = $request->input('task_priority');

        $task = Task::find($taskId);

        $sticker = $task->task_sticker;

        $priority_level='level_'.$priority;

        $weight = DB::table('stickers as s')->where('sticker_name', '=', $sticker)->value($priority_level);

        $task->task_priority = $priority;
        
        $task->weight = $weight;

        $task->save();

        return [
            'code' => 200,
            'message' => 'Cập nhật thành công'
        ];

    }

    public function getReport(Request $request) {

        $filter = $request->all();
        $department = $filter['task_department']? explode(',', $filter['task_department']): [];
        $project = $filter['project_id']? explode(',', $filter['project_id']): [];
        $taskSummaryQuery = DB::table('tasks as t')->where('valid','=', '1')
           ->selectRaw("COUNT(t.id)")
            //->selectRaw("COUNT(t.task_department) total_task")
            ->selectRaw("SUM(IF (t.task_department != '', 1, 0)) total_task")
            ->selectRaw("SUM(IF (t.status = 1 AND t.task_performer > 0, 1, 0)) total_wait")
            ->selectRaw("SUM(IF (t.status = 2, 1, 0)) total_processing ")
            ->selectRaw("SUM(IF (t.status = 3, 1, 0)) total_pause")
            ->selectRaw("SUM(IF (t.status = 4 , 1, 0)) total_complete")
            ->selectRaw("SUM(IF (t.status = 4 AND (((t.real_end_time - t.real_start_time)/3600 - t.time_pause)>t.time), 1, 0)) total_complete_slow")
            ->selectRaw("SUM(IF (t.status = 0  AND (t.task_performer > 0), 1, 0)) total_slow")
            ->selectRaw("SUM(IF (t.status = 5  AND (t.task_performer > 0), 1, 0)) total_wait_fb")
            ->selectRaw("SUM(IF (t.status = 6  AND (t.task_performer > 0), 1, 0)) total_again")
            ->selectRaw("SUM(t.weight) total_weight")
            ->selectRaw("SUM(IF (t.status = 4, t.weight, 0)) total_weight_complete")


            ->selectRaw("SUM(IF (t.task_department = 2, 1, 0)) total_task_dev")
            ->selectRaw("SUM(IF (t.task_department = 2 && t.status = 1 AND t.task_performer > 0, 1, 0)) total_wait_dev")
            ->selectRaw("SUM(IF (t.task_department = 2 && t.status = 2, 1, 0)) total_processing_dev ")
            ->selectRaw("SUM(IF (t.task_department = 2 && t.status = 3, 1, 0)) total_pause_dev")
            ->selectRaw("SUM(IF (t.task_department = 2 && t.status = 4 , 1, 0)) total_complete_dev")
            ->selectRaw("SUM(IF (t.task_department = 2 && t.status = 4 AND (((t.real_end_time - t.real_start_time)/3600 - t.time_pause)>t.time), 1, 0)) total_complete_slow_dev")
            ->selectRaw("SUM(IF (t.task_department = 2 && (t.status = 0) AND t.task_performer > 0 , 1, 0)) total_slow_dev")
            ->selectRaw("SUM(IF (t.task_department = 2 && t.status = 5  AND (t.task_performer > 0), 1, 0)) total_wait_fb_dev")
            ->selectRaw("SUM(IF (t.task_department = 2 && t.status = 6  AND (t.task_performer > 0), 1, 0)) total_again_dev")
            ->selectRaw("SUM(IF (t.task_department = 2, t.weight, 0)) total_weight_dev")
            ->selectRaw("SUM(IF (t.task_department = 2 && t.status = 4, t.weight, 0)) total_weight_dev_complete")


            ->selectRaw("SUM(IF (t.task_department = 3, 1, 0)) total_task_gd")
            ->selectRaw("SUM(IF (t.task_department = 3 && t.status = 1 AND t.task_performer > 0, 1, 0)) total_wait_gd")
            ->selectRaw("SUM(IF (t.task_department = 3 && t.status = 2, 1, 0)) total_processing_gd ")
            ->selectRaw("SUM(IF (t.task_department = 3 && t.status = 3, 1, 0)) total_pause_gd")
            ->selectRaw("SUM(IF (t.task_department = 3 && t.status = 4 , 1, 0)) total_complete_gd")
            ->selectRaw("SUM(IF (t.task_department = 3 && t.status = 4 AND (((t.real_end_time - t.real_start_time)/3600 - t.time_pause)>t.time), 1, 0)) total_complete_slow_gd")
            ->selectRaw("SUM(IF (t.task_department = 3 && (t.status = 0 OR t.status = 1) AND t.task_performer > 0  AND ((t.real_end_time - t.real_start_time) > t.time), 1, 0)) total_slow_gd")
            ->selectRaw("SUM(IF (t.task_department = 3 && t.status = 5  AND (t.task_performer > 0), 1, 0)) total_wait_fb_gd")
            ->selectRaw("SUM(IF (t.task_department = 3 && t.status = 6  AND (t.task_performer > 0), 1, 0)) total_again_gd")
            ->selectRaw("SUM(IF (t.task_department = 3, t.weight, 0)) total_weight_gd")
            ->selectRaw("SUM(IF (t.task_department = 3 && t.status = 4, t.weight, 0)) total_weight_gd_complete")


            ->selectRaw("SUM(IF (t.task_department = 4, 1, 0)) total_task_art")
            ->selectRaw("SUM(IF (t.task_department = 4 && t.status = 1 AND t.task_performer > 0, 1, 0)) total_wait_art")
            ->selectRaw("SUM(IF (t.task_department = 4 && t.status = 2, 1, 0)) total_processing_art ")
            ->selectRaw("SUM(IF (t.task_department = 4 && t.status = 3, 1, 0)) total_pause_art")
            ->selectRaw("SUM(IF (t.task_department = 4 && t.status = 4 , 1, 0)) total_complete_art")
            ->selectRaw("SUM(IF (t.task_department = 4 && t.status = 4 AND (((t.real_end_time - t.real_start_time)/3600 - t.time_pause)>t.time), 1, 0)) total_complete_slow_art")
            ->selectRaw("SUM(IF (t.task_department = 4 && (t.status = 0 OR t.status = 1) AND t.task_performer > 0  AND ((t.real_end_time - t.real_start_time) > t.time), 1, 0)) total_slow_art")
            ->selectRaw("SUM(IF (t.task_department = 4 && t.status = 5  AND (t.task_performer > 0), 1, 0)) total_wait_fb_art")
            ->selectRaw("SUM(IF (t.task_department = 4 && t.status = 6  AND (t.task_performer > 0), 1, 0)) total_again_art")
            ->selectRaw("SUM(IF (t.task_department = 4, t.weight, 0)) total_weight_art")
            ->selectRaw("SUM(IF (t.task_department = 4 && t.status = 4, t.weight, 0)) total_weight_art_complete")


            ->selectRaw("SUM(IF (t.task_department = 5, 1, 0)) total_task_test")
            ->selectRaw("SUM(IF (t.task_department = 5 && t.status = 1 AND t.task_performer > 0, 1, 0)) total_wait_test")
            ->selectRaw("SUM(IF (t.task_department = 5 && t.status = 2, 1, 0)) total_processing_test ")
            ->selectRaw("SUM(IF (t.task_department = 5 && t.status = 3, 1, 0)) total_pause_test")
            ->selectRaw("SUM(IF (t.task_department = 5 && t.status = 4 , 1, 0)) total_complete_test")
            ->selectRaw("SUM(IF (t.task_department = 5 && t.status = 4 AND (((t.real_end_time - t.real_start_time)/3600 - t.time_pause)>t.time), 1, 0)) total_complete_slow_test")
            ->selectRaw("SUM(IF (t.task_department = 5 && (t.status = 0 OR t.status = 1) AND t.task_performer > 0  AND ((t.real_end_time - t.real_start_time) > t.time), 1, 0)) total_slow_test")
            ->selectRaw("SUM(IF (t.task_department = 5 && t.status = 5  AND (t.task_performer > 0), 1, 0)) total_wait_fb_test")
            ->selectRaw("SUM(IF (t.task_department = 5 && t.status = 6  AND (t.task_performer > 0), 1, 0)) total_again_test")
            ->selectRaw("SUM(IF (t.task_department = 5, t.weight, 0)) total_weight_test")
            ->selectRaw("SUM(IF (t.task_department = 5 && t.status = 4, t.weight, 0)) total_weight_test_complete");


        $usersQuery = User::query();

        $summaryQuery = DB::table('tasks as t')->select('t.task_department')
            ->selectRaw("SUM(t.weight) total_weight")
            ->selectRaw("COUNT(t.id) total_task");


        if (isset($filter['search']) && $filter['search']) {
            $search = $filter['search'];
            $taskSummaryQuery->join('users as u', 't.task_performer', '=', 'u.id');
            $taskSummaryQuery->where('u.fullname', 'LIKE', "%$search%");
            $summaryQuery->join('users as u', 't.task_performer', '=', 'u.id');
            $summaryQuery->where('u.fullname', 'LIKE', "%$search%");

            $usersQuery->where('fullname', 'LIKE', "%$search%");
        }

        if(isset($filter['start_date']) && $filter['end_date']){

            $taskSummaryQuery->where('t.start_time', '>=', $filter['start_date'])->where('t.end_time', '<=', $filter['end_date']);

            $summaryQuery->where('t.start_time', '>=', $filter['start_date'])->where('t.end_time', '<=', $filter['end_date']);
        }


        if (isset($filter['project_id']) && $filter['project_id']) {

            $taskSummaryQuery->whereIn('t.project_id', $project);

            $summaryQuery->whereIn('t.project_id', $project);
        }

        if (isset($filter['task_department']) && $filter['task_department']) {

            //$taskSummaryQuery->whereIn('t.task_department', $department);

            $summaryQuery->whereIn('t.task_department', $department);
        }

//        if (isset($filter['start_date']) && $filter['start_date'] &&isset($filter['end_date']) && $filter['end_date']) {
//
//            $taskSummaryQuery->where('t.project_id', '=', $filter['project_id']);
//
//            $summaryQuery->where('t.project_id', '=', $filter['project_id']);
//        }
        $taskSummaryQuery->where('valid', '=', 1);
        $taskSummary = $taskSummaryQuery->first();

        $users = $usersQuery->with(['task' => function ($q) use ($filter, $project, $department) {
            if (isset($filter['project_id']) && $filter['project_id']) {
                $q->whereIn('project_id', $project);
            }
            if (isset($filter['task_department']) && $filter['task_department']) {
                $q->whereIn('task_department', $department);
            }
             if(isset($filter['start_date']) && isset($filter['end_date'])){

             $q->where('start_time', '>=', $filter['start_date'])->where('end_time', '<=', $filter['end_date']);
            }

            $q->where('valid', '=', 1);

        }])->get();
        $summaryQuery->where('valid', '=', 1);
        $summary = $summaryQuery->groupBy('task_department')
            ->get();

        $result = [];
        $resSummary = [];
        foreach ($summary as $val) {
            if ($val->task_department) {
                $resSummary[Task::DEPARTMENTS[$val->task_department]] = [
                    'id' => $val->task_department,
                    'department' => Task::DEPARTMENTS[$val->task_department],
                    'total_task' => $val->total_task,
                    'total_weight' => $val->total_weight,
                ];
            }

        }

        foreach ($users as $user) {

            if ($user->task && count($user->task) > 0) {
                $totalTask = 0;
                $totalComplete = 0;
                $totalProcessing = 0;
                $totalWait = 0;
                $totalPause = 0;
                $totalWaitFb = 0;
                $totalAgain = 0;
                $totalCompleteSlow = 0;
                $totalSlow = 0;
                $totalWeight = 0;
                foreach ($user->task as $value) {
                    $totalTask++;
                    if($value->status == 4){
                        $totalWeight += $value->weight;
                    }
                    if ($value->status == 0 ) {
                        $totalSlow++ ;
                    }
                    if ($value->status == 1) {
                        $totalWait++;
                    }
                    if ($value->status == 2) {
                        $totalProcessing++;
                    }
                    if ($value->status == 3) {
                        $totalPause++;
                    }
                    if ($value->status == 4) {
                        $totalComplete++;
                    }
                    if ($value->status == 5) {
                        $totalWaitFb++;
                    }
                    if ($value->status == 6) {
                        $totalAgain++;
                    }
                    if ($value->status == 4 && ((strtotime($value->real_end_time) - strtotime($value->real_start_time)) > ($value->time)*3600)) {
                        $totalCompleteSlow++ ;
                    }
                }

                $rateWeight = 0;
                if (isset($resSummary[$user->department]) && $resSummary[$user->department]['total_weight'] > 0) {
                    $rateWeight = round($totalWeight/$resSummary[$user->department]['total_weight'], 4)*100;
                }

                $result[] = [
                    'id' => $user->id,
                    'user_name' => $user->fullname,
                    'department' => $user->department,
                    'total_task' => $totalTask,
                    'total_complete' => $totalComplete,
                    'total_processing' => $totalProcessing,
                    'total_pause' => $totalPause,
                    'total_wait' => $totalWait,
                    'total_wait_fb' => $totalWaitFb,
                    'total_again' => $totalAgain,
                    'total_complete_slow' => $totalCompleteSlow,
                    'total_slow' => $totalSlow,
                    'total_weight' => $totalWeight,
                    'rate_weight' => $rateWeight,
                ];
            }
        }

        return [
            'code' => 200,
            'data' => $result,
            'summary' => $resSummary,
            'task_summary' => $taskSummary
        ];
    }

    public function copy($taskId) {
        $task = Task::query()->with(['children' => function ($q) {
            $q->where('valid', '=', 1);
        }])->where('id', '=', $taskId)->first();

        $newTaskId = Task::taskChildrent([$task], $task->task_parent);

        $tasks = Task::query()->with(['parent'])->where('id', '=', $taskId)->get();
        $arrParent = [];
        foreach ($tasks as $value) {
            if ($value->parent) {
                $item = $value->parent;
                while ($item) {
                    $arrParent[] = $item->id;
                    $item = $item->parent;
                }
            }
        }

        $newTasks = Task::query()->with(['taskUser'])->where('id', '=', $newTaskId)->first();
        if ($newTasks) {
            $newTasks->department_label = $newTasks->task_department? Task::DEPARTMENTS[$newTasks->task_department]: '';

            if (($newTasks->status == 0 || $newTasks->status == 1) && (strtotime($newTasks->end_time) < time())) {
                $newTasks->status_title = 'Đã quá hạn';
            } elseif ($newTasks->status == 4 && (strtotime($newTasks->real_end_time) > strtotime($newTasks->end_time))) {
                $newTasks->status_title = 'Hoàn thành chậm';
            } else {
                $newTasks->status_title = $newTasks->status >= 0 ? Task::ARR_STATUS[$newTasks->status]: '';
            }
            $newTasks->fullname = $newTasks->taskUser? $newTasks->taskUser->fullname: '';
            $totalChild = Task::query()->where('task_parent', '=', $newTaskId)->count();
            $newTasks->_hasChildren = $totalChild > 0;
            $newTasks->_children = [];
        }

        return [
            'code' => 200,
            'message' => 'Copy thành công',
            'new_task' => $newTasks,
            'arr_parent' => array_reverse($arrParent),
        ];
    }

    public function new_task(Request $request) {

        $task = new Task();

        $userId = Auth::user()->id;
        $Department = Auth::user()->department;

        $task->task_name = 'Click để thay đổi nội dung';
        $task->task_code ='';
        $task->start_time = date('Y-m-d', strtotime(now()));
        $task->time =null;
        $task->end_time =null;
        $task->description = '';
        $task->task_priority = null;
        $task->task_sticker = null;
        $task->task_department = $Department;
        $task->weight = null;
        $task->project_id = null;
        $task->task_predecessor = null;
        $task->task_parent = null;
        $task->task_performer = $userId;
        $task->status= 1;
        $task->project_id = 1;

        $task->save();

        $newTasks = Task::query()->with(['taskUser'])->where('id', '=', $task->id)->first();

        return [
            'code' => 200,
            'message' => 'Thêm mới thành công',
            'new_task' => $newTasks,
        ];
    }

    public function invalidTasks() {
        $users = User::all();
        $petitions1 = Petition::where('petition_status', 1)->get();
		$userId = Auth::user()->id;
		$petitions01 = Petition::where('petition_status', 1)->where('user_id', '=', $userId)->get();
        return view('projects.invalid_tasks', compact('users','petitions1', 'petitions01'));
    }

    public function invalid(Request $request) {
        $builder = DB::table('tasks', 'tt')->select('tt.*')
            ->selectRaw('p.project_name, u.fullname, ud.fullname as d_fullname');
        $builder->join('projects as p', 'tt.project_id', '=', 'p.id');
        $builder->join('users as u', 'tt.task_performer', '=', 'u.id', 'left');
        $builder->join('users as ud', 'tt.deleted_by', '=', 'ud.id', 'left');
        $builder->where('tt.valid', '=', 0);


        $tasks = $builder->get();

        foreach ($tasks as $key => $value) {
            $value->department_label = $value->task_department ? Task::DEPARTMENTS[$value->task_department] : '';
        }

        return [
            'code' => 200,
            'data' => $tasks
        ];
    }

    public function invalidDelete($taskId, Request $request) {
        $tasks = Task::query()->with(['childrenInvalid'])->where('id', '=', $taskId)->first();
        Task::deleteTaskChildren([$tasks]);
        return [
            'code' => 200,
            'message' => 'Thành công'
        ];
    }
    public function DeleteTask($taskId, Request $request) {
        $tasks = Task::query()->where('id', '=', $taskId)->first();
        $tasks->delete();
        return [
            'code' => 200,
            'message' => 'Thành công'
        ];
    }

    public function restore($taskId, Request $request) {
        $tasks = Task::query()->with(['childrenInvalid'])->where('id', '=', $taskId)->first();

        $hasParent = false;

        if ($tasks->task_parent > 0) {
            $taskParent = Task::query()
                ->where('id', '=', $tasks->task_parent)
                ->where('valid', '=', 1)->first();

            if ($taskParent) {
                $hasParent = true;
            }
        }



        Task::taskChildrenValid([$tasks], $hasParent);
        return [
            'code' => 200,
            'message' => 'Thành công'
        ];
    }
}
