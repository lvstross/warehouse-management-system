 <template>
    <div>
        <InstructionOne></InstructionOne>
        <h2 class="text-center">Company Information</h2>
        <div class="well">
            <!-- Add your company info box with add button -->
            <div v-if="!companyAdded()">
                <p class="alert alert-success text-center">Please add your company info. <button class="btn btn-success btn-sm" @click="form = true">Add Company Info</button></p>
            </div>
            <!-- if company is added: Company Name with edit button -->
            <div v-if="companyAdded()">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-4">
                        <h2 class="text-center">{{ companyName }}</h2>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <button class="btn btn-default btn-sm full-width margin-top-20" v-show="!read" @click="toShow()"><i class="fa fa-eye" aria-hidden="true"></i> View</button>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <button class="btn btn-warning btn-sm full-width margin-top-20" v-show="!edit" @click="toEdit()"><i class="fa fa-pencil" aria-hidden="true"></i> Update</button>
                    </div>
                </div>
            </div>
            <!-- Messages -->
            <ErrorMessage :errorMes="errorMessage"></ErrorMessage>
            <SuccessMessage :successMes="successMessage"></SuccessMessage>
            <!-- End of Messages -->
            <!-- Add Company Form -->
            <div v-if="form">
                <h2 class="text-center">Add Company</h2>
                <form action="#" @submit.prevent="edit ? updateCompany(company.id) : createCompany()">
                <p class="alert alert-danger" v-if="regWarning">{{ regWarning }}</p>
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input v-model="company.name" type="text" name="name" class="form-control" required maxlength="50">
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="inv_prefix">Invoice Prefix Number</label>
                                <input v-model="company.inv_prefix" class="form-control" type="text" name="inv_prefix" required maxlength="15">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea v-model="company.address" name="address" class="form-control" maxlength="255"></textarea>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="desc">Description</label>
                                <textarea v-model="company.desc" name="desc" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input v-model="company.phone" type="text" name="phone" class="form-control" maxlength="25">
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input v-model="company.email" type="email" name="email" class="form-control" maxlength="50">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="invoice_con">Invoice Document Control Number</label>
                                <input v-model="company.invoice_con" type="text" name="invoice_con" class="form-control" maxlength="50">
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="shipper_con">Shipper Document Control Number</label>
                                <input v-model="company.shipper_con" type="text" name="shipper_con" class="form-control" maxlength="50">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="router_con">Router Document Control Number</label>
                                <input v-model="company.router_con" type="text" name="router_con" class="form-control" maxlength="50">
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="po_con">Purchase Order Document Control Number</label>
                                <input v-model="company.po_con" type="text" name="po_con" class="form-control" maxlength="50">
                            </div>
                        </div>
                    </div>
                    <SubmitBtns :editMode="edit" :name="name='Company'"></SubmitBtns>
                </form>
            </div>
            <!-- End of add product form -->
        </div>
        <transition name="fade">
            <div v-show="read" class="well">
                <h2 class="lg-font">{{ company.name }}</h2>
                <strong class="mid-font">Address: </strong><span>{{ company.address }}</span><br>
                <strong class="mid-font">Phone: </strong><span>{{ company.phone }}</span><br>
                <strong class="mid-font">Email: </strong><span>{{ company.email }}</span><br>
                <strong class="mid-font">Description: </strong><span>{{ company.desc }}</span><br>
                <strong class="mid-font">Invoice Prefix Number: </strong><span>{{ company.inv_prefix }}</span><br>
                <strong class="mid-font">Invoice Document Controle Number: </strong><span>{{ company.invoice_con }}</span><br>
                <strong class="mid-font">Shipper Document Controle Number: </strong><span>{{ company.shipper_con }}</span><br>
                <strong class="mid-font">Router Document Controle Number: </strong><span>{{ company.router_con }}</span><br>
                <strong class="mid-font">Purchase Order Document Controle Number: </strong><span>{{ company.po_con }}</span><br>
                <button class="btn btn-danger full-width" @click="closeView()">Close Viewing</button>
            </div>
        </transition>
        <hr>
        <!-- Instruction Area -->
        <InstructionTwo></InstructionTwo>
    </div>
