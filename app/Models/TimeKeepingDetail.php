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
        'person_name',
        'person_title',
        'place_name',
        'time',
        'check_date',
        'partner_id',
    ];

    public function user()
    {
        return $this->hasOne('App\Models\User', 'user_code', 'user_code');
    }
}
