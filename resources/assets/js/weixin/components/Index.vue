<template>
    <div class="container">
        <group>
            <cell title="title" value="value"></cell>
        </group>
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
