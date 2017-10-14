<template>
   <div style="min-width:1000px;max-width:1000px">
        <div class="gm-breadcrumb">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item to="/">首页</el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <remote-script src="/js/echarts.min.js" @load="onEcharts"></remote-script>
        <el-row>
          <el-col :span="12">
            <el-card class="box-card">
              <div slot="header" class="clearfix">
                <span style="line-height: 36px;">新增用户</span>
              </div>
              <div id="user" style="height: 480px;">
                
              </div>
            </el-card>
          </el-col>
          <el-col :span="12">
            <el-card class="box-card">
              <div slot="header" class="clearfix">
                <span style="line-height: 36px;">测试</span>
              </div>
               <div id="order" style="height: 480px;">
                
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
                <span style="line-height: 36px;">任务状态分布</span>
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
    width: 480px;
    /* height: 480px; */
  }
</style>

<script>
  // import '../../../library/js/echarts.min.js'
  export default {
    data() {
      return {
       
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
            console.log(response.data.result)
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
        onEcharts: function(){
          this.setTask()
        }
    },
    mounted(){
      
    }
  }
</script>