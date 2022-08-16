<template>
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6" v-if="disabled === true">
            <button class="btn btn-disabled full-width" type="button" v-show="btnNum" disabled><i class="fa fa-ban" aria-hidden="true"></i> Line Items Limit Reached</button>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6" v-else>
            <button class="btn btn-success full-width" @click="liShowHide(numOne, 'show')" type="button" v-show="btnNum"><i class="fa fa-plus" aria-hidden="true"></i> {{ changeText() }}</button>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6">
            <button class="btn btn-danger full-width" @click="liShowHide(numOne - 1, 'hide')" type="button" v-show="btnNum"><i class="fa fa-minus" aria-hidden="true"></i> Remove A Line Item</button>
        </div>
    </div>
</template>
<script>
    export default {
        props: {
            numOne: Number,
            btnNum: Boolean,
            disabled: Boolean,
            liShowHide: Function
        },
        methods: {
            changeText(){
                if(this.numOne == 2){
                    return 'Add A Third Line Item';
                } else if (this.numOne === 3){
                    return 'Add A Fourth Line Item';
                } else if (this.numOne === 4){
                    return 'Add A Fifth Line Item';
                } else if (this.numOne === 5){
                    return 'Add A Sixth Line Item';
                } else if (this.numOne === 6) {
                    return 'Add A Seventh Line Item';
                }
            }
        }
    }
</script>