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
            <div>
                <table class="table table-bordered mt-5">
                    <thead class="table-active">
                        <tr>
                            <th>Thời gian thống kê</th>
                            <th>Công chuẩn</th>
                            <th>Warrior 1</th>
                            <th>Warrior 2</th>
                            <th>Warrior 3</th>
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
            <table class="table table-bordered mt-5">
                <thead class="table-active">
                    <tr>
                        <th>Mã NV</th>
                        <th>Tên nhân viên</th>
                        <th>Số lần đi muộn</th>
                        <th>Thời gian đi muộn</th>
                        <th>Số lần về sớm</th>
                        <th>Thời gian về sớm</th>
                        <th>Tổng giờ đi muộn về sớm</th>
                        <th>Số giờ về muộn</th>
                        <th>Số giờ đi sớm</th>
                        <th>Nghỉ không lương</th>
                        <th>Số ngày chấm công</th>
                        <th>Tổng giờ nỗ lực</th>
                        <th>Cấp độ Warrior đạt được</th>
                        <th>Số giờ cần giữ Warrior</th>
                        <th>TB thời gian cần giữ Warrior</th>
                        <th>Cấp độ Warrior tiếp theo</th>
                        <th>Số giờ còn thiếu để lên Warrior</th>
                        <th>TB thời gian nỗ lực để lên giữ Warrior</th>
                        <th>Tỷ lệ đi muộn</th>
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
                        <td>{{ formatNumber(user.timeAboutLate) }}</td>
                        <td>{{ formatNumber(user.timeGoEarly) }}</td>
                        <td>{{ user.totalUnpaidLeave }}</td>
                        <td>{{ user.totalTimeKeeping }}</td>
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
    methods: {
        async getReport() {
            if (this.dateRange.length) {
                let params = {
                    start_date: moment(this.dateRange[0]).format('YYYY-MM-DD'),
                    end_date: moment(this.dateRange[1]).format('YYYY-MM-DD')
                }
                const res = await $get('/time-keeping/get-report', {...params});
                if (res.code == 200) {
                    this.data = res.data.result;
                    this.expected = res.data.expected;
                    this.current = res.data.current;
                }
            } else {
                toastr.error('Vui lòng chọn thời gian thống kê');
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
