<template>
    <div class="container">

        <i class="ion-person"></i>
        <i class="ion-chatboxes"></i>
        <i class="ion-chatbubbles"></i>
        <group>
            <cell title="title" value="value"></cell>
        </group>
        <navbottom></navbottom>
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

        }
    }
</script>
