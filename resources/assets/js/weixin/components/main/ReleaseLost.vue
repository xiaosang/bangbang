<template>
    <div>
       <x-header :left-options="{ showBack:false,backText:'' }">
            <span slot="left">
                <!--<a href="back(-1)" class="ion-arrow-left-c"></a>-->
                <!--<a href="javascript:history.go(-1)" class="ion-android-arrow-back" style="font-size: 18px;"></a>-->
                <!--<router-link to="/main" class="ion-android-arrow-back" style="font-size: 18px;"></router-link>-->
                <!--返回到首页-->
                <a href="javascript:history.go(-1)" class="ion-android-arrow-back" style="font-size: 18px;"></a>
                <span style="font-size: 18px;">发布失物/寻物</span>
            </span>
        </x-header>
        <div>
            
            

        </div>
    </div>
</template>

<style>
.item .weui-form-preview__hd .weui-form-preview__value{
        font-size: 1em ;
        color: #0BB20C;
    }
</style>

<script>
    import {XHeader,Swiper ,Swipeout, SwipeoutItem, SwipeoutButton , Grid, GridItem  , FormPreview , Scroller , Divider , ToastPlugin  } from 'vux'
    Vue.use(ToastPlugin)
    var self = this;
    export default {
        components: {
            XHeader,
            Swiper,
            Swipeout,
            SwipeoutItem,
            SwipeoutButton,
            Grid,
            GridItem,
            FormPreview,
            Scroller,
            Divider,
            ToastPlugin
        },
        data(){
            return {
                id:0,
            }
                
        },
        methods:{
            getLostInfo:function(){
                // console.log(this.id)
            }
        },
        mounted() {
            // this.id = this.$route.params.id
            // this.getLostInfo()
        }
    }
</script>