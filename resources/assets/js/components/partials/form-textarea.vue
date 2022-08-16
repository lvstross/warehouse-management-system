<template>
    <div class="form-group" v-if="max">
        <label :for="forVal">{{ inputName }}</label>
        <textarea 
            :value="dataModel" 
            type="text" 
            :name="forVal" 
            @keyup="updateModel" 
            :row="rows" 
            :class="inputClass" 
            :maxlength="max"
            ></textarea>
        <p class="alert alert-warning" v-if="dataModel.length == max">{{ max }} character limit reached!</p>
    </div>
    <div class="form-group" v-else>
        <label :for="forVal">{{ inputName }}</label>
        <textarea 
            :value="dataModel" 
            type="text" 
            :name="forVal" 
            @keyup="updateModel" 
            :row="rows" 
            :class="inputClass"
            ></textarea>
    </div>
</template>
<script>
    export default {
        props: {
        dataModel: String,
        forVal: String,
        inputName: String,
        inputClass: String,
        max: Number,
        rows: Number,
        update: Function
        },
        methods: {
            updateModel(e) {
                this.update(e);
            }
        }
    }
</script>