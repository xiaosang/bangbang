<template>
    <div>
        <div class="gm-breadcrumb">
            <i class="ion-ios-home gm-home"></i>
            <el-breadcrumb separator="/">
                <el-breadcrumb-item>微信模块</el-breadcrumb-item>
                <el-breadcrumb-item>自动回复配置</el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <div v-loading.body="loading" element-loading-text="请稍等~">
            <el-row>
                <el-col :span="24">
                    <el-tabs v-model="activeName" type="card" @tab-click="handleClick">
                        <el-tab-pane label="关注自动回复" name="first">
                            <div class="tab-box">
                                <div class="wechat-title">
                                    <p>1、 关注自动回复，当用户关注公众号时自动回复的内容。 </p>

                                    <p>2、 自动回复文字内容最多600个汉字。 </p>
                                </div>
                                <el-input type="textarea" :rows="15" placeholder="请输入内容" v-model="follow.content"></el-input>
                                <el-button style="margin-top:10px;" type="primary" @click="saveFollow">保存</el-button>
                            </div>
                        </el-tab-pane>
                        <el-tab-pane label="消息自动回复" name="second">
                            <div class="tab-box">
                                <div class="wechat-title">
                                    <p>1、消息自动回复，当用户关在微信公众号里发送消息关键词未匹配到时自动回复的内容。 </p>

                                    <p>2、消息自动回复，1个小时内回复1—2条内容。 </p>
                                </div>
                                <el-input type="textarea" :rows="15" placeholder="请输入内容" v-model="news.content"></el-input>
                                <el-button style="margin-top:10px;" type="primary" @click="saveNews">保存</el-button>
                            </div>
                        </el-tab-pane>
                        <el-tab-pane label="关键词自动回复" name="third">
                            <div class="tab-box">
                                <div class="wechat-title">
                                    <p>1、关键词自动回复，当用户关在微信公众号里发送消息关键词匹配到时自动回复的内容。 </p>

                                    <p>2、关键字自动回复设置规则上限为200条规则（每条规则名，最多可设置60个汉字）。 </p>

                                    <p>3、每条规则内最多设置10条关键字（每条关键字，最多可设置30个汉字）。 </p>

                                    <p>4、回复内容（每条回复，最多可设置300个汉字）。 </p>
                                </div>
                                <el-button type="primary" @click="create_reply" icon="plus">添加新规则</el-button>
                                <div style="margin-top:10px;" v-show=create.show>
                                    <el-collapse v-model="activeNames">
                                        <el-collapse-item title="新规则" name="1">
                                            <el-form :model="create" ref="create" :rules="rules" label-width="100px" style="margin-top:20px;">
                                                <el-form-item label="规则名称" prop="title">
                                                    <el-input v-model="create.title" style="width:60%;"></el-input>
                                                </el-form-item>
                                                <el-form-item label="关键词">
                                                    <el-input class="input-new-tag" v-if="create.validate" style="width:15%;" v-model="inputValue" ref="saveTagInput" @keyup.enter.native="handleInputConfirm(create)" @blur="handleInputConfirm(create)"></el-input>
                                                    <el-button v-else class="button-new-tag" @click="showInput(create)">+ 新关键词</el-button>
                                                    <el-tag v-for="(tag,cindex) in create.words" :key="cindex" :closable="true" @close="handleClose(create,tag)" type="primary" style="margin-left:10px;">
                                                        {{tag}}
                                                    </el-tag>
                                                </el-form-item>
                                                <el-form-item label="回复内容">
                                                    <el-input type="textarea" :rows="5" v-model="create.content" style="width:60%;"></el-input>
                                                </el-form-item>
                                                <el-form-item>
                                                    <el-button size="small" type="success" @click="saveCreate(create,'create')">保存</el-button>
                                                    <el-button size="small" type="danger" @click="closeCreate(create)">取消</el-button>
                                                </el-form-item>
                                            </el-form>
                                        </el-collapse-item>
                                    </el-collapse>
                                </div>
                                <div style="margin-top:10px;" v-for="(item,kindex) in keyword" :key="kindex">
                                    <el-collapse>
                                        <el-collapse-item :title="item.header" name="1">
                                            <el-form :model="item" :ref="item.id" :rules="rules" label-width="100px" style="margin-top:20px;">
                                                <el-form-item label="规则名称" prop="title">
                                                    <el-input v-model="item.title" style="width:60%;"></el-input>
                                                </el-form-item>
                                                <el-form-item label="关键词">
                                                    <el-input class="input-new-tag" v-if="item.validate" style="width:15%;" v-model="inputValue" ref="saveTagInput" @keyup.enter.native="handleInputConfirm(item)" @blur="handleInputConfirm(item)"></el-input>
                                                    <el-button v-else class="button-new-tag" @click="showInput(item)">+ 新关键词</el-button>
                                                    <el-tag v-for="(tag,windex) in item.words" :key = "windex" :closable="true" @close="handleClose(item,tag)" type="primary" style="margin-left:10px;">
                                                        {{tag}}
                                                    </el-tag>
                                                </el-form-item>
                                                <el-form-item label="回复内容">
                                                    <el-input type="textarea" :rows="5" v-model="item.content" style="width:60%;"></el-input>
                                                </el-form-item>
                                                <el-form-item>
                                                    <el-button size="small" type="success" @click="saveData(item,item.id)">保存</el-button>
                                                    <el-button size="small" type="danger" @click="deleteReply(item)">删除</el-button>
                                                </el-form-item>
                                            </el-form>
                                        </el-collapse-item>
                                    </el-collapse>
                                </div>
                            </div>
                        </el-tab-pane>
                    </el-tabs>
                </el-col>
            </el-row>
        </div>
    </div>
