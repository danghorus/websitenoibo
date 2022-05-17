<template>
    <div>
        <div>
            <div>
                <p @click="updateCheckin()">Checkin:
                    <span v-if="!showUpdateCheckIn">{{ user && user.checkin? user.checkin: '--:--:--' }}</span>
                    <DatePicker
                        v-else
                        v-model="checkin"
                        value-type="format"
                        type="time"
                        placeholder="Select time"
                    ></DatePicker>
                </p>
                <p @click="updateCheckout()">Checkout:
                    <span v-if="!showUpdateCheckOut">{{ user && user.checkout? user.checkout: '--:--:--' }}</span>
                    <DatePicker
                        v-else
                        v-model="checkout"
                        value-type="format"
                        type="time"
                        placeholder="Select time"
                    ></DatePicker>
                </p>
                <p>Lý do:
                    <span v-if="!showUpdateReason">{{ user && user.reason }}</span>
                    <textarea v-else v-model="reason" ></textarea>
                </p>
                <div v-if="showUpdateCheckIn || showUpdateCheckOut" class="mb-3">
                    <button class="btn btn-primary" @click="update()">Cập nhật</button>
                    <button class="btn btn-default" @click="close()">Đóng</button>
                </div>
            </div>
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
                    <td>{{ time.personName }}</td>
                    <td>{{ time.deviceName }}</td>
                    <td>{{ time.title }}</td>
                    <td style="width:300px; height:300px; text-align:center;">
                        <img :src="time.avatar" style="width:200px; height:200px; border: 2px; border-radius: 5px;"/>
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
            user: [],
            checkin: '',
            checkout: '',
            reason: '',
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
                this.data = res.data;
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
            this.showUpdateCheckIn = true;
            this.showUpdateReason = true;
        },
        updateCheckout() {
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
