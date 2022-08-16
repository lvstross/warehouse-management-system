<template>
    <div>
        <Loader v-show="loading"></Loader>
        <!-- Products Table -->
        <transition name="fade">
            <div v-show="!loading">
                <div id="product-table" v-if="list.length > 0" class="table-responsive overflow-scroll-table">
                    <table class="table table-condensed">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Material</th>
                                <th>Revision</th>
                                <th>Rev Date</th>
                                <th>View</th>
                                <th v-if="user.permission == 1 || user.permission == 2 || user.permission == 3">Edit</th>
                                <th v-if="user.permission == 1">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="product in list">
                                <td>{{ product.name }}</td>
                                <td>{{ product.description }}</td>
                                <td v-if="product.material.length > 10" v-bind:title="product.material">{{ product.material.substring(0, 10) + '...' }}</td>
                                <td v-else>{{ product.material }}</td>
                                <td>{{ product.rev }}</td>
                                <td>{{ newDate(product.rev_date) }}</td>
                                <td><button @click="viewProduct(product.id)" class="btn btn-default btn-xs"><i class="fa fa-eye" aria-hidden="true"></i> View</button></td>
                                <td v-if="user.permission == 1 || user.permission == 2 || user.permission == 3"><button @click="showProduct(product.id)" class="btn btn-warning btn-xs"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</button></td>
                                <td v-if="user.permission == 1"><button @click="deleteProduct(product.id)" class="btn btn-danger btn-xs"><i class="fa fa-trash" aria-hidden="true"></i> Delete</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-else> 
                    <p class="alert alert-info text-center">You currently have no products to show.</p>
                </div>
                <!-- end of products table -->
                <hr>
                <div v-show="read" class="well">
                    <div class="pull-right">
                        <button class="btn btn-default full-width" @click="closeView()">X</button>
                    </div>
                    <h2 class="lg-font">{{ product.name }}</h2>
                    <strong class="mid-font">Description: </strong><span>{{ product.description }}</span><br>
                    <strong class="mid-font">Material: </strong><span>{{ product.material }}</span><br>
                    <strong class="mid-font">Revision: </strong><span>{{ product.rev }}</span><br>
                    <strong class="mid-font">Revision Date: </strong><span>{{ newDate(product.rev_date) }}</span><br>
                </div>
                <!-- Error and Success Messages -->
                <ErrorMessage :errorMes="errorMessage"></ErrorMessage>
                <SuccessMessage :successMes="successMessage"></SuccessMessage>
                <div v-show="edit" class="pull-right">
                    <button type="button" @click="cancelEdit" class="btn btn-default">X</button>
                </div>
                <!-- Add product Form -->
                <div v-show="!read">
                    <h2 class="text-center">Product Details</h2>
                    <form action="#" @submit.prevent="edit ? updateProduct(product.id) : createProduct()">
                    <p class="alert alert-danger" v-if="regWarning">{{ regWarning }}</p>
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input v-model="product.name" @keyup="checkName()" type="text" name="name" class="form-control" required maxlength="25">
                                    <p class="alert alert-warning" v-if="product.name.length == 25">25 character limit reached!</p>
                                    <p class="alert alert-warning" v-if="nameAlert">{{ nameAlert }}</p>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <input v-model="product.description" type="text" name="description" class="form-control" maxlength="255">
                                    <p class="alert alert-warning" v-if="product.description.length == 255">255 character limit reached!</p>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="material">Material</label>
                            <input v-model="product.material" type="text" name="material" class="form-control" maxlength="255">
                            <p class="alert alert-warning" v-if="product.material.length == 255">255 character limit reached!</p>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label for="rev">Revision</label>
                                    <input v-model="product.rev" type="text" name="rev" class="form-control" maxlength="20">
                                    <p class="alert alert-warning" v-if="product.rev.length == 20">20 character limit reached!</p>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label for="rev_date">Revision Date</label>
                                    <input v-model="product.rev_date" type="date" name="rev_date" class="form-control">
                                </div>
                            </div>
                        </div>
                        <SubmitBtns :editMode="edit" :name="name='Product'"></SubmitBtns>
                    </form>
                </div>
                <!-- End of add product form -->
                <Instructions></Instructions>
            </div>
        </transition>
    </div>
