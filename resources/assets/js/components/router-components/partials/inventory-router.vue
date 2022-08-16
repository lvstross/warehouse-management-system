<template>
    <div class="inner-router-inv" :style="{ background: mathColor }">
        <div class="router-checkbox float-left">
            <input v-show="checkable" type="checkbox" @change="emitRouterId" :value="router.id">
        </div>
        <div class="router-text clear-both">
            <p class="text-center sm-text"> <strong>RN:</strong>{{ router.router_num }} / <strong>STK:</strong> {{ Number(router.stock_qty) }} / <strong>DII:</strong> {{ newDate(router.date_in_inv) }}</p>
        </div>
        <div class="router-number float-right">
            <input v-model="sub_qty" @keyup="runFunctions" type="number" placeholder="Qty">
        </div>
        <div class="router-number float-right">
            <input v-model="cure_qtr" type="text" placeholder="Cure Qtr">
        </div>
        <div class="clear-both"></div>
    </div>
</template>
<script>
    import Utils from '../../../modules/utils.js';
    export default {
        props: {
            router: Object,
            emit: Function
        },
        data(){
            return {
                sub_qty: Number,
                cure_qtr: '',
                mathColor: '#fff',
                checkable: false
            }
        },
        methods: {
            runFunctions(){
                this.checkValues();
                this.emit();
            },
            newDate(date){
                return Utils.invertDate(date);
            },
            checkValues(){
                // reset the values by default for when they have been changed by ref
                if(this.checkable === true){ 
                    this.mathColor = '#fff';
                    this.checkable = false; 
                }
                // Check the math of the sub_qty and the stock_qty
                // Show or hide the checkbox based on if their is a value
                if(this.sub_qty > this.router.stock_qty){
                    this.mathColor = '#f29f9f';
                    this.checkable = false;
                }else if(this.sub_qty == 0 || this.sub_qty == ''){
                    this.mathColor = '#fff';
                    this.checkable = false;
                }else if(this.sub_qty <= this.router.stock_qty && this.sub_qty != 0){
                    this.mathColor = '#9befa4';
                    this.checkable = true;
                }
            },
            emitRouterId(e){
                this.$emit('routerId', {
                    id: e.target.value, 
                    r_num: this.router.router_num,
                    cure_qtr: this.cure_qtr,
                    apply_qty: this.sub_qty, 
                    old_qty: this.router.stock_qty, 
                    new_router_total: this.router.stock_qty - this.sub_qty 
                });
            }
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
    .inner-router-inv > .router-checkbox > input[type='checkbox']{
        width: 20px;
        height: 20px;
        border: 1px solid #bbb;
        background: #fff;
    }
    .router-checkbox {
        width: 5%;
    }
    .router-text {
        width: 55%;
        padding-top: 4px;
        margin: 0 5px;
        /*min-width: 100px;*/
        /*white-space: nowrap;*/
        /*overflow: scroll;*/
    }
    .router-text > p {
        margin: 0;
    }
    .router-number {
        width: 17%;
    }
    .inner-router-inv > .router-number > input[type='number'] {
        border-radius: 5px;
        border: 1px solid #bbb;
        width: 100%;
    }
    .inner-router-inv > .router-number > input[type='text'] {
        border-radius: 5px;
        border: 1px solid #bbb;
        width: 100%;
    }
    .inner-router-inv > div {
        display: inline-block;
    }
</style>