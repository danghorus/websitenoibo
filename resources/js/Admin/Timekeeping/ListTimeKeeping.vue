<template>
    <div class="card">
        <div class="card-header" style="height:50px;">
            <h4>Bảng chấm công</h4>
            <div style="position: absolute; right: 20px; top: 5px">
                <button v-if="showCheckIn && currentUser.check_type == 2" class="btn btn-success"
                    @click="checkIn()">Checkin</button>
                <button v-if="showCheckOut && currentUser.check_type == 2" class="btn btn-danger"
                    @click="checkIn()">Checkout</button>
                <button v-if=" currentUser.permission == 1" class="btn btn-primary" @click="exportData()">Xuất file
                    excel</button>
                <button v-if=" currentUser.permission == 1" class="btn btn-primary" @click="showModalConfig()">Cấu
                    hình</button>
            </div>

        </div>
        <div class="card-body table-responsive">
            <select class="form-select col-lg-2"
                style="position: absolute; right: 20px; top: 65px; width:180px; height:34px;" v-model="option">
                <option value="1">Theo tuần</option>
                <option value="2">Theo tháng</option>
            </select>
            <date-picker v-if="option == 2" v-model="timeSelected" type="month"
                placeholder="Vui lòng chọn tháng để tìm kiếm" @change="changeOption()"
                style="position: absolute;right: 201px;top: 65px; width:300px; height:50px;">
            </date-picker>
            <date-picker v-if="option == 1" v-model="timeSelected" type="week"
                placeholder="Vui lòng chọn tuần để tìm kiếm" @change="changeOption()"
                style="position: absolute;right: 201px;top: 65px; width:300px; height:50px;">
            </date-picker>
            <table v-if="option == 1" class="table table-bordered mt-5">
                <thead class="table-active">
                    <tr>
                        <th style="width:200px">
                            <input type="text" name="search" class="form-control mb-2 input-search" v-model="search"
                                placeholder="Tìm kiếm" v-on:keyup.enter="getTimeKeepings()">
                        </th>
                        <th v-for="(label, index) in labels" :key="index"
                            style="font-size:13px; text-align:center; vertical-align: middle; width:180px">
                            <button type="button" data-toggle="tooltip" data-placement="bottom"
                                title="Click để chọn ngày nghỉ của công ty( Du lịch, nghỉ lễ,...)"
                                style="background-color:#d4d4d4; border: 0px;"><b>{{ label }}</b>
                            </button>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(user, index) in data" :key="index">
                        <td style="width:200px; font-size:14px; vertical-align: middle; height: 88px">{{ user.fullname
                        }}</td>
                        <td v-for="(time, index) in user.time_keeping" :key="index" :class="time.class"
                            @click="showModal(user.id, user.fullname, time)"
                            style="text-align:center;vertical-align: middle">
                            <template v-if="time.petition_type == 0 && time.label_day != 'sunday'" style="background-color: red;">
                                <b>Ca hành chính</b>
                                <div>{{ time.checkin ? time.checkin: '-:-'}} - {{ time.checkout ?
                                time.checkout:'-:-' }}</div>
                                <div v-if="time.go_early_0 > 0 && time.checkin == ''">(Đi sớm: {{ time.go_early }} - )
                                </div>
                                <div v-if="time.go_early_0 > 0 && time.about_late_0 > 0">(Đi sớm: {{ time.go_early }} -
                                    Về
                                    muộn: {{ time.about_late }})</div>
                                <div v-if="time.go_early_0 > 0 && time.about_early_0 > 0">(Đi sớm: {{ time.go_early }} -
                                    <b style="color:red;">Về sớm: {{ time.about_early }})</b>
                                </div>
                                <div v-if="time.go_late_0  > 0 && time.about_late_0 > 0">(<b style="color:red;">Đi muộn:
                                        {{ time.go_late }}</b> - Về muộn: {{ time.about_late }})</div>
                                <div v-if="time.go_late_0  > 0 && time.about_early_0 > 0">(<b style="color:red;">Đi
                                        muộn: {{ time.go_late }}</b> - <b style="color:red;">Về sớm: {{ time.about_early
                                        }}</b>)</div>
                            </template>
                            <template v-else-if="time.petition_type == 4">
                                <b>Thay đổi giờ công</b>
                                <div>{{ time.checkin ? time.checkin: '-:-'}} - {{ time.checkout ?
                                time.checkout:'-:-' }}</div>
                                <div v-if="time.go_early > 0 && time.about_late > 0">(Đi sớm: {{ time.go_early }} - Về
                                    muộn: {{ time.about_late }})</div>
                                <div v-if="time.go_early > 0 && time.about_early > 0">(Đi sớm: {{ time.go_early }} - Về
                                    sớm: {{ time.about_early }})</div>
                                <div v-if="time.go_late  > 0 && time.about_late > 0">(Đi muộn: {{ time.go_late }} - Về
                                    muộn: {{ time.about_late }})</div>
                                <div v-if="time.go_late  > 0 && time.about_early > 0">(Đi muộn: {{ time.go_late }} - Về
                                    sớm: {{ time.about_early }})</div>
                            </template>
                            <template v-else-if="time.petition_type == 5">
                                <b>Đăng ký làm tính công</b>
                                <div>{{ time.checkin ? time.checkin: '-:-'}} - {{ time.checkout ?
                                time.checkout:'-:-' }}</div>
                                <div v-if="time.go_early > 0 && time.about_late > 0">(Đi sớm: {{ time.go_early }} - Về
                                    muộn: {{ time.about_late }})</div>
                                <div v-if="time.go_early > 0 && time.about_early > 0">(Đi sớm: {{ time.go_early }} - Về
                                    sớm: {{ time.about_early }})</div>
                                <div v-if="time.go_late  > 0 && time.about_late > 0">(Đi muộn: {{ time.go_late }} - Về
                                    muộn: {{ time.about_late }})</div>
                                <div v-if="time.go_late  > 0 && time.about_early > 0">(Đi muộn: {{ time.go_late }} - Về
                                    sớm: {{ time.about_early }})</div>
                            </template>
                            <template v-else-if="time.petition_type == 6">
                                <b>Đăng ký làm nỗ lực</b>
                                <div>{{ time.checkin ? time.checkin: '-:-'}} - {{ time.checkout ?
                                time.checkout:'-:-' }}</div>
                                <div v-if="time.check_in >0 && time.checkout >0">(Tổng giờ nỗ lực {{ time.go_early }})</div>
                            </template>
                            <template v-else-if="time.petition_type == 7">
                                <b>Lịch nghỉ công ty</b>
                                <div>{{ time.checkin ? time.checkin: '-:-'}} - {{ time.checkout ?
                                time.checkout:'-:-' }}</div>
                            </template>
                            <template v-else-if="time.petition_type == 1">
                                <b>Đi muộn/về sớm</b>
                                <div>{{ time.checkin ? time.checkin: '-:-'}} - {{ time.checkout ?
                                time.checkout:'-:-' }}</div>
                                <div v-if="time.go_early > 0 && time.about_late > 0">(Đi sớm: {{ time.go_early }} - Về
                                    muộn: {{ time.about_late }})</div>
                                <div v-if="time.go_early > 0 && time.about_early > 0">(Đi sớm: {{ time.go_early }} - Về
                                    sớm: {{ time.about_early }})</div>
                                <div v-if="time.go_late  > 0 && time.about_late > 0">(Đi muộn: {{ time.go_late }} - Về
                                    muộn: {{ time.about_late }})</div>
                                <div v-if="time.go_late  > 0 && time.about_early > 0">(Đi muộn: {{ time.go_late }} - Về
                                    sớm: {{ time.about_early }})</div>
                            </template>
                            <template v-else-if="time.petition_type == 2 && time.type_leave == 1">
                                <b>Nghỉ buổi sáng</b>
                                <div v-if="time.checkin == ''"></div>
                                <div v-else>{{time.checkin}} - {{time.checkout }}</div>
                                <div v-if="time.go_early > 0 && time.about_late > 0">(Đi sớm: {{ time.go_early }} - Về
                                    muộn: {{ time.about_late }})</div>
                                <div v-if="time.go_early > 0 && time.about_early > 0">(Đi sớm: {{ time.go_early }} - Về
                                    sớm: {{ time.about_early }})</div>
                                <div v-if="time.go_late  > 0 && time.about_late > 0">(Đi muộn: {{ time.go_late }} - Về
                                    muộn: {{ time.about_late }})</div>
                                <div v-if="time.go_late  > 0 && time.about_early > 0">(Đi muộn: {{ time.go_late }} - Về
                                    sớm: {{ time.about_early }})</div>
                            </template>
                            <template v-else-if="time.petition_type == 2 && time.type_leave == 2">
                                <b>Nghỉ buổi chiều</b>
                                <div v-if="time.checkin == ''"></div>
                                <div v-else>{{time.checkin}} - {{time.checkout }}</div>
                                <div v-if="time.go_early > 0 && time.about_late > 0">(Đi sớm: {{ time.go_early }} - Về
                                    muộn: {{ time.about_late }})</div>
                                <div v-if="time.go_early > 0 && time.about_early > 0">(Đi sớm: {{ time.go_early }} - Về
                                    sớm: {{ time.about_early }})</div>
                                <div v-if="time.go_late  > 0 && time.about_late > 0">(Đi muộn: {{ time.go_late }} - Về
                                    muộn: {{ time.about_late }})</div>
                                <div v-if="time.go_late  > 0 && time.about_early > 0">(Đi muộn: {{ time.go_late }} - Về
                                    sớm: {{ time.about_early }})</div>
                            </template>
                            <template
                                v-else-if="time.petition_type == 2 && (time.type_leave == 3 || time.type_leave == 4)">
                                <b>Nghỉ cả ngày</b>
                                <div v-if="time.checkin == ''"></div>
                                <div v-else>{{time.checkin}} - {{time.checkout }}</div>
                                <div v-if="time.go_early > 0 && time.about_late > 0">(Đi sớm: {{ time.go_early }} - Về
                                    muộn: {{ time.about_late }})</div>
                                <div v-if="time.go_early > 0 && time.about_early > 0">(Đi sớm: {{ time.go_early }} - Về
                                    sớm: {{ time.about_early }})</div>
                                <div v-if="time.go_late  > 0 && time.about_late > 0">(Đi muộn: {{ time.go_late }} - Về
                                    muộn: {{ time.about_late }})</div>
                                <div v-if="time.go_late  > 0 && time.about_early > 0">(Đi muộn: {{ time.go_late }} - Về
                                    sớm: {{ time.about_early }})</div>
                            </template>
                            <template v-else>
                                <div>{{ time.checkin ? time.checkin: '-:-'}} - {{ time.checkout ?
                                time.checkout:'-:-' }}</div>
                                <div v-if="time.go_early > 0 && time.about_late > 0">(Đi sớm: {{ time.go_early }} - Về
                                    muộn: {{ time.about_late }})</div>
                                <div v-if="time.go_early > 0 && time.about_early > 0">(Đi sớm: {{ time.go_early }} - Về
                                    sớm: {{ time.about_early }})</div>
                                <div v-if="time.go_late  > 0 && time.about_late > 0">(Đi muộn: {{ time.go_late }} - Về
                                    muộn: {{ time.about_late }})</div>
                                <div v-if="time.go_late  > 0 && time.about_early > 0">(Đi muộn: {{ time.go_late }} - Về
                                    sớm: {{ time.about_early }})</div>
                            </template>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table v-else class="table table-bordered mt-5">
                <thead class="table-active">
                    <tr>
                        <th style="width:200px">
                            <input type="text" name="search" class="form-control mb-2 input-search" v-model="search"
                                placeholder="Tìm kiếm" v-on:keyup.enter="getTimeKeepings()">
                        </th>
                        <th v-for="(label, index) in labels" :key="index"
                            style="font-size:13px; text-align:center; vertical-align: middle;">{{ label
                            }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(user, index) in data" :key="index">
                        <td style="width:200px; font-size:14px; vertical-align: middle;">{{ user.fullname }}</td>
                        <td v-for="(time, index) in user.time_keeping" :key="index" :class="time.class"
                            @click="showModal(user.id, user.fullname, time)"
                            style="text-align:center;vertical-align: middle">
                            <div style=" font-size:13px; text-align: center;">
                                {{ time.checkin ? time.checkin: '--:--'}} <br>
                                {{ time.checkout ? time.checkout: '--:--' }}
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div>
                <p><i class="fa fa-circle" style="color: red"></i> Chưa checkin (checkout)</p>
                <p><i class="fa fa-circle" style="color: green"></i> Chấm công đầy đủ</p>
                <p><i class="fa fa-circle" style="color: yellow"></i> Chấm công muộn (Về sớm)</p>
            </div>
        </div>
        <div>
            <div ref="modalDetail" class="modal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document" style="  max-width: 80%;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Chi tiết chấm công: {{userName}} - Ngày {{ time.day }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                @click="closeModal()">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <ModalDetailTimeKeepingInDay v-if="showDetail" :userId="userId" :time="time" />
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div>
            <div ref="modal" class="modal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document" style=" max-width: 80%;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Cấu hình chấm công</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                @click="closeModalConfig()">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <ModalConfigTimeKeeping v-if="modalConfig" @closeModalConfig="closeModalConfig()" />
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import ModalDetailTimeKeepingInDay from "./ModalDetailTimeKeepingInDay";
import ModalConfigTimeKeeping from "./ModalConfigTimeKeeping";
import {$get, $post} from "../../ultis";
import moment from "moment";

export default {
    name: "ListTimeKeeping",
    components: {
        ModalDetailTimeKeepingInDay, ModalConfigTimeKeeping, DatePicker
    },
    data() {
        return {
            showDetail: false,
            modalConfig: false,
            showOtherTime: false,
            showCheckOut: false,
            showCheckIn: false,
            option: 1,
            labels: [],
            start_date: '',
            end_date: '',
            search: '',
            data: '',
            userId: '',
            userName: '',
            time: '',
            currentUser: '',
            timeSelected: '',
        }
    },
    created() {
        this.getTimeKeepings();
    },
    methods: {
        async getTimeKeepings() {
            let params = {
                option: this.option,
                search: this.search,
                time: this.timeSelected? moment(this.timeSelected).format('YYYY-MM-DD'): ''
            };
            const res = await $get('/time-keeping/get', {...params});
            if (res.code === 200) {
                this.labels = res.labels;
                this.data = res.data;
                this.currentUser = res.current_user;
                if (res.showBtn && res.showBtn == 'checkin') {
                    this.showCheckIn = true;
                } else if (res.showBtn && res.showBtn == 'checkout') {
                    this.showCheckOut = true;
                }
            }
        },
        changeOption() {
            this.getTimeKeepings();
        },
        showModal(userId, userName, time) {
            this.userId = userId;
            this.userName = userName;
            this.time = time;
            this.showDetail = true;
            $(this.$refs.modalDetail).modal('show');
        },
        closeModal() {
            $(this.$refs.modalDetail).modal('hide');
            this.showDetail = false;
        },
        showModalConfig() {
            this.modalConfig = true;
            $(this.$refs.modal).modal('show');
        },
        closeModalConfig() {
            $(this.$refs.modal).modal('hide');
            this.modalConfig = false;
        },
        async checkIn() {
            const res = await $post('/time-keeping/checkin');
            if (res.code == 200) {
                this.getTimeKeepings();
                if (res.checkin) {
                    this.showCheckOut = true;
                }else {
                    this.showCheckOut = false;
                }

                this.showCheckIn = false;
            }
        },
        async exportData() {

                let option = this.option;
                let search = this.search;
                let time = this.timeSelected? moment(this.timeSelected).format('YYYY-MM-DD'): '';
            // const res = await $get('/time-keeping/export', {...params});

            window.open("/time-keeping/export?option="+option+"&search="+search+"&time="+time,'_blank');
        }
    },
    watch: {
        'option': function (newVal) {
            this.timeSelected = '';
            this.changeOption();
        },
    }
}
</script>

<style scoped>
table table-bordered mt-5
{
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
