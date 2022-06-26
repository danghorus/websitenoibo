<template>
    <div class="cmp-tree">
        <div class="cmp-node">
            <div @click="getChildTask()" style="width: 25%; display: flex">
                <span>{{value.task_name}}</span>
                <div v-if="hasChildren" class="arrow-right" :class="{ 'active': open }"></div>
            </div>
            <span style="width: 10%">{{ value.start_time }}</span>
            <span style="width: 10%">{{ value.time }}</span>
            <span style="width: 15%">{{ value.project_name }}</span>
            <span style="width: 10%">{{ value.department_label }}</span>
            <span style="width: 15%">{{ value.fullname }}</span>
            <span style="width: 10%">{{ value.sport }}</span>
            <span style="width: 5%">+</span>
        </div>
        <div v-if="open">
            <draggable
                :value="value.children"
                ghost-class="ghost"
                @input="updateValue"
                :group="group"
                tag="ul"
                v-bind="dragOptions"
                @start="drag = true"
                @end="drag = false"
            >
                <tree-node
                    v-for="(item,index) in value.children"
                    :key="index"
                    :value="item"
                    @input="updateChildValue"
                    :group="group"
                    :rowKey="rowKey"
                >
                    <span>{{item.task_name}}</span>
                </tree-node>
            </draggable>
        </div>
    </div>

</template>

<script>
import Draggable from "vuedraggable";
import {$get} from "../../ultis";

export default {
    name: "TreeNode",
    components: {
        Draggable
    },
    props: ['value','root','group','rowKey'],
    data() {
        return {
            open: false,
            drag: false,
            localValue: Object.assign({}, this.value)
        };
    },
    computed: {
        hasChildren() {
            return (this.value.total_child != null && this.value.total_child > 0) || (this.value.children != null && this.value.children.length > 0);
        },
        isDark() {
            return "";
        },
        hasDefaultSlot() {
            // return this.$scopedSlots.hasOwnProperty("body");
        },
        dragOptions() {
            return {
                animation: 200,
                group: "description",
                disabled: false,
                ghostClass: "ghost"
            };
        }
    },
    watch: {
        value(value) {
            this.localValue = Object.assign({}, value);
        }
    },
    methods: {
        updateValue(value) {
            if (value.constructor == Array) {
                this.localValue.children = [...value];
                this.$emit("input", this.localValue);
            }
        },
        updateChildValue(value) {
            console.log(value, 'value');
            const index = this.localValue.children.findIndex(c => c[this.rowKey] === value[this.rowKey]);
            console.log(index, 'index');
            this.$set(this.localValue.children, index, value);
            this.$emit("input", this.localValue);
        },
        handleChange() {
            if (this.isFolder) {
                //
                console.log("test");
            }
            return;
        },
        async getChildTask() {
            if (!this.open) {
                let filters = {
                    project_id: this.localValue.project_id,
                    parent_task: this.localValue.id
                }
                const res = await $get('/tasks/index', filters);

                if (res.code == 200) {
                    this.localValue.children = res.data;
                    this.$emit("input", this.localValue);
                }
            }
            this.open = !this.open;
        }
    }
};
</script>

<style>

.cmp-node {
    display: flex;
    align-items: center;
    border-bottom: 1px solid #666;
    padding-top: 5px;
    padding-bottom: 5px;
}

.cmp-node:hover {
    background-color: #ecf0f1;
}
.cmp-drag-node {
    background-color: #1abc9c;
    opacity: 0.7;
}

.disabled {
    pointer-events: none;
    opacity: 0.3;
    background: #bdc3c7;
}

.arrow-right {
    width: 0;
    height: 0;
    margin-left: 10px;
    border-top: 5px solid transparent;
    border-bottom: 5px solid transparent;
    border-left: 6px solid #666;
    transition: 0.3s ease-in-out;
}

.active {
    transform: rotate(90deg);
    -webkit-transform: rotate(90deg);
    -ms-transform: rotate(90deg);
}

@keyframes spin {
    to {
        -webkit-transform: rotate(360deg);
    }
}
@-webkit-keyframes spin {
    to {
        -webkit-transform: rotate(360deg);
    }
}
</style>
