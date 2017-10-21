<template>
    <div>
<!--          <div class="nav_top">
            <HeaderBar></HeaderBar>
        </div> -->
        <div class="content-tab-wrap">
              <div class="content-tab">
                    <a class="menu" @click="menuControll">
                        <i slot="icon" class="ion-navicon-round"></i>
                    </a>
                  <nav>
                        <a v-for="item,index in nav" @click.prevent="changeTab(index)" :class="{'selected': index === chNav}">{{item}}</a>
                  </nav>
                  <transition name="fade">
                    <ul class="user-menu" v-show="showMenu">
                        <li><i slot="icon" class="ion-edit"></i>
                            <a @click="$router.push('/note/create')">发表帖子</a></li>
                        <li><i slot="icon" class="ion-ios-body"></i>
                            <a @click="$router.push('/note/report')">帖子记录</a></li>
                        <li> <i slot="icon" class="ion-ios-pricetags-outline"></i>
                            <a @click="$router.push('/note/join')">回复记录</a></li>
                        <li><i slot="icon" class="ion-ios-bell"></i>
                            <a @click="$router.push('/note/msg')">消息通知<badge v-if="msg>0" :text="msg" class="note-msg"></badge></a> </li>
                    </ul>
                </transition>
              </div>
        </div>

        <div class="note-scroller">
            <scroller lock-x  height="-40px" use-pulldown use-pullup :pulldown-config="{downContent: '下拉刷新', upContent: '正在更新',loadingContent:''}"
            :pullup-config="{upContent:'', downContent: '',content:'',loadingContent:''}" v-model="status" @on-pulldown-loading="refresh" @on-pullup-loading="getNext"  ref="scrollerObj" >
                <div>
                    <p v-for="i in bottomCount-1">
                        <NotePack :author="connect[i].author" :time="connect[i].update_time" :label="connect[i].label"
                        :read="connect[i].read_num" :comment="connect[i].comment_num">
                             <p slot="title" @click="$router.push('/note/detail/'+connect[i].id)">{{connect[i].title}}</p>
                        </NotePack>
                    </p>
                </div>
                 <load-more :show-loading="pdState" v-show="pullDown" :tip="pullTitle"></load-more>
            </scroller>
        </div>

        <div class="back-to-top" @click="$refs.scrollerObj.reset({top:0})">
            <i  slot="icon" class="iconfont ion-arrow-up-a"></i>
            <p class="text">回到顶部</p>
        </div>

        <loading :show="showLoad" :text="'正在加载'"></loading>

        <Navbottom v-if="msg>0" :msges="msg"></Navbottom>
        <Navbottom v-else></Navbottom>
    </div>
</template>

