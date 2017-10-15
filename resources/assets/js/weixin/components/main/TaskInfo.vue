<template>
    <div>
        <h1>{{ id }}</h1>
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
            return {
                id:0
            }
        },
        methods:{

        },
        watch:{

        },
        mounted() {
            this.id = this.$route.params.id
        }
    }
</script>
