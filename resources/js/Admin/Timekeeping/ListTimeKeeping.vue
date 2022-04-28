<template>
    <div class="card">
        <div class="card-header">
            <h4>Bảng chấm công</h4>
            <div style="position: absolute; right: 20px; top: 15px">
                <button class="btn btn-success">Checkin</button>
                <button class="btn btn-primary">Xuất file excel</button>
                <button class="btn btn-primary" @click="showModalConfig()">Cấu hình</button>
            </div>

        </div>
        <div class="card-body table-responsive">
            <select class="form-select col-lg-2" style="position: absolute; right: 20px; top: 80px" v-model="option" @change="changeOption()">
                <option value="1">Theo tuần</option>
                <option value="2">Theo tháng</option>
                <option value="3">
                    <button data-toggle="collapse"  data-target="#collapseOtherTime"
                            aria-expanded="false" aria-controls="collapseOtherTime">Tùy chọn</button>
                </option>
            </select>
            <div>

            </div>
            <table class="table table-bordered mt-5">
                <thead class="table-active">
                <tr>
                    <th style="width: 15%">
                        <input type="text" name="search" class="form-control mb-2 input-search" id="inlineFormInput" placeholder="Tìm kiếm">
                    </th>
                    <th v-for="label in labels">{{ label }}</th>
                </tr>
                </thead>
            </table>
            <div>
                <p><i class="fa fa-circle" style="color: red"></i>  Chưa checkin (checkout)</p>
                <p><i class="fa fa-circle" style="color: green"></i>  Chấm công đầy đủ</p>
                <p><i class="fa fa-circle" style="color: yellow"></i>  Chấm công muộn</p>
            </div>
        </div>
        <div>
            <div ref="modalDetail" class="modal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document" style="  max-width: 80%;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Chi tiết chấm công: Lê Văn Duy - Ngày 22/04/2022</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="closeModal()">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <ModalDetailTimeKeepingInDay v-if="showDetail" />
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div>
            <div ref="modal" class="modal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document" style=" max-width: 80%;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Cấu hình chấm công</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="closeModalConfig()">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <ModalConfigTimeKeeping v-if="modalConfig" />
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="collapse" id="collapseOtherTime" v-if="showOtherTime">
            <div class="card card-body collapse-edit">
                <div class="row">
                    <button class="btn btn-primary col-6">Cập nhật</button>
                    <button class="btn btn-default col-6">Đóng</button>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
import ModalDetailTimeKeepingInDay from "./ModalDetailTimeKeepingInDay";
import ModalConfigTimeKeeping from "./ModalConfigTimeKeeping";
import {$get} from "../../ultis";

export default {
    name: "ListTimeKeeping",
    components: {
        ModalDetailTimeKeepingInDay, ModalConfigTimeKeeping
    },
    data() {
        return {
            showDetail: false,
            modalConfig: false,
            showOtherTime: false,
            option: 1,
            labels: [],
            start_date: '',
            end_date: ''
        }
    },
    created() {
        this.getTimeKeepings();
    },
    methods: {
        async getTimeKeepings() {
            if (this.option != 3 || (this.option == 3 && this.start_date != '' && this.end_date != '')) {
                let params = {
                    option: this.option
                };
                const res = await $get('/api/time-keeping/get', {...params});
                if (res.code === 200) {
                    this.labels = res.labels;
                }
            }
        },
        changeOption() {
            if (this.option === 3) {
                this.showOtherTime = true;
            } else {
                this.start_date = '';
                this.end_date = '';
                this.getTimeKeepings();
            }
        },
        showModal() {
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
        }
    }
}
</script>

<style scoped>

</style>
