<template>
   <div>
        <div class="gm-breadcrumb">
            <!--<i class="ion-ios-home gm-home"></i>-->
            <el-breadcrumb separator="/">
                <el-breadcrumb-item to="/task/overlist">任务管理</el-breadcrumb-item>
                <el-breadcrumb-item to="/task/overlist">已完成任务</el-breadcrumb-item>
                <!-- <el-breadcrumb-item v-if="exam.exam_paper.name">{{exam.exam_paper.name}}</el-breadcrumb-item> -->
            </el-breadcrumb>
        </div>
        <div>
            <span>任务状态</span><TaskStatus @change="myChange"></TaskStatus>
            <span>任务类型</span><TaskType @change="myTypeChange"></TaskType>
        </div>
        
        <h3></h3>

        <el-dialog title="详细信息" :visible.sync="isShow">
            <el-input
                placeholder="请输入内容"
                style="margin-top:10px;"
                :disabled="true"> 
                <template slot="prepend"><span style="width:100px;display: block;text-align: right;">名称</span></template>
            </el-input>
            <el-input
                placeholder="请输入内容"
                style="margin-top:10px;"
                :disabled="true">
                <template slot="prepend"><span style="width:100px;display: block;text-align: right;">详情</span></template>
            </el-input>
            <el-input
                placeholder="请输入内容"
                style="margin-top:10px;"
                :disabled="true">
                <template slot="prepend"><span style="width:100px;display: block;text-align: right;">完成秘钥</span></template>
            </el-input>
            <el-input
                placeholder="请输入内容"
                style="margin-top:10px;"
                :disabled="true">
                <template slot="prepend"><span style="width:100px;display: block;text-align: right;">是否匿名</span></template>
            </el-input>
            <el-input
                placeholder="请输入内容"
                style="margin-top:10px;"
                :disabled="true">
                <template slot="prepend"><span style="width:100px;display: block;text-align: right;">预计接受时间</span></template>
            </el-input>
            <el-input
                placeholder="请输入内容"
                style="margin-top:10px;"
                :disabled="true">
                <template slot="prepend"><span style="width:100px;display: block;text-align: right;">接受完成时间</span></template>
            </el-input>
            <el-input
                placeholder="请输入内容"
                style="margin-top:10px;"
                :disabled="true">
                <template slot="prepend"><span style="width:100px;display: block;text-align: right;">支付金额</span></template>
            </el-input>
            <el-input
                placeholder="请输入内容"
                style="margin-top:10px;"
                :disabled="true">
                <template slot="prepend"><span style="width:100px;display: block;text-align: right;">创建用户</span></template>
            </el-input>
            <el-input
                placeholder="请输入内容"
                style="margin-top:10px;"
                :disabled="true">
                <template slot="prepend"><span style="width:100px;display: block;text-align: right;">类型</span></template>
            </el-input>
             <el-input
                placeholder="请输入内容"
                style="margin-top:10px;"
                :disabled="true">
                <template slot="prepend"><span style="width:100px;display: block;text-align: right;">状态</span></template>
            </el-input>

        </el-dialog>
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
            prop="name"
            label="名称"
            width="100">
            </el-table-column>
            <el-table-column
            label="详情"
            width="100">
                <template scope="scope">
                    <el-popover trigger="click" placement="right">
                    <p style="max-width:200px"> {{ scope.row.content }}</p>
                    <div slot="reference" class="name-wrapper">
                        <el-tag style="cursor: pointer">点击查看</el-tag>
                    </div>
                    </el-popover>
                </template>
            </el-table-column>
            <el-table-column
            prop="key"
            label="完成秘钥"
            width="100">
            </el-table-column>
            <el-table-column
            label="是否匿名"
            width="100">
            <template  scope="scope"><span v-if="scope.row.is_hide">是</span><span v-if="!scope.row.is_hide">否</span></template>
            </el-table-column>
            <el-table-column
            label="预计接受时间">
            <template  scope="scope">{{ scope.row.expected_time|date }}</template>
            </el-table-column>
            <el-table-column
            label="接受完成时间">
            <template  scope="scope">{{ scope.row.complete_time|date }}</template>
            </el-table-column>
            <el-table-column
            label="支付金额"
            width="100">
            <template  scope="scope">{{ scope.row.pay_money/100 }}</template>
            </el-table-column>
            <el-table-column
            prop="user_name"
            label="创建用户"
            width="100">
            </el-table-column>
            <el-table-column
            label="类型"
            width="100">
            <template  scope="scope"><span v-if="!scope.row.type">有偿</span><span v-if="scope.row.type">无偿</span></template>    
            </el-table-column>
            <el-table-column
            label="状态" 
            width="100">
            <template  scope="scope">
                <span v-if="scope.row.status==0">
                    <el-tag type="danger">未接受</el-tag>
                </span>
                <span v-if="scope.row.status==1">
                    <el-tag type="warning">已接受</el-tag>
                </span>
                <span v-if="scope.row.status==2">
                    <el-tag type="success">已完成</el-tag>
                </span>
                <span v-if="scope.row.status==3">
                    <el-tag type="primary">已结束</el-tag>
                </span>
                <span v-if="scope.row.status==4">
                    <el-tag type="gray">已取消</el-tag>
                </span>
            </template>
            </el-table-column>
            <el-table-column 
             label="操作"
             style="width:100">
                <template scope="scope">
                    <el-button
                    size="small"
                    type="info"
                    @click="handleShow(scope.$index, scope.row)">查看</el-button>
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
        isShow : false,
        
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
                status : this.status,
                type : this.type,
            };
            axios.post("/task/over",param).then(response =>{
                self.taskAll = response.data.result.data
                console.log(self.taskAll)
                self.paginate_total = response.data.result.total
                self.tableLoading = false
            }).catch(error => {
                self.$message("网络错误")
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
        handleDelete: function (index,row){
            console.log(index,row)
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
        handleShow: function(index,row){
            // console.log(index,row)
            this.isShow = true
            // axios.post("/task/del",{
            //     id : row.id
            // }).then(response =>{
                
            // }).catch(error => {
                
            // })
            
            
        }
    },
    mounted(){
        this.getList()
    }
  }
</script>