<template>
    <div class="card">
        <div class="card-header" style="height:50px;">
            <h4>Bảng tính lương</h4>
            <div style="position: absolute; right: 10px; top: 8px">
                <date-picker v-model="timeSelected" type="month" placeholder="Vui lòng chọn tháng để tính lương" @click="getWage()"
                             style="position: absolute;right: 95px;top: -5px; width:300px; height:50px;">
                </date-picker>
                <button class="btn btn-primary" @click="getWage()" style="height:33px; font-size:14px; margin: -5px 0px 0px 0px">Tính lương</button>
            </div>
        </div>
        <div class="card-body table-responsive">
            <div>
                <table class="table-striped table-responsive table-hover result-point">
                    <thead class="point-table-head">
                        <tr style=" text-align:center;">
                            <th rowspan="2" width="400px" >Tính lương tháng</th>
                            <th rowspan="2" width="400px" >Công chuẩn</th>
                            <th colspan="3">Thời gian làm việc dưới 3 năm</th>
                            <th colspan="3">Thời gian làm việc trên 3 năm</th>
                        </tr>
                        <tr style="text-align: center">
                            <th width="10%">WARRIOR 1</th>
                            <th width="10%">WARRIOR 2</th>
                            <th width="10%">WARRIOR 3</th>
                            <th width="10%">WARRIOR 1</th>
                            <th width="10%">WARRIOR 2</th>
                            <th width="10%">WARRIOR 3</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style=" text-align:center;">{{ current.start_month ?current.start_month : " "}}</td>
                            <td style=" text-align:center;">{{ current.total }}</td>
                            <td style=" text-align:center;">{{ current.warrior1 }}</td>
                            <td style=" text-align:center;">{{ current.warrior2 }}</td>
                            <td style=" text-align:center;">{{ current.warrior3 }}</td>
                            <td style=" text-align:center;">{{ current.warrior1_3 }}</td>
                            <td style=" text-align:center;">{{ current.warrior1 }}</td>
                            <td style=" text-align:center;">{{ current.warrior2 }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div>
               <table class="table-striped table-responsive table-hover result-point">
                    <thead class="point-table-head">
                        <tr style="vertical-align: middle; font-size:14px; text-align:center; ">

                            <th style="vertical-align: middle; witdh: 210px" rowspan="2">
                                <input type="text" name="search" class="form-control mb-2 input-search" v-model="search"
                                    placeholder="Tìm kiếm" v-on:keyup.enter="getWage()">
                            </th>
                            <th rowspan="2" style="vertical-align: middle;width: 80px">Mã Nhân viên</th>
                            <th rowspan="2" style="vertical-align: middle; width: 100px">Số ngày chấm công</th>
                            <th rowspan="2" style="vertical-align: middle; width: 120px">Số ngày không chấm công</th>
                            <th rowspan="2" style="vertical-align: middle; ">Không Checkout</th>
                            <th rowspan="2" style="vertical-align: middle; ">Nghỉ không lương</th>
                            <th rowspan="2" style="vertical-align: middle; ">Nghỉ có lương</th>
                            <th rowspan="2" style="vertical-align: middle;width: 100px">Công thực tế</th>
                            <th rowspan="2" style="vertical-align: middle;">Warrior tháng</th>
                            <th rowspan="2" style="vertical-align: middle; width: 120px">Trợ cấp ăn Warrior</th>
                            <th rowspan="2" style="vertical-align: middle; width: 120px">BHXH</th>
                            <th rowspan="2" style="vertical-align: middle; width: 120px">Mức lương hiện tại</th>
                            <th rowspan="2" style="vertical-align: middle; width: 120px">Lương thời gian</th>
                            <th rowspan="2" style="vertical-align: middle; width: 120px">Lương thực nhận</th>
                            <th rowspan="2" style="vertical-align: middle; width: 120px">Lương nhận ngày 05</th>
                            <th rowspan="2" style="vertical-align: middle; width: 120px">Lương nhận ngày 20</th>

                        </tr>
                    </thead>
                    <tbody style="font-size:14px;">
                        <tr v-for="(user, index) in data" :key="index">
                            <td witdh= "210px">{{ user.fullname }}</td>
                            <td style="text-align:center;">{{ user.id }}</td>
                            <td style=" text-align:right;">{{ user.totalWorkingDays }}</td>
                            <td style=" text-align:right;">{{ user.totalNoWorkingDays }}</td>
                            <td style=" text-align:right;">{{ user.totalNotCheckOut }}</td>
                            <td style=" text-align:right;">{{ user.totalUnpaidLeave }}</td>
                            <td style=" text-align:right;">{{ user.totalPaidLeave }}</td>
                            <td style=" text-align:right;">{{ user.totalTimeKeeping }}</td>
                            <td style=" text-align:right;">{{ user.currentWar }}</td>
                            <td style=" text-align:right;">{{ user.AllowanceWarrior+ " VNĐ" }}</td>
                            <td style=" text-align:right;">{{ user.Social_Insurance+ " VNĐ" }}</td>
                            <td style=" text-align:right;">{{ user.wage_now+ " VNĐ"}}</td>
                            <td style=" text-align:right;">{{ user.wage_actual+ " VNĐ"}}</td>
                            <td style=" text-align:right;">{{ user.TotalWage+ " VNĐ"}}</td>
                            <td style=" text-align:right;">{{ user.TotalWage05+ " VNĐ"}}</td>
                            <td style=" text-align:right;">{{ user.TotalWage20+ " VNĐ"}}</td>
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
    name: "Wage",
    components: {
        DatePicker
    },
    data() {
        return {
            dateRange: '',
            data: [],
            expected: {},
            search: '',
            current: {},
            timeSelected: '',
            start_date: '',
            start_month: '',
            end_date: '',
            time:'',
        }
    },
    created() {
        this.getWage();
    },
    methods: {
        async getWage() {
            let params = {
                search: this.search ,
                start_date: this.timeSelected ? moment(this.timeSelected).startOf('month').format('YYYY-MM-DD') : moment().startOf('month').format('YYYY-MM-DD'),
                end_date: this.timeSelected ? moment(this.timeSelected).endOf('month').format('YYYY-MM-DD') : moment().endOf('month').format('YYYY-MM-DD'),
            }
            const res = await $get('/time-keeping/get-wage', {...params});
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

