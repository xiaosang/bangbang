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
            path: "/note/create",
            component: resolve =>void(require(['./components/connect/CreateNote.vue'], resolve)),
        },
        {
            name: "发表的帖子",
            path: "/note/report",
            component: resolve =>void(require(['./components/connect/ReportNote.vue'], resolve)),
        },
        {
            name: "帖子详细信息",
            path: "/note/detail/:id",
            component: resolve =>void(require(['./components/connect/NoteDetail.vue'], resolve)),
        },
        {
            name: "参与帖子",
            path: "/note/join",
            component: resolve =>void(require(['./components/connect/JoinNote.vue'], resolve)),
        },
        {
            name: "社交消息",
            path: "/note/msg",
            component: resolve =>void(require(['./components/connect/MsgNote.vue'], resolve)),
        },
        {
            name:"我的",
            path:'/me',
            component: resolve =>void(require(['./components/Me.vue'], resolve))
        },
        {
            name:"feedback",
            path:'/feedback',
            component: resolve =>void(require(['./components/me/proposal/Feedback.vue'], resolve))
        },
        {
            name:"发布",
            path:'/main/release',
            component: resolve =>void(require(['./components/main/Release.vue'], resolve))
        },
        {
            name:"发布成功",
            path:'/main/IssueSuccess/:key',
            component: resolve =>void(require(['./components/main/IssueSuccess.vue'], resolve))
        },
        {
            name:"任务列表",
            path:'/main/task/list',
            component: resolve =>void(require(['./components/main/TaskList.vue'], resolve))
        },
    ]
})
