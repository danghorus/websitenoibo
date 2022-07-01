<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $table = 'holiday';

    protected $fillable = [
        'id',
        'holiday_name',
        'date_start',
        'date_end',
    ];
}
