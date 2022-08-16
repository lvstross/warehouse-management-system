<template>
    <transition name="fade">
        <div>
            <h4 class="text-center background padding radius">{{ header }}</h4>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <textFormLineItem
                        :dataModel="model.line_items[itemNum].item" 
                        :forVal="forValLi"
                        :inputName="'Line Item'" 
                        :inputClass="'form-control'" 
                        :item="itemNum" 
                    ></textFormLineItem>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <selectForm
                        :dataModel="model.line_items[itemNum].product"  
                        :inputName="'Product'" 
                        :inputClass="'form-control'" 
                        :list="products" 
                        :item="itemNum"
                    ></selectForm>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-4 col-md-4">
                    <numberFormLineItem
                    :dataModel="model.line_items[itemNum].qty" 
                    :forVal="forValQty" 
                    :inputName="'Qty'" 
                    :inputClass="'form-control'" 
                    :item="itemNum" 
                    :set="0" 
                    ></numberFormLineItem>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-4">
                    <numberFormLineItem
                    :dataModel="model.line_items[itemNum].unit" 
                    :forVal="forValUnit" 
                    :inputName="'Unit Price'" 
                    :inputClass="'form-control'" 
                    :item="itemNum" 
                    :set="1" 
                    ></numberFormLineItem>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-4">
                    <label :for="forValExt">Ext Price</label>
                    <input 
                        :value="model.line_items[itemNum].extended.toFixed(2)" 
                        number type="number" 
                        :name="forValExt" 
                        min="0" step="0.01" 
                        class="form-control"  
                        readonly>
                </div>
            </div>
            <br />
        </div>
    </transition>
</template>
<script>
    // imports
    import TextFormLineItem from '../partials/form-text-li.vue';
    import NumberFormLineItem from '../partials/form-number-li.vue';
    import SelectForm from '../partials/form-select-li.vue';
    // export
    export default {
        components: {
            textFormLineItem: TextFormLineItem,
            numberFormLineItem: NumberFormLineItem,
            selectForm: SelectForm
        },
        props: {
            model: Object,
            products: Array,
            header: String,
            itemNum: Number,
            forValLi: String,
            forValQty: String,
            forValUnit: String,
            forValExt: String
        }
    }
</script>