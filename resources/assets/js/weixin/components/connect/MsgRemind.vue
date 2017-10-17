<!-- 帖子的回复记录 -->
<template>
    <div>
        <x-header>消息记录</x-header>
        <div>
            <div class="label_title">未读消息</div>
            <p v-for="i in remind.length">
                <MsgPack :item="remind[i-1]"></MsgPack>
            </p>
            <div class="label_title">已读消息</div>
        </div>
        <scroller lock-x  height="-40px" use-pulldown use-pullup :pulldown-config="{downContent: '下拉刷新', upContent: '正在更新',loadingContent:''}"
            :pullup-config="{upContent:'', downContent: '',content:'',loadingContent:''}" v-model="status" @on-pulldown-loading="refresh" @on-pullup-loading="getNext"  ref="scrollerObj" >
                <div>
                    <p v-for="i in bottomCount-1">
                        <MsgPack :item="connect[i]"></MsgPack>
                    </p>
                </div>
                 <load-more :show-loading="pdState" v-show="pullDown" :tip="pullTitle"></load-more>
            </scroller>
    </div>
</template>

<script>
    import { Group, Cell,XHeader,Scroller,LoadMore } from 'vux'
    import MsgPack from './MsgPack.vue'
    export default {
        components: {
            Group,XHeader,Scroller,MsgPack,LoadMore,
            Cell
        },
        data(){
            return {
                pullTitle: '正在加载', //Loading的文字提示
                pullDown: false,    //显示下拉加载Loading
                pdState: true,  //是否显示Loading的圆圈
                bottomCount: 1,
                connect: [],
                remind: [],
                limit: 0,
                status: {
                    pullupStatus: 'default',
                    pulldownStatus: 'default'
                }
            }
        },
        methods:{
            getRemind(){
                let self = this
                axios.get("wx/connect/msgRemind").then(function(response){
                    let num = response.data.code
                    if (num>=0) {
                        self.remind = []
                        for(var i=0;i<num;i++)
                            self.remind[i] = response.data.msg[i]
                        console.log(self.remind.length)
                    }
                });
            },
            getConnect(state){
                let self = this
                axios.get("wx/connect/msgRemindScorll?page="+self.limit).then(function(response){
                    let num = response.data.code
                    if (num>0) {
                        if(state==1){
                            self.connect = []
                            self.bottomCount = 1
                        }
                        for(var i=0;i<num;i++)
                            self.connect[self.bottomCount+i] = response.data.msg[i]
                        self.bottomCount += num
                        if(num<6){
                            self.pdState = false
                            self.pullDown = true
                            self.pullTitle = "没有更多数据"
                        }
                    }else{
                        self.pdState = false
                        self.pullDown = true
                        self.pullTitle = "没有更多数据"
                    }
                });
            },
            //下拉刷新（重新刷新数组数据）
            refresh(){
                this.limit = 0
                this.pdState = true
                this.pullDown = false
                this.pullTitle = "正在加载"
                this.getConnect(1)
                this.$nextTick(() => {
                  setTimeout(() => {
                    this.$refs.scrollerObj.donePulldown()
                    this.pullupEnabled && this.$refs.scrollerObj.enablePullup()
                  }, 500)
                })
            },
            //得到下一页
            getNext() {
                if(!this.pullDown&&this.pdState){   //没有加载并且服务器有数据
                    this.pullDown = true
                    setTimeout(() => {
                        ++this.limit
                        this.getConnect(0)
                        if(this.pdState)    //不显示loading圆圈 => 一直显示loading
                            this.pullDown = false
                        this.$nextTick(() => {
                            this.$refs.scrollerObj.reset()
                        })
                    }, 1000)
                }else{
                    if(!this.pdState)   //服务器没有数据显示提示信息
                        this.pullDown = true
                }
                this.$refs.scrollerObj.donePullup()
            },
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
</style>