<template>
    <div>
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="time-keeping-tab" data-toggle="tab" href="#time-keeping" @click="showTabTimeKeepingConfig()">
                    <span class="nav-text">Thời gian chấm công</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="connect-tab" data-toggle="tab" href="#connect" aria-controls="connect" @click="showTabConnect()">
                    <span class="nav-text">Kết nối Camera AI</span>
                </a>
            </li>
             <li class="nav-item">
                <a class="nav-link" id="sync-tab" data-toggle="tab" href="#sync" aria-controls="sync" @click="showTabSync()">
                    <span class="nav-text">Đồng bộ giờ công từ Hanet</span>
                </a>
            </li>
        </ul>
        <div class="tab-content mt-5" id="myTabContent">
            <div v-if="isShowTabTimeKeepingConfig" class="tab-pane fade show active" id="time-keeping" role="tabpanel" aria-labelledby="time-keeping-tab">
                <div class="form-group">
                    <label>Thứ 2:</label>
                    <div class="row">
                        <DatePicker
                            v-model="settings.monday.start_time"
                            value-type="format"
                            type="time"
                            format="HH:mm"
                            placeholder="Select time"
                            @change="handleChange"
                        ></DatePicker>
                        <DatePicker
                            v-model="settings.monday.end_time"
                            value-type="format"
                            type="time"
                            format="HH:mm"
                            placeholder="Select time"
                            @change="handleChange"
                        ></DatePicker>
                    </div>
                </div>
                <div class="form-group">
                    <label>Thứ 3:</label>
                    <div class="row">
                        <DatePicker
                            v-model="settings.tuesday.start_time"
                            value-type="format"
                            type="time"
                            format="HH:mm"
                            placeholder="Select time"
                            @change="handleChange"
                        ></DatePicker>
                        <DatePicker
                            v-model="settings.tuesday.end_time"
                            value-type="format"
                            type="time"
                            format="HH:mm"
                            placeholder="Select time"
                            @change="handleChange"
                        ></DatePicker>
                    </div>
                </div>
                <div class="form-group">
                    <label>Thứ 4:</label>
                    <div class="row">
                        <DatePicker
                            v-model="settings.wednesday.start_time"
                            value-type="format"
                            type="time"
                            format="HH:mm"
                            placeholder="Select time"
                            @change="handleChange"
                        ></DatePicker>
                        <DatePicker
                            v-model="settings.wednesday.end_time"
                            value-type="format"
                            type="time"
                            format="HH:mm"
                            placeholder="Select time"
                            @change="handleChange"
                        ></DatePicker>
                    </div>
                </div>
                <div class="form-group">
                    <label>Thứ 5:</label>
                    <div class="row">
                        <DatePicker
                            v-model="settings.thursday.start_time"
                            value-type="format"
                            type="time"
                            format="HH:mm"
                            placeholder="Select time"
                            @change="handleChange"
                        ></DatePicker>
                        <DatePicker
                            v-model="settings.thursday.end_time"
                            value-type="format"
                            type="time"
                            format="HH:mm"
                            placeholder="Select time"
                            @change="handleChange"
                        ></DatePicker>
                    </div>
                </div>
                <div class="form-group">
                    <label>Thứ 6:</label>
                    <div class="row">
                        <DatePicker
                            v-model="settings.friday.start_time"
                            value-type="format"
                            type="time"
                            format="HH:mm"
                            placeholder="Select time"
                            @change="handleChange"
                        ></DatePicker>
                        <DatePicker
                            v-model="settings.friday.end_time"
                            value-type="format"
                            type="time"
                            format="HH:mm"
                            placeholder="Select time"
                            @change="handleChange"
                        ></DatePicker>
                    </div>
                </div>
                <div class="form-group">
                    <label>Thứ 7:</label>
                    <div class="row">
                        <DatePicker
                            v-model="settings.saturday.start_time"
                            value-type="format"
                            type="time"
                            format="HH:mm"
                            placeholder="Select time"
                            @change="handleChange"
                        ></DatePicker>
                        <DatePicker
                            v-model="settings.saturday.end_time"
                            value-type="format"
                            type="time"
                            format="HH:mm"
                            placeholder="Select time"
                            @change="handleChange"
                        ></DatePicker>
                    </div>
                </div>
                <div class="form-group">
                    <label>Chủ nhật:</label>
                    <div class="row">
                        <DatePicker
                            v-model="settings.sunday.start_time"
                            value-type="format"
                            type="time"
                            format="HH:mm"
                            placeholder="Select time"
                            @change="handleChange"
                        ></DatePicker>
                        <DatePicker
                            v-model="settings.sunday.end_time"
                            value-type="format"
                            type="time"
                            format="HH:mm"
                            placeholder="Select time"
                            @change="handleChange"
                        ></DatePicker>
                    </div>
                </div>
                <button class="btn btn-primary" @click="saveConfig()">Lưu</button>
            </div>
            <div v-if="isShowTabConnect" class="tab-pane" id="connect" role="tabpanel" aria-labelledby="connect-tab">
                <setting-config-partner />
            </div>
            <div v-if="isShowTabSync" class="tab-pane" id="sync" role="tabpanel" aria-labelledby="sync-tab">
                <div class="row mb-2">
                    <div class="col-lg-6">
                        <date-picker
                            style="width:100%"
                            v-model="dateRange"
                            type="date"
                            range
                            placeholder="Vui lòng chọn khoảng thời gian để lấy dữ liệu từ Hanet">
                        </date-picker>
                    </div>
                    <div class="col-lg-6">
                        <multiselect
                            v-model="values"
                            :options="users"
                            :multiple="true"
                            :close-on-select="true"
                            :clear-on-select="true"
                            :preserve-search="true"
                            placeholder="Vui lòng chọn"
                            label="fullname"
                            track-by="id"
                        >
                            <template slot="selection" slot-scope="{ values, search, isOpen }">
                                <span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">Đã chọn {{ values.length }} nhân viên</span>
                            </template>
                        </multiselect>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-lg-6">
                        <multiselect v-model="device" :options="devices" value="device_code" label="device_name" :close-on-select="false" :show-labels="true" placeholder="Pick a value">
                        </multiselect>
                    </div>
                </div>
                <button class="btn btn-primary" @click="getSyncTimekeeping()" style="height:33px; font-size:14px; margin: -5px 0px 0px 0px">Đồng bộ</button>
            </div>
        </div>
    </div>
