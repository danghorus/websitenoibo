@extends('layouts.menu')

@section('content')

    <!-- Page Heading -->
    <div class="row">
        <div class="card mx-auto" style="width: 98%;">
            <div>
                @if (session()->has('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @endif
            </div>
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <form method="GET" action="{{ route('users.index') }}">
                            <div class="form-row align-items-center">
                                <div class="col" >
                                    <input type="search" name="search" class="form-control mb-2" id="inlineFormInput" placeholder="Jane Doe" 
                                    style="width:30%; border-radius:60px 0px 0px 60px;">
                                </div>
                                <div class="col" >
                                    <button type="submit" class="btn btn-primary mb-2"
                                        style="height:38px; margin: 0px -71.5%; background-color:#999da1; border-radius:0px 60px 60px 0px; border: 2px;">
                                        <i class="fas fa-search" style="color:#ffffff; " ></i>
                                </button>
                                </div>
                                <!--<a href= "{{ route('users.create') }}" class="btn btn-primary mb-2" style="float:right;">Thêm nhân viên</a>-->
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" style="float:right;">Thêm nhân viên</button>
                            </div>
                            
                        </form>
                    </div>
                    <div>
                        
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr style="text-align:center;"> 
                            <th scope="col">STT</th>
                            <th scope="col">Mã nhân viên</th>
                            <th scope="col">Ảnh đại diện</th>
                            <th scope="col" >Họ và tên</th>
                            <th scope="col" >Số điện thoại</th>
                            <th scope="col">Email</th>
                            <th scope="col" >Ngày sinh</th>
                            <th scope="col" >Bộ phận</th>
                            <th scope="col" >Chức danh</th>
                            <th scope="col" >Quyền truy cập</th>
                            <th scope="col" >Thao tác</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=0;?>
                        @foreach ($users as $key => $user)
                            <tr style="text-align:center;">
                                <td>{{++$i }}</td>
                                <td scope="row">{{ $user->id }}</td>
                                <td ><img src="{{asset('image/'.$user->avatar)}}" style="width: 50px; height: 50px; border-radius: 50%;" ></td>
                                <td>{{ $user->fullname }}</td>
                                <td scope="row">{{ $user->phone }}</td>
                                <td>{{ $user->email }}</td>
                                <td scope="row">{{ $user->birthday }}</td>
                                <td scope="row">{{ $user->department }}</td>
                                <td scope="row">{{ $user->position }}</td>
                                <td scope="row">{{ $user->permission==0?'Nhân viên':'Quản lý' }} </td>
                                <td>
                                    <a class="btn btn-success" href="{{route('users.edit', $user->id)}}">Chỉnh sửa</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>    
    </div>
@extends('users.create')
@endsection
