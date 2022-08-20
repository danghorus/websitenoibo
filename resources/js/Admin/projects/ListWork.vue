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
                <div class="form-group p-2">
                    <label for="project_description">Theo bộ phận</label>
                    <multiselect v-model="taskDepartment" :options="departments" value="value" label="label"
                        :close-on-select="true" :show-labels="true" placeholder="Vui lòng chọn">
                    </multiselect>
                </div>
                <div class="form-group p-2">
                    <label for="project_description">Theo người thực hiện</label>
                    <multiselect v-model="taskPerformer" :options="users" value="id" label="fullname"
                        :close-on-select="true" :show-labels="true" placeholder="Vui lòng chọn">
                    </multiselect>
                </div>
                <div class="form-group p-2">
                    <label for="project_description">Theo trạng thái</label>
                    <select class="form-select" v-model="option">
                        <option value="1">Tất cả</option>
                        <option value="2">Đang tiến hành </option>
                        <option value="3">Tạm dừng</option>
                        <option value="4">Hoàn thành</option>
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
            <h4 style="margin-left: 10px;">Danh sách công việc</h4>&emsp;
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
                        <th scope="col" width="430px">Tên công việc</th>
                        <th scope="col" width="430px">Công việc cha</th>
                        <th scope="col">Bắt đầu</th>
                        <th scope="col">Thời lượng (Giờ)</th>
                        <th scope="col">Thời lượng thực tế (Giờ)</th>
                        <th scope="col">Người thực hiện</th>
                        <th scope="col" width="120px">Bộ phận</th>
                        <th scope="col" width="150px">Trạng thái</th>
                        <th scope="col">Thao tác</th>
                    </tr>
                </thead>
                <tr v-for="(item, index) in list" :key="item.id" style="text-align:center;">
                    <template v-if="item.task_performer != null">
                        <td>{{ index + 1 }}</td>
                        <td scope="row" style="text-align:left;">
                            <p @click="showModalEditTask(item.id)">
                                {{ item.task_name }}
                            </p>
                        </td>
                        <td style="text-align:left;">{{ item.task_parent }}
                        </td>
                        <td>{{ item.start_time }}</td>
                        <td>{{ item.time }}</td>
                        <td>{{ item.time_real}}</td>
                        <td>{{ item.fullname }}</td>
                        <td>{{ item.department_label }}</td>
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

                                    <option value="1" v-if="item.status == 1" disabled>Lựa chọn</option>
                                    <option value="2" v-if="item.status == 2" disabled>Lựa chọn</option>
                                    <option value="3" v-if="item.status == 3" disabled>Lựa chọn</option>
                                    <option value="5" v-if="item.status == 5" disabled>Lựa chọn</option>
                                    <option value="6"
                                        :disabled="item.status == 1 || item.status == 2 || item.status == 3 || item.status == 6 ">
                                        Làm lại </option>
                                    <option value="4"
                                        :disabled="item.status == 1 || item.status == 2 || item.status == 3 || item.status == 6 || item.status == 4">
                                        Hoàn thành </option>
                                    <option value="0">Đã quá hạn</option>
                                </select>
                            </div>
                        </td>
                    </template>
                </tr>
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
    name: "ListWork",
    components: { CreateTask },
    el: '#infoListWork',
    components: { Multiselect },
    props: ['projectId', 'users', 'groupUsers', 'priorities', 'stickers', 'projects', 'searchProjectId', 'search',
        'startTime', 'taskPerformer', 'taskDepartment', 'status', 'list', 'currentUser'],
    data() {
        return {
            option: 1,
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
            taskPerformer: '',
            taskDepartment: 0,
            departments: [
                { value: 1, label: 'Admin' },
                { value: 2, label: 'Dev' },
                { value: 3, label: 'Game design' },
                { value: 4, label: 'Art' },
                { value: 5, label: 'Tester' },
                { value: 6, label: 'Điều hành' },
                { value: 7, label: 'Hành chính nhân sự' },
                { value: 8, label: 'Kế toán' },
                { value: 9, label: 'Phân tích dữ liệu' },
                { value: 10, label: 'Support' },
            ],
            arrStatus: [
                { value: 0, label: 'Mới tạo' },
                { value: 1, label: 'Đang chờ' },
                { value: 2, label: 'Đang tiến hành' },
                { value: 3, label: 'Tạm dừng' },
                { value: 4, label: 'Hoàn thành' },
            ],
            status: '',
            currentUser: ''
        }
    },
    created() {
        this.getAllUser();
        this.getProjects();
        this.getListWorks();
    },
    methods: {
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
        showModalCreateTask() {
            this.showModal = true;
            $(this.$refs.modalCreateTask).modal('show');
        },
        closeModalCreateTask() {
            $(this.$refs.modalCreateTask).modal('hide');
            this.showModal = false;
        },
        showModalEditProject() {
            $(this.$refs.modalCreate).modal('show');
            this.isShowModalEditProject = true;
        },
        closeModalEditProject() {
            $(this.$refs.modalCreate).modal('hide');
            this.isShowModalEditProject = false;
        },
        showModalConfirm(id) {
            this.showModal = true;
            $(this.$refs.modalConfirm).modal('show');
            this.taskId = id;
        },
        closeModalConfirm() {
            this.showModal = false;
            $(this.$refs.modalConfirm).modal('hide');
            this.taskId = 0;
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
        async deleteTask() {
            const res = await $post(`/tasks/delete/${this.taskId}`);

            if (res.code == 200) {
                toastr.success('Xóa thành công');
                this.showModal = false;
                $(this.$refs.modalConfirm).modal('hide');
                this.taskId = 0;
                this.handleGetTasks();
            }
        },
        async copyTask(id) {
            const res = await $get(`/tasks/copy/${id}`);

            if (res.code == 200) {
                toastr.success('Copy thành công');
                this.$emit('getTaskTimeLine');
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
            this.showFilter = false;
        },
        async getListWorks() {
            let params = {};

            if (this.option && this.option != 1) {
                params.status = this.option;
            }

            if (this.project && this.project.id > 0) {
                params.project_id = this.project.id;
            }

            if (this.taskDepartment && this.taskDepartment > 0) {
                params.task_department = this.taskDepartment;
            }
            let filters = {
                project_id: this.searchProjectId ? this.searchProjectId.id : 0,
                search: this.search || '',
                start_time: this.startTime || '',
                task_performer: this.taskPerformer || 0,
                task_department: this.taskDepartment ? this.taskDepartment.value : 0,
                status: this.status ? this.status.value : -1,
            }


            const res = await $get('/list-works', params);

            if (res.code == 200) {
                this.list = res.tasks;
                this.summary = res.summary
            }
        },
        async changeStatus(e, taskId) {
            const res = await $post(`/tasks/change-status/${taskId}`, { status: e.target.value });

            if (res.code == 200) {
                toastr.success(res.message);
                this.getListWorks();
            }
        },

        async getProjects() {
            const res = await $get('/projects/get_all');

            this.projects = res.projects
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
