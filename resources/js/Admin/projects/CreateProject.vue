<template>
    <div>
        <div class="row">
            <div class="form-group col-lg-9">
                <label for="project_name">Tên dự án</label>
                <input type="text" v-model="project.project_name" class="form-control" id="project_name" placeholder="Nhập tên dự án">
            </div>
            <div class="form-group col-lg-3">
                <label for="project_code">Mã dự án</label>
                <input type="text" v-model="project.project_code" class="form-control" id="project_code" placeholder="Nhập mã dự án">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-lg-4">
                <label for="project_start_date">Thời gian bắt đầu</label>
                <DatePicker
                    style="width: 100%"
                    v-model="project.project_start_date"
                    value-type="format"
                    type="date"
                    placeholder="Vui lòng chọn"
                ></DatePicker>
            </div>
            <div class="form-group col-lg-4">
                <label for="project_end_date">Thời gian kết thúc</label>
                <DatePicker
                    style="width: 100%"
                    v-model="project.project_end_date"
                    value-type="format"
                    type="date"
                    placeholder="Vui lòng chọn"
                ></DatePicker>
            </div>
            <div class="form-group col-lg-4">
                <label for="project_day">Số ngày</label>
                <input type="text" v-model="project.project_day" class="form-control" id="project_day" placeholder="Nhập số ngày">
            </div>
        </div>
        <div class="form-group">
            <label for="project_description">Mô tả dự án</label>
            <editor
                api-key="0pn43qeafddiqh81a9ba9c5abtfey57b1m07tfsa05gir4s3"
                v-model="project.description"
                :init="{
                height: 500,
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
        <div class="form-group">
            <label for="project_description">Người tham gia</label>
            <multiselect
                v-model="values"
                :options="users"
                :multiple="true"
                :close-on-select="true"
                :clear-on-select="true"
                :preserve-search="true"
                placeholder="Vui lòng chọn"
                label="fullname"
                track-by="id"
            >
                <template slot="selection" slot-scope="{ values, search, isOpen }">
                    <span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">Đã chọn {{ values.length }} nhân viên</span>
                </template>
            </multiselect>
        </div>
        <div class="form-group">
            <label for="project_manager">Người thực hiện</label>
            <multiselect v-model="project.project_manager" :options="users" value="id" label="fullname" :close-on-select="false" :show-labels="true" placeholder="Vui lòng chọn">
            </multiselect>
        </div>
        <button class="btn btn-primary" @click="saveProject()">{{ projectId? 'Cập nhật': 'Tạo mới' }}</button>
    </div>
</template>

<script>
import Editor from '@tinymce/tinymce-vue'
import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import Multiselect from 'vue-multiselect';
import {$get, $post} from "../../ultis";
import moment from "moment";

export default {
    name: "CreateProject",
    components: { Editor, DatePicker, Multiselect },
    props: ['projectId'],
    data() {
        return {
            project: {},
            users: [],
            values: [],
        }
    },
    created() {
        this.getAllUser();
        if (this.projectId) {
            this.getDetailProject();
        }
    },
    methods: {
        async getAllUser() {
            const res = await $get('/user/all_user');
            if (res.code == 200) {
                this.users = res.data;
            }
        },
        async getDetailProject() {
            const res = await $get(`/projects/${this.projectId}/get_detail`);
            if (res.code == 200) {
                this.project = res.project;
                this.values = this.users.filter(item => res.users_related.includes(item.id));
                let project_manager = this.users.filter(item => res.project.project_manager == item.id);
                this.project.project_manager = project_manager.length > 0? project_manager[0]: []
            }
        },
        async saveProject() {

            if (!this.project.project_name) {
                toastr.error('Vui lòng nhập tên dự án');
                return false;
            }

            if (!this.project.project_code) {
                toastr.error('Vui lòng nhập mã dự án');
                return false;
            }

            if (!this.project.project_manager) {
                toastr.error('Vui lòng chọn người thực hiện');
                return false;
            }

            let data = {
                project: this.project,
                user_related: this.values.map(item => item.id)
            };

            if (this.projectId) {
                const res = await $post(`/projects/${this.projectId}/update`, data);
                if (res.code == 200) {
                    toastr.success(res.message);
                }
            } else {
                const res = await $post('/projects/create', data);
                if (res.code == 200) {
                    toastr.success(res.message);
                }
            }

            this.$emit('updateProject');
        }
    }
}
</script>

<style scoped>

</style>
