<template>
    <div class="task">
        <!--<x-header title="任务列表"></x-header>-->
        <x-header :left-options="{ showBack:false,backText:'' }">
            <span slot="left">
                <!--<a href="back(-1)" class="ion-arrow-left-c"></a>-->
                <!--<a href="javascript:history.go(-1)" class="ion-android-arrow-back" style="font-size: 18px;"></a>-->
                <router-link to="/main" class="ion-android-arrow-back" style="font-size: 18px;"></router-link>
                <span style="font-size: 18px;">任务列表</span>
            </span>
            <!--<router-link slot="right" to="/main" class="ion-plus-round" style="font-size: 18px;"></router-link>-->
            <!--<router-link slot="right" to="/main" class="ion-plus" style="font-size: 18px;"></router-link>-->
            <!--<router-link slot="right" to="/main" class="ion-plus-circled" style="font-size: 18px;"></router-link>-->
            <!--<router-link slot="right" to="/main" class="ion-ios-plus-empty" style="font-size: 18px;"></router-link>-->
            <!--<router-link slot="right" to="/main" class="ion-ios-plus-outline" style="font-size: 18px;"></router-link>-->
            <!--<router-link slot="right" to="/main" class="ion-ios-plus" style="font-size: 18px;"></router-link>-->
            <router-link slot="right" to="/main/release" class="ion-android-add" style="font-size: 22px;"></router-link>
            <!--<router-link slot="right" to="/main" class="ion-android-add-circle" style="font-size: 18px;"></router-link>-->
            <!--<router-link slot="right" to="/main" class="ion-ios-plus" style="font-size: 18px;"></router-link>-->
            <!--<router-link slot="right" to="/main" class="ion-compose" style="font-size: 18px;"></router-link>-->
        </x-header>


            <scroller v-if="tabbar_val==0" lock-x  use-pulldown :pulldown-config="pulldown"  use-pullup :pullup-config="pullup"  @on-pulldown-loading="down_updateTask('-1','start_all','task_all')" @on-pullup-loading="up_updateTask('-1','start_all','task_all')" ref="scroller" @on-scroll="onScroll('-1')" height="-99">
                <div style="padding-bottom: 10px;">
                    <router-link :to="'/main/task/info/'+item.id" style="margin: 10px;overflow: hidden;display: block;" v-for="item in task_all" :key="item.id">
                        <masker style="border-radius: 5px;">
                            <div class="m-img" style="background: yellow url('/img/wx/money.png') center no-repeat;" v-if=" item.type == 0 "></div>
                            <div class="m-img" style="" v-if=" item.type == 1 "></div>
                            <div slot="content" class="m-title">
                                <div style="position: absolute;width: 100%;height: 100%;">
                                    <p class="address" style="top: 0;">
                                        <span style="float: left;" v-if=" item.type == 0 ">金额：</span>
                                        <span style="text-align: right;float: right;font-size: 14px;" v-if=" item.type == 0 ">￥ {{ item.pay_money/100 }}</span>
                                        <span style="float: left;" v-if=" item.type == 1 ">类型：</span>
                                        <span style="text-align: right;float: right;font-size: 14px;" v-if=" item.type == 1 ">无偿</span>
                                    </p>
                                    <div style="padding: 8% 0;">
                                        <p>{{ item.name }}</p>
                                        <p class="m-address">{{ item.task_finish_time | formatDuring }}</p>
                                    </div>
                                    <p class="address" style="bottom: 0;">
                                        <span style="float: left;">收货地址：</span>
                                        <span style="text-align: right;float: right;">{{ item.address_name }}</span>
                                    </p>
                                </div>
                            </div>
                        </masker>
                    </router-link>
                </div>
            </scroller>


            <scroller v-else-if="tabbar_val==1" lock-x  use-pulldown :pulldown-config="pulldown" use-pullup :pullup-config="pullup"  @on-pulldown-loading="down_updateTask('1','start_n','task_n')" @on-pullup-loading="up_updateTask('1','start_n','task_n')" ref="scroller" @on-scroll="onScroll('1')" height="-99">
                <div style="padding-bottom: 10px;">
                    <router-link :to="'/main/task/info/'+item.id" style="margin: 10px;overflow: hidden;display: block;" v-for="item in task_n" :key="item.id">
                        <masker style="border-radius: 5px;">
                            <div class="m-img" style="background: yellow url('/img/wx/money.png') center no-repeat;" v-if=" item.type == 0 "></div>
                            <div class="m-img" style="" v-if=" item.type == 1 "></div>
                            <div slot="content" class="m-title">
                                <div style="position: absolute;width: 100%;height: 100%;">
                                    <p class="address" style="top: 0;">
                                        <span style="float: left;" v-if=" item.type == 0 ">金额：</span>
                                        <span style="text-align: right;float: right;font-size: 14px;" v-if=" item.type == 0 ">￥ {{ item.pay_money/100 }}</span>
                                        <span style="float: left;" v-if=" item.type == 1 ">类型：</span>
                                        <span style="text-align: right;float: right;font-size: 14px;" v-if=" item.type == 1 ">无偿</span>
                                    </p>
                                    <div style="padding: 8% 0;">
                                        <p>{{ item.name }}</p>
                                        <p class="m-address">{{ item.task_finish_time | formatDuring }}</p>
                                    </div>
                                    <p class="address" style="bottom: 0;">
                                        <span style="float: left;">收货地址：</span>
                                        <span style="text-align: right;float: right;">{{ item.address_name }}</span>
                                    </p>
                                </div>
                            </div>
                        </masker>
                    </router-link>
                </div>
            </scroller>

            <scroller v-else-if="tabbar_val==2" lock-x  use-pulldown :pulldown-config="pulldown" use-pullup :pullup-config="pullup"  @on-pulldown-loading="down_updateTask('0','start_y','task_y')" @on-pullup-loading="up_updateTask('0','start_y','task_y')" ref="scroller" @on-scroll="onScroll('0')" height="-99">
                <div style="padding-bottom: 10px;">
                    <router-link :to="'/main/task/info/'+item.id" style="margin: 10px;overflow: hidden;display: block;" v-for="item in task_y" :key="item.id">
                        <masker style="border-radius: 5px;">
                            <div class="m-img" style="background: yellow url('/img/wx/money.png') center no-repeat;" v-if=" item.type == 0 "></div>
                            <div class="m-img" style="" v-if=" item.type == 1 "></div>
                            <div slot="content" class="m-title">
                                <div style="position: absolute;width: 100%;height: 100%;">
                                    <p class="address" style="top: 0;">
                                        <span style="float: left;" v-if=" item.type == 0 ">金额：</span>
                                        <span style="text-align: right;float: right;font-size: 14px;" v-if=" item.type == 0 ">￥ {{ item.pay_money/100 }}</span>
                                        <span style="float: left;" v-if=" item.type == 1 ">类型：</span>
                                        <span style="text-align: right;float: right;font-size: 14px;" v-if=" item.type == 1 ">无偿</span>
                                    </p>
                                    <div style="padding: 8% 0;">
                                        <p>{{ item.name }}</p>
                                        <p class="m-address">{{ item.task_finish_time | formatDuring }}</p>
                                    </div>
                                    <p class="address" style="bottom: 0;">
                                        <span style="float: left;">收货地址：</span>
                                        <span style="text-align: right;float: right;">{{ item.address_name }}</span>
                                    </p>
                                </div>
                            </div>
                        </masker>
                    </router-link>
                </div>
            </scroller>


        <tabbar @on-index-change="tabbar_change">
            <tabbar-item selected>
                <i slot="icon" class="ion-android-person"></i>
                <span slot="label">全部</span>
            </tabbar-item>
            <tabbar-item>
                <i slot="icon" class="ion-android-person"></i>
                <span slot="label">无偿</span>
            </tabbar-item>
            <tabbar-item>
                <i slot="icon" class="ion-android-person"></i>
                <span slot="label">有偿</span>
            </tabbar-item>
        </tabbar>
    </div>
