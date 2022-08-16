<template>
    <div>
        <!-- Instruction Area -->
        <i @click="instruction = true" class="fa fa-question-circle fa-2x pointer" aria-hidden="true"></i>
        <transition name="fade">
            <div v-show="instruction" class="well">
                <i @click="instruction = false" class="fa fa-times-circle pull-right fa-2x pointer"></i>
                <h3 class="text-center">Routers Instructions</h3>
                <p>Welcome to the routers interface. There are two sections to this area:</p>
                <ol>
                    <li>View Routers</li>
                    <li>Add Router</li>
                </ol>
                <h4>View Routers</h4>
                <p>The 'View Routers' area is where you will view your most recent routers or search for specific routers in order to view their data. To view the most recent routers in the last twelve months, simply click the 
                'Toggle Routers Table' button since the table is closed by default. The routers are automaticly sorted in decending order by their router number. If you wish to re-sort them into ascending order, click the 'ascending' button 
                at the top of the table. This button is togglable for sorting by descending order and ascending order. If you wish to view all routers ever made in the system, click the 'Show All Routers' button below the routers table. 
                Above the routers table is the search area. Here you may search by router number, part number or by status. There are five different status' that a router can have:</p>
                <ul>
                    <li>(NIP) Not In Production: New Routers that haven't been placed in a department.</li>
                    <li>(IP) In Production: Routers moved into departments.</li>
                    <li>(STFI) Staged For Inventory: Routers being preped for inventory.</li>
                    <li>(II) In Inventory: Routers available as stock.</li>
                    <li>(A) Archive: Routers' stock qty that have reached zero after once being in inventory.</li>
                </ul>
                <p>A routers status is controlled by where it's placed in the part flow as well as how it's used in the inventory area. Each router contains a move log that tracks all activity that the router has under gone such as being moved 
                between departments in the partflow, taking quantity for ship tickets or when a routers status has changed. If you are at least a level 2 user, you may add custom move log entries as well as edit the partflow data on each router.</p>
                <h4>Add Router</h4>
                <p><strong>Adding</strong> a router is easy. Simply give it a router number, choose a part number, give it a quantity and the current date.</p>
                <div v-if="user.permission == 1 || user.permission == 2">
                    <p><strong>Editing</strong> a router is a little different. For those who have at least level 2 permissions, you are able to view the advanced options on the routers which is all the data that is captured as the router moves through the 
                    partflow and is used in the inventory area. Any of this data is maliable, however, it is best to edit it with caution since this data much of this data is captured in sync with each other. If you desire to edit this data, proceed with the 
                    the following cautions:</p>
                    <ol>
                        <li><strong>Date In Inventory &amp; Stock Qty:</strong> The 'Date In Inventory' value is captured when the routers has been submitted to inventory through the partflow. At this point the router quantity is add to the stock quantity. The 'Date In Inventory' 
                        data is then used in the inventory area to show routers that have been submitted to inventory in a first in, first out fashion. If this date is changed, it may not accuratly represent the parts that have entered into inventory. The 'Stock Qty' 
                        is simplier to edit as nothing in the system checks if it has a quantiy unless you are trying to use it in the inventory ship ticket area which will not allow you to use it unless it has a 'Stock Qty' of at least one.</li>
                        <li><strong>Status &amp; Department:</strong> Editing the status is simple but does have drastic effects. If the router status is changed from 'In Production' to any other status, the router will forget what department it was in last and will cut off the department field. 
                        If the router is changed to 'In Production', you must give it a department to be in. If not, the router will be sorted into the 'No Department' red box in the partflow.</li>
                        <li><strong>Move Log:</strong> The move log data is the simplest and least consiquential part of the router data to edit. The router does not depend on the move log at all. The move log is simply there to provide tracability and extra comments to a router as it 
                        goes through it's production cycle. If something unique happens to the router through out it's life cycle, you may add to the move log any information that would be helpful to know down the road.</li>
                    </ol>
                </div>
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
