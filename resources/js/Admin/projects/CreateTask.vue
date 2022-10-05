<template>
    <div>
        <div class="row">
            <div class="form-group col-lg-3">
                <label for="project_code">Loại công việc</label>
                <!--<input type="text" v-model="task.check_task" class="form-control">-->
                <select v-model="option" class="form-control">
                    <option value="1" selected>Công việc cha</option>
                    <option value="2">Công việc con</option>
                </select>
            </div>
            <div class="form-group col-lg-9">
                <label for="project_name">Tên công việc</label>
                <input type="text" v-model="task.task_name" class="form-control" id="project_name"
                    placeholder="Nhập tên công việc">
            </div>
            <!--<div class="form-group col-lg-3">
                <label for="project_code">Mã công việc</label>
                <input type="text" v-model="task.task_code" class="form-control" id="project_code" disabled>
            </div>-->
        </div>
        <div v-if="option == 2 || task.task_performer != null" class="row">
            <div class="form-group col-lg-3">
                <label for="project_start_date">Ngày bắt đầu</label>
                <DatePicker 
                    style="width: 100%"
                    v-model="task.start_time"
                    value-type="format"
                    type="datetime"
                    placeholder="Select time"
                >
                </DatePicker>
            </div>
            
            <div class="form-group col-lg-3">
                <label for="project_time">Thời lượng</label>
                <input type="number" class="form-control" v-model="task.time" id="project_end_date"
                    placeholder="Nhập thời gian (Giờ)">
            </div>
            <div class="form-group col-lg-3">
                <label for="project_day">Thời gian kết thúc</label>
                <DatePicker style="width: 100%" v-model="task.end_time" value-type="format" type="datetime" disabled>
                </DatePicker>
            </div>
            <div class="form-group col-lg-3">
                <label for="project_weight">Trọng số</label>
                <input type="text" v-model="task.weight" class="form-control" id="project_weight"
                    placeholder="Nhập trọng số">
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
        <div v-if="option == 2 || task.task_performer != null" class=" row">
            <div v-if="option == 2 || task.task_performer != null" class=" row">
                <div class="form-group col-lg-3">
                    <label for="project_description">Độ ưu tiên</label>
                    <multiselect v-model="task.task_priority" :options="priorities" value="id" label="priority_label"
                        :close-on-select="true" :show-labels="true" placeholder="Vui lòng chọn">
                    </multiselect>
                </div>
                <div class="form-group col-lg-3">
                    <label for="project_description">Nhãn dán</label>
                    <multiselect v-model="task.task_sticker" :options="stickers" value="id" label="sticker_name"
                        :close-on-select="true" :show-labels="true" placeholder="Vui lòng chọn">
                    </multiselect>
                </div>
                <div class="form-group col-lg-3">
                    <label for="project_description">Bộ phận</label>
                    <multiselect v-model="task.task_department" :options="departments" value="value" label="label"
                        :close-on-select="true" :show-labels="true" placeholder="Vui lòng chọn">
                    </multiselect>
                </div>
                <div class="form-group col-lg-3">
                    <label for="project_description">Trạng thái công việc</label>
                    <select class="form-control" placeholder="Vui lòng chọn" v-model="task.status">
                        <option value="0">Quá hạn</option>
                        <option value="1" selected>Đang Chờ</option>
                        <option value="2">Đang tiến hành</option>
                        <option value="3">Tạm dừng</option>
                        <option value="4">Hoàn thành</option>
                        <option value="5">Chờ feedback</option>
                        <option value="6">làm lại</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="project_description">Dự án</label>
            <multiselect v-model="task.project_id" :options="projects" value="id" label="project_name"
                :close-on-select="true" :show-labels="true" placeholder="Vui lòng chọn">
            </multiselect>
        </div>
        <!--<div class="form-group">
            <label for="project_description">Công việc tiền nhiệm</label>
            <multiselect v-model="task.task_predecessor" :disabled="task.project_id == 0" :options="tasks" value="id"
                label="label" :close-on-select="true" :show-labels="true" placeholder="Vui lòng chọn">
            </multiselect>
        </div>-->
        <div class="form-group">
            <label for="project_description">Công việc cha</label>
            <treeselect :options="tasks" :load-options="loadOptions" loadingText="Loading..."
                v-model="task.task_parent" />
            <!--            <multiselect v-model="task.task_parent" @input="changeTaskParent($event)" :disabled="task.project_id == 0"-->
            <!--                :options="tasks" value="id" label="label" :close-on-select="true" :show-labels="true"-->
            <!--                placeholder="Vui lòng chọn">-->
            <!--            </multiselect>-->
        </div>
        <div v-if="option == 2 || task.task_performer != null" class=" row">
            <div class="form-group col-lg-6">
                <label>Người thực hiện</label>
                <multiselect v-model="task.task_performer" :options="users" value="id" label="fullname"
                    :close-on-select="true" :show-labels="true" placeholder="Vui lòng chọn">
                </multiselect>
            </div>
            <div class="form-group col-lg-6">
                <label>Người giám sát</label>
                <multiselect v-model="values" :options="groupUsers" :multiple="true" group-values="values"
                    group-label="department" :group-select="true" placeholder="Vui lòng chọn" track-by="id"
                    label="fullname">
                    <span slot="noResult">Oops! No elements found. Consider changing the search query.</span>
                </multiselect>
            </div>
        </div>
        <button @click="saveTask()" class="btn btn-primary">{{ taskId? 'Cập nhật': 'Tạo mới' }}</button>
    </div>
