<template>
    <div>
        <div class="form-group">
            <label for="project_name">Tên công việc</label>
            <input type="text" v-model="task.task_name" class="form-control" id="project_name" placeholder="Nhập tên công việc" readonly>
        </div>
        <div class="form-group">
            <label for="project_name">Thông tin công việc</label>
            <quill-editor
                ref="myQuillEditor"
                v-model="task.description"
                :options="editorOption"
                class="editor-form"
            />
        </div>
        
        <button @click="saveTask()" class="btn btn-primary">Cập nhật</button>
    </div>
</template>

<script>
import 'quill/dist/quill.core.css'
import 'quill/dist/quill.snow.css'
import 'quill/dist/quill.bubble.css'

import { quillEditor } from 'vue-quill-editor'

import { $get, $post } from "../../ultis";
import _ from "lodash";

export default {
    name: "CreateTask",
    components: {quillEditor },
    props: [ 'taskId', 'projectId'],
    data() {
        return {
            task: {
                project_id: ''
            }
        }
    },
    created() {
        this.getInfoTask();
    },
    methods: {
        async getInfoTask() {
            const res = await $get(`/tasks/detail/${this.taskId}`);

            if (res.code == 200) {
                this.task = res.data;
            }
        },

        async saveTask() {
            

            let data = {
                task: this.task,
            };

            if (this.taskId) {
                const res = await $post(`/tasks/update_description/${this.taskId}`, data);
                if (res.code == 200) {
                    toastr.success(res.message);
                    this.values = [];
                }
            }
        },
    },
}
</script>

<style scoped>

</style>
