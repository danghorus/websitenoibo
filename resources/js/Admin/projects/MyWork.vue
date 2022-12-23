<template>
    <div>
        <div class="mt-4" style="background-color:white">
            <nav class="navbar navbar-expand-lg" style="margin-top:-20px; float:right;">
                <ul class="navbar-nav mr-auto" style="font-size:16px;" >
                    <li class="nav-item">
                        <div class="form-group p-2">
                            <label for="project_description" style="font-size:12px;">Nhập tên công việc</label>
                            <input @change="changeOption()" class="form-control"
                                style="margin-top:3px;width:300px;height:33px;font-size:14px" type="text" placeholder="Tên công việc"
                                v-model="search">
                        </div>
                    </li>
                    <li class="nav-item" v-if="option2 != 2 && option2 != 3" style="width:270px;">
                        <div class="form-group p-2">
                            <label for="project_description" style="font-size:12px;">Chọn khoảng thời gian</label>
                            <date-picker style="margin-top:3px; width: 100%;" v-model="dateRange" type="date" range
                                placeholder="Vui lòng chọn khoảng thời gian" @change="changeOption()">
                            </date-picker>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="form-group p-2">
                            <label for="project_description" style="font-size:12px">Theo dự án</label>
                            <select  class="form-select" @change="changeOption()" v-model="project" style="width:170px">
                                <option value="0" selected="selected">Tất cả</option>
                                <option v-for="(project, index) in projects" :key="index" :value="project.id">{{project.project_name}}</option>
                            </select>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="form-group p-2">
                            <label for="project_description" style="font-size:12px">Theo trạng thái</label>
                            <select @change="changeOption()" class="form-select" v-model="option" style="width:170px">
                                <option value="10">Tất cả</option>
                                <option value="0">Quá hạn</option>
                                <option value="1">Đang chờ </option>
                                <option value="2">Đang làm</option>
                                <option value="3">Tạm dừng</option>
                                <option value="5">Chờ feedback</option>
                                <option value="6">Làm lại</option>
								<option value="4">Hoàn thành</option>
                            </select>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="form-group p-2" style="margin:30px 0px 0px 0px;">
                            <button class="btn btn-outline-secondary "><a v-bind:href="'/list_work'">Việc bộ phận</a></button>
                        </div>
                    </li> &ensp;
                    <li class="nav-item">
                        <button class="btn btn-outline-info" @click="ShowInfoMyWork()" type="button" data-toggle="collapse"
                            data-target="#collapseExample" style="margin-top: 38px"> Thông tin
                        </button>
                        <div class="col-lg">
                            <div class="collapse info-my-work" id="collapseExample" v-if="showInfoMyWork">
                                <div>
                                    <table style="width:100%; border: 0px" class="my-work">
                                        <div class="float-left" style="margin-top: 15px; margin-left: 5px;">
                                            <button type="submit" class="btn" @click="ShowInfoMyWork()"><h4>X</h4></button>
                                        </div>
                                        <h4 style="margin: 20px 0px 20px 130px;">Thông tin làm việc</h4>
                                        <tr v-if="summary.total > 0" style="color:green;">
                                            <td><b>Tổng số công việc của bạn</b></td>
                                            <td>:</td>
                                            <td>{{ summary.total }}</td>
                                        </tr>
                                        <tr v-else >
                                            <td>Tổng số công việc của bạn</td>
                                            <td>:</td>
                                            <td>{{ summary.total }}</td>
                                        </tr>
                                        <tr v-if="summary.total_wait > 0" style="color:green;">
                                            <td><b>Đang chờ</b></td>
                                            <td>:</td>
                                            <td>{{ summary.total_wait }}</td>
                                        </tr>
                                        <tr v-else>
                                            <td>Đang chờ</td>
                                            <td>:</td>
                                            <td>{{ summary.total_wait }}</td>
                                        </tr>
                                        <tr v-if="summary.total_processing > 0" style="color:green;">
                                            <td><b>Đang làm</b></td>
                                            <td>:</td>
                                            <td>{{ summary.total_processing }}</td>
                                        </tr>
                                        <tr v-else>
                                            <td>Đang làm</td>
                                            <td>:</td>
                                            <td>{{ summary.total_processing }}</td>
                                        </tr>
                                        <tr v-if="summary.total_pause > 0" style="color:green;">
                                            <td><b>Tạm dừng</b></td>
                                            <td>:</td>
                                            <td>{{ summary.total_pause }}</td>
                                        </tr>
                                        <tr v-else>
                                            <td>Tạm dừng</td>
                                            <td>:</td>
                                            <td>{{ summary.total_pause }}</td>
                                        </tr>
                                        <tr v-if="summary.total_complete > 0" style="color:green;">
                                            <td><b>Hoàn thành</b></td>
                                            <td>:</td>
                                            <td>{{ summary.total_complete }}</td>
                                        </tr>
                                        <tr v-else>
                                            <td>Hoàn thành</td>
                                            <td>:</td>
                                            <td>{{ summary.total_complete }}</td>
                                        </tr>
                                        <tr v-if="summary.total_wait_fb > 0" style="color:green;">
                                            <td><b>Chờ feedback</b></td>
                                            <td>:</td>
                                            <td>{{ summary.total_wait_fb }}</td>
                                        </tr>
                                        <tr v-else>
                                            <td>Chờ feedback</td>
                                            <td>:</td>
                                            <td>{{ summary.total_wait_fb }}</td>
                                        </tr>
                                        <tr v-if="summary.total_again > 0" style="color:green;">
                                            <td><b>Làm lại</b></td>
                                            <td>:</td>
                                            <td>{{ summary.total_again }}</td>
                                        </tr>
                                        <tr v-else>
                                            <td>Làm lại</td>
                                            <td>:</td>
                                            <td>{{ summary.total_again }}</td>
                                        </tr>
                                        <tr v-if="summary.total_slow > 0" style="color:green;">
                                            <td><b>Quá hạn</b></td>
                                            <td>:</td>
                                            <td>{{ summary.total_slow }}</td>
                                        </tr>
                                        <tr v-else>
                                            <td>Quá hạn</td>
                                            <td>:</td>
                                            <td>{{ summary.total_slow }}</td>
                                        </tr>
                                        <tr v-if="summary.totalTime > 0" style="color:green;">
                                            <td><b>Thời gian làm việc dự kiến trong ngày</b></td>
                                            <td>:</td>
                                            <td>{{ summary.totalTime }}</td>
                                        </tr>
                                        <tr v-else>
                                            <td>Thời gian làm việc dự kiến trong ngày</td>
                                            <td>:</td>
                                            <td>{{ summary.totalTime }}</td>
                                        </tr>
                                        <tr v-if="summary.totalRealtime > 0" style="color:green;">
                                            <td><b>Thời gian làm việc thực tế trong ngày</b></td>
                                            <td>:</td>
                                            <td>{{ summary.totalRealtime }}</td>
                                        </tr>
                                        <tr v-else>
                                            <td>Thời gian làm việc thực tế trong ngày</td>
                                            <td>:</td>
                                            <td>{{ summary.totalRealtime }}</td>
                                        </tr>
                                        <tr v-if="summary.totalWeight > 0" style="color:green;">
                                            <td><b>Tổng trọng số</b></td>
                                            <td>:</td>
                                            <td>{{ summary.totalWeight }}</td>
                                        </tr>
                                        <tr v-else>
                                            <td>Tổng trọng số</td>
                                            <td>:</td>
                                            <td>{{ summary.totalWeight }}</td>
                                        </tr>
                                        <tr v-if="summary.totalWeightComplete > 0" style="color:green;">
                                            <td><b>Trọng số đã tích luỹ được</b></td>
                                            <td>:</td>
                                            <td>{{ summary.totalWeightComplete }}</td>
                                        </tr>
                                        <tr v-else>
                                            <td>Trọng số đã tích luỹ được</td>
                                            <td>:</td>
                                            <td>{{ summary.totalWeightComplete }}</td>
                                        </tr>

                                    </table>
                                </div>
                                <div class="float-right p-2">
                                    <button type="submit" class="btn btn-secondary p-2" @click="ShowInfoMyWork()">Đóng</button>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </nav>
            <h4 style=" margin: -30px 0px 0px 24px">Danh sách công việc</h4>
            <select @change="changeOption2()" class="form-select col-lg-2"
                    style="position: absolute; left: 25px; top: 105px; width:220px; height:34px;" v-model="option2">
				<option value="1">Tất cả</option>
                <option value="2">Việc hôm nay</option>
                <option value="3">Việc hôm qua</option>
                <option value="4">Việc tuần này</option>
                <option value="5">Việc tuần trước</option>


            </select>&emsp;
            <p>
                <button @click="NewTaskToday()" v-if="option2 == 1 || option2 == 2 || option2 == 4" class="btn btn-success btn-sm" 
                style="height:35px; font-size:15px; margin: -40px 0px 0px 300px;">Thêm mới</button>
            </p>
             <p>
                <button @click="NewTaskYesterday()" v-if="option2 == 3" class="btn btn-success btn-sm" 
                style="height:35px; font-size:15px; margin: -72px 0px 0px 300px;">Thêm mới</button>
            </p>
            <p>
                <button @click="NewTaskLastWeek()" v-if="option2 == 5" class="btn btn-success btn-sm"
                    style="height:35px; font-size:15px; margin: -72px 0px 0px 300px;">Thêm mới</button>
            </p>
            <Paginate style="margin: 0px 0px 0px 10px" v-model="paginate" :pagechange="onPageChange"></Paginate>
            <table class="table-responsive table-hover"
                style="width:99%; margin: 0px 0px 0px 10px">
                <thead class="point-table-head">
                    <tr style="text-align: center;">
                        <th scope="col">No</th>
                        <th scope="col" width="700px">Work name</th>
                        <th scope="col" width="150px">Type</th>
                        <th scope="col" width="10%">Project</th>
                        <!--<th scope="col" width="10%">Công việc cha</th>-->
                        <th scope="col" width="7%">Department</th>
                        <th scope="col" width="6%">Begin <input type="checkbox" @change="changeOption()" v-model="beginSort" ></th>
                        <th scope="col" width="3%">Estimated(h)</th>
                        <th scope="col" width="6%">End</th>
                        <th scope="col" width="3%">Actual(h)</th>
						<th scope="col" width="5%">Progress</th>
                        <th scope="col" width="150px">Status</th>
                        <th scope="col" width="100px">Manipulation</th>
                    </tr>
                </thead>
                <tbody v-for="(item, index) in list" :key="item.id" style="text-align:center;">
                    <tr>
                        <td>{{index + 1 }}</td>
                        <td scope="row" style="text-align:left;">
                            <div style="display: flex; font-size:12px;">
                                <textarea class="form-control" v-if="item.task_name == 'Click để thay đổi nội dung'"
                                    style=" font-weight: bold; font-size:16px;"
                                    @change="changeTaskName($event, item.id)" v-model="item.task_name">
                                </textarea>
                                <textarea class="form-control" v-else
                                    @change="changeTaskName($event, item.id)" v-model="item.task_name">
                                </textarea>
                                &ensp;
                                <p @click="showModalEditTask(item.id)">
                                    <i class="fas fa-info-circle" style="font-size:16px; margin-top: 1.5rem; cursor: pointer" />
                                </p>
                            </div>
                        </td>
                        
                        <td>
                            <select class="form-select" style="height:34px" aria-label=".form-select-sm example"
                                @click="getStickerByDepartment(item.task_department)"
                                @change="changeSticker($event, item.id)" v-model="item.task_sticker">
                                <option v-for="(sticker, index) in stickers" :key="index" :value="sticker.sticker_name">{{sticker.sticker_name}}
                                </option>
                            </select>
                        </td>
                        <td style="text-align:left;">
                            <select class="form-select" style="height:34px;" @change="changeProject($event, item.id)" v-model="item.project_id">
                                <option value="1" disabled>Chọn dự án</option>
                                <option v-for="(project, index) in projects" :key="index" :value="project.id">{{project.project_name}}</option>
                        </select>
                        </td>
                        <!--<td>
                            <treeselect
                                :options="list"
                                :load-options="loadOptions"
                                loadingText="Loading..."
                                v-model="item.task_parent"
                            />
                        </td>-->
                        <td style="text-align:left;">
                            <select class="form-select" style="height:34px;" aria-label=".form-select-sm example"
                                @change="changeDepartment($event, item.id)" v-model="item.task_department">
                                <option value="null" disabled >Lựa chọn</option>
                                <option value="2"> Dev</option>
                                <option value="3"> Game Design</option>
                                <option value="4"> Art</option>
                                <option value="5"> Tester</option>
                                <option value="11"> Marketing</option>
                            </select>
                        </td>
                         <td>
                            <DatePicker 
                                style="width: 120px;" 
                                v-model="item.start_time" 
                                value-type="format" 
                                type="date"
                                placeholder="Select time" 
                                @change="changeStartTime($event, item.id)">
                            </DatePicker>
                        </td>
                        <td style=" text-align:right;">
                            <input class="form-control"
                                style="width:100%;height:34px; text-align:right;" 
                                @change="changeTime($event, item.id)"
                                v-model="item.time"
                            >
                        </td>
                        <td>
                            <DatePicker 
                                style="width: 120px" 
                                v-model="item.end_time" 
                                value-type="format" 
                                type="date" 
                                placeholder="Select time"
                                @change="changeEndTime($event, item.id)">
                            </DatePicker>
                        </td>
                        <td style=" text-align:right;">
                            <input class="form-control"
                                style="width:100%; height:34px; text-align:right;" 
                                @change="changeRealTime($event, item.id)"
                                v-model="item.real_time">
                        </td>
                        <td>
                            <input class="form-control"
                            type="number" 
                            style="width: 100%;height:34px;" 
                            min="0" max="100" 
                            @change="changeProgress($event, item.id)" 
                            v-model="item.progress">
                        </td>
                        <td v-if="item.status == 0" style="background-color:black">
                            <select class="form-select" style="height:34px" aria-label=".form-select-sm example"
                                @change="changeStatus($event, item.id)" v-model="item.status">
                                <option value="0" v-if="item.status == 0" :disabled="item.status == 0"><p>Đang Chờ</p></option>
                                <option value="1" v-if="item.status == 1">Đang Chờ</option>
                                <option value="2" :disabled="item.status == 2 || item.status == 4">Đang tiến hành</option>
                                <option value="3" style="color: orange"
                                    :disabled="item.status == 3 || item.status == 4 || item.status == 5 || item.status == 6">Tạm dừng</option>
                                <option value="5" :disabled="item.status == 4 || item.status == 5 || item.status == 6">Chờ
                                    feedback</option>
                                <option value="6" v-if="item.status == 6">Làm lại</option>
                                <option value="4"  v-if="(currentUser.permission == 1 || currentUser.permission == 2 || currentUser.permission == 3)"> Hoàn thành</option>
                            </select>
                        </td>
                        <td v-else-if="item.status == 1" style="background-color:white">
                            <select class="form-select" style="height:34px" aria-label=".form-select-sm example"
                                @change="changeStatus($event, item.id)" v-model="item.status">
                                <option value="0" v-if="item.status == 0" :disabled="item.status == 0">
                                    <p>Đang Chờ</p>
                                </option>
                                <option value="1" v-if="item.status == 1">Đang Chờ</option>
                                <option value="2" :disabled="item.status == 2 || item.status == 4">Đang tiến hành</option>
                                <option value="3" style="color: orange"
                                    :disabled="item.status == 3 || item.status == 4 || item.status == 5 || item.status == 6">Tạm dừng</option>
                                <option value="5" :disabled="item.status == 4 || item.status == 5 || item.status == 6">Chờ
                                    feedback</option>
                                <option value="6" v-if="item.status == 6">Làm lại</option>
                                <option value="4" v-if="(currentUser.permission == 1 || currentUser.permission == 2 || currentUser.permission == 3)"> Hoàn thành</option>
                            </select>
                        </td>
                        <td v-else-if="item.status == 2" style="background-color:#008080">
                            <select class="form-select" style="height:34px;  border: none;" aria-label=".form-select-sm example"
                                @change="changeStatus($event, item.id)" v-model="item.status">
                                <option value="0" v-if="item.status == 1" :disabled="item.status == 0">
                                    <p>Đang Chờ</p>
                                </option>
                                <option value="1" v-if="item.status == 1">Đang Chờ</option>
                                <option style="background-color:#008080" value="2" :disabled="item.status == 2 || item.status == 4">Đang tiến hành</option>
                                <option value="3" style="color: orange"
                                    :disabled="item.status == 3 || item.status == 4 || item.status == 5 || item.status == 6">Tạm dừng</option>
                                <option value="5" :disabled="item.status == 4 || item.status == 5 || item.status == 6">Chờ
                                    feedback</option>
                                <option value="6" v-if="item.status == 6">Làm lại</option>
                                <option value="4" v-if="(currentUser.permission == 1 || currentUser.permission == 2 || currentUser.permission == 3)"> Hoàn thành</option>
                            </select>
                        </td>
                        <td v-else-if="item.status == 3" style="background-color:orange">
                            <select class="form-select" style="height:34px" aria-label=".form-select-sm example"
                                @change="changeStatus($event, item.id)" v-model="item.status">
                                <option value="0" v-if="item.status == 0" :disabled="item.status == 0">
                                    <p>Đang Chờ</p>
                                </option>
                                <option value="1" v-if="item.status == 1">Đang Chờ</option>
                                <option value="2" :disabled="item.status == 2 || item.status == 4">Đang tiến hành</option>
                                <option value="3" style="color: orange"
                                    :disabled="item.status == 3 || item.status == 4 || item.status == 5 || item.status == 6">Tạm dừng</option>
                                <option value="5" :disabled="item.status == 4 || item.status == 5 || item.status == 6">Chờ
                                    feedback</option>
                                <option value="6" v-if="item.status == 6">Làm lại</option>
                                <option value="4" v-if="(currentUser.permission == 1 || currentUser.permission == 2 || currentUser.permission == 3)"> Hoàn thành</option>
                            </select>
                        </td>
                        <td v-else-if="item.status == 4" style="background-color:green">
                            <select class="form-select" style="height:34px; border:0px;" aria-label=".form-select-sm example"
                                @change="changeStatus($event, item.id)" v-model="item.status">
                                <option value="0" v-if="(currentUser.permission == 1 || currentUser.permission == 2 || currentUser.permission == 3)"><p>Đang Chờ</p></option>
                                <option value="1" v-if="(currentUser.permission == 1 || currentUser.permission == 2 || currentUser.permission == 3)">Đang Chờ</option>
                                <option value="2" v-if="(currentUser.permission == 1 || currentUser.permission == 2 || currentUser.permission == 3)">Đang tiến hành</option>
                                <option value="3" v-if="(currentUser.permission == 1 || currentUser.permission == 2 || currentUser.permission == 3)">Tạm dừng</option>
                                <option value="5" v-if="(currentUser.permission == 1 || currentUser.permission == 2 || currentUser.permission == 3)">Chờ feedback</option>
                                <option value="6" v-if="(currentUser.permission == 1 || currentUser.permission == 2 || currentUser.permission == 3)">Làm lại</option>
                                <option value="4" :disabled="item.status == 4"> Hoàn thành</option>
                            </select>
                        </td>
                        <td v-else-if="item.status == 5" style="background-color:#ff8080">
                            <select class="form-select" style="height:34px" aria-label=".form-select-sm example"
                                @change="changeStatus($event, item.id)" v-model="item.status">
                                <option value="0" v-if="item.status == 0" :disabled="item.status == 0">
                                    <p>Đang Chờ</p>
                                </option>
                                <option value="1" v-if="item.status == 1">Đang Chờ</option>
                                <option value="2" :disabled="item.status == 2 || item.status == 4">Đang tiến hành</option>
                                <option value="3" style="color: orange"
                                    :disabled="item.status == 3 || item.status == 4 || item.status == 5 || item.status == 6">Tạm dừng</option>
                                <option value="5" :disabled="item.status == 4 || item.status == 5 || item.status == 6">Chờ
                                    feedback</option>
                                <option value="6" v-if="item.status == 6">Làm lại</option>
                                <option value="4"  v-if="(currentUser.permission == 1 || currentUser.permission == 2 || currentUser.permission == 3)"> Hoàn thành</option>
                            </select>
                        </td>
                        <td v-else-if="item.status == 6" style="background-color:#ff0000">
                            <select class="form-select" style="height:34px" aria-label=".form-select-sm example"
                                @change="changeStatus($event, item.id)" v-model="item.status">
                                <option value="0" v-if="item.status == 0" :disabled="item.status == 0">
                                    <p>Đang Chờ</p>
                                </option>
                                <option value="1" v-if="item.status == 1">Đang Chờ</option>
                                <option value="2" :disabled="item.status == 2 || item.status == 4">Đang tiến hành</option>
                                <option value="3" style="color: orange"
                                    :disabled="item.status == 3 || item.status == 4 || item.status == 5 || item.status == 6">Tạm dừng</option>
                                <option value="5" :disabled="item.status == 4 || item.status == 5 || item.status == 6">Chờ
                                    feedback</option>
                                <option value="6" v-if="item.status == 6">Làm lại</option>
                                <option value="4"  v-if="(currentUser.permission == 1 || currentUser.permission == 2 || currentUser.permission == 3)"> Hoàn thành</option>
                            </select>
                        </td>
                        <td class="status">
                            <button style="height: 34px;" class="btn btn-danger" @click="deleteTask($event, item.id)">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </button>
                            <button style="height: 34px;"  @click="copyMyWorks(item.id)" class="btn btn-success">
                                <i class="fa fa-copy" aria-hidden="true"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <Paginate style="margin: 0px 0px 0px 10px" v-model="paginate" :pagechange="onPageChange"></Paginate>
        </div>
        <div ref="modalInfoTask" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document" style=" max-width: 62%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Nhập thông tin công việc</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            @click="closeModalEditTask()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <CreateTask_Parent v-if="showModalEdit" :projects="projects" :taskId="taskEditId" @handleGetTasks="handleGetTasks()" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import {$get, $post} from "../../ultis";
