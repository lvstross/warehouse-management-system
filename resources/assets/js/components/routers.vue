<template>
    <div class="space-top">
        <RouterNavBtns
            :toRouters="switchToRouters"
            :toPartFlow="switchToPartFlow"
            :toInventory="switchToInventory"
        ></RouterNavBtns>
        <hr>
        <transition name="fadetwo">
            <div v-show="table_routers">
                <Routers></Routers>
            </div><!-- End of table_routers = true container -->
        </transition>
        <transition name="fadetwo">
            <div v-show="table_departments">
                <Departments></Departments>
            </div><!-- End of table_departments = true contrainer -->
        </transition>
        <transition name="fadetwo">
            <div v-show="table_part_flow">
                <PartFlow @toDept="switchToDepartments()"></PartFlow>
            </div><!-- End of table_part_flow = true container -->
        </transition>
        <transition name="fadetwo">
            <div v-show="table_inventory">
                <Inventory @resetApps="resetRouters"></Inventory>
            </div><!-- End of table_inventory = true container -->
        </transition>
        <transition name="fadetwo">
            <div v-show="submit_router">
                <SubmitRouter></SubmitRouter>
            </div><!-- Employee submit router -->
        </transition>
    </div>
</template>
<script>
    // imports
    import RouterNavBtns from '../components/router-components/router-nav-btns.vue';
    import Departments from '../components/router-components/departments.vue';
    import PartFlow from '../components/router-components/partflow.vue';
    import Inventory from '../components/router-components/inventory.vue';
    import Routers from '../components/router-components/routers.vue';
    import SubmitRouter from '../components/router-components/submit-router.vue';
    import Utils from '../modules/utils.js';
    export default {
        data(){
            return {
                table_routers: false,
                table_departments: false,
                table_part_flow: false,
                table_inventory: false,
                submit_router: false
            }
        },
        components: {
            RouterNavBtns,
            Departments,
            PartFlow,
            Inventory,
            Routers,
            SubmitRouter
        },
        mounted(){
            this.setApplication();
            this.getUser();
            this.getProducts();
            this.getDepartments();
            this.getShipTickets();
        },
        methods: {
            resetRouters(){
                this.getRouters();
                this.$store.commit('resetState');
                this.$store.commit('updateSearchRouter', '');
                this.$store.commit('updateSearchPnNum', '');
                this.$store.commit('updateShipTicPartNum', '');
                this.$store.commit('updateEdit', false);
                this.$store.commit('updateRead', false);
                this.$store.commit('updatePnSet', false);
            },
            setApplication() {
                if(window.location.pathname == '/submitrouter'){
                    this.switchToSubmitRouter();
                }else if(window.location.pathname == '/production'){
                    let param = Utils.getUriParam();
                    if(param == 'viewRouters' || param == 'addRouter'){
                        this.switchToRouters();
                    }else if(param == 'departments'){
                        this.switchToDepartments();
                    }else if(param == 'partflow'){
                        this.switchToPartFlow();
                    }else if(param == 'searchInventory' || param == 'viewInventory' || param == 'addInventory'){
                        this.switchToInventory();
                    }
                }
            },
            getRouters() { 
                this.$store.dispatch('commitRouters')
                .then(()=>{
                    this.$store.commit('setLoad', false);
                })
                .catch((error)=>{
                   alert('An Error occured, please refresh the page');
                   this.$store.commit('setLoad', false);
                   throw new Error('getRouters failed!!! ' + error);
                });
            },
            getUser() { this.$store.dispatch('commitPermission'); },
            getDepartments(){ this.$store.dispatch('commitDepartments'); },
            getProducts(){ this.$store.dispatch('commitProducts'); },
            getShipTickets(){ this.$store.dispatch('commitShipTickets'); },
            switchToRouters(){
                this.$store.commit('setLoad', true);
                this.closeDepartments();
                this.closePartFlow();
                this.closeInventory();
                this.$store.commit('updateRouterPnSearchTotal', 0);
                this.resetRouters();
                this.table_departments = false;
                this.table_part_flow = false;
                this.table_inventory = false;
                setTimeout(()=>{
                    Utils.closeContainer();
                    this.table_routers = true;
                }, 300);
            },
            switchToDepartments(){
                this.closeRouters();
                this.closePartFlow();
                this.closeInventory();
                this.$store.commit('updateRouterPnSearchTotal', 0);
                this.table_routers = false;
                this.table_part_flow = false;
                this.table_inventory = false;
                setTimeout(()=>{
                    Utils.closeContainer();
                    this.table_departments = true;
                }, 300);
            },
            switchToPartFlow(){
                this.$store.commit('setLoad', true);
                this.closeRouters();
                this.closeDepartments();
                this.closeInventory();
                this.$store.commit('updateRouterPnSearchTotal', 0);
                this.resetRouters();
                this.table_departments = false;
                this.table_routers = false;
                this.table_inventory = false;
                setTimeout(()=>{
                    Utils.openContainer();
                    this.table_part_flow = true;
                }, 300);
            },
            switchToInventory(){
                this.$store.commit('setLoad', true);
                this.closeRouters();
                this.closeDepartments();
                this.closePartFlow();
                this.$store.commit('updateRouterPnSearchTotal', 0);
                this.resetRouters();
                this.table_departments = false;
                this.table_routers = false;
                this.table_part_flow = false;
                this.$store.dispatch('commitAllRouters');
                setTimeout(()=>{
                    Utils.closeContainer();
                    this.table_inventory = true;
                }, 300);
            },
            switchToSubmitRouter(){
                this.getRouters();
                this.submit_router = true;
            },
            closeRouters(){
                this.$children[1]._data.read = false;
                this.$children[1]._data.edit = false;
                this.$children[1]._data.form = false;
                this.$children[1]._data.search = false;
                this.$children[1]._data.search_pn = false;
                this.$children[1]._data.search_status = "By Status";
                this.$children[1]._data.search_status_move = false;
            },
            closeDepartments(){
                this.$children[2]._data.edit = false;
                this.$children[2]._data.read = false;
            },
            closePartFlow(){
                this.$children[3]._data.edit = false;
                this.$children[3]._data.search_pn = false;
                this.$children[3]._data.search_router = false;
            },
            closeInventory(){
                this.$children[4].$children[4]._data.read = false;
                this.$children[4].$children[4]._data.search = false;
                this.$children[4].$children[4]._data.search_pn = false;
            }
        }
    }
</script>
<style scoped>
.space-top {
    margin-top: 50px;
}
@media(max-width: 767px){
    .space-top {
        margin-top: 120px;
    }    
}
</style>
