window.Vue = require('vue');

// Vuex Store
import { store } from './../store/store';

// Vue Components
Vue.component('dashboard', require('./../components/dashboard.vue'));
Vue.component('settings', require('./../components/settings.vue'));
Vue.component('users', require('./../components/users.vue'));
Vue.component('customers', require('./../components/customers.vue'));
Vue.component('products', require('./../components/products.vue'));
Vue.component('invoices', require('./../components/invoices.vue'));
Vue.component('routers', require('./../components/routers.vue'));
Vue.component('purchaseorders', require('./../components/purchaseorders.vue'));
Vue.component('certifications', require('./../components/certs.vue'));
Vue.component('systemlog', require('./../components/systemlog.vue'));
Vue.component('vendors', require('./../components/vendors.vue'));
Vue.component('purchases', require('./../components/purchases.vue'));

// Vue instances when element is present
if (document.getElementById('dashboard-app')) {
    const dashboardApp = new Vue({ el: '#dashboard-app', store: store });
} else if (document.getElementById('settings-app')) {
    const settingsApp = new Vue({ el: '#settings-app', store: store });
} else if (document.getElementById('users-app')) {
    const usersApp = new Vue({ el: '#users-app', store: store });
} else if (document.getElementById('customers-app')) {
    const custApp = new Vue({ el: '#customers-app', store: store });
} else if (document.getElementById('products-app')) {
    const prodApp = new Vue({ el: '#products-app', store: store });
} else if (document.getElementById('invoice-app')) {
    const invApp = new Vue({ el: '#invoice-app', store: store });
} else if (document.getElementById('routers-app')) {
    const routerApp = new Vue({ el: '#routers-app', store: store });
} else if (document.getElementById('purchaseorders-app')) {
    const poApp = new Vue({ el: '#purchaseorders-app', store: store });
} else if (document.getElementById('cert-app')) {
    const certApp = new Vue({ el: '#cert-app', store: store });
} else if (document.getElementById('systemlog-app')) {
    const systemlogApp = new Vue({ el: '#systemlog-app', store: store });
} else if (document.getElementById('vendors-app')) {
    const vendorApp = new Vue({ el: '#vendors-app', store: store });
} else if (document.getElementById('purchases-app')) {
    const purchaseApp = new Vue({ el: '#purchases-app', store: store });
}
