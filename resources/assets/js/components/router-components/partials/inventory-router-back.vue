<template>
    <div class="inner-router-inv">
        <div class="router-text">
            <p class="text-center sm-text"> RN: {{ router.r_num }} / Qty Applied: {{ router.apply_qty }} / Cure Qtr: {{ router.cure_qtr }}</p>
        </div>
    </div>
</template>
<script>
    export default {
        props: {
            router: Object
        }
    }
</script>
<style scoped>
    .inner-router-inv {
        width: 100%;
        border-radius: 10px;
        border: 1px solid #bbb;
        height: 50px;
        padding: 10px;
    }
    .router-text {
        width: 100%;
        padding-top: 4px;
        margin: 0 5px;
        min-width: 130px;
        /*white-space: nowrap;*/
        /*overflow: scroll;*/
    }
    .router-text > p {
        margin: 0;
    }
    .inner-router-inv > div {
        display: inline-block;
    }
</style>