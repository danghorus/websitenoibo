<template>
    <div>
        <div class="col-lg">
            <div class="collapse search-collapse" id="collapseExample" v-if="showFilter">
                <div class="form-group p-2">
                    <label for="project_description">Theo dự án</label>
                    <select class="form-select">
                        <option>Tất cả</option>
                        <option>Beach War version 0.1.2</option>
                        <option>Beach War version 0.1.3</option>
                        <option>Beach War version 0.1.4</option>
                    </select>
                </div>
                <div class="form-group p-2">
                    <label for="project_description">Theo trạng thái</label>
                    <select class="form-select">
                        <option>Tất cả</option>
                        <option>Đang làm</option>
                        <option>Tạm dừng</option>
                        <option>Kết thúc</option>
                    </select>
                </div>
                <div class="float-right p-2">
                    <button type="submit" class="btn btn-secondary p-2" @click="handleShowFilter()">Đóng</button>
                    <button type="submit" class="btn btn-primary p-2" style="width:70px;">Lọc</button>
                </div>
            </div>
        </div>
        <div class="col-lg">
            <div class="collapse info-my-work" id="collapseExample_1" v-if="showInfoMyWork">
                <div>
                    <table style="width:100%; border: 0px" class="my-work">
                        <h4 style="margin: 20px 0px 20px 130px;">Thông tin làm việc</h4>
                        <tr>
                            <td><b>Warrior đăng ký</b></td>
                            <td>:</td>
                            <td>Warrior 3</td>
                        </tr>
                        <tr>
                            <td><b>Warrior hiện tại</b></td>
                            <td>:</td>
                            <td>Warrior 3</td>
                        </tr>
                        <tr>
                            <td><b>Thời gian làm việc trong ngày</b></td>
                            <td>:</td>
                            <td>12 tiếng</td>
                        </tr>
                        <tr>
                            <td><b>Tổng số công việc của bạn</b></td>
                            <td>:</td>
                            <td>120</td>
                        </tr>
                        <tr>
                            <td><b>Đang làm</b></td>
                            <td>:</td>
                            <td>20</td>
                        </tr>
                        <tr>
                            <td><b>Hoàn thành</b></td>
                            <td>:</td>
                            <td>80</td>
                        </tr>
                        <tr>
                            <td><b>Quá hạn</b></td>
                            <td>:</td>
                            <td>20</td>
                        </tr>
                    </table>
                </div>
                <div class="float-right p-2">
                    <button type="submit" class="btn btn-secondary p-2" @click="ShowInfoMyWork()">Đóng</button>
                </div>
            </div>
        </div>
        <div class="mt-4">
            <h4 style="margin-left: 10px;">Danh sách công việc</h4>
            <div style="width:120px; margin: -35px 0px 0px 240px;">
                <select class="form-select form-select-sm" aria-label=".form-select-sm example" v-model="option">
                    <option value="1">Đang làm</option>
                    <option value="2">Hoàn thành</option>
                    <option value="3">Quá hạn</option>
                </select>
            </div> &emsp;
            <button class="btn btn-outline-secondary" @click="handleShowFilter()" type="button" data-toggle="collapse"
                data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample1"
                style="float:right; margin:  -35px 10px 0px 0px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-funnel"
                    viewBox="0 0 16 16">
                    <path
                        d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2zm1 .5v1.308l4.372 4.858A.5.5 0 0 1 7 8.5v5.306l2-.666V8.5a.5.5 0 0 1 .128-.334L13.5 3.308V2h-11z" />
                </svg> Tạo bộ lọc
            </button>
            <button class="btn btn-outline-info" @click="ShowInfoMyWork()" type="button" data-toggle="collapse"
                data-target="#collapseExample_1" aria-expanded="false" aria-controls="collapseExample"
                style="margin:  -140px 10px 0px 0px;"> Thông tin thời gian
            </button><br>
            <table v-if="option == 1" class="table-striped table-responsive table-hover result-point"
                style="width:99%; margin: 0px 0px 0px 10px">
                <thead class="point-table-head">
                    <tr style="text-align: center;">
                        <th scope="col">STT1</th>
                        <th scope="col" width="400px">Tên công việc</th>
                        <th scope="col" width="250px">Dự án</th>
                        <th scope="col" width="180px">Bắt đầu</th>
                        <th scope="col">Thời lượng (Giờ)</th>
                        <th scope="col" width="180px">Kết thúc</th>
                        <th scope="col" width="180px">Bắt đầu thực tế</th>
                        <th scope="col">Thời lượng thực tế (Giờ)</th>
                        <th scope="col" width="180px">Kết thúc thực tế</th>
                        <th scope="col" width="120px">Trạng thái</th>
                        <th scope="col" width="120px">Thao tác</th>
                    </tr>
                </thead>
                <tr v-for="item in list" :key="item.name_task" style="text-align:center;">
                    <td>1</td>
                    <td scope="row" style="text-align:left;">{{ item.name_task }}</td>
                    <td style="text-align:left;">{{ item.project_name }}</td>
                    <td>{{ item.time_start_expected }}</td>
                    <td>{{ item.time_expected }}</td>
                    <td>{{ item.time_end_expected }}</td>
                    <td>{{ item.time_start_real }}</td>
                    <td>{{ item.time_real }}</td>
                    <td>{{ item.time_end_real }}</td>
                    <td></td>
                    <td>
                        <select class="form-select form-select-sm" aria-label=".form-select-sm example">
                            <option value="1">Bắt đầu</option>
                            <option value="2">Tạm dừng</option>
                            <option value="3">Kết thúc</option>
                        </select>
                    </td>
                </tr>
            </table>
            <table v-if="option == 2" class="table-striped table-responsive table-hover result-point"
                style="width:99%; margin: 0px 0px 0px 10px">
                <thead class="point-table-head">
                    <tr style="text-align: center;">
                        <th scope="col">STT2</th>
                        <th scope="col" width="400px">Tên công việc</th>
                        <th scope="col" width="250px">Dự án</th>
                        <th scope="col" width="180px">Bắt đầu</th>
                        <th scope="col">Thời lượng (Giờ)</th>
                        <th scope="col" width="180px">Kết thúc</th>
                        <th scope="col" width="180px">Bắt đầu thực tế</th>
                        <th scope="col">Thời lượng thực tế (Giờ)</th>
                        <th scope="col" width="180px">Kết thúc thực tế</th>
                        <th scope="col" width="120px">Trạng thái</th>
                        <th scope="col" width="120px">Thao tác</th>
                    </tr>
                </thead>
                <tr v-for="item in list" :key="item.name_task" style="text-align:center;">
                    <td>1</td>
                    <td scope="row" style="text-align:left;">{{ item.name_task }}</td>
                    <td style="text-align:left;">{{ item.project_name }}</td>
                    <td>{{ item.time_start_expected }}</td>
                    <td>{{ item.time_expected }}</td>
                    <td>{{ item.time_end_expected }}</td>
                    <td>{{ item.time_start_real }}</td>
                    <td>{{ item.time_real }}</td>
                    <td>{{ item.time_end_real }}</td>
                    <td></td>
                    <td>
                        <select class="form-select form-select-sm" aria-label=".form-select-sm example">
                            <option value="1">Bắt đầu</option>
                            <option value="2">Tạm dừng</option>
                            <option value="3">Kết thúc</option>
                        </select>
                    </td>
                </tr>
            </table>
            <table v-if="option == 3" class="table-striped table-responsive table-hover result-point"
                style="width:99%; margin: 0px 0px 0px 10px">
                <thead class="point-table-head">
                    <tr style="text-align: center;">
                        <th scope="col">STT3</th>
                        <th scope="col" width="400px">Tên công việc</th>
                        <th scope="col" width="250px">Dự án</th>
                        <th scope="col" width="180px">Bắt đầu</th>
                        <th scope="col">Thời lượng (Giờ)</th>
                        <th scope="col" width="180px">Kết thúc</th>
                        <th scope="col" width="180px">Bắt đầu thực tế</th>
                        <th scope="col">Thời lượng thực tế (Giờ)</th>
                        <th scope="col" width="180px">Kết thúc thực tế</th>
                        <th scope="col" width="120px">Trạng thái</th>
                        <th scope="col" width="120px">Thao tác</th>
                    </tr>
                </thead>
                <tr v-for="item in list" :key="item.name_task" style="text-align:center;">
                    <td>1</td>
                    <td scope="row" style="text-align:left;">{{ item.name_task }}</td>
                    <td style="text-align:left;">{{ item.project_name }}</td>
                    <td>{{ item.time_start_expected }}</td>
                    <td>{{ item.time_expected }}</td>
                    <td>{{ item.time_end_expected }}</td>
                    <td>{{ item.time_start_real }}</td>
                    <td>{{ item.time_real }}</td>
                    <td>{{ item.time_end_real }}</td>
                    <td></td>
                    <td>
                        <select class="form-select form-select-sm" aria-label=".form-select-sm example">
                            <option value="1">Bắt đầu</option>
                            <option value="2">Tạm dừng</option>
                            <option value="3">Kết thúc</option>
                        </select>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</template>

