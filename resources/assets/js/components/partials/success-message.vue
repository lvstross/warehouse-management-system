<template>
    <transition name="fade">
        <div v-if="successMes" class="fixed-message">
            <p class="alert alert-success text-center">
                <i class="fa fa-thumbs-up" aria-hidden="true"></i> 
                    {{ successMes }} 
                <i class="fa fa-thumbs-up" aria-hidden="true"></i>
                <span class="pull-right" @click="hideMes()">
                    X
                </span>
            </p>
        </div>
    </transition>
</template>
<script>
    export default {
        props: {
            successMes: String
        },
        methods: {
            hideMes(){
                this.successMes = '';
            }
        }
    }
</script>
<style scoped>
    span {
        cursor: pointer;
    }
</style>