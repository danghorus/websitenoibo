<template>
    <div>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">STT</th>
                <th scope="col">Công việc</th>
                <th scope="col">Dự án</th>
                <th scope="col">Bộ phận</th>
                <th scope="col">Người thực hiện</th>
                <th scope="col">Ngày xóa</th>
                <th scope="col">Người xóa</th>
                <th scope="col">Thao tác</th>
            </tr>
            </thead>
            <tr v-for="item in list" :key="item.id">
                <td scope="row">{{ item.id + 1 }}</td>
                <td scope="row">{{ item.task_name }}</td>
                <td>{{ item.project_name }}</td>
                <td>{{ item.department_label }}</td>
                <td>{{ item.fullname }}</td>
                <td>{{ item.deleted_at }}</td>
                <td>{{ item.d_fullname }}</td>
                <td>
                    <div style="display: flex">
                        <button class="btn btn-danger" @click="deleteTask(item.id)">Xóa</button> &ensp;
                        <button class="btn btn-primary" @click="restoreTask(item.id)">Khôi phuc</button>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</template>

<script>
import {$get} from "../../ultis";

export default {
    name: "InvalidTasks",
    data() {
        return {
            list: []
        }
    },
    created() {
        this.getListInvalidTask();
    },
    methods: {
        async getListInvalidTask() {
            const res = await $get('/tasks/invalid');

            if (res.code == 200) {
                this.list = res.data;
            }
        },
        async deleteTask(id) {
            const res = await $get(`/tasks/delete/${id}/invalid`);

            if (res.code == 200) {
                toastr.success('Xóa thành công');
                this.getListInvalidTask();
            }
        },
        async restoreTask(id) {
            const res = await $get(`/tasks/restore/${id}`);

            if (res.code == 200) {
                toastr.success('Khôi phục thành công');
                this.getListInvalidTask();
            }
        }
    }
}
</script>

<style scoped>

</style>
