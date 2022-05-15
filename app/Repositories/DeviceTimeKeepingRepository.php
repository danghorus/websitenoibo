<?php

namespace App\Repositories;

use App\Models\DeviceTimeKeeping;

class DeviceTimeKeepingRepository
{
    /**
     * @var DeviceTimeKeeping
     */
    private $deviceTimeKeeping;

    public function __construct(DeviceTimeKeeping $deviceTimeKeeping)
    {
        $this->deviceTimeKeeping = $deviceTimeKeeping;
    }

    public function getAll()
    {
        $data = DeviceTimeKeeping::all()->toArray();

        return $data;
    }

    public function getAllDeviceCode()
    {
        $result = [];

        $data = DeviceTimeKeeping::all()->toArray();

        foreach ($data as $value) {
            $result[] = $value['device_code'];
        }

        return $result;
    }

    public function create(array $array)
    {
        $device = new DeviceTimeKeeping();
        $device->fill($array);
        $isCreted = $device->save();

        return $isCreted;
    }

    public function getOne($code)
    {
        $data = DeviceTimeKeeping::query()->where('device_code', '=', $code)->first();

        return $data;
    }
}
