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
                        </ul>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal_petition"
                                style="float:right; margin:-45px 0px 0px 0px;">Tạo yêu cầu</button>
                        <div class="tab-content" id="myTabContent">
                            <!-- Start Yeu Cau Can Duyet -->
                            <div class="tab-pane fade show active" id="content-unapproved" role="tabpanel" aria-labelledby="tab-unapproved">
                                <table class="table-striped table-responsive table-hover result-point">
                                                <thead class="point-table-head">
                                                <tr style="text-align: center;">
                                                    <th style="width:50px">STT</th>
                                                    <th style="width:50px">ID</th>
                                                    <th width=15%>Người yêu cầu</th>
                                                    <th width=20%>Thông tin</th>
                                                    <th width=10%>Ngày giờ bắt đầu</th>
                                                    <th width=10%>Ngày giờ kết thúc</th>
                                                    <th width=20%>Lý do</th>
                                                    <th width=10%>Ngày gửi</th>
                                                    <th width=15%>Thao tác</th>
                                                </tr>
                                                </thead>
                                                <?php $i=0;?>
                                                @foreach ($petitions as $petition )
                                                    <?php if( $petition->petition_status == 1) { ?>
                                                    <?php if( Auth::user()->permission == 0 && Auth::user()->fullname == $petition->user_fullname) { ?>
                                                <tbody style="text-align: center;">
                                                    <tr>
                                                        <td>{{ ++$i }}</td>
                                                        <td>{{ $user->id }}</td>
                                                        <td>{{ $petition->user_fullname }}</td>
                                                        <td>
                                                            <?php
                                                            if($petition->petition_type == 1){
                                                                echo "Đi muộn về sớm";
                                                            }
                                                            if($petition->petition_type == 2){
                                                                if($petition->type_leave == "in_day"){
                                                                    echo "Nghỉ phép trong ngày";
                                                                }else{
                                                                    echo "Nghỉ phép nhiều ngày";
                                                                }
                                                            }
                                                            if($petition->petition_type == 3){
                                                                echo "Nghỉ việc";
                                                            }
                                                            if($petition->petition_type == 4){
                                                                echo "Thay đổi giờ chấm công";
                                                            }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            $check_date = date("d-m-Y", strtotime($petition->check_date));
                                                            $checkin = date("H:i", strtotime($petition->checkin));
                                                            if ($petition->petition_type == 3) {
                                                                echo $check_date;
                                                            } else{
                                                                echo $checkin." ngày ".$check_date;
                                                            }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            $check_date = date("d-m-Y", strtotime($petition->check_date));
                                                            $date_to = date("d-m-Y", strtotime($petition->date_to));
                                                            $checkout = date("H:i", strtotime($petition->checkout));
                                                            if ($petition->petition_type == 3) {
                                                                echo $check_date;
                                                            } else if($petition->date_to != null){
                                                                echo $checkout." ngày ".$date_to;
                                                            }
                                                            else{
                                                                echo $checkout." ngày ".$check_date;
                                                            }
                                                            ?>
                                                        </td>
                                                        <td>{{ $petition->petition_reason }}</td>
                                                        <td>
                                                            <?php
                                                            $date_created_at_d = date("d-m-Y", strtotime($petition->created_at ));
                                                            $date_created_at_t = date("H:i:s", strtotime($petition->created_at ));
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
                                                <?php if( Auth::user()->permission == 1 ){ ?>
                                                <tbody style="text-align: center;">
                                                <tr>
                                                    <td>{{ ++$i }}</td>
                                                    <td>{{ $petition->id }}</td>
                                                    <td>
                                                        @foreach($users as $user)
                                                            <?php if($petition->user_id == $user->id){
                                                                $petition->user_fullname == $user->fullname;
                                                                echo $petition->user_fullname;
                                                            }
                                                            ?>
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        <?php
                                                        if($petition->petition_type == 1){
                                                            echo "Đi muộn về sớm";
                                                        }
                                                        if($petition->petition_type == 2){
                                                            if($petition->type_leave == "in_day"){
                                                                echo "Nghỉ phép trong ngày";
                                                            }else{
                                                                echo "Nghỉ phép nhiều ngày";
                                                            }
                                                        }
                                                        if($petition->petition_type == 3){
                                                            echo "Nghỉ việc";
                                                        }
                                                        if($petition->petition_type == 4){
                                                            echo "Thay đổi giờ chấm công";
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $check_date = date("d-m-Y", strtotime($petition->date_from));
                                                        $checkin = $petition->time_from? date("H:i", strtotime($petition->time_from)): '';
                                                        if ($petition->petition_type == 3) {
                                                            echo $check_date;
                                                        } else{
                                                            echo $checkin." ngày ".$check_date;
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $check_date = date("d-m-Y", strtotime($petition->check_date));
                                                        $date_to = date("d-m-Y", strtotime($petition->date_to));
                                                        $checkout = $petition->time_to ? date("H:i", strtotime($petition->time_to)) : '';
                                                        if ($petition->petition_type == 3) {
                                                            echo $check_date;
                                                        } else if($petition->date_to != null){
                                                            echo $checkout." ngày ".$date_to;
                                                        }
                                                        else{
                                                            echo $checkout." ngày ".$check_date;
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>{{ $petition->petition_reason }}</td>
                                                    <td>
                                                        <?php
                                                        $date_created_at_d = date("d-m-Y", strtotime($petition->created_at ));
                                                        $date_created_at_t = date("H:i:s", strtotime($petition->created_at ));
                                                        echo  $date_created_at_t." ngày ".$date_created_at_d;
                                                        ?>
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <nav class="navbar navbar-expand-lg navbar-light bg-light" style="font-size: 20px;">
                                                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                                                <ul class="navbar-nav mr-auto" style="font-size:16px;" >
                                                                    &emsp;&nbsp;
                                                                    <li class="nav-item">
                                                                        <form action="{{ route('petitions.destroy',$petition->id) }}" method="POST">
                                                                        </form>
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
                                                            </div>
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
                                        <th width=20%>Thông tin</th>
                                        <th width=12%>Ngày giờ bắt đầu</th>
                                        <th width=12%>Ngày giờ kết thúc</th>
                                        <th width=20%>Lý do</th>
                                        <th width=12%>Ngày gửi</th>
                                        <th width=7%>Thao tác</th>
                                    </tr>
                                    </thead>
                                    <?php $i=0;?>
                                    @foreach ($petitions as $petition)
                                        <?php if( $petition->petition_status == 2) { ?>
                                        <?php if( Auth::user()->permission == 0 && Auth::user()->fullname == $petition->user_fullname) { ?>
                                            <tbody style="text-align: center;">
                                                <tr>
                                                    <td>{{ ++$i }}</td>
                                                    <td>{{ $petition->id }}</td>
                                                    <td>{{ $petition->user_fullname }}</td>
                                                    <td>
                                                        <?php
                                                        if($petition->petition_type == 1){
                                                            echo "Đi muộn về sớm";
                                                        }
                                                        if($petition->petition_type == 2){
                                                            if($petition->type_leave == "in_day"){
                                                                echo "Nghỉ phép trong ngày";
                                                            }else{
                                                                echo "Nghỉ phép nhiều ngày";
                                                            }
                                                        }
                                                        if($petition->petition_type == 3){
                                                            echo "Nghỉ việc";
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $check_date = date("d-m-Y", strtotime($petition->check_date));
                                                        $checkin = date("H:i", strtotime($petition->checkin));
                                                        if ($petition->petition_type == 3) {
                                                            echo $check_date;
                                                        } else{
                                                            echo $checkin." ngày ".$check_date;
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $check_date = date("d-m-Y", strtotime($petition->check_date));
                                                        $date_to = date("d-m-Y", strtotime($petition->date_to));
                                                        $checkout = date("H:i", strtotime($petition->checkout));
                                                        if ($petition->petition_type == 3) {
                                                            echo $check_date;
                                                        } else if($petition->date_to != null){
                                                            echo $checkout." ngày ".$date_to;
                                                        }
                                                        else{
                                                            echo $checkout." ngày ".$check_date;
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>{{ $petition->petition_reason }}</td>
                                                    <td>
                                                        <?php
                                                        $date_created_at_d = date("d-m-Y", strtotime($petition->created_at ));
                                                        $date_created_at_t = date("H:i:s", strtotime($petition->created_at ));
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
                                        <?php if( Auth::user()->permission == 1 ){ ?>
                                            <tbody style="text-align: center;">
                                                <tr>
                                                    <td>{{ ++$i }}</td>
                                                    <td>{{ $petition->id }}</td>
                                                    <td>{{ $petition->user_fullname }}</td>
                                                    <td>
                                                        <?php
                                                        if($petition->petition_type == 1){
                                                            echo "Đi muộn về sớm";
                                                        }
                                                        if($petition->petition_type == 2){
                                                            if($petition->type_leave == "in_day"){
                                                                echo "Nghỉ phép trong ngày";
                                                            }else{
                                                                echo "Nghỉ phép nhiều ngày";
                                                            }
                                                        }
                                                        if($petition->petition_type == 3){
                                                            echo "Nghỉ việc";
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $check_date = date("d-m-Y", strtotime($petition->check_date));
                                                        $checkin = date("H:i", strtotime($petition->checkin));
                                                        if ($petition->petition_type == 3) {
                                                            echo $check_date;
                                                        } else{
                                                            echo $checkin." ngày ".$check_date;
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $check_date = date("d-m-Y", strtotime($petition->check_date));
                                                        $date_to = date("d-m-Y", strtotime($petition->date_to));
                                                        $checkout = date("H:i", strtotime($petition->checkout));
                                                        if ($petition->petition_type == 3) {
                                                            echo $check_date;
                                                        } else if($petition->date_to != null){
                                                            echo $checkout." ngày ".$date_to;
                                                        }
                                                        else{
                                                            echo $checkout." ngày ".$check_date;
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>{{ $petition->petition_reason }}</td>
                                                    <td>
                                                        <?php
                                                        $date_created_at_d = date("d-m-Y", strtotime($petition->created_at ));
                                                        $date_created_at_t = date("H:i:s", strtotime($petition->created_at ));
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
                                        <th width=20%>Thông tin</th>
                                        <th width=12%>Ngày giờ bắt đầu</th>
                                        <th width=12%>Ngày giờ kết thúc</th>
                                        <th width=20%>Lý do</th>
                                        <th width=12%>Ngày gửi</th>
                                        <th width=7%>Thao tác</th>
                                    </tr>
                                    </thead>
                                    <?php $i=0;?>
                                    @foreach ($petitions as $petition)
                                        <?php if( $petition->petition_status == 0) { ?>
                                        <?php if( Auth::user()->permission == 0 && Auth::user()->fullname == $petition->user_fullname) { ?>
                                            <tbody style="text-align: center;">
                                                <tr>
                                                    <td>{{ ++$i }}</td>
                                                    <td>{{ $petition->id }}</td>
                                                    <td>{{ $petition->user_fullname }}</td>
                                                    <td>
                                                        <?php
                                                        if($petition->petition_type == 1){
                                                            echo "Đi muộn về sớm";
                                                        }
                                                        if($petition->petition_type == 2){
                                                            if($petition->type_leave == "in_day"){
                                                                echo "Nghỉ phép trong ngày";
                                                            }else{
                                                                echo "Nghỉ phép nhiều ngày";
                                                            }
                                                        }
                                                        if($petition->petition_type == 3){
                                                            echo "Nghỉ việc";
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $check_date = date("d-m-Y", strtotime($petition->check_date));
                                                        $checkin = date("H:i", strtotime($petition->checkin));
                                                        if ($petition->petition_type == 3) {
                                                            echo $check_date;
                                                        } else{
                                                            echo $checkin." ngày ".$check_date;
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $check_date = date("d-m-Y", strtotime($petition->check_date));
                                                        $date_to = date("d-m-Y", strtotime($petition->date_to));
                                                        $checkout = date("H:i", strtotime($petition->checkout));
                                                        if ($petition->petition_type == 3) {
                                                            echo $check_date;
                                                        } else if($petition->date_to != null){
                                                            echo $checkout." ngày ".$date_to;
                                                        }
                                                        else{
                                                            echo $checkout." ngày ".$check_date;
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>{{ $petition->petition_reason }}</td>
                                                    <td>
                                                        <?php
                                                        $date_created_at_d = date("d-m-Y", strtotime($petition->created_at ));
                                                        $date_created_at_t = date("H:i:s", strtotime($petition->created_at ));
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
                                        <?php if( Auth::user()->permission == 1 ){ ?>
                                            <tbody style="text-align: center;">
                                                <tr>
                                                    <td>{{ ++$i }}</td>
                                                    <td>{{ $petition->id }}</td>
                                                    <td>{{ $petition->user_fullname }}</td>
                                                    <td>
                                                        <?php
                                                        if($petition->petition_type == 1){
                                                            echo "Đi muộn về sớm";
                                                        }
                                                        if($petition->petition_type == 2){
                                                            if($petition->type_leave == "in_day"){
                                                                echo "Nghỉ phép trong ngày";
                                                            }else{
                                                                echo "Nghỉ phép nhiều ngày";
                                                            }
                                                        }
                                                        if($petition->petition_type == 3){
                                                            echo "Nghỉ việc";
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $check_date = date("d-m-Y", strtotime($petition->check_date));
                                                        $checkin = date("H:i", strtotime($petition->checkin));
                                                        if ($petition->petition_type == 3) {
                                                            echo $check_date;
                                                        } else{
                                                            echo $checkin." ngày ".$check_date;
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $check_date = date("d-m-Y", strtotime($petition->check_date));
                                                        $date_to = date("d-m-Y", strtotime($petition->date_to));
                                                        $checkout = date("H:i", strtotime($petition->checkout));
                                                        if ($petition->petition_type == 3) {
                                                            echo $check_date;
                                                        } else if($petition->date_to != null){
                                                            echo $checkout." ngày ".$date_to;
                                                        }
                                                        else{
                                                            echo $checkout." ngày ".$check_date;
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>{{ $petition->petition_reason }}</td>
                                                    <td>
                                                        <?php
                                                        $date_created_at_d = date("d-m-Y", strtotime($petition->created_at ));
                                                        $date_created_at_t = date("H:i:s", strtotime($petition->created_at ));
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
                            <!-- End Tu Choi -->
                        </div>
                    </form>
                </div>
            </form>
        </main>
    </div>
@endsection
