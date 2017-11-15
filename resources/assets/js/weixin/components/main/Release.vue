<template>
    <div class="release">
        <x-header :left-options="{ showBack:false,backText:'' }">
            <span slot="left">
                <!--<a href="back(-1)" class="ion-arrow-left-c"></a>-->
                <!--<a href="javascript:history.go(-1)" class="ion-android-arrow-back" style="font-size: 18px;"></a>-->
                <!--<router-link to="/main" class="ion-android-arrow-back" style="font-size: 18px;"></router-link>-->
                <!--返回到首页-->
                <a href="javascript:history.go(-1)" class="ion-android-arrow-back" style="font-size: 18px;"></a>
                <span style="font-size: 18px;">新建任务</span>
            </span>
            <span slot="right" id="release-text" @click="submit">发布</span>
        </x-header>

        <group>
            <x-input title='标题' :max="15" v-model="name" placeholder="请输入任务的名称，不超过十五字" :show-clear="false"></x-input>
        </group>

        <group>
            <x-textarea  v-model="content" placeholder="输入任务描述..." :max="300" :rows="6" ></x-textarea>
        </group>

        <group>
            <x-input title="任务类型" disabled >
                <checker
                        v-model="type"
                        default-item-class="demo5-item"
                        selected-item-class="demo5-item-selected"
                        slot="right"
                        :radio-required="true"
                        @on-change="type_change"
                >
                    <checker-item value="1">无偿</checker-item>
                    <checker-item value="0">有偿</checker-item>
                </checker>
            </x-input>
            <x-input id="money" v-if="type==0" title='支付金额' type="text" v-model="pay_money"  :show-clear="false" text-align="right"  placeholder="0.00">
                <span slot="right" style="margin-left: 6px;font-size: 12px;"> 元 </span>
            </x-input>
        </group>

        <group>
            <!--<datetime v-model="complete_time" format="YYYY-MM-DD HH:mm"  title="完成时间" placeholder="设置任务完成所需时间" :start-date="startDate" :compute-hours-function="computeHoursFunction" @on-change="complete_time_change"></datetime>-->
            <x-address title="完成时间" v-model="task_finish_time" :list="task_finish_time_list" value-text-align="right" placeholder="完成任务所需时间"></x-address>
            <datetime v-if="task_finish_time.length!=0" v-model="expected_time" format="YYYY/MM/DD HH:mm"  title="截至时间" placeholder="设置任务截至时间" :start-date="startDate" :compute-hours-function="computeHoursFunction" :minute-list="['00','10','20','30','40','50']"  @on-change="expected_time_change"></datetime>
        </group>
        <p v-if="task_finish_time.length!=0" class="prompt">截止时间：到时间没人接任务将被取消</p>

        <group>
            <x-input title='雇主姓名' v-model="user_name" :show-clear="false" text-align="right"  placeholder="请输入您的姓名"></x-input>
            <x-input title='联系电话' v-model="user_phone" :show-clear="false" text-align="right"  placeholder="请输入您的联系电话" readonly></x-input>
        </group>

        <group>
            <x-input title='收货地址' v-model="address" :show-clear="false" text-align="right"  placeholder="请输入您的收货地址" disabled @click.native="addressOnOff=true"></x-input>
            <x-switch title="开启匿名" v-model="is_hide" @on-change="is_hide_change"></x-switch>
        </group>
        <p class="prompt">接到任务的人可以看到您的个人信息</p>
        <div v-transfer-dom>
            <popup v-model="addressOnOff">
                <div class="popup1">
                    <group>
                        <x-input v-model="address_input" :show-clear="false" text-align="left"  placeholder="请输入您的收货地址">
                            <x-button mini type="primary" slot="right" @click.native="address_enter">确认</x-button>
                        </x-input>
                    </group>
                    <p class="prompt">可在个人中心设置收货地址</p>
                    <group>
                        <picker :data='address_all' v-model='address_select' @on-change='select_address'></picker>
                    </group>
                </div>
            </popup>
        </div>


    </div>
</template>

