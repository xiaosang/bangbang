<template>
    <div>
        <msg title="成功" description="赶紧行动起来吧" :buttons="buttons" :icon="icon"></msg>
    </div>
</template>

<script>
    import { Msg, Divider, XButton } from 'vux'

    export default {
        components: {
            Msg,
            Divider,
            XButton
        },
        methods: {
            changeIcon () {
                if (!this.icon || this.icon === 'success') {
                    this.icon = 'warn'
                    return
                }
                if (this.icon === 'warn') {
                    this.icon = 'info'
                    return
                }
                if (this.icon === 'info') {
                    this.icon = 'waiting'
                    return
                }
                if (this.icon === 'waiting') {
                    this.icon = 'success'
                }
            },
            back(){
                this.$router.push('/main/task/list')
            }
        },
        data () {
            return {
//                description: 'msg description',
                icon: '',
                buttons: [
//                    {
//                        type: 'default',
//                        text: '查看任务详情',
//                        link: '/main/task/info/'+ this.$route.params.id
//                    },

//                    {
//                        type: 'primary',
//                        text: '返回任务列表',
//                        onClick:this.back.bind(this)
////                        link:'/main/task/list'
//                    }]
                    {
                        type: 'default',
                        text: '查看详情',
                        link: '/main/task/info/'+ this.$route.params.id
                    },
                    {
                        type: 'primary',
                        text: '返回首页',
                        link:'/main'
                    }]
            }
        }
    }
</script>