</template>

<script>
import Editor from '@tinymce/tinymce-vue'
import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import moment from "moment";
import { $get, $post } from "../../ultis";
import Multiselect from 'vue-multiselect';
import _ from "lodash";
import Treeselect from '@riophae/vue-treeselect';
import '@riophae/vue-treeselect/dist/vue-treeselect.css';

export default {
    name: "CreateTask",
    components: { Editor, DatePicker, Multiselect, Treeselect },
    props: ['users', 'groupUsers', 'priorities', 'stickers', 'projects', 'taskId', 'projectId', 'taskParentId'],
    data() {
        return {
            option: 1,
            task: {
                project_id: ''
            },
            users: [],
            count: 0,
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
        } else {
            if (this.projectId) {
                this.task.project_id = _.find(this.projects, { id: parseInt(this.projectId) });
                // if (this.taskParentId) {
                //     this.task.task_parent = this.taskParentId;
                // }
                this.getTaskByProject(this.projectId, this.taskParentId ?? 0);
            } else {
                this.task.project_id = '';
            }
        }
    },
    methods: {
        async getInfoTask() {
            const res = await $get(`/tasks/detail/${this.taskId}`);

            if (res.code == 200) {
                this.task = res.data;
                this.values = res.user_related;
                this.getTaskByProject(this.task.project_id.id, this.task.task_parent ?? 0);
            }
        },
        async loadOptions({ action, parentNode, callback }) {
            const res = await $get('/tasks/get_all', { project_id: this.projectId, task_parent: parentNode.id })

            if (res.code == 200) {
                parentNode.children = res.data;
            }
        },
        async saveTask() {
            if (!this.task.task_name) {
                toastr.error('Vui lòng nhập tên công việc');
                return false;
            }
            /*f (!this.task.start_time) {
                toastr.error('Vui lòng nhập thời gian thực hiện');
                return false;
            }
            if (!this.task.time) {
                toastr.error('Vui lòng nhập thời lượng');
                return false;
            }*/
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
                    this.task = {
                        project_id: ''
                    };
                    this.values = [];
                    this.$emit('handleGetTasks', _.cloneDeep(res));
                }
            } else {
                const res = await $post('/tasks/create', data);
                if (res.code == 200) {
                    toastr.success(res.message);
                    this.task = {
                        project_id: ''
                    };
                    this.values = [];
                    this.$emit('handleCreateTask', _.cloneDeep(res));
                }
            }

        },
        async getTaskByProject(projectId, taskId) {
            const res = await $get('/tasks/get_all', { project_id: projectId, task_id: taskId ?? 0 })

            if (res.code == 200) {
                this.tasks = res.data;
                if (taskId) {
                    this.task.task_parent = taskId;
                }
            }
        },
        changeTaskParent(e) {
            this.task.task_parent = e;
        }
    },
    watch: {
        /*'task.time': function (newVal) {
            if (newVal > 0 && this.task.start_time) {
                let dateTime = moment(this.task.start_time).add(newVal, 'h').toDate();
                if (moment(dateTime).format('YYYY-MM-DD HH:mm:ss') < (moment(dateTime).format('YYYY-MM-DD 12:00:00')){
                this.task.end_time = moment(dateTime).format('YYYY-MM-DD HH:mm:ss');
                } else {
                    this.task.end_time = 1;
                }
            }
        },
        'task.start_time_day': function (newVal) {
            if (newVal && this.task.time) {
                let dateTime = moment(newVal).add(this.task.time, 'h').toDate();
                this.task.end_time = moment(dateTime).format('YYYY-MM-DD HH:mm:ss');
            }
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
        },*/
        // 'tasks': function (newVal) {
        //     if (this.taskParentId && this.count === 0) {
        //         this.task.task_parent = _.find(newVal, {id: parseInt(this.taskParentId)});
        //         this.count = this.count + 1;
        //     }
        // },
        'task.project_id': function (newVal) {
            if (newVal.id) {
                this.getTaskByProject(newVal.id, 0);
            }
        },
        // 'projects': function (newVal) {
        //     if (this.projectId) {
        //         this.task.project_id = _.find(newVal, {id: parseInt(this.projectId)});
        //         this.getTaskByProject(this.projectId);
        //     }
        // },
        'taskParentId': function (newVal) {
            console.log(newVal, 'new val');
        }
    }
}
</script>

<style scoped>

</style>
