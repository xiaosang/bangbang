<template>
   <div style="min-width:1000px;max-width:1000px">
        <div class="gm-breadcrumb">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item to="/">首页</el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <remote-script src="/js/echarts.min.js" @load="onEcharts"></remote-script>
        <el-row>
          <el-col :span="8">
            <el-card class="box-card">
              <div slot="header" class="clearfix">
                <span style="line-height: 36px;">用户

                </span>
              </div>
              <div id="user" style="height: 100px;">
                <div class="databox-right">
                  <span class="databox-number azure" >全部用户：{{ userCount.all_follow }}&nbsp;&nbsp;昨日新增：{{  userCount.yet_follow }}</span>
                </div>
              </div>
            </el-card>
          </el-col>
          <el-col :span="8">
            <el-card class="box-card">
              <div slot="header" class="clearfix">
                <span style="line-height: 36px;">完成订单量</span>
              </div>
               <div id="orderAll" style="height: 100px;">
                 <el-row type="flex" class="row-bg" justify="space-around">
                    <!-- <el-col :span="4"></el-col> -->
                    <el-col :span="12" class="order-left databox-number azure"><span>昨日完成订单量</span></el-col>
                    <el-col :span="12" class="order-right databox-number azure" ><span v-if="flag">{{ finishOrderNums['yet_num'][0].count }}</span></el-col>
                    <!-- <el-col :span="4"></el-col> -->
                </el-row>
                <el-row type="flex" class="row-bg" justify="space-around">
                    <!-- <el-col :span="4"></el-col> -->
                    <el-col :span="12" class="order-left databox-number azure"><span>今日完成订单量</span></el-col>
                    <el-col :span="12" class="order-right databox-number azure"><span v-if="flag">{{ finishOrderNums['day_num'][0].count }}</span></el-col>
                    <!-- <el-col :span="4"></el-col> -->
                </el-row>
                <el-row type="flex" class="row-bg" justify="space-around">
                    <!-- <el-col :span="4"></el-col> -->
                    <el-col :span="12" class="order-left databox-number azure"><span>总共完成订单量</span></el-col>
                    <el-col :span="12" class="order-right databox-number azure"><span v-if="flag">{{ finishOrderNums['all_num'][0].count }}</span></el-col>
                    <!-- <el-col :span="4"></el-col> -->
                </el-row>
              </div>
            </el-card>
          </el-col>
           <el-col :span="8">
            <el-card class="box-card">
              <div slot="header" class="clearfix">
                <span style="line-height: 36px;">订单总量</span>
              </div>
               <div id="orderAllNum" style="height: 100px;">
                 <el-row type="flex" class="row-bg" justify="space-around">
                    <!-- <el-col :span="4"></el-col> -->
                    <el-col :span="12" class="order-left databox-number azure"><span>昨日订单量</span></el-col>
                    <el-col :span="12" class="order-right databox-number azure" ><span v-if="flag">{{ allOrderNums['yet_num'][0].count }}</span></el-col>
                    <!-- <el-col :span="4"></el-col> -->
                </el-row>
                <el-row type="flex" class="row-bg" justify="space-around">
                    <!-- <el-col :span="4"></el-col> -->
                    <el-col :span="12" class="order-left databox-number azure"><span>今日订单量</span></el-col>
                    <el-col :span="12" class="order-right databox-number azure"><span v-if="flag">{{ allOrderNums['day_num'][0].count }}</span></el-col>
                    <!-- <el-col :span="4"></el-col> -->
                </el-row>
                <el-row type="flex" class="row-bg" justify="space-around">
                    <!-- <el-col :span="4"></el-col> -->
                    <el-col :span="12" class="order-left databox-number azure"><span>总共订单量</span></el-col>
                    <el-col :span="12" class="order-right databox-number azure"><span v-if="flag">{{ allOrderNums['all_num'][0].count }}</span></el-col>
                    <!-- <el-col :span="4"></el-col> -->
                </el-row>
              </div>
            </el-card>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="12">
            <el-card class="box-card">
              <div slot="header" class="clearfix">
                <span style="line-height: 36px;">任务状态分布</span>
              </div>
              <div id="task" style="height: 480px;">
                
              </div>
            </el-card>
          </el-col>
          <el-col :span="12">
            <el-card class="box-card">
              <div slot="header" class="clearfix">
                <span style="line-height: 36px;">订单状态分布</span>
              </div>
               <div id="order" style="height: 480px;">
                
              </div>
            </el-card>
          </el-col>
        </el-row>
    </div>
