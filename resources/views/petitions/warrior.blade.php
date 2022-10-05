 <div class="modal fade" id="exampleModal_warrior" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog" role="document">
        <div class="modal-content" style="width:100%;">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">Đăng ký cấp độ Warrior</h3>
                <button type="button" class="close" data-bs-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="modal-body">
                <form action="{{ route('warriors.store') }}" method="POST">
                    @csrf

                    <div class="form-floating mb-3">
                        <input class="form-control" name="user_fullname" type="text" value="{{ Auth::user()->fullname }}" readonly/>
                        <label for="">Họ và tên:</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" name="user_id" type="text" value="{{ Auth::user()->id }}" hidden/>
                        <label for="">Mã nhân viên:</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select class="form-control" id="warrior" name="warrior">
                            <option selected disabled value>Lựa chọn</option>
                            <option value="0">Soldier</option>
                            <option value="1">Warrior 1</option>
                            <option value="2">Warrior 2</option>
                            <option value="3">Warrior 3</option>
                        </select>
                        <label for="">Cấp độ Warrior đăng ký</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select class="form-control" id="project_id" name="project_id">
                            <option selected disabled value>Lựa chọn</option>
                            @foreach($projects as $project)
                                <option value="{{$project->id}}"> {{$project->project_name}}</option>
                            @endforeach
                        </select>
                        <label for="">Chọn dự án</label>
                    </div>
                     
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" >Tạo yêu cầu</button>
                    </div>
                </form>
            </div>
        </div>
     </div>
 </div>
