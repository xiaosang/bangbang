<template>
    <div>
        <x-header>{{content['title']}}</x-header>
        <div class="content">
            <p class="content-title">{{content['title']}}</p>
            <div class="connect-mid" v-html = "content['content']"></div>
            <blockquote class="connect-foot">
                <span class="author">
                    <i slot="icon" class="ion-person"></i>
                    <span>{{content['name']}}</span>
                </span>
                <span class="time">
                    <i slot="icon" class="ion-ios-calendar-outline"></i>
                    <span>{{content['time']}}</span>
                </span>
            </blockquote>

            <group title="评论">
                <x-textarea  :placeholder="placeholder" v-model="message.content" label="评论" :show-counter="false" :rows="1" autosize></x-textarea>
                <cell>
                     <x-button mini plain type="primary" @click.native="cancelMsg">取消</x-button>
                    <x-button mini plain type="primary" @click.native="submitMsg($event)">提交</x-button>
                </cell>

                <cell value-align="left">
                    <div class="comment" v-for="items,index in reply">
                        <div class="comment-top">
                            <a href="" class="avatar">
                                <img src="" alt=" ">
                                <span>{{items.name}}</span>
                            </a>
                            <!-- 回复 -->
                            <span class="comment-res" @click="changeReply(items.id,items.cid,items.name)">
                                <i slot="icon" class="ion-ios-compose-outline"></i>
                            </span>
                            <!-- 删除 -->
                            <span v-if="items.cid==userId" class="comment-res" @click="showDelete(items.id,index,-1)">
                                <i slot="icon" class="ion-ios-trash-outline"></i>
                            </span>
                        </div>
                        <p>{{items.content}}</p>
                        <div class="comment-btm"><span>{{items.time}}</span></div>

                        <div class="sub-comment-list">
                            <div class="sub-comment" v-for="item,ind in items.reply">
                                <a href="">{{item.name}}</a>
                                <span>: <a href="">@{{item.reply_name}}</a>
                                    {{item.content}}
                                </span>
                                <div class="comment-btm">
                                    <span>{{item['time']}}</span>
                                    <span class="choose" @click="changeReply(item.reply_id,item.cid,item.name)">回复</span>
                                    <span class="choose" v-if="item.cid==userId" @click="showDelete(item.id,index,ind)">删除</span>
                                </div>
                            </div>
                        </div>
                        <div class="more-comment" @click="moreComment($event)" v-if="items.reply" >
                            <a v-if="items.reply.length>4">更多回复</a>
                        </div>
                    </div>
                    <div v-show="showEnd">
                        <span class="load-more" v-show="!pullDown" @click="getNext">加载更多评论 <i slot="icon" class="ion-chevron-down"></i></span>
                        <load-more :show-loading="pdState" v-show="pullDown" :tip="pullTitle"></load-more>
                    </div>
                </cell>
            </group>
        </div>
        <toast v-model="remindState" type="text">{{remind}}</toast>
        <confirm
              :title="'删除评论'"
              theme="android"
              v-model="showDel"
              @on-confirm="deleteMsg">
                <p style="text-align:center;">确认删除这条评论吗？</p>
            </confirm>
    </div>
</template>

