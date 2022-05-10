@extends('layouts.menu')

@section('content')

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
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" 
                        data-whatever="@mdo" style="float:right; margin:-45px 0px 0px 0px;">Tạo yêu cầu</button>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="content-unapproved" role="tabpanel" aria-labelledby="tab-unapproved">
                            <br>
                            <table class="table table-bordered" id="yeucaub">
                                <thead>
                                    <tr style="text-align: center;">
                                        <th width=3% >STT</th>
                                        <th width=3%>ID</th>
                                        <th width=8%>Người yêu cầu</th>
                                        <th width=10%>Thông tin</th>
                                        <th width=10%>Ngày giờ bắt đầu</th>
                                        <th width=10%>Ngày giờ kết thúc</th>
                                        <th width=15%>Lý do</th>     
                                        <th width=10%>Ngày gửi</th>
                                        <th width=10%>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody style="text-align: center;">
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>
                                            <button class="btn btn-success" style="font-size:12px;">Duyệt</button>
                                            <button class="btn btn-danger" style="font-size:12px;">Từ chối</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="content-approved" role="tabpanel" aria-labelledby="tab-approved">

                            <br>
                            <table class="table table-bordered" id="yeucaub">
                                <thead>
                                    <tr style="text-align: center;">
                                        <th width=3% >STT</th>
                                        <th width=3%>ID</th>
                                        <th width=8%>Người yêu cầu</th>
                                        <th width=10%>Thông tin</th>
                                        <th width=10%>Ngày giờ bắt đầu</th>
                                        <th width=10%>Ngày giờ kết thúc</th>
                                        <th width=15%>Lý do</th>     
                                        <th width=10%>Ngày gửi</th>
                                    </tr>
                                </thead>
                                <tbody style="text-align: center;">
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="content-refuse" role="tabpanel" aria-labelledby="tab-refuse">
                            <br>
                            <table class="table table-bordered" id="yeucaub">
                                <thead>
                                    <tr style="text-align: center;">
                                        <th width=3% >STT</th>
                                        <th width=3%>ID</th>
                                        <th width=8%>Người yêu cầu</th>
                                        <th width=10%>Thông tin</th>
                                        <th width=10%>Ngày giờ bắt đầu</th>
                                        <th width=10%>Ngày giờ kết thúc</th>
                                        <th width=15%>Lý do</th>     
                                        <th width=10%>Ngày gửi</th>
                                    </tr>
                                </thead>
                                <tbody style="text-align: center;">
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @extends('request.create')
                </form>
            </div>
        </form>
    </main>
</div>
@endsection
