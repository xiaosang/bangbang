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
                        <a @click.prevent="changeTab('all')">全部</a>
                        <a @click.prevent="changeTab('good')">分享</a>
                        <a @click.prevent="changeTab('share')">讨论</a>
                        <a @click.prevent="changeTab('ask')">提问</a>
                  </nav>

                  <transition name="fade">
                    <ul class="user-menu" v-show="showMenu">
                        <li>
                            <i slot="icon" class="ion-edit"></i>
                            <a @click="$router.push('/note/create')">发表帖子</a>
                        </li>
                        <li>
                            <i slot="icon" class="ion-ios-body"></i>
                            <a @click="$router.push('/note/report')">帖子记录</a>
                        </li>
                        <li>
                            <i slot="icon" class="ion-ios-pricetags-outline"></i>
                            <a @click="$router.push('/note/join')">回复记录</a>
                        </li>
                        <li>
                            <i slot="icon" class="ion-ios-bell"></i>
                            <a @click="$router.push('/note/msg')">消息记录</a>
                        </li>
                    </ul>
                </transition>

              </div>

        </div>
        <div style="padding-top: 41px;">
            <scroller lock-x  height="-40px" use-pulldown use-pullup :pulldown-config="{downContent: '下拉刷新', upContent: '正在更新',loadingContent:''}"
            :pullup-config="{upContent:'', downContent: '',content:'',loadingContent:'aaa'}" v-model="status" @on-pulldown-loading="refresh" @on-pullup-loading="getNext"  ref="scrollerObj" >
                <div class="box2">
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
                showMenu: false,
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
            menuControll(){
                this.showMenu = !this.showMenu
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
                        if(num<7){
                            self.pdState = false
                            self.pullTitle = "没有更多数据"
                        }
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
.content-tab-wrap {
    width: 100%;
    line-height: 36px;
    position: fixed;
    left: 0;
    z-index: 99;
    background: rgba(7, 17, 27, .8);
    color: #ffffff;
    border-top: 1px solid rgba(255, 255, 255, .8);
    .content-tab {
        padding: 5px;
        a {
            color: #ffffff;
            text-decoration: none;
            margin-right: 2px;
            font-size: 14px;
            padding: 2px 5px;
            text-align: center;
            &.selected {
                background-color: #ffffff;
                color: #1F2D3D;
                border-radius: 5px;
            }
        }
        .menu{
            float: right;
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
              margin: 0 30px;
              text-align: center;
              border-bottom: 1px solid #475669;
              color: #ffffff;
              a {
                color: #ffffff;
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
        .fade-enter-active, .fade-leave-active {
            transition: opacity .5s
        }
        .fade-enter, .fade-leave-to /* .fade-leave-active in below version 2.1.8 */ {
            opacity: 0
        }

    }
}
</style>
