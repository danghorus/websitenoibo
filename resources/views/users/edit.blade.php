@extends('layouts.menu')

@section('content')

    <!-- Page Heading -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>{{ __('Thay đổi ảnh đại diện') }}</h4>
                        <a class="btn btn-secondary" href="{{ route('users.index') }}" style="float:right; margin:-43px -15px 0px 0px;">Quay lại</a>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            @csrf
                            <div class="form-input-wide">
                                <label for="password"class="col-md-4 col-form-label text-md-right"></label>
                                <div class="avatar-wrapper1" style="margin: 0px 0px 0px 10% ;">  
                                    <img id="avatar" name="avatar" class="profile-pic" src="{{ asset('img/avt.jpg') }}"/>
                                    <div class="upload-button">
                                        <i class="fa fa-arrow-circle-up" aria-hidden="true"></i>
                                    </div>
                                    <input class="file-upload" name="avatar" id="avatar" type="file" accept="image/*" value="" />
                                    @error('password')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div><br>
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4" style="margin: -27% 0px 0px 60% ;">
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
                        <div class="m-2 p-2">
                            <form method="POST" action="{{ route('users.destroy', $user->id) }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" style="float:right; margin:-50px -30px 0px 0px;">Xoá {{ $user->fullname }}</button>  
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('users.update', $user->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="form-group row">
                                <label for="fullname"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Họ và tên') }}</label>

                                <div class="col-md-6">
                                    <input id="fullname" type="text"
                                        class="form-control @error('name') is-invalid @enderror" name="fullname"
                                        value="{{ old('fullname', $user->fullname) }}" required autocomplete="fullname"
                                        autofocus>

                                    @error('fullname')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="phone"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Số điện thoại') }}</label>

                                <div class="col-md-6">
                                    <input id="phone" type="text"
                                        class="form-control @error('name') is-invalid @enderror" name="phone"
                                        value="{{ old('phone', $user->phone) }}" required
                                        autocomplete="phone" autofocus>

                                    @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="birthday"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Ngày sinh') }}</label>

                                <div class="col-md-6">
                                    <input id="birthday" type="date"
                                        class="form-control @error('name') is-invalid @enderror" name="birthday"
                                        value="{{ old('birthday', $user->birthday) }}" required
                                        autocomplete="birthday" autofocus>

                                    @error('birthday')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Email') }}</label>

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
                                <label for="bophan"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Bộ phận') }}</label>

                                <div class="col-md-6">
                                    <select id="bophan" type="text" class="form-control @error('name') is-invalid @enderror" name="bophan"
                                        required autocomplete="bophan" autofocus>
                                        <option >{{ $user->bophan }}</option>
                                        <option value="Dev">Dev</option>
                                        <option value="Game design">Game design</option>
                                        <option value="Art">Art</option>
                                        <option value="Tester">Tester</option>
                                </select>

                                    @error('bophan')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="chucdanh"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Chức danh') }}</label>

                                <div class="col-md-6">
                                <select id="chucdanh" type="text" class="form-control @error('name') is-invalid @enderror" name="chucdanh"
                                        required autocomplete="chucdanh" autofocus>
                                    <option > {{$user->chucdanh }} </option>
                                    <option value="Nhân viên">Nhân viên</option>
                                    <option value="Leader">Leader</option>
                                    <option value="Quản lý">Quản lý</option>
                                    <option value="Giám đốc">Giám đốc</option>
                                </select>

                                    @error('chucdanh')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="quyen"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Quyền truy cập') }}</label>

                                <div class="col-md-6">
                                    <select id="quyen" type="text" class="form-control @error('name') is-invalid @enderror" name="quyen"
                                        value="" required autocomplete="quyen" autofocus>
                                        <option >
                                            @if ($user->quyen ==0)
                                            Nhân viên 
                                            @else
                                            Quản lý
                                            @endif
                                        </option>
                                        <option value="0">Nhân viên</option>
                                        <option value="1">Quản lý</option>
                                    </select>

                                    @error('quyen')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Cập nhật thông tin') }}
                                    </button>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
