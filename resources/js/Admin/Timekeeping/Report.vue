<template>
    <div class="card">
        <div class="card-header" style="height:50px;">
            <h4>Bảng thống kê chấm công</h4>
            <div style="position: absolute; right: 10px; top: 8px">
                <date-picker v-model="dateRange" type="date" range
                    placeholder="Vui lòng chọn khoảng thời gian thống kê"></date-picker>
                <button class="btn btn-primary" @click="getReport()"
                    style="height:33px; font-size:14px; margin: -5px 0px 0px 0px">Thống kê</button>
                <button v-if=" currentUser.permission == 1" class="btn btn-success" @click="exportData()"
                    style="height:33px; font-size:14px; margin: -5px 0px 0px 0px">Xuất file Excel</button>
            </div>
        </div>
        <div class="card-body table-responsive">
            <div>
                <table class="table-striped table-responsive table-hover result-point">
                    <thead class="point-table-head">
                        <tr style=" text-align:center;">
                            <th rowspan="2" width="500px">Thời gian thống kê</th>
                            <th rowspan="2" width="400px">Công chuẩn</th>
                            <th colspan="3" width="30%" style="background-color: #6cb2eb">Thời gian làm việc chính thức
                                dưới 3 năm</th>
                            <th colspan="3" width="30%" style="background-color: #26C1E0">Thời gian làm việc chính thức
                                trên 3 năm</th>
                        </tr>
                        <tr style=" text-align:center;">
                            <th width="10%" style="background-color: #6cb2eb">WARRIOR 1</th>
                            <th width="10%" style="background-color: #6cb2eb">WARRIOR 2</th>
                            <th width="10%" style="background-color: #6cb2eb">WARRIOR 3</th>
                            <th width="10%" style="background-color: #26C1E0">WARRIOR 1</th>
                            <th width="10%" style="background-color: #26C1E0">WARRIOR 2</th>
                            <th width="10%" style="background-color: #26C1E0">WARRIOR 3</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="expected.end_date > current.end_date">
                            <td><b>Dự kiến:</b>: Từ ngày {{ expected.start_date ? expected.start_date : ".........."}}
                                đến ngày {{ expected.end_date ? expected.end_date : ".........."}}</td>
                            <td style=" text-align:center;">{{ expected.total }}</td>
                            <td style=" text-align:center; background-color: #6cb2eb">{{ expected.warrior1 }}</td>
                            <td style=" text-align:center; background-color: #6cb2eb">{{ expected.warrior2 }}</td>
                            <td style=" text-align:center; background-color: #6cb2eb">{{ expected.warrior3 }}</td>
                            <td style=" text-align:center; background-color: #26C1E0">{{ expected.warrior1_3 }}</td>
                            <td style=" text-align:center; background-color: #26C1E0">{{ expected.warrior1 }}</td>
                            <td style=" text-align:center; background-color: #26C1E0">{{ expected.warrior2 }}</td>
                        </tr>
                        <tr>
                            <td v-if="expected.end_date > current.end_date">
                                <b>Hiện tại:</b> Từ ngày {{ current.start_date ? current.start_date : ".........." }}
                                đến ngày {{ current.end_date ? current.end_date: ".........."}}
                            </td>
                            <td v-if="expected.end_date <= current.end_date">
                                <b>Thống kê:</b> Từ ngày {{ current.start_date ? current.start_date : ".........." }}
                                đến ngày {{ current.end_date ? current.end_date: ".........."}}
                            </td>
                            <td style=" text-align:center;">{{ current.total }}</td>
                            <td style=" text-align:center; background-color: #6cb2eb">{{ current.warrior1 }}</td>
                            <td style=" text-align:center; background-color: #6cb2eb">{{ current.warrior2 }}</td>
                            <td style=" text-align:center; background-color: #6cb2eb">{{ current.warrior3 }}</td>
                            <td style=" text-align:center; background-color: #26C1E0">{{ current.warrior1_3 }}</td>
                            <td style=" text-align:center; background-color: #26C1E0">{{ current.warrior1 }}</td>
                            <td style=" text-align:center; background-color: #26C1E0">{{ current.warrior2 }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div>
                <table class="table-striped table-responsive table-hover result-point">
                    <thead class="point-table-head">
                        <tr style="vertical-align: middle; font-size:12px; text-align:center; ">

                            <th style="vertical-align: middle; width:180px;" rowspan="2">
                                <input type="text" name="search" class="form-control mb-2 input-search" v-model="search"
                                    placeholder="Tìm kiếm" v-on:keyup.enter="getReport()">
                            </th>
                            <!--<th rowspan="2" style="vertical-align: middle;">Mã Nhân viên</th>-->
                            <th style="vertical-align: middle; width: 100px;" rowspan="2"
                                @click="sort('date_official')">Ngày làm việc
                                chính thức</th>
                            <!--<th style="vertical-align: middle; width:160px;" rowspan="2">Số ngày đã làm việc chính thức
                            </th>-->
                            <th colspan="2" style="vertical-align: middle;">Đi muộn</th>
                            <th colspan="2" style="vertical-align: middle;">Về sớm</th>
                            <th rowspan="2" style="vertical-align: middle;">Tổng giờ ĐMVS</th>
                            <th rowspan="2" style="vertical-align: middle;">Số giờ đi sớm</th>
                            <th rowspan="2" style="vertical-align: middle;">Số giờ về muộn</th>
                            <th rowspan="2" style="vertical-align: middle;">Số ngày chấm công</th>
                            <th rowspan="2" style="vertical-align: middle;">Công thực tế</th>
                            <th rowspan="2" style="vertical-align: middle;">Công đăng ký làm thêm</th>
                            <th rowspan="2" style="vertical-align: middle;">Công đăng ký nỗ lực</th>
                            <th rowspan="2" style="vertical-align: middle;">Công nghỉ</th>
                            <th rowspan="2" style="vertical-align: middle;">Không checkout</th>
                            <th rowspan="2" style="vertical-align: middle;">Tổng giờ nỗ lực</th>
                            <th rowspan="2" style="vertical-align: middle; width:80px;">Warrior hiện tại</th>
                            <th rowspan="2" style="vertical-align: middle;" v-if="expected.end_date > current.end_date">
                                TG để giữ Warrior</th>
                            <th rowspan="2" style="vertical-align: middle;" v-if="expected.end_date > current.end_date">
                                TGTB để giữ Warrior</th>
                            <th rowspan="2" style="vertical-align: middle; width:80px;"
                                v-if="expected.end_date > current.end_date">Warrior tiếp theo</th>
                            <th rowspan="2" style="vertical-align: middle;" v-if="expected.end_date > current.end_date">
                                TG để lên Warrior</th>
                            <th rowspan="2" style="vertical-align: middle;" v-if="expected.end_date > current.end_date">
                                TGTB để lên Warrior</th>
                            <th rowspan="2" style="vertical-align: middle; width:70px; +">Tỷ lệ đi muộn</th>
                        </tr>
                        <tr style="font-size:12px; text-align:center;">
                            <th style="vertical-align: middle;">Số lần</th>
                            <th style="vertical-align: middle;">Thời gian</th>
                            <th style="vertical-align: middle;">Số lần</th>
                            <th style="vertical-align: middle;">Thời gian</th>
                        </tr>
                    </thead>
                    <tbody style="font-size:14px;">
                        <tr v-for="(user, index) in data" :key="index">
                            <td>{{ user.fullname }}</td>
                            <!--<td style="text-align:center;">{{ user.id }}</td>-->
                            <td style=" text-align:center;" v-if="user.date_official_new != 0">{{
                                user.date_official_new}}</td>
                            <td style=" text-align:center;" v-else><b>Thử việc</b></td>
                            <!--<td style=" text-align:right;" v-if="user.date_official_new != 0">
                                {{ user.totalWorkDateY ? user.totalWorkDateY+" năm":" "}}
                                {{ user.totalWorkDateM ? user.totalWorkDateM+" tháng" :" "}}
                                {{ user.totalWorkDateD ? user.totalWorkDateD+" ngày" :" "}}
                            </td>
                            <td v-else></td>-->
                            <td>{{ user.totalGoLate }}</td>
                            <td>{{ formatNumber(user.timeGoLate) }}</td>
                            <td>{{ user.totalAboutEarly }}</td>
                            <td>{{ formatNumber(user.timeAboutEarly) }}</td>
                            <td>{{ user.totalGoLateAboutEarly }}</td>
                            <td>{{ formatNumber(user.timeGoEarly) }}</td>
                            <td>{{ formatNumber(user.timeAboutLate) }}</td>
                            <td>{{ user.totalWorkingDays }}</td>
                            <td>{{ user.totalTimeKeeping }}</td>
                            <td>{{ user.totalOT}}</td>
                            <td>{{ user.totalWar}}</td>
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
                            <td> {{ formatNumber(user.rateGoLate)}} %</td>
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
import {$get} from "../../ultis";
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
                search: this.search ,
                start_date: this.dateRange.length > 1 ? moment(this.dateRange[0]).format('YYYY-MM-DD') : moment().startOf('month').format('YYYY-MM-DD'),
                end_date: this.dateRange.length > 1 ? moment(this.dateRange[1]).format('YYYY-MM-DD') : moment().endOf('month').format('YYYY-MM-DD'),
            }
            const res = await $get('/time-keeping/get-report', {...params});
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
                let time = this.timeSelected? moment(this.timeSelected).format('YYYY-MM-DD'): '';
            // const res = await $get('/time-keeping/export', {...params});

            window.open("/time-keeping-report/export?option="+option+"&search="+search+"&time="+time,'_blank');
        }
    }
}
</script>

<style scoped>
table {
    background: #fff;
    border: 1px solid #999999;
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
    text-align: left!important;
}

table tr td {
    padding: 0px 0px;
    border: 1px solid #999999;
}

table.result-point tr td .fa {
    font-size: 20px;
    position: absolute;
    right: 20px;
}

table tr td {
    padding: 5px 5px;
    border: 1px solid #999999;
}
</style>
