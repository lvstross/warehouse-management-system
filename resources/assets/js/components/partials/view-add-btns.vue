<template>
    <div class="row"> 
        <div class="col-xs-6"><button @click="toTable" class="btn btn-primary btn-md full-width"><i class="fa fa-table" aria-hidden="true"></i>  {{ textOne }}</button></div>
        <div class="col-xs-6"><button @click="toForm" class="btn btn-success btn-md full-width"><i class="fa fa-plus" aria-hidden="true"></i>  {{ textTwo }}</button></div>
    </div>
</template>
<script>
    export default {
        props: {
            textOne: String,
            textTwo: String,
            toTable: Function, // prop from invoices.vue & customers.vue
            toForm: Function // prop from invoices.vue & customers.vue
        }
    }
</script>
<style scoped>
    @media(max-width:1345px){
        .fa-table, .fa-plus {
            display: none;
        }
    }
</style>