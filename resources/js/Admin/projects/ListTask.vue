<template>
    <div>
        <div>
            <vue-ads-table
                :columns="columns"
                :rows="list"
                :classes="classes"
                :call-children="getChildTask"
            >
                <template slot="man" slot-scope="props">
                    <div style="display: flex">
                        <p @click="showModalEditTask(props.row.id)">
                            <i class="fas fa-pencil-alt" style="cursor: pointer" />
                        </p>
                        <p @click="showModalConfirm(props.row.id)">
                            <i class="fas fa-trash ml-2" style="cursor: pointer" />
                        </p>
                        <p @click="copyTask(props)">
                            <i class="fas fa-copy ml-2" style="cursor: pointer" />
                        </p>
                        <p @click="showCreateTask(props.row.id)">
                            <i class="fas fa-plus-square ml-2" style="cursor: pointer" />
                        </p>
                    </div>

                </template>
                <template slot="status_template" scope="scope">
                    <div v-if="currentUser.permission == 1">
                        <select class="form-select form-select-sm" aria-label=".form-select-sm example"
                                @change="changeStatus($event, scope.row.id)" v-model="scope.row.status">
                            <option value="0" :disabled="scope.row.status == 2 || scope.row.status == 3 || scope.row.status==4">Quá hạn</option>
                            <option value="1" v-if="scope.row.status == 1">Đang Chờ</option>
                            <option value="2" :disabled="scope.row.status == 2">Đang tiến hành</option>
                            <option value="3" :disabled="scope.row.status == 3">Tạm dừng</option>
                            <option value="4" :disabled="scope.row.status == 3 || scope.row.status == 4">Hoàn thành
                            </option>
                        </select>
                    </div>
                    <div v-else>
                        <p>{{ scope.row.status_title }}</p>
                    </div>
                </template>
                <template slot="no-rows">Không có dữ liệu</template>
                <template slot="toggle-children-icon" slot-scope="props"> <span style="cursor: pointer"> [{{ props.expanded ? '-' : '+' }}] </span></template>
            </vue-ads-table>
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
                            <create-task v-if="showModalEdit" :users="users" :groupUsers="groupUsers" :projectId="projectId"
                                         :priorities="priorities" :stickers="stickers" :projects="projects" :taskId="taskEditId"
                                         @handleGetTasks="handleGetAll()" />
                        </div>
                    </div>
                </div>
            </div>
            <div ref="modalCreateTask" class="modal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document" style=" max-width: 60%;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Thêm mới Công việc</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                    @click="closeModalCreateTask()">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <create-task v-if="showModalCreate" :users="users" :groupUsers="groupUsers"
                                         :projectId="projectId" :priorities="priorities" :stickers="stickers" :projects="projects"
                                         :taskParentId="parentId" @handleGetTasks="handleGetAll()" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {$get, $post} from "../../ultis";
import { VueAdsTable } from 'vue-ads-table-tree';
import CreateTask from "./CreateTask";

export default {
    name: "ListTask",
    components: { VueAdsTable, CreateTask },
    props: ['projectId', 'users', 'groupUsers', 'priorities', 'stickers', 'projects', 'searchProjectId', 'search',
        'startTime', 'taskPerformer', 'taskDepartment', 'status', 'list', 'currentUser'],

    data () {
        let classes = {
            table: {
                'table': true
            },
            '1_/0': {
                'vue-ads-cursor-pointer': true
            }
        };

        return {
            classes: classes,
            showModal: false,
            showTimeline: true,
            parentTask: 0,
            taskId: 0,
            showModalEdit: false,
            showModalCreate: false,
            taskEditId: 0,
            parentId: 0,
            columns: [
                {
                    title: 'Công việc',
                    property: 'task_name',
                },
                {
                    title: 'Ngày bắt đầu',
                    property: 'start_time',
                },
                {
                    title: 'Thời lượng',
                    property: 'time',
                },
                {
                    title: 'Trọng số',
                    property: 'weight',
                },
                {
                    title: 'Bộ phận',
                    property: 'department_label',
                },
                {
                    title: 'Người thực hiện',
                    property: 'fullname',
                },
                {
                    title: 'Trạng thái',
                    property: 'status_template',
                },
                {
                    title: 'Thao tác',
                    property: 'man',
                },
            ],
        }
    },

    methods: {
        handleGetAll() {
            this.$emit('getAllTasks');
            this.closeModalConfirm();
            this.closeModalEditTask();
            this.closeModalCreateTask();
        },

        sleep (ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        },

        async callRows (indexesToLoad) {
            await this.sleep(1000);
            return indexesToLoad.map(index => {
                return {
                    name: 'Call Rows',
                    function: 'Developer',
                    city: 'San Francisco',
                    id: '8196',
                    since: '2010/07/14',
                    budget: '$86,500',
                };
            });
        },

        async callTempRows (filter, columns, start, end) {
            await this.sleep(1000);
            return {
                rows: [
                    {
                        name: 'Temp call',
                        function: 'Developer',
                        city: 'San Francisco',
                        id: '8196',
                        since: '2010/07/14',
                        budget: '$86,500',
                    },
                    {
                        name: 'Temp call',
                        function: 'Developer',
                        city: 'San Francisco',
                        id: '8196',
                        since: '2010/07/14',
                        budget: '$86,500',
                    },
                ],
                total: 4,
            };
        },
        async getChildTask(parent) {
            let filters = {
                project_id: parent.project_id,
                parent_task: parent.id,
                search: this.search || '',
                start_time: this.startTime || '',
                task_performer: this.taskPerformer || 0,
                task_department: this.taskDepartment? this.taskDepartment.value : 0,
                status: this.status? this.status.value : -1,
            }

            const res = await $get('/tasks/index', filters);

            if (res.code == 200) {
                return [...res.data];
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
            console.log(id, 'props');
            console.log(this.$ref.tableTree, 'ref');
            const res = await $get(`/tasks/copy/${id}`);

            if (res.code == 200) {
                toastr.success('Copy thành công');
                this.handleGetAll();
            }
        },
        showCreateTask(parentId) {
            this.parentId = parentId;
            this.showModalCreate = true;
            $(this.$refs.modalCreateTask).modal('show');
        },
        closeModalCreateTask() {
            this.showModalCreate = false;
            $(this.$refs.modalCreateTask).modal('hide');
            this.parentId = 0;
        },
        async changeStatus(e, taskId) {
            const res = await $post(`/tasks/change-status/${taskId}`, {status: e.target.value});

            if (res.code == 200) {
                toastr.success(res.message);
                this.$emit('getAllTasks');
            }
        },
    },
};
</script>

<style scoped>
.leftAlign {
    text-align: left;
}
</style>
