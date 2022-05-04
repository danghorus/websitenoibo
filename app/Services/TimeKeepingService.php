<?php

namespace App\Services;

use App\Models\ConfigTimeKeeping;
use App\Models\DeviceTimeKeeping;
use App\Models\TimeKeeping;
use App\Models\User;
use App\Repositories\HanetRepository;
use App\Repositories\PartnerRepository;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Support\Facades\Auth;

class TimeKeepingService
{
    /**
     * @var HanetRepository
     */
    private $hanetRepository;
    /**
     * @var PartnerRepository
     */
    private $partnerRepository;

    public function __construct(
        HanetRepository $hanetRepository,
        PartnerRepository $partnerRepository
    )
    {
        $this->hanetRepository = $hanetRepository;
        $this->partnerRepository = $partnerRepository;
    }

    public function getAllTimeKeeping(array $filters = []) {
        $start_date = '';
        $end_date = '';
        switch ($filters['option']) {
            case 1:
                $start_date = date('Y-m-d',strtotime('monday this week'));
                $end_date = date('Y-m-d',strtotime('sunday this week +1 days'));
                break;
            case 2:
                $start_date = date('Y-m-01');
                $end_date = date('Y-m-d', strtotime('+1 day', strtotime(date('Y-m-t'))));
                break;
        }
        $labels = [];

        $range = $this->getLabelTimeKeeping($start_date, $end_date, $labels);

        $users = \App\Models\User::getAllUser($filters, $range);

        $config = ConfigTimeKeeping::query()->where('code', '=', 'TIME')->first();

        if ($config && $config->settings) {
            $settings = json_decode($config->settings, true);
        }

        $result = [];

        foreach ($users as $user) {
            if ($user) {
                $tmp = [];
                foreach ($range as $key => $day) {
                    $tmp[$key] = [];
                    $tmp[$key]['day'] = $key;
                    if ($user->timeKeeping) {
                        foreach ($user->timeKeeping as $time) {
                            if ($time->check_date == $key) {
                                $tmp[$key]['checkin'] = $time->checkin;
                                $tmp[$key]['checkout'] = $time->checkout;

                                $configTimeKeepingDay = $settings[$day]?? [];

                                if ($configTimeKeepingDay && $configTimeKeepingDay['start_time'] && $configTimeKeepingDay['end_time']) {
                                    $checkIn = $time->checkin? strtotime($key. ' '. $time->checkin): '';
                                    $checkOut = $time->checkout? strtotime($key. ' '. $time->checkout): '';

                                    $start = $configTimeKeepingDay['start_time'] != ''? strtotime($key. ' '. $configTimeKeepingDay['start_time']): '';
                                    $end = $configTimeKeepingDay['end_time'] != ''? strtotime($key. ' '. $configTimeKeepingDay['end_time']): '';

                                    if (($checkIn && !$checkOut) || (!$checkIn && $checkOut)) {
                                        $tmp[$key]['class'] = 'table-danger';
                                    } elseif ($checkIn && $checkOut && $checkIn <= $start && $checkOut >= $end) {
                                        $tmp[$key]['class'] = 'table-success';
                                    } elseif (($checkIn && $checkIn > $start) || ($checkOut && $checkOut < $end)) {
                                        $tmp[$key]['class'] = 'table-warning';
                                    }
                                }
                            }
                        }
                    }
                }
                $result[] = [
                    'fullname' => $user->fullname,
                    'id' => $user->id,
                    'time_keeping' => $tmp
                ];
            }

        }

        $currentUser = Auth::user();

        $showBtn = '';

        if ($currentUser->check_type == 2) {
            $timeKeeping = TimeKeeping::query()
                ->where([
                    'user_id' => $currentUser->id,
                    'check_date' => date('Y-m-d', time())
                ])->first();

            if (!$timeKeeping) {
                $showBtn = 'checkin';
            } else if ($timeKeeping->checkin && ! $timeKeeping->checkout) {
                $showBtn = 'checkout';
            }
        }

        return [
            'code' => 200,
            'labels' => $labels,
            'data' => $result,
            'current_user' => $currentUser,
            'showBtn' => $showBtn,
        ];

    }

    public function getDetailTimeKeeping(array $filters)
    {
        $partnerConfig = $this->partnerRepository->getOne('HANET');
        $setting = $partnerConfig? $partnerConfig->setting: '';
        if ($setting && $setting->access_token) {
            $accessToken = $setting->access_token;

            $user = User::query()->where('id', '=', $filters['user_id'])->first();

            $devices = DeviceTimeKeeping::all();

            foreach ($devices as $device) {
                $devicesArr[] = $device->device_code;
            }

            $data = $this->hanetRepository->getCheckinByPlaceIdInDay($accessToken, $filters['date'], $devicesArr, $user);

            if ($data->statusCode === 0) {
                return $data->data;
            }
        }

        return false;
    }

