<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'projects';

    protected $fillable = [
        'id',
        'project_name',
        'project_code',
        'project_start_date',
        'project_end_date',
        'project_day',
        'description',
        'project_manager',
    ];
}
