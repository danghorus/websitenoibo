<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    use HasFactory;

    protected $table = 'proposals';


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
        'type_OT',
        'petition_reason',
        'petition_status',
        'read',
        'check_type'
    ];

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
