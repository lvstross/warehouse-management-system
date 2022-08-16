<template>
    <div>
        <!-- Instruction Area -->
        <i @click="instruction = true" class="fa fa-question-circle fa-2x pointer" aria-hidden="true"></i>
        <transition name="fade">
            <div v-show="instruction" class="well">
                <i @click="instruction = false" class="fa fa-times-circle pull-right fa-2x pointer"></i>
                <h3 class="text-center">Products Instructions</h3>
                <p>Welcome to the products area of the dataentry system. This is where you will add all of your product information. This information
                is under the assest area because this information will be made available in multiple areas of the system. There are five major components
                to a product:</p>
                <ol>
                    <li><strong>Name:</strong> The name of your product.</li>
                    <li><strong>Description:</strong> A brief description of your product.</li>
                    <li><strong>Material:</strong> The material that your product is made out of.</li>
                    <li><strong>Revision:</strong> The revision that your product is currently under.</li>
                    <li><strong>Revision Date:</strong> The starting date of the current product revision.</li>
                </ol>
                <p>Security measures apply to each of these fields which means each field has character restriction. The following characters
                are allowed in the following fields:</p>
                <ol>
                    <li><strong>Name:</strong> Alphanumeric characters as well as single dashes in between alphanumeric characters.</li>
                    <li><strong>Description:</strong> Alphanumeric characters as well as single dashes in between alphanumeric characters.</li>
                    <li><strong>Material:</strong> Alphanumeric characters, commas, periods as well as single dashes in between alphanumeric characters.</li>
                    <li><strong>Revision:</strong> Alphanumeric characters with a max of three characters.</li>
                    <li><strong>Revision Date:</strong> Forced date value</li>
                </ol>
            </div>
        </transition>
    </div>
</template>
<script>
    export default {
        data(){
            return {
                instruction: false
            }
        },
        methods: {
            open(){
                this.instruction = true;
            }
        }
    }
</script>
<style scoped>

</style>
