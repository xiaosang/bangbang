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
            component: resolve =>void(require(['./components/Connect.vue'], resolve)),
        },
         {
            name: "发布",
            path: "/createnote",
            component: resolve =>void(require(['./components/connect/CreateNote.vue'], resolve)),
        },
        {
            name: "发表帖子",
            path: "/reportnote",
            component: resolve =>void(require(['./components/connect/ReportNote.vue'], resolve)),
        },
        {
            name: "参与帖子",
            path: "/joinnote",
            component: resolve =>void(require(['./components/connect/JoinNote.vue'], resolve)),
        },
        {
            name: "社交消息",
            path: "/msgnote",
            component: resolve =>void(require(['./components/connect/MsgNote.vue'], resolve)),
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
