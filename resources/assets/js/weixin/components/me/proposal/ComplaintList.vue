<template>
    <div>
        <div class="header">
            <x-header :left-options="{backText:'',preventGoBack:true}" @on-click-back="return_last">投诉列表
                <a href="javascript:void(0)" slot="right" style="margin-top: -5px" @click="show_complaint_menu=true;"><i class="ion-android-menu" style="font-size:27px"></i></a>
            </x-header>
        </div>
        <div class="content">
            <scroller v-if="complaint_list.length>0" lock-x height="-125" @on-scroll="get_complaint_list" ref="scroller">
                <div class="complaint_item" v-for="(cl,index) in complaint_list" :key="index" @click="$router.push('/complaint/detail/'+cl.id)">
                    <div class="complaint_content">{{cl.content | delHtmlTag}}</div>
                    <div class="complaint_date">
                        <span v-if="judge_complaint==0">我投诉:{{cl.name}}</span>
                        <span style="margin-left: 10px" v-if="judge_complaint==1">投诉我:{{cl.name}}</span>
                        <span>投诉日期:{{cl.create_time | date('yyyy-MM-dd hh:mm')}}</span>
                    </div>
                </div>
            </scroller>
            <load-more v-if="loadmore" tip="加载中..."></load-more>
            <load-more v-if="nomore" :show-loading="false" tip="没有更多" background-color="#fbf9fe"></load-more>
            <load-more v-if="nodata" :show-loading="false" tip="暂无数据" background-color="#fbf9fe"></load-more>
        </div>
        <actionsheet v-if="judge_complaint==0" v-model="show_complaint_menu" :menus="complaint_menu1" :close-on-clicking-mask="false" show-cancel @on-click-menu="click_complaint_menu"></actionsheet>
        <actionsheet v-if="judge_complaint==1" v-model="show_complaint_menu" :menus="complaint_menu2" :close-on-clicking-mask="false" show-cancel @on-click-menu="click_complaint_menu"></actionsheet>
    </div>
</template>
<style>

</style>
<style scoped>
    .header{
        position:fixed;
        width: 100%;
    }
    .content{
        padding-top: 47px;
    }
    .complaint_content{
        overflow: hidden;
        text-overflow:ellipsis;
        white-space: nowrap;
        height:25px;
        padding-top: 5px;
        padding-bottom: 5px;
        font-size: 17px;
        color: red;
    }
    .complaint_date{
        font-size: 14px;
        color: #5e5d5d;
    }
    .complaint_item{
        padding-top: 10px;
        padding-bottom: 10px;
        border-bottom: 1px solid #fbce9a;
        width: 90%;
        margin: auto;
    }
</style>
<script>
    //    import Navbottom from './NavBottom.vue'
    import {XHeader,Group,XButton,Divider,Actionsheet,Scroller,LoadMore} from 'vux'
    export default {
        components: {
            XHeader,
            Group,
            XButton,
            Divider,
            Actionsheet,
            Scroller,
            LoadMore
        },
        data(){
            return {
                judge_complaint:0, //0 我投诉的，  1 投诉我的
                show_complaint_menu:false,
                complaint_menu1:{
                    to_complaint_me:'投诉我的',
                    complaint:'我要投诉',
                },
                complaint_menu2:{
                    to_complaint_by_me:'我投诉的',
                    complaint:'我要投诉',
                },
                complaint_list:[],
                num:10,
                page:0,
                receive_list:[],
                can_get:true,
                loadmore:false,
                nomore:false,
                nodata:false
            }
        },
        methods:{
            return_last(){
                this.$router.push('/me')
            },
            click_complaint_menu(key){
                switch(key){
                    case 'complaint':
                        this.$router.push('/complaint')
                    break;
                    case 'to_complaint_me':
                        this.page = 0;
                        this.can_get = true;
                        this.judge_complaint = 1;
                        this.get_complaint_list();
                    break;
                    case 'to_complaint_by_me':
                        this.page = 0;
                        this.can_get = true;
                        this.judge_complaint=0;
                        this.get_complaint_list();
                    break;
                }
            },
            get_complaint_list(){
                if(this.can_get){
                    this.can_get = false;
                    this.nomore = false;
                    this.nodata = false;
                    if(this.page==0){
                        this.receive_list = [];
                        this.complaint_list = [];
                        this.$vux.loading.show({
                            text: '努力加载中...'
                        })
                    }else{
                        this.loadmore = true;
                    }
                    let data = {
                        judge_complaint:this.judge_complaint,
                        page : this.page,
                        num:this.num
                    }
                    this.send_request('post','/wx/me/complaint/list',function (response,self) {
                        if(self.page==0){
                            self.$vux.loading.hide()
                        }else{
                            self.loadmore = false;
                        }
                        if(response.data.code==1){
                            self.receive_list = response.data.result;
                            if(self.receive_list.length>0){
                                self.complaint_list.push.apply(self.complaint_list,self.receive_list);
                                if(self.receive_list.length==self.num){
                                    self.can_get = true;
                                    self.page++;
                                }else{
                                    self.nomore = true;
                                }
                            }else{
                                if(self.complaint_list.length>0){
                                    self.nomore = true;
                                }else{
                                    self.nodata = true;
                                }
                            }
                        }else{
                            self.toast_message(response.data.msg,'warn')
                        }
                        if(self.page>0){
                            self.$refs.scroller.reset()
                        }
                    },data);
                }
            }
        },
        mounted() {
            this.get_complaint_list()
        }
    }
</script>
