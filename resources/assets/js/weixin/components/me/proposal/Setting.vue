<template>
    <div class="release">
       
        <remote-script src="/js/md5.js" @load="onMD5"></remote-script>
        <group>
            <x-input title='选择学校'  v-model="school_select_one" text-align="right"  placeholder="选择学校" disabled @click.native="selSchool"></x-input>
        </group>
        <group>
            <x-input title='学号' v-model="student_code" :show-clear="false" text-align="center"  placeholder="请输入您的学号"></x-input>
            <x-input title='密码' type="password" v-model="student_psd" :show-clear="false" text-align="center"  placeholder="请输入您的密码"></x-input>
            <x-input title='验证码' v-model="student_img" :show-clear="false" text-align="center"  @on-focus='getFocus'  placeholder="请输入验证码"></x-input>
            <img :src='img_src' id="validateImg" v-if="imgShow">
        </group>
        <group>
            <x-button type="primary"  @click.native="school_enter">登录</x-button>
        </group>
         
        <div v-transfer-dom>
            <popup v-model="addressOnOff">
                <div class="popup1">
                    <group>
                        <x-input v-model="school_select_one" :show-clear="false" text-align="left"  placeholder="选择学校" readonly>
                        </x-input>
                    </group>
                    <p class="prompt">选择学校</p>
                    <group>
                        <picker :data='school_all' v-model='school_select' @on-change='select_school'></picker>
                    </group>
                </div>
            </popup>
        </div>


    </div>
</template>

<script>
    import { XHeader ,XImg,XInput,Group , ToastPlugin,XTextarea , Checker , CheckerItem , Datetime , XSwitch ,XAddress , TransferDom , Popup ,XButton , Picker } from 'vux'
    
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
            XImg,
            ToastPlugin,
        },
        data(){
            return {
                addressOnOff: false,
                // school : [],
                inputSchool : '',
                school_select: [],
                school_select_one:'',
                student_code : '',
                student_psd:'',
                student_img:'',
                school_id : 0,
                img_src:'',
                imgShow: false,
                school_all: [
               
                ],


            }
        },
        methods:{
            selSchool:function(){
                this.addressOnOff = true
            },
            getSchool : function(){
                axios.get('/wx/set/school').then(res => {
                    this.school_all = []
                    this.school_all.push(res.data.result)
                }).catch(err => {
                    this.getSchool()
                })
            },
            select_school: function(value){
                this.school_id = value[0]
                for(let i=0;i<this.school_all[0].length;i++){
                    if(parseInt(this.school_all[0][i].value)==parseInt(value[0])){
                        this.school_select_one = this.school_all[0][i].name
                        break;
                    }
                    
                }
                
            },
            getFocus:function(value){
                var date = new Date()
                this.img_src = '/wx/set/get_check?school_id='+this.school_id+'&current_date='+Date.parse(date)
                this.imgShow = true
            },
            checkV:function(v,psd){
                return md5(md5(v.toUpperCase()).substring(0,30).toUpperCase()+'10467').substring(0,30).toUpperCase()
                
            },
            checkPsd:function(psd){
                return md5(this.student_code+md5(psd).substring(0,30).toUpperCase()+'10467').substring(0,30).toUpperCase()
            },
            school_enter: function(){
                var psd = this.student_psd
                // var ch = $('#check').value
                var check = this.student_img
                var password = psd
                if(this.school_id == 1){
                    check= this.checkV(this.student_img)
                    password = this.checkPsd(psd)
                }
                
                var parm = {
                    user_id: this.student_code, 
                    school_id: this.school_id,
                    password: password,
                    validate : check,
                }
                axios.post('/wx/set/login',parm).then(res => {
                    this.$vux.toast.text(res.data.result)
                    console.log(res.data.code)
                    if(res.data.code == 0){
                        this.$router.push('/main/info/'+this.school_id)
                    }
                }).catch(err => {
                    this.$vux.toast.text('网络异常!', 'top')
                    
                })
            },
            onMD5:function(){
                
            }
        },
        mounted() {
            this.getSchool()
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
