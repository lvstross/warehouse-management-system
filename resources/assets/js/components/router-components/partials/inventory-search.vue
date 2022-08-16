<template>
    <div>
        <!-- Error and Success Messages -->
        <ErrorMessage :errorMes="errorMessage"></ErrorMessage>
        <SuccessMessage :successMes="successMessage"></SuccessMessage>
        <!-- table_routers = true  -->
        <!-- Start of Search Area -->
        <div>
            <h1 class="text-center">Search Inventory</h1>
            <div class="row">
                <!-- Start of Search By Router Number -->
                <div class="col-xs-12 col-sm-4">
                    <form action="#" @submit.prevent="">
                        <div class="row">
                            <div class="col-xs-8 col-sm-6 col-lg-8">
                                <div class="form-group">
                                    <input v-model="search_router" type="text" name="search" class="form-control" placeholder="Router Number">
                                </div>
                            </div>
                            <div class="col-xs-4 col-sm-6 col-lg-4">
                                <div class="form-group">
                                    <button @click="searchByRouter(search_router)" type="button" class="btn btn-default full-width"><i class="fa fa-search" aria-hidden="true"></i>  Search</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <button @click="cancelSearch" v-show="search" class="btn btn-danger full-width btn-sm cancel-search-btn"><i class="fa fa-ban" aria-hidden="true"></i> Cancel</button>
                </div>
                <!-- End of Search By Router Number -->
                <!-- Start of Search By Part Number -->
                <div class="col-xs-12 col-sm-4">
                    <form action="#" @submit.prevent="">
                        <div class="row">
                            <div class="col-xs-8 col-sm-6 col-lg-8">
                                <div class="form-group">
                                    <input v-model="search_pn_num" type="text" name="search" class="form-control" placeholder="Part Number">
                                </div>
                            </div>
                            <div class="col-xs-4 col-sm-6 col-lg-4">
                                <div class="form-group">
                                    <button @click="searchByPn(search_pn_num)" type="button" class="btn btn-default full-width"><i class="fa fa-search" aria-hidden="true"></i>  Search</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <button @click="cancelSearchPn" v-show="search_pn" class="btn btn-danger full-width btn-sm cancel-search-btn"><i class="fa fa-ban" aria-hidden="true"></i> Cancel Search</button>
                </div>
                <!-- End of Search By Part Number -->
                <!-- Get all routers button -->
                <div class="col-xs-12 col-sm-4">
                    <button @click="getAllInventoryRouters" class="btn btn-primary full-width">Show All Inventory</button>
                </div>
                <!-- End of Get all routers button -->
            </div>
        </div>
        <!-- End of Search Area -->
        <Loader v-show="loading"></Loader>
        <!-- Start of Routers Table -->
        <transition name="fade">
            <div v-show="!loading">
                <div v-if="!loading && (search || search_pn || all)">
                    <p class="text-center">Sorted By: Date In Inventory</p>
                    <div id="routers-table" v-if="list.length > 0" class="table-responsive">
                        <table class="table table-condensed">
                            <thead>
                                <tr>
                                    <th>Router #</th>
                                    <th>Part #</th>
                                    <th>Status</th>
                                    <th>Stock Qty</th>
                                    <th>Date In Inventory</th>
                                    <th>View</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="router in list">
                                    <td>{{ router.router_num }}</td>
                                    <td>{{ router.part_num }}</td>
                                    <td v-if="router.status == 'II'" class="ii-item text-center">{{ router.status }}</td>
                                    <td>{{ router.stock_qty }}</td>
                                    <td>{{ newDate(router.date_in_inv) }}</td>
                                    <td><button @click="readRouter(router.id)" class="btn btn-primary btn-xs"><i class="fa fa-eye" aria-hidden="true"></i> View</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-else>
                        <p class="alert alert-info text-center">You currently have no routers to show.</p>
                    </div>
                </div>
                <!-- end of Routers table -->
                <hr v-if="!loading && (search || search_pn)" class="dashed">
                <!-- Start of Part Number Routers Total -->
                <div v-if="!loading && (search || search_pn)">
                    <div v-if="pnTotal != 0" class="full-width">
                        <p class="pull-right mid-font">Total Stock Qty: {{ pnTotal }}</p>
                        <div class="clear-fix"></div>
                    </div>
                </div>
            </div>
        </transition>
        <!-- End of Part Number Routers Total -->
        <hr>
        <br>
        <!-- Start of Viewing Area -->
        <div v-show="read">
            <transition name="fade">
                <div class="well">
                    <h2 class="lg-font underline">Router #: {{routerObj.router_num}}</h2>
                    <strong class="mid-font">Part Number: <span>{{routerObj.part_num}}</span></strong><br>
                    <strong class="mid-font">Date: <span>{{ newDate(routerObj.date) }}</span></strong><br>
                    <strong class="mid-font">Qty: <span>{{routerObj.qty}}</span></strong><br>
                    <strong class="mid-font">Stock Qty: <span>{{routerObj.stock_qty}}</span></strong><br>
                    <strong class="mid-font">Employee Assigned: <span>{{routerObj.employee}}</span></strong><br>
                    <strong class="mid-font">Date In Inventory: <span>{{ newDate(routerObj.date_in_inv) }}</span></strong><br>
                    <strong class="mid-font">Router Status:
                      <span v-if="routerObj.status == 'II'">In Inventory</span>
                    </strong><br>
                    <div class="row">
                      <div class="col-xs-12" v-if="routerObj.move_log.length == 0">
                        <p class="alert alert-info text-center">Move Log Empty</p>
                      </div>
                      <div class="col-xs-12" v-if="routerObj.move_log.length > 0">
                        <p class="mid-font text-center">Move Log</p>
                        <div class="well">
                            <p v-for="(item, index) in routerObj.move_log">{{index+1}}. {{item.item}}</p>
                        </div>
                      </div>
                    </div>
                    <button @click="closeView()" class="btn btn-danger full-width">Close Viewing</button>
                </div>
            </transition>
        </div>
        <!-- End of Viewing Area -->
    </div>
