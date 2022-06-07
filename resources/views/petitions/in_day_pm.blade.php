 <div class="modal fade" id="exampleModal_inDayPM" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog" role="document">
        <div class="modal-content" style="width:100%;">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">Nghỉ phép nửa ngày (sáng)</h3>
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
                <form action="{{ route('petitions.store') }}" method="POST">
                    @csrf

                    <?php
                    if(Auth::user()->permission == 0) {
                    ?>

                    <div class="form-floating mb-3">
                        <input class="form-control" name="user_fullname" type="text" value="{{ Auth::user()->fullname }}" readonly/>
                        <label for="">Họ và tên:</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" name="user_id" type="text" value="{{ Auth::user()->id }}" readonly/>
                        <label for="">Mã nhân viên:</label>
                    </div>
                    <?php } else {?>

                       <div class="form-floating mb-3">
                            <select class="form-control" id="user_fullname" name="user_fullname">
                                <option selected disabled value>Chọn nhân viên</option>
                                @foreach($users as $user)
                                    <?php
                                    if ($user->user_status==1 && $user->id){
                                    ?>
                                    <option>{{$user->fullname}}</option>
                                    <?php } ?>
                                @endforeach
                            </select>
                            <label for="user_fullname">Họ và tên:</label>
                        </div>
                    <?php } ?>

                    <div class="form-floating mb-3">
                        <input class="form-control" name="petition_type" type="text" value="2" hidden/>
                        <label for="">loại yêu cầu</label>
                    </div>
                     <div class="form-floating mb-3">
                        <input class="form-control" name="type_leave" type="text" value="in_day_PM" hidden/>
                        <label for="">Loại nghỉ phép</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" id="indaypm" name="date_from" type="date" />
                        <script>
                            document.getElementById('indaypm').value = new Date().toISOString().substring(0, 10);
                        </script>
                        <label for="">Chọn ngày xin nghỉ phép:</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" name="time_from" type="time" value="13:30" hidden/>
                        <label for="">Thời gian từ:</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" name="time_to" type="time" value="17:30" hidden/>
                        <label for="">Đến:</label>
                    </div>
                    <div class="form-floating mb-3">
                        <textarea class="form-control" name="petition_reason" type="text" style="height:100px;"></textarea>
                        <label for="">Lý do:</label>
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
