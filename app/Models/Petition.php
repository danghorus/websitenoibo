<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
  
class Petition extends Model
{
    use HasFactory;
  
    protected $fillable = [
        'user_fullname',
        'petition_type',
        'time_from',
        'date_from',
        'time_to',
        'date_to',
        'type_leave',
        'petition_reason',
        'petition_status'
    ];
}