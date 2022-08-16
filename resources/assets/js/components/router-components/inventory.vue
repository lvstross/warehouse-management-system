<template>
    <div>
        <Nav
            :textOne="'Search Inventory'" 
            :textTwo="'View Ship Tickets'" 
            :textThree="'Add Ship Ticket'"
            :toSearch="switchToSearch" 
            :toTable="switchToTable"
            :toForm="switchToForm"
        ></Nav>
        <hr>
        <Loader v-show="loading"></Loader>
        <!-- Error and Success Messages -->
        <ErrorMessage :errorMes="errorMessage"></ErrorMessage>
        <SuccessMessage :successMes="successMessage"></SuccessMessage>
        <!-- End of Error and Success Messages -->
        <transition name="fadetwo">
            <div v-show="search_inventory && !loading">
                <SearchInventory></SearchInventory>
            </div>
        </transition>
        <transition name="fadetwo">
            <div v-show="shipticket_table && !loading">
                <ShipTicketsTable
                    @toForm="switchToFormEdit" 
                    @succMessage="message($event.message, $event.status, $event.time)" 
                    @errMessage="message($event.message, $event.status, $event.time)"
                ></ShipTicketsTable>
            </div>
        </transition>
        <transition name="fadetwo">
            <div v-show="shipticket_form && !loading">
                <ShipTicketsForm 
                    @toTable="switchToTable"
                    @succMessage="message($event.message, $event.status, $event.time)" 
                    @errMessage="message($event.message, $event.status, $event.time)"
                ></ShipTicketsForm>
            </div>
        </transition>
        <Instructions></Instructions>
    </div>
</template>
<script>
import Nav from './partials/shipticket-nav.vue';
import SearchInventory from './partials/inventory-search.vue';
import ShipTicketsTable from './partials/shipticket-table.vue';
import ShipTicketsForm from './partials/shipticket-form.vue';
import Loader from '../partials/loader.vue';
import Utils from '../../modules/utils.js';
import ErrorMessage from '../partials/error-message.vue';
import SuccessMessage from '../partials/success-message.vue';
import Instructions from '../info-components/inventory-inst.vue';
export default {
    data(){
        return {
            successMessage: '',
            errorMessage: '',
            search_inventory: true,
            shipticket_table: false,
            shipticket_form: false,
            loading: true
        }
    },
    computed: {
        edit() { return this.$store.getters.getEdit; },
        read() { return this.$store.getters.getRead; }
    },
    components: {
        Nav,
        SearchInventory,
        ShipTicketsTable,
        ShipTicketsForm,
        Loader,
        ErrorMessage,
        SuccessMessage,
        Instructions
    },
    mounted(){
        this.loadIn();
    },
    methods: {
        loadIn(){
            setTimeout(()=>{
                this.loading = false;
            }, 1000);
            this.setApplication();
        },
        setApplication(){
            let param = Utils.getUriParam();
            if(param == 'searchInventory'){
                this.switchToSearch();
            }else if(param == 'viewInventory'){
                this.switchToTable();
            }else if(param == 'addInventory'){
                this.switchToForm();
            }
        },
        resetRouters(){
            this.$store.commit('updateEdit', false);
            this.$store.commit('updateRead', false);
            this.$emit('resetApps');
            this.$store.commit('updatePnSet', false);
        },
        switchToSearch(){
            this.resetRouters();
            this.shipticket_table = false;
            this.shipticket_form = false;
            this.$store.dispatch('commitAllRouters');
            setTimeout(()=>{
                this.search_inventory = true;
            }, 500);
        },
        switchToTable(){
            this.resetRouters();
            this.search_inventory = false;
            this.shipticket_form = false;
            this.$store.dispatch('commitAllRouters');
            setTimeout(()=>{
                this.shipticket_table = true;
            }, 500);
        },
        switchToForm(){
            this.resetRouters();
            this.shipticket_table = false;
            this.search_inventory = false;
            this.$store.dispatch('commitAllRouters');
            setTimeout(()=>{
                this.shipticket_form = true;
            }, 500);
        },
        switchToFormEdit(){
            this.shipticket_table = false;
            this.search_inventory = false;
            this.$store.commit('updateEdit', true);
            this.$store.commit('updateRead', false);
            setTimeout(()=>{
                this.shipticket_form = true;
            }, 500);
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
