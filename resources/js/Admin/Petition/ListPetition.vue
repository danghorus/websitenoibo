<template>
    <div id="layoutSidenav_content" style=" margin-top:-20px;">
        <main>
            <form>
                <div class="container-fluid px-4" >
                    <br>
                    <form>
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item ">
                                <a class="nav-link active " id="tab-unapproved" data-toggle="tab"
                                   href="#content-unapproved" role="tab" aria-controls="content-unapproved" aria-selected="false">
                                    Yêu cầu cần duyệt </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tab-approved" data-toggle="tab"
                                   href="#content-approved" role="tab" aria-controls="content-approved" aria-selected="false">
                                    Đã duyệt </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " id="tab-refuse" data-toggle="tab"
                                   href="#content-refuse" role="tab" aria-controls="content-refuse" aria-selected="true">
                                    Từ chối </a>
                            </li>
                        </ul>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal_petition"
                                style="float:right; margin:-45px 0px 0px 0px;">Tạo yêu cầu</button>
                        <div class="tab-content" id="myTabContent">
                            <!-- Start Yeu Cau Can Duyet -->
                            <div class="tab-pane fade show active" id="content-unapproved" role="tabpanel" aria-labelledby="tab-unapproved">
                                <table class="table-striped table-responsive table-hover result-point">
                                                <thead class="point-table-head">
                                                <tr style="text-align: center;">
                                                    <th style="width:50px">STT</th>
                                                    <th style="width:50px">ID</th>
                                                    <th width=10%>Người yêu cầu</th>
                                                    <th width=15%>Thông tin</th>
                                                    <th width=12%>Ngày giờ bắt đầu</th>
                                                    <th width=12%>Ngày giờ kết thúc</th>
                                                    <th width=20%>Lý do</th>
                                                    <th width=10%>Ngày gửi</th>
                                                    <th width=15%>Thao tác</th>
                                                </tr>
                                                </thead>
                                                <tbody style="text-align: center;">
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td style="text-align: center;">
                                                            <form action="{{ route('petitions.destroy',$petition->id) }}" method="POST">
                                                            </form>
                                                            <form action="{{ route('petitions.destroy',$petition->id) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" onclick="return confirm('Bạn có chắc chắn xoá yêu cầu này?');"
                                                                        class="btn btn-danger" style="font-size:12px;">Xoá</button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                                <tbody style="text-align: center;">
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td style="text-align: center;">
                                                        <nav class="navbar navbar-expand-lg navbar-light bg-light" style="font-size: 20px;">
                                                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                                                <ul class="navbar-nav mr-auto" style="font-size:16px;" >
                                                                    &emsp;&nbsp;
                                                                    <li class="nav-item">
                                                                        <form action="{{ route('petitions.destroy',$petition->id) }}" method="POST">
                                                                        </form>
                                                                        <form method="POST" action="{{ route('petitions.update', $petition->id) }}">
                                                                            @csrf
                                                                            @method('PUT')
                                                                            <input id="petition_status" type="text" name="petition_status" value="2" hidden>
                                                                            <button type="submit" class="btn btn-success" style="font-size:12px;">Duyệt</button>
                                                                        </form>
                                                                    </li> &ensp;
                                                                    <li class="nav-item">
                                                                        <form method="POST" action="{{ route('petitions.update', $petition->id) }}">
                                                                            @csrf
                                                                            @method('PUT')
                                                                            <input id="petition_status" type="text" name="petition_status" value="0" hidden>
                                                                            <button type="submit" class="btn btn-warning" style="font-size:12px;">Từ chối</button>
                                                                        </form>
                                                                    </li> &ensp;
                                                                    <li class="nav-item">
                                                                        <form action="{{ route('petitions.destroy',$petition->id) }}" method="POST">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" onclick="return confirm('Bạn có chắc chắn xoá yêu cầu này?');"
                                                                                    class="btn btn-danger" style="font-size:12px;">Xoá</button>
                                                                        </form>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </nav>
                                                    </td>
                                                </tr>
                                            </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="content-approved" role="tabpanel" aria-labelledby="tab-approved">
                                <table class="table-striped table-responsive table-hover result-point">
                                    <thead class="point-table-head">
                                    <tr style="text-align: center;">
                                        <th style="width:50px">STT</th>
                                        <th style="width:50px">ID</th>
                                        <th width=15%>Người yêu cầu</th>
                                        <th width=20%>Thông tin</th>
                                        <th width=12%>Ngày giờ bắt đầu</th>
                                        <th width=12%>Ngày giờ kết thúc</th>
                                        <th width=20%>Lý do</th>
                                        <th width=12%>Ngày gửi</th>
                                        <th width=7%>Thao tác</th>
                                    </tr>
                                    </thead>
                                            <tbody style="text-align: center;">
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td style="text-align: center;">
                                                        <form action="{{ route('petitions.destroy',$petition->id) }}" method="POST">
                                                        </form>
                                                        <form action="{{ route('petitions.destroy',$petition->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" onclick="return confirm('Bạn có chắc chắn xoá yêu cầu này?');"
                                                                    class="btn btn-danger" style="font-size:12px;">Xoá</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <tbody style="text-align: center;">
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td style="text-align: center;">
                                                        <form action="{{ route('petitions.destroy',$petition->id) }}" method="POST">
                                                        </form>
                                                        <form action="{{ route('petitions.destroy',$petition->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" onclick="return confirm('Bạn có chắc chắn xoá yêu cầu này?');"
                                                                    class="btn btn-danger" style="font-size:12px;">Xoá</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        
                                </table>
                            </div>
                            <!-- End Da Duyet-->
                            <!-- Start Tu Choi -->
                            <div class="tab-pane fade" id="content-refuse" role="tabpanel" aria-labelledby="tab-refuse">
                                <table class="table-striped table-responsive table-hover result-point">
                                    <thead class="point-table-head">
                                    <tr style="text-align: center;">
                                        <th style="width:50px">STT</th>
                                        <th style="width:50px">ID</th>
                                        <th width=15%>Người yêu cầu</th>
                                        <th width=20%>Thông tin</th>
                                        <th width=12%>Ngày giờ bắt đầu</th>
                                        <th width=12%>Ngày giờ kết thúc</th>
                                        <th width=20%>Lý do</th>
                                        <th width=12%>Ngày gửi</th>
                                        <th width=7%>Thao tác</th>
                                    </tr>
                                    </thead>
                                            <tbody style="text-align: center;">
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td style="text-align: center;">
                                                        <form action="{{ route('petitions.destroy',$petition->id) }}" method="POST">
                                                        </form>
                                                        <form action="{{ route('petitions.destroy',$petition->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" onclick="return confirm('Bạn có chắc chắn xoá yêu cầu này?');"
                                                                    class="btn btn-danger" style="font-size:12px;">Xoá</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            </tbody>
                                       
                                            <tbody style="text-align: center;">
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td style="text-align: center;">
                                                        <form action="{{ route('petitions.destroy',$petition->id) }}" method="POST">
                                                        </form>
                                                        <form action="{{ route('petitions.destroy',$petition->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" onclick="return confirm('Bạn có chắc chắn xoá yêu cầu này?');"
                                                                    class="btn btn-danger" style="font-size:12px;">Xoá</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        
                                </table>
                            </div>
                            <!-- End Tu Choi -->
                        </div>
                    </form>
                </div>
            </form>
        </main>
    </div>