</template>
<script>
    // Imports
    import SubmitBtns from '../components/partials/submit-btn.vue';
    import ErrorMessage from '../components/partials/error-message.vue';
    import SuccessMessage from '../components/partials/success-message.vue';
    import Loader from '../components/partials/loader.vue';
    import Instructions from '../components/info-components/product-inst.vue';
    import Utils from '../modules/utils.js';
    // Export
    export default {
        data() {
            return {
                edit: false,
                read: false,
                loading: true,
                product: {
                    id: '',
                    name: '',
                    description: '',
                    material: '',
                    rev: '',
                    rev_date: ''
                },
                regWarning: '',
                nameAlert: '',
                errorMessage: '',
                successMessage: ''
            }
        },
        mounted() {
            this.getProducts();
            this.getUser();
            this.loadIn();
        },
        components: {
            SubmitBtns,
            ErrorMessage,
            SuccessMessage,
            Loader,
            Instructions
        },
        computed: {
            user() { return this.$store.getters.getUser; },
            list() { return this.$store.getters.getProducts; }
        },
        methods: {
            loadIn(){
                setTimeout(()=>{
                    this.loading = false;
                }, 1000)
            },
            newDate(date) {
                return Utils.invertDate(date);
            },
            getUser(){ this.$store.dispatch('commitPermission'); },
            getProducts(){ this.$store.dispatch('commitProducts'); },
            createProduct(){
                this.noDuplicateNames();
                this.regexCheck();
                if(!this.product.description){this.product.description = 'NA';}
                if(!this.product.material){this.product.material = 'NA';}
                if(!this.product.rev){this.product.rev = 'NA';}
                let params = Object.assign({}, this.product);
                axios({
                    method: 'post',
                    url: 'api/products/store',
                    data: params,
                    validateStatus(status) {
                        if(status >= 200 && status < 300){
                            return status;
                        }else if(status == 401){
                            alert("Your session has timed out. You will now be redirected.");
                            window.location = window.location.origin + '/login';
                        }
                    }
                }).then(response => {
                    this.resetValues();
                    this.message("Product has successfully been created!", 'success', 5000);
                }).catch(error => {
                    this.message("Sorry! Something went wrong when creating your product.", 'error', 10000);
                    throw new Error("Create Product Failed! " + error.message);
                });
            },
            updateProduct(id){
                this.regexCheck();
                if(!this.product.description){this.product.description = 'NA';}
                if(!this.product.material){this.product.material = 'NA';}
                if(!this.product.rev){this.product.rev = 'NA';}
                let params = Object.assign({}, this.product);
                axios({
                    method: 'patch',
                    url: 'api/products/' + id,
                    data: params,
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
                    this.message("Product has successfully been updated!", 'success', 5000);
                }).catch((error) => {
                    this.message("Sorry! Something went wrong when updating your product.", 'error', 10000);
                    throw new Error("Update Product Failed! " + error.message);
                });
            },
            showProduct(id){
                axios({
                    method: 'get',
                    url: 'api/products/' + id,
                    validateStatus(status) {
                        if(status >= 200 && status < 300){
                            return status;
                        }else if(status == 401){
                            alert("Your session has timed out. You will now be redirected.");
                            window.location = window.location.origin + '/login';
                        }
                    }
                }).then((response) => {
                    for(var key in this.product){
                        for(var k in response.data){
                            if(key === k){
                                this.product[key] = response.data[k];
                            }
                        }
                    }
                }).catch((error) => {
                    this.message("Sorry! Something went wrong retrieving your product.", 'error', 10000);
                    throw new Error("Show Product Failed! " + error.message);
                });
                this.edit = true;
            },
            viewProduct(id) {
                axios({
                    method: 'get',
                    url: 'api/products/' + id,
                    validateStatus(status) {
                        if(status >= 200 && status < 300){
                            return status;
                        }else if(status == 401){
                            alert("Your session has timed out. You will now be redirected.");
                            window.location = window.location.origin + '/login';
                        }
                    }
                }).then((response) => {
                    for(var key in this.product){
                        for(var k in response.data){
                            if(key === k){
                                this.product[key] = response.data[k];
                            }
                        }
                    }
                }).catch((error) => {
                    this.message("Sorry! Something went wrong when retrieving your product.", 'error', 10000);
                    throw new Error("View Product Failed! " + error.message);
                });
                this.read = true;
            },
            closeView() {
                this.resetValues();
                this.read = false;
            },
            cancelEdit(){
                this.edit = false;
                for(var key in this.product){
                    this.product[key] = '';
                }
            },
            deleteProduct(id){
                if(confirm('Are you sure you want to delete this product?')){
                    axios({
                        method: 'delete',
                        url: 'api/products/' + id,
                        validateStatus(status) {
                            if(status >= 200 && status < 300){
                                return status;
                            }else if(status == 401){
                                alert("Your session has timed out. You will now be redirected.");
                                window.location = window.location.origin + '/login';
                            }
                        }
                    }).then((response) => {
                        this.getProducts();
                        this.message("Product has successfully been deleted!", 'success', 5000);
                    }).catch((error) => {
                        this.message("Sorry! Something went wrong when deleting your product.", 'error', 10000);
                        throw new Error("Delete Product Failed! " + error.message);
                    });
                }else{
                    return;
                }
            },
            resetValues(){
                for(var key in this.product){
                    this.product[key] = '';
                }
                this.regWarning = '';
                this.nameAlert = '';
                this.edit = false;
                this.getProducts();
            },
            regexCheck(){
                var arr = [this.product.name, this.product.description, this.product.material, this.product.rev],
                    pattern = /^(?!-)(?!.*--)[A-Za-z0-9\-\.\(\)\/\,\s]+$/;
                var newArr = arr.filter(function(val){
                    return pattern.test(val) === false;
                });
                if(newArr.length > 0){
                    this.regWarning = `Unapproved characters detected! 
                    List of approved characters: a-z, A-Z, 0-9, highens, commas and periods. 
                    However, '--' is not allowed. Current values rejected: `; 
                    for(var i = 0; i < newArr.length; i++){
                        this.regWarning += "'"+newArr[i]+"'";
                    }
                    throw new Error("Unapproved characters rejected by the client.");
                } else {
                    this.regWarning = '';
                }
            },
            checkName(){
                var self = this;
                var nameTaken = self.list.some(function(val){
                    return self.product.name === val.name;
                });
                if(nameTaken){
                    self.nameAlert = 'This name has already been taken.';
                }else{
                    self.nameAlert = '';
                }
            },
            noDuplicateNames(){
                var self = this;
                self.list.forEach(function(arrayItem){
                    var x = arrayItem;
                    if(self.product.name == x.name){
                        self.message('This product name has already been taken. Please choose a different one to avoid duplicate information.', 'error', 10000);
                        throw new Error("This product name already exists. Server rejects duplicate values.");
                    }
                });
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