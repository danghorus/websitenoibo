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
                    <label><span style="color: red">*</span>Thứ 2:</label>
                    <div class="row">
                        <DatePicker
                            v-model="value"
                            value-type="format"
                            type="time"
                            placeholder="Select time"
                            @change="handleChange"
                        ></DatePicker>
                        <DatePicker
                            v-model="value"
                            value-type="format"
                            type="time"
                            placeholder="Select time"
                            @change="handleChange"
                        ></DatePicker>
                    </div>
                </div>
                <div class="form-group">
                    <label><span style="color: red">*</span>Thứ 3:</label>
                    <div class="row">
                        <DatePicker
                            v-model="value"
                            value-type="format"
                            type="time"
                            placeholder="Select time"
                            @change="handleChange"
                        ></DatePicker>
                        <DatePicker
                            v-model="value"
                            value-type="format"
                            type="time"
                            placeholder="Select time"
                            @change="handleChange"
                        ></DatePicker>
                    </div>
                </div>
                <div class="form-group">
                    <label><span style="color: red">*</span>Thứ 4:</label>
                    <div class="row">
                        <DatePicker
                            v-model="value"
                            value-type="format"
                            type="time"
                            placeholder="Select time"
                            @change="handleChange"
                        ></DatePicker>
                        <DatePicker
                            v-model="value"
                            value-type="format"
                            type="time"
                            placeholder="Select time"
                            @change="handleChange"
                        ></DatePicker>
                    </div>
                </div>
                <div class="form-group">
                    <label><span style="color: red">*</span>Thứ 5:</label>
                    <div class="row">
                        <DatePicker
                            v-model="value"
                            value-type="format"
                            type="time"
                            placeholder="Select time"
                            @change="handleChange"
                        ></DatePicker>
                        <DatePicker
                            v-model="value"
                            value-type="format"
                            type="time"
                            placeholder="Select time"
                            @change="handleChange"
                        ></DatePicker>
                    </div>
                </div>
                <div class="form-group">
                    <label><span style="color: red">*</span>Thứ 6:</label>
                    <div class="row">
                        <DatePicker
                            v-model="value"
                            value-type="format"
                            type="time"
                            placeholder="Select time"
                            @change="handleChange"
                        ></DatePicker>
                        <DatePicker
                            v-model="value"
                            value-type="format"
                            type="time"
                            placeholder="Select time"
                            @change="handleChange"
                        ></DatePicker>
                    </div>
                </div>
                <div class="form-group">
                    <label><span style="color: red">*</span>Thứ 7:</label>
                    <div class="row">
                        <DatePicker
                            v-model="value"
                            value-type="format"
                            type="time"
                            placeholder="Select time"
                            @change="handleChange"
                        ></DatePicker>
                        <DatePicker
                            v-model="value"
                            value-type="format"
                            type="time"
                            placeholder="Select time"
                            @change="handleChange"
                        ></DatePicker>
                    </div>
                </div>
                <div class="form-group">
                    <label><span style="color: red">*</span>Chủ nhật:</label>
                    <div class="row">
                        <DatePicker
                            v-model="value"
                            value-type="format"
                            type="time"
                            placeholder="Select time"
                            @change="handleChange"
                        ></DatePicker>
                        <DatePicker
                            v-model="value"
                            value-type="format"
                            type="time"
                            placeholder="Select time"
                            @change="handleChange"
                        ></DatePicker>
                    </div>
                </div>
                <button class="btn btn-primary">Lưu</button>
            </div>
            <div class="tab-pane" id="connect" role="tabpanel" aria-labelledby="connect-tab">
                <setting-config-partner />
            </div>
        </div>
    </div>
</template>

<script>
import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import SettingConfigPartner from "./SettingConfigPartner";

export default {
    name: "ModalConfigTimeKeeping",
    components: {SettingConfigPartner, DatePicker},
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
