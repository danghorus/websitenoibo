<template>
    <div class="card">
        <div class="card-header">
            <h4>Bảng thống kê chấm công</h4>
            <div style="position: absolute; right: 20px; top: 15px">
                <date-picker v-model="dateRange" type="date" range placeholder="Vui lòng chọn khoảng thời gian thống kê"></date-picker>
                <button class="btn btn-primary" @click="getReport()">Thống kê</button>
            </div>
        </div>
        <div class="card-body table-responsive">
            <div style="margin: -50px 0px 0px 0px">
                <table class="table table-bordered mt-5">
                    <thead class="table-active">
                        <tr style=" text-align:center;">
                            <th>Thời gian thống kê</th>
                            <th>Công chuẩn</th>
                            <th>WARRIOR 1</th>
                            <th>WARRIOR 2</th>
                            <th>WARRIOR 3</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Dự kiến: {{ expected.start_date }} / {{ expected.end_date }}</td>
                            <td>{{ expected.total }}</td>
                            <td>{{ expected.warrior1 }}</td>
                            <td>{{ expected.warrior2 }}</td>
                            <td>{{ expected.warrior3 }}</td>
                        </tr>
                        <tr>
                            <td>Thực tế: {{ current.start_date }} / {{ current.end_date }}</td>
                            <td>{{ current.total }}</td>
                            <td>{{ current.warrior1 }}</td>
                            <td>{{ current.warrior2 }}</td>
                            <td>{{ current.warrior3 }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div style="margin: -40px 0px 0px 0px">
                <table class="table table-bordered mt-5" style="border:2px;" >
                    <thead class="table-active">
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
                            <th>Số lần</th>
                            <th>Thời gian</th>
                            <th>Số lần</th>
                            <th>Thời gian</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(user, index) in data" :key="index">
                            <td>{{ user.id }}</td>
                            <td>{{ user.fullname }}</td>
                            <td>{{ user.totalGoLate }}</td>
                            <td>{{ formatNumber(user.timeGoLate) }}</td>
                            <td>{{ user.totalAboutEarly }}</td>
                            <td>{{ formatNumber(user.timeAboutEarly) }}</td>
                            <td>{{ user.totalGoLateAboutEarly }}</td>
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
                            <td>{{ user.rateGoLate }} %</td>
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

</style>
