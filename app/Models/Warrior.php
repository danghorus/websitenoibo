<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warrior extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'user_fullname',
        'warrior',
        'project_id'
    ];
}
