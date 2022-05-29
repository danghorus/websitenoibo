<template>
    <div class="col-lg-2 list-project">
        <p class="dashboard pl-3"><i class="fa fa-project-diagram" /> Danh sách dự án</p>
        <div class="pl-3">
            <li class="pointer project-title" @click="chooseProject()">
                <p>Dự án 1 (01-01-2022 -> 12-12-2022)</p>
            </li>
            <li class="pointer project-title">
                <p>Dự án 2 (01-01-2022 -> 12-12-2022)</p>
            </li>
            <li class="pointer project-title">
                <p>Dự án 3 (01-01-2022 -> 12-12-2022)</p>
            </li>
            <li class="pointer project-title">
                <p>Dự án 4 (01-01-2022 -> 12-12-2022)</p>
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
                            <create-project />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import CreateProject from "./CreateProject";
export default {
    name: "ListProject",
    components: {CreateProject},
    data() {
        return {
            showModal: false
        }
    },
    methods: {
        showModalCreate() {
            this.showModal = true;
            $(this.$refs.modalCreate).modal('show');
        },
        closeModalCreate() {
            $(this.$refs.modalCreate).modal('hide');
            this.showModal = false;
        },
        chooseProject() {
            this.$emit('chooseProject');
        }
    }
}
</script>

<style scoped lang="scss">
@import "resources/css/project";
</style>
