 <div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="exampleModal_goLate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                <form action="{{ route('petitions.store') }}" method="POST" id="GoLate">
                    @csrf
					<div class="form-check" style="font-size:24px;">
                        <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault">
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
                        <input class="form-control" name="user_id" id="usernameGoLate" type="text" value="{{ Auth::user()->id }}" hidden/>
                        <label for="">Mã nhân viên:</label>
                    </div>
                    <?php } else {?>

                        <div class="form-floating mb-3">
                            <select class="form-control" id="usernameGoLate" name="user_id">
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
							<div id="vmsgGoLate1" style="color:brown; margin: 10px;"></div>
                        </div>
                        
                    <?php } ?>

                    <div class="form-floating mb-3">
                        <input class="form-control" name="petition_type" type="text" value="1" hidden/>
                        <label for="">Loại yêu cầu</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" id="goLate" name="date_from" type="date" />
                        <script>
                            document.getElementById('goLate').value = new Date().toISOString().substring(0, 10);
                        </script>
                        <label for="">Chọn ngày:</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" name="time_from" type="time" value="08:00"/>
                        <label for="">Thời gian từ:</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" name="time_to" type="time" value="09:00"/>
                        <label for="">Đến:</label>
                    </div>
                    <div class="form-floating mb-3">
                        <textarea class="form-control" name="petition_reason" id="petition_reasonGoLate" type="text" style="height:100px;"></textarea>
                        <label for="">Lý do:</label>
						<div id="vmsgGoLate2" style="color:brown; margin: 10px;"></div>
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
                    $("#GoLate").submit(function() {
                        var query1 = document.getElementById('usernameGoLate');
                        if (query1.value == "") {
                        $('#vmsgGoLate1').html("* Vui lòng chọn người tạo yêu cầu")
                        return false; 
                        }
                        return true; 
                    })
                    });
                    $(document).ready(function() {
                    $("#GoLate").submit(function() {
                        var query2 = document.getElementById('petition_reasonGoLate');
                        if (query2.value == "") {
                        $('#vmsgGoLate2').html("* Vui lòng nhập lí do")
                        return false; 
                        }
                        return true; 
                    })
                    });
                </script>
            </div>
        </div>
     </div>
 </div>
