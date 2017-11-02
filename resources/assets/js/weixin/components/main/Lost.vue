<template>

</template>

<script>
    import { Swiper ,Swipeout, SwipeoutItem, SwipeoutButton , Grid, GridItem  , FormPreview , Scroller , Divider , ToastPlugin  } from 'vux'
    Vue.use(ToastPlugin)
    export default {
        components: {
            Swiper,
            Swipeout,
            SwipeoutItem,
            SwipeoutButton,
            Grid,
            GridItem,
            FormPreview,
            Scroller,
            Divider,
            ToastPlugin
        },
        data(){
            return {
               lostList:[],
            }
        },
        methods:{
           getList: function (){
               axios.get('/wx/getlist').then(res =>{
                   consosle.log(res)
               }).catch(error =>{

               })
           }
        },
        mounted() {

        }
    }
