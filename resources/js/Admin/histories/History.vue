<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.17/vue.min.js"></script>
<template>
    <div class="card">
        <div class="card-header" style="height:50px;">
            <h4>Lịch sử</h4>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered mt-5">
                <thead class="table-active">
                    <tr>
                        <th>#</th>
                        <th>Department</th>
                        <th>Description</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(history, index) in histories" :key="index">
                        <td>{{ index+1 }}</td>
                        <td>{{ histories.id }}</td>
                        <td>{{ history.department }}</td>
                        <td>{{ history.description }}</td>
                        <td>{{ history.created_at }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import 'vue2-datepicker/index.css';
import {$get, $post} from "../../ultis";
import moment from "moment";

export default {
    name: "ListHistory",
    components: { },
    data() {
        return {
        }
    },
    created() {
        this.getHistories();
    },
    methods: {
        async  getHistories(){
            let params = {
            };
            const res = await $get('/histories/get', {...params});
            if (res.code === 200) {

                this.histories = res.histories;

            }
        },
        async getProjects() {
            const res = await $get('/projects/get_all');

            this.histories = res.projects
        },
    }
}
</script>

<style scoped>
table {
    border-collapse: collapse;
    width: 100%;
}

th,
td {
    padding: 8px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

tr:hover {
    background-color: rgb(219, 219, 219);
}

table table-bordered mt-5
{
    background: #fff;
    border: 0px solid #999999;
}

table thead tr th {
    padding: 5px;
    border: 0px solid #9b9b9b;
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
    padding: 5px 5px;
    border: 1px solid #999999;
}

table {
    table-layout: fixed;
}

th,

td {
    padding: 0px 0px;
    border: 1px solid #000;
}

thead {
    background: #f9f9f9;
    display: table;
}

tbody {
    height: 860px;
    overflow: auto;
    display: block;
}
</style>
