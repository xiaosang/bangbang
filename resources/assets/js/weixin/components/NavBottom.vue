<template>
    <div style="height: 53px;">
        <div class="nav_bottom">
            <tabbar v-model="select_num">
                <tabbar-item link="/main" index="0">
                    <i slot="icon" class="ion-ios-home"></i>
                    <span slot="label">首页</span>
                </tabbar-item>
                <tabbar-item link="/connect" index="1">
                    <i slot="icon" class="ion-person-stalker"></i>
                    <span slot="label">社交
                        <div v-if="msges" style="display: inline-block;">
                            <badge v-if="msg!=msges" :text="msges" class="note-msg"></badge>
                            <badge v-else :text="msg" class="note-msg"></badge>
                        </div>
                        <div v-else style="display: inline-block;">
                            <badge v-if="msg>0" :text="msg" class="note-msg"></badge>
                        </div>
                    </span>
                </tabbar-item>
                <tabbar-item link="/me" index="2">
                    <i slot="icon" class="ion-android-person"></i>
                    <span slot="label">我的</span>
                </tabbar-item>
            </tabbar>
        </div>
    </div>
</template>

<style scoped>
    .nav_bottom{
        position: fixed;
        bottom: 0;
        width: 100%;
        z-index: 500;
        background-color: white;
    }
    a{
        text-decoration: none;
    }
    .weui-tabbar__icon i{
        color: #000;
    }
    .note-msg{
        position: absolute;
        bottom: 30px;
    }
</style>

<script type="text/ecmascript-6">
    import {Grid,GridItem,Tabbar, TabbarItem , Loading  , LoadingPlugin,Badge } from 'vux'
    Vue.use(LoadingPlugin)
    export default {
        props: ['msges'],
        components:{
            Grid,Badge,
            GridItem,
            Tabbar,
            TabbarItem,
        },
        data(){
            return {
                select_num:-1,
                msg : 0,
            }
        },
        computed: {

        },
        methods: {
            init(){
                switch(this.$route.path){
                    case '/':
                        this.select_num=0
                        break
                    case '/main':
                        this.select_num = 0
                        break
                    case '/connect':
                        this.select_num = 1
                        break
                    case '/me':
                        this.select_num = 2
                        break
                }
            },
//            change_index(){
//                var self = this
//                self.$vux.loading.show({
//                    text: 'Loading'
//                })
////                setTimeout(function () {
////                    self.$vux.loading.hide()
////                },666)
//            },
            get_note_msg(){
                if(localStorage.getItem('note-msg')==null)
                    localStorage.setItem('note-msg',0)
                this.msg = localStorage.getItem('note-msg')
            }
        },
        mounted(){
            this.init()
            this.get_note_msg()
        },
    }
</script>