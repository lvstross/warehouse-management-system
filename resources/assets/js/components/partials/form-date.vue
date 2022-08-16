<template>
    <div v-if="req" class="form-group">
        <label for="date">{{ inputName }}</label>
        <input :value="dataModel" @keyup="updateModel" type="date" name="date" class="form-control" required>
    </div>
    <div v-else class="form-group">
        <label for="date">{{ inputName }}</label>
        <input :value="dataModel" @keyup="updateModel" type="date" name="date" class="form-control">
    </div>
</template>
<script>
    export default {
        props: {
            forVal: String,
            req: Boolean,
            inputName: String,
            inputClass: String,
            dataModel: String,
            update: Function
        },
        methods: {
            updateModel(e) {
                this.update(e.target.value);
            }
        }
    }
</script>
