<template>
    <div>
        <div class="mt-4">
            <h4 style="margin-left: 10px;">Danh sách Warrior</h4>
            <table class="table-striped table-responsive table-hover result-point"
                style="width:99%; margin: 0px 0px 0px 10px">
                <thead class="point-table-head">
                    <tr style="text-align: center;">
                        <th scope="col" width="3%">STT</th>
                        <th scope="col" width="20%">Nhân viên</th>
                        <th scope="col" width="20%">Warrior đăng ký</th>
                        <th scope="col" width="20%">Warrior thực tế</th>
                        <th scope="col" width="15%">Dự án</th>
                        <th scope="col" width="15%">Ngày đăng ký</th>
                        <th scope="col" width="200px">Thao tác</th>
                    </tr>
                </thead>
                <tbody v-for="(war, index) in list" :key="war.id" style="text-align:center;">
                        <tr>
                            <td>{{index + 1 }}</td>
                            <td>{{ war.user_fullname }}</td>
                            <td>{{ war.warrior }}</td>
                            <td>{{ war.warrior_real }}</td>
                            <td>{{ war.project_id}}</td>
                            <td>{{ war.created_at}}</td>
                            <td></td>
                        </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>

import {$get, $post} from "../../ultis";
//import Multiselect from 'vue-multiselect';

export default {
    name: "Warrior",
    components: {},
    data() {
        return {
            
            list: [],
            projects: [],
            project: '',
        }
    },
    created() {
        this.getWarrior();
        this.getProjects();
    },
    methods: {
        async getWarrior() {
            let params = {};

            const res = await $get('/warrior', params);

            if (res.code == 200) {
                this.list = res.tasks;
                this.summary = res.summary
            }
        },

        async getProjects() {
            const res = await $get('/projects/get_all');

            this.projects = res.projects
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