    /**
     * @throws \Exception
     */
    private function getLabelTimeKeeping(string $start_date, string $end_date, array &$labels)
    {

        $period = new DatePeriod(
            new DateTime($start_date),
            new DateInterval('P1D'),
            new DateTime($end_date)
        );

        foreach ($period as $key => $value) {
            $day = $value->format('Y-m-d');

            $map = [
                'monday' => 'T2',
                'tuesday' => 'T3',
                'wednesday' => 'T4',
                'thursday' => 'T5',
                'friday' => 'T6',
                'saturday' => 'T7',
                'sunday' => 'CN',
            ];

            $daySymbol = $map[lcfirst(date('l', strtotime($day)))];

            $dateRange[$day] = lcfirst(date('l', strtotime($day)));

            $labels[] = date('d/m', strtotime($day)) . '  '. $daySymbol;


        }

        return $dateRange;
    }

    public function checkin(array $data)
    {
        if (isset($data['data_type']) && $data['data_type'] === 'log'
            && isset($data['personType']) && $data['personType'] == 0) {

            $user = \App\Models\User::query()->where('id', '=', $data['aliasID'])->first();

            if ($user) {
                $device = DeviceTimeKeeping::query()->where('device_code', '=', $data['deviceID'])->first();

                $type = $device ? $device->type: 0;

                $timeKeeping = TimeKeeping::query()
                    ->where('check_date', '=', date('Y-m-d', time()))
                    ->where('user_id', '=', $user->id)
                    ->first();

                if ($type == 0) {
                    if ($timeKeeping) {
                        $timeKeeping->checkout = date('H:i:s', strtotime($data['date']));
                    } else {
                        $timeKeeping = new TimeKeeping();
                        $timeKeeping->checkin = date('H:i:s', strtotime($data['date']));
                        $timeKeeping->user_id = $user->id;
                        $timeKeeping->check_date = date('Y-m-d', time());
                        $timeKeeping->check_type = 1;
                    }
                } else if ($type == 1) {
                    if ($timeKeeping) {
                        $timeKeeping->checkin = date('H:i:s', strtotime($data['date']));
                    } else {
                        $timeKeeping = new TimeKeeping();
                        $timeKeeping->checkin = date('H:i:s', strtotime($data['date']));
                        $timeKeeping->user_id = $user->id;
                        $timeKeeping->check_date = date('H:i:s', strtotime($data['date']));
                        $timeKeeping->check_type = 1;
                    }
                } else if ($type == 2) {
                    if ($timeKeeping) {
                        $timeKeeping->checkout = date('H:i:s', strtotime($data['date']));
                    } else {
                        $timeKeeping = new TimeKeeping();
                        $timeKeeping->checkout = date('H:i:s', strtotime($data['date']));
                        $timeKeeping->user_id = $user->id;
                        $timeKeeping->check_date = date('H:i:s', strtotime($data['date']));
                        $timeKeeping->check_type = 1;
                    }
                }

                $timeKeeping->save();

                return [
                    'status' => '0',
                    'message' => 'Chấm công thành công'
                ];
            }

        }

        return [
            'status' => '1',
            'message' => 'Không thế chấm công'
        ];
    }

    public function getDetailTimeKeepingUser(array $filters)
    {
        $data = TimeKeeping::query()
            ->where([
                'user_id' => $filters['user_id'],
                'check_date' => $filters['date']
            ])->first();

        return $data;
    }

    public function update(array $data)
    {
        $timeKeeping = TimeKeeping::query()
            ->where([
                'user_id' => $data['user_id'],
                'check_date' => $data['date']
            ])->first();

        if (! $timeKeeping) {
            $timeKeeping = new TimeKeeping();
            $timeKeeping->user_id = $data['user_id'];
            $timeKeeping->check_date = $data['date'];
        }

        if (isset($data['checkin']) && $data['checkin'] != '') {
            $timeKeeping->checkin = $data['checkin'];
        }

        if (isset($data['checkout']) && $data['checkout'] != '') {
            $timeKeeping->checkout = $data['checkout'];
        }

        if (isset($data['reason']) && $data['reason'] != '') {
            $timeKeeping->reason = $data['reason'];
        }

        $timeKeeping->save();

        return true;
    }

    public function checkinHandmade(User $user)
    {
        if ($user->check_type == 2) {
            $timeKeeping = TimeKeeping::query()
                ->where([
                    'user_id' => $user->id,
                    'check_date' => date('Y-m-d', time())
                ])->first();

            if (! $timeKeeping) {
                $timeKeeping = new TimeKeeping();
                $timeKeeping->user_id = $user->id;
                $timeKeeping->check_date = date('Y-m-d', time());
                $timeKeeping->checkin = date('H:i:s', time());
                $timeKeeping->save();

                return [
                    'code' => 200,
                    'checkin' => true
                ];
            } else {
                $timeKeeping->checkout = date('H:i:s', time());
                $timeKeeping->save();

                return [
                    'code' => 200,
                    'checkin' => false
                ];
            }

            return true;
        }

        return false;
    }
}
