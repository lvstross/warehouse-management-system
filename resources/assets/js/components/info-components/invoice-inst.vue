<template>
    <div>
        <!-- Instruction Area -->
        <i @click="instruction = true" class="fa fa-question-circle fa-2x pointer" aria-hidden="true"></i>
        <transition name="fade">
            <div v-show="instruction" class="well">
                <i @click="instruction = false" class="fa fa-times-circle pull-right fa-2x pointer"></i>
                <h3 class="text-center">Invoices Instructions</h3>
                <h4>Invoices Table</h4>
                <p>The invoices table by default only shows the invoices from the current year. At any point, you many download a pdf of the shipper or invoice as well as view the details of your invoice, edit the invoice or delete the invoice entirly.</p>
                <h4>Filling out the form</h4>
                <ol>
                    <li><strong>Invoice #: </strong>The invoice number is auto generated with two factors in mind: (1) the company prefix number and (2) the lastest entry in the invoices table. This
                    number will always start with the prefix number as well as '0000' indicating the beginning of a sequence. If the prefix number is changed to something else, the sequence will start
                    back at '0000'. </li>
                    <li><strong>Date: </strong>The date the invoice is being made.</li>
                    <li><strong>Customer: </strong>The customer dropdown is dependent on the customers list that you make over in the customers area. This dropdown shows both the ID number and the customer
                    name. When customer has been chosen and the field is left, the cutomer name will appear on the right next to the bold lettered 'Customer:'. This indecates that a customer was selected successfully.
                    All invoices come with a snap shot of the customer data at the time of making the invoice. If the chosen customers data changes on a later date, the information will remain the same with that invoice.</li>
                    <li><strong>P.O #: </strong>The purhcase order number for this invoice. The field only allows alphanumeric characters as well as single dashes between alphanumeric charcters.</li>
                    <li><strong>Line Items: </strong>The line item section of the form is pretty streight forward. Every invoice must have at least one line item attached to it. If you desire to add another line item, press the 'Add A Line Item' Button.
                    When you've added another line item, you'll notice that another button appears where you can remove the added line item. This button will only remove one line item at a time as well as recalculate the line
                    iten quantities. You are allowed to add up to seven line items for one invoice. When seven line items are on the page, you'll notice that the 'Add A Line Item' button has been disabled. As for the fields in the
                    line item sections, proceed with the following instructions:</li>
                    <ol type="a">
                        <li><strong>Line Item: </strong>The line item number which may contain alphanumeric characters as well as dashes between alphanumeric characters. The max limit is 30 characters.</li>
                        <li><strong>Product: </strong>This dropdown shows all products that were created in the products area. Simply select one to add to this line item.</li>
                        <li><strong>Qty: </strong>The quantity of products for this line item. Must be a whole number to calculate properly.</li>
                        <li><strong>Unit Price: </strong>The unit price per product. Can either be a whole number or a decimal point number.</li>
                        <li><strong>Ext Price: </strong>This field is not editable as it is used to calculate the total amount for all line items.</li>
                    </ol>
                    <li><strong>Shipping Fee: </strong>Any shipping fees related to this invoice. Can either be a whole number or a decimal point number.</li>
                    <li><strong>Misc. Charges: </strong>Any miscellaneous charges for this invoice. Can either be a whole number or a decimal point number.</li>
                    <li><strong>Carrier: </strong>The name of the carrier that will be shipping this product.</li>
                    <li><strong>Memo: </strong>Any in house information that would be helpful in the process of this invoice.</li>
                </ol>
                <h4>Search By Invoice Number</h4>
                <p>Above the invoices table, you'll notice search form. This form is so you may search all of your invoices for a specific invoice by it's invoice number. The number must be exact or it will not
                find the correct invoice. Once the invoice has been searched for, you have now entered 'Search Mode'. To cancel out of search mode, simply click the 'cancel search' button.</p>
                <h4>Make Invoice Reports</h4>
                <p>The dataentry system allows you to make reports in order to see your sales for a given time period. In the report section, type in the start date and the end date you wish to make a report from.
                You may also give your report print out a name in the 'Report Name' field. Click 'Get Report' and the invoices table will populate with all the invoices on and in between the given dates.</p>
            </div>
        </transition>
        <!-- End of Instruction Area -->
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