</template>
<style>



  .box-card {
    /* width: 480px; */
    /* height: 480px; */
  }

  .order-right{
    /* font-family: 'Lucida Sans','trebuchet MS',Arial,Helvetica; */
    text-align: right;
    font-size: 18px;
  }
  .order-left{
    /* font-family: 'Lucida Sans','trebuchet MS',Arial,Helvetica; */
    text-align: left;
    font-size: 18px;
  }

  .databox-right {
    /* -lh-property: 0; */
    /* width: -webkit-calc(100% - 80px); */
    /* width: -moz-calc(100% - 80px); */
    /* width: calc(100% - 80px); */
    /* height: 80px; */
    /* padding: 10px 15px; */
    line-height: 100px;
    text-align: center;
    
    }
.azure {
    color: #2dc3e8!important;
    /* line-height: 100px; */
    display: inline;
    font-size: 30px;
}
  .databox-number {
    /* font-size: 20px; */
    margin: 4px 0 6px;
    font-size: 20px;
    /* line-height: 26px; */
    margin: 2px;
    position: relative;
    font-family: 'Lucida Sans','trebuchet MS',Arial,Helvetica;
    font-weight: 700;
    
}
</style>

<script>
  // import '../../../library/js/echarts.min.js'
  export default {
    data() {
      return {
       userCount : [],
       finishOrderNum :[],
       flag:false,
       allOrderNums:[],
      }
    },
    
    methods:{
        setTask: function(){
          var taskStatus=[
            {'value':0,'name':'未接受'},
            {'value':0,'name':'已接受'},
            {'value':0,'name':'已完成'},
            {'value':0,'name':'已结束'},
            {'value':0,'name':'已取消'},
          ]
          axios.post('index/task').then(response =>{
            // console.log(response.data.result)
            var res = response.data.result
            for (var i = 0; i < res.length; i++) {
              taskStatus[res[i].status].value = res[i].count
            }
            var myCharts = echarts.init(document.getElementById('task'))
            var option = {
              tooltip : {
                  // trigger: 'item',
                  formatter: "{b} : {c} ({d}%)"
              },
              legend: {
                  top: 10,
                  left: 'center',
                  data: ['未接受', '已接受','已完成','已结束','已取消']
              },
              series : [
                  {
                      type: 'pie',
                      radius : '65%',
                      center: ['50%', '50%'],
                      selectedMode: 'single',
                      data:taskStatus,
                      itemStyle: {
                          emphasis: {
                              shadowBlur: 10,
                              shadowOffsetX: 0,
                              shadowColor: 'rgba(0, 0, 0, 0.5)'
                          }
                      }
                  }
              ]
            }
            console.log(taskStatus)
            myCharts.setOption(option,true)
          }).catch(error => {
            this.$message("网络错误")
          })
          

          
        },
        setUser: function(){
          axios.post('index/user').then(response =>{
            // console.log(response.data.result)
            this.userCount = response.data.result

          }).catch(error => {
            this.$message("网络错误")
          })
        },
        setOrder: function(){
          var orderStatus=[
            {'value':0,'name':'未支付'},
            {'value':0,'name':'已支付'},
          ]
          axios.post('index/order').then(response =>{
            console.log(response.data.result)
            var res = response.data.result
            for (var i = 0; i < res.length; i++) {
              orderStatus[res[i].is_pay].value = orderStatus[res[i].is_pay].value + 1
            }
            var myCharts = echarts.init(document.getElementById('order'))
            var option = {
              tooltip : {
                  // trigger: 'item',
                  formatter: "{b} : {c} ({d}%)"
              },
              legend: {
                  top: 10,
                  left: 'center',
                  data: ['未支付', '已支付','已完成','已结束','已取消']
              },
              series : [
                  {
                      type: 'pie',
                      radius : '65%',
                      center: ['50%', '50%'],
                      selectedMode: 'single',
                      data:orderStatus,
                      itemStyle: {
                          emphasis: {
                              shadowBlur: 10,
                              shadowOffsetX: 0,
                              shadowColor: 'rgba(0, 0, 0, 0.5)'
                          }
                      }
                  }
              ]
            }
            // console.log(taskStatus)
            myCharts.setOption(option,true)
          }).catch(error => {
            this.$message("网络错误")
          })
        },
        getNum :function(){
          this.flag = false
          axios.post('index/ordernum').then(response =>{
            console.log(response.data.result)
            this.finishOrderNums = response.data.result['finish']
            this.allOrderNums =   response.data.result['all']
            this.flag = true
          }).catch(error =>{
            this.$message("网络错误")
          })
        },
        onEcharts: function(){
          this.setTask()
          this.setUser()
          this.setOrder()
          this.getNum()
        }
    },
    mounted(){
      
    }
  }
</script>