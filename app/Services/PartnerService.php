<?php

namespace App\Services;

use App\Models\DeviceTimeKeeping;
use App\Models\User;
use App\Repositories\DeviceTimeKeepingRepository;
use App\Repositories\HanetRepository;
use App\Repositories\PartnerRepository;

class PartnerService
{
    /**
     * @var PartnerRepository
     */
    private $partnerRepository;
    /**
     * @var HanetRepository
     */
    private $hanetRepository;
    /**
     * @var DeviceTimeKeepingRepository
     */
    private $deviceTimeKeepingRepository;

    public function __construct(
        PartnerRepository $partnerRepository,
        HanetRepository $hanetRepository,
        DeviceTimeKeepingRepository $deviceTimeKeepingRepository
    )
    {
        $this->partnerRepository = $partnerRepository;
        $this->hanetRepository = $hanetRepository;
        $this->deviceTimeKeepingRepository = $deviceTimeKeepingRepository;
    }

    /**
     * @param array $dataConnect
     */
    public function connect(array $dataConnect = []) {
        $isCreated = $this->partnerRepository->create($dataConnect);

        if ($isCreated) {
            $url = $this->hanetRepository->buildUrl($dataConnect['client_id'] ?? '');
            return $url;
        } else {
            return false;
        }
    }

    public function getDevices()
    {
        $data = $this->deviceTimeKeepingRepository->getAll();

        if (count($data)) {

            foreach ($data as $key => $value) {
                $type = DeviceTimeKeeping::TYPE;
                $data[$key]['type_text'] = $type[$value['type']] ?? '';
            }

            return [
                'data' => $data,
                'code' => 200
            ];
        }

        return [
            'message' => 'Not found',
            'code' => 404
        ];

//        }
    }

    public function getAccessToken($code)
    {
        $partnerConfig = $this->partnerRepository->getOne('HANET');

        $data = $this->hanetRepository->getAccessToken($code, $partnerConfig);

        $isUpdated = $this->partnerRepository->updateConfig($partnerConfig, $data);

        return $isUpdated;
    }

    public function getConfig()
    {
        $partnerConfig = $this->partnerRepository->getOne('HANET');
        if ($partnerConfig) {
            return [
                'data' => $partnerConfig->setting ?? '',
                'code' => 200
            ];
        }

        return [
            'data' => 'Not found',
            'code' => 404
        ];
    }

    public function syncDevices()
    {
        $partnerConfig = $this->partnerRepository->getOne('HANET');
        $setting = $partnerConfig->setting;
        if ($setting && $setting->access_token) {
            $accessToken = $setting->access_token;

            $data = $this->hanetRepository->getDevices($accessToken);

            $arrDev = $this->deviceTimeKeepingRepository->getAllDeviceCode();
            if ($data->statusCode === 0) {
                foreach ($data->data as $value) {
                    if (! in_array($value->deviceID, $arrDev)) {
                        $this->deviceTimeKeepingRepository->create([
                            'device_name' => $value->deviceName,
                            'device_code' => $value->deviceID,
                        ]);
                    }
                }

                return true;
            }

        }

        return false;
    }

    public function getDeviceInfo($code)
    {
        $device = $this->deviceTimeKeepingRepository->getOne($code);

        return $device;
    }

    public function updateDevice(array $data)
    {
        $device = $this->deviceTimeKeepingRepository->getOne($data['device_code']);

        if ($device) {
            $device->update($data);

            return true;
        }

        return false;
    }

    public function getPlaces()
    {
        $partnerConfig = $this->partnerRepository->getOne('HANET');
        $setting = $partnerConfig->setting;
        if ($setting && $setting->access_token) {
            $accessToken = $setting->access_token;

            $data = $this->hanetRepository->getPlaces($accessToken);
            if ($data->statusCode === 0) {
                return $data->data;
            }
        }

        return false;
    }

    public function updateUser(array $data)
    {
        $user = User::query()->where('id', '=', $data['user_id'])->first();

        unset($data['user_id']);

        $user->update($data);

        return true;

    }
}
