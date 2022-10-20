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
								<option value="4" v-if="option2 == 3">Hoàn thành</option>
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
                            style="margin-top:  38px"> Thông tin thời gian
                        </button>
                        <div class="col-lg">
                            <div class="collapse info-my-work" id="collapseExample_1" v-if="showInfoMyWork">
                                <div>
                                    <table style="width:100%; border: 0px" class="my-work">
                                        <h4 style="margin: 20px 0px 20px 130px;">Thông tin làm việc</h4>
                                        <tr>
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
                                        </tr>
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
            <select @change="changeOption()" class="form-select col-lg-2"
                    style="position: absolute; left: 25px; top: 105px; width:220px; height:34px;" v-model="option2">
				<option value="3">Tất cả</option>
                <option value="1">Chưa hoàn thành</option>
                <option value="5">Chờ feedback</option>
                <option value="2">Đã hoàn thành</option>
            </select>&emsp;
            <p @click="NewTask()">
                <button class="btn btn-success btn-sm" 
                style="height:35px; font-size:15px; margin: -40px 0px 0px 300px;">Thêm mới</button>
            </p>
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
                        <th scope="col" width="10%">Dự án</th>
                        <!--<th scope="col" width="10%">Công việc cha</th>-->
                        <th scope="col" width="7%">Bộ phận</th>
                        <th scope="col" width="6%">Bắt đầu</th>
                        <th scope="col" width="3%">Thời lượng (Giờ)</th>
                        <th scope="col" width="6%">Kết thúc</th>
                        <th scope="col" width="3%">Thời lượng thực tế (Giờ)</th>
						<th scope="col" width="4%">Tiến độ</th>
                        <th scope="col" width="8%">Trạng thái</th>
                        <th scope="col" width="10%">Cập nhật trạng thái</th>
                        <th scope="col" width="10%">Thao tác</th>
                    </tr>
                </thead>
                <tbody v-for="(item, index) in list" :key="item.id" style="text-align:center;">
                    <tr>
                        <td>{{index + 1 }}</td>
                        <td scope="row" style="text-align:left;">
                            <div style="display: flex; font-size:12px;">
                                <input style="width:100%; border:0px;" @change="changeTaskName($event, item.id)" v-model="item.task_name">
                                <p >
                                    <i class="fas fa-info-circle" style="font-size:16px; margin-top: 15px; cursor: pointer" />
                                </p>
                            </div>
                        </td>
                        <td style="text-align:left;">
                            <select class="form-select"  @change="changeProject($event, item.id)" v-model="item.project_id">
                                <option value="1" disabled>Chọn dự án</option>
                                <option v-for="(project, index) in projects" :key="index" :value="project.id">{{project.project_name}}</option>
                            </select>
                        </td>
                        <!--<td style="text-align:left;">
                            <select class="form-select" @change="changeParent($event, item.id)" v-model="item.task_parent">
                                <option value="" disabled>Lựa chọn</option>
                                <option v-for="(task, index) in list_task" :key="index" :value="task.id">{{task.task_name}}</option>
                            </select>
                        </td>-->
                        <!--<td>
                            <treeselect
                                :options="list"
                                :load-options="loadOptions"
                                loadingText="Loading..."
                                v-model="item.task_parent"
                            />
                        </td>-->
                        <td style="text-align:left;">
                            <select class="form-select form-select-sm" aria-label=".form-select-sm example"
                                @change="changeDepartment($event, item.id)" v-model="item.task_department">
                                <option value="null" disabled >Lựa chọn</option>
                                <option value="2">Dev</option>
                                <option value="3">Game Design</option>
                                <option value="4">Art</option>
                                <option value="5">Tester</option>
                            </select>
                        </td>
                         <td>
                            <DatePicker 
                                style="width: 120px" 
                                v-model="item.start_time" 
                                value-type="format" 
                                type="date"
                                placeholder="Select time" 
                                @change="changeStartTime($event, item.id)">
                            </DatePicker>
                        </td>
                        <td>{{item.time}}</td>
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
                            <input 
                                style="width:100%; border:0px; text-align:right;" 
                                @change="changeRealTime($event, item.id)"
                                v-model="item.real_time">
                        </td>
                        <td>
                            <input 
                            type="number" 
                            style="width: 100%; border:0px;" 
                            min="0" max="100" 
                            @change="changeProgress($event, item.id)" 
                            v-model="item.progress">
                        </td>
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
                            <button style="height: 25px;font-size:12px;" class="btn btn-danger" @click="deleteTask($event, item.id)">Xóa</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>

