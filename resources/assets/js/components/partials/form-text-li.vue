<template>
    <div class="form-group">
        <label :for="forVal">{{ inputName }}</label>
        <input 
            :value="dataModel" 
            type="text" 
            :name="forVal" 
            @keyup="updateModel" 
            :class="inputClass"
            maxlength="30">
    </div>
</template>
<script>
    export default {
        props: {
            dataModel: String,
            forVal: String,
            inputName: String,
            inputClass: String,
            item: Number
        },
        methods: {
            updateModel(e) {
                this.$store.commit(
                    'updateLineItem', 
                    {
                        item: this.item, 
                        event: e.target.value
                    }
                );
            }
        }
    }
</script>