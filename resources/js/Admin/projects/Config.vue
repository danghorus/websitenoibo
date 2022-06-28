<template>
    <div>
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="priority-tab" data-toggle="tab" href="#priority"
                    @click="getAllPriority()">
                    <span class="nav-text">Quản lý mức độ ưu tiên</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="sticker-tab" data-toggle="tab" href="#sticker" aria-controls="sticker"
                    @click="getAllSticker()">
                    <span class="nav-text">Quản lý nhãn dán</span>
                </a>
            </li>
        </ul>
        <div class="tab-content mt-5" id="myTabContent">
            <div class="tab-pane fade show active" id="priority" role="tabpanel" aria-labelledby="priority-tab">
                <button class="btn btn-primary float-right mb-2" v-if="!isShowCreatePriority"
                    @click="handleCreatePriority()">
                    Thêm mới
                </button>
                <div v-else class="mb-2">
                    <div class="form-group col-lg-9">
                        <input type="text" v-model="priority_label" class="form-control" id="priority_label"
                            placeholder="Nhập tên mức độ ưu tiên">
                    </div>
                    <div style="float: right; margin-top: -55px;">
                        <button class="btn btn-secondary" @click="closeCreatePriority()"
                            style="width: 100px;">Hủy</button>
                        <button class="btn btn-primary" @click="savePriority()" style="width: 100px;">Lưu</button>
                    </div>
                </div>
                <table class=" table">
                            <thead>
                                <tr>
                                    <th scope="col" width="250px">STT</th>
                                    <th scope="col">Tên mức độ ưu tiên</th>
                                    <th scope="col" width="200px">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(priority, index) in priorities" :key="index">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ priority.priority_label }}</td>
                                    <td>
                                        <nav class="navbar navbar-expand">
                                            <div class="collapse navbar-collapse">
                                                <ul class="navbar-nav">
                                                    <li>
                                                        <p @click="handleUpdatePriority(priority)">
                                                            <i class="fas fa-pencil-alt" style="cursor: pointer" />
                                                        </p>
                                                    </li> &emsp; &emsp;
                                                    <li>
                                                        <p @click="deletePriority(priority.id)">
                                                            <i class="fas fa-trash" style="cursor: pointer" />
                                                        </p>
                                                    </li>
                                                </ul>
                                            </div>
                                        </nav>
                                    </td>
                                </tr>
                            </tbody>
                            </table>
                    </div>
                    <div class="tab-pane" id="sticker" role="tabpanel" aria-labelledby="sticker-tab">
                        <button class="btn btn-primary float-right mb-2" v-if="!isShowCreateSticker"
                            @click="handleCreateSticker()">
                            Thêm mới
                        </button>
                        <div v-else class="mb-3">
                            <div class="form-group col-lg-9">
                                <input type="text" v-model="sticker_name" class="form-control" id="sticker_name"
                                    placeholder="Nhập tên nhãn dán">
                            </div>
                            <div style="float: right; margin-top: -55px;">
                                <button class="btn btn-secondary" @click="closeCreateSticker()"
                                    style="width:100px; ">Hủy</button>
                                <button class="btn btn-primary" @click="saveSticker()"
                                    style="width:100px; ">Lưu</button>
                            </div>
                        </div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col" width="250px">STT</th>
                                    <th scope="col">Tên nhãn dán</th>
                                    <th scope="col" width="200px">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(sticker, index) in stickers" :key="index">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ sticker.sticker_name }}</td>
                                    <td>
                                        <nav class="navbar navbar-expand">
                                            <div class="collapse navbar-collapse">
                                                <ul class="navbar-nav">
                                                    <li>
                                                        <p @click="handleUpdateSticker(sticker)">
                                                            <i class="fas fa-pencil-alt" style="cursor: pointer" />
                                                        </p>
                                                    </li> &emsp; &emsp;
                                                    <li>
                                                        <p @click="deleteSticker(sticker.id)">
                                                            <i class="fas fa-trash" style="cursor: pointer" />
                                                        </p>
                                                    </li>
                                                </ul>
                                            </div>
                                        </nav>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
</template>

<script>
import {$post, $get} from "../../ultis";

export default {
    name: "Config",
    data() {
        return{
            isShowCreatePriority: false,
            priority_label: '',
            priorities: [],
            priority_id: 0,
            isShowCreateSticker: false,
            sticker_name: '',
            stickers: [],
            sticker_id: 0
        }
    },
    created() {
        this.getAllPriority();
    },
    methods: {
        handleCreatePriority() {
            this.isShowCreatePriority = true;
        },
        closeCreatePriority() {
            this.isShowCreatePriority = false;
        },
        handleUpdatePriority(priority) {
            console.log(priority);
            this.isShowCreatePriority = true;
            this.priority_label = priority.priority_label;
            this.priority_id = priority.id;
        },
        async savePriority() {
            if (!this.priority_label) {
                toastr.error('Vui lòng nhập tên mức độ ưu tiên');
                return false;
            }
            if (this.priority_id > 0) {
                const res = await $post(`/priorities/update/${this.priority_id}`, {priority_label: this.priority_label});
                if (res.code == 200) {
                    toastr.success('Cập nhật thành công');
                    this.isShowCreatePriority = false;
                    this.priority_label = '';
                    this.priority_id = 0;
                    this.getAllPriority();
                }
            } else {
                const res = await $post('/priorities/create', {priority_label: this.priority_label});
                if (res.code == 200) {
                    toastr.success('Tạo mới thành công');
                    this.isShowCreatePriority = false;
                    this.priority_label = '';
                    this.priority_id = 0;
                    this.getAllPriority();
                }
            }
        },
        async getAllPriority() {
            const res = await $get('/priorities/get_all');
            if (res.code == 200) {
                this.priorities = res.data;
            }
        },
        async deletePriority(id) {
            const res = await $post(`/priorities/delete/${id}`);
            if (res.code == 200) {
                toastr.success('Xóa thành công');
                this.getAllPriority();
            }
        },
        handleCreateSticker() {
            this.isShowCreateSticker = true;
        },
        closeCreateSticker() {
            this.isShowCreateSticker = false;
        },
        handleUpdateSticker(sticker) {
            this.isShowCreateSticker = true;
            this.sticker_name = sticker.sticker_name;
            this.sticker_id = sticker.id;
        },
        async saveSticker() {
            if (!this.sticker_name) {
                toastr.error('Vui lòng nhập tên nhãn dán');
                return false;
            }
            if (this.sticker_id > 0) {
                const res = await $post(`/stickers/update/${this.sticker_id}`, {sticker_name: this.sticker_name});
                if (res.code == 200) {
                    toastr.success('Cập nhật thành công');
                    this.isShowCreateSticker = false;
                    this.sticker_name = '';
                    this.sticker_id = 0;
                    this.getAllSticker();
                }
            } else {
                const res = await $post('/stickers/create', {sticker_name: this.sticker_name});
                if (res.code == 200) {
                    toastr.success('Tạo mới thành công');
                    this.isShowCreateSticker = false;
                    this.sticker_name = '';
                    this.sticker_id = 0;
                    this.getAllSticker();
                }
            }
        },
        async getAllSticker() {
            const res = await $get('/stickers/get_all');
            if (res.code == 200) {
                this.stickers = res.data;
            }
        },
        async deleteSticker(id) {
            const res = await $post(`/stickers/delete/${id}`);
            if (res.code == 200) {
                toastr.success('Xóa thành công');
                this.getAllSticker();
            }
        }
    }
}
</script>

<style scoped>

</style>
