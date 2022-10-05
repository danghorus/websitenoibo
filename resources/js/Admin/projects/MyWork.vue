<template>
    <div>
        <div class="mt-4" style="background-color:white">
            <nav class="navbar navbar-expand-lg" style="margin-top:-20px; float:right;">
                <ul class="navbar-nav mr-auto" style="font-size:16px;" >
                    <li class="nav-item">
                        <div class="form-group p-2">
                            <label for="project_description" style="font-size:12px">Theo dự án</label>
                            <select  class="form-select" @click="filterTask()" v-model="project" style="width:250px">
                                <option value="0" selected="selected">Tất cả</option>
                                <option v-for="(project, index) in projects" :key="index" :value="project.id">{{project.project_name}}</option>
                            </select>
                        </div>
                    </li>
                    <li v-if="option2 != 2" class="nav-item">
                        <div class="form-group p-2">
                            <label for="project_description" style="font-size:12px">Theo trạng thái</label>
                            <select @click="filterTask()" class="form-select" v-model="option" style="width:250px">
                                <option value="10">Tất cả</option>
                                <option value="0">Quá hạn</option>
                                <option value="1">Đang chờ </option>
                                <option value="2">Đang làm</option>
                                <option value="3">Tạm dừng</option>
                                <option value="5">Chờ feedback</option>
                                <option value="6">Làm lại</option>
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
                            data-target="#collapseExample_1" aria-expanded="false" aria-controls="collapseExample"
                            style="margin-top: 38px"> Thông tin công việc
                        </button>
                        <div class="col-lg">
                            <div class="collapse info-my-work" id="collapseExample_1" v-if="showInfoMyWork">
                                <div>
                                    <h4 style="margin: 20px 0px 10px 150px;">Thông tin làm việc</h4>
                                    <table style="width:100%; border: 0px" class="my-work">
                                        <!--<tr>
                                            <td><b>Warrior đăng ký</b></td>
                                            <td>:</td>
                                            <td>Warrior 3</td>
                                        </tr>
                                        <tr>
                                            <td><b>Warrior hiện tại</b></td>
                                            <td>:</td>
                                            <td>Warrior 3</td>
                                        </tr>
                                        <tr>
                                            <td><b>Thời gian làm việc trong ngày</b></td>
                                            <td>:</td>
                                            <td>12 tiếng</td>
                                        </tr>-->
                                        <tr>
                                            <td width="300px"><b>Tổng số công việc</b></td>
                                            <td>:</td>
                                            <td>{{ summary.total }} &emsp; công việc</td>
                                        </tr>
                                        <tr>
                                            <td><b>Quá hạn</b></td>
                                            <td>:</td>
                                            <td  v-if=" summary.total_slow >0 "><b style="color:red;">{{ summary.total_slow }}</b>&emsp; công việc</td>
                                            <td  v-else>{{ summary.total_slow }} &emsp; công việc</td>
                                        </tr>
                                        <tr>
                                            <td><b>Đang chờ</b></td>
                                            <td>:</td>
                                            <td>{{ summary.total_wait }} &emsp; công việc</td>
                                        </tr>
                                        <tr>
                                            <td><b>Đang làm</b></td>
                                            <td>:</td>
                                            <td>{{ summary.total_processing }} &emsp; công việc</td>
                                        </tr>
                                        <tr>
                                            <td><b>Tạm dừng</b></td>
                                            <td>:</td>
                                            <td>{{ summary.total_pause }} &emsp; công việc</td>
                                        </tr>
                                        <tr>
                                            <td><b>Chờ feedback</b></td>
                                            <td>:</td>
                                            <td>{{ summary.total_wait_fb }} &emsp; công việc</td>
                                        </tr>
                                        <tr>
                                            <td><b>Làm lại</b></td>
                                            <td>:</td>
                                            <td>{{ summary.total_again }} &emsp; công việc</td>
                                        </tr>
                                        <tr>
                                            <td><b>Hoàn thành</b></td>
                                            <td>:</td>
                                            <td>{{ summary.total_complete }} &emsp; công việc</td>
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
            <select class="form-select col-lg-2"
                    style="position: absolute; left: 25px; top: 105px; width:220px; height:34px;" v-model="option2">
				<option value="3">Tất cả</option>
                <option value="1">Chưa hoàn thành</option>
                <option value="2">Đã hoàn thành</option>
            </select>&emsp;
            <!--<button class="btn btn-outline-secondary" @click="handleShowFilter()" type="button" data-toggle="collapse"
                data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample1"
                style="float:right; margin:  -35px 10px 0px 0px;">
                 Tạo bộ lọc
            </button>-->
            <table class="table-striped table-responsive table-hover result-point"
                style="width:99%; margin: 0px 0px 0px 10px">
                <thead class="point-table-head">
                    <tr style="text-align: center;">
                        <th scope="col">STT</th>
                        <th scope="col" width="700px">Tên công việc</th>
                        <th scope="col" width="10%">Bắt đầu</th>
                        <th scope="col" width="10%">Thời lượng (Giờ)</th>
                        <th scope="col" width="10%">Thời lượng thực tế (Giờ)</th>
                        <th scope="col" width="10%">Trọng số</th>
						<th scope="col" width="10%">Tiến độ</th>
                        <th scope="col" width="10%">Trạng thái</th>
                        <th scope="col" width="10%">Thao tác</th>
                        <th scope="col" width="10%">Thao tác</th>
                    </tr>
                </thead>
                <tbody v-for="(item, index) in list" :key="item.id" style="text-align:center;">
                    <template v-if="option2 == 1 && item.status != 4">
                        <tr>
                            <td>{{index + 1 }}</td>
                            <td scope="row" style="text-align:left;">{{ item.task_name }}</td>
                            <!--<td style="text-align:left;">{{ item.task_parent }}
                            </td>-->
                            <td>{{ item.start_time }}</td>
                            <td>{{ item.time }}</td>
                            <!--<td>{{ item.end_time }}</td>
                            <td>{{ item.real_start_time }}</td>-->
                            <td>{{ item.time_real}}</td>
                            <td>{{ item.weight}}</td>
							<td>
                                <input type="number" min="0" step="1" max="100" style="width: 100%;border:0px; background-color:#F8F9FA"
                                 @change="changeProgress($event, item.id)" v-model="item.progress">
                            </td>
                            <!--<td>{{ item.real_end_time }}</td>-->
                            <!--<td>{{ item.status_title }}</td>-->
                            <td v-if="item.status == 0" style="background-color:red">Đã quá hạn</td>
                            <td v-else-if="item.status == 1" style="background-color:white">Đang chờ</td>
                            <td v-else-if="item.status == 2" style="background-color:#008080">Đang làm</td>
                            <td v-else-if="item.status == 3" style="background-color:orange">Tạm dừng</td>
                            <td v-else-if="item.status == 5" style="background-color:#ff8080">Chờ feedback</td>
                            <td v-else-if=" item.status==6" style="background-color:#ff0000">Làm lại</td>
                            <td v-else-if="item.status_title == 'Hoàn thành chậm'" style="background-color:gray">Hoàn thành chậm
                            </td>
                            <td v-else-if="item.status_title == 'Hoàn thành'" style="background-color:green">Hoàn thành</td>
                            <td>
                                <select class="form-select form-select-sm" aria-label=".form-select-sm example"
                                    @change="changeStatus($event, item.id)" v-model="item.status">
                                    <option value="0" v-if="item.status == 0" :disabled="item.status == 0">Đang Chờ</option>
                                    <option value="1" v-if="item.status == 1">Đang Chờ</option>
                                    <option value="2" :disabled="item.status == 2 || item.status == 4">Đang tiến hành</option>
                                    <option value="3"
                                        :disabled="item.status == 3 || item.status == 4 || item.status == 5 || item.status == 6">
                                        Tạm dừng</option>
                                    <option value="5" :disabled="item.status == 4 || item.status == 5 || item.status == 6">Chờ
                                        feedback</option>
                                    <option value="6" v-if="item.status == 6">Làm lại</option>
                                    <option value="4" v-if="item.status == 4"> Hoàn thành</option>
                                </select>
                            </td>
                            <td>
                                <div style="display: flex">
                                    <p @click="showModalEditTask(item.id)">
                                        <button class="btn btn-primary btn-sm" style="height:20px; font-size:10px;">Sửa</button>
                                    </p> &ensp;
                                    <p @click="deleteTask(item.id)">
                                        <button class="btn btn-danger btn-sm" style="height:20px; font-size:10px;">Xoá</button>
                                    </p>&ensp;
                                    <p @click="copyTask(item.id)">
                                        <button class="btn btn-success btn-sm" style="height:20px; font-size:10px;">Copy</button>
                                    </p>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <template v-if="option2 == 2 && item.status == 4">
                        <tr>
                            <td>{{index + 1 }}</td>
                            <td scope="row" style="text-align:left;">{{ item.task_name }}</td>
                            <!--<td style="text-align:left;">{{ item.task_parent }}
                            </td>-->
                            <td>{{ item.start_time }}</td>
                            <td>{{ item.time }}</td>
                            <!--<td>{{ item.end_time }}</td>
                            <td>{{ item.real_start_time }}</td>-->
                            <td>{{ item.time_real}}</td>
                            <td>{{ item.weight}}</td>
                            <td style="width: 100%; border:0px; background-color: #F9F9F9;">
                                <input type="number" style="width: 100%; border:0px; background-color: #F9F9F9;" min="0" max="100" 
                                @change="changeProgress($event, item.id)" v-model="item.progress">
                            </td>
                            <!--<td>{{ item.real_end_time }}</td>-->
                            <!--<td>{{ item.status_title }}</td>-->
                            <td v-if="item.status == 0" style="background-color:red">Đã quá hạn</td>
                            <td v-else-if="item.status == 1" style="background-color:white">Đang chờ</td>
                            <td v-else-if="item.status == 2" style="background-color:#008080">Đang làm</td>
                            <td v-else-if="item.status == 3" style="background-color:orange">Tạm dừng</td>
                            <td v-else-if="item.status == 5" style="background-color:#ff8080">Chờ feedback</td>
                            <td v-else-if=" item.status==6" style="background-color:#ff0000">Làm lại</td>
                            <td v-else-if="item.status_title == 'Hoàn thành chậm'" style="background-color:gray">Hoàn thành chậm
                            </td>
                            <td v-else-if="item.status_title == 'Hoàn thành'" style="background-color:green">Hoàn thành</td>
                            <td>
                                <select class="form-select form-select-sm" aria-label=".form-select-sm example"
                                        @change="changeStatus($event, item.id)" v-model="item.status">
                                    <option value="0" v-if="item.status == 0" :disabled="item.status == 0">Đang Chờ</option>
                                    <option value="1" v-if="item.status == 1">Đang Chờ</option>
                                    <option value="2" :disabled="item.status == 2 || item.status == 4">Đang tiến hành</option>
                                    <option value="3"
                                            :disabled="item.status == 3 || item.status == 4 || item.status == 5 || item.status == 6">
                                        Tạm dừng</option>
                                    <option value="5" :disabled="item.status == 4 || item.status == 5 || item.status == 6">Chờ
                                        feedback</option>
                                    <option value="6" v-if="item.status == 6">Làm lại</option>
                                    <option value="4" v-if="item.status == 4"> Hoàn thành</option>
                                </select>
                            </td>
                        </tr>
                    </template>
					<template v-if="option2 == 3">
                        <tr>
                            <td>{{index + 1 }}</td>
                            <td scope="row" style="text-align:left;">{{ item.task_name }}</td>
                            <!--<td style="text-align:left;">{{ item.task_parent }}
                            </td>-->
                            <td>{{ item.start_time }}</td>
                            <td>{{ item.time }}</td>
                            <!--<td>{{ item.end_time }}</td>
                            <td>{{ item.real_start_time }}</td>-->
                            <td>{{ item.time_real}}</td>
                            <td>{{ item.weight}}</td>
                            <td>
                                <input type="number" style="width: 100%; border:0px;" min="0" max="100" @change="changeProgress($event, item.id)" v-model="item.progress">
                            </td>
                            <!--<td>{{ item.real_end_time }}</td>-->
                            <!--<td>{{ item.status_title }}</td>-->
                            <td v-if="item.status == 0" style="background-color:red">Đã quá hạn</td>
                            <td v-else-if="item.status == 1" style="background-color:white">Đang chờ</td>
                            <td v-else-if="item.status == 2" style="background-color:#008080">Đang làm</td>
                            <td v-else-if="item.status == 3" style="background-color:orange">Tạm dừng</td>
                            <td v-else-if="item.status == 5" style="background-color:#ff8080">Chờ feedback</td>
                            <td v-else-if=" item.status==6" style="background-color:#ff0000">Làm lại</td>
                            <td v-else-if="item.status_title == 'Hoàn thành chậm'" style="background-color:gray">Hoàn thành chậm
                            </td>
                            <td v-else-if="item.status_title == 'Hoàn thành'" style="background-color:green">Hoàn thành</td>
                            <td>
                                <select class="form-select form-select-sm" aria-label=".form-select-sm example"
                                        @change="changeStatus($event, item.id)" v-model="item.status">
                                    <option value="0" v-if="item.status == 0" :disabled="item.status == 0">Đang Chờ</option>
                                    <option value="1" v-if="item.status == 1">Đang Chờ</option>
                                    <option value="2" :disabled="item.status == 2 || item.status == 4">Đang tiến hành</option>
                                    <option value="3"
                                            :disabled="item.status == 3 || item.status == 4 || item.status == 5 || item.status == 6">
                                        Tạm dừng</option>
                                    <option value="5" :disabled="item.status == 4 || item.status == 5 || item.status == 6">Chờ
                                        feedback</option>
                                    <option value="6" v-if="item.status == 6">Làm lại</option>
                                    <option value="4" v-if="item.status == 4"> Hoàn thành</option>
                                </select>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>

