<template>
    <div>
        <msg :title="this.$route.params.key" description="请牢记该号码" :buttons="buttons" :icon="icon"></msg>
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
            }
        },
        data () {
            return {
                description: 'msg description',
                icon: '',
                buttons: [
                    {
                        type: 'default',
                        text: '继续发布',
                        link: '/main/release'
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