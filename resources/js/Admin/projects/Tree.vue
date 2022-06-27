<template>
    <div>
        <draggable
            :value="localValue"
            :group="group"
            v-model="data"
            class="cmp-tree"
            ghost-class="ghost"
            @input="updateValue"
            v-bind="dragOptions"
            @start="drag = true"
            @end="drag = false"
        >
            <TreeNode
                v-for="(item, index) in data"
                :key="index"
                :value="item"
                :group="group"
                @input="updateItem"
                :rowKey="rowKey"
            />
        </draggable>
    </div>
</template>

<script>
import Draggable from "vuedraggable";
import TreeNode from "./Treenode";

export default {
    components: {
        Draggable,
        TreeNode
    },
    props: ['data', 'group', 'rowKey'],
    data() {
        return {
            drag: false,
            localValue: [...this.data]
        };
    },
    computed: {
        treeData: {
            get() {
                return this.data
            },
            set(val) {
                // We should not update original data
            }
        },
        themeClassName() {
            return 'theme--dark';
        },
        hasDefaultSlot() {
            return this.$scopedSlots.hasOwnProperty("body");
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
            this.localValue = [...value];
        }
    },
    methods: {
        updateValue(value) {
            this.localValue = value;
            this.$emit("input", this.localValue);
        },
        updateItem(itemValue) {
            const index = this.localValue.findIndex(v => v[this.rowKey] === itemValue[this.rowKey]);
            this.$set(this.localValue, index, itemValue);
            this.$emit("input", this.localValue);
        }
    }
};
</script>

<style scoped>

.cmp-node {
    display: flex;
    align-items: center;
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
    margin-left: 5px;
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
