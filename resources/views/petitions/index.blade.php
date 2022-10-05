@extends('layouts.menu')

@section('content')

<style>
    table {
        background: #fff;
        border: 1px solid #999999;
    }

    table thead tr th {
        padding: 10px;
        border: 1px solid #9b9b9b;
        color: #000;
    }

    table.table-striped tbody tr:nth-of-type(odd) {
        background: #f9f9f9;
    }


    .text-left {
        text-align: left!important;
    }

    table tr td {
        padding: 0px 0px;
        border: 1px solid #999999;
    }

    table.result-point tr td .fa {
        font-size: 20px;
        position: absolute;
        right: 20px;
    }

    table tr td {
        padding: 10px 10px;
        border: 1px solid #999999;
    }
</style>


    <div id="layoutSidenav_content" style=" margin-top:-20px;">
        <main>
<style type="text/css">
#popup-giua-man-hinh .headerContainer,#popup-giua-man-hinh .bodyContainer,#popup-giua-man-hinh .footerContainer{
    max-width:960px;margin:0 auto;background:#FFF}
#popup-giua-man-hinh .padding{padding:0px}
#popup-giua-man-hinh .bodyContainer{min-height:500px}
#popup-giua-man-hinh .popUpBannerBox{position:fixed;background:rgba(0,0,0,0.9);
    width:100%;height:100%;top:0;left:0;color:#FFF;z-index:999999;display:none}
#popup-giua-man-hinh .popUpBannerInner{max-width:300px;margin:0 auto}
#popup-giua-man-hinh .popUpBannerContent{position:fixed;top:20%; left: 40%;}
#popup-giua-man-hinh .closeButton{color:red;text-decoration:none;font-size:18px}
#popup-giua-man-hinh a.closeButton{float:right}

