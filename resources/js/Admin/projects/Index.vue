<template>
    <div class="col-lg-12">
        <div class="row">
            <list-project @chooseProject="chooseProject()" />
            <div class="col-lg-10">
                <div class="row">
                    <div class="col-lg-6">
                        <h4>{{ showTimeline? 'Timeline công việc': 'Dự án 1' }}</h4>
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
                            <create-task />
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
import TimelineTask from "./TimelineTask";
import ListTask from "./ListTask";

export default {
    name: "Index",
    components: {ListTask, TimelineTask, CreateTask, ListProject, draggable},
    data() {
        return {
            list: [
                {
                    "id": 1,
                    "name": "Abby",
                    "sport": "basket"
                },
                {
                    "id": 2,
                    "name": "Brooke",
                    "sport": "foot"
                },
                {
                    "id": 3,
                    "name": "Courtenay",
                    "sport": "volley"
                },
                {
                    "id": 4,
                    "name": "David",
                    "sport": "rugby"
                },{
                    "id": 1,
                    "name": "Abby",
                    "sport": "basket"
                },
                {
                    "id": 2,
                    "name": "Brooke",
                    "sport": "foot"
                },
                {
                    "id": 3,
                    "name": "Courtenay",
                    "sport": "volley"
                },
                {
                    "id": 4,
                    "name": "David",
                    "sport": "rugby"
                },{
                    "id": 1,
                    "name": "Abby",
                    "sport": "basket"
                },
                {
                    "id": 2,
                    "name": "Brooke",
                    "sport": "foot"
                },
                {
                    "id": 3,
                    "name": "Courtenay",
                    "sport": "volley"
                },
                {
                    "id": 4,
                    "name": "David",
                    "sport": "rugby"
                },{
                    "id": 1,
                    "name": "Abby",
                    "sport": "basket"
                },
                {
                    "id": 2,
                    "name": "Brooke",
                    "sport": "foot"
                },
                {
                    "id": 3,
                    "name": "Courtenay",
                    "sport": "volley"
                },
                {
                    "id": 4,
                    "name": "David",
                    "sport": "rugby"
                },{
                    "id": 1,
                    "name": "Abby",
                    "sport": "basket"
                },
                {
                    "id": 2,
                    "name": "Brooke",
                    "sport": "foot"
                },
                {
                    "id": 3,
                    "name": "Courtenay",
                    "sport": "volley"
                },
                {
                    "id": 4,
                    "name": "David",
                    "sport": "rugby"
                },{
                    "id": 1,
                    "name": "Abby",
                    "sport": "basket"
                },
                {
                    "id": 2,
                    "name": "Brooke",
                    "sport": "foot"
                },
                {
                    "id": 3,
                    "name": "Courtenay",
                    "sport": "volley"
                },
                {
                    "id": 4,
                    "name": "David",
                    "sport": "rugby"
                },{
                    "id": 1,
                    "name": "Abby",
                    "sport": "basket"
                },
                {
                    "id": 2,
                    "name": "Brooke",
                    "sport": "foot"
                },
                {
                    "id": 3,
                    "name": "Courtenay",
                    "sport": "volley"
                },
                {
                    "id": 4,
                    "name": "David",
                    "sport": "rugby"
                },{
                    "id": 1,
                    "name": "Abby",
                    "sport": "basket"
                },
                {
                    "id": 2,
                    "name": "Brooke",
                    "sport": "foot"
                },
                {
                    "id": 3,
                    "name": "Courtenay",
                    "sport": "volley"
                },
                {
                    "id": 4,
                    "name": "David",
                    "sport": "rugby"
                },{
                    "id": 1,
                    "name": "Abby",
                    "sport": "basket"
                },
                {
                    "id": 2,
                    "name": "Brooke",
                    "sport": "foot"
                },
                {
                    "id": 3,
                    "name": "Courtenay",
                    "sport": "volley"
                },
                {
                    "id": 4,
                    "name": "David",
                    "sport": "rugby"
                },{
                    "id": 1,
                    "name": "Abby",
                    "sport": "basket"
                },
                {
                    "id": 2,
                    "name": "Brooke",
                    "sport": "foot"
                },
                {
                    "id": 3,
                    "name": "Courtenay",
                    "sport": "volley"
                },
                {
                    "id": 4,
                    "name": "David",
                    "sport": "rugby"
                },{
                    "id": 1,
                    "name": "Abby",
                    "sport": "basket"
                },
                {
                    "id": 2,
                    "name": "Brooke",
                    "sport": "foot"
                },
                {
                    "id": 3,
                    "name": "Courtenay",
                    "sport": "volley"
                },
                {
                    "id": 4,
                    "name": "David",
                    "sport": "rugby"
                },{
                    "id": 1,
                    "name": "Abby",
                    "sport": "basket"
                },
                {
                    "id": 2,
                    "name": "Brooke",
                    "sport": "foot"
                },
                {
                    "id": 3,
                    "name": "Courtenay",
                    "sport": "volley"
                },
                {
                    "id": 4,
                    "name": "David",
                    "sport": "rugby"
                },{
                    "id": 1,
                    "name": "Abby",
                    "sport": "basket"
                },
                {
                    "id": 2,
                    "name": "Brooke",
                    "sport": "foot"
                },
                {
                    "id": 3,
                    "name": "Courtenay",
                    "sport": "volley"
                },
                {
                    "id": 4,
                    "name": "David",
                    "sport": "rugby"
                },{
                    "id": 1,
                    "name": "Abby",
                    "sport": "basket"
                },
                {
                    "id": 2,
                    "name": "Brooke",
                    "sport": "foot"
                },
                {
                    "id": 3,
                    "name": "Courtenay",
                    "sport": "volley"
                },
                {
                    "id": 4,
                    "name": "David",
                    "sport": "rugby"
                },{
                    "id": 1,
                    "name": "Abby",
                    "sport": "basket"
                },
                {
                    "id": 2,
                    "name": "Brooke",
                    "sport": "foot"
                },
                {
                    "id": 3,
                    "name": "Courtenay",
                    "sport": "volley"
                },
                {
                    "id": 4,
                    "name": "David",
                    "sport": "rugby"
                },{
                    "id": 1,
                    "name": "Abby",
                    "sport": "basket"
                },
                {
                    "id": 2,
                    "name": "Brooke",
                    "sport": "foot"
                },
                {
                    "id": 3,
                    "name": "Courtenay",
                    "sport": "volley"
                },
                {
                    "id": 4,
                    "name": "David",
                    "sport": "rugby"
                },{
                    "id": 1,
                    "name": "Abby",
                    "sport": "basket"
                },
                {
                    "id": 2,
                    "name": "Brooke",
                    "sport": "foot"
                },
                {
                    "id": 3,
                    "name": "Courtenay",
                    "sport": "volley"
                },
                {
                    "id": 4,
                    "name": "David",
                    "sport": "rugby"
                },
            ],
            showModal: false,
            showFilter: false,
            showTimeline: true
        }
    },
    methods: {
        showModalCreateTask() {
            this.showModal = true;
            $(this.$refs.modalCreateTask).modal('show');
        },
        closeModalCreateTask() {
            $(this.$refs.modalCreateTask).modal('hide');
            this.showModal = false;
        },
        chooseProject() {
            this.showTimeline = false;
        },
        handleShowFilter() {
            this.showFilter = !this.showFilter
        }
    }
}
</script>

<style scoped lang="scss">
@import "resources/css/task";
</style>
