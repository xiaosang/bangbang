<template>
    <div>
        <x-header>发表帖子</x-header>
        <group>
            <x-input title="标题"  v-model="note.title" required placeholder="文章标题" :max="20"></x-input>
        </group>
        <group>
            <x-textarea :max="50" :height='50' v-model="note.describe"  title="描述" placeholder="文章描述"></x-textarea>
        </group>

        <divider></divider>
        <quill-editor v-model="note.content" :options="editorOption"></quill-editor>

        <divider></divider>
        <x-button type="primary" action-type="button" @click.native="setNote">提交</x-button>

        <toast v-model="warned" type="text">{{warn}}</toast>
    </div>
</template>

<script>
    import { Toast,Divider,XTextarea,XInput,XButton,XHeader, Actionsheet, TransferDom, ButtonTab, ButtonTabItem,Group, Cell } from 'vux'
    export default {
        components: {
            Group,Cell,Toast,
            XHeader,Actionsheet,ButtonTab,ButtonTabItem,
            XInput,XButton,XTextarea,Divider,
        },
        data(){
            return {
                note: {'title':'','describe':'','content':''},
                warned: false,
                warn: "",
                editorOption:{
                    modules: {
                        toolbar: [
                            [{'size': [ 'small', false, 'large', 'huge' ]}],
                            ['bold', 'italic'],
                            [{ 'list': 'ordered'},
                            { 'list': 'bullet' }],
                            ['link', 'image']
                        ],
                    },
                    placeholder: '文章内容',
                    theme: 'snow'
                },
            }
        },
        methods: {
            setNote(){
                let self = this
                if(self.note.title==""){
                    self.warn = "文章标题为必填项"
                    self.warned = true;
                    return;
                }else if(self.note.content==""){
                    self.warn = "文章内容为必填项"
                    self.warned = true;
                    return;
                }
                axios.post("wx/connect/setNote",self.note).then(function(response){
                    if(response.data.code == 0||response.data.code == 1){
                        self.warn = response.data.msg
                        self.warned = true;
                        return;
                    }else if(response.data.code == 1){
                        self.warn = "系统错误"
                        self.warned = true;
                        return;
                    }
                });
            }
        },
        mounted() {
//            console.log('Component mounted.')
        }
    }
</script>
<style lang="less">
.ql-container {
    height: 120px;
 }
</style>