</template>
<script>
    import ErrorMessage from '../../../components/partials/error-message.vue';
    import SuccessMessage from '../../../components/partials/success-message.vue';
    import Loader from '../../../components/partials/loader.vue';
    import Utils from '../../../modules/utils.js';
    export default {
        data(){
            return {
                // Modes
                read: false,
                all: false,
                // loading: false,
                search: false,
                search_pn: false,
                report: false,
                // Messages
                errorMessage: '',
                successMessage: '',
                // Data
                start: '',
                end: '',
                rep_name: '',
            }
        },
        mounted() {
            this.setApplication();
        },
        components: {
            ErrorMessage,
            SuccessMessage,
            Loader
        },
        computed: {
          loading(){ return this.$store.getters.getLoad; },
          list() { return this.$store.getters.getInventory; },
          user() { return this.$store.getters.getUser; },
          routerObj() { return this.$store.state.routers.router; },
          pnTotal() { return this.$store.state.routers.search_pn_total; },
          search_router: { 
            get() {
                return this.$store.state.routers.search_router;   
            },
            set(value) {
                this.$store.commit('updateSearchRouter', value);
            }
          },
          search_pn_num: {
            get() {
                return this.$store.state.routers.search_pn_num;
            },
            set(value) {
                this.$store.commit('updateSearchPnNum', value);
            }
          }
        },
        methods: {
            setApplication(){
                let param = Utils.getUriParam();
                if(param == 'searchInventory'){
                    console.log('Show Search Inventory');
                }else if(param == 'viewInventory'){
                    console.log('Show View Inventory');
                }else if(param == 'addInventory'){
                    console.log('Show Add Inventory');
                }
            },
            getAllInventoryRouters(){
                this.$store.commit('setLoad', true);
                this.search = false;
                this.search_pn = false;
                this.$store.commit('updateSearchRouter', '');
                this.$store.commit('updateSearchPnNum', '');
                this.$store.commit('updateSearchPnTotal');
                this.$store.dispatch('commitAllRouters')
                .then(()=>{
                    this.all = true;
                    this.$store.commit('setLoad', false);
                })
                .catch((error)=>{
                    this.message("Sorry! Something went wrong when retrieving your router!", 'error', 10000);
                    throw new Error('Something went wrong with the dispatch for commitAllRouters');
                });
            },
            readRouter(id){ // get request to show an router for viewing
                this.$store.dispatch('showRouter', {r_id: id, mode: 'view'})
                .then(() => {
                    this.read = true;
                })
                .catch((error) => {
                    this.message("Sorry! Something went wrong when retrieving your router!", 'error', 10000);
                    throw new Error('Something went wrong with the dispatch for showRouter');
                });
            },
            closeView(){
                this.$store.commit('resetState');
                this.read = false;
            },
            loadOut(){
                setTimeout(()=>{
                    this.$store.commit('setLoad', false);
                }, 1000)
            },
            searchByRouter(router){
                this.$store.commit('setLoad', true);
                this.$store.dispatch('commitAllRouters')
                .then(() => {
                    this.$store.commit('searchByRouter', 'date');
                    this.search = true;
                    this.search_pn = false;
                    this.$store.commit('updateSearchPnNum', '');
                    this.loadOut();
                })
                .catch((error) => {
                    this.message('Sorry! Something went wrong in the search', 'error', 5000);
                    throw new Error('searchByRouter failed!!! ' + error);
                })
            },
            searchByPn(part){
                this.$store.commit('setLoad', true);
                this.$store.dispatch('commitAllRouters')
                .then(() => {
                    this.$store.commit('searchByPn', 'date');
                    this.search_pn = true;
                    this.search = false;
                    this.$store.commit('updateSearchRouter', '');
                    this.$store.commit('getSearchPnTotal');
                    this.loadOut();
                })
                .catch((error) => {
                    this.message('Sorry! Something went wrong in the search', 'error', 5000);
                    throw new Error('searchByPn method failed!!! ' + error);
                });
            },
            cancelSearch() {
                this.search = false;
                this.$store.commit('updateSearchRouter', '');
                this.$store.dispatch('commitAllRouters');
            },
            cancelSearchPn() {
                this.search_pn = false;
                this.$store.commit('updateSearchPnNum', '');
                this.$store.commit('updateSearchPnTotal');
                this.$store.dispatch('commitAllRouters');
            },
            newDate(date){
                return Utils.invertDate(date);
            },
            message(message, setting="success", timing){
                if(setting == 'success'){
                    this.successMessage = message;
                    setTimeout(()=>{
                        this.successMessage = '';
                    }, timing);
                } else if (setting == 'error'){
                    this.errorMessage = message;
                    setTimeout(()=>{
                        this.errorMessage = '';
                    }, timing);
                }
            },
        }
    }
</script>
<style scoped>
    #routers-table {
        max-height: 565px;
        overflow: scroll;
    }
    .ii-item {
        background: #7abaa2;
        color: #fff;
    }
    .cust_top_margin {
        margin-top: 32px;
    }
    .btn-margin {
        margin-top: 27px;
    }
    .space-below {
        margin-bottom: 20px;
    }
    .wide {
        width: 100%;
    }
    .cancel-search-btn {
        margin-bottom: 18px;
    }
    @media(max-width:1345px){
        .fa-search {
            display: none;
        }
    }
</style>