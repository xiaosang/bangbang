
<template>
   <div>
        <div class="gm-breadcrumb">
            <!--<i class="ion-ios-home gm-home"></i>-->
            <el-breadcrumb separator="/">
                <el-breadcrumb-item to="/order/payorder">订单管理</el-breadcrumb-item>
                <el-breadcrumb-item to="/order/payorder">支付订单</el-breadcrumb-item>
                <!-- <el-breadcrumb-item v-if="exam.exam_paper.name">{{exam.exam_paper.name}}</el-breadcrumb-item> -->
            </el-breadcrumb>
        </div>
        <div>
            <span>订单状态</span><TaskStatus @change="myChange"></TaskStatus>
            <span>订单类型</span><TaskType @change="myTypeChange"></TaskType>
            <span>订单时间
                <el-date-picker
                    v-model="dateRange"
                    type="daterange"
                    placeholder="选择日期范围">
                </el-date-picker>
                <el-button
                    size="small"
                    type="info"
                    @click="getDate">查询</el-button>
            </span>
        </div>
        
        <h3></h3>
        <el-table
            :data="orderAll"
            border
            style="width: 100%;"
            v-loading="tableLoading">
            <el-table-column
            type="index"
            width="50">
            </el-table-column>
            <el-table-column
            prop="order_code"
            label="订单编号">
            </el-table-column>
            <el-table-column
            label="类型"
            width="70">
            <template  scope="scope"><span v-if="scope.row.type">出账</span><span v-if="!scope.row.type">入账</span></template>
            </el-table-column>
            <el-table-column
            label="是否支付"
            width="100">
            <template  scope="scope"><span v-if="scope.row.is_pay">是</span><span v-if="!scope.row.is_pay">否</span></template>
            </el-table-column>
            <el-table-column
            label="支付时间">
            <template  scope="scope">{{ scope.row.create_time|date}}</template>
            </el-table-column>
            <el-table-column
            label="支付金额"
            width="100">
            <template  scope="scope">{{ scope.row.pay_money/100 }}</template>
            </el-table-column>
            <el-table-column
            prop="user_name"
            label="发布用户"
            width="100">
            </el-table-column>
            
            <el-table-column
            prop="task_name"
            label="任务名称"
            width="100">
            </el-table-column>
            <el-table-column
            label="详情"
            width="100">
                <template scope="scope">
                    <el-popover trigger="click" placement="right">
                    <p style="max-width:200px"> {{ scope.row.task_content }}</p>
                    <div slot="reference" class="name-wrapper">
                        <el-tag style="cursor: pointer">点击查看</el-tag>
                    </div>
                    </el-popover>
                </template>
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
             width="100">
                <template scope="scope">
                    <el-button
                    size="small"
                    type="danger"
                    @click="handleDelete(scope.$index, scope.row)">删除</el-button>
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
        orderAll : [],
        status : -1,
        type : -1,
        tableLoading : false,
        dateRange : '',
        startDate : null,
        endDate : null,
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
                endDate : this.endDate,
                startDate : this.startDate,
            };
            axios.post("/order/payorder",param).then(response =>{
                
                self.orderAll = response.data.result.data
                console.log(self.orderAll)
                self.paginate_total = response.data.result.total
                self.tableLoading = false
            }).catch(error => {
                this.$massage("网络错误")
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
        getDate: function(){
          this.startDate = this.dateRange[0]
          this.endDate = this.dateRange[1]
          this.getList()
        },
        handleDelete: function(index,row){
            // console.log(index,row)
           
                    
                this.$confirm('此操作将永久删除该文件, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    axios.post("/order/paydel",{
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