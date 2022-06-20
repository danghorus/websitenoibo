<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskUser extends Model
{
    protected $table = 'task_users';

    protected $fillable = [
        'id',
        'task_id',
        'user_id',
    ];

}
