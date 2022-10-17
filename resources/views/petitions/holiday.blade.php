 <div class="modal fade" id="exampleModal_holiday" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog" role="document">
        <div class="modal-content" style="width:100%;">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">Thêm lịch nghỉ tiêu chuẩn của công ty</h3>
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
                <form action="{{ route('petitions.store') }}" method="POST" id="Holiday">
                    @csrf

                    <div class="form-floating mb-3">
                        <select class="form-control" id="userlname" name="user_id">
                            <option selected disabled value>Chọn nhân viên</option>
                            @foreach($users as $user)
                                <?php
                                if ($user->user_status==1){
                                ?>
                                <option value="{{$user->id}}"> {{$user->fullname}}</option>
                                <?php } ?>
                            @endforeach
                        </select>
                        <label for="user_fullname">Họ và tên:</label>
						<div id="vmsg1" style="color:brown; margin: 10px;"></div>
                    </div>

                    <div class="form-floating mb-3" hidden>
                        <input class="form-control" name="type_leave" type="text" value="5" hidden/>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" id="inday" name="date_from" type="date" />
                        <script>
                            document.getElementById('inday').value = new Date().toISOString().substring(0, 10);
                        </script>
                        <label for="">Vui lòng chọn ngày:</label>
                    </div>
                    <div class="form-floating mb-3" hidden>
                        <input class="form-control" name="petition_reason" id="petition_reason" type="text" style="height:100px;" value="5">
                        <label for="">Lý do:</label>
						<div id="vmsg2" style="color:brown; margin: 10px;"></div>
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
