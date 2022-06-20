<template>
    <div class="col-lg-12">
        <div class="row">
            <list-project @chooseProject="chooseProject" :users="users" :groupUsers="groupUsers" :projects="projects" />
            <div class="col-lg-10">
                <div class="row">
                    <div class="col-lg-6"
                         @click="showModalEditProject()"
                    >
                        <h4>{{ showTimeline? 'Timeline công việc': project.project_name }}
                            <i v-if="projectId" class="fas fa-pencil-alt" style="font-size: 25px; cursor: pointer"/>
                        </h4>
                    </div>
                    <div class="col-lg-4">
                        <div class="input-seach float-right">
                            <input class="input-elevated" type="text" placeholder="Search">
                            <button class="btn btn-outline-secondary border-btn-search">
                                Tìm kiếm
                            </button>
                            <button
                                class="btn-outline-secondary border-search"
                                @click="handleShowFilter()"
                                type="button"
                                data-toggle="collapse"
                                data-target="#collapseExample"
                                aria-expanded="false"
                                aria-controls="collapseExample"
                            >
                                <i class="fa fa-sort-down" />
                            </button>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <button class="btn btn-primary float-right ml-2" @click="showModalConfigTask()">Cấu hình</button>
                        <button class="btn btn-primary float-right" @click="showModalCreateTask()">Thêm mới</button>
                    </div>
                </div>
                <div class="collapse search-collapse" id="collapseExample" v-if="showFilter">
                    <div class="form-group p-2">
                        <label for="project_description">Theo ngày bắt đầu</label>
                        <select class="form-select">
                            <option>Lê Duy1</option>
                            <option>Lê Duy2</option>
                            <option>Lê Duy3</option>
                        </select>
                    </div>
                    <div class="form-group p-2">
                        <label for="project_description">Theo dự án</label>
                        <select class="form-select">
                            <option>Lê Duy1</option>
                            <option>Lê Duy2</option>
                            <option>Lê Duy3</option>
                        </select>
                    </div>
                    <div class="form-group p-2">
                        <label for="project_description">Theo bộ phận</label>
                        <select class="form-select">
                            <option>Lê Duy1</option>
                            <option>Lê Duy2</option>
                            <option>Lê Duy3</option>
                        </select>
                    </div>
                    <div class="form-group p-2">
                        <label for="project_description">Theo người thực hiện</label>
                        <select class="form-select">
                            <option>Lê Duy1</option>
                            <option>Lê Duy2</option>
                            <option>Lê Duy3</option>
                        </select>
                    </div>
                    <div class="form-group p-2">
                        <label for="project_description">Theo trạng thái</label>
                        <select class="form-select">
                            <option>Lê Duy1</option>
                            <option>Lê Duy2</option>
                            <option>Lê Duy3</option>
                        </select>
                    </div>
                    <div class="float-right p-2">
                        <button type="submit" class="btn btn-secondary p-2" @click="handleShowFilter()">Đóng</button>
                        <button type="submit" class="btn btn-primary p-2">Áp dụng</button>
                    </div>
                </div>
                <timeline-task v-if="showTimeline" />
                <list-task v-else />
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
                                         :stickers="stickers" :projects="projects" />
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

export default {
    name: "Index",
    components: {ListTask, TimelineTask, CreateTask, ListProject, draggable, CreateProject, Config},
    data() {
        return {
            projectId: 0,
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
            projects: []
        }
    },
    created() {
        this.getAllUser();
        this.getGroupUsers();
        this.getAllPriority();
        this.getAllSticker();
        this.getProjects();
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