</template>

<script>
import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import ModalCreatePetition from "./ModalCreatePetition";
import {$get, $post} from "../../ultis";
import moment from "moment";

export default {
    name: "ListPetition",
    components: {
        ModalCreatePetition, DatePicker
    },
    data() {
        return {
            showDetail: false,
            modalConfig: false,
            option: 1,
            labels: [],
            start_date: '',
            end_date: '',
            search: '',
            data: '',
            userId: '',
            userName: '',
            time: '',
            currentUser: '',
            timeSelected: '',
        }
    },
    created() {
        this.getTimeKeepings();
    },
    methods: {
        async getTimeKeepings() {
            let params = {
                option: this.option,
                search: this.search,
                time: this.timeSelected? moment(this.timeSelected).format('YYYY-MM-DD'): ''
            };
            const res = await $get('/time-keeping/get', {...params});
            if (res.code === 200) {
                this.labels = res.labels;
                this.data = res.data;
                this.currentUser = res.current_user;
                if (res.showBtn && res.showBtn == 'checkin') {
                    this.showCheckIn = true;
                } else if (res.showBtn && res.showBtn == 'checkout') {
                    this.showCheckOut = true;
                }
            }
        },
        changeOption() {
            this.getTimeKeepings();
        },
        showModal(userId, userName, time) {
            this.userId = userId;
            this.userName = userName;
            this.time = time;
            this.showDetail = true;
            $(this.$refs.modalDetail).modal('show');
        },
        closeModal() {
            $(this.$refs.modalDetail).modal('hide');
            this.showDetail = false;
        },
        showModalConfig() {
            this.modalConfig = true;
            $(this.$refs.modal).modal('show');
        },
        closeModalConfig() {
            $(this.$refs.modal).modal('hide');
            this.modalConfig = false;
        },
        async checkIn() {
            const res = await $post('/time-keeping/checkin');
            if (res.code == 200) {
                this.getTimeKeepings();
                if (res.checkin) {
                    this.showCheckOut = true;
                }else {
                    this.showCheckOut = false;
                }

                this.showCheckIn = false;
            }
        },
        async exportData() {

                let option = this.option;
                let search = this.search;
                let time = this.timeSelected? moment(this.timeSelected).format('YYYY-MM-DD'): '';
            // const res = await $get('/time-keeping/export', {...params});

            window.open("/time-keeping/export?option="+option+"&search="+search+"&time="+time,'_blank');
        }
    },
    watch: {
        'option': function (newVal) {
            this.timeSelected = '';
            this.changeOption();
        },
    }
}
</script>

<style scoped>


        table {
            background: #fff;
            border: 1px solid #999999;
        }

        table thead tr th {
            padding: 10px;
            border: 1px solid #9b9b9b;
            color: #000;
        }

        table.table-striped tbody tr:nth-of-type(odd) {
            background: #f9f9f9;
        }


        .text-left {
            text-align: left!important;
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
            padding: 10px 10px;
            border: 1px solid #999999;
        }


</style>
