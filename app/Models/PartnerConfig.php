<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerConfig extends Model
{
    protected $table = 'partners_config';

    protected $fillable = [
        'partner_name',
        'partner_code',
        'setting',
        'active',
    ];
}
