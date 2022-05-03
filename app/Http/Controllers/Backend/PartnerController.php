<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\PartnerService;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    /**
     * @var PartnerService
     */
    private $partnerService;

    public function __construct(PartnerService $partnerService)
    {
        $this->partnerService = $partnerService;
    }

    /**
     * @param Request $request
     */
    public function connect(Request $request)
    {
        $data = $request->all();

        $url = $this->partnerService->connect($data);
        if ($url) {
            return [
                'code' => 200,
                'message' => 'Kết nối thành công',
                'url' => $url
            ];
        }
    }

    public function getDevices(Request $request)
    {
        $data = $this->partnerService->getDevices();

        return $data;
    }

    public function getDeviceInfo(Request $request)
    {
        $code = $request->input('code');
        if ($code) {
            $data = $this->partnerService->getDeviceInfo($code);

            return [
                'code' => 200,
                'data' => $data
            ];
        }


        return [
            'code' => 404,
            'message' => 'Not found'
        ];
    }

    public function syncDevice(Request $request)
    {
        $isSync = $this->partnerService->syncDevices();

        return (bool) $isSync;
    }

    public function getConfig(Request $request) {
        $data = $this->partnerService->getConfig();

        return $data;
    }

    public function getAuthCode(Request $request)
    {
        $code = $request->input('code');

        if ($code) {
            $isUpdated = $this->partnerService->getAccessToken($code);

            return redirect(env('MIX_API_URL').'/time-keeping');
        }
    }

    public function updateDevice(Request $request)
    {
        $data = $request->all();
        if (isset($data['device_code'])) {
            $isUpdated = $this->partnerService->updateDevice($data);

            return [
                'code' => 200,
                'message' => 'Cập nhật thành công'
            ];
        }

        return [
            'code' => 404,
            'message' => 'Not found'
        ];
    }

    public function getUsers(Request $request)
    {
        $data = $request->all();
        $users = User::all();

        $places = $this->partnerService->getPlaces();

        return [
            'code' => 200,
            'places' => $places,
            'users' => $users,
        ];
    }

    public function updateUser(Request $request)
    {
        $data = $request->all();

        $this->partnerService->updateUser($data);

        return [
            'code' => 200
        ];
    }

    public function updateConfig(Request $request)
    {
        $data = $request->all();

        $this->partnerService->updateConfig($data);

        return [
            'code' => 200
        ];
    }

    public function getConfigTime(Request $request)
    {
        $settings = $this->partnerService->getConfigTime();

        return $settings;
    }
}
