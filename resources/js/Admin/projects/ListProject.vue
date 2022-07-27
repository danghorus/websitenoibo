<template>
    <div class="col-lg-2 list-project">
        <p style="font-size: 32px;">
            <i class="fas fa-terminal p-1" style="border: 3px solid #212529; border-radius: 6px" />
            Danh sách dự án
        </p>
        <div class="pl-3">
            <li class="pointer project-title" @click="showTimeline()">
                <p>Công việc trong ngày</p>
            </li>
            <li class="pointer project-title" v-for="(project, index) in projects" :key="index"
                @click="chooseProject(project.id)">
                <p>{{ project.project_name }} ( {{ project.project_start_date }} -> {{ project.project_end_date }} )<div style="display: flex">
                    <p @click="showModalEditTask(item.id)">
                        <i class="fas fa-pencil-alt" style="cursor: pointer" />
                    </p>
                    <p @click="showModalConfirm(item.id)">
                        <i class="fas fa-trash ml-2" style="cursor: pointer" />
                    </p>
                </div>
                </p>
            </li>
        </div>
        <div class="div-create-project">
            <a role="button" class="btn-create" @click="showModalCreate()">Tạo mới dự án</a>
        </div>
        <div>
            <div ref="modalCreate" class="modal" tabindex="-1" role="dialog">
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
                            <create-project @updateProject="updateProject" :users="users" :groupUsers="groupUsers" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import CreateProject from "./CreateProject";
import {$get} from "../../ultis";
import TimelineTask from './TimelineTask'
export default {
    name: "ListProject",
    components: { CreateProject, TimelineTask},
    props: ['users', 'groupUsers', 'projects'],
    data() {
        return {
            showModal: false
        }
    },
    created() {
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
        },
        chooseProject(project_id) {
            this.$emit('chooseProject', project_id);
        },
        updateProject() {
            this.getProjects();
        },
        showTimeline(){
            this.$emit('handleShowTimeline');
        }
    }
}
</script>

<style scoped lang="scss">
@import "resources/css/project";
</style>
