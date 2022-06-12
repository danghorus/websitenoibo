<template>
    <form>
        <div class="row">
            <div class="form-group col-lg-9">
                <label for="project_name">Tên công việc</label>
                <input type="text" class="form-control" id="project_name" placeholder="Nhập tên công việc">
            </div>
            <div class="form-group col-lg-3">
                <label for="project_code">Mã công việc</label>
                <input type="text" class="form-control" id="project_code" placeholder="Nhập mã công việc">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-lg-4">
                <label for="project_start_date">Thời gian thực hiện</label>
                <DatePicker
                    style="width: 100%"
                    v-model="start_time"
                    value-type="format"
                    type="datetime"
                    placeholder="Select time"
                ></DatePicker>
            </div>
            <div class="form-group col-lg-4">
                <label for="project_end_date">Thời lượng</label>
                <input type="text" class="form-control" id="project_end_date" placeholder="Nhập thời gian">
            </div>
            <div class="form-group col-lg-4">
                <label for="project_day">Thời gian kết thúc</label>
                <input type="text" class="form-control" id="project_day" disabled>
            </div>
        </div>
        <div class="form-group">
            <label for="project_description">Thông tin công việc</label>
            <editor
                api-key="0pn43qeafddiqh81a9ba9c5abtfey57b1m07tfsa05gir4s3"
                :init="{
                    height: 500,
                    menubar: false,
                    plugins: [
                       'advlist autolink lists link image charmap print preview anchor',
                       'searchreplace visualblocks code fullscreen',
                       'insertdatetime media table paste code help wordcount'
                    ],
                     toolbar:
                       'undo redo | formatselect | bold italic backcolor | \
                       alignleft aligncenter alignright alignjustify | \
                       bullist numlist outdent indent | removeformat | help'
                }"
            />
        </div>
        <div class="row">
            <div class="form-group col-lg-6">
                <label for="project_description">Dộ ưu tiên</label>
                <select class="form-select">
                    <option>Lê Duy1</option>
                    <option>Lê Duy2</option>
                    <option>Lê Duy3</option>
                </select>
            </div>
            <div class="form-group col-lg-6">
                <label for="project_description">Nhãn dán</label>
                <select class="form-select">
                    <option>Lê Duy1</option>
                    <option>Lê Duy2</option>
                    <option>Lê Duy3</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-lg-6">
                <label for="project_description">Bộ phận</label>
                <select class="form-select">
                    <option>Lê Duy1</option>
                    <option>Lê Duy2</option>
                    <option>Lê Duy3</option>
                </select>
            </div>
            <div class="form-group col-lg-6">
                <label for="project_description">Trọng số</label>
                <input type="text" class="form-control" id="project_end_date" placeholder="Nhập trọng số">
            </div>
        </div>
        <div class="form-group">
            <label for="project_description">Công việc tiền nhiệm</label>
            <select class="form-select">
                <option>Lê Duy1</option>
                <option>Lê Duy2</option>
                <option>Lê Duy3</option>
            </select>
        </div>
        <div class="form-group">
            <label for="project_description">Công việc cha</label>
            <select class="form-select">
                <option>Lê Duy1</option>
                <option>Lê Duy2</option>
                <option>Lê Duy3</option>
            </select>
        </div>
        <div class="form-group">
            <label for="project_description">Người thực hiện</label>
            <select class="form-select">
                <option>Lê Duy1 > Duylv 1> Duy 2 (Lê Duy -.....)</option>
                <option>Lê Duy1 > Duylv 1> Duy 2 (Lê Duy -.....)</option>
                <option>Lê Duy1 > Duylv 1> Duy 2 (Lê Duy -.....)</option>
            </select>
        </div>
        <div class="form-group">
            <label for="project_description">Người giám sát</label>
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
import Editor from '@tinymce/tinymce-vue'
import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';

export default {
    name: "CreateTask",
    components: { Editor, DatePicker },
    data() {
        return {
            start_time: ''
        }
    }
}
</script>

<style scoped>

</style>
