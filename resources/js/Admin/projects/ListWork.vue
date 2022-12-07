<template>
    <div>
        <div class="mt-4">
            <h3 style="margin: -30px 0px 0px 25px;">Danh sách công việc</h3>
            <select @change="changeOption()" class="form-select col-lg-2"
                style="position: absolute; left: 25px; top: 110px; width:220px; height:34px;" v-model="option2">
                <option value="3">Tất cả</option>
                <option value="15">Việc hôm nay</option>
                <option value="10">Mới tạo</option>
                <option value="1">Chưa hoàn thành</option>
                <option value="5">Chờ feedback</option>
                <option value="2">Đã hoàn thành</option>
            </select>
            <button v-if="option2 != 15" class="btn btn-success btn-sm" @click="NewTask()"
                style="height:35px; font-size:15px; margin: 0px 0px 0px 300px;">Thêm mới
            </button>
            <button v-if="option2 == 15" class="btn btn-success btn-sm" @click="NewTaskToday()"
                style="height:35px; font-size:15px; margin: 0px 0px 0px 300px;">Thêm mới
            </button>
            <nav class="navbar navbar-expand-lg" style="margin-top:-55px;float:right;">
                <ul class="navbar-nav mr-auto" style="font-size:16px;">
                    <li class="nav-item">
                        <div class="form-group p-2">
                            <label for="project_description" style="font-size:12px;">Nhập tên công việc</label>
                            <input @input="changeOption()" class="form-control"
                                style="margin-top:3px;width:220px;height:33px;font-size:14px" type="text"
                                placeholder="Tên công việc" v-model="search">
                        </div>
                    </li>
                    <li class="nav-item" v-if="option2 != 15">
                        <div class="form-group p-2">
                            <label for="project_description" style="font-size:12px;">Chọn khoảng thời gian</label>
                            <date-picker style="margin-top:3px; width: 100%;" v-model="dateRange" type="date" range
                                placeholder="Vui lòng chọn khoảng thời gian" @change="changeOption()">
                            </date-picker>
                        </div>
                    </li>
                    <!--<li class="nav-item">
                        <div class="form-group p-1">
                            <DatePicker style="width: 100%; margin-top: 33px" v-model="startTime" value-type="format" type="date"
                                placeholder="Ngày bắt đầu" @change="changeOption()">
                            </DatePicker>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="form-group p-1">
                            <DatePicker style="width: 100%; margin-top: 33px" v-model="endTime" value-type="format" type="date"
                            placeholder="Ngày kết thúc" @change="changeOption()">
                            </DatePicker>
                        </div>
                    </li>-->
                    <li class="nav-item">
                        <div class="form-group p-2">
                            <label for="project_description" style="font-size:12px">Theo dự án</label>
                            <select class="form-select" @change="changeOption()" v-model="project" style="width:160px">
                                <option value="0" selected="selected">Tất cả</option>
                                <option value="1">Trống</option>
                                <option v-for="(project, index) in projects" :key="index" :value="project.id">
                                    {{ project.project_name }}</option>
                            </select>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="form-group p-2">
                            <label for="project_description" style="font-size:12px">Theo bộ phận</label>
                            <select @change="changeOption()" class="form-select" v-model="option1" style="width:160px">
                                <option value="12" disabled>Lựa chọn</option>
                                <option value="1">Tất cả</option>
                                <option value="2">Dev</option>
                                <option value="3"> Game design</option>
                                <option value="4">Art</option>
                                <option value="5">Tester</option>
                                <option value="11">Marketing</option>
                            </select>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="form-group p-2">
                            <label for="project_description" style="font-size:12px">Người thực hiện</label>
                            <select class="form-select" @change="changeOption()" v-model="performer"
                                style="width:160px">
                                <option value="0" selected="selected">Tất cả</option>
                                <option value="20" >Trống</option>
                                <option v-for="(user, index) in users" :key="index" :value="user.id">{{ user.fullname }}
                                </option>
                            </select>
                        </div>
                    </li>
                    <li v-if="option2 != 2 &&  option2 != 5" class="nav-item">
                        <div class="form-group p-2">
                            <label for="project_description" style="font-size:12px">Theo trạng thái</label>
                            <select @change="changeOption()" class="form-select" v-model="option" style="width:160px">
                                <option value="10">Tất cả</option>
                                <option value="0">Quá hạn</option>
                                <option value="1">Đang chờ </option>
                                <option value="2">Đang tiến hành </option>
                                <option value="3">Tạm dừng</option>
                                <option v-if="option2 != 1" value="5" >Chờ feedback</option>
                                <option value="6">Làm lại</option>
                                <option v-if="option2 != 1" value="4" >Hoàn thành</option>
                            </select>
                        </div>
                    </li>
                    <li style="margin-top:38px;">
                        <button class="btn btn-outline-secondary "><a v-bind:href="'/my_work'">Việc của tôi</a></button>
                    </li> &ensp;
                    <li class="nav-item">
                        <button class="btn btn-outline-info" @click="ShowInfoListWork()" type="button" data-toggle="collapse"
                            data-target="#collapseExample_1" aria-expanded="false" aria-controls="collapseExample" style="margin-top: 38px">
                            Thông tin
                        </button>
                        <div class="col-lg">
                            <div class="collapse info-list-work" id="collapseExample_1" v-if="showInfoListWork">
                                <div>
                                    <table style="width:100%; border: 0px" class="my-work">
                                        <h4 style="margin: 20px 0px 20px 130px;">Thông tin làm việc</h4>
                                        <tr>
                                            <td><b>Tổng số công việc của bạn</b></td>
                                            <td>:</td>
                                            <td>{{ summary.total }}</td>
                                        </tr>
                                        <tr>
                                            <td><b>Đang làm</b></td>
                                            <td>:</td>
                                            <td>{{ summary.total_processing }}</td>
                                        </tr>
                                        <tr>
                                            <td><b>Tạm dừng</b></td>
                                            <td>:</td>
                                            <td>{{ summary.total_pause }}</td>
                                        </tr>
                                        <tr>
                                            <td><b>Hoàn thành</b></td>
                                            <td>:</td>
                                            <td>{{ summary.total_complete }}</td>
                                        </tr>
                                        <tr>
                                            <td><b>Thời gian làm việc dự kiến trong ngày</b></td>
                                            <td>:</td>
                                            <td>{{ summary.totalTime }}</td>
                                        </tr>
                                        <tr>
                                            <td><b>Thời gian làm việc thực tế trong ngày</b></td>
                                            <td>:</td>
                                            <td>{{ summary.totalRealtime }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="float-right p-2">
                                    <button type="submit" class="btn btn-secondary p-2" @click="ShowInfoListWork()">Đóng</button>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </nav>
            <Paginate style=" margin:10px 0px 0px 10px;" v-model="paginate" :pagechange="onPageChange"></Paginate>
            <table class="table-striped table-responsive table-hover result-point"
                style="width:99%; margin: 10px 0px 0px 10px">
                <thead class="point-table-head">
                    <tr style="text-align: center;">
                        <th scope="col">STT</th>
                        <th scope="col" width="500px">Tên công việc</th>
                        <th scope="col" width="150px">Loại công việc</th>
                        <th scope="col" width="150px" >Cấp độ công việc</th>
                        <th scope="col" width="150px">Dự án</th>
                        <th scope="col" width="200px">Công việc cha</th>
                        <th scope="col" width="80px">Bắt đầu</th>
                        <th scope="col" width="60px">Thời lượng dư kiến (Giờ)</th>
                        <th scope="col" width="80px">Kết thúc</th>
                        <th scope="col" width="60px">Thời lượng thực tế (Giờ)</th>
                        <th scope="col" width="50px">Trọng số</th>
                        <th scope="col" width="200px">Người thực hiện</th>
                        <th scope="col" width="100px">Bộ phận</th>
                        <th scope="col" width="40px">Tiến độ</th>
                        <th scope="col" width="145px">Trạng thái</th>
                        <th scope="col" width="120px">Thao tác</th>
                    </tr>
                </thead>
                <tbody v-for="(item, index) in list" :key="item.id">
                    <tr style="text-align:center;">
                        <td>{{ index + 1 }}</td>
                        <td scope="row" style="text-align:left;">
                            <div style="display: flex; font-size:12px;">
                                <textarea v-if="item.task_name == 'Click để thay đổi nội dung'"
                                    style=" font-weight: bold; font-size:16px; width:100%; border:0px; word-wrap:break-word; resize: none;"
                                    @change="changeTaskName($event, item.id)" v-model="item.task_name">
                            </textarea>
                                <textarea v-else style="width:100%; border:0px; word-wrap:break-word; resize: none;"
                                    @change="changeTaskName($event, item.id)" v-model="item.task_name">
                            </textarea>
                                <p @click="showModalEditTask(item.id)">
                                    <i class="fas fa-info-circle"
                                        style="font-size:16px; margin-top: 15px; cursor: pointer" />
                                </p>
                            </div>
                        </td>
                        <td>
                            <select class="form-select form-select-sm" aria-label=".form-select-sm example"
                                @change="changeSticker($event, item.id)" v-model="item.task_sticker">
                                <option v-for="(sticker, index) in stickers" :key="index" :value="sticker.sticker_name">
                                    {{ sticker.sticker_name }}
                                </option>
                            </select>
                            <!--<multiselect
                                v-model="item.task_sticker"
                                :options="stickers"
                                value="sticker_name"
                                label="sticker_name"
                                :close-on-select="true"
                                :show-labels="true"
                                placeholder="Chọn"
                                @select="changeSticker($event ,item.id)">
                            </multiselect>-->
                        </td>
                        <td>
                            <select class="form-select form-select-sm" aria-label=".form-select-sm example"
                                @change="changePriority($event, item.id)" v-model="item.task_priority">
                                <option v-for="(priority, index) in priorities" :key="index"
                                    :value="priority.priority_label">Level
                                    {{ priority.priority_label }}</option>
                            </select>
                        </td>
                        <td style="text-align:left">
                            <select class="form-select" @change="changeProject($event, item.id)"
                                v-model="item.project_id">
                                <option value="1" disabled>Chọn dự án</option>
                                <option value="" >Bỏ chọn</option>
                                <option v-for="(project, index) in projects" :key="index" :value="project.id">
                                    {{ project.project_name }}</option>
                            </select>
                            <!--<multiselect
                            size="12px"
                            v-model="item.project_id"
                            :options="projects"
                            value="id"
                            label="project_name"
                            :close-on-select="true"
                            :show-labels="true"
                            placeholder="Vui lòng chọn"
                            @select="changeProject($event ,item.id)">
                            </multiselect>-->
                        </td>
                        <!--@open="getTaskByProject(item.project_id)"-->
                        <td>
                            <div>
                                <treeselect :options="tasks" :load-options="loadOptions"
                                    @open="getTaskByProject(item.project_id)"
                                    @select="changeTaskParent($event, item.id)" loadingText="Loading..."
                                    v-model="item.task_parent" :show-count="true" />
                            </div>
                        </td>
                        <td>
                            <DatePicker style="width: 120px" v-model="item.start_time" value-type="format" type="date"
                                placeholder="Select time" @change="changeStartTime($event, item.id)">
                            </DatePicker>
                        </td>
                        <td><input style="width:100%; border:0px; text-align:right;"
                                @change="changeTime($event, item.id)" v-model="item.time"></td>
                        <td>
                            <DatePicker style="width: 120px" v-model="item.end_time" value-type="format" type="date"
                                placeholder="Select time" @change="changeEndTime($event, item.id)">
                            </DatePicker>
                        </td>
                        <td style=" text-align:right;">
                            <input style="width:100%; border:0px; text-align:right;"
                                @change="changeRealTime($event, item.id)" v-model="item.real_time">
                        </td>
                        <td> <input style="width:100%; border:0px;" @change="changeWeight($event, item.id)"
                                v-model="item.weight"></td>
                        <td>
                            <select class="form-select form-select-sm" aria-label=".form-select-sm example"
                                @change="changePerformer($event, item.id)" v-model="item.task_performer">
                                <option value="">Bỏ chọn</option>
                                <option v-for="(user, index) in users" :key="index" :value="user.id">{{ user.fullname }}
                                </option>
                            </select>
                            <!--<multiselect
                                v-model="item.task_performer"
                                :options="users"
                                value="id"
                                label="fullname"
                                :close-on-select="true"
                                :show-labels="true"
                                placeholder="Vui lòng chọn"
                                @select="changePerformer($event ,item.id)"
                                >
                            </multiselect>-->
                        </td>
                        <td>
                            <div style="display: flex">
                                <select class="form-select form-select-sm" aria-label=".form-select-sm example"
                                    @change="changeDepartment($event, item.id)" v-model="item.task_department">
                                    <option value="">Bỏ chọn</option>
                                    <option value="2">Dev</option>
                                    <option value="3">Game Design</option>
                                    <option value="4">Art</option>
                                    <option value="5">Tester</option>
                                    <option value="11">Marketing</option>
                                </select>
                            </div>
                        </td>
                        <td>{{ item.progress }}</td>
                        <td>
                            <div style="display: flex">
                                <select class="form-select form-select-sm" aria-label=".form-select-sm example"
                                    @change="changeStatus($event, item.id)" v-model="item.status">
                                    <option value="1">Đang chờ</option>
                                    <option value="2">Đang tiến hành</option>
                                    <option value="3">Tạm dừng</option>
                                    <option value="5">Chờ feedback</option>
                                    <option value="6">Làm lại </option>
                                    <option value="4">Hoàn thành </option>
                                    <option value="0">Đã quá hạn</option>
                                </select>
                            </div>
                        </td>
                        <td>
                            <button style="height: 25px;font-size:12px;" class="btn btn-danger"
                                @click="deleteTask($event, item.id)">Xóa</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <Paginate style="float:right; padding: 10px;" v-model="paginate" :pagechange="onPageChange"></Paginate>
            <div>
                <div ref="modalCreateTask" class="modal" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document" style=" max-width: 60%;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Tạo mới Công việc</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                    @click="closeModalCreateTask()">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <create-task v-if="showModal" :users="users" :groupUsers="groupUsers"
                                    :priorities="priorities" :stickers="stickers" :projects="projects"
                                    :projectId="projectId" @handleCreateTask="handleCreateTask" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import { $get, $post } from "../../ultis";
import Paginate from "../../components/Paginate";
import Multiselect from 'vue-multiselect';
import CreateTask from "./CreateTask";
import DatePicker from 'vue2-datepicker';
import Treeselect from '@riophae/vue-treeselect';
import '@riophae/vue-treeselect/dist/vue-treeselect.css';
import 'vue2-datepicker/index.css';
import _ from "lodash";


export default {
    name: "ListWork",
    components: { DatePicker, Multiselect, CreateTask, Treeselect, Paginate },
    props: [, 'paginate', 'users', 'groupUsers', 'priorities', 'stickers', 'projects', 'search',
        'startTime', 'endTime', 'taskPerformer', 'task_performer', 'taskDepartment', 'status', 'list', 'currentUser'],
    data() {
        return {
            paginate: [],
            tasks: [],
            dateRange: '',
            search: '',
            option: 10,
            option1: 12,
            option2: 15,
            performer: 0,
            project: 0,
            project_id: '',
            startTime: '',
            endTime: '',
            toggle: false,
            show: false,
            list: [],
            showModal: false,
            showListWork: true,
            showFilter: false,
            showInfoListWork: false,
            projects: [],
            departments: [],
            summary: '',
            taskId: 0,
            showModalEdit: false,
            taskEditId: 0,
            users: [],
            values: [],
            groupUsers: [],
            priorities: [],
            stickers: [],
            taskPerformer: '',
            task_performer: '',
            taskDepartment: 0,
            status: '',
            currentUser: '',
            task_sticker: '',
        }
    },
    created() {
        this.getProjects();
        this.getAllPriority();
        this.getAllSticker();

        this.getAllUser();
        this.getGroupUsers();
        this.getListWorks();
    },
    methods: {

        changeOption(page) {
            this.getListWorks(page);
            this.getAllUser(page);
            this.getAllSticker(page);
        },

        async loadOptions({ action, parentNode, callback }) {
            const res = await $get('/tasks/get_all', { project_id: parentNode.project_id, task_parent: parentNode.id })

            if (res.code == 200) {
                parentNode.children = res.data;
            }
        },

        async getTaskByProject(projectId, taskId) {
            const res = await $get('/tasks/get_all', { project_id: projectId, task_id: taskId ?? 0 })

            if (res.code == 200) {
                this.tasks = res.data;
                if (taskId) {
                    this.item.task_parent = taskId;
                }
            }
        },
        async NewTask() {
            const res = await $get('/tasks/list_new_task');

            if (res.code == 200) {
                toastr.success('Thêm mới thành công');
                this.getListWorks();
            }
        },
        async NewTaskToday() {
            const res = await $get('/tasks/list_new_task_today');

            if (res.code == 200) {
                toastr.success('Thêm mới thành công');
                this.getListWorks();
            }
        },
        async getAllPriority() {
            const res = await $get('/priorities/get_all');
            if (res.code == 200) {
                this.priorities = res.data;
            }
        },
        async getAllSticker() {

            let params = {};

            if (this.option1 && this.option1 != 1) {
                params.task_department = this.option1;
            }

            const res = await $get('/stickers/get_all', params);
            if (res.code == 200) {
                this.stickers = res.data;
            }
        },
        async getProjects() {
            const res = await $get('/projects/get_all');

            this.projects = res.projects
        },
        async getAllUser() {

            let params = {};

            if (this.option1 && this.option1 != 1) {
                params.task_department = this.option1;
            }

            const res = await $get('/user/all_user', params);
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
        handleShowFilter() {
            this.showFilter = !this.showFilter
        },
        ShowInfoListWork() {
            this.showInfoListWork = !this.showInfoListWork
        },
        ShowInfoListWork() {
            this.showInfoListWork = !this.showInfoListWork
        },
        showModalEditTask(id) {
            this.showModalEdit = true;
            $(this.$refs.modalUpdateTask).modal('show');
            this.taskEditId = id;
        },
        closeModalEditTask() {
            $(this.$refs.modalUpdateTask).modal('hide');
            this.showModalEdit = false;
            this.taskEditId = 0;
        },

        filterTask() {
            this.getListWorks();
        },
        async getListWorks(page) {

            console.log(page, 'page');

            let params = {
                page: page ?? 1
            };

            if (this.dateRange) {
                params.start_time = this.dateRange.length > 1 ? moment(this.dateRange[0]).format('YYYY-MM-DD') : moment().startOf('month').format('YYYY-MM-DD');
                params.end_time = this.dateRange.length > 1 ? moment(this.dateRange[1]).format('YYYY-MM-DD') : moment().endOf('month').format('YYYY-MM-DD');
            }

            if (this.option && this.option != 10) {
                params.status = this.option;
            }

            if (this.option1 && this.option1 != 1) {
                params.task_department = this.option1;
            }

            if (this.option2) {
                params.status2 = this.option2;
            }

            if (this.project && this.project != 0) {
                params.project_id = this.project;
            }

            if (this.performer && this.performer != 0) {
                params.task_performer = this.performer;
            }
            if (this.search) {
                params.search = this.search || '';
            }

            const res = await $get('/list-works', params);

            if (res.code == 200) {
                this.list = res.tasks;
                this.paginate = res.paginate;
                this.summary = res.summary;
            }
        },

        onPageChange(page) {
            this.getListWorks(page);
        },

        async changeProject(e, taskId) {
            const res = await $post(`/tasks/change-project/${taskId}`, { project_id: e.target.value });

            if (res.code == 200) {
                toastr.success(res.message);
            }
        },
        async changePerformer(e, taskId) {
            const res = await $post(`/tasks/change-performer/${taskId}`, { task_performer: e.target.value });

            if (res.code == 200) {
                toastr.success(res.message);
            }
        },
        async changeTaskParent(e, taskId) {
            const res = await $post(`/tasks/change-task_parent/${taskId}`, { task_parent: e.id });

            if (res.code == 200) {
                toastr.success(res.message);
            }
        },
        async changeStatus(e, taskId) {
            const res = await $post(`/tasks/change-status/${taskId}`, { status: e.target.value });

            if (res.code == 200) {
                toastr.success(res.message);
            }
        },
        async changeTaskName(e, taskId) {
            const res = await $post(`/tasks/change-task_name/${taskId}`, { task_name: e.target.value });

            if (res.code == 200) {
                toastr.success(res.message);
            }
        },
        async changeStartTime(e, taskId) {
            const res = await $post(`/tasks/change-start_time/${taskId}`, { start_time: e });

            if (res.code == 200) {
                toastr.success(res.message);
            }
        },
        async changeEndTime(e, taskId) {
            const res = await $post(`/tasks/change-end_time/${taskId}`, { end_time: e });

            if (res.code == 200) {
                toastr.success(res.message);
            }
        },
        async changeTime(e, taskId) {
            const res = await $post(`/tasks/change-time/${taskId}`, { time: e.target.value });

            if (res.code == 200) {
                toastr.success(res.message);
            }
        },
        async changePause(e, taskId) {
            const res = await $post(`/tasks/change-pause/${taskId}`, { time_pause: e.target.value });

            if (res.code == 200) {
                toastr.success(res.message);
            }
        },
        async changeRealTime(e, taskId) {
            const res = await $post(`/tasks/change-real_time/${taskId}`, { real_time: e.target.value });

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
        async changeWeight(e, taskId) {
            const res = await $post(`/tasks/change-weight/${taskId}`, { weight: e.target.value });

            if (res.code == 200) {
                toastr.success(res.message);
            }
        },
        async deleteTask(e, taskId, page) {
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
    },
    watch: {
        'task.project_id': function (newVal) {
            if (newVal.id) {
                this.getTaskByProject(newVal.id, 0);
            }
        },
        'taskParentId': function (newVal) {
            console.log(newVal, 'new val');
        }
    }
}
</script>

<style scoped>
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

.info-list-work {
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

/*
* {
    box-sizing: border-box;
    margin: 0;
    font-family: verdana;
    font-size: 16px;
}

#app {
    background: #e7ecff;
    width: 100%;
    height: 100vh;
    transition: 0.3s ease-in;
}

#app.darkmode {
    background: #333;
    transition: 0.3s ease-in;
}

.container {
    width: 100%;
    padding-right: 15px;
    padding-left: 15px;
    margin-right: auto;
    margin-left: auto;
}

.row {
    display: flex;
    flex-wrap: wrap;
    margin-right: -15px;
    margin-left: -15px;
}

.grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    grid-column-start: 1;
    grid-gap: 25px;
    margin: auto;
}

/* -------- Button Dark / Light ------- */
/*figure {
    flex: 1;
    flex-direction: column;
    justify-content: center;
}

.toggle {
    display: flex;
    position: relative;
    width: 7.5rem;
    height: 2.5rem;
    background: white;
    border-radius: 6px;
    align-self: center;
    user-select: none;
    margin: 2rem;
    box-shadow: 0 15px 20px -10px #b3c9fa;
    transition: 0.2s ease-in;
}

.darkmode .toggle {
    box-shadow: 0 15px 20px -10px #181818;
    transition: 0.2s ease-in;
}

.toggle:after,
.toggle:before {
    flex: 1;
    text-align: center;
    line-height: 2.5rem;
}

.toggle:after {
    content: "Light";
}

.toggle:before {
    content: "Dark";
}

#c {
    display: none;
}

#bt {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    perspective: 1000;
    cursor: pointer;
}

.card {
    position: relative;
    background: #599cff;
    border-radius: 6px;
    transition: 0.4s;
    width: 50%;
    height: 2.5rem;
    pointer-events: none;
}

input:checked+label .card {
    background: #272727;
    border-radius: 6px;
}

.slide .card {
    transform: translate(0);
}

.slide input:checked+label .card {
    transform: translateX(3.75rem);
}

/* -------- End button Dark / Light ------- */

/*
.cards {
    background: #fff;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 10px;
    min-width: 160px;
    height: 100px;
    transition: 0.2s ease !important;
    box-shadow: 0 15px 20px -10px #b3bdfa;
}

.darkmode .cards {
    box-shadow: 0 25px 15px -15px #242424;
}

.darkmode .cards:not(.not) {
    background: #222;
    transition: 0.3s ease-in;
    box-shadow: 0 25px 15px -15px #242424;
}

.cards span {
    padding: 0 1rem;
    transition: 0.4s ease-in;
}

.darkmode .cards:not(.not) span {
    color: white;
}

@media (max-width: 600px) {
    .grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 380px) {
    .grid {
        grid-template-columns: repeat(1, 1fr);
    }
}*/
</style>
