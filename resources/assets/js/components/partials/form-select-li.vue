<template>
    <div class="form-group">
        <label>{{ inputName }}</label>
        <select :value="dataModel" @blur="updateModel" :class="inputClass" required>
            <option>Choose An Item</option>
            <option v-for="item in list">{{ item.name }}</option>
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
            item: Number
        },
        methods: {
            updateModel(e) {
                this.$store.commit(
                    'updateProduct', 
                    {
                        item: this.item, 
                        event: e.target.value
                    }
                );
            }
        }
    }
</script>