<script>

export default {
    name: "MyWork",
    el: '#infoMyWork',
    data() {
        return {
            option: 1,
            toggle: false,
            show: false,
            list: [
                {
                    "id": 1,
                    "name_task": "Tìm kiếm model",
                    "time_start_expected": "01-06-2022",
                    "time_expected": "1",
                    "time_end_expected": "01-06-2022",
                    "project_name": "Beach War Version 0.1.3",
                    "time_start_real": "01-06-2022",
                    "time_real": "0.6",
                    "time_end_real": "01-06-2022"
                },
                {
                    "id": 2,
                    "name_task": "Design model",
                    "time_start_expected": "01-06-2022",
                    "time_expected": "1",
                    "time_end_expected": "01-06-2022",
                    "project_name": "Beach War Version 0.1.3",
                    "time_start_real": "01-06-2022",
                    "time_real": "0.6",
                    "time_end_real": "01-06-2022"
                },
                {
                    "id": 3,
                    "name_task": "Optimize Model",
                    "time_start_expected": "01-06-2022",
                    "time_expected": "1",
                    "time_end_expected": "01-06-2022",
                    "project_name": "Beach War Version 0.1.3",
                    "time_start_real": "01-06-2022",
                    "time_real": "0.6",
                    "time_end_real": "01-06-2022"
                },
                {
                    "id": 4,
                    "name_task": "Animation model",
                    "time_start_expected": "01-06-2022",
                    "time_expected": "1",
                    "time_end_expected": "01-06-2022",
                    "project_name": "Beach War Version 0.1.3",
                    "time_start_real": "01-06-2022",
                    "time_real": "0.6",
                    "time_end_real": "01-06-2022"
                }
            ],
            showModal: false,
            showMyWork: true,
            showFilter: false,
            showInfoMyWork: false,
        }
    },
    methods: {

        handleShowFilter() {
            this.showFilter = !this.showFilter
        },
        ShowInfoMyWork() {
            this.showInfoMyWork = !this.showInfoMyWork
        },

    }
}
</script>

<style scoped lang="scss">

table table-bordered mt-5 {
    background: #fff;
    border: 1px solid #999999;
}

table thead tr th {
    padding: 5px;
    border: 1px solid #9b9b9b;
    color: #000;
}

table.table-striped tbody tr:nth-of-type(odd) {
    background: #f9f9f9;
}


.text-left {
    text-align: left !important;
}

table tr td {
    padding: 0px 0px;
    border: 1px solid #999999;
}

table.result-point tr td .fa {
    font-size: 20px;
    position: absolute;
    right: 20px;
}

table tr td {
    padding: 5px 5px;
    border: 1px solid #999999;
}

table.my-work td {
    border:0px;
    padding: 10px 20px 10px 20px;
}

.search-collapse {
    width: 400px;
    box-shadow: 2px 5px 5px #d0d0d0;
    position: absolute;
    right: 10px;
    top: 40px;
    z-index: 9;
    background: #FFFFFF;
    border: 2px;
}
.info-my-work {
    width: 500px;
    box-shadow: 2px 5px 5px #d0d0d0;
    position: absolute;
    left: 15px;
    top: 5px;
    z-index: 9;
    background: #c5c5c5;
    border: 2px;
}


</style>
