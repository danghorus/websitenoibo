<template>
    <div class="card">
        <div class="card-header">
            <h4>Bảng chấm công</h4>
            <div style="position: absolute; right: 20px; top: 15px">
                <button v-if="showCheckIn && currentUser.check_type == 2" class="btn btn-success" @click="checkIn()">Checkin</button>
                <button  v-if="showCheckOut && currentUser.check_type == 2" class="btn btn-danger" @click="checkIn()">Checkout</button>
                <button class="btn btn-primary" @click="exportData()">Xuất file excel</button>
                <button class="btn btn-primary" @click="showModalConfig()">Cấu hình</button>
            </div>

        </div>
        <div class="card-body table-responsive">
            <select class="form-select col-lg-2" style="position: absolute; right: 20px; top: 80px" v-model="option">
                <option value="1">Theo tuần</option>
                <option value="2">Theo tháng</option>
            </select>
            <date-picker v-if="option == 2" v-model="timeSelected" type="month" placeholder="Vui lòng chọn tháng để tìm kiếm"
                         @change="changeOption()" style="position: absolute;right: 350px;top: 80px;">
            </date-picker>
            <date-picker v-if="option == 1" v-model="timeSelected" type="week" placeholder="Vui lòng chọn tuần để tìm kiếm"
                         @change="changeOption()" style="position: absolute;right: 350px;top: 80px;">
            </date-picker>
            <table class="table table-bordered mt-5">
                <thead class="table-active">
                    <tr>
                        <th>
                            <input type="text" name="search" class="form-control mb-2 input-search" v-model="search" placeholder="Tìm kiếm" v-on:keyup.enter="getTimeKeepings()">
                        </th>
                        <th v-for="label in labels">{{ label }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in data">
                        <td>{{ user.fullname }}</td>
                        <td v-for="time in user.time_keeping" :class="time.class" @click="showModal(user.id, user.fullname, time)">
                            <span v-if="option == 1">Giờ hành chính:</span><br>
                            <span v-if="option == 1">Check in: </span>{{ time.checkin ? time.checkin: (option == 1? '--:--:--': '--:--' )}} <br>
                            <span v-if="option == 1">Check out: </span>{{ time.checkout ? time.checkout: (option == 1? '--:--:--': '--:--' ) }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div>
                <p><i class="fa fa-circle" style="color: red"></i>  Chưa checkin (checkout)</p>
                <p><i class="fa fa-circle" style="color: green"></i>  Chấm công đầy đủ</p>
                <p><i class="fa fa-circle" style="color: yellow"></i>  Chấm công muộn (Về sớm)</p>
            </div>
        </div>
        <div>
            <div ref="modalDetail" class="modal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document" style="  max-width: 80%;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Chi tiết chấm công: {{userName}} - Ngày {{ time.day }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="closeModal()">
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
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="closeModalConfig()">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <ModalConfigTimeKeeping v-if="modalConfig" @closeModalConfig="closeModalConfig()"/>
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
    }
}
</script>

<style scoped>

</style>
