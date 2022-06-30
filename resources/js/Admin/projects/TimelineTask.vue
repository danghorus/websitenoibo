<template>
    <div>
        <div class="mt-4">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Công việc</th>
                    <th scope="col">Thời gian bắt đầu</th>
                    <th scope="col">Thời lượng</th>
                    <th scope="col">Dự án</th>
                    <th scope="col">Bộ phận</th>
                    <th scope="col">Người thực hiện</th>
                    <th scope="col">Trạng thái</th>
                    <th scope="col">Thao tác</th>
                </tr>
                </thead>
                <tr v-for="item in listTaskTimeLine" :key="item.id">
                    <td scope="row">{{ item.task_name }}</td>
                    <td>{{ item.start_time }}</td>
                    <td>{{ item.time }}</td>
                    <td>{{ item.project_name }}</td>
                    <td>{{ item.department_label }}</td>
                    <td>{{ item.fullname }}</td>
                    <td>{{ item.sport }}</td>
                    <td>
                        <div style="display: flex">
                            <p @click="showModalEditTask(item.id)">
                                <i class="fas fa-pencil-alt" style="cursor: pointer" />
                            </p>
                            <p @click="showModalConfirm(item.id)">
                                <i class="fas fa-trash ml-2" style="cursor: pointer" />
                            </p>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div ref="modalConfirm" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document" style=" max-width: 30%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Xác nhận xóa công việc</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="closeModalConfirm()">
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
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="closeModalEditTask()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <create-task v-if="showModalEdit" :users="users" :groupUsers="groupUsers" :priorities="priorities"
                                     :stickers="stickers" :projects="projects" :taskId="taskEditId" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import {$post} from "../../ultis";
import CreateTask from "./CreateTask";

export default {
    name: "TimelineTask",
    components: {CreateTask},
    props: ['listTaskTimeLine', 'users', 'groupUsers', 'priorities', 'stickers', 'projects'],
    data() {
        return {
            list: [],
            showModal: false,
            showTimeline: true,
            taskId: 0,
            showModalEdit: false,
            taskEditId: 0
        }
    },
    created() {
    },
    methods: {
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
            }
        }
    }
}
</script>

<style scoped>

</style>
