<template>
    <div>
        <div class="mt-4">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Công việc</th>
                        <th scope="col">Thời gian bắt đầu</th>
                        <th scope="col">Thời lượng</th>
                        <th scope="col">Dự án</th>
                        <th scope="col">Bộ phận</th>
                        <th scope="col">Người thực hiện</th>
                        <th scope="col">Trạng thái</th>
                    </tr>
                </thead>
                <tr v-for="item in list" :key="item.name">
                    <td scope="row">{{ item.name }}</td>
                    <td>{{ item.name }}</td>
                    <td>{{ item.sport }}</td>
                    <td>{{ item.sport }}</td>
                    <td>{{ item.sport }}</td>
                    <td>{{ item.sport }}</td>
                    <td>{{ item.name }}</td>
                </tr>
            </table>
        </div>
    </div>
</template>

<script>

export default {
    name: "TimelineTask",
    data() {
        return {
            list: [
                {
                    "id": 1,
                    "name": "Abby",
                    "sport": "basket"
                },
                {
                    "id": 2,
                    "name": "Brooke",
                    "sport": "foot"
                },
                {
                    "id": 3,
                    "name": "Courtenay",
                    "sport": "volley"
                },
                {
                    "id": 4,
                    "name": "David",
                    "sport": "rugby"
                }
            ],
            showModal: false,
            showTimeline: true
        }
    },
    methods: {

    }
}
</script>

<style scoped>

</style>
