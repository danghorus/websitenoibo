@extends('layouts.menu')

@section('content')

<style>

table {
    background: #fff;
    border: 1px solid #999999;
}

table thead tr th {
    padding: 10px;
    border: 1px solid #9b9b9b;
    color: #000;
}

table.table-striped tbody tr:nth-of-type(odd) {
    background: #f9f9f9;
}
 

.text-left {
    text-align: left!important;
}

table tr td {
    padding: 0px 0px;
    border: 1px solid #999999;
}

table.result-point tr td .fa {
    font-size: 20px;
    position: absolute;
    right: 20px;
}

table tr td {
    padding: 10px 10px;
    border: 1px solid #999999;
}


</style>

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
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal_petition" 
                        style="float:right; margin:-45px 0px 0px 0px;">Tạo yêu cầu</button>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="content-unapproved" role="tabpanel" aria-labelledby="tab-unapproved">
                            <table class="table-striped table-responsive table-hover result-point">
                                <thead class="point-table-head">
                                    <tr style="text-align: center;">
                                        <th width=5%>STT</th>
                                        <th width=5%>ID</th>
                                        <th width=10%>Người yêu cầu</th>
                                        <th width=10%>Thông tin</th>
                                        <th width=10%>Ngày giờ bắt đầu</th>
                                        <th width=10%>Ngày giờ kết thúc</th>
                                        <th width=20%>Lý do</th>     
                                        <th width=10%>Ngày gửi</th>
                                        <th width=10%>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody style="text-align: center;">
                                    <?php $i=0;?>
                                    @foreach ($petitions as $petition)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $petition->id }}</td>
                                        <td>{{ $petition->user_fullname }}</td>
                                        <td>
                                            <?php
                                                if($petition->petition_type == 1){
                                                    echo "Đi muộn về sớm";
                                                }
                                                if($petition->petition_type == 2){
                                                    if($petition->type_leave == "in_day"){
                                                        echo "Nghỉ phép trong ngày";
                                                    }else{
                                                        echo "Nghỉ phép nhiều ngày";
                                                    }
                                                } 
                                                if($petition->petition_type == 3){
                                                    echo "Nghỉ việc";
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                                if ($petition->petition_type == 3) {
                                                    echo $petition->date_from;
                                                } else{
                                                    echo $petition->time_from." - ".$petition->date_from;
                                                }
                                                ?>
                                        </td>
                                        <td>
                                            <?php
                                                $t= $petition->date_to;
                                                if ($petition->petition_type == 3) {
                                                    echo $petition->date_from;
                                                } else if($t != null){
                                                    echo $petition->time_to." - ".$petition->date_to;
                                                    } 
                                                else{
                                                    echo $petition->time_to." - ".$petition->date_from;
                                                }
                                            ?>
                                        </td>
                                        <td>{{ $petition->petition_reason }}</td>
                                        <td>{{ $petition->created_at }}</td>
                                        <td>
                                            <form action="{{ route('petitions.destroy',$petition->id) }}" method="POST">
                                                <a class="btn btn-success" href="{{ route('petitions.show',$petition->id) }}" style="font-size:12px;">Duyệt</a>
                                                <a class="btn btn-warning" href="{{ route('petitions.edit',$petition->id) }}" style="font-size:12px;">Từ chối</a>
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Bạn có chắc chắn xoá yêu cầu này?');" 
                                                    class="btn btn-danger" style="font-size:12px;">Xoá</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="content-approved" role="tabpanel" aria-labelledby="tab-approved">
                            <table class="table-striped table-responsive table-hover result-point">
                                <thead class="point-table-head">
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
                                   <?php $i=0;?>
                                    <tr>
                                        <td>{{ ++$i }}</td>
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
                           <table class="table-striped table-responsive table-hover result-point">
                                <thead class="point-table-head">
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
                                   <?php $i=0;?>
                                    <tr>
                                        <td>{{ ++$i }}</td>
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
                </form>
            </div>
        </form>
    </main>
</div>
@endsection
