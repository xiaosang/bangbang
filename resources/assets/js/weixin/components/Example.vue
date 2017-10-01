<template>
    <div class="container">
        <navbottom></navbottom>
    </div>
</template>

<script>
    import Navbottom from './NavBottom.vue'
    export default {
        components:{
            Navbottom
        },
        data(){
            return {}
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
