                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <form method="POST" action="{{ route('petitions.store') }}" >
                            <div class="modal-dialog" role="document">
                                <div class="modal-content" style="width:100%;">
                                    <div class="modal-header">
                                        <h3 class="modal-title" id="exampleModalLabel">Tạo yêu cầu</h3>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="user_fullname" name="user_fullname" type="text" placeholder="..." />
                                            <label for="user_fullname">Họ và tên:</label>
                                        </div>  
                                        <div class="form-floating mb-3">
                                            <select class="form-control" name="petition_type" id="petition_type" >
                                                <option selected disabled value>Lựa chọn</option>
                                                <option value="1">Đi muộn về sớm</option>
                                                <option value="2">Nghỉ phép</option>
                                                <option value="3">Nghỉ việc</option>
                                            </select>
                                            <label  for="petition_type">Loại yêu cầu</label>
                                        </div>
                                        <div class="form-floating mb-3" style="display: none;" id="1">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="date_1" name="date_from" type="date" />
                                                <script>
                                                    document.getElementById('date_1').value = new Date().toISOString().substring(0, 10);
                                                </script>
                                                <label for="">Chọn ngày:</label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input class="form-control" name="time_from" type="time" value="08:00"/>
                                                <label for="">Thời gian từ:</label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input class="form-control" name="time_to" type="time" value="17:30"/>
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
                                                    <input  class="form-control" type="date" name="date_from" id="inday" />
                                                    <label for="">Chọn ngày:</label>
                                                    <script>
                                                        document.getElementById('inday').value = new Date().toISOString().substring(0, 10);
                                                    </script>
                                                </div>
                                                <div class="form-floating mb-3">
                                                    <input class="form-control" type="time" name="time_from" value="08:00" />
                                                    <label for="">Thời gian từ:</label>
                                                </div>
                                                <div class="form-floating mb-3">
                                                    <input class="form-control" type="time" name="time_to" value="17:30" />
                                                    <label for="">Thời gian đến:</label>
                                                </div>
                                            </div>
                                            <div class="form-floating mb-3" style="display: none;" id="multi_day">
                                                <div class="form-floating mb-3">
                                                    <input class="form-control" type="time" name="time_from" value="08:00" />
                                                    <label for="">Thời gian từ:</label>
                                                </div>
                                                <div class="form-floating mb-3">
                                                    <input class="form-control" type="date" name="date_from" id="multidayfrom" />
                                                    <label for="">Ngày:</label>
                                                    <script>
                                                        document.getElementById('multidayfrom').value = new Date().toISOString().substring(0, 10);
                                                    </script>
                                                </div>
                                                <div class="form-floating mb-3">
                                                    <input class="form-control" type="time"  name="time_to" value="17:30" />
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
                                                <input class="form-control" id="lay_off" type="date" name="date_from" />
                                                <script>
                                                    document.getElementById('lay_off').value = new Date().toISOString().substring(0, 10);
                                                </script>
                                                <label for="">Ngày dự định nghỉ việc:</label>
                                            </div>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <textarea class="form-control" name="petition_reason" type="text" style="height:100px;"></textarea>
                                            <label for="">Lý do:</label>
                                        </div>
                                        <script>
                                            document.getElementById('petition_type').addEventListener("change", function (e) {
                                                if (e.target.value === '2') {
                                                    document.getElementById('1').style.display = 'none';
                                                    document.getElementById('2').style.display = 'block';
                                                    document.getElementById('3').style.display = 'none';
                                                } else if (e.target.value === '1') {
                                                    document.getElementById('1').style.display = 'block';
                                                     document.getElementById('2').style.display = 'none';
                                                    document.getElementById('3').style.display = 'none';
                                                } else {
                                                    document.getElementById('1').style.display = 'none';
                                                     document.getElementById('2').style.display = 'none';
                                                    document.getElementById('3').style.display = 'block';
                                                }
                                            });
                                        </script>
                                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                            <a class="btn btn-secondary" href="{{ route('petitions.index') }}" style="float:left;"> Quay lại</a>
                                            <button type="submit" class="btn btn-primary" style="float:right;">Tạo yêu cầu</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div> 