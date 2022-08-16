<template>
    <div class="form-group" v-if="max">
        <label :for="forVal">{{ inputName }}</label>
        <input 
            :value="dataModel" 
            number type="number" 
            :name="forVal" 
            @keyup="updateModel" 
            :class="inputClass" 
            :maxlength="max">
        <p class="alert alert-warning" v-if="dataModel.length == max">{{ max }} character limit reached!</p>
    </div>
    <div class="form-group" v-else>
        <label :for="forVal">{{ inputName }}</label>
        <input 
            :value="dataModel" 
            number type="number" 
            :name="forVal" 
            @keyup="updateModel" 
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
        max: Number,
        update: Function
        },
        methods: {
            updateModel(e) {
                this.update(e);
            }
        }
    }
</script>