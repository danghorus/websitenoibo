@extends('layouts.menu')

@section('content')
        <!-- Page heading -->
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ __('Thay đổi ảnh đại diện') }}</h4>
                            <a type="button" class="btn btn-secondary" href="{{ route('users.index')}}"style="float:right; margin: -40px -12px 0px 0px;">Quay lại</a>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('users.change.avatar', $user->id) }}" enctype="multipart/form-data">
                                   @csrf
                                <div class="form-input-wide">
                                    <div class="avatar-wrapper1" style="margin: 0px 0px 0px 20% ;">
                                        <img id="avatar" name="avatar" class="profile-pic" src="{{asset('image/'.$user->avatar)}}"/>
                                        <div class="upload-button">
                                            <i class="fa fa-arrow-circle-up" aria-hidden="true"></i>
                                        </div>
                                        <input class="file-upload" name="avatar" id="avatar" type="file" accept=".png, .jpg, .jpeg" value="" />
                                        @error('avatar')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div><br>
                                 <div class="form-group row mb-0">
                                    <div class="col-md-6 offset-md-4" style="margin: -17% 0px 0px 60% ;">
                                        <button type="submit" class="btn btn-primary">{{ __('Cập nhật ảnh đại diện') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ __('Chỉnh sửa thông tin nhân viên') }}</h4>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('users.update', $user->id) }}">
                                @csrf
                                @method('PUT')
                                <div class="form-group row">
                                    <label for="fullname" class="col-md-4 col-form-label text-md-right">{{ __('Họ và tên') }}</label>
                                    <div class="col-md-6">
                                        <input id="fullname" type="text"
                                            class="form-control @error('name') is-invalid @enderror" name="fullname"
                                            value="{{ old('fullname', $user->fullname) }}" required autocomplete="fullname">

                                        @error('fullname')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="phone" class="col-md-4 col-form-label text-md-right">{{ __('Số điện thoại') }}</label>

                                    <div class="col-md-6">
                                        <input id="phone" type="text"
                                            class="form-control @error('name') is-invalid @enderror" name="phone"
                                            value="{{ old('phone', $user->phone) }}" required autocomplete="phone">

                                        @error('phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="birthday" class="col-md-4 col-form-label text-md-right">{{ __('Ngày sinh') }}</label>

                                    <div class="col-md-6">
                                        <input id="birthday" type="date"
                                            class="form-control @error('name') is-invalid @enderror" name="birthday"
                                            value="{{ old('birthday', $user->birthday) }}" required autocomplete="birthday" >

                                        @error('birthday')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Email') }}</label>

                                    <div class="col-md-6">
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                            name="email" value="{{ old('email', $user->email) }}">

                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="department" class="col-md-4 col-form-label text-md-right">{{ __('Bộ phận') }}</label>

                                    <div class="col-md-6">
                                        <select id="department" type="text" class="form-control @error('name') is-invalid @enderror" name="department"
                                            required autocomplete="department">
                                            <option>
                                            <?php
                                            if($user->department == 1){
                                                echo "Admin";
                                            }
                                            else if($user->department == 2){
                                                echo "Dev";
                                            }
                                            else if($user->department == 3){
                                                echo "Game Design";
                                            }
                                            else if($user->department == 4){
                                                echo "Art";
                                            }
                                            else if($user->department == 5){
                                                echo "Tester";
                                            }
                                            else if($user->department == 6){
                                                echo "Điều hành";
                                            }
                                            else if($user->department == 7){
                                                echo "Hành chính nhân sự";
                                            }
                                            else if($user->department == 8){
                                                echo "Kế toán";
                                            }
                                            else if($user->department == 9){
                                                echo "Phân tích dữ liệu";
                                            }
                                            else if($user->department == 10){
                                                echo "Support";
                                            }
                                            else if($user->department == 11){
                                                echo "Marketing";
                                            }
                                            ?>
                                            </option>
                                            <option value="1">Admin</option>
                                                <option value="2">Dev</option>
                                                <option value="3">Game design</option>
                                                <option value="4">Art</option>
                                                <option value="5">Tester</option>
                                                <option value="6">Điều hành</option>
                                                <option value="7">Hành chính nhân sự</option>
                                                <option value="8">Kế toán</option>
                                                <option value="9">Phân tích dữ liệu</option>
                                                <option value="10">Support</option>
                                                <option value="11">Marketing</option>
                                        </select>

                                        @error('department')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="position" class="col-md-4 col-form-label text-md-right">{{ __('Chức danh') }}</label>

                                    <div class="col-md-6">
                                        <select id="position" type="text" class="form-control @error('name') is-invalid @enderror" name="position"
                                            required autocomplete="position" >
                                            <option > {{$user->position }} </option>
                                            <option value="Nhân viên">Nhân viên</option>
                                            <option value="Leader">Leader</option>
                                            <option value="Quản lý">Quản lý</option>
                                            <option value="Giám đốc">Giám đốc</option>
                                        </select>

                                        @error('position')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="date_official" class="col-md-4 col-form-label text-md-right">{{ __('Ngày bắt đầu làm việc chính thức') }}</label>

                                    <div class="col-md-6">
                                        <input id="date_official" type="date" name="date_official"
                                            class="form-control" value="{{ old('date_official', $user->date_official) }}" >

                                        @error('date_official')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="permission" class="col-md-4 col-form-label text-md-right">{{ __('Quyền truy cập') }}</label>

                                    <div class="col-md-6">
                                        <select id="permission" type="text" class="form-control @error('name') is-invalid @enderror" name="permission"
                                            value="" required autocomplete="permission">
                                            <option value="0" {{old('permission')==0 || $user->permission==0?'selected':false}}>Nhân viên</option>
                                            <option value="2" {{old('permission')==2 || $user->permission==2?'selected':false}}>Leader</option>
                                            <option value="3" {{old('permission')==3 || $user->permission==3?'selected':false}}>Quản lý</option>
                                            <option value="1" {{old('permission')==1 || $user->permission==1?'selected':false}}>Admin</option>
                                        </select>

                                        @error('permission')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="permission" class="col-md-4 col-form-label text-md-right">Loại chấm công</label>

                                    <div class="col-md-6">
                                        <select id="check_type" type="text" class="form-control @error('name') is-invalid @enderror" name="check_type"
                                            value="" required autocomplete="check_type">
                                            <option value="1" {{old('check_type')==1 || $user->check_type==1?'selected':false}}>Camera AI</option>
                                            <option value="2" {{old('check_type')==2 || $user->check_type==2?'selected':false}}>Thủ công</option>
                                            </select>

                                        @error('check_type')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="user_status" class="col-md-4 col-form-label text-md-right">{{ __('Trạng thái hoạt động') }}</label>

                                    <div class="col-md-6">
                                        <select id="user_status" type="text" class="form-control @error('name') is-invalid @enderror" name="user_status"
                                            value="" required autocomplete="user_status">
                                            <option value="1" {{old('user_status')==1 || $user->user_status==1?'selected':false}}>Hoạt động</option>
                                            <option value="2" {{old('user_status')==2 || $user->user_status==2?'selected':false}}>Nghỉ việc</option>
                                        </select>

                                        @error('permission')
                                        <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn btn-primary"> {{ __('Cập nhật thông tin') }} </button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ __('Thay đổi mật khẩu') }}</h4>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('users.change.password', $user->id) }}">
                                @csrf
                                <div class="form-group row">
                                <label for="password"class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>
                                    <div class="col-md-6">
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>
                                    <div class="col-md-6">
                                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
                                    </div>
                                </div>
                                <div class="form-group row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn btn-primary">{{ __('Cập nhật mật khẩu') }}</button>
                                    </div>
                                </div>
                            </form>
                            <form method="POST" action="{{ route('users.destroy', $user->id) }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" style="float:right; margin:0px 0px 0px 0px;"
                                    onclick="return confirm('Bạn có chắc chắn muốn xoá nhân viên này?');">Xoá {{ $user->fullname }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
