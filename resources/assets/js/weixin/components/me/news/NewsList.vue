<template>
    <div>
        <div class="header">
            <x-header :left-options="{backText:''}">消息</x-header>
        </div>
        <div class="content">
            <div >

            </div>
        </div>
    </div>
</template>

<style scoped>
    .header{
        position: fixed;
        width: 100%;
    }
    .content{
        padding-top: 47px;
    }
</style>

<script type="text/ecmascript-6">
    import { XHeader } from 'vux'
    export default {
        components:{
            XHeader
        },
        data(){
            return {
                num:10,
                page:0,
                receive_list:[],
                news_list:[],
                can_get:true,
                unread:-1,                  //-1 全部     0：未读    1：已读
                is_loading:false,
                no_more:false,
                no_data:false,
            }
        },
        computed: {

        },
        methods: {
            get_news_list(){
                if(this.can_get){
                    this.can_get = false;
                    this.no_more = false;
                    this.no_data = false;
                    if(this.page==0){
                        this.$vux.loading.show({
                            text: '努力加载中...'
                        })
                    }else{
                        this.is_loading = true;
                    }
                    let data = {
                        unread:this.unread,
                        page:this.page,
                        num:this.num
                    };
                    this.send_request('post','/wx/news/list',function (response,self) {
                        if(self.page==0){
                            self.$vux.loading.hide();
                        }else{
                            self.is_loading = false;
                        }
                        if(response.data.code==1){
                            self.receive_list = response.data.result;
                            if(self.receive_list.length>0){
                                self.news_list.push.apply(self.news_list,self.receive_list);
                                if(self.receive_list.length==self.num){
                                    self.can_get = true;
                                    self.page++;
                                }else{
                                    self.can_get = fasle;
                                    self.no_more = true;
                                }
                            }else{
                                if(self.news_list.length>0){
                                    self.no_more = true;
                                }else{
                                    self.no_data = true;
                                }
                            }
                        }else{
                            self.toast_message(response.data.msg,'warn');
                        }
                    },data);
                }
            }
        },
        mounted(){
            this.get_news_list();
        },
    }
</script>