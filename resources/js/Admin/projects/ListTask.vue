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
                    <th scope="col">Hành động</th>
                </tr>
                </thead>
                <draggable v-model="list" tag="tbody">
                    <tr v-for="item in list" :key="item.name" style="cursor: all-scroll">
                        <td scope="row">{{ item.name }}</td>
                        <td>{{ item.name }}</td>
                        <td>{{ item.sport }}</td>
                        <td>{{ item.sport }}</td>
                        <td>{{ item.sport }}</td>
                        <td>{{ item.sport }}</td>
                        <td>{{ item.sport }}</td>
                        <td>+</td>
                    </tr>
                </draggable>
            </table>
        </div>
    </div>
</template>

<script>
import draggable from 'vuedraggable';
import {$get} from "../../ultis";

export default {
    name: "ListTask",
    components: { draggable },
    props: ['projectId'],
    data() {
        return {
            list: [],
            showModal: false,
            showTimeline: true
        }
    },
    created() {
        this.getAllTasks();
    },
    methods: {
        async getAllTasks() {
            let filters = {
                project_id: this.projectId
            }
            const res = await $get('/tasks/index', filters);
        }
    }
}
</script>

<style scoped>

</style>