<script>
    import Navbottom from './NavBottom.vue'
    import NotePack from './connect/NotePack.vue'
    import { Tabbar,TabbarItem,Scroller,Divider,Grid,GridItem,XButton,Spinner,
        Loading,LoadMore,GroupTitle,Group, Cell,Badge } from 'vux'
    Echo.channel('orders')
    .listen('.server.created', (e) => {
        if(Vue.userId==e.userId)
                Vue.$vux.alert.show({
                    title: '消息提醒',
                    content: '论坛收到新的消息',
                })
    });
    export default {
        components: {
            Tabbar,TabbarItem,LoadMore,Loading,
            Group,Cell,Navbottom,NotePack,
            Grid,GridItem,GroupTitle,
            Scroller,Divider,Spinner,XButton,Badge
        },
        data(){
            return {
                msg : 0,
                showLoad: false,
                showMenu: false,
                msgStatus: false,
                pullTitle: '正在加载', //Loading的文字提示
                pullDown: false,    //显示下拉加载Loading
                pdState: true,  //是否显示Loading的圆圈
                bottomCount: 1,
                connect: [],
                nav: ['全部','分享','讨论','提问'],//数据要和数据库中的下标对应
                chNav: 0,
                limit: 0,
                status: {
                    pullupStatus: 'default',
                    pulldownStatus: 'default'
                }
            }
        },
        methods: {
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
            //显示弹出框
            menuControll(){
                this.showMenu = !this.showMenu
            },
            //得到帖子的数据
            //state=》是否重新刷新数组数据
            getConnect(state){
                let self = this
                axios.get("wx/connect?type="+self.chNav+"&page="+self.limit).then(function(response){
                    let num = response.data.code
                    if (num>0) {
                        if(state==1){
                            self.connect = []
                            self.bottomCount = 1
                        }
                        for(var i=0;i<num;i++)
                            self.connect[self.bottomCount+i] = response.data.msg[i]
                        self.bottomCount += num
                        if(num<7){
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
            //切换nav。
            changeTab(index){
                this.pullDown = false
                this.pdState = true
                this.showLoading()
                this.chNav = index
                this.getConnect(1)
            },
            //切换nav显示load
            showLoading () {
                this.$vux.loading.show({
                    text: '努力加载中'
                })
                setTimeout(() => {
                    this.$vux.loading.hide()
                }, 1000)
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
             //更新Cookie里面的数据
            msgCount(){
                let self = this
                axios.get("wx/connect/msgCount").then(function(response){
                    self.msg = response.data.code
                    localStorage.setItem('note-msg',response.data.code)
                })
            },
            userInfo(){
                let self = this
                axios.get("wx/connect/userInfo").then(function(response){
                    Vue.userId = response.data.code
                })
            },
        },

        mounted() {
            this.getConnect(0)
            this.msgCount()
            this.userInfo()
        },
    }
</script>

<style lang="less" scoped>
.position-vertical-demo {
  background-color: #ffe26d;
  color: #000;
  text-align: center;
  padding: 15px;
}
.note-msg{
    position: absolute;
    margin: 15px 5px;
}
.nav_top>.weui-tabbar{
    top: 0px;
    bottom: auto;
    background-color: white;
}
.nav_top .weui-tabbar__icon > i:hover{
    color: #3dbb07;
}
.content-tab-wrap {
    left: 0;
    z-index: 99;
    color: #ffffff;
    width: 100%;
    position: fixed;
    background: rgba(7, 17, 27, 1);
    .content-tab {
        a {
             color: #e8e8e8;
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            padding: 10px 15px;
            display: inline-block;
            &.selected {
                background: black;
            }
        }
        .menu{
            float: right;
            padding-top: 13px;
            padding-right: 15px;
            i{
                display: inline-block;
                transform: scale(1.2);
            }
        }
        .user-menu {
            right: 0;
            width: 100%;
            z-index: 200;
            list-style: none;
            overflow: hidden;
            position: absolute;
            background-color: rgba(7, 17, 27, .8);
            li {
              height: 48px;
              line-height: 48px;
              font-size: 16px;
              padding: 0 30px;
              text-align: center;
              cursor: pointer;
              border-bottom: 1px solid #475669;
              color: #ffffff;
              a {
                padding: 0px;
              }
              .iconfont {
                font-size: 20px;
                padding-right: 5px;
              }
              span {
                vertical-align: top;
              }
            }
        }
        li:hover{
            background-color:black;
        }
        .fade-enter-active, .fade-leave-active {
            transition: opacity .5s
        }
        .fade-enter, .fade-leave-to /* .fade-leave-active in below version 2.1.8 */ {
            opacity: 0
        }
    }
}

.back-to-top {
        position: fixed;
        right: 10px;
        bottom: 80px;
        color: #ffffff;
        text-align: center;
        font-size: 12px;
        background-color: rgba(7, 17, 27, .5);
        padding: 5px;
        border-radius: 10px;
        .iconfont {
          font-size: 24px;
        }
}
</style>
<style type="text/css">
    .note-scroller .xs-container{padding-top: 45px;}
    .note-scroller .xs-plugin-pulldown-container{top: -15px!important;}
</style>
