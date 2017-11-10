<template>
    <div>
        <div class="header">
            <x-header :left-options="{backText:''}">消息</x-header>
        </div>
        <div class="content">
            <scroller lock-x height="-55" ref="scroller" @on-scroll-bottom="get_news_list" @on-scroll="$refs.scroller.reset()">
                <div>
                    <div class="news" v-for="(nl,index) in  news_list" :key="index">
                        <div class="news_content">
                            {{nl.content}}
                        </div>
                        <div class="news_info">
                            <span class="create_time">{{nl.create_time | date}}</span>
                        </div>
                    </div>
                    <load-more v-if="no_more" :show-loading="false" tip="没有更多" background-color="#fbf9fe"></load-more>
                    <load-more v-if="is_loading" :show-loading="true" tip="加载中..." background-color="#fbf9fe"></load-more>
                    <load-more v-if="no_data" :show-loading="true" tip="没有数据" background-color="#fbf9fe"></load-more>
                </div>
            </scroller>
        </div>
    </div>
</template>

<style scoped>
    .header{
        position: fixed;
        width: 100%;
    }
    .content{
        padding-top: 55px;
        margin: auto;
        width: 95%;
    }

    .news{
        padding-top: 5px;
        border:1px solid #acacac;
        position: relative;
        margin-bottom: 10px;
    }

    .news_content{
        border-bottom: 1px solid #ececec;
        padding-bottom: 5px;
        display: inline-block;
        width: 100%;
        text-indent: 1em;
        word-wrap: break-word;
    }

    .news_info{
        display: inline-block;
        width: 100%;
        margin-top: 5px;
    }

    .create_time{
        float: right;
        color: #acacac;
        margin-right: 10px;
    }

</style>

<script type="text/ecmascript-6">
    import { XHeader,Scroller,LoadMore } from 'vux'
    export default {
        components:{
            XHeader,
            Scroller,
            LoadMore
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
                        this.receive_list = [];
                        this.news_list = [];
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
                                    self.can_get = false;
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