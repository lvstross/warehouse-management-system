<template>
    <div>
        <ViewAddBtns 
            :textOne="'View Vendors'"
            :textTwo="'Add Vendor'"
            :toTable="switchToTable"
            :toForm="switchToForm">    
        </ViewAddBtns>
        <hr>
        <Loader v-show="loading"></Loader>
        <!-- Messages -->
        <ErrorMessage :errorMes="errorMessage"></ErrorMessage>
        <SuccessMessage :successMes="successMessage"></SuccessMessage>
        <!-- End of Messages -->
        <transition name="fade">
            <div v-show="table && !loading">
                <!-- Vendors Table -->
                <div v-if="list.length > 0" class="table-responsive overflow-scroll-table">
                    <table class="table table-condensed">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Contact</th>
                                <th>Type</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Ext</th>
                                <th>Website</th>
                                <th>View</th>
                                <th v-if="user.permission == 1 || user.permission == 2">Edit</th>
                                <th v-if="user.permission == 1">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="vendor in list">
                                <td>{{ vendor.name }}</td>
                                <td>{{ vendor.contact }}</td>
                                <td>{{ vendor.type }}</td>
                                <td>{{ vendor.email }}</td>
                                <td>{{ vendor.phone }}</td>
                                <td>{{ vendor.ext }}</td>
                                <td>{{ vendor.website }}</td>
                                <td><button @click="viewVendor(vendor.id)" class="btn btn-default btn-xs"><i class="fa fa-eye" aria-hidden="true"></i> View</button></td>
                                <td v-if="user.permission == 1 || user.permission == 2"><button @click="showVendor(vendor.id)" class="btn btn-warning btn-xs"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</button></td>
                                <td v-if="user.permission == 1"><button @click="deleteVendor(vendor.id)" class="btn btn-danger btn-xs"><i class="fa fa-trash" aria-hidden="true"></i> Delete</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-else> 
                    <p class="alert alert-info text-center">You currently have no vendors to show.</p>
                </div>
            </div>
            <!-- end of vendors table -->
        </transition>
        <transition name="fade">
            <div v-show="read" class="well">
                <div class="pull-right">
                    <button class="btn btn-default full-width" @click="closeView()">X</button>
                </div>
                <h2 class="lg-font">{{ vendor.name }}</h2>
                <strong class="mid-font">Contact: </strong><span>{{ vendor.contact }}</span><br>
                <strong class="mid-font">Phone: </strong><span>{{ vendor.phone }}</span><br>
                <strong class="mid-font">Fax: </strong><span>{{ vendor.fax }}</span><br>
                <strong class="mid-font">Extension: </strong><span>{{ vendor.ext }}</span><br>
                <strong class="mid-font">Email: </strong><span>{{ vendor.email }}</span><br>
                <strong class="mid-font">Website: </strong><span>{{ vendor.website }}</span><br>
                <strong class="mid-font">Type: </strong><span>{{ vendor.type }}</span><br>
                <strong class="mid-font">Shipping Address: </strong><span>{{ vendor.ship_address }}</span><br>
                <strong class="mid-font">Purchasing Address: </strong><span>{{ vendor.purch_address }}</span><br>
                <strong class="mid-font">Remittance Address: </strong><span>{{ vendor.remit_address }}</span><br>
                <strong class="mid-font">Comments To Vendor: </strong><span>{{ vendor.comment_to }}</span><br>
            </div>
        </transition>
        <hr>
        <!-- Add Vendor Form -->
        <transition name="fade">
            <div v-show="form">
                <div v-show="edit && !loading" class="pull-right">
                    <button type="button" @click="cancelEdit" class="btn btn-default">X</button>
                </div>
                <h2 class="text-center">Add Vendor</h2>
                <form action="#" @submit.prevent="edit ? updateVendor(vendor.id) : createVendor()">

                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-4">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input v-model="vendor.name" type="text" name="name" class="form-control" required maxlength="50">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4">
                            <div class="form-group">
                                <label for="contact">Contact</label>
                                <input v-model="vendor.contact" type="text" name="contact" class="form-control" maxlength="50">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4">
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input v-model="vendor.phone" type="tel" name="phone" class="form-control" maxlength="30">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4">
                            <div class="form-group">
                                <label for="fax">Fax</label>
                                <input v-model="vendor.fax" type="tel" name="fax" class="form-control" maxlength="30">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4">
                            <div class="form-group">
                                <label for="ext">Extension</label>
                                <input v-model="vendor.ext" type="text" name="ext" class="form-control" maxlength="10">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input v-model="vendor.email" type="email" name="email" class="form-control">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4">
                            <div class="form-group">
                                <label for="website">Website</label>
                                <input v-model="vendor.website" type="url" name="website" class="form-control" placeholder="http://www.vendor.com">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4">
                            <div class="form-group">
                                <label for="type">Vendor Type</label>
                                <input v-model="vendor.type" type="text" name="type" class="form-control" maxlength="50">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label for="ship_address">Shipping Address</label>
                                <textarea v-model="vendor.ship_address" type="text" name="ship_address" class="form-control" rows="3" maxlength="255"></textarea>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label for="purch_address">Purchased Address</label>
                                <textarea v-model="vendor.purch_address" type="text" name="purch_address" class="form-control" rows="3" maxlength="255"></textarea>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label for="remit_address">Remittance Address</label>
                                <textarea v-model="vendor.remit_address" type="text" name="remit_address" class="form-control" rows="3" maxlength="255"></textarea>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label for="comment_to">Comments To Vendor</label>
                                <textarea v-model="vendor.comment_to" type="text" name="comment_to" class="form-control" rows="4" maxlength="500"></textarea>
                            </div>
                        </div>
                    </div>
                    <SubmitBtns :editMode="edit" :name="name='Vendor'"></SubmitBtns>
                </form>
            </div>
        </transition>
        <br />
        <br />
        <!-- End of add vendor form -->
        <!-- Instruction Area -->
        <Instructions></Instructions>
        <!-- End of Instructions -->
    </div>
