<template>
    <div>
        <x-header :left-options="{ showBack:false,backText:'' }" style="background-color:#9EC9BD;">
            <span slot="left">
                <!--<a href="back(-1)" class="ion-arrow-left-c"></a>-->
                <!--<a href="javascript:history.go(-1)" class="ion-android-arrow-back" style="font-size: 18px;"></a>-->
                <!--<router-link to="/main" class="ion-android-arrow-back" style="font-size: 18px;"></router-link>-->
                <!--返回到首页-->
                <a href="javascript:history.go(-1)" class="ion-android-arrow-back" style="font-size: 18px;color:#FFF"></a>
                <span style="font-size: 18px;color:#FFF">寻物启事</span>
                
            </span>
            <router-link slot="right" to="/main/createlost" class="ion-android-add" style="font-size: 22px; color:#FFF"></router-link>
        </x-header>
        <div style="width:100%;">
             <scroller lock-x  use-pulldown :pulldown-config="pulldown" use-pullup :pullup-config="pullup"  @on-pulldown-loading="down_updateTask()" @on-pullup-loading="up_updateTask()" ref="scroller" @on-scroll="onScroll()" height="-99">
            <div>
                <div class="task" v-if="list.length">
                    <form-preview header-label="寻物启事" :header-value=" item[item.length-2]==2 ? '寻主' : '寻物' " :body-items="item" :footer-buttons="item[item.length-1]" v-for="item,index in list" :key="index"  class="item"></form-preview>
                    <!-- <divider style="font-size: 12px;opacity: 0.4;">仅显示最新{{ show_num }}条任务</divider> -->
                </div>
                <!-- <divider style="font-size: 12px;opacity: 0.4;" v-if="show_result">暂时没有任务</divider> -->
            </div>
        </scroller>
            

        </div>

        <tabbar @on-index-change="tabbar_change">
            <tabbar-item selected>
                <i slot="icon" class="ion-ios-medical"></i>
                <span slot="label">全部</span>
            </tabbar-item>
            <tabbar-item>
                <i slot="icon" class="ion-android-sad"></i>
                <span slot="label">寻物</span>
            </tabbar-item>
            <tabbar-item>
                <i slot="icon" class="ion-android-happy"></i>
                <span slot="label">寻主</span>
            </tabbar-item>
        </tabbar>
    </div>
</template>

<style>
.item .weui-form-preview__hd .weui-form-preview__value{
        font-size: 1em ;
        color: #0BB20C;
    }
</style>

<script>
    import { Tabbar,TabbarItem,Panel,XHeader,Swiper ,Swipeout, SwipeoutItem, SwipeoutButton , Grid, GridItem  , FormPreview , Scroller , Divider , ToastPlugin  } from 'vux'
    Vue.use(ToastPlugin)
    var self = this;
    export default {
        components: {
            TabbarItem,
            Tabbar,
            Panel,
            XHeader,
            Swiper,
            Swipeout,
            SwipeoutItem,
            SwipeoutButton,
            Grid,
            GridItem,
            FormPreview,
            Scroller,
            Divider,
            ToastPlugin
        },
        data(){
            return {
                pulldown:{
                    content: '',
                    height: 60,
                    autoRefresh: false,
                    downContent: '下拉刷新',
                    upContent: '释放后更新',
                    loadingContent: 'Loading...',
                    clsPrefix: 'xs-plugin-pulldown-'
                },
                pullup:{
                    content: '',
                    pullUpHeight: 60,
                    height: 40,
                    autoRefresh: false,
                    downContent: '释放后更新',
                    upContent: '上拉加载',
                    loadingContent: 'Loading...',
                    clsPrefix: 'xs-plugin-pullup-'
                },
               lostList:[],
               time:Date.parse(new Date())/1000,
               start : 0,
               num:5,
               type:0,
               list: [/*{
                    label: '商品',
                    value: '电动打蛋机'
                }, {
                    label: '标题标题',
                    value: '名字名字名字'
                }, {
                    label: '标题标题',
                    value: '很长很长的名字很长很长的名字很长很长的名字很长很长的名字很长很长的名字'
                }],
                buttons1: [{
                    style: 'default',
                    text: '辅助操作'
                }, {
                    style: 'primary',
                    text:'跳转到首页',
                    link: '/'
                }],
                buttons2: [{
                    style: 'primary',
                    text: '点击事件',
                    onButtonClick: (name) => {
                    alert(`clicking ${name}`)
                    }
                }*/]
            }
        },
        methods:{
            
           getList: function (callback){
            //    console.log(1232131)
            var params = {
                        time:this.time,
                        start:this.start,
                        num:this.num,
                        type:this.type
                    }
               axios.post('/wx/main/get_lost_list',params).then(res =>{
                //    console.log(res)
                this.start += this.num
                if(callback)callback();
                   this.list = this.list.concat(res.data.result)
                   
               }).catch(error =>{
                   this.$vux.toast.text('网络异常!', 'top')
                // console.log(error)
               })
           },
           tabbar_change:function(value){
               this.$vux.loading.show({
                    text: '正在加载数据...'
                })
            //    console.log(value)
               this.type = value
               this.list = []
               this.start = 0
                this.getList((res)=>{
                    // this.list = []
                   this.$refs.scroller.donePulldown()
                   this.$vux.loading.hide()
                })
           },
            down_updateTask(){
                //下拉更新
                this.time = Date.parse(new Date())/1000
                this.start = 0
                this.getList((res)=>{
                    this.list = []
                    this.$refs.scroller.donePulldown()
                    
                })
                // this.getList()
                // setTimeout(()=>{
                //     this.$refs.scroller.donePulldown()
                // },1000)

            },
            up_updateTask(){
                //上拉加载
                this.getList((res)=>{
                   this.$refs.scroller.donePullup()
                })
            },   
            onScroll(){

            }        
        },
        mounted() {
            // console.log(123)
            // this.getList()
            
        }
    }
</script>