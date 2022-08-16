<template>
    <div class="form-group">
        <label :for="forVal">{{ inputName }}</label>
        <input 
            :value="dataModel" 
            number type="number" 
            :name="forVal" 
            min="0" step="0.01"
            @keyup="updateModel"
            @blur="updateTotal" 
            :class="inputClass">
    </div>
</template>
<script>
    export default {
        props: {
        dataModel: [Number, String],
        forVal: String,
        inputName: String,
        inputClass: String,
        update: Function
        },
        methods: {
            updateModel(e) {
                this.update(e);
            },
            updateTotal() {
                this.$store.dispatch('commitTotal');
            }
        }
    }
</script>