<template>
    <div>
        <!--<x-header title="任务列表"></x-header>-->
        <x-header :left-options="{ showBack:false,backText:'' }">
            <span slot="left">
                <!--<a href="back(-1)" class="ion-arrow-left-c"></a>-->
                <!--<a href="javascript:history.go(-1)" class="ion-android-arrow-back" style="font-size: 18px;"></a>-->
                <router-link to="/main" class="ion-android-arrow-back" style="font-size: 18px;"></router-link>
                <span style="font-size: 18px;">任务列表</span>
            </span>
            <!--<router-link slot="right" to="/main" class="ion-plus-round" style="font-size: 18px;"></router-link>-->
            <!--<router-link slot="right" to="/main" class="ion-plus" style="font-size: 18px;"></router-link>-->
            <!--<router-link slot="right" to="/main" class="ion-plus-circled" style="font-size: 18px;"></router-link>-->
            <!--<router-link slot="right" to="/main" class="ion-ios-plus-empty" style="font-size: 18px;"></router-link>-->
            <!--<router-link slot="right" to="/main" class="ion-ios-plus-outline" style="font-size: 18px;"></router-link>-->
            <!--<router-link slot="right" to="/main" class="ion-ios-plus" style="font-size: 18px;"></router-link>-->
            <router-link slot="right" to="/main/release" class="ion-android-add" style="font-size: 22px;"></router-link>
            <!--<router-link slot="right" to="/main" class="ion-android-add-circle" style="font-size: 18px;"></router-link>-->
            <!--<router-link slot="right" to="/main" class="ion-ios-plus" style="font-size: 18px;"></router-link>-->
            <!--<router-link slot="right" to="/main" class="ion-compose" style="font-size: 18px;"></router-link>-->
        </x-header>




        <tabbar on-index-change="switch">
            <tabbar-item>
                <i slot="icon" class="ion-android-person"></i>
                <span slot="label">全部</span>
            </tabbar-item>
            <tabbar-item>
                <i slot="icon" class="ion-android-person"></i>
                <span slot="label">无偿</span>
            </tabbar-item>
            <tabbar-item>
                <i slot="icon" class="ion-android-person"></i>
                <span slot="label">有偿</span>
            </tabbar-item>
        </tabbar>
    </div>
</template>

<script>
    import { XHeader , Tabbar, TabbarItem} from 'vux'
    export default {
        components: {
            XHeader,
            Tabbar,
            TabbarItem
        },
        data(){
            return {}
        },
        methods:{
            switch(value){
                console.log(1)
                console.log(value)
            }

        },
        watch:{

        },
        mounted() {

        }
    }
</script>

<style scoped>

</style>

<style>

</style>

