<template>
    <div>
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="time-keeping-tab" data-toggle="tab" href="#time-keeping"
                    @click="showTabTimeKeepingConfig()">
                    <span class="nav-text">Thời gian chấm công</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="connect-tab" data-toggle="tab" href="#connect" aria-controls="connect"
                    @click="showTabConnect()">
                    <span class="nav-text">Kết nối Camera AI</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="sync-tab" data-toggle="tab" href="#sync" aria-controls="sync"
                    @click="showTabSync()">
                    <span class="nav-text">Đồng bộ giờ công từ Hanet</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="sync-tab" data-toggle="tab" href="#holiday" aria-controls="holiday"
                    @click="showTabHoliday()">
                    <span class="nav-text">Lịch nghỉ trong năm</span>
                </a>
            </li>
        </ul>
        <div class="tab-content mt-5" id="myTabContent">
            <div v-if="isShowTabTimeKeepingConfig" class="tab-pane fade show active" id="time-keeping" role="tabpanel"
                aria-labelledby="time-keeping-tab">
                <div class="form-group">
                    <label>Thứ 2:</label>
                    <div class="row">
                        <DatePicker v-model="settings.monday.start_timeAM" value-type="format" type="time"
                            format="HH:mm" placeholder="Select time" @change="handleChange"></DatePicker>
                        <DatePicker v-model="settings.monday.end_timeAM" value-type="format" type="time" format="HH:mm"
                            placeholder="Select time" @change="handleChange"></DatePicker>
                        <DatePicker v-model="settings.monday.start_timePM" value-type="format" type="time"
                            format="HH:mm" placeholder="Select time" @change="handleChange"></DatePicker>
                        <DatePicker v-model="settings.monday.end_timePM" value-type="format" type="time" format="HH:mm"
                            placeholder="Select time" @change="handleChange"></DatePicker>
                    </div>
                </div>
                <div class="form-group">
                    <label>Thứ 3:</label>
                    <div class="row">
                        <DatePicker v-model="settings.tuesday.start_timeAM" value-type="format" type="time"
                            format="HH:mm" placeholder="Select time" @change="handleChange"></DatePicker>
                        <DatePicker v-model="settings.tuesday.end_timeAM" value-type="format" type="time" format="HH:mm"
                            placeholder="Select time" @change="handleChange"></DatePicker>
                        <DatePicker v-model="settings.tuesday.start_timePM" value-type="format" type="time"
                            format="HH:mm" placeholder="Select time" @change="handleChange"></DatePicker>
                        <DatePicker v-model="settings.tuesday.end_timePM" value-type="format" type="time" format="HH:mm"
                            placeholder="Select time" @change="handleChange"></DatePicker>
                    </div>
                </div>
                <div class="form-group">
                    <label>Thứ 4:</label>
                    <div class="row">
                        <DatePicker v-model="settings.wednesday.start_timeAM" value-type="format" type="time"
                            format="HH:mm" placeholder="Select time" @change="handleChange"></DatePicker>
                        <DatePicker v-model="settings.wednesday.end_timeAM" value-type="format" type="time"
                            format="HH:mm" placeholder="Select time" @change="handleChange"></DatePicker>
                        <DatePicker v-model="settings.wednesday.start_timePM" value-type="format" type="time"
                            format="HH:mm" placeholder="Select time" @change="handleChange"></DatePicker>
                        <DatePicker v-model="settings.wednesday.end_timePM" value-type="format" type="time"
                            format="HH:mm" placeholder="Select time" @change="handleChange"></DatePicker>
                    </div>
                </div>
                <div class="form-group">
                    <label>Thứ 5:</label>
                    <div class="row">
                        <DatePicker v-model="settings.thursday.start_timeAM" value-type="format" type="time"
                            format="HH:mm" placeholder="Select time" @change="handleChange"></DatePicker>
                        <DatePicker v-model="settings.thursday.end_timeAM" value-type="format" type="time"
                            format="HH:mm" placeholder="Select time" @change="handleChange"></DatePicker>
                        <DatePicker v-model="settings.thursday.start_timePM" value-type="format" type="time"
                            format="HH:mm" placeholder="Select time" @change="handleChange"></DatePicker>
                        <DatePicker v-model="settings.thursday.end_timePM" value-type="format" type="time"
                            format="HH:mm" placeholder="Select time" @change="handleChange"></DatePicker>
                    </div>
                </div>
                <div class="form-group">
                    <label>Thứ 6:</label>
                    <div class="row">
                        <DatePicker v-model="settings.friday.start_timeAM" value-type="format" type="time"
                            format="HH:mm" placeholder="Select time" @change="handleChange"></DatePicker>
                        <DatePicker v-model="settings.friday.end_timeAM" value-type="format" type="time" format="HH:mm"
                            placeholder="Select time" @change="handleChange"></DatePicker>
                        <DatePicker v-model="settings.friday.start_timePM" value-type="format" type="time"
                            format="HH:mm" placeholder="Select time" @change="handleChange"></DatePicker>
                        <DatePicker v-model="settings.friday.end_timePM" value-type="format" type="time" format="HH:mm"
                            placeholder="Select time" @change="handleChange"></DatePicker>
                    </div>
                </div>
                <div class="form-group">
                    <label>Thứ 7:</label>
                    <div class="row">
                        <DatePicker v-model="settings.saturday.start_timeAM" value-type="format" type="time"
                            format="HH:mm" placeholder="Select time" @change="handleChange"></DatePicker>
                        <DatePicker v-model="settings.saturday.end_timeAM" value-type="format" type="time"
                            format="HH:mm" placeholder="Select time" @change="handleChange"></DatePicker>
                        <DatePicker v-model="settings.saturday.start_timePM" value-type="format" type="time"
                            format="HH:mm" placeholder="Select time" @change="handleChange"></DatePicker>
                        <DatePicker v-model="settings.saturday.end_timePM" value-type="format" type="time"
                            format="HH:mm" placeholder="Select time" @change="handleChange"></DatePicker>
                    </div>
                </div>
                <div class="form-group">
                    <label>Chủ nhật:</label>
                    <div class="row">
                        <DatePicker v-model="settings.sunday.start_timeAM" value-type="format" type="time"
                            format="HH:mm" placeholder="Select time" @change="handleChange"></DatePicker>
                        <DatePicker v-model="settings.sunday.end_timeAM" value-type="format" type="time" format="HH:mm"
                            placeholder="Select time" @change="handleChange"></DatePicker>
                        <DatePicker v-model="settings.sunday.start_timePM" value-type="format" type="time"
                            format="HH:mm" placeholder="Select time" @change="handleChange"></DatePicker>
                        <DatePicker v-model="settings.sunday.end_timePM" value-type="format" type="time" format="HH:mm"
                            placeholder="Select time" @change="handleChange"></DatePicker>
                    </div>
                </div>
                <button class="btn btn-primary" @click="saveConfig()">Lưu</button>
            </div>
            <div v-if="isShowTabConnect" class="tab-pane" id="connect" role="tabpanel" aria-labelledby="connect-tab">
                <setting-config-partner />
            </div>
            <div v-if="isShowTabSync" class="tab-pane" id="sync" role="tabpanel" aria-labelledby="sync-tab">
                <div class="col-lg-6">
                    <date-picker style="width:100%" v-model="dateRange" type="date" range
                        placeholder="Vui lòng chọn khoảng thời gian để lấy dữ liệu từ Hanet">
                    </date-picker>
                </div>
                <div class="col-lg-6">
                    <div class="form-check">
                        <input class="form-check-input" v-model="option" value="1" type="radio" name="flexRadioDefault"
                            id="flexRadioDefault1">
                        <label class="form-check-label" for="flexRadioDefault1"> Cả công ty</label> &emsp;&emsp;&emsp;
                        <input class="form-check-input" type="radio" v-model="option" value="2" name="flexRadioDefault"
                            id="flexRadioDefault2">
                        <label class="form-check-label" for="flexRadioDefault2">Chọn nhân viên</label>
                    </div>
                </div>
                <div class="col-lg-6 mt-2" v-if="option == 2">
                    <multiselect v-model="values" :options="users" :multiple="true" :close-on-select="false"
                        :clear-on-select="true" :preserve-search="true" placeholder="Vui lòng chọn" label="fullname"
                        track-by="id">
                    </multiselect>
                </div>
                <div class="col-lg-6 mt-2">
                    <multiselect v-model="device" :options="devices" :multiple="true" value="device_code"
                        label="device_name" :close-on-select="false" :show-labels="true" placeholder="Pick a value">
                    </multiselect>
                </div>
                <button class="btn btn-primary mt-2" @click="getSyncTimekeeping()"
                    style="height:33px; font-size:14px; padding: -50px 0px 0px 0px">Đồng bộ</button>
            </div>
            <div v-if="isShowTabHoliday" class="tab-pane" id="holiday" role="tabpanel" aria-labelledby="holiday-tab">
                <div class="col-lg-6">
                    <input class="form-check-input" v-model="option" value="1" type="radio" name="flexRadioDefault"
                        id="flexRadioDefault">
                    <label class="form-check-label" for="flexRadioDefault"> Cả công ty</label>
                    <DatePicker style="width: 100%" v-model="date_holiday" value-type="format" type="date"
                        placeholder="Vui lòng chọn">
                    </DatePicker>

                    <p>Not before than 12:00 Today</p>
                    <date-picker v-model="value4" type="datetime" :default-value="new Date().setHours(12, 0, 0, 0)"
                        :disabled-date="notBeforeToday" :disabled-time="notBeforeTodayTwelveOClock" value-type="format">
                    </date-picker>
                </div>
                <button class="btn btn-primary mt-2" @click="getHoliday()"
                    style="height:33px; font-size:14px; float:right;">Thêm
                    ngày
                </button>
                <table class="table-striped table-responsive table-hover result-point">
                    <thead class="point-table-head">
                        <tr>
                            <th style="width:150px; text-align:center;">Tháng</th>
                            <th style="width:94%; text-align:center;">Ngày được nghỉ theo lịch công ty</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Tháng 01</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Tháng 02</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Tháng 03</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Tháng 04</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Tháng 05</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Tháng 06</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Tháng 07</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Tháng 08</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Tháng 09</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Tháng 10</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Tháng 11</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Tháng 12</td>
                            <td></td>
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
            date_holiday: '',
            value4: '',
            value: null,
            open: false,
            settings: {
                monday: {
                    start_timeAM: '',
                    end_timeAM: '',
                    start_timePM: '',
                    end_timePM: ''
                },
                tuesday: {
                    start_timeAM: '',
                    end_timeAM: '',
                    start_timePM: '',
                    end_timePM: ''
                },
                wednesday: {
                    start_timeAM: '',
                    end_timeAM: '',
                    start_timePM: '',
                    end_timePM: ''
                },
                thursday: {
                    start_timeAM: '',
                    end_timeAM: '',
                    start_timePM: '',
                    end_timePM: ''
                },
                friday: {
                    start_timeAM: '',
                    end_timeAM: '',
                    start_timePM: '',
                    end_timePM: ''
                },
                saturday: {
                    start_timeAM: '',
                    end_timeAM: '',
                    start_timePM: '',
                    end_timePM: ''
                },
                sunday: {
                    start_timeAM: '',
                    end_timeAM: '',
                    start_timePM: '',
                    end_timePM: ''
                },
            },
            users: [],
            values: [],
            isShowTabTimeKeepingConfig: true,
            isShowTabConnect: false,
            isShowTabSync: false,
            isShowTabHoliday: false,
            devices: [],
            device: '',
            option: 1
        };
    },
    created() {
        this.getConfigTime();
        this.getHoliday();
    },
    methods: {
        disabledBeforeTodayAndAfterAWeek(date) {
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            return date < today || date > new Date(today.getTime() + 7 * 24 * 3600 * 1000);
        },
        notBeforeNineOClock(date) {
            return date.getHours() < 9;
        },
        notBeforeToday(date) {
            return date < new Date(new Date().setHours(0, 0, 0, 0));
        },
        notBeforeTodayTwelveOClock(date) {
            return date < new Date(new Date().setHours(12, 0, 0, 0));
        },


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
            let usersData = [];
            if (this.option == 1) {
                usersData = this.users.map(val => val.user_code)
            } else {
                usersData = this.values.map(val => val.user_code)
            }

            let params = {
                start_date: this.dateRange.length > 1 ? moment(this.dateRange[0]).format('YYYY-MM-DD') : "",
                end_date: this.dateRange.length > 1 ? moment(this.dateRange[1]).format('YYYY-MM-DD') : "",
                users: usersData,
                device: this.device.device_code,
            }

            const res = await $get('/partner/get-sync-timekeeping', {...params});

            if (res.code == 200) {
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
            this.isShowTabHoliday = false;
        },
        showTabConnect() {
            this.isShowTabTimeKeepingConfig = false;
            this.isShowTabConnect = true;
            this.isShowTabSync = false;
            this.isShowTabHoliday = false;
        },
        showTabSync() {
            this.isShowTabTimeKeepingConfig = false;
            this.isShowTabConnect = false;
            this.isShowTabSync = true;
            this.isShowTabHoliday = false;
            this.getAllUser();
            this.getDevices();
        },
        showTabHoliday() {
            this.isShowTabTimeKeepingConfig = false;
            this.isShowTabConnect = false;
            this.isShowTabSync = false;
            this.isShowTabHoliday = true;
            this.getAllUser();
        },
    },
}
</script>

<style scoped>


table {
    background: #fff;
    border: 1px solid #dedede;
}

table thead tr th {
    padding: 20px;
    border: 1px solid #dedede;
    color: #000;
}

table.table-striped tbody tr:nth-of-type(odd) {
    background: #f9f9f9;
}

table.result-point tr td.number {
    width: 100px;
    position: relative;
}

.text-left {
    text-align: left !important;
}

table tr td {
    padding: 0px 0px;
    border: 1px solid #dedede;
}

table.result-point tr td .fa.fa-caret-up {
    color: green;
}

table.result-point tr td .fa {
    font-size: 20px;
    position: absolute;
    right: 20px;
}

table tr td {
    padding: 5px 5px;
    border: 1px solid #dedede;
}

table tr td img {
    margin-right: 12px;
    margin-top: 1px;
    border: 1px solid #dedede;
}
</style>
