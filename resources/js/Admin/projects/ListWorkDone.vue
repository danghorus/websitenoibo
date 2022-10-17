<template>
    <div>
        <div class="col-lg">
            <div class="collapse search-collapse" id="collapseExample" v-if="showFilter">
                <div class="form-group p-2">
                    <label for="project_description">Theo dự án</label>
                    <multiselect v-model="project" :options="projects" value="id" label="project_name"
                                 :close-on-select="true" :show-labels="true" placeholder="Vui lòng chọn">
                    </multiselect>
                </div>
                <!--<div class="form-group p-2">
                    <label for="project_description">Theo bộ phận</label>
                    <multiselect v-model="department" :options="departments" value="id" label="label"
                        :close-on-select="true" :show-labels="true" placeholder="Vui lòng chọn">
                    </multiselect>
                </div>-->

                <div class="form-group p-2">
                    <label for="project_description">Theo bộ phận</label>
                    <select class="form-select" v-model="option1">
                        <option value="1" >Tất cả</option>
                        <option value="2" >Dev</option>
                        <option value="3" > Game design</option>
                        <option value="4" >Art</option>
                        <option value="5" >Tester</option>
                        <option value="9" >Phân tích dữ liệu</option>
                    </select>
                </div>
                <div class="form-group p-2">
                    <label for="project_description">Theo người thực hiện</label>

                    <!--<multiselect v-model="performer" :options="users" value="id" label="fullname"
                                 :close-on-select="true" :show-labels="true" placeholder="Vui lòng chọn">

                    </multiselect>-->
					<select class="form-select form-select-sm" aria-label=".form-select-sm example"
                        v-model="performer">
						<option selected="selected">Vui lòng chọn</option>
                        <option v-for="(user, index) in users" :key="index" :value="user.id">{{user.fullname}}</option>
                    </select>
                </div>
                <div class="form-group p-2">
                    <label for="project_description">Theo trạng thái</label>
                    <select class="form-select" v-model="option">
                        <option value="10">Tất cả</option>
                        <option value="1">Đang chờ</option>
                        <option value="2">Đang tiến hành </option>
                        <option value="3">Tạm dừng</option>
                        <option value="4">Hoàn thành</option>
                        <option value="5">Chờ feedback </option>
                        <option value="6">Làm lại</option>
                        <option value="0">Quá hạn</option>
                    </select>
                </div>
                <div class="float-right p-2">
                    <button type="submit" class="btn btn-secondary p-2" @click="handleShowFilter()">Đóng</button>
                    <button type="submit" class="btn btn-primary p-2" style="width:70px;"
                            @click="filterTask()">Lọc</button>
                </div>
            </div>
        </div>
        <div class="form-group col-lg-1" style="float:right;">
            <button class="btn btn-outline-secondary "><a v-bind:href="'/my_work'">Việc của tôi</a></button>
        </div>
        <div class="mt-4">
            <h4 style="margin-left: 10px;">Danh sách công việc</h4>
            <!--<select class="form-select col-lg-2"
                    style="position: absolute; left: 250px; top: 98px; width:250px; height:34px;" v-model="option2">
                <option value="3">Tất cả</option>
                <option value="1">Chưa hoàn thành</option>
                <option value="2">Đã hoàn thành</option>
            </select>&emsp;-->
			<div class="form-group col-lg-2" style="position: absolute; left: 250px; top: 98px; width:180px; height:34px;">
				<button class="btn btn-outline-secondary "><a v-bind:href="'/list_work'">Chưa hoàn thành</a></button>
			</div>
            <button class="btn btn-outline-secondary" @click="handleShowFilter()" type="button" data-toggle="collapse"
                    data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample1"
                    style="float:right; margin:  -35px 10px 0px 0px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-funnel"
                     viewBox="0 0 16 16">
                    <path
                        d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2zm1 .5v1.308l4.372 4.858A.5.5 0 0 1 7 8.5v5.306l2-.666V8.5a.5.5 0 0 1 .128-.334L13.5 3.308V2h-11z" />
                </svg> Tạo bộ lọc
            </button>
            <table class="table-striped table-responsive table-hover result-point"
                   style="width:99%; margin: 0px 0px 0px 10px">
                <thead class="point-table-head">
                <tr style="text-align: center;">
                    <th scope="col">STT</th>

                    <th scope="col" width="650px">Tên công việc</th>
                    <th scope="col" width="8%">Bắt đầu</th>
                    <th scope="col" width="8%">Thời lượng (Giờ)</th>
                    <th scope="col" width="8%">Thời lượng thực tế (Giờ)</th>
                    <th scope="col" width="8%">Trọng số</th>
                    <th scope="col" width="12%">Người thực hiện</th>
                    <th scope="col" width="8%">Bộ phận</th>
					<th scope="col" width="8%">Tiến độ</th>
                    <th scope="col" width="8%">Trạng thái</th>
                    <th scope="col" width="8%">Thao tác</th>

                </tr>
                </thead>
                <tbody v-for="(item, index) in list" :key="item.id" style="text-align:center;">
                    <!--<template v-if="option2 == 1 && item.status != 4">-->
                        <tr>
                            <td>{{ index + 1 }}</td>
                            <td scope="row" style="text-align:left;">
                                <div style="display: flex">
                                    <p @click="showModalEditTask(item.id)">
                                        <button class="btn btn-success btn-sm" style="height:20px;font-size:10px; margin-top:-2px;">Sửa</button>
                                    </p>
                                    <p>
                                        <input style="width:500px; border:0px; background-color: #F9F9F9"  @change="changeTaskName($event, item.id)" v-model="item.task_name">
                                    </p>
                                </div>
                            </td>
                            <td>
                                <input type="date" style="width: 100%; border:0px; background-color: #F9F9F9" @change="changeStartTime($event, item.id)" v-model="item.start_time">
                            </td>
                            <td><input style="width:100%; border:0px; text-align:right;background-color: #F9F9F9"  @change="changeTime($event, item.id)" v-model="item.time"></td>
                            <td style=" text-align:right;">{{ item.time_real}}</td>
                            <td>{{ item.weight}}</td>
                            <td>
                                <select class="form-select form-select-sm" aria-label=".form-select-sm example"
                                        @change="changePerformer($event, item.id)" v-model="item.task_performer">
                                    <option v-for="(user, index) in users" :key="index" :value="user.id">{{user.fullname}}</option>
                                </select>
                            </td>
                            <td>
                                <div style="display: flex">
                                    <select class="form-select form-select-sm" aria-label=".form-select-sm example"
                                            @change="changeDepartment($event, item.id)" v-model="item.task_department">
                                        <option value="2">Dev</option>
                                        <option value="3">Game Design</option>
                                        <option value="4">Art</option>
                                        <option value="5">Tester</option>
                                    </select>
                                </div>
                            </td>
                            <td style="text-align:right;">
                                <div style="display: flex;">
                                    <p><input style="width:50px; border:0px;background-color: #F9F9F9"  @change="changeProgress($event, item.id)" v-model="item.progress"></p>
                                    <p>%</p>
                                </div>
                            </td>
                            <td v-if="item.status == 0" style="background-color:red">Đã quá hạn</td>
                            <td v-else-if="item.status == 1" style="background-color:white">Đang chờ</td>
                            <td v-else-if="item.status == 2" style="background-color:#008080">Đang làm</td>
                            <td v-else-if="item.status == 3" style="background-color:orange">Tạm dừng</td>
                            <td v-else-if="item.status == 5" style="background-color:#ff8080">Chờ feedback</td>
                            <td v-else-if="item.status == 6" style="background-color:#ff0000">Làm lại</td>
                            <td v-else-if="item.status_title == 'Hoàn thành chậm'" style="background-color:gray">Hoàn thành
                                chậm</td>
                            <td v-else-if="item.status_title == 'Hoàn thành'" style="background-color:green">Hoàn thành</td>
                            <td>
                                <div style="display: flex">
                                    <select class="form-select form-select-sm" aria-label=".form-select-sm example"
                                            @change="changeStatus($event, item.id)" v-model="item.status">

                                        <option value="1" >Đang chờ</option>
                                        <option value="2" >Đang làm</option>
                                        <option value="3" >Tạm dừng</option>
                                        <option value="5" >Chờ feedback</option>
                                        <option value="6" >Làm lại </option>
                                        <option value="4" >Hoàn thành </option>
                                        <option value="0" >Đã quá hạn</option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                    <!--</template>
                    <template v-if="option2 == 2 && item.status == 4">
                        <tr>
                            <td>{{ index + 1 }}</td>
                            <td scope="row" style="text-align:left;">
                                <div style="display: flex">
                                    <p @click="showModalEditTask(item.id)">
                                        <button class="btn btn-success btn-sm" style="height:20px;font-size:10px; margin-top:-2px;">Sửa</button>
                                    </p>
                                    <p>
                                        <input style="width:500px; border:0px; background-color: #F9F9F9"  @change="changeTaskName($event, item.id)" v-model="item.task_name">
                                    </p>
                                </div>
                            </td>
                            <td>
                                <input type="date" style="width: 100%; border:0px; background-color: #F9F9F9" @change="changeStartTime($event, item.id)" v-model="item.start_time">
                            </td>
                            <td><input style="width:100%; border:0px; text-align:right;background-color: #F9F9F9"  @change="changeTime($event, item.id)" v-model="item.time"></td>
                            <td style=" text-align:right;">{{ item.time_real}}</td>
                            <td>{{ item.weight}}</td>
                            <td>
                                <select class="form-select form-select-sm" aria-label=".form-select-sm example"
                                        @change="changePerformer($event, item.id)" v-model="item.task_performer">
                                    <option v-for="(user, index) in users" :key="index" :value="user.id">{{user.fullname}}</option>
                                </select>
                            </td>
                            <td>
                                <div style="display: flex">
                                    <select class="form-select form-select-sm" aria-label=".form-select-sm example"
                                            @change="changeDepartment($event, item.id)" v-model="item.task_department">
                                        <option value="2">Dev</option>
                                        <option value="3">Game Design</option>
                                        <option value="4">Art</option>
                                        <option value="5">Tester</option>
                                    </select>
                                </div>
                            </td>
                            <td style="text-align:right;">
                                <div style="display: flex;">
                                    <p><input style="width:50px; border:0px;background-color: #F9F9F9"  @change="changeProgress($event, item.id)" v-model="item.progress"></p>
                                    <p>%</p>
                                </div>
                            </td>
                            <td v-if="item.status == 0" style="background-color:red">Đã quá hạn</td>
                            <td v-else-if="item.status == 1" style="background-color:white">Đang chờ</td>
                            <td v-else-if="item.status == 2" style="background-color:#008080">Đang làm</td>
                            <td v-else-if="item.status == 3" style="background-color:orange">Tạm dừng</td>
                            <td v-else-if="item.status == 5" style="background-color:#ff8080">Chờ feedback</td>
                            <td v-else-if="item.status == 6" style="background-color:#ff0000">Làm lại</td>
                            <td v-else-if="item.status_title == 'Hoàn thành chậm'" style="background-color:gray">Hoàn thành
                                chậm</td>
                            <td v-else-if="item.status_title == 'Hoàn thành'" style="background-color:green">Hoàn thành</td>
                            <td>
                                <div style="display: flex">
                                    <select class="form-select form-select-sm" aria-label=".form-select-sm example"
                                            @change="changeStatus($event, item.id)" v-model="item.status">

                                        <option value="1" >Đang chờ</option>
                                        <option value="2" >Đang làm</option>
                                        <option value="3" >Tạm dừng</option>
                                        <option value="5" >Chờ feedback</option>
                                        <option value="6" >Làm lại </option>
                                        <option value="4" >Hoàn thành </option>
                                        <option value="0" >Đã quá hạn</option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <template v-if="option2 == 3">
                        <tr>
                            <td>{{ index + 1 }}</td>
                            <td scope="row" style="text-align:left;">
                                <div style="display: flex">
                                    <p @click="showModalEditTask(item.id)">
                                        <button class="btn btn-success btn-sm" style="height:20px;font-size:10px; margin-top:-2px;">Sửa</button>
                                    </p>
                                    <p>
                                        <input style="width:500px; border:0px; background-color: #F9F9F9"  @change="changeTaskName($event, item.id)" v-model="item.task_name">
                                    </p>
                                </div>
                            </td>
                            <td>
                                <input type="date" style="width: 100%; border:0px; background-color: #F9F9F9" @change="changeStartTime($event, item.id)" v-model="item.start_time">
                            </td>
                            <td><input style="width:100%; border:0px; text-align:right;background-color: #F9F9F9"  @change="changeTime($event, item.id)" v-model="item.time"></td>
                            <td style=" text-align:right;">{{ item.time_real}}</td>
                            <td>{{ item.weight}}</td>
                            <td>
                                <select class="form-select form-select-sm" aria-label=".form-select-sm example"
                                        @change="changePerformer($event, item.id)" v-model="item.task_performer">
                                    <option v-for="(user, index) in users" :key="index" :value="user.id">{{user.fullname}}</option>
                                </select>
                            </td>
                            <td>
                                <div style="display: flex">
                                    <select class="form-select form-select-sm" aria-label=".form-select-sm example"
                                            @change="changeDepartment($event, item.id)" v-model="item.task_department">
                                        <option value="2">Dev</option>
                                        <option value="3">Game Design</option>
                                        <option value="4">Art</option>
                                        <option value="5">Tester</option>
                                    </select>
                                </div>
                            </td>
                            <td style="text-align:right;">
                                <div style="display: flex;">
                                    <p><input style="width:50px; border:0px;background-color: #F9F9F9"  @change="changeProgress($event, item.id)" v-model="item.progress"></p>
                                    <p>%</p>
                                </div>
                            </td>
                            <td v-if="item.status == 0" style="background-color:red">Đã quá hạn</td>
                            <td v-else-if="item.status == 1" style="background-color:white">Đang chờ</td>
                            <td v-else-if="item.status == 2" style="background-color:#008080">Đang làm</td>
                            <td v-else-if="item.status == 3" style="background-color:orange">Tạm dừng</td>
                            <td v-else-if="item.status == 5" style="background-color:#ff8080">Chờ feedback</td>
                            <td v-else-if="item.status == 6" style="background-color:#ff0000">Làm lại</td>
                            <td v-else-if="item.status_title == 'Hoàn thành chậm'" style="background-color:gray">Hoàn thành
                                chậm</td>
                            <td v-else-if="item.status_title == 'Hoàn thành'" style="background-color:green">Hoàn thành</td>
                            <td>
                                <div style="display: flex">
                                    <select class="form-select form-select-sm" aria-label=".form-select-sm example"
                                            @change="changeStatus($event, item.id)" v-model="item.status">

                                        <option value="1" >Đang chờ</option>
                                        <option value="2" >Đang làm</option>
                                        <option value="3" >Tạm dừng</option>
                                        <option value="5" >Chờ feedback</option>
                                        <option value="6" >Làm lại </option>
                                        <option value="4" >Hoàn thành </option>
                                        <option value="0" >Đã quá hạn</option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                    </template>-->
                </tbody>
            </table>
        </div>
        <div ref="modalUpdateTask" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document" style=" max-width: 60%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Sửa Công việc</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                @click="closeModalEditTask()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <create-task v-if="showModalEdit" :users="users" :groupUsers="groupUsers"
                             :priorities="priorities" :stickers="stickers" :projects="projects" :taskId="taskEditId"
                             @handleGetTasks="handleGetTasks()" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import { $get, $post } from "../../ultis";
    import Multiselect from 'vue-multiselect';
    import CreateTask from "./CreateTask";

    export default {
        name: "ListWorkDone",
        components: { Multiselect, CreateTask },
        props: ['projectId', 'users', 'groupUsers', 'priorities', 'stickers', 'projects', 'searchProjectId', 'search',
            'startTime', 'taskPerformer', 'taskDepartment', 'status', 'list', 'currentUser'],
        data() {
            return {
                option: 10,
                option1: 1,
                option2: 1,
                toggle: false,
                show: false,
                list: [],
                showModal: false,
                showListWork: true,
                showFilter: false,
                projects: [],
                project: '',
                departments: [],
                summary: '',
                taskId: 0,
                showModalEdit: false,
                taskEditId: 0,
                users: [],
                groupUsers: [],
                priorities: [],
                stickers: [],
                performer: '',
                task_department: '',
                status: '',
                currentUser: '',
            }
        },
        created() {
            this.getProjects();
            this.getAllPriority();
            this.getAllSticker();
            this.getAllUser();
            this.getGroupUsers();
            this.getListWorkDone();
        },
        methods: {
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
            async getProjects() {
                const res = await $get('/projects/get_all');

                this.projects = res.projects
            },
            async getAllUser() {
                const res = await $get('/user/all_user');
                if (res.code == 200) {
                    this.users = res.data;
                }
            },
            async getGroupUsers() {
                const res = await $get('/user/all_user_by_group');
                if (res.code == 200) {
                    this.groupUsers = res.data;
                }
            },
            showModalEditTask(id) {
                this.showModalEdit = true;
                $(this.$refs.modalUpdateTask).modal('show');
                this.taskEditId = id;
            },
            closeModalEditTask() {
                this.showModalEdit = false;
                $(this.$refs.modalUpdateTask).modal('hide');
                this.taskEditId = 0;
            },
            handleShowFilter() {
                this.showFilter = !this.showFilter
            },
            ShowInfoListWork() {
                this.showInfoListWorkDone = !this.showInfoListWorkDone
            },
            filterTask() {
                this.getListWorkDone();
                this.showFilter = false;
            },
            async getListWorkDone() {
                let params = {};

                if (this.option1 && this.option1 != 1) {
                    params.task_department = this.option1;
                }
                if (this.option && this.option != 10) {
                    params.status = this.option;
                }

                if (this.project && this.project.id > 0) {
                    params.project_id = this.project.id;
                }
				if (this.performer){
                    params.task_performer = this.performer;
                }

                const res = await $get('/list-work-done', params);

                if (res.code == 200) {
                    this.list = res.tasks;
                    this.summary = res.summary
                }
            },
            async changeStatus(e, taskId) {
                const res = await $post(`/tasks/change-status/${taskId}`, { status: e.target.value });

                if (res.code == 200) {
                    toastr.success(res.message);
                    this.getListWorkDone();
                }
            },
            async changeProgress(e, taskId) {
                const res = await $post(`/tasks/change-progress/${taskId}`, { progress: e.target.value });

                if (res.code == 200) {
                    toastr.success(res.message);
                    this.getListWorkDone();
                }
            },
            async changeTaskName(e, taskId) {
                const res = await $post(`/tasks/change-task_name/${taskId}`, { task_name: e.target.value });

                if (res.code == 200) {
                    toastr.success(res.message);
                    this.getListWorkDone();
                }
            },
            async changeStartTime(e, taskId) {
                const res = await $post(`/tasks/change-start_time/${taskId}`, { start_time: e.target.value });

                if (res.code == 200) {
                    toastr.success(res.message);
                    this.getListWorkDone();
                }
            },
            async changeTime(e, taskId) {
                const res = await $post(`/tasks/change-time/${taskId}`, { time: e.target.value });

                if (res.code == 200) {
                    toastr.success(res.message);
                    this.getListWorkDone();
                }
            },
            async changeDepartment(e, taskId) {
                const res = await $post(`/tasks/change-department/${taskId}`, { task_department: e.target.value });

                if (res.code == 200) {
                    toastr.success(res.message);
                    this.getListWorkDone();
                }
            },
            async changePerformer(e, taskId) {
                const res = await $post(`/tasks/change-performer/${taskId}`, { task_performer: e.target.value });

                if (res.code == 200) {
                    toastr.success(res.message);
                    this.getListWorkDone();
                }
            },
            handleGetTasks() {
                this.closeModalEditTask();
                this.$emit('getListWorkDone');
            }
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
        border: 0px;
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
        left: 15px;
        top: 5px;
        z-index: 9;
        background: #c5c5c5;
        border: 2px;
    }
</style>
