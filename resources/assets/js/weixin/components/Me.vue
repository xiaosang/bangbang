<template>
    <div>
        <div class="header">
            <x-header :left-options="{backText:'',showBack:false}">我的</x-header>
        </div>
        <div class="content" v-if="!loading">
            <group class="account_div">
                <div class="main_info"></div>
                <blur :height="80" url="" style="float:left;margin-left:30px;">
                    <div style="width:80px; height:80px; border-radius:50%; overflow:hidden;">
                        <img style="width: 100%;height: 100%;" :src="user_avatar" alt=""/>
                    </div>
                </blur>
                <div class="info">
                    <p>{{nick_name}}</p>
                    <p>{{user_is_v}}</p>
                </div>
                <!--<div class="detail"><i class="fa fa-angle-right"></i></div>-->
                <div class="main_info"></div>
                <cell-box  v-if="is_student==0" is-link link="/accountset">
                    <cell-box is-link link="">
                        学生认证
                    </cell-box>
                </cell-box>
                <cell-box v-else is-link link="/main/info/">
                    <cell-box is-link link="">
                        个人信息
                    </cell-box>
                </cell-box>
            </group>
            <group title="任务">
                <cell-box is-link link="/me/task/release/list">
                    我发布的
                </cell-box>
                <cell-box is-link link="/me/task/receive/list">
                    我接收的
                </cell-box>
            </group>
            <group title="消息">
                <cell-box is-link link="/news/list">
                    我的消息<badge v-if="unread" style="position: absolute;right: 10%;height: 1px;top: 0;bottom: 0;margin: auto"></badge>
                </cell-box>
            </group>
            <group title="反馈与投诉">
                <cell-box is-link link="/complaint/list">
                    投诉
                </cell-box>
                <cell-box is-link link="/feedback">
                    反馈
                </cell-box>
            </group>
        </div>
        <Navbottom></Navbottom>
    </div>
</template>
<style>
    .account_div > div{
        margin-top: 5px;
        margin-bottom: 5px;
    }
</style>
<style scoped>
    .header{
        position:fixed;
        width: 100%;
        z-index: 22;
    }
    .content{
        padding-top: 47px;
    }
    .info{
        width: 170px;
        height: 70px;
        margin-left: 15px;
        display: inline-block;
        overflow: hidden;
    }

    .info p{
        width: 170px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        font-size: 15px;
        margin-top: 15px;
        height: 20px;
    }
    .main_info{
        padding-top: 10px;
    }
</style>
<script>
    import Navbottom from './NavBottom.vue'
    import { Group, Cell,XHeader,Blur,CellBox,Badge } from 'vux'
    export default {
        components: {
            Group,
            Cell,
            Navbottom,
            XHeader,
            Blur,
            CellBox,
            Badge
        },
        data(){
            return {
                user_avatar:"",
                nick_name:'',
                user_is_v:'',
                unread:false,
                is_student : 0,
                loading : true,
                
            }
        },
        methods:{
          get_user_info(){
              this.$vux.loading.show({
                  text: '加载中...'
              });
            this.send_request('post','/wx/me/user/info',function (response,self) {
                self.$vux.loading.hide();
                if(response.data.code==1){
                    let user_info = response.data.result
                    if(user_info){
                        self.user_avatar = user_info.avatar;
                        self.nick_name = user_info.nick_name;
                        if(user_info.is_v==1){
                            self.user_is_v = "已认证"
                        }else{
                            self.user_is_v = '未认证'
                        }
                        console.log(user_info)
                        self.is_student = user_info.is_student
                        self.user_avatar = user_info.avatar
                    }else{
                        self.user_info_empty()
                    }
                }else{
                    self.user_info_empty()
                }
                if(self.user_avatar==''){
                    self.user_avatar='/img/wx/student.jpg'
                }
                self.loading = false
            });
          },
            user_info_empty(){
                this.user_avatar = '/img/wx/student.jpg'
                this.user_name='未知'
                this.user_is_v = '未知'
            },
            check_unread(){
                this.send_request('post','/wx/news/check/unread',function (response,self) {
                    if(response.data.result){
                        self.unread = true;
                    }else{
                        self.unread = false;
                    }
                });
            }

        },
        mounted() {
            this.get_user_info()
            this.check_unread();
        }
    }
</script>
