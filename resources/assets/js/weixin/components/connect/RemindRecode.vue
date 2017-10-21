<!-- 帖子的消息记录 -->
<template>
    <div>
        <x-header>消息记录</x-header>
        <div>
            <card :header="{title:'未读消息'}">
                <div slot="content">
                    <x-button mini type="primary" class="msg-btn" @click.native="readMsg">全部标记为已读</x-button>
                    <p v-for="i in remind.length">
                        <MsgPack :item="remind[i-1]" v-on:increment="showSub(remind[i-1].author,remind[i-1].parent_id,remind[i-1].cid,remind[i-1].note_id)" class="msg-remind"></MsgPack>
                    </p>
                </div>
            </card>
            <card :header="{title:'已读消息'}" :footer="{title:pullTitle}" @on-click-footer="clickBottom">
                <x-button mini>submit</x-button>
                <div slot="content">
                    <p v-for="i in bottomCount">
                            <MsgPack :item="connect[i-1]" v-on:increment="showSub(connect[i-1].author,connect[i-1].parent_id,connect[i-1].cid,connect[i-1].note_id)"></MsgPack>
                    </p>
                </div>
            </card>
        </div>

        <confirm
          v-model="submit"
          show-input
          :title="'回复内容'"
          @on-confirm="submitMsg">
          </confirm>
    </div>
</template>

<script>
    import { Group, Cell,XHeader,Scroller,LoadMore,Card,XButton,Confirm } from 'vux'
    import MsgPack from './RemindPack.vue'
    export default {
        components: {
            Group,XHeader,Scroller,MsgPack,LoadMore,
            Cell,Card,XButton,Confirm
        },
        data(){
            return {
                submit: false,
                submitData: {},
                pullTitle: '点击加载', //Loading的文字提示
                pdState: true,  //是否显示Loading的圆圈
                bottomCount: 0,
                connect: [],
                remind: [],
                limit: 0,
            }
        },
        methods:{
            //回复的人的名字、回复的一级id、回复的人的id、帖子的id
            showSub(author,id,cId,noteId){
                this.submit = true
                this.submitData = {'reply_name':author,'reply_id':id,'userId':cId,'noteId':noteId}
            },
            getRemind(){
                let self = this
                axios.get("wx/connect/msgRemind").then(function(response){
                    let num = response.data.code
                    if (num>=0) {
                        self.remind = []
                        for(var i=0;i<num;i++)
                            self.remind[i] = response.data.msg[i]
                    }
                });
            },
            //得到帖子信息；
            getConnect(){
                let self = this
                axios.get("wx/connect/msgRemindScorll?page="+self.limit).then(function(response){
                    let num = response.data.code
                    if (num>0) {
                        for(var i=0;i<num;i++)
                            self.connect[self.bottomCount+i] = response.data.msg[i]
                        self.bottomCount += num
                        if(num<6){
                            self.pdState = false
                            self.pullTitle = "没有更多数据"
                        }
                    }else{
                        self.pdState = false
                        self.pullTitle = "没有更多数据"
                    }
                });
            },
            //得到下一页
            getNext() {
                if(this.pdState){   //没有加载并且服务器有数据
                    ++this.limit
                    this.getConnect()
                }else{
                    if(!this.pdState)   //服务器没有数据显示提示信息
                        this.pullTitle = "没有更多数据"
                }
            },
            readMsg(){
                let self = this
                if(self.remind.length>0){
                    var data = []
                    for(let i=0;i<self.remind.length;i++)
                        data[i] = self.remind[i].id
                    axios.post("wx/connect/readMsg",{'data':data}).then(function(response){
                        let num = response.data.code
                        if(num>0){
                            for(let i=0;i<self.remind.length;i++)
                                    self.connect.splice(0,0,self.remind[i])
                            self.remind = []
                            localStorage.setItem('note-msg',0)
                        }
                    });
                }
            },
            submitMsg(value){
                this.submitData.content = value
                let self = this
                axios.post('wx/connect/submitMsg',self.submitData).then((response) => {
                      if(response.data.code>=1){
                            self.$vux.toast.text('回复成功', 'middle')
                      }else{
                            self.$vux.toast.text('回复失败', 'middle')
                      }
                });
            },
            clickBottom(){
                this.getNext()
            }
        },
        mounted() {
            this.getConnect()
            this.getRemind()
        }
    }
</script>

<style lang="less" scoped>
.label_title{
    padding: 7px;
    background-color: white;
    border-bottom: 2px solid #e8dede;
    color: #827f7f;
    font-weight: bold;
}
.msg-btn{
    float: right;
    top: -35px;
    right: 15px;
}
</style>
<style type="text/css">
.msg-remind .msg-list{
    margin-bottom: 0px !important;
}
</style>