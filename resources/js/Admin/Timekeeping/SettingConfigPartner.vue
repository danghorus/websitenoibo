<template>
    <div>
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="face-tab" data-toggle="tab" href="#face">
                    <span class="nav-text">Quản lý FaceId</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="camera-tab" data-toggle="tab" href="#camera" aria-controls="camera">
                    <span class="nav-text">Quản lý Camera</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="connect-tab" data-toggle="tab" href="#connect" aria-controls="connect">
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
                        <tr>
                            <td>123</td>
                            <td>123</td>
                            <td>123</td>
                            <td>123</td>
                            <td>123</td>
                            <td>123</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane" id="camera" role="tabpanel" aria-labelledby="camera-tab">
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
                    <tr>
                        <td>123</td>
                        <td>123</td>
                        <td>123</td>
                        <td>123</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane" id="connect" role="tabpanel" aria-labelledby="connect-tab">
                <div class="form-group">
                    <label><span style="color: red">*</span>Client:</label>
                    <input type="text" class="form-control">
                </div>
                <div class="form-group">
                    <label><span style="color: red">*</span>Secret:</label>
                    <input type="text" class="form-control">
                </div>
                <button class="btn btn-primary">Kết nối</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "SettingConfigPartner"
}
</script>

<style scoped>

</style>