</template>

<script>
    import { XHeader , Tabbar, TabbarItem , Scroller , Masker , ToastPlugin } from 'vux'
    Vue.use(ToastPlugin)
    export default {
        components: {
            XHeader,
            Tabbar,
            TabbarItem,
            Scroller,
            Masker,
            ToastPlugin
        },
        data(){
            return {
                pulldown:{
                    content: '一大波数据正在赶来...',
                    height: 60,
                    autoRefresh: false,
                    downContent: '下拉刷新',
                    upContent: '释放后更新',
                    loadingContent: 'Loading...',
                    clsPrefix: 'xs-plugin-pulldown-'
                },
                pullup:{
                    content: '上拉加载..',
                    pullUpHeight: 60,
                    height: 40,
                    autoRefresh: false,
                    downContent: '释放后更新',
                    upContent: '上拉加载',
                    loadingContent: 'Loading...',
                    clsPrefix: 'xs-plugin-pullup-'
                },
                tabbar_val:0,//全部  有偿  无偿
                time:'',//进入时间
                setInterval_time:'',//每秒更新一次
                start_all:0,//从第0条开始
                start_y:0,//从第0条开始
                start_n:0,//从第0条开始
                num:30,//显示/更新 数量
                task_all:[],
                task_y:[],
                task_n:[],
            }
        },
        methods:{
            tabbar_change(value){
                this.tabbar_val = value
                try {
                    this.$refs.scroller.enablePullup()
                    this.$nextTick(() => {
                        this.$refs.scroller.reset({
                            top: 0
                        })
                    })
                }catch(e){
                    //第一次进由于上拉刷新还在 所以this.$refs.scroller.enablePullup()会报错
                }
            },
            down_updateTask(type,start,arr){
                //下拉更新
                this.time = Date.parse(new Date())/1000
                this[start] = this.num
                this.get_task_list(type,this[start],(res)=>{
                    this[arr] = res.data.result
                    this.$refs.scroller.donePulldown()
                    if(res.data.result.length >= this.num){
                        this.$refs.scroller.enablePullup()
                    }
                })
            },
            up_updateTask(type,start,arr){
                //上拉加载
                this.get_task_list(type,start,(res)=>{
                    if(res.data.result.length == 0){
                        this.$refs.scroller.disablePullup()
                        this.$nextTick(() => {
                            this.$refs.scroller.reset()
                        })
                    }else{
                        this[start]+=this.num
                        this[arr] = this[arr].concat(res.data.result)
                    }
                    this.$refs.scroller.donePullup()
                })
            },
            get_task_list(type,start,callback){
                axios.get('/wx/task/get_task',{
                    params:{
                        time:this.time,
                        start:this[start],
                        num:this.num,
                        type:type
                    }
                })
                    .then((res)=>{
//                    console.log(res.data.result.length)
//                    console.log(this.num)
                        if(res.data.result.length < this.num){
                            this.$refs.scroller.disablePullup()
                        }
                        if(callback)callback(res);
                    })
                    .catch((err)=>{
                        this.$vux.toast.text('网络异常!', 'top')
                    })
            },
            onScroll (type) {
                this.$nextTick(() => {
                    this.$refs.scroller.reset()
                })
            },
        },
        watch:{

        },
        mounted() {
            this.$vux.loading.show({
                text: 'Loading'
            })
            setInterval(()=>{
                this.setInterval_time = Date.parse(new Date())/1000
            },1000)
            this.time = Date.parse(new Date())/1000

            new Promise((resolve , reject)=>{
                this.get_task_list('-1',this.start,(res)=>{
                    this.task_all = this.task_all.concat(res.data.result)
                    this.start_all+=this.num
                    resolve()
                })}
            ).then(()=>{
                return new Promise((resolve , reject)=>{
                    this.get_task_list('0',this.start,(res)=>{
                        this.task_y = this.task_y.concat(res.data.result)
                        this.start_y+=this.num
                        resolve()
                    })
                })}
            ).then(()=>{
                return new Promise((resolve , reject)=>{
                    this.get_task_list('1',this.start,(res)=>{
                        this.task_n = this.task_n.concat(res.data.result)
                        this.start_n+=this.num
                        this.$vux.loading.hide()
                    })
                })}
            )
//            this.get_task_list('-1',this.start,(res)=>{
//                this.task_all = this.task_all.concat(res.data.result)
//                this.start_all+=this.num
//                this.$vux.loading.hide()
//            })
//
//            this.get_task_list('0',this.start,(res)=>{
//                this.task_y = this.task_y.concat(res.data.result)
//                this.start_y+=this.num
//            })
//
//            this.get_task_list('1',this.start,(res)=>{
//                this.task_n = this.task_n.concat(res.data.result)
//                this.start_n+=this.num
//            })

        }
    }
