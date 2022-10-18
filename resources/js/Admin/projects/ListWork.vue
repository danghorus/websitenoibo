<template>
    <div>
        <div class="mt-4">
            <h4 style="margin: -30px 0px 0px 25px;">Danh sách công việc</h4>
            <select  @change="changeOption()" class="form-select col-lg-2" style="position: absolute; left: 25px; top: 105px; width:220px; height:34px;"
            v-model="option2">
				<option value="3">Tất cả</option>
                <option value="10">Mới tạo</option>
                <option value="1">Chưa hoàn thành</option>
                <option value="5">Chờ feedback</option>
                <option value="2">Đã hoàn thành</option>
            </select>
            <nav class="navbar navbar-expand-lg" style="margin-top:-45px;float:right;">
                <ul class="navbar-nav mr-auto" style="font-size:16px;" >
                    <li class="nav-item">
                        <div class="form-group p-2">
                            <label for="project_description" style="font-size:12px;">Nhập tên công việc</label>
                            <input @input="changeOption()" class="form-control" style="width:250px;height:33px;font-size:14px" type="text" placeholder="Tên công việc" v-model="search">
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="form-group p-2">
                            <label for="project_description" style="font-size:12px">Theo ngày bắt đầu</label>
                            <DatePicker style="width: 100%" v-model="startTime" value-type="format" type="date"
                                placeholder="Select time" @change="changeOption()">
                            </DatePicker>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="form-group p-2">
                            <label for="project_description" style="font-size:12px">Theo dự án</label>
                            <select  class="form-select"  @change="changeOption()" v-model="project" style="width:200px">
                                <option value="0" selected="selected">Tất cả</option>
                                <option v-for="(project, index) in projects" :key="index" :value="project.id">{{project.project_name}}</option>
                            </select>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="form-group p-2">
                            <label for="project_description" style="font-size:12px">Theo bộ phận</label>
                            <select  @change="changeOption()" class="form-select" v-model="option1" style="width:200px">
                                <option value="1" >Tất cả</option>
                                <option value="2" >Dev</option>
                                <option value="3" > Game design</option>
                                <option value="4" >Art</option>
                                <option value="5" >Tester</option>
                                <option value="9" >Phân tích dữ liệu</option>
                            </select>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="form-group p-2">
                            <label for="project_description" style="font-size:12px">Người thực hiện</label>
                            <select class="form-select" @change="changeOption()" v-model="performer"  style="width:200px">
                                <option value="0" selected="selected">Tất cả</option>
                                <option v-for="(user, index) in users" :key="index" :value="user.id">{{user.fullname}}</option>
                            </select>
                        </div>
                    </li>
                    <li v-if="option2 == 1" class="nav-item">
                        <div class="form-group p-2">
                            <label for="project_description" style="font-size:12px">Theo trạng thái</label>
                            <select  @change="changeOption()" class="form-select" v-model="option" style="width:200px">
                                <option value="10">Tất cả</option>
                                <option value="0">Quá hạn</option>
                                <option value="1">Đang chờ </option>
                                <option value="2">Đang tiến hành </option>
                                <option value="3">Tạm dừng</option>
                                <option value="5">Chờ feedback</option>
                                <option value="6">Làm lại</option>
                            </select>
                        </div>
                    </li>
                    <li style="margin-top:38px;">
                        <button class="btn btn-outline-secondary "><a v-bind:href="'/my_work'">Việc của tôi</a></button>
                    </li>
                </ul>
            </nav>
            <table class="table-striped table-responsive table-hover result-point"
                style="width:99%; margin: 0px 0px 0px 10px">
                <thead class="point-table-head">
                    <tr style="text-align: center;">
                        <th scope="col">STT</th>
                        <th scope="col" width="500px">Tên công việc</th>
                        <th scope="col" width="200px">Dự án</th>
                        <th scope="col" width="300px">Công việc cha</th>
                        <th scope="col" width="80px">Bắt đầu</th>
                        <th scope="col" width="60px">Thời lượng (Giờ)</th>
                        <th scope="col" width="80px">Kết thúc</th>
                        <th scope="col" width="60px">Thời lượng thực tế (Giờ)</th>
                        <th scope="col" width="120px">Loại công việc</th>
                        <th scope="col" >Cấp độ công việc</th>
                        <th scope="col" width="50px">Trọng số</th>
                        <th scope="col" width="200px">Người thực hiện</th>
                        <th scope="col" width="100px">Bộ phận</th>
                        <th scope="col" width="40px">Tiến độ</th>
                        <th scope="col" width="145px">Thao tác</th>
                    </tr>
                </thead>
                <tbody v-for="(item, index) in list" :key="item.id" >
                    <tr style="text-align:center;">
                        <td>{{ index +1 }}</td>
                        <td scope="row" style="text-align:left;">
                            <input style="width:100%; border:0px;"  @change="changeTaskName($event, item.id)" v-model="item.task_name">
                        </td>
                        <td style="text-align:left;">
                            <select class="form-select" @change="changeProject($event, item.id)" v-model="item.project_id">
                                <option value="1" disabled>Chọn dự án</option>
                                <option v-for="(project, index) in projects" :key="index" :value="project.id">{{project.project_name}}</option>
                            </select>
                        </td>
                        <td>
                            <treeselect
                                :options="tasks"
                                @open="getTaskByProject(item.project_id)"
                                :load-options="loadOptions"
                                loadingText="Loading..."
                                v-model="item.task_parent"
                            />
                        </td>
                        <td>
                            <DatePicker style="width: 100%" v-model="item.start_time" value-type="format" type="date"
                                        placeholder="Select time" @change="changeStartTime($event, item.id)">
                            </DatePicker>
