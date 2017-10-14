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
        <el-table
            :data="taskAll"
            border
            style="width: 100%;"
            @expand="get_evaluate"
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
                <span v-else-if="scope.row.status==1">
                    <el-tag type="warning">已接受</el-tag>
                </span>
                <span v-else-if="scope.row.status==2">
                    <el-tag type="success">已完成</el-tag>
                </span>
                <span v-else-if="scope.row.status==3">
                    <el-tag type="primary">已结束</el-tag>
                </span>
                <span v-else-if="scope.row.status==4">
                    <el-tag type="gray">已取消</el-tag>
                </span>
            </template>
            </el-table-column>
            <el-table-column type="expand">
                <template scope="props">
                    <el-form label-position="left" inline class="demo-table-expand" v-if="exLoading">
                        <!-- <span v-if="scope.row.evaluate">{{ scope.row.evaluate }}fsdhgsdfgsdf</span> -->
                        <!-- <span v-if="scope[0].evaluate.length!=0">{{ scope.evaluate[0].id }}fsdhgsddfgsdf</span> -->
                        <el-form-item label="发布任务用户">
                            <span>{{ props.row.release }}</span>
                        </el-form-item>
                        <el-form-item label="评论">
                            <span>{{ props.row.user_content }}</span>
                        </el-form-item><br />
                        <el-form-item label="分数">
                            <el-rate
                                v-model="props.row.score"
                                disabled
                                show-text
                                text-color="#ff9900"
                                text-template="{value}">
                            </el-rate>
                        </el-form-item><br/>
                        <el-form-item label="接受任务用户">
                            <span>{{ props.row.accept }}</span>
                        </el-form-item>
                        <el-form-item label="评论">
                            <span>{{ props.row.user_content1 }}</span>
                        </el-form-item><br />
                        <el-form-item label="分数">
                            <el-rate
                                v-model="props.row.score1"
                                disabled
                                show-text
                                text-color="#ff9900"
                                text-template="{value}">
                            </el-rate>
                        </el-form-item><br />
                        <!-- <el-form-item label="订单编号">
                            <span>{{ props.row.orderNum }}</span>
                        </el-form-item> -->
                    </el-form>
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
        selTask : [],
        evaluate : [],
        exLoading: false,
        
         // 分页
        page: 1,
        page_size: 5,
        paginate_total: 0
      }
    },

    methods:{
        getList: function(){
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
        get_evaluate: function(row, expanded){
            if(expanded){
                var param = {
                    task_id : row.id
                };
                axios.post("/task/evaluate",param).then(response =>{
                    // this.taskAll[0].evaluate = response.data.result
                    
                    row.score = response.data.result[0].score
                    row.score1 = response.data.result[1].score
                    // row.status = response.data.result[0].status
                    row.user_content = response.data.result[0].content
                    row.release = response.data.result[0].release_user_name
                    row.accept = response.data.result[0].accept_user_name
                    row.user_content1 = response.data.result[1].content
                    // row.orderNum = response.data.result[0].transaction_order_id
                    this.exLoading = true
                // row.accept = response.data.result[1]
                // console.log( this.taskAll.evaluate)
            }).catch(error =>{
                 this.$message("网络错误")
            })

            }
            console.log(row,expanded)
        },
        // conversion: function(type,status){
        //     if(type == 0){
        //         if(status == 0){
        //             return '有偿'
        //         }else{
        //             return '无偿'
        //         }
        //     }else if(type == 1){
        //         if(status == 0){
        //             return '未接受'
        //         }else if(status == 1){
        //             return '已接受'
        //         }else if(status == 2){
        //             return '已完成'
        //         }else if(status == 3){
        //             return '已结束'
        //         }
        //     }else if(type == 2){
        //          if(status == 0){
        //             return '否'
        //         }else if(status == 1){
        //             return '是'
        //         }
        //     }
        // }
    },
    mounted(){
        this.getList()
    }
  }
</script>