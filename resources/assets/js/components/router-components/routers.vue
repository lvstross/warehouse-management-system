<template>
    <div>
        <h1 class="text-center">Routers</h1>
        <hr>
        <ViewAddBtns
            :textOne="'View Routers'"
            :textTwo="'Add Router'"
            :toTable="switchToTable"
            :toForm="switchToForm"
        ></ViewAddBtns>
        <hr>
        <!-- Error and Success Messages -->
        <ErrorMessage :errorMes="errorMessage"></ErrorMessage>
        <SuccessMessage :successMes="successMessage"></SuccessMessage>
        <!-- table_routers = true  -->
        <!-- Start of Search Area -->
        <transition name="fade">
            <div v-if="!edit && !form">
                <h2 class="text-center">Search</h2>
                <div class="row">
                    <!-- Start of Search By Router Number -->
                    <div v-if="!edit" class="col-xs-12 col-md-4">
                        <form action="#" @submit.prevent="">
                            <div class="row">
                                <div class="col-xs-8 col-sm-8">
                                    <div class="form-group">
                                        <input v-model="search_router" type="text" name="search" class="form-control" placeholder="Router Number">
                                    </div>
                                </div>
                                <div class="col-xs-4 col-sm-4">
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
                    <div v-if="!edit && !form" class="col-xs-12 col-md-4">
                        <form action="#" @submit.prevent="">
                            <div class="row">
                                <div class="col-xs-8 col-sm-8">
                                    <div class="form-group">
                                        <input v-model="search_pn_num" type="text" name="search" class="form-control" placeholder="Part Number">
                                    </div>
                                </div>
                                <div class="col-xs-4 col-sm-4">
                                    <div class="form-group">
                                        <button @click="searchByPn(search_pn_num)" type="button" class="btn btn-default full-width"><i class="fa fa-search" aria-hidden="true"></i>  Search</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <button @click="cancelSearchPn" v-show="search_pn" class="btn btn-danger full-width btn-sm cancel-search-btn"><i class="fa fa-ban" aria-hidden="true"></i> Cancel Search</button>
                    </div>
                    <!-- End of Search By Part Number -->
                    <!-- Start of Search By Status -->
                    <div v-if="!edit && !form" class="col-xs-12 col-md-4">
                        <form action="#" @submit.prevent="">
                            <div class="row">
                                <div class="col-xs-8 col-sm-8">
                                    <div class="form-group">
                                        <select v-model="search_status" @blur="searchBySt(search_status)" class="form-control">
                                            <option>By Status</option>
                                            <option v-for="item in statusList" :value="item.value">{{ item.name }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-4 col-sm-4">
                                    <div class="form-group">
                                        <button class="btn btn-default full-width"><i class="fa fa-search" aria-hidden="true"></i>  Search</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <button @click="cancelSearchStatus" v-show="search_status_mode" class="btn btn-danger full-width btn-sm cancel-search-btn"><i class="fa fa-ban" aria-hidden="true"></i> Cancel Search</button>
                    </div>
                    <!-- End of Search By Status -->
                </div>
            </div>
        </transition>
        <transition name="fade">
            <div v-show="!loading && !edit && !form" class="flex-center">
                <button @click="table == true ? table = false : table = true" class="btn btn-default btn-xs">Toggle Routers Table</button>
            </div>
        </transition>
        <br>
        <!-- End of Search Area -->
        <Loader v-show="loading"></Loader>
        <!-- Start of Routers Table -->
        <transition name="fade">
            <div v-show="table && !loading">
                <p class="text-center">
                    <span>Order By: 
                        <button v-show="desc" @click="orderDesc" class="btn hover-underline btn-xs">Descending</button>
                        <button v-show="asc" @click="orderAsc" class="btn hover-underline btn-xs">Ascending</button>
                    </span>
                    <span>Show Archive:
                        <button v-show="no_arch" @click="no_arch = false" class="btn hover-underline btn-xs">Yes</button>
                        <button v-show="!no_arch" @click="no_arch = true" class="btn hover-underline btn-xs">No</button>
                    </span>
                    <span v-if="part_num_search_total != 0">Search Total: {{ part_num_search_total }}</span>
                </p>
                <div id="routers-table" v-if="list.length > 0" class="table-responsive overflow-scroll-table">
                    <table class="table table-condensed">
                        <thead>
                            <tr>
                                <th>Router #</th>
                                <th>Part #</th>
                                <th>Status</th>
                                <th>Qty</th>
                                <th>Stock Qty</th>
                                <th>Date</th>
                                <th>View</th>
                                <th>Edit</th>
                                <th v-if="user.permission == 1">Delete</th>
                            </tr>
                        </thead>
                        <tbody v-if="no_arch">
                            <tr v-for="router in list">
                                <td v-if="router.status != 'A'">{{ router.router_num }}</td>
                                <td v-if="router.status != 'A'">{{ router.part_num }}</td>
                                <td v-if="router.status == 'NIP'" class="nip-item text-center">{{ router.status }}</td>
                                <td v-else-if="router.status == 'IP'" class="ip-item text-center">{{ router.status }}</td>
                                <td v-else-if="router.status == 'STFI'" class="stfi-item text-center">{{ router.status }}</td>
                                <td v-else-if="router.status == 'II'" class="ii-item text-center">{{ router.status }}</td>
                                <td v-if="router.status != 'A'">{{ router.qty }}</td>
                                <td v-if="router.status != 'A'">{{ router.stock_qty }}</td>
                                <td v-if="router.status != 'A'">{{ newDate(router.date) }}</td>
                                <td v-if="router.status != 'A'"><button @click="readRouter(router.id)" class="btn btn-primary btn-xs"><i class="fa fa-eye" aria-hidden="true"></i> View</button></td>
                                <td v-if="router.status != 'A'"><button @click="showRouter(router.id)" class="btn btn-warning btn-xs"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</button></td>
                                <td v-if="user.permission == 1 && router.status != 'A'"><button @click="deleteRouter(router.id)" class="btn btn-danger btn-xs"><i class="fa fa-trash" aria-hidden="true"></i> Delete</button></td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr v-for="router in list">
                                <td>{{ router.router_num }}</td>
                                <td>{{ router.part_num }}</td>
                                <td v-if="router.status == 'NIP'" class="nip-item text-center">{{ router.status }}</td>
                                <td v-else-if="router.status == 'IP'" class="ip-item text-center">{{ router.status }}</td>
                                <td v-else-if="router.status == 'STFI'" class="stfi-item text-center">{{ router.status }}</td>
                                <td v-else-if="router.status == 'II'" class="ii-item text-center">{{ router.status }}</td>
                                <td v-else-if="router.status == 'A'" class="a-item text-center">{{ router.status }}</td>
                                <td>{{ router.qty }}</td>
                                <td>{{ router.stock_qty }}</td>
                                <td>{{ newDate(router.date) }}</td>
                                <td><button @click="readRouter(router.id)" class="btn btn-primary btn-xs"><i class="fa fa-eye" aria-hidden="true"></i> View</button></td>
                                <td><button @click="showRouter(router.id)" class="btn btn-warning btn-xs"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</button></td>
                                <td v-if="user.permission == 1"><button @click="deleteRouter(router.id)" class="btn btn-danger btn-xs"><i class="fa fa-trash" aria-hidden="true"></i> Delete</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-else>
                    <p class="alert alert-info text-center">You currently have no routers to show.</p>
                </div>
                <br />
                <div class="flex-center">
                    <button @click="getAllRouters" class="btn hover-underline btn-xs">Show All Routers</button>
                </div>
            </div>
        </transition>
        <!-- end of Routers table -->
        <hr v-if="!edit && table">
        <!-- Start of Viewing Area -->
        <div v-show="read && !edit">
            <transition name="fade">
                <div class="well">
                    <div class="pull-right">
                        <button @click="closeView()" class="btn btn-default full-width">X</button>
                    </div>
                    <h2 class="lg-font underline">Router #: {{routerObj.router_num}}</h2>
                    <strong class="mid-font">Part Number: <span>{{routerObj.part_num}}</span></strong><br>
                    <strong class="mid-font">Date: <span>{{ newDate(routerObj.date) }}</span></strong><br>
                    <strong class="mid-font">Qty: <span>{{routerObj.qty}}</span></strong><br>
                    <strong class="mid-font">Stock Qty: <span>{{routerObj.stock_qty}}</span></strong><br>
                    <strong class="mid-font">Date In Inventory: <span>{{ newDate(routerObj.date_in_inv) }}</span></strong><br>
                    <strong class="mid-font">Department: <span>{{ routerObj.dept_name }}</span></strong><br>
                    <strong class="mid-font">Router Status:
                      <span v-if="routerObj.status == 'NIP'">Not In Production</span>
                      <span v-if="routerObj.status == 'IP'">In Production</span>
                      <span v-if="routerObj.status == 'STFI'">Staged For Inventory</span>
                      <span v-if="routerObj.status == 'II'">In Inventory</span>
                      <span v-if="routerObj.status == 'A'">Archive</span>
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
                </div>
            </transition>
        </div>
        <!-- End of Viewing Area -->
        <div v-show="edit && !loading" class="pull-right">
            <button type="button" @click="cancelEdit" class="btn btn-default">X</button>
        </div>
        <!-- Start Routers Form -->
        <transition name="fade">
            <div v-show="form && !loading">
              <RouterForm
                :edit="edit"
                @EditEvent="edit = $event"
                @FormEvent="form = $event" 
                @TableEvent="table = $event"
                @ReadEvent="read = $event"
                @SearchEvent="cancelAllSearch()"
                @createEndFalse="form = $event"
                @successMessage="message($event.message, $event.status, $event.time)"
                @errorMessage="message($event.message, $event.status, $event.time)"
                @updateRouterFunction="updateRouter = $event"
                @showRouterFunction="showRouter = $event"
                @deleteRouterFunction="deleteRouter = $event"
                @readRouterFunction="readRouter = $event"
              ></RouterForm>
            </div>
        </transition>
        <!-- End Routers Form -->
        <Instructions></Instructions>
    </div>
</template>
<script>
    import RouterForm from '../../components/router-components/partials/routers-form.vue';
    import ErrorMessage from '../../components/partials/error-message.vue';
    import SuccessMessage from '../../components/partials/success-message.vue';
    import ViewAddBtns from '../../components/partials/view-add-btns.vue';
    import Loader from '../../components/partials/loader.vue';
    import Instructions from '../info-components/routers-inst.vue';
    import Utils from '../../modules/utils.js';
    export default {
        data(){
            return {
                statusList: [
                    { name: 'Not In Production', value: 'NIP' },
                    { name: 'In Production', value: 'IP' },
                    { name: 'Staged For Inventory', value: 'STFI' },
                    { name: 'In Inventory', value: 'II' },
                    { name: 'Archive', value: 'A' },
                ],
                // Modes
                table: false,
                loading: true,
                edit: false,
                form: false,
                read: false,
                search: false,
                search_pn: false,
                search_status_mode: false,
                report: false,
                no_arch: true,
                desc: false,
                asc: true,
                // Messages
                errorMessage: '',
                successMessage: '',
                // Data
                search_status: 'By Status',
                start: '',
                end: '',
                rep_name: '',
            }
        },
        mounted() {
            this.loadIn();
        },
        components: {
            RouterForm,
            ErrorMessage,
            SuccessMessage,
            ViewAddBtns,
            Loader,
            Instructions
        },
        computed: {
          list() { return this.$store.getters.getRoutersByRouterNum; },
          part_num_search_total() { return this.$store.getters.getRouterSearchTotal; },
          user() { return this.$store.getters.getUser; },
          routerObj() { return this.$store.state.routers.router; },
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
                if(param == 'addRouter'){
                    this.form = true;
                    this.table = false;
                }else if(param == 'viewRouters'){
                    this.form = false;
                }
            },
            loadIn(){
                setTimeout(()=> {
                    this.loading = false;
                    this.setApplication();
                }, 1500)
            },
            // local functions to be assigned the emited event functions from the routers form component
            updateRouter(){},
            showRouter(){},
            deleteRouter(){},
            readRouter(){},
            getAllRouters(){ 
                this.loading = true;
                this.table = false;
                this.search = false;
                this.search_pn = false;
                this.search_status_mode = false;
                this.$store.commit('updateSearchRouter', '');
                this.$store.commit('updateSearchPnNum', '');
                this.search_status = 'By Status';
                this.$store.dispatch('commitAllRouters')
                .then(()=>{
                    this.loading = false;
                    this.table = true;
                })
                .catch((error)=>{
                    this.message('Sorry! Something went wrong in getting all your routers.', 'error', 5000);
                    throw new Error('getAllRouters failed!!! ' + error);
                });
            },
            orderDesc(){ 
                this.$store.commit('orderByDesc');
                this.asc = true;
                this.desc = false;
            },
            orderAsc(){ 
                this.$store.commit('orderByAsc');
                this.asc = false;
                this.desc = true;
            },
            closeView(){
                this.$store.commit('resetState');
                this.read = false;
            },
            switchToTable() {
                if(this.form){
                    this.form = false;
                    this.edit = true ? this.cancelEdit() :
                    this.$store.commit('resetState');
                }
            },
            switchToForm() {
                this.cancelAllSearch();
                this.table = false;
                this.read = false;
                setTimeout(()=>{
                    this.form = true;
                }, 500);
            },
            searchByRouter(router){
                this.$store.commit('updateRouterPnSearchTotal', 0);
                this.$store.dispatch('commitRouters')
                .then(() => {
                    this.$store.commit('searchByRouter', 'router');
                    this.search = true;
                    this.search_pn = false;
                    this.search_status_mode = false;
                    this.$store.commit('updateSearchPnNum', '');
                    this.search_status = 'By Status';
                    this.table = true;
                })
                .catch((error) => {
                    this.message('Sorry! Something went wrong in the search', 'error', 5000);
                    throw new Error('searchByRouter failed!!! ' + error);
                })
            },
            searchByPn(part){
                this.$store.dispatch('commitRouters')
                .then(() => {
                    this.$store.commit('searchByPn', 'router');
                    this.search_pn = true;
                    this.search = false;
                    this.search_status_mode = false;
                    this.$store.commit('updateSearchRouter', '');
                    this.search_status = 'By Status';
                    this.table = true;
                })
                .catch((error) => {
                    this.message('Sorry! Something went wrong in the search', 'error', 5000);
                    throw new Error('searchByPn method failed!!! ' + error);
                });
            },
            searchBySt(status){
                this.$store.commit('updateRouterPnSearchTotal', 0);
                status == 'A' ? this.no_arch = false : this.no_arch = true;
                this.$store.dispatch('commitRouters')
                .then(() => {
                    this.$store.commit('searchBySt', status);
                    this.search_status_mode = true;
                    this.search = false;
                    this.search_pn = false;
                    this.$store.commit('updateSearchPnNum', '');
                    this.$store.commit('updateSearchRouter', '');
                    this.table = true;
                })
                .catch((error) => {
                    this.message('Sorry! Something went wrong in the search', 'error', 5000);
                    throw new Error('searchBySt method failed!!! ' + error);
                });
            },
            cancelEdit(){
                this.edit = false;
                this.form = false;
                this.$store.commit('resetState');
            },
            cancelAllSearch(){
                this.search = false;
                this.$store.commit('updateSearchRouter', '');
                this.search_pn = false;
                this.$store.commit('updateSearchPnNum', '');
                this.$store.commit('updateRouterPnSearchTotal', 0);
                this.search_status_mode = false;
                this.search_status = 'By Status';
                this.no_arch = true;
                this.$store.dispatch('commitRouters');
            },
            cancelSearch() {
                this.search = false;
                this.table = false;
                this.$store.commit('updateSearchRouter', '');
                this.$store.dispatch('commitRouters');
            },
            cancelSearchPn() {
                this.search_pn = false;
                this.table = false;
                this.$store.commit('updateSearchPnNum', '');
                this.$store.commit('updateRouterPnSearchTotal', 0);
                this.$store.dispatch('commitRouters');
            },
            cancelSearchStatus() {
                this.search_status_mode = false;
                this.no_arch = true;
                this.table = false;
                this.search_status = 'By Status';
                this.$store.dispatch('commitRouters');
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
    .nip-item {
        background: #bf839d;
        color: #fff;
    }
    .ip-item {
        background: #6aa6b5;
        color: #fff;
    }
    .stfi-item {
        background: #c9a976;
        color: #fff;
    }
    .ii-item {
        background: #7abaa2;
        color: #fff;
    }
    .a-item {
        background: #939393;
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
</style>
