<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use function GuzzleHttp\Promise\task;

class Task extends Model
{
    public const DEPARTMENTS = [
        1 => 'Admin',
        2 => 'Dev',
        3 => 'Game design',
        4 => 'Art',
        5 => 'Tester',
        6 => 'Điều hành',
        7 => 'Hành chính nhân sự',
        8 => 'Kế toán',
        9 => 'Phân tích dữ liệu',
        10 => 'Support',
        11 => 'Marketing',
    ];

    public const ARR_STATUS = [
      0 => 'Mới tạo',
      1 => 'Đang chờ',
      2 => 'Đang tiến hành',
      3 => 'Tạm dừng',
      4 => 'Hoàn thành'
    ];

    public const TASK_NEW = 0;
    public const TASK_WAITING = 1;
    public const TASK_PROCESSING = 2;
    public const TASK_PAUSE = 3;
    public const TASK_COMPLETED = 4;

    protected $table = 'tasks';

    protected $fillable = [
        'id',
        'task_name',
        'task_code',
        'start_time',
        'time',
        'end_time',
        'description',
        'task_priority',
        'task_sticker',
        'task_department',
        'weight',
        'project_id',
        'task_predecessor',
        'task_parent',
        'task_performer',
        //'level',
        'status',
        'real_start_time',
        'real_end_time',
        'time_pause'
    ];

    public function children()
    {
        return $this->hasMany('App\Models\Task', 'task_parent', 'id')->with(['children' => function ($q) {
            $q->where('valid', '=', 1);
        }]);
    }

    public function childrenInvalid()
    {
        return $this->hasMany('App\Models\Task', 'task_parent', 'id')->with(['childrenInvalid']);
    }

    public function parent()
    {
        return $this->hasOne('App\Models\Task', 'id', 'task_parent')->with(['parent']);
    }

    public function taskUser()
    {
        return $this->hasOne('App\Models\User', 'id', 'task_performer');
    }

    public static function taskChildrent($arr, $taskParent, $isFirst = true) {
        $taskId = 0;
        if ($arr) {
            foreach ($arr as $value) {

                $newTask = new Task();
                $newTask->task_name = $value->task_name;
                $newTask->task_code = $value->task_code;
                $newTask->start_time = $value->start_time;
                $newTask->time = $value->time;
                $newTask->end_time = $value->end_time;
                $newTask->description = $value->description;
                $newTask->task_priority = $value->task_priority;
                $newTask->task_sticker = $value->task_sticker;
                $newTask->task_department =$value->task_department;
                $newTask->weight = $value->weight;
                $newTask->project_id = $value->project_id;
//                $newTask->task_predecessor = $value->task_predecessor;
                $newTask->task_parent = $taskParent;
                $newTask->level = $value->level;
                $newTask->task_performer = $value->task_performer;
                $newTask->status = $isFirst? 1: 0;

                $newTask->save();
                if ($isFirst) {
                    $taskId = $newTask->id;
                }
                $taskUsers = TaskUser::query()->where('task_id', '=', $value->id);
                foreach ($taskUsers as $val) {
                    $taskUser = new TaskUser();
                    $taskUser->user_id = $val->user_id;
                    $taskUser->task_id = $newTask->id;
                    $taskUser->save();
                }

                if (count($value->children) > 0) {
                    self::taskChildrent($value->children, $newTask->id, false);
                }
            }
        }

        return $taskId;
    }
    public static function taskChildrentFormat($arr)
    {
        if ($arr) {
            foreach ($arr as $key => $value) {
                $value->label = $value->task_name;

                if (count($value->children) > 0) {
                    self::taskChildrentFormat($value->children);
                }
            }
        }

        return $arr;
    }
    public static function taskChildrenInvalid($arr)
    {
        if ($arr) {
            foreach ($arr as $key => $value) {
                self::query()->where('id', '=', $value->id)
                    ->update([
                        'valid' => 0,
                        'deleted_by' => Auth::id(),
                        'deleted_at' => date('Y-m-d H:i:s', time())
                    ]);

                if (count($value->children) > 0) {
                    self::taskChildrenInvalid($value->children);
                }
            }
        }

        return $arr;
    }
    public static function taskChildrenValid($arr, $hasParent)
    {
        if ($arr) {
            foreach ($arr as $key => $value) {
                self::query()->where('id', '=', $value->id)
                    ->update([
                        'valid' => 1,
                        'task_parent' => $hasParent? $value->task_parent: null,
                        'deleted_by' => null,
                        'deleted_at' => null
                    ]);

                if (count($value->children) > 0) {
                    self::taskChildrenValid($value->children, true);
                }
            }
        }

        return $arr;
    }
    public static function deleteTaskChildren($arr)
    {
        if ($arr) {
            foreach ($arr as $key => $value) {
                self::query()->where('id', '=', $value->id)
                    ->delete();

                if (count($value->children) > 0) {
                    self::deleteTaskChildren($value->children);
                }
            }
        }

        return $arr;
    }
}
