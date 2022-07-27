@extends('layouts.menu')

@section('content')
    <!-- Page heading -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>{{ __('Thêm nhân viên') }}</h4>
                        <!--<a type="button" class="btn btn-secondary" href="{{ route('users.index')}}"style="float:right; margin: -40px -12px 0px 0px;">Quay lại</a>-->
                    </div>
                    <div class="card-body">
                            <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
                            @csrf
                                <div class="container">
                                    <div class="row justify-content-center">
                                        <div class="col-md-8">
                                            @if (session()->has('success'))
                                                <h1>IT WORKS!</h1>
                                            @endif
                                                <div class="form-group row">
                                                    <label for="avatar" class="col-md-3 col-form-label text-md-right">{{ __('Ảnh đại diện') }}</label>

                                                    <div class="form-input-wide">
                                                        <div class="avatar-wrapper" style="margin: -5% 0px 0px 42% ;">
                                                            <img name="avatar" class="profile-pic" src="{{ asset('img/avt.jpg') }}"/>
                                                            <div class="upload-button">
                                                                <i class="fa fa-arrow-circle-up" aria-hidden="true"></i>
                                                            </div>
                                                            <input class="file-upload" name="avatar" type="file" accept=".png, .jpg, .jpeg" />
                                                            @error('avatar')
                                                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="fullname"
                                                        class="col-md-3 col-form-label text-md-right">{{ __('Họ và tên') }}</label>

                                                    <div class="col-md-8">
                                                        <input id="fullname" type="text"
                                                            class="form-control @error('name') is-invalid @enderror" name="fullname"
                                                            value="{{ old('fullname') }}" required autocomplete="fullname" autofocus>

                                                        @error('fullname')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="phone"
                                                        class="col-md-3 col-form-label text-md-right">{{ __('Số điện thoại') }}</label>

                                                    <div class="col-md-8">
                                                        <input id="phone" type="text"
                                                            class="form-control @error('name') is-invalid @enderror" name="phone"
                                                            value="{{ old('phone') }}" required autocomplete="phone" autofocus>

                                                        @error('phone')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="birthday"
                                                        class="col-md-3 col-form-label text-md-right">{{ __('Ngày sinh') }}</label>

                                                    <div class="col-md-8">
                                                        <input id="birthday" type="date"
                                                            class="form-control @error('name') is-invalid @enderror" name="birthday"
                                                            value="2000-01-01" required autocomplete="birthday" autofocus>

                                                        @error('birthday')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="email"
                                                        class="col-md-3 col-form-label text-md-right">{{ __('Email') }}</label>

                                                    <div class="col-md-8">
                                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                                            name="email" value="{{ old('email') }}" required autocomplete="email">

                                                        @error('email')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="department"
                                                        class="col-md-3 col-form-label text-md-right">{{ __('Bộ phận') }}</label>

                                                    <div class="col-md-8">
                                                        <select id="department" type="text" class="form-control @error('department') is-invalid @enderror"
                                                            name="department" value="{{ old('department') }}" required autocomplete="department">
                                                                        <option value="Admin">Admin</option>
                                                                        <option value="Dev">Dev</option>
                                                                        <option value="Game design">Game design</option>
                                                                        <option value="Art">Art</option>
                                                                        <option value="Tester">Tester</option>
                                                                        <option value="Điều hành">Điều hành</option>
                                                                        <option value="Hành chính nhân sự">Hành chính nhân sự</option>
                                                                        <option value="Kế toán">Kế toán</option>
                                                                        <option value="Phân tích dữ liệu">Phân tích dữ liệu</option>
                                                                        <option value="Support">Support</option>
                                                                        <option value="Marketing">Marketing</option>
                                                                    </select>
                                                        @error('department')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="position"
                                                        class="col-md-3 col-form-label text-md-right">{{ __('Chức danh') }}</label>

                                                    <div class="col-md-8">
                                                        <select id="position" type="text" class="form-control @error('position') is-invalid @enderror"
                                                            name="position" value="{{ old('position') }}" required autocomplete="position">
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

                                                <div class="form-group row" hidden>
                                                    <label for="date_official"
                                                        class="col-md-3 col-form-label text-md-right">{{ __('Ngày làm việc chính thức') }}</label>

                                                    <div class="col-md-8">
                                                        <input id="date_official" name="date_official" type="date"
                                                            class="form-control @error('date_official') is-invalid @enderror">

                                                        @error('date_official')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="permission"
                                                        class="col-md-3 col-form-label text-md-right">{{ __('Quyền truy cập') }}</label>

                                                    <div class="col-md-8">
                                                        <select id="permission" type="text" class="form-control @error('permission') is-invalid @enderror"
                                                            name="permission" value="{{ old('permission') }}" required autocomplete="permission">
                                                                        <option value="0">Nhân viên</option>
                                                                        <option value="1">Quản lý</option>
                                                                    </select>
                                                        @error('permission')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="form-group row" hidden>
                                                    <label for="permission"
                                                        class="col-md-3 col-form-label text-md-right">Loại chấm công</label>

                                                    <div class="col-md-8">
                                                        <select id="check_type" type="text" class="form-control @error('check_type') is-invalid @enderror"
                                                            name="check_type" value="{{ old('check_type') }}" required autocomplete="check_type">
                                                                        <option value="1">Camera AI</option>
                                                                        <option value="2">Thủ công</option>
                                                                    </select>
                                                        @error('check_type')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="form-group row" hidden>
                                                    <label for="user_status" class="col-md-3 col-form-label text-md-right">{{ __('Trạng thái hoạt động') }}</label>

                                                    <div class="col-md-8">
                                                        <select id="user_status" type="text" class="form-control @error('name') is-invalid @enderror" name="user_status"
                                                                value="{{ old('user_status') }}" required autocomplete="user_status">
                                                            <option value="1">Hoạt động</option>
                                                            <option value="2">Nghỉ việc</option>
                                                        </select>

                                                        @error('permission')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="password"
                                                        class="col-md-3 col-form-label text-md-right">{{ __('Password') }}</label>

                                                    <div class="col-md-8">
                                                        <input id="password" type="password"
                                                            class="form-control @error('password') is-invalid @enderror" name="password"
                                                            required autocomplete="new-password">

                                                        @error('password')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <center>
                                    <a type="button" class="btn btn-secondary" href="{{ route('users.index')}}">Cancel</a>
                                    <button type="submit" class="btn btn-primary" >Thêm nhân viên</button>
                                </center>
                                <br>
                            </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
