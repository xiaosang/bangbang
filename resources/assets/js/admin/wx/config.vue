<template>
    <div>
        <div class="gm-breadcrumb">
            <i class="ion-ios-home gm-home"></i>
            <el-breadcrumb separator="/">
                <el-breadcrumb-item>微信模块</el-breadcrumb-item>
                <el-breadcrumb-item>微信基础配置</el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <div v-loading.body="loading" element-loading-text="请稍等~">
            <el-row>
                <el-col :span="18">
                    <div>
                        <el-form :model="ruleForm" ref="ruleForm" label-width="150px" class="demo-ruleForm">
                            <el-form-item label="AppID(应用ID)">
                                <el-input v-model="ruleForm.app_id"></el-input>
                            </el-form-item>
                            <el-form-item label="AppSecret(应用密钥)">
                                <el-input v-model="ruleForm.app_secret"></el-input>
                            </el-form-item>
                            <el-form-item label="关注公众号引导页">
                                <el-input v-model="ruleForm.follow_url"></el-input>
                            </el-form-item>
                            <el-form-item label="URL(服务器地址)">
                                <el-input v-model="ruleForm.app_url"></el-input>
                            </el-form-item>
                            <el-form-item label="Token(令牌)">
                                <el-input v-model="ruleForm.app_token"></el-input>
                            </el-form-item>
                            <el-form-item label="消息加解密密钥">
                                <el-input v-model="ruleForm.app_ask"></el-input>
                            </el-form-item>
                            <el-form-item label="消息加解密方式">
                                <el-select v-model="ruleForm.app_type" style="width:100%;" placeholder="请选择">
                                    <el-option v-for="(item,tindex) in types" :key="tindex" :label="item.label" :value="item.value"></el-option>
                                </el-select>
                            </el-form-item>
                            <el-form-item label="开放平台AppID">
                                <el-input v-model="ruleForm.open_id"></el-input>
                            </el-form-item>
                            <el-form-item label="开放平台AppSecret">
                                <el-input v-model="ruleForm.open_sk"></el-input>
                            </el-form-item>
                            <el-form-item>
                                <el-button type="primary" @click="setData">保存</el-button>
                            </el-form-item>
                        </el-form>
                    </div>
                </el-col>
            </el-row>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                ruleForm: {

                },
                types: [{
                    value: "0",
                    label: "明文模式"
                }, {
                    value: "1",
                    label: "兼容模式"
                }, {
                    value: "2",
                    label: "安全模式"
                }],
                loading: false,
            }
        },
        methods: {
            getData(){
                let self = this
                this.loading = true;
                axios.post("admin/weixin/config/get").then(function (response) {
                    self.loading = false;
                    self.ruleForm = response.data;
                });
            },
            setData(){
                let self = this
                this.loading = true;
                axios.post("admin/weixin/config/set", self.ruleForm).then(function (response) {
                    self.loading = false;
                    if(response.data.code == 0){
                        self.$notify({
                            type: 'success',
                            message: '微信配置保存成功!',
                            duration: 2000,
                        });
                    }else{
                        self.$notify({
                            type: 'error',
                            message: '微信配置保存失败!',
                            duration: 2000,
                        });
                    }
                });
            }
        },
        mounted(){
            this.getData();
        }
    }
</script>