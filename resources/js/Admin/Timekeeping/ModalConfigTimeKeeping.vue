<template>
    <div>
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="time-keeping-tab" data-toggle="tab" href="#time-keeping">
                    <span class="nav-text">Thời gian chấm công</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="connect-tab" data-toggle="tab" href="#connect" aria-controls="connect">
                    <span class="nav-text">Kết nối Camera AI</span>
                </a>
            </li>
        </ul>
        <div class="tab-content mt-5" id="myTabContent">
            <div class="tab-pane fade show active" id="time-keeping" role="tabpanel" aria-labelledby="time-keeping-tab">
                <div class="form-group">
                    <label><span style="color: red">*</span>Checkin:</label>
                    <DatePicker
                        v-model="value"
                        value-type="format"
                        type="time"
                        :open.sync="open"
                        placeholder="Select time"
                        @change="handleChange"
                    ></DatePicker>
                </div>
                <div class="form-group">
                    <label><span style="color: red">*</span>Checkout:</label>
                    <DatePicker
                        v-model="value"
                        value-type="format"
                        type="time"
                        :open.sync="open"
                        placeholder="Select time"
                        @change="handleChange"
                    ></DatePicker>
                </div>
                <button class="btn btn-primary">Lưu</button>
            </div>
            <div class="tab-pane" id="connect" role="tabpanel" aria-labelledby="connect-tab">
                <div class="form-group">
                    <label><span style="color: red">*</span>Client:</label>
                    <input type="text" class="form-control">
                </div>
                <div class="form-group">
                    <label><span style="color: red">*</span>Secret:</label>
                    <input type="text" class="form-control">
                </div>
                <button class="btn btn-primary">Kết nối</button>
            </div>
        </div>
    </div>
</template>

<script>
import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';

export default {
    name: "ModalConfigTimeKeeping",
    components: {DatePicker},
    data() {
        return {
            value: null,
            open: false,
        };
    },
    methods: {
        handleChange(value, type) {
            if (type === 'second') {
                this.open = false;
            }
        },
    },
}
</script>

<style scoped>

</style>
