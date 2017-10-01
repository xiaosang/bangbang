<template>
    <div>
        <div class="gm-breadcrumb">
            <i class="ion-ios-home gm-home"></i>
            <el-breadcrumb separator="/">
                <el-breadcrumb-item>微信模块</el-breadcrumb-item>
                <el-breadcrumb-item>消息模板配置</el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <div v-loading.body="loading" element-loading-text="请稍等~">
            <el-row>
                <el-col :span="24">
                    <div class="wechat-title">
                      <!--  <p>1、在所有操作开始之前，必须先设置行业，请点击
                            <el-button size="small" type="primary" icon="setting">设置行业</el-button>
                            。
                        </p>-->

                        <p>1、
                            <el-button size="small" type="primary" icon="edit">全部更新</el-button>
                            会自动从公众号上获取全部模板消息ID配置到数据库，并到结果显示到页面。
                        </p>

                        <p>2、
                            <el-button size="small" type="primary"><i class="el-icon-edit"></i></el-button>
                            会自动从公众号上获取单条模板消息ID并配置到数据库，并到结果显示到页面。
                        </p>

                        <p>3、
                            <el-button size="small" type="primary" icon="plus">手动保存</el-button>
                            手动输入模板消息ID后配置到数据库。
                        </p>

                        <p>4、微信模板消息所在行业必须设置为IT科技/互联网|电子商务、IT科技/IT软件与服务。 </p>
                    </div>
                    <div style="margin-bottom:20px;">
                      <!--  <el-button size="small" type="primary" @click="setIndustry" icon="setting">设置行业</el-button>-->
                        <el-button size="small" type="primary" @click="autoUpdate" icon="edit">全部更新</el-button>
                    </div>
                    <el-form label-width="300px">
                        <el-form-item v-for="(item,itindex) in items" :key="itindex" :label="getName(item)">
                            <el-input v-model="item.template_id" style="width:60%;"></el-input>
                            <el-button size="small" type="primary" @click="singleUpdate(item)"><i class="el-icon-edit"></i></el-button>
                            <el-button size="small" type="primary" @click="manualUpdate(item)">手动保存</el-button>
                        </el-form-item>
                    </el-form>
                </el-col>
            </el-row>
        </div>
    </div>
</template>
<script>
    export default{
        data() {
            return {
                items: [],
                loading: false,
            }
        },
        methods: {
            getName: function (vo) {
                return vo.wx_name + "（" + vo.template_key + "）";
            },
            getData(){
                let self = this
                this.loading = true;
                axios.post("admin/weixin/template/get").then(function (response) {
                    self.items = response.data;
                    self.loading = false;
                });
            },
            setIndustry() {
                let self = this
                this.loading = true;
                axios.post("admin/weixin/industry").then(function (response) {
                    self.loading = false;
                });
            },
            autoUpdate() {
                let self = this
                this.loading = true;
                axios.post("admin/weixin/auto_update").then(function (response) {
                    self.loading = false;
                    self.items = response.data["result"];
                });
            },
            singleUpdate(vo) {
                let self = this
                this.loading = true;
                axios.post("admin/weixin/update", {tpl_key: vo.template_key, tpl_id: vo.template_id}).then(function (response) {
                    self.loading = false;
                    vo.template_id = response.data["result"];
                });
            },
            manualUpdate(vo) {
                let self = this
                this.loading = true;
                axios.post("admin/weixin/template/set", {tpl_key: vo.template_key, tpl_id: vo.template_id}).then(function (response) {
                    self.loading = false;
                });
            },
        },
        mounted(){
            this.getData();
        }
    }
</script>
