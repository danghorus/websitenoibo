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
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    public function index(Request $request) {
        $projectId = $request->input('project_id');
        $taskParent = $request->input('parent_task');
        $builder = DB::table('tasks', 'tt')->select('tt.*')
            ->selectRaw("(SELECT count(t.id) total_child FROM tasks as t WHERE t.task_parent = tt.id) total_child")
            ->selectRaw('p.project_name, u.fullname');
        $builder->join('projects as p', 'tt.project_id', '=', 'p.id');
        $builder->join('users as u', 'tt.task_performer', '=', 'u.id', 'left');
        $builder->where('tt.project_id', '=',$projectId);

        if ($taskParent) {
            $builder->where('tt.task_parent', '=', $taskParent);
        } else {
            $builder->where('tt.task_parent', '=', NULL);
        }

        $tasks = $builder->get();

        foreach ($tasks as $task) {
            $task->children = [];
            $task->department_label = Task::DEPARTMENTS[$task->task_department];
//            $tmp = '';
//            if ($task->level && $task->level > 1) {
//                for ($i = 1; $i < $task->level; $i++){
//                    if ($i == 1) {
//                        $tmp .= '|--';
//                    } else {
//                        $tmp .= '--';
//                    }
//                }
//                $task->task_name = $tmp. ' '.$task->task_name;
//            }
        }

        return [
            'code' => 200,
            'data' => $tasks
        ];
    }

    public function timeline(Request $request) {

        $projectId = $request->input('project_id');
        $startTime = $request->input('start_time');
        $search = $request->input('search');
        $taskPerformer = $request->input('task_performer');
        $taskDepartment = $request->input('task_department');

        $builder = DB::table('tasks', 'tt')->select('tt.*')
            ->selectRaw("(SELECT count(t.id) total_child FROM tasks as t WHERE t.task_parent = tt.id) total_child")
            ->selectRaw('p.project_name, u.fullname');
        $builder->join('projects as p', 'tt.project_id', '=', 'p.id');
        $builder->join('users as u', 'tt.task_performer', '=', 'u.id');

        if ($projectId > 0) {
            $builder->where('tt.project_id', '=',$projectId);
        }

        if ($startTime && $startTime != '') {
            $builder->whereDate('tt.start_time', '=', $startTime);
        } else {
            $builder->whereDate('tt.start_time', '<=', date('Y-m-d', time()));
            $builder->whereDate('tt.end_time', '>=', date('Y-m-d', time()));
        }

        if ($taskPerformer && $taskPerformer > 0) {
            $builder->where('tt.task_performer', '=', $taskPerformer);
        }

        if ($taskDepartment && $taskDepartment > 0) {
            $builder->where('tt.task_department', '=', $taskDepartment);
        }

        if ($search && $search != '') {
            $builder->where('tt.task_name', 'LIKE', "%$search%");
        }

        $tasks = $builder->get();

        foreach ($tasks as $task) {
            $task->department_label = Task::DEPARTMENTS[$task->task_department];
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
        $task->start_time = $taskInfo['start_time'] ?? date('Y-m-d H:i:s', time());
        $task->time = $taskInfo['time'] ?? 0;
        $task->end_time = $taskInfo['end_time'] ?? date('Y-m-d H:i:s', time());
        $task->description = $taskInfo['description'] ?? '';
        $task->task_priority = isset($taskInfo['task_priority']) && $taskInfo['task_priority'] ? $taskInfo['task_priority']['id']: null;
        $task->task_sticker = isset($taskInfo['task_sticker']) && $taskInfo['task_sticker'] ?$taskInfo['task_sticker']['id']: null;
        $task->task_department = isset($taskInfo['task_department']) && $taskInfo['task_department']? $taskInfo['task_department']['value']: null;
        $task->weight = $taskInfo['weight'] ?? null;
        $task->project_id = isset($taskInfo['project_id']) && $taskInfo['project_id'] ?$taskInfo['project_id']['id']: null;
        $task->task_predecessor = isset($taskInfo['task_predecessor']) && $taskInfo['task_predecessor'] ?$taskInfo['task_predecessor']['id']: null;
        $task->task_parent = isset($taskInfo['task_parent']) && $taskInfo['task_parent'] ?$taskInfo['task_parent']['id']: null;
        $task->level = isset($taskInfo['task_parent']) && $taskInfo['task_parent'] ?$taskInfo['task_parent']['level'] + 1: 1;
        $task->task_performer = isset($taskInfo['task_performer']) && $taskInfo['task_performer'] ?$taskInfo['task_performer']['id']: null;
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
        $task->start_time = $taskInfo['start_time'] ?? date('Y-m-d H:i:s', time());
        $task->time = $taskInfo['time'] ?? 0;
        $task->end_time = $taskInfo['end_time'] ?? date('Y-m-d H:i:s', time());
        $task->description = $taskInfo['description'] ?? '';
        $task->task_priority = isset($taskInfo['task_priority']) && $taskInfo['task_priority'] ? $taskInfo['task_priority']['id']: null;
        $task->task_sticker = isset($taskInfo['task_sticker']) && $taskInfo['task_sticker'] ?$taskInfo['task_sticker']['id']: null;
        $task->task_department = isset($taskInfo['task_department']) && $taskInfo['task_department']? $taskInfo['task_department']['value']: null;
        $task->weight = $taskInfo['weight'] ?? null;
        $task->project_id = isset($taskInfo['project_id']) && $taskInfo['project_id'] ?$taskInfo['project_id']['id']: null;
        $task->task_predecessor = isset($taskInfo['task_predecessor']) && $taskInfo['task_predecessor'] ?$taskInfo['task_predecessor']['id']: null;
        $task->task_parent = isset($taskInfo['task_parent']) && $taskInfo['task_parent'] ?$taskInfo['task_parent']['id']: null;
        $task->level = isset($taskInfo['task_parent']) && $taskInfo['task_parent'] ?$taskInfo['task_parent']['level'] + 1: 1;
        $task->task_performer = isset($taskInfo['task_performer']) && $taskInfo['task_performer'] ?$taskInfo['task_performer']['id']: null;
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

        return [
            'code' => 200,
            'message' => 'Cập nhật thành công',
        ];
    }

    public function getAll(Request $request) {
        $projectId = $request->input('project_id');

        $tasks = Task::query()->with(['parent'])->where('project_id', '=', $projectId)->get();

        foreach ($tasks as $task) {
            $label = '';
            if ($task->parent) {
                $item = $task->parent;
                while ($item) {
                    $label .= $item->task_name . ' > ';
                    $item = $item->parent;
                }
            }
            $task->label = $label. $task->task_name;
        }

        return [
            'code' => 200,
            'data' => $tasks
        ];
    }

    public function delete($taskId) {
        $task = Task::find($taskId);

        Task::query()->where('id', '=', $taskId)->delete();
        TaskUser::query()->where('task_id', '=', $taskId)->delete();

        if ($task) {
            Task::query()->where('task_parent', '=', $taskId)
                ->update([
                    'task_parent' => $task->task_parent
                ]);
        }

        return [
            'code' => 200
        ];
    }

    public function detail($taskId) {
        $task = Task::find($taskId);
        $detail = [];

        $detail['task_name'] = $task->task_name ?? '';
        $detail['task_code'] = $task->task_code ?? '';
        $detail['start_time'] = $task->start_time ?? '';
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
            $detail['task_predecessor'] = Task::find($task->task_predecessor);
        }

        if ($task->task_parent) {
            $detail['task_parent'] = Task::find($task->task_parent);
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
}
