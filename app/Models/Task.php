<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
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
    ];
}
