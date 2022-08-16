<template>
    <div>
        <!-- Cert From -->
        <Loader v-show="loading"></Loader>
        <transition name="fade">
            <div v-show="!loading">
                <h2 class="text-center">Create A Certification of Confirmation</h2>
                <form action="/pdf/certification" method="POST">
                    <div class="form-group">
                        <label for="pl_num">Packing List #:</label>
                        <select v-model="inv_id" @blur="setPackingListInfo(inv_id)" name="pl_num" class="form-control" required>
                            <option>Choose An Item</option>
                            <option v-for="invoice in invoices">{{ invoice.id }}-{{ invoice.inv_num }}</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 col-md-4">
                            <div class="form-group">
                                <label for="date">Date: </label>
                                <input v-model="cert.date" type="date" name="date" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <div class="form-group">
                                <label for="qty">Total Qty: </label>
                                <input v-model="cert.qty" @keyup="checkQty()" type="text" name="qty" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <div class="form-group">
                                <label for="product">Product: </label>
                                <select v-model="prod_id" name="product" class="form-control" required>
                                    <option>Choose An Item</option>
                                    <option v-for="product in products">{{ product.id }}-{{ product.name }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="seller_certifies">Seller Certifies That:</label>
                                <textarea v-model="cert.seller_cert" class="form-control" row="3" name="seller_certifies" placeholder="Note: Do not add numbered points as they will be added onto the print out. Press enter for every new point item."></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="centered">
                        <button type="button" class="btn btn-primary btn-xs" v-if="cert.invoice.inv_num != 0" @click="!read ? read=true : read=false">View Packing List Data</button>
                    </div>
                    <div v-if="read" class="well">
                        <h3 class="text-center">Packing List Data</h3>
                        <strong class="mid-font">Packing List #: </strong><span>{{ cert.invoice.inv_num }}</span><br>
                        <strong class="mid-font">Purchase Order #: </strong><span>{{ cert.invoice.po_num }}</span><br>
                        <strong class="mid-font">Date: </strong><span>{{ cert.invoice.date }}</span><br>
                        <strong class="mid-font">Customer: </strong><span>{{ cert.invoice.customer.name }}</span><br>
                        <strong class="mid-font">Bill To Address: </strong><span>{{ cert.invoice.customer.billto }}</span><br>
                        <strong class="mid-font">Ship To Address: </strong><span>{{ cert.invoice.customer.shipto }}</span><br>
                    </div>

                    <div class="form-group">
                        <p class="alert alert-danger text-center" v-if="qtyErrorMessage != ''">{{ qtyErrorMessage }}</p>
                        <p class="alert alert-success text-center" v-if="qtySuccessMessage != ''">{{ qtySuccessMessage }}</p>
                        <div class="row">
                            <div class="col-xs-12 col-md-6">
                                <button type="button" @click="addLotItem" class="btn btn-success full-width margin">Add Lot Item</button>
                            </div>
                            <div class="col-xs-12 col-md-6">
                                <button type="button" @click="removeLotItem" class="btn btn-danger full-width margin">Remove Lot Item</button>
                            </div>
                        </div>
                        <hr class="dashed">
                        <h2 class="text-center">Lot Items: {{ cert.lot_items.length }}</h2>
                        <p class="alert alert-info text-center" v-show="cert.lot_items.length == 20">Sorry! You have reached the max number of lot items!</p>
                        <div class="items_cont">
                            <div v-for="(item, i) in cert.lot_items" class="row space border">
                                <p class="text-center"><span class="badge">{{i+1}}</span></p>
                                <div class="col-xs-12 col-sm-4">
                                    <label :for="'lot_num_' + i">Lot Number:</label>
                                    <input v-model="cert.lot_items[i].lot_num" type="text" :name="'lot_num_' + i" class="form-control" required>
                                </div>
                                <div class="col-xs-12 col-sm-4">
                                    <label :for="'cure_qty_' + i">Cure Date:</label>
                                    <input v-model="cert.lot_items[i].cure_qtr" type="text" :name="'cure_qtr_' + i" class="form-control" required>
                                </div>
                                <div class="col-xs-12 col-sm-4">
                                    <label :for="'qty_' + i">Qty:</label>
                                    <input v-model="cert.lot_items[i].qty" @keyup="checkQty()" type="number" :name="'qty_' + i" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="dashed">
                    <button v-if="cert.qty != lot_qty" disabled class="btn btn-primary full-width">Print Cert</button>
                    <button v-else type="submit" class="btn btn-primary full-width">Print Cert</button>
                </form>
                <br />
                <Instruction></Instruction>
            </div>
        </transition>
    </div>
</template>

<script>
    // Imports
    import TextForm from '../components/partials/form-text.vue';
    import NumberForm from '../components/partials/form-number.vue';
    import ErrorMessage from '../components/partials/error-message.vue';
    import SuccessMessage from '../components/partials/success-message.vue';
    import Loader from '../components/partials/loader.vue';
    import Instruction from '../components/info-components/cert-inst.vue';
    import Utils from '../modules/utils.js';
    // Export
    export default {
        data() {
            return {
                errorMessage: '',
                successMessage: '',
                qtyErrorMessage: '',
                qtySuccessMessage: '',
                read: false,
                loading: true,
                inv_id: '',
                prod_id: '',
                lot_qty: 0,
                cert: {
                    invoice: {
                        inv_num: 0,
                        date: '',
                        customer: {
                            id: '',
                            name: '',
                            shipto: '',
                            billto: '',
                            buyer: '',
                            email: '',
                            phone: '',
                            country: '',
                            disclaimer: '',
                            comments: '',
                        },
                        po_num: '',
                        line_items: [
                            { item: '', product: '', qty: 0, unit: 0, extended: 0 },
                            { item: '', product: '', qty: 0, unit: 0, extended: 0 },
                            { item: '', product: '', qty: 0, unit: 0, extended: 0 },
                            { item: '', product: '', qty: 0, unit: 0, extended: 0 },
                            { item: '', product: '', qty: 0, unit: 0, extended: 0 },
                            { item: '', product: '', qty: 0, unit: 0, extended: 0 },
                            { item: '', product: '', qty: 0, unit: 0, extended: 0 }
                        ],
                        misc_char: 0,
                        ship_fee: 0,
                        total: 0,
                        complete: '',
                        carrier: '',
                        memo: ''
                    },
                    date: '',
                    qty: '',
                    product: '',
                    seller_cert: '',
                    lot_items: [{ lot_num: '', cure_qtr: '', qty: '' }]
                }
            }
        },
        mounted() {
            /*
            * When Vue instance is mounted:
            * 1.) Get all customers to have for customer dropdown
            * 2.) Get all products to have for product dropdowns
            */
            this.getProducts();
            this.getInvoices();
            this.loadIn();
        },
        components: {
            TextForm,
            NumberForm,
            ErrorMessage,
            SuccessMessage,
            Loader,
            Instruction
        },
        computed: {
            // GETTERS
            products() { return this.$store.getters.getProducts; },
            invoices() { return this.$store.getters.getInvoices; },
        },
        methods: {
            loadIn(){
                setTimeout(()=>{
                    this.loading = false
                }, 500);
            },
            // ACTIONS
            getProducts(){ this.$store.dispatch('commitProducts'); },
            getInvoices(){ this.$store.dispatch('commitInvoices'); },
            setPackingListInfo(id){
              let newId = id.split('-');
                axios({
                    method: 'get',
                    url: 'api/invoices/' + parseInt(newId[0]),
                    validateStatus(status) {
                        if(status >= 200 && status < 300){
                            return status;
                        }else if(status == 401){
                            alert("Your session has timed out. You will now be redirected.");
                            window.location = window.location.origin + '/login';
                        }
                    }
                }).then((response) => {
                    let data = Utils.invoiceLoop(response.data);
                    this.cert.invoice = data;
                }).catch((error) => {
                    throw new Error('setPackingListInfo method failed on cert object.' + error);
                });
            },
            checkQty(){
                let qty = 0;
                this.cert.lot_items.forEach((el,i,a) => {
                    qty += Number(a[i].qty);
                });
                this.lot_qty = qty;
                if(Number(this.cert.qty) != qty){
                    this.qtySuccessMessage = '';
                    this.qtyErrorMessage = "Lot item quanties do not add up to total quantity!";
                }else{
                    this.qtyErrorMessage = '';
                    this.qtySuccessMessage = "Lot item quantities adds up to total quantity!";
                }
            },
            addLotItem(){
                if(this.cert.lot_items.length < 20){
                    let item = { lot_num: '', cure_qtr: '', qty: '' };
                    this.cert.lot_items.push(item);
                }
                this.checkQty();
            },
            removeLotItem(){
                if(this.cert.lot_items.length > 1){
                    this.cert.lot_items.pop();
                }
                this.checkQty();
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
<style scoped>
    .centered {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 15px 0;
    }
    .items_cont {
        max-height: 350px;
        overflow-y: scroll;
    }
    .border {
        border: 1px solid #aaa;
        border-radius: 10px;
    }
    .space {
        padding: 15px 0;
        margin: 5px 0;
    }
    .cust_top_margin {
        margin-top: 32px;
    }
    @media(max-width:992px){
        .margin {
            margin: 2px 0;
        }
    }
</style>
