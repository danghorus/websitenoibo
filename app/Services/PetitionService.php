<?php

namespace App\Services;

use App\Models\Petition;

class PetitionService
{
    public function __construct()
    {
    }

    public function createPetition($data = [])
    {
        $petition = new Petition();
        $petition->petition_type = 4;
        $petition->user_id = $data['user_id'] ?? 0;
        $petition->user_fullname = $data['user_fullname'] ?? '';
        $petition->time_from_old = $data['old_checkin'] ?? '';
        $petition->time_from = $data['checkin'] ?? $data['old_checkin'];
        //$petition->time_from = $data['type'] == 4? $data['old_checkin'] : $data['old_checkout'];
        $petition->date_from = $data['date'] ?? '';
        $petition->time_to_old = $data['old_checkout'] ?? '';
        $petition->time_to = $data['checkout'] ?? $data['old_checkout'];
        //$petition->time_to = $data['type'] == 4? $data['checkin'] : $data['checkout'];
        $petition->date_to = $data['date'] ?? '';
        $petition->petition_reason = $data['reason'] ?? '';

        $petition->save();

        return true;
    }
}
