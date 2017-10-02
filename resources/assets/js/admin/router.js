import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter)

export default new VueRouter({
    saveScrollPosition: true,
    routes: [
    	{
            path: '/user/password',
            component: resolve =>void(require(['./user/password.vue'], resolve))
        },
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
        },{
            path: '/task/list',
            component: resolve =>void(require(['./task/List.vue'], resolve))
        },{
            path: '/order/uorder',
            component: resolve =>void(require(['./order/UserOrder.vue'], resolve))
        },{
            path: '/order/payorder',
            component: resolve =>void(require(['./order/PayOrder.vue'], resolve))
        },
    ]
})
