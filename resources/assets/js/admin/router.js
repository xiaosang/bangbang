import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter)

export default new VueRouter({
    saveScrollPosition: true,
    routes: [
        {
            path: '/task/list',
            component: resolve =>void(require(['../admin/task/List.vue'], resolve))
        },
    ]
})
