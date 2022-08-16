<template>
    <div>
        <!-- Start of Search Area -->
        <transition name="fade">
            <div>
                <h2 class="text-center">Search Inventory Ship Tickets</h2>
                <div class="row">
                    <!-- Start of Search By Part Number -->
                    <div class="col-xs-12 col-md-4">
                        <form action="#" @submit.prevent="">
                            <div class="row">
                                <div class="col-xs-8 col-sm-8">
                                    <div class="form-group">
                                        <input v-model="PnToSearch" type="text" name="search" class="form-control" placeholder="Part Number">
                                    </div>
                                </div>
                                <div class="col-xs-4 col-sm-4">
                                    <div class="form-group">
                                        <button @click="search(PnToSearch, 'PN')" type="button" class="btn btn-default full-width"><i class="fa fa-search" aria-hidden="true"></i>  Search</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <button v-show="search_pn_mode" @click="cancelPnSearch(0)" class="btn btn-danger full-width btn-sm cancel-search-btn"><i class="fa fa-ban" aria-hidden="true"></i> Cancel Search</button>
                    </div>
                    <!-- End of Search By Part Number -->
                    <!-- Start of Search By Po Number -->
                    <div class="col-xs-12 col-md-4">
                        <form action="#" @submit.prevent="">
                            <div class="row">
                                <div class="col-xs-8 col-sm-8">
                                    <div class="form-group">
                                        <input v-model="DateToSeach" type="date" name="search" class="form-control" placeholder="Date">
                                    </div>
                                </div>
                                <div class="col-xs-4 col-sm-4">
                                    <div class="form-group">
                                        <button @click="search(DateToSeach, 'DA')" type="button" class="btn btn-default full-width"><i class="fa fa-search" aria-hidden="true"></i>  Search</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <button v-show="search_date_mode" @click="cancelDateSearch(0)" class="btn btn-danger full-width btn-sm cancel-search-btn"><i class="fa fa-ban" aria-hidden="true"></i> Cancel Search</button>
                    </div>
                    <!-- End of Search By Po Number -->
                    <!-- Start of Search By Status -->
                    <div class="col-xs-12 col-md-4">
                        <form action="#" @submit.prevent="">
                            <div class="row">
                                <div class="col-xs-8 col-sm-8">
                                    <div class="form-group">
                                        <select v-model="StToSearch" class="form-control">
                                            <option>By Status</option>
                                            <option value="Unapproved">Unapproved</option>
                                            <option value="Approved">Approved</option>
                                            <option value="Canceled">Canceled</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-4 col-sm-4">
                                    <div class="form-group">
                                        <button @click="search(StToSearch, 'ST')" class="btn btn-default full-width"><i class="fa fa-search" aria-hidden="true"></i>  Search</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <button v-show="search_st_mode" @click="cancelStSearch(0)" class="btn btn-danger full-width btn-sm cancel-search-btn"><i class="fa fa-ban" aria-hidden="true"></i> Cancel Search</button>
                    </div>
                    <!-- End of Search By Status -->
                </div>
            </div>
        </transition>
        <div v-show="!loading" class="flex-center">
            <button @click="table == false ? table = true : table = false" class="btn btn-default btn-xs">View Recent Ship Tickets</button>
        </div>
        <br>
        <div v-show="loading" class="flex-center">
            <Loader></Loader>
        </div>
        <transition name="fade">
            <div v-show="table && !loading">
                <div id="shipticket-table" v-if="list.length > 0" class="table-responsive overflow-scroll-table">
                    <table class="table table-condensed">
                        <thead>
                            <tr>
                                <th>Part #</th>
                                <th>PO #</th>
                                <th>Customer</th>
                                <th>Qty</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Print</th>
                                <th>View</th>
                                <th v-if="user.permission == 1 || user.permission == 2 || user.permission == 3">Edit</th>
                                <th v-if="user.permission == 1">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="ticket in list">
                                <td>{{ ticket.part_num }}</td>
                                <td>{{ ticket.po_num }}</td>
                                <td>{{ ticket.customer }}</td>
                                <td>{{ ticket.qty }}</td>
                                <td>{{ newDate(ticket.date) }}</td>
                                <td v-if="ticket.status == 'Unapproved'" class="yellowText">{{ ticket.status }}</td>
                                <td v-else-if="ticket.status == 'Approved'" class="greenText">{{ ticket.status }}</td>
                                <td v-else-if="ticket.status == 'Canceled'" class="redText">{{ ticket.status }}</td>
                                <td v-else>{{ ticket.status }}</td>
                                <td v-if="ticket.status == 'Canceled'"><button type="button" class="btn btn-default btn-xs" disabled><i class="fa fa-print" aria-hidden="true"></i> Print</button></td>
                                <td v-else ><a :href="'/pdf/shipticket/' + ticket.id" class="btn btn-default btn-xs"><i class="fa fa-print" aria-hidden="true"></i> Print</a></td>
                                <td><button @click="viewShipTic(ticket.id)" class="btn btn-primary btn-xs"><i class="fa fa-eye" aria-hidden="true"></i> View</button></td>
                                <td v-if="user.permission == 1 || user.permission == 2 || user.permission == 3"><button @click="showShipTic(ticket.id)" class="btn btn-warning btn-xs"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</button></td>
                                <td v-if="user.permission == 1"><button @click="deleteShipTic(ticket.id)" class="btn btn-danger btn-xs"><i class="fa fa-trash" aria-hidden="true"></i> Delete</button></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="flex-center">
                        <button @click="getAllShipTickets" class="btn hover-underline btn-xs">Show All Ship Tickets</button>
                    </div>
                </div>
                <div v-else> 
                    <p class="alert alert-info text-center">You currently have no ship tickets to show.</p>
                </div>
                <!-- end of ship ticekts table -->
                <!-- Start of Viewing Area -->
                <transition name="fade">
                    <div v-show="read && !edit">
                        <div class="well">
                            <div class="pull-right">
                                <button @click="closeView" class="btn btn-default full-width">X</button>
                            </div>
                            <div class="clear-both"></div>
                            <h2 v-if="shipticketObj.status == 'Unapproved'" class="alert alert-warning lg-font text-center">Status: {{ shipticketObj.status }}</h2>
                            <h2 v-if="shipticketObj.status == 'Approved'" class="alert alert-success lg-font text-center">Status: {{ shipticketObj.status }}</h2>
                            <h2 v-if="shipticketObj.status == 'Canceled'" class="alert alert-danger lg-font text-center">Status: {{ shipticketObj.status }}</h2>
                            <strong class="mid-font">Part Number: <span>{{shipticketObj.part_num}}</span></strong><br>
                            <strong class="mid-font">Date of Creation: <span>{{ newDate(shipticketObj.date) }}</span></strong><br>
                            <strong class="mid-font">PO Number: <span>{{shipticketObj.po_num}}</span></strong><br>
                            <strong class="mid-font">Qty: <span>{{shipticketObj.qty}}</span></strong><br>
                            <strong class="mid-font">Customer: <span>{{shipticketObj.customer}}</span></strong><br>
                            <strong class="mid-font">Triple Person Count?: <span>{{ shipticketObj.trip_count }}</span></strong><br>
                            <strong class="mid-font">Customer Requirements: <span>{{ shipticketObj.cust_req }}</span></strong><br>
                            <!-- Applied Routers Section -->
                            <div class="row">
                              <div class="col-xs-12" v-if="shipticketObj.routers.length == 0">
                                <p class="alert alert-info text-center">No Applied Routers</p>
                              </div>
                              <div class="col-xs-12" v-if="shipticketObj.routers.length > 0">
                                <p class="mid-font text-center">Routers Applied to Ship Ticket</p>
                                <div class="well">
                                    <div class="table-responsive">
                                        <table class="table table-condensed">
                                            <thead>
                                                <tr>
                                                    <th>Router #</th>
                                                    <th>Cure Qtr</th>
                                                    <th>Applied Qty</th>
                                                    <th>Old Router Qty</th>
                                                    <th>New Router Qty</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="item in shipticketObj.routers">
                                                    <td>{{ item.r_num }}</td>
                                                    <td>{{ item.cure_qtr }}</td>
                                                    <td>{{ item.apply_qty }}</td>
                                                    <td>{{ item.old_qty }}</td>
                                                    <td>{{ item.new_router_total }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                              </div>
                            </div>
                            <!-- End of Applied Routers Section -->
                            <!-- Boxes Section -->
                            <div class="row">
                              <div class="col-xs-12" v-if="shipticketObj.boxes.length == 0">
                                <p class="alert alert-info text-center">No Boxes Applied</p>
                              </div>
                              <div class="col-xs-12" v-if="shipticketObj.boxes.length > 0">
                                <p class="mid-font text-center">Boxes</p>
                                <div class="well">
                                    <div class="table-responsive">
                                        <table class="table table-condensed">
                                            <thead>
                                                <tr>
                                                    <th>Box #</th>
                                                    <th>Router #(s)</th>
                                                    <th>Cure Qtr</th>
                                                    <th>Qty</th>
                                                    <th>Weight</th>
                                                    <th>Dimension</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(item, index) in shipticketObj.boxes">
                                                    <td>{{ index+1 }} of {{ shipticketObj.boxes.length }}</td>
                                                    <td>{{ item.router_num }}</td>
                                                    <td>{{ item.cure_qtr }}</td>
                                                    <td>{{ item.qty }}</td>
                                                    <td>{{ item.wt }}</td>
                                                    <td>{{ item.dim }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                              </div>
                            </div>
                            <!-- End of Boxes Section -->
                            <!-- Router Log Section -->
                            <div class="row">
                              <div class="col-xs-12" v-if="shipticketObj.log.length == 0">
                                <p class="alert alert-info text-center">Router Log Empty</p>
                              </div>
                              <div class="col-xs-12" v-if="shipticketObj.log.length > 0">
                                <p class="mid-font text-center">Log</p>
                                <div class="well">
                                    <div class="table-responsive">
                                        <table class="table table-condensed">
                                            <thead>
                                                <tr>
                                                    <th>Lot Item #</th>
                                                    <th>Log Info</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(item, index) in shipticketObj.log">
                                                    <td>{{ index+1 }}</td>
                                                    <td>{{ item.item }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                              </div>
                            </div>
                            <!-- End of Router Log Section -->
                        </div>
                    </div>
                </transition>
                <!-- End of Viewing Area -->
            </div>
        </transition>
    </div>
</template>
<script>
    import Loader from '../../partials/loader.vue';
    import Utils from '../../../modules/utils.js';
    export default {
        data(){
            return {
                table: false,
                successMessage: '',
                errorMessage: '',
                PnToSearch: '',
                DateToSeach: '',
                StToSearch: 'By Status',
                search_pn_mode: false,
                search_date_mode: false,
                search_st_mode: false,
                loading: false
            }
        },
        components: {
            Loader
        },
        computed: {
            user() { return this.$store.getters.getUser; },
            list() { return this.$store.getters.getShipTickets; },
            shipticketObj() { return this.$store.state.shiptickets.shipticket; },
            edit() { return this.$store.getters.getEdit; },
            read() { return this.$store.getters.getRead; }
        },
        methods: {
            getAllShipTickets(){ 
                this.table = false;
                this.loading = true;
                this.cancelAllSearch();
                this.$store.dispatch('commitAllShipTickets')
                .then(()=>{
                    this.loading = false;
                    this.table = true;
                })
                .catch(()=>{
                    this.message('Sorry! Something went wrong in getting all your ship tickets', 'error', 5000);
                    throw new Error('getAllShipTickets failed!!! ' + error);
                });
            },
            newDate(date){
                return Utils.invertDate(date);
            },
            closeView(){
                this.$store.commit('updateRead', false);
                setTimeout(()=>{
                    this.$store.commit('resetState');
                }, 300);
            },
            cancelAllSearch(){
                this.search_pn_mode = false;
                this.PnToSearch = '';
                this.search_date_mode = false;
                this.DateToSeach = '';
                this.search_st_mode = false;
                this.StToSearch = false;
            },
            cancelPnSearch(r){ 
                this.search_pn_mode = false;
                this.PnToSearch = '';
                if(r){ return; }
                this.$store.dispatch('commitShipTickets');
             },
            cancelDateSearch(r){ 
                this.search_date_mode = false;
                this.DateToSeach = '';
                if(r){ return; }
                this.$store.dispatch('commitShipTickets');
             },
            cancelStSearch(r){ 
                this.search_st_mode = false;
                this.StToSearch = 'By Status';
                if(r){ return; }
                this.$store.dispatch('commitShipTickets');
             },
            viewShipTic(id){
                this.$store.dispatch('showShipTic', id)
                .then(()=>{
                    this.$store.commit('updateRead', true);
                }).catch((error)=>{
                    this.$emit('errMessage', {message: "Sorry! Something went wrong when showing your ship ticket!", status: 'error', time: 10000});
                    throw new Error('Something went wrong with the dispatch for showShipTic');
                })
            },
            showShipTic(id){
                this.$store.dispatch('showShipTic', id)
                .then(()=>{
                    this.$emit('toForm');
                }).catch((error)=>{
                    this.$emit('errMessage', {message: "Sorry! Something went wrong when showing your ship ticket!", status: 'error', time: 10000});
                    throw new Error('Something went wrong with the dispatch for showShipTic');
                });
            },
            deleteShipTic(id){
                this.$store.dispatch('deleteShipTic', id)
                .then(() => {
                    this.$store.dispatch('commitShipTickets');
                    this.$emit('succMessage', {message: "Ship ticket successfully deleted!", status: 'success', time: 5000});
                })
                .catch((error) => {
                    this.$emit('errMessage', {message: "Sorry! Something went wrong when deleting your ship ticket!", status: 'error', time: 10000});
                    throw new Error('Something went wrong with the dispatch for deleteShipTicket');
                });
            },
            search(term, action){
                if(action == 'PN'){ 
                    this.cancelDateSearch(1);
                    this.cancelStSearch(1);
                    this.search_pn_mode = true;
                    this.$store.dispatch('shipTicPnSearch', term)
                    .then(()=>{ console.log('success'); })
                    .catch((error)=>{
                        this.$emit('errMessage', {message: "Sorry! Something went wrong when getting your ship tickets!", status: 'error', time: 10000});
                        throw new Error('Something went wrong with the dispatch for shipTicPnSearch');
                    });
                }else if(action == 'DA'){
                    this.cancelPnSearch(1);
                    this.cancelStSearch(1);
                    this.search_date_mode = true;
                    this.$store.dispatch('shipTicDateSearch', term)
                    .then(()=>{ console.log('success'); })
                    .catch((error)=>{
                        this.$emit('errMessage', {message: "Sorry! Something went wrong when getting your ship tickets!", status: 'error', time: 10000});
                        throw new Error('Something went wrong with the dispatch for shipTicDateSearch');
                    });
                }else if(action == 'ST'){
                    this.cancelPnSearch(1);
                    this.cancelDateSearch(1);
                    this.search_st_mode = true;
                    this.$store.dispatch('shipTicStSearch', term)
                    .then(()=>{ console.log('success'); })
                    .catch((error)=>{
                        this.$emit('errMessage', {message: "Sorry! Something went wrong when getting your ship tickets!", status: 'error', time: 10000});
                        throw new Error('Something went wrong with the dispatch for shipTicStSearch');
                    });
                }
                this.table = true;
            }
        }
    }
</script>
<style scoped>
    .cancel-search-btn {
        margin-bottom: 18px;
    }
    .redText {
        color: #a62f61;
    }
    .greenText {
        color: #29a77a;
    }
    .yellowText {
        color: #c58e32;
    }
</style>