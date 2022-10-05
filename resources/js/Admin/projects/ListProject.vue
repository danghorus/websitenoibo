<template>
    <div class="col-lg-2 list-project">
        <p style="font-size: 32px;">
            <i class="fas fa-terminal p-1" style="border: 3px solid #212529; border-radius: 6px" />
            Danh sách dự án 
            <a role="button" class="btn-create" @click="showModalCreate()">Tạo mới dự án</a>
        </p>
        <div class="pl-3">
            <li class="pointer project-title" @click="showTimeline()">
                <p>Công việc trong ngày</p>
            </li>
            <li class="pointer project-title">
                <a class="pointer project-title" v-bind:href="'/invalid_tasks'" style="color:black">Thùng rác</a>
            </li><br>
            <li class="pointer project-title">
                <a class="pointer project-title" v-bind:href="'/list_work'" style="color:black">Danh sách công việc</a>
            </li><br>
            <li class="pointer project-title" v-for="(project, index) in projects" :key="index" style="display: flex">
                <p @click="chooseProject(project.id)">{{ project.project_name }}</p>
                &nbsp;
                <p @click="showModalEditTask(project.id)">
                    <i class="fas fa-pencil-alt" style="cursor: pointer" />
                </p>
                <p @click="showModalConfirm(project.id)">
                    <i class="fas fa-trash ml-2" style="cursor: pointer" />
                </p>
            </li>
        </div>
        <div>
            <div ref="modalCreate" data-bs-backdrop="static" data-bs-keyboard="false" class="modal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document" style=" max-width: 60%;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tạo mới dự án</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                @click="closeModalCreate()">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <create-project @updateProject="updateProject" v-if="showModal" :users="users"
                                :groupUsers="groupUsers" />
                            <create-project @updateProject="updateProject" v-if="showModalEdit" :projectId="projectId"
                                :users="users" :groupUsers="groupUsers" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div ref="modalConfirm" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document" style=" max-width: 30%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Xác nhận xóa công việc</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            @click="closeModalConfirm()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <span>Bạn có chắc muốn xóa dự án này?</span><br>
                        <div class="float-right">
                            <button class="btn btn-secondary" @click="closeModalConfirm()">Hủy</button>
                            <button class="btn btn-primary" @click="deleteProject()">Xóa</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import CreateProject from "./CreateProject";
import {$get, $post} from "../../ultis";
import TimelineTask from './TimelineTask'
export default {
    name: "ListProject",
    components: { CreateProject, TimelineTask},
    props: ['users', 'groupUsers', 'projects'],
    data() {
        return {
            showModal: false,
            showModalDelete: false,
            showModalEdit: false,
            projectId: 0
        }
    },
    created() {
        this.getProjects();
    },
    methods: {
        async getProjects() {
            const res = await $get('/projects/get_all');

            this.projects = res.projects
        },
        showModalCreate() {
            this.showModal = true;
            $(this.$refs.modalCreate).modal('show');
        },
        closeModalCreate() {
            $(this.$refs.modalCreate).modal('hide');
            this.showModal = false;
            this.showModalEdit = false;
            this.projectId = 0;
        },
        chooseProject(project_id) {
            this.$emit('chooseProject', project_id);
            localStorage.setItem('project_id', project_id);
        },
        updateProject() {
            this.getProjects();
            this.closeModalCreate();
        },
        showTimeline(){
            this.$emit('handleShowTimeline');
            localStorage.setItem('project_id', 0);
        },
        showModalEditTask(id) {
            this.showModalEdit = true;
            this.projectId = id;
            $(this.$refs.modalCreate).modal('show');
        },

        showModalConfirm(id) {
            this.showModalDelete = true;
            $(this.$refs.modalConfirm).modal('show');
            this.projectId = id;
        },
        closeModalConfirm() {
            this.showModalDelete = false;
            $(this.$refs.modalConfirm).modal('hide');
            this.projectId = 0;
        },
        async deleteProject() {
            const res = await $post(`/projects/delete/${this.projectId}`);

            if (res.code == 200) {
                toastr.success('Xóa thành công');
                this.showModalDelete = false;
                $(this.$refs.modalConfirm).modal('hide');
                this.projectId = 0;
                this.getProjects();
            }
        }
    }
}
</script>

<style scoped lang="scss">
@import "resources/css/project";
</style>
