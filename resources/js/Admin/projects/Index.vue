<template>
    <div class="col-lg-12">
        <div class="row">
            <list-project @chooseProject="chooseProject" :users="users" :groupUsers="groupUsers" :projects="projects" @handleShowTimeline="handleShowTimeline()" />
            <div class="col-lg-10">
                <div class="row">
                    <div
                        class="col-lg-6"
                        @click="showModalEditProject()"
                    >
                        <h4>{{ showTimeline? 'Timeline công việc': project.project_name }}
                            <i v-if="projectId" class="fas fa-pencil-alt" style="font-size: 25px; cursor: pointer"/>
                        </h4>
                    </div>
                    <div class="col-lg-6" style="margin: 0 0 20px -10px;">
                        <div class="input-seach float-right">
                            <input class="input-elevated" type="text" placeholder="Search" v-model="search">
                            <button class="btn btn-outline-secondary border-btn-search" @click="getTaskTimeLine()" style="width:100px;">
                                Tìm kiếm
                            </button> &ensp;
                            <button
                                class="btn-outline-secondary border-search"
                                @click="handleShowFilter()"
                                type="button"
                                data-toggle="collapse"
                                data-target="#collapseExample"
                                aria-expanded="false"
                                aria-controls="collapseExample"
                                style="float:right; width:130px;"
                            >
                                <i class="fas fa-filter"></i>
                                Tạo bộ lọc
                            </button> &ensp;
                            <button class="btn btn-primary float-right" style="width: 110px" @click="showModalCreateTask()">Thêm mới</button>
                            <button class="btn btn-primary float-right ml-2" style="width: 110px" @click="showModalConfigTask()">Cấu hình</button>
                        </div>
                    </div>
                </div>
                <div class="collapse search-collapse" id="collapseExample" v-if="showFilter">
                    <div class="form-group p-2">
                        <label for="project_description">Theo ngày bắt đầu</label>
                        <DatePicker
                            style="width: 100%"
                            v-model="startTime"
                            value-type="format"
                            type="date"
                            placeholder="Select time"
                        ></DatePicker>
                    </div>
                    <div class="form-group p-2">
                        <label for="project_description">Theo dự án</label>
                        <multiselect v-model="searchProjectId" :disabled="!showTimeline" :options="projects" value="id" label="project_name" :close-on-select="false" :show-labels="true" placeholder="Vui lòng chọn">
                        </multiselect>
                    </div>
                    <div class="form-group p-2">
                        <label for="project_description">Theo bộ phận</label>
                        <multiselect v-model="taskDepartment" :options="departments" value="value" label="label" :close-on-select="false" :show-labels="true" placeholder="Vui lòng chọn">
                        </multiselect>
                    </div>
                    <div class="form-group p-2">
                        <label for="project_description">Theo người thực hiện</label>
                        <multiselect v-model="taskPerformer" :options="users" value="id" label="fullname" :close-on-select="false" :show-labels="true" placeholder="Vui lòng chọn">
                        </multiselect>
                    </div>
                    <div class="form-group p-2">
                        <label for="project_description">Theo trạng thái</label>
                        <multiselect v-model="status" :options="arrStatus" value="value" label="label" :close-on-select="false" :show-labels="true" placeholder="Vui lòng chọn">
                        </multiselect>
                    </div>
                    <div class="float-right p-2">
                        <button type="submit" class="btn btn-secondary p-2" @click="handleShowFilter()">Đóng</button>
                        <button type="submit" class="btn btn-primary p-2" @click="showTimeline? getTaskTimeLine(): getAllTasks()">Áp dụng</button>
                    </div>
                </div>
                <timeline-task v-if="showTimeline" :listTaskTimeLine="listTaskTimeLine" :users="users" :groupUsers="groupUsers" :priorities="priorities"
                               :stickers="stickers" :projects="projects" @getTaskTimeLine="getTaskTimeLine()" />
                <list-task v-else :project-id="projectId" :users="users" :groupUsers="groupUsers" :priorities="priorities"
                           :stickers="stickers" :projects="projects" :searchProjectId="searchProjectId" :search="search"
                           :startTime="startTime" :taskPerformer="taskPerformer" :taskDepartment="taskDepartment" :status="status"
                           @getAllTasks="getAllTasks" :list="list"
                />
            </div>

        </div>
        <div>
            <div ref="modalCreateTask" class="modal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document" style=" max-width: 60%;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tạo mới Công việc</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="closeModalCreateTask()">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <create-task :users="users" :groupUsers="groupUsers" :priorities="priorities"
                                         :stickers="stickers" :projects="projects" :projectId="projectId" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <div ref="modalCreate" class="modal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document" style=" max-width: 60%;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Sửa dự án</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="closeModalEditProject()">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <CreateProject v-if="isShowModalEditProject" :projectId="projectId" :users="users" :groupUsers="groupUsers" @updateProject="updateProject" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <div ref="modalConfigTask" class="modal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document" style=" max-width: 60%;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Cấu hình</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="closeModalConfigTask()">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <Config />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ListProject from "./ListProject";
