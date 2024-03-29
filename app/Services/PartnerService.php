<?php

namespace App\Services;

use App\Models\ConfigTimeKeeping;
use App\Models\DeviceTimeKeeping;
use App\Models\PartnerConfig;
use App\Models\TimeKeeping;
use App\Models\TimeKeepingDetail;
use App\Models\User;
use App\Repositories\DeviceTimeKeepingRepository;
use App\Repositories\HanetRepository;
use App\Repositories\PartnerRepository;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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

    public function getCheckinByPlaceIdInTimestamp()
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
        $setting = $partnerConfig? $partnerConfig->setting: '';
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
        $setting = $partnerConfig? $partnerConfig->setting: '';
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

    public function updateConfig(array $data)
    {
        $config = ConfigTimeKeeping::query()->where('code', '=', $data['code'])->first();

        if ($config) {
            $config->settings = json_encode($data['settings'] ?? []);
            $config->save();
        } else {
            $config = new ConfigTimeKeeping();
            $config->settings = json_encode($data['settings'] ?? []);
            $config->code = $data['code'] ?? '';
            $config->name = $data['name'] ?? '';
            $config->save();
        }

        return true;
    }

    public function getConfigTime()
    {
        $config = ConfigTimeKeeping::query()->where('code', '=', 'TIME')->first();
        if ($config && $config->settings) {
            return [
                'code' => 200,
                'settings' => json_decode($config->settings)
            ];
        }

        return [
            'code' => 404
        ];
    }

    public function disconnect()
    {
        $result = PartnerConfig::query()->where('partner_code', '=', 'HANET')->delete();

        return [
            'code' => 200
        ];
    }

    public function syncUsers()
    {
        $partnerConfig = $this->partnerRepository->getOne('HANET');
        $setting = $partnerConfig? $partnerConfig->setting: '';
        if ($setting && $setting->access_token) {
            $accessToken = $setting->access_token;

            $places = $this->hanetRepository->getPlaces($accessToken);
            if ($places->returnCode == 1) {
                foreach ($places->data as $value) {
                    $users = $this->hanetRepository->getAllUsers($accessToken, $value->id);

                    if ($users->returnCode == 1) {
                        foreach ($users->data as $val) {
                            $exist = User::query()->where('user_code', '=', $val->aliasID)->exists();
                            if (!$exist) {
                                $newUser = new User();
                                $newUser->fullname = $val->name;
//                            $newUser->date_official = date('Y-m-d', time());
                                $newUser->user_code = $val->aliasID;
                                $newUser->email = Str::random(10).'@gmail.com';
                                $newUser->password = Hash::make('Chamchi123');
                                $newUser->avatar = 'user.png';
                                $newUser->phone = '0123456789';
                                $newUser->birthday = date('Y-m-d', time());
                                $newUser->department = 'Dev';
                                $newUser->position = 'Nhân viên';
                                $newUser->permission = 1;
                                $newUser->check_type = 1;
                                $newUser->place_id = $value->id;
                                $newUser->place_name = $value->name;
                                $newUser->face_image_url = $val->avatar;

                                $newUser->save();
                            }
                        }
                    }
                }

                return [
                    'code' => 200
                ];
            }
        }

        return false;
    }

    public function getSyncTimekeeping(array $filters)
    {
        $from = strtotime($filters['start_date']. ' 00:00:00') * 1000;
        $to = strtotime($filters['end_date']. ' 23:59:59') * 1000;

        $partnerConfig = $this->partnerRepository->getOne('HANET');
        $setting = $partnerConfig? $partnerConfig->setting: '';

        if ($setting && $setting->access_token) {
            $accessToken = $setting->access_token;
            $users = explode(',', $filters['users']);

            $device = DeviceTimeKeeping::query()->where('device_code', '=', $filters['device'])->first();

            $type = $device ? $device->type: 0;

            foreach ($users as $userCode) {
                $user = User::query()->where('user_code', '=', $userCode)->first();

                if ($user) {
                    $data = $this->hanetRepository->getCheckinByPlaceIdInTimestamp($accessToken, $user->place_id, [$filters['device']], $from, $to, $userCode);

                    if($data->returnCode == 1) {
                        $currentDate = '';
                        foreach ($data->data as $value) {
                            if ($value->date != $currentDate) {
                                $currentDate = $value->date;
                                TimeKeeping::query()->where([
                                    'user_id' => $user->id,
                                    'check_date' => $value->date
                                ])->delete();
                                TimeKeepingDetail::query()->where([
                                    'user_code' => $userCode,
                                    'check_date' => $value->date
                                ])->delete();
                            }

                            $detail = new TimeKeepingDetail();
                            $detail->user_code = $value->aliasID;
                            $detail->detected_image_url = $value->avatar;
                            $detail->device_name = $value->deviceName;
                            $detail->device_id = $value->deviceID;
                            $detail->person_name = $value->personName;
                            $detail->person_title = $value->title;
                            $detail->place_name = $value->place;
                            $detail->time_int = $value->checkinTime/1000;
                            $detail->time = date('H:i:s', $value->checkinTime/1000);
                            $detail->check_date = date('Y-m-d', $value->checkinTime/1000);
//                            $detail->partner_id = $data['id'];
                            $detail->obj_data = json_encode($value);

                            $detail->save();

                            $timeKeeping = TimeKeeping::query()
                                ->where('check_date', '=', $value->date)
                                ->where('user_id', '=', $user->id)
                                ->first();

                            if ($type == 0) {
                                if ($timeKeeping) {
                                    $timeKeeping->checkout = date('H:i:s', $value->checkinTime/1000);
                                } else {
                                    $timeKeeping = new TimeKeeping();
                                    $timeKeeping->checkin = date('H:i:s', $value->checkinTime/1000);
                                    $timeKeeping->user_id = $user->id;
                                    $timeKeeping->check_date = $value->date;
                                    $timeKeeping->check_type = 1;
                                }
                            } else if ($type == 1) {
                                if ($timeKeeping) {
                                    $timeKeeping->checkin = date('H:i:s', $value->checkinTime/1000);
                                } else {
                                    $timeKeeping = new TimeKeeping();
                                    $timeKeeping->checkin = date('H:i:s', $value->checkinTime/1000);
                                    $timeKeeping->user_id = $user->id;
                                    $timeKeeping->check_date = date('Y-m-d', $value->checkinTime/1000);
                                    $timeKeeping->check_type = 1;
                                }
                            } else if ($type == 2) {
                                if ($timeKeeping) {
                                    $timeKeeping->checkout = date('H:i:s', $value->checkinTime/1000);
                                } else {
                                    $timeKeeping = new TimeKeeping();
                                    $timeKeeping->checkout = date('H:i:s', $value->checkinTime/1000);
                                    $timeKeeping->user_id = $user->id;
                                    $timeKeeping->check_date = date('Y-m-d', $value->checkinTime/1000);
                                    $timeKeeping->check_type = 1;
                                }
                            }

                            $timeKeeping->save();
                        }
                    }
                }
            }

            return [
                'code' => '200',
                'message' => 'Đồng bộ thành công'
            ];
        }
    }
}
