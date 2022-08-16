<template>
    <p class="text-center">Order By: 
        <button v-show="desc" @click="orderDesc" class="btn hover-underline btn-xs">Descending</button>
        <button v-show="asc" @click="orderAsc" class="btn hover-underline btn-xs">Ascending</button>
    </p>
</template>
<script>
    export default {
        props: {
            descFunc: Function,
            ascFunc: Function
        },
        data(){
            return {
                desc: false,
                asc: true,
            }
        },
        methods: {
            orderDesc(){ 
                this.descFunc(); 
                this.asc = true;
                this.desc = false;
            },
            orderAsc(){ 
                this.ascFunc(); 
                this.desc = true;
                this.asc = false;
            },
        }
    }
</script>