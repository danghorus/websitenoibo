<template>
    <div>
        <div class="form-group">
            <label for="project_name">Tên công việc</label>
            <input type="text" v-model="task.task_name" class="form-control" id="project_name" placeholder="Nhập tên công việc" readonly>
        </div>
        <div class="form-group">
            <label for="project_name">Thông tin công việc</label>
            <!--<editor @change="changeDescription($event, task.id)" api-key="0pn43qeafddiqh81a9ba9c5abtfey57b1m07tfsa05gir4s3" v-model="task.description" :init="{
                height: 200,
                menubar: false,
                plugins: [
                    'advlist autolink lists link image charmap print preview anchor',
                    'searchreplace visualblocks code fullscreen',
                    'insertdatetime media table paste code help wordcount'
                ],
                toolbar:
                    'undo redo | formatselect | bold italic backcolor | link \
                               alignleft aligncenter alignright alignjustify | \
                               bullist numlist outdent indent | removeformat | help'
            }" />-->
            <quill-editor
                style="min-height:300px;"
                ref="myQuillEditor"
                v-model="task.description"
                :options="editorOption"
                class="editor-form"
                @input="handleInput" 
                @change="handleChange($event)"
                
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

import Editor from '@tinymce/tinymce-vue'
import 'vue2-datepicker/index.css';
import moment from "moment";
import { $get, $post } from "../../ultis";
import _ from "lodash";
import '@riophae/vue-treeselect/dist/vue-treeselect.css';

export default {
    name: "CreateTask",
    components: { Editor, quillEditor },
    props: [ 'taskId', 'projectId'],
    data() {
        return {
            taskId: '',
            task: {
                project_id: ''
            },
            tasks: []
        }
    },
    created() {
        if (this.taskId) {
            this.getInfoTask();
        } else {
            if (this.projectId) {
                this.task.project_id = _.find(this.projects, { id: parseInt(this.projectId) });
                this.getTaskByProject(this.projectId, this.taskParentId ?? 0);
            } else {
                this.task.project_id = '';
            }
        }
    },
    methods: {
        async changeDescription(e, taskId) {
            const res = await $post(`/tasks/change-description/${taskId}`, { description: e.target.value });

            if (res.code == 200) {
                toastr.success(res.message);

            }
        },
        async getInfoTask() {
            const res = await $get(`/tasks/detail/${this.taskId}`);

            if (res.code == 200) {
                this.task = res.data;
                this.values = res.user_related;
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
                    this.task = {
                        project_id: ''
                    };
                    this.values = [];
                    this.$emit('handleGetTasks', _.cloneDeep(res));
                }
            }
        },
    },
}
</script>

<style scoped>

</style>
