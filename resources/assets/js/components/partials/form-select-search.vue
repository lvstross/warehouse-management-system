<template>
    <div class="form-group">
        <label>{{ inputName }}</label>
        <select :value="dataModel" @blur="updateModel()" :class="inputClass" required>
            <option>Choose An Item</option>
            <option v-for="item in list" :value="item.value">{{ item.id }}-{{ item.name }}</option>
        </select>
    </div>
</template>
<script>
    export default {
        props: {
            dataModel: String,
            inputName: String,
            inputClass: String,
            list: Array,
            update: Function
        },
        methods: {
          updateModel(e){
            this.update(e);
          }
        }
    }
</script>
