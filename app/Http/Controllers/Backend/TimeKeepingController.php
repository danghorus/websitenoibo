<?php

namespace App\Http\Controllers\Backend;

use App\Exports\TimeKeepingExport;
use App\Http\Controllers\Controller;
use App\Services\TimeKeepingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User;

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

    public function index(Request $request)
    {
        $users = User::all();
        return view('timekeeping.index', compact('users'));
    }

    public function report(Request $request)
    {
        $users = User::all();
        return view('timekeeping.report', compact('users'));
    }
    public function wage(Request $request)
    {
        $users = User::all();
        return view('timekeeping.wage', compact('users'));
    }
    public function bonus(Request $request)
    {
        $users = User::all();
        return view('timekeeping.bonus', compact('users'));
    }

    public function get(Request $request) {
        $filters = $request->all();

        $data = $this->timeKeepingService->getAllTimeKeeping($filters);

        return $data;
    }

    public function detail(Request $request) {
        $filters = $request->all();

        $data = $this->timeKeepingService->getDetailTimeKeeping($filters);

        return [
            'code' => 200,
            'data' => $data,
        ];
    }

    public function getUser(Request $request) {
        $filters = $request->all();

        $data = $this->timeKeepingService->getDetailTimeKeepingUser($filters);

        return [
            'code' => 200,
            'data' => $data,
        ];
    }

    public function timeKeeping(Request $request)
    {
        $data = $request->all();

        $result = $this->timeKeepingService->checkin($data);

        return response($result, 200);

    }

    public function update(Request $request)
    {
        $data = $request->all();

        $result = $this->timeKeepingService->update($data);

        return [
            'code' => 200
        ];

    }

    public function checkin(Request $request)
    {
        $user = Auth::user();

        $result = $this->timeKeepingService->checkinHandmade($user);

        return $result;

    }

    public function export(Request $request)
    {
        $filters = $request->all();

        $data = $this->timeKeepingService->getAllTimeKeeping($filters);

        return Excel::download(new TimeKeepingExport($data), 'timekeeping.xlsx');
    }

    public function getReport(Request $request)
    {
        $filters = $request->all();

        $data = $this->timeKeepingService->report($filters);

        return [
            'code' => 200,
            'data' => $data
        ];
    }
    public function export_report(Request $request)
    {
        $filters = $request->all();

        $data = $this->timeKeepingService->getAllTimeKeeping($filters);

        return Excel::download(new TimeKeepingExport($data), 'timekeeping.xlsx');
    }
}
