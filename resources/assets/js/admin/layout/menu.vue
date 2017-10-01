<template>
    <div>
        <el-menu v-if="change == 1" :default-active="current" :default-openeds="opened"
                 :router="true">
            <li class="el-menu-item menu_change_h" @click="change_menu(2)"
                style="padding-left: 20px;text-align: -webkit-center;height: 40px;line-height: 40px;">
                <i class="ion-navicon-round rorate" style="font-size: 19px;"></i>
            </li>
            <el-submenu v-for="(v,index) in menu" :key="index" :index.sync="v.path" :router="true">
                <template slot="title"><i :class="v.icon" class="menu-icon"></i>{{v.name}}</template>
                <el-menu-item v-for="(vv,vindex) in v.children" :key="vindex" :index.sync="vv.path">
                    <i :class="vv.icon" class="menu-icon"></i>{{vv.name}}
                </el-menu-item>
            </el-submenu>
        </el-menu>

        <el-menu v-if="change == 2" :default-active="current" :default-openeds="opened"
                 :router="true">
            <li class="el-menu-item menu_change_h" @click="change_menu(1)"
                style="padding-left: 18px;height:40px;line-height: 40px;">
                <i class="ion-navicon-round menu-icon"></i>
            </li>
            <el-submenu v-for="(v,index) in menu" :key="index" :data-menu="v.name" :index.sync="v.path"
                        class="menu_show" :router="true">
                <el-tooltip v-for="(vv,vindex) in v.children" :key="vindex" class="item" effect="dark" :content="vv.name" placement="right">
                    <el-menu-item :index.sync="vv.path" style="padding-right: 18px;padding-left:18px;min-width: initial!important;">
                        <i :class="vv.icon" class="menu-icon"></i>
                    </el-menu-item>
                </el-tooltip>
            </el-submenu>
        </el-menu>
    </div>
</template>
<style scoped>

    .rorate:before {
        transform: rotate(90deg);
        -ms-transform: rotate(90deg);
        -moz-transform: rotate(90deg);
        -webkit-transform: rotate(90deg);
        -o-transform: rotate(90deg);
    }

    .menu_change_h {
        text-align: center !important;
        /* background-color: #d1e3e5; */
    }

    .menu_change_h:hover {
        /* background-color: #a5e3c7 !important; */
    }

    .menu-icon {
        font-size: 18px;
        position: relative;
        top: 2px;
        vertical-align: bottom;
    }

    .menu-icon:after {
        content: " "
    }
</style>
<script>
    export default{
        data(){
            return {
                menu: [],
                current: "",
                opened: [],

                change: 1,
            }
        },
        methods: {
            getMenu(){
                var self = this;
                self.current = self.$route.path
                axios.get('/admin/menu/get').then(function (res) {
                    self.menu = res.data;
                    for (var i = 0; i < self.menu.length; i++) {
                        if (window.location.hash.indexOf(self.menu[i].path) >= 0) {
                            self.opened.push(self.menu[i].path);
                        }
                    }
                }, function () {
                })
            },
            change_menu(val){
                console.log(123)
                menu_style(val, this);
            }
        },
        created(){
            this.getMenu();
        },
    }
    function menu_style(val, self) {
        if (val == 2) {
            self.change = 2;
            $(".page-menu").addClass("page-menu-small");
            $(".page-content").addClass("page-content-small");
            self.$nextTick(function () {
                $(".menu_show .el-submenu__title").bind("mouseover", function () {
                    var top = $(this).offset().top + 12;
                    $(".menu_tool").css("top", top + "px");
                    $(".menu_tool .tool_html").html($(this).parent().attr("data-menu"))
                    $(".menu_tool").show();
                })
                $(".menu_show .el-submenu__title").bind("mouseout", function () {
                    $(".menu_tool").hide();
                })
            })
        } else if (val == 1) {
            self.change = 1;
            $(".page-menu").removeClass("page-menu-small");
            $(".page-content").removeClass("page-content-small");
            self.$nextTick(function () {
                $(".el-submenu__title").unbind("mouseover")
                $(".el-submenu__title").unbind("mouseout")
            })
        }
    }
</script>
