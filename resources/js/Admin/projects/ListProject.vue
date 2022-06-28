<template>
    <div class="col-lg-2 list-project">
        <p style="font-size: 32px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-terminal"
                viewBox="0 0 16 16" style="margin-top: -8px;">
                <path
                    d="M6 9a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3A.5.5 0 0 1 6 9zM3.854 4.146a.5.5 0 1 0-.708.708L4.793 6.5 3.146 8.146a.5.5 0 1 0 .708.708l2-2a.5.5 0 0 0 0-.708l-2-2z" />
                <path
                    d="M2 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2H2zm12 1a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1h12z" />
            </svg>
            Danh sách dự án
        </p>
        <div class="pl-3">
            <li class="pointer project-title" @click="showTimeline()">
                <p>Công việc trong ngày</p>
            </li>
            <li class="pointer project-title" v-for="(project, index) in projects" :key="index"
                @click="chooseProject(project.id)">
                <p>{{ project.project_name }} ( {{ project.project_start_date }} -> {{ project.project_end_date }} )</p>
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
import TimelineTask from "./TimelineTask";
import {$get} from "../../ultis";
export default {
    name: "ListProject",
    components: { CreateProject, TimelineTask},
    props: ['users', 'groupUsers', 'projects'],
    data() {
        return {
            showModal: false,
            showTimeline: false,
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
            this.showTimeline = true;
        }
    }
}
</script>

<style scoped lang="scss">
@import "resources/css/project";
</style>
