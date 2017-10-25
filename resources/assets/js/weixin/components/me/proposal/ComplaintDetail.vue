<template>
    <div>
        <div class="header">
            <x-header :left-options="{backText:'',preventGoBack:true}" @on-click-back="return_last">投诉详情
                <div slot="right" @click="show__operate_menu" style="margin-top: -5px"><i style="font-size: 30px" class="ion-android-menu"></i></div>
            </x-header>
        </div>
        <div class="content">
            <div class="complaint_content" v-html="complaint.content">
            </div>
            <div class="complaint_detail">
                <div class="imgs" v-if="complaint_paths.length>0&&complaint_paths[0]!=''">
                    <img v-for="(cp,index) in complaint_paths" :key="index" :src="'/wx/me/show_img?path='+cp" alt="">
                </div>
                <div class="complaint_info" style="margin-top: 5px">
                    <div style="margin-left: 5px" v-if="judge_complaint==0"><span>我投诉:{{user.name}}</span></div>
                    <div style="margin-left: 5px" v-if="judge_complaint==1"><span>{{user.name}}投诉我</span></div>
                    <div style="margin-left: 5px;margin-top: 5px">投诉日期：{{complaint.create_time | date}}</div>
                </div>
            </div>
        </div>
        <actionsheet v-model="show_operate_complaint_menu" :menus="operate_complaint_menu" show-cancel @on-click-menu="switch_menu"></actionsheet>
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
        min-height: 100px;
        border-bottom: 1px solid #acacac;
    }
    .complaint_detail{
        margin-top: 10px;
        padding-bottom: 10px;
        /*border-bottom: 1px solid #acacac;*/
    }
    .imgs {
        border-bottom: 1px solid #acacac;
    }
    .imgs img{
        width: 70px;
        height: 70px;
        margin-left: 5px;
        margin-right: 5px;
    }
</style>
<script>
    //    import Navbottom from './NavBottom.vue'
    import {XHeader,Actionsheet} from 'vux'
    export default {
        components: {
            XHeader,
            Actionsheet
        },
        data(){
            return {
                complaint:'',
                user:'',
                judge_complaint:0,
                complaint_paths:[],
                show_operate_complaint_menu:false,
                operate_complaint_menu:{
                    edit_complaint: '编辑',
                    delete_complaint: '删除'
                },

            }
        },
        methods:{
            return_last(){
                this.$router.push('/complaint/list');
            },
            show__operate_menu(){
                if(this.complaint.create_user_id==this.user.id){
                    this.show_operate_complaint_menu = true;
                }else{
                    this.toast_message('此投诉不是你所添加的，没有权限','warn');
                }
            },
            single_complaint(){
                this.$vux.loading.show({
                    text: '努力加载中'
                })
                let data = {
                    complaint_id:this.$route.params.complaint_id
                }
                this.send_request('post','/wx/me/complaint/single',function (response,self) {
                    if(response.data.code==1){
                        if(response.data.result){
                            self.complaint = response.data.result['complaint'];
                            self.user = response.data.result['user'];
                            self.judge_complaint = response.data.result['judge_complaint']
                            let path = response.data.result['complaint']['path']
                            self.complaint_paths = path.split(';');
                        }else{
                            self.toast_message('请正确使用该系统','warn')
                        }
                    }else{
                        self.toast_message(response.data.msg,'warn')
                    }
                    self.$vux.loading.hide()
                },data)
            },
            switch_menu(key){
                console.log(key)
                if(key=='edit_complaint'){
                    this.edit_complaint();
                }else if(key=='delete_complaint'){
                    this.delete_complaint();
                }
            },
            delete_complaint(){
                let self = this;
                this.$vux.confirm.show({
                    'title':'投诉',
                    'content':'确定要删除该投诉吗?',
                    onConfirm () {
                        let data = {
                            complaint_id:self.$route.params.complaint_id
                        }
                        self.send_request('post','/wx/me/complaint/delete',function (response,self) {
                            self.toast_message(response.data.msg);
                            if(response.data.code==1){
                                self.$router.push('/complaint/list');
                            }
                        },data);
                    }
                })
            },
            edit_complaint(){
                this.toast_message('此功能暂未开放','text');
//                let self = this;
//                this.$vux.confirm.prompt('编辑投诉内容', {
//                    'title':'编辑',
//                    onConfirm (val) {
//                        let data = {
//                            complaint_id:self.complaint_id,
//                            complaint_content:val
//                        }
//                        self.send_request('post','',function (response,self) {
//
//                        },data);
//                    }
//                })
            }
        },
        mounted() {
            this.single_complaint()
        }
    }
</script>
