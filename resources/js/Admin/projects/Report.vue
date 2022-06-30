<template>
    <div>
        <h1 style="margin: 0px 0px 0px 20px">BẢNG THỐNG KÊ CÔNG VIỆC</h1>
        <div style="width:400px; margin: -40px 0px 0px 650px;">
            <span>Thống kê theo dự án</span>
            <select class="form-select form-select-sm" aria-label=".form-select-sm example" style="height:35px;">
                <option selected="selected">Tất cả</option>
                <option>Beach War version 0.1.3</option>
                <option>Beach War version 0.1.4</option>
                <option>Beach War version 0.1.5</option>
            </select>
        </div>
        <div style="width:400px; margin: -59px 0px 0px 1060px;">
            <span>Thống kê theo bộ phận</span>
            <select class="form-select form-select-sm" aria-label=".form-select-sm example" style="height:35px;">
                <option selected="selected">Tất cả</option>
                <option>Dev</option>
                <option>Art</option>
                <option>Game Design</option>
                <option>Tester</option>
            </select>
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
        <div class="card-body table-responsive">
            <table class="project" style=" width:600px;">
                <tr>
                    <td colspan="3">
                        <h4 style="text-align:center;">Công việc</h4>
                    </td>
                </tr>
                <tr>
                    <td><b>Tổng</b></td>
                    <td>:</td>
                    <td>120 công việc</td>
                </tr>
                <tr>
                    <td><b>Hoàn thành</b></td>
                    <td>:</td>
                    <td>80 công việc</td>
                </tr>
                <tr>
                    <td><b>Chưa hoàn thành</b></td>
                    <td>:</td>
                    <td>20 công việc</td>
                </tr>
                <tr>
                    <td><b>Quá hạn</b></td>
                    <td>:</td>
                    <td>20 công việc</td>
                </tr>
            </table>
            <table class="work-department" style="margin: -189px 0px 0px 635px; width:600px;">
                <tr>
                    <td colspan="3">
                        <h4 style="text-align:center;">Công việc bộ phận</h4>
                    </td>
                </tr>
                <tr>
                    <td><b>Dev</b></td>
                    <td>:</td>
                    <td>120 công việc</td>
                </tr>
                <tr>
                    <td><b>Art</b></td>
                    <td>:</td>
                    <td>80 công việc</td>
                </tr>
                <tr>
                    <td><b>Game Design</b></td>
                    <td>:</td>
                    <td>20 công việc</td>
                </tr>
                <tr>
                    <td><b>Tester</b></td>
                    <td>:</td>
                    <td>20 công việc</td>
                </tr>
            </table>
            <table class="weight" style="margin: -189px 0px 0px 1270px; width:600px;">
                <tr>
                    <td colspan="3">
                        <h4 style="text-align:center;">Trọng số</h4>
                    </td>
                </tr>
                <tr>
                    <td><b>Dev</b></td>
                    <td>:</td>
                    <td>1200</td>
                </tr>
                <tr>
                    <td><b>Art</b></td>
                    <td>:</td>
                    <td>1200</td>
                </tr>
                <tr>
                    <td><b>Game Design</b></td>
                    <td>:</td>
                    <td>1200</td>
                </tr>
                <tr>
                    <td><b>Tester</b></td>
                    <td>:</td>
                    <td>1200</td>
                </tr>
            </table>
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
                            <th style="vertical-align: middle; width: 8.7%;">Bộ phận</th>
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
                        <tr v-for="(user, index) in data" :key="index">
                            <td>{{ user.fullname }}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                    <!--<tbody style="font-size:14px;">
                        <tr v-for="(user, index) in data" :key="index">
                            <td>{{ user.fullname }}</td>
                            <td style=" text-align:center;" v-if="user.date_official_new != 0">{{
                                user.date_official_new
                                }}</td>
                            <td style=" text-align:center;" v-else><b>Thử việc</b></td>
                            <td>{{ user.totalGoLate }}</td>
                            <td>{{ formatNumber(user.timeGoLate) }}</td>
                            <td>{{ user.totalAboutEarly }}</td>
                            <td>{{ formatNumber(user.timeAboutEarly) }}</td>
                            <td>{{ user.totalGoLateAboutEarly }}</td>
                            <td>{{ formatNumber(user.timeGoEarly) }}</td>
                            <td>{{ formatNumber(user.timeAboutLate) }}</td>
                            <td>{{ user.totalWorkingDays }}</td>
                            <td>{{ user.totalTimeKeeping }}</td>
                            <td>{{ user.totalOT }}</td>
                            <td>{{ user.totalWar }}</td>
                            <td>{{ user.totalUnpaidLeave }}</td>
                            <td>{{ user.totalNotCheckOut }}</td>
                            <td>{{ formatNumber(user.totalHourEfforts) }}</td>
                            <td>{{ user.currentWar }}</td>
                            <td v-if="expected.end_date > current.end_date">{{ formatNumber(user.timeHoldWar) }}</td>
                            <td v-if="expected.end_date > current.end_date">{{ formatNumber(user.avgTimeHoldWar) }}</td>
                            <td v-if="expected.end_date > current.end_date">{{ user.nextWar }}</td>
                            <td v-if="expected.end_date > current.end_date">{{ formatNumber(user.timeIncreaseWar) }}
                            </td>
                            <td v-if="expected.end_date > current.end_date">{{ formatNumber(user.avgTimeIncreaseWar) }}
                            </td>
                            <td> {{ formatNumber(user.rateGoLate) }} %</td>
                        </tr>
                    </tbody>-->
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

export default {
    name: "Report",
    components: {
        DatePicker
    },
    data() {
        return {
            dateRange: '',
            data: [],
            expected: {},
            current: {},
            search: '',
            currentUser: {},

        }
    },
    created() {
        this.getReport();
    },
    methods: {
        async getReport() {
            let params = {
                search: this.search,
                start_date: this.dateRange.length > 1 ? moment(this.dateRange[0]).format('YYYY-MM-DD') : moment().startOf('month').format('YYYY-MM-DD'),
                end_date: this.dateRange.length > 1 ? moment(this.dateRange[1]).format('YYYY-MM-DD') : moment().endOf('month').format('YYYY-MM-DD'),
            }
            const res = await $get('/time-keeping/get-report', { ...params });
            if (res.code == 200) {
                this.data = res.data.result;
                this.expected = res.data.expected;
                this.current = res.data.current;
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
