<template>
    <div id="main">
        <swiper id="swiper" loop auto :list="swiperImg"></swiper>

        <swipeout v-if="!announcement" >
            <swipeout-item transition-mode="follow">
                <div slot="right-menu">
                    <swipeout-button @click.native="noShowAnnouncement()" type="warn">不再显示</swipeout-button>
                </div>
                <div slot="content" class="demo-content vux-1px-t" >
                    <marquee  scrollamount="4"  @click="$router.push('/main/announcement')">{{ '公告 : ' + announcementContent }}</marquee>
                </div>
            </swipeout-item>
        </swipeout>

        <grid>
            <grid-item :link="item.url"  v-for="item,index in menu" :key="index">
                <div class="weui-grid__icon">
                    <i :class="item.icon"></i>
                </div>
                <!--<img slot="icon" :src="item.img">-->
                <span class="grid-center">{{ item.title }}</span>
            </grid-item>
        </grid>

        <scroller lock-x  use-pulldown :pulldown-config="pulldown"  @on-pulldown-loading="updateTask" ref="scroller" @on-scroll="onScroll" height="-127">
            <div>
                <div class="task" v-if="list.length">
                    <form-preview header-label="任务类型" :header-value=" item[item.length-2] ? '有偿' : '无偿' " :body-items="item" :footer-buttons="item[item.length-1]" v-for="item,index in list" :key="index"  class="item"></form-preview>
                    <divider style="font-size: 12px;opacity: 0.4;">仅显示最新{{ show_num }}条任务</divider>
                </div>
                <divider style="font-size: 12px;opacity: 0.4;" v-if="show_result">暂时没有任务</divider>
            </div>
        </scroller>

        <Navbottom></Navbottom>
    </div>
</template>

