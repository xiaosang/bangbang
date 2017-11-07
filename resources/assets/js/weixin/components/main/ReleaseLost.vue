<template>
    <div class="lost">
       <x-header :left-options="{ showBack:false,backText:'' }">
            <span slot="left">
                <a href="javascript:history.go(-1)" class="ion-android-arrow-back" style="font-size: 18px;"></a>
                <span style="font-size: 18px;">发布失物/寻物</span>
            </span>
        </x-header>

        <group>
            <x-input title='标题' :max="15" v-model="name" placeholder="请输入标题，不超过十五字" :show-clear="false"></x-input>
        </group>

        <group>
            <x-textarea  v-model="content" placeholder="输入描述信息..." :max="300" :rows="6" ></x-textarea>
        </group>

        <group>
            <x-input title="类型" disabled >
                <checker
                        v-model="type"
                        default-item-class="demo5-item"
                        selected-item-class="demo5-item-selected"
                        slot="right"
                        :radio-required="true"
                        @on-change="type_change"
                >
                    <checker-item value="1">丢失</checker-item>
                    <checker-item value="2">拾到</checker-item>
                </checker>
            </x-input>
            <x-textarea id="reward_content" v-if="type==1" title='悬赏内容' type="text" v-model="reward_content"    placeholder="给点奖励吧..." :max="300" :rows="3">
            </x-textarea>
        </group>

        <group>
            <datetime v-model="complete_time" format="YYYY-MM-DD HH:mm"  title="时间" placeholder="请选择时间" @on-change="complete_time_change"></datetime>
            <x-input title='地点' v-model="place" placeholder="请输入详细地点..." :show-clear="false" text-align="right"></x-input>
            <!--<x-address title="完成时间" v-model="task_finish_time" :list="task_finish_time_list" value-text-align="right" placeholder="完成任务所需时间"></x-address>-->
            <!--<datetime v-if="task_finish_time.length!=0" v-model="expected_time" format="YYYY/MM/DD HH:mm"  title="选择时间" placeholder="请选择时间" :start-date="startDate" :compute-hours-function="computeHoursFunction" :minute-list="['00','10','20','30','40','50']"  @on-change="expected_time_change"></datetime>-->
        </group>

        <group>
            <x-input title='姓名' v-model="user_name" :show-clear="false" text-align="right"  placeholder="请输入您的姓名(可选)"></x-input>
            <x-input :type="'number'" title='' v-model="user_phone" :show-clear="false" text-align="left"  placeholder="请输入您的联系电话">
                <x-button slot="right" type="primary" mini @click.native="send_yzm">发送验证码</x-button>
            </x-input>
            <x-input title='验证码' v-model="yzm" :show-clear="false" text-align="right"  placeholder="请输入收到的验证码"></x-input>
        </group>



        <group title="添加照片（可选）">
            <input ref="input" id="upload" style="display: none;" type="file" accept="image/jpg,image/jpeg,image/png,image/gif" @change="upload">
            <div id="preview_div" style="float: left;">
            </div>
            <i @click="show" class="ion-android-add" style="
    text-align: center;
    display: inline-block;
    font-size: 40px;
    color: #ddd;
    border: 1px solid #ddd;
    margin: 6px;
    padding: 0px 16px;
">
            </i>
        </group>

        <x-button id="submit" class="submit_btn" type="primary" @click.native="submit_feedback" style="background: #a9a9a9">提交</x-button>

        <!--<remote-script src="/js/fileapi/FileAPI.html5.min.js" @load="onFileApiLoad"></remote-script>-->

    </div>
</template>

<style>
.item .weui-form-preview__hd .weui-form-preview__value{
        font-size: 1em ;
        color: #0BB20C;
    }
    .lost .weui-cells{
        margin-top:0.4em;
    }