<script>
    import { XHeader ,XInput,Group , XTextarea , Checker , CheckerItem , Datetime , XSwitch ,XAddress , TransferDom , Popup ,XButton , Picker , ToastPlugin  } from 'vux'
    const task_finish_time = require('./task_finish_time.json')
    Vue.use(ToastPlugin)

    export default {
        directives: {
            TransferDom
        },
        components: {
            XHeader,
            XInput,
            Group,
            XTextarea,
            Checker,
            CheckerItem,
            Datetime,
            XSwitch,
            XAddress,
            Popup,
            XButton,
            Picker,
            ToastPlugin
        },
        data(){
            return {
                id:'',
                name: '',
                content:'',
                type:"1",
                pay_money:"",
                temp_pay_money:"",
                complete_time:'',
                expected_time:'',
                is_hide:false,
                startDate:'',
                computeHoursFunction (date, isToday, generateRange) {
                    if (isToday) {
                        return generateRange(new Date().getHours(), 23)
                    } else {
                        return generateRange(0, 23)
                    }
                },
                releaseOnOff:false,
                task_finish_time_list:task_finish_time,
                task_finish_time:[],
                addressOnOff:false,
                address:'',
                user_name:'',
                user_phone:'',
                address_all: [
//                    [//历史地址   个人中心希望有添加删除
//                        {
//                            name: '快速选择',
//                            value: '快速选择'
//                        },
//                        {
//                            name: '河南科技学院',
//                            value: '河南科技学院'
//                        },
//                        {
//                            name: '河南师范大学',
//                            value: '河南师范大学'
//                        }
//                    ]
                ],
                address_input:'',
                address_select:[''],
            }
        },
        methods:{
            expected_time_change(value) {
                let expected_time = Date.parse(new Date(value))/1000
                let now_time = Date.parse(new Date())/1000
                if(expected_time <= now_time){
                    this.$vux.toast.show({
                        width:'16em',
                        text: '截止时间不能小于当前时间!',
                        type:'text',
                        position:'top'
                    })
//                    this.$vux.toast.text('截止时间不能小于当前时间!', 'top','20em')
                }
            },
            type_change(){
                this.pay_money = ""
                this.temp_pay_money = ""
            },
            submit(){
//                this.expected_time = this.expected_time.replace(/\-/g, "/")
                let expected_time = Date.parse(new Date(this.expected_time))/1000
                let now_time = Date.parse(new Date())/1000
                if(this.type == 0 && parseFloat(this.pay_money).toFixed(2) != parseFloat(this.pay_money)){
                    this.$vux.toast.text('请输入正确的金额!', 'top')
                    return false
                }
                if( expected_time <= now_time ){
                    this.$vux.toast.show({
                        width:'16em',
                        text: '截止时间不能小于当前时间!',
                        type:'text',
                        position:'top'
                    })
//                    this.$vux.toast.text('截止时间不能小于当前时间!', 'top')
                    return false
                }
                if(this.releaseOnOff){
                    this.$vux.loading.show({
                        text: '正在发布...'
                    })
                    let param = {
                        type:this.type,
                        name:this.name,
                        content:this.content,
//                        task_finish_time:parseInt(this.task_finish_time[0])+parseInt(this.task_finish_time[1])+parseInt(this.task_finish_time[2])+Date.parse(new Date())/1000,
                        task_finish_time:parseInt(this.task_finish_time[0])+parseInt(this.task_finish_time[1])+parseInt(this.task_finish_time[2]),
                        expected_time:expected_time,
                        pay_money:this.pay_money,
                        address_name:this.address,
                        user_name:this.user_name,
                        user_phone:this.user_phone,
                        is_hide:this.is_hide
                    }

                    new Promise( ( resolve , reject ) => {
                        axios.post('/wx/release/issue_task',param)
                            .then((res)=>{
                            var aaa =false;
                                this.$vux.loading.hide()
                                if(res.data.code == 1){//发布成功

                                    if(this.type == 0){//有偿
                                        this.$vux.loading.show({
                                            text: '生成订单...'
                                        })
                                        //生成订单  $task_id  res.data.result[1]  1->id
                                        let param = {
                                            pay_money : this.pay_money,
                                            task_id : res.data.result[1],
                                            yzm:res.data.result[0]
                                        }

                                        resolve(param)

                                    }else if(this.type == 1){//无偿
                                        this.$router.push({ path: '/main/IssueSuccess/' + res.data.result[0] })//0->key
                                    }
                                }else{
                                    this.$vux.toast.text('发布失败!', 'top')
                                }
                        })
                            .catch((err)=>{
                                this.$vux.toast.text('网络异常!', 'top')
                        })
                    })
                        .then( ( res ) => {
                            return new Promise( ( resolve , reject ) => {
                                axios.post('/wx/release/create_pay_order',res)
                                    .then((result)=>{
                                        this.$vux.loading.hide()
                                        //调用支付接口
                                        let param = {
                                            yzm:res.yzm,
                                            msg:'支付成功！'
                                        }
                                        resolve(param)
                                    })
                                    .catch((error)=>{
                                        this.$vux.toast.text('网络异常!', 'top')
                                    })
                            })
                        } )
                        .then( ( res )=>{
                            this.$vux.toast.show({
                                text: res.msg,
                                time:1000
                            })
                            setTimeout(()=>{
                                this.$router.push({ path: '/main/IssueSuccess/' + res.yzm }) // 0->key
//                                this.$vux.toast.hide()
                            },800)
                        })

                    /*axios.post('/wx/release/issue_task',param)
                        .then((res)=>{
                            this.$vux.loading.hide()
                            if(res.data.code == 1){//发布成功
                                if(this.type == 0){//有偿
                                    alert('有偿')
                                    this.$vux.loading.show({
                                        text: '生成订单...'
                                    })
                                    //生成订单  $task_id  res.data.result[1]  1->id
                                    let param = {
                                        pay_money : this.pay_money,
                                        task_id : res.data.result[1]
                                    }
                                    axios.post('/wx/release/create_pay_order',param)
                                        .then((result)=>{
                                            this.$vux.loading.hide()
                                            //调用支付接口
                                            console.log(result.data.msg)
                                        })
                                        .catch((error)=>{
                                            alert("网络异常，请重新尝试！")
                                        })
                                }else if(this.type == 1){//无偿
                                    this.$router.push({ path: '/main/IssueSuccess/' + res.data.result[0] })//0->key
                                }
                            }else{
                                alert('发布失败');
                            }
                    })
                        .catch((err)=>{
                            alert("网络异常，请重新尝试！")
                    })*/
                }
            },
            select_address (value) {
                this.address_input = value[0]       //["河南科技学院"]
            },
            address_enter(){
                this.address = this.address_input
                this.addressOnOff = false
            },
            get_user_info(){
                axios.get('/wx/release/get_user_info')
                .then((res)=>{
                    this.user_name = res.data.result.name
                    this.user_phone = res.data.result.phone
                    console.log(res.data)
                })
                .catch((err)=>{
                    this.$vux.toast.text('网络异常!', 'top')
                })
            },
            get_address_list(){
                axios.get('/wx/release/get_address_list')
                    .then((res)=>{
                        if(res.data.default){
                            this.address = res.data.default
                            this.address_select = [res.data.default]
                        }
                        if(res.data.result.length != 0){
                            this.address_all.push(res.data.result)
                        }
                        console.log(res.data)
                    })
                    .catch((err)=>{
                        this.$vux.toast.text('网络异常!', 'top')
                    })
            },
            is_hide_change(value){
                this.is_hide = value
            },
            get_task_info(){
                axios.get('/wx/release/get_task_info',{
                    params:{
                        id:this.id,
                        type:'1'
                    }
                }).then((res)=>{
                    if( res.data.code == 3  ){
                        this.type = res.data.result.type.toString()
                        setTimeout(()=>{
                            this.pay_money = res.data.result.pay_money/100
                        },0)
                        this.name = res.data.result.name
                        this.content = res.data.result.content
                        this.task_finish_time = [parseInt(res.data.result.task_finish_time / ( 60 * 60 * 24))*86400+"T",parseInt((res.data.result.task_finish_time % ( 60 * 60 * 24)) / ( 60 * 60))*3600+"H",parseInt((res.data.result.task_finish_time % ( 60 * 60)) /  60 )*60+"M"]
                        this.expected_time = new Date(res.data.result.expected_time*1000).format("yyyy/MM/dd hh:mm")
                        this.address = res.data.result.address_name
                        this.user_name = res.data.result.user_name
                        this.user_phone = res.data.result.user_phone
                        this.is_hide = res.data.result.is_hide?true:false
                    }else if( res.data.code == 4 ){
                        this.$vux.toast.show({
                            text:"您没有发布该任务!",
                            width:"10em",
                            position:"top",
                            type:"text",
                            time:1000
                        })
//                        this.$router.go(-1);
                        this.$router.push({path:"/me/task/release/list"})
                    }else{
                        this.$vux.toast.text("网络异常！", 'top')
                    }
                    this.$vux.loading.hide()
                }).catch((err)=>{
                    this.$vux.toast.text("网络异常！", 'top')
                })
            },
        },
        watch:{
            name(){
                if(this.name&&this.content&&this.task_finish_time.length!=0&&this.expected_time&&this.address&&this.user_name&&this.user_phone){
                    this.releaseOnOff = true
                }else{
                    this.releaseOnOff = false
                }
            },
            content(){
                if(this.name&&this.content&&this.task_finish_time.length!=0&&this.expected_time&&this.address&&this.user_name&&this.user_phone){
                    this.releaseOnOff = true
                }else{
                    this.releaseOnOff = false
                }
            },
            complete_time(){
                if(this.name&&this.content&&this.task_finish_time.length!=0&&this.expected_time&&this.address&&this.user_name&&this.user_phone){
                    this.releaseOnOff = true
                }else{
                    this.releaseOnOff = false
                }
            },
            expected_time(){
                if(this.name&&this.content&&this.task_finish_time.length!=0&&this.expected_time&&this.address&&this.user_name&&this.user_phone){
                    this.releaseOnOff = true
                }else{
                    this.releaseOnOff = false
                }
            },
            address(){
                console.log(this.address)
                if(this.name&&this.content&&this.task_finish_time.length!=0&&this.expected_time&&this.address&&this.user_name&&this.user_phone){
                    this.releaseOnOff = true
                }else{
                    this.releaseOnOff = false
                }
            },
            user_name(){
                if(this.name&&this.content&&this.task_finish_time.length!=0&&this.expected_time&&this.address&&this.user_name&&this.user_phone){
                    this.releaseOnOff = true
                }else{
                    this.releaseOnOff = false
                }
            },
            user_phone(){
                if(this.name&&this.content&&this.task_finish_time.length!=0&&this.expected_time&&this.address&&this.user_name&&this.user_phone){
                    this.releaseOnOff = true
                }else{
                    this.releaseOnOff = false
                }
            },
            releaseOnOff(){
                if(this.releaseOnOff){
                    document.getElementById('release-text').style.backgroundColor = '#ff4a00'
                    document.getElementById('release-text').style.color = '#fff'
                }else{
                    document.getElementById('release-text').style.backgroundColor = 'rgb(241,241,241)'
                    document.getElementById('release-text').style.color = 'unset'
                }
            },

        },
        mounted() {

            this.startDate = new Date().format("yyyy-MM-dd")
            this.get_address_list()
            this.get_user_info()

            /*if(this.$route.params.id){
                this.$vux.loading.show({
                    text: 'Loading'
                })
                this.id = this.$route.params.id
                this.get_task_info()
            }else{
                this.get_user_info()
            }*/
        }
    }
