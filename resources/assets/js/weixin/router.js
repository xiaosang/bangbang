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
        // {
        //     name:"首页",
        //     path:'/',
        //     component: resolve =>void(require(['./components/Index.vue'], resolve))
        // },
        {
            name:"首页",
            path:'/main',
            meta:{keepAlive: true},
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
            component: resolve =>void(require(['./components/connect/ReportRecode.vue'], resolve)),
        },
        {
            name: "帖子详细信息",
            path: "/note/detail/:id",
            component: resolve =>void(require(['./components/connect/NoteDetail.vue'], resolve)),
        },
        {
            name: "参与帖子",
            path: "/note/join",
            component: resolve =>void(require(['./components/connect/ReplyRecode.vue'], resolve)),
        },
        {
            name: "社交消息",
            path: "/note/msg",
            component: resolve =>void(require(['./components/connect/RemindRecode.vue'], resolve)),
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
            name:"complaint",
            path:'/complaint',
            component: resolve =>void(require(['./components/me/proposal/Complaint.vue'], resolve))
        },
        {
            name:"complaint/list",
            path:'/complaint/list',
            component: resolve =>void(require(['./components/me/proposal/ComplaintList.vue'], resolve))
        },
        {
            name:"complaint/detail",
            path:'/complaint/detail/:complaint_id',
            component: resolve =>void(require(['./components/me/proposal/ComplaintDetail.vue'], resolve))
        },
        {
            name:"发布任务",
            path:'/main/release',
            component: resolve =>void(require(['./components/main/Release.vue'], resolve))
        },
        {
            name:"发布成功",
            path:'/main/IssueSuccess/:key',
            component: resolve =>void(require(['./components/main/IssueSuccess.vue'], resolve))
        },
        {
            name:"接受任务成功",
            path:'/main/AcceptSuccess/:id',
            component: resolve =>void(require(['./components/main/AcceptSuccess.vue'], resolve))
        },
        {
            name:"任务列表",
            path:'/main/task/list',
            meta:{keepAlive: true},
            component: resolve =>void(require(['./components/main/TaskList.vue'], resolve))
        },
        {
            name:"账号设置",
            path:'/accountset',
            component: resolve =>void(require(['./components/me/proposal/Setting.vue'], resolve))
        },
        {
            name:"任务详情",
            path:'/main/task/info/:id',
            component: resolve =>void(require(['./components/main/TaskInfo.vue'], resolve))
        },
        {
            name:"公告列表",
            path:'/main/announcement',
            meta:{keepAlive: true},
            component: resolve =>void(require(['./components/main/Announcement.vue'], resolve))
        },
    ]
})
