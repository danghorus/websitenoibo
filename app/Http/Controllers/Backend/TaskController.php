<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
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
            } else {
                $value->status_title = $value->status >= 0 ? Task::ARR_STATUS[$value->status]: '';
            }
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

        return [
            'code' => 200,
            'message' => 'Thêm mới thành công',
        ];
    }

    public function update($taskId, Request $request) {
        $data = $request->all();

        $taskInfo = $data['task'];

        $task = Task::find($taskId);

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

        return [
            'code' => 200,
            'message' => 'Cập nhật thành công',
        ];
    }

    public function getAll(Request $request) {
        $projectId = $request->input('project_id');
        $taskParent = $request->input('task_parent');
        $taskId = $request->input('task_id');

        $builder = Task::query()
            ->where('project_id', '=', $projectId);
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
            $taskParent = Task::query()->with(['children'])->where('id', '=', $id)->first();
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

        Task::query()->where('id', '=', $taskId)->delete();
        TaskUser::query()->where('task_id', '=', $taskId)->delete();

        if ($task) {
            Task::query()->where('task_parent', '=', $taskId)
                ->update([
                    'task_parent' => $task->task_parent
                ]);
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

        $builder = DB::table('tasks', 'tt')->select('tt.*')
            ->selectRaw('p.project_name');

        $builder->join('projects as p', 'tt.project_id', '=', 'p.id')
            ->where('task_performer', '=', Auth::id());
         if ($taskPerformer && $taskPerformer > 0) {
            $builder->where('tt.task_performer', '=', $taskPerformer);
        }

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
         $totalWaitFeedback = 0;

        foreach ($tasks as $task) {
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
        $projectId = $request->input('project_id');
        $startTime = $request->input('start_time');
        $taskPerformer = $request->input('task_performer');
        $taskDepartment = $request->input('task_department');

        $builder = DB::table('tasks', 'tt')->select('tt.*')
            ->selectRaw("(SELECT count(t.id) total_child FROM tasks as t WHERE t.task_parent = tt.id) total_child")
            ->selectRaw('p.project_name, u.fullname');
        $builder->join('projects as p', 'tt.project_id', '=', 'p.id');
        $builder->join('users as u', 'tt.task_performer', '=', 'u.id', 'left');

        $builder = DB::table('tasks', 'tt')->select('tt.*')
            ->selectRaw('p.project_name');

        $builder->join('projects as p', 'tt.project_id', '=', 'p.id');


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

    public function getReport(Request $request) {

        $filter = $request->all();
        $department = $filter['task_department']? explode(',', $filter['task_department']): [];
        $project = $filter['project_id']? explode(',', $filter['project_id']): [];
        $taskSummaryQuery = DB::table('tasks as t')
            ->selectRaw("COUNT(t.id)")
            ->selectRaw("COUNT(t.task_performer) total_task")
            ->selectRaw("SUM(IF (t.status = 1 AND t.task_performer > 0, 1, 0)) total_wait")
            ->selectRaw("SUM(IF (t.status = 2, 1, 0)) total_processing ")
            ->selectRaw("SUM(IF (t.status = 3, 1, 0)) total_pause")
            ->selectRaw("SUM(IF (t.status = 4 AND t.real_end_time < t.start_time, 1, 0)) total_complete")
            ->selectRaw("SUM(IF (t.status = 4 AND t.real_end_time > t.start_time, 1, 0)) total_complete_slow")
            ->selectRaw("SUM(IF ((t.status = 0 OR t.status = 1) AND t.task_performer > 0  AND ((t.real_end_time - t.real_start_time) > t.time), 1, 0)) total_slow");

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


        if (isset($filter['project_id']) && $filter['project_id']) {

            $taskSummaryQuery->whereIn('t.project_id', $project);

            $summaryQuery->whereIn('t.project_id', $project);
        }

        if (isset($filter['task_department']) && $filter['task_department']) {

            $taskSummaryQuery->whereIn('t.task_department', $department);

            $summaryQuery->whereIn('t.task_department', $department);
        }

//        if (isset($filter['start_date']) && $filter['start_date'] &&isset($filter['end_date']) && $filter['end_date']) {
//
//            $taskSummaryQuery->where('t.project_id', '=', $filter['project_id']);
//
//            $summaryQuery->where('t.project_id', '=', $filter['project_id']);
//        }
        $taskSummary = $taskSummaryQuery->first();

        $users = $usersQuery->with(['task' => function ($q) use ($filter, $project, $department) {
            if (isset($filter['project_id']) && $filter['project_id']) {
                $q->whereIn('project_id', $project);
            }
            if (isset($filter['task_department']) && $filter['task_department']) {
                $q->whereIn('task_department', $department);
            }
        }])->get();

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
                $totalCompleteSlow = 0;
                $totalSlow = 0;
                $totalWeight = 0;
                foreach ($user->task as $value) {
                    $totalTask++;
                    if($value->status == 4){
                        $totalWeight += $value->weight;
                    }
                    if ($value->status == 4) {
                        $totalComplete++;
                    }
                    if ($value->status == 2) {
                        $totalProcessing++;
                    }
                    if ($value->status == 0 || $value->status == 1) {
                        $totalWait++;
                    }
                    if ($value->status == 4 && ((strtotime($value->real_end_time) - strtotime($value->real_start_time)) > ($value->time)*3600)) {
                        $totalCompleteSlow++;
                    }
                    if (($value->status == 0 || $value->status == 1) && (strtotime($value->real_end_time - $value->real_start_time) > $value->time)) {
                        $totalSlow++;
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
                    'total_wait' => $totalWait,
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
        $task = Task::query()->with(['children'])->where('id', '=', $taskId)->first();

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

        $newTasks = Task::query()->with(['children', 'taskUser'])->where('id', '=', $newTaskId)->first();
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
            'arr_parent' => $arrParent,
        ];
    }

}
