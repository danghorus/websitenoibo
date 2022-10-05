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
<script>
$(document).ready(function(){
  $("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#myTable tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>
<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
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
                                <a class="nav-link" href="{{ url('/petitions') }}">
                                    Yêu cầu cần duyệt </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{url('/approved')}}" style="background-color: #408080; color:#fff">
                                    Đã duyệt </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/unapproved') }}" >
                                    Từ chối </a>
                            </li>
                            <!--<li class="nav-item">
                                <input type="date" name="search" class="form-control mb-2" id="inlineFormInput" >
                            </li>-->
                            <li>
                                
                            </li>
                            <li>
                                <input class="form-control mb-2" id="myInput" type="text" placeholder="Search.."\
                                style="width:100%; border-radius:0px 0px 0px 0px;">
                                <select class="form-control" id="yourAge">
                                    <option value="Đi muộn về sớm">Đi muộn về sớm</option>
                                    <option value="Nghỉ phép">Nghỉ phép</option>
                                    <option value="3">Nghỉ việc</option>
                                    <option value="4">Thay đổi giờ công</option>
                                    <option value="5">Đăng ký làm thêm</option>
                                    <option value="6">Đăng ký nỗ lực</option>
                                </select>
                            </li>
                            <script>
                                var input = document.getElementById("myInput");
                                var sel1 = document.getElementById("yourAge");

                                    sel1.onclick = function(){
                                        sel1 = document.getElementById("yourAge");
                                        var selected = sel1.options[sel1.selectedIndex].value;
                                        input = document.getElementById("myInput");
                                        input.value=selected;
                                        };
                            </script>
                        </ul>
                        <ul class="navbar-nav" style="float:right; margin: -50px 0px 0px 0px;">
                            <li class="nav-item dropdown">
                                    <button class="btn btn-success dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown" data-bs-auto-close="true">Tạo yêu cầu</button>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                    <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#exampleModal_goLate">Đi muộn/về sớm</a></li>
                                    <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#exampleModal_goOut">Đăng ký ra ngoài</a></li>
                                    <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#exampleModal_OTWar">Đăng ký làm nỗ lực</a></li>
                                    <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#exampleModal_warrior">Đăng ký Warrior</a></li>
                                    <li class="dropdown-submenu">
                                        <a class="dropdown-item dropdown-toggle" href="#" data-bs-auto-close="true">Đăng ký làm công</a>
                                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#exampleModal_OTAM">ĐKLC nửa ngày(AM)</a></li>
                                            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#exampleModal_OTPM">ĐKLC nửa ngày(PM)</a></li>
                                            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#exampleModal_OT">ĐKLC một ngày</a></li>
                                            <li><a class="dropdown-item" data-bs-toggle="modal"  data-bs-target="#exampleModal_multiOT">ĐKLC nhiều ngày</a></li>
                                        </ul>
                                    </li>
                                    <li class="dropdown-submenu">
                                        <a class="dropdown-item dropdown-toggle" href="#" data-bs-auto-close="true">Nghỉ phép</a>
                                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#exampleModal_inDayAM">Nửa ngày(AM)</a></li>
                                            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#exampleModal_inDayPM">Nửa ngày(PM)</a></li>
                                            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#exampleModal_inDay">Một ngày</a></li>
                                            <li><a class="dropdown-item" data-bs-toggle="modal"  data-bs-target="#exampleModal_multiDay">Nhiều ngày</a></li>
                                        </ul>
                                    </li>
                                        <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#exampleModal_quit">Nghỉ việc</a></li>
                                </ul>
                            </li>
                        </ul>
                           
                        <script >
                            $('.dropdown-menu a.dropdown-toggle').on('click', function(e) {
                            if (!$(this).next().hasClass('show')) {
                                $(this).parents('.dropdown-menu').first().find('.show').removeClass('show');
                            }
                            var $subMenu = $(this).next('.dropdown-menu');
                            $subMenu.toggleClass('show');


                            $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function(e) {
                                $('.dropdown-submenu .show').removeClass('show');
                            });

                            return false;
                        });
                        </script>
                        <div class="tab-content" id="myTabContent">
                            <!-- Start Yeu Cau Can Duyet -->
                            <div class="tab-pane fade show active" id="content-unapproved" role="tabpanel" aria-labelledby="tab-unapproved">
                                <table class="table-striped table-responsive table-hover result-point">
                                    <thead class="point-table-head">
                                    <tr style="text-align: center;">
                                        <th style="width:50px">STT</th>
                                        <th style="width:50px">ID</th>
                                        <th width=12%>Người yêu cầu</th>
                                        <th width=12%>Loại yêu cầu</th>
                                        <th width=25%>Thông tin yêu cầu</th>
                                        <th width=20%>Lý do</th>
                                        <th width=12%>Ngày gửi</th>
                                        <th width=14%>Thao tác</th>
                                    </tr>
                                    </thead>
                                    <?php $i=0;?>
                                    @foreach ($petitions as $petition )
                                        <?php if( Auth::user()->permission == 0 && Auth::user()->fullname == $petition->user_fullname) { ?>
                                        <tbody id="myTable">
                                            <tr>
                                            <td>John</td>
                                            <td>Doe</td>
                                            <td>john@example.com</td>
                                            <td>1</td>
                                            <td>2</td>
                                            <td>3</td>
                                            <td>4</td>
                                            <td>5</td>
                                            </tr>
                                            <tr>
                                            <td>Dang</td>
                                            <td>Bui</td>
                                            <td>dangbn@example.com</td>
                                            <td>6</td>
                                            <td>7</td>
                                            <td>8</td>
                                            <td>9</td>
                                            <td>10</td>
                                            </tr>
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $petition->id }}</td>
                                            <td>{{ $petition->user_fullname }}</td>
                                            <td>
                                                <?php
                                                $type = $petition->petition_type;
                                                $leave = $petition->type_leave;
                                                if($type == 1){
                                                    echo "Đi muộn về sớm";
                                                }
                                                if($type == 2){
                                                    if($leave == 1){
                                                        echo "Nghỉ phép nửa ngày(sáng)";
                                                    }else if($leave == 2){
                                                        echo "Nghỉ phép nửa ngày(chiều)";
                                                    } else if($leave == 3) {
                                                        echo "Nghỉ phép một ngày";
                                                    } else if($leave == 3) {
                                                        echo "Nghỉ phép nhiều ngày";
                                                    }
                                                }
                                                if($type == 3){
                                                    echo "Nghỉ việc";
                                                }
                                                if($type == 4){
                                                    echo "Thay đổi giờ chấm công";
                                                }
                                                if($type == 5){
                                                    echo "Đăng ký làm thêm";
                                                }
                                                if($type == 6){
                                                    echo "Đăng ký làm nỗ lực";
                                                }
                                                if($type == 9){
                                                    echo "Đăng ký ra ngoài";
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $type = $petition->petition_type;
                                                $leave = $petition->type_leave;
                                                $time_from_old = $petition->time_from_old? date("H:i", strtotime($petition->time_from_old)): ' -:- ';
                                                $time_from = $petition->time_from? date("H:i", strtotime($petition->time_from)): ' -:- ';
                                                $date_from = date("d-m-Y", strtotime($petition->date_from));
                                                $time_to_old = $petition->time_to_old? date("H:i", strtotime($petition->time_to_old)): ' -:- ';
                                                $time_to = $petition->time_to? date("H:i", strtotime($petition->time_to)): ' -:- ';
                                                $date_to = date("d-m-Y", strtotime($petition->date_to));
                                                if($type == 4 ){
                                                    if($time_from_old == $time_from){
                                                        echo "Ngày <b>".$date_from."</b> từ ".$time_from_old."-><b>".$time_to_old."</b> thành ".$time_from." -><b>".$time_to."</b>.";
                                                    } else if($time_to_old == $time_to){
                                                        echo "Ngày <b>".$date_from."</b> từ <b>".$time_from_old."</b>->".$time_to_old." thành <b>".$time_from."</b> ->".$time_to.".";
                                                    }else {
                                                        echo "Ngày <b>".$date_from."</b> từ <b>".$time_from_old."</b>-><b>".$time_to_old."</b> thành <b>".$time_from."</b>-><b>".$time_to."</b>.";
                                                    }
                                                } else if($type == 1){
                                                    echo "Ngày <b>".$date_from."</b> từ <b>".$time_from."</b> đến <b>".$time_to."</b>.";
                                                } else if($type == 2){
                                                    if($leave == 1){
                                                        echo "Ngày <b>".$date_from."</b>.";
                                                    } else if($leave == 2){
                                                        echo "Ngày <b>".$date_from."</b>.";
                                                    } else if($leave == 3){
                                                        echo "Ngày <b>".$date_from."</b>.";
                                                    } else if($leave == 4){
                                                        echo "Ngày <b>".$date_from."</b> đến hết ngày <b>".$date_to."</b>.";
                                                    }
                                                } else if($type == 3){
                                                    echo "Ngày bắt đầu nghỉ việc <b>".$date_from."</b>.";
                                                } else if($type == 5){
                                                    echo "Đăng ký làm ngày <b>".$date_from."</b>.";
                                                } else if($type == 6){
                                                    echo "Đăng ký làm nỗ lực ngày <b>".$date_from."</b>.";
                                                } else if($type == 9){
                                                    echo "Đăng ký ra ngoài <b>".$date_from."</b> từ <b>".$time_from."</b> đến <b>".$time_to."</b>.";
                                                }
                                                ?>
                                            </td>
                                            <td>{{ $petition->petition_reason }}</td>
                                            <td>
                                                <?php
                                                $date_created_at_d = date("d-m-Y", strtotime($petition->created_at ));
                                                $date_created_at_t = date("H:i", strtotime($petition->created_at ));
                                                echo  $date_created_at_t." ngày ".$date_created_at_d;
                                                ?>
                                            </td>
                                            <td style="text-align: center;">
                                                <form action="{{ route('petitions.destroy',$petition->id) }}" method="POST">
                                                </form>
                                                <form action="{{ route('petitions.destroy',$petition->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Bạn có chắc chắn xoá yêu cầu này?');"
                                                            class="btn btn-danger" style="font-size:12px;">Xoá</button>
                                            </td>
                                        </tr>
                                        </tbody>
                                        <?php } ?>
                                        <?php 
                                            $date = date("m-Y", time());
                                            $date_from = date("m-Y", strtotime($petition->date_from));
                                            if( (Auth::user()->permission == 1 || Auth::user()->permission == 2 || Auth::user()->permission == 3)){ ?>
                                        <tbody id="myTable">
                                        <tr>
                                            <td style="text-align: center;">{{ ++$i }}</td>
                                            <td style="text-align: center;">{{ $petition->id }}</td>
                                            <!--<td>{{ $petition->user_fullname }}</td>-->
                                            <td>
                                                @foreach($users as $user)
                                                    <?php if($petition->user_id == $user->id){
                                                        $petition->user_fullname = $user->fullname;
                                                        echo $user->fullname;
                                                    }
                                                    ?>
                                                @endforeach
                                            </td>
                                            <td>
                                                <?php
                                                $type = $petition->petition_type;
                                                $leave = $petition->type_leave;
                                                if($type == 1){
                                                    echo "Đi muộn về sớm";
                                                }
                                                if($type == 2){
                                                    if($leave == 1){
                                                        echo "Nghỉ phép nửa ngày(sáng)";
                                                    }else if($leave == 2){
                                                        echo "Nghỉ phép nửa ngày(chiều)";
                                                    } else if($leave == 3) {
                                                        echo "Nghỉ phép một ngày";
                                                    } else {
                                                        echo "Nghỉ phép nhiều ngày";
                                                    }
                                                }
                                                if($type == 3){
                                                    echo "Nghỉ việc";
                                                }
                                                if($type == 4){
                                                    echo "Thay đổi giờ chấm công";
                                                }
                                                if($type == 5){
                                                    echo "Đăng ký làm thêm";
                                                }
                                                if($type == 6){
                                                    echo "Đăng ký làm nỗ lực";
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $type = $petition->petition_type;
                                                $leave = $petition->type_leave;
                                                $time_from_old = $petition->time_from_old? date("H:i", strtotime($petition->time_from_old)): ' -:- ';
                                                $time_from = $petition->time_from? date("H:i", strtotime($petition->time_from)): ' -:- ';
                                                $date_from = date("d-m-Y", strtotime($petition->date_from));
                                                $time_to_old = $petition->time_to_old? date("H:i", strtotime($petition->time_to_old)): ' -:- ';
                                                $time_to = $petition->time_to? date("H:i", strtotime($petition->time_to)): ' -:- ';
                                                $date_to = date("d-m-Y", strtotime($petition->date_to));
                                                if($type == 4 ){
                                                    if($time_from_old == $time_from){
                                                        echo "Ngày <b>".$date_from."</b> từ ".$time_from_old."-><b>".$time_to_old."</b> thành ".$time_from." -><b>".$time_to."</b>.";
                                                    } else if($time_to_old == $time_to){
                                                        echo "Ngày <b>".$date_from."</b> từ <b>".$time_from_old."</b>->".$time_to_old." thành <b>".$time_from."</b> ->".$time_to.".";
                                                    }else {
                                                        echo "Ngày <b>".$date_from."</b> từ <b>".$time_from_old."</b>-><b>".$time_to_old."</b> thành <b>".$time_from."</b>-><b>".$time_to."</b>.";
                                                    }
                                                } else if($type == 1){
                                                    echo "Ngày <b>".$date_from."</b> từ <b>".$time_from."</b> đến <b>".$time_to."</b>.";
                                                } else if($type == 2){
                                                    if($leave == 1){
                                                        echo "Ngày <b>".$date_from."</b>.";
                                                    } else if($leave == 2){
                                                        echo "Ngày <b>".$date_from."</b>.";
                                                    } else if($leave == 3){
                                                        echo "Ngày <b>".$date_from."</b>.";
                                                    } else if($leave == 4){
                                                        echo "Ngày <b>".$date_from."</b> đến hết ngày <b>".$date_to."</b>.";
                                                    }
                                                } else if($type == 3){
                                                    echo "Ngày bắt đầu nghỉ việc <b>".$date_from."</b>.";
                                                } else if($type == 5){
                                                    echo "Đăng ký làm ngày <b>".$date_from."</b>.";
                                                } else if($type == 6){
                                                    echo "Đăng ký làm nỗ lực ngày <b>".$date_from."</b>.";
                                                }
                                                ?>
                                            </td>

                                            <td>{{ $petition->petition_reason }}</td>
                                            <td style="text-align: center;">
                                                <?php
                                                $date_created_at_d = date("d-m-Y", strtotime($petition->created_at ));
                                                $date_created_at_t = date("H:i", strtotime($petition->created_at ));
                                                echo  $date_created_at_t." ngày ".$date_created_at_d;
                                                ?>
                                            </td>
                                            <td style="text-align: center;">
                                                <nav class="navbar navbar-expand-lg navbar-light bg-light" >
                                                    <ul class="navbar-nav mr-auto" style="font-size:16px;" >
                                                        &emsp;&nbsp;
                                                        <li class="nav-item">
                                                            <form action="{{ route('petitions.destroy',$petition->id) }}" method="POST"></form>
                                                            <form method="POST" action="{{ route('petitions.update', $petition->id) }}">
                                                                @csrf
                                                                @method('PUT')
                                                                <input id="read" type="text" name="read" value="2" hidden>
                                                                <button type="submit" class="btn btn-success" style="font-size:12px;">Đã đọc</button>
                                                            </form>
                                                        </li> &ensp;
                                                        <li class="nav-item">
                                                            <form action="{{ route('petitions.destroy',$petition->id) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" onclick="return confirm('Bạn có chắc chắn xoá yêu cầu này?');"
                                                                        class="btn btn-danger" style="font-size:12px;">Xoá</button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </nav>
                                            </td>
                                        </tr>
                                        </tbody>
                                        <?php } ?>
                                    @endforeach
                                </table>
                            </div>
                            <!--End Yeu Cau Can Duyet-->    
                            Rooms:
                            <select id="room-filter">
                            <option value="">Show All</option>
                            <option value="1">1 Room</option>
                            <option value="2">2 Rooms</option>
                            <option value="3">3 Rooms</option>
                            </select>

                            <div class="card text-left" data-rooms="1">
                            <div class="card-body d-flex" id="content-card">
                                <h2>Shack</h2>
                                <p class="main-text">1 room</p>
                            </div>
                            </div>

                            <div class="card text-left" data-rooms="1">
                            <div class="card-body d-flex" id="content-card">
                                <h2>Second Shack</h2>
                                <p class="main-text">1 room</p>
                            </div>
                            </div>

                            <div class="card text-left" data-rooms="3">
                            <div class="card-body d-flex" id="content-card">
                                <h2>Bungalo</h2>
                                <p class="main-text">3 rooms</p>
                            </div>
                            </div>    
                            <script>
                                $('#room-filter').on('change', function() {
                            const numRooms = $(this).val();
                            $('.card').each(function() {
                                if (numRooms && numRooms != $(this).data('rooms')) {
                                $(this).slideUp();
                                } else {
                                $(this).slideDown();
                                }
                            });
                            });
                            </script>      
                        </div>
                    </form>
                </div>
            </form>
        </main>
    </div>
@endsection
