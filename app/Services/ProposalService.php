<?php

namespace App\Services;

use App\Models\Proposal;

class ProposalService
{
    public function __construct()
    {
    }

    public function createProposal($data = [])
    {
        $proposal = new Proposal();
        $proposal->petition_type = 4;
        $proposal->user_id = $data['user_id'] ?? 0;
        $proposal->user_fullname = $data['user_fullname'] ?? '';
        $proposal->time_from_old = $data['old_checkin'] ?? '';
        $proposal->time_from = $data['checkin'] ?? $data['old_checkin'];
        //$proposal->time_from = $data['type'] == 4? $data['old_checkin'] : $data['old_checkout'];
        $proposal->date_from = $data['date'] ?? '';
        $proposal->time_to_old = $data['old_checkout'] ?? '';
        $proposal->time_to = $data['checkout'] ?? $data['old_checkout'];
        //$proposal->time_to = $data['type'] == 4? $data['checkin'] : $data['checkout'];
        $proposal->date_to = $data['date'] ?? '';
        $proposal->petition_reason = $data['reason'] ?? '';

        $proposal->save();

        return true;
    }
}