</script>

<style scoped>
    /*任务*/
    .task{
        background: rgb(241,241,241);
    }
    .m-img {
        padding-bottom: 33%;
        display: block;
        position: relative;
        max-width: 100%;
        background-size: cover;
        background-position: center center;
        cursor: pointer;
        border-radius: 2px;
    }
    .m-title {
        color: #fff;
        text-align: center;
        text-shadow: 0 0 2px rgba(0, 0, 0, .5);
        font-weight: 500;
        font-size: 16px;
        position: absolute;
        left: 0;
        right: 0;
        width: 100%;
        height:100%;
        text-align: center;
        top: 50%;
        transform: translateY(-50%);
    }
    .m-address {
        font-size: 12px;
        padding-top: 4px;
        border-top: 1px solid #f0f0f0;
        display: inline-block;
        margin-top: 5px;
    }
    .weui-tabbar__icon i{
        color: #000;
    }
    .address{
        height: 2em;
        line-height:2em;
        position: absolute;
        width: -webkit-fill-available;
        text-align: left;
        font-size: 12px;
        padding: 0 5px;
    }
    .xs-plugin-pullup-container{
        font-size:12px;
    }
</style>

<style>
    .xs-plugin-pullup-container , .xs-plugin-pulldown-container{
        font-size:12px;
    }
</style>

