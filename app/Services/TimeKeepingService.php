<?php

namespace App\Services;

use App\Models\ConfigTimeKeeping;
use App\Models\DeviceTimeKeeping;
use App\Models\TimeKeeping;
use App\Models\TimeKeepingDetail;
use App\Models\TimeKeepingPetition;
use App\Models\Petition;
use App\Models\User;
use App\Repositories\HanetRepository;
use App\Repositories\PartnerRepository;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Stmt\Echo_;

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
        if ($filters['time']) {
            switch ($filters['option']) {
                case 1:
                    $start_date = date('Y-m-d', strtotime($filters['time']));
                    $end_date = date('Y-m-d',strtotime('+7 days', strtotime($start_date)));
                    break;
                case 2:
                    $start_date = $filters['time'];
                    $end_date = date('Y-m-d', strtotime('+1 day', strtotime(date('Y-m-t', strtotime($filters['time'])))));
                    break;
            }
        } else {
            switch ($filters['option']) {
                case 1:
                    $start_date = date('Y-m-d', strtotime('monday this week'));
                    $end_date = date('Y-m-d', strtotime('sunday this week +1 days'));
                    break;
                case 2:
                    $start_date = date('Y-m-01');
                    $end_date = date('Y-m-d', strtotime('+1 day', strtotime(date('Y-m-t'))));
                    break;
            }
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
            if ($user->user_status == 1 && $user->position != "Giám đốc") {
                $tmp = [];
                foreach ($range as $key => $day) {
                    $tmp[$key] = [];
                    $tmp[$key]['day'] = $key;
                    if ($user->timeKeeping) {
                        foreach ($user->timeKeeping as $time) {
                            if ($time->check_date == $key) {
                                $tmp[$key]['checkin'] = $filters['option'] ==1? $time->checkin: date('H:i', strtotime($key. ' '. $time->checkin));
                                $tmp[$key]['checkout'] = $filters['option'] ==1? $time->checkout: date('H:i', strtotime($key. ' '. $time->checkout));

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

                                    if ($checkIn && $start) {
                                        if ($checkIn < $start) {
                                            $tmp[$key]['go_early'] = (int) (($start - $checkIn) / 60);
                                            $tmp[$key]['go_late'] = 0;
                                        } else {
                                            $tmp[$key]['go_early'] = 0;
                                            $tmp[$key]['go_late'] = (int) (($checkIn - $start) / 60);
                                        }
                                    }

                                    if ($checkOut && $end) {
                                        if ($checkOut < $end) {
                                            $tmp[$key]['about_early'] = (int) (($end - $checkOut) / 60);
                                            $tmp[$key]['about_late'] = 0;
                                        } else {
                                            $tmp[$key]['about_early'] = 0;
                                            $tmp[$key]['about_late'] = (int) (($checkOut - $end) / 60);
                                        }
                                    }


                                }
                            }
                        }
                    }
                }
                $result[] = [
                    'fullname' => $user->fullname,
                    'id' => $user->id,
                    'date_official' => $user->date_official,
                    'wage_now' => $user->wage_now,
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
        $user = User::query()->with(['timeKeepingDetail' => function ($q) use ($filters) {
            $q->where('check_date', '=', $filters['date']);
        }])->where('id', '=', $filters['user_id'])->first();

        return $user;
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
        $dateRange = [];
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

            $user = \App\Models\User::query()->where('user_code', '=', $data['aliasID'])->first();

            if ($user) {
                $device = DeviceTimeKeeping::query()->where('device_code', '=', $data['deviceID'])->first();

                $type = $device ? $device->type: 0;

                $timeKeeping = TimeKeeping::query()
                    ->where('check_date', '=', date('Y-m-d', time()))
                    ->where('user_id', '=', $user->id)
                    ->first();

                // Lưu vào DB
                $detail = new TimeKeepingDetail();
                $detail->user_code = $data['aliasID'];
                $detail->detected_image_url = $data['detected_image_url'];
                $detail->device_name = $data['deviceName'];
                $detail->person_name = $data['personName'];
                $detail->person_title = $data['personTitle'];
                $detail->place_name = $data['placeName'];
                $detail->time_int = strtotime($data['date']);
                $detail->time = date('H:i:s', strtotime($data['date']));
                $detail->check_date = date('Y-m-d', time());
                $detail->partner_id = $data['id'];

                $detail->save();

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

    public function save_old(array $data)
    {
        if (isset($data['data_type']) && $data['data_type'] === 'log' && isset($data['personType']) && $data['personType'] == 0)
        {
            $user = \App\Models\User::query()->where('user_code', '=', $data['aliasID'])->first();

            if ($user)
            {
                $device = DeviceTimeKeeping::query()->where('device_code', '=', $data['deviceID'])->first();

                $type = $device ? $device->type: 0;

                $timeKeeping = TimeKeeping::query()
                    ->where('check_date', '=', date('Y-m-d', time()))
                    ->where('user_id', '=', $user->id)
                    ->first();

                // Lưu vào DB
                $detail = new TimeKeepingDetail();
                $detail->user_code = $data['aliasID'];
                $detail->detected_image_url = $data['detected_image_url'];
                $detail->device_name = $data['deviceName'];
                $detail->person_name = $data['personName'];
                $detail->person_title = $data['personTitle'];
                $detail->place_name = $data['placeName'];
                $detail->time_int = strtotime($data['date']);
                $detail->time = date('H:i:s', strtotime($data['date']));
                $detail->check_date = date('Y-m-d', time());
                $detail->partner_id = $data['id'];

                $detail->save();

            }

        }
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

    
    public function petition(array $data)
    {
        $timeKeeping = Petition::query()
            ->where([
                'user_id' => $data['user_id'],
                'check_date' => $data['date']
            ])->first();

        if (! $timeKeeping) {
            $timeKeeping = new Petition();
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

    public function report(array $filters)
    {
        $result = [];
        $expected = [];
        $current = [];

        if ($filters['start_date'] != '' && $filters['end_date'] != '' ) {
            $start_date = $filters['start_date'];
            $end_date =  date('Y-m-d',strtotime($filters['end_date']. ' +1 days'));

            $timeExpected = $this->timeReport($start_date, $end_date);
            $expectedWar1_3 = $timeExpected['total'] * 1;
            $expectedWar1 = $timeExpected['total'] * 2;
            $expectedWar2 = $timeExpected['total'] * 3;
            $expectedWar3 = $timeExpected['total'] * 4;

            if  (strtotime($filters['end_date']. ' +1 days') >= time() ) {
                $timeNow = $this->timeReport($start_date, date('Y-m-d', time()));
            } else {
                $timeNow = $timeExpected;
            }

            $nowWar1_3 = $timeNow['total'] * 1;
            //$nowWar1_3 = strtotime($filters['end_date']);
            $nowWar1 = $timeNow['total'] * 2;
            //$nowWar1 = time();
            $nowWar2 = $timeNow['total'] * 3;
            //$nowWar2 = date('d-m-Y', strtotime($filters['end_date']));
            $nowWar3 = $timeNow['total'] * 4;
            //$nowWar3 = (time() - strtotime($filters['end_date']));

            $range = $timeNow['range'];

            $keyArr = array_keys($range);

            $diff = date_diff(date_create($filters['end_date']), date_create(end($keyArr)));

            $timeRange = $diff->format('%a');

            $users = \App\Models\User::getAllUser($filters, $range);

            $config = ConfigTimeKeeping::query()->where('code', '=', 'TIME')->first();

            if ($config && $config->settings) {
                $settings = json_decode($config->settings, true);
            }

            foreach ($users as $user) {

                if ($user->user_status == 1 && $user->position != "Giám đốc" ) {
                    $totalWorkDate = 0;

                    $totalGoLate = 0;
                    $timeGoLate = 0;
                    $totalGoEarly = 0;
                    $timeGoEarly = 0;

                    $totalAboutLate = 0;
                    $timeAboutLate = 0;
                    $totalAboutEarly = 0;
                    $timeAboutEarly = 0;

                    $totalTimeKeeping = 0;
                    $totalWorkingDays = 0;
                    $totalNotCheckIn = 0;
                    $totalNotCheckOut = 0;
                    $totalUnpaidLeave = 0;
                    $totalHourEfforts = 0;

                    foreach ($user->timeKeeping as $value) {
                        $labelDay = $range[$value->check_date];
                        $configDay = $settings[$labelDay] ?? [];

                        $checkIn = $value->checkin? strtotime($value->check_date. ' '. $value->checkin): '';
                        $checkOut = $value->checkout? strtotime($value->check_date. ' '. $value->checkout): '';

                        if ($configDay) {
                            $start = $configDay['start_time'] != ''? strtotime($value->check_date. ' '. $configDay['start_time']): '';
                            $end = $configDay['end_time'] != ''? strtotime($value->check_date. ' '. $configDay['end_time']): '';

                            if ($checkIn && $start && $checkIn > $start) {
                                $totalGoLate++;
                                $timeGoLate += $checkIn - $start;
                            } else if ($checkIn && $start && $checkIn < $start) {
                                $totalGoEarly++;
                                $timeGoEarly += $start - $checkIn;
                            }

                            if ($checkOut && $end && $checkOut > $end) {
                                $totalAboutLate++;
                                $timeAboutLate += $checkOut - $end;
                            } else if ($checkOut && $end && $checkOut < $end) {
                                $totalAboutEarly++;
                                $timeAboutEarly += $end - $checkOut;
                            }
                        }

                        if ($checkIn && $checkOut) {
                            switch ($labelDay) {
                                case 'monday':
                                case 'tuesday':
                                case 'wednesday':
                                case 'thursday':
                                case 'friday':
                                    $totalTimeKeeping++;
                                    break;
                                case 'saturday':
                                    $totalTimeKeeping = $totalTimeKeeping + 1/2;
                                    break;
                            }
                            switch ($labelDay) {
                                case 'monday':
                                case 'tuesday':
                                case 'wednesday':
                                case 'thursday':
                                case 'friday':
                                case 'saturday':
                                    $totalWorkingDays++;
                                    break;
                            }
                        }  elseif ($checkIn && !$checkOut) {
                            $totalNotCheckOut++;
                        } elseif (!$checkIn && $checkOut) {
                            $totalNotCheckIn++;
                        }
                    }

                    $totalHourEfforts = (($timeGoEarly + $timeAboutLate) - ($timeGoLate + $timeAboutEarly))/3600;
                    //if ($user->date_official) {
                    //    $totalWorkDate = $this ->timeTotal($user->date_official, date('Y-m-d', time()))['total'];
                    //}

                    $currentWar = '';
                    $nextWar = '';
                    $timeHoldWar = 0;
                    $timeIncreaseWar = 0;
                    $avgTimeHoldWar = 0;
                    $avgTimeIncreaseWar = 0;
                    $EmployeeLongtime =1094;
                    $AllowanceWarrior = 0;

                    if ($timeRange) {
                        if($totalWorkDate > $EmployeeLongtime ){
                            if($totalWorkDate > $EmployeeLongtime && $totalHourEfforts < $nowWar1_3) {
                                $currentWar = 'Soldier';
                                $nextWar = 'Warrior 1';
                                $AllowanceWarrior = 0;
                                $timeIncreaseWar = $expectedWar1_3 - $totalHourEfforts;
                            } elseif ( $totalHourEfforts > $nowWar1_3 && $totalHourEfforts< $nowWar1) {
                                $currentWar = 'Warrior 1';
                                $nextWar = 'Warrior 2';
                                $AllowanceWarrior = 700000;
                                $timeHoldWar = $expectedWar1_3- $totalHourEfforts;
                                $timeIncreaseWar = $expectedWar1 - $totalHourEfforts;
                            } elseif ($totalHourEfforts > $nowWar1 && $totalHourEfforts< $nowWar2) {
                                $currentWar = 'Warrior 2';
                                $nextWar = 'Warrior 3';
                                $AllowanceWarrior = 900000;
                                $timeHoldWar = $expectedWar1 - $totalHourEfforts;
                                $timeIncreaseWar = $expectedWar2 - $totalHourEfforts;
                            } elseif ($totalHourEfforts > $nowWar2) {
                                $currentWar = 'Warrior 3';
                                $nextWar = 'Warrior 3';
                                $AllowanceWarrior = 1100000;
                                $timeHoldWar = $expectedWar2 - $totalHourEfforts;
                                $timeIncreaseWar =$expectedWar2 - $totalHourEfforts;
                            }
                        } else{
                            if($totalHourEfforts < $nowWar1) {
                                $currentWar = 'Soldier';
                                $nextWar = 'Warrior 1';
                                $AllowanceWarrior = 0;
                                $timeIncreaseWar = $expectedWar1 - $totalHourEfforts;
                            } elseif ($totalHourEfforts > $nowWar1 && $totalHourEfforts< $nowWar2) {
                                $currentWar = 'Warrior 1';
                                $nextWar = 'Warrior 2';
                                $AllowanceWarrior = 700000;
                                $timeHoldWar = $expectedWar1- $totalHourEfforts;
                                $timeIncreaseWar = $expectedWar2 - $totalHourEfforts;
                            } elseif ($totalHourEfforts > $nowWar2 && $totalHourEfforts< $nowWar3) {
                                $currentWar = 'Warrior 2';
                                $nextWar = 'Warrior 3';
                                $AllowanceWarrior = 900000;
                                $timeHoldWar = $expectedWar2 - $totalHourEfforts;
                                $timeIncreaseWar = $expectedWar3 - $totalHourEfforts;
                            } elseif ($totalHourEfforts > $nowWar3) {
                                $currentWar = 'Warrior 3';
                                $nextWar = 'Warrior 3';
                                $AllowanceWarrior = 1100000;
                                $timeHoldWar = $expectedWar3 - $totalHourEfforts;
                                $timeIncreaseWar = $expectedWar3 - $totalHourEfforts;
                            }
                        }

                        //$totalUnpaidLeave = count($range) - count($user->timeKeeping);
                        $avgTimeHoldWar = $timeHoldWar/$timeRange;
                        $avgTimeIncreaseWar = $timeIncreaseWar/$timeRange;
                        //$avgTimeHoldWar = $timeHoldWar/( $timeExpected['total'] - $timeNow['total']);
                        //$avgTimeIncreaseWar = $timeIncreaseWar/( $timeExpected['total'] - $timeNow['total']);

                    } else{
                        if($totalWorkDate > $EmployeeLongtime ){
                            if($totalWorkDate > $EmployeeLongtime && $totalHourEfforts < $nowWar1_3) {
                                $currentWar = 'Soldier';
                                $AllowanceWarrior = 0;
                            } elseif ( $totalHourEfforts > $nowWar1_3 && $totalHourEfforts< $nowWar1) {
                                $currentWar = 'Warrior 1';
                                $AllowanceWarrior = 700000;
                            } elseif ($totalHourEfforts > $nowWar1 && $totalHourEfforts< $nowWar2) {
                                $currentWar = 'Warrior 2';
                                $AllowanceWarrior = 900000;
                            } elseif ($totalHourEfforts > $nowWar2) {
                                $currentWar = 'Warrior 3';
                                $AllowanceWarrior = 1100000;
                            }
                        } else{
                            if($totalHourEfforts < $nowWar1) {
                                $currentWar = 'Soldier';
                                $AllowanceWarrior = 0;
                            } elseif ($totalHourEfforts > $nowWar1 && $totalHourEfforts< $nowWar2) {
                                $currentWar = 'Warrior 1';
                                $AllowanceWarrior = 700000;
                            } elseif ($totalHourEfforts > $nowWar2 && $totalHourEfforts< $nowWar3) {
                                $currentWar = 'Warrior 2';
                                $AllowanceWarrior = 900000;
                            } elseif ($totalHourEfforts > $nowWar3) {
                                $currentWar = 'Warrior 3';
                                $AllowanceWarrior = 1100000;
                            }
                        }
                    }
                    $totalUnpaidLeave = $timeNow['total'] - $totalTimeKeeping;
                    $rateGoLate = $totalWorkingDays? round(($totalGoLate/$totalWorkingDays), 4) * 100 : 0;

                    $date_official = new Datetime($user->date_official);
                    $date_official_new = date('d-m-Y', strtotime($user->date_official));

                    $date_now = new Datetime(date('Y-m-d', time()));
                    $totalWorkDate = ($date_official)->diff($date_now);

                    $wage_now = number_format($user->wage_now);
                    $AllowanceWar  = number_format($AllowanceWarrior);
                    $wage_actual = $user->wage_now*$totalTimeKeeping/$timeExpected['total'];
                    $wage_actual_format =number_format( $wage_actual);

                    $result[] = [

                        'fullname' => $user->fullname,
                        'id' => $user->id,
                        'date_official' => $user->date_official,
                        'date_official_new' => $date_official_new,
                        'wage_now' => $wage_now,
                        'AllowanceWarrior' => $AllowanceWar,
                        'wage_actual' => $wage_actual_format,
                        'totalWorkDateY' => $totalWorkDate->y,
                        'totalWorkDateM' => $totalWorkDate->m,
                        'totalWorkDateD' => $totalWorkDate->d,
                        //'totalWorkDateY' => intval($totalWorkDate/365),
                        //'totalWorkDateM' => intval(($totalWorkDate-(intval($totalWorkDate/365)*365))/30),
                        //'totalWorkDateD' => intval(($totalWorkDate-(intval($totalWorkDate/365)*365))-intval(($totalWorkDate-(intval($totalWorkDate/365)*365))/30)*30),
                        'totalGoLate' => $totalGoLate,
                        'timeGoLate' => round($timeGoLate/3600, 2),
                        'totalGoEarly' => $totalGoEarly,
                        'timeGoEarly' => $timeGoEarly/3600,
                        'totalAboutLate' => $totalAboutLate,
                        'timeAboutLate' => $timeAboutLate/3600,
                        'totalAboutEarly' => $totalAboutEarly,
                        'timeAboutEarly' => round($timeAboutEarly/3600, 2),
                        'totalTimeKeeping' => $totalTimeKeeping,
                        'totalWorkingDays' => $totalWorkingDays,
                        'totalUnpaidLeave' => $totalUnpaidLeave,
                        'totalHourEfforts' => $totalHourEfforts,
                        'currentWar' => $currentWar,
                        'nextWar' => $nextWar,
                        'timeHoldWar' => $timeHoldWar,
                        'timeIncreaseWar' => $timeIncreaseWar,
                        'avgTimeHoldWar' => $avgTimeHoldWar,
                        'avgTimeIncreaseWar' => $avgTimeIncreaseWar,
                        'rateGoLate' => $rateGoLate ?? 0,
                        'totalNotCheckIn' => $totalNotCheckIn,
                        'totalNotCheckOut' => $totalNotCheckOut,
                        'totalGoLateAboutEarly' => round(($timeGoLate/3600 + $timeAboutEarly/3600),2),
                    ];
                }
            }

            $expected = [
                'start_date' => $filters['start_date'],
                'end_date' => $filters['end_date'],
                'total' => $timeExpected['total'] ?? '',
                'warrior1_3' => $expectedWar1_3 ?? '',
                'warrior1' => $expectedWar1 ?? '',
                'warrior2' => $expectedWar2 ?? '',
                'warrior3' => $expectedWar3 ?? '',
            ];

            $current = [
                'start_date' => $filters['start_date'],
                //'end_date' => $filters['end_date'],
                'end_date' => end($keyArr),
                'total' => $timeNow['total'],
                'warrior1_3' => $nowWar1_3,
                'warrior1' => $nowWar1,
                'warrior2' => $nowWar2,
                'warrior3' => $nowWar3,
            ];
        } else {
            $users = \App\Models\User::getAllUser($filters);

            foreach ($users as $user) {
                if($user->user_status == 1 && $user->position != "Giám đốc") {
                    $result[] = [
                        'fullname' => $user->fullname,
                        'id' => $user->id,
                        'date_official' => $user->date_official,
                        //'totalWorkDate' => $totalWorkDate = $this ->timeTotal($user->date_official, date('Y-m-d', time()))['total'],
                        //'totalWorkDateY' => intval($totalWorkDate/365),
                        //'totalWorkDateM' => intval(($totalWorkDate-(intval($totalWorkDate/365)*365))/30),
                        //'totalWorkDateD' => intval(($totalWorkDate-(intval($totalWorkDate/365)*365))-intval(($totalWorkDate-(intval($totalWorkDate/365)*365))/30)*30),
                        'wage_now' => $user->wage_now,
                        'totalGoLate' => 0,
                        'timeGoLate' => 0,
                        'totalGoEarly' => 0,
                        'timeGoEarly' => 0,
                        'totalAboutLate' => 0,
                        'timeAboutLate' => 0,
                        'totalAboutEarly' => 0,
                        'timeAboutEarly' => 0,
                        'totalTimeKeeping' => 0,
                        'totalWorkingDays' => 0,
                        'totalUnpaidLeave' => 0,
                        'totalHourEfforts' => 0,
                        'currentWar' => 0,
                        'nextWar' => 0,
                        'timeHoldWar' => 0,
                        'timeIncreaseWar' => 0,
                        'avgTimeHoldWar' => 0,
                        'avgTimeIncreaseWar' => 0,
                        'rateGoLate' => 0,
                        'totalNotCheckIn' => 0,
                        'totalNotCheckOut' => 0,
                        'totalGoLateAboutEarly' => 0,
                    ];
                }
            }
        }

        return [
            'result' => $result,
            'expected' => $expected,
            'current' => $current
        ];
    }

    public function wage(array $filters)
    {
        $result = [];
        $expected = [];
        $current = [];

        if ($filters['start_date'] != '' && $filters['end_date'] != '' ) {
            $start_date = $filters['start_date'];
            $end_date =  date('Y-m-d',strtotime($filters['end_date']. ' +1 days'));

            $timeExpected = $this->timeReport($start_date, $end_date); //Tổng công chuẩn trong tháng

            if  (strtotime($filters['end_date']. ' +1 days') >= time() ) {
                $timeNow = $this->timeReport($start_date, date('Y-m-d', time())); // Tổng số công đã đi làm trong tháng
                $totalDayNow = $this->timeTotal($start_date, date('Y-m-d', time())); //Tổng số ngày đã đi làm trong tháng
            } else {
                $timeNow = $timeExpected; // Tổng số công đã đi làm trong tháng
                $totalDayNow = $this->timeTotal($start_date, $end_date); //Tổng số ngày đã đi làm trong tháng
            }

            $nowWar1_3 = $timeNow['total'] * 1;
            $nowWar1 = $timeNow['total'] * 2;
            $nowWar2 = $timeNow['total'] * 3;
            $nowWar3 = $timeNow['total'] * 4;

            $range = $timeNow['range'];

            $keyArr = array_keys($range);

            $users = \App\Models\User::getAllUser($filters, $range);

            $config = ConfigTimeKeeping::query()->where('code', '=', 'TIME')->first();
            if ($config && $config->settings) {
                $settings = json_decode($config->settings, true);
            }

            foreach ($users as $user) {

                if ($user->user_status == 1 && $user->position != "Giám đốc" ) {
                    $totalWorkDate = 0;

                    $totalGoLate = 0;
                    $timeGoLate = 0;
                    $totalGoEarly = 0;
                    $timeGoEarly = 0;

                    $totalAboutLate = 0;
                    $timeAboutLate = 0;
                    $totalAboutEarly = 0;
                    $timeAboutEarly = 0;

                    $totalTimeKeeping = 0;
                    $totalWorkingDays = 0;
                    $totalNotCheckIn = 0;
                    $totalNotCheckOut = 0;

                    foreach ($user->timeKeeping as $value) {
                        $labelDay = $range[$value->check_date];
                        $configDay = $settings[$labelDay] ?? [];

                        $checkIn = $value->checkin? strtotime($value->check_date. ' '. $value->checkin): '';
                        $checkOut = $value->checkout? strtotime($value->check_date. ' '. $value->checkout): '';

                        if ($configDay) {
                            $start = $configDay['start_time'] != ''? strtotime($value->check_date. ' '. $configDay['start_time']): '';
                            $end = $configDay['end_time'] != ''? strtotime($value->check_date. ' '. $configDay['end_time']): '';

                            if ($checkIn && $start && $checkIn > $start) {
                                $totalGoLate++;
                                $timeGoLate += $checkIn - $start;
                            } else if ($checkIn && $start && $checkIn < $start) {
                                $totalGoEarly++;
                                $timeGoEarly += $start - $checkIn;
                            }

                            if ($checkOut && $end && $checkOut > $end) {
                                $totalAboutLate++;
                                $timeAboutLate += $checkOut - $end;
                            } else if ($checkOut && $end && $checkOut < $end) {
                                $totalAboutEarly++;
                                $timeAboutEarly += $end - $checkOut;
                            }
                        }
                        if ($checkIn && $checkOut) {
                            switch ($labelDay) {
                                case 'monday':
                                case 'tuesday':
                                case 'wednesday':
                                case 'thursday':
                                case 'friday':
                                    $totalTimeKeeping++;
                                    break;
                                case 'saturday':
                                    $totalTimeKeeping = $totalTimeKeeping + 1/2;
                                    break;
                            }
                            switch ($labelDay) {
                                case 'monday':
                                case 'tuesday':
                                case 'wednesday':
                                case 'thursday':
                                case 'friday':
                                case 'saturday':
                                    $totalWorkingDays++;
                                    break;
                            }
                        } elseif ($checkIn && !$checkOut) {
                            $totalNotCheckOut++;
                        } elseif (!$checkIn && $checkOut) {
                            $totalNotCheckIn++;
                        }
                    }

                    $totalHourEfforts = (($timeGoEarly + $timeAboutLate) - ($timeGoLate + $timeAboutEarly))/3600;

                    $currentWar = '';
                    $EmployeeLongtime =1094;
                    $AllowanceWarrior = 0;

                    if($totalWorkDate > $EmployeeLongtime ){
                        if($totalHourEfforts < $nowWar1_3) {
                            $currentWar = 'Soldier';
                            $AllowanceWarrior = 0;
                        } elseif ( $totalHourEfforts > $nowWar1_3 && $totalHourEfforts< $nowWar1) {
                            $currentWar = 'Warrior 1';
                            $AllowanceWarrior = 700000;
                        } elseif ($totalHourEfforts > $nowWar1 && $totalHourEfforts< $nowWar2) {
                            $currentWar = 'Warrior 2';
                            $AllowanceWarrior = 900000;
                        } elseif ($totalHourEfforts > $nowWar2) {
                            $currentWar = 'Warrior 3';
                            $AllowanceWarrior = 1100000;
                        }
                    }else{
                        if($totalHourEfforts < $nowWar1) {
                            $currentWar = 'Soldier';
                            $AllowanceWarrior = 0;
                        } elseif ($totalHourEfforts > $nowWar1 && $totalHourEfforts< $nowWar2) {
                            $currentWar = 'Warrior 1';
                            $AllowanceWarrior = 700000;
                        } elseif ($totalHourEfforts > $nowWar2 && $totalHourEfforts< $nowWar3) {
                            $currentWar = 'Warrior 2';
                            $AllowanceWarrior = 900000;
                        } elseif ($totalHourEfforts > $nowWar3) {
                            $currentWar = 'Warrior 3';
                            $AllowanceWarrior = 1100000;
                        }
                    }
                    if(($timeNow['total'] - $totalTimeKeeping) >=1){
                        $totalPaidLeave = 1;
                    } else{
                        $totalPaidLeave = 0;
                    }
                    $totalUnpaidLeave = $timeNow['total'] - $totalTimeKeeping - $totalPaidLeave;
                    $totalNoWorkingDays = $totalDayNow['total'] - $totalWorkingDays;

                    $wage_now = number_format($user->wage_now);
                    $AllowanceWar  = number_format($AllowanceWarrior);

                    if( $user->date_official != ""){
                        $Social_Insurance = 1000000;
                    } else {
                        $Social_Insurance = 0;
                    }
                    $SocialInsurance  = number_format($Social_Insurance);

                    $wage_actual = $user->wage_now*($totalTimeKeeping +$totalPaidLeave)/$timeExpected['total'];
                    $wage_actual_format =number_format( $wage_actual);
                    if( $user->date_official != ""){
                        $Total_Wage = $wage_actual + $Social_Insurance + $AllowanceWarrior;
                    } else {
                        $Total_Wage = ($wage_actual + $AllowanceWarrior)*0.8;
                    }
                    $TotalWage = number_format($Total_Wage);
                    $TotalWage05 = number_format($Total_Wage*0.3);
                    $TotalWage20 = number_format($Total_Wage*0.7);

                    $result[] = [

                        'fullname' => $user->fullname,
                        'id' => $user->id,
                        'date_official' => $user->date_official,
                        'wage_now' => $wage_now,
                        'AllowanceWarrior' => $AllowanceWar,
                        'Social_Insurance' => $SocialInsurance,
                        'TotalWage'=> $TotalWage,
                        'TotalWage05'=> $TotalWage05,
                        'TotalWage20'=> $TotalWage20,
                        'wage_actual' => $wage_actual_format,
                        'totalGoLate' => $totalGoLate,
                        'timeGoLate' => round($timeGoLate/3600, 2),
                        'totalGoEarly' => $totalGoEarly,
                        'timeGoEarly' => $timeGoEarly/3600,
                        'totalAboutLate' => $totalAboutLate,
                        'timeAboutLate' => $timeAboutLate/3600,
                        'totalAboutEarly' => $totalAboutEarly,
                        'timeAboutEarly' => round($timeAboutEarly/3600, 2),
                        'totalTimeKeeping' => $totalTimeKeeping,
                        'totalWorkingDays' => $totalWorkingDays,
                        'totalNoWorkingDays' => $totalNoWorkingDays,
                        'totalUnpaidLeave' => $totalUnpaidLeave,
                        'totalPaidLeave' => $totalPaidLeave,
                        'totalHourEfforts' => $totalHourEfforts,
                        'currentWar' => $currentWar,
                        'totalNotCheckIn' => $totalNotCheckIn,
                        'totalNotCheckOut' => $totalNotCheckOut,
                        'totalGoLateAboutEarly' => round(($timeGoLate/3600 + $timeAboutEarly/3600),2),
                    ];
                }
            }

            $expected = [
                'start_date' => $filters['start_date'],
                'end_date' => $filters['end_date'],
                'total' => $timeExpected['total'] ?? '',
            ];

            $current = [
                'start_date' => $filters['start_date'],
                'start_month' => date('m-Y',strtotime($filters['start_date'])),
                'end_date' => end($keyArr),
                'total' => $timeNow['total'],
                'warrior1_3' => $nowWar1_3,
                'warrior1' => $nowWar1,
                'warrior2' => $nowWar2,
                'warrior3' => $nowWar3,
            ];
        } else {
            $users = \App\Models\User::getAllUser($filters);

            foreach ($users as $user) {
                if($user->user_status == 1 && $user->position != "Giám đốc") {
                    $result[] = [
                        'fullname' => $user->fullname,
                        'id' => $user->id,
                        'date_official' => $user->date_official,
                        'wage_now' => $user->wage_now,
                        'totalGoLate' => 0,
                        'timeGoLate' => 0,
                        'totalGoEarly' => 0,
                        'timeGoEarly' => 0,
                        'totalAboutLate' => 0,
                        'timeAboutLate' => 0,
                        'totalAboutEarly' => 0,
                        'timeAboutEarly' => 0,
                        'totalTimeKeeping' => 0,
                        'totalWorkingDays' => 0,
                        'totalNoWorkingDays' => 0,
                        'totalUnpaidLeave' => 0,
                        'totalHourEfforts' => 0,
                        'totalNotCheckIn' => 0,
                        'totalNotCheckOut' => 0,
                        'totalGoLateAboutEarly' => 0,
                    ];
                }
            }
        }

        return [
            'result' => $result,
            'expected' => $expected,
            'current' => $current
        ];

    }

    public function bonus(array $filters)
    {
        $result = [];
        $expected = [];
        $current = [];

        if ($filters['start_date'] != '' && $filters['end_date'] != '' ) {
            $start_date = $filters['start_date'];
            $end_date =  date('Y-m-d',strtotime($filters['end_date']. ' +1 days'));

            $timeExpected = $this->timeReport($start_date, $end_date);
            $expectedWar1_3 = $timeExpected['total'] * 1;
            $expectedWar1 = $timeExpected['total'] * 2;
            $expectedWar2 = $timeExpected['total'] * 3;
            $expectedWar3 = $timeExpected['total'] * 4;

            if  (strtotime($filters['end_date']. ' +1 days') >= time() ) {
                $timeNow = $this->timeReport($start_date, date('Y-m-d', time()));
            } else {
                $timeNow = $timeExpected;
            }

            $nowWar1_3 = $timeNow['total'] * 1;
            $nowWar1 = $timeNow['total'] * 2;
            $nowWar2 = $timeNow['total'] * 3;
            $nowWar3 = $timeNow['total'] * 4;

            $range = $timeNow['range'];

            $keyArr = array_keys($range);

            $diff = date_diff(date_create($filters['end_date']), date_create(end($keyArr)));

            $timeRange = $diff->format('%a');

            $users = \App\Models\User::getAllUser($filters, $range);

            $config = ConfigTimeKeeping::query()->where('code', '=', 'TIME')->first();

            if ($config && $config->settings) {
                $settings = json_decode($config->settings, true);
            }

            foreach ($users as $user) {

                if ($user->user_status == 1 && $user->position != "Giám đốc" ) {
                    $totalWorkDate = 0;

                    $totalGoLate = 0;
                    $timeGoLate = 0;
                    $totalGoEarly = 0;
                    $timeGoEarly = 0;

                    $totalAboutLate = 0;
                    $timeAboutLate = 0;
                    $totalAboutEarly = 0;
                    $timeAboutEarly = 0;

                    $totalTimeKeeping = 0;
                    $totalWorkingDays = 0;
                    $totalNotCheckIn = 0;
                    $totalNotCheckOut = 0;
                    $totalUnpaidLeave = 0;
                    $totalHourEfforts = 0;

                    foreach ($user->timeKeeping as $value) {
                        $labelDay = $range[$value->check_date];
                        $configDay = $settings[$labelDay] ?? [];

                        $checkIn = $value->checkin? strtotime($value->check_date. ' '. $value->checkin): '';
                        $checkOut = $value->checkout? strtotime($value->check_date. ' '. $value->checkout): '';

                        if ($configDay) {
                            $start = $configDay['start_time'] != ''? strtotime($value->check_date. ' '. $configDay['start_time']): '';
                            $end = $configDay['end_time'] != ''? strtotime($value->check_date. ' '. $configDay['end_time']): '';

                            if ($checkIn && $start && $checkIn > $start) {
                                $totalGoLate++;
                                $timeGoLate += $checkIn - $start;
                            } else if ($checkIn && $start && $checkIn < $start) {
                                $totalGoEarly++;
                                $timeGoEarly += $start - $checkIn;
                            }

                            if ($checkOut && $end && $checkOut > $end) {
                                $totalAboutLate++;
                                $timeAboutLate += $checkOut - $end;
                            } else if ($checkOut && $end && $checkOut < $end) {
                                $totalAboutEarly++;
                                $timeAboutEarly += $end - $checkOut;
                            }
                        }

                        if ($checkIn && $checkOut) {
                            switch ($labelDay) {
                                case 'monday':
                                case 'tuesday':
                                case 'wednesday':
                                case 'thursday':
                                case 'friday':
                                    $totalTimeKeeping++;
                                    break;
                                case 'saturday':
                                    $totalTimeKeeping = $totalTimeKeeping + 1/2;
                                    break;
                            }
                            switch ($labelDay) {
                                case 'monday':
                                case 'tuesday':
                                case 'wednesday':
                                case 'thursday':
                                case 'friday':
                                case 'saturday':
                                    $totalWorkingDays++;
                                    break;
                            }
                        }  elseif ($checkIn && !$checkOut) {
                            $totalNotCheckOut++;
                        } elseif (!$checkIn && $checkOut) {
                            $totalNotCheckIn++;
                        }
                    }

                    $totalHourEfforts = (($timeGoEarly + $timeAboutLate) - ($timeGoLate + $timeAboutEarly))/3600;

                    $currentWar = '';
                    $nextWar = '';
                    $timeHoldWar = 0;
                    $timeIncreaseWar = 0;
                    $avgTimeHoldWar = 0;
                    $avgTimeIncreaseWar = 0;
                    $EmployeeLongtime =1094;
                    $AllowanceWarrior = 0;

                    if ($timeRange) {
                        if($totalWorkDate > $EmployeeLongtime ){
                            if($totalWorkDate > $EmployeeLongtime && $totalHourEfforts < $nowWar1_3) {
                                $currentWar = 'Soldier';
                                $nextWar = 'Warrior 1';
                                $AllowanceWarrior = 0;
                                $timeIncreaseWar = $expectedWar1_3 - $totalHourEfforts;
                            } elseif ( $totalHourEfforts > $nowWar1_3 && $totalHourEfforts< $nowWar1) {
                                $currentWar = 'Warrior 1';
                                $nextWar = 'Warrior 2';
                                $AllowanceWarrior = 700000;
                                $timeHoldWar = $expectedWar1_3- $totalHourEfforts;
                                $timeIncreaseWar = $expectedWar1 - $totalHourEfforts;
                            } elseif ($totalHourEfforts > $nowWar1 && $totalHourEfforts< $nowWar2) {
                                $currentWar = 'Warrior 2';
                                $nextWar = 'Warrior 3';
                                $AllowanceWarrior = 900000;
                                $timeHoldWar = $expectedWar1 - $totalHourEfforts;
                                $timeIncreaseWar = $expectedWar2 - $totalHourEfforts;
                            } elseif ($totalHourEfforts > $nowWar2) {
                                $currentWar = 'Warrior 3';
                                $nextWar = 'Warrior 3';
                                $AllowanceWarrior = 1100000;
                                $timeHoldWar = $expectedWar2 - $totalHourEfforts;
                                $timeIncreaseWar =$expectedWar2 - $totalHourEfforts;
                            }
                        } else{
                            if($totalHourEfforts < $nowWar1) {
                                $currentWar = 'Soldier';
                                $nextWar = 'Warrior 1';
                                $AllowanceWarrior = 0;
                                $timeIncreaseWar = $expectedWar1 - $totalHourEfforts;
                            } elseif ($totalHourEfforts > $nowWar1 && $totalHourEfforts< $nowWar2) {
                                $currentWar = 'Warrior 1';
                                $nextWar = 'Warrior 2';
                                $AllowanceWarrior = 700000;
                                $timeHoldWar = $expectedWar1- $totalHourEfforts;
                                $timeIncreaseWar = $expectedWar2 - $totalHourEfforts;
                            } elseif ($totalHourEfforts > $nowWar2 && $totalHourEfforts< $nowWar3) {
                                $currentWar = 'Warrior 2';
                                $nextWar = 'Warrior 3';
                                $AllowanceWarrior = 900000;
                                $timeHoldWar = $expectedWar2 - $totalHourEfforts;
                                $timeIncreaseWar = $expectedWar3 - $totalHourEfforts;
                            } elseif ($totalHourEfforts > $nowWar3) {
                                $currentWar = 'Warrior 3';
                                $nextWar = 'Warrior 3';
                                $AllowanceWarrior = 1100000;
                                $timeHoldWar = $expectedWar3 - $totalHourEfforts;
                                $timeIncreaseWar = $expectedWar3 - $totalHourEfforts;
                            }
                        }

                        $avgTimeHoldWar = $timeHoldWar/$timeRange;
                        $avgTimeIncreaseWar = $timeIncreaseWar/$timeRange;

                    } else{
                        if($totalWorkDate > $EmployeeLongtime ){
                            if($totalWorkDate > $EmployeeLongtime && $totalHourEfforts < $nowWar1_3) {
                                $currentWar = 'Soldier';
                                $AllowanceWarrior = 0;
                            } elseif ( $totalHourEfforts > $nowWar1_3 && $totalHourEfforts< $nowWar1) {
                                $currentWar = 'Warrior 1';
                                $AllowanceWarrior = 700000;
                            } elseif ($totalHourEfforts > $nowWar1 && $totalHourEfforts< $nowWar2) {
                                $currentWar = 'Warrior 2';
                                $AllowanceWarrior = 900000;
                            } elseif ($totalHourEfforts > $nowWar2) {
                                $currentWar = 'Warrior 3';
                                $AllowanceWarrior = 1100000;
                            }
                        } else{
                            if($totalHourEfforts < $nowWar1) {
                                $currentWar = 'Soldier';
                                $AllowanceWarrior = 0;
                            } elseif ($totalHourEfforts > $nowWar1 && $totalHourEfforts< $nowWar2) {
                                $currentWar = 'Warrior 1';
                                $AllowanceWarrior = 700000;
                            } elseif ($totalHourEfforts > $nowWar2 && $totalHourEfforts< $nowWar3) {
                                $currentWar = 'Warrior 2';
                                $AllowanceWarrior = 900000;
                            } elseif ($totalHourEfforts > $nowWar3) {
                                $currentWar = 'Warrior 3';
                                $AllowanceWarrior = 1100000;
                            }
                        }
                    }
                    $totalUnpaidLeave = $timeNow['total'] - $totalTimeKeeping;
                    $rateGoLate = $totalWorkingDays? round(($totalGoLate/$totalWorkingDays), 4) * 100 : 0;

                    $date_official = new Datetime($user->date_official);
                    $date_official_new = date('d-m-Y', strtotime($user->date_official));

                    $date_now = new Datetime(date('Y-m-d', time()));
                    $totalWorkDate = ($date_official)->diff($date_now);

                    $wage_now = number_format($user->wage_now);
                    $AllowanceWar  = number_format($AllowanceWarrior);
                    $wage_actual = $user->wage_now*$totalTimeKeeping/$timeExpected['total'];
                    $wage_actual_format =number_format( $wage_actual);

                    $result[] = [

                        'fullname' => $user->fullname,
                        'id' => $user->id,
                        'date_official' => $user->date_official,
                        'date_official_new' => $date_official_new,
                        'wage_now' => $wage_now,
                        'AllowanceWarrior' => $AllowanceWar,
                        'wage_actual' => $wage_actual_format,
                        'totalWorkDateY' => $totalWorkDate->y,
                        'totalWorkDateM' => $totalWorkDate->m,
                        'totalWorkDateD' => $totalWorkDate->d,
                        'totalGoLate' => $totalGoLate,
                        'timeGoLate' => round($timeGoLate/3600, 2),
                        'totalGoEarly' => $totalGoEarly,
                        'timeGoEarly' => $timeGoEarly/3600,
                        'totalAboutLate' => $totalAboutLate,
                        'timeAboutLate' => $timeAboutLate/3600,
                        'totalAboutEarly' => $totalAboutEarly,
                        'timeAboutEarly' => round($timeAboutEarly/3600, 2),
                        'totalTimeKeeping' => $totalTimeKeeping,
                        'totalWorkingDays' => $totalWorkingDays,
                        'totalUnpaidLeave' => $totalUnpaidLeave,
                        'totalHourEfforts' => $totalHourEfforts,
                        'currentWar' => $currentWar,
                        'nextWar' => $nextWar,
                        'timeHoldWar' => $timeHoldWar,
                        'timeIncreaseWar' => $timeIncreaseWar,
                        'avgTimeHoldWar' => $avgTimeHoldWar,
                        'avgTimeIncreaseWar' => $avgTimeIncreaseWar,
                        'rateGoLate' => $rateGoLate ?? 0,
                        'totalNotCheckIn' => $totalNotCheckIn,
                        'totalNotCheckOut' => $totalNotCheckOut,
                        'totalGoLateAboutEarly' => round(($timeGoLate/3600 + $timeAboutEarly/3600),2),
                    ];
                }
            }

            $expected = [
                'start_date' => $filters['start_date'],
                'end_date' => $filters['end_date'],
                'total' => $timeExpected['total'] ?? '',
                'warrior1_3' => $expectedWar1_3 ?? '',
                'warrior1' => $expectedWar1 ?? '',
                'warrior2' => $expectedWar2 ?? '',
                'warrior3' => $expectedWar3 ?? '',
            ];

            $current = [
                'start_date' => $filters['start_date'],
                'start_month' => date('m - Y',strtotime($filters['start_date'])),
                'end_date' => end($keyArr),
                'total' => $timeNow['total'],
                'warrior1_3' => $nowWar1_3,
                'warrior1' => $nowWar1,
                'warrior2' => $nowWar2,
                'warrior3' => $nowWar3,
            ];
        } else {
            $users = \App\Models\User::getAllUser($filters);

            foreach ($users as $user) {
                if($user->user_status == 1 && $user->position != "Giám đốc") {
                    $result[] = [
                        'fullname' => $user->fullname,
                        'id' => $user->id,
                        'date_official' => $user->date_official,
                        'wage_now' => $user->wage_now,
                        'totalGoLate' => 0,
                        'timeGoLate' => 0,
                        'totalGoEarly' => 0,
                        'timeGoEarly' => 0,
                        'totalAboutLate' => 0,
                        'timeAboutLate' => 0,
                        'totalAboutEarly' => 0,
                        'timeAboutEarly' => 0,
                        'totalTimeKeeping' => 0,
                        'totalWorkingDays' => 0,
                        'totalUnpaidLeave' => 0,
                        'totalHourEfforts' => 0,
                        'currentWar' => 0,
                        'nextWar' => 0,
                        'timeHoldWar' => 0,
                        'timeIncreaseWar' => 0,
                        'avgTimeHoldWar' => 0,
                        'avgTimeIncreaseWar' => 0,
                        'rateGoLate' => 0,
                        'totalNotCheckIn' => 0,
                        'totalNotCheckOut' => 0,
                        'totalGoLateAboutEarly' => 0,
                    ];
                }
            }
        }

        return [
            'result' => $result,
            'expected' => $expected,
            'current' => $current
        ];

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

    /**
     * @param string $start_date
     * @param string $end_date
     * @return array
     * @throws \Exception
     */
    private function timeReport(string $start_date, string $end_date): array
    {
        $period = new DatePeriod(
            new DateTime($start_date),
            new DateInterval('P1D'),
            new DateTime($end_date)
        );

        $totalDate = 0;
        $dateRange = [];

        foreach ($period as $key => $value) {

            $day = $value->format('Y-m-d');
            $dateRange[$day] = $day;
            $dayLabel = lcfirst(date('l', strtotime($day)));
            $dateRange[$day] = $dayLabel;

            switch ($dayLabel) {
                case 'monday':
                case 'tuesday':
                case 'wednesday':
                case 'thursday':
                case 'friday':
                    $totalDate++;
                    break;
                case 'saturday':
                    $totalDate = $totalDate + 1/2;
                    break;
            }
        }

        return [
            'total' => $totalDate,
            'range' => $dateRange
        ];
    }
    /**
     * private function timeReport2(string $start_date, string $end_date): array
    {
        $period = new DatePeriod(
            new DateTime($start_date),
            new DateInterval('P1D'),
            new DateTime($end_date)
        );

        $totalDate = 0;
        $dateRange = [];

        foreach ($period as $key => $value) {

            $day = $value->format('Y-m-d');
            $dateRange[$day] = $day;
            $dayLabel = lcfirst(date('l', strtotime($day)));
            $dateRange[$day] = $dayLabel;

            switch ($dayLabel) {
                case 'monday':
                case 'tuesday':
                case 'wednesday':
                case 'thursday':
                case 'friday':
                case 'saturday' :
                    $totalDate++;
                    break;
            }
        }

        return [
            'total' => $totalDate,
            'range' => $dateRange
        ];
    }*/
    private function timeTotal(string $start_date, string $end_date): array
    {
        $period = new DatePeriod(
            new DateTime($start_date),
            new DateInterval('P1D'),
            new DateTime($end_date)
        );

        $totalDate = 0;
        $dateRange = [];

        foreach ($period as $key => $value) {

            $day = $value->format('Y-m-d');
            $dateRange[$day] = $day;
            $dayLabel = lcfirst(date('l', strtotime($day)));
            $dateRange[$day] = $dayLabel;

            switch ($dayLabel) {
                case 'monday':
                case 'tuesday':
                case 'wednesday':
                case 'thursday':
                case 'friday':
                case 'saturday':
                    $totalDate++;
                    break;

            }
        }

        return [
            'total' => $totalDate,
            'range' => $dateRange
        ];
    }
}
