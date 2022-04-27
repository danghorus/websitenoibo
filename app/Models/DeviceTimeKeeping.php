<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceTimeKeeping extends Model
{
    protected $table = 'devices_timekeeping';

    const TYPE = [
        '0' => 'Vào/ra',
        '1' => 'Vào',
        '2' => 'Ra',
    ];

    protected $fillable = [
        'device_name',
        'device_code',
        'type'
    ];
}
