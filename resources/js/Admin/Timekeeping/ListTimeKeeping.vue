<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.17/vue.min.js"></script>
<template>
    <div class="card">
        <div class="card-header" style="height:50px;">
            <h4>Bảng chấm công</h4>
            <div style="position: absolute; right: 20px; top: 5px">
                <button v-if="showCheckIn && currentUser.check_type == 2" class="btn btn-success"
                    @click="checkIn()">Checkin</button>
                <button v-if="showCheckOut && currentUser.check_type == 2" class="btn btn-danger"
                    @click="checkIn()">Checkout</button>
                <button v-if=" currentUser.permission == 1" class="btn btn-primary" @click="showModalConfig()">Cấu
                    hình</button>
                <button v-if=" showFinalCheckout && showGoOut && currentUser.check_type == 1 " class="btn btn-danger" @click="GoOut()">Ra Ngoài</button>
                <button v-if=" showFinalCheckout && showGoIn && currentUser.check_type == 1 " class="btn btn-success" @click="GoOut()">Tiếp tục</button>
                <button v-if="showFinalCheckout && currentUser.check_type == 1 " class="btn btn-success"  @click="showModalFinalCheckout()" >Final Checkout</button>
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
                        <th style="width:250px">
                            <input type="text" name="search" class="form-control mb-2 input-search" v-model="search"
                                placeholder="Tìm kiếm" @input="changeOption()">
                        </th>
                        <th v-for="(label, index) in labels" :key="index"
                            style="font-size:13px; text-align:center; vertical-align: middle; width:12.255%">
                            <b>{{ label }}</b>
                        </th>
                        <th style="width:16px"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(user, index) in data" :key="index">
                        <td style="width:250px; font-size:14px; vertical-align: middle; height: 88px">{{ user.fullname
                        }}</td>
                        <td v-for="(time, index) in user.time_keeping" :key="index" :class="time.class"
                            @click="showModal(user.id, user.fullname, time)"
                            style="text-align:center;vertical-align: middle;width:12.375%">
                            <template>
                                <b v-if="time.petition_type == 0 && time.label_day != 'sunday' && time.holiday != 1">Ca hành chính</b>
                                <b v-else-if="time.petition_type == 4 && time.holiday != 1">Thay đổi giờ công</b>
                                <b v-else-if="time.petition_type == 5">Đăng ký làm tính công</b>
                                <b v-else-if="time.petition_type == 6">Đăng ký làm nỗ lực</b>
                                <b v-else-if="time.petition_type == 1 && time.holiday != 1">Đi muộn/về sớm</b>
                                <b v-else-if="time.petition_type == 9 && time.holiday != 1" style="color:black;">Ra ngoài ({{time.go_out}} phút)</b>
                                <b v-else-if="time.petition_type == 2 && time.type_leave == 1 && time.holiday != 1">Nghỉ buổi sáng</b>
                                <b v-else-if="time.petition_type == 2 && time.type_leave == 2 && time.holiday != 1">Nghỉ buổi chiều</b>
                                <b v-else-if="time.petition_type == 2 && time.type_leave == 3 && time.holiday != 1">Nghỉ một ngày</b>
                                <b v-else-if="time.petition_type == 2 && time.type_leave == 4 && time.holiday != 1">Nghỉ nhiều ngày</b>
                                <b v-else-if="time.holiday == 1 && time.petition_type != 6 && time.petition_type != 5">Nghỉ theo lịch</b>

                                <template v-if="time.petition_type == 6">

                                    <div>{{ time.checkin ? time.checkin: '-:-'}} - {{ time.checkout ?time.checkout:'-:-' }}</div>
                                    <div v-if="time.go_early_0 > 0 ">(Tổng giờ nỗ lực <b>{{ time.go_early }} </b>phút)</div>

                                </template>
                                <template v-else-if="time.petition_type == 2 && (time.type_leave == 3 || time.type_leave == 4 ) && time.holiday != 1">
                                
                                </template>

                                <template v-else>

                                    <div v-if="time.final_checkout != 0 && time.final_checkout != null">
                                        {{ time.checkin ? time.checkin: '-:-'}} - <b style="color:#ff8080 ;">{{ time.checkout ? time.checkout:'-:-' }}</b>
                                    </div>
                                    <div v-else>{{ time.checkin ? time.checkin: '-:-'}} - {{ time.checkout ? time.checkout:'-:-' }}</div>

                                    <div v-if="time.total_pause > 0">Số lần: {{ time.total_pause ? time.total_pause : 0 }} - Thời gian: {{ time.time_pause}}</div>

                                    <div v-if="time.go_early_0 > 0 && time.checkout =='-:-'">
                                        ( Đi sớm: {{ time.go_early }} - )
                                    </div>

                                    <div v-if="time.go_early_0 > 0 && time.about_late_0 > 0">
                                        (Đi sớm: {{ time.go_early }} - Về muộn: {{ time.about_late }})
                                    </div>

                                    <div v-if="time.go_early_0 > 0 && time.about_early_0 > 0">
                                        (Đi sớm: {{ time.go_early }} -<b style="color:red;">Về sớm: {{ time.about_early }})</b>
                                    </div>

                                    <div v-if="time.go_late_0  > 0 && time.checkout =='-:-'">
                                        (<b style="color:black;">Đi muộn: {{ time.go_late }}</b> -)
                                    </div>

                                    <div v-if="time.go_late_0  > 0 && time.about_late_0 > 0">
                                        (<b style="color:red;">Đi muộn:{{ time.go_late }}</b> - Về muộn: {{ time.about_late }})
                                    </div>

                                    <div v-if="time.go_late_0  > 0 && time.about_early_0 > 0">
                                        (<b style="color:red;">Đi muộn: {{ time.go_late }}</b> - <b style="color:red;">Về sớm: {{ time.about_early}}</b>)
                                    </div>

                                </template>

                            </template>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table v-else class="table table-bordered mt-5">
                <thead class="table-active">
                    <tr>
                        <th style="width:250px">
                            <input type="text" name="search" class="form-control mb-2 input-search" v-model="search"
                                placeholder="Tìm kiếm" v-on:keyup.enter="getTimeKeepings()">
                        </th>
                        <th v-for="(label, index) in labels" :key="index"
                            style="font-size:13px; text-align:center; vertical-align: middle; width: 2.879%">{{ label
                            }}
                        </th>
                        <th  style=" width: 16px"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(user, index) in data" :key="index">
                        <td style="width:250px; font-size:14px; vertical-align: middle;">{{ user.fullname }}</td>
                        <td v-for="(time, index) in user.time_keeping" :key="index" :class="time.class"
                            @click="showModal(user.id, user.fullname, time)"
                            style="text-align:center;vertical-align: middle; width: 2.9%;">
                            <div style=" font-size:13px; text-align: center;">
                                {{ time.checkin ? time.checkin: '--:--'}} <br>
                                {{ time.checkout ? time.checkout: '--:--' }}
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div>
            <div ref="modalDetail" class="modal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog" role="document" style="  max-width: 80%;" data-bs-backdrop="static" data-bs-keyboard="false">
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
            <div ref="modal" class="modal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog" role="document" style=" max-width: 80%;" data-bs-backdrop="static" data-bs-keyboard="false">
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
        <div>
            <div ref="modalCheckout" class="modal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog" role="document" style=" max-width: 50%;" data-bs-backdrop="static"
                    data-bs-keyboard="false">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Final Checkout</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                @click="closeModalFinalCheckout()">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <b>Nếu bạn click vào button <button class="btn btn-danger" @click="FinalCheckout()">Final Checkout</button> thì bạn sẽ không được tính thời gian sau thời gian Checkout nữa!</b>
                            <br>
                            <br>
                            <center>
                                <button class="btn btn-danger" @click="FinalCheckout()">Final Checkout</button>
                            </center>
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
            showModalConfirm: false,
            isHidden: true,
            showDetail: false,
            modalConfig: false,
            showOtherTime: false,
            showCheckOut: false,
            modalFinalCheckout: false,
            showCheckIn: false,
            showGoOut: false,
            showGoIn: false,
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
		changeOption(){
            this.getReport();
        },
        async FinalCheckout() {
            const res = await $post('/time-keeping/final_checkout');
            if (res.code == 200) {
                $(this.$refs.modal).modal('hide');
                this.modalFinalCheckout = false;
                this.getTimeKeepings();
            }
        },
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
                }else if (res.showBtn && res.showBtn == 'checkout') {
                    this.showCheckOut = true;
                }
                if (res.showBtn_1 == 'go_out') {
                    this.showGoOut = true;
                    this.showGoIn = false;
                } else if (res.showBtn_1 == 'go_in') {
                    this.showGoIn = true;
                    this.showGoOut = false;
                }
                if (res.showBtn_2 && res.showBtn_2 == 'final_checkout') {
                    this.showFinalCheckout = true;
                } else if (res.showBtn_2 && res.showBtn_2 == 'final_checkout_hide') {
                    this.showFinalCheckout = false;
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
        showModalFinalCheckout() {
            this.modalFinalCheckout = true;
            $(this.$refs.modalCheckout).modal('show');
        },
        closeModalFinalCheckout() {
            $(this.$refs.modalCheckout).modal('hide');
            this.modalFinalCheckout = false;
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
        async GoOut() {
           
            const res = await $post('/time-keeping/go_out');

            if (res.code == 200) {
                if (res.go_out) {
                    this.showGoIn = true;
                } else {
                    this.showGoIn = false;
                }
                this.showGoOut = false;
            }
            this.getTimeKeepings();
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
    background-color: rgb(219, 219, 219);
}

table table-bordered mt-5
{
    background: #fff;
    border: 0px solid #999999;
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

table {
    table-layout: fixed;
}

th,

td {
    padding: 0px 0px;
    border: 1px solid #000;
}

thead {
    background: #f9f9f9;
    display: table;
}

tbody {
    height: 860px;
    overflow: auto;
    display: block;
}
</style>
