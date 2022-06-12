<template>
    <div class="col-lg-2 list-project">
        <p class="dashboard pl-3"><i class="fa fa-project-diagram" /> Danh sách dự án</p>
        <div class="pl-3">
            <li class="pointer project-title" v-for="(project, index) in projects" :key="index" @click="chooseProject(project.id)">
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
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="closeModalCreate()">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <create-project @updateProject="updateProject" />
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
export default {
    name: "ListProject",
    components: {CreateProject},
    data() {
        return {
            showModal: false,
            projects: []
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
        },
        chooseProject(project_id) {
            this.$emit('chooseProject', project_id);
        },
        updateProject() {
            this.getProjects();
        }
    }
}
</script>

<style scoped lang="scss">
@import "resources/css/project";
</style>
