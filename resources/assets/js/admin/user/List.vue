<template>
    <div>
        <div class="gm-breadcrumb">
            <i class="ion-ios-home gm-home"></i>
            <el-breadcrumb separator="/">
                <el-breadcrumb-item to="/task/list">任务管理</el-breadcrumb-item>
                <el-breadcrumb-item to="/task/list">全部任务</el-breadcrumb-item>
            </el-breadcrumb>
        </div>

        <el-table
            :data="userList"
            border
            style="width: 100%;"
            v-loading="tableLoading">
            <el-table-column
            type="index"
            width="50">
            </el-table-column>
            <el-table-column
            prop="name"
            label="姓名"
            width="100">
            </el-table-column>
            <el-table-column
            label="电话"
            prop="phone"
            width="100">
            </el-table-column>
            <el-table-column
            prop="code"
            label="学号">
            </el-table-column>
            <el-table-column
            label="是否匿名"
            width="100">
            <template  scope="scope"><span v-if="scope.row.is_hide">是</span><span v-if="!scope.row.is_hide">否</span></template>
            </el-table-column>
            <el-table-column
            prop="credit_score"
            label="信誉度"
            width="100">
            </el-table-column>

            <el-table-column
            label="操作"
            width="100">
            <template  scope="scope">
                <el-button
                    size="small"
                    type="danger" v-if="scope.row.status==0"
                    @click="handleStatus(scope.row)">禁用</el-button>
                <el-button
                size="small"
                type="danger" v-else
                @click="handleStatus(scope.row)">激活</el-button>
            </template>
            </el-table-column>
        </el-table>
        <el-pagination
                style="padding: 1rem 0;"
                @current-change="current_change"
                @size-change="size_change"
                :page-sizes="[5, 10, 20, 50]"
                :page-size="pagination.pagesize"
                layout="total, sizes, prev, pager, next, jumper"
                :total="pagination.total">
        </el-pagination>
    </div>
</template>
<style>

</style>
<script>


    export default{
        data(){
            return{
                pagination: {
                    current: 1,
                    total: 0,
                    pagesize: 50
                },
                userList: [],
                tableLoading: false
            }
        },
        methods:{
            getList(){
                this.tableLoading = true
                var self = this
                var param = {
                    page_size: this.pagination.pagesize,
                    page: this.pagination.current,
                };
                axios.post("/admin/user/list",param).then(response =>{
                    self.userList = response.data.result.data
                    console.log(self.userList)
                    self.total = response.data.result.total
                    self.tableLoading = false
                }).catch(error => {
                    this.$massage("网络错误")
                })
            },
            size_change: function (size) {
                this.pagination.pagesize = size
                this.getList()
            },
            current_change: function (current) {
                this.pagination.current = current
                this.getList()
            },
            handleStatus (row) {
                console.log(row)
            }
        },
        mounted(){
            this.getList()
        },

    }
</script>
