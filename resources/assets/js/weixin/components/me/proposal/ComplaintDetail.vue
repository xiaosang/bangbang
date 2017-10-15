<template>
    <div>
        <div class="header">
            <x-header :left-options="{backText:'',preventGoBack:true}" @on-click-back="return_last">投诉详情

            </x-header>
        </div>
        <div class="content">

        </div>
    </div>
</template>
<style>

</style>
<style scoped>
    .header{
        position:fixed;
        width: 100%;
    }
    .content{
        padding-top: 47px;
    }

</style>
<script>
    //    import Navbottom from './NavBottom.vue'
    import {XHeader} from 'vux'
    export default {
        components: {
            XHeader,

        },
        data(){
            return {
                complaint:null
            }
        },
        methods:{
            return_last(){
                this.$router.push('/complaint/list');
            },
            single_complaint(){
                let data = {
                    complaint_id:this.$route.params.complaint_id
                }
                this.send_request('post','/wx/me/complaint/single',function (response,self) {
                    console.log(response.data)
                },data)
            },
            delete_complaint(){

            },
        },
        mounted() {
            this.single_complaint()
        }
    }
</script>
