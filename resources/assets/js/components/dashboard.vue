<template>
    <div>
        <Loader v-show="loading"></Loader>
        <transition name="fade">
            <div v-show="!loading" class="row">
                <!-- Purchase Orders Panel -->
                <div v-if="user.permission == 1 || user.permission == 2" class="col-lg-3 col-md-6">
                    <DashboardPanel
                        :panelStyles="['panel-blue']"
                        :panelIconStyles="['fa', 'fa-tasks', 'fa-5x']"
                        :panelHeaderText="'Purchase Orders'"
                        :panelItems="purchaseOrderLinks"
                    ></DashboardPanel>
                </div>
                <!-- End of Purchase Orders Panel -->
                <!-- Routers Panel -->
                <div v-if="user.permission == 1 || user.permission == 2 || user.permission == 3" class="col-lg-3 col-md-6">
                    <DashboardPanel
                        :panelStyles="['panel-red']"
                        :panelIconStyles="['fa', 'fa-arrows', 'fa-5x']"
                        :panelHeaderText="'Routers'"
                        :panelItems="routerLinks"
                    ></DashboardPanel>
                </div>
                <!-- End Routers Panel Panel -->
                <!-- Invoices Panel -->
                <div v-if="user.permission == 1 || user.permission == 2" class="col-lg-3 col-md-6">
                    <DashboardPanel
                        :panelStyles="['panel-green']"
                        :panelIconStyles="['fa', 'fa-money', 'fa-5x']"
                        :panelHeaderText="'Invoices'"
                        :panelItems="invoiceLinks"
                    ></DashboardPanel>
                </div>
                <!-- End Invoices Panel -->
                <!-- Inventory -->
                <div v-if="user.permission == 1 || user.permission == 2 || user.permission == 3" class="col-lg-3 col-md-6">
                    <DashboardPanel
                        :panelStyles="['panel-purple']"
                        :panelIconStyles="['fa', 'fa-ticket', 'fa-5x']"
                        :panelHeaderText="'Inventory &amp; Ship Tickets'"
                        :panelItems="inventoryLinks"
                    ></DashboardPanel>
                </div>
                <!-- End of Inventory -->
                <!-- Certifications -->
                <div v-if="user.permission == 1 || user.permission == 2 || user.permission == 3" class="col-lg-3 col-md-6">
                    <DashboardPanel
                        :panelStyles="['panel-yellow']"
                        :panelIconStyles="['fa', 'fa-certificate', 'fa-5x']"
                        :panelHeaderText="'Certifications'"
                        :panelItems="certLinks"
                    ></DashboardPanel>
                </div>
                <!-- End of Certification -->
                <!-- Vendor Purchases -->
                <div v-if="user.permission == 1 || user.permission == 2" class="col-lg-3 col-md-6">
                    <DashboardPanel
                        :panelStyles="['panel-robin']"
                        :panelIconStyles="['fa', 'fa-credit-card', 'fa-5x']"
                        :panelHeaderText="'Purchases &amp; Receivals'"
                        :panelItems="purchaseLinks"
                    ></DashboardPanel>
                </div>
                <!-- End of Vendor Purchases -->
                <!-- System Data Panel -->
                <div v-if="user.permission == 1 || user.permission == 2 || user.permission == 3" class="col-lg-3 col-md-6">
                    <div class="panel panel-black">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-database fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="head-text">System Data</div>
                                </div>
                            </div>
                        </div>
                        <a v-if="user.permission == 1 || user.permission == 2" href="/customers">
                            <div class="panel-footer">
                                <span class="pull-left"><i class="fa fa-fw fa-users" aria-hidden="true"></i> Customers Data</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                        <a v-if="user.permission == 1 || user.permission == 2" href="/production?app=departments">
                            <div class="panel-footer">
                                <span class="pull-left"><i class="fa fa-fw fa-wrench" aria-hidden="true"></i> Departments Data</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                        <a v-if="user.permission == 1 || user.permission == 2 || user.permission == 3" href="/products">
                            <div class="panel-footer">
                                <span class="pull-left"><i class="fa fa-fw fa-plane" aria-hidden="true"></i> Products Data</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                        <a v-if="user.permission == 1 || user.permission == 2 || user.permission == 3" href="/vendors">
                            <div class="panel-footer">
                                <span class="pull-left"><i class="fa fa-fw fa-truck" aria-hidden="true"></i> Vendors Data</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <!-- End of System Data Panel -->
            </div>
            <!-- /.row -->
        </transition>
    </div>
