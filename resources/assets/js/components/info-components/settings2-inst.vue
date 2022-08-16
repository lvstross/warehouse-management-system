<template>
    <div>
        <i @click="instruction = true" class="fa fa-question-circle fa-2x pointer" aria-hidden="true"></i>
        <transition name="fade">
            <div v-show="instruction" class="well">
                <i @click="instruction = false" class="fa fa-times-circle pull-right fa-2x pointer"></i>
                <h3 class="text-center">Company Information Form</h3>
                <p>Welcome to the settings area of the dataentry system! This is where you will add all of the needed company
                    information that will be used through out your system. This information is also used on your pdf templates
                    as well as your report forms.</p>
                <h4>Filling out the form</h4>
                <p>Filling out the form is simple but some of the information that is asked for may not apply to your specific needs.
                As a summery here are what each field is used for:</p>
                <ol>
                    <li><strong>Name: </strong> This is where you will put the name of your company as you would want it to appear on invoices, shippers
                    and reports.</li>
                    <li><strong>Invoice Prefix Number: </strong> This number, as it suggests, is placed as a prefix before all of your invoice numbers
                    which are automatically generated for you. Your invoice numbers start with your prefix here and end with 0000. The invoice numbers then
                    increment from there and will continue to increment until you update your prefix number. This is so you can have invoice numbers that are 
                    unique for every year, half a year, quarter of the year or however frequent your invoice numbers need to change. Each time you update your
                    prefix number, the invoice counter will reset to 0000.</li>
                    <li><strong>Address: </strong>This is where you put the address to your company. This address is used on both the shipper and the invoice and 
                    will appear as you write it here.</li>
                    <li><strong>Description: </strong>The description of your company goes here and it currently not used on any printable documents as far as the system goes.</li>
                    <li><strong>Phone: </strong>This is where you place the phone number of the company where you want your customers to reach you at. This number will be placed on 
                    both the shipper and the invoice.</li>
                    <li><strong>Email: </strong>This is where you place your company email address which will be placed on both the shipper and the invoice.</li>
                    <li><strong>Invoice Document Control Number: </strong>The document version controle number for the invoice document.</li>
                    <li><strong>Shipper Document Control Number: </strong>The document version controle number for the shipper document.</li>
                    <li><strong>Router Document Control Number: </strong>The document version controle number for the shipper document.</li>
                    <li><strong>Purchase Order Document Control Number: </strong>The document version controle number for the purchase order document.</li>
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
