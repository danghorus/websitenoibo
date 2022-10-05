<template>
    <div>
        <div>
            <vue-ads-table
                :columns="columns"
                :rows="list"
                :classes="classes"
                :call-children="getChildTask"
            >
                <template slot="toggle-children-icon" slot-scope="props"><span style="cursor: pointer"> [{{ props.expanded ? '-' : '+' }}] </span></template>
                <template slot="name" slot-scope="props" style="width: 300px">
                    <input style="width:400px; border:0px;"  @change="changeTaskName($event, props.row.id)" v-model="props.row.task_name">
                </template>

                <template slot="start_time_label" slot-scope="props">
                    <div style="display: flex">
                         <input style="width:100%; border:0px;"  @change="changeStartTime($event, props.row.id)" v-model="props.row.start_time">
                    </div>
                </template>

                 <template slot="time_label" slot-scope="props">
                    <div style="display: flex">
                         <input style="width:100%; border:0px;"  @change="changeTime($event, props.row.id)" v-model="props.row.time">
                    </div>
                </template>

                <template slot="weight_label" slot-scope="props">
                    <div style="display: flex">
                         <input style="width:100%; border:0px;"  @change="changeWeight($event, props.row.id)" v-model="props.row.weight">
                    </div>
                </template>
                <template slot="sticker" slot-scope="scope">
                    <div style="display: flex">
                        <select class="form-select form-select-sm" aria-label=".form-select-sm example"
                            @change="changeSticker($event, scope.row.id)" v-model="scope.row.task_sticker">
                            <option value="">Bỏ chọn</option>
                            <option v-for="(sticker, index) in stickers" :key="index" :value="sticker.sticker_name">{{sticker.sticker_name}}</option>
                        </select>
                    </div>
                </template>
                <template slot="priority" slot-scope="scope">
                    <div style="display: flex">
                        <select class="form-select form-select-sm" aria-label=".form-select-sm example"
                            @change="changePerformer($event, scope.row.id)" v-model="scope.row.task_priority">
                            <option v-for="(priority, index) in priorities" :key="index" :value="priority.priority_label">Level {{priority.priority_label}}</option>
                        </select>
                    </div>
                </template>

                <template slot="task_department_label" slot-scope="scope">
                    <div style="display: flex">
                        <select class="form-select form-select-sm" aria-label=".form-select-sm example"
                                @change="changeDepartment($event, scope.row.id)" v-model="scope.row.task_department">
                                <option value="2">Dev</option>
                                <option value="3">Game Design</option>
                                <option value="4">Art</option>
                                <option value="5">Tester</option>
                        </select>
                    </div>
                </template>

                <template slot="task_performer_label" slot-scope="scope">
                    <div style="display: flex">
                        <select class="form-select form-select-sm" aria-label=".form-select-sm example"
                            @change="changePerformer($event, scope.row.id)" v-model="scope.row.task_performer">
                            <option v-for="(user, index) in users" :key="index" :value="user.id">{{user.fullname}}</option>
                        </select>
                    </div>
                </template>
                 <template slot="progress_label" slot-scope="props">
                    <div style="display: flex">
                         <input style="width:100%; border:0px;"  @change="changePerformer($event, props.row.id)" v-model="props.row.progress">
                    </div>
                </template>

                <template slot="man" slot-scope="props">
                    <div style="display: flex">
                        <p @click="showModalEditTask(props.row.id)">
                            <i class="fas fa-pencil-alt" style="cursor: pointer" />
                        </p>
                        <p @click="showModalConfirm(props.row.id)">
                            <i class="fas fa-trash ml-2" style="cursor: pointer" />
                        </p>
                        <p @click="copyTask(props.row.id)">
                            <i class="fas fa-copy ml-2" style="cursor: pointer" />
                        </p>
                        <p @click="showCreateTask(props.row.id)">
                            <i class="fas fa-plus-square ml-2" style="cursor: pointer" />
                        </p>
                    </div>

                </template>
                <template slot="status_template" scope="scope">
                    <template v-if="scope.row.task_department!=null">
                    <div v-if="(currentUser.permission == 1 || currentUser.permission == 2 || currentUser.permission == 3) ">
                        <select class="form-select form-select-sm" aria-label=".form-select-sm example"
                                @change="changeStatus($event, scope.row.id)" v-model="scope.row.status">
                            <option value="0" >Quá hạn</option>
                            <option value="1" >Đang Chờ</option>
                            <option value="2" >Đang tiến hành</option>
                            <option value="3" >Tạm dừng</option>
                            <option value="5" >Chờ feedback</option>
                            <option value="6" >Làm lại</option>
                            <option value="4" >Hoàn thành
                            </option>
                        </select>
                    </div>
                    <div v-else>
                        <p>{{ scope.row.status_title }}</p>
                    </div>
                    </template>
                </template>
                <template slot="progress_template" scope="props">
                    <template v-if="props.row.task_department!=null">
                            <p>{{ props.row.progress_label }}</p>
                    </template>
                </template>
                <template slot="no-rows">Không có dữ liệu</template>
                <template slot="toggle-children-icon" slot-scope="props"> <span style="cursor: pointer"> [{{ props.expanded ? '-' : '+' }}] </span></template>
            </vue-ads-table>
            <div ref="modalConfirm" class="modal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document" style=" max-width: 30%;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Xác nhận xóa công việc</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                    @click="closeModalConfirm()">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <span>Bạn có chắc muốn xóa công việc này?</span><br>
                            <div class="float-right">
                                <button class="btn btn-secondary" @click="closeModalConfirm()">Hủy</button>
                                <button class="btn btn-primary" @click="deleteTask()">Xóa</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div ref="modalUpdateTask" class="modal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document" style=" max-width: 60%;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Sửa Công việc</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                    @click="closeModalEditTask()">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <create-task v-if="showModalEdit" :users="users" :groupUsers="groupUsers" :projectId="projectId"
                                         :priorities="priorities" :stickers="stickers" :projects="projects" :taskId="taskEditId"
                                         @handleGetTasks="handleGetAll" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {$get, $post} from "../../ultis";
