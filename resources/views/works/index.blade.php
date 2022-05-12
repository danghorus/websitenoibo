@extends('layouts.menu')
 
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Công việc</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('works.create') }}"> Thêm công việc</a>
            </div>
        </div>
    </div>
   
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
   
    <table class="table table-bordered">
        <tr>
            <th>No</th>
            <th>Tên Công việc</th>
            <th>Chi tiết công việc</th>
            <th width="280px">Thao tác</th>
        </tr>
        @foreach ($works as $work)
        <tr>
            <td>{{ ++$i }}</td>
            <td>{{ $work->work_name }}</td>
            <td>{{ $work->work_detail }}</td>
            <td>
                <form action="{{ route('works.destroy',$work->id) }}" method="POST">
   
                    <a class="btn btn-info" href="{{ route('works.show',$work->id) }}">Chi tiết</a>
    
                    <a class="btn btn-primary" href="{{ route('works.edit',$work->id) }}">Sửa</a>
   
                    @csrf
                    @method('DELETE')
      
                    <button type="submit" class="btn btn-danger">Xoá</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
  
    {!! $works->links() !!}
    <br>



    <div class="row">
        <div class="col-lg-2 p-0 border-right ">
            <div class="bg-white radius-5">
                <div class="nav-project">
                    <ul class="nav flex-column">
                        <li class="p-2 position active">
                            <a class="float-left mb-0"><i class="fa fa-home pr-2 font20"></i>Dashboard</a>
                            <div class="float-right">
                                <a class="collapsed"><i class="fa fa-search font16 pr-1"></i></a>
                                <a class="pointer color-blue" tabindex="-1">
                                    <i class="fa fa-exchange font16" style="transform: rotate(90deg);"></i>
                                </a>
                            </div>
                        </li>
                    </ul>
                    <div class="nav-project gf-scroll" style="height: 849px; overflow-y: auto;">
                        <ul class="nav flex-column">
                            <li class="p-2 position">
                                <a class="pointer"><i class="fa fa-folder-open font18 color-folder pr-1"></i>1</a>
                                    <i class="fa fa-angle-down float-right font20"></i>
                                    <span class="noti noti-project noti-project-right">15</span>
                            </li>
                            <div class="collapse show">
                                <li class="p-2 pr-3 pl-4 position">
                                    <div>
                                        <i class="fa fa-angle-double-right"></i>
                                        <a class="pointer">&nbsp;Công việc</a>
                                        <span class="noti noti-project">15</span>
                                    </div>
                                </li>
                                <li class="p-2 pr-3 pl-4 position">
                                    <div>
                                        <i class="fa fa-angle-double-right"></i>
                                        <a class="pointer">&nbsp;Dự án số 3 của zeny  mua trà sữa</a>
                                    </div>
                                </li>
                            </div>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
      
@endsection