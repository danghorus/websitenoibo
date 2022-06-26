<template>
    <div>
        <div class="mt-4">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col" style="width: 25%">Công việc</th>
                        <th scope="col" style="width: 10%">Thời gian bắt đầu</th>
                        <th scope="col" style="width: 10%">Thời lượng (Giờ)</th>
                        <th scope="col" style="width: 15%">Dự án</th>
                        <th scope="col" style="width: 10%">Bộ phận</th>
                        <th scope="col" style="width: 15%">Người thực hiện</th>
                        <th scope="col" style="width: 10%">Trạng thái</th>
                        <th scope="col" style="width: 5%">Hành động</th>
                    </tr>
                </thead>
<!--                <draggable v-model="list" tag="tbody">-->
<!--                    <tr v-for="item in list" :key="item.name" style="cursor: all-scroll">-->
<!--                        <td>{{ item.task_name }}</td>-->
<!--                        <td>{{ item.start_time }}</td>-->
<!--                        <td>{{ item.time }}</td>-->
<!--                        <td>{{ item.project_name }}</td>-->
<!--                        <td>{{ item.sport }}</td>-->
<!--                        <td>{{ item.fullname }}</td>-->
<!--                        <td>{{ item.sport }}</td>-->
<!--                        <td>+</td>-->
<!--                    </tr>-->
<!--                </draggable>-->
            </table>
            <tree v-if="list.length > 0" v-model="list" :data="[...list]" group="testsailordgod" rowKey="id">
            </tree>
        </div>
    </div>
</template>

<script>
import draggable from 'vuedraggable';
import {$get} from "../../ultis";
import Tree from "./Tree";

export default {
    name: "ListTask",
    components: { draggable, Tree },
    props: ['projectId'],
    data() {
        return {
            list: [],
            showModal: false,
            showTimeline: true,
            parentTask: 0
        }
    },
    created() {
        this.getAllTasks();
    },
    methods: {
        async getAllTasks() {
            let filters = {
                project_id: this.projectId,
                parent_task: 0
            }
            const res = await $get('/tasks/index', filters);

            if (res.code == 200) {
                this.list = res.data;
            }
        }
    },
    watch: {
        'list': function (newVal) {
            // console.log(newVal);
        },
        'projectId': function (newVal) {
            this.getAllTasks();
        }
    }
}
</script>

<style scoped>

</style>
