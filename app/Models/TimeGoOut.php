<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeGoOut extends Model
{
    protected $table = 'time_go_out';

    protected $fillable = [
        'user_id',
        'go_date',
        'go_time',
    ];

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
