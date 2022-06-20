<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskUser;
use Illuminate\Http\Request;

class TaskController extends Controller
{
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
        $task->task_performer = isset($taskInfo['task_performer']) && $taskInfo['task_performer'] ?$taskInfo['task_performer']['id']: null;
        $isCreated = $task->save();

        $userIds = $data['user_related'];
        $userIds[] = $task->task_performer;
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
}