</template>
<script>
    export default{
        data() {
            return {
                follow: {},
                news: {},
                keyword: [],

                list: [],
                create: {
                    show: false,
                    validate: false,
                    result: {},
                    title: "",
                    btn: false,
                    content: "",
                    words: []
                },
                delete_item: {},

                activeName: 'first',
                loading: false,
                activeNames: ['1'],
                inputVisible: false,
                inputValue: '',

                rules: {
                    title: [
                        { required: true, message: '请输入规则名', trigger: 'blur' }
                    ]
                }
            }
        },
        methods: {
            handleClick(tab, event) {
                //console.log(tab, event);
            },
            handleClose(item,tag){
                item.words.splice(item.words.indexOf(tag), 1);
            },
            showInput(item) {
                item.validate = true;
            },
            handleInputConfirm(item) {
                let inputValue = this.inputValue;
                if (inputValue) {
                    if(item.words.length < 10){
                        item.words.push(inputValue);
                    }
                }
                this.inputVisible = false;
                this.inputValue = '';
            },
            create_reply() {
                this.create.show = true;
            },
            getData(){
                let self = this
                this.loading = true;
                axios.post("admin/weixin/reply/get").then(function (response) {
                    self.loading = false;
                    var data = response.data;
                    self.follow = data.follow;
                    self.news = data.news;
                    self.keyword = data.keyword;
                });
            },
            saveFollow() {
                let self = this
                this.loading = true;
                axios.post("admin/weixin/follow/set", self.follow).then(function (response) {
                    self.loading = false;
                    if(response.data.code == 0){
                        self.$notify({
                            type: 'success',
                            message: '保存成功!',
                            duration: 2000,
                        });
                    }else{
                        self.$notify({
                            type: 'error',
                            message: '保存失败!',
                            duration: 2000,
                        });
                    }
                });
            },
            saveNews() {
                let self = this
                this.loading = true;
                axios.post("admin/weixin/news/set", self.news).then(function (response) {
                    self.loading = false;
                    if(response.data.code == 0){
                        self.$notify({
                            type: 'success',
                            message: '保存成功!',
                            duration: 2000,
                        });
                    }else{
                        self.$notify({
                            type: 'error',
                            message: '保存失败!',
                            duration: 2000,
                        });
                    }
                });
            },
            saveCreate(item,vo) {
                let self = this
                this.$refs[vo].validate((valid) => {
                    //console.log(valid)
                    if (valid) {
                        this.loading = true;
                        axios.post("admin/weixin/reply/create", item).then(function (response) {
                            self.loading = false;
                            item.validate = false;
                            item.result = {};
                            if(response.data.code == 0){
                                self.$notify({
                                    type: 'success',
                                    message: '保存成功!',
                                    duration: 2000,
                                });
                            }else if(response.data.code == 2) {
                                self.$notify({
                                    type: 'warning',
                                    message: '规则名重复!',
                                    duration: 2000,
                                });
                            }else if(response.data.code == 2){
                                self.$notify({
                                    type: 'warning',
                                    message: '关键词有重复!',
                                    duration: 2000,
                                });
                            }else{
                                self.$notify({
                                    type: 'error',
                                    message: '保存失败!',
                                    duration: 2000,
                                });
                            }
                            if (response.data.code == 0) {
                                item.title = "";
                                item.content = "";
                                item.words = [];
                                item.show = false;
                                self.keyword.splice(0,0,response.data.result);
                            }
                        });
                    }else{
                        //console.log('error submit!!');
                        return false;
                    }
                });
            },
            closeCreate(item) {
                item.validate = false;
                item.btn = false;
                item.title = "";
                item.content = "";
                item.words = [];
                item.result = {};
                item.show = false;
            },
            saveData(items,vo) {
                let self = this
                this.$refs[vo][0].validate((valid) => {
                    //console.log(valid)
                    if (valid) {
                        self.loading = true;
                        axios.post("admin/weixin/reply/set", items).then(function (response) {
                            self.loading = false;
                            items.btn = false;
                            items.validate = false;
                            items.result = {};
                            if(response.data.code == 0){
                                self.$notify({
                                    type: 'success',
                                    message: '保存成功!',
                                    duration: 2000,
                                });
                            }else if(response.data.code == 2) {
                                self.$notify({
                                    type: 'warning',
                                    message: '规则名重复!',
                                    duration: 2000,
                                });
                            }else if(response.data.code == 2){
                                self.$notify({
                                    type: 'warning',
                                    message: '关键词有重复!',
                                    duration: 2000,
                                });
                            }else{
                                self.$notify({
                                    type: 'error',
                                    message: '保存失败!',
                                    duration: 2000,
                                });
                            }
                        });
                    }else{
                        //console.log('error submit!!');
                        return false;
                    }
                });
            },
            deleteReply(item) {
                var self = this;
                this.loading = true;
                axios.post("weixin/reply/delete/" + item.id).then(function (response) {
                    self.loading = false;
                    if(response.data.code == 0){
                        self.$notify({
                            type: 'success',
                            message: '删除成功!',
                            duration: 2000,
                        });
                    }else{
                        self.$notify({
                            type: 'error',
                            message: '删除失败!',
                            duration: 2000,
                        });
                    }
                    if (response.data.code == 0) {
                        $.each(self.keyword,function(i,n){
                            if(n.id == item.id){
                                self.keyword.splice(i,1);
                                return false;
                            }
                        })
                    }
                });
            },
        },
        mounted(){
            this.getData();
        }
    }
</script>
