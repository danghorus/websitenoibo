<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\PetitionService;
use App\Services\TimeKeepingService;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\Petition;
use App\Models\User;
use Illuminate\Http\Request;

class PetitionController extends Controller
{
     /**
     * @var TimeKeepingService
     */
    private $timeKeepingService;
    private PetitionService $petitionService;

    public function __construct(
        TimeKeepingService $timeKeepingService,
        PetitionService $petitionService
    )
    {
        $this->timeKeepingService = $timeKeepingService;
        $this->petitionService = $petitionService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $petitions = Petition::where('petition_status', 1)->orderby('created_at', 'DESC')->get();
		$petitions1 = Petition::where('petition_status', 1)->get();
		$userId = Auth::user()->id;
		$petitions01 = Petition::where('petition_status', 1)->where('user_id', '=', $userId)->get();
		$petitions02 = Petition::where('petition_status', 2)->where('user_id', '=', $userId)->where('readed', '=', 0)->get();
		$petitions03 = Petition::where('petition_status', 3)->where('user_id', '=', $userId)->where('readed', '=', 0)->get();

        $users = User::all();
        foreach ($petitions as $petition) {

            foreach($users as $user){
            if($petition->user_id == $user->id)
                $petition->fullname = $user->fullname;
            }

            $type = $petition->petition_type;
            $leave = $petition->type_leave;
            $time_from_old = $petition->time_from_old? date("H:i", strtotime($petition->time_from_old)): ' -:- ';
            $time_from = $petition->time_from? date("H:i", strtotime($petition->time_from)): ' -:- ';
            $date_from = date("d-m-Y", strtotime($petition->date_from));
            $time_to_old = $petition->time_to_old? date("H:i", strtotime($petition->time_to_old)): ' -:- ';
            $time_to = $petition->time_to? date("H:i", strtotime($petition->time_to)): ' -:- ';
            $date_to = date("d-m-Y", strtotime($petition->date_to));
            $date_created_at_d = date("d-m-Y", strtotime($petition->created_at ));
            $date_created_at_t = date("H:i", strtotime($petition->created_at ));

            if($petition->date_to !=null){
                $date_ft = (strtotime($petition->date_to) - strtotime($petition->date_from))/3600/24 +1;
            }else{
                 $date_ft = "";
            }
            $petition->date_ft =  $date_ft;

            $date_from_check = strtotime($petition->date_from) + 8*3600;
            $created_at_check = strtotime($petition->created_at);
            $time_check = ($date_from_check - $created_at_check);
            $time_check_h = ($time_check - $time_check%3600)/3600;
            $time_check_m = (($time_check%3600) - ($time_check%3600)%60)/60;
            $time_check_d =  ($time_check_h -  $time_check_h%24)/24;
            $time_check_hh = ($time_check_h%24);

            $petition->check_1 = $date_from_check;
            $petition->check_2 = $created_at_check;
            $petition->time_check =  $time_check_h;

            $petition->send =  $date_created_at_t." ngày ".$date_created_at_d;

            if( $type == 1){
               $petition->petition_type_title =  "Đi muộn về sớm";
               $petition->info = "Ngày " . $date_from . " từ " . $time_from . " đến " .  $time_to;

            } else if($type == 2){
                $petition->info = "Ngày " . $date_from;
                if($leave == 1){
                    if($petition->type_paid == 0){
                        $petition->petition_type_title =  "Nghỉ phép không lương nửa ngày(sáng)";
                        if($time_check_h < 48){
                            $petition->check = "Vi phạm do tạo yêu cầu trước ". $time_check_d." ngày ". $time_check_hh." giờ ".$time_check_m." phút.";
                        }else{
                             $petition->check = "";
                        }
                    }else if($petition->type_paid == 1){
                        $petition->petition_type_title =  "Nghỉ phép có lương nửa ngày(sáng)";
                        if($time_check_h < 120){
                            $petition->check = "Vi phạm do tạo yêu cầu trước ". $time_check_d." ngày ". $time_check_hh." giờ ".$time_check_m." phút.";
                        }else{
                             $petition->check = "";
                        }
                    }
                }else if($leave == 2){
                    if($petition->type_paid == 0){
                        $petition->petition_type_title =  "Nghỉ phép không lương nửa ngày(chiều)";
                        if($time_check_h < 48){
                            $petition->check = "Vi phạm do tạo yêu cầu trước ". $time_check_d." ngày ". $time_check_hh." giờ ".$time_check_m." phút.";
                        }else{
                             $petition->check = "";
                        }
                    }else if($petition->type_paid == 1){
                        $petition->petition_type_title =  "Nghỉ phép có lương nửa ngày(chiều)";
                        if($time_check_h < 120){
                            $petition->check = "Vi phạm do tạo yêu cầu trước ". $time_check_d." ngày ". $time_check_hh." giờ ".$time_check_m." phút.";
                        }else{
                             $petition->check = "";
                        }
                    }
                } else if($leave == 3){
                    if($petition->type_paid == 0){
                        $petition->petition_type_title =  "Nghỉ phép không lương một ngày";
                        if($time_check_h < 48){
                            $petition->check = "Vi phạm do tạo yêu cầu trước ". $time_check_d." ngày ". $time_check_hh." giờ ".$time_check_m." phút.";
                        }else{
                             $petition->check = "";
                        }
                    }else if($petition->type_paid == 1){
                        $petition->petition_type_title =  "Nghỉ phép có lương một ngày";
                        if($time_check_h < 120){
                           $petition->check = "Vi phạm do tạo yêu cầu trước ". $time_check_d." ngày ". $time_check_hh." giờ ".$time_check_m." phút.";
                        }else{
                             $petition->check = "";
                        }
                    }
                } else if($leave == 4){

                    $petition->info = "Từ ngày " . $date_from . " đến ngày " . $date_to;

                    if($petition->type_paid == 0){
                        $petition->petition_type_title =  "Nghỉ phép không lương nhiều ngày";
                        if($date_ft < 3){
                            if($time_check_h < 48){
                               $petition->check = "Vi phạm do tạo yêu cầu trước ". $time_check_d." ngày ". $time_check_hh." giờ ".$time_check_m." phút.";
                            }else{
                                $petition->check = "";
                            }
                        } else {
                            if($time_check_h < 120){
                               $petition->check = "Vi phạm do tạo yêu cầu trước ". $time_check_d." ngày ". $time_check_hh." giờ ".$time_check_m." phút.";
                            }else{
                                $petition->check = "";
                            }

                        }
                    }else if($petition->type_paid == 1){
                        $petition->petition_type_title =  "Nghỉ phép có lương nhiều ngày";
                        if($date_ft <3){
                            if($time_check_h < 168){
                                $petition->check = "Vi phạm";
                            }else{
                                $petition->check = "";
                            }
                        } else {
                            if($time_check_h < 336){
                                $petition->check = "Vi phạm";
                            }else{
                                $petition->check = "";
                            }

                        }
                    }
                }
            }else if($type == 3){
                $petition->petition_type_title =  "Nghỉ việc";
                $petition->info = "Ngày " . $date_from;
            }else if($type == 4){
                $petition->petition_type_title =  "Thay đổi giờ công";
                $petition->info = "Ngày ".$date_from." từ ".$time_from_old." - ". $time_to_old." thành ".$time_from." - ".$time_to;
            }else if($type == 5){
                $petition->petition_type_title =  "Đăng ký làm thêm";
                $petition->info = "Ngày " . $date_from;
            }else if($type == 6){
                $petition->petition_type_title =  "Đăng ký nỗ lực";
                $petition->info = "Ngày " . $date_from;
            }else if($type == 9){
                $petition->petition_type_title =  "Đăng ký ra ngoài";
                $petition->info = "Ngày " . $date_from . " từ " . $time_from . " đến " .  $time_to;
            }

        }

        return view('petitions.index',compact('petitions','petitions1','users', 'petitions01', 'petitions02', 'petitions03'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all();
        return view('petitions.create',compact('users'));
    }

    public function approved(Request $request)
    {

        $petitions = Petition::where('petition_status', 2)->orderby('created_at', 'DESC')->get();
		$petitions1 = Petition::where('petition_status', 1)->get();
		$userId = Auth::user()->id;
		$petitions01 = Petition::where('petition_status', 1)->where('user_id', '=', $userId)->get();
		$petitions02 = Petition::where('petition_status', 2)->where('user_id', '=', $userId)->where('readed', '=', 0)->get();
		$petitions03 = Petition::where('petition_status', 3)->where('user_id', '=', $userId)->where('readed', '=', 0)->get();

        $users = User::all();

        foreach ($petitions as $petition) {

            foreach($users as $user){
            if($petition->user_id == $user->id)
                $petition->fullname = $user->fullname;
            }

            $type = $petition->petition_type;
            $leave = $petition->type_leave;
            $time_from_old = $petition->time_from_old? date("H:i", strtotime($petition->time_from_old)): ' -:- ';
            $time_from = $petition->time_from? date("H:i", strtotime($petition->time_from)): ' -:- ';
            $date_from = date("d-m-Y", strtotime($petition->date_from));
            $time_to_old = $petition->time_to_old? date("H:i", strtotime($petition->time_to_old)): ' -:- ';
            $time_to = $petition->time_to? date("H:i", strtotime($petition->time_to)): ' -:- ';
            $date_to = date("d-m-Y", strtotime($petition->date_to));
            $date_created_at_d = date("d-m-Y", strtotime($petition->created_at ));
            $date_created_at_t = date("H:i", strtotime($petition->created_at ));

            if($petition->date_to !=null){
                $date_ft = (strtotime($petition->date_to) - strtotime($petition->date_from))/3600/24 +1;
            }else{
                 $date_ft = "";
            }
            $petition->date_ft =  $date_ft;

            $date_from_check = strtotime($petition->date_from) + 8*3600;
            $created_at_check = strtotime($petition->created_at);
            $time_check = ($date_from_check - $created_at_check);
            $time_check_h = ($time_check - $time_check%3600)/3600;
            $time_check_m = (($time_check%3600) - ($time_check%3600)%60)/60;
            $time_check_d =  ($time_check_h -  $time_check_h%24)/24;
            $time_check_hh = ($time_check_h%24);

            $petition->check_1 = $date_from_check;
            $petition->check_2 = $created_at_check;
            $petition->time_check =  $time_check_h;

            $petition->send =  $date_created_at_t." ngày ".$date_created_at_d;

            if( $type == 1){
               $petition->petition_type_title =  "Đi muộn về sớm";
               $petition->info = "Ngày " . $date_from . " từ " . $time_from . " đến " .  $time_to;

            } else if($type == 2){
                $petition->info = "Ngày " . $date_from;
                if($leave == 1){
                    if($petition->type_paid == 0){
                        $petition->petition_type_title =  "Nghỉ phép không lương nửa ngày(sáng)";
                        if($time_check_h < 48){
                            $petition->check = "Vi phạm do tạo yêu cầu trước ". $time_check_d." ngày ". $time_check_hh." giờ ".$time_check_m." phút.";
                        }else{
                             $petition->check = "";
                        }
                    }else if($petition->type_paid == 1){
                        $petition->petition_type_title =  "Nghỉ phép có lương nửa ngày(sáng)";
                        if($time_check_h < 120){
                            $petition->check = "Vi phạm do tạo yêu cầu trước ". $time_check_d." ngày ". $time_check_hh." giờ ".$time_check_m." phút.";
                        }else{
                             $petition->check = "";
                        }
                    }
                }else if($leave == 2){
                    if($petition->type_paid == 0){
                        $petition->petition_type_title =  "Nghỉ phép không lương nửa ngày(chiều)";
                        if($time_check_h < 48){
                            $petition->check = "Vi phạm do tạo yêu cầu trước ". $time_check_d." ngày ". $time_check_hh." giờ ".$time_check_m." phút.";
                        }else{
                             $petition->check = "";
                        }
                    }else if($petition->type_paid == 1){
                        $petition->petition_type_title =  "Nghỉ phép có lương nửa ngày(chiều)";
                        if($time_check_h < 120){
                            $petition->check = "Vi phạm do tạo yêu cầu trước ". $time_check_d." ngày ". $time_check_hh." giờ ".$time_check_m." phút.";
                        }else{
                             $petition->check = "";
                        }
                    }
                } else if($leave == 3){
                    if($petition->type_paid == 0){
                        $petition->petition_type_title =  "Nghỉ phép không lương một ngày";
                        if($time_check_h < 48){
                            $petition->check = "Vi phạm do tạo yêu cầu trước ". $time_check_d." ngày ". $time_check_hh." giờ ".$time_check_m." phút.";
                        }else{
                             $petition->check = "";
                        }
                    }else if($petition->type_paid == 1){
                        $petition->petition_type_title =  "Nghỉ phép có lương một ngày";
                        if($time_check_h < 120){
                           $petition->check = "Vi phạm do tạo yêu cầu trước ". $time_check_d." ngày ". $time_check_hh." giờ ".$time_check_m." phút.";
                        }else{
                             $petition->check = "";
                        }
                    }
                } else if($leave == 4){

                    $petition->info = "Từ ngày " . $date_from . " đến ngày " . $date_to;

                    if($petition->type_paid == 0){
                        $petition->petition_type_title =  "Nghỉ phép không lương nhiều ngày";
                        if($date_ft < 3){
                            if($time_check_h < 48){
                               $petition->check = "Vi phạm do tạo yêu cầu trước ". $time_check_d." ngày ". $time_check_hh." giờ ".$time_check_m." phút.";
                            }else{
                                $petition->check = "";
                            }
                        } else {
                            if($time_check_h < 120){
                               $petition->check = "Vi phạm do tạo yêu cầu trước ". $time_check_d." ngày ". $time_check_hh." giờ ".$time_check_m." phút.";
                            }else{
                                $petition->check = "";
                            }

                        }
                    }else if($petition->type_paid == 1){
                        $petition->petition_type_title =  "Nghỉ phép có lương nhiều ngày";
                        if($date_ft <3){
                            if($time_check_h < 168){
                                $petition->check = "Vi phạm";
                            }else{
                                $petition->check = "";
                            }
                        } else {
                            if($time_check_h < 336){
                                $petition->check = "Vi phạm";
                            }else{
                                $petition->check = "";
                            }

                        }
                    }
                }
            }else if($type == 3){
                $petition->petition_type_title =  "Nghỉ việc";
                $petition->info = "Ngày " . $date_from;
            }else if($type == 4){
                $petition->petition_type_title =  "Thay đổi giờ công";
                $petition->info = "Ngày ".$date_from." từ ".$time_from_old." - ". $time_to_old." thành ".$time_from." - ".$time_to;
            }else if($type == 5){
                $petition->petition_type_title =  "Đăng ký làm thêm";
                $petition->info = "Ngày " . $date_from;
            }else if($type == 6){
                $petition->petition_type_title =  "Đăng ký nỗ lực";
                $petition->info = "Ngày " . $date_from;
            }else if($type == 9){
                $petition->petition_type_title =  "Đăng ký ra ngoài";
                $petition->info = "Ngày " . $date_from . " từ " . $time_from . " đến " .  $time_to;
            }

        }

        return view('petitions.approved',compact('petitions','petitions1','users', 'petitions01', 'petitions02', 'petitions03'));
    }
    public function unapproved()
    {

        $petitions = Petition::where('petition_status', 3)->orderby('created_at', 'DESC')->get();
        $petitions1 = Petition::where('petition_status', 1)->get();
        $userId = Auth::user()->id;
        $petitions01 = Petition::where('petition_status', 1)->where('user_id', '=', $userId)->get();
        $petitions02 = Petition::where('petition_status', 2)->where('user_id', '=', $userId)->where('readed', '=', 0)->get();
        $petitions03 = Petition::where('petition_status', 3)->where('user_id', '=', $userId)->where('readed', '=', 0)->get();
        $users = User::all();

        foreach ($petitions as $petition) {

            foreach($users as $user){
            if($petition->user_id == $user->id)
                $petition->fullname = $user->fullname;
            }

            $type = $petition->petition_type;
            $leave = $petition->type_leave;
            $time_from_old = $petition->time_from_old? date("H:i", strtotime($petition->time_from_old)): ' -:- ';
            $time_from = $petition->time_from? date("H:i", strtotime($petition->time_from)): ' -:- ';
            $date_from = date("d-m-Y", strtotime($petition->date_from));
            $time_to_old = $petition->time_to_old? date("H:i", strtotime($petition->time_to_old)): ' -:- ';
            $time_to = $petition->time_to? date("H:i", strtotime($petition->time_to)): ' -:- ';
            $date_to = date("d-m-Y", strtotime($petition->date_to));
            $date_created_at_d = date("d-m-Y", strtotime($petition->created_at ));
            $date_created_at_t = date("H:i", strtotime($petition->created_at ));

            if($petition->date_to !=null){
                $date_ft = (strtotime($petition->date_to) - strtotime($petition->date_from))/3600/24 +1;
            }else{
                 $date_ft = "";
            }
            $petition->date_ft =  $date_ft;

            $date_from_check = strtotime($petition->date_from) + 8*3600;
            $created_at_check = strtotime($petition->created_at);
            $time_check = ($date_from_check - $created_at_check);
            $time_check_h = ($time_check - $time_check%3600)/3600;
            $time_check_m = (($time_check%3600) - ($time_check%3600)%60)/60;
            $time_check_d =  ($time_check_h -  $time_check_h%24)/24;
            $time_check_hh = ($time_check_h%24);

            $petition->check_1 = $date_from_check;
            $petition->check_2 = $created_at_check;
            $petition->time_check =  $time_check_h;

            $petition->send =  $date_created_at_t." ngày ".$date_created_at_d;

            if( $type == 1){
               $petition->petition_type_title =  "Đi muộn về sớm";
               $petition->info = "Ngày " . $date_from . " từ " . $time_from . " đến " .  $time_to;

            } else if($type == 2){
                $petition->info = "Ngày " . $date_from;
                if($leave == 1){
                    if($petition->type_paid == 0){
                        $petition->petition_type_title =  "Nghỉ phép không lương nửa ngày(sáng)";
                        if($time_check_h < 48){
                            $petition->check = "Vi phạm do tạo yêu cầu trước ". $time_check_d." ngày ". $time_check_hh." giờ ".$time_check_m." phút.";
                        }else{
                             $petition->check = "";
                        }
                    }else if($petition->type_paid == 1){
                        $petition->petition_type_title =  "Nghỉ phép có lương nửa ngày(sáng)";
                        if($time_check_h < 120){
                            $petition->check = "Vi phạm do tạo yêu cầu trước ". $time_check_d." ngày ". $time_check_hh." giờ ".$time_check_m." phút.";
                        }else{
                             $petition->check = "";
                        }
                    }
                }else if($leave == 2){
                    if($petition->type_paid == 0){
                        $petition->petition_type_title =  "Nghỉ phép không lương nửa ngày(chiều)";
                        if($time_check_h < 48){
                            $petition->check = "Vi phạm do tạo yêu cầu trước ". $time_check_d." ngày ". $time_check_hh." giờ ".$time_check_m." phút.";
                        }else{
                             $petition->check = "";
                        }
                    }else if($petition->type_paid == 1){
                        $petition->petition_type_title =  "Nghỉ phép có lương nửa ngày(chiều)";
                        if($time_check_h < 120){
                            $petition->check = "Vi phạm do tạo yêu cầu trước ". $time_check_d." ngày ". $time_check_hh." giờ ".$time_check_m." phút.";
                        }else{
                             $petition->check = "";
                        }
                    }
                } else if($leave == 3){
                    if($petition->type_paid == 0){
                        $petition->petition_type_title =  "Nghỉ phép không lương một ngày";
                        if($time_check_h < 48){
                            $petition->check = "Vi phạm do tạo yêu cầu trước ". $time_check_d." ngày ". $time_check_hh." giờ ".$time_check_m." phút.";
                        }else{
                             $petition->check = "";
                        }
                    }else if($petition->type_paid == 1){
                        $petition->petition_type_title =  "Nghỉ phép có lương một ngày";
                        if($time_check_h < 120){
                           $petition->check = "Vi phạm do tạo yêu cầu trước ". $time_check_d." ngày ". $time_check_hh." giờ ".$time_check_m." phút.";
                        }else{
                             $petition->check = "";
                        }
                    }
                } else if($leave == 4){

                    $petition->info = "Từ ngày " . $date_from . " đến ngày " . $date_to;

                    if($petition->type_paid == 0){
                        $petition->petition_type_title =  "Nghỉ phép không lương nhiều ngày";
                        if($date_ft < 3){
                            if($time_check_h < 48){
                               $petition->check = "Vi phạm do tạo yêu cầu trước ". $time_check_d." ngày ". $time_check_hh." giờ ".$time_check_m." phút.";
                            }else{
                                $petition->check = "";
                            }
                        } else {
                            if($time_check_h < 120){
                               $petition->check = "Vi phạm do tạo yêu cầu trước ". $time_check_d." ngày ". $time_check_hh." giờ ".$time_check_m." phút.";
                            }else{
                                $petition->check = "";
                            }

                        }
                    }else if($petition->type_paid == 1){
                        $petition->petition_type_title =  "Nghỉ phép có lương nhiều ngày";
                        if($date_ft <3){
                            if($time_check_h < 168){
                                $petition->check = "Vi phạm";
                            }else{
                                $petition->check = "";
                            }
                        } else {
                            if($time_check_h < 336){
                                $petition->check = "Vi phạm";
                            }else{
                                $petition->check = "";
                            }

                        }
                    }
                }
            }else if($type == 3){
                $petition->petition_type_title =  "Nghỉ việc";
                $petition->info = "Ngày " . $date_from;
            }else if($type == 4){
                $petition->petition_type_title =  "Thay đổi giờ công";
                $petition->info = "Ngày ".$date_from." từ ".$time_from_old." - ". $time_to_old." thành ".$time_from." - ".$time_to;
            }else if($type == 5){
                $petition->petition_type_title =  "Đăng ký làm thêm";
                $petition->info = "Ngày " . $date_from;
            }else if($type == 6){
                $petition->petition_type_title =  "Đăng ký nỗ lực";
                $petition->info = "Ngày " . $date_from;
            }else if($type == 9){
                $petition->petition_type_title =  "Đăng ký ra ngoài";
                $petition->info = "Ngày " . $date_from . " từ " . $time_from . " đến " .  $time_to;
            }

        }

        return view('petitions.unapproved',compact('petitions','petitions1','users', 'petitions01', 'petitions02', 'petitions03'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'petition_reason' => 'required',
        ]);

        Petition::create($request->all());
        $users = User::all();


        return redirect()->route('petitions.index', compact('users'))
                        ->with('success','Petition created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Petition  $Petition
     * @return \Illuminate\Http\Response
     */
    public function show(Petition $petition)
    {
        $users = User::all();
        return view('petitions.show',compact('petition', 'users'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Petition  $petition
     * @return \Illuminate\Http\Response
     */
    public function edit(Petition $petition)
    {
       
        $petition = Petition::where('petition_status', 0)->get();
        $petitions = Petition::where('petition_status', 0)->get();
        $petitions1 = Petition::where('petition_status', 1)->get();
        $userId = Auth::user()->id;
        $petitions01 = Petition::where('petition_status', 1)->where('user_id', '=', $userId)->get();
        $petitions02 = Petition::where('petition_status', 2)->where('user_id', '=', $userId)->where('readed', '=', 0)->get();
        $petitions00 = Petition::where('petition_status', 0)->where('user_id', '=', $userId)->where('readed', '=', 0)->get();
        $users = User::all();
        return view('petitions.edit',compact('petitions', 'petition','petitions1','users', 'petitions01', 'petitions00', 'petitions02'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Petition  $petition
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Petition $petition)
    {

        $petitionStatus = $request->petition_status;
        $infringe = $request->infringe;
        $petitionRead = $request->readed;

        if(!$petitionRead){
            $petition -> update([
                'petition_status' => $petitionStatus,
                'infringe' =>  $infringe,
            ]);  

            if ($petition->petition_type == 4 && $request->petition_status == 2) {
                $dataUpdate = [];
                $dataUpdate['user_id'] = $petition->user_id;
                $dataUpdate['date'] = $petition->date_from;
                $dataUpdate['reason'] = $petition->petition_reason;
                $dataUpdate['checkin'] = $petition->time_from;
                $dataUpdate['checkout'] =$petition->time_to;
                $dataUpdate['petition_type'] = 4;

                $this->timeKeepingService->update($dataUpdate);
            }
            if ($petition->petition_type != 4 && $request->petition_status == 2) {
                $dataUpdate = [];
                $dataUpdate['user_id'] = $petition->user_id;
                $dataUpdate['date'] = $petition->date_from;
                $dataUpdate['date_to'] = $petition->date_to;
                $dataUpdate['reason'] = $petition->petition_reason;
                $dataUpdate['time_from'] = $petition->time_from;
                $dataUpdate['time_to'] =$petition->time_to;
                $dataUpdate['petition_type'] =$petition->petition_type;
                $dataUpdate['type_leave'] =$petition->type_leave;
                $dataUpdate['type_paid'] =$petition->type_paid;
                $this->timeKeepingService->update($dataUpdate);
            }
            $users = User::all();
            return redirect()->route('petitions.index', compact('users'));

        }
        if($petitionRead){
            $petition -> update([
                'petition_status' => $petitionStatus,
            ]);
            $users = User::all();
            return redirect()->route('petitions.index', compact('users'));
        }

        if($petitionRead && $petition->status == 2){
            $petition -> update([
                $petition->readed = 1,
            ]);
            $users = User::all();
            return redirect()->route('petitions.approved', compact('users'));

        }else if($petitionRead && $petition->status == 0){
            $petition -> update([
                $petition->readed = 1,
            ]);
            $users = User::all();
            return redirect()->route('petitions.unapproved', compact('users'));

        }  

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Petition  $petition
     * @return \Illuminate\Http\Response
     */
    public function destroy(Petition $petition)
    {
        $petition->delete();
        $users = User::all();

        return redirect()->route('petitions.index', compact('users'))
                        ->with('success','Petition deleted successfully');
    }

    public function petition(Request $request)
    {
        $data = $request->all();

        $result = $this->timeKeepingService->petition($data);

        return [
            'code' => 200,
        ];

    }

    /**
     * @param Request $request
     */
    public function create_petition_time_keeping(Request $request) {
        $request->validate([
            'user_id' => 'required',
            'user_fullname' => 'required|string',
        ]);

        $isCreated = $this->petitionService->createPetition($request->all());

        if ($isCreated) {
            return [
                'code' => 200
            ];
        }
        return [
            'code' => 400
        ];
    }
}