</script>

<style scoped>
    .demo5-item {
        width: 80px;
        text-align: center;
        border-radius: 3px;
        border: 1px solid #ccc;
        background-color: #fff;
        margin-right: 6px;
    }
    .demo5-item-selected {
        background: #ffffff url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAsAAAALCAMAAACecocUAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6QTZDOEJBQ0E3NkIxMTFFNEE3MzJFOUJCMEU5QUM0QkIiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6QTZDOEJBQ0I3NkIxMTFFNEE3MzJFOUJCMEU5QUM0QkIiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDpBNkM4QkFDODc2QjExMUU0QTczMkU5QkIwRTlBQzRCQiIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDpBNkM4QkFDOTc2QjExMUU0QTczMkU5QkIwRTlBQzRCQiIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PnMGp3kAAAAJUExURf9KAP///////4Jqdw0AAAADdFJOU///ANfKDUEAAAAuSURBVHjaTMpBDgAABAPB5f+PlhLUpZMWuQcYMWLEyDN4ymqa5KS4+3G+KAEGACQmAGlKzr56AAAAAElFTkSuQmCC) no-repeat right bottom;
        border-color: #ff4a00;
        color: #ff4a00;
    }
    .prompt{
        opacity: 0.2;
        font-size: 12px;
        text-align: center;
        margin-top: .4em
    }
    #release-text{
        padding: 2px 12px;
        background: rgb(241,241,241);
        /*background: #ff4a00;*/
        /*color: #fff;*/
        border-radius:5%;
        font-size: 18px;
    }
</style>

<style>
    .release .weui-cells{
        margin-top:.4em;
    }
    .release .weui-switch:checked, .release .weui-switch-cp__input:checked ~ .release .weui-switch-cp__box {
        border-color: #ff4a00;
        background-color: #ff4a00;
    }
</style>
