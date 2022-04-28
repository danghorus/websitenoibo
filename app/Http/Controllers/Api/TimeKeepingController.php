<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TimeKeepingService;
use Illuminate\Http\Request;

class TimeKeepingController extends Controller
{
    /**
     * @var TimeKeepingService
     */
    private $timeKeepingService;

    public function __construct(
        TimeKeepingService $timeKeepingService
    )
    {
        $this->timeKeepingService = $timeKeepingService;
    }

    public function get(Request $request) {
        $filters = $request->all();

        $data = $this->timeKeepingService->getAllTimeKeeping($filters);
        return $data;
    }
}
