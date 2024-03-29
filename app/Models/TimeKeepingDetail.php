<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeKeepingDetail extends Model
{
    protected $table = 'time_keeping_detail';

    protected $fillable = [
        'user_code',
        'detected_image_url',
        'device_name',
        'device_id',
        'person_name',
        'person_title',
        'place_name',
        'time_int',
        'time',
        'check_date',
        'partner_id',
        'obj_data',
    ];

    public function user()
    {
        return $this->hasOne('App\Models\User', 'user_code', 'user_code');
    }
}
