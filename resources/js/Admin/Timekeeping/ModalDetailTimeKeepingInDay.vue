<template>
    <div>
        <div>
            <nav class="navbar navbar-expand-lg navbar-light bg-light" style="font-size: 20px;">
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto" style="font-size:16px;" id="">
                        <p @click="updateCheckin()">Checkin:
                            <span v-if="!showUpdateCheckIn" >{{ user && user.checkin? user.checkin: '--:--' }}</span>
                            <DatePicker
                                v-else
                                v-model="checkin"
                                value-type="format"
                                type="time"
                                format="HH:mm"
                                placeholder="Select time"
                            ></DatePicker>
                        </p>
                    </ul>
                </div>
            </nav>
            <nav class="navbar navbar-expand-lg navbar-light bg-light" style="font-size: 20px;">
                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav mr-auto" style="font-size:16px;">
                        <p @click="updateCheckout()" >Checkout:
                            <span v-if="!showUpdateCheckOut">{{ user && user.checkout? user.checkout: '--:--' }}</span>
                            <DatePicker
                                v-else
                                v-model="checkout"
                                value-type="format"
                                type="time"
                                format="HH:mm"
                                placeholder="Select time"
                            ></DatePicker>
                        </p>
                    </ul>
                </div>
            </nav>
            <nav class="navbar navbar-expand-lg navbar-light bg-light" style="font-size: 20px;">
                <div class="collapse navbar-collapse" >
                    <ul class="navbar-nav mr-auto" style="font-size:16px;">
                        <li>
                            <p>Lý do thay đổi giờ công:
                                <span v-if="!showUpdateReason">{{ user && user.reason }}</span>
                                <textarea v-else v-model="reason" style="width: 525px"></textarea>
                            </p>
                        </li>
                    </ul>
                </div>
            </nav>
            <nav class="navbar navbar-expand-lg navbar-light bg-light" style="font-size: 20px;">
                <div class="collapse navbar-collapse" >
                    <ul class="navbar-nav mr-auto" style="font-size:16px;">
                        <div v-if="showUpdateCheckIn || showUpdateCheckOut" class="mb-3">
                            <button class="btn btn-primary" @click="petition()">Tạo yêu cầu</button>
                            <button class="btn btn-primary" v-if="currentUser && currentUser.permission == 1" @click="update()">Cập nhật</button>
                            <button class="btn btn-default" @click="close()">Đóng</button>
                        </div>
                    </ul>
                </div>
            </nav>
        </div>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Thời gian</th>
                <th scope="col">Người thực hiện</th>
                <th scope="col">Tên Camera</th>
                <th scope="col">Chức danh</th>
                <th scope="col" style="text-align: center;">Hình ảnh</th>
            </tr>
            </thead>
            <tbody>
                <tr v-for="(time, index) in data" :key="index">
                    <th scope="row">{{ index + 1 }}</th>
                    <td>{{ time.time }}</td>
                    <td>{{ time.person_name }}</td>
                    <td>{{ time.device_name }}</td>
                    <td>{{ time.person_title }}</td>
                    <td style="width:300px; height:300px; text-align:center;">
                        <img :src="time.detected_image_url" style="width:200px; height:200px; border: 2px; border-radius: 5px;"/>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import {$get, $post} from "../../ultis";

export default {
    name: "ModalDetailTimeKeepingInDay",
    components: {DatePicker},
    props: ['userId', 'time'],
    data() {
        return {
            showUpdateCheckIn: false,
            showUpdateCheckOut: false,
            showUpdateReason: false,
            data: [],
            user: {},
            checkin: '',
            checkout: '',
            reason: '',
            fullname: '',
            currentUser: {},
            type: 4
        };
    },
    created() {
        this.getTimeKeepingInfo();
        this.getInfo();
    },
    methods: {
        async getTimeKeepingInfo() {
            let params = {
                user_id: this.userId,
                date: this.time.day
            };

            const res = await $get('/time-keeping/get_user', {...params});
            if (res.code == 200) {
                this.user = res.data;
            }
        },
        async getInfo() {
            let params = {
                user_id: this.userId,
                date: this.time.day
            };

            const res = await $get('/time-keeping/detail', {...params});
            if (res.code == 200) {
                let user = res.data;
                this.currentUser = res.currentUser;
                this.fullname = res.data.fullname;
                if (user.time_keeping_detail) {
                    this.data = user.time_keeping_detail;
                }
            }
        },
        async petition() {
            let data = {
                checkin: this.checkin,
                old_checkin: this.user && this.user.checkin ? this.user.checkin: '',
                old_checkout: this.user && this.user.checkout ? this.user.checkout: '',
                checkout: this.checkout,
                reason: this.reason,
                user_id: this.userId,
                user_fullname: this.fullname,
                date: this.time.day,
                type: this.type
            }

            const res = await $post('/petition/create_petition_time_keeping', {...data});
            if (res.code == 200) {
                toastr.success('Tạo yêu cầu thành công!');
                this.checkin = '';
                this.checkout = '';
                this.reason = '';
                this.getTimeKeepingInfo();
                this.close();
            }
        },
        async update() {
            let data = {
                checkin: this.checkin,
                checkout: this.checkout,
                reason: this.reason,
                user_id: this.userId,
                date: this.time.day
            }

            const res = await $post('/time-keeping/update', {...data});
            if (res.code == 200) {
                toastr.success('Cập nhật thành công');
                this.getTimeKeepingInfo();
                this.close();
            }
        },
        updateCheckin() {
            this.type = 4;
            this.showUpdateCheckIn = true;
            this.showUpdateReason = true;
        },
        updateCheckout() {
            this.type = 5;
            this.showUpdateCheckOut = true;
            this.showUpdateReason = true;
        },
        close() {
            this.showUpdateCheckIn = false;
            this.showUpdateCheckOut = false;
            this.showUpdateReason = false;
        }
    }
}
</script>

<style scoped>

</style>
