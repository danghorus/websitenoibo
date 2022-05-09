<template>
    <div id="layoutSidenav_content" style=" margin-top:-20px;">
        <main>
            <form>
                <div class="container-fluid px-4" >
                    <br>
                    <form>
                        <button type="button" id="yeucaucanduyet" @click="ModalCreateRequest()">Yêu cầu cần duyệt</button>
                        <button type="button" id="daduyet" @click="ModalCreateRequest()">Đã duyệt</button>
                        <button type="button" id="tuchoi" @click="ModalCreateRequest()">Từ chối</button>
                        <button class="btn btn-primary" @click="ModalCreateRequest()" style="float: right;">Tạo yêu cầu</button>    
                        </form>
                        <br>
                        <table class="table table-bordered" id="yeucaub">
                            <thead>
                                <tr style="text-align: center;">
                                    <th width=3% >STT</th>
                                    <th width=3%>ID</th>
                                    <th width=8%>Người yêu cầu</th>
                                    <th width=10%>Thông tin</th>
                                    <th width=10%>Ngày giờ bắt đầu</th>
                                    <th width=10%>Ngày giờ kết thúc</th>
                                    <th width=15%>Lý do</th>     
                                    <th width=10%>Ngày gửi</th>
                                    <th width=8%>Người duyệt</th>
                                    <th width=10%>Thao tác</th>
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
                                    <td></td>
                                    <td>
                                        <button class="btn btn-success" style="font-size:12px;">Duyệt</button>
                                        <button class="btn btn-danger" style="font-size:12px;">Từ chối</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
            </form>
        </main>
    </div>
</template>

<style scoped>

</style>
