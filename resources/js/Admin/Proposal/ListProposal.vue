<template>
    <div>
        <div class="mt-4">
            <h4 style="margin: -30px 0px 0px 25px;">Danh sách yêu cầu</h4>
            <button class="btn btn-primary float-right" style="width:150px; margin-right: 20px;" type="button"
                data-bs-toggle="dropdown" data-bs-auto-close="true" @click="showModalCreateProposal()">
                Tạo yêu cầu
            </button>
        </br></br>
            <table class="table-striped table-responsive table-hover result-point"
                style="width:99%; margin: 0px 0px 0px 10px">
                <thead class="point-table-head">
                    <tr style="text-align: center;">
                        <th style="width:100px">STT</th>
                        <th width=12%>Người yêu cầu</th>
                        <th width=12%>Loại yêu cầu</th>
                        <th width=25%>Thông tin yêu cầu</th>
                        <th width=20%>Lý do</th>
                        <th width=12%>Ngày gửi</th>
                        <th width=14%>Thao tác</th>
                    </tr>
                </thead>
                <tbody >
                    <tr v-for="(item, index) in list" :key="item.id"  style="text-align:center;">
                        <td width="100px">{{ index +1 }}</td>
                        <td style="width:12%; text-align:left;">{{ item.fullname}}</td>
                        <td style="width:12%; text-align:right;">{{ item.petition_type_title}} {{ item.type_leave_title }} </td>
                        <td style="width:12%; text-align:right;">Ngày {{ item.date_from_title}} từ {{ item.time_from}} đến {{ item.time_to}}</td>
                        <td style="width:12%; text-align:right;">{{ item.petition_reason}}</td>
                        <td style="width:12%; text-align:right;">{{ item.time_send}}</td>
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
                                            <p @click="deleteProposal(item.id)">
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
        <div>
            <div ref="modalCreateProposal" data-bs-backdrop="static" data-bs-keyboard="false" class="modal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document" style=" max-width: 40%;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tạo mới yêu cầu</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                    @click="closeModalCreateProposal()">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <create-proposal :users="users" :groupUsers="groupUsers" @handleCreateProposal="handleCreateProposal"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import { $get, $post } from "../../ultis";
import Multiselect from 'vue-multiselect';
import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import CreateProposal from "./CreateProposal";


export default {
    name: "ListProposal",
    components: {DatePicker, CreateProposal, Multiselect },
    props: ['list','users', 'groupUsers'],
    data() {
        return {
            list: [],
            showModal: false,
            users: [],
            groupUsers: [],
            search: '',
        }
    },
    created() {
        this.getAllUser();
            this.getGroupUsers();
            this.getListProposals();
    },
    methods: {
        async getAllUser() {
            const res = await $get('/user/all_user');
            if (res.code == 200) {
                this.users = res.data;
            }
        },
        async getGroupUsers() {
            const res = await $get('/user/all_user_by_group');
            if (res.code == 200) {
                this.groupUsers = res.data;
            }
        },

        async getListProposals() {
            let params = {};

            const res = await $get('/proposals/list_proposals', params);

            if (res.code == 200) {
                this.list = res.proposals;
            }
        },

        changeOption(){
            this.getListProposals();
        },
        showModalCreateProposal() {
            this.showModal = true;
            $(this.$refs.modalCreateProposal).modal('show');
        },
        closeModalCreateProposal() {
            $(this.$refs.modalCreateProposal).modal('hide');
            this.showModal = false;
        },

        async deleteProposal(id) {
            const res = await $post(`/proposals/delete/${id}`);
            if (res.code == 200) {
                toastr.success('Xóa thành công');
                this.getListProposals();
            }
        }
    }
}
</script>

<style scoped>
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
    border: 0px;
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
