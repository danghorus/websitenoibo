<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfigTimeKeeping extends Model
{
    protected $table = 'config_time_keeping';

    protected $fillable = [
        'name',
        'code',
        'settings',
    ];
}
