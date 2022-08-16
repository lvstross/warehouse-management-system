<template>
    <div class="form-group margin-top">
        <button v-show="!editMode" type="submit" class="btn btn-primary full-width" name="button"><i class="fa fa-plus" aria-hidden="true"></i> Add {{ name }}</button>
        <button v-show="editMode" type="submit" class="btn btn-primary full-width" name="button"><i class="fa fa-pencil" aria-hidden="true"></i> Update {{ name }}</button>
    </div>
</template>
<script>
    export default {
        props: {
            editMode: Boolean,
            name: String
        }
    }
</script>
<style scoped>
    .margin-top {
        margin-top: 20px;
    }
</style>