<template>
    <div>
        <div class="nav_bottom">
            <tabbar v-model="select_num" @on-index-change="change_index">
                <tabbar-item link="/main" index="0">
                    <i slot="icon" class="ion-ios-home"></i>
                    <span slot="label">首页</span>
                </tabbar-item>
                <tabbar-item link="/connect" index="1">
                    <i slot="icon" class="ion-person-stalker"></i>
                    <span slot="label">社交</span>
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
</style>

<script type="text/ecmascript-6">
    import {Grid,GridItem,Tabbar, TabbarItem , Loading  , LoadingPlugin } from 'vux'
    Vue.use(LoadingPlugin)
    export default {
        components:{
            Grid,
            GridItem,
            Tabbar,
            TabbarItem,
        },
        data(){
            return {
                select_num:-1
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
            change_index(){
                var self = this
                self.$vux.loading.show({
                    text: 'Loading'
                })
                setTimeout(function () {
                    self.$vux.loading.hide()
                },666)
            }
        },
        mounted(){
            this.init()
        },
    }
</script>