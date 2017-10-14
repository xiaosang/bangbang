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

        <scroller lock-x  use-pulldown :pulldown-config="pulldown" :use-pullup="false" pullup-config="pullup"  @on-pulldown-loading="down_updateTask" on-pullup-loading="up_updateTask" ref="scroller" @on-scroll="onScroll" height="-99">
            <div style="padding-bottom: 10px;">
                <div style="margin: 10px;overflow: hidden" v-for="i in 25">
                    <masker style="border-radius: 2px;">
                        <div class="m-img" style="background:red"></div>
                        <div slot="content" class="m-title">
                            {{ i }}
                            <br/>
                            <span class="m-time">2016-03-18</span>
                        </div>
                    </masker>
                </div>
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
    import { XHeader , Tabbar, TabbarItem , Scroller , Masker  } from 'vux'
    export default {
        components: {
            XHeader,
            Tabbar,
            TabbarItem,
            Scroller,
            Masker
        },
        data(){
            return {
                pulldown:{
                    content: 'Pull Down To Refresh',
                    height: 60,
                    autoRefresh: false,
                    downContent: '下拉刷新',
                    upContent: '释放后更新',
                    loadingContent: 'Loading...',
                    clsPrefix: 'xs-plugin-pulldown-'
                },
                pullup:{
                    content: 'Pull Up To Refresh',
                    pullUpHeight: 60,
                    height: 40,
                    autoRefresh: false,
                    downContent: '上拉加载',
                    upContent: '释放后更新',
                    loadingContent: 'Loading...',
                    clsPrefix: 'xs-plugin-pullup-'
                },

            }
        },
        methods:{
            tabbar_change(value){
                console.log(value)
            },
            down_updateTask(){
                //下拉更新
//                this.get_task_list(()=>{this.$refs.scroller.donePulldown()})
                setTimeout(()=>{
                    this.$refs.scroller.donePulldown()
                },1000)
            },
            up_updateTask(){
                //下拉更新
//                this.get_task_list(()=>{this.$refs.scroller.donePulldown()})
                setTimeout(()=>{
                    this.$refs.scroller.donePulldown()
                },1000)
            },
//            get_task_list(callback){
//                axios.get('/wx/main/get_task_list')
//                    .then((res)=>{
//                        console.log(res)
//                        this.list = res.data
//                        if(callback)callback();
//                    })
//                    .catch((err)=>{
//                        alert("网络异常，请稍后重试！")
//                    })
//            },
            onScroll (pos) {
                this.$nextTick(() => {
                    this.$refs.scroller.reset()
                })
            },

        },
        watch:{

        },
        mounted() {

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
        text-align: center;
        top: 50%;
        transform: translateY(-50%);
    }
    .m-time {
        font-size: 12px;
        padding-top: 4px;
        border-top: 1px solid #f0f0f0;
        display: inline-block;
        margin-top: 5px;
    }
    .weui-tabbar__icon i{
        color: #000;
    }
</style>

<style>

</style>

