<template>
    <div class="card">
        <div class="card-header" style="height:50px;">
            <h4>Bảng thống kê chấm công</h4>
            <div class="col-lg-6" style="margin-top:-32px; padding-left:300px;">
                <div class="form-check" >
                    <input class="form-check-input" type="checkbox" v-model="option1" name="flexRadioDefault"
                        id="flexRadioDefault2">
                        <label v-if="option1 == false">Bảng Warrior</label>
                        <label v-if="option1 == true">Bảng giờ công</label>
                </div>
            </div>
            <div style="position: absolute; right: 10px; top: 8px">
                <select class="form-select" v-model="option"  @change="getReport()">
                    <option value="2">Theo tháng</option>
                    <option value="1">Theo tuần</option>
                    <option value="3">Tuỳ chỉnh</option>
                </select>
                <date-picker v-if="option == 1" v-model="dateRange" type="week" :disabled-date="disabledAfterToday" placeholder="Vui lòng chọn tuần để thống kê"
                   @change="getReport()" style="width:300px;position: absolute; right: 140px; top: 3px">
                </date-picker>
                <date-picker v-if="option == 2" v-model="dateRange" type="month" placeholder="Vui lòng chọn tháng để thống kê"
                    @change="getReport()" style="width:300px;position: absolute; right: 140px; top: 3px">
                </date-picker>
                <date-picker v-if="option == 3" v-model="dateRange" type="date" range placeholder="Vui lòng chọn khoảng thời gian thống kê"
                 @change="getReport()" style="width:300px;position: absolute; right: 140px; top:3px">
                </date-picker>
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
                <table class="table table-responsive">
                    <thead class="point-table-head">
                        <tr style="vertical-align: middle; font-size:12px; text-align:center; ">

                            <th style="vertical-align: middle; border:0px;" rowspan="2">
                                <input style="border:0px;" type="text" name="search" class="form-control mb-2 input-search" v-model="search"
                                    placeholder="Tìm kiếm" @input="changeOption()">
                            </th>
                            <th style="vertical-align: middle;" rowspan="2" @click="sort('date_official')">
                                Ngày làm việc chính thức</th>
                            <th style="vertical-align: middle; width:160px;" rowspan="2">Số ngày đã làm việc chính thức
                            </th>
                            <th colspan="2" style="vertical-align: middle;">Đi muộn</th>
                            <th colspan="2" style="vertical-align: middle;">Về sớm</th>
                            <th colspan="2" style="vertical-align: middle;">Ra ngoài</th>
                            <th rowspan="2" style="vertical-align: middle;">Tổng giờ ĐMVS</th>
                            <th rowspan="2" style="vertical-align: middle;">ĐMVS/công</th>
                            
                            <template v-if="option1 == true">
                            <th rowspan="2" style="vertical-align: middle;">Công thực tế</th>
                            <th rowspan="2" style="vertical-align: middle;">Công đăng ký làm thêm</th>
							<th rowspan="2" style="vertical-align: middle;">Nghỉ phép có lương</th>
                            <th rowspan="2" style="vertical-align: middle;">Nghỉ phép không lương</th>
                            <th rowspan="2" style="vertical-align: middle;">Công tính lương</th>
                            <th rowspan="2" style="vertical-align: middle;">Ngày phép có lương còn lại</th>
                            </template>
                            <template v-if="option1 == false">
                                <th rowspan="2" style="vertical-align: middle;">Số giờ đi sớm</th>
                                <th rowspan="2" style="vertical-align: middle;">Số giờ về muộn</th>
                                <th rowspan="2" style="vertical-align: middle;">Công đăng ký nỗ lực</th>
                                <th rowspan="2" style="vertical-align: middle;">Số giờ ĐKNL</th>
                            <th rowspan="2" style="vertical-align: middle;">Tổng giờ nỗ lực</th>
                            <th rowspan="2" style="vertical-align: middle;">Warrior hiện tại</th>
                            <th rowspan="2" style="vertical-align: middle;"
                                v-if="expected.end_date > current.end_date">TG để giữ Warrior</th>
                            <th rowspan="2" style="vertical-align: middle;"
                                v-if="expected.end_date > current.end_date">TGTB để giữ Warrior</th>
                            <th rowspan="2" style="vertical-align: middle;"
                                v-if="expected.end_date > current.end_date">Warrior tiếp theo</th>
                            <th rowspan="2" style="vertical-align: middle;"
                                v-if="expected.end_date > current.end_date"> TG để lên Warrior</th>
                            <th rowspan="2" style="vertical-align: middle;"
                                v-if="expected.end_date > current.end_date">TGTB để lên Warrior</th>
                            </template>
                            <th rowspan="2" style="vertical-align: middle;">Tỷ lệ đi muộn</th>
                        </tr>
                        <tr style="font-size:12px; text-align:center;">
                            <th style="vertical-align: middle;">Số lần</th>
                            <th style="vertical-align: middle;">Thời gian</th>
                            <th style="vertical-align: middle;">Số lần</th>
                            <th style="vertical-align: middle;">Thời gian</th>
                            <th style="vertical-align: middle;">Số lần</th>
                            <th style="vertical-align: middle;">Thời gian</th>
                        </tr>
                    </thead>
                    <tbody class="detail" style="font-size:14px;">
                        <tr v-for="(user, index) in data" :key="index">
                            <td style="font-size:16px" v-if="user.rateGoLate >= 12.5" ><b style="color:red;">{{ user.fullname }}</b></td>
                            <td v-else>{{ user.fullname }}</td>
                            <td style=" text-align:center;" v-if="user.date_official_new != 0">{{user.date_official_new}}</td>
                            <td style=" text-align:center; " v-else><b>Thử việc</b></td>
                            <td style=" text-align:right; width:150px;" v-if="user.date_official_new != 0">
                                {{ user.totalWorkDateY ? user.totalWorkDateY+" năm":" "}}
                                {{ user.totalWorkDateM ? user.totalWorkDateM+" tháng" :" "}}
                                {{ user.totalWorkDateD ? user.totalWorkDateD+" ngày" : " "}}
                            </td>
                            <td v-else style=" text-align:right; width:150px;"> Thử việc</td>
                            <td >{{ user.totalGoLate }}</td>
                            <td >{{ formatNumber(user.timeGoLate) }}</td>
                            <td >{{ user.totalAboutEarly }}</td>
                            <td >{{ formatNumber(user.timeAboutEarly) }}</td>
                            <td >{{ user.totalPause }}</td>
                            <td >{{ formatNumber(user.timePause) }}</td>
                            <td >{{ user.totalGoLateAboutEarly }}</td>
                            <td>{{ formatNumber(user.totalGoLateAboutEarly/8) }}</td>
                            
                            <template v-if="option1 == true">
                                <td >{{ user.totalTimeKeeping }}</td>
                                <td>{{ user.totalOT}}</td>
                                <td >{{ user.totalPaidLeave }}</td>
                                <td>{{ user.totalUnpaidLeave }}</td>
                                <td >{{ user.totalPayroll }}</td>
                                <td >{{ user.totalPaidLeaveFull }}</td>
                            </template>
                            <template v-if="option1 == false">
                                <td>{{ formatNumber(user.timeGoEarly) }}</td>
                                <td>{{ formatNumber(user.timeAboutLate) }}</td>
                                <td>{{ user.totalWar}}</td>
                                <td>{{ formatNumber(user.timeWar) }}</td>
                                <td >{{ formatNumber(user.totalHourEfforts) }}</td>
                                <td v-if="user.currentWar == 'Warrior 1'"><b style="color:green;">{{ user.currentWar }}</b></td>
                                <td v-else-if="user.currentWar == 'Warrior 2'"><b style="color:orange;">{{ user.currentWar }}</b></td>
                                <td v-else-if="user.currentWar == 'Warrior 3'"><b style="color:#800000;">{{ user.currentWar }}</b></td>
                                <td v-else ><b style="color:gray;">{{ user.currentWar }}</b></td>
                                <td style="" v-if="expected.end_date > current.end_date">{{formatNumber(user.timeHoldWar) }}</td>
                                <td style="" v-if="expected.end_date > current.end_date">{{formatNumber(user.avgTimeHoldWar)}}</td>
                                <td v-if=" expected.end_date > current.end_date & user.nextWar == 'Warrior 1'" ><b style="color:green;">{{ user.nextWar }}</b></td>
                                <td v-else-if=" expected.end_date > current.end_date & user.nextWar == 'Warrior 2'" ><b style="color:orange;">{{ user.nextWar }}</b></td>
                                <td v-else-if=" expected.end_date > current.end_date & user.nextWar == 'Warrior 3'" ><b style="color:#800000;">{{ user.nextWar }}</b></td>
                                <td v-else-if=" expected.end_date > current.end_date & user.nextWar == 'Soldier'" ><b style="color:gray;">{{ user.currentWar }}</b></td>
                                <td style="" v-if="expected.end_date > current.end_date">{{ formatNumber(user.timeIncreaseWar) }}</td>
                                <td style="" v-if="expected.end_date > current.end_date & user.avgTimeIncreaseWar < 5"><b style="color:green;">{{formatNumber(user.avgTimeIncreaseWar)}}</b></td>
                                <td style="" v-else-if="expected.end_date > current.end_date & user.avgTimeIncreaseWar > 5">{{formatNumber(user.avgTimeIncreaseWar)}}</td>
                            </template>

                            <td style=" font-size:16px" v-if="user.rateGoLate >= 12.5" ><b style="color:red;">{{ formatNumber(user.rateGoLate)}} %</b></td>
                            <td v-else> {{ formatNumber(user.rateGoLate)}} %</td>

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
            option: 2,
            option1: true,
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

        disabledAfterToday(date) {
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            return  date > new Date(today.getTime() - 1 * 24 * 3600 * 1000);
        },

		changeOption(){
            this.getReport();
        },
        async getReport() {
            let params = {
                search: this.search ,
                //time: this.dateRange ? moment(this.dateRange).format('YYYY-MM-DD') : '',
                //start_date: this.dateRange.length > 1 ? moment(this.dateRange[0]).format('YYYY-MM-DD') : moment().startOf('month').format('YYYY-MM-DD'),
                //end_date: this.dateRange.length > 1 ? moment(this.dateRange[1]).format('YYYY-MM-DD') : moment().endOf('month').format('YYYY-MM-DD'),
            };
            if (this.option == 1) {
                params.start_date = this.dateRange ? moment(this.dateRange).startOf('week').format('YYYY-MM-DD') : moment().startOf('week').format('YYYY-MM-DD');
                params.end_date = this.dateRange ? moment(this.dateRange).endOf('week').format('YYYY-MM-DD') : moment().endOf('week').format('YYYY-MM-DD');;
            }
            if (this.option == 2) {
                params.start_date = this.dateRange ? moment(this.dateRange).startOf('month').format('YYYY-MM-DD') : moment().startOf('month').format('YYYY-MM-DD');
                params.end_date = this.dateRange ? moment(this.dateRange).endOf('month').format('YYYY-MM-DD') : moment().endOf('month').format('YYYY-MM-DD');
            }
            if (this.option == 3) {
                params.start_date = this.dateRange.length > 1 ? moment(this.dateRange[0]).format('YYYY-MM-DD') : moment().startOf('month').format('YYYY-MM-DD');
                params.end_date = this.dateRange.length > 1 ? moment(this.dateRange[1]).format('YYYY-MM-DD') : moment().endOf('month').format('YYYY-MM-DD');
            }

            const res = await $get('/time-keeping/get-report', params);
            if (res.code == 200) {
                this.data = res.data.result;
                this.expected = res.data.expected;
                this.current = res.data.current;
            }
            else {
                toastr.error('Thời gian chọn để thống kê không hợp lệ, cần chọn ngày bắt đầu nhỏ hơn hoặc bằng ngày hôm nay!');
                return false;
            }

        },
        formatNumber(val) {
            return (Math.round(val * 100) / 100).toFixed(2);
        },
        //async exportData() {

        //        let option = this.option;
        //        let search = this.search;
        //        let time = this.timeSelected? moment(this.timeSelected).format('YYYY-MM-DD'): '';
            // const res = await $get('/time-keeping/export', {...params});

        //    window.open("/time-keeping-report/export?option="+option+"&search="+search+"&time="+time,'_blank');
        //}
    }
}
</script>

<style scoped>
table {
    border-collapse: collapse;
    width: 100%;
}

th,
td {
    padding: 8px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

tr:hover {
    background-color: coral;
}

table {
    background: #fff;
    border: 1px solid #999999;
}

table thead tr th {
    padding: 5px;
    border: 1px solid #9b9b9b;
    text-align: center;
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

/*tbody.detail {
    height: 850px;
    overflow: auto;
    display: block;
}*/
</style>
