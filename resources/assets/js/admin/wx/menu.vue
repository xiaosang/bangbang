<template>
    <div>
        <div class="gm-breadcrumb">
            <i class="ion-ios-home gm-home"></i>
            <el-breadcrumb separator="/">
                <el-breadcrumb-item>微信模块</el-breadcrumb-item>
                <el-breadcrumb-item>微信菜单配置</el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <div class="wechat-title">
            <p> 1、 最多创建3个一级菜单，一级菜单名称名字不多于4个汉字或8个字母。</p>

            <p>2、 每个一级菜单下的子菜单最多可创建5个，子菜单名称名字不多于8个汉字或16个字母。</p>

            <p>3、 发布成功后，会在24小时内在手机端同步显示。</p>
        </div>
        {{ name1 }}
        <table v-loading.body="loading" element-loading-text="请稍等~">
            <tr>
                <td>
                    <div class="menu_preview_area">
                        <div class="mobile_menu_preview">
                            <div class="mobile_hd">公众号名称</div>
                            <div class="mobile_bd" id="menu-container">
                                <div class="pre_menu_list" id="menuList">
                                    <el-popover ref="popover1" placement="top">
                                        <div style="width:88px;" id="pop0">
                                            <el-button class="button_text" v-for="(sub,sindex) in sub_button1" :key="sindex" style="display:block;width:100%;margin-left:0px;border-radius:0px;border:0px;border-bottom:1px solid #bfcbd9;padding:10px 10px;" @click="setSubMenu(sub)">{{sub.name}}</el-button>
                                            <el-button style="width:100%;margin-left:0px;border:0px;" @click="addSubMenu(0)"><i class="el-icon-plus"></i></el-button>
                                        </div>
                                    </el-popover>
                                    <el-popover ref="popover2" placement="top">
                                        <div style="width:88px;" id="pop1">
                                            <el-button class="button_text" v-for="(sub,sindex) in sub_button2" :key="sindex" style="display:block;width:100%;margin-left:0px;border-radius:0px;border:0px;border-bottom:1px solid #bfcbd9;padding:10px 10px;" @click="setSubMenu(sub)">{{sub.name}}</el-button>
                                            <el-button style="width:100%;margin-left:0px;border:0px;" @click="addSubMenu(1)"><i class="el-icon-plus"></i></el-button>
                                        </div>
                                    </el-popover>
                                    <el-popover ref="popover3" placement="top">
                                        <div style="width:88px;" id="pop2">
                                            <el-button class="button_text" v-for="(sub,sindex) in sub_button3" :key="sindex" style="display:block;width:100%;margin-left:0px;border-radius:0px;border:0px;border-bottom:1px solid #bfcbd9;padding:10px 10px;" @click="setSubMenu(sub)">{{sub.name}}</el-button>
                                            <el-button style="width:100%;margin-left:0px;border:0px;" @click="addSubMenu(2)"><i class="el-icon-plus"></i></el-button>
                                        </div>
                                    </el-popover>
                                    <el-button v-show="but > 0" class="button_text" style="float:left;margin-left:0px;" :id="0" :class="classBut(0)" @click="setMenu(0)" v-popover:popover1> {{ name1 }} </el-button>
                                    <el-button v-show="but > 1" class="button_text" style="float:left;margin-left:0px;" :id="1" :class="classBut(1)" @click="setMenu(1)" v-popover:popover2> {{ name2 }} </el-button>
                                    <el-button v-show="but > 2" class="button_text" style="float:left;margin-left:0px;" :id="2" :class="classBut(2)" @click="setMenu(2)" v-popover:popover3> {{ name3 }} </el-button>
                                    <el-button v-show="add" style="float:right;margin-left:0px;" :class="classObject()" icon="plus" @click="addMenu"></el-button>
                                </div>

                                <!--<div style="position: absolute;bottom: -50px;z-index: 999;background: #FFF;display: block;width: 100%;height: 50px;border-top: 1px solid #ddd;"></div>-->
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="menu_form_area">
                        <div id="js_none" class="menu_initial_tips to_left" v-show=!form_show style="display: block;background-color: #F4F5F9;border: 1px solid #e1e1e1;">
                            点击左侧菜单进行编辑操作
                        </div>
                        <div id="js_rightBox" class="portable_editor to_left " v-show=form_show style="min-width:680px;">
                            <div class="editor_inner">
                                <div class="editor_inner_title" style="padding-top: 20px; border-bottom: 1px solid #e1e1e1;">
                                    <label class="global_info" id="editor_panel_title" style="color: #44b549;font-size: 20px;font-weight: bold">
                                        菜单名称
                                    </label>
                                    <a href="javascript:void(0);" style="float: right;margin-top: 5px;" @click="delMenu(form)">删除菜单</a>
                                </div>
                                <el-form class="form_bottom" :model="form" ref="form" :rules="rules" label-width="100px" style="margin-left:10%;width:80%;margin-top:30px;">
                                    <el-form-item label="菜单名称" prop="name">
                                        <el-input v-model="form.name"></el-input>
                                    </el-form-item>
                                    <el-form-item label="跳转地址" v-if=!form.sub_button.length prop="url">
                                        <el-input placeholder="订阅者点击该菜单会跳转到的网页地址" v-model="form.url"></el-input>
                                    </el-form-item>
                                    <el-form-item v-else>
                                        <span>已添加子菜单，仅可设置菜单名称。</span>
                                    </el-form-item>
                                </el-form>
                            </div>
                            <span class="editor_arrow_wrp">
                                 <i class="editor_arrow editor_arrow_out"></i>
                                 <i class="editor_arrow editor_arrow_in"></i>
                            </span>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
        <el-button v-if="button_show" style="margin-top:20px;margin-left:40%;z-index: 1000;position: relative;" size="small" type="primary" @click="saveMenu">保存并发布</el-button>
    </div>
