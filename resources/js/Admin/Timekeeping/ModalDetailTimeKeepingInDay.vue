<template>
    <div>
        <div>
            <div>
                <p @click="updateCheckin()">Checkin:
                    <span v-if="!showUpdateCheckIn">08:08:08</span>
                    <DatePicker
                        v-else
                        v-model="value"
                        value-type="format"
                        type="time"
                        :open.sync="open"
                        placeholder="Select time"
                    ></DatePicker>
                </p>
                <p @click="updateCheckout()">Checkout:
                    <span v-if="!showUpdateCheckOut">08:08:08</span>
                    <DatePicker
                        v-else
                        v-model="value"
                        value-type="format"
                        type="time"
                        :open.sync="open"
                        placeholder="Select time"
                    ></DatePicker>
                </p>
                <p>Lý do:
                    <span v-if="!showUpdateReason">Thích thì đi muộn</span>
                    <textarea v-else></textarea>
                </p>
            </div>
        </div>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Thời gian</th>
                <th scope="col">Người thực hiện</th>
                <th scope="col">Hình ảnh</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th scope="row">1</th>
                <td>07:58:59</td>
                <td>Lê Văn Duy</td>
                <td>Lê Văn Duy</td>
            </tr>
            <tr>
                <th scope="row">1</th>
                <td>07:58:59</td>
                <td>Lê Văn Duy</td>
                <td>Lê Văn Duy</td>
            </tr>
            <tr>
                <th scope="row">1</th>
                <td>07:58:59</td>
                <td>Lê Văn Duy</td>
                <td>Lê Văn Duy</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';

export default {
    name: "ModalDetailTimeKeepingInDay",
    components: {DatePicker},
    data() {
        return {
            showUpdateCheckIn: false,
            showUpdateCheckOut: false,
            showUpdateReason: false,
        };
    },
    methods: {
        updateCheckin() {
            this.showUpdateCheckIn = true;
            this.showUpdateReason = true;
        },
        updateCheckout() {
            this.showUpdateCheckOut = true;
            this.showUpdateReason = true;
        }
    }
}
</script>

<style scoped>

</style>
