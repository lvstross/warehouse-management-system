<template>
    <div class="row">
        <div class="col-xs-12 col-sm-2">
            <div class="form-group">
                <label :for="'r_num'">Router #:</label>
                <input v-model="box.router_num" type="text" :name="'r_num'" class="form-control">
            </div>
        </div>
        <div class="col-xs-12 col-sm-2">
            <div class="form-group">
                <label :for="'cure_qtr'">Cure Qtr:</label>
                <input v-model="box.cure_qtr" type="text" :name="'cure_qtr'" class="form-control">
            </div>
        </div>
        <div class="col-xs-12 col-sm-2">
            <div class="form-group">
                <label :for="'qty'">Qty:</label>
                <input v-model="box.qty" type="text" :name="'qty'" class="form-control">
            </div>
        </div>
        <div class="col-xs-12 col-sm-2">
            <div class="form-group">
                <label :for="'wt'">Weight:</label>
                <input v-model="box.wt" type="text" :name="'wt'" class="form-control">
            </div>
        </div>
        <div class="col-xs-12 col-sm-2">
            <div class="form-group">
                <label :for="'dim'">Dimension:</label>
                <input v-model="box.dim" type="text" :name="'dim'" class="form-control">
            </div>
        </div>
        <div class="col-xs-12 col-sm-2">
            <button type="button" class="btn btn-success full-width" @click="submitBoxesItem"> Add Box</button>
        </div>
    </div>
</template>
<script>
    export default {
        props: {

        },
        data(){
            return {
                box: {
                    router_num: '',
                    cure_qtr: '',
                    qty: '',
                    wt: '',
                    dim: ''
                }
            }
        },
        methods: {
            submitBoxesItem() {
                if(this.checkValues()){
                    this.$emit('addBox', this.box);
                    this.box = { router_num: '', cure_qtr: '', qty: '', wt: '', dim: '' }
                } else {
                    alert('All box fields are required. Some are empty.');
                }
            },
            checkValues() {
                return this.box.router_num == '' ? false
                     : this.box.cure_qtr == ''   ? false
                     : this.box.qty == ''        ? false
                     : this.box.wt == ''         ? false
                     : this.box.dim == ''        ? false
                     :                             true
            }
        }
    }
</script>
<style scoped>
    .items_cont {
        max-height: 350px;
        overflow-y: scroll;
    }
    .btn {
        margin-top: 23px;
    }
</style>