</template>

<script>
import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import SettingConfigPartner from "./SettingConfigPartner";
import Multiselect from 'vue-multiselect'
import {$get, $post} from "../../ultis";
import moment from "moment";

export default {
    name: "ModalConfigTimeKeeping",
    components: {SettingConfigPartner, DatePicker, Multiselect},
    data() {
        return {
            dateRange: '',
            value: null,
            open: false,
            settings: {
                monday: {
                    start_time: '',
                    end_time: ''
                },
                tuesday: {
                    start_time: '',
                    end_time: ''
                },
                wednesday: {
                    start_time: '',
                    end_time: ''
                },
                thursday: {
                    start_time: '',
                    end_time: ''
                },
                friday: {
                    start_time: '',
                    end_time: ''
                },
                saturday: {
                    start_time: '',
                    end_time: ''
                },
                sunday: {
                    start_time: '',
                    end_time: ''
                },
            },
            users: [],
            values: [],
            isShowTabTimeKeepingConfig: true,
            isShowTabConnect: false,
            isShowTabSync: false,
            devices: [],
            device: ''
        };
    },
    created() {
        this.getConfigTime();
    },
    methods: {
        handleChange(value, type) {
            if (type === 'second') {
                this.open = false;
            }
        },
        async saveConfig() {
            const res = await $post('/partner/update_config', {
                code: 'TIME',
                name: 'time config',
                settings: this.settings
            })

            if (res.code === 200) {
                toastr.success('Lưu thành công');
                this.$emit('closeModalConfig')
            }
        },
        async getConfigTime() {
            const res = await $get('/partner/get_config_time')
            if (res.code === 200) {
                this.settings = res.settings;
            }
        },
        async getSyncTimekeeping() {
            let params = {
                start_date: this.dateRange.length > 1 ? moment(this.dateRange[0]).format('YYYY-MM-DD') : "",
                end_date: this.dateRange.length > 1 ? moment(this.dateRange[1]).format('YYYY-MM-DD') : "",
                users: this.values.map(val => val.user_code),
                device: this.device.device_code
            }

            const res = await $get('/partner/get-sync-timekeeping', {...params});

            if (res.code === 200) {
                toastr.success('Đồng bộ thành công');
            }
        },
        async getAllUser() {
            const res = await $get('/user/all_user');
            if (res.code == 200) {
                this.users = res.data;
            }
        },

        async getDevices() {
            const res = await $get('/partner/get_devices')
            if (res.code === 200) {
                this.devices = res.data;
            }
        },
        showTabTimeKeepingConfig() {
            this.isShowTabTimeKeepingConfig = true;
            this.isShowTabConnect = false;
            this.isShowTabSync = false;
        },
        showTabConnect() {
            this.isShowTabTimeKeepingConfig = false;
            this.isShowTabConnect = true;
            this.isShowTabSync = false;
        },
        showTabSync() {
            this.isShowTabTimeKeepingConfig = false;
            this.isShowTabConnect = false;
            this.isShowTabSync = true;
            this.getAllUser();
            this.getDevices();
        },
    },
}
</script>

<style scoped>

</style>
