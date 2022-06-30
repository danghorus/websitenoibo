<template>
    <div>
        <div class="row">
            <div class="form-group col-lg-9">
                <label for="project_name">Tên công việc</label>
                <input type="text" v-model="task.task_name" class="form-control" id="project_name" placeholder="Nhập tên công việc">
            </div>
            <div class="form-group col-lg-3">
                <label for="project_code">Mã công việc</label>
                <input type="text" v-model="task.task_code" class="form-control" id="project_code" placeholder="Nhập mã công việc">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-lg-4">
                <label for="project_start_date">Thời gian thực hiện</label>
                <DatePicker
                    style="width: 100%"
                    v-model="task.start_time"
                    value-type="format"
                    type="datetime"
                    placeholder="Select time"
                ></DatePicker>
            </div>
            <div class="form-group col-lg-4">
                <label for="project_end_date">Thời lượng</label>
                <input type="number" class="form-control" v-model="task.time" id="project_end_date" placeholder="Nhập thời gian (Giờ)">
            </div>
            <div class="form-group col-lg-4">
                <label for="project_day">Thời gian kết thúc</label>
                <DatePicker
                    style="width: 100%"
                    v-model="task.end_time"
                    value-type="format"
                    type="datetime"
                    disabled
                ></DatePicker>
            </div>
        </div>
        <div class="form-group">
            <label for="project_description">Thông tin công việc</label>
            <editor
                api-key="0pn43qeafddiqh81a9ba9c5abtfey57b1m07tfsa05gir4s3"
                v-model="task.description"
                :init="{
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
            }"
            />
        </div>
        <div class="row">
            <div class="form-group col-lg-6">
                <label for="project_description">Độ ưu tiên</label>
                <multiselect v-model="task.task_priority" :options="priorities" value="id" label="priority_label" :close-on-select="false" :show-labels="true" placeholder="Vui lòng chọn">
                </multiselect>
            </div>
            <div class="form-group col-lg-6">
                <label for="project_description">Nhãn dán</label>
                <multiselect v-model="task.task_sticker" :options="stickers" value="id" label="sticker_name" :close-on-select="false" :show-labels="true" placeholder="Vui lòng chọn">
                </multiselect>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-lg-6">
                <label for="project_description">Bộ phận</label>
                <multiselect v-model="task.task_department" :options="departments" value="value" label="label" :close-on-select="false" :show-labels="true" placeholder="Vui lòng chọn">
                </multiselect>
            </div>
            <div class="form-group col-lg-6">
                <label for="project_weight">Trọng số</label>
                <input type="text" v-model="task.weight" class="form-control" id="project_weight" placeholder="Nhập trọng số">
            </div>
        </div>
        <div class="form-group">
            <label for="project_description">Dự án</label>
            <multiselect v-model="task.project_id" :options="projects" value="id" label="project_name" :close-on-select="false" :show-labels="true" placeholder="Vui lòng chọn">
            </multiselect>
        </div>
        <div class="form-group">
            <label for="project_description">Công việc tiền nhiệm</label>
            <multiselect v-model="task.task_predecessor" :disabled="task.project_id == 0" :options="tasks" value="id" label="label" :close-on-select="false" :show-labels="true" placeholder="Vui lòng chọn">
            </multiselect>
        </div>
        <div class="form-group">
            <label for="project_description">Công việc cha</label>
            <multiselect v-model="task.task_parent" :disabled="task.project_id == 0" :options="tasks" value="id" label="label" :close-on-select="false" :show-labels="true" placeholder="Vui lòng chọn">
            </multiselect>
        </div>
        <div class="form-group">
            <label>Người thực hiện</label>
            <multiselect v-model="task.task_performer" :options="users" value="id" label="fullname" :close-on-select="false" :show-labels="true" placeholder="Vui lòng chọn">
            </multiselect>
        </div>
        <div class="form-group">
            <label>Người giám sát</label>
            <multiselect
                v-model="values"
                :options="groupUsers"
                :multiple="true"
                group-values="values"
                group-label="department"
                :group-select="true"
                placeholder="Vui lòng chọn"
                track-by="id"
                label="fullname"
            >
                <span slot="noResult">Oops! No elements found. Consider changing the search query.</span>
            </multiselect>
        </div>
        <button @click="saveTask()" class="btn btn-primary">Tạo mới</button>
    </div>
</template>

<script>
import Editor from '@tinymce/tinymce-vue'
import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import moment from "moment";
import {$get, $post} from "../../ultis";
import Multiselect from 'vue-multiselect';

export default {
    name: "CreateTask",
    components: { Editor, DatePicker, Multiselect },
    props: ['users', 'groupUsers', 'priorities', 'stickers', 'projects', 'taskId'],
    data() {
        return {
            task: {
                project_id: 0
            },
            users: [],
            values: [],
            departments: [
                { value: 1, label: 'Admin' },
                { value: 2, label: 'Dev' },
                { value: 3, label: 'Game design' },
                { value: 4, label: 'Art' },
                { value: 5, label: 'Tester' },
                { value: 6, label: 'Điều hành' },
                { value: 7, label: 'Hành chính nhân sự' },
                { value: 8, label: 'Kế toán' },
                { value: 9, label: 'Phân tích dữ liệu' },
                { value: 10, label: 'Support' },
            ],
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
            if (!this.task.start_time) {
                toastr.error('Vui lòng nhập thời gian thực hiện');
                return false;
            }
            if (!this.task.time) {
                toastr.error('Vui lòng nhập thời lượng');
                return false;
            }
            if (this.task.project_id == 0 || !this.task.project_id) {
                toastr.error('Vui lòng chọn dự án');
                return false;
            }
            if (!this.task.task_performer) {
                toastr.error('Vui lòng chọn người thực hiện');
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
        'task.time': function (newVal) {
            if (newVal > 0 && this.task.start_time) {
                let dateTime = moment(this.task.start_time).add(newVal, 'h').toDate();
                this.task.end_time = moment(dateTime).format('YYYY-MM-DD hh:mm:ss');
            }
        },
        'task.start_time': function (newVal) {
            if (newVal > 0 && this.task.time) {
                let dateTime = moment(newVal).add(this.task.time, 'h').toDate();
                this.task.end_time = moment(dateTime).format('YYYY-MM-DD hh:mm:ss');
            }
        },
        'task.project_id': function (newVal) {
            if (newVal) this.getTaskByProject(newVal.id);
        }
    }
}
</script>

<style scoped>

</style>
