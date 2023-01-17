<?php

namespace App\Services;

use App\Models\ConfigTimeKeeping;
use App\Models\DeviceTimeKeeping;
use App\Models\TimeKeeping;
use App\Models\TimeKeepingDetail;
use App\Models\TimeGoOut;
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
                if ($user->user_status == 1 && $user->position != "Giám đốc" && $user->id != 44) {
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
                                            $finalCheckout = $tmp[$key]['final_checkout'] = $time->final_checkout? date('H:i', strtotime($key. ' '. $time->final_checkout)) : '';

                                            if( $finalCheckout == ''){
                                                if(date('H:i', strtotime($key. ' '. $time->checkout)) > '17:30' || strtotime($key. ' '. $time->checkout) < strtotime("today")){
                                                    $tmp[$key]['checkout'] = date('H:i', strtotime($key. ' '. $time->checkout));
                                                }else{
                                                    $tmp[$key]['checkout'] = '-:-';
                                                }
                                            } else {
                                                $tmp[$key]['checkout'] =  $finalCheckout;
                                            }
                                            $configTimeKeepingDay = $settings[$day]?? [];
                                            $checkIn = $time->checkin? strtotime($key. ' '. $time->checkin): '';
                                            if( $finalCheckout == null){
                                                if(date('H:i', strtotime($key. ' '. $time->checkout)) > '17:30' || strtotime($key. ' '. $time->checkout) < strtotime("today")){
                                                    $checkOut = strtotime($key. ' '. $time->checkout);
                                                }else {
                                                    $checkOut = '';
                                                }
                                            }else{
                                                $checkOut = strtotime($key. ' '. $time->final_checkout);
                                            }

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
                                            $finalCheckout = $tmp[$key]['final_checkout'] = $time->final_checkout? date('H:i', strtotime($key. ' '. $time->final_checkout)) : '';

                                            if( $finalCheckout == ''){
                                                if(date('H:i', strtotime($key. ' '. $time->checkout)) > '17:30' || strtotime($key. ' '. $time->checkout) < strtotime("today")){
                                                    $tmp[$key]['checkout'] = date('H:i', strtotime($key. ' '. $time->checkout));
                                                }else{
                                                    $tmp[$key]['checkout'] = '-:-';
                                                }
                                            } else {
                                                $tmp[$key]['checkout'] =  $finalCheckout;
                                            }

                                            $configTimeKeepingDay = $settings[$day]?? [];

                                            if ($configTimeKeepingDay && $configTimeKeepingDay['start_timeAM'] && $configTimeKeepingDay['end_timePM']) {
                                                $checkIn = $time->checkin? strtotime($key. ' '. $time->checkin): '';
                                                if( $finalCheckout == null){
                                                    if(date('H:i', strtotime($key. ' '. $time->checkout)) > '17:30' || strtotime($key. ' '. $time->checkout) < strtotime("today")){
                                                        $checkOut = strtotime($key. ' '. $time->checkout);
                                                    }else {
                                                        $checkOut = '';
                                                    }
                                                }else{
                                                    $checkOut = strtotime($key. ' '. $time->final_checkout);
                                                }

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

                                if ($time->check_date == $key && ($time->petition_type == 0 || $time->petition_type == 1 || $time->petition_type == 4 ) && $time->date_to =='' ) {
                                    $tmp[$key]['petition_type'] = $time->petition_type;
                                    $tmp[$key]['label_day'] = $range[$time->check_date];
                                    $tmp[$key]['type_leave'] = $time->type_leave;
                                    $tmp[$key]['date_to'] = $time->date_to;
                                    $tmp[$key]['time_from'] = $time->time_from? date('H:i', strtotime($key. ' '. $time->time_from)) : '-:-';
                                    $tmp[$key]['time_to'] = $time->time_to? date('H:i', strtotime($key. ' '. $time->time_to)) : '-:-';
                                    $tmp[$key]['checkin'] = $time->checkin? date('H:i', strtotime($key. ' '. $time->checkin)) : '-:-';
                                    $finalCheckout = $tmp[$key]['final_checkout'] = $time->final_checkout? date('H:i', strtotime($key. ' '. $time->final_checkout)) : '';
                                    $tmp[$key]['go_out'] = $time->go_out? date('H:i', strtotime($key. ' '. $time->go_out)) : '-:-';
                                    $tmp[$key]['go_in'] = $time->go_in? date('H:i', strtotime($key. ' '. $time->go_in)) : '-:-';
                                    $tmp[$key]['time_pause'] = $time->time_pause? $time->time_pause : '';
                                    $tmp[$key]['total_pause'] = $time->total_pause? $time->total_pause : '';
                                    if( $finalCheckout == ''){
                                        if(date('H:i', strtotime($key. ' '. $time->checkout)) > '17:30' || strtotime($key. ' '. $time->checkout) < strtotime("today")){
                                            $tmp[$key]['checkout'] = date('H:i', strtotime($key. ' '. $time->checkout));
                                        }else{
                                            $tmp[$key]['checkout'] = '-:-';
                                        }
                                    } else {
                                         $tmp[$key]['checkout'] =  $finalCheckout;
                                    }
                                    $configTimeKeepingDay = $settings[$day]?? [];

                                    $GoOut = $time->go_out? strtotime($key. ' '. $time->go_out): '';
                                    $GoIn = $time->go_in? strtotime($key. ' '. $time->go_in): '';
                                    if( $GoOut && $GoIn ){
                                        $tmp[$key]['time_out'] = (int)(($GoIn - $GoOut)/60);
                                    }

                                    if ($configTimeKeepingDay && $configTimeKeepingDay['start_timeAM'] && $configTimeKeepingDay['end_timePM']) {
                                        $checkIn = $time->checkin? strtotime($key. ' '. $time->checkin): '';
                                        if( $finalCheckout == null){
                                            if(date('H:i', strtotime($key. ' '. $time->checkout)) > '17:30' || strtotime($key. ' '. $time->checkout) < strtotime("today")){
                                                $checkOut = strtotime($key. ' '. $time->checkout);
                                            }else {
                                                $checkOut = '';
                                            }
                                        }else{
                                            $checkOut = strtotime($key. ' '. $time->final_checkout);
                                        }
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
                                }else if ($time->check_date == $key && $time->petition_type == 9  && $time->date_to =='' ) {
                                    $tmp[$key]['petition_type'] = $time->petition_type;
                                    $tmp[$key]['label_day'] = $range[$time->check_date];
                                    $tmp[$key]['type_leave'] = $time->type_leave;
                                    $tmp[$key]['date_to'] = $time->date_to;
                                    $tmp[$key]['time_from'] = $time->time_from? date('H:i', strtotime($key. ' '. $time->time_from)) : '-:-';
                                    $tmp[$key]['time_to'] = $time->time_to? date('H:i', strtotime($key. ' '. $time->time_to)) : '-:-';
                                    $tmp[$key]['checkin'] = $time->checkin? date('H:i', strtotime($key. ' '. $time->checkin)) : '-:-';
                                    $finalCheckout = $tmp[$key]['final_checkout'] = $time->final_checkout? date('H:i', strtotime($key. ' '. $time->final_checkout)) : '';

                                    if( $finalCheckout == ''){
                                        if(date('H:i', strtotime($key. ' '. $time->checkout)) > '17:30' || strtotime($key. ' '. $time->checkout) < strtotime("today")){
                                            $tmp[$key]['checkout'] = date('H:i', strtotime($key. ' '. $time->checkout));
                                        }else{
                                            $tmp[$key]['checkout'] = '-:-';
                                        }
                                    } else {
                                         $tmp[$key]['checkout'] =  $finalCheckout;
                                    }
                                    $configTimeKeepingDay = $settings[$day]?? [];
									$timeFrom = $time->time_from? strtotime($key. ' '. $time->time_from): '';
									$timeTo = $time->time_to? strtotime($key. ' '. $time->time_to): '';
                                    if ($configTimeKeepingDay && $configTimeKeepingDay['start_timeAM'] && $configTimeKeepingDay['end_timePM']) {
                                        $checkIn = $time->checkin? strtotime($key. ' '. $time->checkin): '';
                                        if( $finalCheckout == null){
                                            if(date('H:i', strtotime($key. ' '. $time->checkout)) > '17:30' || strtotime($key. ' '. $time->checkout) < strtotime("today")){
                                                $checkOut = strtotime($key. ' '. $time->checkout);
                                            }else {
                                                $checkOut = '';
                                            }
                                        }else{
                                            $checkOut = strtotime($key. ' '. $time->final_checkout);
                                        }
                                        $start = $configTimeKeepingDay['start_timeAM'] != ''? strtotime($key. ' '. $configTimeKeepingDay['start_timeAM']): '';
                                        $end = $configTimeKeepingDay['end_timePM'] != ''? strtotime($key. ' '. $configTimeKeepingDay['end_timePM']): '';
                                        if (($checkIn && !$checkOut) || (!$checkIn && $checkOut)) {
                                            $tmp[$key]['class'] = 'text-light bg-danger';
                                        } elseif ($checkIn && $checkOut && $checkIn <= $start && $checkOut >= $end) {
                                            $tmp[$key]['class'] = 'text-light bg-success';
                                        } elseif (($checkIn && $checkIn > $start) || ($checkOut && $checkOut < $end)) {
                                            $tmp[$key]['class'] = 'text-dark bg-warning';
                                        }
										 $tmp[$key]['go_out'] = ($timeTo - $timeFrom)/60;
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
                                }
								else if ($time->petition_type == 2) {
                                    if($time->check_date == $key){
                                        if($time->type_leave == 1){
                                            $tmp[$key]['petition_type'] = $time->petition_type;
                                            $tmp[$key]['type_leave'] = $time->type_leave;
                                            $tmp[$key]['time_from'] = $time->time_from? date('H:i', strtotime($key. ' '. $time->time_from)) : '-:-';
                                            $tmp[$key]['time_to'] = $time->time_to? date('H:i', strtotime($key. ' '. $time->time_to)) : '-:-';
                                            $tmp[$key]['checkin'] = $time->checkin? date('H:i', strtotime($key. ' '. $time->checkin)) : '-:-';

                                            $finalCheckout = $tmp[$key]['final_checkout'] = $time->final_checkout? date('H:i', strtotime($key. ' '. $time->final_checkout)) : '';

                                            if( $finalCheckout == ''){
                                                if(date('H:i', strtotime($key. ' '. $time->checkout)) > '17:30' || strtotime($key. ' '. $time->checkout) < strtotime("today")){
                                                    $tmp[$key]['checkout'] = date('H:i', strtotime($key. ' '. $time->checkout));
                                                }else{
                                                    $tmp[$key]['checkout'] = '-:-';
                                                }
                                            } else {
                                                $tmp[$key]['checkout'] =  $finalCheckout;
                                            }

                                            $tmp[$key]['class'] = 'text-light bg-secondary';

                                            $configTimeKeepingDay = $settings[$day]?? [];

                                            if ($configTimeKeepingDay && $configTimeKeepingDay['start_timePM'] && $configTimeKeepingDay['end_timePM']) {
                                                $checkIn = $time->checkin? strtotime($key. ' '. $time->checkin): '';
                                            if( $finalCheckout == null){
                                                if(date('H:i', strtotime($key. ' '. $time->checkout)) > '17:30' || strtotime($key. ' '. $time->checkout) < strtotime("today")){
                                                    $checkOut = strtotime($key. ' '. $time->checkout);
                                                }else {
                                                    $checkOut = '';
                                                }
                                            }else{
                                                $checkOut = strtotime($key. ' '. $time->final_checkout);
                                            }

                                                $start = $configTimeKeepingDay['start_timePM'] != ''? strtotime($key. ' '. $configTimeKeepingDay['start_timePM']): '';
                                                $end = $configTimeKeepingDay['end_timePM'] != ''? strtotime($key. ' '. $configTimeKeepingDay['end_timePM']): '';

                                                if ($checkIn && $start) {
                                                    if ($checkIn < $start) {
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
                                                    if ($checkOut < $end) {
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
                                        } else if ($time->type_leave == 2) {
                                            $tmp[$key]['petition_type'] = $time->petition_type;
                                            $tmp[$key]['type_leave'] = $time->type_leave;
                                            $tmp[$key]['time_from'] = $time->time_from? date('H:i', strtotime($key. ' '. $time->time_from)) : '-:-';
                                            $tmp[$key]['time_to'] = $time->time_to? date('H:i', strtotime($key. ' '. $time->time_to)) : '-:-';
                                            $tmp[$key]['checkin'] = $time->checkin? date('H:i', strtotime($key. ' '. $time->checkin)) : '-:-';
                                            $finalCheckout = $tmp[$key]['final_checkout'] = $time->final_checkout? date('H:i', strtotime($key. ' '. $time->final_checkout)) : '';

                                            if( $finalCheckout == ''){
                                                if(date('H:i', strtotime($key. ' '. $time->checkout)) > '17:30' || strtotime($key. ' '. $time->checkout) < strtotime("today")){
                                                    $tmp[$key]['checkout'] = date('H:i', strtotime($key. ' '. $time->checkout));
                                                }else{
                                                    $tmp[$key]['checkout'] = '-:-';
                                                }
                                            } else {
                                                $tmp[$key]['checkout'] =  $finalCheckout;
                                            }

                                            $tmp[$key]['class'] = ' text-light bg-secondary';

                                            $configTimeKeepingDay = $settings[$day]?? [];

                                            if ($configTimeKeepingDay && $configTimeKeepingDay['start_timeAM'] && $configTimeKeepingDay['end_timeAM']) {
                                                $checkIn = $time->checkin? strtotime($key. ' '. $time->checkin): '';

                                                if( $finalCheckout == null){
                                                    if(date('H:i', strtotime($key. ' '. $time->checkout)) > '17:30' || strtotime($key. ' '. $time->checkout) < strtotime("today")){
                                                        $checkOut = strtotime($key. ' '. $time->checkout);
                                                    }else {
                                                        $checkOut = '';
                                                    }
                                                }else{
                                                    $checkOut = strtotime($key. ' '. $time->final_checkout);
                                                }

                                                $start = $configTimeKeepingDay['start_timeAM'] != ''? strtotime($key. ' '. $configTimeKeepingDay['start_timeAM']): '';
                                                $end = $configTimeKeepingDay['end_timeAM'] != ''? strtotime($key. ' '. $configTimeKeepingDay['end_timeAM']): '';

                                                if ($checkIn && $start) {
                                                    if ($checkIn < $start) {
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
                                                    if ($checkOut < $end) {
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
                                        } else if ($time->type_leave == 3) {
                                            $tmp[$key]['petition_type'] = $time->petition_type;
                                            $tmp[$key]['type_leave'] = $time->type_leave;
                                            $tmp[$key]['time_from'] = $time->time_from? date('H:i', strtotime($key. ' '. $time->time_from)) : '-:-';
                                            $tmp[$key]['time_to'] = $time->time_to? date('H:i', strtotime($key. ' '. $time->time_to)) : '-:-';
                                            $tmp[$key]['checkin'] = $time->checkin? date('H:i', strtotime($key. ' '. $time->checkin)) : '-:-';
                                            $tmp[$key]['checkout'] = $time->checkout? date('H:i', strtotime($key. ' '. $time->checkout)) : '-:-';
                                            // $finalCheckout = $tmp[$key]['final_checkout'] = $time->final_checkout? date('H:i', strtotime($key. ' '. $time->final_checkout)) : '';

                                            // if( $finalCheckout == ''){
                                            //     if(date('H:i', strtotime($key. ' '. $time->checkout)) > '17:30' || strtotime($key. ' '. $time->checkout) < strtotime("today")){
                                            //         $tmp[$key]['checkout'] = date('H:i', strtotime($key. ' '. $time->checkout));
                                            //     }else{
                                            //         $tmp[$key]['checkout'] = '-:-';
                                            //     }
                                            // } else {
                                            //     $tmp[$key]['checkout'] =  $finalCheckout;
                                            // }

                                            $tmp[$key]['class'] = 'text-light bg-dark';

                                            $configTimeKeepingDay = $settings[$day]?? [];

                                            if ($configTimeKeepingDay && $configTimeKeepingDay['start_timeAM'] && $configTimeKeepingDay['end_timePM']) {
                                                $checkIn = $time->checkin? strtotime($key. ' '. $time->checkin): '';
                                                $checkOut = $time->checkout? strtotime($key. ' '. $time->checkout): '';
                                                // if( $finalCheckout == null){
                                                //     if(date('H:i', strtotime($key. ' '. $time->checkout)) > '17:30' || strtotime($key. ' '. $time->checkout) < strtotime("today")){
                                                //         $checkOut = strtotime($key. ' '. $time->checkout);
                                                //     }else {
                                                //         $checkOut = '';
                                                //     }
                                                // }else{
                                                //     $checkOut = strtotime($key. ' '. $time->final_checkout);
                                                // }
                                                $start = $configTimeKeepingDay['start_timeAM'] != ''? strtotime($key. ' '. $configTimeKeepingDay['start_timeAM']): '';
                                                $end = $configTimeKeepingDay['end_timePM'] != ''? strtotime($key. ' '. $configTimeKeepingDay['end_timePM']): '';

                                                if ($checkIn && $start) {
                                                    if ($checkIn < $start) {
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
                                                    if ($checkOut < $end) {
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
                                        }
                                    }
                                    if($time->check_date <= $key && $key <= $time->date_to){
                                        if ($time->type_leave == 4) {
                                            $tmp[$key]['petition_type'] = $time->petition_type;
                                            $tmp[$key]['type_leave'] = $time->type_leave;
                                            $tmp[$key]['time_from'] = $time->time_from? date('H:i', strtotime($key. ' '. $time->time_from)) : '-:-';
                                            $tmp[$key]['time_to'] = $time->time_to? date('H:i', strtotime($key. ' '. $time->time_to)) : '-:-';
                                            $tmp[$key]['checkin'] = $time->checkin? date('H:i', strtotime($key. ' '. $time->checkin)) : '-:-';
                                            $tmp[$key]['checkout'] = $time->checkout? date('H:i', strtotime($key. ' '. $time->checkout)) : '-:-';
                                            // $finalCheckout = $tmp[$key]['final_checkout'] = $time->final_checkout? date('H:i', strtotime($key. ' '. $time->final_checkout)) : '';

                                            // if( $finalCheckout == ''){
                                            //     if(date('H:i', strtotime($key. ' '. $time->checkout)) > '17:30' || strtotime($key. ' '. $time->checkout) < strtotime("today")){
                                            //         $tmp[$key]['checkout'] = date('H:i', strtotime($key. ' '. $time->checkout));
                                            //     }else{
                                            //         $tmp[$key]['checkout'] = '-:-';
                                            //     }
                                            // } else {
                                            //     $tmp[$key]['checkout'] =  $finalCheckout;
                                            // }

                                            $tmp[$key]['class'] = 'text-light bg-dark';

                                            $configTimeKeepingDay = $settings[$day]?? [];

                                            if ($configTimeKeepingDay && $configTimeKeepingDay['start_timeAM'] && $configTimeKeepingDay['end_timePM']) {
                                                $checkIn = $time->checkin? strtotime($key. ' '. $time->checkin): '';
                                                $checkOut = $time->checkout? strtotime($key. ' '. $time->checkout): '';
                                                // if( $finalCheckout == null){
                                                //     if(date('H:i', strtotime($key. ' '. $time->checkout)) > '17:30' || strtotime($key. ' '. $time->checkout) < strtotime("today")){
                                                //         $checkOut = strtotime($key. ' '. $time->checkout);
                                                //     }else {
                                                //         $checkOut = '';
                                                //     }
                                                // }else{
                                                //     $checkOut = strtotime($key. ' '. $time->final_checkout);
                                                // }

                                                $start = $configTimeKeepingDay['start_timeAM'] != ''? strtotime($key. ' '. $configTimeKeepingDay['start_timeAM']): '';
                                                $end = $configTimeKeepingDay['end_timePM'] != ''? strtotime($key. ' '. $configTimeKeepingDay['end_timePM']): '';

                                                if ($checkIn && $start) {
                                                    if ($checkIn < $start) {
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
                                                    if ($checkOut < $end) {
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
                                        }
                                    }
                                } else if (($time->check_date == $key  && $time->petition_type == 5) || (($time->check_date <= $key && $key <= $time->date_to) &&  $time->petition_type == 5)) {
                                    $tmp[$key]['petition_type'] = $time->petition_type;
                                    $tmp[$key]['type_leave'] = $time->type_leave;
                                    $tmp[$key]['time_from'] = $time->time_from? date('H:i', strtotime($key. ' '. $time->time_from)) : '-:-';
                                    $tmp[$key]['time_to'] = $time->time_to? date('H:i', strtotime($key. ' '. $time->time_to)) : '-:-';
                                    $tmp[$key]['checkin'] = $time->checkin? date('H:i', strtotime($key. ' '. $time->checkin)) : '-:-';
                                    $finalCheckout = $tmp[$key]['final_checkout'] = $time->final_checkout? date('H:i', strtotime($key. ' '. $time->final_checkout)) : '';

                                    if( $finalCheckout == ''){
                                        if(date('H:i', strtotime($key. ' '. $time->checkout)) > '17:30' || strtotime($key. ' '. $time->checkout) < strtotime("today")){
                                            $tmp[$key]['checkout'] = date('H:i', strtotime($key. ' '. $time->checkout));
                                        }else{
                                            $tmp[$key]['checkout'] = '-:-';
                                        }
                                    } else {
                                        $tmp[$key]['checkout'] =  $finalCheckout;
                                    }

                                    $configTimeKeepingDay = $settings[$day]?? [];
                                    $checkIn = $time->checkin? strtotime($key. ' '. $time->checkin): '';
                                    if( $finalCheckout == null){
                                        if(date('H:i', strtotime($key. ' '. $time->checkout)) > '17:30' || strtotime($key. ' '. $time->checkout) < strtotime("today")){
                                            $checkOut = strtotime($key. ' '. $time->checkout);
                                        }else {
                                            $checkOut = '';
                                        }
                                    }else{
                                        $checkOut = strtotime($key. ' '. $time->final_checkout);
                                    }

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
                                   $finalCheckout = $tmp[$key]['final_checkout'] = $time->final_checkout? date('H:i', strtotime($key. ' '. $time->final_checkout)) : '';

                                    if( $finalCheckout == ''){
                                        if(date('H:i', strtotime($key. ' '. $time->checkout)) > '17:30' || strtotime($key. ' '. $time->checkout) < strtotime("today")){
                                            $tmp[$key]['checkout'] = date('H:i', strtotime($key. ' '. $time->checkout));
                                        }else{
                                            $tmp[$key]['checkout'] = '-:-';
                                        }
                                    } else {
                                        $tmp[$key]['checkout'] =  $finalCheckout;
                                    }

                                    $configTimeKeepingDay = $settings[$day]?? [];

                                    if ($configTimeKeepingDay && $configTimeKeepingDay['start_timeAM'] && $configTimeKeepingDay['end_timePM']) {
                                        $checkIn = $time->checkin? strtotime($key. ' '. $time->checkin): '';
                                        if( $finalCheckout == null){
                                            if(date('H:i', strtotime($key. ' '. $time->checkout)) > '17:30' || strtotime($key. ' '. $time->checkout) < strtotime("today")){
                                                $checkOut = strtotime($key. ' '. $time->checkout);
                                            }else {
                                                $checkOut = '';
                                            }
                                        }else{
                                            $checkOut = strtotime($key. ' '. $time->final_checkout);
                                        }

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
                                    if(date('H:i', strtotime($key. ' '. $time->checkout)) > '17:30' || strtotime($key. ' '. $time->checkout) < strtotime("today")){
                                         $tmp[$key]['checkout'] = date('H:i', strtotime($key. ' '. $time->checkout));
                                    }else{
                                         $tmp[$key]['checkout'] = '-:-';
                                    }

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
        $showBtn_1 = '';
        $showBtn_2 = '';
        $myIp = '14.248.85.119';

        if (!empty($_SERVER['HTTP_CLIENT_IP']))
        {
            $ip_address = $_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
            $ip_address = $_SERVER['REMOTE_ADDR'];
        }

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
        
        if ($currentUser->check_type == 1) {
            $timeKeeping = TimeKeeping::query()
                ->where([
                    'user_id' => $currentUser->id,
                    'check_date' => date('Y-m-d', time())
                ])->first();
            if(!$timeKeeping){
                 $showBtn_1 = '';
            }else{
                if ($timeKeeping->btn_check == 1 ) {
                    $showBtn_1 = 'go_out';
                } else if ($timeKeeping->btn_check == 2) {
                    $showBtn_1 = 'go_in';
                }
                if(/*$myIp == $ip_address &&*/ $timeKeeping->checkin != null && $timeKeeping->final_checkout == null){
                    $showBtn_2 = 'final_checkout';
                } else if(/*$myIp == $ip_address &&*/ $timeKeeping->checkin != null && $timeKeeping->final_checkout != null){
                    $showBtn_2 = 'final_checkout_hide';
                }
            }
        }

        return [
            'code' => 200,
            'labels' => $labels,
            'data' => $result,
            'current_user' => $currentUser,
            'showBtn' => $showBtn,
            'showBtn_1' => $showBtn_1,
            'showBtn_2' => $showBtn_2,
        ];

    }

    public function getDetailTimeKeeping(array $filters)
    {
        $user = User::query()->with(['timeKeepingDetail' => function ($q) use ($filters) {
            $q->where('check_date', '=', $filters['date']);
        }])->where('id', '=', $filters['user_id'])->first();

        return $user;
    }

    public function getDetailTimeGoOut(array $filters)
    {
        $user = User::query()->with(['timeGoOutDetail' => function ($q) use ($filters) {
            $q->where('go_date', '=', $filters['date']);
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

            $start = date('Y-m-d',strtotime($data['date']));
            $end = date('Y-m-d',strtotime($data['date_to']. ' +1 days'));
            $totalLeave = $this->timeReport($start, $end) ?? 0;
            $timeKeeping->total_leave = $totalLeave['total'];
        }
        if (isset($data['type_paid']) && $data['type_paid'] != '') {
            $timeKeeping->type_paid = $data['type_paid'];
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
            $rangeHoliday = $this->getLabelTimeKeeping($holiday->holiday_date_start, 
                date('Y-m-d',strtotime('+1 days', strtotime($holiday->holiday_date_end))), $labelHoliday);
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
            $start_date_00 = date("Y-01-01", strtotime($filters['start_date']));
            $start_date = $filters['start_date'];
            $end_date =  date('Y-m-d',strtotime($filters['end_date']. ' +1 days'));

            $timeExpected = $this->timeReport($start_date, $end_date);
            $TimeE = $this->timeReport($start_date_00, $end_date);
            $expectedWar1_3 = ($timeExpected['total'] - $totalHo ) * 1;
            $expectedWar1 = ($timeExpected['total'] - $totalHo ) * 2;
            $expectedWar2 = ($timeExpected['total'] - $totalHo ) * 3;
            $expectedWar3 = ($timeExpected['total'] - $totalHo ) * 4;

            if  (strtotime($filters['end_date']. ' +1 days') >= time() ) {
                $timeNow = $this->timeReport($start_date, date('Y-m-d', time()));
                $TimeN = $this->timeReport($start_date_00, date('Y-m-d', time()));
            } else {
                $timeNow = $timeExpected;
                $TimeN = $TimeE;
            }

             

            $nowWar1_3 = ($timeNow['total'] - $totalHo ) * 1;
            $nowWar1 = ($timeNow['total'] - $totalHo ) * 2;
            $nowWar2 = ($timeNow['total'] - $totalHo ) * 3;
            $nowWar3 = ($timeNow['total'] - $totalHo ) * 4;

            $range = $timeNow['range'];
            $range_00 = $TimeN['range'];


            $keyArr = array_keys($range);

            $diff = date_diff(date_create($filters['end_date']), date_create(end($keyArr)));
          
            $timeRange = $diff->format('%a');


            $users = \App\Models\User::getAllUser($filters,$range);
            $users_00 = \App\Models\User::getAllUser($filters,$range_00);
            

            $config = ConfigTimeKeeping::query()->where('code', '=', 'TIME')->first();

            if ($config && $config->settings) {
                $settings = json_decode($config->settings, true);
            }
                foreach ($users_00 as $user_00) {

                    $totalPaidLeave = 0;
                     foreach ($user_00->timeKeeping as $value) {

                        $labelDay = $range_00[$value->check_date];

                        $total_paid = $value->total_paid;

                        }

                }

                foreach ($users as $user) {

                        $timeWar = 0;
                        $totalOT = 0;
                        $totalWar  = 0;

                        $totalGoLate = 0;
                        $timeGoLate = 0;
                        $totalGoEarly = 0;
                        $timeGoEarly = 0;
                        $totalPause = 0;
                        $timePause = 0;
                        $totalGoOut = 0;
                        $timeGoOut = 0;

                        $totalAboutLate = 0;
                        $timeAboutLate = 0;
                        $totalAboutEarly = 0;
                        $timeAboutEarly = 0;

                        $totalWorkDate = 0;
                        $totalTimeKeeping = 0;
                        $totalWorkingDays = 0;
                        $totalNotCheckIn = 0;
                        $totalNotCheckOut = 0;
                        $totalUnpaidLeave = 0;
                        $totalPaidLeave = 0;
                        $totalPaidLeaveFull = 0;
                        $totalPaidLeaveRemain = 0;
                        $totalPayroll = 0;
                        $totalHourEfforts = 0;

                       if ($user->user_status == 1 && $user->position != "Giám đốc" && $user->id != 44 ) {
                        $totalWorkDate = 0;
                        if($user->date_official != ""){
                            $date_official = new Datetime($user->date_official);
                            $date_official_new = date('d-m-Y', strtotime($user->date_official));
                        } else {
                            $date_official_new = 0;
                        }

                        $date_now = new Datetime(date('Y-m-d', time()));
                        $date_first = new Datetime(date('Y-01-01'));

                        if($user->date_official == null) {
                            $totalWorkDate = 0;
                            $totalWorkDate_day = 0;
                            $totalPaidLeaveFull = 0;
                        }else{
                            $totalWorkDate = ($date_official)->diff($date_now);
                            $totalWorkDateFirst = ($date_first)->diff($date_now);
                            $totalWorkDate_day = $totalWorkDate->format('%a');

                            if($totalWorkDate_day >= 365){

                                    $totalPaidLeaveFull =  $totalWorkDateFirst->m + 1;

                            }else{

                                //$totalPaidLeaveFull = $totalWorkDate->m +1;

                            }
                        }

                        foreach ($user->timeKeeping as $value) {

                            $labelDay = $range_00[$value->check_date];
                            $configDay = $settings[$labelDay] ?? [];
                            $type = $value->petition_type;
                            $paid = $value->type_paid;
                            $leave = $value->type_leave;
                            $total_leave = $value->total_leave;
                            $total_pause = $value->total_pause;
                            $time_pause = $value->time_pause;
                            $FinalCheckout = strtotime($value->check_date. ' '. $value->final_checkout);

                            if(in_array($value->check_date, $arrHoliday) ){
                                if($type == 5 || $type == 6){
                                    $checkIn = $value->checkin? strtotime($value->check_date. ' '. $value->checkin): '';
                                   
                                    if( $FinalCheckout == null){
                                        $checkOut = strtotime($value->check_date. ' '. $value->checkout);
                                    }else{
                                        $checkOut = strtotime($value->check_date. ' '. $value->final_checkout);
                                    }
                                }else{
                                $checkIn ='';
                                $checkOut = '';
                                }
                            }else{
                                $checkIn = $value->checkin? strtotime($value->check_date. ' '. $value->checkin): '';
                                if( $FinalCheckout == null){
                                    $checkOut = $value->checkout? strtotime($value->check_date. ' '. $value->checkout): '';
                                }else{
                                     $checkOut = strtotime($value->check_date. ' '. $value->final_checkout);
                                }
                               
                            }
                            $timeFrom = strtotime($value->check_date. ' '. $value->time_from);
                            $timeTo = strtotime($value->check_date. ' '. $value->time_to);

                            if($type == 9) {  
                                $totalGoOut++;
                                $timeGoOut += ($timeTo - $timeFrom);
                            }

                            if ($checkIn && $checkOut) {
                                if($configDay && $type != 6){
                                    if($labelDay == 'monday' || $labelDay == 'tuesday' || $labelDay == 'wednesday' ||
                                    $labelDay == 'thursday' || $labelDay == 'friday'){
                                        if ($type == 0 || $type == 1 || $type == 4 || $type == 5 || $type == 9) {
                                            $start = $configDay['start_timeAM'] != '' ? strtotime($value->check_date . ' ' . $configDay['start_timeAM']) : '';
                                            $end = $configDay['end_timePM'] != '' ? strtotime($value->check_date . ' ' . $configDay['end_timePM']) : '';
    
                                        }else if ($type == 2 && $leave == 1) {
                                            $start = $configDay['start_timePM'] != '' ? strtotime($value->check_date . ' ' . $configDay['start_timePM']) : '';
                                            $end = $configDay['end_timePM'] != '' ? strtotime($value->check_date . ' ' . $configDay['end_timePM']) : '';

                                        }else if ($type == 2 && $leave == 2) {
                                            $start = $configDay['start_timeAM'] != '' ? strtotime($value->check_date . ' ' . $configDay['start_timeAM']) : '';
                                            $end = $configDay['end_timeAM'] != '' ? strtotime($value->check_date . ' ' . $configDay['end_timeAM']) : '';

                                        }

                                        if ($checkIn > $start) {
                                                $totalGoLate++;
                                                $timeGoLate += $checkIn - $start;
                                            } else if ($checkIn < $start) {
                                                $totalGoEarly++;
                                                $timeGoEarly += $start - $checkIn;
                                            }
                                            if ($checkOut > $end) {
                                                $totalAboutLate++;
                                                $timeAboutLate += $checkOut - $end;
                                            } else if ( $checkOut < $end) {
                                                $totalAboutEarly++;
                                                $timeAboutEarly += $end - $checkOut;
                                            }
                                    } else if($labelDay == 'saturday'){
                                        $start = $configDay['start_timeAM'] != '' ? strtotime($value->check_date . ' ' . $configDay['start_timeAM']) : '';
                                        $end = strtotime($value->check_date . ' ' . '13:30');
                                        $end12 = strtotime($value->check_date . ' ' . '12:00');
                                        if ($checkIn > $start) {
                                            $totalGoLate++;
                                            $timeGoLate += $checkIn - $start;
                                        } else if ($checkIn < $start) {
                                            $totalGoEarly++;
                                            $timeGoEarly += $start - $checkIn;
                                        }
                                        if ($checkOut > $end) {
                                            $totalAboutLate++;
                                            $timeAboutLate += $checkOut - $end;
                                        } else if ( $checkOut < $end) {
                                            if($checkOut < $end12){
                                                $totalAboutEarly++;
                                                $timeAboutEarly += $end12 - $checkOut;
                                            } else{
                                                $totalAboutEarly =  $totalAboutEarly;
                                                $timeAboutEarly = $timeAboutEarly;
                                            }
                                        }

                                    }

                                    $totalPause += $total_pause;
                                    $timePause += $time_pause;
                                } else if($configDay && $type == 6){
                                    $totalPause += $total_pause;
                                    $timePause += $time_pause;
                                }

                                if($labelDay == 'monday' || $labelDay == 'tuesday' || $labelDay == 'wednesday' ||
                                    $labelDay == 'thursday' || $labelDay == 'friday'){

                                    if($type == 0 || $type == 1 || $type == 4 || $type == 9){
                                        $totalTimeKeeping++;
                                        
                                    }else if($type == 2 && ($leave == 1 || $leave == 2) ){
                                        if($paid == 0){
                                            $totalUnpaidLeave += $total_leave;
                                        }else if($paid == 1){
                                            $totalPaidLeave += $total_leave;
                                        }
                                        $totalTimeKeeping = $totalTimeKeeping + 1/2;
                                        
                                    }else if($type == 2 && ($leave == 3 || $leave == 4)){
                                        if($paid == 0){
                                            $totalUnpaidLeave += $total_leave;
                                        }else if($paid == 1){
                                            $totalPaidLeave += $total_leave;
                                        }

                                    }else if($type == 5){
                                        $totalOT++;
                                        
                                    }else if($type == 6){
                                        $totalWar++;
                                        $timeWar += $checkOut - $checkIn;

                                    }else if($type == 7){
                                        $totalTimeKeeping++;
                                        
                                    }
                                } else if($labelDay == 'saturday'){
                                    if($type == 0 || $type == 1 || $type == 4 || $type == 9){
                                        $totalTimeKeeping = $totalTimeKeeping + 1/2;   
                                    }else if($type == 2 && ($leave == 1 || $leave == 2 || $leave == 3 || $leave == 4 )){
                                        if($paid == 0){
                                            $totalUnpaidLeave += $total_leave;
                                        }else if($paid == 1){
                                            $totalPaidLeave += $total_leave;
                                        }
                                    }else if($type == 5){
                                        $totalOT =  $totalOT+ 1/2;
                                        
                                    }else if($type == 6){
                                        $totalWar++;
                                        $timeWar += $checkOut - $checkIn;
                                        
                                    }else if($type == 7){
                                        $totalTimeKeeping = $totalTimeKeeping + 1/2;
                                        
                                    }
                                }else if($labelDay == 'sunday'){
                                    if($type == 5){
                                        $totalTimeKeeping++;
                                        
                                    }else if($type == 6){
                                        $totalWar++;
                                        $timeWar += $checkOut - $checkIn;
                                        
                                    }
                                }

                            }else if($checkIn && !$checkOut){
                                    $totalNotCheckOut++;

                            }else if(!$checkIn && !$checkOut){
                                if($labelDay == 'monday' || $labelDay == 'tuesday' || $labelDay == 'wednesday' ||
                                    $labelDay == 'thursday' || $labelDay == 'friday'){

                                    if($type == 2 && ($leave == 3 || $leave == 4)){
                                        if($paid == 0){
                                            $totalUnpaidLeave += $total_leave;
                                        }else if($paid == 1){
                                            $totalPaidLeave += $total_leave;
                                        }
                                } else if($labelDay == 'saturday'){
                                    
                                   if($type == 2 && ($leave == 1 || $leave == 2 || $leave == 3 || $leave == 4 )){
                                        if($paid == 0){
                                            $totalUnpaidLeave += $total_leave;
                                        }else if($paid == 1){
                                            $totalPaidLeave += $total_leave;
                                        }
                                    }
                                }
                            }
                        }
                    }

                        $totalHourEfforts = ($timeWar + $timeGoEarly + $timeAboutLate)/3600;

                        $currentWar = '';
                        $nextWar = '';
                        $timeHoldWar = 0;
                        $timeIncreaseWar = 0;
                        $avgTimeHoldWar = 0;
                        $avgTimeIncreaseWar = 0;
                        $EmployeeLongtime =1094;
                        $AllowanceWarrior = 0;

                        if ($timeRange) {
                            if($totalWorkDate_day > $EmployeeLongtime ){
                                if($totalHourEfforts < $nowWar1_3) {
                                    $currentWar = 'Soldier';
                                    $nextWar = 'Warrior 1';
                                    $AllowanceWarrior = 0;
                                    $timeIncreaseWar = $expectedWar1_3 - $totalHourEfforts;
                                } elseif ( $totalHourEfforts >= $nowWar1_3 && $totalHourEfforts< $nowWar1) {
                                    $currentWar = 'Warrior 1';
                                    $nextWar = 'Warrior 2';
                                    $AllowanceWarrior = 700000;
                                    $timeHoldWar = $expectedWar1_3- $totalHourEfforts;
                                    $timeIncreaseWar = $expectedWar1 - $totalHourEfforts;
                                } elseif ($totalHourEfforts >= $nowWar1 && $totalHourEfforts< $nowWar2) {
                                    $currentWar = 'Warrior 2';
                                    $nextWar = 'Warrior 3';
                                    $AllowanceWarrior = 900000;
                                    $timeHoldWar = $expectedWar1 - $totalHourEfforts;
                                    $timeIncreaseWar = $expectedWar2 - $totalHourEfforts;
                                } elseif ($totalHourEfforts >= $nowWar2) {
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
                                } elseif ($totalHourEfforts >= $nowWar1 && $totalHourEfforts< $nowWar2) {
                                    $currentWar = 'Warrior 1';
                                    $nextWar = 'Warrior 2';
                                    $AllowanceWarrior = 700000;
                                    $timeHoldWar = $expectedWar1- $totalHourEfforts;
                                    $timeIncreaseWar = $expectedWar2 - $totalHourEfforts;
                                } elseif ($totalHourEfforts >= $nowWar2 && $totalHourEfforts< $nowWar3) {
                                    $currentWar = 'Warrior 2';
                                    $nextWar = 'Warrior 3';
                                    $AllowanceWarrior = 900000;
                                    $timeHoldWar = $expectedWar2 - $totalHourEfforts;
                                    $timeIncreaseWar = $expectedWar3 - $totalHourEfforts;
                                } elseif ($totalHourEfforts >= $nowWar3) {
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
                            if($totalWorkDate_day > $EmployeeLongtime ){
                                if( $totalHourEfforts < $nowWar1_3) {
                                    $currentWar = 'Soldier';
                                    $AllowanceWarrior = 0;
                                } elseif ( $totalHourEfforts >= $nowWar1_3 && $totalHourEfforts< $nowWar1) {
                                    $currentWar = 'Warrior 1';
                                    $AllowanceWarrior = 700000;
                                } elseif ($totalHourEfforts >= $nowWar1 && $totalHourEfforts< $nowWar2) {
                                    $currentWar = 'Warrior 2';
                                    $AllowanceWarrior = 900000;
                                } elseif ($totalHourEfforts >= $nowWar2) {
                                    $currentWar = 'Warrior 3';
                                    $AllowanceWarrior = 1100000;
                                }
                            } else{
                                if($totalHourEfforts < $nowWar1) {
                                    $currentWar = 'Soldier';
                                    $AllowanceWarrior = 0;
                                } elseif ($totalHourEfforts >= $nowWar1 && $totalHourEfforts< $nowWar2) {
                                    $currentWar = 'Warrior 1';
                                    $AllowanceWarrior = 700000;
                                } elseif ($totalHourEfforts >= $nowWar2 && $totalHourEfforts< $nowWar3) {
                                    $currentWar = 'Warrior 2';
                                    $AllowanceWarrior = 900000;
                                } elseif ($totalHourEfforts >= $nowWar3) {
                                    $currentWar = 'Warrior 3';
                                    $AllowanceWarrior = 1100000;
                                }
                            }
                        }
                        $totalPayroll = $totalTimeKeeping + $totalOT + $totalPaidLeave - ($timeGoLate/3600 + $timeAboutEarly/3600 + $timePause/60)/8 ;
                        $rateGoLate = $totalTimeKeeping? round(($totalGoLate/$totalTimeKeeping), 4) * 100 : 0;

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
                            'totalWorkDateY' => $totalWorkDate?$totalWorkDate->y : 0,
                            'totalWorkDateM' => $totalWorkDate?$totalWorkDate->m : 0,
                            'totalWorkDateD' => $totalWorkDate?$totalWorkDate->d : 0,
                            'totalGoLate' => $totalGoLate,
                            'timeGoLate' => round($timeGoLate/3600, 2),
                            'totalGoEarly' => $totalGoEarly,
                            'timeGoEarly' => $timeGoEarly/3600,
                            'totalPause' => $totalPause,
                            'timePause' => round($timePause/60, 2),
                            'totalGoOut' => $totalGoOut,
                            'timeGoOut' => round($timeGoOut/3600, 2),
                            'totalAboutLate' => $totalAboutLate,
                            'timeAboutLate' => $timeAboutLate/3600,
                            'totalAboutEarly' => $totalAboutEarly,
                            'timeAboutEarly' => round($timeAboutEarly/3600, 2),
                            'timeWar' => round($timeWar/3600, 2),
                            'totalTimeKeeping' =>  round($totalTimeKeeping - ($timeGoLate/3600 + $timeAboutEarly/3600 + $timePause/60)/8, 2),
                            'totalWorkingDays' => $totalWorkingDays,
                            'totalUnpaidLeave' => $totalUnpaidLeave,
                            'totalPaidLeave' => $totalPaidLeave,
                            'totalPaidLeaveFull' => $totalPaidLeaveFull,
                            'totalPayroll' => round($totalPayroll,2),
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
                            'totalGoLateAboutEarly' => round(($timeGoLate/3600 + $timeAboutEarly/3600 + $timePause/60),2),

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

    public function FinalCheckoutHandmade(User $user)
    {
        if ($user->check_type == 1) {
            $timeKeeping = TimeKeeping::query()
                ->where([
                    'user_id' => $user->id,
                    'check_date' => date('Y-m-d', time())
                ])->first();
                $timeKeeping->final_checkout = date('H:i:s', time());
                $timeKeeping->save();

                return [
                    'code' => 200,
                ];

            return true;
        }

        return false;
    }

    public function goOutHandmade(User $user)
    {

        $go_out = TimeGoOut::query()
            ->where([
                'user_id' => $user->id,
                'go_date' => date('Y-m-d', time())
            ])->orderBy('updated_at', 'desc')->first();
        $timeKeeping = TimeKeeping::query()
        ->where([
            'user_id' => $user->id,
            'check_date' => date('Y-m-d', time())
        ])->first();
            
        if (!$go_out) {
            $go_out = new TimeGoOut();
            $go_out->user_id = $user->id;
            $go_out->go_date = date('Y-m-d', time());
            $go_out->go_out = date('H:i:s', time());

            $timeKeeping->btn_check = 2;

                if(($go_out->go_out >= "08:00:00" && $go_out->go_out < "12:00:00") 
                    || ($go_out->go_out >= "13:30:00" && $go_out->go_out < "17:30:00"))
                    {
                        $timeKeeping->total_pause++;

                }else if($go_out->go_out < "08:00:00" || $go_out->go_out > "17:30:00")
                    {
                        $timeKeeping->total_pause_w++;
                }

        } else {
            if (empty($go_out->go_in)) {
                $go_out->go_in = date('H:i:s', time());
                $time8 = strtotime($go_out->go_date. ' ' . '08:00:00');
                $time12 = strtotime($go_out->go_date. ' ' . '12:00:00');
                $time135 = strtotime($go_out->go_date. ' ' . '13:30:00');
                $time175 = strtotime($go_out->go_date. ' ' . '17:30:00');

                $time_pause = $go_out->time_pause = (strtotime($go_out->go_in) - strtotime($go_out->go_out))/60;
                $time_pause8_1 = ($time8 - strtotime($go_out->go_out))/60;
                $time_pause8_2 = (strtotime($go_out->go_in) - $time8)/60;
                $time_pause12_1 = ($time12 - strtotime($go_out->go_out))/60;
                $time_pause12_2 = (strtotime($go_out->go_in) - $time12)/60;
                $time_pause135_1 = ($time135 - strtotime($go_out->go_out))/60;
                $time_pause135_2 = (strtotime($go_out->go_in) - $time135)/60;
                $time_pause175_1 = ($time175 - strtotime($go_out->go_out))/60;
                $time_pause175_2 = (strtotime($go_out->go_in) - $time175)/60;

                $timeKeeping->btn_check = 1;
                if(($go_out->go_out > "08:00:00" && $go_out->go_out < "12:00:00" && $go_out->go_in < "12:00:00") 
                || ($go_out->go_out > "13:30:00" && $go_out->go_out < "17:30:00" && $go_out->go_in < "17:30:00"))
                {
                    $timeKeeping->time_pause += $time_pause;

                }else if(($go_out->go_out < "08:00:00" && $go_out->go_in < "08:00:00") 
                || ($go_out->go_out > "17:30:00" && $go_out->go_in > "17:30:00") 
                || ($go_out->go_out > "11:00:00" && $go_out->go_out <= "12:00:00" && $go_out->go_in <= "12:00:00"))
                {
                    $timeKeeping->time_pause_w += $time_pause;
                }else if($go_out->go_out < "08:00:00" && $go_out->go_in > "08:00:00"){
                    $timeKeeping->time_pause_w += $time_pause8_1;
                    $timeKeeping->time_pause += $time_pause8_2;
                }else if($go_out->go_out < "17:30:00" && $go_out->go_in > "17:30:00"){
                    $timeKeeping->time_pause_w += $time_pause175_2;
                    $timeKeeping->time_pause += $time_pause175_1;
                }else if($go_out->go_out < "12:00:00" && $go_out->go_in > "12:00:00" && $go_out->go_in < "13:30:00"){
                    $timeKeeping->time_pause += $time_pause12_1;
                }else if($go_out->go_out < "12:00:00" && $go_out->go_in > "12:00:00" && $go_out->go_in > "13:30:00"){
                    $timeKeeping->time_pause += ($time_pause - 90) ;
                }else if($go_out->go_out < "13:30:00" && $go_out->go_in > "13:30:00"){
                    $timeKeeping->time_pause += $time_pause135_2 ;
                }
                

            }else{
                $go_out = new TimeGoOut();
                $go_out->user_id = $user->id;
                $go_out->go_date = date('Y-m-d', time());
                $go_out->go_out = date('H:i:s', time());

                $timeKeeping->btn_check = 2;
                if(($go_out->go_out > "08:00:00" && $go_out->go_out < "11:00:00") 
                || ($go_out->go_out > "13:30:00" && $go_out->go_out < "17:30:00"))
                {
                    $timeKeeping->total_pause =  $timeKeeping->total_pause + 1;

                }else if($go_out->go_out < "08:00:00" || $go_out->go_out > "17:30:00"  
                || ($go_out->go_out > "11:00:00" && $go_out->go_out <= "12:00:00"))
                {
                    $timeKeeping->total_pause_w =  $timeKeeping->total_pause_w + 1;
                }

            }
        }
        $go_out->save();
        $timeKeeping->save();

        return [
            'status' => '200',
            'message' => 'Thao tác thành công'
        ];

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