import Paginate from "../../components/Paginate";
import Multiselect from 'vue-multiselect';
import Treeselect from '@riophae/vue-treeselect';
import DatePicker from 'vue2-datepicker';
import CreateTask_Parent from './CreateTask_Parent.vue';
import CreateTask from './CreateTask.vue';

export default {
    name: "MyWork",
    el: '#infoMyWork',
    components: { CreateTask, CreateTask_Parent, Multiselect, Treeselect, DatePicker, Paginate },
    props: ['search', 'paginate', 'users', 'groupUsers', 'priorities', 'stickers', 'projects', 'status', 'list', 'task_sticker', 'currentUser'],
    data() {
        return {
            sort: '',
            dateRange: '',
            search:'',
            paginate: [],
            option: 10,
            option2: 2,
            toggle: false,
            show: false,
            list: [],
            list_task: [],
            showModal: false,
            showModalEdit: false,
            showMyWork: false,
            showFilter: false,
            showInfoMyWork: false,
            projects: [],
            project_id: '',
            task_parent: '',
            project: 0,
            summary: '',
            startTime: '',
            endTime: '',
            task_performer: '',
            currentUser: '',
           
        }
    },
    created() {
        this.getAllUser();
        this.getMyWorks();
        this.getProjects();
        this.getAllPriority();
        this.getAllSticker();
        this.getAllTasks();
    },
    methods: {
        changeOption2() {

            this.option = 10;
            this.search = '';
            this.project = 0;
            this.dateRange = '';
            this.startTime = '';
            this.endTime = '';

            this.getMyWorks();
        },
        changeOption() {
            this.getMyWorks();
        },
        ClearOption() {
            this.option == null;
        },
        async loadOptions({ action, parentNode, callback }) {
            const res = await $get('/tasks/get_all', { project_id: this.projectId, task_parent: parentNode.id })

            if (res.code == 200) {
                parentNode.children = res.data;
            }
        },
        async getTaskByProject(projectId, taskId) {
            const res = await $get('/tasks/get_all', { project_id: projectId, task_id: taskId ?? 0 })

            if (res.code == 200) {
                this.tasks = res.data;
                if (taskId) {
                    this.task.task_parent = taskId;
                }
            }
        },
        changeTaskParent(e) {
            this.task.task_parent = e;
        },
        async getAllUser() {
            const res = await $get('/user/all_user');
            if (res.code == 200) {
                this.users = res.data;
            }
        },
        async getAllPriority() {
            const res = await $get('/priorities/get_all');
            if (res.code == 200) {
                this.priorities = res.data;
            }
        },
        async getAllSticker() {
            const res = await $get('/stickers/get_all');
            if (res.code == 200) {
                this.stickers = res.data;
            }
        },
        async getStickerByDepartment(task_department) {
            const res = await $get('/stickers/get_all_myWork', { task_department: task_department })

            if (res.code == 200) {
                this.stickers = res.data;
            }
        },
        async getAllTasks() {
            
            const res = await $get('/tasks/all_task');

            if (res.code == 200) {
                this.list_task = res.data;
            }
        },
        handleGetTasks() {
            this.closeModalEditTask();
        },

        showModalEditTask(id) {
            this.showModalEdit = true;
            $(this.$refs.modalInfoTask).modal('show');
            this.taskEditId = id;
        },
        closeModalEditTask() {
            $(this.$refs.modalInfoTask).modal('hide');
            this.showModalEdit = false;
            this.taskEditId = 0;
        },

        handleShowFilter() {
            this.showFilter = !this.showFilter
        },
        ShowInfoMyWork() {
            this.showInfoMyWork = !this.showInfoMyWork;
            this.getMyWorks();
        },
        async getMyWorks(page) {
            console.log(page, 'page');
            let params = {
                page: page ?? 1
            };

            if (this.option && this.option != 10) {
                params.status = this.option;
            }

            if (this.beginSort) {
                params.sort = 'ASC';
            } else {
                params.sort = 'DESC';
            }

            if (this.option2) {
                params.status2 = this.option2;
            }

            if (this.project && this.project > 0) {
                params.project_id = this.project;
            }

            if (this.dateRange) {
                params.start_time = this.dateRange.length > 1 ? moment(this.dateRange[0]).format('YYYY-MM-DD') : moment().startOf('month').format('YYYY-MM-DD');
                params.end_time = this.dateRange.length > 1 ? moment(this.dateRange[1]).format('YYYY-MM-DD') : moment().endOf('month').format('YYYY-MM-DD');
            }
    
            if (this.search) {
                params.search = this.search || '';
            }

            const res = await $get('/my-tasks', params);

            if (res.code == 200) {
                this.list = res.tasks;
                this.paginate = res.paginate;
                this.summary = res.summary;
                this.currentUser = res.currentUser;
            }
        },
        onPageChange(page) {
            this.getMyWorks(page);
        },
        async changeStatus(e, taskId) {
            const res = await $post(`/tasks/change-status/${taskId}`, {status: e.target.value});

            if (res.code == 200) {
                toastr.success(res.message);   
            }
            
        },
		async changeProgress(e, taskId) {
            const res = await $post(`/tasks/change-progress/${taskId}`, {progress: e.target.value});

            if (res.code == 200) {
                toastr.success(res.message);
                        
            }
        },

        async getProjects() {
            const res = await $get('/projects/get_all');

            this.projects = res.projects
        },
        async NewTask() {
            const res = await $get('/tasks/new_task');

            if (res.code == 200) {
                toastr.success('Thêm mới thành công');
                this.getMyWorks();
            }
        },
        async NewTaskToday() {
            const res = await $get('/tasks/new_task_today');

            if (res.code == 200) {
                toastr.success('Thêm mới thành công');
                this.getMyWorks();
            }
        },
        async NewTaskYesterday() {
            const res = await $get('/tasks/new_task_yesterday');

            if (res.code == 200) {
                toastr.success('Thêm mới thành công');
                this.getMyWorks();
            }
        },
        async NewTaskLastWeek() {
            const res = await $get('/tasks/new_task_last_week');

            if (res.code == 200) {
                toastr.success('Thêm mới thành công');
                this.getMyWorks();
            }
        },
        async changeStartTime(e, taskId) {
            const res = await $post(`/tasks/change-start_time/${taskId}`, { start_time: e });

            if (res.code == 200) {
                toastr.success(res.message);
                             
            }
        },
        async changeEndTime(e, taskId) {
            const res = await $post(`/tasks/change-end_time/${taskId}`, { end_time: e});

            if (res.code == 200) {
                toastr.success(res.message);
                               
            }
        },
        async changeTime(e, taskId) {
            const res = await $post(`/tasks/change-time/${taskId}`, { time: e.target.value });

            if (res.code == 200) {
                toastr.success(res.message);
                this.summary = res.summary;
                
            }
        },
        async changeRealTime(e, taskId) {
            const res = await $post(`/tasks/change-real_time/${taskId}`, { real_time: e.target.value });

            if (res.code == 200) {
                toastr.success(res.message);
                this.summary = res.summary;

            }
        },
        async changeTaskName(e, taskId) {
            const res = await $post(`/tasks/change-task_name/${taskId}`, { task_name: e.target.value });

            if (res.code == 200) {
                toastr.success(res.message);
                
            }
        },
        async changeDescription(e, taskId) {
            const res = await $post(`/tasks/change-description/${taskId}`, { description: e.target.value });

            if (res.code == 200) {
                toastr.success(res.message);
                     
            }
        },
        async changeProject(e, taskId) {
            const res = await $post(`/tasks/change-project/${taskId}`, { project_id: e.target.value });

            if (res.code == 200) {
                toastr.success(res.message);
                    
            }
        },
        async changeSticker(e, taskId) {

            const res = await $post(`/tasks/change-sticker/${taskId}`, { task_sticker: e.target.value });

            if (res.code == 200) {
                toastr.success(res.message);
            }
        },
        async changePriority(e, taskId) {
            const res = await $post(`/tasks/change-priority/${taskId}`, { task_priority: e.target.value });

            if (res.code == 200) {
                toastr.success(res.message);
            }
        },
        async changeDepartment(e, taskId) {
            const res = await $post(`/tasks/change-department/${taskId}`, { task_department: e.target.value });

            if (res.code == 200) {
                toastr.success(res.message);
                
                
            }
        },
        async changeParent(e, taskId) {
            const res = await $post(`/tasks/change-parent/${taskId}`, { task_parent: e.target.value });

            if (res.code == 200) {
                toastr.success(res.message);
                
                
            }
        },
        
        async deleteTask(e, taskId) {
            const res = await $post(`/tasks/delete/${taskId}`, { task_parent: e.target.value });

            if (res.code == 200) {
                toastr.success('Xóa thành công');
                this.taskId = 0;
                if (res.arr_parent.length > 0) {
                    let arrIndex = [];
                    let listData = _.cloneDeep(this.list);
                    res.arr_parent.forEach(item => {
                        let index = _.findIndex(listData, val => val.id === item);
                        arrIndex.push(index);
                        listData = listData[index]._children;
                    });
                    this.list = this.resetData([...this.list], arrIndex, 0, res.task_id);
                } else {
                    let index = _.findIndex(this.list, val => val.id == res.task_id);
                    this.list.splice(index, 1);
                }

            }
        },
        async copyMyWorks(id) {
            const res = await $get(`/tasks/copy/${id}`);

            if (res.code == 200) {
                toastr.success('Copy thành công');
                this.getMyWorks();
            }
        },
    },
    watch: {
        'tasks': function (newVal) {
             if (this.taskParentId && this.count === 0) {
                 this.task.task_parent = _.find(newVal, {id: parseInt(this.taskParentId)});
                 this.count = this.count + 1;
             }
        },
        'task.project_id': function (newVal) {
            if (newVal.id) {
                this.getTaskByProject(newVal.id, 0);
            }
        },
         'projects': function (newVal) {
             if (this.projectId) {
                 this.task.project_id = _.find(newVal, {id: parseInt(this.projectId)});
                 this.getTaskByProject(this.projectId);
             }
        },
        'taskParentId': function (newVal) {
            console.log(newVal, 'new val');
        }
    }
}
</script>