import {$get, $post} from "../../ultis";
import Multiselect from 'vue-multiselect';

export default {
    name: "MyWork",
    el: '#infoMyWork',
    components: { Multiselect },
    data() {
        return {
            option: 10,
            option2: 1,
            toggle: false,
            show: false,
            list: [],
            showModal: false,
            showMyWork: true,
            showFilter: false,
            showInfoMyWork: false,
            projects: [],
            project: 0,
            summary: ''
        }
    },
    created() {
        this.getMyWorks();
        this.getProjects();
    },
    methods: {

        handleShowFilter() {
            this.showFilter = !this.showFilter
        },
        ShowInfoMyWork() {
            this.showInfoMyWork = !this.showInfoMyWork
        },
        filterTask() {
            this.getMyWorks();
            this.showFilter = false;
        },
        async getMyWorks() {
            let params = {};

            if (this.option && this.option != 10) {
                params.status = this.option;
            }

            if (this.project && this.project > 0) {
                params.project_id = this.project;
            }

            const res = await $get('/my-tasks', params);

            if (res.code == 200) {
                this.list = res.tasks;
                this.summary = res.summary
            }
        },
        async changeStatus(e, taskId) {
            const res = await $post(`/tasks/change-status/${taskId}`, {status: e.target.value});

            if (res.code == 200) {
                toastr.success(res.message);
                this.getMyWorks();
            }
        },
		async changeProgress(e, taskId) {
            const res = await $post(`/tasks/change-progress/${taskId}`, {progress: e.target.value});

            if (res.code == 200) {
                toastr.success(res.message);
                this.getMyWorks();
            }
        },

        async getProjects() {
            const res = await $get('/projects/get_all');

            this.projects = res.projects
        },
        async deleteTask(id) {
            const res = await $post(`/tasks/delete/${id}`);

            if (res.code == 200) {
                toastr.success('Xóa thành công');
                this.getMyWorks();
            }
        },

        async copyTask(id) {
            const res = await $get(`/tasks/copyClone/${id}`);

            if (res.code == 200) {
                toastr.success('Thêm mới công việc');
                this.getMyWorks();
            }
        },
    }
}
</script>

<style scoped lang="scss">

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

 #notify {
    margin-top: 5px;
    padding: 10px;
    font-size: 12px;
    width: 100%;
    color: #fff;
    display: block;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
  }
  #notify.error {
    background-color: #DD2C00;
  }

  input:invalid {
  color: red;
}


</style>
