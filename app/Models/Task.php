<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        return $this->hasMany('App\Models\Task', 'task_parent', 'id');
    }

    public function parent()
    {
        return $this->hasOne('App\Models\Task', 'id', 'task_parent')->with(['parent']);
    }
}
