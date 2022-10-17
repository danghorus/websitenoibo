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
                            <td width="500px">Thời gian thống kê</td>
                            <td width="400px" colspan="2">Công chuẩn</td>
                            <td colspan="3" width="30%" style="background-color: #6cb2eb">Thời gian làm việc chính thức
                                dưới 3 năm</td>
                            <td colspan="3" width="30%" style="background-color: #26C1E0">Thời gian làm việc chính thức
                                trên 3 năm</td>
                        </tr>
                        <tr style=" text-align:center;">
                            <td style=" text-align:left;">Công nghỉ tiêu chuẩn theo công ty: <b>{{
                                    current.totalHoliday}} </b> công
                            </td>
                            <td style=" text-align:center;">Chuẩn tháng</td>
                            <td style=" text-align:center;">Thực tế</td>
                            <td width="10%" style="background-color: #6cb2eb">WARRIOR 1</td>
                            <td width="10%" style="background-color: #6cb2eb">WARRIOR 2</td>
                            <td width="10%" style="background-color: #6cb2eb">WARRIOR 3</td>
                            <td width="10%" style="background-color: #26C1E0">WARRIOR 1</td>
                            <td width="10%" style="background-color: #26C1E0">WARRIOR 2</td>
                            <td width="10%" style="background-color: #26C1E0">WARRIOR 3</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="expected.end_date > current.end_date">
                            <td><b>Dự kiến:</b>: Từ ngày {{ expected.start_date ? expected.start_date : ".........."}}
                                đến ngày {{ expected.end_date ? expected.end_date : ".........."}}</td>
                            <td style=" text-align:center;">{{ expected.total }}</td>
                            <td style=" text-align:center;">{{ expected.total_real }}</td>
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
                            <td style=" text-align:center;">{{ current.total_real }}</td>
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

                            <th style="vertical-align: middle; width:205px; border:0px;" rowspan="2">
                                <input style="border:0px;" type="text" name="search" class="form-control mb-2 input-search" v-model="search"
                                    placeholder="Tìm kiếm" @input="changeOption()">
                            </th>
                            <th style="vertical-align: middle; width:90px;" rowspan="2" @click="sort('date_official')">
                                Ngày làm việc chính thức</th>
                            <th style="vertical-align: middle; width:160px;" rowspan="2">Số ngày đã làm việc chính thức
                            </th>
                            <th colspan="2" style="vertical-align: middle;">Đi muộn</th>
                            <th colspan="2" style="vertical-align: middle;">Về sớm</th>
                            <th colspan="2" style="vertical-align: middle;">Ra ngoài</th>
                            <th rowspan="2" style="vertical-align: middle; width:60px;">Tổng giờ ĐMVS</th>
                            <th rowspan="2" style="vertical-align: middle; width:70px;">Số giờ đi sớm</th>
                            <th rowspan="2" style="vertical-align: middle; width:70px;">Số giờ về muộn</th>
                            <th rowspan="2" style="vertical-align: middle; width:70px;">Công thực tế</th>
							<th rowspan="2" style="vertical-align: middle; width:70px;">Nghỉ phép có lương</th>
                            <th rowspan="2" style="vertical-align: middle; width:60px;">Nghỉ phép có lương(12)</th>
                            <th rowspan="2" style="vertical-align: middle; width:60px;">Công tính lương</th>
                            <th rowspan="2" style="vertical-align: middle; width:80px;">Ngày phép có lương còn lại</th>
                            <th rowspan="2" style="vertical-align: middle; width:60px;">Công đăng ký làm thêm</th>
                            <th rowspan="2" style="vertical-align: middle; width:66px;">Công đăng ký nỗ lực</th>
                            <th rowspan="2" style="vertical-align: middle; width:60px;">Nghỉ không lương</th>
                            <th rowspan="2" style="vertical-align: middle; width:66px;">Tổng giờ nỗ lực</th>
                            <th rowspan="2" style="vertical-align: middle; width:90px;">Warrior hiện tại</th>
                            <th rowspan="2" style="vertical-align: middle; width:65px;"
                                v-if="expected.end_date > current.end_date">TG để giữ Warrior</th>
                            <th rowspan="2" style="vertical-align: middle; width:65px;"
                                v-if="expected.end_date > current.end_date">TGTB để giữ Warrior</th>
                            <th rowspan="2" style="vertical-align: middle; width:90px;"
                                v-if="expected.end_date > current.end_date">Warrior tiếp theo</th>
                            <th rowspan="2" style="vertical-align: middle;width:65px;"
                                v-if="expected.end_date > current.end_date"> TG để lên Warrior</th>
                            <th rowspan="2" style="vertical-align: middle;width:65px;"
                                v-if="expected.end_date > current.end_date">TGTB để lên Warrior</th>
                            <th rowspan="2" style="vertical-align: middle; width:80px;">Tỷ lệ đi muộn</th>
							<th rowspan="2" style="vertical-align: middle; width:20px;"></th>
                        </tr>
                        <tr style="font-size:12px; text-align:center;">
                            <th style="vertical-align: middle; width:40px;">Số lần</th>
                            <th style="vertical-align: middle; width:60px;">Thời gian</th>
                            <th style="vertical-align: middle; width:40px;">Số lần</th>
                            <th style="vertical-align: middle; width:60px;">Thời gian</th>
                            <th style="vertical-align: middle; width:40px;">Số lần</th>
                            <th style="vertical-align: middle; width:60px;">Thời gian</th>
                        </tr>
                    </thead>
                    <tbody class="detail" style="font-size:14px;">
                        <tr v-for="(user, index) in data" :key="index">

                            <td style="width:200px;font-size:16px" v-if="user.rateGoLate >= 12.5" ><b style="color:red;">{{ user.fullname }}</b></td>
                            <td style="width:200px;" v-else>{{ user.fullname }}</td>

                            <td style=" text-align:center; width:90px;" v-if="user.date_official_new != 0">{{
                                user.date_official_new}}</td>
                            <td style=" text-align:center;  width:90px;" v-else><b>Thử việc</b></td>
                            <td style=" text-align:right; width:150px;" v-if="user.date_official_new != 0">
                                {{ user.totalWorkDateY ? user.totalWorkDateY+" năm":" "}}
                                {{ user.totalWorkDateM ? user.totalWorkDateM+" tháng" :" "}}
                                {{ user.totalWorkDateD ? user.totalWorkDateD:" "}}
                            </td>
                            <td v-else style=" text-align:right; width:150px;"> Thử việc</td>
                            <td style="width:40px;">{{ user.totalGoLate }}</td>
                            <td style="width:60px;">{{ formatNumber(user.timeGoLate) }}</td>
                            <td style="width:40px;">{{ user.totalAboutEarly }}</td>
                            <td style="width:60px;">{{ formatNumber(user.timeAboutEarly) }}</td>
                            <td style="width:40px;">{{ user.totalGoOut }}</td>
                            <td style="width:60px;">{{ formatNumber(user.timeGoOut) }}</td>
                            <td style="width:70px;">{{ user.totalGoLateAboutEarly }}</td>
                            <td style="width:70px;">{{ formatNumber(user.timeGoEarly) }}</td>
                            <td style="width:70px;">{{ formatNumber(user.timeAboutLate) }}</td>
                            <!--<td style="width:70px;">{{ user.totalWorkingDays }}</td>-->
                            <td style="width:70px;">{{ user.totalTimeKeeping }}</td>
							<td style="width:70px;">{{ user.totalPaidLeave }}</td>
                            <td style="width:70px;">{{ user.totalPaidLeave12 }}</td>
                            <td style="width:70px;">{{ user.totalPayroll }}</td>
                            <td style="width:70px;">{{ user.totalPaidLeaveFull }}</td>
                            <td style="width:70px;">{{ user.totalOT}}</td>
                            <td style="width:66px;">{{ user.totalWar}}</td>
                            <td style="width:66px;">{{ user.totalUnpaidLeave }}</td>
                            <!--<td style="width:66px;">{{ user.totalNotCheckOut }}</td>-->
                            <td style="width:66px;">{{ formatNumber(user.totalHourEfforts) }}</td>

                            <td v-if="user.currentWar == 'Warrior 1'" style="width:90px;"><b style="color:green;">{{ user.currentWar }}</b></td>
                            <td v-else-if="user.currentWar == 'Warrior 2'" style="width:90px;"><b style="color:orange;">{{ user.currentWar }}</b></td>
                            <td v-else-if="user.currentWar == 'Warrior 3'" style="width:90px;"><b style="color:#800000;">{{ user.currentWar }}</b></td>
                            <td v-else style="width:90px;"><b style="color:gray;">{{ user.currentWar }}</b></td>

                            <td style="width:65px;" v-if="expected.end_date > current.end_date">{{
                                formatNumber(user.timeHoldWar) }}
                            </td>
                            <td style="width:65px;" v-if="expected.end_date > current.end_date">{{
                                formatNumber(user.avgTimeHoldWar)
                                }}
                            </td>
                            <td v-if=" expected.end_date > current.end_date & user.nextWar == 'Warrior 1'" style="width:90px;"><b style="color:green;">{{ user.nextWar }}</b></td>
                            <td v-else-if=" expected.end_date > current.end_date & user.nextWar == 'Warrior 2'" style="width:90px;"><b style="color:orange;">{{ user.nextWar }}</b></td>
                            <td v-else-if=" expected.end_date > current.end_date & user.nextWar == 'Warrior 3'" style="width:90px;"><b style="color:#800000;">{{ user.nextWar }}</b></td>
                            <td v-else-if=" expected.end_date > current.end_date & user.nextWar == 'Soldier'" style="width:90px;"><b style="color:gray;">{{ user.currentWar }}</b></td>

                            <!--<td style="width:90px;" v-if="expected.end_date > current.end_date">{{ user.nextWar }}</td>-->


                            <td style="width:65px;" v-if="expected.end_date > current.end_date">{{
                                formatNumber(user.timeIncreaseWar) }}
                            </td>
                            <td style="width:65px;" v-if="expected.end_date > current.end_date & user.avgTimeIncreaseWar < 5"><b style="color:green;">{{formatNumber(user.avgTimeIncreaseWar)}}</b></td>
							<td style="width:65px;" v-else-if="expected.end_date > current.end_date & user.avgTimeIncreaseWar > 5"">{{formatNumber(user.avgTimeIncreaseWar)}}</td>
                            <td style="width:85px; font-size:16px" v-if="user.rateGoLate >= 12.5" ><b style="color:red;">{{ formatNumber(user.rateGoLate)}} %</b></td>
                            <td style="width:85px;" v-else> {{ formatNumber(user.rateGoLate)}} %</td>
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
		changeOption(){
            this.getReport();
        },
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
    border: 0px solid #9b9b9b;
    color: #000;
}

table.table-striped tbody tr:nth-of-type(odd) {
    background: #f9f9f9;
}


.text-left {
    text-align: left!important;
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


table.detail {
    table-layout: fixed;
}

th,
td.detail {
    padding: 0px 0px;
    border: 1px solid #000;
}

thead.detail {
    background: #f9f9f9;
    display: table;
}

tbody.detail {
    height: 850px;
    overflow: auto;
    display: block;
}
</style>
