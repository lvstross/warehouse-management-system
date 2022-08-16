<template>
    <div>
        <i @click="open" class="fa fa-question-circle fa-2x pointer" aria-hidden="true"></i>
        <transition name="fade">
            <div v-show="instruction" class="well">
                <i @click="instruction = false" class="fa fa-times-circle pull-right fa-2x pointer"></i>
                <h3 class="text-center">Purchases Instructions</h3>
                <h4>The Interface</h4>
                <ol>
                    <li><strong>Purchases and Receivals: </strong>The interface is made up of a purchaes table and a receivals table. The purchases table shows you all of your currently open purchases that you have not received either in part or in full. The receivals table shows you all orders that have been fully received.</li>
                    <li><strong>Search Purchases: </strong>This function allows you to search purchases by purchase order number or by vendor name. The context of the search is based on which table you are viewing. If you are on the purhcases table than the search will be relevant to that table alone. The same goes for the receivals table.</li>
                    <li><strong>Add A Purchase: </strong>This function allows you to add a purchase to your system.</li>
                    <li><strong>Get On Time Vendor Report: </strong>This function provides a printable pdf of your vendors on time delivery report.</li>
                    <li><strong>Get Purchaseing Report: </strong>This function provides a printable pdf of your total purchases between two dates that your provide.</li>
                </ol>
                <h4>Filling out the form</h4>
                <h5>Creating A Purchase</h5>
                <ol>
                    <li><strong>Purchase Number: </strong>The purchase number is the sequence number placed onto the purchase order by an external number to the system.</li>
                    <li><strong>Vendor: </strong>The name of the venodor you are placing the order for. This vendor must already exist in the dataentry system.</li>
                    <li><strong>Terms Code: </strong>The terms code has to do with the type of payment the vendor will receive.</li>
                    <li><strong>Carrier: </strong>The name of the carrier that delivered the product to you.</li>
                    <li><strong>Due Date: </strong>The date the product is expected from the vendor.</li>
                    <ul>
                        <li class="no-bullet"><strong>Purchase Items Section: Fill out the form and add as many items as needed with the 'Add Item' button. Be sure to add item before submitting form.</strong></li>
                        <li><strong>Part Number: </strong>The part number you are ordering.</li>
                        <li><strong>Qty Ordered: </strong>The quantity of units you are ordering.</li>
                        <li><strong>Qty Received Good: </strong>The quantity you have received that is good.</li>
                        <li><strong>Qty Canceled: </strong>The quantity of units canceled.</li>
                        <li><strong>Qty Rejected: </strong>The quantity of units rejected.</li>
                        <li><strong>Unit Cost: </strong>The cost per unit.</li>
                        <li><strong>Unit Of Measure: </strong>The measure per unit.</li>
                        <li><strong>Receive Date: </strong>The date you fully received the order.</li>
                        <li><strong>Back Order Qty: </strong>The quantity of units backordered by the vendor.</li>
                        <li><strong>Vendor Confirm Date: </strong>The date the vendor confirmed the order.</li>
                        <li><strong>Description: </strong>The description of the order.</li>
                    </ul>
                    <li><strong>Comments: </strong>Any comments you wish to place on the print out.</li>
                </ol>
                <h5>Receiving A Purchase</h5>
                <p>Once a purchase has been created, you now have access to extra inputs related to closing a purchase. The information required to
                close out a purchase are as follows:</p>
                <ol>
                    <li><strong>Receival Number: </strong>The receival number you wish to place on this purchase.</li>
                    <li><strong>Status: </strong>Each order begins with a status of 'Open'. Change the status to 'Closed' after you give an receival number.</li>
                    <li><strong>Date Received: </strong>The date you received the full order.</li>
                    <li class="no-bullet"><strong>Be sure to fill out any receiving information for all of your purchase items as well.</strong></li>
                </ol>
                <p>Once you are done filling out those few inputs, click the update button below and the order will be moved to the receivals area.</p>
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
    .no-bullet {
        list-style: none;
    }
</style>
