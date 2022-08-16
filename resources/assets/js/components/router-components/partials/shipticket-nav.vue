<template>
    <div class="row"> 
        <div class="col-xs-6 col-sm-4"><button @click="toSearch" class="btn btn-default btn-md full-width"><i class="fa fa-search" aria-hidden="true"></i>  {{ textOne }}</button></div>
        <div class="col-xs-6 col-sm-4"><button @click="toTable" class="btn btn-primary btn-md full-width"><i class="fa fa-table" aria-hidden="true"></i>  {{ textTwo }}</button></div>
        <div class="col-xs-6 col-sm-4"><button @click="toForm" class="btn btn-success btn-md full-width"><i class="fa fa-plus" aria-hidden="true"></i>  {{ textThree }}</button></div>
    </div>
</template>
<script>
    export default {
        props: {
            textOne: String,
            textTwo: String,
            textThree: String,
            toSearch: Function,
            toTable: Function,
            toForm: Function
        }
    }
</script>
<style scoped>
    .row > div {
        margin-bottom: 10px;
    }
    @media(max-width:1345px){
        .fa-table, .fa-plus, .fa-search {
            display: none;
        }
    }
</style>