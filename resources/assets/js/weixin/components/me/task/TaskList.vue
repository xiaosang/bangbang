<template>
    <div style="padding-top:86px">
        <scroller style="padding-bottom: 40px" lock-x ref="scroller" @on-scroll-bottom="get_task_list()" height="-131">
            <div>
               <router-link :to="'/main/task/info/'+item.id" style="margin: 10px;overflow: hidden;display: block;" v-for="item in task_list" :key="item.id">
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
                <div class="tip">
                    <load-more v-if="no_more" :show-loading="false" tip="没有更多" background-color="#fbf9fe"></load-more>
                    <load-more v-if="loading" tip="加载中..." background-color="#fbf9fe"></load-more>
                    <load-more v-if="no_data" :show-loading="false" tip="没有数据" background-color="#fbf9fe"></load-more>
                </div>
           </div>
        </scroller>
        <div class="xuanze">
            <a href="javascript:void(0);" @click="show_choose_type = true">{{type_name[type+1]}}</a>
            <a href="javascript:void(0);" @click="show_choose_status=true">{{status_name[status+1]}}</a>
        </div>
        <actionsheet v-model="show_choose_type" :menus="type_menus" show-cancel @on-click-menu="choose_type"></actionsheet>
        <actionsheet v-model="show_choose_status" :menus="status_menus" show-cancel @on-click-menu="choose_status"></actionsheet>
    </div>
</template>
<style scoped>
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
    .xuanze{
        position: fixed;
        bottom: 0;
        height: 40px;
        width: 100%;
    }
    .xuanze a{
        display: inline-block;
        width: 48%;
        text-align: center;
        border:1px solid #ececec;
        height:40px;
        line-height: 40px;
        color: #acacac;
    }
</style>
<script>
import {XHeader,Scroller,LoadMore,Masker,Actionsheet} from 'vux'
export default {
   components: {
        XHeader,
       Scroller,
       LoadMore,
       Masker,
       Actionsheet
    },
        data(){
            return {
                task_list:[],
                receive_list:[],
                page:0,
                num:10,
                can_get:true,
                type:-1,
                status:-1,
                create_by_me:0,
                no_more:false,
                loading:false,
                no_data:false,
                type_name:['受益(有/无)','有偿','无偿'],
                status_name:['全部(状态)','未接受','已接受','已完成','已结束','已取消'],
                show_choose_type:false,
                show_choose_status:false,
                type_menus:{
                    'all':'全部',
                    'is_fu':'有偿',
                    'no_fu':'无偿'
                },
                status_menus:{
                    'all':'全部',
                    'no_receive':'未接受',
                    'is_receive':'已接受',
                    'is_finish':'已完成',
                    'is_end':'已结束',
                    'is_cancel':'已取消'
                },
            }
        },
        watch:{
          $route:function (from,to) {
              this.init();
          }
        },
        methods:{
            choose_type(key){
                switch(key){
                    case 'all':
                        this.type = -1;
                        this.init();
                        break;
                    case 'is_fu':
                        this.type = 0;
                        this.init();
                        break;
                    case 'no_fu':
                        this.type = 1;
                        this.init();
                        break;
                }
            },
            choose_status(key){
                switch(key){
                    case 'all':
                        this.status = -1;
                        this.init();
                        break;
                    case 'no_receive':
                        this.status = 0;
                        this.init();
                        break;
                    case 'is_receive':
                        this.status = 1;
                        this.init();
                        break;
                    case 'is_finish':
                        this.status = 2;
                        this.init();
                        break;
                    case 'is_end':
                        this.status = 3;
                        this.init();
                        break;
                    case 'is_cancel':
                        this.status = 4;
                        this.init();
                        break;
                }
            },
            get_task_list(){
                if(this.can_get){
                    this.no_more = false;
                    this.loading = false;
                    this.no_data = false;
                    this.can_get = false;
                    if(this.page ==0){
                        this.receive_list = [];
                        this.task_list = [];
                        this.$vux.loading.show({
                            text: '加载中...'
                        });
                    }else{
                        this.loading = true;
                    }
                    let data = {
                        type:this.type,
                        status:this.status,
                        create_by_me:this.create_by_me,
                        num:this.num,
                        page:this.page
                    }
                    this.send_request('post','/wx/me/task/list',function(response,self){
                        if(self.page==0){
                            self.$vux.loading.hide();
                        }else{
                            self.loading = false;
                        }
                        if(response.data.code==1){
                            self.receive_list = response.data.result;
                            if(self.receive_list.length>0){
                                self.task_list.push.apply(self.task_list,self.receive_list);
                                if(self.receive_list.length==self.num){
                                    self.can_get = true;
                                    self.load_more = true;
                                }else{
                                    self.no_more = true;
                                }
                            }else{
                                if(self.task_list.length>0){
                                    self.no_more = true;
                                }else{
                                    self.no_data = true;
                                }
                            }
                            if(self.page>0){
                                self.$nextTick(() => {
                                    self.$refs.scroller.reset();
                                })
                            }
                        }else{
                            self.toast_message(response.data.msg,'warn');
                        }
                    },data);
                }
            },
            init(){
                this.can_get = true;
                this.page = 0;
                let route_name = this.$route.name;
                if(route_name=='release/list'){
                    this.create_by_me = 1;
                }else if(route_name=='receive/list'){
                    this.create_by_me = 0;
                }
                this.get_task_list();
            }

        },
        mounted() {
            this.init();
        }
}
</script>
