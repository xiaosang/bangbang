<template>
<div>
      <div class="header">
        <x-header :left-options="{ showBack:false,backText:''}">
            我的任务    
        </x-header>
            <nav id="nav_middle">
                <ul>
                    <li v-for="(na,index) in nav" :class="{active: na.show}">
                        <router-link :to="{path: na.path}">
                            <a @click="showIn(index)">{{na.title}}</a>
                        </router-link>
                    </li>
                </ul>
        </nav>
    </div>
    <router-view></router-view>
</div>
</template>
<style scoped>
    .header{
        position: fixed;
        width: 100%;
    }

      #nav_middle{
        background-color:#35495e;
        margin-top: 0.2px;

    }

    #nav_middle ul{
        margin: 0 auto;
        height: 44px;
        width: 80%;
        display: flex;
    }

    #nav_middle li{
        list-style: none;
        display: inline-block;
        flex: 1;
        line-height: 44px;

    }

    #nav_middle a{
        text-decoration: none;
        color: white;
        text-align: center;
         width: 100%;
        height: 100%;
        display: block;
    }

    .active{
        border-bottom: 2px solid #e8f1f3;
    }
</style>
<script>
import {XHeader} from 'vux'
export default {
   components: {
        XHeader
        },
        data(){
            return {
                 nav:[],
            }
        },
        methods:{
             init () {
               var self = this
               self.nav =  [
                   {
                       path: '/me/task/release/list',
                       title: '我发布的',
                       show: false
                   },
                   {
                       path: '/me/task/receive/list',
                       title: '我接受的',
                       show: false
                   }
               ]
               switch(self.$route.name){
                   case 'release/list':  self.code = 0
                       break;
                    case 'receive/list':  self.code = 1
                       break;
               }
               self.nav[self.code]['show'] = true
           },
            showIn (row) {
                this.nav[this.code]['show'] = false
                this.code = row
                this.nav[row]['show'] = true
            },
        },
        mounted() {
            this.init();
        }
}
</script>
