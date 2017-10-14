<template>
    <el-select clearable v-model="id_copy" @change="value_change" v-loading="loading" placeholder="请选择任务状态">
        <el-option
            v-for="item in lists"
            :key="item.value"
            :label="item.label"
            :value="item.value">
        </el-option>
    </el-select>
</template>
<script>
    export default {
        data: function () {
            return {
                id_copy: -1,
                lists: [{
                    value: -1,
                    label: '全部任务'
                    },{
                    value: 0,
                    label: '未接受'
                    }, {
                    value: 1,
                    label: '已接受'
                    }, {
                    value: 2,
                    label: '已完成'
                    }, {
                    value: 3,
                    label: '已结束'
                    }, {
                    value: 4,
                    label: '已取消'
                }],
                loading: false
            }
        },
        methods: {
            value_change: function () {
                let value = this.id_copy
                this.$emit("change", value)
            }
        },
    }
</script>