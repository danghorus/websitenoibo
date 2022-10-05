<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.17/vue.js"></script>

<template>
    <div>
         <nav class="navbar navbar-expand-lg navbar-light bg-light" >
                <ul class="navbar-nav mr-auto" style="font-size:16px;" >
                    <li class="nav-item" style="width:600px;">
                        <h1 style="margin: 0px 0px 0px 20px">BẢNG THỐNG KÊ CÔNG VIỆC</h1>
                        <div class="col-lg-6" style="margin-top:0px; padding-left:20px;">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" v-model="option" value="2" name="flexRadioDefault" id="flexRadioDefault2">
                                <label class="form-check-label" for="flexRadioDefault2"><h5>Bảng tổng</h5></label>
                            </div>
                        </div>
                    </li>
        <!--<div style="width:400px; margin: -80px 0px 0px 650px;">
            <span>Thống kê theo dự án</span>
            <multiselect @click="getReport()" v-model="project" :options="projects" :multiple="true" placeholder="Vui lòng chọn"
                track-by="id" label="project_name">
                <span slot="noResult">Oops! No elements found. Consider changing the search query.</span>
            </multiselect>
        </div>-->
                <li class="nav-item" style="width:540px"></li>
                <li class="nav-item">
                    <div>
                        <span>Thống kê theo dự án</span>
                        <select class="form-select" @click="getReport()" v-model="project" style="width:350px;">
                            <option value="" selected="selected">Tất cả</option>
                            <option v-for="(project, index) in projects" :key="index" :value="project.id">{{project.project_name}}</option>
                        </select>
                    </div>
                </li> &emsp;&emsp;
                <li class="nav-item">
                    <div >
                        <span>Thống kê theo bộ phận</span>
                        <select class="form-select" @click="getReport()" v-model="department" style="width:350px; ">
                            <option value="" selected="selected">Tất cả</option>
                            <option value="2">DEV</option>
                            <option value="3">Game Design</option>
                            <option value="4">ART</option>
                            <option value="5">Tester</option>
                            <option value="11">Marketing</option>
                        </select>
                    </div>
                </li>
            </ul>
        </nav>
        <!--<div style="width:400px; margin: -67px 0px 0px 1060px;">
            <span>Thống kê theo bộ phận</span>
            <multiselect v-model="department" :options="departments" value="2" :multiple="true" placeholder="Vui lòng chọn"
                track-by="value" label="label">
                <span slot="noResult">Oops! No elements found. Consider changing the search query.</span>
            </multiselect>
        </div>-->
        <!--<div style="width:400px; margin: -59px 0px 0px 1470px;">
            <span>Thời gian thống kê</span>
            <date-picker v-model="dateRange" type="date" range placeholder="Vui lòng chọn khoảng thời gian thống kê">
            </date-picker>
        </div>
        <div style="width:320px; margin: -29px 0px 0px 1800px;">
            <button class="btn btn-primary" @click="getReport()"
                style="height:33px; font-size:14px; margin: -5px 0px 0px 0px">Thống kê
            </button>
        </div>--><br>
        <div class="card-body" style="margin-top:-30px;">
            <div class="row">
                <div class="col-lg-12" v-if="option == 1">
                    <table class="project table-responsive" style="width: 100%">
                        <tr>
                            <td width="250px"></td>
                            <td width="68px"></td>
                            <td>
                                <h4 style="text-align:right; width:120px;">Tổng</h4>
                            </td>
                             <td>
                                <h4 style="text-align:right; width:350px;">Game Design</h4>
                            </td>
                            <td>
                                <h4 style="text-align:right; width:350px;">ART</h4>
                            </td>
                            <td>
                                <h4 style="text-align:right; width:350px;">DEV</h4>
                            </td>
                            <td>
                                <h4 style="text-align:right; width:350px;">Tester</h4>
                            </td>
                        </tr>
                        <tr>
                            <td><b>Tổng</b></td>
                            <td>:</td>
                            <td style="text-align:right">{{ taskSummary.total_task }} công việc</td>
                            <td style="text-align:right">{{ taskSummary.total_task_gd }} công việc</td>
                            <td style="text-align:right">{{ taskSummary.total_task_art }} công việc</td>
                            <td style="text-align:right">{{ taskSummary.total_task_dev }} công việc</td>
                            <td style="text-align:right">{{ taskSummary.total_task_test }} công việc</td>
                        </tr>
                        <tr>
                            <td><b>Đang làm</b></td>
                            <td>:</td>
                            <td style="text-align:right">{{ taskSummary.total_processing }} công việc</td>
                            <td style="text-align:right">{{ taskSummary.total_processing_gd }} công việc</td>
                            <td style="text-align:right">{{ taskSummary.total_processing_art }} công việc</td>
                            <td style="text-align:right">{{ taskSummary.total_processing_dev }} công việc</td>
                            <td style="text-align:right">{{ taskSummary.total_processing_test }} công việc</td>
                        </tr>
                        <tr>
                            <td><b>Đang chờ</b></td>
                            <td>:</td>
                            <td style="text-align:right">{{ taskSummary.total_wait }} công việc</td>
                            <td style="text-align:right">{{ taskSummary.total_wait_gd }} công việc</td>
                            <td style="text-align:right">{{ taskSummary.total_wait_art }} công việc</td>
                            <td style="text-align:right">{{ taskSummary.total_wait_dev }} công việc</td>
                            <td style="text-align:right">{{ taskSummary.total_wait_test }} công việc</td>
                        </tr>
                        <tr>
                            <td><b>Tạm dừng</b></td>
                            <td>:</td>
                            <td style="text-align:right">{{ taskSummary.total_pause }} công việc</td>
                            <td style="text-align:right">{{ taskSummary.total_pause_gd }} công việc</td>
                            <td style="text-align:right">{{ taskSummary.total_pause_art }} công việc</td>
                            <td style="text-align:right">{{ taskSummary.total_pause_dev }} công việc</td>
                            <td style="text-align:right">{{ taskSummary.total_pause_test }} công việc</td>
                        </tr>
                        <tr>
                            <td><b>Chờ feedback</b></td>
                            <td>:</td>
                            <td style="text-align:right">{{ taskSummary.total_wait_fb }} công việc</td>
                            <td style="text-align:right">{{ taskSummary.total_wait_fb_gd }} công việc</td>
                            <td style="text-align:right">{{ taskSummary.total_wait_fb_art }} công việc</td>
                            <td style="text-align:right">{{ taskSummary.total_wait_fb_dev }} công việc</td>
                            <td style="text-align:right">{{ taskSummary.total_wait_fb_test }} công việc</td>
                        </tr>
                        <tr>
                            <td><b>Làm lại</b></td>
                            <td>:</td>
                            <td style="text-align:right">{{ taskSummary.total_again }} công việc</td>
                            <td style="text-align:right">{{ taskSummary.total_again_gd }} công việc</td>
                            <td style="text-align:right">{{ taskSummary.total_again_art }} công việc</td>
                            <td style="text-align:right">{{ taskSummary.total_again_dev }} công việc</td>
                            <td style="text-align:right">{{ taskSummary.total_again_test }} công việc</td>
                        </tr>
                        <tr>
                            <td><b>Hoàn thành đúng hạn</b></td>
                            <td>:</td>
                            <td style="text-align:right">{{ taskSummary.total_complete - taskSummary.total_complete_slow  }} công việc</td>
                            <td style="text-align:right">{{ taskSummary.total_complete_gd - taskSummary.total_complete_slow_gd  }} công việc</td>
                            <td style="text-align:right">{{ taskSummary.total_complete_art - taskSummary.total_complete_slow_art  }} công việc</td>
                            <td style="text-align:right">{{ taskSummary.total_complete_dev - taskSummary.total_complete_slow_dev  }} công việc</td>
                            <td style="text-align:right">{{ taskSummary.total_complete_test - taskSummary.total_complete_slow_test }} công việc</td>
                        </tr>
                        <tr>
                            <td><b>Hoàn thành chậm</b></td>
                            <td>:</td>
                            <td style="text-align:right">{{ taskSummary.total_complete_slow }} công việc</td>
                            <td style="text-align:right">{{ taskSummary.total_complete_slow_gd }} công việc</td>
                            <td style="text-align:right">{{ taskSummary.total_complete_slow_art }} công việc</td>
                            <td style="text-align:right">{{ taskSummary.total_complete_slow_dev }} công việc</td>
                            <td style="text-align:right">{{ taskSummary.total_complete_slow_test }} công việc</td>
                        </tr>
                        <tr>
                            <td><b>Quá hạn</b></td>
                            <td>:</td>
                            <td style="text-align:right">{{ taskSummary.total_slow }} công việc</td>
                            <td style="text-align:right">{{ taskSummary.total_slow_gd }} công việc</td>
                            <td style="text-align:right">{{ taskSummary.total_slow_art }} công việc</td>
                            <td style="text-align:right">{{ taskSummary.total_slow_dev }} công việc</td>
                            <td style="text-align:right">{{ taskSummary.total_slow_test }} công việc</td>
                        </tr>
                        <tr>
                            <td><b>Trọng số</b></td>
                            <td>:</td>
                            <td style="text-align:right">{{ taskSummary.total_weight }} &emsp;&ensp;&nbsp; điểm</td>
                            <td style="text-align:right">{{ taskSummary.total_weight_gd }} &emsp;&ensp;&nbsp; điểm</td>
                            <td style="text-align:right">{{ taskSummary.total_weight_art }} &emsp;&ensp;&nbsp; điểm</td>
                            <td style="text-align:right">{{ taskSummary.total_weight_dev }} &emsp;&ensp;&nbsp; điểm</td>
                            <td style="text-align:right">{{ taskSummary.total_weight_test }} &emsp;&ensp;&nbsp; điểm</td>
                        </tr>
                         <tr>
                            <td><b>Trọng số đã hoàn thành</b></td>
                            <td>:</td>
                            <td style="text-align:right">{{ taskSummary.total_weight_complete }} &emsp;&ensp;&nbsp; điểm</td>
                            <td style="text-align:right">{{ taskSummary.total_weight_gd_complete }} &emsp;&ensp;&nbsp; điểm</td>
                            <td style="text-align:right">{{ taskSummary.total_weight_art_complete }} &emsp;&ensp;&nbsp; điểm</td>
                            <td style="text-align:right">{{ taskSummary.total_weight_dev_complete }} &emsp;&ensp;&nbsp; điểm</td>
                            <td style="text-align:right">{{ taskSummary.total_weight_test_complete }} &emsp;&ensp;&nbsp; điểm</td>
                        </tr>
                        <tr>
                            <td><b>Tỉ lệ hoàn thành(công việc)</b></td>
                            <td>:</td>
                            <td style="text-align:right" v-if="taskSummary.total_task !=0">{{ formatNumber((taskSummary.total_complete)*100 / (taskSummary.total_task)) }} &emsp;&ensp;&nbsp; %</td>
							<td style="text-align:right" v-else>0 &emsp;&ensp;&nbsp; %</td>
                            <td style="text-align:right" v-if="taskSummary.total_task_gd !=0">{{ formatNumber((taskSummary.total_complete_gd)*100 / (taskSummary.total_task_gd)) }} &emsp;&ensp;&nbsp; %</td>
							<td style="text-align:right" v-else>0 &emsp;&ensp;&nbsp; %</td>
                            <td style="text-align:right" v-if="taskSummary.total_task_art != 0">{{ formatNumber((taskSummary.total_complete_art)*100 / (taskSummary.total_task_art)) }} &emsp;&ensp;&nbsp; %</td>
							<td style="text-align:right" v-else>0 &emsp;&ensp;&nbsp; %</td>
                            <td style="text-align:right" v-if="taskSummary.total_task_dev != 0">{{ formatNumber((taskSummary.total_complete_dev)*100 / (taskSummary.total_task_dev)) }} &emsp;&ensp;&nbsp; %</td>
							<td style="text-align:right" v-else>0 &emsp;&ensp;&nbsp; %</td>
                            <td style="text-align:right" v-if="taskSummary.total_task_test != 0">{{ formatNumber((taskSummary.total_complete_test)*100 / (taskSummary.total_task_test)) }} &emsp;&ensp;&nbsp; %</td>
							<td style="text-align:right" v-else>0 &emsp;&ensp;&nbsp; %</td>
                        </tr>
                        <tr>
                            <td><b>Tỉ lệ hoàn thành(Trọng số)</b></td>
                            <td>:</td>
                            <td style="text-align:right">{{taskSummary.total_weight?formatNumber((taskSummary.total_weight_complete)*100 / (taskSummary.total_weight)): 0}} &emsp;&ensp;&nbsp; %</td>
                            <td style="text-align:right" v-if="taskSummary.total_weight_gd !=0">{{formatNumber((taskSummary.total_weight_gd_complete)*100 / (taskSummary.total_weight_gd))}} &emsp;&ensp;&nbsp; %</td>
                            <td style="text-align:right" v-else> 0 &emsp;&ensp;&nbsp; %</td>
                            <td style="text-align:right" v-if="taskSummary.total_weight_art !=0">{{formatNumber((taskSummary.total_weight_art_complete)*100 / (taskSummary.total_weight_art))}} &emsp;&ensp;&nbsp; %</td>
                            <td style="text-align:right" v-else> 0 &emsp;&ensp;&nbsp; %</td>
                            <td style="text-align:right" v-if="taskSummary.total_weight_dev !=0">{{formatNumber((taskSummary.total_weight_dev_complete)*100 / (taskSummary.total_weight_dev))}} &emsp;&ensp;&nbsp; %</td>
                            <td style="text-align:right" v-else> 0 &emsp;&ensp;&nbsp; %</td>
                            <td style="text-align:right" v-if="taskSummary.total_weight_test !=0">{{formatNumber((taskSummary.total_weight_test_complete)*100 / (taskSummary.total_weight_test))}} &emsp;&ensp;&nbsp; %</td>
                            <td style="text-align:right" v-else> 0 &emsp;&ensp;&nbsp; %</td>
                        </tr>
                    </table>
                </div>
            </div>
            <br>
            <div>
                <table class="table-striped table-responsive table-hover result-point">
                    <thead class="point-table-head">
                        <tr style="vertical-align: middle; text-align:center; ">

                            <th style="vertical-align: middle; width:300px;" rowspan="2">
                                <input type="text" name="search" class="form-control mb-2 input-search" v-model="search"
                                    placeholder="Tìm kiếm" v-on:keyup.enter="getReport()">
                            </th>
                            <th style="vertical-align: middle; width: 6.5%;">Warrior đăng ký</th>
                            <th style="vertical-align: middle; width: 6.5%;">Tổng số công việc</th>
                            <th style="vertical-align: middle; width: 6.5%;">Hoàn thành</th>
                            <th style="vertical-align: middle; width: 6.5%;">Hoàn thành chậm</th>
                            <th style="vertical-align: middle; width: 6.5%;">Đang làm</th>
                            <th style="vertical-align: middle; width: 6.5%;">Tạm dừng</th>
                            <th style="vertical-align: middle; width: 6.5%;">Đang chờ</th>
                            <th style="vertical-align: middle; width: 6.5%;">Chờ feedback</th>
                            <th style="vertical-align: middle; width: 6.5%;">Làm lại</th>
                            <th style="vertical-align: middle; width: 6.5%;">Quá hạn</th>
							<th style="vertical-align: middle; width: 6.5%;">Tỉ lệ công việc</th>
                            <th style="vertical-align: middle; width: 6.5%;">Trọng số</th>
                            <th style="vertical-align: middle; width: 6.5%;">Trọng số/dự án</th>
							<th style="vertical-align: middle; width: 6.5%;">Trọng số/bộ phận</th>
                        </tr>
                    </thead>
                    <tbody v-for="(user, index) in users" :key="index">
                        <template v-if="user.warrior_p == project ">
                            <tr> 
                                <td>{{ user.user_name }}</td>
                                <td>Warrior {{ user.warrior }}</td>
                                <td>{{ user.total_task }}</td>
                                <td>{{ user.total_complete - user.total_complete_slow  }}</td>
                                <td>{{ user.total_complete_slow }}</td>
                                <td>{{ user.total_processing }}</td>
                                <td>{{ user.total_pause }}</td>
                                <td>{{ user.total_wait }}</td>
                                <td>{{ user.total_wait_fb }}</td>
                                <td>{{ user.total_again }}</td>
                                <td>{{ user.total_slow }}</td>
                                <td>{{ formatNumber((user.total_complete)*100/(user.total_task)) }} %</td>
                                <td>{{ user.total_weight }}</td>
                                <td>{{ formatNumber((user.total_weight)*100/(taskSummary.total_weight)) }} %</td>
                                <td>{{ formatNumber(user.rate_weight) }} %</td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
        <vuetiful-board :theme="classic" :dark-mode="true" :col-num="12" :row-height="30" :layout-editable="true"
            :datasets="[
              {
                  chartInfo: {
                    series: [200, 12, 40, 25, 34, 6, 23],
                    options: {
                      chart: {
                        type: 'pie',
                      },
                      title: {
                        text: 'The recent inflow route',
                        align: 'center',
                        style: {
                          fontSize: '18px',
                          fontWeight: 'bold',
                        },
                      },
                      labels: [
                        'SNS',
                        'Recommend',
                        'Homepage',
                        'Blog',
                        'Kakaotalk Channel',
                        'Rumor',
                        'ETC',
                      ],
                      fill: {
                        opacity: 1,
                      },
                      legend: {
                        position: 'bottom',
                      }
                    }
                  },
                  gridInfo: {
                    x: 6, y: 0, w: 6, h: 12, i: '1', static: false
                  },
                },
            ]" />
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
            option:1,
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
                //{ value: 1, label: 'Admin' },
                { value: 2, label: 'Dev' },
                { value: 3, label: 'Game design' },
                { value: 4, label: 'Art' },
                { value: 5, label: 'Tester' },
                //{ value: 6, label: 'Điều hành' },
                //{ value: 7, label: 'Hành chính nhân sự' },
                //{ value: 8, label: 'Kế toán' },
                { value: 9, label: 'Phân tích dữ liệu' },
                //{ value: 10, label: 'Support' },
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
                project_id: this.project,
                task_department: this.department,
                //task_department: this.department.length? this.department.map(val => val.value): [],
                //start_date: this.dateRange.length > 1 ? moment(this.dateRange[0]).format('YYYY-MM-DD') : moment().startOf('month').format('YYYY-MM-DD'),
                //end_date: this.dateRange.length > 1 ? moment(this.dateRange[1]).format('YYYY-MM-DD') : moment().endOf('month').format('YYYY-MM-DD'),
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
