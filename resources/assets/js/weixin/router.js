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
            component: resolve =>void(require(['./components/index.vue'], resolve))
        },
    ]
})