</template>

<script>
    import elPopover from './elPopover.vue';

    export default {
        components:{elPopover},
        data() {
            var self = this;
            var checkName = (rule, value, callback) => {
                if (value == '') {
                    callback(new Error('菜单名称不能为空'));
                } else if(self.form.name.length > self.form.max){
                    callback(new Error('允许输入的最大长度为' + self.form.max));
                }else{
                    callback();
                }
            };
            var checkUrl = (rule, value, callback) => {
                if(self.form.sub_button.length == false){
                    var reg = /^([hH][tT]{2}[pP]:\/\/|[hH][tT]{2}[pP][sS]:\/\/)(.*)$/;
                    if (value == '') {
                        callback(new Error('跳转地址不能为空'));
                    } else if(!reg.test(self.form.url)){
                        callback(new Error('跳转地址必须以http://或https://开头'));
                    }else{
                        callback();
                    }
                }else{
                    callback();
                }
            };
            return {
                menus: [],
                add: true,
                but: 0,

                sub_button1: [],
                sub_button2: [],
                sub_button3: [],
                name1: "菜单名称",
                name2: "菜单名称",
                name3: "菜单名称",

                form_show: false,
                button_show: false,
                form: {
                    "id": 0,
                    "add_sub": true,
                    "name": "",
                    "sub_button": [],
                    "validate": false,
                    "result": {},
                    "url": "",
                    "max": 0,
                    "show":false,
                },

                loading: false,
                rules: {
                    name: [
                        { required: true, validator: checkName, trigger: 'blur' }
                    ],
                    url: [
                        { required: true, validator: checkUrl, trigger: 'blur' }
                    ]
                }
            }
        },
        watch: {
            menus: {
                handler: function (val, oldVal) {
                    if(val.length > 0){
                        this.name1 = val[0].name
                        if(val.length > 1){
                            this.name2 = val[1].name
                            if(val.length > 2){
                                this.name3 = val[2].name
                            }
                        }
                    }
                    console.log(this.name1,this.name2,this.name3)
                },
                deep: true
            },
            name1:function (val,oldval) {
//                this.$set(this.name1,"123")
                console.log(this.name1)
            }

        },
        methods: {
            classObject() {
                if (this.menus.length == 3) {
                    return "size1of" + (this.but);
                } else {
                    return "size1of" + (this.but + 1);
                }
            },
            classBut(id) {
                if (this.menus.length == 3) {
                    return "size1of" + (this.but) + " item" + id;
                } else {
                    return "size1of" + (this.but + 1) + " item" + id;
                }
            },
            hidePopover1(){
                //console.log(11)
            },
            getData(){
                let self = this
                this.loading = true;
                axios.post("admin/weixin/menu/get").then(function (response) {
                    var data = response.data;
                    self.menus = data;
                    console.log(self.menus,data)
                    if(data.length > 0){
                        self.sub_button1 = data[0].sub_button
                        if(data.length > 1){
                            self.sub_button2 = data[1].sub_button
                            if(data.length > 2){
                                self.sub_button3 = data[2].sub_button
                            }
                        }
                    }
                    self.but = data.length;
                    self.loading = false;
                });
            },
            saveMenu() {
                let self = this
                if(this.button_show){
                    this.$refs['form'].validate((valid) => {
                        //console.log(valid)
                        if (valid) {
                            self.loading = true;
                            axios.post("admin/weixin/menu/set", {data: self.menus}).then(function (response) {
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
                                        message: '保存失败,请检查后重新提交!',
                                        duration: 2000,
                                    });
                                }
                            });
                        }else{
                           // console.log('error submit!!');
                            return false;
                        }
                    });
                }
            },
            setMenu(i) {
                this.button_show = true;
                this.form_show = true;
                this.form = this.menus[i];
            },
            addMenu() {
                this.but ++;
                this.menus.push({
                    "id": this.menus.length,
                    "add_sub": true,
                    "name": "菜单名称",
                    "sub_button": [],
                    "validate": true,
                    "result": {},
                    "url": "",
                    "max": 4,
                    "show":true,
                    "level": 1,
                });
            },
            delMenu(vo){
                checkMenu(this.menus,vo,this);
                this.form_show=false;
            },
            addSubMenu(i){
                if(this.menus[i].sub_button.length <= 4){
                    this.menus[i].sub_button.push({
                        "id":new Date().getTime(),
                        "name": "子菜单名称",
                        "type": "view",
                        "url": "",
                        "max": 8,
                        "sub_button": [],
                        "validate": true,
                        "result": {},
                        "level": 2,
                    });
                    let _id = "#pop" + i;
                    let popver = $(_id).parent(".el-popover")
                    let top = popver.css("top");
                    popver.css("top", (parseInt(top) - 35) + "px");
                    if(i == 0){
                        this.sub_button1 = this.menus[i].sub_button
                    }else if(i == 1){
                        this.sub_button2 = this.menus[i].sub_button
                    }else if(i == 2){
                        this.sub_button3 = this.menus[i].sub_button
                    }

                    this.$refs['form'].validate()
                }
            },
            setSubMenu(sub){
                this.form_show = true;
                this.form = sub;
            },
        },
        mounted(){
            this.getData();
        }
    }

    function checkMenu(menus,item,e){
        for(var i=0;i<menus.length;i++){
            if(menus[i]["sub_button"].length>0){
                checkMenu(menus[i]["sub_button"],item,e);
            }
            if(menus[i].id==item.id){
                if(menus[i].level == 1){
                    e.but--;
                    if(i == 0){
                        e.sub_button1 = e.sub_button2
                        e.sub_button2 = e.sub_button3
                        e.sub_button3 = []
                    }else if(i == 1){
                        e.sub_button2 = e.sub_button3
                        e.sub_button3 = []
                    }
                }
                menus.splice(i,1);
                try{
                    let _id = ".item" + item.id;
                    let top=$(_id).parents(".el-popover").css("top").replace("px","");
                    $(_id).parents(".el-popover").css("top", (parseInt(top)+41) + "px");
                }catch(e){

                }
            }
        }
    }
</script>
