<template>
   <div>
        <div class="gm-breadcrumb">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item>论坛管理</el-breadcrumb-item>
                <el-breadcrumb-item>帖子管理</el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <div>
            <el-table
                :data="noteList"
                v-loading='note_loading'
                @expand="get_node_id"
                style="width: 100%">
                <el-table-column type="expand">
                    <template scope="props">
                       <el-table
                            :data="commentList"
                            style="width: 100%"
                            v-loading='comment_loading'>
                            <el-table-column
                                label="内容"
                                prop="content"
                                >
                            </el-table-column>
                            <el-table-column
                                label="评论人"
                                width="180"
                                prop="create_user_name">
                            </el-table-column>
                            <el-table-column
                                label="回复人"
                                width="180">
                                    <template slot-scope="scope">
                                        <span v-if="scope.row.reply_name == null">回复主贴</span><span v-else-if="scope.row.reply_name != null">{{ scope.row.reply_name }}</span>
                                    </template>
                            </el-table-column>
                            <el-table-column
                                label="操作">
                                <template slot-scope="scope">
                                    <el-button
                                    size="small"
                                    type="danger"
                                    @click="handleDelete(scope.$index, scope.row)">删除</el-button>
                                </template>
                            </el-table-column>
                        </el-table>
                        <el-pagination
                            style="padding: 1rem 0;"
                            @current-change="_change"
                            @size-change="change_size"
                            :page-sizes="[5, 10, 20, 50]"
                            :page-size="list_page_size"
                            layout="total, sizes, prev, pager, next, jumper"
                            :total="list_paginate_total">
                        </el-pagination>
                    </template>
                </el-table-column>
                <el-table-column
                    type="index"
                    width="60">
                </el-table-column>
                <el-table-column
                    label="文章标题"
                    prop="name">
                </el-table-column>
                <el-table-column
                    label="文章描述"
                    prop="describe">
                </el-table-column>
                <el-table-column
                    label="文章内容"
                    width="100px">
                    <template slot-scope="scope">
                        <el-popover trigger="click" placement="right">
                        <span style="max-width:400px;max-height:500px;overflow:scroll;display:block" v-html='scope.row.content'>  </span>
                        <div slot="reference" class="name-wrapper">
                            <el-tag style="cursor: pointer">点击查看</el-tag>
                        </div>
                        </el-popover>
                    </template>
                </el-table-column>
                <el-table-column
                    label="阅读数量"
                    prop="read_num"
                    width="100px">
                </el-table-column>
                <el-table-column
                    label="评论条数"
                    prop="comment_count"
                    width="100px">
                </el-table-column>
                <el-table-column
                    label="帖子分类"
                    width="100px">
                    <template slot-scope="scope">
                        <span v-if="scope.row.label == 0">未分类</span>
                        <span v-else-if="scope.row.label == 1">分享</span>
                        <span v-else-if="scope.row.label == 2">讨论</span>
                        <span v-else-if="scope.row.label == 3">提问</span>
                    </template>
                </el-table-column>
                <el-table-column
                    label="操作"
                    width='70px'>
                    <template slot-scope="scope">
                        <el-button
                        size="small"
                        type="danger"
                        @click="noteDelete(scope.$index, scope.row)">删除</el-button>
                    </template>
                </el-table-column>
            </el-table>
        </div>
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
  export default {
    data() {
      return {
        noteList : [],
        commentList : [],
        comment_loading : false,
        note_loading: false,
        note_id: 0,
        // tableLoading : false,
        // isShow : false,
        // selTask : [],
        // evaluate : [],
        // exLoading: false,
        
         // 分页
        page: 1,
        page_size: 5,
        paginate_total: 0,

        list_page: 1,
        list_page_size:5,
        list_paginate_total:0,
      }
    },

    methods:{
        getList: function(){
            this.note_loading = true
             var param = {
                page_size: this.page_size, 
                page: this.page,
            };
            axios.post('/forum/note',param).then(res =>{
                console.log(res.data.result.data)
                this.noteList = res.data.result.data
                this.paginate_total = res.data.result.total
                this.note_loading = false
            }).catch(err => {
                this.$message('网络错误')
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
        _change:function(page){
            this.list_page = page
            this.get_node_id({id:this.note_id},true)
        },
        change_size: function(size){
            this.list_page_size = size
            this.get_node_id({id:this.note_id},true)
        },
        get_node_id:function(row, expanded){
            console.log(row.id)
            this.note_id = row.id
            this.comment_loading = true
            if(expanded){
                var param = {
                    note_id : row.id,
                    page_size: this.list_page_size, 
                    page: this.list_page,
                };
                axios.post('/forum/comment',param).then(res =>{
                    this.commentList = res.data.result.data
                    // this.noteList = res.data.result.data
                    this.list_paginate_total = res.data.result.total
                    this.comment_loading = false
                }).catch(err => {

                })
            }
        },
        handleDelete: function(index,row){
            this.$confirm('此操作将永久删除该文件, 是否继续?', '提示', {
                confirmButtonText: '确定',
                cancelButtonText: '取消',
                type: 'warning'
            }).then(() => {
                axios.post("/forum/commentdel",{
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
        
            
        },
        noteDelete:function(index,row){

            this.$confirm('此操作将永久删除该文件, 是否继续?', '提示', {
                confirmButtonText: '确定',
                cancelButtonText: '取消',
                type: 'warning'
            }).then(() => {
                axios.post("/forum/notedel",{
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