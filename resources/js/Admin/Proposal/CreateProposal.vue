<template>
    <div>
        <div class="row">
            <input type="text" v-model="petition_type" hidden>
            <div class="form-group col-lg-6">
                <label for="user_id">Họ và tên</label>
                <multiselect 
                    v-model="user_id" 
                    :options="users" 
                    :value="id" 
                    label="fullname"
                    :close-on-select="true" 
                    :show-labels="true" 
                    placeholder="Vui lòng chọn">
                </multiselect>
            </div>
            <div class="form-group col-lg-6">
                <label for="proposal_start_date">Ngày</label>
                <DatePicker
                    style="width: 100%"
                    v-model="date_from"
                    value-type="format"
                    type="date"
                    placeholder="Vui lòng chọn"
                ></DatePicker>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-lg-6">
                <label for="proposal_start_date">Thời gian bắt đầu</label>
                <DatePicker
                    style="width: 100%"
                    v-model="time_from"
                    value-type="format"
                    format="HH:mm"
                    type="time"
                    placeholder="Vui lòng chọn"
                ></DatePicker>
            </div>
            <div class="form-group col-lg-6" >
                <label for="proposal_start_date">Thời gian kết thúc</label>
                <DatePicker
                    style="width: 100%"
                    v-model="time_to"
                    value-type="format"
                    format="HH:mm"
                    type="time"
                    placeholder="Vui lòng chọn"
                ></DatePicker>
            </div>
            <!--<div class="form-group col-lg-3" style="float: right;">
                <label for="project_code">Loại yêu cầu</label>
                <input type="text" v-model="proposal.petition_type" hidden>
                <select v-model="option" class="form-control">
                    <option value="">Lựa chọn</option>
                    <option value="1">Đi muộn về sớm</option>
                    <option value="2">Nghỉ phép</option>
                    <option value="3">Nghỉ việc</option>
                    <option value="5">Đăng ký làm thêm</option>
                    <option value="6">Đăng ký làm nỗ lực</option>
                </select>
            </div>
            <div class="form-group col-lg-3" v-if="option == 2">
                <label for="project_code">Loại nghỉ phép</label>
                <input type="text" v-model="proposal.type_leave" hidden>
                <select v-model="option1" class="form-control">
                    <option value="">Lựa chọn</option>
                    <option value="1">Nửa ngày(sáng)</option>
                    <option value="2">Nửa ngày(chiều)</option>
                    <option value="3">Một ngày</option>
                    <option value="3">Nhiều ngày</option>
                </select>
            </div>
            <div class="form-group col-lg-3" v-if="option == 2">
                <label for="project_code">Hình thức nghỉ</label>
                <input type="text" v-model="proposal.type_paid" hidden>
                <select v-model="option2" class="form-control">
                    <option value="">Lựa chọn</option>
                    <option value="1">Nghỉ phép có lương</option>
                    <option value="2">Nghỉ phép không lương</option>
                </select>
            </div>-->
        </div>
        <!--<div class="row">
            <div class="form-group col-lg-4">
                <label for="proposal_start_date">Ngày bắt đầu</label>
                <DatePicker
                    style="width: 100%"
                    v-model="proposal.date_from"
                    value-type="format"
                    type="date"
                    placeholder="Vui lòng chọn"
                ></DatePicker>
            </div>
            <div class="form-group col-lg-4" v-if="option == 1">
                <label for="proposal_start_date">Thời gian bắt đầu</label>
                <DatePicker
                    style="width: 100%"
                    v-model="proposal.time_from"
                    value-type="format"
                    format="HH:mm"
                    type="time"
                    placeholder="Vui lòng chọn"
                ></DatePicker>
            </div>
            <div class="form-group col-lg-4" v-if="option == 1">
                <label for="proposal_start_date">Thời gian kết thúc</label>
                <DatePicker
                    style="width: 100%"
                    v-model="proposal.time_to"
                    value-type="format"
                    format="HH:mm"
                    type="time"
                    placeholder="Vui lòng chọn"
                ></DatePicker>
            </div>
            <div class="form-group col-lg-4" v-if="option == 2">
                <label for="proposal_end_date">Ngày kết thúc</label>
                <DatePicker
                    :disabled-date="disabledBeforeProposalStart"
                    style="width: 100%"
                    v-model="proposal.date_to"
                    value-type="format"
                    type="date"
                    placeholder="Vui lòng chọn"
                ></DatePicker>
            </div>
            <div class="form-group col-lg-4" v-if="option == 2">
                <label for="proposal_day">Số công</label>
                <input type="text" disabled v-model="proposal.day" class="form-control" id="proposal_day">
            </div>
        </div>-->
        <div class="form-group">
            <label for="proposal_description">Lý do:</label>
            <textarea v-model="petition_reason" style="width:100%; height:200px;"></textarea>
        </div>      
        <button class="btn btn-primary" @click="saveProposal()"> Tạo mới yêu cầu</button>
    </div>
</template>

<script>
import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import Multiselect from 'vue-multiselect';
import {$get, $post} from "../../ultis";
export default {
    name: "CreateProposal",
    components: { DatePicker, Multiselect },
    props: ['proposalId', 'users', 'groupUsers'],
    data() {
        return {
            option: '',
            option1: '',
            option2: '',
            proposal: {},
            values: [],
            users: [],
            petition_type: 1,
            user_id:'',
            date_from:'',
            time_to:'',
            time_from:'',
            petition_reason:'',
        }
    },
    created() {
    },

    methods: {

        showModalCreateProposal() {
            this.showModal = true;
            $(this.$refs.modalCreateProposal).modal('show');
        },
        closeModalCreateProposal() {
            $(this.$refs.modalCreateProposal).modal('hide');
            this.showModal = false;
        },


        async saveProposal() {

             const res = await $post('/proposals/create', {

                    user_id: this.user_id,
                    petition_type: this.petition_type,
                    date_from: this.date_from,
                    time_from: this.time_from,
                    time_to: this.time_to,
                    petition_reason: this.petition_reason,

                });
                if (res.code == 200) {
                    toastr.success('Tạo yêu cầu thành công');
 
                    this.user_id = '';
                    this.petition_type = '',
                    this.time_from = '';
                    this.time_to = '';
                    this.date_from = '';
                    this.petition_reason = '';

                    this.showModal = false;
                    this.getListProposals();
                }
        }
    },
    watch: {

        'proposal.date_from': function (newVal) {
            if (this.proposal.date_to && newVal) {
                let dateFrom = new Date(newVal);
                let dateTo = new Date(this.proposal.date_to);
                let diffTime = Math.abs(dateTo - dateFrom);
                this.proposal.day = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
            }
        },
        'proposal.date_to': function (newVal) {
            if (this.proposal.date_from && newVal) {
                let dateTo = new Date(newVal);
                let dateFrom = new Date(this.proposal.date_from);
                let diffTime = Math.abs(dateTo - dateFrom);
                this.proposal.day = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
            }
        },

        'option': function (newVal) {
            if (newVal) {
                let arrOption = newVal.split(/[. ]/);
                let PetitionType = '';
                arrOption.forEach(item => {
                    PetitionType = PetitionType + item.charAt(0);
                })

                this.proposal.petition_type = PetitionType.toUpperCase();
            } else {
                this.proposal.petition_type = '';
            }
        }
    }
}
</script>

<style scoped>

</style>
