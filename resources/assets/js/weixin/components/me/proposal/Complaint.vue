<template>
    <div>
        <div class="header">
            <x-header :left-options="{backText:'',preventGoBack:true}" @on-click-back="return_last">投诉

            </x-header>
        </div>
        <div class="content" v-if="show_type==0">
            <scroller lock-x height="-100" @on-scroll="get_user_list" ref="scroller">
                <group>
                    <radio title="用户列表" :options="user_list" v-model="select_user_id" @on-change="select_user_fun"></radio>
                </group>
            </scroller>
            <load-more v-if="loadmore" tip="加载中..."></load-more>
            <load-more v-if="nomore" :show-loading="false" tip="没有更多" background-color="#fbf9fe"></load-more>
            <load-more v-if="nodata" :show-loading="false" tip="暂无数据" background-color="#fbf9fe"></load-more>
        </div>
        <div class="content" v-if="show_type==1">
            <group class="feed_editor">
                <quill-editor v-model="complaint_content" ref='editor' :options="editorOption"></quill-editor>
                <input ref="input" id="upload" style="display:none" type="file" accept="image/jpg,image/jpeg,image/png,image/gif" @change="upload">
            </group>
            <divider></divider>
            <div id="preview_div">

            </div>
            <divider></divider>
            <x-button class="submit_btn" type="primary" @click.native="submit_complaint">提交</x-button>
        </div>
    </div>
</template>
<style>
    .feed_editor .ql-container {
        height: 120px;
    }
    .feed_editor .ql-snow {
        background-color:white;
    }
</style>
<style scoped>
    .header{
        position:fixed;
        width: 100%;
    }
    .content{
        padding-top: 47px;
    }
    .submit_btn{
        margin-top: 15px;
    }
</style>
<script>
    //    import Navbottom from './NavBottom.vue'
    import {XHeader,Group,XButton,Divider,Radio,Scroller,LoadMore} from 'vux'
    export default {
        components: {
            XHeader,
            Group,
            XButton,
            Divider,
            Radio,
            Scroller,
            LoadMore
        },
        data(){
            return {
                show_type:0,    //0 显示人员列表，1显示输入
                can_get:true,
                page:0,
                num:10,
                receive_list:[],
                user_list:[],
                select_user_id:0,
                nomore:false,
                nodata:false,
                loadmore:false,
                editorOption:{
                    modules: {
                        toolbar: {
                            container: ['bold','italic',{'size': [ 'small', false, 'large', 'huge' ]},
                                { 'list': 'ordered'},{ 'list': 'bullet' },'image'],
                            handlers: {
                                'image': function() {
                                    $('#upload').click()
                                    $('#upload').click()
                                }
                            }
                        }
                    },
                    placeholder: '投诉内容',
                    theme: 'snow'
                },
                complaint_content:"",
                upload_files:[],
                can_submit:true,
            }
        },
        methods:{
            return_last(){
                if(this.show_type==0){
                    this.$router.push('/complaint/list');
                }else if(this.show_type==1){
                    this.show_type = 0;
                    this.select_user_id = 0;
                    this.page = 0;
                    this.get_user_list();
                }
            },
            /**
             * 获得除自己以外的用户列表
             */
            get_user_list(){
                if(this.can_get){
                    this.can_get = false;
                    this.nomore = false;
                    this.nodata = false;
                    //将显示的提示设为false，显示loading
                    if(this.page==0){
                        this.receive_list = [];
                        this.user_list = [];
                        this.$vux.loading.show({
                            text: '努力加载中...'
                        })
                    }else{
                        this.loadmore = ture;
                    }
                    this.send_request('post','/wx/me/user_list',function (response,self) {
                        if(self.page==0){
                            self.$vux.loading.hide();
                        }else{
                            self.loadmore = false;
                        }
                        if(response.data.code==1){
                            self.receive_list = response.data.result
                            if(self.receive_list.length>0){
                                for(let i=0;i<self.receive_list.length;i++){
                                    let object = new Object();
                                    object.key = self.receive_list[i].id;
                                    object.value = self.receive_list[i].name;
                                    self.user_list.push(object);
                                }
                                if(self.receive_list.length==self.num){
                                    self.can_get = true;
                                    self.page++;
                                }else{
                                    self.nomore = true;
                                }
                            }else{
                                if(self.user_list.length>0){
                                    self.nomore = true;
                                }else{
                                    self.nodata = true;
                                }
                            }
                        }else{
                            self.toast_message(response.data.msg,'warn')
                        }
                        self.$nextTick(() => {
                            self.$refs.scroller.reset()
                        })
                    });
                }
            },
            select_user_fun(key){
                this.select_user_id = key;
                this.show_type = 1;
            },
            upload(event){
                if(this.upload_files.length>=3){
                    this.toast_message('上传的图片不能超过4张');
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
                    img.style.marginLeft = '5px';
                    img.style.marginRight = '5px';
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
            /**
             *提交投诉信息
             */
            submit_complaint(){
                if(this.complaint_content.length==0){
                    this.toast_message('投诉内容不能为空','warn');
                    return;
                }
                if(this.can_submit){
                    this.$vux.loading.show({
                        text: '努力提交中...'
                    })
                    this.can_submit = false;
                    let formdata = new FormData()
                    formdata.append('complaint_content',this.complaint_content)
                    formdata.append('complaint_user_id',this.select_user_id)
                    for (let i=0;i<this.upload_files.length;i++){
                        formdata.append('upload_file'+i,this.upload_files[i])
                    }
                    formdata.append('upload_file_num',this.upload_files.length)
                    this.send_request('post','/wx/me/complaint/submit',function (response,self) {
                        self.$vux.loading.hide();
                        if(response.data.code==1){
                            self.toast_message('投诉成功');
                            self.$router.push('/complaint/list');
                        }else{
                            self.toast_message(response.data.msg,'warn');
                        }
                        self.can_submit = true;
                    },formdata)
                }
            }
        },
        mounted() {
            this.get_user_list();
        }
    }
</script>