</style>
<?php if(count($petitions2)>0 || count($petitions0)>0 ){ ?>
    <div id="popup-giua-man-hinh">
        <div class="popUpBannerBox">
            <div class="popUpBannerInner">
                <div class="popUpBannerContent">
                    <p><a href="#" class="closeButton">Đóng</a></p>
<!-- CODE HIỂN THỊ QUẢNG CÁO -->    
                    <div class="card bg-success text-white" style="width:400px; height:200px;">
                        <div class="card-body">
                            <b id="tc"> YÊU CẦU</b>
                            </br></br>
                            <?php if(count($petitions2) >0){ ?>
                                <nav>
                                    <i>
                                        Bạn có <b style="color:red">{{count($petitions2)}}</b> yêu cầu đã được duyệt.
                                    </i>
                                    <a class="small text-white"  href="{{ url('/approved') }}">Xem chi tiết</a>
                                </nav>
                            <?php }?>
                            </br>
                            <?php if(count($petitions0) >0){ ?>
                                <nav>
                                    <i>
                                        Bạn có <b style="color:red">{{count($petitions0)}}</b> yêu cầu đã bị từ chối.
                                    </i>
                                    <a class="small text-white"  href="{{ url('/unapproved') }}">Xem chi tiết</a>
                                </nav>
                            <?php }?>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white " href="{{ url('/home') }}">Trang chủ</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
<!-- END HIỂN THỊ QUẢNG CÁO -->
            </div>
        </div>
    </div>
    <script type="text/javascript">
    function showPopUpBanner() {
    $('.popUpBannerBox').fadeIn("2000");
    }
    setTimeout(showPopUpBanner, 0); //thời gian popup bắt đầu hiển thị

    $('.popUpBannerBox').click(function(e) {
    if ( !$(e.target).is('.popUpBannerContent, .popUpBannerContent *' ) ) {
    $('.popUpBannerBox').fadeOut("2000");
    return false;
    }
    });
    $('.closeButton').click(function() {
    $('.popUpBannerBox').fadeOut("2000");
    return false;
    });
    </script>
</div>
<?php }?>
            <form>
                <div class="container-fluid px-4" >
                    <br>
                    <form>
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item ">
                                <a class="nav-link" href="{{ url('/petitions') }}" style="background-color: #408080; color:#fff">
                                    Yêu cầu cần duyệt </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{url('/approved')}}">
                                    Đã duyệt </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/unapproved') }}" >
                                    Từ chối </a>
                            </li>
                        </ul>
                        <ul class="navbar-nav" style="float:right; margin: -50px 0px 0px 0px;">
                            <li class="nav-item dropdown">
                                    <button class="btn btn-success dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown" data-bs-auto-close="true">Tạo yêu cầu</button>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                    <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#exampleModal_goLate">Đi muộn/về sớm</a></li>
                                    <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#exampleModal_goOut">Đăng ký ra ngoài</a></li>
                                    <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#exampleModal_OTWar">Đăng ký làm nỗ lực</a></li>
                                    <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#exampleModal_warrior">Đăng ký Warrior</a></li>
                                    <li class="dropdown-submenu">
                                        <a class="dropdown-item dropdown-toggle" href="#" data-bs-auto-close="true">Đăng ký làm công</a>
                                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#exampleModal_OTAM">ĐKLC nửa ngày(AM)</a></li>
                                            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#exampleModal_OTPM">ĐKLC nửa ngày(PM)</a></li>
                                            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#exampleModal_OT">ĐKLC một ngày</a></li>
                                            <li><a class="dropdown-item" data-bs-toggle="modal"  data-bs-target="#exampleModal_multiOT">ĐKLC nhiều ngày</a></li>
                                        </ul>
                                    </li>
                                    <li class="dropdown-submenu">
                                        <a class="dropdown-item dropdown-toggle" href="#" data-bs-auto-close="true">Nghỉ phép</a>
                                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#exampleModal_inDayAM">Nửa ngày(AM)</a></li>
                                            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#exampleModal_inDayPM">Nửa ngày(PM)</a></li>
                                            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#exampleModal_inDay">Một ngày</a></li>
                                            <li><a class="dropdown-item" data-bs-toggle="modal"  data-bs-target="#exampleModal_multiDay">Nhiều ngày</a></li>
                                        </ul>
                                    </li>
                                        <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#exampleModal_quit">Nghỉ việc</a></li>
                                </ul>
                            </li>
                        </ul>
                           
                        <script >
                            $('.dropdown-menu a.dropdown-toggle').on('click', function(e) {
                            if (!$(this).next().hasClass('show')) {
                                $(this).parents('.dropdown-menu').first().find('.show').removeClass('show');
                            }
                            var $subMenu = $(this).next('.dropdown-menu');
                            $subMenu.toggleClass('show');


                            $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function(e) {
                                $('.dropdown-submenu .show').removeClass('show');
                            });

                            return false;
                        });
                        </script>

                        <div class="tab-content" id="myTabContent">
                            <!-- Start Yeu Cau Can Duyet -->
                            <div class="tab-pane fade show active" id="content-unapproved" role="tabpanel" aria-labelledby="tab-unapproved">
                                <table class="table-striped table-responsive table-hover result-point">
                                    <thead class="point-table-head">
                                    <tr style="text-align: center;">
                                        <th style="width:50px">STT</th>
                                        <th width=15%>Người yêu cầu</th>
                                        <th width=12%>Loại yêu cầu</th>
                                        <th width=26%>Thông tin yêu cầu</th>
                                        <th width=20%>Lý do</th>
                                        <th width=12%>Ngày gửi</th>
                                        <th width=14%>Thao tác</th>
                                    </tr>
                                    </thead>
                                    <?php $i=0;?>
                                    @foreach ($petitions as $petition )
                                        <?php if( $petition->petition_status == 1) { ?>
                                        <?php 
                                            $date = date("m-Y", time());
                                            $date_from = date("m-Y", strtotime($petition->date_from));
                                            if( Auth::user()->permission == 0 && Auth::user()->fullname == $petition->user_fullname && $date == $date_from ) { 
                                        ?>
                                        <tbody>
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $petition->user_fullname }}</td>
                                            <td>
                                                <?php
                                                $type = $petition->petition_type;
                                                $leave = $petition->type_leave;
                                                if($type == 1){
                                                    echo "Đi muộn về sớm";
                                                }
                                                if($type == 2){
                                                    if($leave == 1){
                                                        echo "Nghỉ phép nửa ngày(sáng)";
                                                    }else if($leave == 2){
                                                        echo "Nghỉ phép nửa ngày(chiều)";
                                                    } else if($leave == 3) {
                                                        echo "Nghỉ phép một ngày";
                                                    } else {
                                                        echo "Nghỉ phép nhiều ngày";
                                                    }
                                                }
                                                if($type == 3){
                                                    echo "Nghỉ việc";
                                                }
                                                if($type == 4){
                                                    echo "Thay đổi giờ chấm công";
                                                }
                                                if($type == 5){
                                                    echo "Đăng ký làm thêm";
                                                }
                                                if($type == 6){
                                                    echo "Đăng ký làm nỗ lực";
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $type = $petition->petition_type;
                                                $approved = $petition->type_approved;
                                                $leave = $petition->type_leave;
                                                $time_from_old = $petition->time_from_old? date("H:i", strtotime($petition->time_from_old)): ' -:- ';
                                                $time_from = $petition->time_from? date("H:i", strtotime($petition->time_from)): ' -:- ';
                                                $date_from = date("d-m-Y", strtotime($petition->date_from));
                                                $time_to_old = $petition->time_to_old? date("H:i", strtotime($petition->time_to_old)): ' -:- ';
                                                $time_to = $petition->time_to? date("H:i", strtotime($petition->time_to)): ' -:- ';
                                                $date_to = date("d-m-Y", strtotime($petition->date_to));
                                                if($type == 4 ){
                                                    if($time_from_old == $time_from){
                                                        echo "Ngày <b>".$date_from."</b> từ ".$time_from_old."-><b>".$time_to_old."</b> thành ".$time_from." -><b>".$time_to."</b>.";
                                                    } else if($time_to_old == $time_to){
                                                        echo "Ngày <b>".$date_from."</b> từ <b>".$time_from_old."</b>->".$time_to_old." thành <b>".$time_from."</b> ->".$time_to.".";
                                                    }else {
                                                        echo "Ngày <b>".$date_from."</b> từ <b>".$time_from_old."</b>-><b>".$time_to_old."</b> thành <b>".$time_from."</b>-><b>".$time_to."</b>.";
                                                    }
                                                } else if($type == 1){
                                                    echo "Ngày <b>".$date_from."</b> từ <b>".$time_from."</b> đến <b>".$time_to."</b>.";
                                                } else if($type == 2){
                                                    if($leave == 1){
                                                        echo "Ngày <b>".$date_from."</b>.";
                                                    } else if($leave == 2){
                                                        echo "Ngày <b>".$date_from."</b>.";
                                                    } else if($leave == 3){
                                                        echo "Ngày <b>".$date_from."</b>.";
                                                    } else if($leave == 4){
                                                        echo "Ngày <b>".$date_from."</b> đến hết ngày <b>".$date_to."</b>.";
                                                    }
                                                } else if($type == 3){
                                                    echo "Ngày bắt đầu nghỉ việc <b>".$date_from."</b>.";
                                                } else if($type == 5){
                                                    echo "Đăng ký làm ngày <b>".$date_from."</b>.";
                                                } else if($type == 6){
                                                    echo "Đăng ký làm nỗ lực ngày <b>".$date_from."</b>.";
                                                }
                                                ?>
                                            </td>
                                            <td>{{ $petition->petition_reason }}</td>
                                            <td>
                                                <?php
                                                $date_created_at_d = date("d-m-Y", strtotime($petition->created_at ));
                                                $date_created_at_t = date("H:i", strtotime($petition->created_at ));
                                                echo  $date_created_at_t." ngày ".$date_created_at_d;
                                                ?>
                                            </td>
                                            <td style="text-align: center;">
                                                <form action="{{ route('petitions.destroy',$petition->id) }}" method="POST">
                                                </form>
                                                <form action="{{ route('petitions.destroy',$petition->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Bạn có chắc chắn xoá yêu cầu này?');"
                                                            class="btn btn-danger" style="font-size:12px;">Xoá</button>
                                            </td>
                                        </tr>
                                        </tbody>
                                        <?php } ?>
                                        <?php 
                                            $date = date("m-Y", time());
                                            $date_from = date("m-Y", strtotime($petition->date_from));
                                            //dd( $date_from);
                                            if( (Auth::user()->permission == 1 || Auth::user()->permission == 2 || Auth::user()->permission == 3) && $date == $date_from){ ?>
                                        <tbody style="font-size:16px;">
                                        <tr>
                                            <td style="text-align: center;">{{ ++$i }}</td>
                                            <td>
                                                @foreach($users as $user)
                                                    <?php if($petition->user_id == $user->id){
                                                        $petition->user_fullname = $user->fullname;
                                                        echo $user->fullname;
                                                    }
                                                    ?>
                                                @endforeach
                                            </td>
                                            <td>
                                                <?php
                                                $type = $petition->petition_type;
                                                $leave = $petition->type_leave;
                                                if($type == 1){
                                                    echo "Đi muộn về sớm";
                                                }
                                                if($type == 2){
                                                    if($leave == 1){
                                                        echo "Nghỉ phép nửa ngày(sáng)";
                                                    }else if($leave == 2){
                                                        echo "Nghỉ phép nửa ngày(chiều)";
                                                    } else if($leave == 3) {
                                                        echo "Nghỉ phép một ngày";
                                                    } else {
                                                        echo "Nghỉ phép nhiều ngày";
                                                    }
                                                }
                                                if($type == 3){
                                                    echo "Nghỉ việc";
                                                }
                                                if($type == 4){
                                                    echo "Thay đổi giờ chấm công";
                                                }
                                                if($type == 5){
                                                    echo "Đăng ký làm thêm";
                                                }
                                                if($type == 6){
                                                    echo "Đăng ký làm nỗ lực";
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $type = $petition->petition_type;
                                                $leave = $petition->type_leave;
                                                $time_from_old = $petition->time_from_old? date("H:i", strtotime($petition->time_from_old)): ' -:- ';
                                                $time_from = $petition->time_from? date("H:i", strtotime($petition->time_from)): ' -:- ';
                                                $date_from = date("d-m-Y", strtotime($petition->date_from));
                                                $time_to_old = $petition->time_to_old? date("H:i", strtotime($petition->time_to_old)): ' -:- ';
                                                $time_to = $petition->time_to? date("H:i", strtotime($petition->time_to)): ' -:- ';
                                                $date_to = date("d-m-Y", strtotime($petition->date_to));
                                                if($type == 4 ){
                                                    if($time_from_old == $time_from){
                                                        echo "Ngày <b>".$date_from."</b> từ ".$time_from_old."-><b>".$time_to_old."</b> thành ".$time_from." -><b>".$time_to."</b>.";
                                                    } else if($time_to_old == $time_to){
                                                        echo "Ngày <b>".$date_from."</b> từ <b>".$time_from_old."</b>->".$time_to_old." thành <b>".$time_from."</b> ->".$time_to.".";
                                                    }else {
                                                        echo "Ngày <b>".$date_from."</b> từ <b>".$time_from_old."</b>-><b>".$time_to_old."</b> thành <b>".$time_from."</b>-><b>".$time_to."</b>.";
                                                    }
                                                } else if($type == 1){
                                                    echo "Ngày <b>".$date_from."</b> từ <b>".$time_from."</b> đến <b>".$time_to."</b>.";
                                                } else if($type == 2){
                                                    if($leave == 1){
                                                        echo "Ngày <b>".$date_from."</b>.";
                                                    } else if($leave == 2){
                                                        echo "Ngày <b>".$date_from."</b>.";
                                                    } else if($leave == 3){
                                                        echo "Ngày <b>".$date_from."</b>.";
                                                    } else if($leave == 4){
                                                        echo "Ngày <b>".$date_from."</b> đến hết ngày <b>".$date_to."</b>.";
                                                    }
                                                } else if($type == 3){
                                                    echo "Ngày bắt đầu nghỉ việc <b>".$date_from."</b>.";
                                                } else if($type == 5){
                                                    echo "Đăng ký làm ngày <b>".$date_from."</b>.";
                                                } else if($type == 6){
                                                    echo "Đăng ký làm nỗ lực ngày <b>".$date_from."</b>.";
                                                }
                                                ?>
                                            </td>

                                            <td>{{ $petition->petition_reason }}</td>
                                            <td style="text-align: center;">
                                                <?php
                                                $date_created_at_d = date("d-m-Y", strtotime($petition->created_at ));
                                                $date_created_at_t = date("H:i", strtotime($petition->created_at ));
                                                echo  $date_created_at_t." ngày ".$date_created_at_d;
                                                ?>
                                            </td>
                                            <td style="text-align: center;">
                                                <nav class="navbar navbar-expand-lg navbar-light bg-light" style="font-size: 20px;">
                                                        <ul class="navbar-nav mr-auto" style="font-size:16px;" >
                                                            &emsp;&nbsp;
                                                            <li class="nav-item">
                                                                <form action="{{ route('petitions.destroy',$petition->id) }}" method="POST"></form>
                                                                <form method="POST" action="{{ route('petitions.update', $petition->id) }}">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <input id="petition_status" type="text" name="petition_status" value="2" hidden>
                                                                    <button type="submit" class="btn btn-success" style="font-size:12px;">Duyệt</button>
                                                                </form>
                                                            </li> &ensp;
                                                            <li class="nav-item">
                                                                <form method="POST" action="{{ route('petitions.update', $petition->id) }}">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <input id="petition_status" type="text" name="petition_status" value="0" hidden>
                                                                    <button type="submit" class="btn btn-warning" style="font-size:12px;">Từ chối</button>
                                                                </form>
                                                            </li> &ensp;
                                                            <li class="nav-item">
                                                                <form action="{{ route('petitions.destroy',$petition->id) }}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" onclick="return confirm('Bạn có chắc chắn xoá yêu cầu này?');"
                                                                            class="btn btn-danger" style="font-size:12px;">Xoá</button>
                                                                </form>
                                                            </li>
                                                        </ul>
                                                </nav>
                                            </td>
                                        </tr>
                                        </tbody>
                                        <?php } ?>
                                        <?php } ?>
                                    @endforeach
                                </table>
                            </div>
                            <!--End Yeu Cau Can Duyet-->


                            <!-- Start Duyet -->
                            <div class="tab-pane fade" id="content-approved" role="tabpanel" aria-labelledby="tab-approved">
                                <table class="table-striped table-responsive table-hover result-point">
                                    <thead class="point-table-head">
                                    <tr style="text-align: center;">
                                        <th style="width:50px">STT</th>
                                       
                                        <th width=15%>Người yêu cầu</th>
                                        <th width=15%>Loại yêu cầu</th>
                                        <th width=25%>Thông tin yêu cầu</th>
                                        <th width=30%>Lý do</th>
                                        <th width=12%>Ngày gửi</th>
                                        <?php if(Auth::user()->permission == 1 || Auth::user()->permission == 2 || Auth::user()->permission == 3){ ?>
                                            <th width=8% >Thao tác</th>
                                        <?php } ?>
                                    </tr>
                                    </thead>
                                    <?php $i=0;?>
                                    @foreach ($petitions as $petition)
                                        <?php if( $petition->petition_status == 2) { ?>
                                        <?php if( Auth::user()->permission == 0 && Auth::user()->fullname == $petition->user_fullname) { ?>
                                        <tbody >
                                        <tr>
                                            <td style="text-align: center;">{{ ++$i }}</td>
                                            
                                            <td>
                                                @foreach($users as $user)
                                                    <?php if($petition->user_id == $user->id){
                                                        $petition->user_fullname = $user->fullname;
                                                        echo $user->fullname;
                                                    }
                                                    ?>
                                                @endforeach
                                            </td>
                                            <td>
                                                <?php
                                                $type = $petition->petition_type;
                                                $leave = $petition->type_leave;
                                                if($type == 1){
                                                    echo "Đi muộn về sớm";
                                                }
                                                if($type == 2){
                                                    if($leave == 1){
                                                        echo "Nghỉ phép nửa ngày(sáng)";
                                                    }else if($leave == 2){
                                                        echo "Nghỉ phép nửa ngày(chiều)";
                                                    } else if($leave == 3) {
                                                        echo "Nghỉ phép một ngày";
                                                    } else {
                                                        echo "Nghỉ phép nhiều ngày";
                                                    }
                                                }
                                                if($type == 3){
                                                    echo "Nghỉ việc";
                                                }
                                                if($type == 4){
                                                    echo "Thay đổi giờ chấm công";
                                                }
                                                if($type == 5){
                                                    echo "Đăng ký làm thêm";
                                                }
                                                if($type == 6){
                                                    echo "Đăng ký làm nỗ lực";
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $type = $petition->petition_type;
                                                $leave = $petition->type_leave;
                                                $time_from_old = $petition->time_from_old? date("H:i", strtotime($petition->time_from_old)): ' -:- ';
                                                $time_from = $petition->time_from? date("H:i", strtotime($petition->time_from)): ' -:- ';
                                                $date_from = date("d-m-Y", strtotime($petition->date_from));
                                                $time_to_old = $petition->time_to_old? date("H:i", strtotime($petition->time_to_old)): ' -:- ';
                                                $time_to = $petition->time_to? date("H:i", strtotime($petition->time_to)): ' -:- ';
                                                $date_to = date("d-m-Y", strtotime($petition->date_to));
                                                if($type == 4 ){
                                                    if($time_from_old == $time_from){
                                                        echo "Ngày <b>".$date_from."</b> từ ".$time_from_old."-><b>".$time_to_old."</b> thành ".$time_from." -><b>".$time_to."</b>.";
                                                    } else if($time_to_old == $time_to){
                                                        echo "Ngày <b>".$date_from."</b> từ <b>".$time_from_old."</b>->".$time_to_old." thành <b>".$time_from."</b> ->".$time_to.".";
                                                    }else {
                                                        echo "Ngày <b>".$date_from."</b> từ <b>".$time_from_old."</b>-><b>".$time_to_old."</b> thành <b>".$time_from."</b>-><b>".$time_to."</b>.";
                                                    }
                                                } else if($type == 1){
                                                    echo "Ngày <b>".$date_from."</b> từ <b>".$time_from."</b> đến <b>".$time_to."</b>.";
                                                } else if($type == 2){
                                                    if($leave == 1){
                                                        echo "Ngày <b>".$date_from."</b>.";
                                                    } else if($leave == 2){
                                                        echo "Ngày <b>".$date_from."</b>.";
                                                    } else if($leave == 3){
                                                        echo "Ngày <b>".$date_from."</b>.";
                                                    } else if($leave == 4){
                                                        echo "Ngày <b>".$date_from."</b> đến hết ngày <b>".$date_to."</b>.";
                                                    }
                                                } else if($type == 3){
                                                    echo "Ngày bắt đầu nghỉ việc <b>".$date_from."</b>.";
                                                } else if($type == 5){
                                                    echo "Đăng ký làm ngày <b>".$date_from."</b>.";
                                                } else if($type == 6){
                                                    echo "Đăng ký làm nỗ lực ngày <b>".$date_from."</b>.";
                                                }
                                                ?>
                                            </td>
                                            <td>{{ $petition->petition_reason }}</td>
                                            <td style="text-align: center;">
                                                <?php
                                                $date_created_at_d = date("d-m-Y", strtotime($petition->created_at ));
                                                $date_created_at_t = date("H:i", strtotime($petition->created_at ));
                                                echo  $date_created_at_t." ngày ".$date_created_at_d;
                                                ?>
                                            </td>
                                            <td style="text-align: center;">
                                                <form action="{{ route('petitions.destroy',$petition->id) }}" method="POST">
                                                </form>
                                                <form action="{{ route('petitions.destroy',$petition->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Bạn có chắc chắn xoá yêu cầu này?');"
                                                            class="btn btn-danger" style="font-size:12px;">Xoá</button>
                                                </form>
                                            </td>
                                        </tr>
                                        </tbody>
                                        <?php } ?>
                                        <?php if( Auth::user()->permission == 1 || Auth::user()->permission == 2 || Auth::user()->permission == 3 ){ ?>
                                        <tbody>
                                        <tr>
                                            <td style="text-align: center;">{{ ++$i }}</td>
                                            
                                            <td>
                                                @foreach($users as $user)
                                                    <?php if($petition->user_id == $user->id){
                                                        $petition->user_fullname = $user->fullname;
                                                        echo $user->fullname;
                                                    }
                                                    ?>
                                                @endforeach
                                            </td>
                                            <td>
                                                <?php
                                                $type = $petition->petition_type;
                                                $leave = $petition->type_leave;
                                                if($type == 1){
                                                    echo "Đi muộn về sớm";
                                                }
                                                if($type == 2){
                                                    if($leave == 1){
                                                        echo "Nghỉ phép nửa ngày(sáng)";
                                                    }else if($leave == 2){
                                                        echo "Nghỉ phép nửa ngày(chiều)";
                                                    } else if($leave == 3) {
                                                        echo "Nghỉ phép một ngày";
                                                    } else {
                                                        echo "Nghỉ phép nhiều ngày";
                                                    }
                                                }
                                                if($type == 3){
                                                    echo "Nghỉ việc";
                                                }
                                                if($type == 4){
                                                    echo "Thay đổi giờ chấm công";
                                                }
                                                if($type == 5){
                                                    echo "Đăng ký làm thêm";
                                                }
                                                if($type == 6){
                                                    echo "Đăng ký làm nỗ lực";
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $type = $petition->petition_type;
                                                $leave = $petition->type_leave;
                                                $time_from_old = $petition->time_from_old? date("H:i", strtotime($petition->time_from_old)): ' -:- ';
                                                $time_from = $petition->time_from? date("H:i", strtotime($petition->time_from)): ' -:- ';
                                                $date_from = date("d-m-Y", strtotime($petition->date_from));
                                                $time_to_old = $petition->time_to_old? date("H:i", strtotime($petition->time_to_old)): ' -:- ';
                                                $time_to = $petition->time_to? date("H:i", strtotime($petition->time_to)): ' -:- ';
                                                $date_to = date("d-m-Y", strtotime($petition->date_to));
                                                if($type == 4 ){
                                                    if($time_from_old == $time_from){
                                                        echo "Ngày <b>".$date_from."</b> từ ".$time_from_old."-><b>".$time_to_old."</b> thành ".$time_from." -><b>".$time_to."</b>.";
                                                    } else if($time_to_old == $time_to){
                                                        echo "Ngày <b>".$date_from."</b> từ <b>".$time_from_old."</b>->".$time_to_old." thành <b>".$time_from."</b> ->".$time_to.".";
                                                    }else {
                                                        echo "Ngày <b>".$date_from."</b> từ <b>".$time_from_old."</b>-><b>".$time_to_old."</b> thành <b>".$time_from."</b>-><b>".$time_to."</b>.";
                                                    }
                                                } else if($type == 1){
                                                    echo "Ngày <b>".$date_from."</b> từ <b>".$time_from."</b> đến <b>".$time_to."</b>.";
                                                } else if($type == 2){
                                                    if($leave == 1){
                                                        echo "Ngày <b>".$date_from."</b>.";
                                                    } else if($leave == 2){
                                                        echo "Ngày <b>".$date_from."</b>.";
                                                    } else if($leave == 3){
                                                        echo "Ngày <b>".$date_from."</b>.";
                                                    } else if($leave == 4){
                                                        echo "Ngày <b>".$date_from."</b> đến hết ngày <b>".$date_to."</b>.";
                                                    }
                                                } else if($type == 3){
                                                    echo "Ngày bắt đầu nghỉ việc <b>".$date_from."</b>.";
                                                } else if($type == 5){
                                                    echo "Đăng ký làm ngày <b>".$date_from."</b>.";
                                                } else if($type == 6){
                                                    echo "Đăng ký làm nỗ lực ngày <b>".$date_from."</b>.";
                                                }
                                                ?>
                                            </td>
                                            <td>{{ $petition->petition_reason }}</td>
                                            <td style="text-align: center;">
                                                <?php
                                                $date_created_at_d = date("d-m-Y", strtotime($petition->created_at ));
                                                $date_created_at_t = date("H:i", strtotime($petition->created_at ));
                                                echo  $date_created_at_t." ngày ".$date_created_at_d;
                                                ?>
                                            </td>
                                            <td style="text-align: center;">
                                                <form action="{{ route('petitions.destroy',$petition->id) }}" method="POST">
                                                </form>
                                                <form action="{{ route('petitions.destroy',$petition->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Bạn có chắc chắn xoá yêu cầu này?');"
                                                            class="btn btn-danger" style="font-size:12px;">Xoá</button>
                                                </form>
                                            </td>
                                        </tr>
                                        </tbody>
                                        <?php } ?>
                                        <?php } ?>
                                    @endforeach
                                </table>
                            </div>
                            <!-- End Da Duyet-->


                            <!-- Start Tu Choi -->
                            <div class="tab-pane fade" id="content-refuse" role="tabpanel" aria-labelledby="tab-refuse">
                                <table class="table-striped table-responsive table-hover result-point">
                                    <thead class="point-table-head">
                                    <tr style="text-align: center;">
                                        <th style="width:50px">STT</th>
                                        
                                        <th width=15%>Người yêu cầu</th>
                                        <th width=15%>Loại yêu cầu</th>
                                        <th width=25%>Thông tin yêu cầu</th>
                                        <th width=30%>Lý do</th>
                                        <th width=12%>Ngày gửi</th>
                                        <?php if(Auth::user()->permission == 1 || Auth::user()->permission == 2 || Auth::user()->permission == 3){ ?>
                                            <th width=8% >Thao tác</th>
                                        <?php } ?>
                                    </tr>
                                    </thead>
                                    <?php $i=0;?>
                                    @foreach ($petitions as $petition)
                                        <?php if( $petition->petition_status == 0) { ?>
                                        <?php if( Auth::user()->permission == 0 && Auth::user()->fullname == $petition->user_fullname) { ?>
                                        <tbody>
                                        <tr>
                                            <td style="text-align: center;">{{ ++$i }}</td>
                                           
                                            <td>
                                                @foreach($users as $user)
                                                    <?php if($petition->user_id == $user->id){
                                                        $petition->user_fullname = $user->fullname;
                                                        echo $user->fullname;
                                                    }
                                                    ?>
                                                @endforeach
                                            </td>
                                            <td>
                                                <?php
                                                $type = $petition->petition_type;
                                                $leave = $petition->type_leave;
                                                if($type == 1){
                                                    echo "Đi muộn về sớm";
                                                }
                                                if($type == 2){
                                                    if($leave == 1){
                                                        echo "Nghỉ phép nửa ngày(sáng)";
                                                    }else if($leave == 2){
                                                        echo "Nghỉ phép nửa ngày(chiều)";
                                                    } else if($leave == 3) {
                                                        echo "Nghỉ phép một ngày";
                                                    } else {
                                                        echo "Nghỉ phép nhiều ngày";
                                                    }
                                                }
                                                if($type == 3){
                                                    echo "Nghỉ việc";
                                                }
                                                if($type == 4){
                                                    echo "Thay đổi giờ chấm công";
                                                }
                                                if($type == 5){
                                                    echo "Đăng ký làm thêm";
                                                }
                                                if($type == 6){
                                                    echo "Đăng ký làm nỗ lực";
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $type = $petition->petition_type;
                                                $leave = $petition->type_leave;
                                                $time_from_old = $petition->time_from_old? date("H:i", strtotime($petition->time_from_old)): ' -:- ';
                                                $time_from = $petition->time_from? date("H:i", strtotime($petition->time_from)): ' -:- ';
                                                $date_from = date("d-m-Y", strtotime($petition->date_from));
                                                $time_to_old = $petition->time_to_old? date("H:i", strtotime($petition->time_to_old)): ' -:- ';
                                                $time_to = $petition->time_to? date("H:i", strtotime($petition->time_to)): ' -:- ';
                                                $date_to = date("d-m-Y", strtotime($petition->date_to));
                                                if($type == 4 ){
                                                    if($time_from_old == $time_from){
                                                        echo "Ngày <b>".$date_from."</b> từ ".$time_from_old."-><b>".$time_to_old."</b> thành ".$time_from." -><b>".$time_to."</b>.";
                                                    } else if($time_to_old == $time_to){
                                                        echo "Ngày <b>".$date_from."</b> từ <b>".$time_from_old."</b>->".$time_to_old." thành <b>".$time_from."</b> ->".$time_to.".";
                                                    }else {
                                                        echo "Ngày <b>".$date_from."</b> từ <b>".$time_from_old."</b>-><b>".$time_to_old."</b> thành <b>".$time_from."</b>-><b>".$time_to."</b>.";
                                                    }
                                                } else if($type == 1){
                                                    echo "Ngày <b>".$date_from."</b> từ <b>".$time_from."</b> đến <b>".$time_to."</b>.";
                                                } else if($type == 2){
                                                    if($leave == 1){
                                                        echo "Ngày <b>".$date_from."</b>.";
                                                    } else if($leave == 2){
                                                        echo "Ngày <b>".$date_from."</b>.";
                                                    } else if($leave == 3){
                                                        echo "Ngày <b>".$date_from."</b>.";
                                                    } else if($leave == 4){
                                                        echo "Ngày <b>".$date_from."</b> đến hết ngày <b>".$date_to."</b>.";
                                                    }
                                                } else if($type == 3){
                                                    echo "Ngày bắt đầu nghỉ việc <b>".$date_from."</b>.";
                                                } else if($type == 5){
                                                    echo "Đăng ký làm ngày <b>".$date_from."</b>.";
                                                } else if($type == 6){
                                                    echo "Đăng ký làm nỗ lực ngày <b>".$date_from."</b>.";
                                                }
                                                ?>
                                            </td>
                                            <td>{{ $petition->petition_reason }}</td>
                                            <td style="text-align: center;">
                                                <?php
                                                $date_created_at_d = date("d-m-Y", strtotime($petition->created_at ));
                                                $date_created_at_t = date("H:i", strtotime($petition->created_at ));
                                                echo  $date_created_at_t." ngày ".$date_created_at_d;
                                                ?>
                                            </td>
                                            <?php if(Auth::user()->permission == 1 || Auth::user()->permission == 2 || Auth::user()->permission == 3){ ?>
                                                <td style="text-align: center;">
                                                    <form action="{{ route('petitions.destroy',$petition->id) }}" method="POST">
                                                    </form>
                                                    <form action="{{ route('petitions.destroy',$petition->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" onclick="return confirm('Bạn có chắc chắn xoá yêu cầu này?');"
                                                                class="btn btn-danger" style="font-size:12px;">Xoá</button>
                                                    </form>
                                                </td>
                                            <?php } ?>
                                        </tr>
                                        </tbody>
                                        <?php } ?>
                                        <?php if(Auth::user()->permission == 1 || Auth::user()->permission == 2 || Auth::user()->permission == 3 ){ ?>
                                        <tbody>
                                        <tr>
                                            <td style="text-align: center;">{{ ++$i }}</td>
                                            
                                            <td>
                                                @foreach($users as $user)
                                                    <?php if($petition->user_id == $user->id){
                                                        $petition->user_fullname = $user->fullname;
                                                        echo $user->fullname;
                                                    }
                                                    ?>
                                                @endforeach
                                            </td>
                                            <td>
                                                <?php
                                                $type = $petition->petition_type;
                                                $leave = $petition->type_leave;
                                                if($type == 1){
                                                    echo "Đi muộn về sớm";
                                                }
                                                if($type == 2){
                                                    if($leave == 1){
                                                        echo "Nghỉ phép nửa ngày(sáng)";
                                                    }else if($leave == 2){
                                                        echo "Nghỉ phép nửa ngày(chiều)";
                                                    } else if($leave == 3) {
                                                        echo "Nghỉ phép một ngày";
                                                    } else {
                                                        echo "Nghỉ phép nhiều ngày";
                                                    }
                                                }
                                                if($type == 3){
                                                    echo "Nghỉ việc";
                                                }
                                                if($type == 4){
                                                    echo "Thay đổi giờ chấm công";
                                                }
                                                if($type == 5){
                                                    echo "Đăng ký làm thêm";
                                                }
                                                if($type == 6){
                                                    echo "Đăng ký làm nỗ lực";
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $type = $petition->petition_type;
                                                $leave = $petition->type_leave;
                                                $time_from_old = $petition->time_from_old? date("H:i", strtotime($petition->time_from_old)): ' -:- ';
                                                $time_from = $petition->time_from? date("H:i", strtotime($petition->time_from)): ' -:- ';
                                                $date_from = date("d-m-Y", strtotime($petition->date_from));
                                                $time_to_old = $petition->time_to_old? date("H:i", strtotime($petition->time_to_old)): ' -:- ';
                                                $time_to = $petition->time_to? date("H:i", strtotime($petition->time_to)): ' -:- ';
                                                $date_to = date("d-m-Y", strtotime($petition->date_to));
                                                if($type == 4 ){
                                                    if($time_from_old == $time_from){
                                                        echo "Ngày <b>".$date_from."</b> từ ".$time_from_old."-><b>".$time_to_old."</b> thành ".$time_from." -><b>".$time_to."</b>.";
                                                    } else if($time_to_old == $time_to){
                                                        echo "Ngày <b>".$date_from."</b> từ <b>".$time_from_old."</b>->".$time_to_old." thành <b>".$time_from."</b> ->".$time_to.".";
                                                    }else {
                                                        echo "Ngày <b>".$date_from."</b> từ <b>".$time_from_old."</b>-><b>".$time_to_old."</b> thành <b>".$time_from."</b>-><b>".$time_to."</b>.";
                                                    }
                                                } else if($type == 1){
                                                    echo "Ngày <b>".$date_from."</b> từ <b>".$time_from."</b> đến <b>".$time_to."</b>.";
                                                } else if($type == 2){
                                                    if($leave == 1){
                                                        echo "Ngày <b>".$date_from."</b>.";
                                                    } else if($leave == 2){
                                                        echo "Ngày <b>".$date_from."</b>.";
                                                    } else if($leave == 3){
                                                        echo "Ngày <b>".$date_from."</b>.";
                                                    } else if($leave == 4){
                                                        echo "Ngày <b>".$date_from."</b> đến hết ngày <b>".$date_to."</b>.";
                                                    }
                                                } else if($type == 3){
                                                    echo "Ngày bắt đầu nghỉ việc <b>".$date_from."</b>.";
                                                } else if($type == 5){
                                                    echo "Đăng ký làm ngày <b>".$date_from."</b>.";
                                                } else if($type == 6){
                                                    echo "Đăng ký làm nỗ lực ngày <b>".$date_from."</b>.";
                                                }
                                                ?>
                                            </td>
                                            <td>{{ $petition->petition_reason }}</td>
                                            <td style="text-align: center;">
                                                <?php
                                                $date_created_at_d = date("d-m-Y", strtotime($petition->created_at ));
                                                $date_created_at_t = date("H:i", strtotime($petition->created_at ));
                                                echo $date_created_at_t." ngày ".$date_created_at_d;
                                                ?>
                                            </td>
                                            <td style="text-align: center;">
                                                <form action="{{ route('petitions.destroy',$petition->id) }}" method="POST">
                                                </form>
                                                <form action="{{ route('petitions.destroy',$petition->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Bạn có chắc chắn xoá yêu cầu này?');"
                                                            class="btn btn-danger" style="font-size:12px;">Xoá</button>
                                                </form>
                                            </td>
                                        </tr>
                                        </tbody>
                                        <?php } ?>
                                        <?php } ?>
                                    @endforeach
                                </table>
                            </div>
                            <!-- End Tu Choi -->
                        </div>
                    </form>
                </div>
            </form>
        </main>
    </div>
@endsection
