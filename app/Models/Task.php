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
    ];

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
        'level',
        'status'
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
