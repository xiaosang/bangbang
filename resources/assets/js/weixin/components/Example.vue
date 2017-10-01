<template>
    <div class="container">
        <!--<group>-->
            <!--<cell title="title" value="value"></cell>-->
        <!--</group>-->
        <i class="ion-person"></i>
        <i class="ion-chatboxes"></i>
        <i class="ion-chatbubbles"></i>
    </div>
</template>

<script>
    import { Group, Cell } from 'vux'
    export default {
        components: {
            Group,
            Cell
        },
        data(){
            return {}
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
