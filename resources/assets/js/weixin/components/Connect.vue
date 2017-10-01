<template>
    <div>
        <h1>社交</h1>
        <Navbottom></Navbottom>
    </div>
</template>

<script>
    import Navbottom from './NavBottom.vue'
    import { Group, Cell } from 'vux'
    export default {
        components: {
            Group,
            Cell,
            Navbottom
        },
        data(){
            return {}
        },
        mounted() {
//            console.log('Component mounted.')
        }
    }
</script>
