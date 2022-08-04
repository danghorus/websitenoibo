<?php

namespace App\Services;

use App\Models\ConfigTimeKeeping;
use App\Models\DeviceTimeKeeping;
use App\Models\TimeKeeping;
use App\Models\TimeKeepingDetail;
use App\Models\TimeKeepingPetition;
use App\Models\Petition;
use App\Models\User;
use App\Models\Holiday;
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

        $holidays = \App\Models\Holiday::all();

        $config = ConfigTimeKeeping::query()->where('code', '=', 'TIME')->first();
        if ($config && $config->settings) {
            $settings = json_decode($config->settings, true);
        } else 

        $result = [];
         
        $arrHoliday = [];

        foreach ($holidays as $holiday) {
            $labelHoliday = [];
            $rangeHoliday = $this->getLabelTimeKeeping($holiday->holiday_date_start, date('Y-m-d',strtotime('+1 days', strtotime($holiday->holiday_date_end))), $labelHoliday);
            foreach($rangeHoliday as $key => $val) {
                $arrHoliday[] = $key;
            }
        }
            
            foreach ($users as $user) {
                if ($user->user_status == 1 && $user->position != "Giám đốc") {
                    $tmp = [];
                    foreach ($range as $key => $day) {
                        $tmp[$key] = [];
                        $tmp[$key]['day'] = $key;
                        if ($user->timeKeeping) {
                            foreach ($user->timeKeeping as $time) {
                                if(in_array($key, $arrHoliday)){
                                    $tmp[$key]['holiday'] = 1;

                                    if($time->check_date == $key){
                                        if($time->petition_type == 5 ){    
                                            $tmp[$key]['petition_type'] = $time->petition_type;
                                            $tmp[$key]['type_leave'] = $time->type_leave;
                                            $tmp[$key]['checkin'] = $time->checkin? date('H:i', strtotime($key. ' '. $time->checkin)) : '-:-';
                                            $tmp[$key]['checkout'] = $time->checkout? date('H:i', strtotime($key. ' '. $time->checkout)) : '-:-';

                                            $configTimeKeepingDay = $settings[$day]?? [];
                                            $checkIn = $time->checkin? strtotime($key. ' '. $time->checkin): '';
                                            $checkOut = $time->checkout? strtotime($key. ' '. $time->checkout): '';

                                            if ($configTimeKeepingDay && $configTimeKeepingDay['start_timeAM'] && $configTimeKeepingDay['end_timePM']) {
                                                $start = $configTimeKeepingDay['start_timeAM'] != ''? strtotime($key. ' '. $configTimeKeepingDay['start_timeAM']): '';
                                                $end = $configTimeKeepingDay['end_timePM'] != ''? strtotime($key. ' '. $configTimeKeepingDay['end_timePM']): '';
                                            }else if($configTimeKeepingDay['start_timeAM'] == '' && $configTimeKeepingDay['end_timePM'] == ''){
                                                $start = '08:00';
                                                $end = '17:30';
                                            }
                
                                            $tmp[$key]['class'] = 'table-primary';
                                            
                                            if ($checkIn && $start) {
                                                if ($checkIn <= $start) {
                                                    $tmp[$key]['go_early_0'] = ($start - $checkIn);
                                                    $tmp[$key]['go_early'] = (int) (($start - $checkIn) / 60);
                                                    $tmp[$key]['go_late'] = 0;
                                                } else {
                                                    $tmp[$key]['go_early'] = 0;
                                                    $tmp[$key]['go_late'] = (int) (($checkIn - $start) / 60);
                                                    $tmp[$key]['go_late_0'] =($checkIn - $start);
                                                }
                                            }
                                            if ($checkOut && $end) {
                                                if ($checkOut <= $end) {
                                                    $tmp[$key]['about_early_0'] =($end - $checkOut);
                                                    $tmp[$key]['about_early'] = (int) (($end - $checkOut) / 60);
                                                    $tmp[$key]['about_late'] = 0;
                                                } else {
                                                    $tmp[$key]['about_early'] = 0;
                                                    $tmp[$key]['about_late'] = (int) (($checkOut - $end) / 60);
                                                    $tmp[$key]['about_late_0'] =($checkOut - $end);
                                                }
                                            }
                                        
                                        }
                                        if ( $time->petition_type == 6) {
                                            $tmp[$key]['petition_type'] = $time->petition_type;
                                            $tmp[$key]['type_leave'] = $time->type_leave;
                                            $tmp[$key]['time_from'] = $time->time_from? date('H:i', strtotime($key. ' '. $time->time_from)) : '-:-';
                                            $tmp[$key]['time_to'] = $time->time_to? date('H:i', strtotime($key. ' '. $time->time_to)) : '-:-';
                                            $tmp[$key]['checkin'] = $time->checkin? date('H:i', strtotime($key. ' '. $time->checkin)) : '-:-';
                                            $tmp[$key]['checkout'] = $time->checkout? date('H:i', strtotime($key. ' '. $time->checkout)) : '-:-';

                                            $configTimeKeepingDay = $settings[$day]?? [];

                                            if ($configTimeKeepingDay && $configTimeKeepingDay['start_timeAM'] && $configTimeKeepingDay['end_timePM']) {
                                                $checkIn = $time->checkin? strtotime($key. ' '. $time->checkin): '';
                                                $checkOut = $time->checkout? strtotime($key. ' '. $time->checkout): '';

                                                $start = $configTimeKeepingDay['start_timeAM'] != ''? strtotime($key. ' '. $configTimeKeepingDay['start_timeAM']): '';
                                                $end = $configTimeKeepingDay['end_timePM'] != ''? strtotime($key. ' '. $configTimeKeepingDay['end_timePM']): '';
                
                                                $tmp[$key]['class'] = 'bg-info';
                                                
                                                if ($checkIn && $checkOut) {
                                                    $tmp[$key]['go_early_0'] = ($checkOut - $checkIn);
                                                    $tmp[$key]['go_early'] = (int) (($checkOut - $checkIn) / 60);
                                                    $tmp[$key]['go_late'] = 0;
                                                }
                                            }
                                        }
                                    }

                                }else{

                                if ($time->check_date == $key && ($time->petition_type == 0 || $time->petition_type == 1 || $time->petition_type == 4 )) {
                                    $tmp[$key]['petition_type'] = $time->petition_type;
                                    $tmp[$key]['label_day'] = $range[$time->check_date];
                                    $tmp[$key]['type_leave'] = $time->type_leave;
                                    $tmp[$key]['time_from'] = $time->time_from? date('H:i', strtotime($key. ' '. $time->time_from)) : '-:-';
                                    $tmp[$key]['time_to'] = $time->time_to? date('H:i', strtotime($key. ' '. $time->time_to)) : '-:-';
                                    $tmp[$key]['checkin'] = $time->checkin? date('H:i', strtotime($key. ' '. $time->checkin)) : '-:-';
                                    $tmp[$key]['checkout'] = $time->checkout? date('H:i', strtotime($key. ' '. $time->checkout)) : '-:-';

                                    $configTimeKeepingDay = $settings[$day]?? [];

                                    if ($configTimeKeepingDay && $configTimeKeepingDay['start_timeAM'] && $configTimeKeepingDay['end_timePM']) {
                                        $checkIn = $time->checkin? strtotime($key. ' '. $time->checkin): '';
                                        $checkOut = $time->checkout? strtotime($key. ' '. $time->checkout): '';
                                        $start = $configTimeKeepingDay['start_timeAM'] != ''? strtotime($key. ' '. $configTimeKeepingDay['start_timeAM']): '';
                                        $end = $configTimeKeepingDay['end_timePM'] != ''? strtotime($key. ' '. $configTimeKeepingDay['end_timePM']): '';
                                        if (($checkIn && !$checkOut) || (!$checkIn && $checkOut)) {
                                            $tmp[$key]['class'] = 'text-light bg-danger';
                                        } elseif ($checkIn && $checkOut && $checkIn <= $start && $checkOut >= $end) {
                                            $tmp[$key]['class'] = 'text-light bg-success';
                                        } elseif (($checkIn && $checkIn > $start) || ($checkOut && $checkOut < $end)) {
                                            $tmp[$key]['class'] = 'text-dark bg-warning';
                                        }
                                        if ($checkIn && $start) {
                                            if ($checkIn <= $start) {
                                                $tmp[$key]['go_early_0'] = ($start - $checkIn);
                                                $tmp[$key]['go_early'] = (int) (($start - $checkIn) / 60);
                                                $tmp[$key]['go_late'] = 0;
                                            } else {
                                                $tmp[$key]['go_early'] = 0;
                                                $tmp[$key]['go_late'] = (int) (($checkIn - $start) / 60);
                                                $tmp[$key]['go_late_0'] =($checkIn - $start);
                                            }
                                        }
                                        if ($checkOut && $end) {
                                            if ($checkOut <= $end) {
                                                $tmp[$key]['about_early_0'] =($end - $checkOut);
                                                $tmp[$key]['about_early'] = (int) (($end - $checkOut) / 60);
                                                $tmp[$key]['about_late'] = 0;
                                            } else {
                                                $tmp[$key]['about_early'] = 0;
                                                $tmp[$key]['about_late'] = (int) (($checkOut - $end) / 60);
                                                $tmp[$key]['about_late_0'] =($checkOut - $end);
                                            }
                                        }
                                    }
                                } else if ((($time->check_date <= $key && $key <= $time->date_to) &&  $time->petition_type == 2) ||($time->check_date == $key && $time->petition_type == 2)) {
                                    if($time->type_leave == 1){
                                        $tmp[$key]['petition_type'] = $time->petition_type;
                                        $tmp[$key]['type_leave'] = $time->type_leave;
                                        $tmp[$key]['time_from'] = $time->time_from? date('H:i', strtotime($key. ' '. $time->time_from)) : '-:-';
                                        $tmp[$key]['time_to'] = $time->time_to? date('H:i', strtotime($key. ' '. $time->time_to)) : '-:-';
                                        $tmp[$key]['checkin'] = $time->checkin? date('H:i', strtotime($key. ' '. $time->checkin)) : '-:-';
                                        $tmp[$key]['checkout'] = $time->checkout? date('H:i', strtotime($key. ' '. $time->checkout)) : '-:-';
                                    
                                        $tmp[$key]['class'] = 'text-light bg-secondary';

                                        $tmp[$key]['checkin'] =  $time->checkin ? date('H:i', strtotime($key. ' '. $time->checkin)): '-:-';
                                        $tmp[$key]['checkout'] = $time->checkout ? date('H:i', strtotime($key. ' '. $time->checkout)): '-:-';

                                        $configTimeKeepingDay = $settings[$day]?? [];

                                        if ($configTimeKeepingDay && $configTimeKeepingDay['start_timePM'] && $configTimeKeepingDay['end_timePM']) {
                                            $checkIn = $time->checkin? strtotime($key. ' '. $time->checkin): '';
                                            $checkOut = $time->checkout? strtotime($key. ' '. $time->checkout): '';
                                            
                                            $start = $configTimeKeepingDay['start_timePM'] != ''? strtotime($key. ' '. $configTimeKeepingDay['start_timePM']): '';
                                            $end = $configTimeKeepingDay['end_timePM'] != ''? strtotime($key. ' '. $configTimeKeepingDay['end_timePM']): '';

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
                                    } else if ($time->type_leave == 2) {
                                        $tmp[$key]['petition_type'] = $time->petition_type;
                                        $tmp[$key]['type_leave'] = $time->type_leave;
                                        $tmp[$key]['time_from'] = $time->time_from? date('H:i', strtotime($key. ' '. $time->time_from)) : '-:-';
                                        $tmp[$key]['time_to'] = $time->time_to? date('H:i', strtotime($key. ' '. $time->time_to)) : '-:-';
                                        $tmp[$key]['checkin'] = $time->checkin? date('H:i', strtotime($key. ' '. $time->checkin)) : '-:-';
                                        $tmp[$key]['checkout'] = $time->checkout? date('H:i', strtotime($key. ' '. $time->checkout)) : '-:-';
                                    
                                        $tmp[$key]['class'] = ' text-light bg-secondary';

                                        $tmp[$key]['checkin'] =  $time->checkin ? date('H:i', strtotime($key. ' '. $time->checkin)): '-:-';
                                        $tmp[$key]['checkout'] = $time->checkout ? date('H:i', strtotime($key. ' '. $time->checkout)): '-:-';

                                        $configTimeKeepingDay = $settings[$day]?? [];

                                        if ($configTimeKeepingDay && $configTimeKeepingDay['start_timeAM'] && $configTimeKeepingDay['end_timeAM']) {
                                            $checkIn = $time->checkin? strtotime($key. ' '. $time->checkin): '';
                                            $checkOut = $time->checkout? strtotime($key. ' '. $time->checkout): '';
                                            
                                            $start = $configTimeKeepingDay['start_timeAM'] != ''? strtotime($key. ' '. $configTimeKeepingDay['start_timeAM']): '';
                                            $end = $configTimeKeepingDay['end_timeAM'] != ''? strtotime($key. ' '. $configTimeKeepingDay['end_timeAM']): '';

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
                                    } else if ($time->type_leave == 3 || $time->type_leave == 4) {
                                        $tmp[$key]['petition_type'] = $time->petition_type;
                                        $tmp[$key]['type_leave'] = $time->type_leave;
                                        $tmp[$key]['time_from'] = $time->time_from? date('H:i', strtotime($key. ' '. $time->time_from)) : '-:-';
                                        $tmp[$key]['time_to'] = $time->time_to? date('H:i', strtotime($key. ' '. $time->time_to)) : '-:-';
                                        $tmp[$key]['checkin'] = $time->checkin? date('H:i', strtotime($key. ' '. $time->checkin)) : '-:-';
                                        $tmp[$key]['checkout'] = $time->checkout? date('H:i', strtotime($key. ' '. $time->checkout)) : '-:-';
                                    
                                        $tmp[$key]['class'] = 'text-light bg-dark';

                                        $tmp[$key]['checkin'] =  $time->checkin ? date('H:i', strtotime($key. ' '. $time->checkin)): '-:-';
                                        $tmp[$key]['checkout'] = $time->checkout ? date('H:i', strtotime($key. ' '. $time->checkout)): '-:-';

                                        $configTimeKeepingDay = $settings[$day]?? [];

                                        if ($configTimeKeepingDay && $configTimeKeepingDay['start_timeAM'] && $configTimeKeepingDay['end_timePM']) {
                                            $checkIn = $time->checkin? strtotime($key. ' '. $time->checkin): '';
                                            $checkOut = $time->checkout? strtotime($key. ' '. $time->checkout): '';
                                            
                                            $start = $configTimeKeepingDay['start_timeAM'] != ''? strtotime($key. ' '. $configTimeKeepingDay['start_timeAM']): '';
                                            $end = $configTimeKeepingDay['end_timePM'] != ''? strtotime($key. ' '. $configTimeKeepingDay['end_timePM']): '';

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
                                } else if (($time->check_date == $key  && $time->petition_type == 5) || (($time->check_date <= $key && $key <= $time->date_to) &&  $time->petition_type == 5)) {
                                    $tmp[$key]['petition_type'] = $time->petition_type;
                                    $tmp[$key]['type_leave'] = $time->type_leave;
                                    $tmp[$key]['time_from'] = $time->time_from? date('H:i', strtotime($key. ' '. $time->time_from)) : '-:-';
                                    $tmp[$key]['time_to'] = $time->time_to? date('H:i', strtotime($key. ' '. $time->time_to)) : '-:-';
                                    $tmp[$key]['checkin'] = $time->checkin? date('H:i', strtotime($key. ' '. $time->checkin)) : '-:-';
                                    $tmp[$key]['checkout'] = $time->checkout? date('H:i', strtotime($key. ' '. $time->checkout)) : '-:-';

                                    $configTimeKeepingDay = $settings[$day]?? [];
                                    $checkIn = $time->checkin? strtotime($key. ' '. $time->checkin): '';
                                    $checkOut = $time->checkout? strtotime($key. ' '. $time->checkout): '';

                                    if ($configTimeKeepingDay && $configTimeKeepingDay['start_timeAM'] && $configTimeKeepingDay['end_timePM']) {
                                        $start = $configTimeKeepingDay['start_timeAM'] != ''? strtotime($key. ' '. $configTimeKeepingDay['start_timeAM']): '';
                                        $end = $configTimeKeepingDay['end_timePM'] != ''? strtotime($key. ' '. $configTimeKeepingDay['end_timePM']): '';
                                    }else if($configTimeKeepingDay['start_timeAM'] == '' && $configTimeKeepingDay['end_timePM'] == ''){
                                        $start = '08:00';
                                        $end = '17:30';
                                    }
        
                                    $tmp[$key]['class'] = 'table-primary';
                                    
                                    if ($checkIn && $start) {
                                        if ($checkIn <= $start) {
                                            $tmp[$key]['go_early_0'] = ($start - $checkIn);
                                            $tmp[$key]['go_early'] = (int) (($start - $checkIn) / 60);
                                            $tmp[$key]['go_late'] = 0;
                                        } else {
                                            $tmp[$key]['go_early'] = 0;
                                            $tmp[$key]['go_late'] = (int) (($checkIn - $start) / 60);
                                            $tmp[$key]['go_late_0'] =($checkIn - $start);
                                        }
                                    }
                                    if ($checkOut && $end) {
                                        if ($checkOut <= $end) {
                                            $tmp[$key]['about_early_0'] =($end - $checkOut);
                                            $tmp[$key]['about_early'] = (int) (($end - $checkOut) / 60);
                                            $tmp[$key]['about_late'] = 0;
                                        } else {
                                            $tmp[$key]['about_early'] = 0;
                                            $tmp[$key]['about_late'] = (int) (($checkOut - $end) / 60);
                                            $tmp[$key]['about_late_0'] =($checkOut - $end);
                                        }
                                    }
                                } else if ($time->check_date == $key  && $time->petition_type == 6) {
                                    $tmp[$key]['petition_type'] = $time->petition_type;
                                    $tmp[$key]['type_leave'] = $time->type_leave;
                                    $tmp[$key]['time_from'] = $time->time_from? date('H:i', strtotime($key. ' '. $time->time_from)) : '-:-';
                                    $tmp[$key]['time_to'] = $time->time_to? date('H:i', strtotime($key. ' '. $time->time_to)) : '-:-';
                                    $tmp[$key]['checkin'] = $time->checkin? date('H:i', strtotime($key. ' '. $time->checkin)) : '-:-';
                                    $tmp[$key]['checkout'] = $time->checkout? date('H:i', strtotime($key. ' '. $time->checkout)) : '-:-';

                                    $configTimeKeepingDay = $settings[$day]?? [];

                                    if ($configTimeKeepingDay && $configTimeKeepingDay['start_timeAM'] && $configTimeKeepingDay['end_timePM']) {
                                        $checkIn = $time->checkin? strtotime($key. ' '. $time->checkin): '';
                                        $checkOut = $time->checkout? strtotime($key. ' '. $time->checkout): '';

                                        $start = $configTimeKeepingDay['start_timeAM'] != ''? strtotime($key. ' '. $configTimeKeepingDay['start_timeAM']): '';
                                        $end = $configTimeKeepingDay['end_timePM'] != ''? strtotime($key. ' '. $configTimeKeepingDay['end_timePM']): '';
        
                                        $tmp[$key]['class'] = 'bg-info';
                                        
                                        if ($checkIn && $checkOut) {
                                            $tmp[$key]['go_early_0'] = ($checkOut - $checkIn);
                                            $tmp[$key]['go_early'] = (int) (($checkOut - $checkIn) / 60);
                                            $tmp[$key]['go_late'] = 0;
                                        }
                                    }
                                }else if ($time->check_date == $key  && $time->petition_type == 7) {
                                    $tmp[$key]['petition_type'] = $time->petition_type;
                                    $tmp[$key]['type_leave'] = $time->type_leave;
                                    $tmp[$key]['time_from'] = $time->time_from? date('H:i', strtotime($key. ' '. $time->time_from)) : '-:-';
                                    $tmp[$key]['time_to'] = $time->time_to? date('H:i', strtotime($key. ' '. $time->time_to)) : '-:-';
                                    $tmp[$key]['checkin'] = $time->checkin? date('H:i', strtotime($key. ' '. $time->checkin)) : '-:-';
                                    $tmp[$key]['checkout'] = $time->checkout? date('H:i', strtotime($key. ' '. $time->checkout)) : '-:-';

                                    $tmp[$key]['class'] = 'table-secondary';

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
                $detail->device_id = $data['deviceID'];
                $detail->person_name = $data['personName'];
                $detail->person_title = $data['personTitle'];
                $detail->place_name = $data['placeName'];
                $detail->time_int = strtotime($data['date']);
                $detail->time = date('H:i:s', strtotime($data['date']));
                $detail->check_date = date('Y-m-d', time());
                $detail->partner_id = $data['id'];
                $detail->obj_data = json_encode($data);

                $detail->save();

                if ($type == 0) {
                    if ($timeKeeping && $timeKeeping->checkin == '') {
                        $timeKeeping->checkin = date('H:i:s', strtotime($data['date']));
                    } else if($timeKeeping && $timeKeeping->checkin != '') {
                        $timeKeeping->checkout = date('H:i:s', strtotime($data['date']));
                    } else {
                        $timeKeeping = new TimeKeeping();
                        $timeKeeping->checkin = date('H:i:s', strtotime($data['date']));
                        $timeKeeping->user_id = $user->id;
                        $timeKeeping->check_date = date('Y-m-d', time());
                        $timeKeeping->check_type = 1;
                    }
                } else if ($type == 1) {
                    if ($timeKeeping && $timeKeeping->checkin == '') {
                        $timeKeeping->checkin = date('H:i:s', strtotime($data['date']));
                    } else if($timeKeeping && $timeKeeping->checkin != '') {
                        $timeKeeping->checkout = date('H:i:s', strtotime($data['date']));
                    } else {
                        $timeKeeping = new TimeKeeping();
                        $timeKeeping->checkin = date('H:i:s', strtotime($data['date']));
                        $timeKeeping->user_id = $user->id;
                        $timeKeeping->check_date = date('Y-m-d', strtotime($data['date']));
                        $timeKeeping->check_type = 1;
                    }
                } else if ($type == 2) {
                    if ($timeKeeping && $timeKeeping->checkin == '') {
                        $timeKeeping->checkin = date('H:i:s', strtotime($data['date']));
                    } else if($timeKeeping && $timeKeeping->checkin != '') {
                        $timeKeeping->checkout = date('H:i:s', strtotime($data['date']));
                    } else {
                        $timeKeeping = new TimeKeeping();
                        $timeKeeping->checkout = date('H:i:s', strtotime($data['date']));
                        $timeKeeping->user_id = $user->id;
                        $timeKeeping->check_date = date('Y-m-d', strtotime($data['date']));
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
        if (isset($data['petition_type']) && $data['petition_type'] != '') {
            $timeKeeping->petition_type = $data['petition_type'];
        }
        if (isset($data['type_leave']) && $data['type_leave'] != '') {
            $timeKeeping->type_leave = $data['type_leave'];
        }
        if (isset($data['time_from']) && $data['time_from'] != '') {
            $timeKeeping->time_from = $data['time_from'];
        }
        if (isset($data['time_to']) && $data['time_to'] != '') {
            $timeKeeping->time_to = $data['time_to'];
        }
        if (isset($data['date_to']) && $data['date_to'] != '') {
            $timeKeeping->date_to = $data['date_to'];
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

        
        $holidays = \App\Models\Holiday::all();
         
        $arrHoliday = [];

        foreach ($holidays as $holiday) {
            $labelHoliday = [];
            $rangeHoliday = $this->getLabelTimeKeeping($holiday->holiday_date_start, date('Y-m-d',strtotime('+1 days', strtotime($holiday->holiday_date_end))), $labelHoliday);
            foreach($rangeHoliday as $key => $val) {
                $arrHoliday[] = $key;
            }
        }

        $totalHo = 0;
        foreach ($holidays as $holiday) {
            if(strtotime($holiday->holiday_date_start) <= time()){
                if(strtotime($holiday->holiday_date_start) >= strtotime($filters['start_date'])
                && strtotime($holiday->holiday_date_start) <= strtotime($filters['end_date'])){
                        $holiday_start = date('Y-m-d',strtotime($holiday->holiday_date_start));
                        $holiday_end = date('Y-m-d',strtotime($holiday->holiday_date_end. ' +1 days'));
                        $timeHoliday = $this->timeReport($holiday_start, $holiday_end) ?? 0;   
                        $totalHo += $timeHoliday['total'];
                }
            }
        }

        if ($filters['start_date'] != '' && $filters['end_date'] != '' ) {
            $start_date = $filters['start_date'];
            $end_date =  date('Y-m-d',strtotime($filters['end_date']. ' +1 days'));

            $timeExpected = $this->timeReport($start_date, $end_date);
            $expectedWar1_3 = ($timeExpected['total'] - $totalHo ) * 1;
            $expectedWar1 = ($timeExpected['total'] - $totalHo ) * 2;
            $expectedWar2 = ($timeExpected['total'] - $totalHo ) * 3;
            $expectedWar3 = ($timeExpected['total'] - $totalHo ) * 4;

            if  (strtotime($filters['end_date']. ' +1 days') >= time() ) {
                $timeNow = $this->timeReport($start_date, date('Y-m-d', time()));
            } else {
                $timeNow = $timeExpected;
            }

            $nowWar1_3 = ($timeNow['total'] - $totalHo ) * 1;
            $nowWar1 = ($timeNow['total'] - $totalHo ) * 2;
            $nowWar2 = ($timeNow['total'] - $totalHo ) * 3;
            $nowWar3 = ($timeNow['total'] - $totalHo ) * 4;

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

                        $timeWar = 0;
                        $totalOT = 0;
                        $totalWar  = 0;

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

                             if(in_array($value->check_date, $arrHoliday) ){
                                $checkIn ='';
                                $checkOut = '';
                            }else{
                                $checkIn = $value->checkin? strtotime($value->check_date. ' '. $value->checkin): '';
                                $checkOut = $value->checkout? strtotime($value->check_date. ' '. $value->checkout): '';
                            }

                                if ($configDay && ($value->petition_type == 0 || $value->petition_type == 1 || $value->petition_type == 4 )) {
                                    $start = $configDay['start_timeAM'] != ''? strtotime($value->check_date. ' '. $configDay['start_timeAM']): '';
                                    $end = $configDay['end_timePM'] != ''? strtotime($value->check_date. ' '. $configDay['end_timePM']): '';

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
                                }else if ($configDay && $value->petition_type == 5 ) {
                                    $start = $configDay['start_timeAM'] != ''? strtotime($value->check_date. ' '. $configDay['start_timeAM']): '';
                                    $end = $configDay['end_timePM'] != ''? strtotime($value->check_date. ' '. $configDay['end_timePM']): '';
                                    if ($checkIn  && $checkOut ) {
                                    $totalOT ++;
                                    }
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
                                } else if ($configDay && $value->petition_type == 6 && $checkIn  && $checkOut ) {
                                        $totalWar ++;
                                        $timeWar += $checkOut - $checkIn;
                                } else if($configDay && $value->petition_type == 2 && $value->type_leave == 1){
                                    $start = $configDay['start_timePM'] != ''? strtotime($value->check_date. ' '. $configDay['start_timePM']): '';
                                    $end = $configDay['end_timePM'] != ''? strtotime($value->check_date. ' '. $configDay['end_timePM']): '';

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

                                } else if($configDay && $value->petition_type == 2 && $value->type_leave == 2){
                                    $start = $configDay['start_timeAM'] != ''? strtotime($value->check_date. ' '. $configDay['start_timeAM']): '';
                                    $end = $configDay['end_timeAM'] != ''? strtotime($value->check_date. ' '. $configDay['end_timeAM']): '';

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

                                }else if($configDay && $value->petition_type == 2 && ($value->type_leave == 3 || $value->type_leave == 4)){
                                    $totalUnpaidLeave ++;
                                }

                            if ($checkIn && $checkOut) { 
                                if($labelDay == 'monday' || $labelDay == 'tuesday' || $labelDay == 'wednesday' || $labelDay == 'thursday' || $labelDay == 'friday'){
                                    if($value->petition_type == 0 || $value->petition_type == 1 || $value->petition_type == 4){
                                        $totalTimeKeeping++;
                                        $totalWorkingDays++;
                                    }else if($value->petition_type == 2 && $value->type_leave == 1 ){
                                        $totalTimeKeeping = $totalTimeKeeping + 1/2;
                                        $totalWorkingDays++;
                                    }else if($value->petition_type == 2 && $value->type_leave == 2 ){
                                        $totalTimeKeeping = $totalTimeKeeping + 1/2;
                                        $totalWorkingDays++;
                                    }else if($value->petition_type == 2 && $value->type_leave == 3 || $value->type_leave == 4 ){
                                        $totalTimeKeeping = $totalTimeKeeping;
                                        $totalWorkingDays = $totalWorkingDays;
                                    }else if($value->petition_type == 5){
                                        $totalTimeKeeping++;
                                        $totalWorkingDays++;
                                    }else if($value->petition_type == 6){
                                        $totalTimeKeeping = $totalTimeKeeping;
                                        $totalWorkingDays++;
                                    }else if($value->petition_type == 7){
                                        $totalTimeKeeping++;
                                        $totalWorkingDays++;
                                    }
                                }else if($labelDay == 'saturday'){
                                    if($value->petition_type == 0 || $value->petition_type == 1 || $value->petition_type == 4){
                                        $totalTimeKeeping = $totalTimeKeeping + 1/2;
                                        $totalWorkingDays++;
                                    }else if($value->petition_type == 2 && $value->type_leave == 1 ){
                                        $totalTimeKeeping = $totalTimeKeeping;
                                        $totalWorkingDays++;
                                    }else if($value->petition_type == 2 && $value->type_leave == 2 ){
                                        $totalTimeKeeping = $totalTimeKeeping;
                                        $totalWorkingDays++;
                                    }else if($value->petition_type == 2 && ($value->type_leave == 3 || $value->type_leave == 4 )){
                                        $totalTimeKeeping = $totalTimeKeeping;
                                        $totalWorkingDays = $totalWorkingDays;
                                    }else if($value->petition_type == 5){
                                        $totalTimeKeeping = $totalTimeKeeping + 1/2;
                                        $totalWorkingDays++;
                                    }else if($value->petition_type == 6){
                                        $totalTimeKeeping = $totalTimeKeeping;
                                        $totalWorkingDays++;
                                    }else if($value->petition_type == 7){
                                        $totalTimeKeeping = $totalTimeKeeping + 1/2;
                                        $totalWorkingDays++;
                                    }
                                }else if($labelDay == 'sunday'){
                                    if($value->petition_type == 0 || $value->petition_type == 1 || $value->petition_type == 4){
                                        $totalTimeKeeping = $totalTimeKeeping;
                                        $totalWorkingDays = $totalWorkingDays;
                                    }else if($value->petition_type == 2 && $value->type_leave == 1 ){
                                        $totalTimeKeeping = $totalTimeKeeping;
                                        $totalWorkingDays = $totalWorkingDays;
                                    }else if($value->petition_type == 2 && $value->type_leave == 2 ){
                                        $totalTimeKeeping = $totalTimeKeeping;
                                        $totalWorkingDays = $totalWorkingDays;
                                    }else if($value->petition_type == 2 && ($value->type_leave == 3 || $value->type_leave == 4 )){
                                        $totalTimeKeeping = $totalTimeKeeping;
                                        $totalWorkingDays = $totalWorkingDays;
                                    }else if($value->petition_type == 5){
                                        $totalTimeKeeping++;
                                        $totalWorkingDays++;
                                    }else if($value->petition_type == 6){
                                        $totalTimeKeeping = $totalTimeKeeping;
                                        $totalWorkingDays++;
                                    }else if($value->petition_type == 7){
                                        $totalTimeKeeping = $totalTimeKeeping;
                                        $totalWorkingDays = $totalWorkingDays;
                                    }
                                }           
                            }else if($checkIn && !$checkOut) {
                                if($value->petition_type == 7){
                                    if($labelDay == 'monday' || $labelDay == 'tuesday' || $labelDay == 'wednesday' || $labelDay == 'thursday' || $labelDay == 'friday'){
                                        $totalTimeKeeping++;
                                        $totalWorkingDays++;
                                    }else if($labelDay == 'saturday'){
                                        $totalTimeKeeping = $totalTimeKeeping +1/2;
                                        $totalWorkingDays++;
                                    }else if($labelDay == 'sunday'){
                                        $totalTimeKeeping = $totalTimeKeeping;
                                        $totalWorkingDays = $totalWorkingDays;
                                    }
                                } else {
                                    $totalNotCheckOut++;
                                }
                            }else if(!$checkIn && $checkOut) {
                                if($value->petition_type == 7){
                                    if($labelDay == 'monday' || $labelDay == 'tuesday' || $labelDay == 'wednesday' || $labelDay == 'thursday' || $labelDay == 'friday'){
                                        $totalTimeKeeping++;
                                        $totalWorkingDays++;
                                    }else if($labelDay == 'saturday'){
                                        $totalTimeKeeping = $totalTimeKeeping +1/2;
                                        $totalWorkingDays++;
                                    }else if($labelDay == 'sunday'){
                                        $totalTimeKeeping = $totalTimeKeeping;
                                        $totalWorkingDays = $totalWorkingDays;
                                    }
                                } else {
                                    $totalNotCheckIn++;
                                }
                            }else {
                                if($value->petition_type == 7){
                                    if($labelDay == 'monday' || $labelDay == 'tuesday' || $labelDay == 'wednesday' || $labelDay == 'thursday' || $labelDay == 'friday'){
                                        $totalTimeKeeping++;
                                        $totalWorkingDays++;
                                    }else if($labelDay == 'saturday'){
                                        $totalTimeKeeping = $totalTimeKeeping +1/2;
                                        $totalWorkingDays++;
                                    }else if($labelDay == 'sunday'){
                                        $totalTimeKeeping = $totalTimeKeeping;
                                        $totalWorkingDays = $totalWorkingDays;
                                    }
                                }
                            }
                        }

                        $totalHourEfforts = (($timeWar + $timeGoEarly + $timeAboutLate) - ($timeGoLate + $timeAboutEarly))/3600;

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
                            $avgTimeHoldWar = $timeHoldWar/($timeExpected['total'] -  $timeNow['total']);
                            $avgTimeIncreaseWar = $timeIncreaseWar/($timeExpected['total'] -  $timeNow['total']);
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
                        $totalUnpaidLeave = $timeNow['total'] - $totalTimeKeeping - $totalHo;
                        $rateGoLate = $totalWorkingDays? round(($totalGoLate/$totalWorkingDays), 4) * 100 : 0;
                        if($user->date_official != ""){
                            $date_official = new Datetime($user->date_official);
                            $date_official_new = date('d-m-Y', strtotime($user->date_official));
                        } else {
                            $date_official_new = 0;
                        }

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

                            'totalOT' => $totalOT,
                            'totalWar' => $totalWar,
                        ];
                    }
                }

            $expected = [
                'start_date' => $filters['start_date'],
                'end_date' => $filters['end_date'],
                'totalHoliday' => $totalHo ?? 0,
                'total' => $timeExpected['total'] ?? '',
                'total_real' => $timeExpected['total'] - $totalHo,
                'warrior1_3' => $expectedWar1_3 ?? '',
                'warrior1' => $expectedWar1 ?? '',
                'warrior2' => $expectedWar2 ?? '',
                'warrior3' => $expectedWar3 ?? '',
            ];

            $current = [
                'start_date' => $filters['start_date'],
                'totalHoliday' => $totalHo ?? 0,
                'end_date' => end($keyArr),
                'total' => $timeNow['total'],
                'total_real' => $timeNow['total'] - $totalHo,
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
                        'totalOT' => 0,
                        'totalWar' => 0,
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
