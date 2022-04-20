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
                                        style="margin: 0px -650px; background-color:#999da1; border-radius:0px 60px 60px 0px; border: 2px;">
                                        <i class="fas fa-search" style="color:#ffffff; " ></i>
                                </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div>
                        <a href= "{{ route('users.create') }}" class="btn btn-primary mb-2" style="float:right; margin:-45px 0px 0px 0px;">Thêm nhân viên</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr> 
                            <th scope="col">STT</th>
                            <th scope="col">Mã nhân viên</th>
                            <th scope="col">Ảnh đại diện</th>
                            <th scope="col" >Họ và tên</th>
                            <th scope="col" >Số điện thoại</th>
                            <th scope="col">Username</th>
                            <th scope="col">Email</th>
                            <th scope="col" >Ngày sinh</th>
                            <th scope="col" >Bộ phận</th>
                            <th scope="col" >Chức danh</th>
                            <th scope="col" >Hoạt động</th>
                            <th scope="col" >Quyền truy cập</th>
                            <th scope="col" >Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td></td>
                                <th scope="row">{{ $user->id }}</th>
                                <td></td>
                                <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                <td></td>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->email }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-success">Chỉnh sửa</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
