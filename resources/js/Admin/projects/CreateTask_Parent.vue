<template>
    <div>
        <div class="row">
            <div class="form-group col-lg-9">
                <label for="project_name">Tên công việc</label>
                <input type="text" v-model="task.task_name" class="form-control" id="project_name"
                    placeholder="Nhập tên công việc">
            </div>
            <div class="form-group col-lg-3">
                <label for="project_code">Mã công việc</label>
                <input type="text" v-model="task.task_code" class="form-control" id="project_code" disabled>
            </div>
        </div>
        <div class="form-group">
            <label for="project_description">Thông tin công việc</label>
            <editor api-key="0pn43qeafddiqh81a9ba9c5abtfey57b1m07tfsa05gir4s3" v-model="task.description" :init="{
                height: 200,
                menubar: false,
                plugins: [
                   'advlist autolink lists link image charmap print preview anchor',
                   'searchreplace visualblocks code fullscreen',
                   'insertdatetime media table paste code help wordcount'
                ],
                 toolbar:
                   'undo redo | formatselect | bold italic backcolor | \
                   alignleft aligncenter alignright alignjustify | \
                   bullist numlist outdent indent | removeformat | help'
            }" />
        </div>
        <div class="row">
            <div class="form-group col-lg-6">
                <label for="project_description">Nhãn dán</label>
                <multiselect v-model="task.task_sticker" :options="stickers" value="id" label="sticker_name"
                    :close-on-select="true" :show-labels="true" placeholder="Vui lòng chọn">
                </multiselect>
            </div>
        </div>
        <div class="row">
        </div>
        <div class="form-group">
            <label for="project_description">Dự án</label>
            <multiselect v-model="task.project_id" :options="projects" value="id" label="project_name"
                :close-on-select="true" :show-labels="true" placeholder="Vui lòng chọn">
            </multiselect>
        </div>
        <div class="form-group">
            <label for="project_description">Công việc cha</label>
            <multiselect v-model="task.task_parent" :disabled="task.project_id == 0" :options="tasks" value="id"
                label="label" :close-on-select="true" :show-labels="true" placeholder="Vui lòng chọn">
            </multiselect>
        </div>
        <button @click="saveTask()" class="btn btn-primary" :close-on-select="true">{{ taskId? 'Cập nhật': 'Tạo mới' }}</button>
    </div>
</template>

<script>
import Editor from '@tinymce/tinymce-vue'
import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import moment from "moment";
import {$get, $post} from "../../ultis";
import Multiselect from 'vue-multiselect';
import _ from "lodash";

export default {
    name: "CreateTask_Parent",
    components: { Editor, DatePicker, Multiselect },
    props: [ 'stickers', 'projects', 'taskId', 'projectId'],
    data() {
        return {
            task: {
                project_id: ''
            },
            users: [],
            values: [],
            tasks: []
        }
    },
    created() {
        if (this.taskId) {
            this.getInfoTask();
        }
    },
    methods: {
        async getInfoTask() {
            const res = await $get(`/tasks/detail/${this.taskId}`);

            if(res.code == 200) {
                this.task = res.data;
                this.values = res.user_related;
            }
        },
        async saveTask() {
            if (!this.task.task_name) {
                toastr.error('Vui lòng nhập tên công việc');
                return false;
            }
            if (this.task.project_id == 0 || !this.task.project_id) {
                toastr.error('Vui lòng chọn dự án');
                return false;
            }

            let data = {
                task: this.task,
                user_related: this.values.map(item => item.id)
            };

            if (this.taskId) {
                const res = await $post(`/tasks/update/${this.taskId}`, data);
                if (res.code == 200) {
                    toastr.success(res.message);
                }
            } else {
                const res = await $post('/tasks/create', data);
                if (res.code == 200) {
                    toastr.success(res.message);
                }
            }

        },
        async getTaskByProject(projectId) {
            const res = await $get('/tasks/get_all', {project_id: projectId})

            if (res.code == 200) {
                this.tasks = res.data;
            }
        }
    },
    watch: {
        'projectId': function (newVal) {
            if (newVal) {
                let project = _.find(this.projects, {id: newVal});
                this.task.project_id = project;
            } else {
                this.task.project_id = '';
            }
        },
        'task.project_id': function (newVal) {
            if (newVal) this.getTaskByProject(newVal.id);
        },
        'task.task_name': function (newVal) {
            if (newVal) {
                let arrTaskName = newVal.split(' ');
                let taskCode = '';
                arrTaskName.forEach(item => {
                    taskCode = taskCode + item.charAt(0);
                })

                this.task.task_code = taskCode.toUpperCase();
            } else {
                this.task.task_code = '';
            }
        }
    }
}
</script>

<style scoped>

</style>
