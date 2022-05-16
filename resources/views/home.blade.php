@extends('layouts.menu')

@section('content')
<br>
<div id="layoutSidenav_content" style=" margin-top:-20px;">
                <main>
                    <div class="container-fluid px-4">
                        <div class="row">
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-primary text-white mb-4">
                                    <div class="card-body">
                                        <b id="tc">NHÂN SỰ</b>
                                        <nav><i id="notetc">
                                                Số lượng nhân viên: {{count($users)}}
                                            </i></nav>  
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="{{route('users.index')}}">Xem chi tiết</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-warning text-white mb-4">
                                    <div class="card-body">
                                        <b id="tc">DỰ ÁN</b>
                                        <nav><i id="notetc">
                                                Số lượng dự án: {{count($works)}}
                                            </i></nav>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="{{route('works.index')}}">Xem chi tiết</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-success text-white mb-4">
                                    <div class="card-body">
                                        <b id="tc"> YÊU CẦU</b>
                                        <nav><i id="notetc">
                                                Số lượng yêu cầu cần duyệt: {{count($petitions)}}
                                            </i></nav>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="{{route('petitions.index')}}">Xem chi tiết</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-danger text-white mb-4">
                                    <div class="card-body">
                                    <b id="tc">BÁO CÁO</b>
                                        <nav><i id="notetc">
                                        Các thông số về dự án, nhân sự và trọng số
                                        </i></nav>
                                </div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="{{route('home')}}">Xem chi tiết</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
@endsection
