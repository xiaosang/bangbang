<template>
    <div>
        <blur :blur-amount=40 :url="url" :height="120">
            <div class="header">
                <img :src="url">
                <span>昵称 : XXX</span><br>
                <span>信誉积分 : 888</span><br>
                <span>任务发布时间 : <br>8888-88-88 88:88:88</span>
            </div>
        </blur>

        


        <h1>{{ id }}</h1>
    </div>
</template>

<script>
    import { Group, Cell , Blur  } from 'vux'
    export default {
        components: {
            Group,
            Cell,
            Blur
        },
        data(){
            return {
                id:0,
                url: 'https://o3e85j0cv.qnssl.com/tulips-1083572__340.jpg'
            }
        },
        methods:{

        },
        watch:{

        },
        mounted() {
            this.id = this.$route.params.id
        }
    }
</script>

<style scoped>
    .header {
        /*text-align: center;*/
        padding-top: 20px;
        padding-left: 20px;
        color: #fff;
        font-size: 12px;
        line-height: 20px;
    }
    .header img {
        margin-right: 20px;
        width: 80px;
        height: 80px;
        border-radius: 50%;
        float: left;
    }
</style>