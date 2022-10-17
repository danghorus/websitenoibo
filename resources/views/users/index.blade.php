@extends('layouts.menu')

@section('content')
<style>

table {
    background: #fff;
    border: 0px solid #dedede;
}

table thead tr th {
    padding: 20px;
    border: 0px solid #dedede;
    color: #000;
}

table.table-striped tbody tr:nth-of-type(odd) {
    background: #f9f9f9;
}

table.result-point tr td.number {
    width: 100px;
    position: relative;
}

.text-left {
    text-align: left!important;
}

table tr td {
    padding: 0px 0px;
    border: 0px solid #dedede;
}
table.result-point tr td .fa.fa-caret-up {
    color: green;
}

table.result-point tr td .fa {
    font-size: 20px;
    position: absolute;
    right: 20px;
}

table tr td {
    padding: 5px 5px;
    border: 0px solid #dedede;
}

table tr td img {
    margin-right: 12px;
    margin-top: 1px;
    border: 0px solid #dedede;
}

</style>

    <!-- Page Heading -->
    <div class="row">
        <div class="card mx-auto" style="width: 99.9%;">
            <div>
                @if (session()->has('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @endif
            </div>
            <div class="card-header" style="margin: 0px 5px 5px 5px; ">
                <div class="row">
                    <div class="col">
                        <form method="GET" action="{{ route('users.index') }}">
                            <div class="form-row align-items-center">
                                <div class="col" >
                                    <input type="search" name="search" class="form-control mb-2" id="inlineFormInput" placeholder="Tên nhân viên"
                                    style="width:30%; border-radius:60px 0px 0px 60px; margin: 0px 0px 0px -18px;">
                                </div>
                                <div class="col" >
                                    <button type="submit" class="btn btn-primary mb-2"
                                        style="height:38px; margin: 0px -73.25%; background-color:#999da1; border-radius:0px 60px 60px 0px; border: 2px;">
                                        <i class="fas fa-search" style="color:#ffffff; " ></i>
                                </button>
                                </div>
                                <?php if(Auth::user()->permission == 1) { ?>
                                <a href= "{{ route('users.create') }}" class="btn btn-primary mb-2" style="float:right;">Thêm nhân viên</a>
                                <!--<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal"
                                    style="float:right;">Thêm nhân viên</button>-->
                                <?php } ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <form style="margin: -20px 5px 5px 5px; ">
                <ul class="nav nav-tabs" id="myTab" role="tablist" style="background-color: #F7F7F7">
                    <?php if(Auth::user()->permission == 1) { ?>
                    <li class="nav-item ">
                        <a class="nav-link active " id="tab-unapproved" data-toggle="tab"
                           href="#content-unapproved" role="tab" aria-controls="content-unapproved" aria-selected="false"> Đang làm việc</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-approved" data-toggle="tab"
                           href="#content-approved" role="tab" aria-controls="content-approved" aria-selected="false">Đã nghỉ việc</a>
                    </li>
                    <?php } ?>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="content-unapproved" role="tabpanel" aria-labelledby="tab-unapproved">
                        <table class="table-striped table-responsive table-hover result-point" style="table-layout: fixed;">
                            <thead class="point-table-head">
                                <tr style="text-align:center;">
                                    <th scope="col" width="3%">STT</th>
                                    <th scope="col" width="7%">Mã nhân viên</th>
                                    <th scope="col" width="8%">Ảnh đại diện</th>
                                    <th scope="col" width="10%" >Họ và tên</th>
                                    <th scope="col" width="8%" >Số điện thoại</th>
                                    <th scope="col" width="10%">Email</th>
                                    <th scope="col" width="8%"  >Ngày sinh</th>
                                    <th scope="col" width="6%" >Bộ phận</th>
                                    <th scope="col" width="7%">Chức danh</th>
                                    <th scope="col" width="8%">Ngày làm việc</th>
                                    <th scope="col" width="8%">Quyền truy cập</th>
                                    <?php if(Auth::user()->permission == 1) { ?>
                                    <th scope="col" width="7%">Thao tác</th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=0;?>
                                @foreach ($users as $key => $user)
                                    <?php
                                    if($user->user_status == 1) {
                                    ?>
                                    <tr style="text-align:center;">
                                        <td scope="col" width="3.57%">{{++$i }}</td>
                                        <td scope="col" width="7%" >{{ $user->id }}</td>
                                        <td scope="col" width="8%" ><img src="{{asset('image/'.$user->avatar)}}" style="width: 50px; height: 50px; border-radius: 50%;" ></td>
                                        <td scope="col" width="10%">{{ $user->fullname }}</td>
                                        <td scope="col" width="8%" >{{ $user->phone }}</td>
                                        <td scope="col" width="10%">{{ $user->email }}</td>
                                        <td scope="col" width="8.05%" >{{ $user->birthday }}</td>
                                        <td scope="col" width="6%" >{{ $user->department }}</td>
                                        <td scope="col" width="7%" >{{ $user->position }}</td>
                                        <td scope="col" width="8%" >
                                        <?php 
                                            if($user->date_official != ""){
                                                echo $user->date_official;
                                            }else{
                                                echo "Thử việc";
                                            } 
                                        ?>
                                    </td>
										
                                        <?php if($user->permission==0){ ?>
											<td scope="col" width="8%">Nhân viên</td>
										<?php }if($user->permission==1) { ?>
											<td scope="col" width="8%">Admin</td>
										<?php }if($user->permission==2) { ?>
											<td scope="col" width="8%">Leader</td>
										<?php }if($user->permission==3) { ?>
											<td scope="col" width="8%">Quản lý</td>
										<?php } ?>
										
										
                                        <?php if(Auth::user()->permission == 1) { ?>

                                        <td scope="col" width="6.2%" style="text-align:center; font-size:14px;">
                                            <form method="POST" action="{{ route('users.destroy', $user->id) }}">
                                                <a class="btn btn-success" href="{{route('users.edit', $user->id)}}" style="font-size:12px;">Sửa</a>
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xoá nhân viên này?');" style="text-align:center; font-size:12px;">Xoá</button>
                                            </form>
                                        </td>
                                        <?php } ?>
                                    </tr>
                                    <?php } ?>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="content-approved" role="tabpanel" aria-labelledby="tab-approved">
                        <table class="table-striped table-responsive table-hover result-point" style="table-layout: fixed;">
                            <thead class="point-table-head">
                            <tr style="text-align:center;" style="table-layout: fixed;">
                                <th scope="col" width="3%" >STT</th>
                                <th scope="col" width="7%">Mã nhân viên</th>
                                <th scope="col" width="8%">Ảnh đại diện</th>
                                <th scope="col" width="10%" >Họ và tên</th>
                                <th scope="col" width="8%" >Số điện thoại</th>
                                <th scope="col" width="10%">Email</th>
                                <th scope="col" width="8%" >Ngày sinh</th>
                                <th scope="col" width="6%" >Bộ phận</th>
                                <th scope="col" width="7%" >Chức danh</th>
                                <th scope="col" width="8%" >Ngày làm việc</th>
                                <th scope="col" width="8%" >Quyền truy cập</th>
                                <th scope="col" width="7%" >Thao tác</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i=0;?>
                            @foreach ($users as $key => $user)
                                <?php
                                if($user->user_status == 2) {
                                ?>
                                <tr style="text-align:center;">
                                    <td scope="col" width="3%">{{++$i }}</td>
                                    <td scope="col" width="7%">{{ $user->id }}</td>
                                    <td scope="col" width="8%"><img src="{{asset('image/'.$user->avatar)}}" style="width: 50px; height: 50px; border-radius: 50%;" ></td>
                                    <td scope="col" width="10%">{{ $user->fullname }}</td>
                                    <td scope="col" width="8%">{{ $user->phone }}</td>
                                    <td scope="col" width="10%">{{ $user->email }}</td>
                                    <td scope="col" width="8%">{{ $user->birthday }}</td>
                                    <td scope="col" width="6%">{{ $user->department }}</td>
                                    <td scope="col" width="7%">{{ $user->position }}</td>
                                    <td scope="col" width="8%">{{ $user->date_official}}</td>
									
                                    <?php if($user->permission==0){ ?>
                                        <td scope="col" width="8%">Nhân viên</td>
                                    <?php }if($user->permission==1) { ?>
                                        <td scope="col" width="8%">Admin</td>
                                    <?php }if($user->permission==2) { ?>
                                        <td scope="col" width="8%">Leader</td>
                                    <?php }if($user->permission==3) { ?>
                                        <td scope="col" width="8%">Quản lý</td>
                                    <?php } ?>
									
                                    <td scope="col" width="7%" style="text-align:center; font-size:14px;">
                                        <form method="POST" action="{{ route('users.destroy', $user->id) }}">
                                            <a class="btn btn-success" href="{{route('users.edit', $user->id)}}" style="font-size:12px;">Sửa</a>
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xoá nhân viên này?');" style="text-align:center; font-size:12px;">Xoá</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php } ?>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <style>
        table {
        table-layout: fixed;
        }

        th, td {
        padding: 0px 0px;
        border: 1px solid #000;
        }

        thead {
        background: #f9f9f9;
        display: table;
        }

        tbody {
        height: 860px;
        overflow: auto;
        display: block;
        tr {
            display: table;
            table-layout: fixed;
        }
        }
    </style>
@endsection
