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
                                        <th width=8%>Người duyệt</th>
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
                                        <th width=8%>Người duyệt</th>
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
                                        <th width=8%>Người duyệt</th>
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
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <form >
                            <div class="modal-dialog" role="document">
                                <div class="modal-content" style="width:100%;">
                                <div class="modal-header">
                                    <h3 class="modal-title" id="exampleModalLabel">Tạo yêu cầu</h3>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                </div>
                                <div class="modal-body">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="user_fulllname" type="text" placeholder="..." />
                                                <label for="user_fullname">Họ và tên:</label>
                                            </div>  
                                            <form>
                                                <div class="form-floating mb-3">
                                                    <select class="form-control" name="request_type" id="request_type" >
                                                        <option selected disabled value>Lựa chọn</option>
                                                        <option value="1">Đi muộn về sớm</option>
                                                        <option value="2">Nghỉ phép</option>
                                                        <option value="3">Nghỉ việc</option>
                                                    </select>
                                                    <label  for="request_type">Loại yêu cầu</label>
                                                </div>
                                                <div class="form-floating mb-3" style="display: none;" id="1">
                                                    <div class="form-floating mb-3">
                                                        <input class="form-control" id="date_1" name="date_from" type="date" />
                                                        <script>
                                                            document.getElementById('date_1').value = new Date().toISOString().substring(0, 10);
                                                        </script>
                                                        <label for="">Chọn ngày:</label>
                                                    </div>
                                                    <div class="form-floating mb-3">
                                                        <input class="form-control" name="time_from" type="time" value="08:00"/>
                                                        <label for="">Thời gian từ:</label>
                                                    </div>
                                                    <div class="form-floating mb-3">
                                                        <input class="form-control" name="time_to" type="time" value="17:30"/>
                                                        <label for="">Đến:</label>
                                                    </div>
                                                </div>
                                                <div class="form-floating mb-3" style="display: none;" id="2">
                                                    <form>
                                                        <div class="form-floating mb-3">
                                                            <select class="form-control" name="type_leave" id="type_leave">
                                                                <option selected disabled value>Lựa chọn</option>
                                                                <option value="in_day">Trong ngày</option>
                                                                <option value="multi_day">Nhiều ngày</option>
                                                            </select>
                                                            <label for="type_leave">Loại nghỉ phép:</label>
                                                        </div>
                                                        <div class="form-floating mb-3" style="display: none;" id="in_day">
                                                            <div class="form-floating mb-3">
                                                                <input  class="form-control" type="date" name="date_from" id="inday" />
                                                                <label for="">Chọn ngày:</label>
                                                                <script>
                                                                    document.getElementById('inday').value = new Date().toISOString().substring(0, 10);
                                                                </script>
                                                            </div>
                                                            <div class="form-floating mb-3">
                                                                <input class="form-control" type="time" name="time_from" value="08:00" />
                                                                <label for="">Thời gian từ:</label>
                                                            </div>
                                                            <div class="form-floating mb-3">
                                                                <input class="form-control" type="time" name="time_to" value="17:30" />
                                                                <label for="">Thời gian đến:</label>
                                                            </div>
                                                        </div>
                                                        <div class="form-floating mb-3" style="display: none;" id="multi_day">
                                                            <div class="form-floating mb-3">
                                                                <input class="form-control" type="time" name="time_from" value="08:00" />
                                                                <label for="">Thời gian từ:</label>
                                                            </div>
                                                            <div class="form-floating mb-3">
                                                                <input class="form-control" type="date" name="date_from" id="multidayfrom" />
                                                                <label for="">Ngày:</label>
                                                                <script>
                                                                    document.getElementById('multidayfrom').value = new Date().toISOString().substring(0, 10);
                                                                </script>
                                                            </div>
                                                            <div class="form-floating mb-3">
                                                                <input class="form-control" type="time"  name="time_to" value="17:30" />
                                                                <label for="">đến:</label>
                                                            </div>
                                                            <div class="form-floating mb-3">
                                                                <input class="form-control" type="date" name="date_to" id="multidayto" />
                                                                <label for="">Ngày:</label>
                                                                <script>
                                                                    document.getElementById('multidayto').value = new Date().toISOString().substring(0, 10);
                                                                </script>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <script>
                                                        document.getElementById('type_leave').addEventListener("change", function (e) {
                                                            if (e.target.value === 'in_day') {
                                                                document.getElementById('in_day').style.display = 'block';
                                                                document.getElementById('multi_day').style.display = 'none';
                                                            } else {
                                                                document.getElementById('in_day').style.display = 'none';
                                                                document.getElementById('multi_day').style.display = 'block';
                                                            }
                                                        });
                                                    </script>
                                                </div>
                                                <div class="form-floating mb-3" style="display: none;" id="3">
                                                       <div class="form-floating mb-3">
                                                        <input class="form-control" id="lay_off" type="date" name="date_from" />
                                                        <script>
                                                            document.getElementById('lay_off').value = new Date().toISOString().substring(0, 10);
                                                        </script>
                                                        <label for="">Ngày dự định nghỉ việc:</label>
                                                    </div>
                                                </div>
                                                <div class="form-floating mb-3">
                                                    <textarea class="form-control" name="request_reason" type="text" style="height:100px;"></textarea>
                                                    <label for="">Lý do:</label>
                                                </div>
                                        <script>
                                            document.getElementById('request_type').addEventListener("change", function (e) {
                                                if (e.target.value === '2') {
                                                    document.getElementById('1').style.display = 'none';
                                                    document.getElementById('2').style.display = 'block';
                                                    document.getElementById('3').style.display = 'none';
                                                } else if (e.target.value === '1') {
                                                    document.getElementById('1').style.display = 'block';
                                                     document.getElementById('2').style.display = 'none';
                                                    document.getElementById('3').style.display = 'none';
                                                } else {
                                                    document.getElementById('1').style.display = 'none';
                                                     document.getElementById('2').style.display = 'none';
                                                    document.getElementById('3').style.display = 'block';
                                                }
                                            });
                                        </script>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                                        <button type="submit" class="btn btn-primary"> {{ __('Tạo yêu cầu') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </form>
                </form>
            </div>
        </form>
    </main>
</div>
@endsection