<template>
    <div>
        <div class="gm-breadcrumb">
            <el-button type="text" class="gm-back" @click="$router.go(-1)"><i class="el-icon-arrow-left"></i></el-button>
            <el-breadcrumb separator="/">
                <el-breadcrumb-item>商品管理</el-breadcrumb-item>
                <el-breadcrumb-item>编辑商品</el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <el-row>
            <el-col :span="24">
                <el-form :model="notice" label-width="100px":rules="rules" ref="ruleForm">
                    <el-form-item label="公告内容" prop="content">
                        <el-input type="textarea" autosize class="name" v-model="notice.content" placeholder="必填"></el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="onSubmitClick('ruleForm')" :loading="isSubmiting">提 交</el-button>
                        <router-link to="/notice/list">
                            <el-button>取 消</el-button>
                        </router-link>
                    </el-form-item>
                </el-form>
            </el-col>
        </el-row>
    </div>
</template>
<style scoped>
</style>
<script>

    export default{
        data(){
            return{
                isSubmiting: false,
                loading:false,
                rules: {
                    content: [
                        { required: true, message: '请输入公告内容'}
                    ],
                },
                notice: {id:0,content:''},
                loading: false,
            }
        },
        methods:{
            onSubmitClick: function(formName){
                var self = this
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        this.isSubmiting = true;
                        axios.post('admin/notice/edit', self.notice).then((result)=>{
                            self.$message({
                                title: '提示',
                                message: result.data.msg,
                                type: result.data.code==0?'success':'info'
                            });
                            this.isSubmiting = false;
                            //跳转
                            if(result.data.code==0) {
                                this.$router.push({
                                    path: '/notice/list'
                                })
                            }



                        }).catch(res => {
                            self.$message({
                                title: '提示',
                                message: "网络错误",
                                type: 'info'
                            });
                            this.isSubmiting = false;
                        })
                    }
                })
            },
            load(id){
                var self = this;
                self.loading = true;
                var params = {
                    id: id,
                };
                console.log(params)
                axios.get('admin/notice/get',{params: params}).then((result)=>{
//                    if(result){
//                        self.notices = result.data;
//                        self.pagination.total = result.total;
//
//                        self.loading=false;
//                    }
//                    else {
//                        console.log(error);
//                        self.loading=false;
//                    }
                    self.notice = result.data.result
                    console.log(self.notice,result.data.result)
                    self.loading=false

                });

            },
        },
        mounted(){
            let id = Number.parseInt(this.$route.query.id);
            if(id > 0){
                //加载对象
                this.load(id);
            }
        }
    }
</script>
