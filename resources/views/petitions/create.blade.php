 <div class="modal fade" id="exampleModal_petition" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog" role="document">
        <div class="modal-content" style="width:100%;">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">Tạo yêu cầu</h3>
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
                        <div class="form-floating mb-3">
                            <input class="form-control" name="user_id" type="text" value="{{$user->id }}" readonly/>
                            <label for="">Họ và tên:</label>
                        </div>
                    <?php } ?>

                    <div class="form-floating mb-3">
                        <select class="form-control" name="petition_type" id="petition_type" >
                            <option selected disabled value>Lựa chọn</option>
                            <option value="4">Thay đổi giờ công</option>
                            <option value="1">Đi muộn về sớm</option>
                            <option value="2">Nghỉ phép</option>
                            <option value="3">Nghỉ việc</option>
                        </select>
                        <label  for="petition_type">Loại yêu cầu</label>
                    </div>
                    <div class="form-floating mb-3" style="display: none;" id="1">
                        <div class="form-floating mb-3">
                            <input class="form-control" id="date_1" name="check_date" type="date" />
                            <script>
                                document.getElementById('date_1').value = new Date().toISOString().substring(0, 10);
                            </script>
                            <label for="">Chọn ngày:</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" name="checkin" type="time" value="08:00"/>
                            <label for="">Thời gian từ:</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" name="checkout" type="time" value="17:30"/>
                            <label for="">Đến:</label>
                        </div>
                    </div>
                    <div class="form-floating mb-3" style="display: none;" id="2">
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
                                <input  class="form-control" type="date" name="check_date" id="inday" />
                                <label for="">Chọn ngày:</label>
                                <script>
                                    document.getElementById('inday').value = new Date().toISOString().substring(0, 10);
                                </script>
                            </div>
                            <div class="form-floating mb-3">
                                <input class="form-control" type="time" name="checkin" value="08:00" />
                                <label for="">Thời gian từ:</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input class="form-control" type="time" name="checkout" value="17:30" />
                                <label for="">Thời gian đến:</label>
                            </div>
                        </div>
                        <div class="form-floating mb-3" style="display: none;" id="multi_day">
                            <div class="form-floating mb-3">
                                <input class="form-control" type="time" name="checkin" value="08:00" />
                                <label for="">Thời gian từ:</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input class="form-control" type="date" name="check_date" id="multidayfrom" />
                                <label for="">Ngày:</label>
                                <script>
                                    document.getElementById('multidayfrom').value = new Date().toISOString().substring(0, 10);
                                </script>
                            </div>
                            <div class="form-floating mb-3">
                                <input class="form-control" type="time"  name="checkout" value="17:30" />
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
                            <input class="form-control" id="lay_off" type="date" name="check_date" />
                            <script>
                                document.getElementById('lay_off').value = new Date().toISOString().substring(0, 10);
                            </script>
                            <label for="">Ngày dự định nghỉ việc:</label>
                        </div>
                    </div>
                    <div class="form-floating mb-3" style="display: none;" id="4">
                        <div class="form-floating mb-3">
                            <input class="form-control" id="date_4" name="check_date" type="date" />
                            <script>
                                document.getElementById('date_4').value = new Date().toISOString().substring(0, 10);
                            </script>
                            <label for="">Ngày muốn thay đổi giờ công:</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" name="checkin" type="time" value="08:00"/>
                            <label for="">Checkin:</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" name="checkout" type="time" value="{{ Auth::user()->fullname }}"/>
                            <label for="">Checkout:</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" name="checkin" type="time" value="08:00"/>
                            <label for="">Checkin thay đổi:</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" name="checkout" type="time" value="17:30"/>
                            <label for="">Checkout thay đổi:</label>
                        </div>
                    </div>
                    <div class="form-floating mb-3">
                        <textarea class="form-control" name="reason" type="text" style="height:100px;"></textarea>
                        <label for="">Lý do:</label>
                    </div>
                    <script>
                        document.getElementById('petition_type').addEventListener("change", function (e) {
                            if (e.target.value === '1') {
                                document.getElementById('1').style.display = 'block';
                                document.getElementById('2').style.display = 'none';
                                document.getElementById('3').style.display = 'none';
                                document.getElementById('4').style.display = 'none';
                            } else if (e.target.value === '2') {
                                document.getElementById('1').style.display = 'none';
                                document.getElementById('2').style.display = 'block';
                                document.getElementById('3').style.display = 'none';
                                document.getElementById('4').style.display = 'none';
                            } else if (e.target.value === '3'){
                                document.getElementById('1').style.display = 'none';
                                document.getElementById('2').style.display = 'none';
                                document.getElementById('3').style.display = 'block';
                                document.getElementById('4').style.display = 'none';
                            } else {
                                document.getElementById('1').style.display = 'none';
                                document.getElementById('2').style.display = 'none';
                                document.getElementById('3').style.display = 'none';
                                document.getElementById('4').style.display = 'block';
                            }
                        });
                    </script>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" >Tạo yêu cầu</button>
                    </div>
                </form>
            </div>
        </div>
     </div>
 </div>
