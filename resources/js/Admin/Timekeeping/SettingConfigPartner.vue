<template>
    <div>
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="face-tab" data-toggle="tab" href="#face" @click="getUsers()">
                    <span class="nav-text">Quản lý FaceId</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="camera-tab" data-toggle="tab" href="#camera" aria-controls="camera" @click="getDevices()">
                    <span class="nav-text">Quản lý Camera</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="hanet-tab" data-toggle="tab" href="#hanet" aria-controls="hanet"  @click="getConfig()">
                    <span class="nav-text">Kết nối Camera AI</span>
                </a>
            </li>
        </ul>
        <div class="tab-content mt-5" id="myTabContent">
            <div class="tab-pane fade show active" id="face" role="tabpanel" aria-labelledby="face-tab">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Mã FaceId</th>
                            <th scope="col">Tên nhân viên</th>
                            <th scope="col">Chi nhánh</th>
                            <th scope="col">Hình ảnh nhận diện</th>
                            <th scope="col">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(user, index) in users">
                        <td>{{ index+1 }}</td>
                        <td>{{ user.id }}</td>
                        <td>{{ user.fullname }}</td>
                        <td>{{ user.place_name }}</td>
                        <td>
                            <img :src="user.face_image_url" width="150" height="150" />
                        </td>
                        <td>
                            <button class="btn btn-outline-primary" data-toggle="collapse" data-target="#collapseFace"
                                    aria-expanded="false" aria-controls="collapseFace" @click="showEditFaceId(user)">
                                Sửa
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane" id="camera" role="tabpanel" aria-labelledby="camera-tab">
                <div>
                    <button class="btn btn-primary" @click="syncDevice()" style="position: absolute; right: 20px; top: 155px">Đồng bộ thiết bị</button>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Tên Camera</th>
                                <th scope="col">Loại ghi nhận</th>
                                <th scope="col">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(device, index) in devices">
                                <td>{{ index+1 }}</td>
                                <td>{{ device.device_name }}</td>
                                <td>{{ device.type_text }}</td>
                                <td>
                                    <button class="btn btn-outline-primary" data-toggle="collapse" data-target="#collapseExample"
                                            aria-expanded="false" aria-controls="collapseExample" @click="getDeviceInfo(device.device_code)">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane" id="hanet" role="tabpanel" aria-labelledby="hanet-tab">
                <div class="form-group">
                    <label><span style="color: red">*</span>Client Id:</label>
                    <input type="text" class="form-control" v-model="clientId" :disabled="config.access_token">
                </div>
                <div class="form-group">
                    <label><span style="color: red">*</span>Client Secret:</label>
                    <input type="text" class="form-control" v-model="clientSecret" :disabled="config.access_token">
                </div>
                <button v-if="!config.access_token" class="btn btn-primary" @click="connectHanet()">Kết nối</button>
            </div>
        </div>
        <div class="collapse" id="collapseExample" v-if="showEdit">
            <div class="card card-body collapse-edit">
                <div class="form-group">
                    <label>Tên thiết bị:</label>
                    <input type="text" class="form-control" v-model="device.device_name">
                </div>
                <div class="form-group">
                    <label>Loại ghi nhận:</label>
                    <select class="form-select" v-model="device.type">
                        <option value="0">Vào/ra</option>
                        <option value="1">Vào</option>
                        <option value="2">ra</option>
                    </select>
                </div>
                <div class="row">
                    <button class="btn btn-primary col-6" @click="updateDivice()">Cập nhật</button>
                    <button class="btn btn-default col-6" @click="closeCol()">Đóng</button>
                </div>

            </div>
        </div>
        <div class="collapse" id="collapseFace" v-if="showEditFace">
            <div class="card card-body collapse-edit">
                <div class="form-group">
                    <label><span style="color: red">*</span>Upload ảnh đăng kí chấm công:</label>
                    <input type="file" ref="uploader" accept="image/*" @change="uploadFile($event)">
                </div>
                <div class="form-group">
                    <label>Địa điểm đăng kí:</label>
                    <select class="form-select" v-model="place" :disabled="user.place_id">
                        <option v-for="p in places" :value="p" :selected="p.id == user.place_id">{{ p.name }}</option>
                    </select>
                </div>
                <div class="row">
                    <button class="btn btn-primary col-6" @click="updateUser(user.id)">Cập nhật</button>
                    <button class="btn btn-default col-6" @click="closeCol()">Đóng</button>
                </div>

            </div>
        </div>
    </div>
