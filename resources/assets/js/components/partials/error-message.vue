<template>
    <transition name="fade">
        <div v-if="errorMes" class="fixed-message">
            <p class="alert alert-danger text-center">
                <i class="fa fa-frown-o" aria-hidden="true"></i> 
                    {{ errorMes }} 
                <i class="fa fa-frown-o" aria-hidden="true"></i>
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
            errorMes: String
        },
        methods: {
            hideMes(){
                this.errorMes = '';
            }
        }
    }
</script>
<style scoped>
    span {
        cursor: pointer;
    }
</style>