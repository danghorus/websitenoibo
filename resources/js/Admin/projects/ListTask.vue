<template>
    <div>
        <div class="mt-4">
            <zk-table
                ref="table"
                index-text="#"
                :data="list"
                :columns="columns"
                :stripe="false"
                :border="true"
                :show-header="true"
                :show-row-hover="true"
                :show-index="true"
                :tree-type="true"
                :is-fold="false"
                :expand-type="false"
                :selection-type="false"
                row-style="font-size: 15px"
                empty-text="Không có dữ liệu"
            >
                <template slot="man" scope="scope">
                    <div style="display: flex">
                        <p @click="showModalEditTask(scope.row.id)">
                            <i class="fas fa-pencil-alt" style="cursor: pointer" />
                        </p>
                        <p @click="showModalConfirm(scope.row.id)">
                            <i class="fas fa-trash ml-2" style="cursor: pointer" />
                        </p>
                        <p @click="copyTask(scope.row.id)">
                            <i class="fas fa-copy ml-2" style="cursor: pointer" />
                        </p>
                    </div>
                </template>
            </zk-table>
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
                        <span>Bạn có chắc muốn xóa công việc này?</span><br>
                        <div class="float-right">
                            <button class="btn btn-secondary" @click="closeModalConfirm()">Hủy</button>
                            <button class="btn btn-primary" @click="deleteTask()">Xóa</button>
                        </div>
                    </div>
                </div>
            </div>
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
                                     @handleGetTasks="handleGetAll()"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import draggable from 'vuedraggable';
import {$get, $post} from "../../ultis";
import Tree from "./Tree";
import ZkTable from 'vue-table-with-tree-grid';
import CreateTask from "./CreateTask";

export default {
    name: "ListTask",
    components: { draggable, Tree, ZkTable, CreateTask },
    props: ['projectId', 'users', 'groupUsers', 'priorities', 'stickers', 'projects', 'searchProjectId', 'search',
        'startTime', 'taskPerformer', 'taskDepartment', 'status', 'list'],
    data() {
        return {
            showModal: false,
            showTimeline: true,
            parentTask: 0,
            taskId: 0,
            showModalEdit: false,
            taskEditId: 0,
            columns: [
            {
                label: 'Công việc',
                prop: 'task_name',
                minWidth: '400px',
            },
            {
                label: 'Thời gian bắt đầu',
                prop: 'start_time',
            },
            {
                label: 'Thời lượng',
                prop: 'time',
            },
            {
                label: 'Bộ phận',
                prop: 'department_label',
            },
            {
                label: 'Người thực hiện',
                prop: 'fullname',
            },
            {
                label: 'Trạng thái',
                prop: 'status_title',
            },
            {
                label: 'Thao tác',
                prop: 'man',
                type: 'template',
                template: 'man',
            },
        ],
        }
    },
    created() {
        // this.$emit('getAllTasks')
    },
    methods: {
        handleGetAll() {
            this.$emit('getAllTasks');
            this.closeModalConfirm();
            this.closeModalEditTask();
        },
        handleChangeInput(value) {
            this.list = [...value];
        },
        async getChildTask(e) {
            let filters = {
                project_id: e.project_id,
                parent_task: e.id,
                search: this.search || '',
                start_time: this.startTime || '',
                task_performer: this.taskPerformer || 0,
                task_department: this.taskDepartment? this.taskDepartment.value : 0,
                status: this.status? this.status.value : -1,
            }

            const res = await $get('/tasks/index', filters);

            if (res.code == 200) {
                let index = this.list.findIndex(item => item.id === e.id);
                this.list[index].children = res.data;
            }
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
                this.handleGetAll();
            }
        },
        async copyTask(id) {
            const res = await $get(`/tasks/copy/${id}`);

            if (res.code == 200) {
                toastr.success('Copy thành công');
                this.handleGetAll();
            }
        },
    },
    watch: {
        'list': function (newVal) {
            console.log(newVal);
        },
        // 'projectId': function (newVal) {
        //     this.getAllTasks();
        // }
    }
}
</script>

<style scoped>

</style>
