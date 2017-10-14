<template>
    <div>
        <div class="header">
            <x-header :left-options="{backText:'',preventGoBack:true}" @on-click-back="return_last">问题反馈</x-header>
        </div>
        <div class="content">
            <group class="feed_editor">
                <quill-editor v-model="feedback_content" ref='editor' :options="editorOption"></quill-editor>
                <input ref="input" id="upload" style="display:none" type="file" accept="image/jpg,image/jpeg,image/png,image/gif" @change="upload">
            </group>
            <divider></divider>
                <div id="preview_div">

                </div>
            <divider></divider>
            <x-button class="submit_btn" type="primary" @click.native="submit_feedback">提交</x-button>
        </div>
        <!--<Navbottom></Navbottom>-->
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
    import {XHeader,Group,XButton,Divider} from 'vux'
    export default {
        components: {
            XHeader,
            Group,
            XButton,
            Divider
        },
        data(){
            return {
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
                    placeholder: '反馈内容',
                    theme: 'snow'
                },
                feedback_content:"",
                upload_files:[],
                can_submit:true,
            }
        },
        methods:{
            return_last(){
                this.$router.push('/me')
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
            submit_feedback(){
                if(this.feedback_content.length==0){
                    this.toast_message('反馈内容不能为空','warn');
                    return;
                }
                if(this.can_submit){
                    this.can_submit = false;
                    let formdata = new FormData()
                    formdata.append('feedback_content',this.feedback_content)
                    for (let i=0;i<this.upload_files.length;i++){
                        formdata.append('upload_file'+i,this.upload_files[i])
                    }
                    formdata.append('upload_file_num',this.upload_files.length)
                    this.send_request('post','/wx/me/feedback/submit',function (response,self) {
                        if(response.data.code==1){
                            self.toast_message('感谢您的反馈，我们会继续努力');
                            self.$router.push('/me');
                        }else{
                            self.toast_message(response.data.msg,'warn');
                        }
                        self.can_submit = true;
                    },formdata)


                }
            }

        },
        mounted() {

        }
    }
</script>
