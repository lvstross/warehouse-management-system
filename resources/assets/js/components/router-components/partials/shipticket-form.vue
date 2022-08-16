<template>
    <div>
        <!-- Ship ticket form -->
        <h1 class="text-center">Ship Ticket Details</h1>
        <div class="row">
            <div class="col-xs-12">
                <div class="pull-right">
                    <button @click="cancelForm" v-show="edit" type="button" class="btn btn-default">X</button>
                </div>
            </div>
        </div>
        <form action="#" @submit.prevent="edit ? updateShipTicket(shipticketObj.id) : createShipTicket()">
            <div class="row">
                <!-- Part Number -->
                <div class="col-xs-12 col-sm-4">
                    <div class="form-group">
                        <label for="part_num">Part #</label>
                        <input v-model="shipticketObj.part_num" @keyup="updatePartNum" @blur="getRouters" class="form-control" type="text" name="part_num" maxlength="50" required>
                    </div>
                </div>
                <!-- End of Part Number -->
                <!-- Po Number -->
                <div class="col-xs-12 col-sm-4">
                    <div class="form-group">
                        <label for="po_num">PO #</label>
                        <input v-model="shipticketObj.po_num" @keyup="updatePoNum" class="form-control" type="text" name="po_num" maxlength="30" required>
                    </div>
                </div>
                <!-- End of Po Number -->
                <!-- Qty -->
                <div class="col-xs-12 col-sm-4">
                    <div class="form-group">
                        <label for="qty">Qty</label>
                        <input v-model="shipticketObj.qty" @keyup="updateQty" class="form-control" type="text" name="qty" maxlength="10" required>
                    </div>
                </div>
                <!-- End of Qty -->
                <!-- Customer -->
                <div class="col-xs-12 col-sm-4">
                    <div class="form-group">
                        <label for="customer">Customer</label>
                        <input v-model="shipticketObj.customer" @keyup="updateCustomer" class="form-control" type="text" name="customer" maxlength="50" required>
                    </div>
                </div>
                <!-- End of Customer -->
                <!-- Trip count checkbox -->
                <div class="col-xs-12 col-sm-4">
                    <div class="form-group">
                        <label>Triple Count Required?</label>
                        <select class="form-control" v-model="shipticketObj.trip_count" @blur="updateTripCount">
                            <option>No</option>
                            <option>Yes</option>
                        </select>
                    </div>
                </div> 
                <!-- End of Trip count checkbox -->
                <!-- Status -->
                <div v-if="edit" class="col-xs-12 col-sm-4">
                    <div class="form-group">
                        <label>Status</label>
                        <select v-model="shipticketObj.status" @blur="updateStatus" class="form-control">
                            <option v-if="shipticketObj.status != 'Canceled'">Choose An Option</option>
                            <option v-if="shipticketObj.status != 'Canceled'">Unapproved</option>
                            <option v-if="shipticketObj.status != 'Canceled'">Approved</option>
                            <option v-if="user.permission == 1">Canceled</option>
                        </select>
                    </div>
                    <p v-if="shipticketObj.status == 'Unapproved'" class="alert alert-info">Ship Ticket Still Needs Approval</p>
                </div>
                <!-- End of Status -->
            </div><!-- End of .row -->
            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group">
                        <label>Customer Requirements</label>
                        <textarea v-model="shipticketObj.cust_req" @keyup="updateCustReq" class="form-control" row="3" maxlength="500" placeholder="Press 'Enter' for every new line on the print out"></textarea>
                    </div>
                </div>
            </div><!-- End of .row -->
            <!-- Applie Routers to Inventory Section -->
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-lg-4">
                    <h3 class="text-center">Inventory</h3>
                    <div v-if="pnSet && !edit && list.length == 0">
                        <p class="alert alert-info">You have no routers available in inventory that match that part number.</p>
                    </div>
                    <transition name="fade">
                        <div class="router-cont">
                            <div v-if="pnSet && !edit && list.length > 0" :ref="'inv_cont_routers'">
                                <div v-for="router in list">
                                    <InventoryRouter 
                                        @routerId="setToBeAppliedRouters($event)" 
                                        :emit="totalRouters"
                                        :router="router">        
                                    </InventoryRouter>
                                </div>
                            </div>
                        </div>
                    </transition>
                    <transition name="fade">
                        <button v-if="toBeApplied.length > 0" @click="applyRouters" type="button" class="btn btn-success btn-md full-width">Apply Routers</button>
                    </transition>
                </div>
                <div class="col-xs-12 col-sm-6 col-lg-4">
                    <h3 class="text-center">Applied Routers</h3>
                    <div class="router-cont">
                        <div v-for="router in shipticketObj.routers">
                            <transition name="fade">
                                <InventoryRouterBack
                                    @routerBack="setToBePutBack($event)"
                                    :router="router">
                                </InventoryRouterBack>
                            </transition>
                        </div>
                    </div>
                    <transition name="fade">
                        <button @click="unApplyRouters" v-if="shipticketObj.routers.length > 0 && !edit" type="button" class="btn btn-warning btn-md full-width">Undo Routers</button>
                    </transition>
                </div>
                <div class="col-xs-12 col-sm-12 col-lg-4">
                    <h3 class="text-center">Totals</h3>
                    <div class="totals-cont">
                        <div class="row">
                            <div class="col-xs-12">
                                <p class="text-center"><strong>Totals</strong></p>
                                <p><strong>Ticket Requires:</strong> {{ shipticketObj.qty }}</p>
                                <p :class="{ redText: toBeAppliedTotal > shipticketObj.qty }"><strong>To Be Applied Total:</strong> {{ toBeAppliedTotal }}</p>
                                <p v-if="toBeAppliedTotal > shipticketObj.qty" class="alert alert-danger text-center">Your ship ticket requires {{ toBeAppliedTotal - shipticketObj.qty }} less than what you have selected.</p>
                                <p v-else-if="appliedTotal > shipticketObj.qty" class="alert alert-danger text-center">Your ship ticket requires {{ appliedTotal - shipticketObj.qty }} less than what you have selected.</p>
                                <p v-if="totalRemaining > 0" style="color: #d6b246;"><strong>Qty still needed:</strong> {{ totalRemaining }}</p>
                                <p><strong>Routers Total:</strong> {{ appliedTotal }}</p>
                            </div>
                            <div v-if="edit && user.permission == 1" class="col-xs-12">
                                <p class="text-center"><strong>Log</strong></p>
                                <div v-if="shipticketObj.log.length > 0" class="well">
                                    <ul class="little-padding-ul">
                                        <li v-for="logItem in shipticketObj.log">{{ logItem.item }}</li>
                                    </ul>
                                </div>
                                <div v-else class="well">
                                    <p class="alert alert-info text-center">No Log Items</p>
                                </div>
                            </div>
                        </div><!-- End of .row -->
                    </div>
                </div>
            </div><!-- End of .row -->
            <!-- End of  Applie Routers to Inventory Section -->
            <transition name="fade">
                <div v-if="router_mess || qty_mess" class="alert alert-danger">
                    <p v-if="router_mess" class=" text-center">{{ router_mess }}</p>
                    <p v-if="qty_mess" class=" text-center">{{ qty_mess }}</p>
                </div>
            </transition>
            <!-- Add Boxes Section -->
            <hr class="dashed">
            <h2 class="text-center">Boxes: {{ shipticketObj.boxes.length }}</h2>
            <div v-if="shipticketObj.boxes.length < 15">
                <ShipticketBox 
                    @addBox="addNewBox($event)"
                ></ShipticketBox>
            </div>
            <div v-else>
                <p class="alert alert-info text-center" v-show="shipticketObj.boxes.length == 15">You have reached the max number of boxes!</p>
            </div>
            <div class="items_cont">
                <div v-if="shipticketObj.boxes.length > 0">
                    <div v-for="(item, i) in shipticketObj.boxes" class="row space border">
                        <p class="text-center"><span class="badge">Box {{ i+1 }} of {{ shipticketObj.boxes.length }}</span></p>
                            <p class="text-center">
                                <strong>Router #:</strong> {{ item.router_num }} | 
                                <strong>Cure Qtr:</strong> {{ item.cure_qtr }} | 
                                <strong>Qty:</strong> {{ item.qty }} | 
                                <strong>Weight:</strong> {{ item.wt }} | 
                                <strong>Dimension:</strong> {{ item.dim }}
                                <button type="button" @click="removeBox(i)" class="btn btn-danger btn-xs">Remove Box</button>
                            </p>
                    </div>
                </div>
                <div v-else>
                    <p class="alert alert-info text-center">Add a box above!</p>
                </div>
            </div>
            <!-- End of add boxes section -->
            <p v-if="shipticketObj.status == 'Canceled'" class="alert alert-danger text-center">
                <i class="fa fa-exclamation-circle" aria-hidden="true"></i> 
                    By changing the ship ticket status to canceled, all the quantities of the routers set aside for this ship ticket will be put back to their original routers. 
                <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
            </p>
            <SubmitBtn :editMode="edit" :name="'Ship Ticket'"></SubmitBtn>
        </form>
    </div>
