import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter)

export default new VueRouter({
    saveScrollPosition: true,
    routes: [
    	{
            path: '/super/password',
            component: resolve =>void(require(['./super/password.vue'], resolve))
        },
        //首页
        {
            path: '/',
            component: resolve =>void(require(['./layout/index.vue'], resolve))
        },
        //用户
        {
            path: '/user/list',
            component: resolve =>void(require(['./user/List.vue'], resolve))
        },
        //论坛
        {
            path: '/forum/note',
            component: resolve =>void(require(['./forum/NoteList.vue'], resolve))
        },
        //微信
    	{
            path: '/wx/reply',
            component: resolve =>void(require(['./wx/reply.vue'], resolve))
        },
        {
            path: '/wx/config',
            component: resolve =>void(require(['./wx/config.vue'], resolve))
        },
        {
            path: '/wx/template',
            component: resolve =>void(require(['./wx/template.vue'], resolve))
        },
        {
            //name: "微信菜单配置",
            path: '/wx/menu',
            component: resolve =>void(require(['./wx/menu.vue'], resolve))
        },
        //公告
        {
            path: '/notice/list',
            component: resolve =>void(require(['./notice/List.vue'], resolve))
        },
        {
            path: '/notice/edit',
            component: resolve =>void(require(['./notice/Edit.vue'], resolve))
        },
        //失物招领
        {
            path: '/loafo/list',
            component: resolve =>void(require(['./loafo/List.vue'], resolve))
        },
        {

            //任务管理
            path: '/task/list',
            component: resolve =>void(require(['./task/List.vue'], resolve))
        },{
            path: '/task/overlist',
            component: resolve =>void(require(['./task/OverTask.vue'], resolve))
        },
        {
            //订单管理
            path: '/order/uorder',
            component: resolve =>void(require(['./order/UserOrder.vue'], resolve))
        },{
            path: '/order/payorder',
            component: resolve =>void(require(['./order/PayOrder.vue'], resolve))
        },{
            //论坛管理
            path: '/forum/note',
            component: resolve =>void(require(['./forum/NoteList.vue'], resolve))
        }
    ]
})