<!--                            <input type="date" style="width: 80%; border:0px;" @change="changeStartTime($event, item.id)" v-model="item.start_time">-->
                        </td>
                        <td><input style="width:100%; border:0px; text-align:right;"  @change="changeTime($event, item.id)" v-model="item.time"></td>
                        <td>
                            <input type="date" style="width: 80%; border:0px;" @change="changeEndTime($event, item.id)"
                                v-model="item.end_time">
                        </td>
                        <td style=" text-align:right;">
                            <input style="width:100%; border:0px; text-align:right;" @change="changeRealTime($event, item.id)" v-model="item.real_time">
                        </td>
                        <td>
                            <select class="form-select form-select-sm" aria-label=".form-select-sm example"
                                @change="changeSticker($event, item.id)" v-model="item.task_sticker">
                                <option v-for="(sticker, index) in stickers" :key="index" :value="sticker.sticker_name">{{sticker.sticker_name}}</option>
                            </select>
                        </td>
                        <td>
                             <select class="form-select form-select-sm" aria-label=".form-select-sm example"
                                @change="changePriority($event, item.id)" v-model="item.task_priority">
                                <option v-for="(priority, index) in priorities" :key="index" :value="priority.priority_label">Level {{priority.priority_label}}</option>
                            </select>
                        </td>
                        <td> <input style="width:100%; border:0px;"  @change="changeWeight($event, item.id)" v-model="item.weight"></td>
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
                        <td>{{item.progress}}</td>
                        <td>
                            <div style="display: flex">
                                <select class="form-select form-select-sm" aria-label=".form-select-sm example"
                                    @change="changeStatus($event, item.id)" v-model="item.status">
                                    <option value="1" >Đang chờ</option>
                                    <option value="2" >Đang tiến hành</option>
                                    <option value="3" >Tạm dừng</option>
                                    <option value="5" >Chờ feedback</option>
                                    <option value="6" >Làm lại </option>
                                    <option value="4" >Hoàn thành </option>
                                    <option value="0">Đã quá hạn</option>
                                </select>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>

import { $get, $post } from "../../ultis";
import Multiselect from 'vue-multiselect';
import CreateTask from "./CreateTask";
import DatePicker from 'vue2-datepicker';
import Treeselect from '@riophae/vue-treeselect';
import '@riophae/vue-treeselect/dist/vue-treeselect.css';
import 'vue2-datepicker/index.css';

