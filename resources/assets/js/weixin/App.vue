<template>
    <div>
        <keep-alive>
            <router-view v-if="$route.meta.keepAlive"></router-view>
        </keep-alive>
        <router-view v-if="!$route.meta.keepAlive"></router-view>
    </div>
</template>
<script scoped>

    export default {
        data(){
            return {}
        },
        components: {

        },
        computed: {},
        methods: {

        },
        mounted() {
        },
    }
</script>