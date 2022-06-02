<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeKeepingPetition extends Model
{
    protected $table = 'time_keeping';

    protected $fillable = [
        'user_id',
        'checkin',
        'checkout',
        'check_date',
        'reason',
        'check_type',
    ];

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
