@extends('layouts.menu')

@section('content')

    <style>
        table {
            background: #fff;
            border: 1px solid #999999;
            min-height: 800px;
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
                                <a class="nav-link" href="{{ url('/petitions') }}" style="background-color: #408080; color:#fff">
                                    Yêu cầu cần duyệt
                                    <?php if( (Auth::user()->permission == 1 || Auth::user()->permission == 2 || Auth::user()->permission == 3) && count($petitions1) > 0){ ?>
                                    <span class="badge">{{count($petitions1)}}</span>
                                    <?php }  ?>
                                    <?php if( Auth::user()->permission == 0 && count($petitions01) > 0){ ?>
                                    <span class="badge">{{count($petitions01)}}</span>
                                    <?php } ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{url('/approved')}}" >
                                    Đã duyệt
                                    <?php if(count($petitions02) > 0){ ?>
                                    <span class="badge">{{count($petitions02)}}</span>
                                    <?php } ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/unapproved') }}">
                                    Từ chối
                                    <?php if(count($petitions03) > 0){ ?>
                                    <span class="badge">{{count($petitions03)}}</span>
                                    <?php } ?>
                                </a>
                            </li>
                            <li style="margin-left: 25px;">
                                <input class ="form-control" id="searchText" type="text" placeholder="Search.."\
                                       style="width:150%; border-radius:5px;">
                            </li>
                            <li style="margin-left: 47%;">
                                <input class ="form-control" id="searchMonth" name="searchMonth" type="month"
                                    style="width:120%; border-radius:5px;">
                            </li>
                         </ul>
                        <script>
                            $(document).ready(function(){
                                $("#searchMonth").on("change", function() {
                                    let value = $(this).val().toLowerCase();
                                    console.log(value)
                                    $("#myTable tr").filter(function() {
                                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                                    });
                                });
                                $("#searchText").on("keyup", function() {
                                    var value = $(this).val().toLowerCase();
                                    console.log(value)
                                    $("#myTable tr").filter(function() {
                                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                                    });
                                });
                            });
                        </script>

                        <ul class="navbar-nav" style="float:right; margin: -50px 0px 0px 0px;">
                            <li class="nav-item dropdown">
                                <button class="btn btn-success dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown" data-bs-auto-close="true">Tạo yêu cầu</button>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                    <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#exampleModal_goLate">Đi muộn/về sớm</a></li>
                                    <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#exampleModal_goOut">Đăng ký ra ngoài</a></li>
                                    <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#exampleModal_OTWar">Đăng ký làm nỗ lực</a></li>
                                    <!--<li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#exampleModal_warrior">Đăng ký Warrior</a></li>-->
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
                                        <th style="width:40px">STT</th>
                                        <th width=10%>Người yêu cầu</th>
                                        <th width=17%>Loại yêu cầu</th>
                                        <th width=18%>Thông tin yêu cầu</th>
                                        <th width=20%>Lý do</th>
                                        <th width=17%>Trạng thái</th>
                                        <th width=10%>Ngày gửi</th>
                                        <th width=6%>Thao tác</th>
                                    </tr>
                                    </thead>
                                    <?php $i=0;?>
                                    @foreach ($petitions as $petition )
                                        <?php if( Auth::user()->permission == 0 && Auth::user()->id == $petition->user_id) { ?>
                                            <tbody id="myTable">
                                                <tr>
                                                    <td hidden>{{ $petition->date_from }}</td>
                                                    <td>{{ ++$i }}</td>
                                                    <td>{{ $petition->fullname }}</td>
                                                    <td>{{ $petition->petition_type_title}}</td>
                                                    <td>{{ $petition->info}}</td>
                                                    <td>{{ $petition->petition_reason }}</td>
                                                    <td>{{$petition->send}}</td>
                                                    <td style="text-align: center;">
                                                        <form action="{{ route('petitions.destroy',$petition->id) }}" method="POST">
                                                        </form>
                                                        <form action="{{ route('petitions.destroy',$petition->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" onclick="return confirm('Bạn có chắc chắn xoá yêu cầu này?');"
                                                                class="btn btn-danger" style="font-size:12px;">Xoá
                                                            </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        <?php } ?>
                                        <?php if( Auth::user()->permission == 1 || Auth::user()->permission == 2 || Auth::user()->permission == 3 ){ ?>

                                            <tbody id="myTable" style="font-size:16px;">
                                                <tr>
                                                    <td hidden>{{ $petition->date_from }}</td>
                                                    <td>{{ ++$i }}</td>
                                                    <td>{{$petition->fullname}}</td>
                                                    <td>{{$petition->petition_type_title}}</td>
                                                    <td>{{$petition->info}}</td>
                                                    <td>{{ $petition->petition_reason }}</td>
                                                    <td>{{ $petition->check }}</td>
                                                    <td>{{$petition->send}}</td>
                                                    <!--<td style="text-align: center;">
                                                        <nav class="navbar navbar-expand-lg navbar-light bg-light" style="font-size: 20px;">
                                                                <ul class="navbar-nav mr-auto" style="font-size:16px;" >
                                                                    &emsp;&nbsp;
                                                                    <li class="nav-item">
                                                                        <form action="{{ route('petitions.destroy',$petition->id) }}" method="POST"></form>
                                                                        <form method="POST" action="{{ route('petitions.update', $petition->id) }}">
                                                                            @csrf
                                                                            @method('PUT')
                                                                            <input id="petition_status" type="text" name="petition_status" value="2" hidden>
                                                                            <button type="submit" class="btn btn-success" style="font-size:12px;">Duyệt</button>
                                                                        </form>
                                                                    </li> &ensp;
                                                                    <li class="nav-item">
                                                                        <form method="POST" action="{{ route('petitions.update', $petition->id) }}">
                                                                            @csrf
                                                                            @method('PUT')
                                                                            <input id="petition_status" type="text" name="petition_status" value="3" hidden>
                                                                            <button type="submit" class="btn btn-warning" style="font-size:12px;">Từ chối</button>
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
                                                    </td>-->
                                                    <td>
                                                        <ul class="navbar-nav">
                                                            <li class="nav-item dropdown">
                                                                <button class="btn btn-success dropdown-toggle" type="button"
                                                                    data-bs-toggle="dropdown" data-bs-auto-close="true">Thao tác</button>
                                                                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                                                    <center>
                                                                    <li><form action="{{ route('petitions.destroy',$petition->id) }}" method="POST"></form>
                                                                        <form method="POST" action="{{ route('petitions.update', $petition->id) }}">
                                                                            @csrf
                                                                            @method('PUT')
                                                                            <input id="petition_status" type="number" name="petition_status" value="2" hidden>
                                                                            <input id="infringe" type="number" name="infringe" value="0" hidden>
                                                                            <button class="btn btn-success" type="submit" style="font-size:12px;">Duyệt yêu cầu</button>
                                                                        </form>
                                                                    </li><br>
                                                                    <li><form method="POST" action="{{ route('petitions.update', $petition->id) }}">
                                                                            @csrf
                                                                            @method('PUT')
                                                                            <input id="petition_status" type="number" name="petition_status" value="2" hidden>
                                                                            <input id="infringe" type="number" name="infringe" value="1" hidden>
                                                                            <button class="btn btn-primary " type="submit" style="font-size:12px;">Duyệt vi phạm</button>
                                                                        </form>
                                                                    </li><br>
                                                                    <li><form method="POST" action="{{ route('petitions.update', $petition->id) }}">
                                                                            @csrf
                                                                            @method('PUT')
                                                                            <input id="petition_status" type="text" name="petition_status" value="3" hidden>
                                                                            <input id="infringe" type="number" name="infringe" value="0" hidden>
                                                                            <button class="btn btn-warning " type="submit" style="font-size:12px;">Từ chối yêu cầu</button>
                                                                        </form>
                                                                    </li><br>
                                                                    <li><form action="{{ route('petitions.destroy',$petition->id) }}" method="POST">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button class="btn btn-danger" type="submit" onclick="return confirm('Bạn có chắc chắn xoá yêu cầu này?');"
                                                                                style="font-size:12px;">Xoá yêu cầu</button>
                                                                        </form>
                                                                    </li>
                                                                    </center>
                                                                </ul>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        <?php } ?>
                                    @endforeach
                                </table>
                            </div>
                            <!--End Yeu Cau Can Duyet-->
                        </div>
                    </form>
                </div>
            </form>
        </main>
    </div>
@endsection
