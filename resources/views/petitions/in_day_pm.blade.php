 <div class="modal fade"  data-bs-backdrop="static" data-bs-keyboard="false"  id="exampleModal_inDayPM" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog" role="document">
        <div class="modal-content" style="width:100%;">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">Nghỉ phép nửa ngày (chiều)</h3>
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
                <form action="{{ route('petitions.store') }}" method="POST" id="InDayPm" name="InDayPm">
                    @csrf
					<div class="form-check" style="font-size:24px;">
                        <input oninput="CheckdatePM()" class="form-check-input" type="checkbox" value="1" id="flexCheckDefault">
                        <label class="form-check-label" for="flexCheckDefault">
                            <b>Đã được quản lý trực tiếp duyệt</b>
                        </label>
                    </div>
					</br>
                    <?php
                    if(Auth::user()->permission == 0) {
                    ?>

                    <div class="form-floating mb-3">
                        <input class="form-control" name="user_fullname" type="text" value="{{ Auth::user()->fullname }}" readonly/>
                        <label for="">Họ và tên:</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" name="user_id" id="usernamePM" type="text" value="{{ Auth::user()->id }}" hidden/>
                        <label for="">Mã nhân viên:</label>
                    </div>
                    <?php } else {?>

                       <div class="form-floating mb-3">
                            <select class="form-control" id="usernamePM" name="user_id">
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
							<div id="vmsgPM1" style="color:brown; margin: 10px;"></div>
                        </div>
                    <?php } ?>

                    <div class="form-floating mb-3">
                        <input class="form-control" name="petition_type" type="text" value="2" hidden/>
                        <label for="">loại yêu cầu</label>
                    </div>
                     <div class="form-floating mb-3">
                        <input class="form-control" name="type_leave" type="text" value="2" hidden/> <!-- 2 =in_day_PM -->
                        <label for="">Loại nghỉ phép</label>
                    </div>
					<div class="form-floating mb-3">
                        <select onchange="CheckdatePM()" class="form-control" id="type_paid_pm" name="type_paid">
						<option value="0">Nghỉ phép không lương</option>
						<option value="1">Nghỉ phép có lương</option>
						</select>
                        <label for="">Hình thức nghỉ phép:</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input oninput="CheckdatePM()" class="form-control" id="indaypm" name="date_from" type="date" />
                        <script>
                            document.getElementById('indaypm').value = new Date().toISOString().substring(0, 10);
                        </script>
                        <label for="">Chọn ngày xin nghỉ phép:</label>
                        <div id="vmsgPM3" style="color:rgb(204, 7, 7); margin: 10px;"></div>
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
                        <textarea class="form-control" name="petition_reason" id="petition_reasonPM" type="text" style="height:100px;"></textarea>
                        <label for="">Lý do:</label>
						<div id="vmsgPM2" style="color:brown; margin: 10px;"></div>
						
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <div class="btn_check">
                            <button type="submit" class="btn btn-primary" >Tạo yêu cầu</button>
                        </div>
                    </div>
                </form>
				<script>
                    $(".btn_check").hide();
                    $(".form-check-input").click(function() {
                        if($(this).is(":checked")) {
                            $(".btn_check").show();
                        } else {
                            $(".btn_check").hide();
                        }
                    });
					
					$(document).ready(function() {
                    $("#InDayPM").submit(function() {
                        var query1 = document.getElementById('usernamePM');
                        if (query1.value == "") {
                        $('#vmsgPM1').html("* Vui lòng chọn người tạo yêu cầu")
                        return false; 
                        }
                        return true; 
                    })
                    });
                    $(document).ready(function() {
                    $("#InDayPM").submit(function() {
                        var query2 = document.getElementById('petition_reasonPM');
                        if (query2.value == "") {
                        $('#vmsgPM2').html("* Vui lòng nhập lí do")
                        return false; 
                        }
                        return true; 
                    })
                    });
                    function CheckdatePM() {
                        var paid_pm = InDayPm.type_paid_pm;
                        var p_pm = paid_pm.selectedIndex;

                        var now_pm = new Date().getTime();
                        var dateSelect_pm = new Date(document.getElementById('indaypm').value).getTime();

                        var d_pm = (dateSelect_pm - now_pm)/1000;
                        var d_h_pm = (d_pm - (d_pm % 3600))/3600 + 1;
                        var d_m_pm = ((d_pm % 3600) - (d_pm % 3600)%60)/60 ;

                        if(d_h_pm < 48 && p_pm == 0) {
                            $('#vmsgPM3').html("Bạn đã vi phạm nội quy!!! Bạn tạo yêu cầu trước thời gian nghỉ "+d_h_pm+" giờ "+d_m_pm+" phút "
                            +" Trong nội quy là 48 giờ trước thời gian nghỉ. Nếu bạn tiếp tục bạn sẽ được tính 1 lần vi phạm nội quy.");
                        }else if(d_h_pm < 120 && p_pm == 1){
                            $('#vmsgPM3').html("Bạn đã vi phạm nội quy!!! Bạn tạo yêu cầu trước thời gian nghỉ "+d_h_pm+" giờ "+d_m_pm +" phút "
                            +" Trong nội quy là 120 giờ trước thời gian nghỉ. Nếu bạn tiếp tục bạn sẽ được tính 1 lần vi phạm nội quy.");
                        }else{
                            $('#vmsgPM3').html('');
                        }
                    }
                </script>
            </div>
        </div>
     </div>
 </div>
