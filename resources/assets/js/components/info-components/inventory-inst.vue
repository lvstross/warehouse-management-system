<template>
    <div>
        <!-- Instruction Area -->
        <i @click="instruction = true" class="fa fa-question-circle fa-2x pointer" aria-hidden="true"></i>
        <transition name="fade">
            <div v-show="instruction" class="well">
                <i @click="instruction = false" class="fa fa-times-circle pull-right fa-2x pointer"></i>
                <h3 class="text-center">Router Inventory and Inventory Ship Ticket Instructions</h3>
                <p>Welcome to the inventory interface. There are three major sections that comprise this area of the system:</p>
                <ol>
                    <li><strong>Search Inventory</strong> which allows you to search your router stock by 'Router Number' and 'Part Number'.</li>
                    <li><strong>View Ship Tickets</strong> which allows you to view your recent inventory ship ticket data.</li>
                    <li><strong>Add Ship Tickets</strong> which allows you to create inventory ship tickets.</li>
                </ol>
                <h4>Search Inventory</h4>
                <p>The 'Search Inventory' section is pretty simple. All routers in this area have the 'II' or 'In Inventory' status which means they contain stock quantities that are separate from the normal starting 
                quantity. This is because the stock qty is the quantity that will be used to trake part availability when creating ship tickets. This interface allows you to search for a specific router by it's router number 
                or you can get a total of all routers that have a certain part number. You may also just peak into your general inventory by clicking the 'Show All Inventory" button. This will give you a report on all routers 
                in stock with their relevant data which is viewable by clicking the 'View' button next to each item in the list.</p>
                <h4>View Ship Tickets</h4>
                <p>The 'View Ship Tickets' section is similar to most other table based interfaces in the system. You are able to view your most recent ship tickets, search ship tickets by part number, date they were created or by status. 
                You table by default only shows you the ship ticket for the past twelve months. If you wish to view ship tickets from longer than twelve months ago, you may either click the 'Show All Ship Tickets' button at the bottom of 
                the ship tickets table or search by an earlier date.</p>
                <h4>Add Ship Ticket</h4>
                <p>What the ship ticket does is designate router stock quntities to a certain shipment. When a ship ticket is made, the stock quantities are updated on each router used in the ship ticket. Log items will be added to those routers and their stock 
                quantity will reflect the new quantity. If a routers stock quantity reaches zero, the router will be moved to 'Archive' status where the router will no longer be available for use for inventory.</p>
                <p>The instructions for the ship ticket form go as follows:</p>
                <ol>
                    <li><strong>Part #:</strong> when you select a part number, the inventory box below will populate with a list of routers in your inventory that have that same part number. They are sorted in order of when they entered 
                    'In Inventory' status. So if you wish to pull from the oldest sources of inventory, then just select from the top of the listing when selecting your routers to apply to the ship ticket.</li>
                    <li><strong>PO #:</strong> the purchase order number of the order you are fulfilling.</li>
                    <li><strong>Qty: </strong> the quantity of parts you wish to ship for this purchase order. The quantity you chose will be shown below in the totals section. This area keeps track of your ship ticket totals as you build your 
                    ship ticket.</li>
                    <li><strong>Customer:</strong> the customer you are shipping these parts to.</li>
                    <li><strong>Triple Count Required?:</strong> if you desire the inventory ship ticket to print out a secion for triple person count, select 'Yes', otherwise a triple count section will be left out of the ship ticket all together.</li>
                    <li><strong>Status:</strong> during the creation of a ship ticket, you may not see a 'Status' field. This is because all ship tickets are created with a default status of 'Unapproved'. Only when editing a ship ticket will the 'Status' 
                    field appear in order that a quality inspector may verify and approved the ship ticket. Simply switch the value to 'Approved' in order to approve the ship ticket. <span v-if="user.permission == 1 || user.permission == 2">*** CANCELATION *** When canceling a ship ticket, all actions on the 
                    used routers will be reversed which means the stock quantity that was taken from those routers will be added back to those routers and if those routers were in 'Archive' status, they will be moved back to 'In Inventory' status. The cancelation 
                    process is unreversable so cancel with caution.</span></li>
                    <li><strong>Customer Requirements:</strong> this feild is where you will list all of the customer requirements for your shipment. This information will be printed out on the inventory ship ticket in a numbered list. For every bullent point 
                    simple press 'Enter' to start a new line. Each new line will be processed as a numbered point. This includes empty new lines, so please refraine from putting double spacing between entries.</li>
                    <li><strong>Apply Routers Section:</strong> This section is where you will apply stock quantity to your ship ticket. In the 'Inventory' box, you may use the empty fields in each router box to apply an amount of parts from that router 
                    to your ship ticket. If the quantity is with in the amount of the stock quantity a checkbox will appear and the container will turn green. If you try to apply a quantity that is beyond the amound available for that router, the checkbox will 
                    disappear and the container will turn red. While applying quantities to your ship ticket, the 'Totals' box is keeping a running total of parts being applied and will notify you if you are about to apply to much with a red alert box. Once 
                    you have checked off the routers you want to apply you will notice an 'Apply Routers' button has appear. Once you click this button all the routers you have selected will be applied to the ship ticket and will turn gray. At this point, if you have 
                    made any mistakes in applying routers, you will have to click the newly appeard 'Undo Routers' button below the 'Applied Routers' box which will reset all the applied routers back to normal for you to re-apply again. ***QUARK ALERT*** after clicking the
                    'Undo Routers' button, the router containers need to be reset again in order to flash green and show the checkboxes. To do this, simply type in any number in the number field and then delete it leaving the fields empty again. At this point the container functionality should go 
                    back to normal.</li>
                    <li><strong>Boxes:</strong> this is where you will add box information to your ship ticket. You are allowed up to 15 box entries.</li>
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
        computed: {
            user() { return this.$store.getters.getUser; },
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
