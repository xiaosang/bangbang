<template>
    <div>
        <div class="gm-breadcrumb">
            <!--<i class="ion-ios-home gm-home"></i>-->
            <el-breadcrumb separator="/">
                <el-breadcrumb-item>公告管理</el-breadcrumb-item>
                <el-breadcrumb-item>公告列表</el-breadcrumb-item>
                <!-- <el-breadcrumb-item v-if="exam.exam_paper.name">{{exam.exam_paper.name}}</el-breadcrumb-item> -->
            </el-breadcrumb>
        </div>
        <el-form :inline="true" @keydown.enter.native="getList">
            <el-form-item>
                <router-link :to="{path: '/notice/edit'}">
                    <el-button><i class="ion-plus"></i> 添加公告</el-button>
                </router-link>
            </el-form-item>

            <el-form-item label="关键字">
                <el-input v-model="keyword" placeholder="名称"></el-input>
                <el-input style="display:none;"></el-input>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" icon="search" @click="getList">查询</el-button>
            </el-form-item>
        </el-form>

        <h3></h3>
        <el-table
                :data="taskAll"
                border
                style="width: 100%;"
                v-loading="tableLoading">
            <el-table-column
                    type="index"
                    width="35">
            </el-table-column>
            <el-table-column
                    label="公告详情"
                    prop="content">
                <!--<template slot-scope="scope">-->
                    <!--<el-popover trigger="click" placement="right">-->
                        <!--<p style="max-width:200px"> {{ scope.row.content }}</p>-->
                        <!--<div slot="reference" class="name-wrapper">-->
                            <!--<el-tag style="cursor: pointer">点击查看</el-tag>-->
                        <!--</div>-->
                    <!--</el-popover>-->
                <!--</template>-->
            </el-table-column>

            <el-table-column
                    label="时间">
                <template  slot-scope="scope">{{ scope.row.create_time | date }}</template>
            </el-table-column>

            <el-table-column
                    label="操作"
                    style="width:100">
                <template slot-scope="scope">
                    <el-button
                            size="small"
                            type="danger"
                            @click="handleDelete(scope.row)" icon="delete">删除</el-button>
                    <router-link :to="{path: '/notice/edit',query:{id:scope.row.id}}"><el-button size="small" icon="edit">编辑</el-button></router-link>
                </template>
            </el-table-column>
        </el-table>
        <el-pagination
                style="padding: 1rem 0;"
                @current-change="current_change"
                @size-change="size_change"
                :page-sizes="[5, 10, 20, 50]"
                :page-size="page_size"
                layout="total, sizes, prev, pager, next, jumper"
                :total="paginate_total">
        </el-pagination>
    </div>
</template>

<script>
    import TaskStatus from '../../widget/TaskStatus.vue'
    import TaskType from '../../widget/TaskType.vue'
    export default {
        components: {
            TaskStatus,
            TaskType
        },
        data() {
            return {
                taskAll : [],
                status : -1,
                type : -1,
                tableLoading : false,
                keyword: '',
                // 分页
                page: 1,
                page_size: 5,
                paginate_total: 0
            }
        },

        methods:{
            getList(){
                this.tableLoading = true
                var self = this
                var param = {
                    page_size: this.page_size,
                    page: this.page,
                    keyword : this.keyword,
                };
                axios.post("admin/notice/list",param).then(response =>{

                    self.taskAll = response.data.result.data
                    console.log(self.taskAll)
                    self.paginate_total = response.data.result.total
                    self.tableLoading = false
                }).catch(error => {
                    this.$message("网络错误")
                })
            },
            size_change: function (size) {
                this.page_size = size
                this.getList()
            },
            current_change: function (page) {
                this.page = page
                this.getList()
            },
            myChange: function(value){
                console.log(value)
                this.status = value
                this.getList()
            },
            myTypeChange: function(value){
                this.type = value
                this.getList()
            },
            handleDelete: function(row){
                // console.log(index,row)


                this.$confirm('此操作将永久删除该文件, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    axios.post("admin/notice/del",{
                        id : row.id
                    }).then(response =>{
                        var data = response.data;
                        console.log(data)
                        if (data.code == 0) {
                            //success
                            this.$message({
                                title: '提示',
                                message: '删除成功',
                                type: 'success'
                            });
                            this.getList();
                        } else {
                            this.$message({
                                title: '提示',
                                message: data.msg,
                                type: 'warning'
                            });
                        }
                    }).catch(error => {
                        this.$message("网络错误")
                    })
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消删除'
                    });
                });


            }
        },
        mounted(){
            this.getList()
        }
    }
</script>