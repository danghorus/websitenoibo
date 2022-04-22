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
            <select class="form-select col-lg-2" style="position: absolute; right: 20px; top: 80px">
                <option>Theo tuần</option>
                <option>Theo tháng</option>
            </select>
            <table class="table table-bordered mt-5">
                <thead class="table-active">
                <tr>
                    <th style="width: 15%">
                        <input type="text" name="search" class="form-control mb-2 input-search" id="inlineFormInput" placeholder="Tìm kiếm">
                    </th>
                    <th>T2 18/4</th>
                    <th>T3 19/4</th>
                    <th>T4 20/4</th>
                    <th>T5 21/4</th>
                    <th>T6 22/4</th>
                    <th>T7 23/4</th>
                    <th>CN 24/4</th>
                </tr>
                </thead>
                <tr>
                    <th>Lê Văn Duy</th>
                    <th style="font-size: 13px" class="table-success" @click="showModal()">
                        Giờ hành chính:<br>
                        Check in: 08:00:00 <br>
                        Check out: 17:30:00
                    </th>
                    <th style="font-size: 13px" class="table-danger" @click="showModal()">
                        Giờ hành chính:<br>
                        Check in: 08:01:01 <br>
                        Check out: --:--:--
                    </th>
                    <th style="font-size: 13px">
                        Giờ hành chính:<br>
                        Check in: --:--:-- <br>
                        Check out: --:--:--
                    </th>
                    <th style="font-size: 13px">
                        Giờ hành chính:<br>
                        Check in: --:--:-- <br>
                        Check out: --:--:--
                    </th>
                    <th style="font-size: 13px">
                        Giờ hành chính:<br>
                        Check in: --:--:-- <br>
                        Check out: --:--:--
                    </th>
                    <th style="font-size: 13px">
                        Giờ hành chính:<br>
                        Check in: --:--:-- <br>
                        Check out: --:--:--
                    </th>
                    <th style="font-size: 13px">
                        Giờ hành chính:<br>
                        Check in: --:--:-- <br>
                        Check out: --:--:--
                    </th>
                </tr>
                <tr>
                    <th>Bùi Ngọc Đăng</th>
                    <th style="font-size: 13px" class="table-success" @click="showModal()">
                        Giờ hành chính:<br>
                        Check in: 08:00:00 <br>
                        Check out: 17:30:00
                    </th>
                    <th style="font-size: 13px" class="table-warning" @click="showModal()">
                        Giờ hành chính:<br>
                        Check in: 08:01:01 <br>
                        Check out: --:--:--
                    </th>
                    <th style="font-size: 13px">
                        Giờ hành chính:<br>
                        Check in: --:--:-- <br>
                        Check out: --:--:--
                    </th>
                    <th style="font-size: 13px">
                        Giờ hành chính:<br>
                        Check in: --:--:-- <br>
                        Check out: --:--:--
                    </th>
                    <th style="font-size: 13px">
                        Giờ hành chính:<br>
                        Check in: --:--:-- <br>
                        Check out: --:--:--
                    </th>
                    <th style="font-size: 13px">
                        Giờ hành chính:<br>
                        Check in: --:--:-- <br>
                        Check out: --:--:--
                    </th>
                    <th style="font-size: 13px">
                        Giờ hành chính:<br>
                        Check in: --:--:-- <br>
                        Check out: --:--:--
                    </th>
                </tr>
            </table>
            <div>
                <p><i class="fa fa-circle" style="color: red"></i>  Chưa checkin (checkout)</p>
                <p><i class="fa fa-circle" style="color: green"></i>  Chấm công đầy đủ</p>
                <p><i class="fa fa-circle" style="color: yellow"></i>  Chấm công muộn</p>
            </div>
        </div>
        <div>
            <div ref="modal" class="modal" tabindex="-1" role="dialog">
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
    </div>
</template>

<script>
import ModalDetailTimeKeepingInDay from "./ModalDetailTimeKeepingInDay";
import ModalConfigTimeKeeping from "./ModalConfigTimeKeeping";

export default {
    name: "ListTimeKeeping",
    components: {
        ModalDetailTimeKeepingInDay, ModalConfigTimeKeeping
    },
    data() {
        return {
            showDetail: false,
            modalConfig: false
        }
    },
    methods: {
        showModal() {
            this.showDetail = true;
            $(this.$refs.modal).modal('show');
        },
        closeModal() {
            $(this.$refs.modal).modal('hide');
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
