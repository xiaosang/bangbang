<template>
    <div>
         <div class="nav_top">
            <HeaderBar></HeaderBar>
        </div>

        <scroller lock-x  height="-40px" use-pulldown use-pullup :pulldown-config="{downContent: '下拉刷新', upContent: '正在更新',loadingContent:''}"
        :pullup-config="{upContent:'', downContent: '',content:'',loadingContent:'aaa'}" v-model="status" @on-pulldown-loading="refresh" @on-pullup-loading="getNext"  ref="scrollerObj" >
            <div class="box2">
                <p v-for="i in bottomCount-1">
                    <NotePack :author="connect[i].name" :time="connect[i].time">
                         <span slot="title" @click="$router.push('/note/detail/'+connect[i].id)">{{connect[i].title}}</span>
                         <span slot="describe">{{connect[i].describe}}</span>
                    </NotePack>
                </p>
            </div>
             <load-more :show-loading="pdState" v-show="pullDown" :tip="pullTitle"></load-more>
        </scroller>

        <Navbottom></Navbottom>
    </div>
</template>

<script>
    import Navbottom from './NavBottom.vue'
    import NotePack from './connect/NotePack.vue'
    import HeaderBar from './connect/HeaderBar.vue'
    import { Tabbar,TabbarItem,Scroller,Divider,Grid,GridItem,XButton,Spinner,LoadMore,GroupTitle,Group, Cell } from 'vux'
    export default {
        components: {
            Tabbar,TabbarItem,LoadMore,
            Group,Cell,Navbottom,NotePack,
            Grid,GridItem,GroupTitle,HeaderBar,
            Scroller,Divider,Spinner,XButton
        },
        data(){
            return {
                pullTitle: '正在加载', //Loading的文字提示
                pullDown: false,    //显示下拉加载Loading
                pdState: true,  //是否显示Loading的圆圈
                bottomCount: 1,
                connect: [],
                limit: 0,
                status: {
                    pullupStatus: 'default',
                    pulldownStatus: 'default'
                }
            }
        },
        methods: {
            //存在不停上拉，一直请求的问题。
            getNext() {
                if(!this.pullDown&&this.pdState){
                    this.pullDown = true
                    setTimeout(() => {
                        ++this.limit
                        this.getConnect(0)
                        //不显示loading圆圈 => 一直显示loading
                        if(this.pdState)
                            this.pullDown = false
                        this.$nextTick(() => {
                            this.$refs.scrollerObj.reset()
                        })
                    }, 1000)
                }else{
                    if(!this.pdState)
                        this.pullDown = true
                }
                this.$refs.scrollerObj.donePullup()
            },
            getConnect(state){
                let self = this
                axios.get("wx/connect?page="+self.limit).then(function(response){
                    let num = response.data.code
                    if (num>0) {
                        if(state==1){
                            self.connect = []
                            self.bottomCount = 1
                        }
                        for(var i=0;i<num;i++)
                            self.connect[self.bottomCount+i] = response.data.msg[i]
                        self.bottomCount += num
                    }else{
                        self.pdState = false
                        self.pullTitle = "没有更多数据"
                    }
                });
            },
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
            }
        },
        mounted() {
            this.getConnect(0)
        },
    }
</script>

<style lang="less">
.nav_top>.weui-tabbar{
    top: 0px;
    bottom: auto;
    background-color: white;
}
.nav_top .weui-tabbar__icon > i:hover{
    color: #3dbb07;
}
</style>