</template>
<script>
    import InventoryRouter from './inventory-router.vue';
    import InventoryRouterBack from './inventory-router-back.vue';
    import ShipticketBox from '../../partials/shipticket-boxes.vue';
    import SubmitBtn from '../../partials/submit-btn.vue';
    export default {
        data(){
            return {
                successMessage: '',
                errorMessage: '',
                router_mess: '',
                qty_mess: '',
                toBeApplied: [],
                totalRemaining: 0,
                toBeAppliedTotal: 0,
                appliedTotal: 0,
            }
        },
        components: {
            InventoryRouter,
            InventoryRouterBack,
            ShipticketBox,
            SubmitBtn
        },
        computed: {
            list() { return this.$store.getters.getRouters; },
            user() { return this.$store.getters.getUser; },
            shipticketObj() { return this.$store.state.shiptickets.shipticket; },
            pnSet() { return this.$store.getters.getPnSet; },
            edit() { return this.$store.getters.getEdit; },
            read() { return this.$store.getters.getRead; }
        },
        methods: {
            // Vuex methods
            updatePartNum(e){ this.$store.commit('updateShipTicPartNum', e.target.value); },
            updatePoNum(e){ this.$store.commit('updateShipTicPoNum', e.target.value); },
            updateQty(e){ this.$store.commit('updateShipTicQty', e.target.value); },
            updateCustomer(e){ this.$store.commit('updateShipTicCust', e.target.value); },
            updateTripCount(e){ this.$store.commit('updateShipTicTripCount', e.target.value); },
            updateStatus(e){ this.$store.commit('updateShipTicStatus', e.target.value); },
            updateCustReq(e){ this.$store.commit('updateShipTicCustReq', e.target.value); },
            addNewBox(obj){ this.$store.commit('pushToShipTicBoxes', obj); },
            removeBox(i){ this.$store.commit('removeShipTicBox', i); },
            // Local component Methods
            /**
             * Calculating the total qty from the routers that will be applied.
             */
            totalRouters(){
                let routers = this.$refs.inv_cont_routers.childNodes
                let total = 0;
                for(let i = 0; i < routers.length; i++){
                    if(routers[i].childNodes[0].childNodes[4].childNodes[0].value != ''){
                        total += Number(routers[i].childNodes[0].childNodes[4].childNodes[0].value);
                    }
                }
                this.toBeAppliedTotal = total;
            },
            /**
             * Change the appearance of routers that have been checked and applied
             */
            changeAppliedRouters(){
                let routers = this.$refs.inv_cont_routers.childNodes
                for(let i = 0; i < routers.length; i++){
                    if(routers[i].childNodes[0].childNodes[0].childNodes[0].checked == true){
                        routers[i].childNodes[0].childNodes[0].childNodes[0].checked = false; // checkbox value
                        routers[i].childNodes[0].childNodes[0].childNodes[0].style.display = 'none'; // checkbox
                        routers[i].childNodes[0].childNodes[4].childNodes[0].value = ''; // input value
                        routers[i].childNodes[0].childNodes[4].childNodes[0].style.display = 'none'; // input
                        routers[i].childNodes[0].childNodes[6].childNodes[0].value = ''; // input value
                        routers[i].childNodes[0].childNodes[6].childNodes[0].style.display = 'none'; // input
                        routers[i].childNodes[0].style.background = '#ccc';
                    }
                }
            },
            /**
             * Reset routers back to original appearance
             */
            resetRouters(){
                let routers = this.$refs.inv_cont_routers.childNodes
                for(let i = 0; i < routers.length; i++){
                    routers[i].childNodes[0].childNodes[0].childNodes[0].checked = false; // checkbox value
                    routers[i].childNodes[0].childNodes[0].childNodes[0].style.display = 'none'; // checkbox
                    routers[i].childNodes[0].childNodes[4].childNodes[0].value = ''; // input value
                    routers[i].childNodes[0].childNodes[4].childNodes[0].style.display = 'block'; // input
                    routers[i].childNodes[0].childNodes[6].childNodes[0].value = ''; // input value
                    routers[i].childNodes[0].childNodes[6].childNodes[0].style.display = 'block'; // input
                    routers[i].childNodes[0].style.background = '#fff';
                }
            },
            /**
             * Generate a log entry for every router applied to the ship ticket
             */
            generateTicketLog(){
                this.$store.commit('removeShipTicLogAll');
                let text;
                this.$store.state.shiptickets.shipticket.routers.forEach((el) => {
                    text = el.apply_qty + ' piece(s) have been applied from ' + el.r_num + ' router leaving a balance of ' + el.new_router_total + '.';
                    if(el.new_router_total == 0){ text += ' Router has been archived.'; }
                    this.$store.commit('pushToShipTicLog',{ item: text });
                });
            },
            /**
            * Generate a log entry for ship ticket cancellation
            */
            generateCanceleLog(){
                let text = '!!!CANCELED!!! Ship ticket has been canceled. All router quantities have been re-applied. !!!CANCELED!!!';
                this.$store.commit('pushToShipTicLog', { item: text });
            },
            /**
             * Push routers to array when checked, this array is the waiting zone for
             * routers that will be applied to the ship ticket
             */
            setToBeAppliedRouters(router){
                this.toBeApplied.push(router);
            },
            /**
             * Applied routers in waiting zone to the ship ticket
             */
            applyRouters(){
                this.toBeApplied = this.filterDuplicates(this.toBeApplied);
                this.toBeApplied.forEach((el)=>{
                    this.$store.commit('updateShipTicRouters', el);
                });
                this.generateTicketLog();
                this.setAppliedTotal();
                this.toBeApplied = [];
                this.toBeAppliedTotal = 0;
                this.changeAppliedRouters();
                this.setStillRemainingTotal();
            },
            /**
             * Calculate the total qty of applied routers
             */
            setAppliedTotal(){
                this.appliedTotal = 0;
                this.$store.state.shiptickets.shipticket.routers.forEach((el)=>{
                    this.appliedTotal += Number(el.apply_qty);
                });
            },
            /**
             * Calculate the total still remaining to fullfill the ship ticket qty
             */
            setStillRemainingTotal(){
                this.totalRemaining = 0;
                if(this.appliedTotal < this.$store.state.shiptickets.shipticket.qty){
                    this.totalRemaining = this.$store.state.shiptickets.shipticket.qty - this.appliedTotal;
                }
            },
            /**
             * Filter out any duplicate routers
             */
            filterDuplicates(a){
                var seen = {};
                return a.filter((item) => {
                    return seen.hasOwnProperty(item.r_num) ? false : (seen[item.r_num] = true);
                });
            },
            /**
             * Cancel the applied routers and totals and reset the interface and state
             */
            unApplyRouters(){
                this.$store.commit('removeShipTicRouters');
                this.appliedTotal = 0;
                this.toBeAppliedTotal = 0;
                this.totalRemaining = 0;
                this.$store.commit('removeShipTicLogAll');
                this.resetRouters();
            },
            getRouters(){
                this.$store.dispatch('commitRouters')
                .then(() => {
                    this.$store.commit('updateSearchPnNum', this.shipticketObj.part_num);
                    this.$store.commit('searchByPnII');
                    this.$store.commit('updatePnSet', true);
                })
                .catch((error) => {
                    this.$emit('errMessage', {message: 'Sorry! Something went wrong in the search', status: 'error', time: 5000});
                    throw new Error('searchByPn method failed!!! ' + error);
                });
            },
            createShipTicket(){
                if(this.shipticketObj.routers.length > 0 && this.shipticketObj.qty == this.appliedTotal){
                    this.$store.dispatch('createNewShipTic')
                    .then((response) => {
                        if(response.data.value == false){
                            this.$emit('errMessage', {message: response.data.message, status: 'error', time: 10000});
                            return;
                        }
                        this.$emit('toTable');
                        this.$emit('succMessage', {message: "Ship ticket successfully created!", status: 'success', time: 5000});
                        this.$store.dispatch('commitShipTickets');
                        this.unApplyRouters();
                    })
                    .catch((error) => {
                        this.$emit('errMessage', {message: "Sorry! Something went wrong when creating your ship ticket!", status: 'error', time: 10000});
                        throw new Error('Something went wrong with the dispatch for createNewShipTicket');
                    });
                }else{
                    if(this.shipticketObj.routers.length == 0){
                        this.router_mess = 'All ship tickets require at least one router. None applied.';
                        setTimeout(()=>{ this.router_mess = ''; }, 10000);
                    }
                    if(this.shipticketObj.qty != this.appliedTotal){
                        this.qty_mess = 'Applied routers quantity must match ship ticket quantity. Quantities do not match.';
                        setTimeout(()=>{ this.qty_mess = ''; }, 10000);
                    }
                }
            },
            updateShipTicket(id){
                if(this.shipticketObj.status == 'Canceled'){
                    this.generateCanceleLog();
                }
                this.$store.dispatch('updateShipTic', id)
                .then(() => {
                    this.$store.commit('updateEdit', false);
                    this.$emit('toTable');
                    this.$emit('succMessage', { message: "Ship ticket successfully updated!", status: 'success', time: 5000 });
                    this.$store.dispatch('commitShipTickets');
                })
                .catch((error) => {
                    this.$emit('errMessage', {message: "Sorry! Something went wrong when updating your ship ticket!", status: 'error', time: 10000});
                    throw new Error('Something went wrong with the dispatch for updateShipTicket');
                });
            },
            cancelForm(){
                this.$emit('toTable');
            }
        }
    }
</script>
<style scoped>
    .router-cont, .totals-cont {
        width: 100%;
        border: 1px solid #bbb;
        border-radius: 10px;
        height: 400px;
        overflow: scroll;
        padding: 10px;
        box-sizing: border-box;
    }
    .little-padding-ul {
        padding-left: 15px;
    }
    .redText {
        color: red;
    }
    .border {
        border: 1px solid #aaa;
        border-radius: 10px;
    }
    .space {
        padding: 5px 0;
        margin: 5px 0;
    }
    .items_cont {
        max-height: 350px;
        overflow-y: scroll;
    }
</style>