</template>
<script>
    import Loader from '../components/partials/loader.vue';
    import DashboardPanel from '../components/partials/DashboardPanel.vue';
    import Utils from '../modules/utils.js';
    export default {
        data(){
            return {
                loading: true,
                purchaseOrderLinks: [
                    {
                        link: '/purchaseorders?app=create',
                        iconStyles: ['fa', 'fa-fw', 'fa-plus'],
                        linkText: 'Create Purchase Order',
                    },
                    {
                        link: '/purchaseorders?app=open',
                        iconStyles: ['fa', 'fa-fw', 'fa-folder-open'],
                        linkText: 'Current Open Order Report',
                    },
                    {
                        link: '/purchaseorders?app=closed',
                        iconStyles: ['fa', 'fa-fw', 'fa-folder'],
                        linkText: 'Closed Orders',
                    },
                ],
                routerLinks: [
                    {
                        link: '/production?app=addRouter',
                        iconStyles: ['fa', 'fa-fw', 'fa-plus'],
                        linkText: 'Create Router',
                    },
                    {
                        link: '/production?app=viewRouters',
                        iconStyles: ['fa', 'fa-fw', 'fa-arrows'],
                        linkText: 'View Routers',
                    },
                    {
                        link: '/production?app=partflow',
                        iconStyles: ['fa', 'fa-fw', 'fa-columns'],
                        linkText: 'Partflow',
                    },
                ],
                invoiceLinks: [
                    {
                        link: '/invoices?app=addInvoice',
                        iconStyles: ['fa', 'fa-fw', 'fa-plus'],
                        linkText: 'Create Invoice',
                    },
                    {
                        link: '/invoices?app=viewInvoices',
                        iconStyles: ['fa', 'fa-fw', 'fa-money'],
                        linkText: 'View Invoices',
                    },
                ],
                inventoryLinks: [
                    {
                        link: '/production?app=searchInventory',
                        iconStyles: ['fa', 'fa-search-plus'],
                        linkText: 'Search Inventory',
                    },
                    {
                        link: '/production?app=addInventory',
                        iconStyles: ['fa', 'fa-fw', 'fa-plus'],
                        linkText: 'Create Inventory Ship Ticket',
                    },
                    {
                        link: '/production?app=viewInventory',
                        iconStyles: ['fa', 'fa-fw', 'fa-ticket'],
                        linkText: 'View Inventory Ship Tickets',
                    },
                ],
                certLinks: [
                    {
                        link: '/certifications',
                        iconStyles: ['fa', 'fa-fw', 'fa-certificate'],
                        linkText: 'Create Certification',
                    },
                ],
                purchaseLinks: [
                    {
                        link: '/purchases?app=add',
                        iconStyles: ['fa', 'fa-fw', 'fa-plus'],
                        linkText: 'Add Purchase',
                    },
                    {
                        link: '/purchases?app=purchases',
                        iconStyles: ['fa', 'fa-fw', 'fa-credit-card'],
                        linkText: 'View Purchases',
                    },
                    {
                        link: '/purchases?app=receivals',
                        iconStyles: ['fa', 'fa-fw', 'fa-paperclip'],
                        linkText: 'View Receivals',
                    },
                ],
            }
        },
        mounted(){
            Utils.openContainer();
            this.getUser();
            this.loadIn();
        },
        computed: {
            user() { return this.$store.getters.getUser; }
        },
        components: {
            Loader,
            DashboardPanel,
        },
        methods: {
            getUser() { this.$store.dispatch('commitPermission'); },
            loadIn(){
                setTimeout(()=>{
                    this.loading = false;
                }, 300)
            }
        }
    }
</script>