import draggable from 'vuedraggable';
import CreateTask from "./CreateTask";
import CreateProject from "./CreateProject";
import TimelineTask from "./TimelineTask";
import ListTask from "./ListTask";
import Config from "./Config";
import {$get, $post} from "../../ultis";
import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import Multiselect from 'vue-multiselect';

export default {
    name: "Index",
    components: {ListTask, TimelineTask, CreateTask, ListProject, draggable, CreateProject, Config, DatePicker, Multiselect},
    data() {
        return {
            projectId: 0,
            searchProjectId: 0,
            list: [],
            showModal: false,
            showFilter: false,
            showTimeline: true,
            project: {},
            isShowModalEditProject: false,
            showModalConfig: false,
            users: [],
            groupUsers: [],
            priorities: [],
            stickers: [],
            projects: [],
            listTaskTimeLine: [],
            startTime: '',
            taskPerformer: '',
            taskDepartment: 0,
            search: '',
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
            status: ''
        }
    },
    created() {
        this.getAllUser();
        this.getGroupUsers();
        this.getAllPriority();
        this.getAllSticker();
        this.getProjects();
        this.getTaskTimeLine();
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
        showModalConfigTask() {
            this.showModalConfig = true;
            $(this.$refs.modalConfigTask).modal('show');
        },
        closeModalCreateTask() {
            $(this.$refs.modalCreateTask).modal('hide');
            this.showModal = false;
        },
        closeModalConfigTask() {
            $(this.$refs.modalConfigTask).modal('hide');
            this.showModalConfig = false;
        },
        chooseProject(project_id) {
            this.showTimeline = false;
            this.isShowModalEditProject = false;
            this.projectId = project_id;
        },
        handleShowFilter() {
            this.showFilter = !this.showFilter
        },
        async getInfoProject() {
            const res = await $get(`/projects/${this.projectId}`);
            if (res.code == 200) {
                this.project = res.project;
            }
        },
        showModalEditProject() {
            $(this.$refs.modalCreate).modal('show');
            this.isShowModalEditProject = true;
        },
        closeModalEditProject() {
            $(this.$refs.modalCreate).modal('hide');
            this.isShowModalEditProject = false;
        },
        updateProject() {
            this.getInfoProject();
            this.closeModalEditProject();
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
        async getProjects() {
            const res = await $get('/projects/get_all');

            this.projects = res.projects
        },
        async getTaskTimeLine() {
            let filters = {
                project_id: this.searchProjectId? this.searchProjectId.id : 0,
                search: this.search || '',
                start_time: this.startTime || '',
                task_performer: this.taskPerformer || 0,
                task_department: this.taskDepartment? this.taskDepartment.value : 0,
                status: this.status? this.status.value : -1,
            }
            const res = await $get('/tasks/timeline', filters);

            if (res.code == 200) {
                this.listTaskTimeLine = res.data;
            }
        },
        handleShowTimeline() {
            this.showTimeline = true;
        },
        async getAllTasks() {

            let filters = {
                project_id: this.projectId,
                parent_task: 0,
                search: this.search || '',
                start_time: this.startTime || '',
                task_performer: this.taskPerformer || 0,
                task_department: this.taskDepartment? this.taskDepartment.value : 0,
                status: this.status? this.status.value : -1,
            }
            const res = await $get('/tasks/index', filters);

            if (res.code == 200) {
                this.list = res.data;
            }
        }
    },
    watch: {
        'projectId': function (newVal) {
            this.getInfoProject();
        }
    }
}
</script>

<style scoped lang="scss">
@import "resources/css/task";
</style>