</template>
<script>
    import InstructionOne from '../components/info-components/settings1-inst.vue';
    import InstructionTwo from '../components/info-components/settings2-inst.vue';
    import SubmitBtns from '../components/partials/submit-btn.vue';
    import ErrorMessage from '../components/partials/error-message.vue';
    import SuccessMessage from '../components/partials/success-message.vue';
    export default {
        data() {
            return {
                edit: false,
                form: false,
                read: false,
                instruction_1: false,
                instruction_2: false,
                security: '',
                companyName: '',
                companyId: 0,
                company: {
                    id: '',
                    name: '',
                    address: '',
                    phone: '',
                    email: '',
                    desc: '',
                    invoice_con: '',
                    shipper_con: '',
                    router_con: '',
                    po_con: '',
                    inv_prefix: ''
                },
                regWarning: '',
                errorMessage: '',
                successMessage: '',
                user: ''
            }
        },
        mounted() {
            this.getUser();
            this.getCompany();
            this.companyAdded();
        },
        components: {
            SubmitBtns,
            ErrorMessage,
            SuccessMessage,
            InstructionOne,
            InstructionTwo
        },
        methods: {
            getUser(){
                axios({
                    method: 'get',
                    url: 'api/user',
                    validateStatus(status) {
                        if(status >= 200 && status < 300){
                            return status;
                        }else if(status == 401){
                            alert("Your session has timed out. You will now be redirected.");
                            window.location = window.location.origin + '/login';
                        }
                    }
                }).then((response) => {
                    this.user = response.data.permission;
                }).catch((error) => {
                    throw new Error("Was not able to find user.");
                });
            },
            toEdit(){
                this.form = true;
                this.edit = true;
                this.showCompany(this.companyId);
            },
            toShow(){
                this.read = true;
                this.showCompany(this.companyId);
            },
            closeView(){
                this.read = false;
                this.resetValues();
            },
            companyAdded(){
                if(this.companyName !== ''){
                    return true;
                }else {
                    return false;
                }
            },
            getCompany(){
                axios({
                    method: 'get',
                    url: 'api/company',
                    validateStatus(status) {
                        if(status >= 200 && status < 300){
                            return status;
                        }else if(status == 401){
                            alert("Your session has timed out. You will now be redirected.");
                            window.location = window.location.origin + '/login';
                        }
                    }
                }).then((response) => {
                    this.companyName = response.data[0].name;
                    this.companyId = response.data[0].id;
                }).catch((error) => {
                    throw new Error("getCompany method failed!", error.message);
                    this.message("Sorry! Something went wrong when receiving your company info!", 'error', 10000);
                });
            },
            createCompany(){
                this.regexCheck();
                let params = Object.assign({}, this.company);
                axios({
                    method: 'post',
                    url: 'api/company/store',
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
                    this.message('Company Info Successfully Added!', 'success', 5000);
                }).catch((error) => {
                    throw new Error("createCompany method failed!", error.message);
                    this.message('Sorry! Something went wrong when adding your company info!', 'error' , 10000);
                });
            },
            updateCompany(id){
                this.regexCheck();
                let params = Object.assign({}, this.company);
                axios({
                    method: 'patch',
                    url: 'api/company/' + id,
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
                    this.message('Company Info Successfully Updated!', 'success', 5000);
                }).catch((error) => {
                    throw new Error("updateCompany method failed!", error.message);
                    this.message("Sorry! Something went wrong when updating your company info!", 'error', 10000);
                });
            },
            showCompany(id){
                axios({
                    method: 'get',
                    url: 'api/company/' + id,
                    validateStatus(status) {
                        if(status >= 200 && status < 300){
                            return status;
                        }else if(status == 401){
                            alert("Your session has timed out. You will now be redirected.");
                            window.location = window.location.origin + '/login';
                        }
                    }
                }).then((response) => {
                    for(var key in this.company){
                        for(var k in response.data){
                            if(key === k){
                                this.company[key] = response.data[k];
                            }
                        }
                    }
                }).catch((error) => {
                    throw new Error("showCompany method faile!", error.message);
                    this.message("Sorry! Something went wrong in receiving your company info!", 'error', 10000);
                });
                this.edit = true;
            },
            resetValues(){
                for(var key in this.company){
                    this.company[key] = '';
                }
                this.regWarning = '';
                this.form = false;
                this.edit = false;
                this.getCompany();
                this.companyAdded();
            },
            regexCheck(){
                var arr = [this.company.invoice_con, this.company.shipper_con, this.company.router_con, this.company.po_con],
                    pattern = /^(?!-)(?!.*--)[A-Za-z0-9\,\&\-\(\)\/\"\.\*\#\s]+$/i;
                var newArr = arr.filter(function(val){
                    return pattern.test(val) === false;
                });
                if(newArr.length > 0){
                    this.regWarning = "Unapproved characters detected with the control numbers! List of approved characters: a-z, A-Z, 0-9, '()-/&*#\"' commas and periods. However, '--' is not allowed. Current values rejected: "; 
                    for(var i = 0; i < newArr.length; i++){
                        this.regWarning += "'"+newArr[i]+"'    ";
                    }
                    throw new Error("Unapproved characters rejected by the client.");
                } else {
                    this.regWarning = '';
                }
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