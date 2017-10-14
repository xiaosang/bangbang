<template>
    <div>
        <div class="gm-breadcrumb">
            <i class="ion-ios-home gm-home"></i>
            <el-breadcrumb separator="/">
                <el-breadcrumb-item>用户管理</el-breadcrumb-item>
                <el-breadcrumb-item>用户列表</el-breadcrumb-item>
            </el-breadcrumb>
        </div>

        <el-form :inline="true">
            <el-form-item>
                <el-select v-model="status" @change="getList">
                    <el-option
                            v-for="item in options" :key="item.value"
                            :label="item.label"
                            :value="item.value">
                    </el-option>
                </el-select>
            </el-form-item>

            <el-form-item>
                <el-select v-model="score" @change="getList">
                    <el-option
                            v-for="item in credit_score" :key="item.value"
                            :label="item.label"
                            :value="item.value">
                    </el-option>
                </el-select>
            </el-form-item>

            <el-form-item>
                <el-input v-model="input" placeholder="学号、姓名"
                          icon="search" :on-icon-click="getList"
                          @keyup.enter.native="getList">

                </el-input>
            </el-form-item>
        </el-form>

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
            label="姓名">
            </el-table-column>
            <el-table-column
                    prop="student_code"
                    label="学号">
            </el-table-column>
            <el-table-column
            label="电话"
            prop="phone">
            </el-table-column>
            <el-table-column
                    label="身份证"
                    prop="code">
                <template slot-scope="scope">
                    {{ scope.row.code | hide_code(4,14) }}
                </template>
            </el-table-column>

            <el-table-column
            prop="credit_score"
            label="信誉度">
            </el-table-column>

            <el-table-column
            label="操作">
            <template  slot-scope="scope">
                <el-button
                    size="small"
                    type="danger" v-if="scope.row.status==0"
                    @click="handleStatus(scope.row)">禁用</el-button>
                <el-button
                size="small"
                type="success" v-else
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
                options: [{
                    value: -1,
                    label: '全部'
                },{
                    value: 0,
                    label: '已激活'
                }, {
                    value: 1,
                    label: '已禁用'
                }],
                status: -1,
                input: '',
                pagination: {
                    current: 1,
                    total: 0,
                    pagesize: 50
                },
                userList: [],
                tableLoading: false,
                credit_score: [{
                    value: 0,
                    label: '信誉度'
                },{
                    value: 1,
                    label: '60分以上'
                },{
                    value: 2,
                    label: '60分一下'
                }],
                score: 0,
            }
        },
        methods:{
            getList(){
                this.tableLoading = true
                var self = this
                var param = {
                    page_size: this.pagination.pagesize,
                    page: this.pagination.current,
                    status: this.status,
                    input: this.input,
                    score: this.score,
                };
                axios.post("/admin/user/list",param).then(response =>{
                    self.userList = response.data.result.data
                    console.log(self.userList)
                    self.total = response.data.result.total
                    self.tableLoading = false
                }).catch(error => {
                    this.$message("网络错误")
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
                var param = {
                    id: row.id,
                    status: (row.status+1)%2
                };
                axios.post("/admin/user/status",param).then(response =>{
//                    if(response.data.code == 0)
//                        row.status = param.status
                    this.$message({
                        type: 'info',
                        message: response.data.msg
                    });
                    this.getList()
                }).catch(error => {
                    this.$message("网络错误")
                })
            }
        },
        mounted(){
            this.getList()
        },

    }
</script>
