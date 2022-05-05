<?php

namespace App\Repositories;

use App\Models\PartnerConfig;

class PartnerRepository
{
    /**
     * @var PartnerConfig
     */
    private $partnerConfig;

    public function __construct(PartnerConfig $partnerConfig)
    {
        $this->partnerConfig = $partnerConfig;
    }

    /**
     * @param array $dataCreate
     * @return bool
     */
    public function create(array $dataCreate = []): bool
    {
        $dataCreate['access_token'] = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjUxNDQwMjE4NjE2NDkzMTQzOTQiLCJlbWFpbCI6ImRhbmdibkBob3J1c3ZuLmNvbSIsImNsaWVudF9pZCI6Ijg0OTg3MjVhNDBhY2IzZDViNjBhYjVjYjk3MGMxYjA5IiwidHlwZSI6ImF1dGhvcml6YXRpb25fY29kZSIsImlhdCI6MTY1MDkwMDMyOSwiZXhwIjoxNjUzNDkyMzI5fQ.06A8Rb2c9hbNZKTc01oeD3QHrycQ2YeoKEYDUEX8oAw';

        $partner = new PartnerConfig();

        $partner->partner_name = $dataCreate['partner_name'] ?? '';
        $partner->partner_code = $dataCreate['partner_code'] ?? '';
        $partner->setting = $dataCreate? json_encode($dataCreate): '';
        $partner->active = $dataCreate['active'] ?? 1;

        $isCreated = $partner->save();

        return $isCreated;
    }

    /**
     * @param string $partnerCode
     */
    public function getOne(string $partnerCode)
    {
        $partner = PartnerConfig::query()->where('partner_code', $partnerCode)->first();
        if ($partner) {
            $partner->setting = $partner->setting? json_decode($partner->setting): '';
        }
        return $partner;
    }

    public function updateConfig(?\Illuminate\Database\Eloquent\Model $partnerConfig, $data)
    {
        $setting = $partnerConfig->setting;

        $setting->access_token = $data->access_token;
        $setting->refresh_token = $data->refresh_token;

        $isUpdated = $partnerConfig->update([
            'setting' => json_encode($setting)
        ]);

        return $isUpdated;
    }
}
