@extends('layouts.menu')

@section('content')
<br>
<div id="layoutSidenav_content" style=" margin-top:-20px;">
<style type="text/css">
#popup-giua-man-hinh .headerContainer,#popup-giua-man-hinh .bodyContainer,#popup-giua-man-hinh .footerContainer{
    max-width:960px;margin:0 auto;background:#FFF}
#popup-giua-man-hinh .padding{padding:0px}
#popup-giua-man-hinh .bodyContainer{min-height:500px}
#popup-giua-man-hinh .popUpBannerBox{position:fixed;background:rgba(0,0,0,0.9);
    width:100%;height:100%;top:0;left:0;color:#FFF;z-index:999999;display:none}
#popup-giua-man-hinh .popUpBannerInner{max-width:300px;margin:0 auto}
#popup-giua-man-hinh .popUpBannerContent{position:fixed;top:20%; left: 40%;}
#popup-giua-man-hinh .closeButton{color:red;text-decoration:none;font-size:18px}
#popup-giua-man-hinh a.closeButton{float:right}
</style>
<?php if(count($petitions)>0 && Auth::user()->permission == 1 ){ ?>
    <div id="popup-giua-man-hinh">
        <div class="popUpBannerBox">
            <div class="popUpBannerInner">
                <div class="popUpBannerContent">
                    <p><a href="#" class="closeButton">Đóng</a></p>
<!-- CODE HIỂN THỊ QUẢNG CÁO -->    
                    <div class="card bg-success text-white" style="width:400px; height:200px;">
                        <div class="card-body">
                            <b id="tc"> YÊU CẦU</b>
                            <nav><i>
                                    Số lượng yêu cầu cần duyệt: {{count($petitions)}}
                                </i>
                            </nav>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="{{route('petitions.index')}}">Xem chi tiết</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
<!-- END HIỂN THỊ QUẢNG CÁO -->
            </div>
        </div>
    </div>
    <script type="text/javascript">
    function showPopUpBanner() {
    $('.popUpBannerBox').fadeIn("2000");
    }
    setTimeout(showPopUpBanner, 0); //thời gian popup bắt đầu hiển thị

    $('.popUpBannerBox').click(function(e) {
    if ( !$(e.target).is('.popUpBannerContent, .popUpBannerContent *' ) ) {
    $('.popUpBannerBox').fadeOut("2000");
    return false;
    }
    });
    $('.closeButton').click(function() {
    $('.popUpBannerBox').fadeOut("2000");
    return false;
    });
    </script>
</div>
<?php }?>
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
