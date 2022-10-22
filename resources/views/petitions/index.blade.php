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
            <form>
                <div class="container-fluid px-4" >
                    <br>
                    <form>
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item ">
                                <a class="nav-link active " id="tab-unapproved" data-toggle="tab"
                                   href="#content-unapproved" role="tab" aria-controls="content-unapproved" aria-selected="false">
                                    Yêu cầu cần duyệt </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tab-approved" data-toggle="tab"
                                   href="#content-approved" role="tab" aria-controls="content-approved" aria-selected="false">
                                    Đã duyệt </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " id="tab-refuse" data-toggle="tab"
                                   href="#content-refuse" role="tab" aria-controls="content-refuse" aria-selected="true">
                                    Từ chối </a>
                            </li>
                            <li style="margin-left: 20px;">
                                <input class ="form-control" id="myInput" type="text" placeholder="Search.."\
                                       style="width:100%; border-radius:0px 0px 0px 0px;">
                            </li>
                            <li style="margin-left: 20px;">
                                <input class ="form-control" id="myInput1" type="month" placeholder="Search..">
                            </li>
                        </ul>
                        <!--<script>
                           $(document).ready(function(){
                            $("#myInput1").on("change", function() {
                                let value = $(this).val().toLowerCase();
                                console.log(value)
                                $("#myTable tr").filter(function() {
                                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                                });
                            });
                            });
                        </script>-->
                        <script>
                            $(document).ready(function(){
                                $("#myInput1").on("change", function() {
                                    let value = $(this).val().toLowerCase();
                                    console.log(value)
                                    $("#myTable tr").filter(function() {
                                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                                    });
                                });
                                $("#myInput").on("keyup", function() {
                                    var value = $(this).val().toLowerCase();
                                    $("#myTable tr").filter(function() {
                                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                                    });
                                });
                            });
                        </script>
                        
                        <!--<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal_petition"
                                style="float:right; margin:-45px 0px 0px 0px;">Tạo yêu cầu</button>-->
                                <ul class="navbar-nav" style="float:right; margin:-50px 0px 0px 0px;">
                                    <li class="nav-item dropdown">
                                         <button class="btn btn-success dropdown-toggle" type="button"
                                         data-bs-toggle="dropdown" data-bs-auto-close="true">Tạo yêu cầu</button>
                                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#exampleModal_goLate">Đi muộn/về sớm</a></li>
                                            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#exampleModal_goOut">Ra ngoài</a></li>
                                            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#exampleModal_OT">Đăng ký làm công</a></li>
                                            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#exampleModal_OTWar">Đăng ký làm nỗ lực</a></li>
                                            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#exampleModal_warrior">Đăng ký Warrior</a></li>
                                            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#exampleModal_quit">Nghỉ việc</a></li>
                                            <li class="dropdown-submenu">
                                                <a class="dropdown-item dropdown-toggle" href="#" data-bs-auto-close="true">Nghỉ phép</a>
                                                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                                    <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#exampleModal_inDayAM">Nửa ngày(sáng)</a></li>
                                                    <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#exampleModal_inDayPM">Nửa ngày(chiều)</a></li>
                                                    <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#exampleModal_inDay">Một ngày</a></li>
                                                    <li><a class="dropdown-item" data-bs-toggle="modal"  data-bs-target="#exampleModal_multiDay">Nhiều ngày</a></li>
                                                </ul>
                                            </li>
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
                                        <th style="width:50px">ID</th>
                                        <th width=12%>Người yêu cầu</th>
                                        <th width=12%>Loại yêu cầu</th>
                                        <th width=25%>Thông tin yêu cầu</th>
                                        <th width=20%>Lý do</th>
                                        <th width=12%>Ngày gửi</th>
                                        <th width=14%>Thao tác</th>
                                    </tr>
                                    </thead>
                                    <?php $i=0;?>
                                    @foreach ($petitions as $petition )
                                        <?php if( $petition->petition_status == 1) { ?>
                                        <?php if( Auth::user()->permission == 0 && Auth::user()->fullname == $petition->user_fullname) { ?>
                                        <tbody id="myTable">
                                        <tr>
                                             <td hidden>{{ $petition->date_from }}</td>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $petition->type_approved }}</td>
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
                                                if($type == 9){
                                                    echo "Đăng ký ra ngoài";
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
                                                        echo "Ngày <b>".$date_from."</b> đến hết ngày <b>".$date_to."</b>.".$approved;
                                                    }
                                                } else if($type == 3){
                                                    echo "Ngày bắt đầu nghỉ việc <b>".$date_from."</b>.";
                                                } else if($type == 5){
                                                    echo "Đăng ký làm ngày <b>".$date_from."</b>.";
                                                } else if($type == 6){
                                                    echo "Đăng ký làm nỗ lực ngày <b>".$date_from."</b>.";
                                                }  else if($type == 9){
                                                    echo "Ngày <b>".$date_from."</b> từ <b>".$time_from."</b> đến <b>".$time_to."</b>.";
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
                                        <?php if( Auth::user()->permission == 1 || Auth::user()->permission == 2 || Auth::user()->permission == 3 ){ ?>
                                        <tbody id="myTable" style="font-size:16px;">
                                        <tr>
                                            <td hidden>{{ $petition->date_from }}</td>
                                            <td style="text-align: center;">{{ ++$i }}</td>
                                            <td style="text-align: center;">{{ $petition->id }}</td>
                                            <!--<td>{{ $petition->user_fullname }}</td>-->
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
                                                if($type == 9){
                                                    echo "Đăng ký ra ngoài";
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
                                                } else if($type == 9){
                                                    echo "Ngày <b>".$date_from."</b> từ <b>".$time_from."</b> đến <b>".$time_to."</b>.";
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
                                        <th style="width:50px">ID</th>
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
                                        <tbody id="myTable" >
                                        <tr>
                                            <td hidden>{{ $petition->date_from }}</td>
                                            <td style="text-align: center;">{{ ++$i }}</td>
                                            <td style="text-align: center;">{{ $petition->id }}</td>
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
                                                if($type == 9){
                                                    echo "Đăng ký ra ngoài";
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
                                                } else if($type == 9){
                                                    echo "Ngày <b>".$date_from."</b> từ <b>".$time_from."</b> đến <b>".$time_to."</b>.";
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
                                        <tbody id="myTable">
                                        <tr>
                                            <td hidden>{{ $petition->date_from }}</td>
                                            <td style="text-align: center;">{{ ++$i }}</td>
                                            <td style="text-align: center;">{{ $petition->id }}</td>
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
                                                if($type == 9){
                                                    echo "Đăng ký ra ngoài";
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
                                                } else if($type == 9){
                                                    echo "Ngày <b>".$date_from."</b> từ <b>".$time_from."</b> đến <b>".$time_to."</b>.";
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
                                        <th style="width:50px">ID</th>
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
                                        <tbody id="myTable">
                                        <tr>
                                            <td hidden>{{ $petition->date_from }}</td>
                                            <td style="text-align: center;">{{ ++$i }}</td>
                                            <td style="text-align: center;">{{ $petition->id }}</td>
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
                                                if($type == 9){
                                                    echo "Đăng ký ra ngoài";
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
                                                } else if($type == 9){
                                                    echo "Ngày <b>".$date_from."</b> từ <b>".$time_from."</b> đến <b>".$time_to."</b>.";
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
                                        <tbody id="myTable">
                                        <tr>
                                            <td hidden>{{ $petition->date_from }}</td>
                                            <td style="text-align: center;">{{ ++$i }}</td>
                                            <td style="text-align: center;">{{ $petition->id }}</td>
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
                                                if($type == 9){
                                                    echo "Đăng ký ra ngoài";
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
                                                } else if($type == 9){
                                                    echo "Ngày <b>".$date_from."</b> từ <b>".$time_from."</b> đến <b>".$time_to."</b>.";
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