<style scoped lang="scss">
table {
    border-collapse: collapse;
    width: 100%;
}
th {
    padding: 8px;
    text-align: center;
    border-bottom: 1px solid #ddd;
}

td {
    padding: 8px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

tr:hover {
    background-color: coral;
}


table table-bordered mt-5 {
    background: #fff;
    border: 1px solid #999999;
}

table thead tr th {
    padding: 5px;
    border: 1px solid #9b9b9b;
    color: #000;
}

table.table-striped tbody tr:nth-of-type(odd) {
    background: #f9f9f9;
}


.text-left {
    text-align: left !important;
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
    padding: 5px 5px;
    border: 1px solid #999999;
}

table.my-work td {
    border:0px;
    padding: 10px 20px 10px 20px;
}
.status{
    text-align: center;
}

.search-collapse {
    width: 400px;
    box-shadow: 2px 5px 5px #d0d0d0;
    position: absolute;
    right: 10px;
    top: 40px;
    z-index: 9;
    background: #FFFFFF;
    border: 2px;
}
.info-my-work {
    width: 500px;
    box-shadow: 2px 5px 5px #d0d0d0;
    position: absolute;
    right: 0px;
    top: 5px;
    z-index: 9;
    background: #c5c5c5;
    border: 2px;
}

.modal-content {
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.greenText {
    background-color: green;
}

.blueText {
    background-color: blue;
}

.redText {
    background-color: red;
}
</style>
