<template>
    <form>
        <div class="row">
            <div class="form-group col-lg-9">
                <label for="project_name">Tên dự án</label>
                <input type="text" class="form-control" id="project_name" placeholder="Nhập tên dự án">
            </div>
            <div class="form-group col-lg-3">
                <label for="project_code">Mã dự án</label>
                <input type="text" class="form-control" id="project_code" placeholder="Nhập mã dự án">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-lg-4">
                <label for="project_start_date">Thời gian bắt đầu</label>
                <input type="text" class="form-control" id="project_start_date" placeholder="Nhập thời gian">
            </div>
            <div class="form-group col-lg-4">
                <label for="project_end_date">Thời gian kết thúc</label>
                <input type="text" class="form-control" id="project_end_date" placeholder="Nhập thời gian">
            </div>
            <div class="form-group col-lg-4">
                <label for="project_day">Số ngày</label>
                <input type="text" class="form-control" id="project_day" placeholder="Nhập số ngày">
            </div>
        </div>
        <div class="form-group">
            <label for="project_description">Mô tả dự án</label>
            <textarea id="project_description" class="form-control"/>
        </div>
        <div class="form-group">
            <label for="project_description">Người tham gia</label>
            <select class="form-select">
                <option>Lê Duy1</option>
                <option>Lê Duy2</option>
                <option>Lê Duy3</option>
            </select>
        </div>
        <div class="form-group">
            <label for="project_description">Người thực hiện</label>
            <select class="form-select">
                <option>Lê Duy1</option>
                <option>Lê Duy2</option>
                <option>Lê Duy3</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Tạo mới</button>
    </form>
</template>

<script>
export default {
    name: "CreateProject"
}
</script>

<style scoped>

</style>