</style>
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
<script>
    import { XHeader ,XInput,Group , XTextarea , Checker , CheckerItem , Datetime , XSwitch ,XAddress , TransferDom , Popup ,XButton , Picker , ToastPlugin } from 'vux'
    export default {
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
                name:'',
                content:'',
                type:'',
                reward_content:'',
                user_name:'',
                user_phone:'',
                complete_time:'',
                place:'',
                upload_files:[],
                can_submit:true,
                is_submit:false,
                yzm:''
            }
        },
        methods:{
            type_change(){

            },
            complete_time_change(){

            },
            upload(event){
                if(this.upload_files.length>3){
                    this.toast_message('上传的图片不能超过3张');
                    return ;
                }
                let file = event.target.files[0];
                this.upload_files.push(file);
                this.append_img(file);
            },
            append_img(file){
                let reader = new FileReader();
                let self = this
                reader.onload = function (e) {
                    let data = e.target.result;
                    let preview_div = document.getElementById('preview_div');
                    let img = new Image();
                    img.src = data;
                    img.id = file.name;
                    img.width = 50;
                    img.height = 50;
//                    img.style.margin = '5px';
                    img.style.margin = '5px';
                    img.onclick=function () {
                        document.getElementById(file.name).remove()
                        for(let i=0;i<self.upload_files.length;i++){
                            if(self.upload_files[i]==file){
                                self.upload_files.splice(i,1);
                            }
                        }
                    };
                    preview_div.append(img)
                };
                reader.readAsDataURL(file);
            },
            show(){
                document.getElementById('upload').click()
                document.getElementById('upload').click()
            },
            onFileApiLoad(){

            },
            submit_feedback(){
                if(!this.is_submit){
                    alert('请填写完整信息')
                    return
                }
                if(this.can_submit){
                    this.$vux.loading.show({
                        text: '努力提交中...'
                    })
                    this.can_submit = false;
                    let formdata = new FormData()
                    formdata.append('name',this.name)
                    formdata.append('content',this.content)
                    formdata.append('type',this.type)
                    formdata.append('reward_content',this.reward_content)
                    formdata.append('user_name',this.user_name)
                    formdata.append('user_phone',this.user_phone)
                    formdata.append('complete_time',this.complete_time)
                    formdata.append('place',this.place)
                    for (let i=0;i<this.upload_files.length;i++){
                        formdata.append('upload_file'+i,this.upload_files[i])
                    }
                    formdata.append('upload_file_num',this.upload_files.length)
                    this.send_request('post','/wx/main/submit_lost',function (response,self) {
                        if(response.data.code==1){
                            self.toast_message('发布成功');
                            self.$router.push('/main/lost');
                        }else{
                            self.toast_message(response.data.msg,'warn');
                        }
                        self.$vux.loading.hide();
                        self.can_submit = true;
                    },formdata)
                }
            },
            send_yzm(){
                axios.post('/wx/main/send_yzm',{phone:this.user_phone})
                    .then((res)=>{
                        console.log(res.data.message)
                    })
                    .catch((err)=>{
                        console.log(err)
                    })
            }

        },
        watch:{
            name(){

                if( this.name.trim() && this.content.trim() && this.type.trim() && this.complete_time.trim() && this.place.trim() && this.user_phone.trim() ){
                    this.is_submit = true
                }else{
                    this.is_submit = false
                }

            },
            content(){
                //                console.log(this.name)
                if( this.name.trim() && this.content.trim() && this.type.trim() && this.complete_time.trim() && this.place.trim() && this.user_phone.trim() ){
                    this.is_submit = true
                }else{
                    this.is_submit = false
                }
            },
            type(){
//                console.log(this.name)
                if( this.name.trim() && this.content.trim() && this.type.trim() && this.complete_time.trim() && this.place.trim() && this.user_phone.trim() ){
                    this.is_submit = true
                }else{
                    this.is_submit = false
                }
            },
            complete_time(){
//                console.log(this.name)
                if( this.name.trim() && this.content.trim() && this.type.trim() && this.complete_time.trim() && this.place.trim() && this.user_phone.trim() ){
                    this.is_submit = true
                }else{
                    this.is_submit = false
                }
            },
            place(){
//                console.log(this.name)
                if( this.name.trim() && this.content.trim() && this.type.trim() && this.complete_time.trim() && this.place.trim() && this.user_phone.trim() ){
                    this.is_submit = true
                }else{
                    this.is_submit = false
                }
            },
            user_name(){

            },
            user_phone(){
//                console.log(this.name)
                if( this.name.trim() && this.content.trim() && this.type.trim() && this.complete_time.trim() && this.place.trim() && this.user_phone.trim() ){
                    this.is_submit = true
                }else{
                    this.is_submit = false
                }
            },
            is_submit(){
//                console.log(this.is_submit)
                if(!this.is_submit){
//                    console.log('red')
                    document.getElementById('submit').style.backgroundColor = '#a9a9a9'
                }else{
//                    console.log('yellow')
                    document.getElementById('submit').style.background = '#1AAD19'
                }
            }
        },
        mounted() {

        }
    }
</script>