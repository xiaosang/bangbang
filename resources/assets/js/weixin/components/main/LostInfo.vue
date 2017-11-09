<template>
    <div>
       <x-header style="background-color:#9EC9BD;" :left-options="{ showBack:false,backText:'' }" :title="lostInfo.title">
            <span slot="left">
                <!--<a href="back(-1)" class="ion-arrow-left-c"></a>-->
                <!--<a href="javascript:history.go(-1)" class="ion-android-arrow-back" style="font-size: 18px;"></a>-->
                <!--<router-link to="/main" class="ion-android-arrow-back" style="font-size: 18px;"></router-link>-->
                <!--返回到首页-->
                <a href="javascript:history.go(-1)" class="ion-android-arrow-back" style="font-size: 18px;color:#FFF">
                <span style="font-size: 18px;color:#FFF"></span></a>
            </span>
        </x-header>
        <div v-if="lostInfo !=[]">
            <group>
                <div>
                    <swiper :list="img" auto style="width:100%;margin:0 auto;" height="280px" dots-class="custom-bottom" dots-position="center"  v-model="index" @click.native="open_img"></swiper>
                    <!-- {{ lostInfo.img_path }} -->
                </div>
                <div class="back">
                    <!-- <group> -->
                        <x-input title="联系人:" readonly v-model="lostInfo.user_name"></x-input>
                        <x-input title="地　点:" readonly v-model="lostInfo.place"></x-input>
                        <x-input title="时　间:" readonly v-model="lostInfo.lost_time"></x-input>
                        <x-input title="内　容:" readonly v-model="lostInfo.content"></x-input>
                        <x-input title="悬　赏:" readonly v-model="lostInfo.reward_content" v-if="lostInfo.is_lost==1"></x-input>
                        <a :href="'tel:'+lostInfo.phone_num" style="color :#000"><x-input title="电　话:" readonly v-model="lostInfo.phone_num"></x-input></a>
                    <!-- </group> -->
                       
                    <div class="over" v-if="lostInfo.status == 1">

                    </div>
                </div>
                
                
            </group>
            <!-- <hr></hr> -->
            
        </div>
        <div v-transfer-dom>
            <x-dialog v-model="show" class="dialog-demo">
                <div class="img-box">
                    <img :src="open_url" style="max-width:100%">
                </div>
                <div @click="show=false">
                    <span class="vux-close"></span>
                </div>
            </x-dialog>
        </div>
    </div>
</template>

<style lang="less" scoped>
@import '~vux/src/styles/close';
.item .weui-form-preview__hd .weui-form-preview__value{
        font-size: 1em ;
        color: #0BB20C;
    }
    .img_css{
        max-width:100%;
    }
.dialog-demo {
  .weui-dialog{
    border-radius: 8px;
    padding-bottom: 8px;
  }
  .dialog-title {
    line-height: 30px;
    color: #666;
  }
  .img-box {
    // height: 350px;
    overflow: hidden;
  }
  .vux-close {
    margin-top: 8px;
    margin-bottom: 8px;
  }
}

.over{
    background-image: url(/img/wx/over.png);
    background-size: 100%;
    height: 300px;
    position: absolute;
    top: -100px;
    z-index: 10000;
    width: 100%;
}
.back{
    background:url('/img/wx/lost.png') no-repeat;
    background-size:100%;
    position:relative;
}
</style>

<script>
    


    import { Group,XInput, XHeader,Swiper ,Swipeout, SwipeoutItem, SwipeoutButton , Grid, GridItem  , FormPreview , Scroller , Divider , ToastPlugin,XDialog,TransferDomDirective as TransferDom   } from 'vux'
    import {filters} from '../../filter.js'
    Vue.use(ToastPlugin)
    var self = this
    export default {
        directives: {
            TransferDom
        },
        components: {
            Group,
            XInput,
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
            ToastPlugin,
            XDialog
        },
        data(){
            return {
                id:0,
                lostInfo:[],
                img:[],
                index:0,
                open_url:'',
                show:false,
            }
                
        },
        methods:{
            getLostInfo:function(){
                axios.post('/wx/main/lost/info',{'id':this.id}).then(res => {
                    console.log(res.data.result)
                    this.lostInfo = res.data.result
                    var img_path = this.lostInfo.img_path
                    if(img_path != ""){
                        var img = img_path.split(";")
                        for(var i=0;i < img.length;i++){
                            this.img.push({
                                img: 'wx/show_img/?name=app/lost/min/' + img[i]
                            });
                        }
                    }else{
                        this.img.push({
                            img: '/img/wx/nopic.png'
                        });
                        console.log(this.img)
                    }
                    this.setDate()
                    this.$vux.loading.hide()
                    
                }).catch(error => {
                    this.$vux.toast.text('网络异常!', 'top')
                })
            },
            open_img:function(){
                this.show = true
                this.open_url = this.img[this.index].img


            },
            setDate: function(){
             this.lostInfo.lost_time = filters.date(this.lostInfo.lost_time)
            }
        },
        mounted() {
            this.$vux.loading.show({
                    text: '正在加载数据...'
            })
            this.id = this.$route.params.id
            this.getLostInfo()
        }
    }
</script>