export default {
    name: "ListWork",
    components: { DatePicker, Multiselect, CreateTask, Treeselect },
    props: ['projectId', 'users', 'groupUsers', 'priorities', 'stickers', 'projects', 'searchProjectId', 'search',
        'startTime', 'taskPerformer', 'task_performer', 'taskDepartment', 'status', 'list', 'currentUser'],
    data() {
        return {
            task: {
                project_id: ''
            },
            tasks: [],
            search:'',
            option: 10,
            option1: 2,
            option2: 1,
            performer: 0,
            project: 0,
            startTime: '',
            toggle: false,
            show: false,
            //list: [],
            showModal: false,
            showListWork: true,
            showFilter: false,
            projects: [],
            departments: [],
            summary: '',
            taskId: 0,
            showModalEdit: false,
            taskEditId: 0,
            users: [],
            groupUsers: [],
            priorities: [],
            stickers: [],
            taskPerformer: '',
            task_performer: '',
            taskDepartment: 0,
            status: '',
            currentUser: ''
        }
    },
    created() {
        this.getProjects();
        this.getAllPriority();
        this.getAllSticker();

        this.getAllUser();
        this.getListWorks();

        // this.getTaskByProject(3);
    },
    methods: {

        changeOption(){
            this.getListWorks();
        },

        async getInfoTask() {
            const res = await $get(`/tasks/detail/${this.taskId}`);

            if (res.code == 200) {
                this.task = res.data;
                this.values = res.user_related;
                this.getTaskByProject(this.task.project_id.id, this.task.task_parent ?? 0);
            }
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
        handleShowFilter() {
            this.showFilter = !this.showFilter
        },
        ShowInfoListWork() {
            this.showInfoListWork = !this.showInfoListWork
        },

        filterTask() {
            this.getListWorks();
        },
        async getListWorks() {
            let params = {};

            if (this.option && this.option != 10) {
                params.status = this.option;
            }

            if (this.option1 && this.option1 != 1) {
                params.task_department = this.option1;
            }

            if (this.option2 && this.option2 != 3) {
                params.status2 = this.option2;
            }

            if (this.project && this.project != 0) {
                params.project_id = this.project;
            }

            if (this.performer && this.performer != 0) {
                params.task_performer = this.performer;
            }
            if (this.startTime) {
                params.start_time = this.startTime ;
            }
            if(this.search){
                params.search = this.search || '';
            }

            const res = await $get('/list-works', params);

            if (res.code == 200) {
                this.list = res.tasks;
                this.summary = res.summary
            }
        },
        async changeProject(e, taskId) {
            const res = await $post(`/tasks/change-project/${taskId}`, { project_id: e.target.value });

            if (res.code == 200) {
                toastr.success(res.message);
                this.getListWorks();
            }
        },
        async changeStatus(e, taskId) {
            const res = await $post(`/tasks/change-status/${taskId}`, { status: e.target.value });

            if (res.code == 200) {
                toastr.success(res.message);
                this.getListWorks();
            }
        },
        async changeTaskName(e, taskId) {
            const res = await $post(`/tasks/change-task_name/${taskId}`, { task_name: e.target.value });

            if (res.code == 200) {
                toastr.success(res.message);
                this.getListWorks();
            }
        },
        async changeStartTime(e, taskId) {
            const res = await $post(`/tasks/change-start_time/${taskId}`, { start_time: e });

            if (res.code == 200) {
                toastr.success(res.message);
                this.getListWorks();
            }
        },
        async changeEndTime(e, taskId) {
            const res = await $post(`/tasks/change-end_time/${taskId}`, { end_time: e.target.value });

            if (res.code == 200) {
                toastr.success(res.message);
                this.getListWorks();
            }
        },
        async changeTime(e, taskId) {
            const res = await $post(`/tasks/change-time/${taskId}`, { time: e.target.value });

            if (res.code == 200) {
                toastr.success(res.message);
                this.getListWorks();
            }
        },
        async changePause(e, taskId) {
            const res = await $post(`/tasks/change-pause/${taskId}`, { time_pause: e.target.value });

            if (res.code == 200) {
                toastr.success(res.message);
                this.getListWorks();
            }
        },
        async changeRealTime(e, taskId) {
            const res = await $post(`/tasks/change-real_time/${taskId}`, { real_time: e.target.value });

            if (res.code == 200) {
                toastr.success(res.message);
                this.getListWorks();
            }
        },
        async changeDepartment(e, taskId) {
            const res = await $post(`/tasks/change-department/${taskId}`, { task_department: e.target.value });

            if (res.code == 200) {
                toastr.success(res.message);
                this.getListWorks();
            }
        },
        async changePerformer(e, taskId) {
            const res = await $post(`/tasks/change-performer/${taskId}`, { task_performer: e.target.value });

            if (res.code == 200) {
                toastr.success(res.message);
                this.getListWorks();
            }
        },
        async changeSticker(e, taskId) {

            const res = await $post(`/tasks/change-sticker/${taskId}`, {task_sticker: e.target.value });

            if (res.code == 200) {
                toastr.success(res.message);
                this.getListWorks();
            }
        },
        async changePriority(e, taskId) {
            const res = await $post(`/tasks/change-priority/${taskId}`, { task_priority: e.target.value });

            if (res.code == 200) {
                toastr.success(res.message);
                this.getListWorks();
            }
        },
        async changeWeight(e, taskId) {
            const res = await $post(`/tasks/change-weight/${taskId}`, { weight: e.target.value });

            if (res.code == 200) {
                toastr.success(res.message);
                this.getListWorks();
            }
        },
        async changeWeight(e, taskId) {
            const res = await $post(`/tasks/change-weight/${taskId}`, { weight: e.target.value });

            if (res.code == 200) {
                toastr.success(res.message);
                this.getListWorks();
            }
        },


        async getProjects() {
            const res = await $get('/projects/get_all');

            this.projects = res.projects
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
