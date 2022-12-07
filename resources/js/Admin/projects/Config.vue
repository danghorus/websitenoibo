<template>
    <div>
        <ul class="nav nav-tabs" id="myTab" role="tablist">
             <li class="nav-item">
                <a class="nav-link active" id="sticker-tab" data-toggle="tab" href="#sticker" aria-controls="sticker"
                    @click="getAllSticker()">
                    <span class="nav-text">Quản lý Loại công việc</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="priority-tab" data-toggle="tab" href="#priority"
                    @click="getAllPriority()">
                    <span class="nav-text">Quản lý cấp độ công việc</span>
                </a>
            </li>
        </ul>
        <div class="tab-content mt-5" id="myTabContent">
            <div class="tab-pane " id="priority" role="tabpanel" aria-labelledby="priority-tab">
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
            <div class="tab-pane fade show active" id="sticker" role="tabpanel" aria-labelledby="sticker-tab">
                <button class="btn btn-primary float-right mb-2" v-if="!isShowCreateSticker"
                    @click="handleCreateSticker()">
                    Thêm mới
                </button>
                <div v-else class="mb-3">
                    <nav class="navbar navbar-expand-lg navbar-light bg-light" >
                        <ul class="navbar-nav mr-auto" style="font-size:12px;" >
                            <li class="nav-item">
                                <label>Loại công việc</label>
                                <input type="text" v-model="sticker_name" class="form-control" id="sticker_name"
                                    placeholder="Nhập loại công việc">
                            </li> &ensp;
                            <li class="nav-item">
                                <label>Theo bộ phận</label>
                                <select class="form-select" style="width:130px" v-model="sticker_department">
                                    <option value="12" disabled>Lựa chọn</option>
                                    <option value="1">Tất cả</option>
                                    <option value="2">Dev</option>
                                    <option value="3"> Game design</option>
                                    <option value="4">Art</option>
                                    <option value="5">Tester</option>
                                    <option value="11">Marketing</option>
                                </select>
                            </li> &ensp;
                                <li class="nav-item">
                                <label>Level 1</label>
                                <input type="text" v-model="level_1" class="form-control" id="level_1"
                                    placeholder="Trọng số">
                            </li>&ensp;
                                <li class="nav-item">
                                <label>Level 2</label>
                                <input type="text" v-model="level_2" class="form-control" id="level_2"
                                    placeholder="Trọng số">
                            </li>&ensp;
                                <li class="nav-item">
                                <label>Level 3</label>
                                <input type="text" v-model="level_3" class="form-control" id="level_3"
                                    placeholder="Trọng số">
                            </li>&ensp;
                                <li class="nav-item">
                                <label>Level 4</label>
                                <input type="text" v-model="level_4" class="form-control" id="level_4"
                                    placeholder="Trọng số">
                            </li>&ensp;
                                <li class="nav-item">
                                <label>Level 5</label>
                                <input type="text" v-model="level_5" class="form-control" id="level_5"
                                    placeholder="Trọng số">
                            </li>&ensp;
                                <li class="nav-item">
                                <label>Level 6</label>
                                <input type="text" v-model="level_6" class="form-control" id="level_6"
                                    placeholder="Trọng số">
                            </li>&ensp;
                                <li class="nav-item">
                                <label>Level 7</label>
                                <input type="text" v-model="level_7" class="form-control" id="level_7"
                                    placeholder="Trọng số">
                            </li>&ensp;
                                <li class="nav-item">
                                <label>Level 8</label>
                                <input type="text" v-model="level_8" class="form-control" id="level_8"
                                    placeholder="Trọng số">
                            </li>&ensp;
                                <li class="nav-item">
                                <label>Level 9</label>
                                <input type="text" v-model="level_9" class="form-control" id="level_9"
                                    placeholder="Trọng số">
                            </li>&ensp;
                                <li class="nav-item">
                                <label>Level 10</label>
                                <input type="text" v-model="level_10" class="form-control" id="level_10"
                                    placeholder="Trọng số">
                                </li>&ensp;
                                <li class="nav-item">
                                    <label>Huỷ</label>
                                <button class="btn btn-secondary" @click="closeCreateSticker()"
                                    style="width:100px; ">Hủy</button>
                            </li>&ensp;
                            <li class="nav-item">  
                                <label>Lưu</label> 
                                <button class="btn btn-primary" @click="saveSticker()"
                                    style="width:100px; ">Lưu</button>
                            </li>
                        </ul>
                    </nav>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col" width="20px">STT</th>
                            <th scope="col" width="250px" style="text-align:center;">Tên nhãn dán</th>
                            <th scope="col">Bộ phận</th>
                            <th scope="col">Level 1</th>
                            <th scope="col">Level 2</th>
                            <th scope="col">Level 3</th>
                            <th scope="col">Level 4</th>
                            <th scope="col">Level 5</th>
                            <th scope="col">Level 6</th>
                            <th scope="col">Level 7</th>
                            <th scope="col">Level 8</th>
                            <th scope="col">Level 9</th>
                            <th scope="col">Level 10</th>
                            <th scope="col" width="100px">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(sticker, index) in stickers" :key="index">
                            <td>{{ index + 1 }}</td>
                            <td>{{ sticker.sticker_name }}</td>
                            <td>{{ sticker.sticker_department_label }}</td>
                            <td>{{ sticker.level_1 }}</td>
                            <td>{{ sticker.level_2 }}</td>
                            <td>{{ sticker.level_3 }}</td>
                            <td>{{ sticker.level_4 }}</td>
                            <td>{{ sticker.level_5 }}</td>
                            <td>{{ sticker.level_6 }}</td>
                            <td>{{ sticker.level_7 }}</td>
                            <td>{{ sticker.level_8 }}</td>
                            <td>{{ sticker.level_9 }}</td>
                            <td>{{ sticker.level_10 }}</td>
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
            isShowCreateSticker: false,
            sticker_name: '',
            sticker_department: '',
            level_1: '',
            level_2: '',
            level_3: '',
            level_4: '',
            level_5: '',
            level_6: '',
            level_7: '',
            level_8: '',
            level_9: '',
            level_10: '',
            stickers: [],
            sticker_id: 0,
            isShowCreatePriority: false,
            priority_label: '',
            priorities: [],
            priority_id: 0,
        }
    },
    created() {
        //this.getAllPriority();
        //this.getAllSticker();
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
                const res = await $post(`/priorities/update/${this.priority_id}`, {
                    priority_label: this.priority_label ,
                });
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
            this.sticker_department = sticker.sticker_department;
            this.level_1 = sticker.level_1;
            this.level_2 = sticker.level_2;
            this.level_3 = sticker.level_3;
            this.level_4 = sticker.level_4;
            this.level_5 = sticker.level_5;
            this.level_6 = sticker.level_6;
            this.level_7 = sticker.level_7;
            this.level_8 = sticker.level_8;
            this.level_9 = sticker.level_9;
            this.level_10 = sticker.level_10;
            this.sticker_id = sticker.id;
        },
        async saveSticker() {
            if (!this.sticker_name) {
                toastr.error('Vui lòng nhập tên nhãn dán');
                return false;
            }
            if (this.sticker_id > 0) {
                const res = await $post(`/stickers/update/${this.sticker_id}`, {
                    sticker_name: this.sticker_name,
                    sticker_department: this.sticker_department,
                    level_1: this.level_1,
                    level_2: this.level_2,
                    level_3: this.level_3,
                    level_4: this.level_4,
                    level_5: this.level_5,
                    level_6: this.level_6,
                    level_7: this.level_7,
                    level_8: this.level_8,
                    level_9: this.level_9,
                    level_10: this.level_10,
                });
                if (res.code == 200) {
                    toastr.success('Cập nhật thành công');
                    this.isShowCreateSticker = false;
                    this.sticker_name = '';
                    this.sticker_department = '';
                    this.level_1 = '';
                    this.level_2 = '';
                    this.level_3 = '';
                    this.level_4 = '';
                    this.level_5 = '';
                    this.level_6 = '';
                    this.level_7 = '';
                    this.level_8 = '';
                    this.level_9 = '';
                    this.level_10 = '';
                    this.sticker_id = 0;
                    this.getAllSticker();
                }
            } else {
                const res = await $post('/stickers/create', {
                    sticker_name: this.sticker_name,
                    sticker_department: this.sticker_department,
                    level_1: this.level_1,
                    level_2: this.level_2,
                    level_3: this.level_3,
                    level_4: this.level_4,
                    level_5: this.level_5,
                    level_6: this.level_6,
                    level_7: this.level_7,
                    level_8: this.level_8,
                    level_9: this.level_9,
                    level_10: this.level_10,

                });
                if (res.code == 200) {
                    toastr.success('Tạo mới thành công');
                    this.isShowCreateSticker = false;
                    this.sticker_name = '';
                    this.sticker_department = '';
                    this.level_1 = '';
                    this.level_2 = '';
                    this.level_3 = '';
                    this.level_4 = '';
                    this.level_5 = '';
                    this.level_6 = '';
                    this.level_7 = '';
                    this.level_8 = '';
                    this.level_9 = '';
                    this.level_10 = '';
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
