<template>
    <div>
        <i @click="open" class="fa fa-question-circle fa-2x pointer" aria-hidden="true"></i>
        <transition name="fade">
            <div v-show="instruction" class="well">
                <i @click="instruction = false" class="fa fa-times-circle pull-right fa-2x pointer"></i>
                <h3 class="text-center">Purchase Order Instructions</h3>
                <h4>The Interface</h4>
                <p>The purchase orders interface is a sortable interface where you may sort your purchase orders in whatever order you need. This interface has a few functions that allow you to get the most out of your purcahse orders table.</p>
                <ol>
                    <li><strong>Open and Closed Orders: </strong>These two functions are for switching back and forth between open orders and closed orders.</li>
                    <li><strong>Search Purchase Orders: </strong>This function opens up the search interface where you may search for purchase orders based on the purchase order number, the customer or the part number.</li>
                    <li><strong>Add A Purchase: </strong>This function pulls up the Purchase Order form where you may create a purchase order entry in your system.</li>
                    <li><strong>Print Current Open Order Report: </strong>This function will provide a downloadable printout with all of your open purchase orders in the order that you sorted them in.</li>
                    <li><strong>Print Production Planning Sheet: </strong>This function provides a planning sheet with all your purchae orders in the sort order you left them in. This is so you may see which routers are assigned to each purchase order.</li>
                    <li><strong>Print Employee Task Sheet: </strong>This functions provides a printable sheet where you may assign task to your employees based on your current open order report.</li>
                    <li><strong>Get On Time Delivery Report: </strong>This functions provides a printable that shows your on-time delivary percentages of all the past purchase orders between the dates your provide.</li>
                    <li><strong>Get Projection Or Sales Report: </strong>This function provies a printable that shows you are sales projections for all the orders between two dates.</li>
                    <li><strong>Clear All Rating: </strong>This function clears all of the ratings on all of your purchase orders so that you may begin rating your orders from scratch.</li>
                </ol>
                <h4>Filling out the form</h4>
                <h5>Creating A Purchase Order</h5>
                <ol>
                    <li><strong>Will Ship Date: </strong>The date the order is schedualed to ship.</li>
                    <li><strong>Due Date: </strong>The date the order should reach the customer.</li>
                    <li><strong>Customer: </strong>The customer placeing the order.</li>
                    <li><strong>Purchase Order Number: </strong>The po number given by the customer.</li>
                    <li><strong>Part Number: </strong>The product part number the customer is ordering.</li>
                    <li><strong>Qty: </strong>The quantity of parts the customer requires.</li>
                    <li><strong>Stock Comments: </strong>Comments related to your stock.</li>
                    <li><strong>Sales: </strong>The approximate sales of this po.</li>
                    <li><strong>Rating: </strong>The rating importance of this order.</li>
                    <li><strong>Sooner?: </strong>Indicates if this order is needed sooner than the due date.</li>
                    <li><strong>Routers Needed?: </strong>Indicates that this order needs routers assigned to it.</li>
                    <li><strong>Customer Requirements: </strong>Any process requirements of the customer.</li>
                </ol>
                <h5>Closing A Purchase Order</h5>
                <p>Once a purchase order has been created, you now have access to extra inputs related to closing a purchase order. The information required to
                close out a purchase order are as follows:</p>
                <ol>
                    <li><strong>Invoice #: </strong>An invoice number is the first thing that is required. An invoice does not have to be created in the system before closing an order, though it is recommended to do so.</li>
                    <li><strong>Status: </strong>Each order begins with a status of 'Open'. Change the status to 'Closed' after you give an invoice number.</li>
                    <li><strong>Has Order Shipped?: </strong>Click the button 'Order Shipped!' to generate a ship date for this order. Make sure you close orders on the actual day that the order shipped or else
                    this button will produce the wrong date. If an order needs a date prior to the current date, a level 1 user has access another input where they may manually input the ship date.
                    This feature is only available to level 1 admin users so as to provide a level of honesty and document control for on-time delivery reports.</li>
                </ol>
                <p>Once you are done filling out those few inputs, click the update button below and the order will be moved to the closed orders area.</p>
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
