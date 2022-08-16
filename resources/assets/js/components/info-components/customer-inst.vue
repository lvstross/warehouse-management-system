<template>
    <div>
        <i @click="open" class="fa fa-question-circle fa-2x pointer" aria-hidden="true"></i>
        <transition name="fade">
            <div v-show="instruction" class="well">
                <i @click="instruction = false" class="fa fa-times-circle pull-right fa-2x pointer"></i>
                <h3 class="text-center">Customers Instructions</h3>
                <h4>Filling out the form</h4>
                <p>The customer data is essential as it is used extensively in multiple printables throughout the dataentry system. Here are some instructions on how to fill out this form. This section is the only
                section that allows for somewhat duplicate information. Sometimes you'll want the same customer name but with different addresses, or different phone numbers. This can get confusing the more duplicate entries you have
                which is why each customer entry is given an ID number so that they may be uniquely identified when being used in other parts of the system. These ID numbers are not editable and will auto increment on their own 
                with every entry. Even if you delete all customers, the ID number will continue to auto increment from where it last was. Also, feel free to edit your customer info without changing the customer information of 
                past invoices as the system will take a snap shot of the customer information at the time it was created. </p>
                <ol>
                    <li><strong>Name: </strong>The name of the customer.</li>
                    <li><strong>Email: </strong>The contact email of the customer.</li>
                    <li><strong>Phone: </strong>The contact phone number of the customer.</li>
                    <li><strong>Buyer: </strong>The name of the one who is making the purchases on behalf of the company.</li>
                    <li><strong>Country: </strong>The country of which the customer resides.</li>
                    <li><strong>Bill To Address: </strong>The address that will be billed. The address will appear on the invoice and the shipper as 
                    it appears here.</li>
                    <li><strong>Ship To Address: </strong>The address that the products will be shipping to. The address will appear on the invocie and the shipper
                    as it appears here.</li>
                    <li><strong>Shipper Statement: </strong>The disclaimer is a note of information that will appear on the shipper.</li>
                    <li><strong>Comments: </strong>The comments that will need to follow this invoice through it's life cycle. This information is not printed anywhere in the system.</li>
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
