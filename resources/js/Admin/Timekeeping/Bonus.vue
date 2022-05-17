<template>
    <div class="card">
        <div class="card-header" style="height:50px;">
            <h4>Bảng tính thưởng</h4>
            <div style="position: absolute; right: 10px; top: 8px">
                <date-picker v-model="dateRange" type="date" range placeholder="Vui lòng chọn thời gian tính thưởng"></date-picker>
                <button class="btn btn-primary" @click="getReport()" style="height:33px; font-size:14px; margin: -5px 0px 0px 0px">Tính thưởng</button>
                <button class="btn btn-success" @click="exportData()" style="height:33px; font-size:14px; margin: -5px 0px 0px 0px">Xuất file Excel</button>
            </div>
        </div>
        <div class="card-body table-responsive">
            <div>
                <table class="table-striped table-responsive table-hover result-point" style="font-size:14px;">
                        <tr>
                            <td colspan="2"><b>Tính thưởng dự án</b></td>
                            <td style="width:160px;">{{ expected.start_date ?"Từ ngày: "+ expected.start_date : " "}}{{ expected.end_date ?" đến ngày: "+ expected.end_date : " "}}</td>
                        </tr>
                        <tr>
                            <td colspan="2"><b>Công chuẩn</b></td>
                            <td style=" text-align:center;">{{ expected.total }}</td>
                        </tr>
                        <tr>
                             <td rowspan="3" style="width:120px"><b>Thời gian làm việc trên 3 năm</b></td>
                            <td><b>WARRIOR 1</b></td>
                            <td style=" text-align:center;">{{ expected.warrior1_3 }}</td>
                        </tr>
                        <tr>
                            <td><b>WARRIOR 2</b></td>
                            <td style=" text-align:center;">{{ expected.warrior1 }}</td>
                        </tr>
                        <tr>
                            <td><b>WARRIOR 3</b></td>
                            <td style=" text-align:center;">{{ expected.warrior2 }}</td>
                        </tr>
                        <tr>
                            <td rowspan="3" style="width:120px"><b>Thời gian làm việc dưới 3 năm</b></td>
                            <td><b>WARRIOR 1</b></td>
                            <td style=" text-align:center;">{{ expected.warrior1 }}</td>
                            
                        </tr>
                        <tr>
                            <td><b>WARRIOR 2</b></td>
                            <td style=" text-align:center;">{{ expected.warrior2 }}</td>
                        </tr>
                        <tr>
                            <td><b>WARRIOR 3</b></td>
                            <td style=" text-align:center;">{{ expected.warrior3 }}</td>
                        </tr>
                </table>
            </div>
            <div>
               <table class="table-striped table-responsive table-hover result-point">
                    <thead class="point-table-head">
                        <tr style="vertical-align: middle; font-size:14px; text-align:center; ">
                        
                            <th style="vertical-align: middle;" rowspan="2">
                                <input type="text" name="search" class="form-control mb-2 input-search" v-model="search" 
                                    placeholder="Tìm kiếm" v-on:keyup.enter="getReport()">
                            </th>
                            <th rowspan="2" style="vertical-align: middle;">Mã Nhân viên</th>
                            <th style="vertical-align: middle;" rowspan="2">Ngày làm việc chính thức</th>
                            <th style="vertical-align: middle;" rowspan="2">Số ngày đã làm việc chính thức</th>
                            <th rowspan="2" style="vertical-align: middle;">Nghỉ không lương</th>
                            <th rowspan="2" style="vertical-align: middle;">Số ngày chấm công</th>
                            <th rowspan="2" style="vertical-align: middle;">Công thực tế</th>
                            <th rowspan="2" style="vertical-align: middle;">Số ngày không checkin</th>
                            <th rowspan="2" style="vertical-align: middle;">Số ngày không checkout</th>
                            <th rowspan="2" style="vertical-align: middle;">Warrior tháng</th>
                            <th rowspan="2" style="vertical-align: middle;">Trợ cấp ăn Warrior</th>
                            <th rowspan="2" style="vertical-align: middle;">Mức lương hiện tại</th>
                            <th rowspan="2" style="vertical-align: middle;">Lương thời gian</th>
                            <th rowspan="2" style="vertical-align: middle;">Tổng lương tháng</th>
                            <th rowspan="2" style="vertical-align: middle;">Nhận ngày 05</th>
                            <th rowspan="2" style="vertical-align: middle;">Nhận ngày 20</th>
                        </tr>
                    </thead>
                    <tbody style="font-size:14px;">
                        <tr v-for="(user, index) in data" :key="index">
                            <td>{{ user.fullname }}</td>
                            <td style="text-align:center;">{{ user.id }}</td>
                            <td style=" text-align:center;">{{ user.date_official }}</td>
                            <td style=" text-align:center;">{{ user.totalWorkDateY ? user.totalWorkDateY+" năm":" "}} {{ user.totalWorkDateM ? user.totalWorkDateM+" tháng" :" "}}</td>
                            <td>{{ user.totalUnpaidLeave }}</td>
                            <td>{{ user.totalWorkingDays }}</td>
                            <td>{{ user.totalTimeKeeping }}</td>
                            <td>{{ user.totalNotCheckIn }}</td>
                            <td>{{ user.currentWar }}</td>
                            <td></td>
                            <td></td>
                            <td>{{ user.wage_now}}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
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

