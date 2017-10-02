import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter)

export default new VueRouter({
    saveScrollPosition: true,
    routes: [
        {
            name:"测试",
            path:'/test',
            component: resolve =>void(require(['./components/Example.vue'], resolve))
        },
        {
            name:"首页",
            path:'/',
            component: resolve =>void(require(['./components/Index.vue'], resolve))
        },
        {
            name:"首页",
            path:'/main',
            component: resolve =>void(require(['./components/Main.vue'], resolve))
        },
        {
            name:"社交",
            path:'/connect',
            component: resolve =>void(require(['./components/Connect.vue'], resolve))
        },
        {
            name:"我的",
            path:'/me',
            component: resolve =>void(require(['./components/Me.vue'], resolve))
        },
        {
            name:"发布",
            path:'/main/release',
            component: resolve =>void(require(['./components/Release.vue'], resolve))
        },
    ]
})
