<template>
    <div>
        <blur :blur-amount=40 url="https://o3e85j0cv.qnssl.com/waterway-107810__340.jpg" :height="120">
            <div class="header" style="overflow: hidden;position: relative;">
                <div style="float: left;overflow: hidden;">
                    <img :src="url">
                </div>
                <div style="float: left;">
                    <span>昵称 : {{ create_user_name }}</span><br>
                    <span>信誉积分 : {{ credit_score }}</span><br>
                    <span>任务发布时间 : <br><span v-if="task_create_time">{{ task_create_time | date }}</span></span>
                </div>
            </div>
        </blur>

        <group>
            <div slot="title" class="weui-cells__title">
                <span>悬赏金额</span>
                <span style="color: red;float: right;font-size: 20px;" v-if="task_type == 0">￥{{ task_pay_money/100 }}</span>
                <div style="clear: both;"></div>
            </div>
            <cell-box>{{ task_name }}</cell-box>
        </group>

        <group>
            <div slot="title" class="weui-cells__title">
                <span>任务描述</span>
                <!--<span style="color: red;float: right;font-size: 20px;" v-if="task_type == 0">￥{{ task_pay_money/100 }}</span>-->
                <!--<div style="clear: both;"></div>-->
            </div>
            <cell-box> {{ task_content }} </cell-box>
        </group>

        <group title="限定时间">
            <cell-box> <span v-if="task_finish_time">{{ task_finish_time | formatDuring }}</span> </cell-box>
        </group>

        <group title="收货地址">
            <cell-box> {{ address_name }} </cell-box>
        </group>

        <group title="联系方式" v-if="is_hide == 0">
            <cell-box><a :href="'tel:'+user_phone" style="color: #000;">{{ user_name }}  {{ user_phone }} </a></cell-box>
        </group>

        <p class="prompt" v-else>对方开启了匿名，接受任务后方可查看</p>

        <group v-if="status==0">
            <x-button type="primary" @click.native="submit" >接受任务</x-button>
        </group>
        <group v-else-if="status!=-1">
            <x-button type="primary" @click.native="$router.push({ path: '/main/task/list' })" >返回任务</x-button>
        </group>



        <!--<h1>{{ id }}</h1>-->
    </div>
</template>

<script>
    import { Group, Cell , Blur , CellBox , XButton , ToastPlugin } from 'vux'
    Vue.use(ToastPlugin)

    export default {
        components: {
            Group,
            Cell,
            Blur,
            CellBox,
            XButton,
            ToastPlugin
        },
        data(){
            return {
                id:'',
                url: '/img/wx/wx_avatar.jpg',
                create_user_name:'...',
                credit_score:'...',
                task_create_time:'',
                task_name:'',
                task_content:'',
                task_type:'1',
                task_pay_money:'',
                task_finish_time:'',
                address_name:'',
                user_name:' ',
                user_phone:'',
                is_hide:'0',
                status:'-1',
            }
        },
        methods:{
            get_task_info(){
                axios.get('/wx/release/get_task_info',{
                    params:{
                        id:this.id
                    }
                }).then((res)=>{
                    if(res.data.code == 0 ){//您老手慢了
                        this.$vux.toast.text(res.data.msg, 'top')
                        this.$router.push({ path: '/main/task/list' })
                    }else if( res.data.code == 1 ){
                        this.url = res.data.result.avatar
                        this.create_user_name = res.data.result.create_user_name
                        this.credit_score = res.data.result.credit_score
                        this.task_create_time = res.data.result.create_time
                        this.task_name = res.data.result.name
                        this.task_content = res.data.result.content
                        this.task_type = res.data.result.type
                        this.task_pay_money = res.data.result.pay_money
                        this.task_finish_time = res.data.result.task_finish_time
                        this.address_name = res.data.result.address_name
                        this.user_name = res.data.result.user_name
                        this.user_phone = res.data.result.user_phone
                        this.is_hide = res.data.result.is_hide
                        this.status = res.data.result.status
                    }else{
                        this.$vux.toast.text("网络异常！", 'top')
                    }
                    this.$vux.loading.hide()
                }).catch((err)=>{
                    this.$vux.toast.text("网络异常！", 'top')
                })
            },
            submit(){
                this.$vux.loading.show({
                    text: 'Loading'
                })
                let params = {
                    id:this.id
                }
                axios.post('/wx/task/accept_task',params)
                    .then((res)=>{
                        this.$vux.loading.hide()
                        if(res.data.code == 1){
                            this.$router.push({ path: '/main/AcceptSuccess/' + this.id })//0->key
                        }else if(res.data.code == 2 ){//自己不能接自己的任务
                            this.$vux.toast.show({
                                type:'warn',
                                text: res.data.msg,
                                time:2000
                            })
                        }else{
                            this.$vux.toast.show({
                                type:'warn',
                                text: res.data.msg,
                                time:2000
                            })
                        }
                    })
            },
            test(){
                alert(1)
            }

        },
        watch:{

        },
        mounted() {
            this.$vux.loading.show({
                text: 'Loading'
            })
            this.id = this.$route.params.id
            this.get_task_info()
        }
    }
</script>

<style scoped>
    .header {
        /*text-align: center;*/
        padding-top: 20px;
        padding-left: 20px;
        color: #fff;
        font-size: 12px;
        line-height: 20px;
    }
    .header img {
        margin-right: 20px;
        width: 80px;
        height: 80px;
        border-radius: 50%;
        float: left;
    }
    .prompt{
        opacity: 0.2;
        font-size: 12px;
        text-align: center;
        margin-top: .4em
    }
</style>