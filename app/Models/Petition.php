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
        'time_from_old',
        'time_from',
        'date_from',
        'time_to_old',
        'time_to',
        'date_to',
        'type_leave',
        'petition_reason',
        'petition_status',
        'type_approved',
		'type_paid',
        'check_type',
        'readed',
        'infringe',

    ];

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