import {$get, $post} from "../../ultis";
import Multiselect from 'vue-multiselect';
import Treeselect from '@riophae/vue-treeselect';
import DatePicker from 'vue2-datepicker';

export default {
    name: "MyWork",
    el: '#infoMyWork',
    components: { Multiselect, Treeselect, DatePicker },
    data() {
        return {
            option: 10,
            option2: 1,
            toggle: false,
            show: false,
            list: [],
            list_task: [],
            showModal: false,
            showMyWork: true,
            showFilter: false,
            showInfoMyWork: false,
            projects: [],
            project_id: '',
            task_parent: '',
            project: 0,
            summary: ''
        }
    },
    created() {
        this.getAllUser();
        this.getMyWorks();
        this.getProjects();
        this.getAllTasks();
    },
    methods: {
        changeOption() {
            this.getMyWorks();
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
        async getAllTasks() {
            
            const res = await $get('/tasks/all_task');

            if (res.code == 200) {
                this.list_task = res.data;
            }
        },
        handleGetTasks(res) {
            this.closeModalCreateTask_Parent()
            this.closeModalCreateTask();
            if (this.projectId > 0) {
                this.bus.$emit('submit', _.cloneDeep(res))
            } else {
                this.getTaskTimeLine();
            }
        },

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

            if (this.option2 && this.option2 != 3) {
                params.status2 = this.option2;
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
        async NewTask() {
            const res = await $get('/tasks/new_task');

            if (res.code == 200) {
                toastr.success('Thêm mới thành công');
                this.getMyWorks();
            }
        },
        async changeStartTime(e, taskId) {
            const res = await $post(`/tasks/change-start_time/${taskId}`, { start_time: e });

            if (res.code == 200) {
                toastr.success(res.message);
                this.getMyWorks();
            }
        },
        async changeEndTime(e, taskId) {
            const res = await $post(`/tasks/change-end_time/${taskId}`, { end_time: e});

            if (res.code == 200) {
                toastr.success(res.message);
                this.getMyWorks();
            }
        },
        async changeRealTime(e, taskId) {
            const res = await $post(`/tasks/change-real_time/${taskId}`, { real_time: e.target.value });

            if (res.code == 200) {
                toastr.success(res.message);
                this.getMyWorks();
            }
        },
        async changeTaskName(e, taskId) {
            const res = await $post(`/tasks/change-task_name/${taskId}`, { task_name: e.target.value });

            if (res.code == 200) {
                toastr.success(res.message);
                this.getMyWorks();
            }
        },
        async changeProject(e, taskId) {
            const res = await $post(`/tasks/change-project/${taskId}`, { project_id: e.target.value });

            if (res.code == 200) {
                toastr.success(res.message);
                this.getMyWorks();
            }
        },
        async changeDepartment(e, taskId) {
            const res = await $post(`/tasks/change-department/${taskId}`, { task_department: e.target.value });

            if (res.code == 200) {
                toastr.success(res.message);
                this.getMyWorks();
            }
        },
        async changeParent(e, taskId) {
            const res = await $post(`/tasks/change-parent/${taskId}`, { task_parent: e.target.value });

            if (res.code == 200) {
                toastr.success(res.message);
                this.getMyWorks();
            }
        },
        async deleteTask(e, taskId) {
            const res = await $post(`/tasks/delete/${taskId}`, { task_parent: e.target.value });

            if (res.code == 200) {
                toastr.success('Xóa thành công');
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

.modal-content {
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}
</style>
