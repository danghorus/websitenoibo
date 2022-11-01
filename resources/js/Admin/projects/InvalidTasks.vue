<template>
    <div>
        <Paginate style="float: right; padding-right: 10px;" v-model="paginate" :pagechange="onPageChange"></Paginate>
        <table class="table">
            <thead>
            <tr>
                <th scope="col" width="3%">STT</th>
                <th scope="col" width="35%">Công việc</th>
                <th scope="col" width="10%">Dự án</th>
                <th scope="col">Bộ phận</th>
                <th scope="col">Người thực hiện</th>
                <th scope="col">Ngày xóa</th>
                <th scope="col">Người xóa</th>
                <th style="text-align:center" scope="col" width="11%">Thao tác</th>
            </tr>
            </thead>
            <tr v-for="(item, index) in list" :key="item.id">
                <td scope="row">{{ index + 1 }}</td>
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
        <Paginate style="float: right; padding: 0px 10px 20px 0px;" v-model="paginate" :pagechange="onPageChange"></Paginate>
    </div>
</template>

<script>
import {$get} from "../../ultis";
import Paginate from "../../components/Paginate";

export default {
    name: "InvalidTasks",
    components: { Paginate },
    props: ['paginate'],
    data() {
        return {
            list: [],
            paginate: [],
        }
    },
    created() {
        this.getListInvalidTask();
    },
    methods: {
        async getListInvalidTask(page) {
            console.log(page, 'page');

            let params = {
                page: page ?? 1
            };
            const res = await $get('/tasks/invalid', params);

            if (res.code == 200) {
                this.list = res.data;
                this.paginate = res.paginate; 
            }
        },
        onPageChange(page) {
            this.getListInvalidTask(page);
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
