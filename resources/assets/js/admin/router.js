import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter)

export default new VueRouter({
    saveScrollPosition: true,
    routes: [
    	{
            //name: "自动回复配置",
            path: '/wx/reply',
            component: resolve =>void(require(['./wx/reply.vue'], resolve))
        },
        {
            //name: "微信基础配置",
            path: '/wx/config',
            component: resolve =>void(require(['./wx/config.vue'], resolve))
        },
        {
            //name: "模板消息配置",
            path: '/wx/template',
            component: resolve =>void(require(['./wx/template.vue'], resolve))
        },
        {
            //name: "微信菜单配置",
            path: '/wx/menu',
            component: resolve =>void(require(['./wx/menu.vue'], resolve))
        },
    ]
})
