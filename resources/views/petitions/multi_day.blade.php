 <div class="modal fade" id="exampleModal_multiDay" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog" role="document">
        <div class="modal-content" style="width:100%;">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">Nghỉ phép nhiều ngày</h3>
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
                        <input class="form-control" name="user_id" type="text" value="{{ Auth::user()->id }}" hidden/>
                        <label for="">Mã nhân viên:</label>
                    </div>
                    <?php } else {?>

                       <div class="form-floating mb-3">
                            <select class="form-control" id="user_fullname" name="user_id">
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
                        </div>
                    <?php } ?>

                    <div class="form-floating mb-3">
                        <input class="form-control" name="petition_type" type="text" value="2" hidden/>
                        <label for="">loại yêu cầu</label>
                    </div>
                     <div class="form-floating mb-3">
                        <input class="form-control" name="type_leave" type="text" value="4" hidden/>
                        <label for="">Loại nghỉ phép</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select class="form-control" name="type_paid">
                            <option value="0">Nghỉ không lương</option>
                            <option value="1">Nghỉ có lương</option>
                        </select>
                        <label for="">Hình thức nghỉ</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select class="form-control" name="type_law">
                            <option value="1">Nghỉ không lương</option>
                            <option value="2">Nghỉ có lương</option>
                            <option value="3">Nghỉ không lương</option>
                            <option value="4">Nghỉ có lương</option>
                            <option value="5">Nghỉ không lương</option>
                            <option value="6">Nghỉ có lương</option>
                        </select>
                        <label for="">Hình thức nghỉ</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" id="multidayfrom" name="date_from" type="date" />
                        <script>
                            document.getElementById('multidayfrom').value = new Date().toISOString().substring(0, 10);
                        </script>
                        <label for="">Từ ngày:</label>
                    </div>
                     <div class="form-floating mb-3">
                        <input class="form-control" id="multidayto" name="date_to" type="date" />
                        <script>
                            document.getElementById('multidayto').value = new Date().toISOString().substring(0, 10);
                        </script>
                        <label for="">Đến ngày:</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" name="time_from" type="time" value="08:00" hidden/>
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