</template>

<script>
    // Imports
    import ViewAddBtns from '../components/partials/view-add-btns.vue';
    import SubmitBtns from '../components/partials/submit-btn.vue';
    import ErrorMessage from '../components/partials/error-message.vue';
    import SuccessMessage from '../components/partials/success-message.vue';
    import Instructions from '../components/info-components/customer-inst.vue';
    import Loader from '../components/partials/loader.vue';
    import Utils from '../modules/utils.js';
    // Export
    export default {
        data() {
            return {
                edit: false, 
                table: true, 
                read: false, 
                form: false,
                loading: true,
                // Vendor Data Model
                vendor: { 
                    id: '',
                    name: '',
                    contact: '',
                    phone: '',
                    fax: '',
                    ext: '',
                    email: '',
                    website: '',
                    type: '',
                    ship_address: '',
                    purch_address: '',
                    remit_address: '',
                    comment_to: ''
                },
                // property for error messages
                errorMessage: '',
                successMessage: '',
            }
        },
        mounted() { 
            this.getUser();
            this.getVendors();
            this.loadIn();
        },
        components: {
            ViewAddBtns,
            SubmitBtns,
            ErrorMessage,
            SuccessMessage,
            Loader,
            Instructions
        },
        computed: {
            /**
             * Get the user permissions
             * @return number
             */
            user() { 
                return this.$store.getters.getUser; 
            },
            /**
             * Get the list of vendors from the vuex state
             * @return Array
             */
            list() { 
                return this.$store.getters.getVendors; 
            }
        },
        methods: {
            /*
            *===== COMPONENT METHODS =====
            */
            loadIn(){
                setTimeout(()=>{
                    this.loading = false;
                }, 1000)
            },
            switchToTable(){ // prop: toTable | component: <viewAddBtns>
                if(this.form){
                    for(var key in this.vendor){
                        this.vendor[key] = '';
                    }
                    this.form = false;
                    setTimeout(()=>{
                        this.table = true;
                    }, 500);
                }
            },
            switchToForm(){ // prop: toForm | component: <viewAddBtns>
                this.table = false;
                setTimeout(()=>{
                    this.form = true;
                }, 500);
            },
            getUser(){ this.$store.dispatch('commitPermission'); },
            getVendors(){ this.$store.dispatch('commitVendors'); },
            // ===== C.R.U.D methods =====
            createVendor(){
                axios({
                    method: 'post',
                    url: 'api/vendors/store',
                    data: this.vendor,
                    validateStatus(status) {
                        if(status >= 200 && status < 300){
                            return status;
                        }else if(status == 401){
                            alert("Your session has timed out. You will now be redirected.");
                            window.location = window.location.origin + '/login';
                        }
                    }
                }).then(() => {
                    this.resetValues();
                    this.message("Vendor has successfully been created!", 'success', 5000);
                }).catch((error) => {
                    throw new Error("Create Vendor Failed! " + error.message);
                    this.message("Sorry! Something went wrong when creating your vendor!", 'error', 10000); 
                });
            },
            updateVendor(id){
                axios({
                    method: 'patch',
                    url: 'api/vendors/' + id,
                    data: this.vendor,
                    validateStatus(status) {
                        if(status >= 200 && status < 300){
                            return status;
                        }else if(status == 401){
                            alert("Your session has timed out. You will now be redirected.");
                            window.location = window.location.origin + '/login';
                        }
                    }
                }).then(() => {
                    this.resetValues();
                    this.message("Vendor has successfully updated!", 'success', 5000);
                }).catch((error) => {
                    throw new Error("Update Vendor Failed! " + error.message);
                    this.message("Sorry! Something went wrong when updating your vendor!", 'error', 10000);
                });
            },
            showVendor(id){
                axios({
                    method: 'get',
                    url: 'api/vendors/' + id,
                    validateStatus(status) {
                        if(status >= 200 && status < 300){
                            return status;
                        }else if(status == 401){
                            alert("Your session has timed out. You will now be redirected.");
                            window.location = window.location.origin + '/login';
                        }
                    }
                }).then((response) => {
                    this.table = false;
                    this.read = false;
                    for (var key in this.vendor){
                        this.vendor[key] = response.data[key];
                    }
                    setTimeout(()=>{
                        this.form = true;
                        this.edit = true;
                    }, 500);
                }).catch((error) => {
                    throw new Error("Show Vendor Failed! " + error.message);
                    this.message("Sorry! Something went wrong when receiving your vendor info!", 'error', 10000);
                });
            },
            viewVendor(id){
                axios({
                    method: 'get',
                    url: 'api/vendors/' + id,
                    validateStatus(status) {
                        if(status >= 200 && status < 300){
                            return status;
                        }else if(status == 401){
                            alert("Your session has timed out. You will now be redirected.");
                            window.location = window.location.origin + '/login';
                        }
                    }
                }).then((response) => {
                    this.read = true;
                    for (var key in this.vendor){
                        this.vendor[key] = response.data[key];
                    }
                }).catch((error) => {
                    throw new Error("View Vendor Failed! " + error.message);
                    this.message("Sorry! Something went wrong when receiving your vendor info!", 'error', 10000);
                });
            },
            deleteVendor(id){
                if(confirm('Are you sure you want to delete this vendor?')){
                    axios({
                        method: 'delete',
                        url: 'api/vendors/' + id,
                        validateStatus(status) {
                            if(status >= 200 && status < 300){
                                return status;
                            }else if(status == 401){
                                alert("Your session has timed out. You will now be redirected.");
                                window.location = window.location.origin + '/login';
                            }
                        }
                    }).then((response) => {
                        this.getVendors();
                        this.message("Vendor has been successfully deleted!", 'success', 5000);
                    }).catch((error) => {
                        throw new Error("Delete Vendor Failed! " + error.message);
                        this.message("Sorry! Something went wrong when deleting your vendor!", 'error', 10000);
                    });
                }else{
                    return;
                }
            },
            closeView(){
                this.read = false;
                setTimeout(()=>{
                    this.resetValues();
                }, 500);
            },
            cancelEdit(){
                if(confirm("Are you sure you wish to cancel updating?")){
                    for (var key in this.vendor){
                        this.vendor[key] = '';
                    }
                    this.form = false;
                    this.edit = false;
                    setTimeout(()=>{
                        this.table = true;
                    }, 500);
                }
            },
            resetValues(){
                for (var key in this.vendor){
                    this.vendor[key] = '';
                }
                this.edit = false;
                this.form = false;
                setTimeout(()=>{
                    this.getVendors();
                    this.table = true;
                }, 500)
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
            }
        }
    }
</script>