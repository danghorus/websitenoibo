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
                                        <th colspan="3" width=15%>Thao tác</th>
                                    </tr>
                                    </thead>
                                    <tbody style="text-align: center;">
                                    <?php $i=0;?>
                                    @foreach ($petitions as $petition)
                                        <?php
                                        if($petition->petition_status == 1) {
                                        ?>
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
                                                if ($petition->petition_type == 3) {
                                                    echo $petition->date_from;
                                                } else{
                                                    echo $petition->time_from." - ".$petition->date_from;
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $t= $petition->date_to;
                                                if ($petition->petition_type == 3) {
                                                    echo $petition->date_from;
                                                } else if($t != null){
                                                    echo $petition->time_to." - ".$petition->date_to;
                                                }
                                                else{
                                                    echo $petition->time_to." - ".$petition->date_from;
                                                }
                                                ?>
                                            </td>
                                            <td>{{ $petition->petition_reason }}</td>
                                            <td>{{ $petition->created_at }}</td>
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
                                        <?php }

                                        ?>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="content-approved" role="tabpanel" aria-labelledby="tab-approved">
                                <table class="table-striped table-responsive table-hover result-point">
                                    <thead class="point-table-head">
                                    <tr style="text-align: center;">
                                        <th width=50px >STT</th>
                                        <th width=100px>ID</th>
                                        <th width=15%>Người yêu cầu</th>
                                        <th width=15%>Thông tin</th>
                                        <th width=11%>Ngày giờ bắt đầu</th>
                                        <th width=11%>Ngày giờ kết thúc</th>
                                        <th width=25%>Lý do</th>
                                        <th width=15%>Ngày gửi</th>
                                    </tr>
                                    </thead>
                                    <tbody style="text-align: center;">
                                    <?php $i=0;?>
                                    @foreach ($petitions as $petition)
                                        <?php
                                        if($petition->petition_status == 2) {
                                        ?>
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
                                                if ($petition->petition_type == 3) {
                                                    echo $petition->date_from;
                                                } else{
                                                    echo $petition->time_from." - ".$petition->date_from;
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $t= $petition->date_to;
                                                if ($petition->petition_type == 3) {
                                                    echo $petition->date_from;
                                                } else if($t != null){
                                                    echo $petition->time_to." - ".$petition->date_to;
                                                }
                                                else{
                                                    echo $petition->time_to." - ".$petition->date_from;
                                                }
                                                ?>
                                            </td>
                                            <td>{{ $petition->petition_reason }}</td>
                                            <td>{{ $petition->created_at }}</td>
                                        </tr>
                                        <?php }

                                        ?>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="content-refuse" role="tabpanel" aria-labelledby="tab-refuse">
                                <table class="table-striped table-responsive table-hover result-point">
                                    <thead class="point-table-head">
                                    <tr style="text-align: center;">
                                        <th width=50px >STT</th>
                                        <th width=100px>ID</th>
                                        <th width=15%>Người yêu cầu</th>
                                        <th width=15%>Thông tin</th>
                                        <th width=11%>Ngày giờ bắt đầu</th>
                                        <th width=11%>Ngày giờ kết thúc</th>
                                        <th width=25%>Lý do</th>
                                        <th width=15%>Ngày gửi</th>
                                    </tr>
                                    </thead>
                                    <tbody style="text-align: center;">
                                    <?php $i=0;?>
                                    @foreach ($petitions as $petition)
                                        <?php
                                        if($petition->petition_status == 0) {
                                        ?>
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
                                                if ($petition->petition_type == 3) {
                                                    echo $petition->date_from;
                                                } else{
                                                    echo $petition->time_from." - ".$petition->date_from;
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $t= $petition->date_to;
                                                if ($petition->petition_type == 3) {
                                                    echo $petition->date_from;
                                                } else if($t != null){
                                                    echo $petition->time_to." - ".$petition->date_to;
                                                }
                                                else{
                                                    echo $petition->time_to." - ".$petition->date_from;
                                                }
                                                ?>
                                            </td>
                                            <td>{{ $petition->petition_reason }}</td>
                                            <td>{{ $petition->created_at }}</td>
                                        </tr>
                                        <?php }

                                        ?>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>
            </form>
        </main>
    </div>
@endsection
