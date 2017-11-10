<template>
    <div>
        <div class="gm-breadcrumb">
            <!--<i class="ion-ios-home gm-home"></i>-->
            <el-breadcrumb separator="/">
                <el-breadcrumb-item>失物招领</el-breadcrumb-item>
                <el-breadcrumb-item>物品列表</el-breadcrumb-item>
                <!-- <el-breadcrumb-item v-if="exam.exam_paper.name">{{exam.exam_paper.name}}</el-breadcrumb-item> -->
            </el-breadcrumb>
        </div>
        <el-form :inline="true">

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
                    label="内容"
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
                    label="图片">
                <template slot-scope="scope">
                    <div @click="show_img(scope.row.img_path)">
                        <el-tag style="cursor: pointer">点击查看</el-tag>
                    </div>
                </template>
            </el-table-column>
            <el-table-column
                    label="创建人"
                    prop="user_name">
            </el-table-column>
            <el-table-column
                    label="物品状态">
                <template slot-scope="scope">
                    <el-tag style="cursor: pointer" v-if="scope.row.is_lost==1">丢失</el-tag>
                    <el-tag style="cursor: pointer" v-else>捡到</el-tag>
                </template>
            </el-table-column>
            <el-table-column
                    label="进度">
                <template slot-scope="scope">
                    <el-tag style="cursor: pointer" v-if="scope.row.is_lost==1">已完成</el-tag>
                    <el-tag style="cursor: pointer" v-else>未完成</el-tag>
                </template>
            </el-table-column>
            <el-table-column
                    label="手机号"
                    prop="phone_num">
            </el-table-column>
            <el-table-column
                    label="酬劳"
                    prop="reward_content">
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
        <el-dialog
                title="提示"
                :visible.sync="dialogVisible"
                >
            <el-carousel indicator-position="outside">
                <el-carousel-item v-for="item in img" :key="item">
                    <img :src="item.img">
                </el-carousel-item>
            </el-carousel>

        </el-dialog>
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
                paginate_total: 0,
                img: [],
                dialogVisible: false,
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
                axios.post("admin/loafo/list",param).then(response =>{

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
            show_img (img_path) {
                this.img = []
                if(img_path != "") {
                    var img = img_path.split(";")
                    for (var i = 0; i < img.length; i++) {
                        this.img.push({
                            img: '/show_img/?name=app/lost/min/' + img[i]
                        });
                    }
                    console.log(this.img)
                } else {
                    this.img.push({
                        img: '/img/wx/nopic.png'
                    });
                }
                this.dialogVisible = true
                console.log(this.dialogVisible)
            },
            handleDelete: function(row){
                // console.log(index,row)


                this.$confirm('此操作将永久删除该文件, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    axios.post("admin/loafo/del",{
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