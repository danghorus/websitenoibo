<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fullname',
        'email',
        'password',
        'avatar',
        'phone',
        'birthday',
        'department',
        'email',
        'position',
        'permission' ,
        'check_type' ,
        'place_id' ,
        'place_name' ,
        'face_image_url' ,
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function timeKeeping()
    {
        return $this->hasMany('App\Models\TimeKeeping');
    }

    public static function getAllUser(array $filters, array $range = []) {
         $builder = User::query();

         if (isset($filters['search']) && $filters['search'] != '') {
             $builder->where('fullname', 'like', "%{$filters['search']}%");
         }

         $builder->with(['timeKeeping' => function ($q) use ($range) {
             if (count($range) > 0) {
                 $q->whereIn('check_date', array_keys($range));
             }
        }]);

        $data = $builder->get();

        return $data;
    }
}
