<template>
    <div>
        <div class="gm-breadcrumb">
            <i class="ion-ios-home gm-home"></i>
            <el-breadcrumb separator="/">
                <el-breadcrumb-item>微信模块</el-breadcrumb-item>
                <el-breadcrumb-item>微信用户同步</el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <el-button type="primary" @click="setData">微信用户同步</el-button>
    </div>
</template>

<script type="text/ecmascript-6">
    import { Loading,Message } from 'element-ui';
    export default {
        data() {
            return {
                loadingInstance: null
            }
        },
        methods: {
            setData(){
                let self = this
                self.loadingInstance = Loading.service({text: "请耐心等待，数据正在努力导入中..."});
                axios.post("admin/weixin/user/sync").then(function (response) {
                    self.loadingInstance.close();
                    if (response.data.code == 1) {
                        Message.warning({message: "同步失败"});
                    } else {
                        Message.success({message: "成功同步" + response.data.msg + "条数据"});
                    }
                });
            }
        },
        mounted(){

        }
    }
</script>