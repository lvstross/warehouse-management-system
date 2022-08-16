<template>
    <div>
        <!-- Instruction Area -->
        <i @click="instruction = true" class="fa fa-question-circle fa-2x pointer" aria-hidden="true"></i>
        <transition name="fade">
            <div v-show="instruction" class="well">
                <i @click="instruction = false" class="fa fa-times-circle pull-right fa-2x pointer"></i>
                <h3 class="text-center">Certifications Instructions</h3>
                <p>Welcome to the Certifications interface. Here are the fields that you will be interacting with as well as the data they require.</p>
                <ol>
                    <li><strong>Packing List #:</strong> The Invoice number that you are creating the cert for. Once you have selected a packing list number (i.e. invoice number) you can see the specific data related to that 
                    invoice by clicking the button that appears above the 'Lot Items' section.</li>
                    <li><strong>Date:</strong> The date of creation as it will be printed out on the cert.</li>
                    <li><strong>Total Qty:</strong> The quantity of parts you are certifying.</li>
                    <li><strong>Product:</strong> The product part number that will be certified.</li>
                    <li><strong>Seller Certifies That:</strong> Any extra cert information that needs to be added onto the list of certifications that are already on the print out template.
                    When adding a cert item, just start typing into the field. For every new cert item you wish to add, press 'Enter' and continue with another cert item. <span style="color: red;">Do not add number points to your cert items as they will be added for you on the print out.</span></li>
                    <li><strong>Lot Items:</strong> Lot items represent the different lot numbers that will be certified as well as their cure dates and quantities. You have a max of 20 lot items. When you wish to add a lot 
                    item, click the 'Add Lot Item' button. If you wish to remove a lot item, click the 'Remove Lot Item' button.</li>
                </ol>
                <p>The Certifications interface keeps track of the lot item quanties for you as it totals them up and checks to see if they match the 'Total Qty' that you added before hand. You'll notice an alert box telling you 
                if your quantities line up or not. If they do, the alert box will show green. If not, it will show red. Once you have finished filling out all the nessesary data, you can click the 'Print Cert' button to receive 
                a pdf download of the certification.</p>
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
