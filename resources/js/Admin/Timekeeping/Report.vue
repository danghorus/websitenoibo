<template>
    <div class="card">
        <div class="card-header" style="height:50px;">
            <h4>Bảng thống kê chấm công</h4>
            <div style="position: absolute; right: 10px; top: 8px">
                <date-picker v-model="dateRange" type="date" range placeholder="Vui lòng chọn khoảng thời gian thống kê"></date-picker>
                <button class="btn btn-primary" @click="getReport()" style="height:33px; font-size:14px; margin: -5px 0px 0px 0px">Thống kê</button>
            </div>
        </div>
        <div class="card-body table-responsive">
            <div>
                <table class="table-striped table-responsive table-hover result-point">
                    <thead class="point-table-head">
                        <tr style=" text-align:center;">
                            <th width="600px">Thời gian thống kê</th>
                            <th width="400px">Công chuẩn</th>
                            <th width="16%">WARRIOR 1</th>
                            <th width="16%">WARRIOR 2</th>
                            <th width="16%">WARRIOR 3</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><b>Dự kiến:</b>: Từ ngày {{ expected.start_date ? expected.start_date : ".........."}} đến ngày {{ expected.end_date ? expected.end_date : ".........."}}</td>
                            <td>{{ expected.total }}</td>
                            <td>{{ expected.warrior1 }}</td>
                            <td>{{ expected.warrior2 }}</td>
                            <td>{{ expected.warrior3 }}</td>
                        </tr>
                        <tr>
                            <td><b>Hiện tại:</b> Từ ngày {{ current.start_date ? current.start_date : ".........." }} đến ngày {{ current.end_date ? current.end_date: ".........."}}</td>
                            <td>{{ current.total }}</td>
                            <td>{{ current.warrior1 }}</td>
                            <td>{{ current.warrior2 }}</td>
                            <td>{{ current.warrior3 }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div>
               <table class="table-striped table-responsive table-hover result-point">
                    <thead class="point-table-head">
                        <tr style="vertical-align: middle; font-size:12px; text-align:center; ">
                            <th rowspan="2" style="vertical-align: middle;">MNV</th>
                            <th style="vertical-align: middle; width:200px;" rowspan="2">Nhân viên</th>
                            <th colspan="2" style="vertical-align: middle;">Đi muộn</th>
                            <th colspan="2" style="vertical-align: middle;">Về sớm</th>
                            <th rowspan="2" style="vertical-align: middle;">Tổng giờ ĐMVS</th>
                            <th rowspan="2" style="vertical-align: middle;">Số giờ đi sớm</th>
                            <th rowspan="2" style="vertical-align: middle;">Số giờ về muộn</th>
                            <th rowspan="2" style="vertical-align: middle;">Nghỉ không lương</th>
                            <th rowspan="2" style="vertical-align: middle;">Số ngày chấm công</th>
                            <th rowspan="2" style="vertical-align: middle;">Số ngày không checkin</th>
                            <th rowspan="2" style="vertical-align: middle;">Số ngày không checkout</th>
                            <th rowspan="2" style="vertical-align: middle;">Tổng giờ nỗ lực</th>
                            <th rowspan="2" style="vertical-align: middle; width:120px;">Warrior hiện tại</th>
                            <th rowspan="2" style="vertical-align: middle;">TG để giữ Warrior</th>
                            <th rowspan="2" style="vertical-align: middle;">TGTB để giữ Warrior</th>
                            <th rowspan="2" style="vertical-align: middle; width:120px;">Warrior tiếp theo</th>
                            <th rowspan="2" style="vertical-align: middle;">TG để lên Warrior</th>
                            <th rowspan="2" style="vertical-align: middle;">TGTB để lên Warrior</th>
                            <th rowspan="2" style="vertical-align: middle;">Tỷ lệ đi muộn</th>
                        </tr>
                        <tr style="font-size:12px; text-align:center;">
                            <th style="vertical-align: middle;">Số lần</th>
                            <th style="vertical-align: middle;">Thời gian</th>
                            <th style="vertical-align: middle;">Số lần</th>
                            <th style="vertical-align: middle;">Thời gian</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(user, index) in data" :key="index">
                            <td style="text-align:center;">{{ user.id }}</td>
                            <td>{{ user.fullname }}</td>
                            <td>{{ user.totalGoLate }}</td>
                            <td>{{ formatNumber(user.timeGoLate) }}</td>
                            <td>{{ user.totalAboutEarly }}</td>
                            <td>{{ formatNumber(user.timeAboutEarly) }}</td>
                            <td>{{ formatNumber(user.totalGoLateAboutEarly) }}</td>
                            <td>{{ formatNumber(user.timeGoEarly) }}</td>
                            <td>{{ formatNumber(user.timeAboutLate) }}</td>
                            <td>{{ user.totalUnpaidLeave }}</td>
                            <td>{{ user.totalTimeKeeping }}</td>
                            <td>{{ user.totalNotCheckIn }}</td>
                            <td>{{ user.totalNotCheckOut }}</td>
                            <td>{{ formatNumber(user.totalHourEfforts) }}</td>
                            <td>{{ user.currentWar }}</td>
                            <td>{{ formatNumber(user.timeHoldWar) }}</td>
                            <td>{{ formatNumber(user.avgTimeHoldWar) }}</td>
                            <td>{{ user.nextWar }}</td>
                            <td>{{ formatNumber(user.timeIncreaseWar) }}</td>
                            <td>{{ formatNumber(user.avgTimeIncreaseWar) }}</td>
                            <td>{{ formatNumber(user.rateGoLate) }} %</td>
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
        }
    },
    created() {
        this.getReport();
    },
    methods: {
        async getReport() {
            let params = {
                start_date: this.dateRange.length > 1? moment(this.dateRange[0]).format('YYYY-MM-DD'): '',
                end_date: this.dateRange.length > 1? moment(this.dateRange[1]).format('YYYY-MM-DD'): ''
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