</template>

<script>

import {$post, $get, $upload} from "../../ultis";

export default {
    name: "SettingConfigPartner",
    data() {
        return {
            clientId: '',
            clientSecret: '',
            devices: '',
            users: '',
            places: '',
            device: '',
            config: '',
            showEdit: false,
            showEditFace: false,
            file: '',
            place: '',
            user: ''
        }
    },
    created() {
        this.getUsers();
        this.getConfig();
    },
    methods: {
        async connectHanet() {
            this.closeCol();
            let data = {
                client_id: this.clientId,
                client_secret: this.clientSecret,
                partner_name: 'hanet',
                partner_code: 'HANET',
                active: 1
            };
            const res = await $post('/api/partner/connect',
                {...data},
                false)
            if (res.code === 200) {
                window.open(res.url);
            }
        },
        async getDevices() {
            this.closeCol();
            const res = await $get('/api/partner/get_devices')
            if (res.code === 200) {
                this.devices = res.data;
            }
        },
        async getConfig() {
            const res = await $get('/api/partner/get_config')
            if (res.code === 200) {
                this.config = res.data;
                this.clientId = res.data.client_id;
                this.clientSecret = res.data.client_secret;
            }
        },
        async syncDevice() {
            const res = await $get('/api/partner/sync_device')
            if (res) {
                this.getDevices();
            }
        },
        async getDeviceInfo(code) {
            this.showEdit = true;

            const res = await $get('/api/partner/get_device_info', {code: code})
            if (res.code === 200) {
                this.device = res.data;
                this.deviceName = this.device.device_name;
                this.deviceType = this.device.type;
            }
        },
        async updateDivice() {
            const res = await $post('/api/partner/update_device', {
                device_code: this.device.device_code,
                device_name: this.device.device_name,
                type: this.device.type,
            })
            if (res.code === 200) {
                this.closeCol();
                this.getDevices();
            }
        },
        async updateUser(userId) {
            let files = this.$refs.uploader.files;
            if (this.user.face_image_url) {
                const res = await $upload('https://partner.hanet.ai/person/updateByFaceImage', [],  {
                    placeID: this.user.place_id,
                    aliasID: userId,
                    token: this.config?.access_token,
                    file: files[0],
                })

                if (res.returnCode === 1 ) {
                    const r = await $post('/api/partner/update_user', {
                        user_id: userId,
                        face_image_url: res.data?.path
                    })
                    if (r.code === 200) {
                        this.closeCol();
                        this.getUsers();
                    }
                } else {
                    toastr.error(res.returnMessage);
                }
            } else {
                const res = await $upload('https://partner.hanet.ai/person/register', [],  {
                    placeID: this.place.id,
                    aliasID: userId,
                    token: this.config?.access_token,
                    title: this.user?.position,
                    name: this.user?.fullname,
                    file: files[0],
                })

                if (res.returnCode === 1 ) {
                    const r = await $post('/api/partner/update_user', {
                        place_id: this.place.id,
                        place_name: this.place.name,
                        user_id: userId,
                        face_image_url: res.data?.file
                    })
                    if (r.code === 200) {
                        this.closeCol();
                        this.getUsers();
                    }
                } else {
                    toastr.error(res.returnMessage);
                }
            }
        },
        async getUsers() {
            this.closeCol();
            const res = await $get('/api/partner/get_users')
            if (res.code === 200) {
                this.users = res.users;
                this.places = res.places;
            }
        },
        closeCol() {
            this.showEdit = false;
            this.showEditFace = false;
        },
        showEditFaceId(user) {
            this.showEditFace = true;
            this.user = user;
        }
    }
}
</script>

<style scoped lang="scss">
.collapse-edit {
    width: 300px;
    height: auto;
    position: absolute;
    right: 60px;
    bottom: 90px;
    z-index: 10;
    box-shadow: 3px 3px #888888;
}
</style>
