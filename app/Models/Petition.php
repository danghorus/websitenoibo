<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Petition extends Model
{
    use HasFactory;

    //protected $table = 'time_keeping';


    protected $fillable = [
        'user_id',
        'user_fullname',
        'petition_type',
        'checkin',
        'check_date',
        'checkout',
        'date_to',
        'type_leave',
        'reason',
        'petition_status',
        'check_type'
    ];

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