import { VueAdsTable } from 'vue-ads-table-tree';
import CreateTask from "./CreateTask";
import _ from "lodash";

export default {
    name: "ListTask",
    components: { VueAdsTable, CreateTask },
    props: ['projectId', 'users', 'groupUsers', 'priorities', 'stickers', 'projects', 'searchProjectId', 'search',
        'startTime', 'taskPerformer', 'taskDepartment','progress', 'status', 'list', 'currentUser', 'bus'],

    data () {
        let classes = {
            table: {
                'table': true
            },
            '1_/0': {
                'vue-ads-cursor-pointer': true
            }
        };

        return {
            classes: classes,
            showModal: false,
            showTimeline: true,
            parentTask: 0,
            taskId: 0,
            showModalEdit: false,
            showModalCreate: false,
            taskEditId: 0,
            parentId: 0,
            columns: [
                {
                    title: '',
                    property: '',
                },
                {
                    title: 'Công việc',
                    property: 'name',
                },
                {
                    title: 'Ngày bắt đầu',
                    property: 'start_time_label',
                },
                {
                    title: 'Thời lượng',
                    property: 'time_label',
                },
                {
                    title: 'Loại CV',
                    property: 'sticker',
                },
                {
                    title: 'Cấp độ',
                    property: 'priority',
                },
                {
                    title: 'Trọng số',
                    property: 'weight_label',
                },
                {
                    title: 'Bộ phận',
                    property: 'task_department_label',
                },
                {
                    title: 'Người thực hiện',
                    property: 'task_performer_label',
                },
				{
                    title: 'Tiến độ',
                    property: 'progress_label',
                },
                {
                    title: 'Trạng thái',
                    property: 'status_template',
                },
                {
                    title: 'Thao tác',
                    property: 'man',
                },
            ],
        }
    },
    mounted() {
        this.bus.$on('submit', this.handleCreateTask)
    },
    methods: {
        handleCreateTask(e) {
            this.closeModalCreateTask();
            if (e.arr_parent.length > 0) {
                let arrIndex = [];
                let listData = _.cloneDeep(this.list);
                e.arr_parent.forEach(item => {
                    let index = _.findIndex(listData, val => val.id == item);
                    arrIndex.push(index);
                    listData = listData[index]._children;
                });
                this.list = this.resetDataAfterCopy([...this.list], arrIndex, 0, {...e.new_task, _meta: {}});
            } else {
                this.list.push(e.new_task);
            }
        },
        handleGetAll(e) {
            this.closeModalEditTask();
            if (e.change_parent) {
                if (e.arr_old_parent.length > 0) {
                    let arrIndex = [];
                    let listData = _.cloneDeep(this.list);
                    e.arr_old_parent.forEach(item => {
                        let index = _.findIndex(listData, val => val.id == item);
                        arrIndex.push(index);
                        listData = listData[index]._children;
                    });
                    this.list = this.resetData([...this.list], arrIndex, 0, e.new_task.id);
                } else {
                    let index = _.findIndex(this.list, val => val.id == e.new_task.id);
                    this.list.splice(index, 1);
                }
                if (e.arr_new_parent.length > 0) {
                    let arrIndex = [];
                    let listData = _.cloneDeep(this.list);
                    e.arr_new_parent.forEach(item => {
                        let index = _.findIndex(listData, val => val.id == item);
                        arrIndex.push(index);
                        listData = listData[index]._children;
                    });
                    this.list = this.resetDataAfterCopy([...this.list], arrIndex, 0, e.new_task);
                } else {
                    this.list.push(e.new_task);
                }
            } else {
                if (e.arr_old_parent.length > 0) {
                    let arrIndex = [];
                    let listData = _.cloneDeep(this.list);
                    e.arr_old_parent.forEach(item => {
                        let index = _.findIndex(listData, val => val.id == item);
                        arrIndex.push(index);
                        listData = listData[index]._children;
                    });
                    this.list = this.resetRow([...this.list], arrIndex, 0, e.new_task);
                } else {
                    let listData = _.cloneDeep(this.list);
                    let indexTask = _.findIndex(listData, val => val.id == e.new_task.id);
                    let tmpTask = listData[indexTask];
                    listData[indexTask] = Object.assign({}, tmpTask, e.new_task);
                    this.list = [...listData];
                }
            }
        },

        sleep (ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        },

        async callRows (indexesToLoad) {
            await this.sleep(300);
            return indexesToLoad.map(index => {
                return {
                    name: 'Call Rows',
                    function: 'Developer',
                    city: 'San Francisco',
                    id: '8196',
                    since: '2010/07/14',
                    budget: '$86,500',
                };
            });
        },

        async callTempRows (filter, columns, start, end) {
            await this.sleep(300);
            return {
                rows: [
                    {
                        name: 'Temp call',
                        function: 'Developer',
                        city: 'San Francisco',
                        id: '8196',
                        since: '2010/07/14',
                        budget: '$86,500',
                    },
                    {
                        name: 'Temp call',
                        function: 'Developer',
                        city: 'San Francisco',
                        id: '8196',
                        since: '2010/07/14',
                        budget: '$86,500',
                    },
                ],
                total: 4,
            };
        },
        async getChildTask(parent) {
            let filters = {
                project_id: parent.project_id,
                parent_task: parent.id,
                search: this.search || '',
                start_time: this.startTime || '',
                task_performer: this.taskPerformer || 0,
                task_department: this.taskDepartment? this.taskDepartment.value : 0,
                status: this.status? this.status.value : -1,
            }

            const res = await $get('/tasks/index', filters);

            if (res.code == 200) {
                return [...res.data];
            }
        },
        showModalConfirm(id) {
            this.showModal = true;
            $(this.$refs.modalConfirm).modal('show');
            this.taskId = id;
        },
        closeModalConfirm() {
            this.showModal = false;
            $(this.$refs.modalConfirm).modal('hide');
            this.taskId = 0;
        },
        showModalEditTask(id) {
            this.showModalEdit = true;
            $(this.$refs.modalUpdateTask).modal('show');
            this.taskEditId = id;
        },
        closeModalEditTask() {
            $(this.$refs.modalUpdateTask).modal('hide');
            this.showModalEdit = false;
            this.taskEditId = 0;
        },
        resetData(tree, arrIndex, index, taskId) {
            if (index < arrIndex.length - 1) {
                this.resetData([...tree[arrIndex[index]]._children], arrIndex, index + 1, taskId);
            } else {
                let indexTask = _.findIndex(tree[arrIndex[index]]._children, val => val.id == taskId);
                tree[arrIndex[index]]._children.splice(indexTask, 1);
            }
            return tree;
        },
        resetRow(tree, arrIndex, index, task) {
            if (index < arrIndex.length - 1) {
                this.resetRow([...tree[arrIndex[index]]._children], arrIndex, index + 1, task);
            } else {
                let indexTask = _.findIndex(tree[arrIndex[index]]._children, val => val.id == task.id);
                let tmpTask = tree[arrIndex[index]]._children[indexTask];
                tree[arrIndex[index]]._children[indexTask] = Object.assign({}, tmpTask, task);
            }
            return tree;
        },
        async deleteTask() {
            const res = await $post(`/tasks/delete/${this.taskId}`);
            if (res.code == 200) {
                toastr.success('Xóa thành công');
                this.showModal = false;
                $(this.$refs.modalConfirm).modal('hide');
                this.taskId = 0;
                if (res.arr_parent.length > 0) {
                    let arrIndex = [];
                    let listData = _.cloneDeep(this.list);
                    res.arr_parent.forEach(item => {
                        let index = _.findIndex(listData, val => val.id === item);
                        arrIndex.push(index);
                        listData = listData[index]._children;
                    });
                    this.list = this.resetData([...this.list], arrIndex, 0, res.task_id);
                } else {
                    let index = _.findIndex(this.list, val => val.id == res.task_id);
                    this.list.splice(index, 1);
                }
            }
        },
        resetDataAfterCopy(tree, arrIndex, index, tasks) {
            if (tree[arrIndex[index]]._showChildren || tree[arrIndex[index]]._children.length > 0 || !tree[arrIndex[index]]._hasChildren) {
                if (index < arrIndex.length - 1) {
                    this.resetDataAfterCopy([...tree[arrIndex[index]]._children], arrIndex, index + 1, tasks);
                } else {
                    tasks = {...tasks, _meta: {
                            groupParent: 0,
                            parent: tree[arrIndex[index]] ? tree[arrIndex[index]]._meta.parent + 1 : 0,
                            uniqueIndex: _.uniqueId(),
                            loading: false,
                            visibleChildren: tasks._children,
                            index: tree[arrIndex[index]]._children.length,
                            groupColumn: null,
                            selected: false
                        }};
                    tree[arrIndex[index]]._children.push(tasks);
                }
            }
            return tree;
        },
        async copyTask(id) {
            const res = await $get(`/tasks/copy/${id}`);

            if (res.code == 200) {
                toastr.success('Copy thành công');
                if (res.arr_parent.length > 0) {
                    let arrIndex = [];
                    let listData = _.cloneDeep(this.list);
                    res.arr_parent.forEach(item => {
                        let index = _.findIndex(listData, val => val.id === item);
                        arrIndex.push(index);
                        listData = listData[index]._children;
                    });
                    this.list = this.resetDataAfterCopy([...this.list], arrIndex, 0, res.new_task);
                } else {
                    this.list.push(res.new_task);
                }
            }
        },
        showCreateTask(parentId) {
            this.parentId = parentId;
            this.showModalCreate = true;
            $(this.$refs.modalCreateTask).modal('show');
        },
        closeModalCreateTask() {
            this.showModalCreate = false;
            $(this.$refs.modalCreateTask).modal('hide');
            this.parentId = 0;
        },
        async changeStatus(e, taskId) {
            if (e.target.value) {
                const res = await $post(`/tasks/change-status/${taskId}`, {status: e.target.value});

                if (res.code == 200) {
                    toastr.success(res.message);
                    // this.$emit('getAllTasks');
                }
            }
        },
        async changeTaskName(e, taskId) {
            if (e.target.value) {
                const res = await $post(`/tasks/change-task_name/${taskId}`, { task_name: e.target.value });

                if (res.code == 200) {
                    toastr.success(res.message);
                    //this.getListWorks();
                }
            }
        },
        async changeStartTime(e, taskId) {
            if (e.target.value) {
                const res = await $post(`/tasks/change-start_time/${taskId}`, { start_time: e.target.value });

                if (res.code == 200) {
                    toastr.success(res.message);
                    //this.getListWorks();
                }
            }
        },
        async changeTime(e, taskId) {
            if (e.target.value) {
                const res = await $post(`/tasks/change-time/${taskId}`, { time: e.target.value });

                if (res.code == 200) {
                    toastr.success(res.message);
                    //this.getListWorks();
                }
            }
        },
        async changeWeight(e, taskId) {
            if (e.target.value) {
                const res = await $post(`/tasks/change-weight/${taskId}`, { weight: e.target.value });

                if (res.code == 200) {
                    toastr.success(res.message);
                    //this.getListWorks();
                }
            }
        },
        async changeDepartment(e, taskId) {
            if (e.target.value) {
                const res = await $post(`/tasks/change-department/${taskId}`, { task_department: e.target.value });

                if (res.code == 200) {
                    toastr.success(res.message);
                    //this.getListWorks();
                }
            }
        },
        async changePerformer(e, taskId) {
            if (e.target.value) {
                const res = await $post(`/tasks/change-performer/${taskId}`, { task_performer: e.target.value });

                if (res.code == 200) {
                    toastr.success(res.message);
                    //this.getListWorks();
                }
            }
        },
        async changeProgress(e, taskId) {
            if (e.target.value) {
                const res = await $post(`/tasks/change-progress/${taskId}`, { progress: e.target.value });

                if (res.code == 200) {
                    toastr.success(res.message);
                    //this.getListWorks();
                }
            }
        },
        async changeSticker(e, taskId) {

            const res = await $post(`/tasks/change-sticker/${taskId}`, {task_sticker: e.target.value });

            if (res.code == 200) {
                toastr.success(res.message);
            //this.$emit('getAllTasks');
            }
        },
        async changePriority(e, taskId) {
            const res = await $post(`/tasks/change-priority/${taskId}`, { task_priority: e.target.value });

            if (res.code == 200) {
                toastr.success(res.message);
                //this.getListWorks();
            }
        },

    },
    watch: {
        /*'task.time': function (newVal) {
            if (newVal > 0 && this.task.start_time_day) {
                let dateTime = moment(this.task.start_time_day).add(newVal, 'h').toDate();
                this.task.end_time = moment(dateTime).format('YYYY-MM-DD HH:mm:ss');
            }
        },
        'task.start_time_day': function (newVal) {
            if (newVal && this.task.time) {
                let dateTime = moment(newVal).add(this.task.time, 'h').toDate();
                this.task.end_time = moment(dateTime).format('YYYY-MM-DD HH:mm:ss');
            }
        },*/
        'task.task_sticker': function (newVal) {
            if (newVal && this.task.task_priority) {
                let dateTime = moment(this.task.start_time_day).add(newVal, 'h').toDate();
                this.task.end_time = moment(dateTime).format('YYYY-MM-DD HH:mm:ss');
            }
        },
        'task.task_priority': function (newVal) {
            if (newVal && this.task.task_sticker) {
                let dateTime = moment(newVal).add(this.task.time, 'h').toDate();
                this.task.end_time = moment(dateTime).format('YYYY-MM-DD HH:mm:ss');
            }
        },
        /*'task.task_name': function (newVal) {
            if (newVal) {
                let arrTaskName = newVal.split(' ');
                let taskCode = '';
                arrTaskName.forEach(item => {
                    taskCode = taskCode + item.charAt(0);
                })

                this.task.task_code = taskCode.toUpperCase();
            } else {
                this.task.task_code = '';
            }
        },*/
        /*'tasks': function (newVal) {
            if (this.taskParentId && this.count === 0) {
                this.task.task_parent = _.find(newVal, {id: parseInt(this.taskParentId)});
                this.count = this.count + 1;
            }
        },*/
    }
};
</script>

<style scoped>
.leftAlign {
    text-align: left;
}
</style>
