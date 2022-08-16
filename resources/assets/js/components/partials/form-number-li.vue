<template>
    <div class="form-group">
        <label :for="forVal">{{ inputName }}</label>
        <input 
            :value="dataModel" 
            number type="number" 
            :name="forVal" 
            @keyup="updateModel" 
            @blur="updateTotal"
            :class="inputClass" 
            min="0" 
            step="0.01" 
            required>
    </div>
</template>
<script>
    export default {
        props: {
        dataModel: [Number, String],
        forVal: String,
        inputName: String,
        inputClass: String,
        item: Number, // array index for line item
        set: Number // 0 or 1
        },
        methods: {
            updateModel(e) {
                this.$store.dispatch(
                    'commitMath', 
                    {
                        item: this.item,
                        set: this.set,
                        event: e.target.value
                    }
                );
            },
            updateTotal() {
                this.$store.dispatch('commitTotal');
            }
        }
    }
</script>