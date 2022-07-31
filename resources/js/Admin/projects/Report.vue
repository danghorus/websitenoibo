<template>
    <div>
        <h1 style="margin: 0px 0px 0px 20px">BẢNG THỐNG KÊ CÔNG VIỆC</h1>
        <div style="width:400px; margin: -40px 0px 0px 650px;">
            <span>Thống kê theo dự án</span>
            <multiselect v-model="project" :options="projects" :multiple="true"
                         placeholder="Vui lòng chọn" track-by="id" label="project_name">
                <span slot="noResult">Oops! No elements found. Consider changing the search query.</span>
            </multiselect>
        </div>
        <div style="width:400px; margin: -67px 0px 0px 1060px;">
            <span>Thống kê theo bộ phận</span>
            <multiselect v-model="department" :options="departments" :multiple="true"
                         placeholder="Vui lòng chọn" track-by="value" label="label">
                <span slot="noResult">Oops! No elements found. Consider changing the search query.</span>
            </multiselect>
        </div>
        <div style="width:400px; margin: -59px 0px 0px 1470px;">
            <span>Thời gian thống kê</span>
            <date-picker v-model="dateRange" type="date" range placeholder="Vui lòng chọn khoảng thời gian thống kê">
            </date-picker>
        </div>
        <div style="width:320px; margin: -29px 0px 0px 1800px;">
            <button class="btn btn-primary" @click="getReport()"
                style="height:33px; font-size:14px; margin: -5px 0px 0px 0px">Thống kê
            </button>
        </div><br>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-4">
                    <table class="project table-responsive" style="width: 100%">
                        <tr>
                            <td colspan="3">
                                <h4 style="text-align:center;">Trạng thái công việc</h4>
                            </td>
                        </tr>
                        <tr>
                            <td><b>Tổng</b></td>
                            <td>:</td>
                            <td>{{ taskSummary.total_task }} công việc</td>
                        </tr>
                        <tr>
                            <td><b>Đang chờ</b></td>
                            <td>:</td>
                            <td>{{ taskSummary.total_wait }} công việc</td>
                        </tr>
                        <tr>
                            <td><b>Đang làm</b></td>
                            <td>:</td>
                            <td>{{ taskSummary.total_complete }} công việc</td>
                        </tr>
                        <tr>
                            <td><b>Hoàn thành</b></td>
                            <td>:</td>
                            <td>{{ taskSummary.total_complete }} công việc</td>
                        </tr>
                        <tr>
                            <td><b>Hoàn thành chậm</b></td>
                            <td>:</td>
                            <td>{{ taskSummary.total_complete }} công việc</td>
                        </tr>
                        <tr>
                            <td><b>Quá hạn</b></td>
                            <td>:</td>
                            <td>{{ taskSummary.total_slow }} công việc</td>
                        </tr>
                    </table>
                </div>
                <div class="col-lg-4">
                    <table class="work-department table-responsive" style="width: 100%">
                        <tr>
                            <td colspan="3">
                                <h4 style="text-align:center;">Công việc bộ phận</h4>
                            </td>
                        </tr>

                        <tr v-for="(dept, idx) in summary" :key="idx">
                            <td><b>Dev</b></td>
                            <td>:</td>
                            <td>{{ dept.total_task }} công việc</td>
                        </tr>

                        <tr v-for="(dept, idx) in summary" :key="idx">
                            <td><b>Game Design</b></td>
                            <td>:</td>
                            <td>{{ dept.total_task }} công việc</td>
                        </tr>

                        <tr v-for="(dept, idx) in summary" :key="idx">
                            <td><b>Art</b></td>
                            <td>:</td>
                            <td>{{ dept.total_task }} công việc</td>
                        </tr>

                        <tr v-for="(dept, idx) in summary" :key="idx">
                            <td><b>Tester</b></td>
                            <td>:</td>
                            <td>{{ dept.total_task }} công việc</td>
                        </tr>

                        <tr v-for="(dept, idx) in summary" :key="idx">
                            <td><b>Data</b></td>
                            <td>:</td>
                            <td>{{ dept.total_task }} công việc</td>
                        </tr>

                        <tr v-for="(dept, idx) in summary" :key="idx">
                            <td><b>Marketing</b></td>
                            <td>:</td>
                            <td>{{ dept.total_task }} công việc</td>
                        </tr>
                    </table>
                </div>
                <div class="col-lg-4">
                    <table class="weight table-responsive" style="width: 100%">
                        <tr>
                            <td colspan="3">
                                <h4 style="text-align:center;">Trọng số bộ phận</h4>
                            </td>
                        </tr>
                        <tr v-for="(dept, idx) in summary" :key="idx">
                            <td><b>Dev</b></td>
                            <td>:</td>
                            <td>{{ dept.total_weight }}</td>
                        </tr>

                        <tr v-for="(dept, idx) in summary" :key="idx">
                            <td><b>Game Design</b></td>
                            <td>:</td>
                            <td>{{ dept.total_weight }}</td>
                        </tr>

                        <tr v-for="(dept, idx) in summary" :key="idx">
                            <td><b>Art</b></td>
                            <td>:</td>
                            <td>{{ dept.total_weight }}</td>
                        </tr>

                        <tr v-for="(dept, idx) in summary" :key="idx">
                            <td><b>Tester</b></td>
                            <td>:</td>
                            <td>{{ dept.total_weight }}</td>
                        </tr>
                        <tr v-for="(dept, idx) in summary" :key="idx">
                            <td><b>Data</b></td>
                            <td>:</td>
                            <td>{{ dept.total_weight }}</td>
                        </tr>

                        <tr v-for="(dept, idx) in summary" :key="idx">
                            <td><b>Marketing</b></td>
                            <td>:</td>
                            <td>{{ dept.total_weight }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <br>
            <div>
                <table class="table-striped table-responsive table-hover result-point">
                    <thead class="point-table-head">
                        <tr style="vertical-align: middle; text-align:center; ">

                            <th style="vertical-align: middle; width:250px;" rowspan="2">
                                <input type="text" name="search" class="form-control mb-2 input-search" v-model="search"
                                    placeholder="Tìm kiếm" v-on:keyup.enter="getReport()">
                            </th>
                            <th style="vertical-align: middle; width: 8.7%;">Warrior đăng ký</th>
                            <th style="vertical-align: middle; width: 8.7%;">Warrior đạt được</th>
                            <th style="vertical-align: middle; width: 8.7%;">Tổng số công việc</th>
                            <th style="vertical-align: middle; width: 8.7%;">Hoàn thành</th>
                            <th style="vertical-align: middle; width: 8.7%;">Đang làm</th>
                            <th style="vertical-align: middle; width: 8.7%;">Chưa làm</th>
                            <th style="vertical-align: middle; width: 8.7%;">Hoàn thành muộn</th>
                            <th style="vertical-align: middle; width: 8.7%;">Quá hạn</th>
                            <th style="vertical-align: middle; width: 8.7%;">Trọng số</th>
                            <th style="vertical-align: middle; width: 8.7%;">Trọng số/Bộ phận</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(user, index) in users" :key="index">
                            <td>{{ user.user_name }}</td>
                            <td></td>
                            <td></td>
                            <td>{{ user.total_task }}</td>
                            <td>{{ user.total_complete }}</td>
                            <td>{{ user.total_processing }}</td>
                            <td>{{ user.total_wait }}</td>
                            <td>{{ user.total_complete_slow }}</td>
                            <td>{{ user.total_slow }}</td>
                            <td>{{ user.total_weight }}</td>
                            <td>{{ formatNumber(user.rate_weight) }} %</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import { $get } from "../../ultis";
import moment from "moment";
import Multiselect from 'vue-multiselect';

export default {
    name: "Report",
    components: {
        DatePicker, Multiselect
    },
    data() {
        return {
            projects: [],
            project: '',
            department: '',
            dateRange: '',
            data: [],
            expected: {},
            current: {},
            search: '',
            currentUser: {},
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
            users: [],
            summary: [],
            taskSummary: [],
        }
    },
    created() {
        this.getReport();
        this.getProjects();
    },
    methods: {

        async getProjects() {
            const res = await $get('/projects/get_all');

            this.projects = res.projects
        },
        async getReport() {
            let params = {
                search: this.search,
                project_id: this.project.length? this.project.map(val => val.id): [],
                task_department: this.department.length? this.department.map(val => val.value): [],
                start_date: this.dateRange.length > 1 ? moment(this.dateRange[0]).format('YYYY-MM-DD') : moment().startOf('month').format('YYYY-MM-DD'),
                end_date: this.dateRange.length > 1 ? moment(this.dateRange[1]).format('YYYY-MM-DD') : moment().endOf('month').format('YYYY-MM-DD'),
            }
            const res = await $get('/tasks/get-report', { ...params });
            if (res.code == 200) {
                this.users = res.data;
                this.summary = res.summary;
                this.taskSummary = res.task_summary;
            }

        },
        formatNumber(val) {
            return (Math.round(val * 100) / 100).toFixed(2);
        },
        async exportData() {

            let option = this.option;
            let search = this.search;
            let time = this.timeSelected ? moment(this.timeSelected).format('YYYY-MM-DD') : '';
            // const res = await $get('/time-keeping/export', {...params});

            window.open("/time-keeping-report/export?option=" + option + "&search=" + search + "&time=" + time, '_blank');
        }
    }
}
</script>

<style scoped>

table {
    background: #fff;
    border: 0px solid #999999;
}

table thead tr th {
    padding: 5px;
    border: 1px solid #9b9b9b;
    color: #000;
}

table.table-striped tbody tr:nth-of-type(odd) {
    background: #f9f9f9;
}


.text-left {
    text-align: left !important;
}

table tr td {
    padding: 0px 0px;
    border: 0px solid #999999;
}

table.result-point tr td .fa {
    font-size: 20px;
    position: absolute;
    right: 20px;
}

table.project tr td {
    border: 0px;
    border-bottom: 1px solid #ccc;
}
table.work tr td {
    border: 0px;
    border-bottom: 1px solid #ccc;
}
table.weight tr td {
    border: 0px;
    border-bottom: 1px solid #ccc;
}
table.work-department tr td {
    border: 0px;
    border-bottom: 1px solid #ccc;
}

table tr td {
    padding: 5px 5px;
    border: 1px solid #999999;
}
</style>