<script>
    import Navbottom from './NavBottom.vue'
    import { Swiper ,Swipeout, SwipeoutItem, SwipeoutButton , Grid, GridItem  , FormPreview , Scroller , Divider , ToastPlugin  } from 'vux'
    Vue.use(ToastPlugin)
    export default {
        components: {
            Navbottom,
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
                swiperImg: [{
                    url: 'javascript:;',
                    img: 'https://static.vux.li/demo/1.jpg',
                    title: '送你一朵fua',
                    fallbackImg: 'https://static.vux.li/demo/3.jpg'
                }, {
                    url: 'javascript:;',
                    img: 'https://static.vux.li/demo/2.jpg',
                    title: '送你一辆车',
                    fallbackImg: 'https://static.vux.li/demo/3.jpg'
                }, {
                    url: 'javascript:;',
                    img: 'https://static.vux.li/demo/2.jpg',
                    title: '送你一次旅行',
                    fallbackImg: 'https://static.vux.li/demo/3.jpg'
                }],
                announcement:'',
                announcementContent:'',
                menu:[
                    {
                        url: '/main/release',
                        img:'/img/icon-pwd.png',
                        icon:'ion-compose',
                        title:'发布'
                    },
                    {
                        url: '/main/task/list',
                        img:'/img/icon-pwd.png',
                        icon:'ion-document-text',
                        title:'任务'
                    },
                    {
                        url: '/main/announcement',
                        img:'/img/icon-pwd.png',
                        icon:'ion-speakerphone',
                        title:'公告'
                    },
                    {
                        url: '/main/lost/0',
                        img:'/img/icon-pwd.png',
                        icon:'ion-ios-help',
                        title:'寻物'
                    }
                ],
                list: [
//                    [
//                        {
//                            label: '标题',
//                            value: '买饭'
//                        }, {
//                            label: '时间',
//                            value: '十分钟'
//                        }, {
//                            label: '发布时间',
//                            value: '2017-10-01 17:43'
//                        },
//                        0,
//                        [{
//                            style: 'default',
//                            text: '接受任务'
//                        }, {
//                            style: 'default',
//                            text: '查看详情',
//                            link: '/main/1'
//                        }],
//                    ]
                ],
                buttons1: [{
                    style: 'default',
                    text: '接受任务'
                }, {
                    style: 'default',
                    text: '查看详情',
                    link: '/main/1'
                }],
                pulldown:{
                    content: 'Pull Down To Refresh',
                    height: 60,
                    autoRefresh: false,
                    downContent: '下拉刷新',
                    upContent: '释放后更新',
                    loadingContent: 'Loading...',
                    clsPrefix: 'xs-plugin-pulldown-'
                },
                swiperElement:'',
                num:5,//任务显示条数
                show_num:'',//仅显示最近show_num条数据
                show_result:false,//暂时没有任务
            }
        },
        beforeRouteLeave(to, from, next) {
            to.meta.keepAlive = false;
            next();
        },
        methods:{
            noShowAnnouncement(){
                //本地存储控制公告显示
                localStorage.setItem("announcement", "true");
                this.announcement = true;
            },
            updateTask(){
                //下拉更新
                this.get_task_list(()=>{this.$refs.scroller.donePulldown()})
//                setTimeout(()=>{
//
//                },1000)
            },
            onScroll (pos) {
                //控制轮播图显示
//                console.log(pos)
                if(pos.top > 66){
                    this.swiperElement.style.height='0px'
//                    console.log(localStorage.getItem("announcement"))
//                    console.log(!localStorage.getItem("announcement"))
                    if(!localStorage.getItem("announcement")){
                        this.announcement = true
                    }
//                    this.announcement = true
                }else if(pos.top < -100){
                    this.swiperElement.style.height='180px'
                    if(!localStorage.getItem("announcement")){
                        this.announcement = false
                    }
//                    this.announcement = false
                }
            },
            get_task_list(callback){
                axios.get('/wx/main/get_task_list',{
                    params:{
                        num:this.num,
                    }
                })
                    .then((res)=>{
                        if(res.data.result.length == 0)this.show_result = true
                        console.log(res.data)
                        this.show_num = res.data.result.length
                        this.list = res.data.result
                        if(callback)callback();
                        this.$vux.loading.hide()
                    })
                    .catch((err)=>{
                        this.$vux.toast.text('网络异常!', 'top')
                    })
            },
            get_announcementContent(){
                axios.get('/wx/main/get_announcementContent')
                    .then((res)=>{
                        console.log(res.data.result)
                        if(res.data.result){
                            this.announcementContent = res.data.result.content
                        }else{
                            this.announcementContent = "暂无数据"
                        }
                        // this.announcementContent = res.data.result
                    })
                    .catch(()=>{

                    })
            },
            test(){
            }
        },
        mounted() {
            this.$vux.loading.show({
                text: 'Loading'
            })
            //查看公告是否显示，本地存储
            this.announcement = localStorage.getItem('announcement');
            this.swiperElement = document.getElementById('swiper')
            this.get_announcementContent()
            this.get_task_list()

        }
    }
</script>

<style scoped>
    /*轮播*/
    #swiper{
        height:180px;
        transition: height 0.3s;
    }

    /*公告*/
    .demo-content {
        padding: 10px 10px;
        text-align: center;
        font-size:14px;
        margin:4px 0 0;
    }
    /*菜单*/
    .grid-center {
        display: block;
        text-align: center;
        color: #666;
        line-height: 2;
        font-size: 14px;
    }
    .weui-grids:before,.weui-grid:before,.weui-grid:after{
        border:0;
    }
    .weui-grid{
        padding:2px 10px;
        padding-top: 8px;
        margin:4px 0;
        background: white;
    }
    .weui-grid__icon i{
        display: block;
        width: 100%;
        height: 100%;
        font-size: 24px;
        text-align: center;
        color: #000;
    }
    /*任务*/
    .task{
        background: rgb(241,241,241);
    }
    .item{
        margin-bottom: 4px;
    }
</style>

<style>
    /*任务item */
    .item .weui-form-preview__hd .weui-form-preview__value{
        font-size: 1em ;
        color: #0BB20C;
    }

</style>