<script>
    import { Toast,Group, Cell,XHeader,XTextarea,XButton,LoadMore,Confirm} from 'vux'
    export default {
        components: {
            Group,Cell,XHeader,XTextarea,XButton,
            Toast,LoadMore,Confirm
        },

        data(){
            return {
                showDel:false,//显示删除的弹出框
                delate: {},//执行删除操作的数据参数
                userId: 0,
                content:{},
                note: this.$route.params.id,
                //帖子id、信息、被回复用户id、回复的id、回复姓名
                message: {'noteId':null,'content':"",'userId':0,'reply_id':0,'reply_name':null},
                remindState: false,  //是否显示提醒
                remind: "",   //提示的信息
                reply: [],  //回复信息的数组
                placeholder: "随便写点什么",
                pullTitle: '正在加载', //Loading的文字提示
                pullDown: false,    //显示下拉加载Loading
                pdState: true,  //是否显示Loading的圆圈
                limit: 0,   //当前加载的页数
                showEnd: false, //控制loading和加载的显示
            }
        },
        methods: {
            getDetail(){
                let self = this
                self.message.noteId = self.note
                axios.get('wx/connect/getDetail/'+self.note).then((response) => {
                    if(response.data.code==1){
                        self.content = response.data.msg[0]
                    }else{
                        self.$router.push('/connect')
                    }
                })
            },
            submitMsg(event){
                //var button = event.currentTarget
                let self = this
                if(self.message.content==""){
                    self.remind = '提交的内容不可以为空'
                    self.remindState = true
                }else{
                    //button.setAttribute('disabled','disabled')
                    axios.post('wx/connect/submitMsg',self.message).then((response) => {
                        if(response.data.code>=1){
                            var data = response.data.msg
                            var replyId = self.message.reply_id
                            if(replyId==0){
                                self.reply.splice(0,0,data)
                            }else{
                                for(var i=0;i<self.reply.length;i++){
                                    if(self.reply[i].id==replyId){
                                        self.reply[i].reply.push(data)
                                    }
                                }
                            }
                            self.message.content = ""
                        }else{
                            self.remind = '回复报错啦'
                            self.remindState = true
                        }
                    })
                    //button.removeAttribute('disabled')
                }
            },
            moreComment(event){
                var now = event.currentTarget
                now.setAttribute('class','dis-none')
                var obj = now.previousSibling.previousSibling
                obj.setAttribute('class','auto-hight')
            },
            //一级回复的id，被回复的用户id，用户名
            changeReply(replyId,userId,userName){
                this.message.userId = userId
                this.message.reply_id = replyId
                this.message.reply_name = userName
                this.placeholder = '@'+userName
                $(".weui-textarea").focus();
            },
            //回复的信息id，index顶级评论下标，ind=》二级评论下标
            showDelete(replyId,index,type){
                this.showDel = true
                this.delate = {'id':replyId,'type':type,'nId':this.note,'index':index}
            },
            deleteMsg(){
                let self = this
                let type = self.delate.type
                let index = self.delate.index
                axios.post("wx/connect/deleteMsg",self.delate).then(function(response){
                    if(response.data.code){
                        if(type<0){
                            self.reply.splice(index,1)
                        }else{
                            self.reply[index].reply.splice(type,1)
                        }
                    }else{
                        self.remind = '删除失败'
                        self.remindState = true
                    }
                })
            },
            getMsg(){
                let self = this
                axios.get('wx/connect/getMsg/'+self.note+"?page="+self.limit).then((response) => {
                    let num = response.data.code
                    if(num>0){
                        for(var i=0;i<num;i++)
                            self.reply.push(response.data.msg[i])
                        self.pullDown = false
                    }
                    if(num<6){
                        self.pdState = false
                        self.pullDown = true
                        self.pullTitle = "没有更多数据"
                    }
                    self.showEnd = true
                    console.log(self.reply)
                })
            },
            getNext(){
                ++this.limit
                this.pullDown = true
                this.getMsg()
            },
            cancelMsg(){
                if(this.message.userId!=0){
                    this.message.userId = 0
                    this.message.reply_id = 0
                    this.message.reply_name = null
                    this.placeholder = "随便写点什么"
                    this.message.content = ""
                }else{
                    return
                }
            },
             userInfo(){
                let self = this
                axios.get("wx/connect/userInfo").then(function(response){
                    self.userId = response.data.code
                })
            },
        },
        mounted() {
            this.getDetail()
            this.getMsg()
            this.userInfo()
        }
    }
</script>

<style  lang="less">
.content{
    background-color: white;
}
.content-title{
    font-size: 21px;
    font-family: SimHei, "Helvetica Neue", Helvetica, STHeiTi, sans-serif;
    color: rgb(5, 27, 40);
    font-weight: 500;
    padding: 5px 12px;
}
.connect-mid>p{
    font-size: 14px;
    padding: 5px 12px;
    color: #999999;
    img{
        opacity: .9;
        display: block;
        margin: 0 auto;
        cursor: pointer;
        max-width: 100%;
        border-radius: 2px;
        -webkit-transition: all .25s;
        transition: all .25s;
    }
}
blockquote {
    margin: 0 0 .5em 0;
    background-color: rgb(247, 247, 247);
    border-left: .5em solid #aaa;
    padding: .5em 1em;
}
.connect-foot{
    border-left:aliceblue;
    background-color:white;
    color: #999999;}
span>i{
    padding: 2px 5px 0 0;}
.author{padding-right: 20px;}

//评论样式
.comment-btm{
    margin: 5px 0 0 44px;
    font-size: 12px;
    color: #969696;
}
.comment-btm>span.choose:hover{
    cursor: pointer;
    color: blue;
}
//top
.avatar{float: left;}
.avatar>img{
    border: none;
    width: 34px;
    height: 34px;
    border-radius: 17px;
    display: inline-block;
    vertical-align: text-top;
    background-color: black;
}
.comment{
    padding: 5px 18px;
    margin: auto -18px;
}
.comment p{
    clear: both;
    margin: 5px 0 0 44px;
    font-size: 14px;
    color: #484848;
    line-height: 1.7;
    word-break: break-word !important;
}
.comment-res{
    float: right;
    padding-left: 25px;
}
.comment-res>i{
    float: right;
    transform: scale(1.5);
    cursor: pointer;
}
.comment-res>i:hover{
    color: #e63030;
}
.avatar>span{
    margin-right: 2px;
    color: #484848;
    font-weight: bold;
    padding-left: 7px;
    font-size: 16px;
    line-height: 1.45;
    vertical-align: text-bottom;
}
//评论列表
a{
    color:#3194d0;
}
.sub-comment{
    font-size: 14px;
    line-height: 1.5;
    padding-top: 5px;
    margin: 0 0 0 44px;
    border-top: 1px solid #E6E6E6;
}
.sub-comment>.comment-btm{
    margin: 5px 0 0 0;
}
.sub-comment-list{
    max-height: 225px;
    overflow: hidden;
}
.more-comment{
    margin: 10px 0 0 44px;
    padding-top: 10px;
    text-align: left;
    border-top: 1px solid #E6E6E6;
}
.more-comment{
    font-size: 14px;
}
.auto-hight{
    height: auto;
}
.dis-none{
    display: none;
}

//Loading position
.weui-loading{display:inline-block !important;}

.load-more{
    display: block;
    font-size: 14px;
    margin: 0 -18px;
    text-align: center;
    padding: 10px 0 25px 0;
}
</style>