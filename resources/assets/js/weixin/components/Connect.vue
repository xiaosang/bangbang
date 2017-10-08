<template>
    <div>
         <div class="nav_top">
            <tabbar>
                <tabbar-item link="/note/create" index="0">
                    <i slot="icon" class="ion-edit"></i>
                    <span slot="label">发表帖子</span>
                </tabbar-item>
                <tabbar-item link="/note/report" index="1">
                    <i slot="icon" class="ion-ios-body"></i>
                    <span slot="label">我的帖子</span>
                </tabbar-item>
                <tabbar-item link="/note/join" index="1">
                    <i slot="icon" class="ion-ios-pricetags-outline"></i>
                    <span slot="label">我参与的</span>
                </tabbar-item>
                <tabbar-item link="/note/msg" index="2">
                    <i slot="icon" class="ion-ios-bell"></i>
                    <span slot="label">消息提醒</span>
                </tabbar-item>
            </tabbar>
        </div>

        <scroller lock-x height="-40px" :use-pulldown=true :use-pullup=true :pulldown-config="{autoRefresh: true,downContent: '下拉刷新', upContent: '释放后更新',loadingContent:''}"
        :pullup-config="{upContent:'', downContent: '',content:'',loadingContent:'aaa'}" @on-pullup-loading="getNext"  ref="scrollerBottom" >
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
    import { Tabbar,TabbarItem,Scroller,Divider,Grid,GridItem,XButton,Spinner,LoadMore,GroupTitle,Group, Cell } from 'vux'
    export default {
        components: {
            Tabbar,TabbarItem,LoadMore,
            Group,Cell,Navbottom,NotePack,
            Grid,GridItem,GroupTitle,
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
            }
        },
        methods: {
            //存在不停上拉，一直请求的问题。
            getNext() {
                if(!this.pullDown&&this.pdState){
                    this.pullDown = true
                    setTimeout(() => {
                        ++this.limit
                        this.getConnect()
                        //不显示loading圆圈 => 一直显示loading
                        if(this.pdState)
                            this.pullDown = false
                        this.$nextTick(() => {
                            this.$refs.scrollerBottom.reset()
                        })
                    }, 1000)
                    this.$refs.scrollerBottom.donePullup()
                }else{
                    if(!this.pdState)
                        this.pullDown = true
                    this.$refs.scrollerBottom.donePullup()
                }
            },
            getConnect(){
                let self = this
                axios.get("wx/connect?page="+self.limit).then(function(response){
                    let num = response.data.code
                    if (num>0) {
                        for(var i=0;i<num;i++)
                            self.connect[self.bottomCount+i] = response.data.msg[i]
                        self.bottomCount += num
                    }else{
                        self.pdState = false
                        self.pullTitle = "没有更多数据"
                    }
                });
            },
        },
        mounted() {
            this.getConnect()
        },
    }
</script>

<style lang="less">
.nav_top{height: 53px;}
.nav_top>.weui-tabbar{
    top: 0px;
    bottom: auto;
    background-color: white;
}
.nav_top .weui-tabbar__icon > i:hover{
    color: #3dbb07;
}
</style>
