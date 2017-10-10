<template>
    <div style="background-color: white">
        <x-header>发表帖子</x-header>
        <group>
            <x-input title="标题"  v-model="note.title" required placeholder="帖子标题" :max="20"></x-input>
            <selector placeholder="选择帖子类型" title="帖子类型" :options="list" v-model="note.type"></selector>
            <quill-editor v-model="note.content" ref='editor' :options="editorOption"></quill-editor>
            <input ref="input" id="upload" style="display:none" type="file" accept="image/jpg,image/jpeg,image/png,image/gif" @change="upload">
            <Divider></Divider>
            <x-button type="primary" action-type="button" @click.native="setNote">提交</x-button>
        </group>
        <toast v-model="remindState" type="text">{{remind}}</toast>
    </div>
</template>

<script>
    import Uploader from 'vux-uploader'
    import { Toast,Divider,XTextarea,XInput,XButton,XHeader, Actionsheet,
                    TransferDom, ButtonTab, ButtonTabItem,Group, Cell,Selector } from 'vux'
    export default {
        components: {
            Group,Cell,Toast,Uploader,
            XHeader,Actionsheet,ButtonTab,ButtonTabItem,
            XInput,XButton,XTextarea,Divider,Selector
        },
        data(){
            return {
                list: [{key: '1', value: '分享'}, {key: '2', value: '讨论'},{key: '3', value: '提问'}],
                note: {'title':'','content':'','type': 1},
                remindState: false,  //是否显示提醒
                remind: "",   //提示的信息
                editorOption:{
                    modules: {
                        toolbar: {
                            container: ['bold','italic',{'size': [ 'small', false, 'large', 'huge' ]},
                                                { 'list': 'ordered'},{ 'list': 'bullet' },'image'],
                            handlers: {
                                'image': function() {
                                    $('#upload').click()
                                }
                            }
                        }
                    },
                    placeholder: '帖子内容',
                    theme: 'snow'
                },
                uploadUrl: '/wx/connect/noteUpld'
            }
        },
        methods: {
            //文件上传
            upload () {
                let self = this
                let formData = new window.FormData()
                formData.append('img', self.$refs.input.files[0])
                axios.post(self.uploadUrl, formData).then((response) => {
                    if(response.data.code==1){
                        var value = response.data.msg
                        self.addImgRange = self.$refs.editor.quill.getSelection()
                        value = value.indexOf('http') != -1 ? value : 'http:wx/connect/getImg?img=' + value
                        self.$refs.editor.quill.insertEmbed(self.addImgRange != null?self.addImgRange.index:0, 'image', value, Quill.sources.USER)
                    }else{
                        self.remind = response.data.msg
                        self.remindState = true;
                    }
                })
            },
            //新建帖子
            setNote(){
                let self = this
                if(self.note.title==""){
                    self.remind = "文章标题为必填项"
                    self.remindState = true;
                    return;
                }else if(self.note.content==""){
                    self.remind = "文章内容为必填项"
                    self.remindState = true;
                    return;
                }
                axios.post("wx/connect/setNote",self.note).then(function(response){
                    if(response.data.code == 0||response.data.code == 1){
                        self.remind = response.data.msg
                        self.remindState = true;
                        return;
                    }else if(response.data.code == 1){
                        self.remind = "系统错误"
                        self.remindState = true;
                        return;
                    }
                });
            }
        },
        mounted() {
        }
    }
</script>
<style lang="less">
.ql-container {
    height: 120px;
 }
 .ql-snow {
    background-color:white;
 }
</style>