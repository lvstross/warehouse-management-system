<template>
    <div>
        <ViewAddBtns 
            :textOne="'View Customers'"
            :textTwo="'Add Customer'"
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
                <!-- Customers Table -->
                <div id="customer-table" v-if="list.length > 0" class="table-responsive overflow-scroll-table">
                    <table class="table table-condensed">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Buyer</th>
                                <th>Country</th>
                                <th>View</th>
                                <th v-if="user.permission == 1 || user.permission == 2">Edit</th>
                                <th v-if="user.permission == 1">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="customer in list">
                                <td>{{ customer.name }}</td>
                                <td>{{ customer.buyer }}</td>
                                <td>{{ customer.country }}</td>
                                <td><button @click="viewCustomer(customer.id)" class="btn btn-default btn-xs"><i class="fa fa-eye" aria-hidden="true"></i> View</button></td>
                                <td v-if="user.permission == 1 || user.permission == 2"><button @click="showCustomer(customer.id)" class="btn btn-warning btn-xs"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</button></td>
                                <td v-if="user.permission == 1"><button @click="deleteCustomer(customer.id)" class="btn btn-danger btn-xs"><i class="fa fa-trash" aria-hidden="true"></i> Delete</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-else> 
                    <p class="alert alert-info text-center">You currently have no customers to show.</p>
                </div>
            </div>
            <!-- end of customer table -->
        </transition>
        <transition name="fade">
            <div v-show="read" class="well">
                <div class="pull-right">
                    <button class="btn btn-default full-width" @click="closeView()">X</button>
                </div>
                <h2 class="lg-font">{{ customer.name }}</h2>
                <strong class="mid-font">Buyer: </strong><span>{{ customer.buyer }}</span><br>
                <strong class="mid-font">Ship To Address: </strong><span>{{ customer.shipto }}</span><br>
                <strong class="mid-font">Bill To Address: </strong><span>{{ customer.billto }}</span><br>
                <strong class="mid-font">Country: </strong><span>{{ customer.country }}</span><br>
                <strong class="mid-font">Disclaimer: </strong><span>{{ customer.disclaimer }}</span><br>
                <strong class="mid-font">Comments: </strong><span>{{ customer.comments }}</span><br>
            </div>
        </transition>
        <hr>
        <!-- Add customer Form -->
        <transition name="fade">
            <div v-show="form">
                <div v-show="edit && !loading" class="pull-right">
                    <button type="button" @click="cancelEdit" class="btn btn-default">X</button>
                </div>
                <h2 class="text-center">Add Customer</h2>
                <form action="#" @submit.prevent="edit ? updateCustomer(customer.id) : createCustomer()">

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input v-model="customer.name" @keyup="regexNameCheck(customer.name)" type="text" name="name" class="form-control" required maxlength="50">
                        <p class="alert alert-warning" v-if="customer.name.length == 50">50 character limit reached!</p>
                        <p class="alert alert-danger" v-if="regexNameWarning">{{ regexNameWarning }}</p>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label for="buyer">Buyer</label>
                                <input v-model="customer.buyer" @keyup="regexBuyerCheck(customer.buyer)" type="text" name="buyer" class="form-control" required maxlength="50">
                                <p class="alert alert-warning" v-if="customer.buyer.length == 50">50 character limit reached!</p>
                                <p class="alert alert-danger" v-if="regexBuyerWarning">{{ regexBuyerWarning }}</p>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label for="country">Country</label>
                                <input v-model="customer.country" @keyup="regexCountryCheck(customer.country)" type="text" name="country" class="form-control" maxlength="15">
                                <p class="alert alert-warning" v-if="customer.country.length == 15">15 character limit reached!</p>
                                <p class="alert alert-danger" v-if="regexCountryWarning">{{ regexCountryWarning }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label for="billto">Bill To Address</label>
                                <textarea v-model="customer.billto" @keyup="regexBilltoCheck(customer.billto)" type="text" name="billto" class="form-control" rows="3" required maxlength="255"></textarea>
                                <p class="alert alert-warning" v-if="customer.billto.length == 255">255 character limit reached!</p>
                                <p class="alert alert-danger" v-if="regexBilltoWarning">{{ regexBilltoWarning }}</p>
                            </div>
                        </div>                
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label for="shipto">Ship To Address</label>
                                <textarea v-model="customer.shipto" @keyup="regexShiptoCheck(customer.shipto)" type="text" name="shipto" class="form-control" rows="3" required maxlength="255"></textarea>
                                <p class="alert alert-warning" v-if="customer.shipto.length == 255">255 character limit reached!</p>
                                <p class="alert alert-danger" v-if="regexShiptoWarning">{{ regexShiptoWarning }}</p>
                            </div>
                        </div>                
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label for="disclaimer">Shipper Statement</label>
                                <textarea v-model="customer.disclaimer" @keyup="regexDiscCheck(customer.disclaimer)" type="text" name="disclaimer" class="form-control" rows="3" maxlength="500"></textarea>
                                <p class="alert alert-warning" v-if="customer.disclaimer.length == 500">500 character limit reached!</p>
                                <p class="alert alert-danger" v-if="regexDiscWarning">{{ regexDiscWarning }}</p>
                            </div>
                        </div>                
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label for="comments">Comments</label>
                                <textarea v-model="customer.comments" @keyup="regexComCheck(customer.comments)" type="text" name="comments" class="form-control" rows="3" maxlength="255"></textarea>
                                <p class="alert alert-warning" v-if="customer.comments.length == 255">255 character limit reached!</p>
                                <p class="alert alert-danger" v-if="regexComWarning">{{ regexComWarning }}</p>
                            </div>
                        </div>
                    </div>
                    <ErrorMessage :errorMes="errorMessage"></ErrorMessage>
                    <SubmitBtns :editMode="edit" :name="name='Customer'"></SubmitBtns>
                </form>
            </div>
        </transition>
        <br />
        <br />
        <!-- End of add customer form -->
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
                edit: false, // Hides or shows edit mode which changes the text and functionality of the submit button.
                table: true, // If true, the customers table is showing. If false, the customers form is showing.
                read: false, // If true, the customer information is showing, if false, customer information is hidden.
                form: false,
                loading: true,
                // Customer Data Model
                customer: { 
                    id: '',
                    name: '',
                    buyer: '',
                    shipto: '',
                    billto: '',
                    country: '',
                    // Disclaimer changed to "Shipper Stament" on the front end for visual purposes
                    // disclaimer is still used for code logic.
                    disclaimer: '',
                    comments: ''
                },
                // property for error messages
                errorMessage: '',
                successMessage: '',
                // List of warning properties that have value added by there corrisponding regex[name]Check methods below.
                regexNameWarning: '', 
                regexBuyerWarning: '',
                regexPhoneWarning: '',
                regexCountryWarning: '',
                regexShiptoWarning: '',
                regexBilltoWarning: '',
                regexDiscWarning: '',
                regexComWarning: ''
            }
        },
        mounted() { 
            /* 
            * when vue instance is mounted, get the 
            * customers and the authenticated user.
            */
            this.getUser();
            this.getCustomers();
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
             * Get the list of customers from the vuex state
             * @return Array
             */
            list() { 
                return this.$store.getters.getCustomers; 
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
                    for(var key in this.customer){
                        this.customer[key] = '';
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
            /**
             * Dispatches to 'commitPermission' action in vuex store to
             * call user permission to be commited to 'setPermission'
             * mutation. 
             */
            getUser(){
                this.$store.dispatch('commitPermission');
            },
            /**
             * Dispatches to 'commitCustomers' action in vuex store to
             * get the customers array to be commited to 'setCustomers'
             * mutation.
             */
            getCustomers(){
                this.$store.dispatch('commitCustomers');
            },
            // ===== C.R.U.D methods =====
            /**
             * Creates new customer resource. Runs form validation.
             */
            createCustomer(){
                this.valueCheck();
                axios({
                    method: 'post',
                    url: 'api/customers/store',
                    data: this.customer,
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
                    this.message("Customer has successfully been created!", 'success', 5000);
                }).catch((error) => {
                    throw new Error("Create Customer Failed! " + error.message);
                    this.message("Sorry! Something went wrong when creating your customer!", 'error', 10000); 
                });
            },
            /**
             * Updates specific resourse by it's id
             *
             * @param id | number
             * @return void
             */
            updateCustomer(id){
                this.valueCheck();
                axios({
                    method: 'patch',
                    url: 'api/customers/' + id,
                    data: this.customer,
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
                    this.message("Customer has successfully updated!", 'success', 5000);
                }).catch((error) => {
                    throw new Error("Update Customer Failed! " + error.message);
                    this.message("Sorry! Something went wrong when updating your customer!", 'error', 10000);
                });
            },
            /**
             * Gets a specific resource by it's id to be
             * updated.
             *
             * @param id | number
             * @return void
             */
            showCustomer(id){
                axios({
                    method: 'get',
                    url: 'api/customers/' + id,
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
                    for (var key in this.customer){
                        this.customer[key] = response.data[key];
                    }
                    setTimeout(()=>{
                        this.form = true;
                    }, 500);
                }).catch((error) => {
                    throw new Error("Show Customer Failed! " + error.message);
                    this.message("Sorry! Something went wrong when receiving your customer info!", 'error', 10000);
                });
                this.edit = true;
            },
            /**
             * Gets a specific resourse by it's id to
             * be viewed only.
             *
             * @param id | number
             * @return void
             */
            viewCustomer(id){
                axios({
                    method: 'get',
                    url: 'api/customers/' + id,
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
                    for (var key in this.customer){
                        this.customer[key] = response.data[key];
                    }
                }).catch((error) => {
                    throw new Error("View Customer Failed! " + error.message);
                    this.message("Sorry! Something went wrong when receiving your customer info!", 'error', 10000);
                });
            },
            /**
             * Delete a specific resource by it's id. Only
             * an admin user can delete resources
             *
             * @param id | number
             * @return void
             */
            deleteCustomer(id){
                if(confirm('Are you sure you want to delete this customer?')){
                    axios({
                        method: 'delete',
                        url: 'api/customers/' + id,
                        validateStatus(status) {
                            if(status >= 200 && status < 300){
                                return status;
                            }else if(status == 401){
                                alert("Your session has timed out. You will now be redirected.");
                                window.location = window.location.origin + '/login';
                            }
                        }
                    }).then((response) => {
                        this.getCustomers();
                        this.message("Customer has been successfully deleted!", 'success', 5000);
                    }).catch((error) => {
                        throw new Error("Delete Customer Failed! " + error.message);
                        this.message("Sorry! Something went wrong when deleting your customer!", 'error', 10000);
                    });
                }else{
                    return;
                }
            },
            /**
             * Closes the resource viwing and resets state
             */
            closeView(){
                this.resetValues();
                this.read = false;
            },
            /**
             * Cancel Editing
             */
            cancelEdit(){
                if(confirm("Are you sure you wish to cancel updating?")){
                    for (var key in this.customer){
                        this.customer[key] = '';
                    }
                    this.form = false;
                    this.edit = false;
                    setTimeout(()=>{
                        this.table = true;
                    }, 500);
                }
            },
            /*
            * Regex methods for each of the feilds. Tried to tie all of this up into one function but
            * it was buggy and didn't work everytime. It worked a lot better when each field had
            * it's own regex check. 
            *
            * Each regex method has a empty string check because it would throw the error even if the
            * field was empty, so I added a check for emptiness and it would set the warning to an empty
            * string as well. 
            *
            * In the conditional statment for the pattern test as well, it needed an else statment to get ride
            * of the error for when the user got ride of the unapproved character but the field wasn't empty. 
            */

            /**
             * Check the pattern of the name field.
             *
             * @param string | string
             * @return void
             */
            regexNameCheck(string){
                var pattern = /^(?!-)(?!.*--)[A-Za-z0-9\,\.\s]+$/;
                if(string == ''){
                    this.regexNameWarning = '';
                    return;
                }
                if(pattern.test(string) != true){
                    this.regexNameWarning = "Unapproved characters detected! Only alphabetical characters, numeric characters, commas and periods are approved for this field.";
                    return;
                } else {
                    this.regexNameWarning = '';
                }
            },
            /**
             * Checks the pattern of the buyer field.
             *
             * @param string | string
             * @return void
             */
            regexBuyerCheck(string){
                var pattern = /^(?!-)(?!.*--)[A-Za-z\,\.\s]+$/;
                if(string == ''){
                    this.regexBuyerWarning = '';
                    return;
                }
                if(pattern.test(string) != true){
                    this.regexBuyerWarning = "Unapproved characters detected! Only alphabetical characters, commas and periods are approved for this field.";
                    return;
                } else {
                    this.regexBuyerWarning = '';
                }
            },
            /**
             * Checks the pattern of the country field.
             *
             * @param string | string
             * @param return void
             */
            regexCountryCheck(string){
                var pattern = /^(?!-)(?!.*--)[A-Za-z\,\.\s]+$/;
                if(string == ''){
                    this.regexCountryWarning = '';
                    return;
                }
                if(pattern.test(string) != true){
                    this.regexCountryWarning = "Unapproved characters detected! Only alphabetical characters, commas and periods are approved for this field.";
                    return;
                } else {
                    this.regexCountryWarning = '';
                }
            },
            /**
             * Checks the pattern of the shipto field.
             *
             * @param string | string
             * @return void
             */
            regexShiptoCheck(string){
                var pattern = /^(?!-)(?!.*--)[A-Za-z0-9\,\-\#\@\(\)\:\.\s]+$/;
                if(string == ''){
                    this.regexShiptoWarning = '';
                    return;
                }
                if(pattern.test(string) != true){
                    this.regexShiptoWarning = "Unapproved characters detected! List of approved characters: a-z, A-Z, 0-9, highens, commas and periods. However, '--' is not allowed.";
                    return;
                } else {
                    this.regexShiptoWarning = '';
                } 
            },
            /**
             * Checks the pattern of the billto field.
             *
             * @param string | string
             * @return void
             */
            regexBilltoCheck(string){
                var pattern = /^(?!-)(?!.*--)[A-Za-z0-9\,\-\#\@\(\)\:\.\s]+$/;
                if(string == ''){
                    this.regexBilltoWarning = '';
                    return;
                }
                if(pattern.test(string) != true){
                    this.regexBilltoWarning = "Unapproved characters detected! List of approved characters: a-z, A-Z, 0-9, highens, commas and periods. However, '--' is not allowed.";
                    return;
                } else {
                    this.regexBilltoWarning = '';
                } 
            },
            /**
             * Checks the pattern of the disclaimer field.
             *
             * @param string | string
             * @return void
             */
            regexDiscCheck(string){
                var pattern = /^(?!-)(?!.*--)[A-Za-z0-9\,\&\-\(\)\/\'\.\*\#\s]+$/;
                if(string == ''){
                    this.regexDiscWarning = '';
                    return;
                }
                if(pattern.test(string) != true){
                    this.regexDiscWarning = "Unapproved characters detected! List of approved characters: a-z, A-Z, 0-9, &, -, (), /, ', ., *, #, commas and periods. However, '--' is not allowed.";
                    return;
                } else {
                    this.regexDiscWarning = '';
                }
            },
            /**
             * Checks the pattern of the comments field.
             *
             * @param string | string
             * @return void
             */
            regexComCheck(string){
                var pattern = /^(?!-)(?!.*--)[A-Za-z0-9\,\&\-\(\)\/\"\.\*\#\s]+$/;
                if(string == ''){
                    this.regexComWarning = '';
                    return;
                }
                if(pattern.test(string) != true){
                    this.regexComWarning = "Unapproved characters detected! List of approved characters: a-z, A-Z, 0-9, &, -, (), /, *, #, commas and periods. However, '--' is not allowed.";
                    return;
                } else {
                    this.regexComWarning = '';
                } 
            },
            /**
             * Check to see if the country, disclaimer and comments fields are empty.
             * If so, they are auto filled with NA due to the need for none null values.
             */
            valueCheck(){
                if(!this.customer.country){this.customer.country = 'NA';}
                if(!this.customer.disclaimer){this.customer.disclaimer = 'NA';}
                if(!this.customer.comments){this.customer.comments = 'NA';}
            },
            /**
             * Resets customer state, gets the customers list and resets
             * boolean values.
             */
            resetValues(){
                for (var key in this.customer){
                    this.customer[key] = '';
                }
                this.edit = false;
                this.form = false;
                setTimeout(()=>{
                    this.getCustomers();
                    this.table = true;
                }, 500)
            },
            /**
             * 
             */
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